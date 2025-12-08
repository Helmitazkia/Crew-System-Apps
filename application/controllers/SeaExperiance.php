<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class SeaExperiance extends CI_Controller {

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

		$sql = "SELECT A.*,B.NmType
					FROM tblseaexp A
					LEFT JOIN tbltype B ON B.KdType = A.typeexp
					WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs btn-block\" onclick=\"getDataEdit('".$val->idexp."');\" title=\"Edit Data\">Edit</button>";
			$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delData('".$val->idexp."','".$idPerson."');\" title=\"Delete Data\">Del</button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->cmpexp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->flagexp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->vslexp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->NmType."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->grtexp."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->dwtexp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->meexp."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$val->hpexp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->rankexp."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->fmdtexp)."</td>";
				$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->todtexp)."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->foreign_crew."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->reasonexp."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		$dataOut['optType'] = $dataContext->getVesselTypeByOption("","kode");
		$dataOut['optRank'] = $dataContext->getRankByOption("","name");
		$dataOut['optMainEngine'] = $dataContext->getMainEngine("");
		$dataOut['optSignOffRemark'] = $dataContext->getSignOffRemarkByOption('','name');

		$this->load->view('frontend/sea_experiance',$dataOut);
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
			$dataIns['cmpexp'] = $data['company'];
			$dataIns['vslexp'] = $data['vessel'];
			$dataIns['flagexp'] = $data['flag'];
			$dataIns['typeexp'] = $data['type'];
			$dataIns['grtexp'] = $data['grt'];
			$dataIns['dwtexp'] = $data['dwt'];
			$dataIns['hpexp'] = $data['bhp'];
			$dataIns['meexp'] = $data['mainEngine'];
			$dataIns['rankexp'] = $data['rank'];
			$dataIns['fmdtexp'] = $data['fromDate'];
			$dataIns['todtexp'] = $data['toDate'];
			$dataIns['reasonexp'] = $data['reason'];
			$dataIns['foreign_crew'] = $data['foreignCrew'];
			
			if($idEdit == "")
			{
				$dataIns['idexp'] = $dataContext->getNewId("idexp","tblseaexp","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblseaexp",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idexp = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblseaexp");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function getDataEdit()
	{
		$id = $_POST['id'];
		$dataOut = array();
		$fullName = "";

		$idPerson = $_POST['idPerson'];

		$sql = "SELECT * FROM tblseaexp WHERE deletests = '0' AND idexp = '".$id."' AND idperson = '".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$dataOut['idEdit'] = $rsl[0]->idexp;
			$dataOut['company'] = $rsl[0]->cmpexp;
			$dataOut['flag'] = $rsl[0]->flagexp;
			$dataOut['vessel'] = $rsl[0]->vslexp;
			$dataOut['type'] = $rsl[0]->typeexp;
			$dataOut['grt'] = $rsl[0]->grtexp;
			$dataOut['dwt'] = $rsl[0]->dwtexp;
			$dataOut['mainEngine'] = $rsl[0]->meexp;
			$dataOut['bhp'] = $rsl[0]->hpexp;
			$dataOut['rank'] = $rsl[0]->rankexp;
			$dataOut['dateFrom'] = $rsl[0]->fmdtexp;
			$dataOut['dateTo'] = $rsl[0]->todtexp;
			$dataOut['reason'] = $rsl[0]->reasonexp;
			$dataOut['foreignCrew'] = $rsl[0]->foreign_crew;
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

		$whereNya = "idexp = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tblseaexp");

		$status = "Success..!!";
		
		print json_encode($status);
	}


}