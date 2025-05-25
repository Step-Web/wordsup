const config = document.getElementById('configImg');
const w = Number(config.dataset.width);
const h = Number(config.dataset.height);
const patch = config.dataset.patch;
let id = config.dataset.id;
let imgblock = document.querySelector('.imgblock');
imgblock.style.maxWidth = w+'px'

let crop = document.getElementById('croppie');
let croppie = new Croppie(crop, {
    viewport: { width: w, height: h },
    boundary: { width: w+10, height: h+10 },
    showZoomer: true,
    enableResize: false,
    enableOrientation: true,
    mouseWheelZoom: 'ctrl'
});
function handleFileSelect(evt) {
    let files = evt.target.files; // объект FileList
    let f = files[0] // первый выбранный
    if (!f.type.match('image.*')) {
        messBlock('Это не изображение или оно не поддерживается','warning',2000); return false;
    }

    let reader = new FileReader();
    reader.onload = (function() {
        return function(e) {
            croppie.bind({ url: e.target.result});
        };
    })(f);
    reader.readAsDataURL(f);  // Читаем файл изображения как URL-адрес данных


    crop.style.display = 'block';
    document.getElementById('btn-upload').style.display = 'none';
    document.getElementById('btn-crop').style.display = 'inline-block';
    document.getElementById('imagefile').style.display = 'none';

}
document.getElementById('btn-crop').addEventListener('click', function () { /// Обрезаем изображение и получаем изображение в base64
    croppie.result({type: 'base64'}).then(function (img) {
        document.getElementById('imagefile').src=img;
        document.getElementById('imagefile').style.display = 'block';
        document.getElementById('imagebase24').value = img;
        document.getElementById('croppie').style.display = 'none';
        document.getElementById('btn-crop').style.display = 'none';
        document.querySelector('.deleteBtn').style.display = 'inline-block';
        messBlock('Не забудьте сохранить изменения','warning')

    });
});
document.getElementById('files').addEventListener('change', handleFileSelect, false);

document.querySelector('.deleteBtn').addEventListener('click', function () {
    this.previousElementSibling.src = '';
    this.style.display = 'none';
    document.getElementById('btn-upload').style.display = 'inline-block';
    let formData = new FormData();
    formData.append("_token",  document.head.querySelector("[name=csrf-token]").content);

    console.log(formData);
    fetch('/admin/deleteImage/'+patch+'/'+id,{
        method: "POST",
        body: formData
    }).then((res) => res.json())
        .then(function(data) {
            // Обработка ответа от сервера
           document.getElementById('imagefile').src ='/storage/images/'+patch+'/noimg.svg';


           // console.log(data);
        })
        .catch(function(error) {
            // Обработка ошибок
            console.log(error);
        });
});
