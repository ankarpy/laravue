<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;

class QuestionsController extends Controller
{

    public function __construct(){
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        /**
         * Debugging example
         */

        /*
        \DB::enableQueryLog();
        $questions = Question::with('user')->latest()->paginate(5);
        //$questions = Question::latest()->paginate(5); // Nem optimalizált. A Question::with('user') egyesíti az user lekéréseket míg enélkül minden Question-höz egyesével kérdezzük le az usert

        view('questions.index', compact("questions"))->render(); // Azért kell a render, hogy lefusson az oldal, és a queryk amik rajta lefutottak visszajöjjenek

        dd(\DB::getQueryLog());
        */


        // Gates are used in complex actions, whereas policies are used for authorizing CRUD actions
        // Defined in \AuthServiceProvider.php
        /*if (\Gate::denies('access-daily-message')){
            // User can't access the daily message
        }*/


        $questions = Question::latest()->paginate(5);
        return view('questions.index', compact("questions"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize("create", Question::class);

        $question = new Question();

        return view('questions.createOrEdit', compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response | \Illuminate\Http\RedirectResponse
     */
    public function store(AskQuestionRequest $request)
    {

        $this->authorize("create", Question::class);

        try {
            $request->user()->questions()->create($request->only('title', 'body'));
        } catch(\Illuminate\Database\QueryException $e)
        {
            return redirect()->back()
                ->with('error', $e->getMessage());
        } catch ( \Exception $e ) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }


        return redirect()->route('questions.index')
            ->with('success', 'Your question has been published.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {

        $question->increment('views');

        return view('questions.show', compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {

        $this->authorize("update", $question);

        return view("questions.createOrEdit", compact('question'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AskQuestionRequest  $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        $this->authorize("update", $question);

        $question->update($request->only('title', 'body'));

        return redirect('/questions')->with('success', "Your question has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $this->authorize("delete", $question);

        $question->delete();

        return redirect('/questions')->with('success', "Your question has been deleted.");
    }

    /**
     * Vote the question
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function voteQuestion(Question $question) {
        $this->authorize('vote', $question);

        $user = request()->user();

        $vote = (int) request()->vote;
        $user->voteQuestion($question, $vote);

        return back();
    }
}
