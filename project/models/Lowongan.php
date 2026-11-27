<?php

/**
 * Entity class yang merepresentasikan satu data lowongan pekerjaan.
 */
class Lowongan
{
    private int $id;
    private string $judulPosisi;
    private string $departemen;
    private string $lokasi;
    private string $tipeKerja;
    private int $kuota;
    private string $status;

    public function __construct(
        int $id = 0,
        string $judulPosisi = '',
        string $departemen = '',
        string $lokasi = '',
        string $tipeKerja = 'Full-time',
        int $kuota = 1,
        string $status = 'Dibuka'
    ) {
        $this->id = $id;
        $this->judulPosisi = $judulPosisi;
        $this->departemen = $departemen;
        $this->lokasi = $lokasi;
        $this->tipeKerja = $tipeKerja;
        $this->kuota = $kuota;
        $this->status = $status;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getJudulPosisi(): string
    {
        return $this->judulPosisi;
    }

    public function getDepartemen(): string
    {
        return $this->departemen;
    }

    public function getLokasi(): string
    {
        return $this->lokasi;
    }

    public function getTipeKerja(): string
    {
        return $this->tipeKerja;
    }

    public function getKuota(): int
    {
        return $this->kuota;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setJudulPosisi(string $judulPosisi): void
    {
        $this->judulPosisi = $judulPosisi;
    }

    public function setDepartemen(string $departemen): void
    {
        $this->departemen = $departemen;
    }

    public function setLokasi(string $lokasi): void
    {
        $this->lokasi = $lokasi;
    }

    public function setTipeKerja(string $tipeKerja): void
    {
        $this->tipeKerja = $tipeKerja;
    }

    public function setKuota(int $kuota): void
    {
        $this->kuota = $kuota;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}
