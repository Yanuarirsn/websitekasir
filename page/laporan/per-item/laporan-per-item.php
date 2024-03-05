<div class="card shadow mb-4">
	<div class="card-body">
	  <!-- Tabel daftar penjualan -->
	  <div class="table-responsive">
		<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		  <thead>
			<tr>
			  <th>No</th>
			  <th>Tanggal</th>
			  <th>Kode</th>
			  <th>Kategori</th>
			  <th>Produk</th>
			  <th>Qty</th>
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
				
				$sql="select * from detail_penjualan d left join produk p on p.kode_produk=d.kode_produk LEFT JOIN kategori_produk k on p.kategori_produk=k.id_kt_produk left join penjualan on penjualan.no_invoice=d.no_invoice $kondisi group by d.kode_produk, d.qty, d.no_invoice order by tanggal desc";
			
				// perintah sql
				$hasil=mysqli_query($kon,$sql);
				$no=0;
				$total_modal=0;
				$total_jual=0;
				$total_laba=0;

				//Menampilkan data dengan perulangan while
				while ($data = mysqli_fetch_array($hasil)):
				$no++;

				$qty= $data['qty'];
				$harga_beli=$data['harga_beli']*$qty;
				$harga_jual=$data['harga']*$qty;
				$laba=$harga_jual-$harga_beli;

				$total_modal+=$harga_beli;
				$total_jual+=$harga_jual;
				$total_laba+=$laba;
			?>
			  <tr>
				  <td><?php echo $no; ?></td>
				  <td><?php echo date('d/m/Y', strtotime($data["tanggal"]));?></td>
				  <td><?php echo $data['kode_produk']; ?></td>
				  <td><?php echo $data['nama_kt_produk']; ?></td>
				  <td><?php echo $data['nama_produk']; ?></td>
				  <td><?php echo $qty;?></td>
				  <td>Rp. <?php echo number_format($harga_beli,0,',','.'); ?></td>
				  <td>Rp. <?php echo number_format($harga_jual,0,',','.'); ?></td>
				  <td>Rp. <?php echo number_format($laba,0,',','.'); ?></td>
			  </tr>
			  <!-- bagian akhir (penutup) while -->
			  <?php endwhile; ?>
			  <tr><td colspan="6"><strong>Total</strong></td><td><strong>Rp. <?php echo number_format($total_modal,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_jual,0,',','.'); ?></strong></td><td><strong>Rp. <?php echo number_format($total_laba,0,',','.'); ?></strong></td></tr>
		  </tbody>
		</table>
	  </div>

	  <a href="page/laporan/per-item/cetak/cetak-laporan.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-primary btn-icon-split"><span class="text"><i class="fas fa-print fa-sm"></i> Cetak Invoice</span></a>
      <a href="page/laporan/per-item/cetak/cetak-laporan-pdf.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-danger btn-icon-pdf"><span class="text"><i class="fas fa-file-pdf fa-sm"></i> Export PDF</span></a>
	  <a href="page/laporan/per-item/cetak/cetak-laporan-excel.php?dari_tanggal=<?php if (!empty($_POST["dari_tanggal"])) echo $_POST["dari_tanggal"]; ?>&sampai_tanggal=<?php if (!empty($_POST["sampai_tanggal"])) echo $_POST["sampai_tanggal"]; ?>" target='blank' class="btn btn-success btn-icon-pdf"><span class="text"><i class="fas fa-file-excel fa-sm"></i> Export Excel</span></a>
     
	</div>
</div>