<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Compliance extends CI_Controller {

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
		$this->load->view('frontend/complianceCertificate',$dataOut);
	}

	function getDataReg($typeNya = "",$idPerson = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;

		$dataOut['optCountry'] = $dataContext->getCountryByOption("","kode");
		$dataOut['optRank'] = $dataContext->getRankByOption("","kode");

		if($typeNya == "A")
		{
			$sql = "SELECT A.*,B.NmNegara
					FROM tblreg1 A
					LEFT JOIN tblnegara B ON B.KdNegara = A.rg1issctryid AND B.deletests = '0'
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";

			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs\" title=\"Edit Data\" onclick=\"getDataEditRegACC('".$val->idrg1."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs\" title=\"Delete Data\" onclick=\"delDataRegACC('".$val->idrg1."','".$val->idperson."')\">Del</button>";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg1doc."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".strtoupper($val->NmNegara)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg1docno."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg1issdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg1expdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg1issplc."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg1issby."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;			

			$this->load->view('frontend/compliance_regA',$dataOut);
		}
		else if($typeNya == "B")
		{
			$sql = "SELECT A.*,B.NmNegara
					FROM tblreg2 A
					LEFT JOIN tblnegara B ON B.KdNegara = A.rg2issctryid AND B.deletests = '0'
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";

			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs\" title=\"Edit Data\" onclick=\"getDataEditRegACC('".$val->idrg2."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs\" title=\"Delete Data\" onclick=\"delDataRegACC('".$val->idrg2."','".$val->idperson."')\">Del</button>";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg2doc."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".strtoupper($val->NmNegara)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg2docno."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg2issdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg2expdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg2issplc."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg2issby."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;

			$this->load->view('frontend/compliance_regB',$dataOut);
		}
		else if($typeNya == "C")
		{
			$sql = "SELECT A.*,B.NmNegara
					FROM tblreg3 A
					LEFT JOIN tblnegara B ON B.KdNegara = A.rg3issctryid AND B.deletests = '0'
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";

			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs\" title=\"Edit Data\" onclick=\"getDataEditRegACC('".$val->idrg3."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs\" title=\"Delete Data\" onclick=\"delDataRegACC('".$val->idrg3."','".$val->idperson."')\">Del</button>";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg3doc."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".strtoupper($val->NmNegara)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg3docno."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg3issdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg3expdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg3issplc."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg3issby."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;

			$this->load->view('frontend/compliance_regC',$dataOut);
		}
		else if($typeNya == "D")
		{
			$sql = "SELECT A.*,B.NmNegara,C.nmrank
					FROM tblreg4 A
					LEFT JOIN tblnegara B ON B.KdNegara = A.rg4issctryid AND B.deletests = '0'
					LEFT JOIN mstrank C On C.kdrank = A.kdrank
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";

			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs btn-block\" title=\"Edit Data\" onclick=\"getDataEditRegACC('".$val->idrg4."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete Data\" onclick=\"delDataRegACC('".$val->idrg4."','".$val->idperson."')\">Del</button>";

				$descCertCC = $val->rg4doc;

				if($val->rg4license != "")
				{
					$descCertCC .= " / ".$val->rg4license;
				}

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$descCertCC."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->nmrank."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".strtoupper($val->NmNegara)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg4docno."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg4issdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg4expdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg4issplc."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg4issby."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;

			$this->load->view('frontend/compliance_regD',$dataOut);
		}
		else if($typeNya == "E")
		{
			$sql = "SELECT A.*,B.NmNegara
					FROM tblreg5 A
					LEFT JOIN tblnegara B ON B.KdNegara = A.rg5issctryid AND B.deletests = '0'
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";


			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs\" title=\"Edit Data\" onclick=\"getDataEditRegACC('".$val->idrg5."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs\" title=\"Delete Data\" onclick=\"delDataRegACC('".$val->idrg5."','".$val->idperson."')\">Del</button>";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg5doc."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".strtoupper($val->NmNegara)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg5docno."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg5issdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg5expdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg5issplc."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg5issby."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;

			$this->load->view('frontend/compliance_regE',$dataOut);
		}
		else if($typeNya == "F")
		{
			$sql = "SELECT A.*,B.NmNegara,C.nmrank
					FROM tblreg6 A
					LEFT JOIN tblnegara B ON B.KdNegara = A.rg6issctryid AND B.deletests = '0'
					LEFT JOIN mstrank C On C.kdrank = A.kdrank
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";

			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs btn-block\" title=\"Edit Data\" onclick=\"getDataEditRegACC('".$val->idrg6."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete Data\" onclick=\"delDataRegACC('".$val->idrg6."','".$val->idperson."')\">Del</button>";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg6doc."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->nmrank."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".strtoupper($val->NmNegara)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg6docno."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg6issdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg6expdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg6issplc."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg6issby."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;

			$this->load->view('frontend/compliance_regF',$dataOut);
		}
		else if($typeNya == "G")
		{
			$sql = "SELECT A.*,B.NmNegara,C.nmrank
					FROM tblreg7 A
					LEFT JOIN tblnegara B ON B.KdNegara = A.rg7issctryid AND B.deletests = '0'
					LEFT JOIN mstrank C On C.kdrank = A.kdrank
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' 
					ORDER BY A.rg7doc ASC";

			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs btn-block\" title=\"Edit Data\" onclick=\"getDataEditRegACC('".$val->idrg7."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete Data\" onclick=\"delDataRegACC('".$val->idrg7."','".$val->idperson."')\">Del</button>";

				$descCert = $val->rg7doc."&nbsp;-&nbsp".$val->rg7lvltipe;

				if($val->rg7para != "")
				{
					$descCert .= "&nbsp;(".$val->rg7para.")";
				}

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$descCert."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->rg7lvl."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->nmrank."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".strtoupper($val->NmNegara)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg7docno."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg7issdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg7expdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg7issplc."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg7issby."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;

			$this->load->view('frontend/compliance_regG',$dataOut);
		}
		else if($typeNya == "H")
		{
			$sql = "SELECT A.*,B.NmNegara
					FROM tblreg8 A
					LEFT JOIN tblnegara B ON B.KdNegara = A.rg8issctryid AND B.deletests = '0'
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";

			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs btn-block\" title=\"Edit Data\" onclick=\"getDataEditRegACC('".$val->idrg8."');\">Edit</button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete Data\" onclick=\"delDataRegACC('".$val->idrg8."','".$val->idperson."')\">Del</button>";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg8doc."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->rg8type."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".strtoupper($val->NmNegara)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg8docno."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg8issdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->rg8expdt)."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg8issplc."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->rg8issby."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}

			$dataOut['trNya'] = $trNya;

			$this->load->view('frontend/compliance_regH',$dataOut);
		}
	}

	function saveDataRegA()
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
			$dataIns['rg1doc'] = $data['descCertCC'];
			$dataIns['rg1issctryid'] = $data['countryIssueCC'];
			$dataIns['rg1docno'] = $data['numberCC'];
			$dataIns['rg1issdt'] = $data['dateIssueCC'];
			$dataIns['rg1expdt'] = $data['dateExpCC'];
			$dataIns['rg1issplc'] = $data['placeCC'];
			$dataIns['rg1issby'] = $data['issueAuthCC'];
			
			if($idEdit == "")
			{
				$dataIns['idrg1'] = $dataContext->getNewId("idrg1","tblreg1","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblreg1",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idrg1 = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblreg1");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function saveDataRegB()
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
			$dataIns['rg2doc'] = $data['descCertCC'];
			$dataIns['rg2issctryid'] = $data['countryIssueCC'];
			$dataIns['rg2docno'] = $data['numberCC'];
			$dataIns['rg2issdt'] = $data['dateIssueCC'];
			$dataIns['rg2expdt'] = $data['dateExpCC'];
			$dataIns['rg2issplc'] = $data['placeCC'];
			$dataIns['rg2issby'] = $data['issueAuthCC'];
			
			if($idEdit == "")
			{
				$dataIns['idrg2'] = $dataContext->getNewId("idrg2","tblreg2","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblreg2",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idrg2 = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblreg2");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print json_encode($stData);
	}

	function saveDataRegC()
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
			$dataIns['rg3doc'] = $data['descCertCC'];
			$dataIns['rg3issctryid'] = $data['countryIssueCC'];
			$dataIns['rg3docno'] = $data['numberCC'];
			$dataIns['rg3issdt'] = $data['dateIssueCC'];
			$dataIns['rg3expdt'] = $data['dateExpCC'];
			$dataIns['rg3issplc'] = $data['placeCC'];
			$dataIns['rg3issby'] = $data['issueAuthCC'];
			
			if($idEdit == "")
			{
				$dataIns['idrg3'] = $dataContext->getNewId("idrg3","tblreg3","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblreg3",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idrg3 = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblreg3");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print json_encode($stData);
	}

	function saveDataRegD()
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
			$dataIns['rg4license'] = $data['kdLicense'];
			$dataIns['kdrank'] = $data['kdRank'];
			$dataIns['rg4doc'] = $data['descCertCC'];
			$dataIns['rg4issctryid'] = $data['countryIssueCC'];
			$dataIns['rg4docno'] = $data['numberCC'];
			$dataIns['rg4issdt'] = $data['dateIssueCC'];
			$dataIns['rg4expdt'] = $data['dateExpCC'];
			$dataIns['rg4issplc'] = $data['placeCC'];
			$dataIns['rg4issby'] = $data['issueAuthCC'];
			
			if($idEdit == "")
			{
				$dataIns['idrg4'] = $dataContext->getNewId("idrg4","tblreg4","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblreg4",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idrg4 = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblreg4");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print json_encode($stData);
	}

	function saveDataRegE()
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
			$dataIns['rg5doc'] = $data['descCertCC'];
			$dataIns['rg5issctryid'] = $data['countryIssueCC'];
			$dataIns['rg5docno'] = $data['numberCC'];
			$dataIns['rg5issdt'] = $data['dateIssueCC'];
			$dataIns['rg5expdt'] = $data['dateExpCC'];
			$dataIns['rg5issplc'] = $data['placeCC'];
			$dataIns['rg5issby'] = $data['issueAuthCC'];
			
			if($idEdit == "")
			{
				$dataIns['idrg5'] = $dataContext->getNewId("idrg5","tblreg5","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblreg5",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idrg5 = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblreg5");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print json_encode($stData);
	}

	function saveDataRegF()
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
			$dataIns['kdrank'] = $data['kdRank'];
			$dataIns['rg6doc'] = $data['descCertCC'];
			$dataIns['rg6issctryid'] = $data['countryIssueCC'];
			$dataIns['rg6docno'] = $data['numberCC'];
			$dataIns['rg6issdt'] = $data['dateIssueCC'];
			$dataIns['rg6expdt'] = $data['dateExpCC'];
			$dataIns['rg6issplc'] = $data['placeCC'];
			$dataIns['rg6issby'] = $data['issueAuthCC'];
			
			if($idEdit == "")
			{
				$dataIns['idrg6'] = $dataContext->getNewId("idrg6","tblreg6","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblreg6",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idrg6 = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblreg6");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print json_encode($stData);
	}

	function saveDataRegG()
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
			$dataIns['rg7lvltipe'] = $data['typeCC'];
			$dataIns['rg7doc'] = $data['docTypeCC'];
			$dataIns['kdrank'] = $data['kdRank'];
			$dataIns['rg7para'] = $data['paraCC'];
			$dataIns['rg7lvl'] = $data['levelCC'];
			$dataIns['rg7issctryid'] = $data['countryIssueCC'];
			$dataIns['rg7docno'] = $data['numberCC'];
			$dataIns['rg7issdt'] = $data['dateIssueCC'];
			$dataIns['rg7expdt'] = $data['dateExpCC'];
			$dataIns['rg7issplc'] = $data['placeCC'];
			$dataIns['rg7issby'] = $data['issueAuthCC'];
			
			if($idEdit == "")
			{
				$dataIns['idrg7'] = $dataContext->getNewId("idrg7","tblreg7","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblreg7",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idrg7 = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblreg7");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print json_encode($stData);
	}

	function saveDataRegH()
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
			$dataIns['rg8doc'] = $data['descCertCC'];
			$dataIns['rg8type'] = $data['typeCC'];
			$dataIns['rg8issctryid'] = $data['countryIssueCC'];
			$dataIns['rg8docno'] = $data['numberCC'];
			$dataIns['rg8issdt'] = $data['dateIssueCC'];
			$dataIns['rg8expdt'] = $data['dateExpCC'];
			$dataIns['rg8issplc'] = $data['placeCC'];
			$dataIns['rg8issby'] = $data['issueAuthCC'];
			
			if($idEdit == "")
			{
				$dataIns['idrg8'] = $dataContext->getNewId("idrg8","tblreg8","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblreg8",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idrg8 = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblreg8");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print json_encode($stData);
	}

	function getDataEdit()
	{
		$id = $_POST['id'];
		$type = $_POST['type'];
		$dataOut = array();
		$fullName = "";

		if($type == "editRegA")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblreg1 WHERE deletests = '0' AND idrg1 = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idrg1'] = $rsl[0]->idrg1;
				$dataOut['rg1doc'] = $rsl[0]->rg1doc;
				$dataOut['rg1issctryid'] = $rsl[0]->rg1issctryid;
				$dataOut['rg1docno'] = $rsl[0]->rg1docno;
				$dataOut['rg1issdt'] = $rsl[0]->rg1issdt;
				$dataOut['rg1expdt'] = $rsl[0]->rg1expdt;
				$dataOut['rg1issplc'] = $rsl[0]->rg1issplc;
				$dataOut['rg1issby'] = $rsl[0]->rg1issby;
			}
		}
		else if($type == "editRegB")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblreg2 WHERE deletests = '0' AND idrg2 = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idrg1'] = $rsl[0]->idrg2;
				$dataOut['rg1doc'] = $rsl[0]->rg2doc;
				$dataOut['rg1issctryid'] = $rsl[0]->rg2issctryid;
				$dataOut['rg1docno'] = $rsl[0]->rg2docno;
				$dataOut['rg1issdt'] = $rsl[0]->rg2issdt;
				$dataOut['rg1expdt'] = $rsl[0]->rg2expdt;
				$dataOut['rg1issplc'] = $rsl[0]->rg2issplc;
				$dataOut['rg1issby'] = $rsl[0]->rg2issby;
			}
		}
		else if($type == "editRegC")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblreg3 WHERE deletests = '0' AND idrg3 = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idrg1'] = $rsl[0]->idrg3;
				$dataOut['rg1doc'] = $rsl[0]->rg3doc;
				$dataOut['rg1issctryid'] = $rsl[0]->rg3issctryid;
				$dataOut['rg1docno'] = $rsl[0]->rg3docno;
				$dataOut['rg1issdt'] = $rsl[0]->rg3issdt;
				$dataOut['rg1expdt'] = $rsl[0]->rg3expdt;
				$dataOut['rg1issplc'] = $rsl[0]->rg3issplc;
				$dataOut['rg1issby'] = $rsl[0]->rg3issby;
			}
		}
		else if($type == "editRegD")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblreg4 WHERE deletests = '0' AND idrg4 = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idrg1'] = $rsl[0]->idrg4;
				$dataOut['kdrank'] = $rsl[0]->kdrank;
				$dataOut['rg1license'] = $rsl[0]->rg4license;
				$dataOut['rg1doc'] = $rsl[0]->rg4doc;
				$dataOut['rg1issctryid'] = $rsl[0]->rg4issctryid;
				$dataOut['rg1docno'] = $rsl[0]->rg4docno;
				$dataOut['rg1issdt'] = $rsl[0]->rg4issdt;
				$dataOut['rg1expdt'] = $rsl[0]->rg4expdt;
				$dataOut['rg1issplc'] = $rsl[0]->rg4issplc;
				$dataOut['rg1issby'] = $rsl[0]->rg4issby;
			}
		}
		else if($type == "editRegE")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblreg5 WHERE deletests = '0' AND idrg5 = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idrg1'] = $rsl[0]->idrg5;
				$dataOut['rg1doc'] = $rsl[0]->rg5doc;
				$dataOut['rg1issctryid'] = $rsl[0]->rg5issctryid;
				$dataOut['rg1docno'] = $rsl[0]->rg5docno;
				$dataOut['rg1issdt'] = $rsl[0]->rg5issdt;
				$dataOut['rg1expdt'] = $rsl[0]->rg5expdt;
				$dataOut['rg1issplc'] = $rsl[0]->rg5issplc;
				$dataOut['rg1issby'] = $rsl[0]->rg5issby;
			}
		}
		else if($type == "editRegF")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblreg6 WHERE deletests = '0' AND idrg6 = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idrg1'] = $rsl[0]->idrg6;
				$dataOut['kdrank'] = $rsl[0]->kdrank;
				$dataOut['rg1doc'] = $rsl[0]->rg6doc;
				$dataOut['rg1issctryid'] = $rsl[0]->rg6issctryid;
				$dataOut['rg1docno'] = $rsl[0]->rg6docno;
				$dataOut['rg1issdt'] = $rsl[0]->rg6issdt;
				$dataOut['rg1expdt'] = $rsl[0]->rg6expdt;
				$dataOut['rg1issplc'] = $rsl[0]->rg6issplc;
				$dataOut['rg1issby'] = $rsl[0]->rg6issby;
			}
		}
		else if($type == "editRegG")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblreg7 WHERE deletests = '0' AND idrg7 = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idrg1'] = $rsl[0]->idrg7;
				$dataOut['rg1lvltipe'] = $rsl[0]->rg7lvltipe;
				$dataOut['rg1doc'] = $rsl[0]->rg7doc;
				$dataOut['kdrank'] = $rsl[0]->kdrank;
				$dataOut['rg1para'] = $rsl[0]->rg7para;
				$dataOut['rg1lvl'] = $rsl[0]->rg7lvl;				
				$dataOut['rg1issctryid'] = $rsl[0]->rg7issctryid;
				$dataOut['rg1docno'] = $rsl[0]->rg7docno;
				$dataOut['rg1issdt'] = $rsl[0]->rg7issdt;
				$dataOut['rg1expdt'] = $rsl[0]->rg7expdt;
				$dataOut['rg1issplc'] = $rsl[0]->rg7issplc;
				$dataOut['rg1issby'] = $rsl[0]->rg7issby;
			}
		}
		else if($type == "editRegH")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblreg8 WHERE deletests = '0' AND idrg8 = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idrg1'] = $rsl[0]->idrg8;
				$dataOut['type'] = $rsl[0]->rg8type;
				$dataOut['rg1doc'] = $rsl[0]->rg8doc;
				$dataOut['rg1issctryid'] = $rsl[0]->rg8issctryid;
				$dataOut['rg1docno'] = $rsl[0]->rg8docno;
				$dataOut['rg1issdt'] = $rsl[0]->rg8issdt;
				$dataOut['rg1expdt'] = $rsl[0]->rg8expdt;
				$dataOut['rg1issplc'] = $rsl[0]->rg8issplc;
				$dataOut['rg1issby'] = $rsl[0]->rg8issby;
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

		if($type == "delRegA")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idrg1 = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblreg1");

			$status = "Success..!!";
		}
		else if($type == "delRegB")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idrg2 = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblreg2");

			$status = "Success..!!";
		}
		else if($type == "delRegC")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idrg3 = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblreg3");

			$status = "Success..!!";
		}
		else if($type == "delRegD")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idrg4 = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblreg4");

			$status = "Success..!!";
		}
		else if($type == "delRegE")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idrg5 = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblreg5");

			$status = "Success..!!";
		}
		else if($type == "delRegF")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idrg6 = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblreg6");

			$status = "Success..!!";
		}
		else if($type == "delRegG")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idrg7 = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblreg7");

			$status = "Success..!!";
		}
		else if($type == "delRegH")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idrg8 = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblreg8");

			$status = "Success..!!";
		}

		print json_encode($status);
	}


}
