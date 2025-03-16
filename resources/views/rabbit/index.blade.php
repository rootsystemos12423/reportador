@extends('layouts.rabbit.app')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/echarts/dist/echarts.min.js"></script>

<!-- Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mt-6">
    <div class="bg-zinc-800 p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105">
        <i class="fas fa-chart-line text-pink-400 text-3xl"></i>
        <p class="text-pink-400 font-semibold mt-2">Total Requests</p>
        <p class="text-3xl font-bold">{{ array_sum($totalRequests) }}</p>
    </div>
    <div class="bg-zinc-800 p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105">
        <i class="fas fa-shield-alt text-purple-400 text-3xl"></i>
        <p class="text-purple-400 font-semibold mt-2">Safe Page</p>
        <p class="text-3xl font-bold">{{ array_sum($safePage) }}</p>
    </div>
    <div class="bg-zinc-800 p-6 rounded-lg shadow-md text-center transform transition duration-300 hover:scale-105">
        <i class="fas fa-file-invoice text-blue-400 text-3xl"></i>
        <p class="text-blue-400 font-semibold mt-2">Offer Page</p>
        <p class="text-3xl font-bold">{{ array_sum($offerPage) }}</p>
    </div>
</div>

<!-- Chart -->
<div class="bg-zinc-900 mt-8 rounded-lg shadow-lg overflow-hidden p-6">
    <h2 class="text-xl text-white font-semibold mb-4">Traffic Analytics</h2>
    <div id="chart" class="w-full h-[500px]"></div>
</div>

<script>
    var chart = echarts.init(document.getElementById('chart'));

    var options = {
        backgroundColor: '#2c2c2e',
        textStyle: { color: '#ffffff' },
        tooltip: { trigger: 'axis' },
        legend: {
            data: ['Safe Page', 'Total Requests', 'Offer Page'],
            bottom: 10,
            right: 20,
            orient: 'horizontal',
            textStyle: { color: '#ffffff', fontSize: 14 },
            itemGap: 20,
            itemWidth: 25,
            itemHeight: 14,
            backgroundColor: 'rgba(255,255,255,0.1)',
            borderColor: '#666',
            borderWidth: 1,
            borderRadius: 8,
            padding: [10, 20]
        },
        grid: {
            left: '5%',
            right: '5%',
            bottom: '20%',
            containLabel: true
        },
        xAxis: {
            type: 'category',
            data: @json($dates),
            axisLine: { lineStyle: { color: '#888' } },
            axisLabel: { rotate: 30, fontSize: 12 }
        },
        yAxis: {
            type: 'value',
            axisLine: { lineStyle: { color: '#888' } }
        },
        series: [
            {
                name: 'Safe Page',
                type: 'line',
                smooth: true,
                lineStyle: { width: 4 },
                itemStyle: { color: '#ffcc00' },
                areaStyle: { color: 'rgba(255,204,0,0.3)' },
                data: @json($safePage)
            },
            {
                name: 'Total Requests',
                type: 'line',
                smooth: true,
                lineStyle: { width: 4 },
                itemStyle: { color: '#ff66ff' },
                areaStyle: { color: 'rgba(255,102,255,0.3)' },
                data: @json($totalRequests)
            },
            {
                name: 'Offer Page',
                type: 'line',
                smooth: true,
                lineStyle: { width: 4 },
                itemStyle: { color: '#9933ff' },
                areaStyle: { color: 'rgba(153,51,255,0.3)' },
                data: @json($offerPage)
            }
        ]
    };

    chart.setOption(options);
</script>

@endsection
