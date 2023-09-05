function getStartAndEndDateFromString(data) {
    const daterangepicker = document.querySelector('#reportrange span')
    var parts = daterangepicker.textContent.split(' - ');
    var startDateString = parts[0];
    var endDateString = parts[1];

    data.startDate = moment(startDateString, 'MMMM D, YYYY');
    data.endDate = moment(endDateString, 'MMMM D, YYYY');
}

function addEventClick(options) {
    const elementClick = document.querySelector(options.elementClick)
    const elementSelector = document.querySelector(options.elementSelector)
    const data = {
        startDate: '',
        endDate: ''
    }
    getStartAndEndDateFromString(data)
    console.log(data)

    function childClickHandler(event, e) {
        event.target.innerText = e.target.innerText;
        elementSelector.classList.remove(options.classList);
        cb(data.startDate, data.endDate);
    }

    function addEventToElement(element, event) {
        element.addEventListener('click', function (e) {
            childClickHandler(event, e);
        });
    }

    elementClick.addEventListener('click', function (event) {
        elementSelector.classList.toggle(options.classList);
        const elementSelectorChild = elementSelector.children;

        Array.from(elementSelectorChild).forEach(function (ele, index) {
            addEventToElement(ele, event);
        });
    });

    window.addEventListener('click', function (event) {
        if (!event.target.matches(options.elementClick)) {
            elementSelector.classList.remove(options.classList);
        }
    })
}

function eventClickAddDataChart(options) {
    const formElement = document.querySelector(options.form)
    const elements = formElement.querySelectorAll(options.element)

    const isItemActive = {}
    for (let key in options.isClass) {
        isItemActive[key] = false
    }

    elements.forEach(function (__this) {
        let isActive = false;
        if (__this.classList.contains('active')) {
            isActive = true
            for (let key in options.isClass) {
                if (__this.classList.contains(options.isClass[key].item))
                    isItemActive[key] = true;
            }
        }

        if (options.isSelect) {
            addDataCanvas(isItemActive, options.isClass, options.data)
        }

        __this.addEventListener('click', (event) => {
            if (isActive) {
                __this.classList.remove(options.classList)
                isActive = false;
                for (let key in options.isClass) {
                    if (__this.classList.contains(options.isClass[key].item))
                        isItemActive[key] = false;
                }
                addDataCanvas(isItemActive, options.isClass, options.data)
            } else {
                __this.classList.add(options.classList)
                isActive = true;
                for (let key in options.isClass) {
                    if (__this.classList.contains(options.isClass[key].item))
                        isItemActive[key] = true;
                }
                addDataCanvas(isItemActive, options.isClass, options.data)
            }

        })
    })
}

function addDataCanvas(isItemActive, isClass, options) {
    const dataSet = {
        isRevenue: [],
        isOrder: [],
        isVisit: [],
        isConversionRate: [],
        isPageView: [],
        isRevenueOrder: [],
    }

    label = []

    const revenue = options.revenue;
    const order = options.order;
    const visit = options.visit;
    const pageView = options.pageView;
    const timeCurrent = options.typeTime == 'H' ? moment().format('H') : moment().format('DD');
    let isBefore = options.isBefore;

    for (let key in order) {
        label.push(formatHourOrDate(key));
        if (parseFloat(key) <= parseFloat(timeCurrent) || isBefore) {
            dataSet.isRevenue.push(parseFloat(revenue[key]).toFixed(2))
            dataSet.isOrder.push(parseFloat(order[key]))
            dataSet.isVisit.push(parseFloat(visit[key]))
            dataSet.isPageView.push(parseFloat(pageView[key]))
            dataSet.isRevenueOrder.push((parseFloat(revenue[key]) - (parseFloat(revenue[key]) * 0.3)).toFixed(2))
            dataSet.isConversionRate.push((parseFloat(order[key]) == 0 ? 0 : (parseFloat(visit[key]) == 0 ? 100 :
                (parseFloat(order[key]) / parseFloat(visit[key])).toFixed(2))))
        }
    }

    function formatHourOrDate(item) {
        if (options.typeTime == 'H')
            return item.toString().padStart(2, '0') + ":00";
        else
            return item.toString().padStart(2, '0');
    }

    const data = []
    for (let key in isItemActive) {
        if (isItemActive[key]) {
            data.push({
                // fill: false,
                label: isClass[key].name,
                data: dataSet[key],
                borderColor: isClass[key].color,
                borderWidth: 2,
                tension: 0
            })
        }
    }

    pushDataChart({
        data: data,
        label,
    })
}

