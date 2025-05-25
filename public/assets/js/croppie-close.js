



const config = document.getElementById('configImg');
const w = Number(config.dataset.width);
const h = Number(config.dataset.height);
const patch = config.dataset.patch;
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

        document.querySelector('.deleteImage').style.display = 'flex';


    });
});
document.getElementById('files').addEventListener('change', handleFileSelect, false);

document.querySelector('.deleteImage').addEventListener('click', function () {
    this.previousElementSibling.src = '';
    this.style.display = 'none';
    let image =document.getElementById('image');
    document.getElementById('btn-upload').style.display = 'inline-block';

    fetch('/admin/'+patch+'/deleteImage/?image='+image.value)
        .then(function(response) {
            if (response.ok) {
                image.value='';
                console.log(response.text());
            } else {
                throw new Error('Ошибка при выполнении запроса');
            }
        })
        .then(function(data) {
            // Обработка ответа от сервера
            console.log(data);
        })
        .catch(function(error) {
            // Обработка ошибок
            console.log(error);
        });
});
