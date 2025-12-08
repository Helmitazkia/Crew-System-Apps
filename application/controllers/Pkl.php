<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class PKL extends CI_Controller {
    function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

    function index()
    {
        $this->getPKL();
    }

    function getPKL($id = "")
	{
		if ($id == "") {
			echo json_encode(array('success' => false, 'message' => 'ID Person tidak ditemukan.'));
			return;
		}

		$dataOut = array();

		$sql = "
			SELECT 
				TRIM(CONCAT(p.fname, ' ', p.mname, ' ', p.lname)) AS fullname,
				p.dob,
				k.NmKota AS pob,
				p.idperson,
				p.nationalid AS nationality,
				p.duration,
				p.paddress,
				p.telpno,
				p.applyfor,
				p.vesselfor,
				p.duration,
				v.nmvsl AS vessel_name,
				v.st_own AS st_own,
				ccmp.nmcmp AS company_name,
				p.flag,
				p.imo,
				p.grt_hp,
				p.competency_cert,
				p.safety_cert,
				p.kodepelaut AS seafarer_code,
				p.passportno,
				p.seamanbookno
			FROM mstpersonal p
			LEFT JOIN tblkota k ON k.KdKota = p.pob
			LEFT JOIN tblcertdoc c ON c.idperson = p.idperson AND c.deletests = 0
			LEFT JOIN mstvessel v ON v.kdvsl = p.vesselfor AND v.deletests = 0
			LEFT JOIN mstcmprec ccmp ON ccmp.kdcmp = v.kdcmp AND ccmp.deletests = 0
			WHERE p.idperson = '".$id."' 
			AND p.deletests = 0
			GROUP BY 	
				p.fname, p.mname, p.lname, p.dob, k.NmKota,
				p.idperson, p.nationalid, p.paddress, p.telpno,
				p.applyfor, p.vesselfor, v.nmvsl, v.st_own, ccmp.nmcmp,
				p.flag, p.imo, p.grt_hp, p.kodepelaut, 
				p.competency_cert, p.safety_cert
		";

		$dataCrew = $this->MCrewscv->getDataQuery($sql);

		if (empty($dataCrew)) {
			echo json_encode(array('success' => false, 'message' => 'Data crew tidak ditemukan untuk ID: ' . $id));
			return;
		}

		$crew = $dataCrew[0];

		if (!empty($crew->st_own) && $crew->st_own === "Y") {
			$dataOut['crewing_position'] = "CREWING MANAGER";
		} else {
			$dataOut['crewing_position'] = "HEAD OF CREWING DIVISION";
		}

		$crew->vessel_name       = !empty($crew->vessel_name) ? $crew->vessel_name : "";
		$crew->company_name      = !empty($crew->company_name) ? $crew->company_name : "";
		$crew->flag              = !empty($crew->flag) ? $crew->flag : "";
		$crew->imo               = !empty($crew->imo) ? $crew->imo : "";
		$crew->grt_hp            = !empty($crew->grt_hp) ? $crew->grt_hp : "";
		$crew->competency_cert   = !empty($crew->competency_cert) ? $crew->competency_cert : "";
		$crew->safety_cert       = !empty($crew->safety_cert) ? $crew->safety_cert : "";

		$dataOut['crew'] = $crew;
		$dataOut['idPerson'] = $id;

		$sqlContract = "SELECT idcontract FROM tblcontract WHERE idperson = '".$id."' AND deletests = 0 ORDER BY idcontract DESC LIMIT 1";
		$contract = $this->MCrewscv->getDataQuery($sqlContract);

		if (!empty($contract)) {
			$idcontract = $contract[0]->idcontract;

			$sqlSal = "
				SELECT saltype, amount 
				FROM tblsalcon 
				WHERE idcontract = '".$idcontract."' 
				AND saltype IN ('001','006','008')
			";

			$salaryData = $this->MCrewscv->getDataQuery($sqlSal);

			$basic = 0; $fix = 0; $leave = 0;
			foreach ($salaryData as $salary) {
				switch ($salary->saltype) {
					case '001': $basic = $salary->amount; break;
					case '006': $fix = $salary->amount; break;
					case '008': $leave = $salary->amount; break;
				}
			}

			$total = $basic + $fix + $leave;

			$dataOut['salary'] = array(
				'basic' => $basic,
				'fix' => $fix,
				'leave' => $leave,
				'total' => $total
			);
		} else {
			$dataOut['salary'] = array('basic'=>0,'fix'=>0,'leave'=>0,'total'=>0);
		}

		require("application/views/frontend/pdf/mpdf60/mpdf.php");
		$mpdf = new mPDF('utf-8', 'A4');

		ob_start();
		$this->load->view('frontend/pkl', $dataOut);
		$html = ob_get_contents();
		ob_end_clean();

		$mpdf->WriteHTML($html);
		$mpdf->Output("PKL_" . $crew->fullname . ".pdf", 'I');
		exit;
	} 

	function saveVesselData()
	{
		$idperson  = $this->input->post('idperson');
		$dob = $this->input->post('dob');
		$kodepelaut = $this->input->post('kodepelaut');
		$passportno    = $this->input->post('passportno');
		$seamanbookno  = $this->input->post('seamanbookno');
		$address       = $this->input->post('paddress');
		$txtVesselFor = $this->input->post('txtVesselFor');
		$flag      = $this->input->post('flag');
		$imo       = $this->input->post('imo');
		$grt_hp    = trim($this->input->post('grt_hp'));
		$txtSafetyCert = $this->input->post('txtSafetyCert');
		$txtCompetencyCert = $this->input->post('txtCompetencyCert');
		$txtBasicWage = $this->input->post('txtBasicWage');
		$txtFixOvertime = $this->input->post('txtFixOvertime');
		$txtLeavePay = $this->input->post('txtLeavePay');
		$txtduration = $this->input->post('txtduration');

		$username = $this->session->userdata('userInitCrewSystem');
		$date = date('Y-m-d H:i:s');

		if (empty($idperson)) {
			echo json_encode(array('success' => false, 'message' => 'ID Person kosong'));
			return;
		}

		$sqlVessel = "
			SELECT 
				v.kdvsl, v.nmvsl, v.imo, v.grt, v.serpel AS competency_cert,
				v.descvsl AS safety_cert,
				c.nmcmp AS company_name
			FROM mstvessel v
			LEFT JOIN mstcmprec c ON c.kdcmp = v.kdcmp AND c.deletests = 0
			WHERE v.kdvsl = '".$txtVesselFor."' AND v.deletests = 0
			LIMIT 1
		";
		
		$vesselData = $this->MCrewscv->getDataQuery($sqlVessel);
		$companyName = '';
		$vesselName = '';

		if (!empty($vesselData)) {
			$vesselName = $vesselData[0]->nmvsl;
			$companyName = $vesselData[0]->company_name;
			if (empty($flag)) $flag = 'INDONESIA';
			if (empty($imo)) $imo = $vesselData[0]->imo;
			if (empty($grt_hp)) $grt_hp = $vesselData[0]->grt;
			if (empty($txtCompetencyCert)) $txtCompetencyCert = $vesselData[0]->competency_cert;
			if (empty($txtSafetyCert)) $txtSafetyCert = $vesselData[0]->safety_cert;
		}

		$updateData = array(
			'dob'           => $dob,
			'kodepelaut'    => $kodepelaut,
			'paddress'      => $address,
			'passportno'    => $passportno,
			'seamanbookno'  => $seamanbookno,
			'vesselfor'     => $txtVesselFor,
			'duration'      => $txtduration,
			'flag'          => $flag,
			'imo'           => $imo,
			'grt_hp'        => $grt_hp,
			'competency_cert' => $txtCompetencyCert,
			'safety_cert'   => $txtSafetyCert,
			'updusrdt'      => $username . "#" . $date
		);


		$this->MCrewscv->updateData(array('idperson' => $idperson), $updateData, 'mstpersonal');
		
		$sqlContract = "SELECT idcontract FROM tblcontract WHERE idperson = '".$idperson."' AND deletests = 0 ORDER BY idcontract DESC LIMIT 1";
		$contract = $this->MCrewscv->getDataQuery($sqlContract);

		if (!empty($contract)) {
			$idcontract = $contract[0]->idcontract;
			$salaryData = array(
				'001' => $txtBasicWage,
				'006' => $txtFixOvertime,
				'008' => $txtLeavePay
			);

			foreach ($salaryData as $saltype => $amount) {
				if ($amount === "" || $amount === null) continue;

				$sqlCekSal = "SELECT * FROM tblsalcon WHERE idcontract = '".$idcontract."' AND saltype = '".$saltype."'";
				$cekSal = $this->MCrewscv->getDataQuery($sqlCekSal);

				if (!empty($cekSal)) {
					$updateSal = array(
						'amount' => $amount,
						'updusrdt' => $username . "#" . $date
					);
					$this->MCrewscv->updateData(array('idcontract' => $idcontract, 'saltype' => $saltype), $updateSal, 'tblsalcon');
				} else {
					$insertSal = array(
						'idcontract' => $idcontract,
						'saltype' => $saltype,
						'amount' => $amount,
						'addusrdt' => $username . "#" . $date
					);
					$this->MCrewscv->insData('tblsalcon', $insertSal);
				}
			}
		}

		echo json_encode(array(
			'success' => true,
			'message' => 'Data kapal & gaji berhasil disimpan',
			'vessel_name' => $vesselName,
			'company_name' => $companyName
		));
	}




}