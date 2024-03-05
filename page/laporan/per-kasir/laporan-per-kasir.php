
<?php
	
?>
<div class="card shadow mb-4">
	<div class="card-body">
	  <!-- Tabel daftar penjualan -->
	  <div class="table-responsive">
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		  <thead>
			<tr>
			  <th>No</th>
			  <th>Kode Kasir</th>
			  <th>Nama</th>
			  <th>Item Terjual</th>
			  <th>QTY Terjual</th>
			  <th>Total Pendapatan</th>
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
				
				$sql="select k.kode_pengguna as kode_kasir, k.nama_pengguna as kasir, count(*) as item_terjual, sum(d.qty) as qty_terjual, sum(d.qty*pr.harga_beli) as modal, sum(d.qty*d.harga) as pendapatan, sum((d.qty*d.harga)-(d.qty*pr.harga_beli)) as laba from penjualan p inner join pengguna k on k.id_pengguna=p.id_kasir inner join detail_penjualan d on d.no_invoice=p.no_invoice inner join produk pr on pr.kode_produk=d.kode_produk $kondisi group by k.nama_pengguna order by kode_kasir";
			
				// perintah sql
				$hasil=mysqli_query($kon,$sql);
				$no=0;
				$total_item=0;
				$total_qty_terjual=0;
				$total_pendapatan=0;
				$total_laba=0;
				//Menampilkan data dengan perulangan while
				while ($data = mysqli_fetch_array($hasil)):
				$no++;
				$total_item+=$data['item_terjual'];
				$total_qty_terjual+=$data['qty_terjual'];
				$total_pendapatan+=$data['pendapatan'];
				$total_laba+=$data['laba'];

			
			?>
			  <tr>
				  <td><?php echo $no; ?></td>
				  <td><?php echo $data['kode_kasir']; ?></td>
				  <td><?php echo $data['kasir']; ?></td>
				  <td><?php echo $data['item_terjual']; ?></td>
				  <td><?php echo $data['qty_terjual'];?></td>
				  <td>Rp. <?php echo number_format($data['pendapatan'],0,',','.'); ?></td>
				  <td>Rp. <?php echo number_format($data['laba'],0,',','.'); ?></td>
			  </tr>
			  <!-- bagian akhir (penutup) while -->
			  <?php endwhile; ?>
			  <tr><td colspan="3"><strong>Total</strong></td><td><strong><?php echo number_format($total_item,0,',','.'); ?></strong></td><td><strong><?php echo number_format($total_qty_terjual,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_pendapatan,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_laba,0,',','.'); ?></strong></td> </tr>
	
		  </tbody>
		</table>
	  </div>

	  <a href="page/laporan/per-kasir/cetak/cetak-laporan.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-primary btn-icon-split"><span class="text"><i class="fas fa-print fa-sm"></i> Cetak Invoice</span></a>
      <a href="page/laporan/per-kasir/cetak/cetak-laporan-pdf.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-danger btn-icon-pdf"><span class="text"><i class="fas fa-file-pdf fa-sm"></i> Export PDF</span></a>
	  <a href="page/laporan/per-kasir/cetak/cetak-laporan-excel.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-success btn-icon-pdf"><span class="text"><i class="fas fa-file-excel fa-sm"></i> Export Excel</span></a>
     
	</div>
</div>