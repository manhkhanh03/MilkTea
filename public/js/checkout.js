// local variables
let total;
const dataOrder = {
    product_size_flavor_id: [],
    quantity: [],
    total: [],
    cart_id: [],
}

function handleCheckoutMethod(options) {
    const inputElements = document.querySelectorAll(options.checkbox);
    inputElements.forEach(function (item) {
        item.onclick = function (event) {
            if (event.target.checked) {
                inputElements.forEach(function (otherInput) {
                    if (otherInput !== event.target) {
                        otherInput.checked = false
                    }
                })
            } else {
                event.target.checked = true
            }
        }
    })
}

function handleTotalCart(options) {
    // function addInfoUser(options) {
    //     const formElement = document.querySelector(options.form)
    //     const nameElement = formElement.querySelector(options.username)
    //     const phoneElement = formElement.querySelector(options.phone)
    //     const shippingElement = formElement.querySelector(options.shipping)

    //     if (user) {
    //         nameElement.value = user.userName;
    //         phoneElement.value = user.phone
    //         shippingElement.value = user.address
    //     }
    // }

    // addInfoUser(options)
    options.handle(options, options)
}

function handleOrder(options) {
    const btnElement = document.querySelector(options.btn)

    btnElement.addEventListener('click', function (event) {
        let arrValue = []
        for(let i = 0; i < options.rules.length; i++) {
            const ruleElement = document.querySelectorAll(options.rules[i].selector)
            let errorMessage = options.rules[i].test(Array.from(ruleElement), options.delivery)
            if (typeof errorMessage === 'string') {
                alert(errorMessage)
                break;
            } else if (Array.isArray(errorMessage)) { 
                arrValue.push(errorMessage)
            }
        }
        if (arrValue.length !== 0) {
            options.handle(options, arrValue)
        }
    })
}

handleOrder.isPaymentMethod = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            return value.filter(item => item.checked).length > 0
                ? value.filter(item => item.checked)
                 : message;
        }
    }
}

handleOrder.isInfo = function (selector, message) { 
    return {
        selector: selector,
        test: function (value, delivery) { 
            return value.filter((item) => item.innerText !== '').length === 0
                ? [
                    document.querySelector(delivery).value,
                ] : message;
        }
    }
}