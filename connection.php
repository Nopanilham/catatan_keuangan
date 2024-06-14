<?php 
	include 'include/js.php';
	
	session_start();
	
	date_default_timezone_set("Asia/Jakarta");

	$host		= "localhost";
	$username	= "root";
	$password	= "";
	$database	= "uang_kas";

	$conn 		= mysqli_connect($host, $username, $password, $database);
	if ($conn) {
		// echo "berhasil terkoneksi!";
	} else {
		echo "gagal terkoneksi!";
	}

// ======================================== FUNCTION ========================================
function setAlert($title='', $text='', $type='', $buttons='') {
	$_SESSION["alert"]["title"]		= $title;
	$_SESSION["alert"]["text"] 		= $text;
	$_SESSION["alert"]["type"] 		= $type;
	$_SESSION["alert"]["buttons"]	= $buttons; 
}

if (isset($_SESSION['alert'])) {
	$title 		= $_SESSION["alert"]["title"];
	$text 		= $_SESSION["alert"]["text"];
	$type 		= $_SESSION["alert"]["type"];
	$buttons	= $_SESSION["alert"]["buttons"];

	echo"
		<div id='msg' data-title='".$title."' data-type='".$type."' data-text='".$text."' data-buttons='".$buttons."'></div>
		<script>
			let title 		= $('#msg').data('title');
			let type 		= $('#msg').data('type');
			let text 		= $('#msg').data('text');
			let buttons		= $('#msg').data('buttons');

			if(text != '' && type != '' && title != '') {
				Swal.fire({
					title: title,
					text: text,
					icon: type,
				});
			}
		</script>
	";
	unset($_SESSION["alert"]);
}

function checkLogin() {
	if (!isset($_SESSION['id_user'])) {
		setAlert("Access Denied!", "Login First!", "error");
		header('Location: login.php');
	} 
}

function checkLoginAtLogin() {
	if (isset($_SESSION['id_user'])) {
		setAlert("You has been logged!", "Welcome!", "success");
		header('Location: index.php');
	}
}

function logout() {
	setAlert("You has been logout!", "Success Logout!", "success");
	header("Location: login.php");
}

if (isset($_SESSION['id_user'])) {
	function dataUser() {
		global $conn;
		$id_user = $_SESSION['id_user'];
		return mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user INNER JOIN jabatan ON user.id_jabatan = jabatan.id_jabatan WHERE id_user = '$id_user'"));
	}
}


function editJabatan($data) {
	global $conn;
	$id_jabatan = htmlspecialchars($data['id_jabatan']);
  	$nama_jabatan = htmlspecialchars($data['nama_jabatan']);
  	$query = mysqli_query($conn, "UPDATE jabatan SET nama_jabatan = '$nama_jabatan' WHERE id_jabatan = '$id_jabatan'");
  	return mysqli_affected_rows($conn);
}

function checkJabatan() {
	$id_jabatan = $_SESSION['id_jabatan'];
	if ($id_jabatan !== '1') {
		setAlert("Access Denied!", "You cannot delete data except administrator!", "error");
     	header("Location: index.php");
	} else {
		return true;
	}
}

function deleteJabatan($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM jabatan WHERE id_jabatan = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}

function addJabatan($data) {
	global $conn;
	$nama_jabatan = htmlspecialchars($data['nama_jabatan']);
	$query = mysqli_query($conn, "INSERT INTO jabatan VALUES ('', '$nama_jabatan')");
  	return mysqli_affected_rows($conn);
}

function addUser($data) {
	global $conn;
	// check username already used or not
	$username = htmlspecialchars($data['username']);
	$query = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username'");
	if (mysqli_fetch_assoc($query)) {
		setAlert("Gagal Menambahkan User!", "Username Sudah Ada!", "error");
     	return header("Location: user.php");
	} else {
		$password = htmlspecialchars($data['password']);
		$password_verify = htmlspecialchars($data['password_verify']);
		if ($password !== $password_verify) {
			setAlert("Gagal Menambahkan User!", "password Tidak Sama!", "error");
	     	return header("Location: user.php");
		} else {
			$password = password_hash($password, PASSWORD_DEFAULT);
			$nama_lengkap = htmlspecialchars($data['nama_lengkap']);
			$id_jabatan = htmlspecialchars($data['id_jabatan']);
			$query = mysqli_query($conn, "INSERT INTO user VALUES ('', '$nama_lengkap', '$username', '$password', '$id_jabatan')");
		  	return mysqli_affected_rows($conn);
		}
	}
}

