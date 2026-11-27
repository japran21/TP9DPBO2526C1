<?php

require_once __DIR__ . '/KontrakView.php';

/**
 * Kontrak View pasif untuk modul Lowongan.
 * Seluruh parameter berupa string primitif tanpa ketergantungan ke Model atau Entity.
 */
interface KontrakViewLowongan extends KontrakView
{
    public function tampilLowongan(
        string $rowsHtml,
        string $totalLowongan,
        string $lowonganDibuka,
        string $totalKuota,
        string $flashMessage = '',
        string $flashType = 'success'
    ): string;

    public function tampilFormLowongan(
        string $formTitle,
        string $formSubtitle,
        string $actionValue,
        string $submitLabel,
        string $id = '',
        string $judulPosisi = '',
        string $departemen = '',
        string $lokasi = '',
        string $tipeKerjaOptionsHtml = '',
        string $kuota = '',
        string $statusOptionsHtml = '',
        string $flashMessage = '',
        string $flashType = 'error'
    ): string;
}
