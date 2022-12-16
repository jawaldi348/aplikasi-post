<link rel="stylesheet" href="<?= base_url() ?>assets/plugins/morris/morris.css">
<script src="<?= base_url() ?>assets/plugins/morris/raphael-min.js"></script>
<script src="<?= base_url() ?>assets/plugins/morris/morris.min.js"></script>

<div id="graph" style="height: 250px;"></div>
<script>
Morris.Bar({
    element: 'graph',
    data: <?php echo $grafik ?> ,
    xkey: 'bulan',
    ykeys: ['total'],
    labels: ['Total Penjualan'],
    barColors : ['#ffbf00']
});
</script>