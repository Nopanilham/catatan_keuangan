<?php 
	require 'connection.php';
	$id_jabatan = $_GET['id_jabatan'];
	if (isset($id_jabatan)) {
		if (deleteJabatan($id_jabatan) > 0) {
			setAlert("Jabatan Berhasil Dihapus", "Berhasil Menghapus Data", "success");
		    header("Location: jabatan.php");
	    }
	} else {
	   header("Location: jabatan.php");
	}