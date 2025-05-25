'use strict';
let setresult;
let userAnswer;
let answerArray;
let slider;
let total = 0;
const btnSkip = document.getElementById("btn-skip");
const activateSlider = function (dataSlider) {
     slider = multiItemSlider(dataSlider);
     total = slider.total();
     userAnswer = [];
     answerArray = [];
     setresult = 0;
};














  function checkWorduu(e){
      btnSkip.classList.add('d-none');
       const item = e.closest('.slider__item');
       let type = item.dataset.type;
       let num = parseInt(item.dataset.num);
       let variant = e.textContent || e.innerText;
      e.style.display = 'none';
      let falseVariant =  item.querySelector('.falseVariant');

      falseVariant.innerText +=' ' +variant;

       let answer = item.querySelector('.trueVariant').textContent;
       let question =  item.querySelector('.question').textContent;
     //
    // if(answer == variant){
    //     e.classList.add('green');
    //     document.getElementById('dot'+num).classList.add('green');
    //     item.querySelectorAll('.variant').forEach(function(v) {  v.removeAttribute('onclick'); });
    //     if(userAnswer[num] != false){
    //         userAnswer[num] = 1;
    //         answerArray[num] = [1,variant,answer,question,type,item.id];
    //         //setBar(num,total);
    //         setexplode(1);
    //     }
    //     nextSlide();
    // } else {
    //     e.classList.add('red');
    //     document.getElementById('dot'+num).classList.add('red');
    //     userAnswer[num] = 0;
    //     answerArray[num] = [0,variant,answer,question,type,item.id];
    //    setexplode(0);
    // }


   }


// Start write
document.getElementById('exerciseModal').addEventListener('input', function(e) {
    //var l = $(this);
    var trying = document.getElementById('trying');
    if(e.target) {
        const item = e.target.closest('.slider__item');
        let falseVariant =  item.querySelector('.falseVariant');
        const type =  item.dataset.type;
        let l = e.target;
        let letter = l.value.toLowerCase();
        falseVariant.innerText +=letter;
        l.value = letter;
        if(letter == l.getAttribute('correct')){
            l.classList.remove('red');
            l.classList.add('green');
            trying.value = 0;
            nextLetter(l);
        } else {
            l.classList.add('red');
            var num = l.parentNode.parentNode.parentNode.getAttribute('data-num');
            setTimeout(function() { l.value = '';},1000);
            let question =  l.parentNode.parentNode.parentNode.querySelector('.question').innerText;
            let trueVariant =  l.parentNode.parentNode.parentNode.querySelector('.trueVariant').innerText;
            if(userAnswer[num] != false){
                userAnswer[num] = 0;
                answerArray[num] = [0,question,falseVariant.innerText,trueVariant,type,item.id];
                setexplode(0);

            } else{

            }
            let ctn = parseInt(trying.value)+1;
            trying.value = ctn;
            if(ctn > 2){
                let newEl = getNewEl(l,l.getAttribute('correct'));
            }
        }

    }

});
function insertLetter(el,letter) {
    let input = el.nextElementSibling;
    input.value = letter;
    input.classList.remove('red');
    input.classList.add('green');
    nextLetter(input);
    document.getElementById('trying').value = 0;
}
function getNewEl(el,letter) {
    el.insertAdjacentHTML("beforeBegin", '<span class="letter-tooltip" onclick="insertLetter(this,\''+letter+'\')">'+letter+'</span>');
    setTimeout(function() { el.previousElementSibling.remove();}, 3500);
}
function nextLetter(l) {


    let total = slider.total();
    l.setAttribute('disabled','disabled');
    const item = l.closest('.slider__item');
    if(l.classList.contains('lastletter')){
        let falseVariant =  item.querySelector('.falseVariant');
        let parent = l.parentNode;
        let type = item.dataset.type;
        let num = parseInt(item.dataset.num);
        if(!parent.nextElementSibling){

            let question =  item.querySelector('.question').innerText;
            let trueVariant =  item.querySelector('.trueVariant').innerText;

            let n = parseInt(num)+1;

            if(userAnswer[num] != false){
                userAnswer[num] = 1;
                answerArray[num] = [1,question,falseVariant.innerText,trueVariant,type,item.id];
                document.getElementById('dot'+num).classList.add('green');
                setexplode(1);
            } else {
                document.getElementById('dot'+num).classList.add('red');
            }



            setBar(num,total);
            nextSlide();



            //alert('следующий сдайд ');
        } else {

            parent.nextElementSibling.firstElementChild.value = '';
            parent.nextElementSibling.firstElementChild.removeAttribute('disabled');
            parent.nextElementSibling.firstElementChild.focus();
        }
    } else {
        l.nextElementSibling.value = '';
        l.nextElementSibling.removeAttribute('disabled');
        l.nextElementSibling.focus();
    }
   // if(total == num){setResult();}
    //console.log(userAnswer);
}
function setFocusInput(){
    if(total > (Number(slider.currentSlide())+1)) {
        setTimeout(function () {
            let item = document.querySelector('#exerciseModal [data-num="' + slider.currentSlide() + '"]') ?? '';
            if (item.dataset.type && item.dataset.type == 'write') {
                item.querySelector('input:not([disabled="disabled"])').focus();
            }
        }, 1000);
    }
}
// End write




