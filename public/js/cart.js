let dataCart = []

function handleCheckout(options, data) {
    const checkboxElement = document.querySelectorAll(options.input)

    function totalCart(options, isChecked, checkboxElement, index) {
        const parentElement = checkboxElement.parentElement.parentElement
        const quantity = parentElement.querySelector(options.quantity)
        const totalCart = document.querySelector(options.total)
        const subtotal = document.querySelector(options.subtotal)
        const delivery = document.querySelector(options.delivery)
        const discount = document.querySelector(options.discount)
        let sub = data[index].price
        let shipping = 0.50
        let discount_code = 0
        let discount_web = 0
        let totalDiscount = parseFloat(discount.innerText)

        data[index].web_discount_code.forEach(function (value) {
            discount_web += handleData(value, discount_code, sub);
        })
        data[index].discount_code.forEach(function (value) {
            discount_code += handleData(value, discount_code, sub);
        })

        // handle data
        let price = data[index].price
        function handleData(value, discount_code, sub) {
            if (value.type_discount_amount == '%') {
                discount_code = sub * (parseFloat(value.discount_amount) / 100)
            } else {
                discount_code = parseFloat(value.discount_amount)
            }
            return discount_code;
        }
        if (isChecked) {
            sub = parseFloat(subtotal.innerText) + (parseFloat(price) * parseFloat(quantity.value))
            discount_code = totalDiscount + discount_code + discount_web;
        } else {
            sub = parseFloat(subtotal.innerText) - (parseFloat(price) * parseFloat(quantity.value))
            discount_code = totalDiscount - discount_code - discount_web;
        }

        subtotal.innerText = sub.toFixed(2);
        delivery.innerText = shipping.toFixed(2);
        discount.innerText = discount_code.toFixed(2);
        totalCart.innerText = (sub + shipping - discount_code).toFixed(2);
    }

    checkboxElement.forEach(function (element, index) {
        element.onclick = function () {
            if (this.checked)
                totalCart(options, true, this, index)
            else totalCart(options, false, this, index)
        }
    })
}

function handleDeleteProductCart(options) {
    const btnDelete = document.querySelectorAll(options.btnDelete);
    btnDelete.forEach(function (item) {
        item.addEventListener('click', function (event) {
            options.urlApi = options.urlApi.split("/").slice(0, 3).join("/") + '/' + event.target.getAttribute(options.attribute)
            options.handleData(options)
        })
    })
}

function handleCheckoutCart(options) { 
    const quantityElements = document.querySelectorAll(options.quantity)
    const checkboxElements = document.querySelectorAll(options.checkbox)
    const btnCheckout = document.querySelector(options.btn);
    const data = {
        product_size_flavor_id: [],
        product_id: [],
        price: [],
        quantity: [],
        total: [],
        cart_id: [],
    }
    btnCheckout.addEventListener('click', function (event) { 
        checkboxElements.forEach(function (item, key) { 
            if (item.checked) {
                let quantity = quantityElements[key].value
                let price = dataCart[key].price

                data.product_size_flavor_id.push(dataCart[key].product_size_flavor_id)
                data.price.push(parseFloat(price))
                data.quantity.push(parseFloat(quantity))
                data.total.push(parseFloat(quantity * price))
                data.product_id.push(dataCart[key].id)
                data.cart_id.push(quantityElements[key].getAttribute(options.attribute))
            }
        })
        if (data.product_size_flavor_id.length !== 0) {
            options.handle(data, options)
        } else {
            alert('Please choose a product')
        }
    })
}