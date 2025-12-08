<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

	function index()
	{
		$dataOut = array();

		$this->load->view('frontend/viewMaster',$dataOut);
	}

	function getDataCertificate($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE deletests = '0' AND certname != '' ";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearchCert'];

			$whereNya .= " AND certname LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM mstcert ".$whereNya." ORDER BY certgroup ASC,certname ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->kdcert."','certificate');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->kdcert."','certificate');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">(".$val->certgroup.") ".$val->certname."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->definition."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterCertificate',$dataOut);
		}
	}

	function getDataCity($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE Deletests = '0' AND NmKota != '' ";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			$whereNya .= " AND NmKota LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM tblkota ".$whereNya." ORDER BY NmKota ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->KdKota."','city');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->KdKota."','city');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->NmKota."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterCity',$dataOut);
		}
	}

	function getDataCompany($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE Deletests = '0' AND nmcmp != '' ";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			$whereNya .= " AND nmcmp LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM mstcmprec ".$whereNya." ORDER BY nmcmp ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->kdcmp."','company');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->kdcmp."','company');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->nmcmp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->desccmp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->cvtype."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterCompany',$dataOut);
		}
	}

	function getDataCountry($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE Deletests = '0' AND NmNegara != '' ";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			$whereNya .= " AND NmNegara LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM tblnegara ".$whereNya." ORDER BY NmNegara ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->KdNegara."','country');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->KdNegara."','country');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->NmNegara."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterCountry',$dataOut);
		}
	}

	function getDataRank($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE Deletests = '0' AND nmrank != '' ";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			if($txtSearch != "")
			{
				$whereNya .= " AND nmrank LIKE '%".$txtSearch."%' ";
			}
		}

		$sql = "SELECT * FROM mstrank ".$whereNya." ORDER BY urutan ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$iconUp = "";
			$iconDown = "";
			$urutan = $val->urutan;

			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->kdrank."','rank');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->kdrank."','rank');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			if($no > 1)
			{
				$iconUp = "<button type=\"button\" class=\"btn btn-success btn-xs btn-block\" onclick=\"btnNavUrut('".$val->kdrank."','up','".$urutan."');\" title=\"Up\"><i class=\"fa fa-sort-asc\"></i></button>";
			}
			
			if($no < count($rsl))
			{
				$iconDown = "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"btnNavUrut('".$val->kdrank."','down','".$urutan."');\" title=\"Down\"><i class=\"fa fa-sort-desc\"></i></button>";
			}

			if(str_replace(' ', '',  $val->nmrank) == "-")
			{
				$btnAct = "";
			}
			

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$iconUp.$iconDown."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->nmrank."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->descrank."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->cadangan."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterRank',$dataOut);
		}
	}

	function getDataVessel($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE deletests = '0' AND nmvsl != '' ";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			$whereNya .= " AND nmvsl LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM mstvessel ".$whereNya." ORDER BY nmvsl ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->kdvsl."','vessel');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->kdvsl."','vessel');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->nmvsl."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->descvsl."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->nmcmp."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['optCompany'] = $dataContext->getCompanyByOption("",'kode');

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterVessel',$dataOut);
		}
	}

	function getDataVesselType($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE Deletests = '0' AND NmType != '' ";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			$whereNya .= " AND NmType LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM tbltype ".$whereNya." ORDER BY NmType ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->KdType."','vesselType');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->KdType."','vesselType');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->NmType."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->DefType."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterVesselType',$dataOut);
		}
	}

	function saveDataCertificate()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];

		try {

			$dataIns['certgroup'] = $data['group'];
			$dataIns['certname'] = $data['certName'];
			$dataIns['dispname'] = $data['certDisplay'];
			$dataIns['definition'] = $data['definisi'];
			
			if($idEdit == "")
			{
				$idEdit = $this->MCrewscv->insData("mstcert",$dataIns);
			}else{				
				$whereNya = "kdcert = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"mstcert");
			}			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print $stData;
	}

	function saveDataCity()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {

			$dataIns['NmKota'] =  strtoupper($data['txtCity']);
			
			if($idEdit == "")
			{
				$dataIns['AddUsrDt'] = $userDateTimeNow;

				$this->MCrewscv->insData("tblkota",$dataIns);
			}else{
				$dataIns['UpdUsrDt'] = $userDateTimeNow;

				$whereNya = "KdKota = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblkota");
			}			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print $stData;
	}

	function saveDataCompany()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {

			$dataIns['nmcmp'] =  $data['txtCompanyName'];
			$dataIns['desccmp'] =  $data['txtDefinitionCom'];
			$dataIns['cvtype'] =  $data['slcReportType'];
			
			if($idEdit == "")
			{
				$dataIns['AddUsrDt'] = $userDateTimeNow;

				$this->MCrewscv->insData("mstcmprec",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userDateTimeNow;

				$whereNya = "kdcmp = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"mstcmprec");
			}			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print $stData;
	}

	function saveDataCountry()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {

			$dataIns['NmNegara'] =  strtoupper($data['txtCountry']);
			
			if($idEdit == "")
			{
				$dataIns['AddUsrDt'] = $userDateTimeNow;

				$this->MCrewscv->insData("tblnegara",$dataIns);
			}else{
				$dataIns['UpdUsrDt'] = $userDateTimeNow;

				$whereNya = "KdNegara = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblnegara");
			}			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print $stData;
	}

	function saveDataRank()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {

			$dataIns['nmrank'] =  $data['txtRankName'];
			$dataIns['descrank'] =  $data['txtDefinition'];
			$dataIns['urutan'] =  $data['txtNumber'];
			$dataIns['cadangan'] = $data['txtCadangan'];
			
			if($idEdit == "")
			{
				$dataIns['addusrdt'] = $userDateTimeNow;

				$this->MCrewscv->insData("mstrank",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userDateTimeNow;

				$whereNya = "kdrank = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"mstrank");
			}			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print $stData;
	}

	function saveDataVessel()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {

			$dataIns['kdcmp'] =  $data['slcCompany'];
			$dataIns['nmcmp'] =  $data['slcCompanyName'];
			$dataIns['nmvsl'] =  $data['txtVesselName'];
			$dataIns['descvsl'] =  $data['txtDefinition'];
			
			if($idEdit == "")
			{
				$dataIns['addusrdt'] = $userDateTimeNow;

				$this->MCrewscv->insData("mstvessel",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userDateTimeNow;

				$whereNya = "kdvsl = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"mstvessel");
			}			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print $stData;
	}

	function saveDataVesselType()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {

			$dataIns['NmType'] =  $data['txtVesselType'];
			$dataIns['DefType'] =  $data['txtDefinition'];
			
			if($idEdit == "")
			{
				$dataIns['AddUsrDt'] = $userDateTimeNow;

				$this->MCrewscv->insData("tbltype",$dataIns);
			}else{
				$dataIns['UpdUsrDt'] = $userDateTimeNow;

				$whereNya = "KdType = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tbltype");
			}			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print $stData;
	}

	function getDataEdit()
	{
		$dataOut = array();

		$idEdit = $_POST['idEdit'];
		$type = $_POST['type'];

		if($type == "certificate")
		{
			$sql = "SELECT * FROM mstcert WHERE kdcert = '".$idEdit."' ";
			$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		}
		else if($type == "city")
		{
			$sql = "SELECT * FROM tblkota WHERE KdKota = '".$idEdit."' ";
			$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		}
		else if($type == "company")
		{
			$sql = "SELECT * FROM mstcmprec WHERE kdcmp = '".$idEdit."' ";
			$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		}
		else if($type == "country")
		{
			$sql = "SELECT * FROM tblnegara WHERE KdNegara = '".$idEdit."' ";
			$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		}
		else if($type == "rank")
		{
			$sql = "SELECT * FROM mstrank WHERE kdrank = '".$idEdit."' ";
			$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		}
		else if($type == "vessel")
		{
			$sql = "SELECT * FROM mstvessel WHERE kdvsl = '".$idEdit."' ";
			$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		}
		else if($type == "vesselType")
		{
			$sql = "SELECT * FROM  tbltype WHERE KdType = '".$idEdit."' ";
			$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		}

		print json_encode($dataOut);
	}

	function deleteData()
	{
		$status = "";
		$dataDel = array();
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');
		
		$idDel = $_POST['idDel'];
		$type = $_POST['type'];

		try {
			if($type == "certificate")
			{
				$dataDel['deletests'] = "1";

				$whereNya = "kdcert = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"mstcert");
			}
			else if($type == "city")
			{
				$dataDel['deletests'] = "1";
				$dataDel['delusrdt'] = $userDateTimeNow;

				$whereNya = "KdKota = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"tblkota");
			}
			else if($type == "company")
			{
				$dataDel['deletests'] = "1";
				$dataDel['delusrdt'] = $userDateTimeNow;

				$whereNya = "kdcmp = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"mstcmprec");
			}
			else if($type == "country")
			{
				$dataDel['deletests'] = "1";
				$dataDel['delusrdt'] = $userDateTimeNow;

				$whereNya = "KdNegara = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"tblnegara");
			}
			else if($type == "rank")
			{
				$dataDel['deletests'] = "1";
				$dataDel['delusrdt'] = $userDateTimeNow;

				$whereNya = "kdrank = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"mstrank");
			}
			else if($type == "vessel")
			{
				$dataDel['deletests'] = "1";
				$dataDel['delusrdt'] = $userDateTimeNow;

				$whereNya = "kdvsl = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"mstvessel");
			}
			else if($type == "vesselType")
			{
				$dataDel['Deletests'] = "1";
				$dataDel['delusrdt'] = $userDateTimeNow;

				$whereNya = "KdType = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"tbltype");
			}

			$status = "Success..!!";
		} catch (Exception $ex) {
			$status = "Failed => ".$ex->getMessage();
		}

		print json_encode($status);
	}

	function updateUrutRank()
	{
		$dataUpd = array();
		$kdRank = $_POST['kdRank'];
		$type = $_POST['type'];
		$urutan = $_POST['urutan'];
		$status = "";

		if($type == "up")
		{
			$newUrut = $urutan - 1;
		}else{
			$newUrut = $urutan + 1;
		}

		try {
			$sqlCekUrut = "SELECT kdrank FROM mstrank WHERE Deletests = '0' AND nmrank != '' AND urutan = '".$newUrut."' LIMIT 0,1 ";
			$rslCekUrut = $this->MCrewscv->getDataQuery($sqlCekUrut);

			if(count($rslCekUrut) > 0)
			{
				$dataUpd['urutan'] =  $urutan;
				$whereNya = "kdrank = '".$rslCekUrut[0]->kdrank."'";
				$this->MCrewscv->updateData($whereNya,$dataUpd,"mstrank");
			}

			$dataUpd = array();
			$dataUpd['urutan'] =  $newUrut;
			$whereNya = "kdrank = '".$kdRank."'";
			$this->MCrewscv->updateData($whereNya,$dataUpd,"mstrank");

			$status = "sukses";
		} catch (Exception $ex) {
			$status = "Failed => ".$ex->getMessages();
		}

		print json_encode($status);
	}


}