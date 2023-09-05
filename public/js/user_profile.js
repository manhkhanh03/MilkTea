function handleUserProfile(options) {
    handleApiMethodGet(options)
}

function handleImageUser(options) {
    const btn = document.querySelector(options.btnChange)
    const selectorImage = document.querySelector(options.selectorImage)

    if (btn) {
        btn.addEventListener('change', function (e) {
            if (e.target.files && e.target.files[0]) {
                selectorImage.style.backgroundImage = `url(${URL.createObjectURL(e.target.files[0])})`;
            }
        })
    }
}

// function handleAlterInformationUser(options, data) {
//     const btn = document.querySelector(options.btn)
//     btn.addEventListener('click', function (e) {
//         const newData = {}
//         options.rules.forEach(function (rule) {
//             const selectorElement = document.querySelector(rule.selector)
//             let errorMessage;
//             if (selectorElement && selectorElement.tagName.toLowerCase() === 'input') {
//                 errorMessage = rule.test(selectorElement.value)
//                 if (!errorMessage) {
//                     newData[rule.selector] = selectorElement.value
//                 }
//             } else {
//                 errorMessage = rule.test(selectorElement.style.backgroundImage)
//                 if (!errorMessage) {
//                     const urlStartIndex = "url(".length;
//                     const urlEndIndex = selectorElement.style.backgroundImage.indexOf(")");
//                     let img = selectorElement.style.backgroundImage
//                         .slice(urlStartIndex, urlEndIndex).replace(/['"]/g, "").trim();
//                     function getBase64FromBlobURL(img, callback) {
//                         const xhr = new XMLHttpRequest();
//                         xhr.onload = function () {
//                             const reader = new FileReader();
//                             reader.onloadend = function () {
//                                 callback(reader.result);
//                             };
//                             reader.readAsDataURL(xhr.response);
//                         };
//                         xhr.open('GET', img);
//                         xhr.responseType = 'blob';
//                         xhr.send();
//                     }
//                     newData[rule.selector] = getBase64FromBlobURL(img, function (newImage) {
//                         return newImage
//                     });
//                 }
//             }
//         })
//         console.log(newData);
//         options.handle(newData, options)
//     })
// }

function handleAlterInformationUser(options, data) {
    const btn = document.querySelector(options.btn);
    btn.addEventListener('click', async function (e) {
        const newData = {};
        for (const rule of options.rules) {
            const selectorElement = document.querySelector(rule.selector);
            let errorMessage;

            if (selectorElement && selectorElement.tagName.toLowerCase() === 'input') {
                errorMessage = rule.test(selectorElement.value);
                if (!errorMessage) {
                    newData[rule.selector] = selectorElement.value;
                }
            } else {
                errorMessage = rule.test(selectorElement.style.backgroundImage);
                if (!errorMessage) {
                    const urlStartIndex = "url(".length;
                    const urlEndIndex = selectorElement.style.backgroundImage.indexOf(")");
                    let img = selectorElement.style.backgroundImage
                        .slice(urlStartIndex, urlEndIndex).replace(/['"]/g, "").trim();
                    var dbx = new Dropbox.Dropbox({
                        accessToken: myAccessToken,
                    });
                    var fileName = img.split('/').pop() + '.jpg';
                    console.log(fileName)
                    console.log(img)
                    await axios.get(img, { responseType: 'arraybuffer' })
                        .then(response => {
                            var fileContent = response.data;
                            console.log(fileContent)
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
                                    newData[rule.selector] = urlImg;
                                    console.log(newData);
                                    options.handle(newData, options);
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
        }
    });
}

handleAlterInformationUser.isSelector = function (selector, message) {
    return {
        selector: selector,
        test: function (value) {
            return value !== '' ? undefined : message
        }
    }
}

handleAlterInformationUser.isChangeImage = function (selector, message, currentUrlImage) {
    return {
        selector: selector,
        test: function (value) {    
            return value !== currentUrlImage ? undefined : message
        }
    }
}

