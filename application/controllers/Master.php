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
			
			$certName = $val->certname;
			$stDisplay = "<i class=\"fa fa-close\" style=\"color:red;\"></i>";
			
			if($val->certgroup != "")
			{
				$certName = "(".$val->certgroup.") ".$val->certname;
			}
			
			if($val->st_display == "Y")
			{
				$stDisplay = "<i class=\"fa fa-check\" style=\"color:green;\"></i>";
			}
			
			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$certName."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->definition."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$stDisplay."</td>";
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

	function getDataOpenRecruitment($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE R.deletests = '0'";

		if($search == "search") {
			$txtSearch = $_POST['txtSearch'];
			$whereNya .= " AND R.subject_name LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT R.*, M.urutan 
			FROM tblopenRecruitment R
			LEFT JOIN mstrank M ON R.rank = M.nmrank
			".$whereNya."
			ORDER BY M.urutan ASC, R.subject_name ASC";


		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$rankName = "";
			$pubDate = "";

			$btnPub = "<button class=\"btn btn-primary btn-xs\" onclick=\"pubDate('".$val->id."','publish', 'openRecruitment');\" title=\"Publish Date\" style=\"margin-top:5px;\"><i class=\"fa fa-check\"></i></button>";
			
			if($val->subject_name != '')
			{
				$rankName = $val->rank;
			}
			
			if($val->subject_name != '')
			{
				if($rankName == "")
				{
					$rankName = $val->subject_name;
				}else{
					$rankName .= " - ".$val->subject_name;
				}
			}

			if($val->sts_publish == 'Y')
			{
				$btnPub = "<button class=\"btn btn-warning btn-xs\" onclick=\"pubDate('".$val->id."','unPublish', 'openRecruitment');\" title=\"Un Publish\" style=\"margin-top:5px;\"><i class=\"fa fa-history\"></i></button>";
				$pubDate = $dataContext->convertReturnNameWithTime($val->datePublish);
			}
			
			
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->id."', 'openRecruitment');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";

			$btnAct .= "<button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->id."', 'openRecruitment');\" title=\"Delete Data\" style=\"margin-top:5px;\"><i class=\"fa fa-close\"></i></button>";

			$btnAct .= $btnPub;
			

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$rankName."<span style='color:gray;font-size:10px;'>(".$val->urutan.")</span></td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->qualification."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$pubDate."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['optRank'] = $dataContext->getRankByOption("","name");
		

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterOpenRecruitment',$dataOut);
		}
	}

	function pubDate()
	{
		$id = $_POST['id'];
		$type = $_POST['type'];
		$dataIns = array();
		$dateNow = date('Y-m-d H:i:s');

		if($id != "")
		{
			if($type == "publish")
			{
				$dataIns['sts_publish'] = 'Y';
				$dataIns['datePublish'] = $dateNow;
			}
			else {
				$dataIns['sts_publish'] = 'N';
				$dataIns['datePublish'] = '0000-00-00 00:00:00';
			}
			$whereNya = "id = '".$id."'";
			

			$this->MCrewscv->updateData($whereNya,$dataIns,"tblopenrecruitment");
			
			print json_encode("Update Success..!!");
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
			$btnDisplay = "";
			if($val->st_display == 'Y')
			{
				$btnDisplay = "<i class=\"fa fa-check\"></i>";
			}
			$mailNya = "";
			if($val->mail_vessel != '')
			{
				$mailNya = "<br>".$val->mail_vessel;
			}
			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnDisplay."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->nmvsl.$mailNya."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->imo."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->grt."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->serpel."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->descvsl."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->nmcmp."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['optCompany'] = $dataContext->getCompanyByOption("",'kode');
		$dataOut['getCrewVesselType'] = $dataContext->getCrewVesselType();

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterVessel',$dataOut);
		}
	}

	function getDataMasterCrewUser($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE sts_delete = '0'";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			$whereNya .= " AND fullname LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM crew_login ".$whereNya." ORDER BY fullname ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->id."','user');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->id."','user');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->idperson."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->fullname."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->username."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->password."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterUser',$dataOut);
		} 
	}

	function getDataMasterUserSystem($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE status = '0'";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];

			$whereNya .= " AND userFullNm LIKE '%".$txtSearch."%' ";
		}

		$sql = "SELECT * FROM login ".$whereNya." ORDER BY userFullNm ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$val->userId."','userSystem');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$val->userId."','userSystem');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->userName."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->userFullNm."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->userPass."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->userJenis."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->userInit."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		if($search == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/viewMasterUserSystem',$dataOut);
		} 
	}

	function getDataMasterSchool($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$tr = "";
		$no = 1;
		$whereNya = " WHERE Deletests = '0'";

		if($search == "search")
		{
			$txtSearch = $_POST['txtSearch'];
			
			$whereNya .= " AND schoolname LIKE '%".$txtSearch."%' ";
		} 

		$sql = "SELECT * FROM mstschool ".$whereNya." ORDER BY schoolname ASC";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $value)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('".$value->id."', 'masterSchool');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('".$value->id."');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

			$tr .= "<tr>";
				$tr .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$tr .= "<td style=\"font-size:11px;\">".$value->schoolname."</td>";
				$tr .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
			$tr .= "</tr>";
			
			$no++;
		}

		$dataOut['tr'] = $tr;

		if($search == "search")
		{
			print json_encode($dataOut);
		}
		else{
			$this->load->view('frontend/viewMasterSchool',$dataOut);
		}
	}

	function saveDataUserMaster() 
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {
			$dataIns['fullname'] = $data['txtfullname'];
			$dataIns['username'] = $data['txtusername'];

			if($data['txtpassword'] != "")
			{
				$dataIns['password'] = md5($data['txtpassword']);
			}

			if($idEdit == "")
			{
				$dataIns['AddUsrDt'] = $userDateTimeNow;
				$this->MCrewscv->insData("crew_login",$dataIns);
			}
			else {
				$dataIns['UpdUsrDt'] = $userDateTimeNow;
				$whereNya = "id = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"crew_login");
			}
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed =>".$ex->getMessage();
		}
		print $stData;
	}

	function saveDataMasterUser() 
	{
		$data = $_REQUEST;
		$dataIns = array();
		$stData = "";
		$idEdit = isset($data['idEdit']) ? $data['idEdit'] : '';
		$userDateTimeNow = $this->session->userdata('userCrewSystem') . "/" . date('Ymd') . "/" . date('H:i:s');

		try {
			$dataIns['userName'] = isset($data['txtusername']) ? $data['txtusername'] : '';
			$dataIns['userFullNm'] = isset($data['txtfullname']) ? $data['txtfullname'] : '';
			$dataIns['userJenis'] = isset($data['txtjenis']) ? $data['txtjenis'] : '';
			$dataIns['userInit'] = isset($data['txtinit']) ? $data['txtinit'] : '';

			if (!empty($data['txtpassword'])) {
				$dataIns['userPass'] = md5($data['txtpassword']);
			}

			if ($idEdit == "") {
				$this->MCrewscv->insData("login", $dataIns);
			} else {
				$whereNya = "userId = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya, $dataIns, "login");
			}

			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => " . $ex->getMessage();
		}

		print $stData;
	}

	function saveDataMasterSchool()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {
			$dataIns['schoolname'] = $data['txtnameschool'];
			
			if($idEdit == "")
			{
				$dataIns['AddUsrDt'] = $userDateTimeNow;
				$this->MCrewscv->insData("mstschool",$dataIns);
			}
			else {
				$dataIns['UpdUsrDt'] = $userDateTimeNow;
				$whereNya = "id = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"mstschool");
			}
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed =>".$ex->getMessage();
		}
		print $stData;
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
	
	function getDataCertificateMatrix($search = '')
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$tempData = array();
		$trNya = "";
		$valNo = 0;        
		$whereNya = "";

		if ($search == "search") {
			$txtSearch = $_POST['txtSearch'];
			$whereNya .= " AND B.nmrank LIKE '%" . $txtSearch . "%' ";
		}

		$sql = "
			SELECT 
				A.id,
				A.certificate_name,
				B.nmrank AS rank_name
			FROM mstcertificatematrix A
			JOIN mstrank B 
				ON A.rank_id = B.kdrank
			WHERE B.deletests = '0'
			AND B.urutan > 0
			{$whereNya}
			ORDER BY B.urutan ASC, B.nmrank ASC
		";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val) {
			$tempData[$val->rank_name][$valNo]['idCertificateMatrix'] = $val->id;
			$tempData[$val->rank_name][$valNo]['certificate_name'] = $val->certificate_name;
			$valNo++;
		}

		foreach ($tempData as $key => $val) {
			$no = 1;
			$ttlRow = count($val); 
			foreach ($val as $keys => $value) {
				$btnAct  = "<button class=\"btn btn-success btn-xs\" onclick=\"getDataEdit('" . $value['idCertificateMatrix'] . "','certificateMatrix');\" title=\"Edit Data\"><i class=\"fa fa-edit\"></i></button>";
				$btnAct .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delData('" . $value['idCertificateMatrix'] . "','certificateMatrix');\" title=\"Delete Data\"><i class=\"fa fa-close\"></i></button>";

				$trNya .= "<tr>";
				if ($no == 1) {
					$trNya .= "<td style=\"font-size:11px;\" rowspan=\"" . $ttlRow . "\">" . $key . "</td>";
				}
				$trNya .= "<td style=\"font-size:11px;\">- " . $value['certificate_name'] . "</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">" . $btnAct . "</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}

		$dataOut['optRank'] = $dataContext->getRankByOption("", "kode");
		$dataOut['optCertificate'] = $dataContext->getMstCertificateByOption("", "nama");
		$dataOut['trNya'] = $trNya;

		if ($search == "search") {
			print json_encode($dataOut);
		} else {
			$this->load->view('frontend/viewMasterReasonEmail', $dataOut);
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
			$dataIns['st_display'] = $data['slcDisplay'];
			
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

	function saveDataOpenRecruitment()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$idEdit = $data['idEdit'];
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {
			$rankName = $data['slcOptionRank'];
			$dataIns['rank'] = $rankName;
			$dataIns['subject_name'] = strtoupper($data['txtSubjectName']);
			$dataIns['qualification'] = $data['txtQualification'];

			$sql = "SELECT urutan FROM mstrank WHERE nmrank = '".$rankName."' AND deletests = '0' LIMIT 1";
			$rankData = $this->MCrewscv->getDataQuery($sql);
			$dataIns['rank_order'] = isset($rankData[0]->urutan) ? $rankData[0]->urutan : 9999;

			if ($idEdit == "") {
				$dataIns['AddUsrDt'] = $userDateTimeNow;
				$this->MCrewscv->insData("tblopenrecruitment", $dataIns);
			} else {
				$dataIns['UpdUsrDt'] = $userDateTimeNow;
				$whereNya = "id = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya, $dataIns, "tblopenrecruitment");
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
			$dataIns['imo'] =  $data['txtIMO'];
			$dataIns['grt'] = $data['txtGRT'];
			$dataIns['serpel'] = $data['txtSerpel'];
			$dataIns['descvsl'] =  $data['slcDefinition'];
			$dataIns['st_display'] =  $data['slcStsDisplay'];
			$dataIns['os_name'] =  $data['osName'];
			$dataIns['os_mail'] =  $data['osMail'];
			$dataIns['mail_vessel'] =  $data['txtMailVessel'];
			
			if($data['txtLoa'] == "")
			{
				$data['txtLoa'] = "0";
			}
			$dataIns['loa'] =  $data['txtLoa'];
			$dataIns['st_own'] =  $data['slcOwn'];
			
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

	function saveDataCertificateMatrix()
	{
		$data = $_POST;
		$stData = "";
		$idEdit = $data['idEdit'];

		try {
			$rank_id   = $data['rankCode'];
			$rank_name = $data['rankName'];
			$certificates = isset($data['certificates']) ? $data['certificates'] : array();

			if ($idEdit == "") {
				foreach ($certificates as $cert) {
					$dataIns = array(
						'rank_id'          => $rank_id,
						'rank_name'        => $rank_name,
						'certificate_name' => $cert
					);
					$this->MCrewscv->insData("mstcertificatematrix", $dataIns);
				}
			} else {
				$whereDelete = "rank_id = '".$rank_id."'";
				$this->MCrewscv->deleteData($whereDelete, "mstcertificatematrix");

				foreach ($certificates as $cert) {
					$dataIns = array(
						'rank_id'          => $rank_id,
						'rank_name'        => $rank_name,
						'certificate_name' => $cert
					);
					$this->MCrewscv->insData("mstcertificatematrix", $dataIns);
				}
			}

			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => " . $ex->getMessage();
		}

		print $stData;
	}

	
	function getDataEdit()
	{
		header('Content-Type: application/json'); 
		$dataOut = array();

		
		if (!isset($_POST['idEdit']) || !isset($_POST['type'])) {
			$dataOut['error'] = "Invalid request. Missing 'idEdit' or 'type'.";
			echo json_encode($dataOut);
			return;
		}

		$idEdit = $_POST['idEdit'];
		$type = $_POST['type'];

		$sql = "";
		if ($type == "certificate") {
			$sql = "SELECT * FROM mstcert WHERE kdcert = '".$idEdit."'";
		} else if ($type == "city") {
			$sql = "SELECT * FROM tblkota WHERE KdKota = '".$idEdit."'";
		} else if ($type == "company") {
			$sql = "SELECT * FROM mstcmprec WHERE kdcmp = '".$idEdit."'";
		} else if ($type == "country") {
			$sql = "SELECT * FROM tblnegara WHERE KdNegara = '".$idEdit."'";
		} else if ($type == "rank") {
			$sql = "SELECT * FROM mstrank WHERE kdrank = '".$idEdit."'";
		} else if ($type == "vessel") {
			$sql = "SELECT * FROM mstvessel WHERE kdvsl = '".$idEdit."'";
		} else if ($type == "vesselType") {
			$sql = "SELECT * FROM tbltype WHERE KdType = '".$idEdit."'";
		} else if ($type == "masterSchool") {
			$sql = "SELECT * FROM mstschool WHERE id = '".$idEdit."'";
		} else if ($type == "openRecruitment") {
			$sql = "SELECT * FROM tblopenrecruitment WHERE id = '".$idEdit."'";
		} else if ($type == "user") {
			$sql = "SELECT * FROM crew_login WHERE id = '".$idEdit."'";
		} else if ($type == "userSystem") {
			$sql = "SELECT * FROM login WHERE userId = '".$idEdit."'";
		} else if ($type == "reasonEmail")
		{
			$sql = "SELECT * FROM  mstreasonemail WHERE id = '".$idEdit."' ";
			$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		}
		

		$dataOut['rsl'] = $this->MCrewscv->getDataQuery($sql);
		echo json_encode($dataOut);
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
			else if($type == "masterSchool")
			{
				$dataDel['Deletests'] = "1";
				$dataDel['DelUsrDt'] = $userDateTimeNow;

				$whereNya = "id = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya, $dataDel, "mstschool");
			}
			else if($type == "openRecruitment")
			{
				$dataDel['Deletests'] = "1";
				$dataDel['DelUsrDt'] = $userDateTimeNow;
				$whereNya = "id = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya, $dataDel, "tblopenrecruitment");
			}
			else if ($type == "user")
			{
				$dataDel['sts_delete'] = "1";
				$dataDel['delusrdt'] = $userDateTimeNow;
				$whereNya = "id = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"crew_login");
			}
			else if ($type == "userSystem")
			{
				$dataDel['status'] = "1";
				$whereNya = "userId = '".$idDel."' ";
				$this->MCrewscv->updateData($whereNya,$dataDel,"login");
			}
			else if($type == "reasonEmail")
			{
				$whereNya = "id = '".$idDel."' ";
				$this->MCrewscv->delData("mstreasonemail",$whereNya);
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