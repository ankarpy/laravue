<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;

class QuestionsController extends Controller
{
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


        $questions = Question::with('user')->latest()->paginate(5);
        return view('questions.index', compact("questions"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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

        // Gates are used in complex actions, whereas policies are used for authorizing CRUD actions
        // But to demonstrate, here's a gate
        // Defined in \AuthServiceProvider.php
        /*if (\Gate::denies('update-question', $question)){
            abort(403, "Access denied")
        }*/

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
        $question->delete();

        return redirect('/questions')->with('success', "Your question has been deleted.");
    }
}
