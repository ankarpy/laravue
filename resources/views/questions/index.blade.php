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
                        @include ('questions._excerpt')
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