function clickLetter(element) {
    btnSkip.classList.add('d-none');
    const item = element.closest('.slider__item');
    let trying = document.getElementById('trying');
    let space = 0;
    let letter = element.getAttribute('class');
    let block = element.parentNode;
    let type = block.parentNode.dataset.type;
    let blockclick = block.getAttribute('data-words');
    let num = block.parentNode.getAttribute('data-num');
    let question =  block.parentNode.querySelector('.question').innerText;
    let trueVariant =  block.parentNode.querySelector('.trueVariant').getAttribute('data-true');
    let falseVariant =  block.parentNode.querySelector('.falseVariant');



    if(blockclick[0] == ' '){ space = 1;}
    if(blockclick[space] == letter){
        //alert('Верно');
        trying.value = 0;
        let badge = (element.lastElementChild)?parseInt(element.lastElementChild.innerHTML):0;
        if(badge >= 2){
            badge = badge - 1;
            if(badge > 1){
                element.lastElementChild.innerHTML = badge;
            } else {
                element.lastElementChild.remove();
            }
        } else {
            element.style.backgroundColor = '#ccc';
            element.removeAttribute('onclick');
        }
        if(space > 0){ letter = ' '+letter;
            block.setAttribute('data-words',blockclick.slice(2));
        } else {
            block.setAttribute('data-words',blockclick.slice(1));
        }
        falseVariant.innerText += letter;
        block.previousElementSibling.insertAdjacentHTML('beforeend', letter);
        block.parentNode.querySelector('.trueVariant').innerText = block.parentNode.querySelector('.trueVariant').innerText;
        if(userAnswer[num] != false){
            userAnswer[num] = 1;
        }
        if(!block.getAttribute('data-words')){
            if(userAnswer[num] !== false && userAnswer[num] !== 0){
                answerArray[num] = [1,question,falseVariant.innerText,trueVariant,type,item.id];
                setexplode(1);
                document.getElementById('dot'+num).classList.add('green');
            } else { // Если есть ошибка, перезапись
                answerArray[num] = [0,question,falseVariant.innerText,trueVariant,type,item.id];
            }
            nextSlide();

        }
    } else {
        //alert('не верно!');
        element.style.backgroundColor = 'red';
        document.getElementById('dot'+num).classList.add('red');
        setTimeout(function () {element.style.backgroundColor = '';}, 500);
        let ctn = parseInt(trying.value)+1;
        trying.value = ctn;
        if(ctn > 2){ block.querySelector('.'+blockclick[space]).style.backgroundColor = 'green'; }
        setTimeout(function() { block.querySelector('.'+blockclick[space]).style.backgroundColor = '';}, 500);
       // console.log(ctn);
        if(ctn == 1) falseVariant.innerText += letter;
        userAnswer[num] = 0;
        answerArray[num] = [0,question,'',trueVariant,type,item.id];
        setexplode(0);

    }
  //  console.log(answerArray);
}


