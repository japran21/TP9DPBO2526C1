<?php

/**
 * Utilitas pembentuk fragmen HTML dan sanitasi input di lapisan Presenter.
 */
class PresenterHelper
{
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Menyusun tag <option> untuk elemen <select> pada form.
     *
     * @param array<int, string> $choices
     */
    public static function buildSelectOptions(array $choices, string $selectedValue): string
    {
        $options = $choices;
        if ($selectedValue !== '' && !in_array($selectedValue, $options, true)) {
            $options[] = $selectedValue;
        }

        $html = '';
        foreach ($options as $choice) {
            $selectedAttr = $choice === $selectedValue ? ' selected' : '';
            $safeChoice = self::escape($choice);
            $html .= '<option value="' . $safeChoice . '"' . $selectedAttr . '>' . $safeChoice . '</option>';
        }

        return $html;
    }

    /**
     * Menyusun tombol aksi Edit (GET) dan Hapus (POST) untuk satu baris tabel.
     */
    public static function buildActionButtons(string $module, int $id, string $confirmMessage): string
    {
        $safeModule = self::escape($module);
        $safeConfirm = self::escape($confirmMessage);
        $editHref = 'index.php?module=' . $safeModule . '&amp;screen=edit&amp;id=' . $id;

        return '<div class="actions-cell">'
            . '<a class="btn btn-sm btn-edit" href="' . $editHref . '">Edit</a>'
            . '<form class="inline-form" method="POST" action="index.php" data-confirm="' . $safeConfirm . '" onsubmit="return confirm(this.dataset.confirm);">'
            . '<input type="hidden" name="module" value="' . $safeModule . '">'
            . '<input type="hidden" name="action" value="hapus">'
            . '<input type="hidden" name="id" value="' . $id . '">'
            . '<button type="submit" class="btn btn-sm btn-danger">Hapus</button>'
            . '</form>'
            . '</div>';
    }

    public static function buildEmptyRow(int $colspan, string $message): string
    {
        return '<tr><td colspan="' . $colspan . '" class="empty-state">'
            . self::escape($message)
            . '</td></tr>';
    }
}
