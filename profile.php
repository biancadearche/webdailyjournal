<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "koneksi.php";

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

$username = $_SESSION['username']; 

// ambil data user
$query = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "Data user tidak ditemukan";
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sora Cafe | Profile</title>
    <link rel="icon" href="img/logo.png"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<div class="container mt-4">
    <h3>Profile User</h3>

    <form method="post" enctype="multipart/form-data">

        <!-- Username -->
        <div class="mb-3">
            <label>Username</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']); ?>" readonly>
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label>Password Baru</label>
            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti">
        </div>

        <!-- Foto -->
        <div class="mb-3">
            <label>Ganti Foto Profil</label><br>
            <?php if (!empty($user['foto'])) { ?>
                <img src="img/<?= $user['foto']; ?>" width="200" class="mb-2"><br>
            <?php } ?>
            <input type="file" name="foto" class="form-control">
            <input type="hidden" name="foto_lama" value="<?= $user['foto']; ?>">
        </div><br>    
        <button type="submit" name="update" class="btn btn-primary">
            Simpan Perubahan
        </button>
    </form>
</div>

<?php
if (isset($_POST['update'])) {

    // update password
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE user SET password='$password' WHERE username='$username'");
    }

    // update foto
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . "_" . $_FILES['foto']['name'];
        $tmp  = $_FILES['foto']['tmp_name'];

        move_uploaded_file($tmp, "img/" . $foto);

        if (!empty($_POST['foto_lama'])) {
            unlink("img/" . $_POST['foto_lama']);
        }

        mysqli_query($conn, "UPDATE user SET foto='$foto' WHERE username='$username'");
    }

    echo "<script>
        alert('Profile berhasil diperbarui');
        location.href='admin.php?page=profile';
    </script>";
}
?>
</body>
</html>