function misSlide() {
     const dots = document.querySelectorAll(".dot");
     const cur = slider.currentSlide();
     const c = dots.length
    for (let i = 0; i < c; i++) {
        if(dots[i].className == 'dot' && cur != i){  return i; }
    }
    return 'не найдено';
}


function nextSlide() {
    setBar();
    const cur = document.getElementById("dot"+(slider.currentSlide()));
      setFocusInput();

    if(cur.nextElementSibling != null && cur.nextElementSibling.className == 'dot'){
      slider.right();
    } else {
        const mis = misSlide();
              if(mis >= 0){
                  slider.toSlide(mis);
                  setFocusInput();
              } else {
                  slider.toSlide(total);
                  setResult();
              }
    }
}

function unKnown(){
    const num = Number(slider.currentSlide());
    document.getElementById('dot'+num).classList.add('yellow');
    let item = document.querySelector('.slider__item:nth-child('+(num+1)+')');
    let type = item.dataset.type;
    let answer = item.querySelector('.trueVariant').textContent;
    let question = item.querySelector('.question').textContent;
    let variant = question+' <small class="text-warning">не знаю</small>';
    let progress = item.querySelector('.stat');
    let start = Number(progress.dataset.start);
    if(start != 0) progress.className = 'stat l'+(start-1);
  // console.log(item);
    userAnswer[num] = 0;
    answerArray[num] = [0,variant,answer,question,type,item.id];
    nextSlide();
    //userAnswer[num] = 0;
}


