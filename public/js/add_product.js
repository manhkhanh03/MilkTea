// const clientId = '7tdbae4pljk4k1b';
// const clientSecret = 'fvfq3y7obvtb4oa';
// const authorizationCode = '0S48ap8OcPoAAAAAAAAAK1f5T99CcOSpc6v6h7m7fJ0'
// const redirectUri = 'http://127.0.0.1:8001';

// const url = 'https://api.dropboxapi.com/oauth2/token';

// const data = new URLSearchParams();
// data.append('code', authorizationCode);
// data.append('grant_type', 'authorization_code');
// data.append('client_id', clientId);
// data.append('client_secret', clientSecret);
// data.append('redirect_uri', redirectUri);
let myAccessToken
let isProduct = false, isImage = false, isflavor = false;
const arrayIdImageDeleted = {
    ids: {}
}

function getAccessToken() {
    const clientId = '7tdbae4pljk4k1b';
    const clientSecret = 'fvfq3y7obvtb4oa';
    const refreshToken = 'hptaNreC2NIAAAAAAAAAASmtgmed1VIMx-PrulT57JTrCH8-1B0o5pX-KCrxcntw';

    const url = 'https://api.dropboxapi.com/oauth2/token';

    const data = new URLSearchParams();
    data.append('grant_type', 'refresh_token');
    data.append('refresh_token', refreshToken);
    data.append('client_id', clientId);
    data.append('client_secret', clientSecret);
    axios.post(url, data)
        .then(response => {
            myAccessToken = response.data.access_token;
        })
        .catch(error => {
            console.error('Error getting tokens:', error);
        });
}

getAccessToken()

function deleteLinkImage(urlApi, data) {
    console.log(data)
    return new Promise((resolve, reject) => {
        async function deleteRecord(urlApi, data) {
            try {
                const response = await axios.post(urlApi, data);
                return response.data;
            } catch (error) {
                throw error;
            }
        }

        deleteRecord(urlApi, data)
            .then(result => {
                console.log('Record deleted img successfully:', result);
                resolve();
            })
            .catch(error => {
                console.error('Error deleting img record:', error);
                reject();
            });
    });
}

function addDropbox(options) {
    const listImg = document.querySelectorAll(options.listImg)
    let file;

    if (arrayIdImageDeleted.length !== 0) {
        deleteLinkImage(options.urlApiDeleteImage, arrayIdImageDeleted)
    }

    var dbx = new Dropbox.Dropbox({
        accessToken: myAccessToken,
    });
    var reader = new FileReader();
    for (let key = 0; key < listImg.length; key++) {
        if (options.originalImage) {
            const isTrue = options.originalImage.filter((img) => {
                return img.url == listImg[key].src
            })
            if (isTrue.length != 0)
                continue;
        }
        var imageUrl = listImg[key].src;
        var fileName = imageUrl.split('/').pop() + '.jpg';
        console.log(imageUrl, fileName)
        axios.get(imageUrl, { responseType: 'arraybuffer' })
            .then(response => {
                var fileContent = response.data;
                dbx.filesUpload({
                    path: '/' + fileName,
                    contents: fileContent
                })
                    .then(function (response) {
                        return dbx.sharingCreateSharedLinkWithSettings({
                            path: '/' + fileName
                        });
                    })
                    .then(function (response) {
                        const urlImg = response.result.url.slice(0, -1) + '1';
                        options.url = urlImg
                        handleUploadImgProduct(options)
                    })
                    .catch(function (error) {
                        console.error('Error uploading file to Dropbox:', error);
                    });
            })
            .catch(error => {
                console.error('Error fetching image:', error);
            });
    }
}

function handleUploadImgProduct(options) {
    return new Promise((resolve, reject) => {
        const data = {
            product_id: options.product_id,
            url: options.url,
        }

        axios.post(URLWeb + options.urlApi, data)
            .then(data => {
                console.log("Success: ", data)
                resolve()
            })
            .catch(error => {
                console.error('error: ', error);
                reject()
            })
    })
}

