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

	function crewBarChart()
	{
		$sql = "SELECT 
					C.nmcmp AS ClientName,
					D.kdvsl AS kode_kapal, 
					D.nmvsl AS nama_kapal, 
					COUNT(A.idperson) AS jumlah_crew_onboard,
					SUM(CASE WHEN A.gender = 'Male' THEN 1 ELSE 0 END) AS total_male,
					SUM(CASE WHEN A.gender = 'Female' THEN 1 ELSE 0 END) AS total_female,
					AVG(TIMESTAMPDIFF(YEAR, A.dob, CURDATE())) AS rata_rata_umur,
					GROUP_CONCAT(CONCAT(A.fname, ' ', COALESCE(A.mname, ''), ' ', A.lname) SEPARATOR ', ') AS crew_names,
					GROUP_CONCAT(E.nmrank SEPARATOR ', ') AS crew_ranks
				FROM 
					mstpersonal A
				LEFT JOIN 
					tblcontract B ON A.idperson = B.idperson
				LEFT JOIN 
					mstcmprec C ON C.kdcmp = B.kdcmprec
				LEFT JOIN 
					mstvessel D ON D.kdvsl = B.signonvsl 
				LEFT JOIN 
					mstrank E ON B.signonrank = E.kdrank
				WHERE 
					A.deletests = '0' 
					
					AND B.deletests = '0' 
					AND B.signoffdt = '0000-00-00' 
					AND A.inaktif = '0' 
					AND D.deletests = '0' 
					AND C.deletests = '0'
					AND D.nmvsl NOT IN (
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
					C.nmcmp, D.kdvsl, D.nmvsl
				ORDER BY 
					C.nmcmp, D.nmvsl";

		$result = $this->MCrewscv->getDataQuery($sql);

		$chartData = array();
		foreach ($result as $row) {
			$chartData[] = array(
				'client' => $row->ClientName,
				'ship' => $row->nama_kapal,
				'crew_count' => (int)$row->jumlah_crew_onboard,
				'male' => (int)$row->total_male,
				'female' => (int)$row->total_female,
				'avg_age' => round($row->rata_rata_umur, 1),
				'crew_names' => explode(', ', $row->crew_names),
				'crew_ranks' => explode(', ', $row->crew_ranks)
			);
		}

		echo json_encode($chartData);
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
				B.signondt AS SignOnDate
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
			ORDER BY 
				Month ASC, CrewName, EstimatedSignOffDate ASC;
		";

		$result = $this->MCrewscv->getDataQuery($sql);
		$data = array();

		$rankCounts = array();
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
		}

		header('Content-Type: application/json');
		print json_encode(array('crewData' => $data, 'rankSummary' => $rankCounts));
	}

	function shipDemograph()
	{
		$sql = "SELECT 
					D.kdvsl AS kode_kapal, 
					D.nmvsl AS nama_kapal, 
					COUNT(A.idperson) AS jumlah_crew_onboard,
					SUM(CASE WHEN A.gender = 'Male' THEN 1 ELSE 0 END) AS total_male,
					SUM(CASE WHEN A.gender = 'Female' THEN 1 ELSE 0 END) AS total_female,
					AVG(TIMESTAMPDIFF(YEAR, A.dob, CURDATE())) AS rata_rata_umur
				FROM 
					mstpersonal A
				LEFT JOIN 
					tblcontract B ON A.idperson = B.idperson
				LEFT JOIN 
					tblkota C ON A.pob = C.KdKota
				LEFT JOIN 
					mstvessel D ON D.kdvsl = B.signonvsl 
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
					D.kdvsl, D.nmvsl
				";
		
		$result = $this->MCrewscv->getDataQuery($sql);

		$data = array();
		$categories = array();
		$crewCounts = array();
		$maleCounts = array();
		$femaleCounts = array();
		$avgAges = array();
		$kdvslList = array();
		
		foreach ($result as $value) {
			$categories[] = $value->nama_kapal;
			$crewCounts[] = (int)$value->jumlah_crew_onboard;
			$maleCounts[] = (int)$value->total_male;
			$femaleCounts[] = (int)$value->total_female;
			$avgAges[] = round($value->rata_rata_umur, 1);
			$kdvslList[] = $value->kode_kapal; 

	
			$status = (int)$value->jumlah_crew_onboard >= 22 ? "Properly Manned" : "Under-Manned";
			$statuses[] = $status; 
		}

		$chartData = array();
		foreach ($result as $value) {
			$chartData[] = array(
				'nama_kapal' => $value->nama_kapal,
				'kode_kapal' => $value->kode_kapal,
				'jumlah_crew_onboard' => (int)$value->jumlah_crew_onboard,
				'total_male' => (int)$value->total_male,
				'total_female' => (int)$value->total_female,
				'rata_rata_umur' => round($value->rata_rata_umur, 1),
				'status' => (int)$value->jumlah_crew_onboard >= 22 ? "Properly Manned" : "Under-Manned",
			);
		}
		echo json_encode($chartData);
	}

	function getSchool() {
    
		$sql = "
			SELECT 
				T.namescl AS nama_sekolah,
				COUNT(B.idperson) AS jumlah_onboard,
				GROUP_CONCAT(
					CONCAT_WS(' ', A.fname, A.mname, A.lname, '(', R.nmrank, ')')
					ORDER BY A.fname SEPARATOR ', '
				) AS nama_crew_rank
			FROM tblscl T
			LEFT JOIN mstpersonal A ON T.idperson = A.idperson
			LEFT JOIN tblcontract B ON A.idperson = B.idperson
			LEFT JOIN mstrank R ON B.signonrank = R.kdrank
			WHERE 
				A.deletests = '0' 
				AND R.urutan > 0
				AND T.deletests = '0'
				AND B.deletests = '0'
				AND B.signoffdt = '0000-00-00' -- Hanya crew yang masih onboard
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
					'crew_details' => $row->nama_crew_rank
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
		$sql = "SELECT 
					RANK.kdrank, 
					RANK.nmrank, 
					COUNT(A.idperson) AS total_onleave,
					(SELECT COUNT(P.idperson)
					FROM mstpersonal P
					LEFT JOIN tblcontract Q ON P.idperson = Q.idperson
					WHERE 
						P.deletests = '0' AND 
						Q.deletests = '0' AND 
						Q.signoffdt = '0000-00-00' AND 
						P.inaktif = '0' AND 
						Q.signonrank = RANK.kdrank
					) AS total_onboard
				FROM 
					mstrank RANK
				LEFT JOIN tblcontract B ON RANK.kdrank = B.signonrank
				LEFT JOIN mstpersonal A ON A.idperson = B.idperson
				WHERE 
					RANK.deletests = '0' 
					AND urutan > 0
					AND RANK.nmrank != '' 
					AND A.deletests = '0' 
					AND B.deletests = '0' 
					AND A.inAktif = '0' 
					AND A.inBlacklist = '0' 
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
					RANK.urutan ASC
				LIMIT 25";

		$result = $this->MCrewscv->getDataQuery($sql);

		$data = array();
		foreach ($result as $row) {
			if ($row->total_onleave > 15) {
				$category = 'Strong';
				$color = 'green';
			} elseif ($row->total_onleave >= 11 && $row->total_onleave <= 15) {
				$category = 'Medium';
				$color = 'yellow';
			} else {
				$category = 'Low';
				$color = 'red';
			}

			$data[] = array(
				'rank' => $row->nmrank, 
				'total_onleave' => $row->total_onleave,
				'total_onboard' => $row->total_onboard,
				'category' => $category,
				'color' => $color
			);
		}

		header('Content-Type: application/json');
		echo json_encode($data);
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