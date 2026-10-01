<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Master_ep_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    // ================= RELASI USER <-> POKJA =================

    public function get_user_pokja_ids($id_user)
    {
        if (empty($id_user)) {
            return array();
        }

        $rows = $this->db->select('id_pokja')->where('id_user', (int)$id_user)->get('user_pokja_ep')->result();
        return array_map(function ($row) {
            return (int)$row->id_pokja;
        }, $rows);
    }

    // ================= POKJA =================

    private function _is_admin_user($id_user)
    {
        return (int)$id_user === 1;
    }

    public function get_pokja($status = 'aktif', $q = '', $id_user = NULL)
    {
        $this->db->from('pokja');
        if ($status === 'aktif') {
            $this->db->where('active', 'Y');
        } elseif ($status === 'nonaktif') {
            $this->db->where('active', 'N');
        }

        if (!empty($id_user) && !$this->_is_admin_user($id_user)) {
            $ids = $this->get_user_pokja_ids($id_user);
            if (empty($ids)) {
                return array();
            }
            $this->db->where_in('id', $ids);
        }

        if (trim($q) !== '') {
            $this->db->group_start();
            $this->db->like('bab', $q);
            $this->db->or_like('ket', $q);
            $this->db->group_end();
        }
        $this->db->order_by('id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_pokja_by_id($id)
    {
        return $this->db->where('id', (int)$id)->get('pokja')->row();
    }

    public function save_pokja($data, $id = 0)
    {
        if ((int)$id > 0) {
            $this->db->where('id', (int)$id)->update('pokja', $data);
            return (int)$id;
        }
        $this->db->insert('pokja', $data);
        return (int)$this->db->insert_id();
    }

    public function set_pokja_active($id, $active)
    {
        if (!in_array($active, array('Y', 'N'), TRUE)) {
            return FALSE;
        }
        $this->db->where('id', (int)$id)->update('pokja', array('active' => $active));
        return TRUE;
    }

    // Ambil satu pokja, tapi HANYA kalau user berhak melihatnya.
    // Dipakai halaman drill-down "Standar & EP" supaya tidak bisa accessed
    // lewat crafted URL, mis. master_ep/pokja_standar/12.
    public function get_pokja_boleh_lihat($id_pokja, $id_user, $is_admin)
    {
        $id_pokja = (int)$id_pokja;
        $id_user  = (int)$id_user;
        if ($id_pokja <= 0) {
            return NULL;
        }

        $row = $this->db->where('id', $id_pokja)->get('pokja')->row();
        if (!$row) {
            return NULL;
        }

        if ($is_admin || $this->_is_admin_user($id_user)) {
            return $row;
        }

        $ids = $this->get_user_pokja_ids($id_user);
        if (empty($ids) || !in_array($id_pokja, $ids, TRUE)) {
            return NULL;
        }

        return $row;
    }

    // Sama seperti get_pokja_boleh_lihat(), tapi mencari pokja berdasarkan
    // BAB. Dipakai halaman rapikan nomor, yang bekerja per standar.
    public function get_pokja_boleh_lihat_by_bab($bab, $id_user, $is_admin)
    {
        $id_user = (int)$id_user;
        if ($bab === '' || $bab === NULL) {
            return NULL;
        }

        $row = $this->db->where('bab', $bab)->get('pokja')->row();
        if (!$row) {
            return NULL;
        }

        if ($is_admin || $this->_is_admin_user($id_user)) {
            return $row;
        }

        $ids = $this->get_user_pokja_ids($id_user);
        if (empty($ids) || !in_array((int)$row->id, $ids, TRUE)) {
            return NULL;
        }

        return $row;
    }

    // Standar milik satu pokja, lengkap dengan jumlah EP di dalamnya.
    // Dipakai untuk accordion di halaman drill-down. Pokja sudah
    // diverifikasi-rights oleh get_pokja_boleh_lihat() sebelum dipanggil,
    // jadi di sini tidak perlu scoping per user lagi.
    public function get_standar_dengan_jumlah_ep($bab, $hanya_aktif = TRUE)
    {
        $filter_ep = $hanya_aktif ? "AND ep.active = 'Y'" : '';

        $this->db->select("
                s.id_standar,
                s.bab,
                s.no_standar,
                s.isi_standar,
                s.maksud_tujuan,
                s.active,
                (SELECT COUNT(*) FROM elemen_penilaian ep
                  WHERE ep.id_standar = s.id_standar $filter_ep
                ) AS jml_ep
            ", FALSE);
        $this->db->from('standar s');
        $this->db->where('s.bab', $bab);
        if ($hanya_aktif) {
            $this->db->where('s.active', 'Y');
        }

        // no_standar varchar, jadi ORDER BY biasa akan mengurutkan "10" sebelum "2".
        // Order by nilai angkanya supaya urutannya benar.
        // Parameter ke-3 FALSE = jangan escape, karena ini ekspresi bukan kolom.
        $this->db->order_by('s.no_standar + 0', 'ASC', FALSE);
        $this->db->order_by('s.id_standar', 'ASC');
        return $this->db->get()->result();
    }

    // EP milik satu standar, untuk isi accordion.
    public function get_ep_dengan_jumlah_bukti($id_standar, $hanya_aktif = TRUE)
    {
        $this->db->select('
                ep.id_ep,
                ep.id_standar,
                ep.no_ep,
                ep.no_urut,
                ep.isi_ep,
                ep.jenis_bukti,
                ep.skor_maks,
                ep.tdd,
                ep.active,
                (SELECT COUNT(*) FROM upload_bukti_ep ub
                   JOIN penilaian_ep pn ON pn.id_penilaian = ub.id_penilaian
                  WHERE pn.id_ep = ep.id_ep
                ) AS jml_bukti
            ', FALSE);
        $this->db->from('elemen_penilaian ep');
        $this->db->where('ep.id_standar', (int)$id_standar);
        if ($hanya_aktif) {
            $this->db->where('ep.active', 'Y');
        }
        // Urut dari no_urut (nomor tampilan). Kalau masih NULL, pakai no_ep.
        $this->db->order_by('COALESCE(ep.no_urut, ep.no_ep)', 'ASC', FALSE);
        $this->db->order_by('ep.no_ep', 'ASC');
        return $this->db->get()->result();
    }

    public function count_pokja($status = 'all', $id_user = NULL)
    {
        $this->db->from('pokja');
        if ($status === 'aktif') {
            $this->db->where('active', 'Y');
        } elseif ($status === 'nonaktif') {
            $this->db->where('active', 'N');
        }

        if (!empty($id_user) && !$this->_is_admin_user($id_user)) {
            $ids = $this->get_user_pokja_ids($id_user);
            if (empty($ids)) {
                return 0;
            }
            $this->db->where_in('id', $ids);
        }

        return (int)$this->db->count_all_results();
    }

    // ================= STANDAR =================

    public function get_standar($status = 'aktif', $q = '', $id_user = NULL)
    {
        $this->db->from('standar');
        if ($status === 'aktif') {
            $this->db->where('active', 'Y');
        } elseif ($status === 'nonaktif') {
            $this->db->where('active', 'N');
        }

        if (!empty($id_user) && !$this->_is_admin_user($id_user)) {
            $pokja_ids = $this->get_user_pokja_ids($id_user);
            if (empty($pokja_ids)) {
                return array();
            }
            $bab_rows = $this->db->select('bab')->from('pokja')->where_in('id', $pokja_ids)->get()->result();
            $babs = array();
            foreach ($bab_rows as $row) {
                $babs[] = $row->bab;
            }
            if (empty($babs)) {
                return array();
            }
            $this->db->where_in('bab', $babs);
        }

        if (trim($q) !== '') {
            $this->db->group_start();
            $this->db->like('bab', $q);
            $this->db->or_like('no_standar', $q);
            $this->db->or_like('isi_standar', $q);
            $this->db->group_end();
        }
        $this->db->order_by('bab', 'ASC');
        $this->db->order_by('no_standar', 'ASC');
        return $this->db->get()->result();
    }

    public function get_standar_by_id($id)
    {
        return $this->db->where('id_standar', (int)$id)->get('standar')->row();
    }

    public function save_standar($data, $id = 0)
    {
        if ((int)$id > 0) {
            $this->db->where('id_standar', (int)$id)->update('standar', $data);
            return (int)$id;
        }
        $this->db->insert('standar', $data);
        return (int)$this->db->insert_id();
    }

    public function set_standar_active($id, $active)
    {
        if (!in_array($active, array('Y', 'N'), TRUE)) {
            return FALSE;
        }
        $this->db->where('id_standar', (int)$id)->update('standar', array('active' => $active));
        return TRUE;
    }

    public function count_standar($status = 'all', $id_user = NULL)
    {
        $this->db->from('standar');
        if ($status === 'aktif') {
            $this->db->where('active', 'Y');
        } elseif ($status === 'nonaktif') {
            $this->db->where('active', 'N');
        }

        if (!empty($id_user) && !$this->_is_admin_user($id_user)) {
            $pokja_ids = $this->get_user_pokja_ids($id_user);
            if (empty($pokja_ids)) {
                return 0;
            }
            $bab_rows = $this->db->select('bab')->from('pokja')->where_in('id', $pokja_ids)->get()->result();
            $babs = array();
            foreach ($bab_rows as $row) {
                $babs[] = $row->bab;
            }
            if (empty($babs)) {
                return 0;
            }
            $this->db->where_in('bab', $babs);
        }

        return (int)$this->db->count_all_results();
    }

    public function get_pokja_options($id_user = NULL)
    {
        $this->db->select('id, bab, ket')->where('active', 'Y')->order_by('id', 'ASC');
        if (!empty($id_user) && !$this->_is_admin_user($id_user)) {
            $ids = $this->get_user_pokja_ids($id_user);
            if (empty($ids)) {
                return array();
            }
            $this->db->where_in('id', $ids);
        }
        return $this->db->get('pokja')->result();
    }

    // ================= ELEMEN PENILAIAN =================

    public function get_elemen($status = 'aktif', $q = '', $id_user = NULL)
    {
        $this->db->from('elemen_penilaian ep');
        $this->db->join('standar s', 's.id_standar = ep.id_standar', 'left');
        if ($status === 'aktif') {
            $this->db->where('ep.active', 'Y');
        } elseif ($status === 'nonaktif') {
            $this->db->where('ep.active', 'N');
        }

        if (!empty($id_user) && !$this->_is_admin_user($id_user)) {
            $pokja_ids = $this->get_user_pokja_ids($id_user);
            if (empty($pokja_ids)) {
                return array();
            }
            $bab_rows = $this->db->select('bab')->from('pokja')->where_in('id', $pokja_ids)->get()->result();
            $babs = array();
            foreach ($bab_rows as $row) {
                $babs[] = $row->bab;
            }
            if (empty($babs)) {
                return array();
            }
            $this->db->where_in('s.bab', $babs);
        }

        if (trim($q) !== '') {
            $this->db->group_start();
            $this->db->like('s.no_standar', $q);
            $this->db->or_like('s.isi_standar', $q);
            $this->db->or_like('ep.no_ep', $q);
            $this->db->or_like('ep.isi_ep', $q);
            $this->db->group_end();
        }
        $this->db->select('ep.*, s.no_standar, s.isi_standar, s.bab');
        $this->db->order_by('s.bab', 'ASC');
        $this->db->order_by('COALESCE(ep.no_urut, ep.no_ep)', 'ASC', FALSE);
        $this->db->order_by('ep.no_ep', 'ASC');
        return $this->db->get()->result();
    }

    public function get_elemen_by_id($id)
    {
        return $this->db->where('id_ep', (int)$id)->get('elemen_penilaian')->row();
    }

    public function save_elemen($data, $id = 0)
    {
        if ((int)$id > 0) {
            $this->db->where('id_ep', (int)$id)->update('elemen_penilaian', $data);
            return (int)$id;
        }
        $this->db->insert('elemen_penilaian', $data);
        return (int)$this->db->insert_id();
    }

    public function set_elemen_active($id, $active)
    {
        if (!in_array($active, array('Y', 'N'), TRUE)) {
            return FALSE;
        }
        $this->db->where('id_ep', (int)$id)->update('elemen_penilaian', array('active' => $active));
        return TRUE;
    }

    // ---------- NOMOR URUT TAMPILAN (no_urut) ----------
    //
    // no_ep   = nomor RESMI dari dokumen SIPARDI/Kemenkes, tidak pernah diubah.
    // no_urut = nomor tampilan tanpa celah, boleh di-rapikan.

    // Assign no_urut = no_ep untuk satu standar (dipakai saat tambah EP baru).
    public function set_no_urut_sama_dengan_no_ep($id_standar)
    {
        $this->db->where('id_standar', (int)$id_standar);
        $this->db->where('no_urut', NULL, FALSE);
        $this->db->update('elemen_penilaian', array('no_urut' => 'no_ep'), FALSE);
    }

    // Rencana penomoran ulang: EP aktif satu standar, diurutkan dari no_urut
    // sekarang (fallback ke no_ep), lalu diberi 1, 2, 3, ... tanpa celah.
    // TIDAK menyentuh no_ep. Return array berisi before => no_urut_lama,
    // after => no_urut_baru.
    public function rencana_rapikan_no_urut($id_standar)
    {
        $rows = $this->db
            ->select('id_ep, no_ep, no_urut, isi_ep')
            ->where('id_standar', (int)$id_standar)
            ->where('active', 'Y')
            ->order_by('COALESCE(no_urut, no_ep)', 'ASC', FALSE)
            ->order_by('no_ep', 'ASC')
            ->get('elemen_penilaian')
            ->result();

        $rencana = array();
        $i = 0;
        foreach ($rows as $r) {
            $i++;
            $rencana[] = array(
                'id_ep'     => (int)$r->id_ep,
                'no_ep'     => (int)$r->no_ep,
                'no_urut_lama' => ($r->no_urut === NULL) ? NULL : (int)$r->no_urut,
                'no_urut_baru' => $i,
                'isi_ep'    => $r->isi_ep,
            );
        }
        return $rencana;
    }

    // Terapkan rencana. Hanya menimpa no_urut EP yang memang berubah.
    public function jalankan_rapikan_no_urut($rencana)
    {
        if (empty($rencana)) {
            return 0;
        }

        $this->db->trans_start();
        foreach ($rencana as $r) {
            if ($r['no_urut_lama'] === $r['no_urut_baru']) {
                continue;
            }
            $this->db->where('id_ep', (int)$r['id_ep']);
            $this->db->update('elemen_penilaian', array('no_urut' => (int)$r['no_urut_baru']));
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }
        return TRUE;
    }

    public function count_elemen($status = 'all', $id_user = NULL)
    {
        $this->db->from('elemen_penilaian ep');
        $this->db->join('standar s', 's.id_standar = ep.id_standar', 'left');
        if ($status === 'aktif') {
            $this->db->where('ep.active', 'Y');
        } elseif ($status === 'nonaktif') {
            $this->db->where('ep.active', 'N');
        }

        if (!empty($id_user) && !$this->_is_admin_user($id_user)) {
            $pokja_ids = $this->get_user_pokja_ids($id_user);
            if (empty($pokja_ids)) {
                return 0;
            }
            $bab_rows = $this->db->select('bab')->from('pokja')->where_in('id', $pokja_ids)->get()->result();
            $babs = array();
            foreach ($bab_rows as $row) {
                $babs[] = $row->bab;
            }
            if (empty($babs)) {
                return 0;
            }
            $this->db->where_in('s.bab', $babs);
        }

        return (int)$this->db->count_all_results();
    }
}

/* End of file Master_ep_model.php */