function handleEventSelectedProduct(options) {
    const getCategory = function (urlApi, type, elementCategory) {
        urlApi += type.toLowerCase();
        axios.get(urlApi)
            .then(response => {
                const elements = document.querySelectorAll(elementCategory)
                const htmls = response.data.map((value) => {
                    return `
                        <li class="list-category__item" data-type="${value.type}" data-id="${value.id}">${value.name}</li>
                    `
                })
                elements.forEach(function (item) {
                    item.innerHTML = htmls.join('')
                })
            })
            .catch(err => {
                console.log('error: ', err);
            })
    }
    let parent = document.querySelectorAll(options.parent)
    parent.forEach(function (this_parent) {
        let children = this_parent.querySelectorAll(options.children)
        let selector = this_parent.querySelector(options.selector)
        children.forEach(function (item) {
            item.addEventListener('click', function (event) {
                selector.innerText = event.target.innerText
                selector.value = event.target.innerText
                selector.setAttribute(options.attribute, event.target.getAttribute(options.attribute))
                if (options.urlApi) {
                    getCategory(options.urlApi, event.target.innerText, options.elementCategory)
                }
            })
        })
    })

    if (options.notClick) {
        parent.forEach(function (this_parent) {
            let children = this_parent.querySelectorAll(options.children)
            let selector = this_parent.querySelector(options.selector)
            children.forEach(function (item) {
                selector.innerText = item.innerText
                selector.value = item.innerText
                selector.setAttribute(options.attribute, item.getAttribute(options.attribute))
                if (options.urlApi) {
                    getCategory(options.urlApi, item.innerText, options.elementCategory)
                }
            })
        })
    }
}

function handleEventAddImage(options) {
    const parent = document.querySelector(options.parent)
    const input = parent.querySelector(options.input)
    const quantity = parent.querySelector(options.quantity)

    input.addEventListener('change', function (e) {
        if (Number(quantity.innerHTML) < 6) {
            const file = e.target.files[0]
            const url = URL.createObjectURL(file);

            const newLiElement = document.createElement('li');
            newLiElement.className = '__right-img__item';

            const newImgElement = document.createElement('img');
            newImgElement.src = url;
            newImgElement.className = "__item-img img-product-add-new";
            newLiElement.appendChild(newImgElement);

            const boxHandleDeleteElement = document.createElement('div');
            boxHandleDeleteElement.className = 'box-handle-delete';
            newLiElement.appendChild(boxHandleDeleteElement);

            const cropImageElement = document.createElement('p');
            cropImageElement.id = 'crop-image';
            cropImageElement.style.backgroundImage = 'url(/img/crop.png)';
            boxHandleDeleteElement.appendChild(cropImageElement);

            const deleteImageElement = document.createElement('p');
            deleteImageElement.id = 'delete-image';
            deleteImageElement.style.backgroundImage = 'url(/img/delete.png)';
            boxHandleDeleteElement.appendChild(deleteImageElement);

            const lastLiElement = parent.lastElementChild;
            parent.insertBefore(newLiElement, lastLiElement);
            quantity.innerHTML = Number(quantity.innerHTML) + 1;

            handleEventDeleteImage({
                parent: '.__right-img__item',
                btn: '#delete-image',
                quantity: quantity,
                input: e.target
            })
        }
    })
}

function handleEventAddCharacter(options) {
    const input = document.querySelectorAll(options.input)
    const quantity = document.querySelectorAll(options.quantity)
    let lastValue = '';


    input.forEach(function (__this, index) {
        __this.addEventListener('input', function (e) {
            if (lastValue.length > e.target.value.length) {
                let length = Number(quantity[index].innerText) - (lastValue.length - e.target.value.length)
                quantity[index].innerText = length
                e.target.style.borderColor = '#c8a16d'
            } else {
                if (quantity[index].innerText <= 120) {
                    quantity[index].innerText = Number(quantity[index].innerText) + 1
                }
                if (Number(quantity[index].innerText) >= 118) {
                    e.target.style.borderColor = '#e04141'
                }
            }
            lastValue = e.target.value
        })

        Array.from(__this.nextElementSibling.nextElementSibling.children).forEach((item) => {
            item.addEventListener('click', function (event) {
                quantity[index].innerText = event.target.innerText.length
                lastValue = event.target.innerText
            })
        })
    })
}

function handleEventDeleteImage(options) {
    const btn = document.querySelectorAll(options.btn)
    const parent = document.querySelectorAll(options.parent)

    btn.forEach(function (__this) {
        __this.addEventListener('click', function (event) {
            let newUrl = options.urlApi + event.target.parentNode.previousElementSibling.getAttribute('data-id')
            event.target.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode)
            options.quantity.innerHTML = Number(options.quantity.innerHTML) - 1
            console.log(options)
            if (options.input)
                options.input.value = ''
            else {
                console.log(newUrl)
                const newItem = event.target.parentNode.previousElementSibling.getAttribute('data-id')
                arrayIdImageDeleted.ids[newItem] = newItem
                // deleteLinkImage(newUrl) // ????????????
                // tạo 1 mảng global chứa các id bị xóa sau khi edit thì thực hiện gọi hàm deletelinkimage
            }
        })
    })
}

function handleDeleteGroup(options) {
    const btn = document.querySelectorAll(options.btn)
    btn.forEach(function (__this) {
        __this.addEventListener('click', function (event) {
            event.target.parentNode.parentNode.parentNode.parentNode.removeChild(event.target.parentNode.parentNode.parentNode)
        })
    })
}

