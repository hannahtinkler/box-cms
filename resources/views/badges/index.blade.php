@extends('layouts.master')

@section('content')

    <h1>Your Badges</h1>

    <hr>

    @if(session('message'))
        <p class="bg-success error-message m-b-xl">{!! session('message') !!}</p>
    @endif

    <div class="wrapper wrapper-content animated blog">
    <div class="row badge-container">
        <div class="col-lg-12">
            @set('x', 0)
            @foreach($badgeGroups as $badgeGroup)
                @foreach($badgeGroup->badges as $badge)
                    @if(is_int($x / 8))
                        <div class="row">
                    @endif
                        <div class="col-lg-2 badge-cell">
                            <a {!! !in_array($badge->id, $userBadges) ? 'class="not-earned"' : null !!} data-toggle="modal" href="/ajax/modal/badges/{{ $badge->id }}" data-target="#more-info">
                                <div class="badge-image left">
                                    <img class="m-b-sm" src="{{ $badge->image }}" />
                                    <p>
                                        <strong>{{ $badgeGroup->name }}</strong><br />
                                        {{ $badge->name }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    @if(is_int(($x + 1) / 8))
                        </div>
                    @endif
                    @set('x', $x + 1)
                @endforeach
            @endforeach
        </div>
    </div>
    </div>

    <div class="modal fade" id="more-info" tabindex="-1" role="dialog" aria-labelledby="more-info" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            </div>
        </div>
    </div>
@stop

@section('scripts')
<script>
    $(document).ready(function() {
        $('body').on('hidden.bs.modal', '.modal', function () {
            $(this).removeData('bs.modal');
            $('.modal .modal-body').text("Loading...");
        });
     });
</script>
 @stop