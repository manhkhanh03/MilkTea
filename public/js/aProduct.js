function handleEventQuantityProduct(options) {
    function handleNext(selector, input, isActive) {
        selector.onclick = function () {
            let value = input.value;
            if (isActive && value <= 70) {
                input.value = Number(value) + 1
            } else if (!isActive && value > 0) { 
                input.value = Number(value) - 1
            }
        }
    }

    const input = document.querySelector(options.input)
    const next = document.querySelector(options.selector.next)
    const prev = document.querySelector(options.selector.prev)

    handleNext(next, input, true)
    handleNext(prev, input, false)
}

function handleEventSelectedProduct(options) { 
    const parentElement = document.querySelector(options.selector)
    const listItem = parentElement.querySelectorAll(options.item)
    
    listItem.forEach((item) => {
        item.onclick = function () {
            const thisElement = parentElement.querySelector(options.input)
            thisElement.innerText = item.innerText
            thisElement.setAttribute('data-active', item.getAttribute(options.attribute))
        }
    });
}

function handleEventChangeImageMain(options) { 
    const parentElement = document.querySelector(options.parent)
    const imgMain = parentElement.querySelector(options.imageMain)
    const listImg = parentElement.querySelectorAll(options.imageList)
    
    listImg.forEach(item => { 
        item.onclick = function () { 
            imgMain.style.backgroundImage = this.style.backgroundImage
            imgMain.style.transition = 'all .25s ease'
        }
    })
}


function handleAddToCart(options) {
    const btn = document.querySelector(options.btn);
    btn.onclick = function () {
        const flavor = document.querySelector(options.flavor).getAttribute(options.attribute)
        const size = document.querySelector(options.size).getAttribute(options.attribute);
        const quantity = document.querySelector(options.quantity).value;
        const isDiscount = document.querySelector(options.discount)
        let discount 
        if (isDiscount) {
            discount = isDiscount.getAttribute(options.attributeDiscount);
        }
        const product = btn.getAttribute(options.attributeProduct)
        let data = {
            flavor: flavor,
            size: size,
            quantity: quantity,
            product: product,
            discount: discount,
        }
        if (flavor && quantity && size) {
            options.handle(data, options)
        } else {
            switch (null) {
                case flavor:
                    confirm('Please select a flavor')
                    break;
                case quantity:
                    confirm('Please select a quantity')
                    break;
                case size:
                    confirm('Please select a size')
                    break;
            }
        }
    }
}

function handleDiscount(options) {
    let discountElement = document.querySelectorAll(options.selector)
    discountElement.forEach(function(ele) {
        ele.addEventListener('click', function (event) {
            discountElement.forEach(function (element) {
                element.classList.remove('active');
            });
            event.target.classList.add('active');
        });
    })
}

