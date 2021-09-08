@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @can('access-daily-message')
            <div class="card mb-4">
                <div class="card-header">
                    Daily message
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Have a nice day today!</li>
                </ul>
            </div>
            @endcan
            <div class="card">
                <div class="card-header">

                    <div class="d-flex align-items-center">
                        <h2 class="mb-0">{{ __('Questions') }}</h2>
                        <div class="ml-auto">
                            <a href="{{ route('questions.create') }}" class="btn btn-outline-secondary">{{ __('Ask Question') }}</a>
                        </div>
                    </div>


                </div>

                <div class="card-body">
                    @include('layouts/_messages')

                    @forelse($questions as $question)
                    <div class="media question">
                        <div class="d-flex flex-column counters">
                            <div class="vote">
                                <strong>{{ $question->vote_count }}</strong> {{ \Str::plural(__('vote'), $question->vote_count) }}
                            </div>
                            <div class="status {{ $question->status }}">
                                <strong>{{ $question->answer_count }}</strong> {{ \Str::plural(__('answer'), $question->answer_count) }}
                            </div>
                            <div class="views">
                                {{ $question->views . " " .  Str::plural(__('view'), $question->views)}}
                            </div>
                        </div>
                        <div class="media-body">
                            <div class="d-flex align-items-center">
                                <h3 class="mt-0 mb-0"><a href="{{ $question->url }}">{{ $question->title }}</a></h3>
                                <div class="ml-auto">
                                    @can('update', $question)
                                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-sm btn-outline-info">{{__('Edit')}}</a>
                                    @endcan
                                    @can('delete', $question)
                                    <form class="form-delete d-inline" method="post" action="{{ route('questions.destroy', $question->id) }}">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                    @endcan
                                </div>
                            </div>

                            <p class="lead">
                                {{ __('Asked by') }}
                                <a href="{{ $question->user->url }}">{{ $question->user->name }}</a>
                                <small class="text-muted">
                                    {{ $question->created_date }}
                                </small>
                            </p>
                            <div class="excerpt">{{ $question->excerpt(350) }}</div>
                        </div>

                    </div>
                    @empty
                        <div class="alert alert-warning">
                            <strong>Sorry</strong> There are no questions available.
                        </div>
                    @endforelse

                    <div class="text-center mt-5">
                        {{ $questions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
