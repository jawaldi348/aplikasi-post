<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/chart/Chart.min.css">
<script src="<?= base_url() ?>assets/plugins/chart/Chart.bundle.min.js"></script>

<canvas id="chartproduklaku"></canvas>
<?php
$namaproduk = "";
$jml = "";

foreach ($grafik as $data) :
    $produk = $data->namaproduk;
    $namaproduk .= "'$produk'" . ", ";
    $jumlah = $data->jml;
    $jml .= "$jumlah" . ", ";
endforeach;
?>
<!-- <div id="graph" style="height: 250px;"></div> -->
<script>
function rubah(angka) {
    var reverse = angka.toString().split('').reverse().join(''),
        ribuan = reverse.match(/\d{1,3}/g);
    ribuan = ribuan.join('.').split('').reverse().join('');
    return ribuan;
}
var ctx = document.getElementById('chartproduklaku').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'bar',
    // The data for our dataset
    data: {
        labels: [<?php echo $namaproduk; ?>],
        datasets: [{
            label: 'Jumlah Produk',
            backgroundColor: ['rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)', 'rgb(60, 179, 113)',
                'rgb(175, 238, 239)', 'rgb(175, 238, 2)', 'rgb(115, 38, 239)',
                'rgba(56, 86, 255, 0.3)', 'rgb(255, 99, 132)', 'rgba(56, 86, 255, 0.87)',
                'rgb(60, 179, 113)'
            ],
            borderColor: ['rgba(56, 86, 255, 0.87)'],
            data: [<?php echo $jml; ?>]
        }]
    },
    // Configuration options go here
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true,
                    callback: function(value, index, values) {
                        return rubah(value);
                    }
                }
            }]
        },
        tooltips: {
            mode: 'index',
            label: 'myLabel',
            callbacks: {
                label: function(tooltipItem, data) {
                    return data.datasets[tooltipItem.datasetIndex].label + ' : ' + tooltipItem.yLabel
                        .toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        }
    },
    duration: 1000
});
</script>