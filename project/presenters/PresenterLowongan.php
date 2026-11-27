<?php

require_once __DIR__ . '/KontrakPresenterLowongan.php';
require_once __DIR__ . '/PresenterHelper.php';
require_once __DIR__ . '/../models/KontrakModelLowongan.php';
require_once __DIR__ . '/../views/KontrakViewLowongan.php';

class PresenterLowongan implements KontrakPresenterLowongan
{
    private const TIPE_KERJA_CHOICES = ['Full-time', 'Hybrid', 'Remote', 'Contract'];
    private const STATUS_CHOICES = ['Dibuka', 'Ditutup'];

    private KontrakModelLowongan $model;
    private KontrakViewLowongan $view;

    public function __construct(KontrakModelLowongan $model, KontrakViewLowongan $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function tampilkanLowongan(string $flashMessage = '', string $flashType = 'success'): string
    {
        $daftarLowongan = $this->model->getAllLowongan();
        $total = count($daftarLowongan);

        if ($total === 0) {
            return $this->view->tampilLowongan(
                PresenterHelper::buildEmptyRow(8, 'Belum ada data lowongan. Silakan tambah lowongan baru.'),
                '0',
                '0',
                '0',
                $flashMessage,
                $flashType
            );
        }

        $jumlahDibuka = 0;
        $totalKuotaDibuka = 0;
        $rowsHtml = '';
        $no = 1;

        foreach ($daftarLowongan as $lowongan) {
            if ($lowongan->getStatus() === 'Dibuka') {
                $jumlahDibuka++;
                $totalKuotaDibuka += $lowongan->getKuota();
            }
            $rowsHtml .= $this->buildRowHtml($no, $lowongan);
            $no++;
        }

        return $this->view->tampilLowongan(
            $rowsHtml,
            (string) $total,
            (string) $jumlahDibuka,
            (string) $totalKuotaDibuka,
            $flashMessage,
            $flashType
        );
    }

    public function tampilkanFormLowongan(?int $id = null): string
    {
        if ($id === null) {
            return $this->view->tampilFormLowongan(
                'Tambah Lowongan Baru',
                'Publikasikan kebutuhan posisi terbuka untuk tim atau departemen.',
                'tambah',
                'Simpan Lowongan',
                '',
                '',
                '',
                '',
                PresenterHelper::buildSelectOptions(self::TIPE_KERJA_CHOICES, 'Full-time'),
                '1',
                PresenterHelper::buildSelectOptions(self::STATUS_CHOICES, 'Dibuka')
            );
        }

        $lowongan = $this->model->getLowonganById($id);
        if ($lowongan === null) {
            return $this->tampilkanLowongan('Data lowongan tidak ditemukan.', 'error');
        }

        return $this->view->tampilFormLowongan(
            'Edit Data Lowongan',
            'Perbarui detail posisi, kuota penerimaan, atau status publikasi lowongan.',
            'ubah',
            'Simpan Perubahan',
            (string) $lowongan->getId(),
            $lowongan->getJudulPosisi(),
            $lowongan->getDepartemen(),
            $lowongan->getLokasi(),
            PresenterHelper::buildSelectOptions(self::TIPE_KERJA_CHOICES, $lowongan->getTipeKerja()),
            (string) $lowongan->getKuota(),
            PresenterHelper::buildSelectOptions(self::STATUS_CHOICES, $lowongan->getStatus())
        );
    }

    public function tambahLowongan(
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): bool {
        $clean = $this->normalizeInput($judulPosisi, $departemen, $lokasi, $tipeKerja, $kuota, $status);
        if ($clean === null) {
            return false;
        }

        return $this->model->addLowongan(
            $clean['judulPosisi'],
            $clean['departemen'],
            $clean['lokasi'],
            $clean['tipeKerja'],
            $clean['kuota'],
            $clean['status']
        );
    }

    public function ubahLowongan(
        int $id,
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): bool {
        if ($id <= 0) {
            return false;
        }

        $clean = $this->normalizeInput($judulPosisi, $departemen, $lokasi, $tipeKerja, $kuota, $status);
        if ($clean === null) {
            return false;
        }

        return $this->model->updateLowongan(
            $id,
            $clean['judulPosisi'],
            $clean['departemen'],
            $clean['lokasi'],
            $clean['tipeKerja'],
            $clean['kuota'],
            $clean['status']
        );
    }

    public function hapusLowongan(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        return $this->model->deleteLowongan($id);
    }

    /**
     * @return array{judulPosisi: string, departemen: string, lokasi: string, tipeKerja: string, kuota: int, status: string}|null
     */
    private function normalizeInput(
        string $judulPosisi,
        string $departemen,
        string $lokasi,
        string $tipeKerja,
        int $kuota,
        string $status
    ): ?array {
        $judulPosisi = trim($judulPosisi);
        $departemen = trim($departemen);
        $lokasi = trim($lokasi);
        $tipeKerja = trim($tipeKerja);
        $status = trim($status);

        if ($judulPosisi === '' || $departemen === '' || $lokasi === '') {
            return null;
        }

        if (!in_array($tipeKerja, self::TIPE_KERJA_CHOICES, true)) {
            return null;
        }

        if (!in_array($status, self::STATUS_CHOICES, true)) {
            return null;
        }

        if ($kuota < 1) {
            return null;
        }

        return [
            'judulPosisi' => $judulPosisi,
            'departemen' => $departemen,
            'lokasi' => $lokasi,
            'tipeKerja' => $tipeKerja,
            'kuota' => $kuota,
            'status' => $status,
        ];
    }

    private function buildRowHtml(int $no, Lowongan $lowongan): string
    {
        $status = $lowongan->getStatus();
        $statusBadgeClass = $status === 'Dibuka' ? 'badge-high' : 'badge-closed';
        $actionsHtml = PresenterHelper::buildActionButtons(
            'lowongan',
            $lowongan->getId(),
            'Hapus lowongan ' . $lowongan->getJudulPosisi() . '?'
        );

        return '<tr>'
            . '<td>' . $no . '</td>'
            . '<td class="cell-strong">' . PresenterHelper::escape($lowongan->getJudulPosisi()) . '</td>'
            . '<td>' . PresenterHelper::escape($lowongan->getDepartemen()) . '</td>'
            . '<td>' . PresenterHelper::escape($lowongan->getLokasi()) . '</td>'
            . '<td><span class="badge badge-neutral">' . PresenterHelper::escape($lowongan->getTipeKerja()) . '</span></td>'
            . '<td>' . $lowongan->getKuota() . ' orang</td>'
            . '<td><span class="badge ' . $statusBadgeClass . '">' . PresenterHelper::escape($status) . '</span></td>'
            . '<td>' . $actionsHtml . '</td>'
            . '</tr>';
    }
}