@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">{{ __('Questions') }}</div>

                <div class="card-body">
                    @foreach($questions as $question)
                    <div class="media question">
                        <div class="d-flex flex-column counters">
                            <div class="vote">
                                <strong>{{ $question->votes }}</strong> {{ \Str::plural(__('vote'), $question->votes) }}
                            </div>
                            <div class="status {{ $question->status }}">
                                <strong>{{ $question->answers }}</strong> {{ \Str::plural(__('answer'), $question->votes) }}
                            </div>
                            <div class="views">
                                {{ $question->views . " " .  Str::plural(__('view'), $question->votes)}}
                            </div>
                        </div>
                        <div class="media-body">
                            <h3 class="mt-0"><a href="{{ $question->url }}">{{ $question->title }}</a></h3>

                            <p class="lead">
                                {{ __('Asked by') }}
                                <a href="{{ $question->user->url }}">{{ $question->user->name }}</a>
                                <small class="text-muted">
                                    {{ $question->created_date }}
                                </small>
                            </p>
                            {{ \Str::limit($question->body, 250) }}
                        </div>

                    </div>
                    @endforeach

                    <div class="text-center mt-5">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
