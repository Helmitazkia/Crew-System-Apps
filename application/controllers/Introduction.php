<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Introduction extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

    function getIntroduction($id = "")
    {
        if ($id == "") {
            echo json_encode(array('success' => false, 'message' => 'ID tidak ditemukan.'));
            return;
        }

        $sql = "
            SELECT 
                i.*,
                p.fname, p.mname, p.lname,
                r.nmrank AS rankname,
                v.nmvsl AS vessel_real_name
            FROM introduction i
            LEFT JOIN mstpersonal p ON p.idperson = i.idperson
            LEFT JOIN mstrank r ON r.kdrank = p.applyfor
            LEFT JOIN mstvessel v ON v.kdvsl = p.vesselfor
            WHERE i.id = '".$id."' AND i.deletests = 0
            LIMIT 1
        ";

        $query = $this->MCrewscv->getDataQuery($sql);

        if (empty($query)) {
            echo json_encode(array('success' => false, 'message' => 'Data tidak ditemukan.'));
            return;
        }

        $crew = $query[0];

        $crew->release_rows   = json_decode($crew->release_json);
        $crew->successor_rows = json_decode($crew->successor_json);

        $data['crew'] = $crew;

        require("application/views/frontend/pdf/mpdf60/mpdf.php");
        $mpdf = new mPDF('utf-8', 'A4');

        ob_start();
        $this->load->view('frontend/introduction', $data);
        $html = ob_get_contents();
        ob_end_clean();

        $mpdf->WriteHTML($html);
        $mpdf->Output("INTRODUCTION_" . $crew->id . ".pdf", 'I');
        exit;
    }


    function saveIntroduction()
    {
        $idperson = $this->input->post('idperson');

        if (empty($idperson)) {
            echo json_encode(array('success' => false, 'message' => 'ID Person kosong'));
            return;
        }

        $username = $this->session->userdata('userInitCrewSystem');
        $now = date('Y-m-d H:i:s');

        $insert = array(
            'idperson'                 => $idperson,
            'entitas'                  => $this->input->post('entitas'),
            'vessel_name'              => $this->input->post('vessel_name'),
            'port_signonoff'           => $this->input->post('port'),
            'tanggal_instruction'      => $this->input->post('tanggal'),

            'release_header_others'    => $this->input->post('release_header_others'),
            'successor_header_others'  => $this->input->post('successor_header_others'),

            'release_json'             => $this->input->post('release'),
            'successor_json'           => $this->input->post('successor'),

            'created_at'               => $now,
            'updated_at'               => $now,
            'created_by'               => $username
        );

        // INSERT
        $this->MCrewscv->insData('introduction', $insert);
        $insert_id = $this->db->insert_id();  // ← AMBIL ID BARU

        echo json_encode(array(
            'success' => true,
            'message' => 'Introduction berhasil disimpan',
            'id'      => $insert_id              // ← KEMBALIKAN DI RESPONSE
        ));
    }



}