// function handleSalesInfo(options) {
//     const parent = document.querySelector(options.parent)
//     const selector = parent.querySelector(options.selector)
//     const listItem = document.querySelectorAll(options.listItem)
//     const listTypeItem = parent.querySelectorAll(options.listTypeItem)

//     listTypeItem.forEach(function (item) {
//         item.addEventListener('click', function (event) {
//             listItem.forEach(item => {
//                 if (event.target.innerText.toLowerCase() !== item.getAttribute(options
//                     .attribute)
//                     .toLowerCase()) {
//                     // item.classList.add('not-active');
//                 } else {
//                     // item.classList.remove('not-active');
//                 }
//             });
//         });
//     })
// }

async function handleInfoBasic(options, optionsSalesInfo) {
    try {
        const name = document.querySelector(options.name)
        const quantity = document.querySelector(options.quantity)
        let isAccept = false
        const data = {
            name: name.value,
            quantity: quantity.value,
            shop_id: options.shop_id,
        }
        if (options.typeClick === 'upload') {
            isAccept = true;
        }
        else if (options.typeClick === 'hide' && options.product_id) {
            data.status = 'hide'
            isAccept = true;
        } else if (options.typeClick === 'cancel') {
            let result = confirm('Are you sure you want to cancel this product?')
            if (result)
                window.location.reload()
        } else if (options.typeClick === 'edit') {
            isAccept = false;
        }

        if (isAccept) {
            const response = await axios.post(URLWeb + options.urlApi, data);
            if (response.data.id) {
                optionsSalesInfo.product_id = response.data.id;
                await addDropbox({
                    listImg: options.listImg,
                    product_id: response.data.id,
                    urlApi: options.urlApiProductImage,
                })
                await handleInfoSales(optionsSalesInfo);
            }
        } else {
            const response = axios.put(URLWeb + options.urlApi, data)
            await deleteRelation(optionsSalesInfo.urlApiDelete)
            await addDropbox({
                listImg: options.listImg,
                product_id: optionsSalesInfo.product_id,
                urlApi: options.urlApiProductImage,
                originalImage: optionsSalesInfo.listImg,
                urlApiDeleteImage: options.urlApiDeleteImage,
            })
            await handleInfoSales(optionsSalesInfo);
        }
    } catch (error) {
        alert('error' + error)
    }
}

function deleteRelation(urlApi) {
    return new Promise((resolve, reject) => {
        async function deleteRecord(urlApi) {
            try {
                const response = await axios.delete(urlApi);
                return response.data;
            } catch (error) {
                throw error;
            }
        }

        deleteRecord(urlApi)
            .then(result => {
                console.log('Record deleted successfully:', result);
                resolve()
            })
            .catch(error => {
                console.error('Error deleting record:', error);
                reject()
            });
    })
}

function handleInfoSales(options) {
    return new Promise((resolve, reject) => {
        const flavors = document.querySelectorAll(options.flavor)
        const sizes = document.querySelectorAll(options.size)
        const prices = document.querySelectorAll(options.price)
        const type = document.querySelector(options.type)

        function handleFlavor(options, nameFlavor, size, price) {
            let isTrue = false;
            options.flavors.forEach(function (flavor) {
                if (nameFlavor.toLowerCase().trim() === flavor.name.toLowerCase().trim()) {
                    isTrue = true;
                }
            })
            const data = {
                flavor: nameFlavor,
                size: size,
                price: price,
                product_id: options.product_id
            }
            data.product_id = options.product_id
            if (!isTrue) {
                data.type = type.value.toLowerCase()
                data.request = 'update';
            }

            axios.post(URLWeb + options.urlApi, data)
                .then(() => {
                    resolve()
                })
                .catch(err => {
                    reject();
                })
        }

        flavors.forEach(function (item, index) {
            handleFlavor(options, item.value, sizes[index].value, prices[index].value)
        })
    })
}

function handleChangeType(options) {
    const parent = document.querySelector(options.parent)
    const selector = parent.querySelector(options.selector)
    const listItem = document.querySelectorAll(options.listItem)
    const listTypeItem = parent.querySelectorAll(options.listTypeItem)

    if (selector.value !== 'Select:') {
        listItem.forEach(item => {
            if (selector.value.toLowerCase() !== item.getAttribute(options
                .attribute)
                .toLowerCase()) {
                item.classList.add('not-active');
            } else {
                item.classList.remove('not-active');
            }
        });
    }
}

function checkQuantityImage(options) {
    const imgElement = document.querySelectorAll(options.img)
    if (imgElement.length > 0) {
        return true;
    } else return false;
}
