function scrollLeftRight(options) {
    const form = document.querySelector(options.form)
    const tableHeader = form.querySelector(options.tableHeader)
    const tableBody = form.querySelector(options.tableBody)
    tableBody.addEventListener('scroll', function (event) {
        const scrollLeft = event.target.scrollLeft
        tableHeader.style.transform = `translateX(-${scrollLeft}px)`
        const thFirst = tableHeader.querySelector(options.th + ':first-child')
        const thLast = tableHeader.querySelector(options.th + ':last-child')

        thFirst.style.transform = `translateX(${scrollLeft}px)`
        thLast.style.transform = `translateX(${scrollLeft}px)`
    })
}

function buttonAction(options) {
    const settings = document.querySelectorAll(options.settings)
    const table = document.querySelector(options.table)

    function action(setting) {
        const ulElement = document.querySelector(options.tableAction)
        ulElement.style.left = setting.x + 30 + 'px'
        ulElement.style.top = setting.y + window.scrollY + 'px'
        const header = document.querySelector(options.header)
        const rect = header.getBoundingClientRect()
        const rectTable = table.getBoundingClientRect()

        if (setting.y <= rect.y || setting.y >= rectTable.y + rectTable.height) {
            ulElement.style.left = '-100%'
        }

        window.addEventListener('click', function (e) {
            if (!e.target.matches(options.tableAction) && !e.target.matches(options.settings)) {
                ulElement.style.left = '-100%'
                table.removeEventListener('scroll', action.bind(e.target, e.target))
            }
        })
    }

    settings.forEach((item) => {
        item.addEventListener('click', function (e) {
            action(e.target)
            table.addEventListener('scroll', action.bind(e.target, e.target))
        })
    })
}

function search(options, callback) {
    const itemSearch = document.querySelector(options.search)
    const input = document.querySelector(options.input)

    function handle() {
        const typeSearch = document.querySelector(options.type).innerText
        const data = {
            status: options.status,
            user_id: options.user_id
        }

        if (typeSearch.includes('name'))
            data.name = input.value
        else data.id = input.value
        axios.post(options.urlApi, data)
            .then(response => response.data)
            .then(data => {
                const display = document.querySelector(options.display)
                let htmls = ''
                if (data.length != 0) {
                    htmls = data.map(discount => {
                        return html(discount);
                    })
                    htmls = htmls.join('')
                }else {
                    htmls = noDiscountCode()
                }
                display.innerHTML = htmls
                callback(options.callback)
            })
            .catch(err => { console.log('error: ', err) })
    }
    itemSearch.addEventListener('click', function () {
        handle()
    })
    input.addEventListener('focus', function (e) {
        e.target.addEventListener('keyup', function (event) {
            if (event.keyCode == 13)
                handle()
        })
    })
}

function html(data) {
    return `<tr class="tr">
        <td class="table-cell-body table-td__th">${ data.name_discount_code + " | " + data.code}</td>
        <td class="table-cell-body table-td__th">${data.type_code}</td>
        <td class="table-cell-body table-td__th">${data.discount_amount}
        </td>
        <td class="table-cell-body table-td__th">${data.type_discount_amount}</td>
        <td class="table-cell-body table-td__th">${data.total}</td>
        <td class="table-cell-body table-td__th">0 ???</td>
        <td class="table-cell-body table-td__th">${data.status}</td>
        <td class="table-cell-body table-td__th">${data.end_date}</td>
        <td class="table-cell-body table-td__th">
            <img src="/img/settings.png" alt="" class="action-discount"
                id="action-discount">
        </td>
    </tr>`
}

function noDiscountCode() {
    return `
        <tr>
            <td class="discount-none">
                <div style="text-align: center;">
                    <img src="/img/coupon-vendor.png" alt="">
                    <p>There are no Discount Codes available</p>
                </div>
            </td>
        </tr>
    `
}

