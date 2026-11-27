<?php

require_once __DIR__ . '/KontrakPresenterKandidat.php';
require_once __DIR__ . '/PresenterHelper.php';
require_once __DIR__ . '/../models/KontrakModelKandidat.php';
require_once __DIR__ . '/../views/KontrakViewKandidat.php';

/**
 * Presenter modul Kandidat yang mengoordinasikan Model dan Passive View melalui interface.
 */
class PresenterKandidat implements KontrakPresenterKandidat
{
    private KontrakModelKandidat $model;
    private KontrakViewKandidat $view;

    public function __construct(KontrakModelKandidat $model, KontrakViewKandidat $view)
    {
        $this->model = $model;
        $this->view = $view;
    }

    public function tampilkanKandidat(string $flashMessage = '', string $flashType = 'success'): string
    {
        $daftarKandidat = $this->model->getAllKandidat();
        $total = count($daftarKandidat);

        if ($total === 0) {
            return $this->view->tampilKandidat(
                PresenterHelper::buildEmptyRow(7, 'Belum ada data kandidat. Silakan tambah kandidat baru.'),
                '0',
                '0',
                '0 tahun',
                $flashMessage,
                $flashType
            );
        }

        $totalSkor = 0;
        $totalPengalaman = 0;
        $rowsHtml = '';
        $no = 1;

        foreach ($daftarKandidat as $kandidat) {
            $totalSkor += $kandidat->getSkorTes();
            $totalPengalaman += $kandidat->getPengalamanTahun();
            $rowsHtml .= $this->buildRowHtml($no, $kandidat);
            $no++;
        }

        $rataSkor = number_format($totalSkor / $total, 1);
        $rataPengalaman = number_format($totalPengalaman / $total, 1) . ' tahun';

        return $this->view->tampilKandidat(
            $rowsHtml,
            (string) $total,
            $rataSkor,
            $rataPengalaman,
            $flashMessage,
            $flashType
        );
    }

    public function tampilkanFormKandidat(?int $id = null): string
    {
        if ($id === null) {
            return $this->view->tampilFormKandidat(
                'Tambah Kandidat Baru',
                'Lengkapi profil pelamar untuk dimasukkan ke daftar seleksi.',
                'tambah',
                'Simpan Kandidat'
            );
        }

        $kandidat = $this->model->getKandidatById($id);
        if ($kandidat === null) {
            return $this->tampilkanKandidat('Data kandidat tidak ditemukan.', 'error');
        }

        return $this->view->tampilFormKandidat(
            'Edit Data Kandidat',
            'Perbarui informasi, skor tes, atau pengalaman kerja kandidat.',
            'ubah',
            'Simpan Perubahan',
            (string) $kandidat->getId(),
            $kandidat->getNama(),
            $kandidat->getPosisi(),
            $kandidat->getDomisili(),
            (string) $kandidat->getSkorTes(),
            (string) $kandidat->getPengalamanTahun()
        );
    }

    public function tambahKandidat(
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): bool {
        $clean = $this->normalizeInput($nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        if ($clean === null) {
            return false;
        }

        return $this->model->addKandidat(
            $clean['nama'],
            $clean['posisi'],
            $clean['domisili'],
            $clean['skorTes'],
            $clean['pengalamanTahun']
        );
    }

    public function ubahKandidat(
        int $id,
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): bool {
        if ($id <= 0) {
            return false;
        }

        $clean = $this->normalizeInput($nama, $posisi, $domisili, $skorTes, $pengalamanTahun);
        if ($clean === null) {
            return false;
        }

        return $this->model->updateKandidat(
            $id,
            $clean['nama'],
            $clean['posisi'],
            $clean['domisili'],
            $clean['skorTes'],
            $clean['pengalamanTahun']
        );
    }

    public function hapusKandidat(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        return $this->model->deleteKandidat($id);
    }

    /**
     * @return array{nama: string, posisi: string, domisili: string, skorTes: int, pengalamanTahun: int}|null
     */
    private function normalizeInput(
        string $nama,
        string $posisi,
        string $domisili,
        int $skorTes,
        int $pengalamanTahun
    ): ?array {
        $nama = trim($nama);
        $posisi = trim($posisi);
        $domisili = trim($domisili);

        if ($nama === '' || $posisi === '' || $domisili === '') {
            return null;
        }

        if ($skorTes < 0 || $skorTes > 100 || $pengalamanTahun < 0) {
            return null;
        }

        return [
            'nama' => $nama,
            'posisi' => $posisi,
            'domisili' => $domisili,
            'skorTes' => $skorTes,
            'pengalamanTahun' => $pengalamanTahun,
        ];
    }

    private function buildRowHtml(int $no, Kandidat $kandidat): string
    {
        $skor = $kandidat->getSkorTes();
        $skorBadgeClass = $skor >= 90 ? 'badge-high' : ($skor >= 80 ? 'badge-mid' : 'badge-low');
        $actionsHtml = PresenterHelper::buildActionButtons(
            'kandidat',
            $kandidat->getId(),
            'Hapus kandidat ' . $kandidat->getNama() . '?'
        );

        return '<tr>'
            . '<td>' . $no . '</td>'
            . '<td class="cell-strong">' . PresenterHelper::escape($kandidat->getNama()) . '</td>'
            . '<td>' . PresenterHelper::escape($kandidat->getPosisi()) . '</td>'
            . '<td>' . PresenterHelper::escape($kandidat->getDomisili()) . '</td>'
            . '<td><span class="badge ' . $skorBadgeClass . '">' . $skor . '</span></td>'
            . '<td>' . $kandidat->getPengalamanTahun() . ' tahun</td>'
            . '<td>' . $actionsHtml . '</td>'
            . '</tr>';
    }
}
