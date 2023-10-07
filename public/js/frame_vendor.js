function handleAction(options) {
    const action = document.querySelectorAll(options.action)
    const selector = document.querySelectorAll(options.selector)
    const iconAction = document.querySelectorAll(options.iconAction)

    action.forEach((__this, index) => {
        __this.onclick = (event) => {

            if (iconAction[index].classList.contains(options.classDown)) {
                iconAction[index].classList.remove(options.classDown)
                iconAction[index].classList.add(options.classUp)
                selector[index].style.display = 'block'
            } else {
                iconAction[index].classList.remove(options.classUp)
                iconAction[index].classList.add(options.classDown)
                selector[index].style.display = 'none'
            }
        }
    });
}

function handleEventChangeTypeSearch(options) {
    const listTypeSearch = document.querySelectorAll(options.selectorSearch);
    const displayText = document.querySelector(options.display)
    const placeholder = document.querySelector(options.placeholder)

    listTypeSearch.forEach((__this, index) => {
        __this.onclick = (event) => {
            const parentTypeSearch = __this.parentNode
            parentTypeSearch.querySelector('.active').classList.remove('active')
            __this.classList.add('active')
            displayText.innerHTML = event.target.innerHTML
            if (placeholder)
                placeholder.placeholder = event.target.innerHTML
        }
    });
}

function handleSearch(options) {
    const date = document.querySelector(options.date);
    let startDate
    let endDate
    let startDateString
    let endDateString
    if (date) {
        const dateRange = date.value
        const dateParts = dateRange.split(" - ");

        startDateString = dateParts[0];
        endDateString = dateParts[1];

        startDate = moment(startDateString, "MM/DD/YYYY").format("YYYY/MM/DD");
        endDate = moment(endDateString, "MM/DD/YYYY").format("YYYY/MM/DD");
    }

    let type
    let data_search
    let btnSearch = document.querySelector(options.btnSearch)
    let btnReset = document.querySelector(options.btnReset)

    btnSearch.addEventListener('click', function () {
        startDate = moment(startDateString, "MM/DD/YYYY").format("YYYY/MM/DD");
        endDate = moment(endDateString, "MM/DD/YYYY").format("YYYY/MM/DD");
        type = document.querySelector(options.type_search)
        data_search = document.querySelector(options.data_search)

        type_search = type.innerText.toLowerCase()
        updateInfoByDate({
            parentList: '.list-products',
            startDate: startDate,
            endDate: endDate,
            urlApi: options.urlApi,
            type_search: type_search,
            data_search: data_search.value,
        })
    })

    btnReset.addEventListener('click', function () {
        startDate = moment(startDateString, "MM/DD/YYYY").format("YYYY/MM/DD");
        endDate = moment(endDateString, "MM/DD/YYYY").format("YYYY/MM/DD");
        updateInfoByDate({
            parentList: '.list-products',
            startDate: startDate,
            endDate: endDate,
            urlApi: options.urlApi
        })
        type.innerText = 'All'
        data_search.value = ''
    })
}

function handleClickButton(options) {
    const btnDelete = document.querySelectorAll(options.btnDelete)
    const btnEdit = document.querySelectorAll(options.btnEdit)
    let data = {}
    btnDelete.forEach(function (item, key) {
        item.addEventListener('click', function (event) {
            let isResult = confirm('Are you sure you want to delete this product?')
            if (isResult) {
                data.status = 'deleted'
                const urlApi = options.urlApi + event.target.getAttribute(options.attribute_product)
                console.log(urlApi)
                axios.put(urlApi, data)
                    .then(() => {
                        window.location.reload()
                    })
                    .catch(error => {
                        console.error('error: ', error)
                    })
            }
        })

        // btnEdit[key].addEventListener('click', function (event) {
        //     console.log(event.target)
        // })
    })
}