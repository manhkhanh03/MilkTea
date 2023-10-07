function onOff(options) {
    const selector = document.querySelectorAll(options.selector)
    const selectorList = document.querySelector(options.selectorList)

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
                selectorList.style.opacity = '0.4'
            }
            else {
                event.target.classList.add(options.classList)
                isActive = true;
                selectorList.style.opacity = '1'
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
    let isClick = false

    if (btnAdd) {
        const newElement = document.createElement('li')
        newElement.classList.add('message-demo')
        newElement.innerHTML = options.html

        btnAdd.addEventListener('click', function (event) {
            if (!isClick) {
                parent.appendChild(newElement)
                callSaveMessage(newElement, options)
                isClick = true
            }
        })
    }
    
    if (options.element) {
        callSaveMessage(options.element, options)
        isClick = true
    }

    function callSaveMessage(Element, options) {
        const btnSave = Element.querySelector(options.btnSave)
        btnSave.addEventListener('click', function (event) {
            isClick = false;
            saveMessage(Element, options.chatbotId, options.urlApi)
        })
    }
}

function saveMessage(element, chatbotId, urlApi) {
    const content = element.querySelector('input')

    if (content.value.length > 0 && content.value.trim().length > 0) {
        const data = {
            chatbot_id: chatbotId,
            content: content.value.trim(),
            type: 'quick_message',
        }

        const eventAfterAxios = function (element, data) {
            element.setAttribute('data-id-chatbot-message', data.id)
            let paragraphElement = document.createElement("p");
            paragraphElement.textContent = content.value.trim();
            paragraphElement.className = content.className
            content.parentNode.insertBefore(paragraphElement, content);
            content.parentNode.removeChild(content);

            // thay doi icon save sang edit
            const saveIcon = element.querySelector('.icon-save')
            saveIcon.style.backgroundImage = 'url(/img/pen.png)'
        }

        if (element.getAttribute('data-id-chatbot-message')) {
            axios.put(urlApi + element.getAttribute('data-id-chatbot-message'), data)
                .then(response => response.data)
                .then(data => {
                    eventAfterAxios(element, data)
                })
                .catch(err => {
                    console.error('error: ', err)
                })
        } else {
            axios.post(urlApi, data)
                .then(response => response.data)
                .then(data => {
                    eventAfterAxios(element, data)
                })
                .catch(err => {
                    console.error('error: ', err)
                })
        }

    }
}

function deleteMessage(options) {
    const btnDelete = document.querySelectorAll(options.btnDelete)

    btnDelete.forEach(function (item) {
        item.addEventListener('click', function (event) {
            const messageChatbotId = event.target.parentNode.parentNode.getAttribute(options.attribute)
            axios.delete(options.urlApi + messageChatbotId)
                .then(() => {
                    alert('Deleted Successfully')
                    event.target.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode)
                })
                .catch(() => {
                    alert('Failed Deletion')
                })
        })
    })
}

function editMessage(options) {
    const btnEdit = document.querySelectorAll(options.btnEdit)
    console.log(btnEdit)
    btnEdit.forEach(function (item) {
        item.addEventListener('click', function (event) {
            const parent = event.target.parentNode.parentNode
            const messageChatbotId = parent.getAttribute(options.attribute)
            const content = parent.querySelector(options.classContent)
            const iconEdit = parent.querySelector(options.iconEdit)

            // tao the input de sua
            let inputElement = document.createElement("input");
            inputElement.value = content.innerText.trim();
            inputElement.className = content.className
            inputElement.id = 'input-content'
            content.parentNode.insertBefore(inputElement, content);
            content.parentNode.removeChild(content);

            // thay nut sua thanh save
            let saveElement = document.createElement("p");
            saveElement.classList.add('icon', 'icon-save')
            saveElement.style.backgroundImage = 'url(/img/bookmark.png)'
            iconEdit.parentNode.insertBefore(saveElement, iconEdit);
            iconEdit.parentNode.removeChild(iconEdit);

            options.element = parent
            addMessage(options)
            // axios.delete(options.urlApi + messageChatbotId, data)
            //     .then(() => {
            //         alert('Deleted Successfully')
            //         event.target.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode)
            //     })
            //     .catch(() => {
            //         alert('Failed Deletion')
            //     })
        })
    })
}