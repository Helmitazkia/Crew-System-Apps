<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Language extends CI_Controller {

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

		$sql = "SELECT idlang, language, CASE WHEN degree=0 THEN '' WHEN degree!=0 THEN degree END AS degree, grade, lang_file
				FROM tbllang 
				WHERE deletests = '0' AND idperson = '".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$langName = $val->language;

			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->idlang."');\" title=\"Edit Data\">Edit</button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->idlang."','".$idPerson."');\" title=\"Delete Data\">Delete</button>";

			if($val->lang_file != "")
			{
				$langName = "<a href=\"".base_url('uploadFile')."/".$val->lang_file."\" target=\"_blank\">".$langName."</a>";
			}

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$langName."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->degree."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->grade."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		$this->load->view('frontend/language',$dataOut);
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
		$dir = "./uploadFile";

		try {

			$dataIns['idperson'] = $idPerson;
			$dataIns['language'] = $data['language'];
			$dataIns['degree'] = $data['degree'];
			$dataIns['grade'] = $data['grade'];
			
			if($idEdit == "")
			{
				$dataIns['idlang'] = $dataContext->getNewId("idlang","tbllang","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tbllang",$dataIns);
				$idEdit = $dataIns['idlang'];
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idlang = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tbllang");
			}

			if($data['cekFileUpload'] != "")
			{
				$dataIns = array();

				$fileUploadNya = "";
				$fileName = $_FILES["fileUpload"]["name"];
				$newFileName = "langFile_".$idPerson."_".$idEdit;
				$fileUploadNya = $dataContext->uploadFile($_FILES["fileUpload"]['tmp_name'],$dir,$fileName,$newFileName);
				$dataIns['lang_file'] = $fileUploadNya;

				$whereNya = "idlang = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tbllang");
			}
			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print $stData;
	}

	function getDataEdit()
	{
		$dataOut = array();

		$id = $_POST['id'];
		$idPerson = $_POST['idPerson'];
		$btnFile = "";

		$sql = "SELECT * FROM tbllang WHERE deletests = '0' AND idlang = '".$id."' AND idperson = '".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$dataOut['idEdit'] = $rsl[0]->idlang;
			$dataOut['language'] = $rsl[0]->language;
			$dataOut['degree'] = $rsl[0]->degree;
			$dataOut['grade'] = $rsl[0]->grade;

			if($rsl[0]->lang_file != "")
			{
				$btnFile = "<a class=\"btn btn-info btn-xs btn-block\" href=\"".base_url('uploadFile')."/".$rsl[0]->lang_file."\" target=\"_blank\" title=\"View File\">View File</a>";
				$btnFile .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete File\" onclick=\"delFile('".$id."','".$rsl[0]->lang_file."','".$idPerson."');\">Delete</button>";
			}

			$dataOut['btnFile'] = $btnFile;
		}

		print json_encode($dataOut);
	}

	function deleteFile()
	{
		$dataContext = new DataContext();
		$dir = "./uploadFile";
		$id = $_POST['id'];
		$file = $_POST['file'];
		$idPerson = $_POST['idPerson'];
		$dataDel = array();

		$dataDel['lang_file'] = "";

		$whereNya = "idlang = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tbllang");
		$dataContext->delFile($file,$dir);

		$status = "Success..!!";

		print json_encode($status);
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

		$whereNya = "idlang = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tbllang");

		$status = "Success..!!";
		
		print json_encode($status);
	}


}
