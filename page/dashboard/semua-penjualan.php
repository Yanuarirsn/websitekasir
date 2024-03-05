
<?php
    session_start();
    include '../../config/database.php';
    
    if ($_SESSION["level"]=="Kasir"){
        //Menampilkan data penjualan berdasarkan kasir
        $id_kasir=$_SESSION["id_pengguna"];
        $sql="select year(tanggal) as tahun,SUM(harga*qty) as total
        from penjualan p 
        inner join detail_penjualan d on d.no_invoice=p.no_invoice
        where id_kasir=$id_kasir
        group by tahun";
    }else {
        //Menampilkan semua data penjualan
        $sql="select year(tanggal) as tahun,SUM(harga*qty) as total
        from penjualan p 
        inner join detail_penjualan d on d.no_invoice=p.no_invoice
        group by tahun";
    }

    $hasil=mysqli_query($kon,$sql);
    while ($data = mysqli_fetch_array($hasil)) {
    $tahun[] = $data['tahun'];
    $total[] = $data['total'];
    
}
?>

<canvas id="grafik_penjualan"></canvas>

<script>
    var ctx = document.getElementById("grafik_penjualan").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($tahun); ?>,
            datasets: [{
                label: 'Grafik Penjualan',
                data: <?php echo json_encode($total); ?>,
                backgroundColor: 'RGB(25, 155, 232)',
                borderWidth: 1
            }]
        },
      options: {
            maintainAspectRatio: true,
            layout: {
            padding: {
                left: 10,
                right: 10,
                top: 25,
                bottom: 0
            }
            },
            scales: {
            xAxes: [{
                time: {
                unit: 'month'
                },
                gridLines: {
                display: false,
                drawBorder: false
                },
                ticks: {
                maxTicksLimit: 30
                },
                maxBarThickness: 25,
            }],
    
            },
            legend: {
            display: false
            },
         }
    });
</script>