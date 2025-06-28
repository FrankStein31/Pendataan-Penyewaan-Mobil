<?php
include '../koneksi/koneksi.php';
session_start();

// PHP logic for adding a transaction (No changes needed here, it's already solid)
if(isset($_POST['tambah'])) {
  $id_penyewa = $_POST['id_penyewa'];
  $id_mobil = $_POST['id_mobil'];
  $id_driver = $_POST['id_driver'] != '' ? $_POST['id_driver'] : null;
  $id_penumpang = $_POST['id_penumpang'] != '' ? $_POST['id_penumpang'] : null;
  $tujuan_sewa = $_POST['tujuan_sewa'];
  $tanggal_mulai = $_POST['tanggal_mulai'] . ' ' . $_POST['jam_mulai'];
  $tanggal_selesai = $_POST['tanggal_selesai'] . ' ' . $_POST['jam_selesai'];
  
  // Hitung total hari
  $start = new DateTime($tanggal_mulai);
  $end = new DateTime($tanggal_selesai);
  $interval = $start->diff($end);
  $total_hari = $interval->days > 0 ? $interval->days : 1;
  
  // Ambil harga mobil dan driver dari input manual
  $harga_mobil = (float)$_POST['harga_mobil'];
  $harga_driver = $id_driver ? (float)$_POST['harga_driver'] : 0;
  
  // Hitung total biaya tambahan
  $total_biaya_tambahan = 0;
  if(isset($_POST['biaya_tambahan'])) {
    foreach($_POST['biaya_tambahan'] as $id_tipe => $jumlah) {
      if($jumlah > 0) {
        $total_biaya_tambahan += (float)$jumlah;
      }
    }
  }
  
  $total_keseluruhan = $harga_mobil + $harga_driver + $total_biaya_tambahan;
  $status_pembayaran = $_POST['status_pembayaran'];
  $jumlah_dp = $status_pembayaran == 'DP' ? (float)$_POST['jumlah_dp'] : 0;
  $sisa_pembayaran = $status_pembayaran == 'DP' ? $total_keseluruhan - $jumlah_dp : 0;
  
  // Set status sewa berdasarkan status pembayaran
  $status_sewa = $status_pembayaran == 'DP' ? 'Berlangsung' : 'Selesai';
  
  mysqli_query($conn, "INSERT INTO transaksi (id_penyewa, id_mobil, id_driver, id_penumpang, tujuan_sewa, tanggal_mulai, tanggal_selesai, 
                      total_hari, harga_mobil, harga_driver, total_biaya_tambahan, total_keseluruhan,
                      status_pembayaran, jumlah_dp, sisa_pembayaran, status_sewa) 
                      VALUES ('$id_penyewa', '$id_mobil', " . ($id_driver ? "'$id_driver'" : "NULL") . ", " . ($id_penumpang ? "'$id_penumpang'" : "NULL") . ", 
                      '$tujuan_sewa', '$tanggal_mulai', '$tanggal_selesai', '$total_hari', '$harga_mobil', '$harga_driver',
                      '$total_biaya_tambahan', '$total_keseluruhan', '$status_pembayaran', '$jumlah_dp', '$sisa_pembayaran', '$status_sewa')");
  
  $id_transaksi = mysqli_insert_id($conn);
  
  // Simpan detail biaya tambahan
  if(isset($_POST['biaya_tambahan'])) {
    foreach($_POST['biaya_tambahan'] as $id_tipe => $jumlah) {
      if($jumlah > 0) {
        mysqli_query($conn, "INSERT INTO detail_biaya (id_transaksi, id_tipe, jumlah) VALUES ('$id_transaksi', '$id_tipe', '$jumlah')");
      }
    }
  }
  
  header("Location: transaksi.php");
}