function setResult() {
    setresult++;
    let str = '';
    let answerTrue = 0;
    let answerFalse = 0;
    let model = document.getElementById('model').value;
    let type = '';
    let myanwers = [];
    let wordphrase = [];
    answerArray.flat();
    userAnswer.flat();
   // console.log(userAnswer);
    console.log(answerArray);
    for (let i = 0; i <= total; i++) {
        if(userAnswer[i] == 1){
            answerTrue += 1;
        } else if(userAnswer[i] == 0) {
            answerFalse += 1;
        }
        //console.log(answerArray);
        if(typeof answerArray[i] !== "undefined"){
            type = answerArray[i][4];
            switch (type) {
                case 'translate':
                    if(answerArray[i][0] == 0){
                        str += '<div class="border-bottom p-3"><small class="text-muted fw-bold">'+(i+1)+'</small><div class="checkquestion red"> '+answerArray[i][3]+'</div><div class="through"><span>'+answerArray[i][1]+'</span></div><div class="green">'+answerArray[i][2]+'</div></div>';
                    } else {
                        str += '<div class="border-bottom p-3"><small class="text-muted fw-bold">'+(i+1)+'</small><div class="checkquestion green"> '+answerArray[i][3]+'</div><div class="green">'+answerArray[i][2]+'</div></div>';
                    }
                    wordphrase[i] = answerArray[i][3];
                    break;
                case 'reverse':
                    if(answerArray[i][0] == 0){
                        str += '<hr><small>'+(i+1)+'</small><div class="checkquestion red"> '+answerArray[i][3]+'</div><div class="through"><span>'+answerArray[i][1]+'</span></div><div class="green">'+answerArray[i][2]+'</div>';
                    } else {
                        str += '<hr><small>'+(i+1)+'</small><div class="checkquestion green"> '+answerArray[i][3]+'</div><div class="green">'+answerArray[i][2]+'</div>';
                    }
                    wordphrase[i] = answerArray[i][2];
                    break;
                case 'asembler':
                    if(answerArray[i][0] == 0){
                        str += '<hr><small>'+(i+1)+'</small><div class="checkquestion red"> '+answerArray[i][1]+'</div><div class="through"><span>'+answerArray[i][2]+'</span></div><div class="green">'+answerArray[i][3]+'</div>';
                    } else {
                        str += '<hr><small>'+(i+1)+'</small><div class="checkquestion green"> '+answerArray[i][1]+'</div><div class="green">'+answerArray[i][2]+'</div>';
                    }
                    wordphrase[i] = answerArray[i][2];
                    break;
                case 'write':
                    if(answerArray[i][0] == 0){
                        str += '<hr><small>'+(i+1)+'</small><div class="checkquestion red"> '+answerArray[i][1]+'</div><div class="through"><span>'+answerArray[i][2]+'</span></div><div class="green">'+answerArray[i][3]+'</div>';
                    } else {
                        str += '<hr><small>'+(i+1)+'</small><div class="checkquestion green"> '+answerArray[i][1]+'</div><div class="green">'+answerArray[i][2]+'</div>';
                    }
                    wordphrase[i] = answerArray[i][2];
                    break;
                case 'sprint':
                    if(answerArray[i][0] == 0){
                        str += '<hr><small>'+(i+1)+'</small><div class="checkquestion red"> '+answerArray[i][1]+'</div><div class="through"><span>'+answerArray[i][2]+'</span></div><div class="green">'+answerArray[i][3]+'</div>';
                    } else {
                        str += '<hr><small>'+(i+1)+'</small><div class="checkquestion green"> '+answerArray[i][1]+'</div><div class="green">'+answerArray[i][2]+'</div>';
                    }
                    wordphrase[i] = answerArray[i][1];
                    break;
                default:
                    alert( type+" Нет такого значения" );
            }
            myanwers.push(answerArray[i][5]+'-'+answerArray[i][0]+'-'+wordphrase[i]);
        }

    }




    document.getElementById("myscore").innerHTML = (Number(document.getElementById("myscore").innerText)+answerTrue);

    document.getElementById("showAnswers").innerHTML = str;
    document.getElementById("answerTrue").innerHTML = answerTrue;
    document.getElementById("answerFalse").innerHTML = answerFalse;
    document.getElementById("answerTotal").innerHTML = total;
    document.querySelector(".slider__control_left").classList.remove('d-none');
    document.querySelector(".slider__control_right").classList.remove('d-none');
    document.getElementById("btn-close").classList.remove('d-none');
    document.getElementById("btn-unknown").style.display = 'none';
    btnSkip.style.display = 'none';
    let formData = new FormData();
    formData.append("score", answerTrue);
    formData.append("myanwers", myanwers.join(','));
    formData.append("_token", csrf_token);
    formData.append("group_id", group_id);
    formData.append("model", model);
    console.log(formData);
    fetch('/learnword/saveUserStatistics', {
        method: "POST",
        body: formData
    }).then((res) => res.json())
        .then((response) => {
            if(response)  { console.log(response);
                if(response !== 'withoutrogress'){
                document.querySelectorAll('#oTable tbody tr').forEach(function (tr, i){
                 if(tr.id in response){
                      tr.querySelector('.stat').className = 'stat l'+response[tr.id];
                  }
                });
              //  messBlock('Прогресс изучения обновлён','success');
                }
            } else{
                messBlock('Что то пошло не так','error')
            }
        });
    let bar = 100 / total * answerTrue;
    changeCircleRating(bar);
}






function changeCircleRating(v) {
    var val = parseInt((v.value > 0)?v.value:v);
    var circle = document.getElementById("bar");
    if (isNaN(val)) {
        val = 0;
    } else {
        var r = circle.getAttribute('r');
        var c = Math.PI*(r*2);
        if (val < 0) { val = 0;}
        if (val > 100) { val = 100;}
        var pct = ((100-val)/100)*c;
        circle.style.strokeDashoffset=pct;
        document.getElementById("cont").setAttribute('data-pct',val);
    }
}



