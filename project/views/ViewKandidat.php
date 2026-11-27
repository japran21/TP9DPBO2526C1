<?php

require_once __DIR__ . '/KontrakViewKandidat.php';
require_once __DIR__ . '/TemplateRenderer.php';

/**
 * Passive View untuk menampilkan daftar dan form modul Kandidat.
 */
class ViewKandidat implements KontrakViewKandidat
{
    public function tampilKandidat(
        string $rowsHtml,
        string $totalKandidat,
        string $rataSkor,
        string $rataPengalaman,
        string $flashMessage = '',
        string $flashType = 'success'
    ): string {
        $content = TemplateRenderer::renderTemplate('kandidat_list.html', [
            '{{TOTAL_KANDIDAT}}' => TemplateRenderer::escape($totalKandidat),
            '{{RATA_SKOR}}' => TemplateRenderer::escape($rataSkor),
            '{{RATA_PENGALAMAN}}' => TemplateRenderer::escape($rataPengalaman),
            '{{ROWS}}' => $rowsHtml,
        ]);

        return TemplateRenderer::renderLayout(
            'Daftar Kandidat — TalentHub HR',
            'kandidat',
            $content,
            $flashMessage,
            $flashType
        );
    }

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
    ): string {
        $content = TemplateRenderer::renderTemplate('kandidat_form.html', [
            '{{FORM_TITLE}}' => TemplateRenderer::escape($formTitle),
            '{{FORM_SUBTITLE}}' => TemplateRenderer::escape($formSubtitle),
            '{{ACTION_VALUE}}' => TemplateRenderer::escape($actionValue),
            '{{SUBMIT_LABEL}}' => TemplateRenderer::escape($submitLabel),
            '{{HIDDEN_ID_INPUT}}' => TemplateRenderer::buildHiddenIdInput($id),
            '{{NAMA}}' => TemplateRenderer::escape($nama),
            '{{POSISI}}' => TemplateRenderer::escape($posisi),
            '{{DOMISILI}}' => TemplateRenderer::escape($domisili),
            '{{SKOR_TES}}' => TemplateRenderer::escape($skorTes),
            '{{PENGALAMAN_TAHUN}}' => TemplateRenderer::escape($pengalamanTahun),
        ]);

        return TemplateRenderer::renderLayout(
            $formTitle . ' — TalentHub HR',
            'kandidat',
            $content,
            $flashMessage,
            $flashType
        );
    }
}
