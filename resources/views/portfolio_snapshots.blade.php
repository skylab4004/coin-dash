<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Coin Dash</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes"></script>
    <script src="https://www.chartjs.org/samples/latest/utils.js"></script>
</head>
<body>

<div class="wrapper">
    {{--    <div class="chartjs-size-monitor">--}}
    {{--        <div class="chartjs-size-monitor-expand">--}}
    {{--            <div class=""></div>--}}
    {{--        </div>--}}
    {{--        <div class="chartjs-size-monitor-shrink">--}}
    {{--            <div class=""></div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <canvas id="chart-0" width="1448" height="800" class="chartjs-render-monitor"
            style="display: block; height: 400px; width: 724px;">

    </canvas>
</div>


<script>

    var presets = window.chartColors;
    var utils = Samples.utils;
    var inputs = {
        min: 20,
        max: 80,
        count: 8,
        decimals: 2,
        continuity: 1
    };

    function generateData() {
        return utils.numbers(inputs);
    }

    function generateLabels() {
        return utils.months({count: inputs.count});
    }

    utils.srand(42);

    var data = {
        labels: generateLabels(), // snapshot_times

        datasets: [{
                // coin
                data: generateData(), // asset values
                label: 'D0'
            }, {
                data: generateData(),
                label: 'D1',
            }, {
                data: generateData(),
                label: 'D2',
            }, {
                data: generateData(),
                label: 'D3',
            }, {
                data: generateData(),
                label: 'D4',
            }, {
                data: generateData(),
                label: 'D5',
            }, {
                data: generateData(),
                label: 'D6',
            }, {
                data: generateData(),
                label: 'D7',
            }, {
                data: generateData(),
                label: 'D8',
            }]
    };

    var options = {
        maintainAspectRatio: false,
        spanGaps: false,
        elements: {
            line: {
                tension: 0.100001
            }
        },
        scales: {
            yAxes: [{
                stacked: true
            }]
        },
        plugins: {
            filler: {
                propagate: false
            },
            'samples-filler-analyser': {
                target: 'chart-analyser'
            },
            colorschemes: {
                scheme: 'tableau.JewelBright9'
            }
        }
    };

    var chart = new Chart('chart-0', {
        type: 'line',
        data: data,
        options: options
    });

    // eslint-disable-next-line no-unused-vars
    function togglePropagate(btn) {
        chart.options.plugins.filler.propagate = btn.classList.toggle('btn-on');
        chart.update();
    }

    // eslint-disable-next-line no-unused-vars
    function toggleSmooth(btn) {
        var value = btn.classList.toggle('btn-on');
        chart.options.elements.line.tension = value ? 0.4 : 0.000001;
        chart.update();
    }

    // eslint-disable-next-line no-unused-vars
    function randomize() {
        chart.data.datasets.forEach(function (dataset) {
            dataset.data = generateData();
        });
        chart.update();
    }

</script>
</body>
</html>
