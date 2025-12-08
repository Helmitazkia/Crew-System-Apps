<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Wages extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

    function index()
    {
        $this->getWages();
    }

    function getWages($id = "")
    {
        if ($id == "") {
            echo json_encode(array('success' => false, 'message' => 'ID Person tidak ditemukan.'));
            return;
        }

        $sql = "
            SELECT 
                wg.idperson,
                wg.name AS fullname,
                wg.position,
                wg.vessel,
                wg.sign_on_date,
                wg.embarkation_port,
                wg.sea_service,
                wg.basic_wages,
                wg.fot,
                wg.tanker_allow,
                wg.leave_pay,
                wg.bs_percent,
                wg.hs_percent,
                wg.total_pay,
                wg.next_of_kin_name,
                wg.next_of_kin_relation,
                wg.next_of_kin_phone,
                wg.created_at,
                wg.updated_at,
                v.nmvsl AS vessel_name,
                ccmp.nmcmp AS company_name
            FROM wages wg
            LEFT JOIN mstvessel v ON TRIM(v.nmvsl) = TRIM(wg.vessel)
            LEFT JOIN mstcmprec ccmp ON ccmp.kdcmp = v.kdcmp
            WHERE wg.idperson = '".$id."'
            ORDER BY wg.created_at DESC
            LIMIT 1
        ";

        $crewData = $this->MCrewscv->getDataQuery($sql);
        if (empty($crewData)) {
            echo json_encode(array('success' => false, 'message' => 'Data Wages tidak ditemukan.'));
            return;
        }
        
        $crew = $crewData[0];

        $data['crew'] = $crew;

        require("application/views/frontend/pdf/mpdf60/mpdf.php");
        $mpdf = new mPDF('utf-8', 'A4');

        ob_start();
        $this->load->view('frontend/wages', $data);
        $html = ob_get_contents();
        ob_end_clean();

        $mpdf->WriteHTML(utf8_encode($html));
        $mpdf->Output("Wages_" . $crew->fullname . ".pdf", 'I');
        exit;
    }


    function saveWagesData()
    {
        $idperson = $this->input->post('idperson');
        if (empty($idperson)) {
            echo json_encode(array('success' => false, 'message' => 'ID Person kosong'));
            return;
        }

        $sql = "
            SELECT 
                p.idperson,
                CONCAT(TRIM(p.fname), ' ', TRIM(p.mname), ' ', TRIM(p.lname)) AS fullname,
                p.famfullname, 
                p.famrelateid, 
                p.famtelp, 
                p.fammobile,
                p.applyfor AS position, 
                TRIM(p.vesselfor) AS personal_vesselfor,
                TRIM(c.signonvsl) AS contract_vesselfor,
                COALESCE(vc.nmvsl, vp.nmvsl) AS vessel_name,
                c.signondt AS sign_on_date,
                c.signoffdt AS sign_off_date,
                c.signonport AS embarkation_port
            FROM mstpersonal p
            LEFT JOIN tblcontract c ON p.idperson = c.idperson
            LEFT JOIN mstvessel vc ON TRIM(c.signonvsl) = TRIM(vc.kdvsl)
            LEFT JOIN mstvessel vp ON TRIM(p.vesselfor) = TRIM(vp.kdvsl)
            WHERE p.idperson = '".$this->db->escape_str($idperson)."'
            AND p.deletests = 0
            ORDER BY c.signondt DESC
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

        $vesselName = !empty($p->vessel_name) ? $p->vessel_name : (
            !empty($p->contract_vesselfor) ? $p->contract_vesselfor : (
                !empty($p->personal_vesselfor) ? $p->personal_vesselfor : 'Unknown Vessel'
            )
        );

        $sea_service = '';
        if (!empty($p->sign_on_date) && !empty($p->sign_off_date) && $p->sign_off_date != '0000-00-00') {
            try {
                $signOn = new DateTime($p->sign_on_date);
                $signOff = new DateTime($p->sign_off_date);
                $diff = $signOn->diff($signOff);

                $months = $diff->m + ($diff->y * 12);
                $days = $diff->d;

                if ($months > 0 && $days > 0) $sea_service = "{$months} Bulan {$days} Hari";
                elseif ($months > 0) $sea_service = "{$months} Bulan";
                elseif ($days > 0) $sea_service = "{$days} Hari";
                else $sea_service = "0 Hari";
            } catch (Exception $e) {
                $sea_service = '';
            }
        } else {
            $sea_service = '';
        }

        $insert = array(
            'idperson' => $idperson,
            'name' => $p->fullname,
            'position' => $p->position,
            'vessel' => $vesselName,
            'sign_on_date' => (!empty($p->sign_on_date) ? $p->sign_on_date : null),
            'embarkation_port' => (!empty($p->embarkation_port) ? $p->embarkation_port : null),
            'sea_service' => $sea_service,
            'basic_wages' => $this->input->post('basic_wages'),
            'fot' => $this->input->post('fot'),
            'tanker_allow' => $this->input->post('tanker_allow'),
            'leave_pay' => $this->input->post('leave_pay'),
            'bs_percent' => $this->input->post('bs_percent'),
            'hs_percent' => $this->input->post('hs_percent'),   
            'total_pay' => $this->input->post('total_pay'),
            'next_of_kin_name' => $p->famfullname,
            'next_of_kin_relation' => $p->famrelateid,
            'next_of_kin_phone' => !empty($p->famtelp) ? $p->famtelp : $p->fammobile,
            'created_at' => $date,
        );

        $this->MCrewscv->insData('wages', $insert);

        echo json_encode(array('success' => true, 'message' => 'Data Wages berhasil disimpan'));
    }
        
}