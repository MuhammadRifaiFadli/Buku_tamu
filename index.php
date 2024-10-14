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
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

* {
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    line-height: 1.6;
    margin: 0;
    padding: 10px;
    background: linear-gradient(to right, #ff9966, #ff5e62);
    font-weight: 400;
}

.container {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

h1 {
    text-align: center;
    color: #333;
    font-size: 28px;
    margin-bottom: 24px;
    font-weight: 700;
}

form {
    margin-bottom: 24px;
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #444;
}

input[type="text"],
input[type="date"],
select {
    width: 100%;
    padding: 12px 16px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s ease;
}

input[type="text"]:focus,
input[type="date"]:focus,
select:focus {
    outline: none;
    border-color: #ff9966;
}

select {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 40px;
}

button {
    background-color: #4CAF50;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    font-weight: 500;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #45a049;
}

button.hapus {
    background-color: #f44336;
}

button.hapus:hover {
    background-color: #da190b;
}

.table-container {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 300px;
}

th, td {
    padding: 14px 10px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f7f7f7;
    font-weight: 600;
    color: #333;
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-buttons button {
    width: auto;
    padding: 8px 16px;
}

@media screen and (max-width: 600px) {
    .container {
        padding: 15px;
    }
    
    h1 {
        font-size: 24px;
    }
    
    th, td {
        padding: 10px 6px;
        font-size: 14px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons button {
        width: 100%;
        margin-bottom: 6px;
    }
    
    /* Responsive table */
    table, thead, tbody, th, td, tr {
        display: block;
    }
    
    thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    
    tr {
        margin-bottom: 16px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }
    
    td {
        border: none;
        position: relative;
        padding-left: 50%;
        text-align: right;
        border-bottom: 1px solid #eee;
    }
    
    td:before {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
        text-align: left;
        font-weight: 500;
        content: attr(data-label);
        color: #666;
    }
}
</style>
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
                <input type="text" id="nama" name="nama" value="<?php echo $tamu_edit ? htmlspecialchars($tamu_edit['nama_tamu']) : ''; ?>" required>
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