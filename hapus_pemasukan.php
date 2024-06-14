<?php 
	require 'connection.php';
	$id_pemasukan = $_GET['id_pemasukan'];
	if (isset($id_pemasukan)) {
		if (deletePemasukan($id_pemasukan) > 0) {
			setAlert("Pemasukan Berhasil Dihapus", "Berhasil Menghapus Data", "success");
		    header("Location: pemasukan.php");
	    }
	} else {
	   header("Location: pemasukan.php");
	}
?>
