@extends('layouts.app')
@include('layouts.inc.meta',['title'=>'Статистика','index'=>'noindex'])
@section('content')
<div class="container">
    <div class="block pt-1">
        <div class="title">
            <h1>Статистика за <b class="red" id="year"></b> год</h1>
            <p style="font-size:1em;margin-top: -10px;">
                <span class="text-muted">За <b id="totaldays">всё время</b> изучения вы получили: <b id="totalscore">0 баллов</b></span>
            </p>
        </div>

        <div id="btnYears">
            @foreach($years AS $y)
            <button type="button" class="btn btn-sm btn-default {{($y == $year)?'active btn-info':'btn-primary'}}" onclick="showChart(this)">{{$y}}</button>
            @endforeach
        </div>


        <div id="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</div>
<script>
    function endWord(number, words) {return words[(number % 100 > 4 && number % 100 < 20) ? 2 : [2, 0, 1, 1, 1, 2][(number % 10 < 5) ? Math.abs(number) % 10 : 5]]; }

    window.onload = function() {
        const config =  {
            type: 'bar',
            data: {
                datasets: [{
                    label: 'Заработанные балы',
                    backgroundColor: '#0b2242',
                    borderColor: '#0b2242',
                    hoverBackgroundColor: '#0c70e2',
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 90,
                            minRotation: 0
                        }
                    }
                }
            }
        };
        const ctx = document.getElementById('myChart').getContext('2d');
        window.myChart = new Chart(ctx, config);
        var items = document.querySelectorAll('#btnYears button');
        items[items.length-1].click();
    };



    function showChart(btn) {
        const y = btn.innerText;
        const buttons = document.querySelectorAll("#btnYears > button");
        for (let i=0, max=buttons.length; i < max; i++) {  buttons[i].classList.remove('active');}
        btn.classList.add('active');
        fetch('/user/statistic/{{$user_id}}?act=get&year='+y)
            .then(res => res.text())
            .then(function (data) {
                const arr = JSON.parse(data);
                data = arr.chart;
                let added = [];
                let score = [];
                for (let i in data) {
                    added.push(data[i].added);
                    score.push(data[i].score);
                }
                let txtscore = (Number(arr.score) < 0)? arr.score * -1:arr.score;
                document.getElementById('year').innerText = y;
                document.getElementById('totaldays').innerText = data.length +' '+ endWord(data.length,['день','дня','дней']);
                document.getElementById('totalscore').innerText = arr.score +' '+ endWord(txtscore,['балл','балла','баллов']);
                window.myChart.data.labels = added;
                window.myChart.data.datasets[0].data = score;
                window.myChart.update();
            });
    }
</script>
@endsection
