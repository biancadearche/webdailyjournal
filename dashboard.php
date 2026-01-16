<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "koneksi.php";

// cek login
if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit;
}

$username = $_SESSION['username'];

// ambil data user
$query = mysqli_query($conn, "SELECT username, foto FROM user WHERE username='$username'");
$user = mysqli_fetch_assoc($query);
?>

<style>
.dashboard-card {
    border: 1px solid #f1b5c0;
    border-radius: 10px;
    padding: 20px;
    margin-top: 20px;
}
</style>

<div class="text-center mt-4">
    <h4>Selamat Datang,</h4>
    <h3 class="fw-bold text-secondary"><?= htmlspecialchars($user['username']); ?></h3>

    <!-- Foto Profil -->
    <?php if (!empty($user['foto'])) { ?>
        <img src="img/<?= $user['foto']; ?>"
             class="rounded-circle shadow"
             width="150"
             height="150"
             style="object-fit: cover;">
    <?php } else { ?>
        <img src="img/default.png"
             class="rounded-circle shadow"
             width="100"
             height="100">
    <?php } ?>
</div>

<?php
//query untuk mengambil data article
$sql1 = "SELECT * FROM article ORDER BY tanggal DESC";
$hasil1 = $conn->query($sql1);

//menghitung jumlah baris data article
$jumlah_article = $hasil1->num_rows; 
?>

<?php
//query untuk mengambil data gallery
$sql1 = "SELECT * FROM gallery ORDER BY tanggal DESC";
$hasil1 = $conn->query($sql1);

//menghitung jumlah baris data gallery
$jumlah_gallery = $hasil1->num_rows; 
?>
<div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center pt-4">
    <div class="col">
        <div class="card border border-secondary-subtle mb-3 shadow" style="max-width: 18rem;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="p-3">
                        <h5 class="card-title"><i class="bi bi-newspaper"></i> Article</h5> 
                    </div>
                    <div class="p-3">
                        <span class="badge rounded-pill text-secondary-emphasis fs-2"><?php echo $jumlah_article; ?></span>
                    </div> 
                </div>
            </div>
        </div>
    </div> 
    <div class="col">
        <div class="card border border-secondary-subtle mb-3 shadow" style="max-width: 18rem;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="p-3">
                        <h5 class="card-title"><i class="bi bi-camera"></i> Gallery</h5> 
                    </div>
                    <div class="p-3">
                        <span class="badge rounded-pill text-secondary-emphasis fs-2"><?php echo $jumlah_gallery; ?></span>
                    </div> 
                </div>
            </div>
        </div>
    </div> 
</div> 