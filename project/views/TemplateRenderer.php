<?php

/**
 * Helper rendering template HTML untuk Passive View.
 * Tidak memiliki akses ke Model/Entity dan tidak melakukan iterasi data.
 */
class TemplateRenderer
{
    private const TEMPLATE_DIR = __DIR__ . '/../templates';

    /**
     * Mengamankan teks biasa sebelum disisipkan ke dalam HTML.
     */
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Mengisi placeholder pada file template HTML.
     *
     * @param array<string, string> $replacements
     */
    public static function renderTemplate(string $templateFile, array $replacements): string
    {
        $path = self::TEMPLATE_DIR . '/' . $templateFile;
        $content = file_get_contents($path);
        if ($content === false) {
            return '';
        }

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * Membungkus konten halaman ke dalam layout utama aplikasi.
     */
    public static function renderLayout(
        string $pageTitle,
        string $activeModule,
        string $contentHtml,
        string $flashMessage = '',
        string $flashType = 'success'
    ): string {
        $alertHtml = self::buildAlertHtml($flashMessage, $flashType);

        return self::renderTemplate('layout.html', [
            '{{PAGE_TITLE}}' => self::escape($pageTitle),
            '{{NAV_KANDIDAT_CLASS}}' => $activeModule === 'kandidat' ? 'active' : '',
            '{{NAV_LOWONGAN_CLASS}}' => $activeModule === 'lowongan' ? 'active' : '',
            '{{ALERT}}' => $alertHtml,
            '{{CONTENT}}' => $contentHtml,
        ]);
    }

    /**
     * Membuat input hidden ID saat berada dalam mode edit.
     */
    public static function buildHiddenIdInput(string $id): string
    {
        if ($id === '') {
            return '';
        }

        return '<input type="hidden" name="id" value="' . self::escape($id) . '">';
    }

    private static function buildAlertHtml(string $message, string $type): string
    {
        if ($message === '') {
            return '';
        }

        $safeClass = $type === 'error' ? 'alert-error' : 'alert-success';
        return '<div class="alert ' . $safeClass . '" role="status">' . self::escape($message) . '</div>';
    }
}
