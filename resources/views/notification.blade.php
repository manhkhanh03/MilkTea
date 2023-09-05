@extends('frame_user')

@php
    $user = Auth::user();
@endphp

@push('body-products')
    <div class="info-profile">
        <div class="__header ">
            <h3
                style="font-size: 20px; height: 50px; display: flex;
                                                                                align-items: center;">
                Notifications</h3>
        </div>
        <div class="noti">
            <ul class="noti__list-noti">The order hasn't been updated yet</ul>
        </div>
    </div>
@endpush


@push('js_frame')
    <script>
    
    </script>
@endpush
