<?php
    include '../../config/database.php';
    $sql="select s.nama_supplier,count(*) as total from supplier s inner join produk p on s.id_supplier=p.supplier inner join detail_penjualan d on d.kode_produk=p.kode_produk group by s.nama_supplier asc";  
    $hasil=mysqli_query($kon,$sql);
    $no=0;

    while ($data = mysqli_fetch_array($hasil)) {
      $total[] = $data['total'];
      $label[] = $data['nama_supplier'];  
    }

  ?>
<!-- Menampilkan Pie Chart -->
<canvas id="pie_chart"></canvas>

<script>
    var ctx = document.getElementById("pie_chart");
    var myPieChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: <?php echo json_encode($label); ?>,
        datasets: [{
          data: <?php echo json_encode($total); ?>,
          backgroundColor: ['#33cc33', '#0066ff', '#ffff00','#ff0000','#ff8000',' #e600ac','#a31aff','#999966','#aaff00'],
          hoverBackgroundColor: ['#47d147', '#3385ff', '#ffff4d', '#ff1a1a', ' #ff8c1a','#ff00bf','#ad33ff','#a3a375','#b3ff1a'],
          hoverBorderColor: "rgba(234, 236, 244, 1)",
        }],
      },
      options: {
        maintainAspectRatio: false,
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          caretPadding: 10,
        },
        legend: {
          display: false
        },
        cutoutPercentage: 80,
      },
    });
</script>