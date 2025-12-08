<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Statement extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

    function getDataStatement($id = "")
    {
        if ($id == "") {
            echo json_encode(array('success' => false, 'message' => 'ID Person tidak ditemukan.'));
            return;
        }

        $sql = "
            SELECT 
                idperson,
                status_data_bank,
                fullname,
                npwp,
                address,
                phone,
                emergency_phone,
                relation,
                bank_name,
                bank_account,
                account_name,
                bank_address,
                created_at,
                updated_at,
                created_by
            FROM crew_statement
            WHERE idperson = '".$this->db->escape_str($id)."'
            ORDER BY created_at DESC
            LIMIT 1
        ";

        $crewData = $this->MCrewscv->getDataQuery($sql);

        if (empty($crewData)) {
            echo json_encode(array('success' => false, 'message' => 'Data Statement tidak ditemukan.'));
            return;
        }

        $crew = $crewData[0];
        $data['crew'] = $crew;

        require("application/views/frontend/pdf/mpdf60/mpdf.php");
        $mpdf = new mPDF('utf-8', 'A4');

        ob_start();
        $this->load->view('frontend/statement', $data);
        $html = ob_get_contents();
        ob_end_clean();

        $mpdf->WriteHTML(utf8_encode($html));
        $mpdf->Output("Statement_" . $crew->fullname . ".pdf", 'I');
        exit;
    }


    function saveStatementCrew()
    {
        $idperson = $this->input->post('idperson');

        if (empty($idperson)) {
            echo json_encode(array('success' => false, 'message' => 'ID Person kosong'));
            return;
        }

        $sql = "
            SELECT 
                a.idperson,
                TRIM(CONCAT(a.fname, ' ', a.mname, ' ', a.lname)) AS fullname,
                a.ptn AS npwp,
                a.paddress AS address,
                a.telpno AS phone,
                a.famtelp AS emergency_phone,
                a.famfullname AS emergency_name,
                a.bank_name AS bank_name,
                a.norek AS bank_account,
                a.norek_name AS account_name,
                a.famrelateid AS relation,
                a.famaddrs AS bank_address,
                IFNULL(a.deletests, 0) AS deletests
            FROM mstpersonal a
            WHERE 
                a.deletests = 0
                AND a.idperson = '".$this->db->escape_str($idperson)."'
            LIMIT 1
        ";

        $personal = $this->MCrewscv->getDataQuery($sql);

        if (empty($personal)) {
            echo json_encode(array('success' => false, 'message' => 'Data personal tidak ditemukan'));
            return;
        }

        $p = $personal[0];
        $username = $this->session->userdata('userInitCrewSystem');
        $date = date('Y-m-d H:i:s');

        $insert = array(
            'idperson'         => $idperson,
            'status_data_bank' => $this->input->post('status_data_bank'),
            'fullname'         => $this->input->post('fullname'),
            'npwp'             => $this->input->post('npwp'),
            'address'          => $this->input->post('address'),
            'phone'            => $this->input->post('phone'),
            'emergency_phone'  => $this->input->post('emergency_phone'),
            'relation'         => $this->input->post('relation'),
            'bank_name'        => $this->input->post('bank_name'),
            'bank_account'     => $this->input->post('bank_account'),
            'account_name'     => $this->input->post('account_name'),
            'bank_address'     => $this->input->post('bank_address'),
            'created_at'       => $date,
            'updated_at'       => $date,
            'created_by'       => $username
        );

        $this->MCrewscv->insData('crew_statement', $insert);

        echo json_encode(array('success' => true, 'message' => 'Data Statement berhasil disimpan'));
    }

}