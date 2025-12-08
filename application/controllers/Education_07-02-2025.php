<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Education extends CI_Controller {

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

		$sql = "SELECT * FROM tblscl 
				WHERE deletests = '0' AND idperson = '".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$sclName = $val->namescl;
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->idscl."');\" title=\"Edit Data\">Edit</button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->idscl."','".$idPerson."');\" title=\"Delete Data\">Delete</button>";

			if($val->scl_file != "")
			{
				$sclName = "<a href=\"".base_url('uploadFile')."/".$val->scl_file."\" target=\"_blank\">".$sclName."</a>";
			}

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->yearscl."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$sclName."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->crsfin."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['yearNya'] = $dataContext->menuTahun('1970','');

		$this->load->view('frontend/education',$dataOut);
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
			$dataIns['yearscl'] = $data['year'];
			$dataIns['namescl'] = $data['school'];
			$dataIns['crsfin'] = $data['course'];
			
			if($idEdit == "")
			{
				$dataIns['idscl'] = $dataContext->getNewId("idscl","tblscl","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblscl",$dataIns);
				$idEdit = $dataIns['idscl'];
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idscl = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblscl");
			}

			if($data['cekFileUpload'] != "")
			{
				$dataIns = array();

				$fileUploadNya = "";
				$fileName = $_FILES["fileUpload"]["name"];
				$newFileName = "sclFile_".$idPerson."_".$idEdit;
				$fileUploadNya = $dataContext->uploadFile($_FILES["fileUpload"]['tmp_name'],$dir,$fileName,$newFileName);
				$dataIns['scl_file'] = $fileUploadNya;

				$whereNya = "idscl = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblscl");
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

		$sql = "SELECT * FROM tblscl WHERE deletests = '0' AND idscl = '".$id."' AND idperson = '".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$dataOut['idEdit'] = $rsl[0]->idscl;
			$dataOut['year'] = $rsl[0]->yearscl;
			$dataOut['school'] = $rsl[0]->namescl;
			$dataOut['course'] = $rsl[0]->crsfin;

			if($rsl[0]->scl_file != "")
			{
				$btnFile = "<a class=\"btn btn-info btn-xs btn-block\" href=\"".base_url('uploadFile')."/".$rsl[0]->scl_file."\" target=\"_blank\" title=\"View File\">View File</a>";
				$btnFile .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete File\" onclick=\"delFile('".$id."','".$rsl[0]->scl_file."','".$idPerson."');\">Delete</button>";
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

		$dataDel['scl_file'] = "";

		$whereNya = "idscl = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tblscl");
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

		$whereNya = "idscl = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tblscl");

		$status = "Success..!!";
		
		print json_encode($status);
	}


}
