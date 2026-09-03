<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penilaian_ep_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    // ================= PERIODE =================

    public function get_active_periode()
    {
        return $this->db->where('status', 'aktif')->get('periode_akreditasi')->row();
    }

    // ================= POKJA =================

    public function get_pokja_by_bab($bab)
    {
        return $this->db->where('bab', $bab)->where('active', 'Y')->get('pokja')->row();
    }

    // Daftar pokja + progres skor untuk periode berjalan, KHUSUS 1 jenis penilaian
    // ($jenis_penilaian = 'internal' atau 'surveior' -- ditentukan controller berdasar role user)
    public function get_pokja_progress($id_periode, $jenis_penilaian = 'internal')
    {
        $this->db->select("
                pokja.bab,
                pokja.ket,
                COUNT(DISTINCT ep.id_ep) AS total_ep,
                COUNT(DISTINCT pn.id_ep) AS ep_dinilai,
                COALESCE(SUM(pn.skor), 0) AS total_skor,
                COALESCE(SUM(ep.skor_maks), 0) AS total_skor_maks
            ", FALSE);
        $this->db->from('pokja');
        $this->db->join('standar', 'standar.bab = pokja.bab');
        $this->db->join('elemen_penilaian ep', "ep.id_standar = standar.id_standar AND ep.tdd = 'N'");
        $this->db->join('penilaian_ep pn', "pn.id_ep = ep.id_ep AND pn.id_periode = " . intval($id_periode) . " AND pn.jenis_penilaian = " . $this->db->escape($jenis_penilaian), 'left');
        $this->db->where('pokja.active', 'Y');
        $this->db->group_by('pokja.bab, pokja.ket');
        $this->db->order_by('pokja.id', 'ASC');
        return $this->db->get()->result();
    }

    // ================= EP =================

    // Daftar EP 1 pokja, sudah sekalian bawa DUA track skor (internal & surveior)
    // supaya bisa ditampilkan berdampingan untuk dibandingkan.
    public function get_ep_by_pokja($bab, $id_periode)
    {
        $this->db->select("
                standar.no_standar, standar.isi_standar,
                ep.id_ep, ep.no_ep, ep.isi_ep, ep.skor_maks,

                pi.id_penilaian AS id_penilaian_internal,
                pi.skor AS skor_internal,
                pi.keterangan AS keterangan_internal,
                pi.dinilai_oleh AS dinilai_oleh_internal,
                (SELECT COUNT(*) FROM upload_bukti_ep ub WHERE ub.id_penilaian = pi.id_penilaian) AS jml_bukti_internal,

                ps.id_penilaian AS id_penilaian_surveior,
                ps.skor AS skor_surveior,
                ps.keterangan AS keterangan_surveior,
                ps.dinilai_oleh AS dinilai_oleh_surveior,
                (SELECT COUNT(*) FROM upload_bukti_ep ub2 WHERE ub2.id_penilaian = ps.id_penilaian) AS jml_bukti_surveior
            ", FALSE);
        $this->db->from('standar');
        $this->db->join('elemen_penilaian ep', "ep.id_standar = standar.id_standar AND ep.tdd = 'N'");
        $this->db->join('penilaian_ep pi', "pi.id_ep = ep.id_ep AND pi.id_periode = " . intval($id_periode) . " AND pi.jenis_penilaian = 'internal'", 'left');
        $this->db->join('penilaian_ep ps', "ps.id_ep = ep.id_ep AND ps.id_periode = " . intval($id_periode) . " AND ps.jenis_penilaian = 'surveior'", 'left');
        $this->db->where('standar.bab', $bab);
        $this->db->order_by('ep.id_ep', 'ASC');
        return $this->db->get()->result();
    }

    // ================= PENILAIAN_EP =================

    public function get_penilaian($id_periode, $id_ep, $jenis_penilaian)
    {
        return $this->db->where('id_periode', $id_periode)
                         ->where('id_ep', $id_ep)
                         ->where('jenis_penilaian', $jenis_penilaian)
                         ->get('penilaian_ep')->row();
    }

    public function get_penilaian_by_id($id_penilaian)
    {
        return $this->db->where('id_penilaian', $id_penilaian)->get('penilaian_ep')->row();
    }

    // insert kalau belum ada baris untuk periode+ep+jenis ini, update kalau sudah ada
    public function upsert_skor($id_periode, $id_ep, $jenis_penilaian, $skor, $keterangan, $dinilai_oleh)
    {
        $existing = $this->get_penilaian($id_periode, $id_ep, $jenis_penilaian);

        if ($existing) {
            $this->db->where('id_penilaian', $existing->id_penilaian);
            $this->db->update('penilaian_ep', array(
                'skor'         => $skor,
                'keterangan'   => $keterangan,
                'dinilai_oleh' => $dinilai_oleh,
            ));
            return $existing->id_penilaian;
        }

        $this->db->insert('penilaian_ep', array(
            'id_periode'      => $id_periode,
            'id_ep'           => $id_ep,
            'jenis_penilaian' => $jenis_penilaian,
            'skor'            => $skor,
            'keterangan'      => $keterangan,
            'dinilai_oleh'    => $dinilai_oleh,
        ));
        return $this->db->insert_id();
    }

    // dipakai saat upload bukti duluan sebelum skor diisi
    public function get_or_create_penilaian($id_periode, $id_ep, $jenis_penilaian, $dinilai_oleh)
    {
        $existing = $this->get_penilaian($id_periode, $id_ep, $jenis_penilaian);
        if ($existing) return $existing->id_penilaian;

        $this->db->insert('penilaian_ep', array(
            'id_periode'      => $id_periode,
            'id_ep'           => $id_ep,
            'jenis_penilaian' => $jenis_penilaian,
            'skor'            => NULL,
            'dinilai_oleh'    => $dinilai_oleh,
        ));
        return $this->db->insert_id();
    }

    // ================= UPLOAD_BUKTI_EP =================

    public function get_bukti_by_penilaian($id_penilaian)
    {
        return $this->db->where('id_penilaian', $id_penilaian)->order_by('uploaded_at', 'DESC')->get('upload_bukti_ep')->result();
    }

    public function get_bukti_by_id($id_upload)
    {
        return $this->db->where('id_upload', $id_upload)->get('upload_bukti_ep')->row();
    }

    public function insert_bukti($data)
    {
        $this->db->insert('upload_bukti_ep', $data);
        return $this->db->insert_id();
    }

    public function delete_bukti($id_upload)
    {
        $this->db->where('id_upload', $id_upload);
        $this->db->delete('upload_bukti_ep');
    }

    // ================= SUMMARY PERBANDINGAN =================

    // Rekap per pokja: total EP, skor internal vs skor surveior, jumlah EP yang beda skor.
    // Dipakai di halaman /penilaian_ep/summary
    public function get_summary($id_periode)
    {
        $this->db->select("
                pokja.bab,
                pokja.ket,
                COUNT(DISTINCT ep.id_ep) AS total_ep,
                COALESCE(SUM(ep.skor_maks), 0) AS total_skor_maks,

                COUNT(DISTINCT pi.id_ep) AS ep_dinilai_internal,
                COALESCE(SUM(pi.skor), 0) AS total_skor_internal,

                COUNT(DISTINCT ps.id_ep) AS ep_dinilai_surveior,
                COALESCE(SUM(ps.skor), 0) AS total_skor_surveior,

                SUM(CASE WHEN pi.skor IS NOT NULL AND ps.skor IS NOT NULL AND pi.skor <> ps.skor THEN 1 ELSE 0 END) AS jml_selisih,
                SUM(CASE WHEN pi.skor IS NOT NULL AND ps.skor IS NOT NULL THEN 1 ELSE 0 END) AS jml_terverifikasi
            ", FALSE);
        $this->db->from('pokja');
        $this->db->join('standar', 'standar.bab = pokja.bab');
        $this->db->join('elemen_penilaian ep', "ep.id_standar = standar.id_standar AND ep.tdd = 'N'");
        $this->db->join('penilaian_ep pi', "pi.id_ep = ep.id_ep AND pi.id_periode = " . intval($id_periode) . " AND pi.jenis_penilaian = 'internal'", 'left');
        $this->db->join('penilaian_ep ps', "ps.id_ep = ep.id_ep AND ps.id_periode = " . intval($id_periode) . " AND ps.jenis_penilaian = 'surveior'", 'left');
        $this->db->where('pokja.active', 'Y');
        $this->db->group_by('pokja.bab, pokja.ket');
        $this->db->order_by('pokja.id', 'ASC');
        return $this->db->get()->result();
    }
}

/* End of file Penilaian_ep_model.php */
/* Location: ./application/models/Penilaian_ep_model.php */