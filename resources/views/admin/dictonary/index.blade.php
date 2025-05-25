@extends('admin.layouts.app')

@section('content')
    <!-- В шапке -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- В конце тела -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <div class="container">
    <h3 class="mt-3 fw-bold">Слова <span class="text-uppercase">{{$lang}}</span></h3>

    <div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="table" data-page-length="500">
        <thead>
        <tr>
            <th></th>
            <th>ID</th>
            <th>Слово</th>
            <th>ts</th>
            <th>перевод</th>
            <th>фраз</th>
            <th>гр.</th>
            <th>тест</th>
            <th>пуб.</th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>

        <input id="tab" type="hidden" value="{{$table}}">
        <div class="text-center mt-4 mb-4"><span class="btn btn-dark" onclick="setWordSentences()">Пересчитать фразы в словах <small>(на странице)</small></span></div>
    </div>
    <div class="modal fade" id="wordModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="ajaxForm"></div>
        </div>
    </div>
    <style>
        table td .status1 {
            background: #1b1b1b;
        }
        table td .setflag {
            width: 1em;
            height: 1em;
            display: block;
            border-radius: 50%;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid #1b1b1b;
            text-indent: 9em;
            white-space: nowrap;
            margin: 0.5em auto 1em;
        }

    </style>

    <script src="/assets/js/sortable.js"></script>

    <script>

        $(function() {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('words.processing',['en']) }}',
                order: [[ 0, "desc" ]],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                columns: [
                    { data: 'freq', name: 'freq' },
                    { data: 'id', name: 'id' },
                    { data: 'word', name: 'word' },
                    { data: 'ts', name: 'ts' },
                    { data: 'translate', name: 'translate' },
                    { data: 'sentences', name: 'sentences' },
                    { data: 'wgroup', name: 'wgroup' },
                    { data: 'is_test', name: 'is_test' },
                    { data: 'is_public', name: 'is_public' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'del', name: 'del', orderable: false, searchable: false }
                ]
            });
        });

  function setWordSentences(){
      messBlock('Подождите идет расчёт','danger');
      setTimeout(function(){
          const tr = document.querySelectorAll('tbody tr');
          let w = [];
          tr.forEach(function(elem) { w.push(elem.id); });

      const req = new XMLHttpRequest();
      req.open('GET', '/admin/setWordSentences/en?ids='+w.join(','), false);req.send(null);


      if (req.readyState == 4 && req.status == 200) {
          messBlock('Расчёт окончен','success',10000);

      } else {
          messBlock('Расчёт окончен','danger');
      }
      },1000);

  }




        var wordModal = document.getElementById('wordModal')
        wordModal.addEventListener('show.bs.modal', function (event) {
            // Button that triggered the modal
            const btn = event.relatedTarget
            let id = btn.dataset.id;
            let act = btn.dataset.act;
            let html = '';


    if(act == 'edit') {
        fetch('/admin/dictonary/'+id+'/edit')
            .then(response => response.text())
            .then(function(res) {
                document.getElementById('ajaxForm').innerHTML = res;
                setTimeout(function(){
                    var sorting = document.getElementById('wintanslate');
                    new Sortable(sorting, {
                        animation: 150,
                        ghostClass: 'blue-background-class',
                        onEnd: function (evt) { collectTranslate(sorting) }
                    });
                },1000);
            });


           }

        })

        function saveWord(form){
            const url = form.getAttribute('action');
            const formData = new FormData(form);
            let req = new XMLHttpRequest(); req.open("POST", url,false);req.send(formData);
            console.log(req);
            if(req.responseText){
                let tr = document.getElementById(formData.get('id'));
                tr.querySelector('.word').textContent = formData.get('word');
                tr.querySelector('.ts').textContent = formData.get('ts');
                tr.querySelector('.translate').textContent = formData.get('translate');
                tr.querySelector('.wgroup').textContent = formData.get('wgroup');
                form.querySelector('button[data-bs-dismiss="modal"]').click();
                messBlock('Слово обновлено','success',2000);
            } else {
                document.querySelector('#winModal .modal-body').innerHTML = 'Что то пошло не так';
            }
            return false;

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





        function setFlag(el){
            let url = el.dataset.url;
            let flag = (Number(el.innerText) == 1)?0:1;
            fetch('/admin/setflag/'+url+'/'+flag)
               .then(response => response.text())
                .then(function(res) {
                   if(res > 0){
                    el.className = 'setflag status'+flag;
                    el.innerText = flag;
                       messBlock('Статус был успешно обновлён','success')
                   }
                })
                .catch(error =>  messBlock(error,'danger'));
        }
    function delItem(form){
            let sistem = form.dataset.sistem;
            if(sistem > 0) { messBlock('Это системная страница её нельзя удалить'); return false}
        let tr = form.closest('tr');
        if(confirm('Вы действительно хотите удалить страницу?') == true){
            return true;
        }
        return false;
    }
    </script>
    @if(session()->has('status'))
        <script>
            window.onload = function() {
            let mes = "{{ session()->get('status') }}";
            messBlock(mes,'success');
            };
        </script>
    @endif
@endsection