function editUser($data) {
	global $conn;
	$id_user = htmlspecialchars($data['id_user']);
	$nama_lengkap = htmlspecialchars(addslashes($data['nama_lengkap']));
  	$username = htmlspecialchars($data['username']);
  	$id_jabatan = htmlspecialchars($data['id_jabatan']);
  	$query = mysqli_query($conn, "UPDATE user SET nama_lengkap = '$nama_lengkap', username = '$username', id_jabatan = '$id_jabatan' WHERE id_user = '$id_user'");
  	return mysqli_affected_rows($conn);
}

function deleteUser($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM user WHERE id_user = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}


function changePassword($data) {
	global $conn;
	$id_user = $_SESSION['id_user'];
	$old_password = htmlspecialchars($data['old_password']);
	// check old password
	$dataUser = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id_user = '$id_user'"));
	if (password_verify($old_password, $dataUser['password'])) {
		$new_password = htmlspecialchars($data['new_password']);
		$new_password_verify = htmlspecialchars($data['new_password_verify']);
		if ($new_password == $new_password_verify) {
			$password = password_hash($new_password, PASSWORD_DEFAULT);
			$query = mysqli_query($conn, "UPDATE user SET password = '$password' WHERE id_user = '$id_user'");
	  		return mysqli_affected_rows($conn);
		} else {
			setAlert("Gagal Mengubah password!", "Password baru tidak sama!", "error");
	     	return header("Location: profile.php");
		}
	} else {
		setAlert("Gagal Mengubah password!", "Password Lama Tidak Sesuai!", "error");
     	return header("Location: profile.php");
	}
}

function addPengeluaran($data) {
	global $conn;
	$id_user = htmlspecialchars($_SESSION['id_user']);
	$jumlah_pengeluaran = htmlspecialchars($data['jumlah_pengeluaran']);
	$keterangan = htmlspecialchars($data['keterangan']);
	$query = mysqli_query($conn, "INSERT INTO pengeluaran VALUES ('', '$jumlah_pengeluaran', '$keterangan', '$id_user')");
  	return mysqli_affected_rows($conn);
}

function editPengeluaran($data) {
	global $conn;
	$id_user = htmlspecialchars($_SESSION['id_user']);
	$id_pengeluaran = htmlspecialchars($data['id_pengeluaran']);
	$fetch_sql = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pengeluaran WHERE id_pengeluaran = '$id_pengeluaran'"));
	$jumlah_pengeluaran = htmlspecialchars($data['jumlah_pengeluaran']);
	$keterangan = htmlspecialchars($data['keterangan']);
	$query = mysqli_query($conn, "UPDATE pengeluaran SET jumlah_pengeluaran = '$jumlah_pengeluaran', keterangan = '$keterangan', id_user = '$id_user' WHERE id_pengeluaran = '$id_pengeluaran'");
  	return mysqli_affected_rows($conn);
}

function deletePengeluaran($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM pengeluaran WHERE id_pengeluaran = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}

function addPemasukan($data) {
	global $conn;
	$id_user = htmlspecialchars($_SESSION['id_user']);
	$jumlah_pemasukan = htmlspecialchars($data['jumlah_pemasukan']);
	$keterangan = htmlspecialchars($data['keterangan']);
	$query = mysqli_query($conn, "INSERT INTO pemasukan VALUES ('', '$jumlah_pemasukan', '$keterangan', '$id_user')");
  	return mysqli_affected_rows($conn);
}


function editPemasukan($data) {
	global $conn;
	$id_user = htmlspecialchars($_SESSION['id_user']);
	$id_pemasukan = htmlspecialchars($data['id_pemasukan']);
	$fetch_sql = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pemasukan WHERE id_pemasukan = '$id_pemasukan'"));
	$jumlah_pemasukan = htmlspecialchars($data['jumlah_pemasukan']);
	$keterangan = htmlspecialchars($data['keterangan']);
	$query = mysqli_query($conn, "UPDATE pemasukan SET jumlah_pemasukan = '$jumlah_pemasukan', keterangan = '$keterangan', id_user = '$id_user' WHERE id_pemasukan = '$id_pemasukan'");
  	return mysqli_affected_rows($conn);
}


function deletePemasukan($id) {
	global $conn;
	if (checkJabatan() == true) {
		$query = mysqli_query($conn, "DELETE FROM pemasukan WHERE id_pemasukan = '$id'");
	  	return mysqli_affected_rows($conn);
	}
}
