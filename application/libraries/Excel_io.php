<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Excel_io
 * ---------------------------------------------------------
 * Penulis & pembaca berkas Excel (.xlsx) ringan tanpa library eksternal.
 * Memanfaatkan ekstensi PHP ZipArchive + SimpleXML yang tersedia di XAMPP.
 * Mendukung fallback CSV untuk impor.
 */
class Excel_io
{
    private $ns = 'http://schemas.openxmlformats.org/spreadsheetml/2006/main';

    // =========================================================
    //  WRITE
    // =========================================================

    /**
     * Kirim berkas .xlsx ke browser (unduhan).
     * @param string $filename nama berkas unduhan (mis. data_pegawai.xlsx)
     * @param array  $headers  baris judul kolom (array string)
     * @param array  $rows     baris data (array of array)
     * @param string $sheet    nama worksheet
     */
    public function export($filename, $headers, $rows, $sheet = 'Sheet1')
    {
        $path = $this->build($headers, $rows, $sheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        readfile($path);
        @unlink($path);
        exit;
    }

    /**
     * Bangun berkas .xlsx di temp dan kembalikan path-nya.
     */
    public function build($headers, $rows, $sheet = 'Sheet1')
    {
        $path = tempnam(sys_get_temp_dir(), 'xl');
        $zip  = new ZipArchive();
        if ($zip->open($path, ZipArchive::OVERWRITE) !== TRUE) {
            show_error('Tidak dapat membuat berkas Excel.');
        }

        $zip->addFromString('[Content_Types].xml', $this->xmlContentTypes());
        $zip->addFromString('_rels/.rels', $this->xmlRootRels());
        $zip->addFromString('xl/workbook.xml', $this->xmlWorkbook($sheet));
        $zip->addFromString('xl/_rels/workbook.xml.rels', $this->xmlWorkbookRels());
        $zip->addFromString('xl/styles.xml', $this->xmlStyles());
        $zip->addFromString('xl/worksheets/sheet1.xml', $this->xmlSheet($headers, $rows));
        $zip->close();

        return $path;
    }

    private function xmlContentTypes()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">'
            . '<Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>'
            . '<Default Extension="xml" ContentType="application/xml"/>'
            . '<Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>'
            . '<Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>'
            . '<Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>'
            . '</Types>';
    }

