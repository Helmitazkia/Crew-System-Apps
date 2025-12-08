<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

	function index()
	{
		$this->getData();
	}

	function getData($idPerson = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$totalCrew = 0;

		$onBoard = $this->getCrewOnboard("");
		$onLeave = $this->getCrewOnLeave();
		$nonAktif = $this->getCrewNonAktif();
		$notForEmp = $this->getCrewNotForEmployeed();
		$newApplicent = $this->getCrewNewApplicent();
		$cadetOnBoard = $this->getCadetOnboard();

		$dataOut['onBoard'] = number_format($onBoard,0);
		$dataOut['onLeave'] = number_format($onLeave,0);
		$dataOut['nonAktif'] = number_format($nonAktif,0);
		$dataOut['notForEmp'] = number_format($notForEmp,0);
		$dataOut['newApplicent'] = number_format($newApplicent,0);
		$dataOut['cadetOnBoard'] = number_format($cadetOnBoard,0);
		$dataOut['totalCrew'] = number_format($onBoard+$onLeave,0);

		$this->load->view('frontend/dashboard',$dataOut);
	}

	function getCrewOnboard($vslCode = "")
	{
		$total = 0;

		$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND B.signoffdt = '0000-00-00' AND A.inaktif = '0' AND D.deletests = '0' ";

		if($vslCode != "")
		{
			$whereNya .= " AND B.signonvsl = '".$vslCode."' ";
		}

		$sql = "SELECT COUNT(A.idperson)
				FROM mstpersonal A
				LEFT JOIN tblcontract B ON A.idperson = B.idperson
				LEFT JOIN tblkota C ON A.pob = C.KdKota
				LEFT JOIN mstvessel D ON D.kdvsl = B.signonvsl AND D.nmvsl != '' AND D.nmvsl != '-'
				".$whereNya."
				GROUP BY A.idperson,D.nmvsl";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		$total = count($rsl);

		return $total;
	}

	function getCrewOnLeave()
	{
		$total = 0;

		$sql = "SELECT COUNT(A.idperson)
				FROM mstpersonal A
				LEFT JOIN tblcontract B ON A.idperson = B.idperson
				LEFT JOIN tblkota C ON A.pob = C.KdKota
				WHERE A.deletests = '0' AND B.deletests = '0' AND A.inAktif  = '0' AND A.inBlacklist='0'
				AND B.idcontract IN (SELECT MAX(idcontract) FROM tblcontract WHERE idperson=B.idperson AND deletests=0)
				AND (B.signoffdt != '0000-00-00' AND B.signoffdt <= CURDATE( ))
				GROUP BY A.idperson";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		$total = count($rsl);

		return $total;
	}

	function getDetailOnLeave() {
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$ttlCrewOnLeave = $this->getCrewOnLeave(); 
		$crewData = $dataContext->getCrewOnLeaveByRank(); 

		$ranks = $dataContext->getDataRank();

		$rankOrder = array();
		foreach ($ranks as $rank) {
			$rankOrder[] = $rank->nmrank;
		}

		$mappedCrewByRank = array();
		foreach ($crewData as $crew) {
			if (isset($crew->nmrank)) {
				$mappedCrewByRank[$crew->nmrank][] = $crew->crew_name;
			} else {
				$mappedCrewByRank['No Rank'][] = $crew->crew_name;
			}
		}       

		foreach ($rankOrder as $rank) {
			$hasData = isset($mappedCrewByRank[$rank]) && !empty($mappedCrewByRank[$rank]);
			$crewCount = $hasData ? count($mappedCrewByRank[$rank]) : 0;

			$backgroundColor = 'transparent';
			$textColor = 'black';

			$trNya .= "<tr class='table-row' data-rank='$rank' 
				style='background-color: $backgroundColor; color: $textColor; font-size: 13px; padding: 8px;' 
				onmouseover=\"changeCursor(this, " . ($hasData ? 'true' : 'false') . ")\" 
				onmouseout=\"resetCursor(this)\">";

			$trNya .= "<td align=\"center\" style=\"font-size:13px; padding: 8px;\">" . $no . "</td>";
			$trNya .= "<td style=\"font-size:13px; padding: 8px;\"><strong>" . $rank . "</strong></td>";

			if ($hasData) {
				$trNya .= "<td style=\"font-size:13px; padding: 8px;\">Total Crew: $crewCount</td>";
			} else {
				$trNya .= "<td style=\"font-size:13px; padding: 8px;\">There's no Crew</td>";
			}

			$trNya .= "</tr>";
			$no++;

			if ($hasData) {
				$trNya .= "<tr class='details-row' style='display:none;'>";
				$trNya .= "<td colspan='3' style='background-color: #f9f9f9; padding: 10px;'>";
				$trNya .= "<div style='padding: 10px; max-height: 400px; overflow-y: auto; border: 1px solid #ddd;'>";
				$trNya .= "<strong style='font-size:13px;'>Crew List:</strong><br>";
				$trNya .= "<ul style='list-style-type: none; padding-left: 10px; font-size: 13px;'>";

				foreach ($mappedCrewByRank[$rank] as $crew) {
					$trNya .= "<li style='margin-bottom: 8px; display: flex; align-items: center;'>";
					$trNya .= "<span style='color: navy; flex: 1; font-size: 13px;'>" . $crew . "</span>";
					$trNya .= "<button class='btn btn-info btn-xs' style='margin-left: 10px; font-size: 12px;' onclick=\"getDetailCrewName('" . $rank . "', '" . addslashes($crew) . "')\">Detail</button>";
					$trNya .= "</li>";
				}

				$trNya .= "</ul>";
				$trNya .= "</div>";
				$trNya .= "</td>";
				$trNya .= "</tr>";
			}
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['totalCrew'] = number_format($ttlCrewOnLeave, 0) . " Crew";

		print json_encode($dataOut);
	}


	function getDetailCrewOnLeave()
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;

		$idRank = $this->input->post('rank');
		$crewName = $this->input->post('crew_name'); 

		$idRank = $this->db->escape($idRank);
		$crewName = $this->db->escape($crewName); 

		$sql = "SELECT 
					CONCAT(A.fname, ' ', COALESCE(A.mname, ''), ' ', A.lname) AS crew_name, 
					R.nmrank AS rank_name, 
					B.signoffdt AS signoff_date, 
					COALESCE(V1.nmvsl, V2.nmvsl) AS last_vessel 
				FROM 
					mstpersonal A 
				LEFT JOIN 
					tblcontract B ON A.idperson = B.idperson 
				LEFT JOIN 
					mstvessel V1 ON B.lastvsl = V1.kdvsl 
				LEFT JOIN 
					mstvessel V2 ON B.signonvsl = V2.kdvsl 
				LEFT JOIN 
					mstrank R ON B.signonrank = R.kdrank 
				WHERE 
					A.deletests = '0' 
					AND B.deletests = '0' 
					AND A.inAktif = '0' 
					AND A.inBlacklist = '0' 
					AND R.nmrank = $idRank
					AND CONCAT(A.fname, ' ', COALESCE(A.mname, ''), ' ', A.lname) = $crewName 
					AND B.idcontract IN ( 
						SELECT MAX(idcontract) 
						FROM tblcontract 
						WHERE idperson = B.idperson 
						AND deletests = 0 
					) 
					AND (B.signoffdt != '0000-00-00' AND B.signoffdt <= CURDATE())";

		$result = $this->MCrewscv->getDataQuery($sql);

		$vesselName = "";
		foreach ($result as $key => $row) {
			$vesselName = $row->last_vessel; 
			
			$formattedDate = $dataContext->getFormatDate($row->signoff_date);

			$trNya .= "<tr>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;\">" . $no . "</td>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;\">" . $formattedDate . "</td>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;\">" . $row->last_vessel . "</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['vessel'] = $vesselName;

		print json_encode($dataOut);
	}

	function getCadanganData() {
		$query = "SELECT nmrank, cadangan FROM mstrank ORDER BY urutan";
		$data = $this->MCrewscv->getDataQuery($query);
		
		print json_encode($data);
	}

	function getCrewNonAktif()
	{
		$total = 0;

		$sql = "SELECT COUNT(A.idperson)
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				WHERE A.deletests = '0' AND B.deletests = '0' AND A.inAktif  = '1' AND A.inBlacklist='0'
				GROUP BY A.idperson";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		$total = count($rsl);

		return $total;
	}

	function getCrewNotForEmployeed()
	{
		$total = 0;

		$sql = "SELECT COUNT(A.idperson)
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				WHERE A.deletests = '0' AND B.deletests = '0' AND A.inBlacklist = '1'
				GROUP BY A.idperson";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		$total = count($rsl);

		return $total;
	}

	function getCrewNewApplicent()
	{
		$total = 0;

		$sql = "SELECT COUNT(A.idperson)
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				WHERE A.deletests = '0' AND B.deletests = '0' AND A.newapplicent = '1'
				GROUP BY A.idperson";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		$total = count($rsl);

		return $total;
	}

	function getCadetOnboard()
	{
		$total = 0;

		$sql = "SELECT COUNT(A.idperson)
				FROM mstpersonal A
				LEFT JOIN tblcontract B ON A.idperson = B.idperson
				LEFT JOIN tblkota C ON A.pob = C.KdKota
				LEFT JOIN mstvessel D ON D.kdvsl = B.signonvsl AND D.nmvsl != '' AND D.nmvsl != '-'
				WHERE A.deletests = '0' AND B.deletests = '0' AND D.deletests = '0' AND B.signoffdt = '0000-00-00' AND A.inaktif = '0' AND A.applyfor LIKE '%cadet%'
				GROUP BY A.idperson,D.nmvsl";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		$total = count($rsl);

		return $total;
	}

	function getDetailOnBoard()
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$ttlCrewOnBo = 0;
 
		$dataVessel = $dataContext->getVessel();

		foreach ($dataVessel as $key => $val)
		{
			$ttlCrew = $this->getCrewOnboard($val->kdvsl);

			if($ttlCrew > 0)
			{
				$btnTtl = "<button class=\"btn btn-success btn-xs btn-block\" title=\"Detail Crew\" onclick=\"getDetailCrew('".$val->kdvsl."');\">".number_format($ttlCrew,0)."</button>";
				$trNya .= "<tr>";
					$trNya .= "<td align=\"center\" style=\"font-size:11px;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;\">".$val->nmvsl."</td>";
					$trNya .= "<td align=\"center\" style=\"font-size:11px;\">".$btnTtl."</td>";
				$trNya .= "</tr>";

				$ttlCrewOnBo = $ttlCrewOnBo + $ttlCrew;
				$no++;
			}
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['totalCrew'] = number_format($ttlCrewOnBo,0)." Crew";

		print json_encode($dataOut);
	}

	function getDetailCrewOnBoard()
	{
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$vslCode = $_POST['vslCode'];
		$vslName = "";

		$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND B.signoffdt = '0000-00-00' AND A.inaktif = '0' AND D.deletests = '0' AND B.signonvsl = '".$vslCode."' ";

		$sql = "SELECT COUNT(A.idperson),TRIM(CONCAT(A.fname,' ',A.mname,' ',A.lname)) AS fullName,D.nmvsl,E.nmrank
				FROM mstpersonal A
				LEFT JOIN tblcontract B ON A.idperson = B.idperson
				LEFT JOIN tblkota C ON A.pob = C.KdKota
				LEFT JOIN mstvessel D ON D.kdvsl = B.signonvsl AND D.nmvsl != '' AND D.nmvsl != '-'
				LEFT JOIN mstrank E ON E.kdrank = B.signonrank AND E.deletests = '0'
				".$whereNya."
				GROUP BY A.idperson,D.nmvsl
				ORDER BY fullName ASC";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$vslName = $val->nmvsl;
			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\" style=\"font-size:11px;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->fullName."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->nmrank."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['vessel'] = $vslName;

		print json_encode($dataOut);
	}
	
	function getDetailCrewNewApplicent()
	{
		$dataOut = array();
		$trNya = "";
		$no = 1;

		$sql = "SELECT A.idperson,TRIM(CONCAT(A.fname,' ',A.mname,' ',A.lname)) AS fullName,A.applyfor
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				WHERE A.deletests = '0' AND B.deletests = '0' AND A.newapplicent = '1'
				ORDER BY fullName ASC";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\" style=\"font-size:11px;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->fullName."</td>";
				$trNya .= "<td style=\"font-size:11px;\">".$val->applyfor."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;

		print json_encode($dataOut);
	}


}