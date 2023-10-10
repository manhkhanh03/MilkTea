function handleInput(options) {
    const inputs = document.querySelectorAll(options.form + " " + options.inputs)
    const labels = document.querySelectorAll(options.form + " " + options.labels)

    inputs.forEach((input, index) => {
        input.addEventListener("focus", () => {
            for (let key in options.css) {
                labels[index].style[key] = options.css[key]
            }
            input.setAttribute('placeholder', '')
        })
    })
}

function handleImport(options) {
    const formElement = document.querySelector(options.form)
    let selectorRules = {}

    function validate(rule, ruleElement) {
        const formMessage = ruleElement.parentElement.querySelector(options.formMessage)
        let errorMessage

        let rules = selectorRules[rule.selector]
        for (let i in rules) {
            errorMessage = rules[i](ruleElement.value)
            if (errorMessage) break
        }
        
        if (errorMessage) {
            formMessage.innerText = errorMessage
        } else formMessage.innerText = ''

        return !errorMessage
    }

    // btn mặc định của form
    formElement.onsubmit = (e) => {
        e.preventDefault();
        
        let isCheckInput = true;

        const inputElement = formElement.querySelectorAll('input:not([disabled])' + options.formInput)
        options.rules.forEach((rule, index) => { 
            const ruleElement = formElement.querySelector(rule.selector)
            let isValidate = validate(rule, ruleElement)
            if (!isValidate) {
                isCheckInput = false;
            }
        })

        if (isCheckInput) {
            const data = {}
            Array.from(inputElement).forEach(ele => {
                data[ele.getAttribute('id')] = ele.value
            })

            options.isSuccess(data)
        }
    }

    // btn ở bên ngoài
    const otherBtn = document.querySelector(options.btnOther)
    if (otherBtn) { 
        otherBtn.addEventListener('click', (e) => {
            e.preventDefault();

            let isCheckInput = true;

            const inputElement = formElement.querySelectorAll('input:not([disabled])' + options.formInput)
            options.rules.forEach((rule, index) => {
                const ruleElement = formElement.querySelectorAll(rule.selector)
                ruleElement.forEach((ruleE) => {
                    let isValidate = validate(rule, ruleE)
                    if (!isValidate) {
                        isCheckInput = false;
                    }
                })
            })

            if (isCheckInput) {
                const data = {}
                Array.from(inputElement).forEach(ele => {
                    data[ele.getAttribute('id')] = ele.value
                })

                options.isSuccess(data)
            }
        })
    }

    options.rules.forEach((rule, index) => {
        // const ruleElement = formElement.querySelector(rule.selector) 
        const ruleElement = formElement.querySelectorAll(rule.selector) 
        
        if (Array.isArray(selectorRules[rule.selector])) { 
            selectorRules[rule.selector].push(rule.test)
        } else 
            selectorRules[rule.selector] = [rule.test]
        ruleElement.forEach(function (ruleE) {
            ruleE.addEventListener('input', () => { 
                validate(rule, ruleE)
            })
    
            ruleE.addEventListener('blur', () => { 
                validate(rule, ruleE)
            })
        })
    })
}

handleImport.isFocus = function (selector, message, other) {
    return {
        selector: selector,
        test: function (value) { 
            return value.trim() ? undefined : message
        }
    };
}

handleImport.isPassword = function (selector, message) { 
    return {
        selector: selector,
        test: function (value) { 
            return value.length > 8 ? undefined : message
        }
    }
}

handleImport.isEmail = function (selector, message) { 
    return {
        selector: selector,
        test: function (value) { 
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(value) ? undefined : message
        }
    }
}

handleImport.isConfirmPassword = function (selector, message, password) { 
    return {
        selector: selector,
        test: function (value) { 
            const valuePassword = document.querySelector(password).value
            return valuePassword === value ? undefined : message
        }
    }
}

handleImport.isMaximumBuyer = function (selector, message, message2, total) {
    return {
        selector: selector,
        test: function (value) {
            const totalSelector = document.querySelector(total).value
            return totalSelector >= value ? ( value == 0 || value > 200000  ? message2 : undefined) : message 
        }
    }
}

handleImport.isTypeDiscount = function (selector, messageType$, messageTypePercent, type, arr) {
    return {
        selector: selector,
        test: function(value) {
            const typeElement = document.querySelector(type).innerText
            if (typeElement === '$') {
                return value >= arr[0] && value <= arr[1] ? undefined : messageType$;
            } 
            return value >= arr[2] && value <= arr[3] ? undefined : messageTypePercent;
        }
    }
}