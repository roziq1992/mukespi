<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Survei_model
 * ---------------------------------------------------------
 * Survei Kepuasan Pasien:
 * - master aspek penilaian (bintang 1..5)
 * - header respon + jawaban per aspek
 * - rekap & agregat untuk dashboard monitoring admin
 */
class Survei_model extends CI_Model
{
    // Skala bintang -> label
    public static $label_skor = array(
        1 => 'Sangat Buruk',
        2 => 'Buruk',
        3 => 'Cukup',
        4 => 'Baik',
        5 => 'Sangat Baik',
    );

    // ================= MASTER ASPEK =================
    public function aspek_all($hanya_aktif = false)
    {
        $this->db->from('survei_aspek');
        if ($hanya_aktif) {
            $this->db->where('is_active', 1);
        }
        $this->db->order_by('urutan', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function aspek_get($id)
    {
        return $this->db->get_where('survei_aspek', array('id' => (int) $id))->row();
    }

    public function aspek_exists_kode($kode, $except_id = 0)
    {
        $this->db->where('kode', trim($kode));
        if ((int) $except_id > 0) {
            $this->db->where('id !=', (int) $except_id);
        }
        return $this->db->count_all_results('survei_aspek') > 0;
    }

    public function aspek_count_jawaban($id)
    {
        return (int) $this->db->where('id_aspek', (int) $id)->count_all_results('survei_jawaban');
    }

    public function aspek_next_urutan()
    {
        return (int) $this->db->select_max('urutan')->get('survei_aspek')->row('urutan') + 1;
    }

    public function aspek_insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('survei_aspek', $data);
        return (int) $this->db->insert_id();
    }

    public function aspek_update($id, $data)
    {
        $this->db->where('id', (int) $id);
        return $this->db->update('survei_aspek', $data);
    }

    public function aspek_delete($id)
    {
        $this->db->where('id', (int) $id);
        return $this->db->delete('survei_aspek');
    }

    // ================= TRANSAKSI PENYIMPANAN =================
    /**
     * Simpan 1 respon beserta seluruh jawabannya.
     *
     * @param array $header  baris tabel survei_responden
     * @param array $jawaban array('id_aspek' => int, 'skor' => int)
     * @return array|false  ['id' => int, 'kode' => string] atau FALSE
     */
    public function simpan_responden($header, $jawaban)
    {
        $this->db->trans_begin();

        $this->db->insert('survei_responden', $header);
        $id = (int) $this->db->insert_id();
        if (!$id) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $now = date('Y-m-d H:i:s');
        $rows = array();
        foreach ($jawaban as $j) {
            $rows[] = array(
                'id_responden' => $id,
                'id_aspek'     => (int) $j['id_aspek'],
                'skor'         => (int) $j['skor'],
                'created_at'   => $now,
            );
        }
        if ($rows) {
            $this->db->insert_batch('survei_jawaban', $rows);
        }

        if (!$this->db->trans_status()) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->trans_commit();
        return array('id' => $id, 'kode' => $header['kode']);
    }

    /**
     * Nomor respon berikutnya, mis. RESP-20260131-0007
     */
    public function kode_responden()
    {
        $prefix = 'RESP-' . date('Ymd') . '-';
        $last = $this->db->select('kode')
            ->like('kode', $prefix, 'after')
            ->order_by('kode', 'DESC')
            ->limit(1)
            ->get('survei_responden')
            ->row();

        $next = 1;
        if ($last) {
            $next = (int) substr($last->kode, strlen($prefix)) + 1;
        }
        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    // ================= ANTI-SPAM =================
    public function sudah_survei_hari_ini($fingerprint)
    {
        return (int) $this->db->where('fingerprint', $fingerprint)
            ->where('created_at >=', date('Y-m-d 00:00:00'))
            ->count_all_results('survei_antispam') > 0;
    }

    public function catat_fingerprint($fingerprint)
    {
        $this->db->insert('survei_antispam', array(
            'fingerprint' => $fingerprint,
            'created_at'  => date('Y-m-d H:i:s'),
        ));
    }

    // ================= QUERY PEMBANGUN =================
    private function _filter($f = array())
    {
        if (!empty($f['bulan']) && !empty($f['tahun'])) {
            $this->db->where('MONTH(r.tanggal_survei) =', (int) $f['bulan']);
            $this->db->where('YEAR(r.tanggal_survei) =', (int) $f['tahun']);
        } elseif (!empty($f['tahun'])) {
            $this->db->where('YEAR(r.tanggal_survei) =', (int) $f['tahun']);
        } elseif (!empty($f['dari'])) {
            $this->db->where('r.tanggal_survei >=', $f['dari'] . ' 00:00:00');
        }
        if (!empty($f['sampai'])) {
            $this->db->where('r.tanggal_survei <=', $f['sampai'] . ' 23:59:59');
        }
        if (!empty($f['id_unit'])) {
            $this->db->where('r.id_unit', (int) $f['id_unit']);
        }
        if (!empty($f['predikat'])) {
            $this->db->where('r.predikat', $f['predikat']);
        }
        if (isset($f['is_kritik']) && $f['is_kritik'] !== '' && $f['is_kritik'] !== null) {
            $this->db->where('r.is_kritik', (int) $f['is_kritik']);
        }
        if (!empty($f['q'])) {
            $this->db->group_start();
            $this->db->like('r.nama', $f['q']);
            $this->db->or_like('r.no_rm', $f['q']);
            $this->db->or_like('r.nik', $f['q']);
            $this->db->or_like('r.kode', $f['q']);
            $this->db->or_like('r.saran', $f['q']);
            $this->db->group_end();
        }
    }

    // ================= DAFTAR RESPON =================
    public function count_responden($f = array())
    {
        $this->db->from('survei_responden r');
        $this->_filter($f);
        return (int) $this->db->count_all_results();
    }

    public function list_responden($f = array(), $limit = 0, $start = 0)
    {
        $this->db->select('r.*, u.nm_unit')
            ->from('survei_responden r')
            ->join('unit u', 'u.id_unit = r.id_unit', 'left');
        $this->_filter($f);
        $this->db->order_by('r.tanggal_survei', 'DESC');
        $this->db->order_by('r.id', 'DESC');
        if ($limit > 0) {
            $this->db->limit($limit, $start);
        }
        return $this->db->get()->result();
    }

    public function get_responden($id)
    {
        return $this->db->select('r.*, u.nm_unit')
            ->from('survei_responden r')
            ->join('unit u', 'u.id_unit = r.id_unit', 'left')
            ->where('r.id', (int) $id)
            ->get()
            ->row();
    }

    public function get_responden_by_kode($kode)
    {
        return $this->db->where('kode', $kode)->get('survei_responden')->row();
    }

    public function delete_responden($id)
    {
        $this->db->where('id', (int) $id);
        return $this->db->delete('survei_responden');
    }

    /**
     * Jawaban satu responden, di-join dengan master aspek.
     */
    public function jawaban_responden($id_responden)
    {
        return $this->db->select('j.skor, a.id AS id_aspek, a.nama_aspek, a.icon, a.urutan')
            ->from('survei_jawaban j')
            ->join('survei_aspek a', 'a.id = j.id_aspek')
            ->where('j.id_responden', (int) $id_responden)
            ->order_by('a.urutan', 'ASC')
            ->get()
            ->result();
    }

    // ================= AGREGAT / DASHBOARD =================
    /**
     * Ringkasan utama untuk kartu KPI & grafik tren.
     */
    public function statistik($f = array())
    {
        $row = $this->db->select(
            'COUNT(*) AS total,
             AVG(r.skor_rata) AS rata,
             SUM(CASE WHEN r.predikat IN ("Sangat Baik","Baik") THEN 1 ELSE 0 END) AS tuntas,
             SUM(r.is_kritik) AS kritik,
             SUM(CASE WHEN r.rekomendasi IS NOT NULL THEN 1 ELSE 0 END) AS ada_nps,
             SUM(CASE WHEN r.rekomendasi >= 9 THEN 1 ELSE 0 END) AS promoter,
             SUM(CASE WHEN r.rekomendasi <= 6 THEN 1 ELSE 0 END) AS detractor'
        )
            ->from('survei_responden r');
        $this->_filter($f);
        $row = $this->db->get()->row();

        $total = (int) $row->total;
        $out = array(
            'total'    => $total,
            'rata'     => $total ? round((float) $row->rata, 2) : 0,
            'tuntas'   => (int) $row->tuntas,
            'kritik'   => (int) $row->kritik,
            'promoter' => (int) $row->promoter,
            'detractor'=> (int) $row->detractor,
        );
        $out['persen_tuntas'] = $total ? round($out['tuntas'] * 100 / $total, 1) : 0;
        $ada_nps = (int) $row->ada_nps;
        // Net Promoter Score = % promoter - % detractor
        $out['nps'] = $ada_nps
            ? (int) round($out['promoter'] * 100 / $ada_nps - $out['detractor'] * 100 / $ada_nps)
            : 0;
        return $out;
    }

    /**
     * Rata-rata skor tiap aspek (untuk grafik batang horizontal).
     */
    public function rekap_aspek($f = array())
    {
        $this->db->select('a.id, a.nama_aspek, a.icon, a.urutan, a.bobot,
                            COUNT(j.id) AS jml, ROUND(AVG(j.skor), 2) AS rata,
                            SUM(CASE WHEN j.skor <= 2 THEN 1 ELSE 0 END) AS tidak_baik')
            ->from('survei_jawaban j')
            ->join('survei_aspek a', 'a.id = j.id_aspek')
            ->join('survei_responden r', 'r.id = j.id_responden');
        $this->_filter($f);
        $this->db->group_by('a.id, a.nama_aspek, a.icon, a.urutan, a.bobot');
        $this->db->order_by('rata', 'ASC');
        $this->db->order_by('jml', 'DESC');
        return $this->db->get()->result();
    }

    /**
     * Sebaran jumlah bintang 1..5 (untuk grafik donat).
     */
    public function sebaran_skor($f = array())
    {
        $out = array(1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);
        $this->db->select('j.skor, COUNT(*) AS jml', false)
            ->from('survei_jawaban j')
            ->join('survei_responden r', 'r.id = j.id_responden');
        $this->_filter($f);
        $this->db->group_by('j.skor');
        foreach ($this->db->get()->result() as $r) {
            $s = (int) $r->skor;
            if (isset($out[$s])) {
                $out[$s] = (int) $r->jml;
            }
        }
        return $out;
    }

    /**
     * Sebaran predikat (Sangat Baik / Baik / Cukup / Buruk).
     */
    public function sebaran_predikat($f = array())
    {
        $out = array('Sangat Baik' => 0, 'Baik' => 0, 'Cukup' => 0, 'Buruk' => 0);
        $this->db->select('r.predikat, COUNT(*) AS jml')
            ->from('survei_responden r');
        $this->_filter($f);
        $this->db->group_by('r.predikat');
        foreach ($this->db->get()->result() as $r) {
            if (isset($out[$r->predikat])) {
                $out[$r->predikat] = (int) $r->jml;
            }
        }
        return $out;
    }

    /**
     * Tren bulanan: jumlah respon & rata-rata skor per bulan.
     * Tahun default = tahun berjalan.
     */
    public function tren_bulanan($tahun = null)
    {
        $tahun = $tahun ? (int) $tahun : (int) date('Y');
        $rows = $this->db->select('MONTH(r.tanggal_survei) AS bln, COUNT(*) AS jml, ROUND(AVG(r.skor_rata), 2) AS rata', false)
            ->from('survei_responden r')
            ->where('YEAR(r.tanggal_survei) =', $tahun)
            ->group_by('MONTH(r.tanggal_survei)')
            ->order_by('bln', 'ASC')
            ->get()
            ->result();

        $data = array();
        for ($i = 1; $i <= 12; $i++) {
            $data[$i] = array('bln' => $i, 'jml' => 0, 'rata' => 0);
        }
        foreach ($rows as $r) {
            $b = (int) $r->bln;
            if (isset($data[$b])) {
                $data[$b] = array('bln' => $b, 'jml' => (int) $r->jml, 'rata' => (float) $r->rata);
            }
        }
        return $data;
    }

    /**
     * Rata-rata skor per unit layanan.
     */
    public function rekap_unit($f = array(), $limit = 10)
    {
        $this->db->select('u.id_unit, u.nm_unit, COUNT(*) AS jml, ROUND(AVG(r.skor_rata), 2) AS rata')
            ->from('survei_responden r')
            ->join('unit u', 'u.id_unit = r.id_unit');
        $this->_filter($f);
        $this->db->group_by('u.id_unit, u.nm_unit');
        $this->db->order_by('rata', 'ASC');
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        return $this->db->get()->result();
    }

    // ================= TINDAK LANJUT =================
    public function tambah_tindak_lanjut($id_responden, $id_user, $status, $catatan)
    {
        return $this->db->insert('survei_tindak_lanjut', array(
            'id_responden' => (int) $id_responden,
            'id_user'      => $id_user ? (int) $id_user : NULL,
            'status'       => in_array($status, array('Diproses', 'Selesai'), true) ? $status : 'Diproses',
            'catatan'      => $catatan,
            'created_at'   => date('Y-m-d H:i:s'),
        ));
    }

    public function tindak_lanjut($id_responden)
    {
        return $this->db->select('t.*, u.name AS nama_user')
            ->from('survei_tindak_lanjut t')
            ->join('users u', 'u.id = t.id_user', 'left')
            ->where('t.id_responden', (int) $id_responden)
            ->order_by('t.created_at', 'DESC')
            // created_at hanya presisi detik: dua catatan pada detik yang sama
            // perlu diurutkan dengan id, jika tidak "catatan terbaru" bisa
            // tertukar dan status yang tampil di layar bukan yang terakhir disimpan.
            ->order_by('t.id', 'DESC')
            ->get()
            ->result();
    }

    public function sudah_ditindak_lanjut($id_responden)
    {
        return (int) $this->db->where('id_responden', (int) $id_responden)
            ->count_all_results('survei_tindak_lanjut') > 0;
    }

    // ================= MASTER PENDUKUNG =================
    public function unit_pelayanan()
    {
        return $this->db->where('status', 'aktif')
            ->order_by('nm_unit', 'ASC')
            ->get('unit')
            ->result();
    }
}
/* End of Survei_model.php */
