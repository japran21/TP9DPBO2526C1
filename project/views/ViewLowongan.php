<?php

require_once __DIR__ . '/KontrakViewLowongan.php';
require_once __DIR__ . '/TemplateRenderer.php';

/**
 * Passive View untuk menampilkan daftar dan form modul Lowongan.
 */
class ViewLowongan implements KontrakViewLowongan
{
    public function tampilLowongan(
        string $rowsHtml,
        string $totalLowongan,
        string $lowonganDibuka,
        string $totalKuota,
        string $flashMessage = '',
        string $flashType = 'success'
    ): string {
        $content = TemplateRenderer::renderTemplate('lowongan_list.html', [
            '{{TOTAL_LOWONGAN}}' => TemplateRenderer::escape($totalLowongan),
            '{{LOWONGAN_DIBUKA}}' => TemplateRenderer::escape($lowonganDibuka),
            '{{TOTAL_KUOTA}}' => TemplateRenderer::escape($totalKuota),
            '{{ROWS}}' => $rowsHtml,
        ]);

        return TemplateRenderer::renderLayout(
            'Daftar Lowongan — TalentHub HR',
            'lowongan',
            $content,
            $flashMessage,
            $flashType
        );
    }

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
    ): string {
        $content = TemplateRenderer::renderTemplate('lowongan_form.html', [
            '{{FORM_TITLE}}' => TemplateRenderer::escape($formTitle),
            '{{FORM_SUBTITLE}}' => TemplateRenderer::escape($formSubtitle),
            '{{ACTION_VALUE}}' => TemplateRenderer::escape($actionValue),
            '{{SUBMIT_LABEL}}' => TemplateRenderer::escape($submitLabel),
            '{{HIDDEN_ID_INPUT}}' => TemplateRenderer::buildHiddenIdInput($id),
            '{{JUDUL_POSISI}}' => TemplateRenderer::escape($judulPosisi),
            '{{DEPARTEMEN}}' => TemplateRenderer::escape($departemen),
            '{{LOKASI}}' => TemplateRenderer::escape($lokasi),
            '{{TIPE_KERJA_OPTIONS}}' => $tipeKerjaOptionsHtml,
            '{{KUOTA}}' => TemplateRenderer::escape($kuota),
            '{{STATUS_OPTIONS}}' => $statusOptionsHtml,
        ]);

        return TemplateRenderer::renderLayout(
            $formTitle . ' — TalentHub HR',
            'lowongan',
            $content,
            $flashMessage,
            $flashType
        );
    }
}
