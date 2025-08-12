<div id="utype_donut"></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const counts = @json($counts);
        console.log(counts);
        var options = {
            chart: {
                type: 'donut'
            },
            series: [counts['admins'], counts['internals'], counts['permittees']],
            labels: ['Admin', 'Internal', 'Pemittee'],
            fill: {
                colors: ['#10B981', '#f97316', '#eab308']
            }
        }

        var chart = new ApexCharts(document.querySelector("#utype_donut"), options);
        chart.render();
    })

    function computePercentage(a, b) {
        return ((a / b) * 100).toFixed(2);
    }
</script>