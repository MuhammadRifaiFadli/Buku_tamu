<?php
require_once 'function.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'tambah':
                tambahTamu($_POST['nama'], $_POST['alamat'], $_POST['tanggal'], $_POST['tujuan']);
                break;
            case 'update':
                updateTamu($_POST['id'], $_POST['nama'], $_POST['alamat'], $_POST['tanggal'], $_POST['tujuan']);
                break;
            case 'hapus':
                hapusTamu($_POST['id']);
                break;
        }
    }
    header('Location: index.php');
    exit;
}

$tamu_edit = null;
if (isset($_GET['edit'])) {
    $tamu_edit = ambilTamu($_GET['edit']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Tamu</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Buku Tamu</h1>
        
        <!-- Form Tambah/Edit -->
        <form method="POST">
            <input type="hidden" name="action" value="<?php echo $tamu_edit ? 'update' : 'tambah'; ?>">
            <?php if ($tamu_edit): ?>
                <input type="hidden" name="id" value="<?php echo $tamu_edit['id']; ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nama">Nama Tamu</label>
                <input type="text" id="nama" name="nama" value="<?php echo $tamu_edit ? htmlspecialchars($tamu_edit['nama_tamu']) : ''; ?>" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <input type="text" id="alamat" name="alamat" value="<?php echo $tamu_edit ? htmlspecialchars($tamu_edit['alamat']) : ''; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tanggal">Tanggal Kunjungan</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo $tamu_edit ? $tamu_edit['tanggal_kunjungan'] : date('Y-m-d'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="tujuan">Tujuan Kunjungan</label>
                <select id="tujuan" name="tujuan" required>
                    <option value="kunjungan" <?php echo ($tamu_edit && $tamu_edit['tujuan'] == 'kunjungan') ? 'selected' : ''; ?>>Kunjungan</option>
                    <option value="keperluan_lain" <?php echo ($tamu_edit && $tamu_edit['tujuan'] == 'keperluan_lain') ? 'selected' : ''; ?>>Keperluan Lain</option>
                </select>
            </div>
            
            <button type="submit"><?php echo $tamu_edit ? 'Update' : 'Tambah'; ?> Tamu</button>
        </form>

        <!-- Tabel Daftar Tamu -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Tamu</th>
                        <th>Alamat</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Tujuan Kunjungan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $result = ambilSemuaTamu();
                    while ($row = $result->fetchArray(SQLITE3_ASSOC)):
                    ?>
                    <tr>
                        <td data-label="Nama Tamu"><?php echo htmlspecialchars($row['nama_tamu']); ?></td>
                        <td data-label="Alamat"><?php echo htmlspecialchars($row['alamat']); ?></td>
                        <td data-label="Tanggal Kunjungan"><?php echo $row['tanggal_kunjungan']; ?></td>
                        <td data-label="Tujuan Kunjungan"><?php echo $row['tujuan'] == 'kunjungan' ? 'Kunjungan' : 'Keperluan Lain'; ?></td>
                        <td data-label="Aksi">
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $row['id']; ?>"><button type="button">Edit</button></a>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="hapus">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" class="hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>