function onOff(options) {
    const selector = document.querySelectorAll(options.selector)

    selector.forEach(function (item) {
        let isActive = false;
        isActive = item.classList.contains('active') ? true : false;
        const data = {
            urlApi: `/api/vendor/chatbot/${options.chatbotId}`,
        }
        item.addEventListener('click', function (event) {
            if (isActive) {
                event.target.classList.remove(options.classList)
                isActive = false
            }
            else {
                event.target.classList.add(options.classList)
                isActive = true;
            }

            if (options.type == 'auto_chat') {
                data.data = {
                    auto_chat: isActive,
                }
            } else {
                data.data = {
                    quick_message: isActive,
                }
            }

            setChat(data)
        })
    })
}

function setChat(options) {
    console.log(options)
    const data = options.data

    const method = options.method

    axios.put(options.urlApi, data)
        .then(response => {
            setTimeout(() => {
                alert('Update successfully')
            }, 1000);
        })
}

function addMessage(options) {
    const parent = document.querySelector(options.parent)
    const btnAdd = document.querySelector(options.btnAdd)

    
    if (btnAdd) {
        const newElement = document.createElement('li')
        newElement.classList.add('message-demo')
        newElement.innerHTML = options.html

        btnAdd.addEventListener('click', function (event) {
            parent.appendChild(newElement)
            callSaveMessage(newElement, options)
        })
    }

    function callSaveMessage(newElement, options) {
        const btnSave = document.querySelector(options.btnSave)
        btnSave.addEventListener('click', function (event) {
            saveMessage(newElement)
        })
    }
}

function saveMessage(newElement) {
    // const contentElement = document.querySelector(options.contentElement)
    console.log(newElement)
    // const data = {
    //     chatbot_id: ,
    //     content: ,
    //     type: ,
    // }
}

// sửa lại phần save đang bị đè nhiều sự kiện 1 lúc 