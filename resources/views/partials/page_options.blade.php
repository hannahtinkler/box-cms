@if($page->created_by == $user->id || $user->curator)
    <div class="m-t-sm btn-group pull-right page-options">
        <a class="btn right btn-default" href="/pages/edit/{{ $page->id }}"><i class="fa fa-pencil"></i> Edit</a>

        @if ($user->curator) {
            <form action="/pages/{{ $page->id }}" method="POST">
                {!! csrf_field() !!}
                {!! method_field('DELETE') !!}
                <button type="submit" id="delete-page-{{ $page->id }}" class="btn btn-default">
                    <i class="fa fa-trash-o"></i> Delete
                </button>
            </form>
        @endif
    </div>
@else
    <div class="m-t-sm btn-group pull-right">
        <a class="btn right btn-default" href="/pages/edit/{{ $page->id }}"><i class="fa fa-pencil"></i> Suggest an Edit</a>
    </div>
@endif
