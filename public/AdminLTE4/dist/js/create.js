function makeUrl(btn) {
    let ext = btn.dataset.ext;
    let title  = document.getElementById('title');
    let url  = document.getElementById('url');
    if(url.dataset.sistem > 0) {  messBlock('Данная страница является системной и её адрес нельзя изменить','danger');  return false;}
    if(url.hasAttribute('disabled')){
      url.removeAttribute('disabled');
      btn.innerHTML = '<i class="fas fa-link"></i>';
      messBlock('Учтите тот факт, что поисковая система уже могла проиндексировать данный адрес.','warning',3000);
      return false;
     }
    var from = title.value;
    if(from === ''){ messBlock('Сначала введите Название, именно из него будет сформирован URL адрес страницы.'); }
    else {
        url.value = RustoEn(from.trim(),0,ext);
    }
}
function RustoEn(w,v,ext) {
    w = w.replace(/\s{2,}/g, ' ');
    var tr='a b v g d e ["zh","j"] z i y k l m n o p r s t u f h c ch sh ["shh","shch"] ~ y ~ e yu ya ~ ["jo","e"]'.split(' ');
    var ww=''; w=w.toLowerCase().replace(/ /g,'-');
    for(i=0; i<w.length; ++i) {
        cc=w.charCodeAt(i); ch=(cc>=1072?tr[cc-1072]:w[i]);
        if(ch.length<3) ww+=ch; else ww+=eval(ch)[v];
    }
    var url = (ext) ? ww.replace(/~/g,'')+ext:ww.replace(/~/g,'');
    return url;
}
