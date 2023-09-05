@extends('vendor_frame')

@push('styles')
    <link rel="stylesheet" href="/css/chatbot.css">
@endpush

@php
    $user = Auth::user();
@endphp

@push('body-vendor')
    <div class="page-container">
        <div class="chatbot_box">
            <h1 class="heading-chatbot">Chatbot</h1>
            <h4 class="subheading">Utilize various tools within the chat assistant to enhance the efficiency of your customer
                support service.</h4>

            <div class="message-function">
                <div class="auto {{ $type_child == 'auto_chat' ? 'active' : '' }}">
                    <a href="{{ $url_web }}/vendor/customer/service/chatbot?type=auto_chat">Automated messages</a>
                </div>
                <div class="quick {{ $type_child == 'quick_chat' ? 'active' : '' }}">
                    <a href="{{ $url_web }}/vendor/customer/service/chatbot?type=quick_chat">Quick messages</a>
                </div>
            </div>

            @if ($type_child == 'auto_chat')
                <div class="noti-chatbot">
                    <ol class="noti-noti">
                        <li class="noti-item">The default automatic response will only be activated once every 24 hours for
                            each
                            buyer.</li>
                    </ol>
                </div>

                <div class="form-message">
                    <div class="message-icon">
                        <img src="/img/auto-reply.png" alt="">
                    </div>

                    <div class="message-main">
                        <div class="message-title">Standard automated messages</div>
                        <div class="message-subtitle">After activation, the messages will be automatically sent to the buyer
                            when they start a chat with you.</div>
                        <div class="message-settings">
                            <div class="message-setting-info">
                                <p class="text">Shop setup</p>
                                <div class="button-button">
                                    <div class="button-edit-message" id="edit">
                                        <i class="fa-regular fa-pen-to-square"></i> <span>Edit</span>
                                    </div>

                                    <div class="button-on-off" id="on-off"></div>
                                </div>
                            </div>
                            <p class="message-demo" id="message-demo">Hey yo what up bro</p>
                        </div>
                    </div>
                </div>
            @elseif($type_child == 'quick_chat')
                
            @endif

        </div>
    </div>
@endpush

@push('js')
    <script src="/js/chatbot.js"></script>
@endpush
