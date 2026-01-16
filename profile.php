<?php
// session & koneksi sudah dari admin.php
$username = $_SESSION['username'];

// ambil data user
$stmt = $conn->prepare("SELECT username, foto FROM user WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    echo "<div class='alert alert-danger'>Data user tidak ditemukan</div>";
    return;
}
?>

<form method="post" enctype="multipart/form-data">

    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control"
               value="<?= htmlspecialchars($user['username']) ?>" readonly>
    </div>

    <div class="mb-3">
        <label class="form-label">Ganti Password</label>
        <input type="password" class="form-control" name="password"
               placeholder="Kosongkan jika tidak ingin mengganti password">
    </div>

    <div class="mb-3">
        <label class="form-label">Ganti Foto Profil</label>
        <input type="file" class="form-control" name="foto">
    </div>

    <?php if (!empty($user['foto'])): ?>
        <div class="mb-3">
            <label class="form-label">Foto Profil Saat Ini</label><br>
            <img src="img/<?= $user['foto'] ?>" class="img-thumbnail" width="150">
            <input type="hidden" name="foto_lama" value="<?= $user['foto'] ?>">
        </div>
    <?php endif; ?>

    <button type="submit" name="simpan" class="btn btn-primary">
        Simpan
    </button>
</form>

<?php
if (isset($_POST['simpan'])) {

    // update password jika diisi
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE user SET password=? WHERE username=?");
        $stmt->bind_param("ss", $password, $username);
        $stmt->execute();
    }

    // update foto jika ada
    if (!empty($_FILES['foto']['name'])) {
        $foto = time().'_'.$_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "img/".$foto);

        if (!empty($_POST['foto_lama'])) {
            unlink("img/".$_POST['foto_lama']);
        }

        $stmt = $conn->prepare("UPDATE user SET foto=? WHERE username=?");
        $stmt->bind_param("ss", $foto, $username);
        $stmt->execute();
    }

    echo "<script>
        alert('Profil berhasil diperbarui');
        location.href='admin.php?page=profile';
    </script>";
}
?>