    private function xmlRootRels()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>'
            . '</Relationships>';
    }

    private function xmlWorkbook($sheet)
    {
        $sheet = $this->xmlAttr($sheet);
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"'
            . ' xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">'
            . '<sheets><sheet name="' . $sheet . '" sheetId="1" r:id="rId1"/></sheets>'
            . '</workbook>';
    }

    private function xmlWorkbookRels()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">'
            . '<Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>'
            . '<Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>'
            . '</Relationships>';
    }

    private function xmlStyles()
    {
        return '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>'
            . '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">'
            . '<fonts count="1"><font><sz val="11"/><name val="Calibri"/></font></fonts>'
            . '<fills count="2"><fill><patternFill patternType="none"/></fill><fill><patternFill patternType="gray125"/></fill></fills>'
            . '<borders count="1"><border><left/><right/><top/><bottom/><diagonal/></border></borders>'
            . '<cellStyleXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0"/></cellStyleXfs>'
            . '<cellXfs count="1"><xf numFmtId="0" fontId="0" fillId="0" borderId="0" xfId="0"/></cellXfs>'
            . '</styleSheet>';
    }

    private function xmlSheet($headers, $rows)
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>';
        $xml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';

        $r = 1;
        if (!empty($headers)) {
            $xml .= $this->xmlRow($r++, array_values($headers));
        }
        foreach ($rows as $row) {
            $xml .= $this->xmlRow($r++, array_values($row));
        }

        $xml .= '</sheetData></worksheet>';
        return $xml;
    }

    private function xmlRow($rowNum, $cells)
    {
        $xml = '<row r="' . $rowNum . '">';
        $c = 0;
        foreach ($cells as $value) {
            $ref = $this->colLetter($c) . $rowNum;
            if ($value === NULL || $value === '') {
                $c++;
                continue;
            }
            if (is_int($value) || is_float($value)) {
                $xml .= '<c r="' . $ref . '"><v>' . $value . '</v></c>';
            } else {
                $xml .= '<c r="' . $ref . '" t="inlineStr"><is><t xml:space="preserve">'
                    . $this->xmlText($value) . '</t></is></c>';
            }
            $c++;
        }
        $xml .= '</row>';
        return $xml;
    }

    // =========================================================
    //  READ
    // =========================================================

    /**
     * Baca berkas .xlsx/.csv menjadi array baris.
     * @return array( array(cell, cell, ...), ... ) atau FALSE bila gagal
     */
    public function read_file($path, $ext = NULL)
    {
        if (!is_file($path)) {
            return FALSE;
        }
        $ext = ($ext !== NULL) ? strtolower($ext) : strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($ext, array('csv', 'txt'), TRUE)) {
            return $this->read_csv($path);
        }
        return $this->read_xlsx($path);
    }

    public function read_csv($path)
    {
        $rows = array();
        if (($fh = fopen($path, 'r')) !== FALSE) {
            while (($data = fgetcsv($fh, 0, ',')) !== FALSE) {
                $rows[] = array_map(function ($v) { return trim((string) $v); }, $data);
            }
            fclose($fh);
        }
        return $rows;
    }

    public function read_xlsx($path)
    {
        $zip = new ZipArchive();
        if ($zip->open($path) !== TRUE) {
            return FALSE;
        }

        $shared = $this->readSharedStrings($zip);
        $sheetPath = $this->firstSheetPath($zip);
        $xmlStr = $zip->getFromName($sheetPath);
        $zip->close();

        if ($xmlStr === FALSE) {
            return FALSE;
        }

        $xml = @simplexml_load_string($xmlStr);
        if ($xml === FALSE) {
            return FALSE;
        }
        $xml->registerXPathNamespace('m', $this->ns);
        $rowNodes = $xml->xpath('//m:sheetData/m:row');
        if ($rowNodes === FALSE) {
            return array();
        }

        $rows = array();
        $maxRow = 0;
        foreach ($rowNodes as $rowNode) {
            $rowNode->registerXPathNamespace('m', $this->ns);
            $cells = $rowNode->xpath('m:c');
            $rowData = array();
            $autoCol = 0;
            foreach ($cells as $cell) {
                $cell->registerXPathNamespace('m', $this->ns);
                $ref = (string) $cell['r'];
                $col = $ref !== '' ? $this->colIndex($ref) : $autoCol;
                $autoCol = $col + 1;

                $type = (string) $cell['t'];
                $value = '';
                if ($type === 's') {
                    $vNodes = $cell->xpath('m:v');
                    $idx = isset($vNodes[0]) ? (int) $vNodes[0] : -1;
                    $value = isset($shared[$idx]) ? $shared[$idx] : '';
                } elseif ($type === 'inlineStr') {
                    $value = $this->stringText($cell, 'm:is');
                } else {
                    $vNodes = $cell->xpath('m:v');
                    $value = isset($vNodes[0]) ? (string) $vNodes[0] : '';
                    if ($type === 'str' || $type === 'b') {
                        // string hasil rumus / boolean -> apa adanya
                    }
                }
                $rowData[$col] = $value;
                if ($col > $maxRow) {
                    $maxRow = $col;
                }
            }
            $rows[] = $rowData;
        }

        // rapikan: pastikan setiap baris punya panjang kolom yang sama & urut
        $result = array();
        foreach ($rows as $rowData) {
            $line = array();
            for ($i = 0; $i <= $maxRow; $i++) {
                $line[] = isset($rowData[$i]) ? $rowData[$i] : '';
            }
            $result[] = $line;
        }
        return $result;
    }

    private function readSharedStrings($zip)
    {
        $strings = array();
        $xmlStr = $zip->getFromName('xl/sharedStrings.xml');
        if ($xmlStr === FALSE) {
            return $strings;
        }
        $xml = @simplexml_load_string($xmlStr);
        if ($xml === FALSE) {
            return $strings;
        }
        $xml->registerXPathNamespace('m', $this->ns);
        $siNodes = $xml->xpath('//m:si');
        if ($siNodes === FALSE) {
            return $strings;
        }
        foreach ($siNodes as $si) {
            $si->registerXPathNamespace('m', $this->ns);
            $tNodes = $si->xpath('.//m:t');
            $text = '';
            if ($tNodes !== FALSE) {
                foreach ($tNodes as $t) {
                    $text .= (string) $t;
                }
            }
            $strings[] = $text;
        }
        return $strings;
    }

    private function firstSheetPath($zip)
    {
        $book = $zip->getFromName('xl/workbook.xml');
        if ($book === FALSE) {
            return 'xl/worksheets/sheet1.xml';
        }
        $xml = @simplexml_load_string($book);
        if ($xml === FALSE) {
            return 'xl/worksheets/sheet1.xml';
        }
        $xml->registerXPathNamespace('m', $this->ns);
        $xml->registerXPathNamespace('r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');
        $sheets = $xml->xpath('//m:sheet');
        $rid = ($sheets !== FALSE && isset($sheets[0])) ? (string) $sheets[0]->attributes('r', TRUE)->id : '';
        if ($rid === '') {
            return 'xl/worksheets/sheet1.xml';
        }
        $relsStr = $zip->getFromName('xl/_rels/workbook.xml.rels');
        if ($relsStr === FALSE) {
            return 'xl/worksheets/sheet1.xml';
        }
        $rels = @simplexml_load_string($relsStr);
        if ($rels === FALSE) {
            return 'xl/worksheets/sheet1.xml';
        }
        $rels->registerXPathNamespace('p', 'http://schemas.openxmlformats.org/package/2006/relationships');
        $relNodes = $rels->xpath('//p:Relationship');
        if ($relNodes !== FALSE) {
            foreach ($relNodes as $rel) {
                if ((string) $rel['Id'] === $rid) {
                    $target = ltrim((string) $rel['Target'], '/');
                    return (strpos($target, 'xl/') === 0) ? $target : 'xl/' . $target;
                }
            }
        }
        return 'xl/worksheets/sheet1.xml';
    }

    private function stringText($node, $tag)
    {
        $node->registerXPathNamespace('m', $this->ns);
        $tNodes = $node->xpath($tag . '//m:t');
        $text = '';
        if ($tNodes !== FALSE) {
            foreach ($tNodes as $t) {
                $text .= (string) $t;
            }
        }
        return $text;
    }

    // =========================================================
    //  HELPERS
    // =========================================================

    public function colLetter($index)
    {
        $letters = '';
        $index = (int) $index;
        while ($index >= 0) {
            $letters = chr(65 + ($index % 26)) . $letters;
            $index = intdiv($index, 26) - 1;
        }
        return $letters;
    }

    public function colIndex($ref)
    {
        $letters = preg_replace('/[^A-Z]/', '', strtoupper($ref));
        $index = 0;
        $len = strlen($letters);
        for ($i = 0; $i < $len; $i++) {
            $index = $index * 26 + (ord($letters[$i]) - 64);
        }
        return $index - 1;
    }

    // Konversi serial tanggal Excel (sistem 1900) ke Y-m-d
    public function excel_date($value)
    {
        if (!is_numeric($value)) {
            return NULL;
        }
        $n = (float) $value;
        if ($n < 1 || $n > 80000) {
            return NULL;
        }
        $ts = (int) round(($n - 25569) * 86400);
        return gmdate('Y-m-d', $ts);
    }

    // Terima serial Excel ATAU string tanggal -> Y-m-d (NULL bila kosong/tidak valid)
    public function parse_date($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return NULL;
        }
        if (is_numeric($value)) {
            $d = $this->excel_date($value);
            if ($d) {
                return $d;
            }
        }
        // coba format umum (dahulukan format Indonesia d/m/Y)
        $formats = array('Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y', 'Y/m/d', 'm/d/Y', 'd/m/Y H:i', 'Y-m-d H:i:s');
        foreach ($formats as $fmt) {
            $dt = DateTime::createFromFormat($fmt, $value);
            if ($dt && $dt->format($fmt) === $value) {
                return $dt->format('Y-m-d');
            }
        }
        $ts = strtotime($value);
        return $ts ? date('Y-m-d', $ts) : NULL;
    }

    private function xmlText($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    private function xmlAttr($value)
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
