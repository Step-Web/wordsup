const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

const group_id = document.getElementById('group_id').value;
const sr = document.getElementById('search_result');
const myOffcanvas = document.getElementById('exerciseModal');
myOffcanvas.addEventListener('show.bs.offcanvas', event => {
    let studymode = document.getElementById('studymode').value;

    let formData = new FormData();
    formData.append('_token', csrf_token);
    formData.append('group_id', group_id);
    formData.append('studymode', studymode);
    let btn = event.relatedTarget;
    let checked = btn.dataset.checked;

    if(checked == 'true'){
        const checkedBoxes = document.querySelectorAll('input.che:checked');
        let w = [];
        checkedBoxes.forEach(function(elem) { w.push(elem.value); });
        formData.append("words", w.join(','));
    } else {
        formData.delete("words");
    }

   // console.log(formData);

    fetch("/learnword/getWords/group", {
        method: "POST",
        body: formData
    })
        .then((res) => res.text())
        .then((req) => {
            myOffcanvas.querySelector('.offcanvas-body').innerHTML = req;
            setTimeout(function () {
                activateSlider('.slider');
            }, 100);
            if (studymode == 'write') {
                setTimeout(function () {
                    document.querySelector('#slider input:first-of-type').focus();
                }, 1000);
            }
        });
});

myOffcanvas.addEventListener('hide.bs.offcanvas', event => {
    if(setresult == 0) setResult();
    myOffcanvas.querySelector('.offcanvas-body').innerHTML = '';
    let sc = myOffcanvas.querySelector('#score');
    sc.textContent = '0';
    sc.className = '';
    document.getElementById('btn-skip').style.display = '';
    document.getElementById('btn-unknown').style.display = '';
    document.getElementById('btn-close').classList.add('d-none');
});


function editRow(e) {
    let tr = e.closest('tr');
    let req = new XMLHttpRequest();req.open('GET','/words/userword/'+tr.id+'/edit',false);req.send(null);
    document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
    setTimeout(function(){
        var sorting = document.getElementById('wintanslate');
        new Sortable(sorting, {
            animation: 150,
            ghostClass: 'blue-background-class',
            onEnd: function (evt) { collectTranslate(sorting) }
        });
    },1000);
}


function saveWord(form){
    const url = form.getAttribute('action');
    const formData = new FormData(form);
    let req = new XMLHttpRequest(); req.open("POST", url,false);req.send(formData);
    console.log(req.responseText);
    if(req.responseText){
        let tr = document.getElementById(formData.get('id'));
        tr.querySelector('.word').textContent = formData.get('word');
        tr.querySelector('.translate').textContent = formData.get('translate');
        form.querySelector('button[data-bs-dismiss="modal"]').click();
        messBlock('Слово <b>'+formData.get('word')+'</b> обновлено','success',2000);
    } else {
        document.querySelector('#winModal .modal-body').innerHTML = 'Что то пошло не так';
    }
    return false;

}

function delRow(e) {
    let tr = e.closest('tr');
    tr.classList.add('fade');
    let formData = new FormData();
    formData.append('_token', csrf_token);
    formData.append('_method', "DELETE");
    fetch("/words/userword/" + tr.id, {
        headers:{  "X-Requested-With": "XMLHttpRequest"},
        method: "POST",
        body: formData
    })
        .then((res) => res.json())
        .then((response) => {
            console.log(response);
            if(response > 0)  {tr.remove(); oTable.setTotal(); messBlock('Слово удалено из группы','success');
            } else{
                messBlock('Не удалось удалить слово из группы','error')
            }
        });
}

function addRow(e='') {
    const total = oTable.total();
    const w = (e) ? e.dataset.word:searchInput.value.trim();
    searchInput.value = w;
    const regexp = /^[a-z\s]+$/i;
    if(w=='') {
        messBlock('Введите слово','warning');
    } else if(!regexp.test(w)) {
        messBlock('Русские буквы и цифры не допустимы');
    } else if(total >= 100) {
        messBlock('Максимально допустимое слов в группе 100','danger');
    } else {
        const form = document.getElementById('newword');
        const formData = new FormData(form);

        fetch('/words/userword', {
            method: "POST",
            body: formData
        })
    .then((response) => response.json())
            .then((res) => {
               // console.log(res);
                if (res.error) { messBlock(res.error); return false}
                if (res.translate =='') {
                    winModal = new bootstrap.Modal(document.getElementById('winModal'));
                    let req = new XMLHttpRequest();req.open('GET','/words/userword/' + res.id + '/edit?modal=setWordTranslate',false);req.send(null);
                   document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
                    setTimeout(() => { document.getElementById('inputtranslate').focus(); }, 500);
                    winModal.show();
                }
                const play = (res.audio) ? ' data-audio="' + res.audio + '" data-voice="f" onclick="playWord(this)"' : '';
                let newRow = [
                    '<input type="checkbox" name="id[]" value="' + res.id + '" class="che" onchange="oTable.showChecked()">',
                    '<div class="wordblock"><div class="audio-icon" '+play+'><i class="icon-play"></i></div><div><div class="word fw-bold text-info">' + res.word + '</div><div class="ts">' + res.ts + '</div></div></div>',
                    '<div class="translate">' + res.translate + '</div>',
                    '<div class="stat l0" onclick="setProgressWord(this)"></div>',
                    '<div class="dropdown-toggle" id="act' + res.id + '" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div><ul class="dropdown-menu dropdown-menu-end" aria-labelledby="act' + res.id + '"><li><div class="fas fa-pencil-alt dropdown-item editRow" data-bs-toggle="modal" data-bs-target="#winModal" data-id="' + res.id + '"><span>Изменить</span></div></li> <li><div class="fas fa-trash-alt dropdown-item delRow"><span>Удалить</span></div></li></ul>'
                ];
                let tableRow = document.querySelector("#oTable tbody");
                let row = tableRow.insertRow(0);
                row.setAttribute('id', res.id);
                for (let i = 0; i < newRow.length; i++) {
                    let cell = row.insertCell(i);
                    cell.innerHTML = newRow[i];
                }
                oTable.refresh();
                messBlock('Добавление прошло успешно','success');
            });

        searchInput.value = '';
        sr.innerHTML = '';
        sr.style.display = 'none';
    }
    return false;


}



