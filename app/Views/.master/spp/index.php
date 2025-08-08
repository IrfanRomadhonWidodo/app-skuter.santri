<!DOCTYPE html>
<html>

<head>
    <title>Chart Pembayaran</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <h2>Grafik Pembayaran SPP per Bulan</h2>
    <canvas id="myChart" width="600" height="300"></canvas>

    <script>
        const ctx = document.getElementById('myChart').getContext('2d');

        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode(array_column($chartData, 'bulan')) ?>,
                datasets: [{
                    label: 'Total Pembayaran (Rp)',
                    data: <?= json_encode(array_column($chartData, 'total')) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>