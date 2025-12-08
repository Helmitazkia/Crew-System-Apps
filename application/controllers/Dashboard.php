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
		$dataOut['vesselType'] = $dataContext->getVesselOwnShipOption();
		$dataOut['vesselTypeClient'] = $dataContext->getVesselClientShipOption();
		$dataOut['TypeVessel'] = $dataContext->getVesselType();

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

	function getCrewOnLeave() {
		$sql = "SELECT COUNT(A.idperson) AS total
				FROM mstpersonal A
				LEFT JOIN tblcontract B ON A.idperson = B.idperson
				WHERE A.deletests = '0' 
				AND B.deletests = '0' 
				AND A.inAktif = '0' 
				AND A.inBlacklist = '0'
				AND B.idcontract IN (
					SELECT MAX(idcontract) 
					FROM tblcontract 
					WHERE idperson = B.idperson 
					AND deletests = 0
				)
				AND (B.signoffdt != '0000-00-00' AND B.signoffdt <= CURDATE())";
		$result = $this->MCrewscv->getDataQuery($sql);

		return !empty($result) ? (int)$result[0]->total : 0;
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

		$sql = "SELECT * FROM new_applicant WHERE deletests = '0' AND st_data = '0' AND st_qualify = 'N' AND st_qualify2 = 'N'";
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

	function getSubmitCV() {
		$sql = "
			SELECT DATE(submit_cv) AS tanggal, COUNT(*) AS jumlah
			FROM new_applicant
			WHERE deletests = '0' AND submit_cv IS NOT NULL
			GROUP BY DATE(submit_cv)
			ORDER BY DATE(submit_cv)
		";

   		return $this->MCrewscv->getDataQuery($sql);
	}

	function getDataCVChart() {
		$data = $this->getSubmitCV();
		echo json_encode($data); 
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
		$genderFilter = isset($_POST['gender']) ? $_POST['gender'] : "";
		$getAges = isset($_POST['getAges']) ? true : false; 
		$vslName = "";

		$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND B.signoffdt = '0000-00-00' AND A.inaktif = '0' AND D.deletests = '0' AND B.signonvsl = '".$vslCode."' ";

		if (!empty($genderFilter)) {
			$whereNya .= " AND A.gender = '".$genderFilter."' ";
		}

		$sql = "SELECT TRIM(CONCAT(A.fname,' ',A.mname,' ',A.lname)) AS fullName, D.nmvsl, E.nmrank, 
					TIMESTAMPDIFF(YEAR, A.dob, CURDATE()) AS age
				FROM mstpersonal A
				LEFT JOIN tblcontract B ON A.idperson = B.idperson
				LEFT JOIN tblkota C ON A.pob = C.KdKota
				LEFT JOIN mstvessel D ON D.kdvsl = B.signonvsl AND D.nmvsl != '' AND D.nmvsl != '-'
				LEFT JOIN mstrank E ON E.kdrank = B.signonrank AND E.deletests = '0'
				".$whereNya."
				GROUP BY A.idperson, D.nmvsl
				ORDER BY fullName ASC";
		
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val) {
			$vslName = $val->nmvsl;
			$trNya .= "<tr>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;\">".$no."</td>";
			$trNya .= "<td style=\"font-size:11px;\">".$val->fullName."</td>";

			if ($getAges) {
				$trNya .= "<td style=\"font-size:11px;\">".$val->age." years</td>";
			} else {
				$trNya .= "<td style=\"font-size:11px;\">".$val->nmrank."</td>";
			}

			$trNya .= "</tr>";
			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['vessel'] = $vslName;

		print json_encode($dataOut);
	}

	function shipDemograph()
	{
		$selectedVessels = $this->input->post('vessels'); 

		if (empty($selectedVessels)) {
			echo json_encode(array()); 
			return;
		}

		if (in_array("All", $selectedVessels)) {
			$whereVessel = "D.deletests = '0' AND D.st_display = 'Y' AND D.nmvsl IN (
				'MV. ANDHIKA ALISHA', 
				'MV. ANDHIKA ATHALIA', 
				'MT. ANDHIKA VIDYANATA', 
				'MV. ANDHIKA KANISHKA', 
				'MV. ANDHIKA PARAMESTI', 
				'MV. ANDHIKA SHAKILLA', 
				'MV. BULK HALMAHERA', 
				'MV. BULK BATAVIA', 
				'MV. BULK NUSANTARA'
			)";
		} else {
			$vesselList = "'" . implode("','", $selectedVessels) . "'";
			$whereVessel = "D.nmvsl IN ($vesselList) AND D.st_display = 'Y' AND D.deletests = '0'";
		}
		$sql = "SELECT 
					D.kdvsl AS kode_kapal, 
					D.nmvsl AS nama_kapal, 
					COUNT(A.idperson) AS jumlah_crew_onboard,
					SUM(CASE WHEN A.gender = 'Male' THEN 1 ELSE 0 END) AS total_male,
					SUM(CASE WHEN A.gender = 'Female' THEN 1 ELSE 0 END) AS total_female,
					SUM(TIMESTAMPDIFF(YEAR, A.dob, CURDATE())) AS total_umur
				FROM 
					mstpersonal A
				LEFT JOIN 
					tblcontract B ON A.idperson = B.idperson
				LEFT JOIN 
					mstvessel D ON D.kdvsl = B.signonvsl 
				WHERE 
					A.deletests = '0' 
					AND B.deletests = '0' 
					AND B.signoffdt = '0000-00-00' 
					AND A.inaktif = '0' 
					AND B.signoffdt = '0000-00-00'
					AND $whereVessel
				GROUP BY 
					D.kdvsl, D.nmvsl;
				";

		$result = $this->MCrewscv->getDataQuery($sql);
		echo json_encode($result);
	}
	
	function crewBarChart()
	{
		$selectedVesselClient = $this->input->post('vesselsClient');

		if (empty($selectedVesselClient)) {
			echo json_encode(array());
			return;
		}

		if (in_array("All", $selectedVesselClient)) {
			$whereVessel = "LOWER(D.nmvsl) NOT IN (
				'mv. andhika alisha', 
				'mv. andhika athalia', 
				'mt. andhika vidyanata', 
				'mv. andhika kanishka', 
				'mv. andhika paramesti', 
				'mv. andhika shakilla', 
				'mv. bulk halmahera', 
				'mv. bulk batavia', 
				'mv. bulk nusantara'
			)"; 
		} else {
			$vesselList = "'" . implode("','", array_map('strtolower', $selectedVesselClient)) . "'";
			$whereVessel = "LOWER(D.nmvsl) IN ($vesselList)";
		}


		$sql = "SELECT 
					C.nmcmp AS ClientName,        
					D.kdvsl AS kode_kapal, 
					D.nmvsl AS nama_kapal, 
					COUNT(A.idperson) AS jumlah_crew_onboard,
					SUM(CASE WHEN A.gender = 'Male' THEN 1 ELSE 0 END) AS total_male,
					SUM(CASE WHEN A.gender = 'Female' THEN 1 ELSE 0 END) AS total_female,
					SUM(TIMESTAMPDIFF(YEAR, A.dob, CURDATE())) AS total_umur
				FROM 
					mstpersonal A
				LEFT JOIN 
					tblcontract B ON A.idperson = B.idperson
				LEFT JOIN 
					mstcmprec C ON C.kdcmp = B.kdcmprec
				LEFT JOIN 
					mstvessel D ON D.kdvsl = B.signonvsl 
				WHERE 
					A.deletests = '0' 
					AND B.deletests = '0' 
					AND B.signoffdt = '0000-00-00' 
					AND A.inaktif = '0' 
					AND D.deletests = '0' 
					AND C.deletests = '0'
					AND $whereVessel
				GROUP BY 
					C.nmcmp, D.kdvsl, D.nmvsl
				ORDER BY 
					C.nmcmp, D.nmvsl
				";



		$result = $this->MCrewscv->getDataQuery($sql);
		echo json_encode($result);
	}


	function contractBarChart()
	{
		$dataContext = new DataContext();
		$sql = "
			SELECT 
				DATE_FORMAT(B.estsignoffdt, '%Y-%m') AS Month,
				B.estsignoffdt AS EstimatedSignOffDate,
				CONCAT(A.fname, ' ', IFNULL(A.mname, ''), ' ', A.lname) AS CrewName,
				RANK.nmrank AS RankName,
				B.signondt AS SignOnDate,
				RANK.urutan AS RankOrder
			FROM 
				mstpersonal A
			LEFT JOIN tblcontract B ON A.idperson = B.idperson
			LEFT JOIN mstvessel D ON D.kdvsl = B.signonvsl
			LEFT JOIN mstrank RANK ON B.signonrank = RANK.kdrank
			WHERE 
				A.deletests = '0'
				AND RANK.urutan > 0
				AND B.deletests = '0'
				AND B.signoffdt = '0000-00-00'
				AND A.inaktif = '0'
				AND B.estsignoffdt != '0000-00-00'
				AND YEAR(B.estsignoffdt) = 2025
				AND D.deletests = '0'
				AND (B.signonvsl IS NULL OR D.nmvsl != '' AND D.nmvsl != '-')
				AND B.signonrank IS NOT NULL 
			ORDER BY 
				Month ASC, RankOrder ASC, CrewName ASC, EstimatedSignOffDate ASC;
		";

		$result = $this->MCrewscv->getDataQuery($sql);
		$data = array();
		$rankCounts = array();
		$rankOrder = array();

		foreach ($result as $row) {
			$monthName = date("F", strtotime($row->EstimatedSignOffDate)); 

			$data[] = array(
				'month' => $monthName,
				'estimated_signoff_date' => $dataContext->convertReturnName($row->EstimatedSignOffDate),
				'crew_name' => $row->CrewName,
				'rank_name' => $row->RankName,
				'sign_on_date' => $dataContext->convertReturnName($row->SignOnDate)
			);

			if (!isset($rankCounts[$monthName][$row->RankName])) {
				$rankCounts[$monthName][$row->RankName] = 0;
			}
			$rankCounts[$monthName][$row->RankName]++;

			if (!isset($rankOrder[$row->RankName])) {
				$rankOrder[$row->RankName] = isset($row->RankOrder) ? $row->RankOrder : 999;
			}
		}

		foreach ($rankCounts as $month => &$ranks) {
			uksort($ranks, function ($a, $b) use ($rankOrder) {
				return (isset($rankOrder[$a]) ? $rankOrder[$a] : 999) - (isset($rankOrder[$b]) ? $rankOrder[$b] : 999);
			});
		}

		header('Content-Type: application/json');
		echo json_encode(array(
			'crewData' => $data, 
			'rankSummary' => $rankCounts,
			'rankOrder' => $rankOrder  
		));
	}


	function getSchool() {
		$sql = "
			SELECT 
				T.namescl AS nama_sekolah,
				COUNT(DISTINCT CASE WHEN B.signoffdt = '0000-00-00' THEN A.idperson END) AS jumlah_onboard,
				COUNT(DISTINCT CASE WHEN B.signoffdt != '0000-00-00' THEN A.idperson END) AS jumlah_onleave,
				GROUP_CONCAT(
					DISTINCT CASE 
						WHEN B.signoffdt = '0000-00-00' 
						THEN CONCAT_WS(' ', A.fname, A.mname, A.lname, '(', R.nmrank, ')') 
					END
					ORDER BY R.urutan, A.fname SEPARATOR ', '
				) AS crew_onboard,
				GROUP_CONCAT(
					DISTINCT CASE 
						WHEN B.signoffdt != '0000-00-00' 
						THEN CONCAT_WS(' ', A.fname, A.mname, A.lname, '(', R.nmrank, ')') 
					END
					ORDER BY R.urutan, A.fname SEPARATOR ', '
				) AS crew_onleave
			FROM tblscl T
			LEFT JOIN mstpersonal A ON T.idperson = A.idperson
			LEFT JOIN tblcontract B ON A.idperson = B.idperson
			LEFT JOIN mstrank R ON B.signonrank = R.kdrank
			WHERE 
				A.deletests = '0' 
				AND R.urutan > 0
				AND T.deletests = '0'
				AND B.deletests = '0'
			GROUP BY T.namescl
			ORDER BY jumlah_onboard DESC
			LIMIT 10;	
		";

		try {
			$result = $this->db->query($sql)->result();
			if (!$result) {
				throw new Exception("No data returned from the database.");
			}

			$data = array_map(function($row) {
				return array(
					'school' => $row->nama_sekolah,
					'onboard_crew' => (int)$row->jumlah_onboard,
					'onleave_crew' => (int)$row->jumlah_onleave,
					'crew_onboard' => $row->crew_onboard ?: '',
					'crew_onleave' => $row->crew_onleave ?: ''
				);
			}, $result);

			echo json_encode($data);

		} catch (Exception $e) {
			http_response_code(500);
			echo json_encode(array('error' => $e->getMessage()));
		}
	}


	function getCadangan()
	{
		$vesselTypeCategory = isset($_POST['vesselTypeCategory']) ? $_POST['vesselTypeCategory'] : 'All';

		if (empty($vesselTypeCategory) || $vesselTypeCategory == 'All') {
			$whereCrewType = "";
			$whereCrewTypeOnboard = "";
		} else {
			$whereCrewType = "AND A.crew_vessel_type = '{$vesselTypeCategory}'";
			$whereCrewTypeOnboard = "AND P.crew_vessel_type = '{$vesselTypeCategory}'";
		}

		$sql = "SELECT 
					RANK.kdrank, 
					RANK.nmrank, 
					COUNT(A.idperson) AS total_onleave,
					(
						SELECT COUNT(P.idperson)
						FROM mstpersonal P
						LEFT JOIN tblcontract Q ON P.idperson = Q.idperson
						WHERE 
							P.deletests = '0' AND 
							P.inaktif = '0' AND
							Q.deletests = '0' AND 
							Q.signoffdt = '0000-00-00' AND 
							Q.signonrank = RANK.kdrank
							{$whereCrewTypeOnboard}
					) AS total_onboard
				FROM 
					mstrank RANK
				LEFT JOIN tblcontract B ON RANK.kdrank = B.signonrank
				LEFT JOIN mstpersonal A ON A.idperson = B.idperson
				WHERE 
					RANK.deletests = '0' 
					AND RANK.urutan > 0
					AND RANK.nmrank != '' 
					AND A.deletests = '0' 
					AND B.deletests = '0' 
					AND A.inAktif = '0' 
					AND A.inBlacklist = '0' 
					{$whereCrewType}
					AND B.idcontract IN (
						SELECT MAX(idcontract) 
						FROM tblcontract 
						WHERE idperson = B.idperson 
						AND deletests = 0
					)
					AND (B.signoffdt != '0000-00-00' AND B.signoffdt <= CURDATE())  
				GROUP BY 
					RANK.kdrank, RANK.nmrank
				ORDER BY 
					RANK.urutan DESC
				LIMIT 50";

		$result = $this->MCrewscv->getDataQuery($sql);
		$data = array();

		foreach ($result as $row) {
			$totalOnboard = $row->total_onboard;
			$totalOnleave = $row->total_onleave;
			$batasMedium = 1.5 * $totalOnboard;
 
			if ($totalOnleave <= $totalOnboard) {
				$category = 'High';
				$color = '#001F5B';
			} elseif ($totalOnleave > $totalOnboard && $totalOnleave <= $batasMedium) {
				$category = 'Medium';
				$color = '#4258B1';
			} else {
				$category = 'Low';
				$color = '#84b0e3';
			}

			$data[] = array(
				'rank' => $row->nmrank,
				'total_onleave' => $totalOnleave,
				'total_onboard' => $totalOnboard,
				'category' => $category,
				'color' => $color
			);
		}

		header('Content-Type: application/json');
		echo json_encode($data);
	}
	
	function sseNotifications()
	{
		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		header('Connection: keep-alive');
		header('Access-Control-Allow-Origin: *'); 

		ini_set('output_buffering', 'off');
		ini_set('zlib.output_compression', false);
		while (ob_get_level() > 0) ob_end_flush();
		ob_implicit_flush(true);

		set_time_limit(0);
		$start = time();
		$timeout = 300;

		while (true) {
			if ((time() - $start) > $timeout) {
				echo "event: close\n";
				echo "data: Connection closed after timeout\n\n";
				break;
			}

			$notifications = $this->getNotificationDetails();
			$jsonData = json_encode($notifications);

			echo "data: $jsonData\n\n";
			sleep(15);
		}
	}




	function getNotificationDetails()
	{
		$queryToMonth = $this->MCrewscv->getDataQuery("SELECT ADDDATE(CURDATE(), 30) AS datetomonth");

		if (!$queryToMonth || !isset($queryToMonth[0])) {
			return array();
		}

		$dueDate = $queryToMonth[0]->datetomonth;

		$sql = "
			SELECT 
				'cert' AS type,
				A.idperson AS id,
				TRIM(CONCAT(D.fname,' ',D.mname,' ',D.lname)) AS fullName,
				C.nmvsl AS extra1,
				B.dispname AS extra2,
				B.expdate AS extra3,
				NULL AS email,
				NULL AS position_applied,
				NULL AS born_date
			FROM tblcontract A
			JOIN tblcertdoc B ON A.idperson = B.idperson
			JOIN mstvessel C ON C.kdvsl = A.signonvsl
			JOIN mstpersonal D ON D.idperson = A.idperson
			WHERE A.signoffdt = '0000-00-00'
				AND A.deletests = 0
				AND B.expdate BETWEEN CURDATE() AND '$dueDate'
				AND B.expdate != '0000-00-00'
				AND B.deletests = 0

			UNION ALL

			SELECT 
				'applicant' AS type,
				NA.id AS id,
				NA.fullname AS fullName,
				NULL AS extra1,
				NULL AS extra2,
				NULL AS extra3,
				NA.email,
				NA.position_applied,
				NA.born_date
			FROM new_applicant NA
			WHERE NA.st_data = 0 
				AND NA.deletests = 0
				AND DATE(NA.submit_cv) = CURDATE()

			ORDER BY type DESC, id DESC
			LIMIT 50
		";

		$rows = $this->MCrewscv->getDataQuery($sql);
		if (!$rows) return array();

		$notifications = array();
		foreach ($rows as $row) {
			if ($row->type === 'cert') {
				if (!isset($notifications[$row->id])) {
					$notifications[$row->id] = array(
						'type' => 'cert',
						'idperson' => $row->id,
						'fullName' => $row->fullName,
						'nmvsl' => $row->extra1,
						'certs' => array(),
					);
				}
				$notifications[$row->id]['certs'][] = array(
					'dispname' => $row->extra2,
					'expdate' => $row->extra3,
				);
			} else if ($row->type === 'applicant') {
				$notifications[] = array(
					'type' => 'applicant',
					'id' => $row->id,
					'fullName' => $row->fullName,
					'email' => $row->email,
					'position_applied' => $row->position_applied,
					'born_date' => $row->born_date,
				);
			}
		}
		return array_values($notifications);
	}

	function getNotificationDetailsAPI()
	{
		header('Content-Type: application/json');
		$data = $this->getNotificationDetails();
		echo json_encode($data);
	}

	function rankContractExpiry()
	{
		$sql = "
			SELECT 
				RANK.nmrank AS RankName,
				COALESCE(expiring.total_expiring, 0) AS total_expiring,
				COALESCE(onleave.total_onleave, 0) AS total_onleave,
				COALESCE(onboard.total_onboard, 0) AS total_onboard,
				CASE 
					WHEN COALESCE(onleave.total_onleave, 0) <= COALESCE(onboard.total_onboard, 0) 
						THEN 'Low' 
					WHEN COALESCE(onleave.total_onleave, 0) > COALESCE(onboard.total_onboard, 0) 
						AND COALESCE(onleave.total_onleave, 0) <= (1.5 * COALESCE(onboard.total_onboard, 0))
						THEN 'Medium' 
					ELSE 'High' 
				END AS RankCategory,
				CASE 
					WHEN COALESCE(onleave.total_onleave, 0) <= COALESCE(onboard.total_onboard, 0) 
						THEN 'red'
					WHEN COALESCE(onleave.total_onleave, 0) > COALESCE(onboard.total_onboard, 0) 
						AND COALESCE(onleave.total_onleave, 0) <= 1.5 * COALESCE(onboard.total_onboard, 0) 
						THEN 'yellow'
					ELSE 'green'
				END AS RankColor,
				CASE 
					WHEN COALESCE(onleave.total_onleave, 0) <= COALESCE(onboard.total_onboard, 0) 
						THEN COALESCE(onboard.total_onboard, 0) + 1 - COALESCE(onleave.total_onleave, 0)
					ELSE 0
				END AS SuggestedRecruitment
			FROM mstrank RANK
			LEFT JOIN (
			 	SELECT 
					B.signonrank,
					COUNT(*) AS total_expiring
				FROM mstpersonal A
				LEFT JOIN tblcontract B ON A.idperson = B.idperson
				WHERE 
					A.deletests = '0'
					AND A.inaktif = '0'
					AND B.deletests = '0'
					AND B.signoffdt = '0000-00-00'
					AND B.estsignoffdt != '0000-00-00'
					AND B.estsignoffdt BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 MONTH)
				GROUP BY B.signonrank
			) AS expiring ON RANK.kdrank = expiring.signonrank
			LEFT JOIN (
				SELECT 
					B.signonrank,
					COUNT(A.idperson) AS total_onleave
				FROM mstpersonal A
				LEFT JOIN tblcontract B ON A.idperson = B.idperson
				WHERE 
					A.deletests = '0' 
					AND B.deletests = '0' 
					AND A.inAktif = '0' 
					AND A.inBlacklist = '0'
					AND B.idcontract = (
						SELECT MAX(idcontract) 
						FROM tblcontract 
						WHERE idperson = B.idperson 
						AND deletests = 0
					)
					AND B.signoffdt != '0000-00-00' 
					AND B.signoffdt <= CURDATE()  
				GROUP BY B.signonrank
			) AS onleave ON RANK.kdrank = onleave.signonrank
			LEFT JOIN (
				SELECT 
					Q.signonrank,
					COUNT(P.idperson) AS total_onboard
				FROM mstpersonal P
				LEFT JOIN tblcontract Q ON P.idperson = Q.idperson
				WHERE 
					P.deletests = '0' 
					AND Q.deletests = '0' 
					AND Q.signoffdt = '0000-00-00' 
					AND P.inaktif = '0'
				GROUP BY Q.signonrank
			) AS onboard ON RANK.kdrank = onboard.signonrank
			WHERE 
				RANK.deletests = '0'
				AND RANK.nmrank != ''
				AND RANK.urutan > 0
				AND expiring.total_expiring > 0
				AND (
					COALESCE(onleave.total_onleave, 0) <= COALESCE(onboard.total_onboard, 0)
				)
			ORDER BY 
				RANK.urutan ASC
			LIMIT 45
			";
			
		$result = $this->MCrewscv->getDataQuery($sql);

		echo json_encode($result);
	}

	function getCrewDetailsWithRanks()
	{
		$dataOut = array();

		$sql = "SELECT 
					D.nmvsl AS nama_kapal,
					COUNT(A.idperson) AS jumlah_crew_onboard,
					GROUP_CONCAT(CONCAT(TRIM(CONCAT(A.fname, ' ', A.mname, ' ', A.lname)), ' (', E.nmrank, ')') SEPARATOR ', ') AS daftar_crew_dengan_rank
				FROM 
					mstpersonal A
				LEFT JOIN 
					tblcontract B ON A.idperson = B.idperson
				LEFT JOIN 
					tblkota C ON A.pob = C.KdKota
				LEFT JOIN 
					mstvessel D ON D.kdvsl = B.signonvsl
				LEFT JOIN 
					mstrank E ON E.kdrank = B.signonrank AND E.deletests = '0'
				WHERE 
					A.deletests = '0'
					AND B.deletests = '0'
					AND B.signoffdt = '0000-00-00'
					AND A.inaktif = '0'
					AND D.deletests = '0'
					AND D.nmvsl IN (
						'MV. ANDHIKA ALISHA', 
						'MV. ANDHIKA ATHALIA', 
						'MT. ANDHIKA VIDYANATA', 
						'MV. ANDHIKA KANISHKA', 
						'MV. ANDHIKA PARAMESTI', 
						'MV. ANDHIKA SHAKILLA', 
						'MV. BULK HALMAHERA', 
						'MV. BULK BATAVIA', 
						'MV. BULK NUSANTARA'
					)
				GROUP BY 
					D.nmvsl
				ORDER BY 
					D.nmvsl ASC";

		$result = $this->db->query($sql)->result();

		$dataOut['details'] = $result;

		echo json_encode($dataOut);
	}

	function getDetailCrewNewApplicent()
	{
		$page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
		$limit = 20;
		$offset = ($page - 1) * $limit;
		$no = $offset + 1;

		$trNya = "";

		$sqlTotal = "SELECT COUNT(*) as total FROM new_applicant WHERE deletests = '0' AND st_data = '0' AND st_qualify = 'N' AND st_qualify2 = 'N'";
		$resultTotal = $this->MCrewscv->getDataQuery($sqlTotal);
		$totalRows = isset($resultTotal[0]) ? $resultTotal[0]->total : 0;
		$totalPages = ceil($totalRows / $limit);
		$start = $offset + 1;
		$end = min($offset + $limit, $totalRows);

		$sql = "SELECT * FROM new_applicant 
				WHERE deletests = '0' AND st_data = '0' AND st_qualify = 'N' AND st_qualify2 = 'N' 
				ORDER BY submit_cv DESC 
				LIMIT $limit OFFSET $offset";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $val) {
			$trNya .= "<tr>";
			$trNya .= "<td align=\"center\" style=\"font-size:11px;\">".$no."</td>";
			$trNya .= "<td style=\"font-size:11px;\">".$val->fullname."</td>";
			$trNya .= "<td style=\"font-size:11px;\">".$val->position_applied."</td>";
			$trNya .= "</tr>";
			$no++;
		}

		$infoTotalData = "<div class='text-left' style='padding: 10px; font-weight: bold;'>
			Menampilkan data $start - $end dari total $totalRows data
		</div>";

		$pagination = '<div class="text-center" style="margin-top: 10px;">';

		if ($page > 1) {
			$prevPage = $page - 1;
			$pagination .= "<button class='btn btn-sm btn-info' style='margin: 0 5px;' onclick='displayNewApplicent($prevPage)'>⟨ Sebelumnya</button>";
		}

		$pagination .= "<span style='margin: 0 10px; font-weight: bold;'>Halaman $page dari $totalPages</span>";

		if ($page < $totalPages) {
			$nextPage = $page + 1;
			$pagination .= "<button class='btn btn-sm btn-info' style='margin: 0 5px;' onclick='displayNewApplicent($nextPage)'>Selanjutnya ⟩</button>";
		}

		$pagination .= '</div>';

		$dataOut['trNya'] = $trNya;
		$dataOut['pagination'] = $pagination;
		$dataOut['info'] = $infoTotalData;

		echo json_encode($dataOut);
	}

}