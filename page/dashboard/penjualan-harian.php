<?php
    session_start();
    include '../../config/database.php';
    $bulan_ini=date('m');
    $tahun_ini=date('Y');
    //Mendapatkan jumlah hari dalam bulan ini
    $jumHari = cal_days_in_month(CAL_GREGORIAN, $bulan_ini, $tahun_ini);

    for($hari=1;$hari<=$jumHari;$hari++)
    {

        if ($_SESSION["level"]=="Kasir"){
            //Menampilkan data penjualan berdasarkan kasir
            $id_kasir=$_SESSION["id_pengguna"];
            $sql="select day(tanggal) as tanggal,SUM(harga*qty) as total
            from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice
            where DAY(tanggal)='$hari' and MONTH(tanggal)='$bulan_ini' and id_kasir=$id_kasir ";
        }else {
            //Menampilkan semua data penjualan
            $sql="select day(tanggal) as tanggal,SUM(harga*qty) as total
            from penjualan p inner join detail_penjualan d on d.no_invoice=p.no_invoice
            where DAY(tanggal)='$hari' and MONTH(tanggal)='$bulan_ini'";
        }

        $hasil=mysqli_query($kon,$sql);
        $data=mysqli_fetch_array($hasil);
        $total[] = $data['total'];
        $label[] = $hari;
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
                maxTicksLimit: 31
           
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
