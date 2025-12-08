<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class General extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

	function getData($idPerson = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$ref1 = "";
		$ref2 = "";

		$sql = "SELECT A.*,B.NmNegara
					FROM tblrefcmp A
					LEFT JOIN tblnegara B ON B.KdNegara = A.refctryid AND B.deletests = '0'
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			if($val->refAktifsts == "1")
			{
				$ref1 = $val->idref;
			}

			if($val->refAktifsts == "2")
			{
				$ref2 = $val->idref;
			}

			$btnAct = "<button class=\"btn btn-success btn-xs btn-block\" onclick=\"getDataEdit('".$val->idref."');\" title=\"Edit Data\">Edit</button>";
			$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delData('".$val->idref."','".$idPerson."');\" title=\"Delete Data\">Del</button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->refcmp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->refpic."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->refaddress."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->NmNegara."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->reftelp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$tempOpt = $dataContext->getMenuGeneralByOption($idPerson,$ref1,$ref2);

		$dataOut['trNya'] = $trNya;
		$dataOut['optCountry'] = $dataContext->getCountryByOption("","kode");
		$dataOut['optRef1'] = $tempOpt['ref1'];
		$dataOut['optRef2'] = $tempOpt['ref2'];

		$this->load->view('frontend/general',$dataOut);
	}

	function saveData()
	{
		$dataContext = new DataContext();
		$data = $_POST;
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dataIns = array();		
		$dateNow = date("Ymd/h:i:s");
		$stData = "";
		$idEdit = $data['idEdit'];
		$idPerson = $data['idPerson'];

		try {

			$dataIns['idperson'] = $idPerson;
			$dataIns['refcmp'] = $data['txtCompanyGen'];
			$dataIns['refpic'] = $data['txtPersonContactGen'];
			$dataIns['refaddress'] = $data['txtAddressGen'];
			$dataIns['refctryid'] = $data['slcCountryGen'];
			$dataIns['reftelp'] = $data['txtPhoneGen'];
			
			if($idEdit == "")
			{
				$dataIns['idref'] = $dataContext->getNewId("idref","tblrefcmp","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblrefcmp",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idref = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblrefcmp");
			}
			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function saveDataRef()
	{
		$dataContext = new DataContext();
		$data = $_POST;
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dataIns = array();
		$dateNow = date("Ymd/h:i:s");
		$stData = "";
		$idPerson = $data['idPerson'];
		$idRef1 = $data['ref1'];
		$idRef2 = $data['ref2'];

		try {

			$dataIns['refAktifsts'] = "0";
			$whereNya = "idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataIns,"tblrefcmp");

			if($idRef1 != "")
			{
				$dataIns = array();

				$dataIns['refAktifsts'] = "1";
				$whereNya = "idref = '".$idRef1."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblrefcmp");
			}

			if($idRef2 != "")
			{
				$dataIns = array();

				$dataIns['refAktifsts'] = "2";
				$whereNya = "idref = '".$idRef2."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblrefcmp");
			}			
			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function getDataEdit()
	{		
		$dataOut = array();

		$id = $_POST['id'];
		$idPerson = $_POST['idPerson'];

		$sql = "SELECT * FROM tblrefcmp WHERE deletests = '0' AND idref = '".$id."' AND idperson = '".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$dataOut['idEdit'] = $rsl[0]->idref;
			$dataOut['company'] = $rsl[0]->refcmp;
			$dataOut['pic'] = $rsl[0]->refpic;
			$dataOut['address'] = $rsl[0]->refaddress;
			$dataOut['country'] = $rsl[0]->refctryid;
			$dataOut['phone'] = $rsl[0]->reftelp;
		}

		print json_encode($dataOut);
	}

	function deleteData()
	{
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");
		$status = "";
		$dataDel = array();
		
		$id = $_POST['id'];
		$idPerson = $_POST['idPerson'];			

		$dataDel['deletests'] = "1";
		$dataDel['delusrdt'] = $userInit."/".$dateNow;

		$whereNya = "idref = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tblrefcmp");

		$status = "Success..!!";
		
		print json_encode($status);
	}


}
