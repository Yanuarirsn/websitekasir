<?php
    session_start();
    include '../../config/database.php';
    $label = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    $tahun_ini=date('Y');

    for($bulan = 1;$bulan <= 12;$bulan++)
    {
        if ($_SESSION["level"]=="Kasir"){
            //Menampilkan data penjualan berdasarkan kasir
            $id_kasir=$_SESSION["id_pengguna"];
            $sql="select SUM(harga*qty) as total
            from penjualan p 
            inner join detail_penjualan d on d.no_invoice=p.no_invoice 
            where MONTH(tanggal)='$bulan' and YEAR(tanggal)='$tahun_ini' and id_kasir=$id_kasir ";
        }else {
            //Menampilkan semua data penjualan
            $sql="select SUM(harga*qty) as total
            from penjualan p 
            inner join detail_penjualan d on d.no_invoice=p.no_invoice 
            where MONTH(tanggal)='$bulan' and YEAR(tanggal)='$tahun_ini' ";
        }

        $hasil=mysqli_query($kon,$sql);
        $data=mysqli_fetch_array($hasil);
        $total[] = $data['total'];
    }
?>
<canvas id="grafik_penjualan"></canvas>

<script>
    var ctx = document.getElementById("grafik_penjualan").getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($label); ?>,
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