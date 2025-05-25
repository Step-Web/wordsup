@extends('layouts.app')
@include('layouts.inc.meta',['title'=>$page->mtitle,'mdesc'=>$page->mdesc,'mkey'=>$page->mkey])
@include('layouts.inc.breadcrumbs')
@section('content')
    <div class="container">
    <article>
   <div class="block"> <div class="title"><h1>{{$page->mtitle}}</h1></div>
       @php echo $page->content @endphp
    </article>
    </div>
    <div id="translate-panel"class="modal-up-down">
        <div class="container"><div class="close" onclick="closeTranslate()">×</div>
            <div id="infoWord"></div>
        </div>
    </div>
    @include('layouts.inc.modal',['id'=>'winModal'])

    <script>
        function closeTranslate() {
            document.querySelector('.modal-up-down').style.bottom = '-100%';
            document.getElementById('infoWord').innerText = '';
        }
        let lastPos = 0;
        window.addEventListener('scroll',() => {
            var res = window.pageYOffset < lastPos ? '' : closeTranslate();
            lastPos = window.pageYOffset+10;
        });


        function getTranslate(w) {
            document.querySelector('.modal-up-down').style.bottom = 0;
            var req = new XMLHttpRequest();
            req.open('GET', '/dictonary/translate/?word='+w, false);
            req.send(null);
            document.getElementById('infoWord').innerHTML = req.responseText;


        }

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
            const w = btn.dataset.word;
            let req = new XMLHttpRequest();req.open('GET','/dictonary/addword/'+w,false);req.send(null);
            document.querySelector('#winModal .modal-content').innerHTML = req.responseText;
            fakeSelect('#groupnew');
        }


        function addWordHandlers(node) {
            var FORBIDDEN_TAGS = { A: 0, BUTTON: 0 };
            // var txt = $('#content').text();
            // alert(txt);
            //console.log(txt);
            if (node.nodeType == 3) {
                var oldContent = node.nodeValue;
                var newContent = oldContent.replace(/[a-zA-Z][a-zA-Z-’']*/g, function (m) {
                    return '<span class="helper-word">' + m.replace(/'/g, '’') + '</span>';
                })
                if (oldContent != newContent) {
                    var parent = node.parentNode;
                    var container = document.createElement('span');
                    container.innerHTML = newContent;
                    var childs = container.childNodes;
                    for (var anchor = node.nextSibling; childs.length > 0; ) {
                        var newNode = childs[childs.length - 1];
                        parent.insertBefore(newNode, anchor);
                        anchor = newNode;
                    }
                    parent.removeChild(node);
                }
            } else if (FORBIDDEN_TAGS[node.tagName] === undefined) {
                var childs = Array.prototype.slice.call(node.childNodes);
                for (var i = 0; i < childs.length; i++)
                    addWordHandlers(childs[i]);
            }

        }

        window.addContextWordHelp = function (elements) {

            // elements.each(function () {
            addWordHandlers(document.querySelector('article'));
            // });


            var hw = document.querySelectorAll(".helper-word");
            for (var i = 0; i < hw.length; i++) {
                hw[i].addEventListener('click', function(){
                    var w = this.innerText;
                    getTranslate(w);
                });
            }

        };

        addContextWordHelp(document.querySelector('article'));

    </script>
    <style>

        #translate-panel .close{ color: #dc3545; background-color: rgba(30,36,55,0.98); font-size: 3em; cursor: pointer; position: absolute; right: 0; top:-1em; line-height: 0.5em;  height: 1em;}

        .modal-up-down {
            background: #fff;
            width: 100%;
            height: auto;
            margin: 0;
            padding: 0;
            transition: all 600ms cubic-bezier(0.86, 0, 0.07, 1);
            bottom: -100%;
            position: fixed;
            left: 0;
            text-align: left;
            z-index: 9;
        }

        #translate-panel{ width: 100%; border-top:1px solid #eee; font-size: 1.5em;
            background-color: rgba(30,36,55,0.98); color: #b2bad5;
        }
        #translate-panel .container{ position: relative}
        .text-desc{ font-size: .6em; color: #3256b2 }
        .modal-open .modal-up-down{
            bottom: 0!important;
        }
        #translate-word{ font-weight: bold; text-transform: uppercase; color: #fff; font-size:1.4em; line-height: 1;margin-left: 0; margin-right: 0.2em }
        .wordblock .audio-icon{ margin-right: 0.2em}
        #translate-content{ color: #0c70e2;}
        .panel-word{ display: flex; flex-wrap: wrap; align-items: center; margin-top: 1em; margin-bottom: 0.8em; }
        .panel-word span:last-child{ display: inline-block; margin-left: 0.5em}



    </style>
@endsection
