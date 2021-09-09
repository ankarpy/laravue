<div class="media">
    @include ('shared._vote', [
             'model' => $answer
         ])
    <div class="media-body">
        {!! $answer->body_html !!}
        <div class="row">
            <div class="col-4">
                <div class="ml-auto">
                    @can('update', $answer)
                        <a href="{{ route('questions.answers.edit', [$answer->question->id, $answer->id]) }}" class="btn btn-sm btn-outline-info">{{__('Edit')}}</a>
                    @endcan
                    @can('delete', $answer)
                        <form class="form-delete d-inline" method="post" action="{{ route('questions.answers.destroy', [$answer->question->id, $answer->id]) }}">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    @endcan
                </div>
            </div>
            <div class="col-4">
            </div>
            <div class="col-4">
                <user-info :model="{{ $answer }}" label="Answered"></user-info>
            </div>
        </div>


    </div>
</div>
<hr>
