const group_id = document.getElementById('group_id').value;
let type = document.getElementById('model').value;

const csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const myOffcanvas = document.getElementById('exerciseModal');
myOffcanvas.addEventListener('show.bs.offcanvas', event => {
    let studymode = document.getElementById('studymode').value;

    let formData = new FormData();
    formData.append('_token', csrf_token);
    formData.append('group_id', group_id);
    formData.append('studymode', studymode);

    const req = new XMLHttpRequest();
    if(type === 'dictonary'){
        const tr = document.querySelectorAll('#oTable tbody tr');
        let w = [];
        tr.forEach(function(elem) { w.push(elem.id); });
        formData.append("words", w.join(','));
        req.open('POST', '/learnword/getWords/words?mode=random', false);
    } else {
        req.open('POST', '/learnword/getWords/'+type+'?mode=random', false);
    }
    //console.log(formData);

    req.send(formData);

    if (req.readyState == 4 && req.status == 200) {
        myOffcanvas.querySelector('.offcanvas-body').innerHTML = req.responseText;
        setTimeout(function() { activateSlider('.slider');},100);
        if(studymode == 'write') {
            setTimeout(function() { document.querySelector('#slider input:first-of-type').focus(); },1000);
        }
    }

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




function translateEdit(e){
    const block = e.parentNode;
    const act = block.className;
    let show = 'save';
    block.style.display = 'none';
    if(act === 'save'){
        show = 'edit';
        block.parentNode.querySelector('.edit span').innerText = block.parentNode.querySelector('input').value;
    } else {
        show = 'save';
    }
    block.parentNode.querySelector('.'+show).style.display = 'flex';
}




function addword(btn){
    const w = (btn.dataset.id) ? btn.dataset.id:btn.dataset.word;
  //console.log(w+' '+type);
    let url = (type === 'dictonary')? '/dictonary/addword/' + w:'/words/addword/'+w+'?type='+type;
    let req = new XMLHttpRequest();req.open('GET',url,false);req.send(null);
    document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
    fakeSelect('#groupnew');
}

function removeWord(btn){
    btn.closest('tr').remove();
    oTable.refresh();
}