function getDataByDateSelect(options) {
    let order = {};
    let orderTotal = 0;
    let orderBeforeTotal = options.order_before;
    let revenue = {};
    let revenueTotal = 0;
    let revenueBeforeTotal = 0;
    let visit = {};
    let visitTotal = 0;
    let visitBeforeTotal = options.visit_before;
    let pageView = {};
    let pageViewTotal = 0;
    let pageViewBeforeTotal = options.page_view_before;
    let isBefore = false;

    function handleData(arrayCurrent, arrNew, element, typeTime, startDate, endDate) {
        let endDateUnix = moment(endDate, 'YYYY-MM-DD HH:mm:ss').valueOf();
        let startDateUnix = moment(startDate, 'YYYY-MM-DD HH:mm:ss').valueOf();
        let timeEndDate;
        let timeStartDate;
        let monthEnd;

        if (typeTime == 'H') {
            timeStartDate = 0
            timeEndDate = 24
            isBefore = moment(startDateUnix).date() < moment(moment(moment(), 'YYYY-MM-DD HH:mm:ss').valueOf()).date() ? true : false
        } else if (typeTime == 'd') {
            timeStartDate = moment(startDateUnix).date();
            timeEndDate = moment(endDateUnix).date();
            monthEnd = moment(endDateUnix).month() + 1;
            isBefore = moment(startDateUnix).month() < moment(moment(moment(), 'YYYY-MM-DD HH:mm:ss').valueOf()).month() ? true : false;
        }

        const totalAllDaySelect = (moment(startDateUnix).daysInMonth() - timeStartDate) + timeEndDate

        let timeLast = timeStartDate;
        let totalDate = options.totalDate != 1 ? timeStartDate + options.totalDate : timeEndDate;

        if (arrayCurrent.length == 0) {
            for (i = timeStartDate; i < totalDate; i++)
                arrNew[i + (typeTime == 'd' ? "/" + (moment(startDateUnix).month() + 1) : 0)] = 0;
        } else {
            arrayCurrent.forEach(function (value, key) {
                let timestamp = value.order_date ? moment(value.order_date).valueOf() : moment(value.created_at).valueOf();
                let current;
                let currentMonth;
                let totalDayMonth;

                if (typeTime == 'H')
                    current = 24
                else if (typeTime == 'd') {
                    current = moment(timestamp).date();
                    currentMonth = moment(timestamp).month() + 1;
                    totalDayMonth = moment(timestamp).daysInMonth()
                }

                let currentDate = parseFloat(current);

                if (currentDate - timeLast != 0) {
                    let total = 0
                    if (currentDate - timeLast >= 1)
                        total = timeLast + (totalDayMonth - currentDate)
                    else
                        total = (timeLast + totalDayMonth) - (totalDayMonth - currentDate)
                    for (let i = timeLast; i <= total; i++) {
                        if (i != totalDayMonth) {
                            if (i > totalDayMonth) {
                                let key = i - totalDayMonth
                                arrNew[key + (currentMonth ? "/" + currentMonth : 0)] = 0;
                                continue;
                            }

                            arrNew[i + (currentMonth ? "/" + currentMonth : 0)] = 0;
                            if (i < currentDate)
                                for (let key = i + 1; key <= currentDate; key++) {
                                    arrNew[key + (currentMonth ? "/" + currentMonth : 0)] = 0;
                                }
                            continue;
                        }
                        arrNew[i + (currentMonth ? "/" + (currentMonth - 1) : 0)] = 0;
                    }
                }

                if (key + 1 === arrayCurrent.length && timeEndDate > currentDate) {
                    for (let i = timeLast + 1; i <= timeEndDate; i++) {
                        arrNew[i + (currentMonth ? "/" + currentMonth : '')] = 0;
                    }
                }

                if (value.total) {
                    element += parseFloat(value.total);
                    if (arrNew[currentDate + (currentMonth ? "/" + currentMonth : '')]) {
                        arrNew[currentDate + (currentMonth ? "/" + currentMonth : '')] += parseFloat(value.total);
                    } else {
                        arrNew[currentDate + (currentMonth ? "/" + currentMonth : '')] = parseFloat(value.total);
                    }
                } else {
                    element++;
                    if (arrNew[currentDate + (currentMonth ? "/" + currentMonth : '')]) {
                        arrNew[currentDate + (currentMonth ? "/" + currentMonth : '')] += 1;
                    } else {
                        arrNew[currentDate + (currentMonth ? "/" + currentMonth : '')] = 1;
                    }
                }
                timeLast = currentDate;
            });
        }
        return element;
    }

    let nullObj = {};
    orderTotal = handleData(options.order, order, orderTotal, options.typeTime, options.startDate, options.endDate);
    revenueTotal = handleData(options.revenue, revenue, revenueTotal, options.typeTime, options.startDate, options.endDate);
    revenueBeforeTotal = handleData(options.revenue_before, nullObj, revenueBeforeTotal, options.typeTime, options.startDate, options.endDate);
    visitTotal = handleData(options.visit, visit, visitTotal, options.typeTime, options.startDate, options.endDate);
    pageViewTotal = handleData(options.page_view, pageView, pageViewTotal, options.typeTime, options.startDate, options.endDate);

    let revenue_order_before = revenueBeforeTotal - revenueBeforeTotal * 0.3;
    let revenue_order = (revenueTotal - revenueTotal * 0.3).toFixed(2);

    eventClickAddDataChart({
        form: '.list-item__overview',
        element: '.item__overview',
        classList: 'active',
        isClass: {
            isOrder: {
                item: 'order',
                color: '#ff3939',
                name: 'Order',
            },
            isRevenue: {
                item: 'revenue',
                color: '#c8a16d',
                name: 'Revenue',
            },
            isPageView: {
                item: 'page-view',
                color: '#c74dff',
                name: 'Page View',
            },
            isRevenueOrder: {
                item: 'revenue-order',
                color: '#fff',
                name: 'Revenue Order',
            },
            isVisit: {
                item: 'visit',
                color: '#58c01d',
                name: 'Visit',
            },
            isConversionRate: {
                item: 'conversion-rate',
                color: '#54accf',
                name: 'Conversion Rate',
            },
        },
        data: {
            revenue: revenue,
            order: order,
            visit: visit,
            pageView: pageView,
            typeTime: options.typeTime,
            isBefore: isBefore,
        },
        isSelect: options.isSelect,
    })

    addDataOverView({
        revenue: '#overview__revenue',
        order: '#overview__order',
        conversionRate: '#overview__conversion-rate',
        visit: '#overview__visit',
        pageView: '#overview__page-view',
        revenueOrder: '#overview__revenue-order',
    }, {
        revenue: parseFloat(revenueTotal).toFixed(2),
        order: orderTotal,
        conversionRate: (orderTotal === 0 ? 0 : (visitTotal === 0 ? 100 : orderTotal / visitTotal)).toFixed(2),
        visit: visitTotal,
        pageView: pageViewTotal,
        revenueOrder: revenue_order,
    })
}

