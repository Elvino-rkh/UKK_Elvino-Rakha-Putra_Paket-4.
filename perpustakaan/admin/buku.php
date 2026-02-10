<?php
session_start();
include "../config/koneksi.php";

// CEK ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
}

// SIMPAN BUKU
if (isset($_POST['simpan'])) {
    mysqli_query($conn, "INSERT INTO buku VALUES(
        NULL,
        '$_POST[judul]',
        '$_POST[penulis]',
        '$_POST[tahun]',
        '$_POST[stok]'
    )");

    echo "<script>alert('Buku berhasil ditambahkan!');window.location='buku.php';</script>";
}

// AMBIL DATA UNTUK EDIT
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $edit = mysqli_query($conn, "SELECT * FROM buku WHERE id='$id_edit'");
    $data_edit = mysqli_fetch_assoc($edit);
}

// UPDATE BUKU
if (isset($_POST['update'])) {
    mysqli_query($conn, "UPDATE buku SET
        judul='$_POST[judul]',
        penulis='$_POST[penulis]',
        tahun='$_POST[tahun]',
        stok='$_POST[stok]'
        WHERE id='$_POST[id]'
    ");

    echo "<script>alert('Buku berhasil diupdate!');window.location='buku.php';</script>";
}

// HAPUS BUKU
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM buku WHERE id='$id_hapus'");
    echo "<script>alert('Buku berhasil dihapus!');window.location='buku.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Kelola Buku</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container">
<div class="dashboard-card">
<h2>📚 Kelola Data Buku</h2>

<!-- FORM TAMBAH / EDIT BUKU -->
<form method="POST">
    <input type="hidden" name="id" value="<?= isset($data_edit) ? $data_edit['id'] : '' ?>">

    <input name="judul" placeholder="Judul Buku" required
        value="<?= isset($data_edit) ? $data_edit['judul'] : '' ?>">

    <input name="penulis" placeholder="Penulis Buku" required
        value="<?= isset($data_edit) ? $data_edit['penulis'] : '' ?>">

    <input name="tahun" placeholder="Tahun Terbit" required
        value="<?= isset($data_edit) ? $data_edit['tahun'] : '' ?>">

    <input name="stok" placeholder="Stok Buku" required
        value="<?= isset($data_edit) ? $data_edit['stok'] : '' ?>">

    <?php if (isset($data_edit)) { ?>
        <button name="update">💾 Update Buku</button>
        <a href="buku.php" style="display:block;margin-top:10px;">❌ Batal</a>
    <?php } else { ?>
        <button name="simpan">➕ Simpan Buku</button>
    <?php } ?>
</form>

<!-- TABEL DATA BUKU -->
<table>
<tr>
    <th>Judul</th>
    <th>Penulis</th>
    <th>Tahun</th>
    <th>Stok</th>
    <th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM buku ORDER BY id DESC");
while ($b = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $b['judul'] ?></td>
    <td><?= $b['penulis'] ?></td>
    <td><?= $b['tahun'] ?></td>
    <td><?= $b['stok'] ?></td>
    <td>
        <a href="buku.php?edit=<?= $b['id'] ?>">✏️ Edit</a>
        <a href="buku.php?hapus=<?= $b['id'] ?>"
           onclick="return confirm('Yakin ingin menghapus buku ini?')"
           style="background:#dc3545;margin-left:5px;">
           🗑 Hapus
        </a>
    </td>
</tr>
<?php } ?>
</table>

<div class="nav" style="margin-top:15px;">
    <a href="dashboard.php">⬅ Kembali ke Dashboard</a>
</div>

</div>
</div>

</body>
</html>
