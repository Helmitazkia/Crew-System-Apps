<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Contract extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

	function getDataCrewStatus($search = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dateNow = date("Y-m-d");
		$trNya = "";
		$sql = "";
		$no =1;
		$whereNya = "";
		
		if($search != "")
		{
			$status = $_POST['status'];
			$company = $_POST['company'];
			$vessel = $_POST['vessel'];
			$rank = $_POST['rank'];

			if($company != "017" AND $company != "")
			{
				$whereNya .= " AND A.kdcmprec='".$company."'";
			}

			if($vessel != "060" AND $vessel != "")
			{
				$whereNya .= " AND A.signonvsl='".$vessel."'";
			}

			if($rank != "060" AND $rank != "")
			{
				$whereNya .= " AND A.signonrank='".$rank."'";
			}

			if($status == "onboard"  OR $status == "all")
			{
				$tempTr = "";
				
				$no =1;

				$sql = "SELECT A.idcontract,A.idperson,TRIM(CONCAT(D.fname,' ',D.mname,' ' ,D.lname)) AS fullName,d.usecertdoc,D.dob,A.kdcmprec,A.signondt,A.signoffdt,A.estsignoffdt,A.signonrank,B.nmrank,A.signonvsl,C.nmvsl,A.lastvsl,A.signondesc,F.NmNegara
					FROM tblcontract A
					LEFT JOIN mstrank B ON B.kdrank = A.signonrank
					LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
					LEFT JOIN mstpersonal D ON D.idperson = A.idperson
					LEFT JOIN mstcmprec E ON E.kdcmp = A.kdcmprec
					LEFT JOIN tblnegara F ON F.KdNegara = D.nationalid
					WHERE A.signoffdt = '0000-00-00' AND A.kdcmprec = E.kdcmp AND A.signonvsl = C.kdvsl AND A.signonrank = B.kdrank AND D.noncrew = 0 AND A.idperson = D.idperson AND A.deletests = 0 AND D.inAktif = '0' AND D.inblacklist = '0' ".$whereNya."
					ORDER BY b.urutan ASC, fullName ASC";

				$rsl = $this->MCrewscv->getDataQuery($sql);
				foreach ($rsl as $key => $val)
				{
					$bintang = "";
					$warning = "";
				
					if($val->usecertdoc == "N")
					{
						$onclickNya = "viewDocument('".$val->idperson."','".$status."');";
					}else{
						$onclickNya = "viewDocument('".$val->idperson."','".$status."');";
					}

					$btnAct = "<button class=\"btn btn-primary btn-xs btn-block\" title=\"Sign Off\" onclick=\"getDataEditSignOff('".$val->idperson."','".$status."');\">Sign Off</button>";
					$btnAct .= "<button class=\"btn btn-info btn-xs btn-block\" title=\"Document\" onclick=\"".$onclickNya."\">Document</button>";
					$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Proses\" onclick=\"viewPersonalProses('".$val->idperson."');\">Proses</button>";
					
					$satuBlnKeBelakang = $dataContext->hitungSelisihByBulan($val->estsignoffdt,'-1');
					$tigaBlnKeBelakang = $dataContext->hitungSelisihByBulan($val->estsignoffdt,'-3');
					
					if($dateNow > $val->estsignoffdt)
					{
						$bintang = "<span style=\"font-weight:bold;font-size:12px;color:red;\">**</span>";

						$warning = $dataContext->hitungSelisihCompleteByHari($dateNow,$val->estsignoffdt);
						$warning = "<br><span style=\"font-size:10px;color:red;\">Expired over ".$warning."</span>";
					}

					if($val->kdcmprec == "001")
					{
						if($dateNow >= $satuBlnKeBelakang AND $dateNow <= $val->estsignoffdt)
						{
							$warning = $dataContext->hitungSelisihCompleteByHari($dateNow,$val->estsignoffdt);
							$warning = "<br><span style=\"font-size:10px;color:red;\">Expired In ".$warning."</span>";

							$bintang = "<span style=\"font-weight:bold;font-size:12px;color:red;\">*</span>";
						}
					}else{
						if($dateNow >= $tigaBlnKeBelakang AND $dateNow <= $val->estsignoffdt)
						{
							$warning = $dataContext->hitungSelisihCompleteByHari($dateNow,$val->estsignoffdt);
							$warning = "<br><span style=\"font-size:10px;color:blue;\">Expired In ".$warning."</span>";

							$bintang = "<span style=\"font-weight:bold;font-size:12px;color:red;\">*</span>";
						}
					}

					$tempTr .= "<tr>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;background-color:#5bc0de;\">".$no."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->nmrank."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->fullName."&nbsp".$bintang.$warning."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->NmNegara."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->dob)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->lastvsl."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->signondt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$btnAct."</td>";
					$tempTr .= "</tr>";

					$no++;
				}

				$trNya .= "<tr>";
					$trNya .= "<td style=\"background-color:#5bc0de;\" colspan=\"10\">";
						$trNya .= "<b><i>:: ON BOARD ::</i> ( ".number_format($no-1,0)." Data ) || </b>";
						$trNya .= "<label style=\"color:#E10303;\">* (Will expire), ** (Over due)</label>";
					$trNya .= "</td>";
				$trNya .= "</tr>";
				$trNya .= $tempTr;
			}
			if($status == "onleave"  OR $status == "all")
			{
				$tempTr = "";
				$no =1;

				$sql = "SELECT A.idcontract,A.idperson,D.inAktif,D.inBlacklist,TRIM(CONCAT(D.fname,' ',D.mname,' ' ,D.lname)) AS fullName,D.usecertdoc,D.dob,A.kdcmprec,A.signondt,A.signoffdt,A.estsignoffdt,A.signonrank,B.nmrank,A.signonvsl,C.nmvsl,A.lastvsl,A.signondesc,F.NmNegara
					FROM tblcontract A
					LEFT JOIN mstrank B ON B.kdrank = A.signonrank
					LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
					LEFT JOIN mstpersonal D ON D.idperson = A.idperson
					LEFT JOIN mstcmprec E ON E.kdcmp = A.kdcmprec
					AND A.idcontract IN (
							SELECT MAX(idcontract) AS idcontract
							FROM tblcontract
							WHERE deletests =0
							AND idperson = D.idperson
							)
					LEFT JOIN tblnegara F ON F.KdNegara = D.nationalid
					WHERE A.deletests = 0 AND A.signoffdt != '0000-00-00' AND A.signoffdt <= CURDATE() AND A.kdcmprec = E.kdcmp AND A.signonvsl = C.kdvsl AND A.signonrank = B.kdrank AND D.noncrew = 0 AND A.idperson = D.idperson AND D.inblacklist = '0' ".$whereNya."
					ORDER BY B.urutan ASC,fullName ASC";

				$rsl = $this->MCrewscv->getDataQuery($sql);
				foreach ($rsl as $key => $val)
				{
					if($val->usecertdoc == "N")
					{
						$onclickNya = "viewDocument('".$val->idperson."','".$status."');";
					}else{
						$onclickNya = "viewDocument('".$val->idperson."','".$status."');";
					}

					$btnAct = "<button class=\"btn btn-primary btn-xs btn-block\" onclick=\"getDataEditSignOff('".$val->idperson."','".$status."');\">Sign Off</button>";
					$btnAct .= "<button class=\"btn btn-info btn-xs btn-block\" onclick=\"".$onclickNya."\">Document</button>";
					$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Proses\" onclick=\"viewPersonalProses('".$val->idperson."');\">Proses</button>";

					$tempTr .= "<tr>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;background-color:#5CB85C;\">".$no."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->nmrank."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->fullName."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->NmNegara."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->dob)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->lastvsl."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->signondt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$btnAct."</td>";
					$tempTr .= "</tr>";

					$no++;
				}

				$trNya .= "<tr><td style=\"background-color:#5CB85C;color:#FFF;\" colspan=\"10\"><b><i>:: ON LEAVE ::</i> ( ".number_format($no-1,0)." Data ) </b></td></tr>";
				$trNya .= $tempTr;
			}
			if($status == "nonaktif" OR $status == "all")
			{
				$tempTr = "";
				$no =1;

				$sql = "SELECT A.idcontract,A.idperson,D.inAktif,D.inBlacklist,TRIM(CONCAT(D.fname,' ',D.mname,' ' ,D.lname)) AS fullName,D.usecertdoc,D.dob,A.kdcmprec,A.signondt,A.signoffdt,A.estsignoffdt,A.signonrank,B.nmrank,A.signonvsl,C.nmvsl,A.lastvsl,A.signondesc,F.NmNegara
						FROM tblcontract A
						LEFT JOIN mstrank B ON B.kdrank = A.signonrank
						LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
						LEFT JOIN mstpersonal D ON D.idperson = A.idperson
						LEFT JOIN mstcmprec E ON E.kdcmp = A.kdcmprec
						AND A.idcontract IN (
							SELECT MAX(idcontract) AS idcontract
							FROM tblcontract
							WHERE deletests =0
							AND idperson = D.idperson
							)
						LEFT JOIN tblnegara F ON F.KdNegara = D.nationalid
						WHERE A.deletests=0 AND A.signoffdt != '0000-00-00' AND A.signoffdt <= CURDATE()
						AND A.kdcmprec=E.kdcmp AND A.signonvsl=C.kdvsl AND A.signonrank=B.kdrank AND D.noncrew=0 AND A.idperson=D.idperson AND D.inblacklist = 0 AND D.inAktif = 1 ".$whereNya."
						GROUP BY A.idperson 
						ORDER BY B.urutan ASC, fullName ASC";

				$rsl = $this->MCrewscv->getDataQuery($sql);
				foreach ($rsl as $key => $val)
				{
					if($val->usecertdoc == "N")
					{
						$onclickNya = "viewDocument('".$val->idperson."','".$status."');";
					}else{
						$onclickNya = "viewDocument('".$val->idperson."','".$status."');";
					}

					$btnAct = "<button class=\"btn btn-primary btn-xs btn-block\" onclick=\"getDataEditSignOff('".$val->idperson."','".$status."');\">Sign Off</button>";
					$btnAct .= "<button class=\"btn btn-info btn-xs btn-block\" onclick=\"".$onclickNya."\">Document</button>";
					$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Proses\" onclick=\"viewPersonalProses('".$val->idperson."');\">Proses</button>";

					$tempTr .= "<tr>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;background-color:#F0AD4E;\">".$no."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->nmrank."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->fullName."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->NmNegara."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->dob)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->lastvsl."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->signondt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$btnAct."</td>";
					$tempTr .= "</tr>";

					$no++;
				}

				$trNya .= "<tr><td style=\"background-color:#F0AD4E;\" colspan=\"10\"><b><i>:: NON AKTIF ::</i> ( ".number_format($no-1,0)." Data ) </b></td></tr>";
				$trNya .= $tempTr;
			}
			if($status == "notforemp"  OR $status == "all")// blacklist
			{
				$tempTr = "";
				$no =1;

				$sql = "SELECT A.idcontract,A.idperson,D.inAktif,D.inBlacklist,TRIM(CONCAT(D.fname,' ',D.mname,' ' ,D.lname)) AS fullName,D.usecertdoc,D.dob,A.kdcmprec,A.signondt,A.signoffdt,A.estsignoffdt,A.signonrank,B.nmrank,A.signonvsl,C.nmvsl,A.lastvsl,A.signondesc,F.NmNegara
						FROM tblcontract A
						LEFT JOIN mstrank B ON B.kdrank = A.signonrank
						LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
						LEFT JOIN mstpersonal D ON D.idperson = A.idperson
						LEFT JOIN mstcmprec E ON E.kdcmp = A.kdcmprec
						AND A.idcontract IN (
							SELECT MAX(idcontract) AS idcontract
							FROM tblcontract
							WHERE deletests =0
							AND idperson = D.idperson
							)
						LEFT JOIN tblnegara F ON F.KdNegara = D.nationalid
						WHERE A.deletests=0 AND A.signoffdt != '0000-00-00' AND A.signoffdt <= CURDATE()
						AND A.kdcmprec=E.kdcmp AND A.signonvsl=C.kdvsl AND A.signonrank=B.kdrank AND D.noncrew=0 AND A.idperson=D.idperson AND D.inblacklist=1 ".$whereNya."
						GROUP BY A.idperson 
						ORDER BY B.urutan ASC, fullName ASC";

				$rsl = $this->MCrewscv->getDataQuery($sql);
				foreach ($rsl as $key => $val)
				{
					if($val->usecertdoc == "N")
					{
						$onclickNya = "viewDocument('".$val->idperson."','".$status."');";
					}else{
						$onclickNya = "viewDocument('".$val->idperson."','".$status."');";
					}

					$btnAct = "<button class=\"btn btn-primary btn-xs btn-block\" onclick=\"getDataEditSignOff('".$val->idperson."','".$status."');\">Sign Off</button>";
					$btnAct .= "<button class=\"btn btn-info btn-xs btn-block\" onclick=\"".$onclickNya."\">Document</button>";
					$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Proses\" onclick=\"viewPersonalProses('".$val->idperson."');\">Proses</button>";

					$tempTr .= "<tr>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;background-color:#D9534F;color:#FFF;\">".$no."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->nmrank."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->fullName."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->NmNegara."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->dob)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$val->lastvsl."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->signondt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:center;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
						$tempTr .= "<td style=\"font-size:11px;text-align:left;\">".$btnAct."</td>";
					$tempTr .= "</tr>";

					$no++;
				}

				$trNya .= "<tr><td style=\"background-color:#D9534F;color:#FFF;\" colspan=\"10\"><b><i>:: NOT FOR EMP ::</i> ( ".number_format($no-1,0)." Data ) </b></td></tr>";
				$trNya .= $tempTr;
			}			
			
		}else{
			$trNya = "<tr><td style=\"text-align:center;font-weight:bold;\" colspan=\"10\">- Select Status -</td></tr>";
		}

		$dataOut['trNya'] = $trNya;

		if($search == "")
		{
			$dataOut['optCompany'] = $dataContext->getCompanyByOption('','kode');
			$dataOut['optVessel'] = $dataContext->getVesselByOption('','kode');
			$dataOut['optRank'] = $dataContext->getRankByOption('','kode');
			$dataOut['optSignOffRemark'] = $dataContext->getSignOffRemarkByOption('','kode');

			$this->load->view('frontend/contractCrewStatus',$dataOut);
		}else{
			print json_encode($trNya);
		}		
	}

	function getData($idPerson = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$ref1 = "";
		$ref2 = "";

		$sql = "SELECT A.*,B.nmcmp,C.nmrank,D.nmvsl,E.nmremark
				FROM tblcontract A
				LEFT JOIN mstcmprec B ON B.kdcmp = A.kdcmprec AND B.deletests = '0'
				LEFT JOIN mstrank C ON C.kdrank = A.signonrank AND C.deletests = '0'
				LEFT JOIN mstvessel D ON D.kdvsl = A.signonvsl AND D.deletests = '0'
				LEFT JOIN mstremark E ON E.kdremark = A.signoffremark AND E.deletests = '0'
				WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ORDER BY A.idcontract DESC ";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "<button class=\"btn btn-success btn-xs btn-block\" onclick=\"getDataEdit('".$val->idcontract."');\" title=\"Edit Data\">Edit</button>";
			$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delData('".$val->idcontract."','".$idPerson."');\" title=\"Delete Data\">Del</button>";

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->nmcmp."</td>";
				$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$dataContext->convertReturnName($val->signondt)."</td>";
				$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->nmrank."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->nmvsl."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->signonport."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->signondesc."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->lastvsl."</td>";
				$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->estremark."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->no_pkl."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->nmremark."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['optCompany'] = $dataContext->getCompanyByOption("","kode");
		$dataOut['optRank'] = $dataContext->getRankByOption("","kode");
		$dataOut['optVessel'] = $dataContext->getVesselByOption("","kode");
		$dataOut['optSignOffRemark'] = $dataContext->getSignOffRemarkByOption("","kode");

		$this->load->view('frontend/contract',$dataOut);
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
		$idPersonRepl = $data['replacement'];
		$addNya = 0;
		$forNya = 0;

		try {
			
			if($data['addForCont'] == "add"){ $addNya = 1; }
			if($data['addForCont'] == "for"){ $forNya = 1; }

			if($idPersonRepl != "")
			{
				$dataIns['idcontractRepl'] = $idPersonRepl;
			}

			$dataIns['idperson'] = $idPerson;			
			$dataIns['kdcmprec'] = $data['company'];
			$dataIns['signondt'] = $data['signOnDate'];
			$dataIns['signoffdt'] = $data['signOffDate'];
			$dataIns['estsignoffdt'] = $data['estSignOffCont'];
			$dataIns['signonrank'] = $data['rank'];
			$dataIns['signonvsl'] = $data['vessel'];
			$dataIns['signoffremark'] = $data['signOffRemark'];
			$dataIns['estremark'] = $data['remark'];
			$dataIns['signonport'] = $data['port'];
			$dataIns['lastvsl'] = $data['lastVessel'];
			$dataIns['signondesc'] = $data['signOnDesc'];
			$dataIns['no_pkl'] = $data['txtNoPkl'];
			$dataIns['additional'] = $addNya;
			$dataIns['foreigncrew'] = $forNya;
			
			if($idEdit == "")
			{
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblcontract",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idcontract = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblcontract");
			}
			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function updateDataCrewStatus()
	{
		$dataContext = new DataContext();
		$data = $_POST;
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dataIns = array();		
		$dateNow = date("Ymd/h:i:s");
		$stData = "";
		$idEdit = $data['idEdit'];
		$idPerson = $data['idPerson'];
		$idPersonRepl = $data['replacement'];
		$addNya = 0;
		$forNya = 0;

		try {
			
			if($data['addForCont'] == "add"){ $addNya = 1; }
			if($data['addForCont'] == "for"){ $forNya = 1; }

			if($idPersonRepl != "")
			{
				$dataIns['idcontractRepl'] = $idPersonRepl;
			}

			$dataIns['idperson'] = $idPerson;
			$dataIns['signoffdt'] = $data['signOffDate'];
			$dataIns['signoffremark'] = $data['signOffRemark'];
			$dataIns['additional'] = $addNya;
			$dataIns['foreigncrew'] = $forNya;
			$dataIns['updusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idcontract = '".$idEdit."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataIns,"tblcontract");	
			
			$stData = "Save Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print json_encode($stData);
	}

	function getDataEdit()
	{
		$dataOut = array();

		$id = $_POST['id'];
		$idPerson = $_POST['idPerson'];

		$sql = "SELECT * FROM tblcontract WHERE deletests = '0' AND idcontract = '".$id."' AND idperson = '".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$signOnDate = $rsl[0]->signondt;
			$signOffDate = $rsl[0]->signoffdt;
			$estSignOffDate = $rsl[0]->estsignoffdt;

			if($signOnDate == "0000-00-00"){ $signOnDate = ""; }
			if($signOffDate == "0000-00-00"){ $signOffDate = ""; }
			if($estSignOffDate == "0000-00-00"){ $estSignOffDate = ""; }

			$dataOut['idEdit'] = $rsl[0]->idcontract;
			$dataOut['company'] = $rsl[0]->kdcmprec;
			$dataOut['signOnDate'] = $signOnDate;
			$dataOut['signOffDate'] = $signOffDate;
			$dataOut['estSignOffDate'] = $estSignOffDate;
			$dataOut['rank'] = $rsl[0]->signonrank;
			$dataOut['vessel'] = $rsl[0]->signonvsl;
			$dataOut['remark'] = $rsl[0]->signoffremark;
			$dataOut['estRemark'] = $rsl[0]->estremark;
			$dataOut['port'] = $rsl[0]->signonport;
			$dataOut['lastVessel'] = $rsl[0]->lastvsl;
			$dataOut['signOnDesc'] = $rsl[0]->signondesc;
			$dataOut['add'] = $rsl[0]->additional;
			$dataOut['for'] = $rsl[0]->foreigncrew;
			$dataOut['noPkl'] = $rsl[0]->no_pkl;
		}

		print json_encode($dataOut);
	}

	function getDataEditSignOff()
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$idPerson = $_POST['idPerson'];
		$status = $_POST['status'];
		$signOnVessel = "";
		$optReplacement = "";

		$sql = "SELECT A.*,B.nmcmp,C.nmrank,D.nmvsl,E.nmremark,TRIM(CONCAT(F.fname,' ',F.mname,' ' ,F.lname)) AS fullName
				FROM tblcontract A
				LEFT JOIN mstcmprec B ON B.kdcmp = A.kdcmprec AND B.deletests = '0'
				LEFT JOIN mstrank C ON C.kdrank = A.signonrank AND C.deletests = '0'
				LEFT JOIN mstvessel D ON D.kdvsl = A.signonvsl AND D.deletests = '0'
				LEFT JOIN mstremark E ON E.kdremark = A.signoffremark AND E.deletests = '0'
				LEFT JOIN mstpersonal F ON F.idperson = A.idperson
				WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' ORDER BY A.idcontract DESC ";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$btnAct = "";
			if($no == 1 AND $status == "onboard")
			{
				$btnAct = "<button class=\"btn btn-success btn-xs btn-block\" onclick=\"getDataEdit('".$val->idcontract."','".$idPerson."');\" title=\"Edit Data\">Edit</button>";
				$signOnVessel = $val->signonvsl;
			}

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$no."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->nmcmp."</td>";
				$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$dataContext->convertReturnName($val->signondt)."</td>";
				$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->nmrank."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->nmvsl."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->signonport."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->signondesc."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->lastvsl."</td>";
				$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->estremark."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->no_pkl."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$val->nmremark."</td>";
				$trNya .= "<td style=\"font-size:10px;\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['fullName'] = $rsl[0]->fullName;
		if($status == "onboard")
		{
			$optReplacement = $dataContext->getReplacementByOption($idPerson,$signOnVessel,"");
		}

		$dataOut['optReplacement'] = $optReplacement;

		print json_encode($dataOut);
	}

	function getDataDocument()
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$tempData = array();
		$trNya = "";
		$no = 1;
		$idPerson = $_POST['idPerson'];
		$signOnVessel = "";
		$optReplacement = "";

		// print_r($idPerson);exit;
		$fullName = $dataContext->getFullNameByIdPerson($idPerson);
		$useAllCertDoc = $dataContext->getDataByReq("usecertdoc","mstpersonal","idperson = '".$idPerson."'");

		if($useAllCertDoc == "N"){ $useAllCertDoc = "No"; } else { $useAllCertDoc = "Yes"; }

		if($useAllCertDoc == "Yes")
		{
			$sql = "SELECT * FROM tblcertdoc WHERE deletests = '0' AND idperson = '".$idPerson."' ORDER BY idcertdoc ASC ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-primary btn-xs btn-block\" title=\"View Document\" onclick=\"viewDocumentDetail('".$val->idcertdoc."');\"><i class=\"fa fa-eye\"></i> View</button>";
				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:10px;\">".$val->certgroup."</td>";
					$trNya .= "<td style=\"font-size:10px;\">".$val->certname."</td>";
					$trNya .= "<td style=\"font-size:10px;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}else{
			for ($lan=1; $lan <= 8 ; $lan++)
			{
				$slcNya = "";
				$dbNya = "tblreg".$lan;
				$whereNya = " WHERE deletests = '0' AND idperson = '".$idPerson."' ";
				$orderNya = " ORDER BY docexpdt ASC";

				$slcNya = "idrg".$lan." AS idRegDet,";

				if($lan == 1)
				{
					$slcNya .= "'(A)' AS certgroup";
				}
				else if($lan == 2)
				{
					$slcNya .= "'(B)' AS certgroup";
				}
				else if($lan == 3)
				{
					$slcNya .= "'(C)' AS certgroup";
				}
				else if($lan == 4)
				{
					$slcNya .= "'(D)' AS certgroup";
				}
				else if($lan == 5)
				{
					$slcNya .= "'(E)' AS certgroup";
				}
				else if($lan == 6)
				{
					$slcNya .= "'(F)' AS certgroup";
				}
				else if($lan == 7)
				{
					$slcNya .= "'(G)' AS certgroup";

					$whereNya .= " AND rg7issdt != '0000-00-00'";
				}
				else if($lan == 8)
				{
					$slcNya .= "'(H)' AS certgroup";
				}

				$slcNya .= ",rg".$lan."doc AS certname,DATE_FORMAT(rg".$lan."expdt,'%d-%m-%Y') AS docexpdt";				

				$sql = "SELECT ".$slcNya." FROM ".$dbNya.$whereNya.$orderNya;
				$rsl = $this->MCrewscv->getDataQuery($sql);

				if(count($rsl) > 0)
				{
					foreach ($rsl as $key => $val)
					{
						$tempData[$val->certgroup]['idRegDet'][] = $val->idRegDet;
						$tempData[$val->certgroup]['certname'][] = $val->certname;
						$tempData[$val->certgroup]['docexpdt'][] = $val->docexpdt;
						$tempData[$val->certgroup]['tblNo'][] = $lan;
					}
				}
			}

			foreach ($tempData as $key => $value)
			{
				for ($lan=0; $lan < count($value['certname']); $lan++)
				{
					$onclickNya = "viewDocumentDetailNo('".$value['idRegDet'][$lan]."','".$idPerson."','".$value['tblNo'][$lan]."')";

					$btnAct = "<button class=\"btn btn-primary btn-xs btn-block\" title=\"View Document\" onclick=\"".$onclickNya."\"><i class=\"fa fa-eye\"></i> View</button>";

					$trNya .= "<tr>";
						$trNya .= "<td style=\"font-size:10px;text-align:center;\">".$no."</td>";
						$trNya .= "<td style=\"font-size:10px;\">".$key."</td>";
						$trNya .= "<td style=\"font-size:10px;\">".strtoupper($value['certname'][$lan])."</td>";
						$trNya .= "<td style=\"font-size:10px;\">".$btnAct."</td>";
					$trNya .= "</tr>";

					$no++;
				}
			}
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['fullName'] = $fullName;
		$dataOut['useAllCertDoc'] = $useAllCertDoc;

		print json_encode($dataOut);
	}

	function getDataDetailDocument()
	{
		$dataContext = new DataContext();
		$dataOut = array();

		$id = $_POST['id'];

		$sql = "SELECT * FROM tblcertdoc WHERE deletests = '0' AND idcertdoc = '".$id."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			if($rsl[0]->redsign == "N"){ $redSign = "No"; }else{ $redSign = "Yes"; }
			if($rsl[0]->display == "N"){ $displayNya = "-"; }else{ $displayNya = "&radic;"; }

			$dataOut['certname'] = $rsl[0]->certname;
			$dataOut['display'] = $displayNya;
			$dataOut['license'] = $rsl[0]->license;
			$dataOut['level'] = $rsl[0]->level;
			$dataOut['nmrank'] = $rsl[0]->nmrank;
			$dataOut['vsltype'] = $rsl[0]->vsltype;
			$dataOut['nmnegara'] = $rsl[0]->nmnegara;
			$dataOut['docno'] = $rsl[0]->docno;
			$dataOut['issdate'] = $dataContext->convertReturnName($rsl[0]->issdate);
			$dataOut['expdate'] = $dataContext->convertReturnName($rsl[0]->expdate);
			$dataOut['issplace'] = $rsl[0]->issplace;
			$dataOut['issauth'] = $rsl[0]->issauth;
			$dataOut['remarks'] = $rsl[0]->remarks;
			$dataOut['redsign'] = $redSign;
		}

		print json_encode($dataOut);
	}

	function getDataDetailDocumentNo()
	{
		$dataContext = new DataContext();
		$dataOut = array();

		$idRegDet = $_POST['idRegDet'];
		$idPerson = $_POST['idPerson'];
		$tblNo = $_POST['tblNo'];

		$sql = "SELECT * FROM tblreg".$tblNo." WHERE deletests = '0' AND idperson = '".$idPerson."' AND idrg".$tblNo." = '".$idRegDet."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$fCname = "rg".$tblNo."doc";
			$docLicense = "";
			$docLevel = "";
			$nmRank = "";
			$vslType = "";
			$kdNegara = "rg".$tblNo."issctryid";
			$docNo = "rg".$tblNo."docno";
			$issDate = "rg".$tblNo."issdt";
			$expDate = "rg".$tblNo."expdt";
			$issPlace = "rg".$tblNo."issplc";
			$issAuth = "rg".$tblNo."issby";

			if($tblNo == "4")
			{
				$docLicense = $rsl[0]->rg4license;
				$nmRank = $dataContext->getDataByReq("nmrank","mstrank","deletests = '0' AND kdrank = '".$rsl[0]->kdrank."' ");
			}
			else if($tblNo == "7")
			{
				$docLevel = $rsl[0]->rg7lvl;
			}
			else if($tblNo == "8")
			{
				$vslType = $rsl[0]->rg8type;
			}

			$dataOut['certname'] = strtoupper($rsl[0]->$fCname);
			$dataOut['display'] = "&radic;";
			$dataOut['license'] = $docLicense;
			$dataOut['level'] = $docLevel;
			$dataOut['nmrank'] = $nmRank;
			$dataOut['vsltype'] = $vslType;
			$dataOut['nmnegara'] = $dataContext->getDataByReq("nmnegara","tblnegara","deletests = '0' AND KdNegara = '".$rsl[0]->$kdNegara."' ");
			$dataOut['docno'] = $rsl[0]->$docNo;
			$dataOut['issdate'] = $dataContext->convertReturnName($rsl[0]->$issDate);
			$dataOut['expdate'] = $dataContext->convertReturnName($rsl[0]->$expDate);
			$dataOut['issplace'] = $rsl[0]->$issPlace;
			$dataOut['issauth'] = $rsl[0]->$issAuth;
			$dataOut['remarks'] = "";
			$dataOut['redsign'] = "";
		}

		print json_encode($dataOut);
	}

	function printData($status = "",$company = "",$vessel = "",$rank = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dateNow = date("Y-m-d");
		$dateNowTime = date("Y-m-d h:i:s");
		$data = $_POST;
		$trNya = "";
		$whereNya = "";
		$sql = "";
		$no =1;
		$statusNya = "";

		if($company != "017" AND $company != "" AND $company != "-")
		{
			$whereNya .= " AND A.kdcmprec='".$company."'";
		}

		if($vessel != "060" AND $vessel != "" AND $vessel != "-")
		{
			$whereNya .= " AND A.signonvsl='".$vessel."'";
		}

		if($rank != "060" AND $rank != "" AND $rank != "-")
		{
			$whereNya .= " AND A.signonrank='".$rank."'";
		}

		if($status == "nonaktif" OR $status == "all")
		{
			$statusNya = "Non Aktif";
			$tempTr = "";
			$no =1;

			$sql = "SELECT A.idcontract,A.idperson,TRIM(CONCAT(D.fname,' ',D.mname,' ' ,D.lname)) AS fullName,D.dob,A.kdcmprec,A.signondt,A.signoffdt,A.estsignoffdt,B.nmrank,C.nmvsl,A.lastvsl,A.estremark
					FROM tblcontract A
					LEFT JOIN mstrank B ON B.kdrank = A.signonrank
					LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
					LEFT JOIN mstpersonal D ON D.idperson = A.idperson
					LEFT JOIN mstcmprec E ON E.kdcmp = A.kdcmprec
					AND A.idcontract IN (
						SELECT MAX(idcontract) AS idcontract
						FROM tblcontract
						WHERE deletests =0
						AND idperson = D.idperson
						)
					WHERE A.deletests=0 AND A.signoffdt != '0000-00-00' AND A.signoffdt <= CURDATE()
					AND A.kdcmprec=E.kdcmp AND A.signonvsl=C.kdvsl AND A.signonrank=B.kdrank AND D.noncrew=0 AND A.idperson=D.idperson AND D.inblacklist = 0 AND D.inAktif = 1 ".$whereNya."
					GROUP BY A.idperson 
					ORDER BY B.urutan ASC, fullName ASC";

			$rsl = $this->MCrewscv->getDataQuery($sql);
			foreach ($rsl as $key => $val)
			{
				$tempTr .= "<tr>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$no."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->nmrank."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->fullName."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->dob)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->hitungUmur($val->dob)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->nmvsl."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->lastvsl."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->signondt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->estremark."</td>";
				$tempTr .= "</tr>";

				$no++;
			}

			$trNya .= "<tr><td colspan=\"11\" style=\"height:25px; padding-left:10px;border:1px solid black;\"><b><i>:: NON AKTIF ::</i> ( ".number_format($no-1,0)." Data ) </b></td></tr>";
			$trNya .= $tempTr;
		}
		if($status == "notforemp"  OR $status == "all")// blacklist
		{
			$statusNya = "Not For Employee";
			$tempTr = "";
			$no =1;

			$sql = "SELECT A.idcontract,A.idperson,TRIM(CONCAT(D.fname,' ',D.mname,' ' ,D.lname)) AS fullName,D.dob,A.kdcmprec,A.signondt,A.signoffdt,A.estsignoffdt,B.nmrank,C.nmvsl,A.lastvsl,A.estremark
					FROM tblcontract A
					LEFT JOIN mstrank B ON B.kdrank = A.signonrank
					LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
					LEFT JOIN mstpersonal D ON D.idperson = A.idperson
					LEFT JOIN mstcmprec E ON E.kdcmp = A.kdcmprec
					AND A.idcontract IN (
						SELECT MAX(idcontract) AS idcontract
						FROM tblcontract
						WHERE deletests =0
						AND idperson = D.idperson
						)
					WHERE A.deletests=0 AND A.signoffdt != '0000-00-00' AND A.signoffdt <= CURDATE()
					AND A.kdcmprec=E.kdcmp AND A.signonvsl=C.kdvsl AND A.signonrank=B.kdrank AND D.noncrew=0 AND A.idperson=D.idperson AND D.inblacklist=1 ".$whereNya."
					GROUP BY A.idperson 
					ORDER BY B.urutan ASC, fullName ASC";

			$rsl = $this->MCrewscv->getDataQuery($sql);
			foreach ($rsl as $key => $val)
			{
				$tempTr .= "<tr>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$no."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->nmrank."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->fullName."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->dob)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->hitungUmur($val->dob)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->nmvsl."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->lastvsl."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->signondt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->estremark."</td>";
				$tempTr .= "</tr>";

				$no++;
			}

			$trNya .= "<tr><td colspan=\"11\" style=\"height:25px; padding-left:10px;border:1px solid black;\"><b><i>:: NOT FOR EMPLOYEE ::</i> ( ".number_format($no-1,0)." Data ) </b></td></tr>";
			$trNya .= $tempTr;
		}
		if($status == "onboard"  OR $status == "all")
		{
			$statusNya = "On Board";
			$tempTr = "";
			$bintang = "";
			$warning = "";
			$no =1;

			$sql = "SELECT A.idcontract,A.idperson,TRIM(CONCAT(D.fname,' ',D.mname,' ' ,D.lname)) AS fullName,D.dob,A.kdcmprec,A.signondt,A.signoffdt,A.estsignoffdt,B.nmrank,C.nmvsl,A.lastvsl,A.estremark
					FROM tblcontract A
					LEFT JOIN mstrank B ON B.kdrank = A.signonrank
					LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
					LEFT JOIN mstpersonal D ON D.idperson = A.idperson
					LEFT JOIN mstcmprec E ON E.kdcmp = A.kdcmprec
					WHERE A.signoffdt = '0000-00-00' AND A.kdcmprec = E.kdcmp AND A.signonvsl = C.kdvsl AND A.signonrank = B.kdrank AND D.noncrew = 0 AND A.idperson = D.idperson AND A.deletests = 0 AND D.inAktif = '0' AND D.inblacklist = '0' ".$whereNya."
					ORDER BY b.urutan ASC, fullName ASC";

			$rsl = $this->MCrewscv->getDataQuery($sql);
			foreach ($rsl as $key => $val)
			{
				$bintang = "";
				$satuBlnKeBelakang = $dataContext->hitungSelisihByBulan($val->estsignoffdt,'-1');
				$tigaBlnKeBelakang = $dataContext->hitungSelisihByBulan($val->estsignoffdt,'-3');
					
				if($dateNow > $val->estsignoffdt)
				{
					$bintang = "<span style=\"font-weight:bold;font-size:12px;color:red;\">**</span>";
				}else{
					if($val->kdcmprec == "001")
					{
						if($dateNow >= $satuBlnKeBelakang AND $dateNow <= $val->estsignoffdt)
						{
							$bintang = "<span style=\"font-weight:bold;font-size:12px;color:red;\">*</span>";
						}
					}else{
						if($dateNow >= $tigaBlnKeBelakang AND $dateNow <= $val->estsignoffdt)
						{
							$bintang = "<span style=\"font-weight:bold;font-size:12px;color:red;\">*</span>";
						}
					}
				}

				$tempTr .= "<tr>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$no."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->nmrank."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->fullName." ".$bintang."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->dob)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->hitungUmur($val->dob)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->nmvsl."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->lastvsl."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->signondt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->estremark."</td>";
				$tempTr .= "</tr>";

				$no++;
			}

			$trNya .= "<tr>";
				$trNya .= "<td colspan=\"11\" style=\"height:25px; padding-left:10px;border:1px solid black;\">";
					$trNya .= "<b><i>:: ON BOARD ::</i> ( ".number_format($no-1,0)." Data ) || </b>";
					$trNya .= "<label>* (Will expire), ** (Over due)</label>";
				$trNya .= "</td>";
			$trNya .= "</tr>";
			$trNya .= $tempTr;
		}
		if($status == "onleave"  OR $status == "all")
		{
			$statusNya = "On Leave";
			$tempTr = "";
			$no =1;

			$sql = "SELECT A.idcontract,A.idperson,TRIM(CONCAT(D.fname,' ',D.mname,' ' ,D.lname)) AS fullName,D.dob,A.kdcmprec,A.signondt,A.signoffdt,A.estsignoffdt,B.nmrank,C.nmvsl,A.lastvsl,A.estremark
					FROM tblcontract A
					LEFT JOIN mstrank B ON B.kdrank = A.signonrank
					LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
					LEFT JOIN mstpersonal D ON D.idperson = A.idperson
					LEFT JOIN mstcmprec E ON E.kdcmp = A.kdcmprec
					AND A.idcontract IN (
						SELECT MAX(idcontract) AS idcontract
						FROM tblcontract
						WHERE deletests =0
						AND idperson = D.idperson
						)
					WHERE A.deletests = 0 AND A.signoffdt != '0000-00-00' AND A.signoffdt <= CURDATE() AND A.kdcmprec = E.kdcmp AND A.signonvsl = C.kdvsl AND A.signonrank = B.kdrank AND D.noncrew = 0 AND A.idperson = D.idperson AND D.inblacklist = '0' ".$whereNya."
					ORDER BY B.urutan ASC,fullName ASC";

			$rsl = $this->MCrewscv->getDataQuery($sql);
			foreach ($rsl as $key => $val)
			{
				$tempTr .= "<tr>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$no."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->nmrank."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->fullName."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->dob)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->hitungUmur($val->dob)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->nmvsl."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->lastvsl."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->signondt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->signoffdt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:center;border:1px solid black;\">".$dataContext->convertReturnName($val->estsignoffdt)."</td>";
					$tempTr .= "<td style=\"font-size:11px;text-align:left;border:1px solid black;\">".$val->estremark."</td>";
				$tempTr .= "</tr>";

				$no++;
			}

			$trNya .= "<tr><td colspan=\"11\" style=\"height:25px; padding-left:10px;border:1px solid black;\"><b><i>:: ON LEAVE ::</i> ( ".number_format($no-1,0)." Data ) </b></td></tr>";
			$trNya .= $tempTr;
		}

		if($status == "all")
		{
			$statusNya = "All";
		}

		$dataOut['statusNya'] = strtoupper($statusNya);
		$dataOut['dateNow'] = $dataContext->convertReturnNameWithTime($dateNowTime);
		$dataOut['trNya'] = $trNya;

		// print_r($dataOut);exit;

		$this->load->view("frontend/exportContractCrew",$dataOut);
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

		$whereNya = "idcontract = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya,$dataDel,"tblcontract");

		$status = "Success..!!";
		
		print json_encode($status);
	}

	function hitungCalculate()
	{
		$data = $_POST;

		$blnSignOn = $data['dateSignOn'];
		$calculate = $data['calc'];

		$signOffDate = date('Y-m-d',strtotime($blnSignOn." +".$calculate." Months"));

		print json_encode($signOffDate);
	}


}
