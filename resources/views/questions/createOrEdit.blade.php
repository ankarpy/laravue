@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center">
                            <h2 class="mb-0">{{ __('Ask Question') }}</h2>
                            <div class="ml-auto">
                                <a href="{{ route('questions.index') }}" class="btn btn-outline-secondary">{{ __('Back') }}</a>
                            </div>
                        </div>

                    </div>

                    <div class="card-body">
                        @if(!isset($question->id))
                        <form action="{{ route('questions.store') }}" method="post">
                            @include ("questions._form", [])
                        </form>
                        @else
                        <form action="{{ route('questions.update', $question->id) }}" method="post">
                            {{ method_field('PUT') }}
                            @include ("questions._form", [])
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
