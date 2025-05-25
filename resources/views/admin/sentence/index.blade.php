@extends('admin.layouts.app')

@section('content')
    <!-- В шапке -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <!-- В конце тела -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <div class="container">
        <div class="row">
        <div class="col-sm-8"><h3 class="mt-3 fw-bold">Слова <span class="text-uppercase">{{$lang}}</span></h3></div>
        <div class="col-sm-4"><p class="text-end mt-3"><a href="#" class="btn btn-sm btn-dark" data-act="add" data-bs-toggle="modal" data-bs-target="#phraseModal">Добавить</a></p></div>
    </div>
    <div class="table-responsive">
    <table class="table table-striped table-hover table-bordered" id="table" data-page-length="500">
        <thead>
        <tr>

            <th>ID</th>
            <th>фраза</th>
            <th>перевод</th>
            <th>сим</th>
            <th>tID</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody></tbody>
    </table>
    </div>

        <input id="tab" type="hidden" value="{{$table}}">
        <p class="text-end mt-3"><a href="#" class="btn btn-sm btn-dark" data-act="add" data-bs-toggle="modal" data-bs-target="#phraseModal">Добавить</a></p>
    </div>
    <div class="modal fade" id="phraseModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="ajaxForm"></div>
        </div>
    </div>
    <audio id="audio"></audio>
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



    <script>



        $(function() {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('sentence.processing',[$lang]) }}',
                order: [[ 0, "desc" ]],
                language: {
                    processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'phrase', name: 'phrase' },
                    { data: 'translate', name: 'translate' },
                    { data: 'qty', name: 'qty' },
                    { data: 'tID', name: 'tID' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                    { data: 'calc', name: 'calc', orderable: false, searchable: false },
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

        function createAudioPhrase(btn) {
            let pathfile = document.getElementById('pathfile').value;
            let phrase = document.getElementById('phrase').value;
            const req = new XMLHttpRequest();
            req.open('GET', '/admin/createAudioPhrase/?pathfile='+pathfile+'&phrase='+phrase, false);req.send(null);
            console.log(req);
            if (req.readyState == 4 && req.status == 200) {
                messBlock('Файл был создан <a href="'+req.responseText+'">прослушать</a>','success',5000);
            } else {
                messBlock('Расчёт окончен','danger');
            }

        }



        var phraseModal = document.getElementById('phraseModal')
        phraseModal.addEventListener('show.bs.modal', function (event) {
            const btn = event.relatedTarget
            let id = btn.dataset.id;
            let url = (btn.dataset.act == 'edit')? '/admin/sentence/'+id+'/edit':'/admin/sentence/create';
            fetch(url)
                .then(response => response.text())
                .then(function(res) {
                    document.getElementById('ajaxForm').innerHTML = res;
                });
        })

        function savePhrase(form){
            const url = form.getAttribute('action');
            const formData = new FormData(form);
           let req = new XMLHttpRequest(); req.open("POST", url,false);req.send(formData);
           if(req.responseText){
               let tr = document.getElementById(formData.get('id'));
               tr.querySelector('.phrase').textContent = formData.get('phrase');
               tr.querySelector('.translate').textContent = formData.get('translate');
               tr.querySelector('.tID').textContent = formData.get('tID');
               tr.querySelector('.qty').textContent = formData.get('phrase').length;
               form.querySelector('button[data-bs-dismiss="modal"]').click();
               messBlock('Фраза обновлено','success',2000);
           } else {
               document.querySelector('#winModal .modal-body').innerHTML = 'Что то пошло не так';
           }
            return false;

        }

        function calcWords(btn){
      let phrase = btn.closest('tr').querySelector('.phrase').innerText;
            let req = new XMLHttpRequest(); req.open("GET", '/admin/setWordSentences/en?phrase='+phrase,false);req.send(null);
            console.log(req.responseText);
            if(req.responseText){
           alert(phrase);
            }
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
