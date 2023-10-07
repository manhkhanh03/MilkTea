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
                <div class="quick {{ $type_child == 'quick_message' ? 'active' : '' }}">
                    <a href="{{ $url_web }}/vendor/customer/service/chatbot?type=quick_message">Quick messages</a>
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
                                        <button class="edit-message">
                                            <i class="fa-regular fa-pen-to-square"></i> <span>Edit</span>
                                        </button>
                                    </div>

                                    <div class="button-on-off {{ $chatbot['auto_chat'] == 1 ? 'active' : '' }}"
                                        id="on-off"></div>
                                </div>
                            </div>
                            <p class="message-demo" id="message-demo">{{ $chatbot['content'] }}</p>
                        </div>
                    </div>
                </div>
            @elseif($type_child == 'quick_message')
                <div class="form-message">
                    <div class="message-icon">
                        <img src="/img/message.png" alt="">
                    </div>

                    <div class="message-main">
                        <div class="group-title">
                            <div class="message-title">My quick message</div>
                            <div class="message-subtitle">Quick messages allow you to create and use message templates that
                                you frequently send to customers.</div>
                        </div>
                        <div class="message-settings">
                            <div class="message-setting-info">
                                <p class="text">Group Chat</p>
                                <div class="button-button">
                                    <div class="button-edit-message">
                                        <button class="add-new-message" id="btn-add-message">
                                            <i class="fa-solid fa-plus"></i> <span>Add</span>
                                        </button>
                                    </div>

                                    <div class="button-on-off {{ $chatbot[0]['quick_message'] == 1 ? 'active' : '' }}"
                                        id="on-off"></div>
                                </div>
                            </div>
                            <ul class="group-quick-message">
                                @if (!empty($chatbot))
                                    @foreach ($chatbot as $key => $item)
                                        <li class="message-demo"
                                            data-id-chatbot-message="{{ $item['message_chatbot_id'] }}">
                                            <p class="content">{{ $item['content'] }}</p>
                                            <div class="box-btn">
                                                <p class="icon icon-pen" style="background-image: url(/img/pen.png)"></p>
                                                <p class="icon icon-delete" style="background-image: url(/img/bin.png)"></p>
                                            </div>
                                        </li>
                                    @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endpush

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
        integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="/js/chatbot.js"></script>
    <script>
        onOff({
            selector: '#on-off',
            classList: 'active',
            chatbotId: @json(isset($chatbot[0]['chatbot_id']) ? $chatbot[0]['chatbot_id'] : $chatbot['chatbot_id']),
            type: @json($type_message),
            selectorList: '.group-quick-message'
        })

        addMessage({
            parent: '.group-quick-message',
            btnAdd: '#btn-add-message',
            btnEdit: '#btn-edit-message',
            btnSave: '.icon-save',
            html: `
                <input type="text" class="content" id="input-content" placeholder="Please enter a quick message...">
                <div class="box-btn">
                    <p class="icon icon-save" id="btn-save" style="background-image: url(/img/bookmark.png)"></p>
                    <p class="icon icon-delete" id="btn-delete" style="background-image: url(/img/bin.png)"></p>
                </div>
            `,
            chatbotId: @json($chatbotId),
            urlApi: '/api/vendor/chatbot/message/chatbot',
        })

        deleteMessage({
            btnDelete: '.message-demo .icon-delete',
            attribute: 'data-id-chatbot-message',
            urlApi: '/api/vendor/chatbot/message/chatbot/'
        })

        editMessage({
            btnEdit: '.message-demo .icon-pen',
            btnSave: '.message-demo .icon-save',
            attribute: 'data-id-chatbot-message',
            urlApi: '/api/vendor/chatbot/message/chatbot/',
            classContent: '.content',
            chatbotId: @json($chatbotId),
            iconEdit: '.icon-pen'
        })
    </script>
@endpush