function setBar() {
    // console.log(total +' '+ userAnswer.flat().length);
    btnSkip.classList.remove('d-none');
      let bar = 100 / total * userAnswer.flat().length;
      let elem = document.getElementById("myBar");
      let width = parseInt(elem.textContent || elem.innerText);
      const id = setInterval(frame, 24);
      function frame() {
          if (width >= bar) {
              clearInterval(id);
          } else {
              width++;
              elem.style.width = width + '%';
              elem.innerHTML = width * 1 + '%';
          }
      }
  }






  function setexplode(num) {
      let color = ''
      if(num == 1){
           color = 'green';
      } else if(num == 0) {
           color = 'red';
      } else {
           color = 'yellow';
      }
      const colors = [color];
      const bubbles = 15;
      const explode = (x, y) => {
          let particles = [];
          let ratio = window.devicePixelRatio;
          let c = document.createElement('canvas');
          let ctx = c.getContext('2d');
          c.style.position = 'absolute';
          c.style.left =  x+'px';
          c.style.top = y+'px';
          c.style.pointerEvents = 'none';
          c.style.width = 200 + 'px';
          c.style.height = 200 + 'px';
          c.style.zIndex = 100;
          c.width = 450 * ratio;
          c.height = 450 * ratio;
          document.getElementById("score").appendChild(c);

          for(let i = 0; i < bubbles; i++) {
              particles.push({
                  x: c.width / 2,
                  y: c.height / 2,
                  radius: r(20, 30),
                  color: colors[Math.floor(Math.random() * colors.length)],
                  rotation: r(0, 360, true),
                  speed: r(8, 12),
                  friction: 0.9,
                  opacity: r(0, 0.5, true),
                  yVel: 0,
                  gravity: 0.1
              });
          }

          render(particles, ctx, c.width, c.height);
      }

      const render = (particles, ctx, width, height) => {
          requestAnimationFrame(() => render(particles, ctx, width, height));
          ctx.clearRect(0, 0, width, height);
          particles.forEach((p, i) => {
              p.x += p.speed * Math.cos(p.rotation * Math.PI / 180);
              p.y += p.speed * Math.sin(p.rotation * Math.PI / 180);
              p.opacity -= 0.01;
              p.speed *= p.friction;
              p.radius *= p.friction;
              p.yVel += p.gravity;
              p.y += p.yVel;
              if(p.opacity < 0 || p.radius < 0) return;
              ctx.beginPath();
              ctx.globalAlpha = p.opacity;
              ctx.fillStyle = p.color;
              ctx.arc(p.x, p.y, p.radius, 0, 2 * Math.PI, false);
              ctx.fill();
          });
          return ctx;
      };

      const r = (a, b, c) => parseFloat((Math.random() * ((a ? a : 1) - (b ? b : 0)) + (b ? b : 0)).toFixed(c ? c : 0));

      const cur = Number(slider.currentSlide());
      let progress = document.querySelector('.slider__item:nth-child('+(cur+1)+') .stat');
      let start = Number(progress.dataset.start);


      let n = userAnswer.flat().reduce((a, b) => a + b, 0);
      let score = document.getElementById("score");
      if(num == 1){
          score.innerText = n;
          score.insertAdjacentHTML('beforeend', '<small class="ascent '+color+'">+1</small>');
         // progress.insertAdjacentHTML('beforeend', '<small class="ascent '+color+'">+1</small>');
          setTimeout(function(){ score.innerText = n;}, 1000);
          score.classList.add('green');
          score.classList.remove('red');
           if(start != 4) progress.className = 'stat l'+(start+1);
      } else {
          score.innerText = n;
          score.classList.add('red');
          score.classList.remove('green');
          if(start != 0) progress.className = 'stat l'+(start-1);
      }
      explode(-95, -95);

  }



   function showDetail() {
      let btn = document.getElementById('showdetail');
       btn.style.left = (btn.style.left == '-100%')?'0':'-100%';
   }
