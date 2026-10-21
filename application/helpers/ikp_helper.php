<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Helper tampilan untuk modul IKP (Insiden Keselamatan Pasien).
 */

if (!function_exists('ikp_val')) {
    /**
     * Escape nilai, tampilkan strip bila kosong.
     */
    function ikp_val($value)
    {
        $value = is_null($value) ? '' : trim((string) $value);
        if ($value === '') {
            return '<span class="ikp-row__value--muted">&mdash;</span>';
        }
        return html_escape($value);
    }
}

if (!function_exists('ikp_date')) {
    /**
     * Format tanggal Y-m-d menjadi "05 Jan 2024".
     */
    function ikp_date($value)
    {
        $value = trim((string) $value);
        if ($value === '' || $value === '0000-00-00') {
            return '';
        }
        $ts = strtotime($value);
        return $ts ? date('d M Y', $ts) : $value;
    }
}

if (!function_exists('ikp_time')) {
    /**
     * Format jam HH:MM:SS menjadi "08:30".
     */
    function ikp_time($value)
    {
        $value = trim((string) $value);
        if ($value === '' || $value === '00:00:00') {
            return '';
        }
        $ts = strtotime($value);
        return $ts ? date('H:i', $ts) : $value;
    }
}

if (!function_exists('ikp_datetime')) {
    /**
     * Gabungan tanggal + jam.
     */
    function ikp_datetime($date, $time)
    {
        $d = ikp_date($date);
        $t = ikp_time($time);
        if ($d === '' && $t === '') {
            return '';
        }
        return trim($d . ' ' . $t);
    }
}

if (!function_exists('ikp_input_date')) {
    /**
     * Normalisasi nilai tanggal agar kompatibel dengan <input type="date">.
     */
    function ikp_input_date($value)
    {
        $value = trim((string) $value);
        if ($value === '' || strpos($value, '0000-00-00') === 0) {
            return '';
        }
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})/', $value, $m)) {
            return $m[1] . '-' . $m[2] . '-' . $m[3];
        }
        $ts = strtotime($value);
        return $ts ? date('Y-m-d', $ts) : '';
    }
}

if (!function_exists('ikp_input_time')) {
    /**
     * Normalisasi nilai jam agar kompatibel dengan <input type="time">.
     */
    function ikp_input_time($value)
    {
        $value = trim((string) $value);
        if ($value === '' || strpos($value, '00:00:00') === 0) {
            return '';
        }
        if (preg_match('/^(\d{1,2}):(\d{2})/', $value, $m)) {
            return sprintf('%02d:%s', $m[1], $m[2]);
        }
        $ts = strtotime($value);
        return $ts ? date('H:i', $ts) : '';
    }
}

if (!function_exists('ikp_risk_class')) {
    /**
     * Kelas warna untuk Grading Risiko (merah/kuning/hijau/biru).
     */
    function ikp_risk_class($value)
    {
        $value = strtolower((string) $value);
        if (strpos($value, 'merah') !== false) return 'merah';
        if (strpos($value, 'kuning') !== false) return 'kuning';
        if (strpos($value, 'hijau') !== false || strpos($value, 'hjau') !== false) return 'hijau';
        if (strpos($value, 'biru') !== false) return 'biru';
        return 'default';
    }
}

if (!function_exists('ikp_badge_class')) {
    /**
     * Kelas badge netral/aksen untuk nilai bebas.
     */
    function ikp_badge_class($value, $accent = 'accent')
    {
        $value = trim((string) $value);
        if ($value === '') {
            return 'netral';
        }
        return $accent;
    }
}

if (!function_exists('ikp_opt_match')) {
    /**
     * Tentukan nilai select/option yang terpilih.
     *
     * Setiap option berformat: array('v' => 'Nilai', 'label' => 'Tampil', 'alias' => array(...))
     * Pencocokan mengabaikan perbedaan huruf besar-kecil dan mendukung nilai lama (alias).
     *
     * @param  mixed $current Nilai saat ini
     * @param  array $options Daftar option
     * @return string|null Nilai option yang cocok
     */
    function ikp_opt_match($current, array $options)
    {
        $current = trim((string) $current);
        if ($current === '') {
            return NULL;
        }
        foreach ($options as $opt) {
            $candidates = array($opt['v']);
            if (isset($opt['alias'])) {
                $candidates = array_merge($candidates, (array) $opt['alias']);
            }
            foreach ($candidates as $candidate) {
                if (strcasecmp($current, trim((string) $candidate)) === 0) {
                    return $opt['v'];
                }
            }
        }
        return NULL;
    }
}

if (!function_exists('ikp_selected')) {
    /**
     * Helper pencetak atribut selected untuk <option>.
     */
    function ikp_selected($selected, $value)
    {
        return ($selected !== NULL && $selected === $value) ? ' selected' : '';
    }
}
