<?php

declare(strict_types=1);

session_start();

require_once __DIR__ . '/models/TabelKandidat.php';
require_once __DIR__ . '/views/ViewKandidat.php';
require_once __DIR__ . '/presenters/PresenterKandidat.php';

require_once __DIR__ . '/models/TabelLowongan.php';
require_once __DIR__ . '/views/ViewLowongan.php';
require_once __DIR__ . '/presenters/PresenterLowongan.php';

/**
 * Menangani aksi POST CRUD untuk modul aktif dan mengembalikan hasil serta pesan status.
 *
 * @param array<string, mixed> $post
 * @return array{success: bool, message: string}
 */
function handlePostAction(
    string $module,
    array $post,
    KontrakPresenterKandidat $presenterKandidat,
    KontrakPresenterLowongan $presenterLowongan
): array {
    $action = (string) ($post['action'] ?? '');
    $labels = [
        'kandidat' => [
            'tambah' => ['Kandidat baru berhasil ditambahkan.', 'Gagal menambahkan kandidat. Periksa kembali isian form.'],
            'ubah' => ['Data kandidat berhasil diperbarui.', 'Gagal memperbarui data kandidat. Periksa kembali isian form.'],
            'hapus' => ['Data kandidat berhasil dihapus.', 'Gagal menghapus data kandidat.'],
        ],
        'lowongan' => [
            'tambah' => ['Lowongan baru berhasil ditambahkan.', 'Gagal menambahkan lowongan. Periksa kembali isian form.'],
            'ubah' => ['Data lowongan berhasil diperbarui.', 'Gagal memperbarui lowongan. Periksa kembali isian form.'],
            'hapus' => ['Data lowongan berhasil dihapus.', 'Gagal menghapus data lowongan.'],
        ],
    ];

    if (!isset($labels[$module][$action])) {
        return ['success' => false, 'message' => 'Aksi tidak dikenali.'];
    }

    if ($module === 'lowongan') {
        $success = match ($action) {
            'tambah' => $presenterLowongan->tambahLowongan(
                (string) ($post['judulPosisi'] ?? ''),
                (string) ($post['departemen'] ?? ''),
                (string) ($post['lokasi'] ?? ''),
                (string) ($post['tipeKerja'] ?? ''),
                (int) ($post['kuota'] ?? 0),
                (string) ($post['status'] ?? '')
            ),
            'ubah' => $presenterLowongan->ubahLowongan(
                (int) ($post['id'] ?? 0),
                (string) ($post['judulPosisi'] ?? ''),
                (string) ($post['departemen'] ?? ''),
                (string) ($post['lokasi'] ?? ''),
                (string) ($post['tipeKerja'] ?? ''),
                (int) ($post['kuota'] ?? 0),
                (string) ($post['status'] ?? '')
            ),
            'hapus' => $presenterLowongan->hapusLowongan((int) ($post['id'] ?? 0)),
        };
    } else {
        $success = match ($action) {
            'tambah' => $presenterKandidat->tambahKandidat(
                (string) ($post['nama'] ?? ''),
                (string) ($post['posisi'] ?? ''),
                (string) ($post['domisili'] ?? ''),
                (int) ($post['skorTes'] ?? 0),
                (int) ($post['pengalamanTahun'] ?? 0)
            ),
            'ubah' => $presenterKandidat->ubahKandidat(
                (int) ($post['id'] ?? 0),
                (string) ($post['nama'] ?? ''),
                (string) ($post['posisi'] ?? ''),
                (string) ($post['domisili'] ?? ''),
                (int) ($post['skorTes'] ?? 0),
                (int) ($post['pengalamanTahun'] ?? 0)
            ),
            'hapus' => $presenterKandidat->hapusKandidat((int) ($post['id'] ?? 0)),
        };
    }

    [$okMsg, $errMsg] = $labels[$module][$action];
    return [
        'success' => $success,
        'message' => $success ? $okMsg : $errMsg,
    ];
}

try {
    $model = new TabelKandidat('localhost', 'mvp_kandidat_db', 'root', '');
    $view = new ViewKandidat();
    $presenter = new PresenterKandidat($model, $view);

    $modelLowongan = new TabelLowongan('localhost', 'mvp_kandidat_db', 'root', '');
    $viewLowongan = new ViewLowongan();
    $presenterLowongan = new PresenterLowongan($modelLowongan, $viewLowongan);
} catch (Throwable $e) {
    http_response_code(500);
    echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Kesalahan Koneksi Database</title></head>'
        . '<body style="font-family:system-ui,sans-serif;max-width:680px;margin:48px auto;padding:0 20px;line-height:1.6;">'
        . '<h2>Koneksi ke Database Gagal</h2>'
        . '<p>Pastikan service MySQL (XAMPP/Laragon) sudah berjalan dan file <code>mvp_db.sql</code> telah diimpor ke database <code>mvp_kandidat_db</code>.</p>'
        . '<p style="color:#b91c1c;">Detail: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>'
        . '</body></html>';
    exit();
}

$module = (string) ($_POST['module'] ?? $_GET['module'] ?? 'kandidat');
if (!in_array($module, ['kandidat', 'lowongan'], true)) {
    $module = 'kandidat';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = handlePostAction($module, $_POST, $presenter, $presenterLowongan);
    $_SESSION['flash'] = [
        'message' => $result['message'],
        'type' => $result['success'] ? 'success' : 'error',
    ];

    $redirectUrl = $module === 'lowongan' ? 'index.php?module=lowongan' : 'index.php';
    header('Location: ' . $redirectUrl);
    exit();
}

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
$flashMessage = is_array($flash) ? (string) ($flash['message'] ?? '') : '';
$flashType = is_array($flash) ? (string) ($flash['type'] ?? 'success') : 'success';

$screen = $_GET['screen'] ?? 'list';

if ($module === 'lowongan') {
    if ($screen === 'add') {
        echo $presenterLowongan->tampilkanFormLowongan();
    } elseif ($screen === 'edit' && isset($_GET['id'])) {
        echo $presenterLowongan->tampilkanFormLowongan((int) $_GET['id']);
    } else {
        echo $presenterLowongan->tampilkanLowongan($flashMessage, $flashType);
    }
} else {
    if ($screen === 'add') {
        echo $presenter->tampilkanFormKandidat();
    } elseif ($screen === 'edit' && isset($_GET['id'])) {
        echo $presenter->tampilkanFormKandidat((int) $_GET['id']);
    } else {
        echo $presenter->tampilkanKandidat($flashMessage, $flashType);
    }
}
