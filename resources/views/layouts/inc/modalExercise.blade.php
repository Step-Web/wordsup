<div class="offcanvas offcanvas-bottom" style="height: 100%" tabindex="-1" id="exerciseModal">
        <input type="hidden" id="learntype" value="learn{{$type}}">
    <div class="offcanvas-header">
        <div class="score"><small>Баллы:</small> <span class="score-num"><b id="score">0</b></span></div>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Закрыть"></button>
    </div>
    <div class="offcanvas-body"></div>
    <div class="offcanvas-footer justify-content-between">
        <div><span id="btn-unknown" class="btn btn-primary" onclick="unKnown()"><i class="fas fa-question-circle"></i> Не знаю</span> <button id="btn-close" type="button" class="btn btn-danger d-none" data-bs-dismiss="offcanvas"><i class="fas fa-times-circle"></i> Закрыть</button></div>
        <div id="btn-skip" class="btn btn-info" onclick="nextSlide()"><i class="fas fa-history"></i> Отвечу позже</div>
    </div>
    <link rel="stylesheet" type="text/css" href="/assets/css/exercise.css">
    <script src="/assets/js/slider.js"></script>
    <script src="/assets/js/exercise.js"></script>
</div>
