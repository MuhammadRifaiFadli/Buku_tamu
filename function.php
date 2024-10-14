<?php
// function.php

$db = new SQLite3('guestbook.db');

// Check if the 'tujuan' column exists, if not, add it
$result = $db->query("PRAGMA table_info(buku_tamu)");
$columnExists = false;
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    if ($row['name'] == 'tujuan') {
        $columnExists = true;
        break;
    }
}

if (!$columnExists) {
    $db->exec('ALTER TABLE buku_tamu ADD COLUMN tujuan TEXT');
}

// Rest of the code remains the same
function tambahTamu($nama, $alamat, $tanggal, $tujuan) {
    global $db;
    $stmt = $db->prepare('INSERT INTO buku_tamu (nama_tamu, alamat, tanggal_kunjungan, tujuan) VALUES (:nama, :alamat, :tanggal, :tujuan)');
    $stmt->bindValue(':nama', $nama, SQLITE3_TEXT);
    $stmt->bindValue(':alamat', $alamat, SQLITE3_TEXT);
    $stmt->bindValue(':tanggal', $tanggal, SQLITE3_TEXT);
    $stmt->bindValue(':tujuan', $tujuan, SQLITE3_TEXT);
    return $stmt->execute();
}

function ambilSemuaTamu() {
    global $db;
    return $db->query('SELECT * FROM buku_tamu ORDER BY tanggal_kunjungan DESC');
}

function ambilTamu($id) {
    global $db;
    $stmt = $db->prepare('SELECT * FROM buku_tamu WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    return $stmt->execute()->fetchArray(SQLITE3_ASSOC);
}

function updateTamu($id, $nama, $alamat, $tanggal, $tujuan) {
    global $db;
    $stmt = $db->prepare('UPDATE buku_tamu SET nama_tamu = :nama, alamat = :alamat, tanggal_kunjungan = :tanggal, tujuan = :tujuan WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':nama', $nama, SQLITE3_TEXT);
    $stmt->bindValue(':alamat', $alamat, SQLITE3_TEXT);
    $stmt->bindValue(':tanggal', $tanggal, SQLITE3_TEXT);
    $stmt->bindValue(':tujuan', $tujuan, SQLITE3_TEXT);
    return $stmt->execute();
}

function hapusTamu($id) {
    global $db;
    $stmt = $db->prepare('DELETE FROM buku_tamu WHERE id = :id');
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    return $stmt->execute();
}