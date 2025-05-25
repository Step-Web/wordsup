function playWord(obj) {
    const sound = document.getElementById('audio');
    let voice = obj.dataset.voice;
    if(obj.className !== 'audio-icon'){ return false; }
    let fn = obj.getAttribute('data-audio');
    let l = fn.charAt(0);
    sound.src = 'http://wordsup.loc/audio/words/'+voice+'/'+l.toLowerCase()+'/'+fn;
    obj.classList.add('loader');
    const audio = new Audio();
    audio.src = sound.getAttribute('src');
    audio.autoplay = true;
    audio.playbackRate = 0.8;
    audio.addEventListener('loadedmetadata', function() {
        obj.classList.remove('loader');
        obj.classList.add('play');
        let sec = (audio.duration * 500).toFixed(0);
        setTimeout(function() {obj.classList.remove('play'); }, sec);
    });
    //  let req = new XMLHttpRequest();req.open('GET','/words/checkVoice/?voice='+voice+'&fn='+fn,false);req.send(null);
    //  obj.dataset.voice = req.responseText;
    let req = (voice=='f')?'m':'f';
    obj.dataset.voice = req;
}


function playPhrase(btn) {
    var sound = document.getElementById('audio');
    let id = btn.dataset.audio;
    if(btn.className !== 'audio-icon'){ return false; }
    let file;
    let playbackRate = 1;
    if(id.charAt(0) == 's'){
         file = 'http://laravel.loc/storage/audio/sentence/en/'+id.slice(-1)+'/'+id.slice(1)+'.mp3';
        playbackRate = 0.8;
    } else {
         file = 'https://audio.tatoeba.org/sentences/eng/'+id+'.mp3';
    }

    sound.setAttribute('src',file);
    btn.classList.add('loader');
    let audio = new Audio();

    audio.src = sound.getAttribute('src');
    audio.autoplay = true;
    audio.playbackRate = playbackRate;
    audio.addEventListener('loadedmetadata', function() {
        btn.classList.remove('loader');
        btn.classList.add('play');
        const sec = (audio.duration * 800).toFixed(0);
        setTimeout(function() {btn.classList.remove('play')}, sec);
    });
}


function fakeSelect(el) {
    let select = document.querySelector(el);
    select.style.display = 'none';
    let ul = '';
    let id = select.name;
    let op = select.querySelectorAll('option');
    let btnClass = (select.dataset.class)?select.dataset.class:'form-select';
    op.forEach(function(o) {
        let subcolor = (o.dataset.color) ?' <small style="width:1em;height:1em;display:inline-block;margin-right:0.3em;background:'+o.dataset.color+'"></small>':'';
        let subtext = (o.dataset.subtext) ?' <small class="text-muted">'+o.dataset.subtext+'</small>':'';
        ul +='<li data-id="'+o.value+'" onclick="fakeOptions(this)"><div class="dropdown-item"><span>'+subcolor+o.textContent+'</span>'+subtext+'</div></li>'
    });
    select.insertAdjacentHTML("afterend", '<div class="mydropdown" id="my'+id+'"> <button class="'+btnClass+' form-select" type="button" id="'+id+'" data-bs-toggle="dropdown" aria-expanded="false">'+select.options[select.selectedIndex].textContent+' </button><ul class="dropdown-menu" aria-labelledby="'+id+'">'+ul+'</ul></div>');
}

function fakeOptions(v) {
    const dropdown = v.closest('.mydropdown');
    const btn = dropdown.querySelector('button');
    const op = dropdown.querySelectorAll('li');
    op.forEach(function(li) {li.classList.remove('bg-light');})
    let key = v.dataset.id;
    let val = v.querySelector('span').textContent;
    v.classList.add('bg-light');
    btn.textContent = val;
    dropdown.previousSibling.value = key;
}

function copyGroup(btn){
    winModal = new bootstrap.Modal(document.getElementById('winModal'));
    let url = btn.dataset.url;
    let req = new XMLHttpRequest();req.open('GET',url,false);req.send(null);
    document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
    winModal.show();
    return false;
}

function clearErorrs(btn,word='clearAll'){
    const type = document.getElementById('errortype').value;
    const req = new XMLHttpRequest();req.open('GET','/user/clearErorrs/'+word+'/'+type,false);req.send(null);
    let badge = document.getElementById('errorbadge');
    if(req.responseText){
        if(word != 'clearAll'){
            btn.closest('tr').remove();
            badge.innerText = req.responseText;
            oTable.refresh();
        } else {
            badge.remove();
            document.querySelector('.table-responsive').innerHTML = 'Ваши ошибки в были удалены';
        }
    }
}

function insertWord(form){
    const formData = new FormData(form);
    let mess = form.querySelector('.mess');
    fetch(form.action, {
        method: "POST",
        body: formData
    })
        .then((response) => response.json())
        .then((res) => {
            if (res.error) { mess.innerText = res.error; mess.className = 'text-danger'; return false}
            if(res.id){
                mess.innerText = 'слово было добавлено'; mess.className = 'text-success';
                setTimeout(() => {
                    if(document.getElementById('redictgroup').checked){  window.location.href = '/words/group/'+document.getElementById('groupnew').value;  }
                    form.querySelector('.btn-close').click();
                }, 1000);
            }
        });
    return false;
}

const topnav = document.getElementById("navbar");topnav.querySelector(".toggle").addEventListener("click",() => {topnav.classList.toggle("collapsed");});
function messBlock(txt,color='warning',time=1500) {
    var mes = document.getElementById('messblock');
    mes.innerHTML ='<div class="alert alert-'+color+' alert-dismissible fade show" role="alert">'+txt+'<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    if(color!=='danger'){
        setTimeout(function(){  mes.querySelector('.alert').classList.remove('show');},time);
    }
}



