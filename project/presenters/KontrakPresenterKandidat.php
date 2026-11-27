<?php

require_once __DIR__ . '/KontrakPresenter.php';

/**
 * Kontrak Presenter untuk modul CRUD Kandidat.
 */
interface KontrakPresenterKandidat extends KontrakPresenter
{
    public function tampilkanKandidat(string $flashMessage = '', string $flashType = 'success'): string;

    public function tampilkanFormKandidat(?int $id = null): string;

    public function tambahKandidat(
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): bool;

    public function ubahKandidat(
        int $id,
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): bool;

    public function hapusKandidat(int $id): bool;
}
