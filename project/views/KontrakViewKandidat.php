<?php

require_once __DIR__ . '/KontrakView.php';

/**
 * Kontrak View pasif untuk modul Kandidat.
 * Seluruh parameter berupa string primitif tanpa ketergantungan ke Model atau Entity.
 */
interface KontrakViewKandidat extends KontrakView
{
    public function tampilKandidat(
        string $rowsHtml,
        string $totalKandidat,
        string $rataSkor,
        string $rataPengalaman,
        string $flashMessage = '',
        string $flashType = 'success'
    ): string;

    public function tampilFormKandidat(
        string $formTitle,
        string $formSubtitle,
        string $actionValue,
        string $submitLabel,
        string $id = '',
        string $nama = '',
        string $posisi = '',
        string $domisili = '',
        string $skorTes = '',
        string $pengalamanTahun = '',
        string $flashMessage = '',
        string $flashType = 'error'
    ): string;
}
