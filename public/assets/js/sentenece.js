document.getElementById('search').addEventListener("submit", function (e) {
    e.preventDefault();
    let word =  document.querySelector('[name="word"]').value;
    word = word.trim().replace( /\s\s+/g, ' ');
    word = word.replace( /\s+/g, '_');
    document.location.href='/sentence/word/'+word;
});
let inputEvent = function (e) {
    let w = this.value;
    if(this.value.length >= 2){
        let req = new XMLHttpRequest();req.open('GET','/dictonary/search?word='+w+'&type=sentences',false);req.send(null);
        let obj = JSON.parse(req.responseText);
        let li = '';
        for (const k of Object.keys(obj)) {
            let re = new RegExp(w,"g"); // search for all instances
            let nw = obj[k].word.replace(re, '<span class="text-danger">'+w+'</span>');
            let nt = obj[k].translate.replace(re, '<span class="text-danger">'+w+'</span>');
            li += '<li class="addwоrd" data-id="'+obj[k].id+'" data-word="'+obj[k].word+'"> <span onclick="selectWоrd(this)" data-word="'+obj[k].word+'"><i class="far fa-plus-square fa-lg"></i> <b id="'+obj[k].id+'">'+nw+'</b> - '+nt+'</span> <small>'+obj[k].sentences+' фраз</small></li>';
        }

        sr.innerHTML = li;
        sr.style.display = 'block';
    } else {
        sr.innerHTML = '';
        sr.style.display = 'none';
    }
};
const sr = document.getElementById('search_result');
let searchInput = document.getElementById('add_words');
searchInput.addEventListener('click', inputEvent, false);
searchInput.addEventListener('input', inputEvent, false);

function selectWоrd(e){
    let word = e.getAttribute('data-word');
    document.getElementById('add_words').value = word;
    sr.style.display = 'none';
    searchInput.focus();
}

function addPhrase(btn){
    let id = btn.dataset.id;
    const formData = new FormData();
    fetch('/phrases/addphrase/' + id)
        .then((res) => res.text())
        .then((response) => {
            document.querySelector('#winModal .modal-content').innerHTML = response;
            fakeSelect('#groupnew');
        });
}

function translateEdit(e){
    const block = e.parentNode;
    const act = block.className;
    let show;
    block.style.display = 'none';
    if(act === 'save'){
        show = 'edit';
        block.parentNode.querySelector('.edit span').innerText = block.parentNode.querySelector('input').value;
    } else {
        show = 'save';
    }
    block.parentNode.querySelector('.'+show).style.display = 'flex';
}

function insertPhrase(form){
    const formData = new FormData(form);
    let mess = form.querySelector('.mess');
    fetch(form.action, {
        method: "POST",
        body: formData
    })
        .then((response) => response.json())
        .then((res) => {
            if (res.error) { mess.innerText = res.error;  mess.className = 'text-danger'; return false}
            if(res.id){
                mess.innerText = 'фраза была добавлена';  mess.className = 'text-success';
                setTimeout(() => {
                    if(document.getElementById('redictgroup').checked){  window.location.href = '/phrases/group/'+document.getElementById('groupnew').value;  }
                    form.querySelector('.btn-close').click();
                }, 1000);
            }
        });
    return false;
}
