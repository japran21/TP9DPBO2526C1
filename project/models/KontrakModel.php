<?php

/**
 * Kontrak dasar seluruh Model pada arsitektur MVP.
 */
interface KontrakModel
{
    /**
     * Menutup koneksi sumber data.
     */
    public function close(): void;
}
