<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Others extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

	function getData($idPerson = "")
	{
		$dataOut = array();

		$dataOut['idPerson'] = $idPerson;
		$this->load->view('frontend/others',$dataOut);
	}

	function getDataOthers($typeNya = "",$idPerson = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;

		if($typeNya == "others1")
		{
			$sql = "SELECT * FROM tblothcer WHERE deletests = '0' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs btn-block\" title=\"Edit Data\" onclick=\"getDataEditOthers('".$val->idcert."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete Data\" onclick=\"delDataOthers('".$val->idcert."','".$val->idperson."')\">Del</button>";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->certname."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->certno."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->certrgtn."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->certissdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->certexpdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->certremarks."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;			

			$this->load->view('frontend/others_1',$dataOut);
		}
		else if($typeNya == "others2")
		{
			$sql = "SELECT * FROM tblphysical WHERE deletests = '0' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs btn-block\" title=\"Edit Data\" onclick=\"getDataEditOthers('".$val->idphy."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete Data\" onclick=\"delDataOthers('".$val->idphy."','".$val->idperson."')\">Del</button>";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->phyitem."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->phyissdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->phyexpdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->phyremarks."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;			

			$this->load->view('frontend/others_2',$dataOut);
		}
		else if($typeNya == "others3")
		{
			$yearOpr = "";
			$yearRank = "";

			$sql = "SELECT yearoperat,yearrank FROM mstpersonal WHERE deletests = '0' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if($rsl > 0)
			{
				$yearOpr = $rsl[0]->yearoperat;
				$yearRank = $rsl[0]->yearrank;
			}

			$dataOut['yearOpr'] = $yearOpr;
			$dataOut['yearRank'] = $yearRank;

			$this->load->view('frontend/others_3',$dataOut);
		}
	}

	function saveDataOther1()
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
			$dataIns['certname'] = $data['nameCert'];
			$dataIns['certno'] = $data['number'];
			$dataIns['certrgtn'] = $data['regulation'];
			$dataIns['certissdt'] = $data['dateIssue'];
			$dataIns['certexpdt'] = $data['dateExp'];
			$dataIns['certremarks'] = $data['remark'];
			
			if($idEdit == "")
			{
				$dataIns['idcert'] = $dataContext->getNewId("idcert","tblothcer","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblothcer",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idcert = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblothcer");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function saveDataOther2()
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
			$dataIns['phyitem'] = $data['items'];
			$dataIns['phyissdt'] = $data['dateIssue'];
			$dataIns['phyexpdt'] = $data['dateExp'];
			$dataIns['phyremarks'] = $data['remark'];
			
			if($idEdit == "")
			{
				$dataIns['idphy'] = $dataContext->getNewId("idphy","tblphysical","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblphysical",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idphy = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblphysical");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function saveDataOther3()
	{
		$dataContext = new DataContext();
		$data = $_POST;
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dataIns = array();		
		$dateNow = date("Ymd/h:i:s");
		$stData = "";
		$idPerson = $data['idPerson'];

		try {

			$dataIns['idperson'] = $idPerson;
			$dataIns['yearoperat'] = $data['yearOperator'];
			$dataIns['yearrank'] = $data['yearRank'];
			
			$dataIns['updusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataIns,"mstpersonal");
			
			$stData = "[".date("h:i:s")."] Crew Matrix successfully saved..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function getDataEdit()
	{
		$id = $_POST['id'];
		$type = $_POST['type'];
		$dataOut = array();
		$fullName = "";

		if($type == "editOther1")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblothcer WHERE deletests = '0' AND idcert = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idcert'] = $rsl[0]->idcert;
				$dataOut['certname'] = $rsl[0]->certname;
				$dataOut['certno'] = $rsl[0]->certno;
				$dataOut['certrgtn'] = $rsl[0]->certrgtn;
				$dataOut['certissdt'] = $rsl[0]->certissdt;
				$dataOut['certexpdt'] = $rsl[0]->certexpdt;
				$dataOut['certremarks'] = $rsl[0]->certremarks;
			}
		}
		else if($type == "editOther2")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblphysical WHERE deletests = '0' AND idphy = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idphy'] = $rsl[0]->idphy;
				$dataOut['phyitem'] = $rsl[0]->phyitem;
				$dataOut['phyissdt'] = $rsl[0]->phyissdt;
				$dataOut['phyexpdt'] = $rsl[0]->phyexpdt;
				$dataOut['phyremarks'] = $rsl[0]->phyremarks;
			}
		}

		print json_encode($dataOut);
	}

	function deleteData()
	{
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");
		$type = $_POST['type'];
		$status = "";
		$dataDel = array();

		if($type == "delOther1")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idcert = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblothcer");

			$status = "Success..!!";
		}
		else if($type == "delOther2")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idphy = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblphysical");

			$status = "Success..!!";
		}

		print json_encode($status);
	}


}
