<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Vaccine extends CI_Controller {

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

		$sql = "SELECT * FROM tblvaccine 
				WHERE deletests = '0' AND idperson = '".$idPerson."' ORDER BY vaccine_date DESC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$vaccineName = $val->vaccine_name;
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->id."');\" title=\"Edit Data\">Edit</button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->id."','".$idPerson."');\" title=\"Delete Data\">Delete</button>";

			if($val->vaccine_file != "")
			{
				$vaccineName = "<a href=\"".base_url('uploadFile')."/".$val->vaccine_file."\" target=\"_blank\">".$vaccineName."</a>";
			}

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$vaccineName."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->vaccine_date)."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->remark."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['yearNya'] = $dataContext->menuTahun('1970','');

		$this->load->view('frontend/vaccine',$dataOut);
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
			$dataIns['vaccine_name'] = $data['vaccineName'];
			$dataIns['vaccine_date'] = $data['vaccineDate'];
			$dataIns['remark'] = $data['remark'];
			
			if($idEdit == "")
			{
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$idEdit = $this->MCrewscv->insData("tblvaccine",$dataIns,"idIns");
			}else{
				$dataIns['editusrdt'] = $userInit."/".$dateNow;
				
				$whereNya = "id = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblvaccine");
			}

			if($data['cekFileUpload'] != "")
			{
				$dataIns = array();

				$fileUploadNya = "";
				$fileName = $_FILES["fileUpload"]["name"];
				$newFileName = "vaccineFile_".$idPerson."_".$idEdit;
				$fileUploadNya = $dataContext->uploadFile($_FILES["fileUpload"]['tmp_name'],$dir,$fileName,$newFileName);
				$dataIns['vaccine_file'] = $fileUploadNya;

				$whereNya = "id = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblvaccine");
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

		$sql = "SELECT * FROM tblvaccine WHERE deletests = '0' AND id = '".$id."' AND idperson = '".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$dataOut['idEdit'] = $rsl[0]->id;
			$dataOut['vaccineName'] = $rsl[0]->vaccine_name;
			$dataOut['vaccineDate'] = $rsl[0]->vaccine_date;
			$dataOut['remark'] = $rsl[0]->remark;

			if($rsl[0]->vaccine_file != "")
			{
				$btnFile = "<a class=\"btn btn-info btn-xs btn-block\" href=\"".base_url('uploadFile')."/".$rsl[0]->vaccine_file."\" target=\"_blank\" title=\"View File\">View File</a>";
				$btnFile .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete File\" onclick=\"delFile('".$id."','".$rsl[0]->vaccine_file."','".$idPerson."');\">Delete</button>";
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

		$dataDel['vaccine_file'] = "";

		$whereNya = "id = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tblvaccine");
		$dataContext->delFile($file,$dir);

		$status = "Success..!!";

		print json_encode($status);
	}

	function deleteData()
	{
		$status = "";
		$dataDel = array();
		
		$id = $_POST['id'];
		$idPerson = $_POST['idPerson'];			

		$dataDel['deletests'] = "1";

		$whereNya = "id = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tblvaccine");

		$status = "Success..!!";
		
		print json_encode($status);
	}


}