// PHP logic for editing a transaction
if(isset($_POST['edit'])) {
  $id_transaksi = $_POST['id_transaksi'];
  $status_pembayaran = $_POST['status_pembayaran'];
  
  // Hitung total biaya tambahan baru
  $total_biaya_tambahan = 0;
  if(isset($_POST['biaya_tambahan'])) {
    foreach($_POST['biaya_tambahan'] as $id_tipe => $jumlah) {
      if($jumlah > 0) {
        $total_biaya_tambahan += $jumlah;
      }
    }
  }
  
  // Ambil data transaksi
  $data_transaksi = mysqli_fetch_array(mysqli_query($conn, "SELECT harga_mobil, harga_driver FROM transaksi WHERE id_transaksi='$id_transaksi'"));
  $total_keseluruhan = $data_transaksi['harga_mobil'] + $data_transaksi['harga_driver'] + $total_biaya_tambahan;
  
  $jumlah_dp = $status_pembayaran == 'DP' ? $_POST['jumlah_dp'] : 0;
  $sisa_pembayaran = $status_pembayaran == 'DP' ? $total_keseluruhan - $jumlah_dp : 0;
  $status_sewa = $status_pembayaran == 'DP' ? 'Berlangsung' : 'Selesai';
  
  // Update transaksi
  mysqli_query($conn, "UPDATE transaksi SET 
                    status_pembayaran='$status_pembayaran',
                    jumlah_dp='$jumlah_dp',
                    sisa_pembayaran='$sisa_pembayaran',
                    status_sewa='$status_sewa',
                    total_biaya_tambahan='$total_biaya_tambahan',
                    total_keseluruhan='$total_keseluruhan'
                    WHERE id_transaksi='$id_transaksi'");
  
  // Hapus detail biaya lama
  mysqli_query($conn, "DELETE FROM detail_biaya WHERE id_transaksi='$id_transaksi'");
  
  // Simpan detail biaya baru
  if(isset($_POST['biaya_tambahan'])) {
    foreach($_POST['biaya_tambahan'] as $id_tipe => $jumlah) {
      if($jumlah > 0) {
        mysqli_query($conn, "INSERT INTO detail_biaya (id_transaksi, id_tipe, jumlah) VALUES ('$id_transaksi', '$id_tipe', '$jumlah')");
      }
    }
  }
  
  header("Location: transaksi.php");
}

// PHP logic for editing complete transaction
if(isset($_POST['edit_lengkap'])) {
  $id_transaksi = $_POST['id_transaksi'];
  $id_penyewa = $_POST['id_penyewa'];
  $id_mobil = $_POST['id_mobil'];
  $id_driver = $_POST['id_driver'] != '' ? $_POST['id_driver'] : null;
  $id_penumpang = $_POST['id_penumpang'] != '' ? $_POST['id_penumpang'] : null;
  $tujuan_sewa = $_POST['tujuan_sewa'];
  $tanggal_mulai = $_POST['tanggal_mulai'] . ' ' . $_POST['jam_mulai'];
  $tanggal_selesai = $_POST['tanggal_selesai'] . ' ' . $_POST['jam_selesai'];
  
  // Hitung total hari
  $start = new DateTime($tanggal_mulai);
  $end = new DateTime($tanggal_selesai);
  $interval = $start->diff($end);
  $total_hari = $interval->days > 0 ? $interval->days : 1;
  
  // Ambil harga mobil dan driver dari input manual
  $harga_mobil = (float)$_POST['harga_mobil'];
  $harga_driver = $id_driver ? (float)$_POST['harga_driver'] : 0;
  
  // Hitung total biaya tambahan
  $total_biaya_tambahan = 0;
  if(isset($_POST['biaya_tambahan'])) {
    foreach($_POST['biaya_tambahan'] as $id_tipe => $jumlah) {
      if($jumlah > 0) {
        $total_biaya_tambahan += (float)$jumlah;
      }
    }
  }
  
  $total_keseluruhan = $harga_mobil + $harga_driver + $total_biaya_tambahan;
  $status_pembayaran = $_POST['status_pembayaran'];
  $jumlah_dp = $status_pembayaran == 'DP' ? (float)$_POST['jumlah_dp'] : 0;
  $sisa_pembayaran = $status_pembayaran == 'DP' ? $total_keseluruhan - $jumlah_dp : 0;
  $status_sewa = $status_pembayaran == 'DP' ? 'Berlangsung' : 'Selesai';
  
  // Update transaksi
  mysqli_query($conn, "UPDATE transaksi SET 
                    id_penyewa='$id_penyewa',
                    id_mobil='$id_mobil',
                    id_driver=" . ($id_driver ? "'$id_driver'" : "NULL") . ",
                    id_penumpang=" . ($id_penumpang ? "'$id_penumpang'" : "NULL") . ",
                    tujuan_sewa='$tujuan_sewa',
                    tanggal_mulai='$tanggal_mulai',
                    tanggal_selesai='$tanggal_selesai',
                    total_hari='$total_hari',
                    harga_mobil='$harga_mobil',
                    harga_driver='$harga_driver',
                    total_biaya_tambahan='$total_biaya_tambahan',
                    total_keseluruhan='$total_keseluruhan',
                    status_pembayaran='$status_pembayaran',
                    jumlah_dp='$jumlah_dp',
                    sisa_pembayaran='$sisa_pembayaran',
                    status_sewa='$status_sewa'
                    WHERE id_transaksi='$id_transaksi'");
  
  // Hapus detail biaya lama
  mysqli_query($conn, "DELETE FROM detail_biaya WHERE id_transaksi='$id_transaksi'");
  
  // Simpan detail biaya baru
  if(isset($_POST['biaya_tambahan'])) {
    foreach($_POST['biaya_tambahan'] as $id_tipe => $jumlah) {
      if($jumlah > 0) {
        mysqli_query($conn, "INSERT INTO detail_biaya (id_transaksi, id_tipe, jumlah) VALUES ('$id_transaksi', '$id_tipe', '$jumlah')");
      }
    }
  }
  
  header("Location: transaksi.php");
}

// PHP logic for deleting a transaction
if(isset($_POST['hapus'])) {
  $id_transaksi = $_POST['id_transaksi'];
  
  // Hapus detail biaya terlebih dahulu
  mysqli_query($conn, "DELETE FROM detail_biaya WHERE id_transaksi='$id_transaksi'");
  
  // Kemudian hapus transaksi
  mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi='$id_transaksi'");
  
  header("Location: transaksi.php");
}

// PHP logic for finishing a transaction (No changes needed here)
if(isset($_POST['selesai'])) {
  $id = $_POST['id'];
  mysqli_query($conn, "UPDATE transaksi SET status_sewa='Selesai' WHERE id_transaksi='$id'");
  header("Location: transaksi.php");
}

// Query total pendapatan hari ini
$qPendapatanHariIni = mysqli_query($conn, "SELECT SUM(total_keseluruhan) as total FROM transaksi WHERE DATE(tanggal_mulai) = CURDATE()");
$dataPendapatanHariIni = mysqli_fetch_assoc($qPendapatanHariIni);
// Query total pendapatan bulan ini
$qPendapatanBulanIni = mysqli_query($conn, "SELECT SUM(total_keseluruhan) as total FROM transaksi WHERE YEAR(tanggal_mulai) = YEAR(CURDATE()) AND MONTH(tanggal_mulai) = MONTH(CURDATE())");
$dataPendapatanBulanIni = mysqli_fetch_assoc($qPendapatanBulanIni);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Transaksi - Sistem Pendataan Penyewaan Mobil</title>
  <link href="../styling/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../styling/assets/css/nucleo-svg.css" rel="stylesheet" />
  <link href="../styling/assets/css/argon-dashboard.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <!-- jQuery harus dimuat pertama -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="../styling/assets/js/core/popper.min.js"></script>
  <script src="../styling/assets/js/core/bootstrap.min.js"></script>
  <script src="../styling/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../styling/assets/js/plugins/smooth-scrollbar.min.js"></script>
  
  <script>
    // Tunggu sampai dokumen siap
    $(document).ready(function() {
      // Inisialisasi Select2 untuk dropdown di modal tambah
      initSelect2();
      
      // Setup event handlers
      setupEventHandlers();
      
      // Trigger kalkulasi awal
      hitungTotal();
    });

    function initSelect2() {
      // Select2 untuk form tambah
      $('.select2').each(function() {
        $(this).select2({
          dropdownParent: $('#tambahModal'),
          width: '100%'
        }).on('select2:open', function() {
          // Pastikan dropdown muncul di atas modal
          $('.select2-dropdown').css('z-index', 1060);
        });
      });

      // Select2 untuk filter
      $('.select2-filter').select2({
        width: '100%'
      });

      // Select2 untuk modal edit lengkap
      $(document).on('shown.bs.modal', '.modal', function() {
        if ($(this).attr('id').includes('editLengkapModal')) {
          $(this).find('.select2').each(function() {
            if ($(this).hasClass('select2-hidden-accessible')) {
              $(this).select2('destroy');
            }
            $(this).select2({
              dropdownParent: $(this).closest('.modal'),
              width: '100%'
            }).on('select2:open', function() {
              $('.select2-dropdown').css('z-index', 1060);
            });
          });
        }
      });
    }

    function setupEventHandlers() {
      // Event untuk kalkulasi otomatis
      $('input[name="harga_mobil"], input[name="harga_driver"]').on('input', function() {
        hitungTotal();
      });

      $('input[name="tanggal_mulai"], input[name="jam_mulai"], input[name="tanggal_selesai"], input[name="jam_selesai"]').on('change', function() {
        hitungTotal();
      });
      
      // Event untuk status pembayaran
      $('#status-pembayaran').on('change', function() {
        const value = $(this).val();
        console.log('Status Pembayaran Changed:', value);
        if (value === 'DP') {
          $('#dpGroup').show();
        } else {
          $('#dpGroup').hide();
          $('#jumlah-dp').val('');
        }
        hitungTotal();
      });
      
      // Event untuk jumlah DP
      $('#jumlah-dp').on('input', function() {
        hitungTotal();
      });

      // Event untuk biaya tambahan
      $('.biaya-tambahan').on('input', function() {
        hitungTotal();
      });

      // Event untuk modal edit lengkap
      $(document).on('input', 'input[name="harga_mobil"], input[name="harga_driver"]', function() {
        const modal = $(this).closest('.modal');
        if (modal.attr('id').includes('editLengkapModal')) {
          const idTransaksi = modal.attr('id').replace('editLengkapModal', '');
          hitungTotalLengkap(idTransaksi);
        }
      });

      $(document).on('change', 'input[name="tanggal_mulai"], input[name="jam_mulai"], input[name="tanggal_selesai"], input[name="jam_selesai"]', function() {
        const modal = $(this).closest('.modal');
        if (modal.attr('id').includes('editLengkapModal')) {
          const idTransaksi = modal.attr('id').replace('editLengkapModal', '');
          hitungTotalLengkap(idTransaksi);
        }
      });

      $(document).on('input', '.biaya-tambahan-lengkap', function() {
        const modal = $(this).closest('.modal');
        if (modal.attr('id').includes('editLengkapModal')) {
          const idTransaksi = modal.attr('id').replace('editLengkapModal', '');
          hitungTotalLengkap(idTransaksi);
        }
      });
    }
    
    function toggleBiaya(id) {
        const isChecked = $('#biaya'+id).is(':checked');
        const groupInput = $('#group-jumlah'+id);
        const input = $('#jumlah'+id);
        
        if(isChecked) {
            groupInput.show();
            input.prop('disabled', false);
            input.focus();
        } else {
            groupInput.hide();
            input.prop('disabled', true);
            input.val(0);
        }
        hitungTotal();
    }
    
    function hitungTotal() {
        try {
            // --- 1. Hitung Durasi ---
            const startDate = $('input[name="tanggal_mulai"]').val();
            const startTime = $('input[name="jam_mulai"]').val();
            const endDate = $('input[name="tanggal_selesai"]').val();
            const endTime = $('input[name="jam_selesai"]').val();
            
            let totalHari = 0;
            if (startDate && startTime && endDate && endTime) {
                const start = new Date(`${startDate}T${startTime}`);
                const end = new Date(`${endDate}T${endTime}`);
                if (end > start) {
                    const diff = Math.abs(end - start);
                    totalHari = Math.ceil(diff / (1000 * 60 * 60 * 24));
                    if (totalHari === 0) totalHari = 1; // Minimum 1 hari
                }
            }
            $('#estimasi-hari').text(totalHari + ' Hari');

            // --- 2. Ambil Harga Mobil & Driver dari Input Manual ---
            const hargaMobil = parseInt($('input[name="harga_mobil"]').val()) || 0;
            const hargaDriver = parseInt($('input[name="harga_driver"]').val()) || 0;
            
            $('#estimasi-mobil').text('Rp ' + formatRupiah(hargaMobil));
            $('#estimasi-driver').text('Rp ' + formatRupiah(hargaDriver));

            // --- 3. Hitung Biaya Tambahan ---
            let totalTambahan = 0;
            $('.biaya-tambahan:not(:disabled)').each(function() {
                const nilai = parseInt($(this).val()) || 0;
                totalTambahan += nilai;
            });
            $('#estimasi-tambahan').text('Rp ' + formatRupiah(totalTambahan));

            // --- 4. Hitung Total Keseluruhan ---
            const total = hargaMobil + hargaDriver + totalTambahan;
            $('#estimasi-total').text('Rp ' + formatRupiah(total));

            // --- 5. Hitung Sisa Pembayaran jika DP ---
            const statusBayar = $('#status-pembayaran').val();
            if (statusBayar === 'DP') {
                const dp = parseInt($('#jumlah-dp').val()) || 0;
                const sisa = total - dp;
                
                console.log('Perhitungan Pembayaran:', {
                    statusBayar,
                    total,
                    dp,
                    sisa
                });
                
                $('#sisa-pembayaran').text('Rp ' + formatRupiah(sisa));
                $('#dpGroup').show();
            } else {
                $('#dpGroup').hide();
                $('#jumlah-dp').val('');
                $('#sisa-pembayaran').text('Rp 0');
            }
        } catch (error) {
            console.error('Error dalam hitungTotal:', error);
        }
    }
    
    function toggleDP(value) {
        console.log('Toggle DP:', value);
        const dpGroup = $('#dpGroup');
        if (value === 'DP') {
            dpGroup.show();
        } else {
            dpGroup.hide();
            $('#jumlah-dp').val('');
        }
        hitungTotal();
    }

    function toggleDPEdit(value, id) {
      const dpGroup = document.getElementById('dpGroupEdit'+id);
      if(value === 'DP') {
        dpGroup.style.display = 'block';
      } else {
        dpGroup.style.display = 'none';
        $('#jumlah-dp').val('');
      }
      hitungTotal();
    }

    function hitungSisaEdit(dp, idTransaksi) {
      try {
        // Ambil total biaya mobil dan driver dari data transaksi
        const totalBiayaMobil = parseFloat($('#totalBiayaMobilEdit'+idTransaksi).val()) || 0;
        const totalBiayaDriver = parseFloat($('#totalBiayaDriverEdit'+idTransaksi).val()) || 0;
        
        // Hitung total biaya tambahan
        let totalBiayaTambahan = 0;
        $('input[name^="biaya_tambahan"]').each(function() {
          if ($(this).is(':visible') && $(this).val()) {
            totalBiayaTambahan += parseFloat($(this).val()) || 0;
          }
        });
        
        // Hitung total keseluruhan
        const totalKeseluruhan = totalBiayaMobil + totalBiayaDriver + totalBiayaTambahan;
        
        // Hitung sisa pembayaran
        const sisa = totalKeseluruhan - parseFloat(dp);
        
        // Update tampilan
        $('#sisaEdit'+idTransaksi).text('Rp ' + formatRupiah(sisa));
        
        console.log('Perhitungan Sisa:', {
          totalBiayaMobil,
          totalBiayaDriver,
          totalBiayaTambahan,
          totalKeseluruhan,
          dp,
          sisa
        });
      } catch (error) {
        console.error('Error dalam hitungSisaEdit:', error);
      }
    }

    function toggleBiayaEdit(idTransaksi, idTipe) {
      const isChecked = $('#biayaEdit'+idTransaksi+'_'+idTipe).is(':checked');
      const groupInput = $('#group-jumlahEdit'+idTransaksi+'_'+idTipe);
      const input = $('#jumlahEdit'+idTransaksi+'_'+idTipe);
      
      if(isChecked) {
        groupInput.show();
      } else {
        groupInput.hide();
        input.val('');
      }
      hitungTotalEdit(idTransaksi);
    }

    function hitungTotalEdit(idTransaksi) {
      let totalBiayaTambahan = 0;
      $('input[name^="biaya_tambahan"]').each(function() {
        if ($(this).is(':visible') && $(this).val()) {
          totalBiayaTambahan += parseFloat($(this).val());
        }
      });
      
      $('#totalBiayaTambahanEdit'+idTransaksi).text('Rp ' + formatRupiah(totalBiayaTambahan));
      
      // Update sisa pembayaran jika status DP
      const statusPembayaran = $('select[name="status_pembayaran"]').val();
      if(statusPembayaran === 'DP') {
        const jumlahDp = parseFloat($('input[name="jumlah_dp"]').val()) || 0;
        const totalKeseluruhan = <?= isset($data) ? $data['harga_mobil'] + $data['harga_driver'] : 0 ?> + totalBiayaTambahan;
        const sisaPembayaran = totalKeseluruhan - jumlahDp;
        $('#sisaEdit'+idTransaksi).text('Rp ' + formatRupiah(sisaPembayaran));
      }
    }

    function formatRupiah(angka) {
        return Math.round(angka).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    function exportExcel() {
      // Ambil parameter filter yang sedang aktif
      const startDate = $('input[name="start_date"]').val();
      const endDate = $('input[name="end_date"]').val();
      const idMobil = $('select[name="id_mobil"]').val();
      const idPenyewa = $('select[name="id_penyewa"]').val();
      const idPenumpang = $('select[name="id_penumpang"]').val();
      
      // Buat URL dengan parameter
      let url = 'export_transaksi.php?';
      url += 'start_date=' + startDate;
      url += '&end_date=' + endDate;
      if(idMobil) url += '&id_mobil=' + idMobil;
      if(idPenyewa) url += '&id_penyewa=' + idPenyewa;
      if(idPenumpang) url += '&id_penumpang=' + idPenumpang;
      
      window.location.href = url;
    }

    function hitungSisaPembayaran() {
        const statusBayar = $('#status-pembayaran').val();
        const total = hitungTotalKeseluruhan();
        
        console.log('Status Pembayaran:', statusBayar);
        console.log('Total Keseluruhan:', total);
        
        if (statusBayar === 'DP') {
            const dp = parseInt($('#jumlah-dp').val()) || 0;
            const sisa = total - dp;
            
            console.log('Perhitungan Sisa:', {
                total: total,
                dp: dp,
                sisa: sisa
            });
            
            $('#sisa-pembayaran').text('Rp ' + formatRupiah(sisa));
            $('#dpGroup').show();
        } else {
            $('#dpGroup').hide();
            $('#jumlah-dp').val('');
            $('#sisa-pembayaran').text('Rp 0');
        }
    }

    function hitungTotalKeseluruhan() {
        const totalMobil = hitungTotalMobil();
        const totalDriver = hitungTotalDriver();
        const totalTambahan = hitungTotalTambahan();
        return totalMobil + totalDriver + totalTambahan;
    }

    // Event handlers
    $(document).ready(function() {
        $('#status-pembayaran').on('change', function() {
            const value = $(this).val();
            console.log('Status Pembayaran Changed:', value);
            if (value === 'DP') {
                $('#dpGroup').show();
            } else {
                $('#dpGroup').hide();
                $('#jumlah-dp').val('');
            }
            hitungSisaPembayaran();
        });
        
        $('#jumlah-dp').on('input', function() {
            hitungSisaPembayaran();
        });
        
        // Trigger initial calculation
        hitungSisaPembayaran();
    });

    // Fungsi untuk modal edit lengkap
    function toggleBiayaLengkap(idTransaksi, idTipe) {
        const isChecked = $('#biayaLengkap'+idTransaksi+'_'+idTipe).is(':checked');
        const groupInput = $('#group-jumlahLengkap'+idTransaksi+'_'+idTipe);
        const input = $('#jumlahLengkap'+idTransaksi+'_'+idTipe);
        
        if(isChecked) {
            groupInput.show();
            input.prop('disabled', false);
            input.focus();
        } else {
            groupInput.hide();
            input.prop('disabled', true);
            input.val('');
        }
        hitungTotalLengkap(idTransaksi);
    }

    function hitungTotalLengkap(idTransaksi) {
        try {
            // --- 1. Hitung Durasi ---
            const modal = $('#editLengkapModal'+idTransaksi);
            const startDate = modal.find('input[name="tanggal_mulai"]').val();
            const startTime = modal.find('input[name="jam_mulai"]').val();
            const endDate = modal.find('input[name="tanggal_selesai"]').val();
            const endTime = modal.find('input[name="jam_selesai"]').val();
            
            let totalHari = 0;
            if (startDate && startTime && endDate && endTime) {
                const start = new Date(`${startDate}T${startTime}`);
                const end = new Date(`${endDate}T${endTime}`);
                if (end > start) {
                    const diff = Math.abs(end - start);
                    totalHari = Math.ceil(diff / (1000 * 60 * 60 * 24));
                    if (totalHari === 0) totalHari = 1; // Minimum 1 hari
                }
            }
            $('#estimasi-hari-lengkap'+idTransaksi).text(totalHari + ' Hari');

            // --- 2. Ambil Harga Mobil & Driver dari Input Manual ---
            const hargaMobil = parseInt(modal.find('input[name="harga_mobil"]').val()) || 0;
            const hargaDriver = parseInt(modal.find('input[name="harga_driver"]').val()) || 0;
            
            $('#estimasi-mobil-lengkap'+idTransaksi).text('Rp ' + formatRupiah(hargaMobil));
            $('#estimasi-driver-lengkap'+idTransaksi).text('Rp ' + formatRupiah(hargaDriver));

            // --- 3. Hitung Biaya Tambahan ---
            let totalTambahan = 0;
            modal.find('.biaya-tambahan-lengkap:not(:disabled)').each(function() {
                const nilai = parseInt($(this).val()) || 0;
                totalTambahan += nilai;
            });
            $('#estimasi-tambahan-lengkap'+idTransaksi).text('Rp ' + formatRupiah(totalTambahan));

            // --- 4. Hitung Total Keseluruhan ---
            const total = hargaMobil + hargaDriver + totalTambahan;
            $('#estimasi-total-lengkap'+idTransaksi).text('Rp ' + formatRupiah(total));

            // --- 5. Hitung Sisa Pembayaran jika DP ---
            const statusBayar = modal.find('#status-pembayaran-lengkap'+idTransaksi).val();
            if (statusBayar === 'DP') {
                const dp = parseInt(modal.find('#jumlah-dp-lengkap'+idTransaksi).val()) || 0;
                const sisa = total - dp;
                $('#sisa-pembayaran-lengkap'+idTransaksi).text('Rp ' + formatRupiah(sisa));
            }
        } catch (error) {
            console.error('Error dalam hitungTotalLengkap:', error);
        }
    }

    function toggleDPLengkap(value, idTransaksi) {
        const dpGroup = $('#dpGroupLengkap'+idTransaksi);
        if(value === 'DP') {
            dpGroup.show();
        } else {
            dpGroup.hide();
            $('#jumlah-dp-lengkap'+idTransaksi).val('');
            $('#sisa-pembayaran-lengkap'+idTransaksi).text('Rp 0');
        }
        hitungTotalLengkap(idTransaksi);
    }

    function hitungSisaLengkap(dp, idTransaksi) {
        try {
            const modal = $('#editLengkapModal'+idTransaksi);
            const hargaMobil = parseInt(modal.find('input[name="harga_mobil"]').val()) || 0;
            const hargaDriver = parseInt(modal.find('input[name="harga_driver"]').val()) || 0;
            
            let totalTambahan = 0;
            modal.find('.biaya-tambahan-lengkap:not(:disabled)').each(function() {
                totalTambahan += parseInt($(this).val()) || 0;
            });
            
            const totalKeseluruhan = hargaMobil + hargaDriver + totalTambahan;
            const sisa = totalKeseluruhan - parseFloat(dp);
            
            $('#sisa-pembayaran-lengkap'+idTransaksi).text('Rp ' + formatRupiah(sisa));
        } catch (error) {
            console.error('Error dalam hitungSisaLengkap:', error);
        }
    }

    $(document).on('input', '[id^="jumlah-dp-lengkap"]', function() {
        const modal = $(this).closest('.modal');
        if (modal.attr('id').includes('editLengkapModal')) {
          const idTransaksi = modal.attr('id').replace('editLengkapModal', '');
          const dp = $(this).val();
          hitungSisaLengkap(dp, idTransaksi);
        }
    });

    $(document).on('change', '[id^="status-pembayaran-lengkap"]', function() {
        const modal = $(this).closest('.modal');
        if (modal.attr('id').includes('editLengkapModal')) {
          const idTransaksi = modal.attr('id').replace('editLengkapModal', '');
          const value = $(this).val();
          toggleDPLengkap(value, idTransaksi);
        }
    });
  </script>
  
  <style>
    /* Custom styling for Select2 */
    .select2-container {
      width: 100% !important;
    }
    
    .select2-container--default .select2-selection--single {
      height: calc(1.5em + 1.25rem + 2px);
      padding: 0.625rem 0.75rem;
      border: 1px solid #d2d6da;
      border-radius: 0.5rem;
      background-color: #fff;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 1.5;
      padding-left: 0;
      padding-right: 20px;
      color: #344767;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: 100%;
      right: 8px;
    }

    .select2-dropdown {
      border-radius: 0.5rem;
      border: 1px solid #d2d6da;
      z-index: 1065;
    }

    .select2-search--dropdown {
      padding: 8px;
    }

    .select2-container--default .select2-search--dropdown .select2-search__field {
      border: 1px solid #d2d6da;
      border-radius: 0.5rem;
      padding: 0.5rem;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected] {
      background-color: #5e72e4;
    }

    /* Modal z-index */
    .modal,
    .modal-backdrop {
      z-index: 2000 !important;
    }
    .sidenav {
      z-index: 900 !important;
    }
    .select2-dropdown {
      z-index: 1060 !important;
    }
    .select2-container--open {
      z-index: 1060 !important;
    }

    /* Custom styling for form elements */
    .form-check-input {
      width: 1.2em;
      height: 1.2em;
      margin-top: 0.25em;
      margin-right: 0.5em;
      border: 1px solid #d2d6da;
      border-radius: 0.25rem;
    }

    .form-check-input:checked {
      background-color: #5e72e4;
      border-color: #5e72e4;
    }

    .form-check-label {
      margin-bottom: 0;
      font-weight: 500;
      color: #344767;
    }

    .input-group-sm .form-control {
      height: calc(1.5em + 0.5rem + 2px);
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
      border-radius: 0.375rem;
    }

    .input-group-sm .input-group-text {
      height: calc(1.5em + 0.5rem + 2px);
      padding: 0.25rem 0.5rem;
      font-size: 0.875rem;
      border-radius: 0.375rem;
    }

    /* Sticky positioning for sidebar */
    .position-sticky {
      position: sticky !important;
    }

    /* Custom styling for modal edit lengkap */
    .modal-xl {
      max-width: 1200px;
    }

    .card.shadow-none.border {
      border: 1px solid #e9ecef !important;
      box-shadow: none !important;
    }

    .list-group-item {
      border-left: none;
      border-right: none;
      padding: 0.75rem 1rem;
    }

    .list-group-item:first-child {
      border-top: none;
    }

    .list-group-item:last-child {
      border-bottom: none;
    }

    .modal-backdrop {
      background-color: #222 !important;
      opacity: 0.3 !important;
    }
    .modal {
      z-index: 2100 !important;
    }
  </style>
</head>

<body class="g-sidenav-show bg-gray-100">
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4" id="sidenav-main">
    <div class="sidenav-header">
      <div class="d-flex align-items-center justify-content-center">
        <i class="fa fa-car text-primary me-2" style="font-size: 24px;"></i>
        <h4 class="m-0">Rental Mobil</h4>
      </div>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse w-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="../index.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-dashboard text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="penyewa.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-users text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Penyewa</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="penumpang.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-user-plus text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Penumpang</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="mobil.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-car text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Mobil</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="driver.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-id-card text-info text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Driver</span>
          </a>
        </li>
         <li class="nav-item">
          <a class="nav-link" href="tipe_biaya.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-list text-success text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Tipe Biaya</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="transaksi.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-money text-danger text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Transaksi</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../logout.php" onclick="return confirm('Yakin ingin logout?')">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="fa fa-sign-out text-warning text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Logout</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>

  <main class="main-content position-relative border-radius-lg">
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
              <h6>Data Transaksi</h6>
              <div>
                <button class="btn btn-success btn-sm me-2" onclick="exportExcel()">
                  <i class="fa fa-file-excel-o"></i> Export Excel
                </button>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                  <i class="fa fa-plus"></i> Tambah
                </button>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="p-4">
                <div class="row mb-4">
                  <div class="col-md-6 mb-2">
                    <div class="card shadow-sm">
                      <div class="card-body text-center">
                        <div class="text-xs text-muted mb-1">Total Pendapatan Hari Ini</div>
                        <h3 class="text-success mb-0">Rp <?= number_format($dataPendapatanHariIni['total'] ?? 0,0,',','.') ?></h3>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-2">
                    <div class="card shadow-sm">
                      <div class="card-body text-center">
                        <div class="text-xs text-muted mb-1">Total Pendapatan Bulan Ini</div>
                        <h3 class="text-primary mb-0">Rp <?= number_format($dataPendapatanBulanIni['total'] ?? 0,0,',','.') ?></h3>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Filter Section -->
                <div class="card shadow-sm mb-4">
                  <div class="card-body">
                    <form method="GET" class="row g-3">
                      <div class="col-md-4">
                        <label class="form-label">Rentang Tanggal</label>
                        <div class="input-group">
                          <input type="date" class="form-control" name="start_date" value="<?= isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01') ?>">
                          <span class="input-group-text">s/d</span>
                          <input type="date" class="form-control" name="end_date" value="<?= isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t') ?>">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Mobil</label>
                        <select class="form-control select2-filter" name="id_mobil">
                          <option value="">Semua Mobil</option>
                          <?php
                          $q_mobil = mysqli_query($conn, "SELECT * FROM mobil ORDER BY nama_mobil");
                          while($d_mobil = mysqli_fetch_array($q_mobil)) {
                            $selected = (isset($_GET['id_mobil']) && $_GET['id_mobil'] == $d_mobil['id_mobil']) ? 'selected' : '';
                            echo "<option value='$d_mobil[id_mobil]' $selected>$d_mobil[nama_mobil] - $d_mobil[plat_nomor]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <label class="form-label">Penyewa</label>
                        <select class="form-control select2-filter" name="id_penyewa">
                          <option value="">Semua Penyewa</option>
                          <?php
                          $q_penyewa = mysqli_query($conn, "SELECT * FROM penyewa ORDER BY nama_penyewa");
                          while($d_penyewa = mysqli_fetch_array($q_penyewa)) {
                            $selected = (isset($_GET['id_penyewa']) && $_GET['id_penyewa'] == $d_penyewa['id_penyewa']) ? 'selected' : '';
                            echo "<option value='$d_penyewa[id_penyewa]' $selected>$d_penyewa[nama_penyewa]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-2">
                        <label class="form-label">Penumpang</label>
                        <select class="form-control select2-filter" name="id_penumpang">
                          <option value="">Semua Penumpang</option>
                          <?php
                          $q_penumpang = mysqli_query($conn, "SELECT * FROM penumpang ORDER BY nama_penumpang");
                          while($d_penumpang = mysqli_fetch_array($q_penumpang)) {
                            $selected = (isset($_GET['id_penumpang']) && $_GET['id_penumpang'] == $d_penumpang['id_penumpang']) ? 'selected' : '';
                            echo "<option value='$d_penumpang[id_penumpang]' $selected>$d_penumpang[nama_penumpang]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-2 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                          <i class="fa fa-search me-2"></i>Cari
                        </button>
                        <?php if(
                          (isset($_GET['start_date']) && $_GET['start_date'] != date('Y-m-01')) ||
                          (isset($_GET['end_date']) && $_GET['end_date'] != date('Y-m-t')) ||
                          (isset($_GET['id_mobil']) && $_GET['id_mobil'] != '') ||
                          (isset($_GET['id_penyewa']) && $_GET['id_penyewa'] != '') ||
                          (isset($_GET['id_penumpang']) && $_GET['id_penumpang'] != '')
                        ) { ?>
                        <a href="transaksi.php" class="btn btn-secondary btn-sm">Reset</a>
                        <?php } ?>
                      </div>
                    </form>
                  </div>
                </div>
                

                <?php
                // Default filter untuk bulan ini
                $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
                $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
                $id_mobil = isset($_GET['id_mobil']) ? $_GET['id_mobil'] : '';
                $id_penyewa = isset($_GET['id_penyewa']) ? $_GET['id_penyewa'] : '';
                $id_penumpang = isset($_GET['id_penumpang']) ? $_GET['id_penumpang'] : '';

                $where = "WHERE 1=1";
                $where .= " AND DATE(t.tanggal_mulai) BETWEEN '$start_date' AND '$end_date'";
                if($id_mobil != '') {
                  $where .= " AND t.id_mobil='$id_mobil'";
                }
                if($id_penyewa != '') {
                  $where .= " AND t.id_penyewa='$id_penyewa'";
                }
                if($id_penumpang != '') {
                  $where .= " AND t.id_penumpang='$id_penumpang'";
                }

                $query = mysqli_query($conn, "SELECT t.*, p.nama_penyewa, m.nama_mobil, m.plat_nomor, d.nama_driver, pn.nama_penumpang 
                                              FROM transaksi t 
                                              JOIN penyewa p ON t.id_penyewa=p.id_penyewa
                                              JOIN mobil m ON t.id_mobil=m.id_mobil
                                              LEFT JOIN driver d ON t.id_driver=d.id_driver
                                              LEFT JOIN penumpang pn ON t.id_penumpang=pn.id_penumpang
                                              $where
                                              ORDER BY t.tanggal_mulai DESC");
                while($data = mysqli_fetch_array($query)) {
                ?>
                <div class="card shadow-sm mb-3">
                  <div class="card-body p-3">
                    <div class="row">
                      <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                          <div class="icon-shape icon-sm me-2 bg-gradient-primary shadow text-center">
                            <i class="fa fa-user text-white opacity-10"></i>
                          </div>
                          <h6 class="mb-0"><?= $data['nama_penyewa'] ?></h6>
                        </div>
                        <p class="text-xs mb-1"><i class="fa fa-car text-primary me-1"></i><?= $data['nama_mobil'] ?> - <?= $data['plat_nomor'] ?></p>
                        <p class="text-xs mb-1"><i class="fa fa-id-card text-info me-1"></i><?= $data['nama_driver'] ? $data['nama_driver'] : 'Tanpa Driver' ?></p>
                        <p class="text-xs mb-1"><i class="fa fa-user-plus text-success me-1"></i><?= $data['nama_penumpang'] ? $data['nama_penumpang'] : 'Tanpa Penumpang' ?></p>
                        <p class="text-xs mb-1"><i class="fa fa-map-marker text-warning me-1"></i><?= $data['tujuan_sewa'] ?></p>
                        <div class="mt-2">
                          <span class="badge bg-gradient-<?= $data['status_sewa'] == 'Berlangsung' ? 'warning' : 'success' ?> me-1">
                            <?= $data['status_sewa'] ?>
                          </span>
                          <span class="badge bg-gradient-<?= $data['status_pembayaran'] == 'Lunas' ? 'success' : 'info' ?>">
                            <?= $data['status_pembayaran'] ?>
                          </span>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="d-flex align-items-center mb-2">
                          <div class="icon-shape icon-sm me-2 bg-gradient-success shadow text-center">
                            <i class="fa fa-calendar text-white opacity-10"></i>
                          </div>
                          <h6 class="mb-0">Periode Sewa</h6>
                        </div>
                        <p class="text-xs mb-1">
                          <i class="fa fa-calendar text-success me-1"></i>Mulai: <?= date('d/m/Y H:i', strtotime($data['tanggal_mulai'])) ?>
                        </p>
                        <p class="text-xs mb-1">
                          <i class="fa fa-calendar-check-o text-danger me-1"></i>Selesai: <?= date('d/m/Y H:i', strtotime($data['tanggal_selesai'])) ?>
                        </p>
                        <p class="text-xs mb-0">
                          <i class="fa fa-clock-o text-warning me-1"></i>Total: <?= $data['total_hari'] ?> hari
                        </p>
                      </div>
                      <div class="col-md-3">
                        <div class="d-flex align-items-center mb-2">
                          <div class="icon-shape icon-sm me-2 bg-gradient-danger shadow text-center">
                            <i class="fa fa-money text-white opacity-10"></i>
                          </div>
                          <h6 class="mb-0">Rincian Biaya</h6>
                        </div>
                        <p class="text-xs mb-1">Mobil: Rp <?= number_format($data['harga_mobil'],0,',','.') ?></p>
                        <p class="text-xs mb-1">Driver: Rp <?= number_format($data['harga_driver'],0,',','.') ?></p>
                        <p class="text-xs mb-1">Tambahan: Rp <?= number_format($data['total_biaya_tambahan'],0,',','.') ?></p>
                        <p class="text-xs font-weight-bold mb-0">Total: Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?></p>
                        <?php if($data['status_pembayaran'] == 'DP') { ?>
                        <p class="text-xs text-danger mb-0">Sisa: Rp <?= number_format($data['sisa_pembayaran'],0,',','.') ?></p>
                        <?php } ?>
                      </div>
                      <div class="col-md-1 text-end">
                        <div class="btn-group-vertical">
                          <button class="btn btn-info btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#detailModal<?= $data['id_transaksi'] ?>">
                            <i class="fa fa-list"></i>
                          </button>
                          <button class="btn btn-warning btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editModal<?= $data['id_transaksi'] ?>">
                            <i class="fa fa-edit"></i>
                          </button>
                          <button class="btn btn-primary btn-sm mb-1" data-bs-toggle="modal" data-bs-target="#editLengkapModal<?= $data['id_transaksi'] ?>">
                            <i class="fa fa-cogs"></i>
                          </button>
                          <button class="btn btn-danger btn-sm" onclick="if(confirm('Hapus transaksi ini?')) { document.getElementById('formHapus<?= $data['id_transaksi'] ?>').submit(); }">
                            <i class="fa fa-trash"></i>
                          </button>
                          <form id="formHapus<?= $data['id_transaksi'] ?>" action="" method="POST">
                            <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi'] ?>">
                            <input type="hidden" name="hapus" value="1">
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Modal Edit -->
                <div class="modal fade" id="editModal<?= $data['id_transaksi'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                          <i class="fa fa-edit text-warning me-2"></i>Edit Transaksi #<?= $data['id_transaksi'] ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <form action="" method="POST">
                        <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi'] ?>">
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="card shadow-none border mb-4">
                                <div class="card-body">
                                  <h6 class="text-primary mb-3"><i class="fa fa-money me-2"></i>Status Pembayaran</h6>
                                  <div class="form-group mb-3">
                                    <label class="form-label">Status Pembayaran</label>
                                    <select class="form-control" name="status_pembayaran" onchange="toggleDPEdit(this.value, <?= $data['id_transaksi'] ?>)" required>
                                      <option value="Lunas" <?= $data['status_pembayaran'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                                      <option value="DP" <?= $data['status_pembayaran'] == 'DP' ? 'selected' : '' ?>>DP (Uang Muka)</option>
                                    </select>
                                  </div>
                                  <div id="dpGroupEdit<?= $data['id_transaksi'] ?>" style="display: <?= $data['status_pembayaran'] == 'DP' ? 'block' : 'none' ?>;">
                                    <div class="form-group">
                                      <label class="form-label">Jumlah DP</label>
                                      <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control" name="jumlah_dp" value="<?= $data['jumlah_dp'] ?>" oninput="hitungSisaEdit(this.value, <?= $data['id_transaksi'] ?>)">
                                      </div>
                                    </div>
                                    <div class="alert alert-danger mt-3 mb-0">
                                      <div class="d-flex justify-content-between align-items-center">
                                        <span>Sisa Pembayaran:</span>
                                        <strong id="sisaEdit<?= $data['id_transaksi'] ?>">Rp <?= number_format($data['sisa_pembayaran'],0,',','.') ?></strong>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="card shadow-none border">
                                <div class="card-body">
                                  <h6 class="text-primary mb-3"><i class="fa fa-plus-circle me-2"></i>Biaya Tambahan</h6>
                                  <div class="row g-3">
                                    <?php
                                    $q_tipe = mysqli_query($conn, "SELECT * FROM tipe_biaya ORDER BY nama_tipe");
                                    while($d_tipe = mysqli_fetch_array($q_tipe)) {
                                      // Ambil jumlah biaya jika ada
                                      $biaya = mysqli_fetch_array(mysqli_query($conn, "SELECT jumlah FROM detail_biaya WHERE id_transaksi='$data[id_transaksi]' AND id_tipe='$d_tipe[id_tipe]'"));
                                    ?>
                                    <div class="col-12">
                                      <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div class="form-check mb-0">
                                          <input class="form-check-input" type="checkbox" id="biayaEdit<?= $data['id_transaksi'] ?>_<?= $d_tipe['id_tipe'] ?>" 
                                                 onchange="toggleBiayaEdit(<?= $data['id_transaksi'] ?>, <?= $d_tipe['id_tipe'] ?>)"
                                                 <?= $biaya ? 'checked' : '' ?>>
                                          <label class="form-check-label" for="biayaEdit<?= $data['id_transaksi'] ?>_<?= $d_tipe['id_tipe'] ?>"><?= $d_tipe['nama_tipe'] ?></label>
                                        </div>
                                        <div class="input-group input-group-sm w-50" id="group-jumlahEdit<?= $data['id_transaksi'] ?>_<?= $d_tipe['id_tipe'] ?>" 
                                             style="display: <?= $biaya ? 'flex' : 'none' ?>;">
                                          <span class="input-group-text bg-gradient-light">Rp</span>
                                          <input type="number" class="form-control text-end" name="biaya_tambahan[<?= $d_tipe['id_tipe'] ?>]" 
                                                 id="jumlahEdit<?= $data['id_transaksi'] ?>_<?= $d_tipe['id_tipe'] ?>" 
                                                 placeholder="0" value="<?= $biaya ? $biaya['jumlah'] : '' ?>"
                                                 oninput="hitungTotalEdit(<?= $data['id_transaksi'] ?>)">
                                        </div>
                                      </div>
                                      <hr class="my-2">
                                    </div>
                                    <?php } ?>
                                  </div>
                                  <div class="alert alert-info mt-3 mb-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                      <span class="text-sm">Total Biaya Tambahan:</span>
                                      <strong id="totalBiayaTambahanEdit<?= $data['id_transaksi'] ?>" class="text-info">
                                        Rp <?= number_format($data['total_biaya_tambahan'],0,',','.') ?>
                                      </strong>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                      <span class="text-sm">Total Keseluruhan:</span>
                                      <strong class="text-primary">
                                        Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?>
                                      </strong>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
                          <button type="submit" name="edit" class="btn bg-gradient-primary">
                            <i class="fa fa-save me-2"></i>Simpan Perubahan
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>

                <!-- Modal Detail -->
                <div class="modal fade" id="detailModal<?= $data['id_transaksi'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                  <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title d-flex align-items-center">
                          <i class="fa fa-info-circle text-info me-2"></i>Detail Transaksi #<?= $data['id_transaksi'] ?>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <div class="row">
                          <div class="col-md-6 mb-3 mb-md-0">
                            <h6 class="text-primary">Informasi Penyewaan</h6>
                            <ul class="list-group">
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-user fa-fw me-2 text-warning"></i>Penyewa</span>
                                <strong class="text-dark"><?= $data['nama_penyewa'] ?></strong>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-car fa-fw me-2 text-success"></i>Mobil</span>
                                <strong class="text-dark"><?= $data['nama_mobil'] ?></strong>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-id-card fa-fw me-2 text-info"></i>Driver</span>
                                <strong class="text-dark"><?= $data['nama_driver'] ? $data['nama_driver'] : '-' ?></strong>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-user-plus fa-fw me-2 text-success"></i>Penumpang</span>
                                <strong class="text-dark"><?= $data['nama_penumpang'] ? $data['nama_penumpang'] : '-' ?></strong>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-map-marker fa-fw me-2 text-warning"></i>Tujuan Sewa</span>
                                <strong class="text-dark"><?= $data['tujuan_sewa'] ?></strong>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-calendar fa-fw me-2 text-primary"></i>Mulai Sewa</span>
                                <strong class="text-dark"><?= date('d/m/Y H:i', strtotime($data['tanggal_mulai'])) ?></strong>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-calendar-check-o fa-fw me-2 text-danger"></i>Selesai Sewa</span>
                                <strong class="text-dark"><?= date('d/m/Y H:i', strtotime($data['tanggal_selesai'])) ?></strong>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="fa fa-clock-o fa-fw me-2 text-secondary"></i>Total Hari</span>
                                <strong class="text-dark"><?= $data['total_hari'] ?> hari</strong>
                              </li>
                            </ul>
                          </div>
                          <div class="col-md-6">
                            <h6 class="text-primary">Informasi Biaya</h6>
                            <ul class="list-group">
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Biaya Mobil</span>
                                <span>Rp <?= number_format($data['harga_mobil'],0,',','.') ?></span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Biaya Driver</span>
                                <span>Rp <?= number_format($data['harga_driver'],0,',','.') ?></span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Biaya Tambahan</span>
                                <span>Rp <?= number_format($data['total_biaya_tambahan'],0,',','.') ?></span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                <strong class="text-success">Total Keseluruhan</strong>
                                <strong class="text-success">Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?></strong>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>Status Pembayaran</span>
                                <span class="badge bg-gradient-<?= $data['status_pembayaran'] == 'Lunas' ? 'success' : 'warning' ?>"><?= $data['status_pembayaran'] ?></span>
                              </li>
                              <?php if($data['status_pembayaran'] == 'DP') { ?>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-primary">Jumlah DP</span>
                                <span class="text-primary">Rp <?= number_format($data['jumlah_dp'],0,',','.') ?></span>
                              </li>
                              <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span class="text-danger">Sisa Pembayaran</span>
                                <span class="text-danger">Rp <?= number_format($data['sisa_pembayaran'],0,',','.') ?></span>
                              </li>
                              <?php } ?>
                            </ul>
                          </div>
                        </div>
                        
                        <?php if($data['total_biaya_tambahan'] > 0) { ?>
                        <div class="mt-4">
                          <h6 class="text-primary">Detail Biaya Tambahan</h6>
                          <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered">
                              <thead class="bg-light">
                                <tr>
                                  <th>Tipe Biaya</th>
                                  <th class="text-end">Jumlah</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $total_biaya_tambahan = 0;
                                $query_detail = mysqli_query($conn, "SELECT db.*, tb.nama_tipe 
                                                                      FROM detail_biaya db 
                                                                      JOIN tipe_biaya tb ON db.id_tipe=tb.id_tipe 
                                                                      WHERE db.id_transaksi='" . $data['id_transaksi'] . "'");
                                while($detail = mysqli_fetch_array($query_detail)) {
                                  $total_biaya_tambahan += $detail['jumlah'];
                                ?>
                                <tr>
                                  <td><?= $detail['nama_tipe'] ?></td>
                                  <td class="text-end">Rp <?= number_format($detail['jumlah'],0,',','.') ?></td>
                                </tr>
                                <?php } ?>
                                <tr class="bg-light">
                                  <th>Total Biaya Tambahan</th>
                                  <th class="text-end">Rp <?= number_format($total_biaya_tambahan,0,',','.') ?></th>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </div>
                        <?php } ?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </main>

  <div class="modal fade" id="tambahModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="fa fa-plus text-primary me-2"></i>Tambah Transaksi Baru
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST">
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-8">
                <div class="card shadow-none border mb-4">
                  <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fa fa-user me-2"></i>1. Data Penyewaan</h6>
                    <div class="row g-3">
                      <div class="col-12">
                        <label class="form-label">Penyewa</label>
                        <select class="form-control select2" name="id_penyewa" required>
                          <option value="">Pilih Penyewa...</option>
                          <?php
                          $q_penyewa = mysqli_query($conn, "SELECT * FROM penyewa ORDER BY nama_penyewa");
                          while($d_penyewa = mysqli_fetch_array($q_penyewa)) {
                            echo "<option value='$d_penyewa[id_penyewa]'>$d_penyewa[nama_penyewa]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Mobil</label>
                        <select class="form-control select2" name="id_mobil" id="select-mobil" required>
                          <option value="">Pilih Mobil...</option>
                          <?php
                          $query = mysqli_query($conn, "SELECT * FROM mobil ORDER BY nama_mobil");
                          while($data = mysqli_fetch_array($query)) {
                            echo "<option value='$data[id_mobil]'>$data[nama_mobil] - $data[plat_nomor]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Driver (Opsional)</label>
                        <select class="form-control select2" name="id_driver">
                          <option value="">Tanpa Driver</option>
                          <?php
                          $query = mysqli_query($conn, "SELECT * FROM driver ORDER BY nama_driver");
                          while($data = mysqli_fetch_array($query)) {
                            echo "<option value='$data[id_driver]'>$data[nama_driver]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Harga Mobil</label>
                        <div class="input-group">
                          <span class="input-group-text">Rp</span>
                          <input type="number" class="form-control" name="harga_mobil" placeholder="0" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Harga Driver (Opsional)</label>
                        <div class="input-group">
                          <span class="input-group-text">Rp</span>
                          <input type="number" class="form-control" name="harga_driver" placeholder="0" value="0">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Penumpang (Opsional)</label>
                        <select class="form-control select2" name="id_penumpang">
                          <option value="">Tanpa Penumpang</option>
                          <?php
                          $q_penumpang = mysqli_query($conn, "SELECT * FROM penumpang ORDER BY nama_penumpang");
                          while($d_penumpang = mysqli_fetch_array($q_penumpang)) {
                            echo "<option value='$d_penumpang[id_penumpang]'>$d_penumpang[nama_penumpang]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-12">
                        <label class="form-label">Tujuan Sewa</label>
                        <textarea class="form-control" name="tujuan_sewa" placeholder="Masukkan tujuan sewa..." required></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card shadow-none border mb-4">
                  <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fa fa-calendar me-2"></i>2. Waktu Sewa</h6>
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Tanggal & Jam Mulai</label>
                        <div class="input-group">
                          <input type="date" class="form-control" name="tanggal_mulai" onchange="hitungTotal()" required>
                          <input type="time" class="form-control" name="jam_mulai" onchange="hitungTotal()" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Tanggal & Jam Selesai</label>
                        <div class="input-group">
                          <input type="date" class="form-control" name="tanggal_selesai" onchange="hitungTotal()" required>
                          <input type="time" class="form-control" name="jam_selesai" onchange="hitungTotal()" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card shadow-none border">
                  <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fa fa-plus-circle me-2"></i>3. Biaya Tambahan (Opsional)</h6>
                    <div class="row g-3">
                      <?php
                      $q_tipe = mysqli_query($conn, "SELECT * FROM tipe_biaya ORDER BY nama_tipe");
                      while($d_tipe = mysqli_fetch_array($q_tipe)) {
                      ?>
                      <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                          <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="biaya<?= $d_tipe['id_tipe'] ?>" 
                                   onchange="toggleBiaya(<?= $d_tipe['id_tipe'] ?>)">
                            <label class="form-check-label" for="biaya<?= $d_tipe['id_tipe'] ?>"><?= $d_tipe['nama_tipe'] ?></label>
                          </div>
                          <div class="input-group input-group-sm w-50" id="group-jumlah<?= $d_tipe['id_tipe'] ?>" style="display: none;">
                            <span class="input-group-text bg-gradient-light">Rp</span>
                            <input type="number" class="form-control text-end biaya-tambahan" name="biaya_tambahan[<?= $d_tipe['id_tipe'] ?>]" 
                                   id="jumlah<?= $d_tipe['id_tipe'] ?>" placeholder="0" value="0"
                                   oninput="hitungTotal()">
                          </div>
                        </div>
                        <hr class="my-2">
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="card shadow-none border position-sticky" style="top: 1rem;">
                  <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fa fa-calculator me-2"></i>Rincian Biaya</h6>
                    <ul class="list-group mb-4">
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Lama Sewa</span>
                        <strong id="estimasi-hari">0 Hari</strong>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Biaya Mobil</span>
                        <strong id="estimasi-mobil">Rp 0</strong>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Biaya Driver</span>
                        <strong id="estimasi-driver">Rp 0</strong>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Biaya Tambahan</span>
                        <strong id="estimasi-tambahan">Rp 0</strong>
                      </li>
                      <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                        <strong>Total Biaya</strong>
                        <strong id="estimasi-total">Rp 0</strong>
                      </li>
                    </ul>

                    <h6 class="text-primary mb-3"><i class="fa fa-money me-2"></i>4. Pembayaran</h6>
                    <div class="form-group mb-3">
                      <label class="form-label">Status Pembayaran</label>
                      <select class="form-control" name="status_pembayaran" id="status-pembayaran" required>
                        <option value="Lunas">Lunas</option>
                        <option value="DP">DP (Uang Muka)</option>
                      </select>
                    </div>
                    <div id="dpGroup" style="display: none;">
                      <div class="form-group">
                        <label class="form-label">Jumlah DP</label>
                        <div class="input-group">
                          <span class="input-group-text">Rp</span>
                          <input type="number" class="form-control" name="jumlah_dp" id="jumlah-dp">
                        </div>
                      </div>
                      <div class="alert alert-danger mt-3 mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                          <span>Sisa Pembayaran:</span>
                          <strong id="sisa-pembayaran">Rp 0</strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="tambah" class="btn bg-gradient-primary">
              <i class="fa fa-save me-2"></i>Simpan Transaksi
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <!-- Modal Edit Lengkap -->
  <?php
  // Reset query untuk modal edit lengkap
  $query = mysqli_query($conn, "SELECT t.*, p.nama_penyewa, m.nama_mobil, m.plat_nomor, d.nama_driver, pn.nama_penumpang 
                                FROM transaksi t 
                                JOIN penyewa p ON t.id_penyewa=p.id_penyewa
                                JOIN mobil m ON t.id_mobil=m.id_mobil
                                LEFT JOIN driver d ON t.id_driver=d.id_driver
                                LEFT JOIN penumpang pn ON t.id_penumpang=pn.id_penumpang
                                $where
                                ORDER BY t.tanggal_mulai DESC");
  while($data = mysqli_fetch_array($query)) {
  ?>
  <div class="modal fade" id="editLengkapModal<?= $data['id_transaksi'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title d-flex align-items-center">
            <i class="fa fa-cogs text-primary me-2"></i>Edit Lengkap Transaksi #<?= $data['id_transaksi'] ?>
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form action="" method="POST">
          <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi'] ?>">
          <div class="modal-body">
            <div class="row">
              <div class="col-lg-8">
                <div class="card shadow-none border mb-4">
                  <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fa fa-user me-2"></i>1. Data Penyewaan</h6>
                    <div class="row g-3">
                      <div class="col-12">
                        <label class="form-label">Penyewa</label>
                        <select class="form-control select2" name="id_penyewa" required>
                          <option value="">Pilih Penyewa...</option>
                          <?php
                          $q_penyewa = mysqli_query($conn, "SELECT * FROM penyewa ORDER BY nama_penyewa");
                          while($d_penyewa = mysqli_fetch_array($q_penyewa)) {
                            $selected = ($data['id_penyewa'] == $d_penyewa['id_penyewa']) ? 'selected' : '';
                            echo "<option value='$d_penyewa[id_penyewa]' $selected>$d_penyewa[nama_penyewa]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Mobil</label>
                        <select class="form-control select2" name="id_mobil" id="select-mobil-edit<?= $data['id_transaksi'] ?>" required>
                          <option value="">Pilih Mobil...</option>
                          <?php
                          $q_mobil = mysqli_query($conn, "SELECT * FROM mobil ORDER BY nama_mobil");
                          while($d_mobil = mysqli_fetch_array($q_mobil)) {
                            $selected = ($data['id_mobil'] == $d_mobil['id_mobil']) ? 'selected' : '';
                            echo "<option value='$d_mobil[id_mobil]' $selected>$d_mobil[nama_mobil] - $d_mobil[plat_nomor]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Driver (Opsional)</label>
                        <select class="form-control select2" name="id_driver" id="select-driver-edit<?= $data['id_transaksi'] ?>">
                          <option value="">Tanpa Driver</option>
                          <?php
                          $q_driver = mysqli_query($conn, "SELECT * FROM driver ORDER BY nama_driver");
                          while($d_driver = mysqli_fetch_array($q_driver)) {
                            $selected = ($data['id_driver'] == $d_driver['id_driver']) ? 'selected' : '';
                            echo "<option value='$d_driver[id_driver]' $selected>$d_driver[nama_driver]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Harga Mobil</label>
                        <div class="input-group">
                          <span class="input-group-text">Rp</span>
                          <input type="number" class="form-control" name="harga_mobil" placeholder="0" value="<?= $data['harga_mobil'] ?>" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Harga Driver (Opsional)</label>
                        <div class="input-group">
                          <span class="input-group-text">Rp</span>
                          <input type="number" class="form-control" name="harga_driver" placeholder="0" value="<?= $data['harga_driver'] ?>">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Penumpang (Opsional)</label>
                        <select class="form-control select2" name="id_penumpang">
                          <option value="">Tanpa Penumpang</option>
                          <?php
                          $q_penumpang = mysqli_query($conn, "SELECT * FROM penumpang ORDER BY nama_penumpang");
                          while($d_penumpang = mysqli_fetch_array($q_penumpang)) {
                            $selected = ($data['id_penumpang'] == $d_penumpang['id_penumpang']) ? 'selected' : '';
                            echo "<option value='$d_penumpang[id_penumpang]' $selected>$d_penumpang[nama_penumpang]</option>";
                          }
                          ?>
                        </select>
                      </div>
                      <div class="col-12">
                        <label class="form-label">Tujuan Sewa</label>
                        <textarea class="form-control" name="tujuan_sewa" placeholder="Masukkan tujuan sewa..." required><?= $data['tujuan_sewa'] ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card shadow-none border mb-4">
                  <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fa fa-calendar me-2"></i>2. Waktu Sewa</h6>
                    <div class="row g-3">
                      <div class="col-md-6">
                        <label class="form-label">Tanggal & Jam Mulai</label>
                        <div class="input-group">
                          <input type="date" class="form-control" name="tanggal_mulai" value="<?= date('Y-m-d', strtotime($data['tanggal_mulai'])) ?>" required>
                          <input type="time" class="form-control" name="jam_mulai" value="<?= date('H:i', strtotime($data['tanggal_mulai'])) ?>" required>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label class="form-label">Tanggal & Jam Selesai</label>
                        <div class="input-group">
                          <input type="date" class="form-control" name="tanggal_selesai" value="<?= date('Y-m-d', strtotime($data['tanggal_selesai'])) ?>" required>
                          <input type="time" class="form-control" name="jam_selesai" value="<?= date('H:i', strtotime($data['tanggal_selesai'])) ?>" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card shadow-none border">
                  <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fa fa-plus-circle me-2"></i>3. Biaya Tambahan (Opsional)</h6>
                    <div class="row g-3">
                      <?php
                      $q_tipe = mysqli_query($conn, "SELECT * FROM tipe_biaya ORDER BY nama_tipe");
                      while($d_tipe = mysqli_fetch_array($q_tipe)) {
                        // Ambil jumlah biaya jika ada
                        $biaya = mysqli_fetch_array(mysqli_query($conn, "SELECT jumlah FROM detail_biaya WHERE id_transaksi='$data[id_transaksi]' AND id_tipe='$d_tipe[id_tipe]'"));
                      ?>
                      <div class="col-12">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                          <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" id="biayaLengkap<?= $data['id_transaksi'] ?>_<?= $d_tipe['id_tipe'] ?>" 
                                   onchange="toggleBiayaLengkap(<?= $data['id_transaksi'] ?>, <?= $d_tipe['id_tipe'] ?>)"
                                   <?= $biaya ? 'checked' : '' ?>>
                            <label class="form-check-label" for="biayaLengkap<?= $data['id_transaksi'] ?>_<?= $d_tipe['id_tipe'] ?>">
                              <?= $d_tipe['nama_tipe'] ?>
                            </label>
                          </div>
                          <div class="input-group input-group-sm" style="width: 200px; display: <?= $biaya ? 'flex' : 'none' ?>;" 
                               id="group-jumlahLengkap<?= $data['id_transaksi'] ?>_<?= $d_tipe['id_tipe'] ?>">
                            <span class="input-group-text bg-gradient-light">Rp</span>
                            <input type="number" class="form-control text-end biaya-tambahan-lengkap" name="biaya_tambahan[<?= $d_tipe['id_tipe'] ?>]" 
                                   id="jumlahLengkap<?= $data['id_transaksi'] ?>_<?= $d_tipe['id_tipe'] ?>" 
                                   placeholder="0" value="<?= $biaya ? $biaya['jumlah'] : '' ?>">
                          </div>
                        </div>
                        <hr class="my-2">
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-lg-4">
                <div class="card shadow-none border position-sticky" style="top: 1rem;">
                  <div class="card-body">
                    <h6 class="text-primary mb-3"><i class="fa fa-calculator me-2"></i>Rincian Biaya</h6>
                    <ul class="list-group mb-4">
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Lama Sewa</span>
                        <strong id="estimasi-hari-lengkap<?= $data['id_transaksi'] ?>"><?= $data['total_hari'] ?> Hari</strong>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Biaya Mobil</span>
                        <strong id="estimasi-mobil-lengkap<?= $data['id_transaksi'] ?>">Rp <?= number_format($data['harga_mobil'],0,',','.') ?></strong>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Biaya Driver</span>
                        <strong id="estimasi-driver-lengkap<?= $data['id_transaksi'] ?>">Rp <?= number_format($data['harga_driver'],0,',','.') ?></strong>
                      </li>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>Biaya Tambahan</span>
                        <strong id="estimasi-tambahan-lengkap<?= $data['id_transaksi'] ?>">Rp <?= number_format($data['total_biaya_tambahan'],0,',','.') ?></strong>
                      </li>
                      <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                        <strong>Total Biaya</strong>
                        <strong id="estimasi-total-lengkap<?= $data['id_transaksi'] ?>">Rp <?= number_format($data['total_keseluruhan'],0,',','.') ?></strong>
                      </li>
                    </ul>

                    <h6 class="text-primary mb-3"><i class="fa fa-money me-2"></i>4. Pembayaran</h6>
                    <div class="form-group mb-3">
                      <label class="form-label">Status Pembayaran</label>
                      <select class="form-control" name="status_pembayaran" id="status-pembayaran-lengkap<?= $data['id_transaksi'] ?>" onchange="toggleDPLengkap(this.value, <?= $data['id_transaksi'] ?>)" required>
                        <option value="Lunas" <?= $data['status_pembayaran'] == 'Lunas' ? 'selected' : '' ?>>Lunas</option>
                        <option value="DP" <?= $data['status_pembayaran'] == 'DP' ? 'selected' : '' ?>>DP (Uang Muka)</option>
                      </select>
                    </div>
                    <div id="dpGroupLengkap<?= $data['id_transaksi'] ?>" style="display: <?= $data['status_pembayaran'] == 'DP' ? 'block' : 'none' ?>;">
                      <div class="form-group">
                        <label class="form-label">Jumlah DP</label>
                        <div class="input-group">
                          <span class="input-group-text">Rp</span>
                          <input type="number" class="form-control" name="jumlah_dp" id="jumlah-dp-lengkap<?= $data['id_transaksi'] ?>" value="<?= $data['jumlah_dp'] ?>">
                        </div>
                      </div>
                      <div class="alert alert-danger mt-3 mb-0">
                        <div class="d-flex justify-content-between align-items-center">
                          <span>Sisa Pembayaran:</span>
                          <strong id="sisa-pembayaran-lengkap<?= $data['id_transaksi'] ?>">Rp <?= number_format($data['sisa_pembayaran'],0,',','.') ?></strong>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" name="edit_lengkap" class="btn bg-gradient-primary">
              <i class="fa fa-save me-2"></i>Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php } ?>

</body>
</html>