function resetProgress(){
    const formData = new FormData(document.getElementById('formtab'));
    formData.append('_token', csrf_token);
    fetch('/words/userword/resetProgress', {
        method: 'POST',
        body: formData
    }).then((res) => res.json())
        .then((response) => {
            if(response > 0)  {
                document.querySelectorAll('tbody input.che:checked').forEach(function(c) { c.closest('tr').querySelector('.stat').className = 'stat l0'; });
            } else{
                messBlock('Не удалось изменить прогресс','warning')
            }
        });

}

let searchInput = document.getElementById('add_words');
let inputEvent = function (e) {
    let w = this.value;
    if(this.value.length >= 2){
        let req = new XMLHttpRequest();req.open('GET','/dictonary/search?word='+w,false);req.send(null);
        let obj = JSON.parse(req.responseText);
        let li = '';
        for (const k of Object.keys(obj)) {
            let re = new RegExp(w,"g"); // search for all instances
            let nw = obj[k].word.replace(re, '<span class="text-danger">'+w+'</span>');
            let nt = obj[k].translate.replace(re, '<span class="text-danger">'+w+'</span>');
            li += '<li class="addwоrd" data-id="'+obj[k].id+'" data-word="'+obj[k].word+'" onclick="addRow(this)"><i class="far fa-plus-square fa-lg"></i> <b id="'+obj[k].id+'">'+nw+'</b> - '+nt+'</li>';
        }

        sr.innerHTML = li;
        sr.style.display = 'block';
    } else {
        sr.innerHTML = '';
        sr.style.display = 'none';
    }
};

searchInput.addEventListener('click', inputEvent, false);
searchInput.addEventListener('input', inputEvent, false);

function formTransfer(act){
    let req = new XMLHttpRequest();req.open('GET','/words/userword/formTransfer/'+act+'/'+group_id,false);req.send(null);
    document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
    fakeSelect('#groupnew');
}

function transferWords(){
    let cb = document.querySelectorAll('input.che:checked');
    let w = '';
    for (let i = 0; i < cb.length; i++) { w+= cb[i].value+','; }
    w = w.slice(0, -1);
    let formData = new FormData(document.getElementById('transferForm'));
    formData.append('move',w);
    let req = new XMLHttpRequest();req.open('POST','/words/userword/transferWords',false);req.send(formData);
    const res = JSON.parse(req.responseText);
    if(res.status){
        if(res.redirect) window.location.href = res.redirect;
        let txt = 'копирования';
        if(formData.get('act') == 'cut'){
            document.querySelectorAll('tbody input.che:checked').forEach(function(c) { c.closest('tr').remove(); });
            txt = 'переноса';
        }
        messBlock('Прогресс '+txt+' завершен, перенесено '+res.status+' записей','success');
        oTable.refresh();
    } else {
        messBlock('Новые записи не добавлены, после удаления копий','warning',3000);
    }
    document.querySelector('#winModal .btn-close').click();
    return false;
}


function deleteMultiple(){
    const formData = new FormData(document.getElementById('formtab'));

    formData.append('_token', csrf_token);
    formData.append('group_id', group_id);
    let req = new XMLHttpRequest();req.open('POST','/words/userword/deleteMultiple',false);req.send(formData);
    if(req.responseText > 0) {
        document.querySelectorAll('tbody input.che:checked').forEach(function(c) { c.closest('tr').remove(); });
        messBlock('Выбранные записи удалены','success');
        oTable.refresh();
    } else {
        messBlock('Ошибка при удалении','danger');
    }
    return false;
}



function setProgressWord(e) {
    let id = e.closest('tr').id;
    let p = Number(e.className.slice(-1));
    let level = (p != 4)?p+1:0;
    fetch('/words/userword/setProgressWord/'+id+'/'+level)
        .then((res) => res.json()).then((response) => {  if(response > 0)  e.className = 'stat l'+level; });
}

function delTranslate(btn){
    let el = btn.closest('.sortable');
    btn.parentNode.remove();
    collectTranslate(el);
}

function collectTranslate(el){
    let id = el.getAttribute('id');
    let arr = Array.from(el.children);
    const res = arr.map(t => {return t.innerText.trim()})
    document.querySelector('.'+id).value = res.join(', ');
}

function showFieldTranslate(btn){
    btn.innerText='введите через запятую';
    btn.className = 'small text-warning'
    let sel = btn.dataset.target;
    document.getElementById(sel).style.display = 'none';
    document.querySelector('.'+sel).style.display = 'block';
}

