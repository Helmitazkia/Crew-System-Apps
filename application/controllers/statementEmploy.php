<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class StatementEmploy extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

    function printStatementCrew($idperson = "")
    {
        if (!$idperson) {
            echo "ID Person tidak dikirim!";
            exit;
        }

        $sql = "
            SELECT 
                p.idperson,
                TRIM(CONCAT(p.fname, ' ', p.mname, ' ', p.lname)) AS nama_crew,
                DATE_FORMAT(c.signondt, '%d %M %Y') AS tanggal_statement,
                v.kdvsl,
                v.nmvsl AS nama_kapal,
                r.kdrank,
                r.nmrank AS nama_rank,
                p.file_statement
            FROM mstpersonal p
            LEFT JOIN tblcontract c 
                ON c.idperson = p.idperson
                AND c.deletests = 'N'
                AND c.signondt = (
                    SELECT MAX(signondt)
                    FROM tblcontract
                    WHERE idperson = p.idperson
                    AND deletests = 'N'
                )
            LEFT JOIN mstvessel v ON v.kdvsl = c.signonvsl
            LEFT JOIN mstrank r ON r.kdrank = c.signonrank
            WHERE p.idperson = '{$idperson}'
            LIMIT 1
        ";

        $result = $this->MCrewscv->getDataQuery($sql);

        if (empty($result)) {
            echo "Data tidak ditemukan!";
            exit;
        }

        $crew = $result[0];
        $data['crew'] = $crew;
        $data['today'] = date("d F Y");

        require("application/views/frontend/pdf/mpdf60/mpdf.php");
        $mpdf = new mPDF('utf-8','A4');

        ob_start();
        $this->load->view('frontend/statementEmp', $data);
        $html = ob_get_clean();

        $mpdf->WriteHTML($html);
        $mpdf->Output("STATEMENT_{$crew->idperson}.pdf", 'I');
        exit;
    }


}