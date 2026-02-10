<?php
session_start();
include "../config/koneksi.php";

// CEK ADMIN
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
}

// SIMPAN ANGGOTA
if (isset($_POST['simpan'])) {
    mysqli_query($conn, "INSERT INTO users VALUES(
        NULL,
        '$_POST[nama]',
        '$_POST[username]',
        '$_POST[password]',
        'anggota'
    )");

    echo "<script>alert('Anggota berhasil ditambahkan!');window.location='anggota.php';</script>";
}

// AMBIL DATA UNTUK EDIT
if (isset($_GET['edit'])) {
    $id_edit = $_GET['edit'];
    $edit = mysqli_query($conn, "SELECT * FROM users WHERE id='$id_edit'");
    $data_edit = mysqli_fetch_assoc($edit);
}

// UPDATE ANGGOTA
if (isset($_POST['update'])) {

    // kalau password diisi → update password
    if (!empty($_POST['password'])) {
        mysqli_query($conn, "UPDATE users SET
            nama='$_POST[nama]',
            username='$_POST[username]',
            password='$_POST[password]'
            WHERE id='$_POST[id]'
        ");
    } else {
        // kalau password kosong → tidak diubah
        mysqli_query($conn, "UPDATE users SET
            nama='$_POST[nama]',
            username='$_POST[username]'
            WHERE id='$_POST[id]'
        ");
    }

    echo "<script>alert('Anggota berhasil diupdate!');window.location='anggota.php';</script>";
}

// HAPUS ANGGOTA
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$id_hapus'");
    echo "<script>alert('Anggota berhasil dihapus!');window.location='anggota.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Kelola Anggota</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="../assets/style.css">
</head>

<body>

<div class="container">
<div class="dashboard-card">
<h2>👥 Kelola Anggota</h2>

<!-- FORM TAMBAH / EDIT ANGGOTA -->
<form method="POST">
    <input type="hidden" name="id" value="<?= isset($data_edit) ? $data_edit['id'] : '' ?>">

    <input name="nama" placeholder="Nama Lengkap" required
        value="<?= isset($data_edit) ? $data_edit['nama'] : '' ?>">

    <input name="username" placeholder="Username" required
        value="<?= isset($data_edit) ? $data_edit['username'] : '' ?>">

    <input type="password" name="password"
        placeholder="<?= isset($data_edit) ? 'Password baru (opsional)' : 'Password' ?>"
        <?= isset($data_edit) ? '' : 'required' ?>>

    <?php if (isset($data_edit)) { ?>
        <button name="update">💾 Update Anggota</button>
        <a href="anggota.php" style="display:block;margin-top:10px;">❌ Batal</a>
    <?php } else { ?>
        <button name="simpan">➕ Tambah Anggota</button>
    <?php } ?>
</form>

<!-- TABEL DATA ANGGOTA -->
<table>
<tr>
    <th>Nama</th>
    <th>Username</th>
    <th>Role</th>
    <th>Aksi</th>
</tr>

<?php
$data = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
while ($u = mysqli_fetch_assoc($data)) {
?>
<tr>
    <td><?= $u['nama'] ?></td>
    <td><?= $u['username'] ?></td>
    <td><?= $u['role'] ?></td>
    <td>
        <a href="anggota.php?edit=<?= $u['id'] ?>">✏️ Edit</a>
        <a href="anggota.php?hapus=<?= $u['id'] ?>"
           onclick="return confirm('Yakin ingin menghapus anggota ini?')"
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
