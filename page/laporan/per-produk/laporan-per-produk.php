<div class="card shadow mb-4">
	<div class="card-body">
	  <!-- Tabel daftar penjualan per produk -->
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>No</th>
						<th>Kode</th>
						<th>Kategori</th>
						<th>Produk</th>
						<th>QTY</th>
						<th>Modal</th>
						<th>Jual</th>
						<th>Laba</th>
					</tr>
				</thead>
				<tbody>
					<?php
						// include database
						include '../../../config/database.php';
						$kondisi="";

						if (!empty($_POST["dari_tanggal"]) && empty($_POST["sampai_tanggal"])) $kondisi= "where date(tanggal)='".$_POST['dari_tanggal']."' ";
						if (!empty($_POST["dari_tanggal"]) && !empty($_POST["sampai_tanggal"])) $kondisi= "where date(tanggal) between '".$_POST['dari_tanggal']."' and '".$_POST['sampai_tanggal']."'";
						
						$sql="select k.nama_kt_produk, p.kode_produk,p.nama_produk,sum(d.qty)as qty,sum(d.qty*p.harga_beli) as modal 
						,sum(d.harga*d.qty)as jual from detail_penjualan d left join produk p on p.kode_produk=d.kode_produk
						left join kategori_produk k on p.kategori_produk=k.id_kt_produk
						left join penjualan on penjualan.no_invoice=d.no_invoice $kondisi
						group by p.nama_produk order by nama_kt_produk asc";
					
						// Eksekusi perintah SQL 
						$hasil=mysqli_query($kon,$sql);
						$no=0;
						$total_modal=0;
						$total_jual=0;
						$total_laba=0;
						//Menampilkan data dengan perulangan while
						while ($data = mysqli_fetch_array($hasil)):
						$no++;

						$total_modal+=$data['modal'];
						$total_jual+=$data['jual'];
						$total_laba+=$data['jual']-$data['modal'];

					?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $data['kode_produk']; ?></td>
						<td><?php echo $data['nama_kt_produk']; ?></td>
						<td><?php echo $data['nama_produk']; ?></td>
						<td><?php echo $data['qty'];?></td>
						<td>Rp. <?php echo number_format($data['modal'],0,',','.'); ?></td>
						<td>Rp. <?php echo number_format($data['jual'],0,',','.'); ?></td>
						<td>Rp. <?php echo number_format($data['jual']-$data['modal'],0,',','.'); ?></td>
					</tr>
					<!-- bagian akhir (penutup) while -->
					<?php endwhile; ?>
					<tr><td colspan="5"><strong>Total</strong></td><td><strong>Rp. <?php echo number_format($total_modal,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_jual,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_laba,0,',','.'); ?></strong></td></tr>
			
				</tbody>
			</table>
		</div>
		<!-- Tombol Cetak, excel dan PDF -->
	  <a href="page/laporan/per-produk/cetak/cetak-laporan.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-primary btn-icon-split"><span class="text"><i class="fas fa-print fa-sm"></i> Cetak Invoice</span></a>
      <a href="page/laporan/per-produk/cetak/cetak-laporan-pdf.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-danger btn-icon-pdf"><span class="text"><i class="fas fa-file-pdf fa-sm"></i> Export PDF</span></a>
	  <a href="page/laporan/per-produk/cetak/cetak-laporan-excel.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-success btn-icon-pdf"><span class="text"><i class="fas fa-file-excel fa-sm"></i> Export Excel</span></a>
     
	</div>
</div>