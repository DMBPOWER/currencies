<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Currencies</title>

    <!-- Styles -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>

    </style>
</head>
<body>

<main role="main">
    <section class="jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">Currencies</h1>

            {{ Form::open(array('url' => '/', 'method' => 'get')) }}
                <div class="currency-form">
                    <div class="btn-group">
                        <select name="history" id="" class="form-control" style="margin: 20px 0;">
                            <option class="dropdown-item" value="2">Choose history</option>
                            <option class="dropdown-item" value="4">5 days</option>
                            <option class="dropdown-item" value="6">7 days</option>
                            <option class="dropdown-item" value="all">For the all time</option>
                        </select>
                    </div>
                </div>

                <div class="currency-types">
                    {{-- foreach for all currencies what you have passed --}}
                    @foreach ($currencies as $currency)
                        <label for="{{ $currency['name'] }}" style="width: 60px;">
                            <input id="{{ $currency['name'] }}" type="radio" class="form-check-input" name="currency" value="{{ $currency['name'] }}">

                            {{ $currency['name'] }}
                        </label>
                    @endforeach
                </div>

                {{ Form::submit('Get history', ['class' => 'btn btn-success ml-2 mr-2 ']) }}

            {{ Form::close() }}

            <canvas id="myChart"></canvas>
        </div>
    </section>
</main>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<script>
    let ctx = document.getElementById('myChart').getContext('2d');

    let lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                @foreach($data as $item)
                "{{ $item['exchange_date'] }}"@if($item != end($data)), @endif
                @endforeach
            ],
            datasets: [{
                label: 'USD',
                borderColor: 'rgb(255, 99, 132)',
                data: [
                    @foreach($data as $item)
                        {{ $item['rate'] }} @if($item != end($data)), @endif
                    @endforeach
                ]
            }]
        },
        options: {
            legend: {
                display: false
            },
            tooltips: {
                callbacks: {
                    label: function(tooltipItem) {
                        return tooltipItem.yLabel;
                    }
                }
            }
        }
    });
</script>
</body>
</html>