function addDataOverView(options, data) {
    for (let key in options) {
        document.querySelector(options[key]).innerText = data[key]
    }
}

function dashboardSelect(options) {
    const element = document.querySelectorAll(options.element);

    element.forEach(function (item) {
        item.addEventListener('click', function (event) {
            document.querySelector(options.element + '.' + options.classList).classList.remove(options.classList)
            this.classList.add(options.classList)
            addDataRanking({
                urlApi: options.urlApi,
                dashboardTypeSelect: this.innerText.toLowerCase(),
                productRank: options.productRank,
                productCategoryRank: options.productCategoryRank,
            })
        })
    })
}

function addDataRanking(options) {
    const date = {
        startDate: '',
        endDate: ''
    }
    getStartAndEndDateFromString(date)

    const data = {
        startDate: date.startDate.format('YYYY-MM-DD'),
        endDate: date.endDate.format('YYYY-MM-DD'),
        dashboardTypeSelect: options.dashboardTypeSelect,
    }
    
    axios.post(options.urlApi, data) 
        .then(response => {
            console.log(response.data)
            const productRank = document.querySelector(options.productRank)
            const productCategoryRank = document.querySelector(options.productCategoryRank)

            let htmls = response.data.productRank.map(function (item, index) {
                return `
                    <li class="product-info__item">
                        <p class="product-info__item--rank">${index + 1}</p>
                        <a href="#" class="__item-sales__link">
                            <div class="product-info__item-sales">
                                <img src="${item.url}" alt=""
                                    class="product-info__item-sales__img">
                                <p class="product-info__item-name">${item.name}</p>
                            </div>
                        </a>
                        <p class="product-info__item-revenue">${parseFloat(item.total).toFixed(2)}</p>
                    </li>
                `
            })

            productRank.innerHTML = htmls.join('')
            htmls = ''

            htmls = response.data.productCategoryRank.map(function (item, index) {
                return `
                    <li class="product-info__item">
                        <p class="product-info__item--rank"> ${index + 1}</p>
                        <p class="product-info__item-name product-category"> ${item.name_type.toLowerCase()}</p>
                        <p class="product-info__item-revenue"> ${item.total}</p>
                    </li>
                `
            })

            productCategoryRank.innerHTML = htmls.join('')
        })
}