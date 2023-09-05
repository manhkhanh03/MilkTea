function onOff(options) {
    const selector = document.querySelectorAll(options.selector)

    selector.forEach(function (item) {
        item.addEventListener('click', function (event) {
            console.log(event.target)
            event.target.classList.toggle(options.classList)
        })
    })
} 

onOff({
    selector: '#on-off',
    classList: 'active',
})