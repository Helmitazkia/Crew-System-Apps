<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SPJ extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

    function getSpj($id = "")
    {
        if ($id == "") {
            echo json_encode(array('success' => false, 'message' => 'ID SPJ tidak ditemukan.'));
            return;
        }

        $sql = "
            SELECT 
                s.id,
                s.idperson,
                s.base_on,
                s.name,
                s.rank,
                s.destination,
                s.purpose,
                s.depart_date,
                s.arrival_date,
                s.transportation,
                s.note,
                s.created_at,
                s.updated_at,
                mp.fname,
                mp.mname,
                mp.lname,
                v.nmvsl AS vessel_name,
                ccmp.nmcmp AS company_name
            FROM spj s
            LEFT JOIN mstpersonal mp ON mp.idperson = s.idperson
            LEFT JOIN mstvessel v ON TRIM(v.nmvsl) = TRIM(s.destination)
            LEFT JOIN mstcmprec ccmp ON ccmp.kdcmp = v.kdcmp
            WHERE s.id = '".$id."' AND s.deletests = '0'
            LIMIT 1
        ";

        $crewData = $this->MCrewscv->getDataQuery($sql);
        if (empty($crewData)) {
            echo json_encode(array('success' => false, 'message' => 'Data SPJ tidak ditemukan.'));
            return;
        }

        $crew = $crewData[0];

        $sqlAccompany = "
            SELECT name, rank
            FROM spj_accompany
            WHERE spj_id = '".$crew->id."' AND deletests = '0'
        ";
        $accompany = $this->MCrewscv->getDataQuery($sqlAccompany);

        $data['crew'] = $crew;
        $data['accompany'] = $accompany;


        require("application/views/frontend/pdf/mpdf60/mpdf.php");
        $mpdf = new mPDF('utf-8', 'A4');

        ob_start();
        $this->load->view('frontend/spj', $data);
        $html = ob_get_contents();
        ob_end_clean();

        $mpdf->WriteHTML(utf8_encode($html));
        $mpdf->Output("SPJ_" . $crew->name . ".pdf", 'I');
        exit;
    }

    function saveSPJ()
    {
        $rawData = file_get_contents('php://input');
        $post = json_decode($rawData, true);

        if (empty($post['idperson'])) {
            echo json_encode(array('success' => false, 'message' => 'ID Person kosong'));
            return;
        }

        $idperson = trim($post['idperson']);

        $sql = "
            SELECT 
                a.idperson,
                TRIM(CONCAT(a.fname, ' ', a.mname, ' ', a.lname)) AS fullname,
                a.applyfor AS rank,
                c.nmvsl AS vessel
            FROM mstpersonal a
            LEFT JOIN tblcontract b ON a.idperson = b.idperson AND b.deletests = 0
            LEFT JOIN mstvessel c ON b.signonvsl = c.kdvsl AND c.deletests = 0
            WHERE a.deletests = 0 AND a.idperson = '".$idperson."'
            ORDER BY b.signondt DESC 
            LIMIT 1
        ";

        $data = $this->MCrewscv->getDataQuery($sql);
        if (empty($data)) {
            echo json_encode(array('success' => false, 'message' => 'Data personal tidak ditemukan'));
            return;
        }

        $p = $data[0];
        $dateNow = date('Y-m-d H:i:s');
        $username = $this->session->userdata('userInitCrewSystem');

        $header = array(
            'idperson'       => $idperson,
            'base_on'        => isset($post['base_on']) ? $post['base_on'] : '',
            'name'           => !empty($post['name']) ? $post['name'] : $p->fullname,
            'rank'           => !empty($post['rank']) ? $post['rank'] : $p->rank,
            'destination'    => isset($post['destination']) ? $post['destination'] : '',
            'purpose'        => isset($post['purpose']) ? $post['purpose'] : '',
            'depart_date'    => isset($post['depart_date']) ? $post['depart_date'] : NULL,
            'arrival_date'   => isset($post['arrival_date']) ? $post['arrival_date'] : NULL,
            'transportation' => isset($post['transportation']) ? $post['transportation'] : '',
            'note'           => isset($post['note']) ? $post['note'] : '',
            'created_by'     => $username,
            'created_at'     => $dateNow
        );

        $this->MCrewscv->insData('spj', $header);
        $spj_id = $this->db->insert_id();

        $accompany = isset($post['accompany']) ? $post['accompany'] : array();

        if (!empty($accompany) && is_array($accompany)) {
            foreach ($accompany as $a) {
                $name = isset($a['name']) ? $this->db->escape_str(trim($a['name'])) : '';
                $rank = isset($a['rank']) ? $this->db->escape_str(trim($a['rank'])) : '';

                if ($name !== '' || $rank !== '') {
                    $detail = array(
                        'idperson'   => $idperson,
                        'spj_id'     => $spj_id,
                        'name'       => $name,
                        'rank'       => $rank,
                        'created_at' => $dateNow
                    );
                    $this->MCrewscv->insData('spj_accompany', $detail);
                }
            }
        }

        echo json_encode(array(
            'success' => true,
            'message' => 'Data SPJ berhasil disimpan!',
            'spj_id'  => $spj_id
        ));
    }


}