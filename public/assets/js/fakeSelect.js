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


