<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Report extends CI_Controller {

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

	function getData($searchNya = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$whereNya = " WHERE deletests = '0' AND (fname != '' OR mname != '' OR lname != '') ";

		if($searchNya == "search")
		{
			$txtSearch = $_POST['txtSearch'];
			$whereNya .= " AND CONCAT(fname,' ',mname,' ',lname) LIKE '%".$txtSearch."%'";
		}

		$sql = " SELECT idperson,TRIM(CONCAT(fname,' ',mname,' ',lname)) AS fullName FROM mstpersonal ".$whereNya." ORDER BY fullName ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$lblName = "<b><i>:: ".$val->fullName." ::</i></b>";
				$btnAct = "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Refresh\" onclick=\"pickUpData('".$val->idperson."','".$lblName."');\">Pick Up</button>";
				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:left;\">".$val->fullName."</td>";
					$trNya .= "<td style=\"font-size:11px;text-align:center;\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}

		$dataOut['trNya'] = $trNya;
		if($searchNya == "")
		{
			$dataOut['optCompany'] = $dataContext->getCompanyByOption("","kode");
			$this->load->view('frontend/reportData',$dataOut);
		}else{
			print json_encode($dataOut);
		}
	}

	function navReport($idPerson = "",$kdCmp = "")
	{
		$dataContext = new DataContext();
		$rsl = $dataContext->getDataByReq("cvtype","mstcmprec","kdcmp = '".$kdCmp."'");

		if($rsl == "")
		{
			echo "CV EMPTY..!!";
		}else{
			if(strtoupper($rsl) == "ADNYANA")
			{
				$this->getDataCVAdnyana($idPerson,$kdCmp);
			}
			else if(strtoupper($rsl) == "STELLAR")
			{
				$this->getDataCVStellar($idPerson,$kdCmp);
			}
			else if(strtoupper($rsl) == "SUNTECHNO" || strtoupper($rsl) == 'SUNTECHNO KOREA')
			{
				$this->getDataCVSuntechno($idPerson,$kdCmp);
			}else{
				$this->getDataCVOtherForm($idPerson,$kdCmp);//report default AES
			}
		}
	}

	function getDataCVOtherForm($idPerson = "",$company = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dateNow = date("Y-m-d");
		$dateNowTime = date("Y-m-d h:i:s");

		$sql = "SELECT A.*,TRIM(CONCAT(A.fname,' ',A.mname,' ' ,A.lname)) AS fullName,B.NmKota,C.NmNegara
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				LEFT JOIN tblnegara C ON A.nationalid = C.KdNegara
				WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' " ;

		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$degreeNya = $dataContext->getDataByReq("degree","tbllang","idperson = '".$idPerson."' AND language LIKE '%english%' AND deletests = '0' ");
			if($degreeNya == "0" OR $degreeNya == "")
			{
				$degreeNya = "";
			}
			$dataOut['fullName'] = $rsl[0]->fullName;
			$dataOut['agama'] = $rsl[0]->religion;
			$dataOut['dob'] = $rsl[0]->NmKota.", ".$dataContext->convertReturnName($rsl[0]->dob);
			$dataOut['negara'] = $rsl[0]->NmNegara;
			$dataOut['maritalSt'] = $rsl[0]->maritalstsid;
			$dataOut['contactNo'] = $rsl[0]->mobileno;
			$dataOut['address'] = $rsl[0]->paddress;
			$dataOut['rank'] = $rsl[0]->applyfor;
			$dataOut['degree'] = $degreeNya;
			$dataOut['nextKin'] = $rsl[0]->famfullname;
			$dataOut['relKin'] = $rsl[0]->famrelateid;
			$dataOut['famtelp'] = $rsl[0]->famtelp;
			$dataOut['availDate'] = "";

			$dataCertDocCoc = $this->certDocReg($idPerson,"COC");
			$dataOut['cocName'] = "COC ".$dataCertDocCoc['name'];
			$dataOut['cocDocNo'] = $dataCertDocCoc['docNo'];
			$dataOut['cocIssPlace'] = $dataCertDocCoc['issPlace'];
			$dataOut['cocIssDate'] = $dataCertDocCoc['issDate'];
			$dataOut['cocExpDate'] = $dataCertDocCoc['expDate'];
			
			$dataCertDocEnd = $this->certDocReg($idPerson,"Endorsement");
			$dataOut['endorsName'] = "Endorsment";
			$dataOut['endorsDocNo'] = $dataCertDocEnd['docNo'];
			$dataOut['endorsIssPlace'] = $dataCertDocEnd['issPlace'];
			$dataOut['endorsIssDate'] = $dataCertDocEnd['issDate'];
			$dataOut['endorsExpDate'] = $dataCertDocEnd['expDate'];

			$dataOut['trIdDoc'] = $this->certDocDetail($idPerson,"idDocument");
			$dataOut['trCop'] = $this->certDocDetail($idPerson,"cop");
			$dataOut['trTankerCert'] = $this->certDocDetail($idPerson,"tankerCert");
			$dataOut['trSeaService'] = $this->getSeaServiceRecord($idPerson,"otherForm");

			$photo = $rsl[0]->pic;
			if($photo != "")
			{
				$photo = "<img src=\"".base_url('imgProfile/'.$photo)."\" style=\"width:90px;height:120px;\">";
			}

			$dataOut['photo'] = $photo;
		}
		
		$this->load->view("frontend/exportPersonalId",$dataOut);
	}

	function getDataCVAdnyana($idPerson = "",$company = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dateNow = date("Y-m-d");
		$dateNowTime = date("Y-m-d h:i:s");

		$sql = "SELECT A.*,TRIM(CONCAT(A.fname,' ',A.mname,' ' ,A.lname)) AS fullName,B.NmKota,C.NmNegara,D.NmKota As pKota
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				LEFT JOIN tblnegara C ON A.nationalid = C.KdNegara
				LEFT JOIN tblkota D ON A.pcity = D.KdKota
				WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' " ;
		
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$degreeNya = $dataContext->getDataByReq("degree","tbllang","idperson = '".$idPerson."' AND language LIKE '%english%' AND deletests = '0' ");
			if($degreeNya == "0" OR $degreeNya == "")
			{
				$degreeNya = "";
			}

			$ppNegara = "";
			$ppNo = "";
			$ppIssue = "";
			$ppValid = "";

			$rslPp = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND certname='passport' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1");
			if(count($rslPp) > 0)
			{
				$ppNegara = $rslPp[0]->nmnegara;
				$ppNo = $rslPp[0]->docno;
				$ppIssue = $dataContext->convertReturnName($rslPp[0]->issdate);
				$ppValid = $dataContext->convertReturnName($rslPp[0]->expdate);
			}

			$seaNegara = "";
			$seaNo = "";
			$seaIssue = "";
			$seaValid = "";

			$rslSea = $this->getCertAllByWhere("nmnegara NOT LIKE '%Panama%' AND idperson = '".$idPerson."' AND display='Y' AND certname='seaman book' AND deletests=0 ORDER BY idcertdoc DESC LIMIT 0,1");
			if(count($rslSea) > 0)
			{
				$seaNegara = $rslSea[0]->nmnegara;
				$seaNo = $rslSea[0]->docno;
				$seaIssue = $dataContext->convertReturnName($rslSea[0]->issdate);
				$seaValid = $dataContext->convertReturnName($rslSea[0]->expdate);
			}

			$mdNo = "";
			$mdIssue = "";
			$mdValid = "";

			$rslMedikal = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND certname='medical check up' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1");
			if(count($rslMedikal) > 0)
			{
				$mdNo = $rslMedikal[0]->docno;
				$mdIssue = $dataContext->convertReturnName($rslMedikal[0]->issdate);
				$mdValid = $dataContext->convertReturnName($rslMedikal[0]->expdate);
			}

			$pnmNo = "";
			$pnmIsse = "";
			$pnmValid = "";

			$rslPnm = $this->getCertAllByWhere("nmnegara LIKE '%Panama%' AND idperson = '".$idPerson."' AND display='Y' AND certname='seaman book' AND deletests=0 ORDER BY idcertdoc DESC LIMIT 0,1");
			if(count($rslPnm) > 0)
			{
				$pnmNo = $rslPnm[0]->docno;
				$pnmIsse = $dataContext->convertReturnName($rslPnm[0]->issdate);
				$pnmValid = $dataContext->convertReturnName($rslPnm[0]->expdate);
			}

			$gmdsNo = "";
			$gmdsIsse = "";
			$gmdsValid = "";

			$rslGmds = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND certname='gmdss' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1");
			if(count($rslGmds) > 0)
			{
				$gmdsNo = $rslGmds[0]->docno;
				$gmdsIsse = $dataContext->convertReturnName($rslGmds[0]->issdate);
				$gmdsValid = $dataContext->convertReturnName($rslGmds[0]->expdate);
			}

			$dataOut['fname'] = $rsl[0]->fname;
			$dataOut['mname'] = $rsl[0]->mname;
			$dataOut['lname'] = $rsl[0]->lname;
			$dataOut['pob'] = $rsl[0]->NmKota;
			$dataOut['dob'] = $dataContext->convertReturnName($rsl[0]->dob);
			$dataOut['negara'] = $rsl[0]->NmNegara;
			$dataOut['applyFor'] = $rsl[0]->applyfor;

			$dataOut['lowerRank'] = "No";
			if($rsl[0]->lower_rank == "1")
			{
				$dataOut['lowerRank'] = "Yes";
			}
			$dataOut['availDate'] = $dataContext->convertReturnName($rsl[0]->availdt);
			$cAddress = $rsl[0]->paddress;
			$cAddress .= "<br>City : ".$rsl[0]->pKota;
			$cAddress .= "<br>Phone : ".$rsl[0]->telpno;
			$dataOut['address'] = $cAddress;

			$docNya = "Passport : ".$ppNegara;
			$docNya .= "<br>Seaman's Book : ".$seaNegara;
			$docNya .= "<br>Medical Check Up";
			$docNya .= "<br>Panamanian";
			$docNya .= "<br>GMDSS";

			$numberNya = $ppNo;
			$numberNya .= "<br>".$seaNo;
			$numberNya .= "<br>".$mdNo;
			$numberNya .= "<br>".$pnmNo;
			$numberNya .= "<br>".$gmdsNo;
			
			$issDate = $ppIssue;
			$issDate .= "<br>".$seaIssue;
			$issDate .= "<br>".$mdIssue;
			$issDate .= "<br>".$pnmIsse;
			$issDate .= "<br>".$gmdsIsse;
			
			$validDate = $ppValid;
			$validDate .= "<br>".$seaValid;
			$validDate .= "<br>".$mdValid;
			$validDate .= "<br>".$pnmValid;
			$validDate .= "<br>".$gmdsValid;

			$hcchIssAutho = "National (Country)";
			$hcchIssAutho .= "<br>Endorsement COC";
			$hcchIssAutho .= "<br>Panamanian";
			$hcchIssAutho .= "<br>Singaporean";
			$hcchIssAutho .= "<br>Malaysian";
			$hcchIssAutho .= "<br>Others ()";
			$hcchNo = "";
			$HcchGrade = "";
			$hcchValid = "";
			$hcchPetroleum = "";
			$hcchChemical = "";
			$hcchGas = "";

			$natNo = "";
			$natGrade = "";
			$natValid = "";
			$rslNat = $this->getCertAllByWhere("nmnegara IN('indonesia','indonesian','national') AND idperson = '".$idPerson."' AND display='Y' AND license='COC' AND display='Y' AND deletests = 0 ORDER BY idcertdoc ASC LIMIT 0,1");
			if(count($rslNat) > 0)
			{
				$natNo = $rslNat[0]->docno;
				$natGrade = $rslNat[0]->dispname;
				if($rslNat[0]->expdate == "0000-00-00")
				{
					$natValid = "Permanent";
				}else{
					$natValid = $dataContext->convertReturnName($rslNat[0]->expdate);
				}
			}

			$endNo = "";
			$endGrade = "";
			$endValid = "";
			$rslEnd = $this->getCertAllByWhere("nmnegara IN('indonesia','indonesian','national') AND idperson = '".$idPerson."' AND display='Y' AND license='Endorsement' AND display='Y' AND deletests = 0 ORDER BY idcertdoc ASC LIMIT 0,1");
			if(count($rslEnd) > 0)
			{
				$endNo = $rslEnd[0]->docno;
				$endGrade = $rslEnd[0]->dispname;
				if($rslEnd[0]->expdate == "0000-00-00")
				{
					$endValid = "Permanent";
				}else{
					$endValid = $dataContext->convertReturnName($rslEnd[0]->expdate);
				}
			}

			$panNo = "";
			$panGrade = "";
			$panValid = "";
			$rslPan = $this->getCertAllByWhere("nmnegara IN('panama') AND idperson = '".$idPerson."' AND display='Y' AND license='COC' AND display='Y' AND deletests = 0 ORDER BY idcertdoc ASC LIMIT 0,1");
			if(count($rslPan) > 0)
			{
				$panNo = $rslPan[0]->docno;
				$panGrade = $rslPan[0]->dispname;
				if($rslPan[0]->expdate == "0000-00-00")
				{
					$panValid = "Permanent";
				}else{
					$panValid = $dataContext->convertReturnName($rslPan[0]->expdate);
				}
			}

			$singNo = "";
			$singGrade = "";
			$singValid = "";
			$rslSing = $this->getCertAllByWhere("nmnegara IN('singapore','singapura') AND idperson = '".$idPerson."' AND display='Y' AND license='COC' AND display='Y' AND deletests = 0 ORDER BY idcertdoc ASC LIMIT 0,1");
			if(count($rslSing) > 0)
			{
				$singNo = $rslSing[0]->docno;
				$singGrade = $rslSing[0]->dispname;
				if($rslSing[0]->expdate == "0000-00-00")
				{
					$singValid = "Permanent";
				}else{
					$singValid = $dataContext->convertReturnName($rslSing[0]->expdate);
				}
			}

			$malNo = "";
			$malGrade = "";
			$malValid = "";
			$rslMal = $this->getCertAllByWhere("nmnegara IN('malaysia','malaysian') AND idperson = '".$idPerson."' AND display='Y' AND license='COC' AND display='Y' AND deletests = 0 ORDER BY idcertdoc ASC LIMIT 0,1");
			if(count($rslMal) > 0)
			{
				$malNo = $rslMal[0]->docno;
				$malGrade = $rslMal[0]->dispname;
				if($rslMal[0]->expdate == "0000-00-00")
				{
					$malValid = "Permanent";
				}else{
					$malValid = $dataContext->convertReturnName($rslMal[0]->expdate);
				}
			}

			$otherNo = "";
			$otherGrade = "";
			$otherValid = "";
			$rslOther = $this->getCertAllByWhere("nmnegara NOT IN('indonesia','indonesian','national','panama','singapore','malaysia','malaysian') AND idperson = '".$idPerson."' AND display='Y' AND license='COC' AND display='Y' AND deletests = 0 ORDER BY idcertdoc ASC LIMIT 0,1");
			if(count($rslOther) > 0)
			{
				$hcchIssAutho .= " - ".$rslOther[0]->nmnegara;
				$otherNo = $rslOther[0]->docno;
				$otherGrade = $rslOther[0]->dispname;
				if($rslOther[0]->expdate == "0000-00-00")
				{
					$otherValid = "Permanent";
				}else{
					$otherValid = $dataContext->convertReturnName($rslOther[0]->expdate);
				}
			}

			$hcchNo = $natNo;
			$hcchNo .= "<br>".$endNo;
			$hcchNo .= "<br>".$panNo;
			$hcchNo .= "<br>".$singNo;
			$hcchNo .= "<br>".$malNo;
			$hcchNo .= "<br>".$otherNo;

			$HcchGrade = $natGrade;
			$HcchGrade .= "<br>".$endGrade;
			$HcchGrade .= "<br>".$panGrade;
			$HcchGrade .= "<br>".$singGrade;
			$HcchGrade .= "<br>".$malGrade;
			$HcchGrade .= "<br>".$otherGrade;

			$hcchValid = $natValid;
			$hcchValid .= "<br>".$endValid;
			$hcchValid .= "<br>".$panValid;
			$hcchValid .= "<br>".$singValid;
			$hcchValid .= "<br>".$malValid;
			$hcchValid .= "<br>".$otherValid;

			$perSurv = "";
			$basFireFig = "";
			$basMdcl = "";
			$humanRel = "";
			$profInSurv = "";
			$advFight = "";
			$mdclFirstAid = "";
			$mdclCare = "";
			$arpa = "";
			$radarSim = "";
			$gmdss = "";
			$bridgeRes = "";
			$tkrFam = "";
			$tkrSafetyPet = "";
			$tkrSafetyChm = "";
			$tkrSafetyLpg = "";
			$inertGas = "";
			$crudeOil = "";
			$shipHandling = "";
			$energyCons = "";
			$shipSecurity = "";
			$sdsd = "";
			$securityAwer = "";
			$gocOru = "";
			$ecDis = "";
			$engineRes = "";
			$shipHandlingAndmanuver = "";
			$shipSafetyOffice = "";

			$rsl1 = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND certname='basic safety training' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1");
			if(count($rsl1) > 0)
			{
				$perSurv = "checked='checked'";
				$basFireFig = "checked='checked'";
				$basMdcl = "checked='checked'";
				$humanRel = "checked='checked'";
			}else{
				$rsl1a = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND certname='personal survival' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1");
				if(count($rsl1a) > 0)
				{
					$perSurv = "checked='checked'";
				}
				$rsl1b = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND certname='fire fighting & fire prevention' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1");
				if(count($rsl1b) > 0)
				{
					$basFireFig = "checked='checked'";
				}
				$rsl1c = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND certname='elementary first aid' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1");
				if(count($rsl1c) > 0)
				{
					$basMdcl = "checked='checked'";
				}
				$rsl1d = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND certname='pssr' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1");
				if(count($rsl1d) > 0)
				{
					$humanRel = "checked='checked'";
				}
			}

			$rslAllCekCert = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND deletests = 0 ORDER BY idcertdoc DESC");
			if(count($rslAllCekCert) > 0)
			{
				foreach ($rslAllCekCert as $key => $val)
				{
					if(strtolower($val->certname) == strtolower("proficiency in survival craft"))
					{
						$profInSurv = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("advance fire fighting"))
					{
						$advFight = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("medical first aid"))
					{
						$mdclFirstAid = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("medical care"))
					{
						$mdclCare = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("arpa"))
					{
						$arpa = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("radar simulator"))
					{
						$radarSim = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("gmdss"))
					{
						$gmdss = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("bridge resource management"))
					{
						$bridgeRes = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("tanker familiarisation (oil)"))
					{
						$tkrFam = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("tanker safety (oil)"))
					{
						$tkrSafetyPet = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("tanker safety (chemical)"))
					{
						$tkrSafetyChm = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("tanker safety (gas)"))
					{
						$tkrSafetyLpg = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("inert gas system"))
					{
						$inertGas = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("crude oil washing"))
					{
						$crudeOil = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("ship handling"))
					{
						$shipHandling = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("energy conservation"))
					{
						$energyCons = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("ship security officer"))
					{
						$shipSecurity = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("security training for seafarer with designated security duties"))
					{
						$sdsd = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("ship security awareness training"))
					{
						$securityAwer = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("goc"))
					{
						$gocOru = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("ecdis (generic)"))
					{
						$ecDis = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("engine resource management"))
					{
						$engineRes = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("ship handling and manuvering"))
					{
						$shipHandlingAndmanuver = "checked='checked'";
					}
					if(strtolower($val->certname) == strtolower("ship safety officer"))
					{
						$shipSafetyOffice = "checked='checked'";
					}
				}
			}
			
			$dataOut['docNya'] = $docNya;
			$dataOut['numberNya'] = $numberNya;
			$dataOut['issDate'] = $issDate;
			$dataOut['validDate'] = $validDate;
			$dataOut['hcchIssAutho'] = $hcchIssAutho;
			$dataOut['hcchNo'] = $hcchNo;
			$dataOut['HcchGrade'] = $HcchGrade;
			$dataOut['hcchValid'] = $hcchValid;
			$dataOut['hcchPetroleum'] = $hcchPetroleum;
			$dataOut['hcchChemical'] = $hcchChemical;
			$dataOut['hcchGas'] = $hcchGas;
			$dataOut['trSeaService'] = $this->getSeaServiceRecord($idPerson,"adnyana");
			$dataOut['perSurv'] = $perSurv;
			$dataOut['basFireFig'] = $basFireFig;
			$dataOut['basMdcl'] = $basMdcl;
			$dataOut['humanRel'] = $humanRel;
			$dataOut['profInSurv'] = $profInSurv;
			$dataOut['advFight'] = $advFight;
			$dataOut['mdclFirstAid'] = $mdclFirstAid;
			$dataOut['mdclCare'] = $mdclCare;
			$dataOut['arpa'] = $arpa;
			$dataOut['radarSim'] = $radarSim;
			$dataOut['gmdss'] = $gmdss;
			$dataOut['bridgeRes'] = $bridgeRes;
			$dataOut['tkrFam'] = $tkrFam;
			$dataOut['tkrSafetyPet'] = $tkrSafetyPet;
			$dataOut['tkrSafetyChm'] = $tkrSafetyChm;
			$dataOut['tkrSafetyLpg'] = $tkrSafetyLpg;
			$dataOut['inertGas'] = $inertGas;
			$dataOut['crudeOil'] = $crudeOil;
			$dataOut['shipHandling'] = $shipHandling;
			$dataOut['energyCons'] = $energyCons;
			$dataOut['shipSecurity'] = $shipSecurity;
			$dataOut['sdsd'] = $sdsd;
			$dataOut['securityAwer'] = $securityAwer;
			$dataOut['gocOru'] = $gocOru;
			$dataOut['ecDis'] = $ecDis;
			$dataOut['engineRes'] = $engineRes;
			$dataOut['shipHandlingAndmanuver'] = $shipHandlingAndmanuver;
			$dataOut['shipSafetyOffice'] = $shipSafetyOffice;

			$photo = $rsl[0]->pic;
			if($photo != "")
			{
				$photo = "<img src=\"".base_url('imgProfile/'.$photo)."\" style=\"width:90px;height:120px;\">";
			}

			$dataOut['photo'] = $photo;
		}
		
		$this->load->view("frontend/exportPersonalIdAdnyana",$dataOut);
	}

	function getDataCVSuntechno($idPerson = "",$company = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dateNow = date("Y-m-d");
		$dateNowTime = date("Y-m-d h:i:s");

		$sql = "SELECT A.*,TRIM(CONCAT(A.fname,' ',A.mname,' ' ,A.lname)) AS fullName,B.NmKota,C.NmNegara,D.NmKota As pKota,DATE_FORMAT( FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)),'%y') AS age
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				LEFT JOIN tblnegara C ON A.nationalid = C.KdNegara
				LEFT JOIN tblkota D ON A.pcity = D.KdKota
				WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' " ;
		
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$photo = $rsl[0]->pic;
			if($photo != "")
			{
				$photo = "<img src=\"".base_url('imgProfile/'.$photo)."\" style=\"width:90px;height:120px;\">";
			}

			$dataOut['photo'] = $photo;
			$dataOut['dateNow'] = $dataContext->convertReturnName($dateNow);
			$dataOut['fullName'] = $rsl[0]->fullName;
			$dataOut['rank'] = $rsl[0]->applyfor;
			$dataOut['address'] = $rsl[0]->paddress;
			$dataOut['dob'] = $dataContext->convertReturnName($rsl[0]->dob);
			$dataOut['kotaLahir'] = $rsl[0]->NmKota;
			$dataOut['vesselFor'] = $rsl[0]->vesselfor;
			$dataOut['negara'] = $rsl[0]->NmNegara;
			$dataOut['maritalSt'] = $rsl[0]->maritalstsid;			
			$dataOut['contactNo'] = $rsl[0]->telpno;
			$dataOut['rank'] = $rsl[0]->applyfor;
			$dataOut['wght'] = $rsl[0]->wght." kg";
			$dataOut['hght'] = $rsl[0]->hght." cm";
			$dataOut['eyeColor'] = $rsl[0]->eyecol;
			$dataOut['shoesz'] = $rsl[0]->shoesz." mm";
			$dataOut['clothszid'] = $rsl[0]->clothszid;
			$dataOut['age'] = $rsl[0]->age;
			$dataOut['wname'] = $rsl[0]->wname;

			$dataOut['educationSun'] = $this->educationSun($idPerson);
			$dataOut['LicenseCert'] = $this->getLicenseDocSun($idPerson);
			$dataOut['certificates'] = $this->getCertDocSun($idPerson);
			$dataOut['otherCert'] = $this->getOtherCertSun($idPerson);
			$dataOut['getPhysical'] = $this->getPhysical($idPerson);
			$dataOut['getVaccine'] = $this->getVaccine($idPerson);
			$dataOut['getLanguage'] = $this->getLanguageEnglisSun($idPerson);

			$trIsm = "<tr>";
				$trIsm .= "<td align=\"center\" style=\"font-size:10px;border-left:1px;border-bottom:1px;border-right:1px;border-style:solid;\">1.Yes &nbsp;&nbsp;&nbsp;&nbsp; 2.No</td>";
				$trIsm .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$dataContext->convertReturnName($rsl[0]->ismdate)."</td>";
				$trIsm .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$rsl[0]->ismeval."</td>";
			$trIsm .= "</tr>";

			$dataOut['trIsm'] = $trIsm;
			$dataOut['yearyOpSun'] = $rsl[0]->yearoperat;
			$dataOut['yearyRankSun'] = $rsl[0]->yearrank;
			$dataOut['trSeaService'] = $this->getSeaServiceRecord($idPerson,"suntechno");
			$dataOut['teamLead'] = $this->detilperdir($idPerson);
		}

		
		$this->load->view("frontend/exportPersonalIdSuntechno",$dataOut);
	}

	function getDataCVStellar($idPerson = "",$company = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dateNow = date("Y-m-d");
		$dateNowTime = date("Y-m-d h:i:s");

		$sql = "SELECT A.*,TRIM(CONCAT(A.fname,' ',A.mname,' ' ,A.lname)) AS fullName,B.NmKota,C.NmNegara,D.docno AS docPassPort
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				LEFT JOIN tblnegara C ON A.nationalid = C.KdNegara
				LEFT JOIN tblpersonaldoc D ON D.idperson = A.idperson AND D.doctp = 'passport'
				WHERE A.deletests = '0' AND A.idperson = '".$idPerson."' " ;

		$rsl = $this->MCrewscv->getDataQuery($sql);
		//echo "<pre>";print_r($rsl);exit;
		if(count($rsl) > 0)
		{
			$degreeNya = $dataContext->getDataByReq("degree","tbllang","idperson = '".$idPerson."' AND language LIKE '%english%' AND deletests = '0' ");
			if($degreeNya == "0" OR $degreeNya == "")
			{
				$degreeNya = "";
			}

			$parents = $rsl[0]->fathernm;
			if($parents == "")
			{
				$parents = $rsl[0]->mothernm;
			}else{
				if($rsl[0]->mothernm != "")
				{
					$parents = $rsl[0]->fathernm." & ".$rsl[0]->mothernm;
				}
			}

			$dataOut['fullName'] = $rsl[0]->fullName;
			$dataOut['dob'] = $dataContext->convertReturnName($rsl[0]->dob);
			$dataOut['negara'] = $rsl[0]->NmNegara;
			$dataOut['placeOfBirth'] = $rsl[0]->NmKota;
			$dataOut['passPortNo'] = $rsl[0]->docPassPort;
			$dataOut['agama'] = $rsl[0]->religion;
			$dataOut['telpNo'] = $rsl[0]->telpno;
			$dataOut['maritalSt'] = $rsl[0]->maritalstsid;
			$dataOut['relKin'] = $rsl[0]->famrelateid;
			$dataOut['nextKin'] = $rsl[0]->famfullname;
			$dataOut['contactNo'] = $rsl[0]->mobileno;
			$dataOut['address'] = $rsl[0]->paddress;
			$dataOut['parents'] = $parents;
			$dataOut['rank'] = $rsl[0]->applyfor;
			$dataOut['typeOfCert'] = $this->getCertReg4($idPerson);

			$tempExpRank = $this->getExpLastPerson($idPerson);
			$dataOut['rankExp'] = $tempExpRank["rankExp"];
			$dataOut['trSeaService'] = $this->getSeaServiceRecord($idPerson,"stellar");
			$dataOut['lastCompany'] = $tempExpRank["cmpExp"];
			$dataOut['signDate'] = $dataContext->convertReturnBulanTglTahun($rsl[0]->signdt);

			
			$dataOut['famtelp'] = $rsl[0]->famtelp;
			$dataCertDocCoc = $this->certDocReg($idPerson,"COC");
			$dataOut['cocName'] = "COC ".$dataCertDocCoc['name'];
			$dataOut['cocDocNo'] = $dataCertDocCoc['docNo'];
			$dataOut['cocIssPlace'] = $dataCertDocCoc['issPlace'];
			$dataOut['cocIssDate'] = $dataCertDocCoc['issDate'];
			$dataOut['cocExpDate'] = $dataCertDocCoc['expDate'];			
			$dataCertDocEnd = $this->certDocReg($idPerson,"Endorsement");
			$dataOut['endorsName'] = "Endorsment";
			$dataOut['endorsDocNo'] = $dataCertDocEnd['docNo'];
			$dataOut['endorsIssPlace'] = $dataCertDocEnd['issPlace'];
			$dataOut['endorsIssDate'] = $dataCertDocEnd['issDate'];
			$dataOut['endorsExpDate'] = $dataCertDocEnd['expDate'];
			$dataOut['trIdDoc'] = $this->certDocDetail($idPerson,"idDocument");
			$dataOut['trCop'] = $this->certDocDetail($idPerson,"cop");
			$dataOut['trTankerCert'] = $this->certDocDetail($idPerson,"tankerCert");

			$dataOut['trDocPersonal'] = $this->getDocPersonalStellar($idPerson,"docPersonalStellar");

			$photo = $rsl[0]->pic;
			if($photo != "")
			{
				$photo = "<img src=\"".base_url('imgProfile/'.$photo)."\" style=\"width:90px;height:120px;border:1px ridge;\">";
			}

			$dataOut['photo'] = $photo;
		}
		
		$this->load->view("frontend/exportPersonalIdStellar",$dataOut);
	}

	function getDocPersonalStellar($idPerson = "")
	{
		$no = 1;
		$trNya = "";

		$labelTemp = array('Passport No'=>'passport',
							'Seaman Book'=>'seaman book',
							'Certificate of Competency'=>'COC',
							'Certificate of Endorsement'=>'Endorsement',
							'Radar Simulator'=>'radar simulator',
							'ARPA Simulator'=>'arpa',
							'GMDSS'=>'gmdss',
							'GOC / ORU'=>'goc',
							'Tanker Familiarization'=>'tanker familiarisation (oil)',
							'Oil Tankers Training'=>'tanker safety (oil)',
							'Liquefied Gas Tanker Training'=>'tanker safety (gas)',
							'Chemical Tanker STP'=>'tanker safety (chemical)',
							'Basic Safety Training'=>'basic safety training',
							'Survival Craft & Rescue Boat'=>'proficiency in survival craft',
							'Advance Fire Fighting'=>'advance fire fighting',
							'Medical Care'=>'medical care',
							'Medical First Aid'=>'medical first aid',
							'COE Endorsement MPA'=>'coe',
							'Advance Tanker Training'=>'',
							'V/I Endorsement STCW 95'=>'V/I endorsement STCW 95',
							'ISM Code'=>'ism',
							'Module I & II'=>'module I & II',
							'Pilotage Exemption Certificate'=>'pilotage exemption certificate',
							'Watch Keeping Certificate'=>'watchkeeping',
							'Bridge Team Management / BRM'=>'bridge',
							'Ship Security Officer'=>'ship security officer');

		foreach ($labelTemp as $key => $val)
		{
			if($val == "passport")
			{
				$tempData = $this->getDocPersonStellar($idPerson,$val);

				$trNya .= "<tr>";
					$trNya .= "<td style=\"width:20px;font-size:10px;border-left:1px;border-bottom:1px;border-style:solid;\">".$no."</td>";
					$trNya .= "<td style=\"width:150px;font-size:10px;border-left:1px;border-bottom:1px;border-style:solid;\">".$key."</td>";
					$trNya .= "<td style=\"width:150px;font-size:10px;border-left:1px;border-bottom:1px;border-style:solid;\">".$tempData['docNo']."</td>";
					$trNya .= "<td style=\"width:80px;font-size:10px;border-left:1px;border-bottom:1px;border-style:solid;\">".$tempData['issDate']."</td>";
					$trNya .= "<td style=\"width:80px;font-size:10px;border-left:1px;border-bottom:1px;border-style:solid;\">".$tempData['issPlace']."</td>";
					$trNya .= "<td style=\"width:80px;font-size:10px;border-left:1px;border-bottom:1px;border-style:solid;\" align=\"center\">".$tempData['expDate']."</td>";
					$trNya .= "<td style=\"width:80px;font-size:10px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;\" align=\"center\"></td>";
				$trNya .= "</tr>";
				$no++;
			}
		}
		
		return $trNya;
	}

	function getDocPersonStellar($idPerson = "",$certName = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$docNo = "";
		$expDate = "";
		$issDate = "";
		$issPlace = "";

		$sql = "SELECT * FROM tblcertdoc 
				WHERE idperson='".$idPerson."' AND certname = '".$certName."' AND display='Y' AND deletests=0 ORDER BY idcertdoc DESC limit 0,1";
		$rsl = $this->MCrewscv->getDataQuery($sql);
		// print_r($sql);exit;
		if(count($rsl) > 0)
		{
			$docNo = $rsl[0]->docno;
			$issPlace = $rsl[0]->issplace;

			if($rsl[0]->expdate == "0000-00-00")
			{
				$expDate = "Permanent";
			}else{
				$expDate = $dataContext->convertReturnName($rsl[0]->expdate);
			}

			if($rsl[0]->issdate == "0000-00-00")
			{
				$issDate = "";
			}else{
				$issDate = $dataContext->convertReturnName($rsl[0]->issdate);
			}
		}

		$dataOut['docNo'] = $docNo;
		$dataOut['expDate'] = $expDate;
		$dataOut['issDate'] = $issDate;
		$dataOut['issPlace'] = $issPlace;

		return $dataOut;
	}

	function getLicenseDocSun($idPerson = "")
	{
		$trNya = "";
		$tempData = array();
		$dataContext = new DataContext();

		$tempData['national'][0]['typeCert'] = "National License";
		$tempData['national'][0]['nameCert'] = "COC";
		$tempData['national'][1]['typeCert'] = "National License(GOC)";
		$tempData['national'][1]['nameCert'] = "GOC";
		$tempData['panama'][0]['typeCert'] = "Panama License";
		$tempData['panama'][0]['nameCert'] = "COC";
		$tempData['panama'][1]['typeCert'] = "Panama License(GOC)";
		$tempData['panama'][1]['nameCert'] = "GOC";
		$tempData['panama'][2]['typeCert'] = "Panama License(OT)";
		$tempData['panama'][2]['nameCert'] = "tanker safety (oil)";
		$tempData['panama'][3]['typeCert'] = "Panama License(CT)";
		$tempData['panama'][3]['nameCert'] = "tanker safety (chemical)";

		$certSsd = "ship security officer";

		$cekCertSSDSDSSAT = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND deletests = '0' AND nmnegara LIKE '%panama%' AND certname = 'security training for seafarer with designated security duties' ORDER BY idcertdoc DESC LIMIT 0,1");
		if(count($cekCertSSDSDSSAT) > 0)
		{
			$certSsd = "security training for seafarer with designated security duties";
		}

		$tempData['panama'][4]['typeCert'] = "Panama SSO/SD/SAT";
		$tempData['panama'][4]['nameCert'] = $certSsd;

		foreach ($tempData as $key => $val)
		{
			$whereNya = "";
			if($key == "national")
			{
				$whereNya = "idperson = '".$idPerson."' AND display='Y' AND deletests = '0' AND (nmnegara LIKE '%indonesia%' OR nmnegara LIKE '%national%')";
			}else{
				$whereNya = "idperson = '".$idPerson."' AND display='Y' AND deletests = '0' AND nmnegara LIKE '%panama%'";
			}
			
			foreach ($val as $keys => $value)
			{
				$certTemp = $this->getCertAllByWhere($whereNya." AND certname = '".$value['nameCert']."' ORDER BY idcertdoc DESC LIMIT 0,1");

				$nmRankTemp = "";
				$docNoTemp = "";
				$issDateTemp = "";
				$expDateTemp = "";

				$trNya .= "<tr>";
					$trNya .= "<td style=\"width:250px;font-size:10px;border-left:1px;border-bottom:1px;border-right:1px;border-style:solid;\">".$value['typeCert']."</td>";
				foreach ($certTemp as $keyCert => $valCert)
				{
					$nmRankTemp = $valCert->nmrank;
					$docNoTemp = $valCert->docno;
					$issDateTemp = $dataContext->convertReturnName($valCert->issdate);
					$expDateTemp = $dataContext->convertReturnName($valCert->expdate);
				}
					$trNya .= "<td align=\"center\" style=\"width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$nmRankTemp."</td>";
					$trNya .= "<td align=\"center\" style=\"width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$docNoTemp."</td>";
					$trNya .= "<td align=\"center\" style=\"width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$issDateTemp."</td>";
					$trNya .= "<td align=\"center\" style=\"width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$expDateTemp."</td>";
				$trNya .= "</tr>";
			}
		}

		return $trNya;
	}

	function getCertDocSun($idPerson = "")
	{
		$trNya = "";
		$tempData = array();
		$dataContext = new DataContext();


		$certTemp = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND deletests = '0' AND certgroup='PID' ORDER BY idcertdoc");

		foreach ($certTemp as $key => $value)
		{
			$trNya .= "<tr>";
				$trNya .= "<td style=\"width:200px;font-size:10px;border-left:1px;border-bottom:1px;border-right:1px;border-style:solid;\">".$value->dispname." (".$value->nmnegara.")</td>";
				$trNya .= "<td align=\"center\" style=\"width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$value->issplace."</td>";
				$trNya .= "<td align=\"center\" style=\"width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$value->docno."</td>";
				$trNya .= "<td align=\"center\" style=\"width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$dataContext->convertReturnName($value->issdate)."</td>";
				$trNya .= "<td align=\"center\" style=\"width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$dataContext->convertReturnName($value->expdate)."</td>";
			$trNya .= "</tr>";
		}

		return $trNya;
	}

	function getOtherCertSun($idPerson = "")
	{
		$trNya = "";
		$tempData = array();
		$dataContext = new DataContext();

		$certSsd = "ship security officer";

		$cekCertSSDSDSSAT = $this->getCertAllByWhere("idperson = '".$idPerson."' AND display='Y' AND deletests = '0' AND nmnegara LIKE '%panama%' AND certname = 'security training for seafarer with designated security duties' ORDER BY idcertdoc DESC LIMIT 0,1");
		if(count($cekCertSSDSDSSAT) > 0)
		{
			$certSsd = "security training for seafarer with designated security duties";
		}

		$otherCert1 = array(
					"Basic Safety Course"=>"basic safety training",
					"PSCRB"=>"proficiency in survival craft",
					"AFRC"=>"advance fire fighting",
					"MFARC"=>"medical first aid",
					"Tanker familiarization"=>"basic training for oil and chemical tanker cargo operations",
					"ACTC"=>"tanker safety (chemical)",
					"AOTC"=>"tanker safety (oil)",
					"RSC / APPA"=>"radar simulator",
					"RCOG"=>"");
		$otherCert2 = array(
					"BRM"=>"bridge resource management",
					"ERM"=>"engine resource management",
					"SHS"=>"ship handling",
					"ECDIS Generic"=>"ecdis (generic)",
					"ECDIS Specific(FURNO)"=>"ecdis (specific)",
					"SD/SSO"=> $certSsd,
					"SOTC"=>"safety officer",
					"MECA"=>"medical care",
					"Watch Keeping"=>"watchkeeping");

		while (list($key1, $value1) = each($otherCert1))
		{
			list($key2, $value2) = each($otherCert2);

			$docNo1 = "";
			$issDate1 = "";
			$expDate1 = "";
			$docNo2 = "";
			$issDate2 = "";
			$expDate2 = "";

			$whereNya1 = "idperson=".$idPerson." AND display='Y' AND deletests=0 AND certname = '".$value1."' AND nmnegara NOT LIKE '%panama%' ORDER BY idcertdoc DESC LIMIT 0,1";
			$dataTemp1 = $this->getCertAllByWhere($whereNya1);

			if(count($dataTemp1) > 0)
			{
				$docNo1 = $dataTemp1[0]->docno;
				$issDate1 = $dataContext->convertReturnName($dataTemp1[0]->issdate);
				$expDate1 = $dataContext->convertReturnName($dataTemp1[0]->expdate);
			}

			$whereNya2 = "idperson=".$idPerson." AND display='Y' AND deletests=0 AND certname = '".$value2."' AND nmnegara NOT LIKE '%panama%' ORDER BY idcertdoc DESC LIMIT 0,1";
			$dataTemp2 = $this->getCertAllByWhere($whereNya2);

			if(count($dataTemp2) > 0)
			{
				$docNo2 = $dataTemp2[0]->docno;
				$issDate2 = $dataContext->convertReturnName($dataTemp2[0]->issdate);
				$expDate2 = $dataContext->convertReturnName($dataTemp2[0]->expdate);
			}

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:10px;border-left:1px;border-bottom:1px;border-right:1px;border-style:solid;\">".$key1."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\" align=\"center\">".$docNo1."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\" align=\"center\">".$issDate1."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\" align=\"center\">".$expDate1."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$key2."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\" align=\"center\">".$docNo2."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\" align=\"center\">".$issDate2."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\" align=\"center\">".$expDate2."</td>";
			$trNya .= "</tr>";
		}

		return $trNya;
	}

	function getPhysical($idPerson = "")
	{
		$trNya = "";
		$tempData = array();
		$dataContext = new DataContext();

		$dataArr = array(
						"Medical cert."=>"physical inspection",
						"Chemical & D.O.A"=>"chemical contamination test after disembarkation vessel",
						"Yellow Fever"=>"yellow card");

		while (list($key1, $value1) = each($dataArr))
		{
			$issDate = "";
			$expDate = "";
			$remark = "";

			$whereNya = "idperson=".$idPerson." AND display='Y' AND deletests=0 AND certname = '".$value1."' ORDER BY idcertdoc DESC LIMIT 0,1";
			$dataTemp = $this->getCertAllByWhere($whereNya);

			if(count($dataTemp) > 0)
			{
				$remark = $dataTemp[0]->remarks;
				$issDate = $dataContext->convertReturnName($dataTemp[0]->issdate);
				$expDate = $dataContext->convertReturnName($dataTemp[0]->expdate);
			}

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:10px;border-left:1px;border-bottom:1px;border-right:1px;border-style:solid;\">".$key1."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\" align=\"center\">".$issDate."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\" align=\"center\">".$expDate."</td>";
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$remark."</td>";
			$trNya .= "</tr>";
		}

		return $trNya;
	}

	function getVaccine($idPerson = "")
	{
		$trNya = "";
		$tempData = array();
		$dataContext = new DataContext();
		$keyNo = 0;

		$sql = "SELECT * FROM tblvaccine WHERE deletests = '0' AND idperson = '".$idPerson."' ORDER BY vaccine_date DESC LIMIT 0,2 ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$tempData[strtolower($val->vaccine_name)][$keyNo]['dateVaccine'] = $val->vaccine_date;
			$tempData[strtolower($val->vaccine_name)][$keyNo]['remark'] = $val->remark;
			$keyNo++;
		}
		// echo "<pre>";print_r($tempData);exit;
		foreach ($tempData as $keys => $val)
		{
			$remark = "";
			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:10px;border-left:1px;border-bottom:1px;border-right:1px;border-style:solid;\">".ucfirst($keys)."</td>";
			krsort($val);
			foreach ($val as $key => $value)
			{
				$remark = $value['remark'];
				$trNya .= "<td align=\"center\" style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$dataContext->convertReturnName($value['dateVaccine'])."</td>";
			}
			if(count($val) <= 1)
			{
				$trNya .= "<td align=\"center\" style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\"></td>";
			}
				$trNya .= "<td style=\"font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;\">".$remark."</td>";
		
			$trNya .= "</tr>";
		}

		return $trNya;
	}

	function getExpLastPerson($idPerson = "")
	{
		$dataOut = array();

		$name = "";
		$rankExp = "";
		$cmpExp = "";

		$sql = "SELECT A.idperson,A.fname,B.rankexp, B.cmpexp
				FROM mstpersonal A
				LEFT JOIN tblseaexp B ON B.idperson = A.idperson
				WHERE A.idperson = '".$idPerson."' AND A.deletests = '0' GROUP BY A.fname";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$name = $rsl[0]->fname;
			$rankExp = $rsl[0]->rankexp;
			$cmpExp = $rsl[0]->cmpexp;
		}

		$dataOut['name'] = $name;
		$dataOut['rankExp'] = $rankExp;
		$dataOut['cmpExp'] = $cmpExp;

		return $dataOut;
	}

	function getCertReg4($idPerson = "")
	{
		$typeCertTemp = "";
		$sqlReg4 = "SELECT * FROM tblreg4 WHERE idperson = '".$idPerson."' AND rg4license = 'COC' AND Deletests = '0' ORDER BY rg4license ASC LIMIT 0,1";
		$rslReg4 = $this->MCrewscv->getDataQuery($sqlReg4);
		if(count($rslReg4) > 0)
		{
			$typeCertTemp = $rslReg4[0]->rg4doc;
		}
		return $typeCertTemp;
	}

	function certDocReg($idPerson = "",$license = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$name = "";
		$docNo = "";
		$expDate = "";
		$issDate = "";
		$issPlace = "";

		$sql = "SELECT idcertdoc,docno,CONCAT('- ',dispname) AS dispname,expdate,issdate,issplace
				FROM tblcertdoc 
				WHERE idperson='".$idPerson."' AND license = '".$license."' AND display='Y' AND deletests=0 ORDER BY idcertdoc ASC limit 0,1";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$docNo = $rsl[0]->docno;
			$name = $rsl[0]->dispname;
			$issPlace = $rsl[0]->issplace;

			if($rsl[0]->expdate == "0000-00-00")
			{
				$expDate = "Permanent";
			}else{
				$expDate = $dataContext->convertReturnName($rsl[0]->expdate);
			}

			if($rsl[0]->issdate == "0000-00-00")
			{
				$issDate = "";
			}else{
				$issDate = $dataContext->convertReturnName($rsl[0]->issdate);
			}
		}

		$dataOut['docNo'] = $docNo;
		$dataOut['name'] = $name;
		$dataOut['expDate'] = $expDate;
		$dataOut['issDate'] = $issDate;
		$dataOut['issPlace'] = $issPlace;

		return $dataOut;
	}

	function certDocDetail($idPerson = "",$type = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$docNo = "";
		$expDate = "";
		$issDate = "";
		$issPlace = "";
		$whereNya = "";
		$trNya = "";

		if($type == "idDocument")
		{
			$labelTemp = array('seaman book'=>'Seaman Book',
							'passport'=>'Passport',
							'yellow card'=>'Yellow Fiver',
							'thypoid'=>'Thypiod');
		}
		if($type == "cop")
		{
			$labelTemp = array('Basic Safety Training'=>'Basic Safety Training',
							'proficiency in survival craft'=>'Prof. Survival Craft & Rescue Boat',
							'advance fire fighting'=>'Advance Fire Fighting',
							'medical first aid'=>'Medical First Aid / Elementary',
							'medical care on board'=>'Medical Care On Board Ship',
							'bridge resource management'=>'Bridge Resource Management',
							'engine resource management'=>'Engine Room Management',
							'radar simulator'=>'Radar Simulator',
							'arpa'=>'Arpa Simulator',
							'gmdss'=>'GMDSS Radio Certificate',
							'goc'=>'General Operator Certificate (GOC)',
							'ism'=>'ISM Code',
							'ecdis (generic)'=>'ECDIS Training Programme',
							'ship security officer'=>'Ship Security Officer',
							'ship security awareness training'=>'Ship Security Awareness Training',
							'sdsd'=>'Seafarer With Designated Security Duties',
							// 'sdsd'=>'SEAFARER WITH DESIGNATED SECURITY DUTIES',
							'imdg code'=>'IMDG Code',
							'electrician certificate'=>'Electrician Certficate',
							'welder certificate'=>'Welder Certficate',
							'endorsement for cook'=>'Food Handling / Ship Cook Certificate',
							'mlc chief cook'=>'MLC Chief Cook',
							'ship handling and manuvering'=>'Ship Handling Manuevering Course',
							'ship board safety officer training'=>'Ship Board Safety Officer Training',
							'rating as able seafarer deck'=>'Rating As Able Seafarer Deck',
							'rating as able seafarer engine'=>'Rating As Able Seafarer Engine',
							'rating forming navigation & watchkeeping'=>'Rating Forming Part of A Navigation Watch',
							'rating forming part of watchkeeping engine room'=>'Rating Forming Part of A Watch in Engine Room');
		}
		if($type == "tankerCert")
		{
			$labelTemp = array('basic training for oil and chemical tanker cargo operations'=>'Basic Training For Oil And Chemical Tanker Cargo Operations',
							'tanker safety (oil)'=>'Advance Oil Tanker',
							'tanker safety (chemical)'=>'Advance Chemical Tanker',
							'tanker familiarisation (gas)'=>'Basic Liquid Gas Tanker',
							'tanker safety (gas)'=>'Advance Liquid Gas Tanker');
		}
		
		// if($type == "cop")
		// {
			// echo "<pre>";print_r($labelTemp);exit;
		// }
		
		foreach ($labelTemp as $key => $val)
		{
			$docNo = "";
			$issPlace = "";
			$expDate = "";
			$issDate = "";

			if($key != "")
			{
				$sql = "SELECT idcertdoc,docno,expdate,issdate,issplace
						FROM tblcertdoc 
						WHERE idperson='".$idPerson."' AND (certname = '".$key."' OR certname = '".$val."') AND display='Y' AND deletests=0 ORDER BY idcertdoc DESC limit 0,1";
				$rsl = $this->MCrewscv->getDataQuery($sql);

				if(count($rsl) > 0)
				{
					$docNo = $rsl[0]->docno;
					$issPlace = $rsl[0]->issplace;

					if($rsl[0]->issdate == "0000-00-00")
					{
						$issDate = "";
					}else{
						$issDate = $dataContext->convertReturnName($rsl[0]->issdate);
					}

					if($rsl[0]->expdate == "0000-00-00")
					{
						$expDate = "Permanent";
					}else{
						$expDate = $dataContext->convertReturnName($rsl[0]->expdate);
					}
				}
			}

			$trNya .= "<tr>";
				$trNya .= "<td style=\"width:265px;font-size:10px;border:1px solid black;\">".$val."</td>";
				$trNya .= "<td style=\"width:150px;font-size:10px;border:1px solid black;\">".$docNo."</td>";
				$trNya .= "<td style=\"width:150px;font-size:10px;border:1px solid black;\">".$issPlace."</td>";
				$trNya .= "<td style=\"width:90px;font-size:10px;border:1px solid black;\" align=\"center\">".$issDate."</td>";
				$trNya .= "<td style=\"width:90px;font-size:10px;border:1px solid black;\" align=\"center\">".$expDate."</td>";
			$trNya .= "</tr>";
			
		}

		return $trNya;
	}

	function getSeaServiceRecord($idPerson = "",$typeCV = "")
	{
		$dataContext = new DataContext();
		$trNya = "";

		$sql = "SELECT A.cmpexp,A.vslexp,A.flagexp,A.grtexp,A.dwtexp,A.hpexp,A.meexp,A.fmdtexp,A.todtexp,A.rankexp,A.reasonexp,B.DefType,B.NmType,ROUND( (DATEDIFF(A.todtexp,A.fmdtexp))/30, 2 ) AS reasonexpp
				FROM tblseaexp A
				LEFT JOIN tbltype B ON B.KdType = A.typeexp
				WHERE A.deletests = '0' AND B.deletests = '0' AND A.idperson='".$idPerson."' 
				ORDER BY A.todtexp DESC;" ;

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$fromDate = $dataContext->convertReturnName($val->fmdtexp);
			$toDate = $dataContext->convertReturnName($val->todtexp);

			if($typeCV == "otherForm")
			{
				$trNya .= "<tr>";
					$trNya .= "<td style=\"width:150px;font-size:9px;border:1px solid black;vertical-align:top;\">".$val->cmpexp."</td>";
					$trNya .= "<td style=\"width:130px;font-size:9px;border:1px solid black;vertical-align:top;\">".$val->vslexp."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border:1px solid black;vertical-align:top;\">".$val->DefType."</td>";
					$trNya .= "<td style=\"width:50px;font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$val->grtexp."</td>";
					$trNya .= "<td style=\"width:50px;font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$val->hpexp."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$fromDate."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$toDate."</td>";
					$trNya .= "<td style=\"width:60px;font-size:9px;border:1px solid black;vertical-align:top;\">".$val->rankexp."</td>";
					$trNya .= "<td style=\"width:60px;font-size:9px;border:1px solid black;vertical-align:top;\">".$val->reasonexp."</td>";
				$trNya .= "</tr>";
			}
			else if($typeCV == "adnyana")
			{
				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\">".$val->cmpexp."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\">".$val->vslexp."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\">".$val->flagexp."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\">".$val->NmType."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$val->grtexp."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$val->dwtexp."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$val->meexp."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$val->hpexp."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\">".$val->rankexp."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$fromDate."</td>";
					$trNya .= "<td style=\"font-size:9px;border:1px solid black;vertical-align:top;\" align=\"center\">".$toDate."</td>";
				$trNya .= "</tr>";
			}
			else if($typeCV == "stellar")
			{
				$trNya .= "<tr>";
					$trNya .= "<td style=\"width:120px;font-size:9px;border-left:1px;border-bottom:1px;border-style:solid;vertical-align:top;\">".$val->cmpexp."</td>";
					$trNya .= "<td style=\"width:100px;font-size:9px;border-left:1px;border-bottom:1px;border-style:solid;vertical-align:top;\">".$val->vslexp."</td>";
					$trNya .= "<td style=\"width:100px;font-size:9px;border-left:1px;border-bottom:1px;border-style:solid;vertical-align:top;\">".$val->DefType."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border-left:1px;border-bottom:1px;border-style:solid;vertical-align:top;\">".$val->meexp."</td>";
					$trNya .= "<td style=\"width:60px;font-size:9px;border-left:1px;border-bottom:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$val->grtexp."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border-left:1px;border-bottom:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$val->rankexp."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border-left:1px;border-bottom:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$fromDate."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border-left:1px;border-bottom:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$toDate."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border-left:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:top;\"></td>";
				$trNya .= "</tr>";
			}
			else if($typeCV == "suntechno")
			{
				$trNya .= "<tr>";
					$trNya .= "<td style=\"width:120px;font-size:9px;border-left:1px;border-right:1px;border-style:solid;vertical-align:top;\">".$val->vslexp."</td>";
					$trNya .= "<td style=\"width:100px;font-size:9px;border-right:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$val->DefType."</td>";
					$trNya .= "<td style=\"width:70px;font-size:9px;border-right:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$val->grtexp."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border-right:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$val->cmpexp."</td>";
					$trNya .= "<td style=\"width:60px;font-size:9px;border-right:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$fromDate."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border-right:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$val->reasonexp."</td>";
				$trNya .= "</tr>";
				$trNya .= "<tr>";
					$trNya .= "<td style=\"width:120px;font-size:9px;border-left:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:top;\" align=\"right\">".$val->flagexp."</label></td>";
					$trNya .= "<td style=\"width:100px;font-size:9px;border-right:1px;border-bottom:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$val->rankexp."</td>";
					$trNya .= "<td style=\"width:50px;font-size:9px;border-right:1px;border-bottom:1px;border-style:solid;vertical-align:top;\"></td>";
					$trNya .= "<td style=\"width:50px;font-size:9px;border-right:1px;border-bottom:1px;border-style:solid;vertical-align:top;\"></td>";
					$trNya .= "<td style=\"width:60px;font-size:9px;border-right:1px;border-bottom:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$dataContext->convertReturnName($val->todtexp)."</td>";
					$trNya .= "<td style=\"width:80px;font-size:9px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:top;\" align=\"center\">".$val->reasonexpp."</td>";
				$trNya .= "</tr>";
			}
		}


		return $trNya;
	}

	function detilperdir($idPerson = "")
	{
		$teamLead = "";

		$sql = " SELECT refpic FROM tblrefcmp WHERE refAktifsts='1' AND deletests='0' AND idperson='".$idPerson."' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$teamLead = $rsl[0]->refpic;
		}

		return $teamLead;
	}

	function educationSun($idPerson = "")
	{
		$trNya ="";

		$sql = "SELECT * FROM tblscl WHERE Deletests='0' AND idperson='".$idPerson."'" ;
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\" style=\"font-size:10px;vertical-align:top;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;\">".$val->yearscl."</td>";
				$trNya .= "<td style=\"font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;\">".$val->namescl."</td>";
				$trNya .= "<td style=\"font-size:10px;vertical-align:top;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;\">".$val->crsfin."</td>";
			$trNya .= "</tr>";
		}

		return $trNya;
	}

	function getLanguageEnglisSun($idPerson = "")
	{
		$trNya = "";

		$sql = "SELECT * FROM tbllang WHERE Deletests='0' AND idperson='".$idPerson."'" ;
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$teks5 = ($val->grade == "Excellent")?"(5)":"5";
			$teks4 = ($val->grade == "Good")?"(4)":"4";
			$teks3 = ($val->grade == "Acceptable")?"(3)":"3";
			$teks2 = ($val->grade == "Poor")?"(2)":"2";
			$teks1 = ($val->grade == "Unsuitable")?"(1)":"1";

			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\" style=\"width:200px;font-size:10px;vertical-align:top;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;\">".$val->language."</td>";
				$trNya .= "<td align=\"center\" style=\"width:540px;font-size:10px;vertical-align:top;border-right:1px;border-bottom:1px;border-style:solid;\">".$teks5." Excellent ".$teks4." Good ".$teks3." Acceptable ".$teks2." Poor ".$teks1." Unsuitable</td>";
			$trNya .= "</tr>";
		}
		
		return $trNya;
	}

	function getCertAllByWhere($whereNya = "")
	{
		$dataOut = array();

		$sql = "SELECT * FROM tblcertdoc WHERE ".$whereNya;
		$dataOut = $this->MCrewscv->getDataQuery($sql);

		return $dataOut;
	}

	function getCertDocByField($idPerson = "",$field = "",$whereNya = "")
	{
		$sql = "SELECT ".$field.", redsign FROM tblcertdoc WHERE idperson=".$idPerson." AND display='Y' AND deletests=0 ".$whereNya." ORDER BY idcertdoc DESC LIMIT 0,1";
		$dataOut = $this->MCrewscv->getDataQuery($sql);
		
		$valNya = $dataOut[0]->$field;
		return $valNya;
	}

	function printData($typeCert = "",$typeButton = "",$slcSearchType = "",$txtSearch = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dateNow = date("Y-m-d");
		$dateNowTime = date("Y-m-d h:i:s");
		$no = 1;
		$sql = "";
		$judulType = "";
		$teksInfoCert = "";
		$typeCertJudul = "";

		if($typeCert == "allCert")
		{
			$typeCertJudul = "ALL CERTIFICATES";

			$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND C.deletests = '0' AND A.expdate != '0000-00-00' AND C.signoffdt = '0000-00-00' AND TRIM(CONCAT(B.fname,' ',B.mname,' ',B.lname)) != '' ";

			if($typeButton == "passport")
			{
				$whereNya .= " AND A.kdcert = '0055' ";
			}
			if($typeButton == "seaman")
			{
				$whereNya .= " AND A.kdcert = '0056' ";
			}
			if($typeButton == "certificates")
			{
				$whereNya .= " AND A.kdcert != '0055' AND A.kdcert != '0056' AND A.kdnegara!='021' AND A.issplace NOT LIKE '%panama%' ";
			}
			if($typeButton == "panama")
			{
				$whereNya .= " AND A.kdcert != '0055' AND A.kdcert != '0056' AND (A.kdnegara ='021' OR A.issplace LIKE '%panama%') ";
			}

			if($txtSearch != "")
			{
				if($slcSearchType == "crew")
				{
					$whereNya .= " AND CONCAT(B.fname,' ',B.mname,' ',B.lname) LIKE '%".$txtSearch."%'";
				}

				if($slcSearchType == "cert")
				{
					$whereNya .= " AND A.certname LIKE '%".$txtSearch."%'";
				}

				if($slcSearchType == "country")
				{
					$whereNya .= " AND (A.nmnegara LIKE '%".$txtSearch."%' OR A.issplace LIKE '%".$txtSearch."%') ";
				}

				if($slcSearchType == "noDoc")
				{
					$whereNya .= " AND A.docno LIKE '%".$txtSearch."%' ";
				}

				if($slcSearchType == "expMonth")
				{
					if(strlen(trim($txtSearch)) == 1)
					{
						$txtSearch = "0".$txtSearch;
					}
					$whereNya .= " AND (DATE_FORMAT(A.expdate,'%m')='".$txtSearch."' OR DATE_FORMAT(A.expdate,'%M') LIKE '%".$txtSearch."%') ";
				}
			}

			$sql = "SELECT A.idcertdoc, A.idperson, A.kdcert, A.certname, A.kdnegara, A.nmnegara, A.docno, A.issdate, A.expdate, A.issplace, TRIM(CONCAT(B.fname,' ',B.mname,' ',B.lname)) AS fullName
					FROM tblcertdoc A
					LEFT JOIN mstpersonal B ON B.idperson = A.idperson
					LEFT JOIN tblcontract C ON A.idperson = C.idperson AND C.signoffdt = '0000-00-00'
					".$whereNya."
					ORDER BY A.expdate ASC";
			if($sql != "")
			{
				$rsl = $this->MCrewscv->getDataQuery($sql);
				if(count($rsl) > 0)
				{
					foreach ($rsl as $key => $val)
					{
						$bintang = "";
						$tampil = "No";
						$curDate = date("Ymd");
						$limaBelasBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$val->expdate), "-15"));
						$duaBelasBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$val->expdate), "-12"));
						$enamBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$val->expdate), "-6"));
						$satuBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$val->expdate), "-1"));

						$countryPlace = $val->nmnegara;
						if($countryPlace == "-")
						{
							$countryPlace = $val->issplace;
						}

						if($typeButton == "passport")
						{
							if($curDate >= $limaBelasBlnBelakang AND $curDate <= str_replace("-","",$val->expdate))
							{
								$bintang = "<span style=\"font-size:10px;color:red;\">*</span>";
								$tampil = "Yes";
							}
						}
						else if($typeButton == "seaman")
						{
							if($curDate >= $duaBelasBlnBelakang AND $curDate <= str_replace("-","",$val->expdate))
							{
								$bintang = "<span style=\"font-size:10px;\">*</span>";
								$tampil = "Yes";
							}
						}
						else if($typeButton == "certificates")
						{
							if($curDate >= $enamBlnBelakang AND $curDate <= str_replace("-","",$val->expdate))
							{
								$bintang = "<span style=\"font-size:10px;\">*</span>";
								$tampil = "Yes";
							}
						}
						else if($typeButton == "panama")
						{
							if($curDate >= $satuBlnBelakang AND $curDate <= str_replace("-","",$val->expdate))
							{
								$bintang = "<span style=\"font-size:10px;\">*</span>";
								$tampil = "Yes";
							}
						}

						if($curDate > str_replace("-","",$val->expdate))
						{
							$bintang = "<span style=\"font-size:10px;color:red;\">* *</span>";
							$tampil = "Yes";
						}

						if($tampil == "Yes")
						{
							$trNya .= "<tr>";
								$trNya .= "<td style=\"width:20px;font-size:10px;text-align:center;border:0.5px solid black;height:20px;vertical-align:top;\">".$no."</td>";
								$trNya .= "<td style=\"width:120px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".$val->fullName." ".$bintang."</td>";
								$trNya .= "<td style=\"width:190px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".$val->certname."</td>";
								$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".strtoupper($countryPlace)."</td>";
								$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".$val->docno."</td>";
								$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;text-align:center;vertical-align:top;\">".$dataContext->convertReturnName($val->issdate)."</td>";
								$trNya .= "<td style=\"width:100px;font-size:12px;border:0.5px solid black;text-align:center;vertical-align:top;\">".$dataContext->convertReturnName($val->expdate)."</td>";
							$trNya .= "<tr>";

							$no++;
						}
					}
				}
			}
		}
		else if($typeCert == "compCert")
		{
			$tempData = array();
			$typeCertJudul = "COMPLIANCE CERTIFICATES";
			
			if($typeButton == "passport" OR $typeButton == "seaman")
			{
				$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND C.deletests = '0' AND A.docexpdt != '0000-00-00' AND C.signoffdt = '0000-00-00' AND TRIM(CONCAT(B.fname,' ',B.mname,' ',B.lname)) != '' ";

				if($typeButton == "passport")
				{
					$whereNya .= " AND A.doctp LIKE '%passport%' ";
				}
				if($typeButton == "seaman")
				{
					$whereNya .= " AND A.doctp LIKE '%seaman%' ";
				}

				if($txtSearch != "")
				{
					if($slcSearchType == "crew")
					{
						$whereNya .= " AND CONCAT(B.fname,' ',B.mname,' ',B.lname) LIKE '%".$txtSearch."%'";
					}

					if($slcSearchType == "cert")
					{
						$whereNya .= " AND A.doctp LIKE '%".$txtSearch."%'";
					}

					if($slcSearchType == "country")
					{
						$whereNya .= " AND (D.nmnegara LIKE '%".$txtSearch."%' OR A.docissplc LIKE '%".$txtSearch."%') ";
					}

					if($slcSearchType == "noDoc")
					{
						$whereNya .= " AND A.docno LIKE '%".$txtSearch."%' ";
					}

					if($slcSearchType == "expMonth")
					{
						if(strlen(trim($txtSearch)) == 1)
						{
							$txtSearch = "0".$txtSearch;
						}
						$whereNya .= " AND (DATE_FORMAT(A.docexpdt,'%m')='".$txtSearch."' OR DATE_FORMAT(A.docexpdt,'%M') LIKE '%".$txtSearch."%') ";
					}
				}

				$sql = "SELECT A.idperdoc,A.idperson,A.doctp AS certname,A.docno,A.docissdt AS issdate,A.docexpdt AS expdate,A.docissplc AS issplace,TRIM(CONCAT(B.fname,' ',B.mname,' ',B.lname)) AS fullName,D.nmnegara
						FROM tblpersonaldoc A
						LEFT JOIN mstpersonal B ON B.idperson = A.idperson
						LEFT JOIN tblcontract C ON A.idperson = C.idperson AND C.signoffdt = '0000-00-00'
						LEFT JOIN tblnegara D ON A.docissctryid = D.KdNegara AND D.deletests = '0'
						".$whereNya."
						ORDER BY A.docexpdt ASC";

				if($sql != "")
				{
					$rsl = $this->MCrewscv->getDataQuery($sql);
					if(count($rsl) > 0)
					{
						foreach ($rsl as $key => $val)
						{
							$bintang = "";
							$tampil = "No";
							$curDate = date("Ymd");
							$limaBelasBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$val->expdate), "-15"));
							$duaBelasBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$val->expdate), "-12"));
							$enamBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$val->expdate), "-6"));
							$satuBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$val->expdate), "-1"));

							$countryPlace = $val->nmnegara;
							if($countryPlace == "-" OR $countryPlace == "")
							{
								$countryPlace = $val->issplace;
							}

							if($typeButton == "passport")
							{
								if($curDate >= $limaBelasBlnBelakang AND $curDate <= str_replace("-","",$val->expdate))
								{
									$bintang = "<span style=\"font-size:10px;color:red;\">*</span>";
									$tampil = "Yes";
								}
							}
							else if($typeButton == "seaman")
							{
								if($curDate >= $duaBelasBlnBelakang AND $curDate <= str_replace("-","",$val->expdate))
								{
									$bintang = "<span style=\"font-size:10px;\">*</span>";
									$tampil = "Yes";
								}
							}
							else if($typeButton == "certificates")
							{
								if($curDate >= $enamBlnBelakang AND $curDate <= str_replace("-","",$val->expdate))
								{
									$bintang = "<span style=\"font-size:10px;\">*</span>";
									$tampil = "Yes";
								}
							}
							else if($typeButton == "panama")
							{
								if($curDate >= $satuBlnBelakang AND $curDate <= str_replace("-","",$val->expdate))
								{
									$bintang = "<span style=\"font-size:10px;\">*</span>";
									$tampil = "Yes";
								}
							}

							if($curDate > str_replace("-","",$val->expdate))
							{
								$bintang = "<span style=\"font-size:10px;color:red;\">* *</span>";
								$tampil = "Yes";
							}

							if($tampil == "Yes")
							{
								$trNya .= "<tr>";
									$trNya .= "<td style=\"width:20px;font-size:10px;border:0.5px solid black;text-align:center;vertical-align:top;height:20px;\">".$no."</td>";
									$trNya .= "<td style=\"width:120px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".$val->fullName." ".$bintang."</td>";
									$trNya .= "<td style=\"width:190px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".$val->certname."</td>";
									$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".strtoupper($countryPlace)."</td>";
									$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".$val->docno."</td>";
									$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;text-align:center;\">".$dataContext->convertReturnName($val->issdate)."</td>";
									$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;text-align:center;\">".$dataContext->convertReturnName($val->expdate)."</td>";
								$trNya .= "<tr>";

								$no++;
							}
						}
					}
				}
			}
			else if($typeButton == "certificates" OR $typeButton == "panama")
			{
				$no = 1;		
				for ($lan=1; $lan <= 8; $lan++)
				{
					if($typeButton == "certificates")
					{
						$whereNya = " WHERE A.deletests = '0' AND A.rg".$lan."issctryid != '021' AND A.rg".$lan."expdt != '0000-00-00' AND D.signoffdt = '0000-00-00' ";
					}
					if($typeButton == "panama")
					{
						$whereNya = " WHERE A.deletests = '0' AND A.rg".$lan."issctryid = '021' AND A.rg".$lan."expdt != '0000-00-00' AND D.signoffdt = '0000-00-00' ";
					}

					if($txtSearch != "")
					{
						if($slcSearchType == "crew")
						{
							$whereNya .= " AND CONCAT(B.fname,' ',B.mname,' ',B.lname) LIKE '%".$txtSearch."%'";
						}
						if($slcSearchType == "cert")
						{
							$whereNya .= " AND A.rg".$lan."doc LIKE '%".$txtSearch."%'";
						}
						if($slcSearchType == "country")
						{
							$whereNya .= " AND (C.NmNegara LIKE '%".$txtSearch."%' OR A.rg".$lan."issplc LIKE '%".$txtSearch."%') ";
						}
						if($slcSearchType == "noDoc")
						{
							$whereNya .= " AND A.rg".$lan."docno LIKE '%".$txtSearch."%' ";
						}
						if($slcSearchType == "expMonth")
						{
							if(strlen(trim($txtSearch)) == 1)
							{
								$txtSearch = "0".$txtSearch;
							}
							$whereNya .= " AND (DATE_FORMAT(A.rg".$lan."expdt,'%m')='".$txtSearch."' OR DATE_FORMAT(A.rg".$lan."expdt,'%M') LIKE '%".$txtSearch."%') ";
						}
					}

					$sql = "SELECT A.*,TRIM(CONCAT(B.fname,' ',B.mname,' ',B.lname)) AS fullName,C.NmNegara
							FROM tblreg".$lan." A
							LEFT JOIN mstpersonal B ON B.idperson = A.idperson
							LEFT JOIN tblnegara C ON C.KdNegara = A.rg".$lan."issctryid AND C.deletests = '0'
							LEFT JOIN tblcontract D ON D.idperson = A.idperson AND D.deletests = '0'
							".$whereNya."
							ORDER BY rg".$lan."expdt ASC";
					$rsl = $this->MCrewscv->getDataQuery($sql);
						
					if(count($rsl) > 0)
					{
						$noIndex = 0;
						if($lan == "1"){ $tpeNya = "(A)"; }
						else if($lan == "2"){ $tpeNya = "(B)"; }
						else if($lan == "3"){ $tpeNya = "(C)"; }
						else if($lan == "4"){ $tpeNya = "(D)"; }
						else if($lan == "5"){ $tpeNya = "(E)"; }
						else if($lan == "6"){ $tpeNya = "(F)"; }
						else if($lan == "7"){ $tpeNya = "(G)"; }
						else if($lan == "8"){ $tpeNya = "(H)"; }
							
						foreach ($rsl as $key => $val)
						{
							$idReg = "idrg".$lan;
							$docName = "rg".$lan."doc";
							$docExpDate = "rg".$lan."expdt";
							$place = "rg".$lan."issplc";
							$docNo = "rg".$lan."docno";
							$docIssDate = "rg".$lan."issdt";
							$levelType = "";
							$para = "";

							if($lan == "7")
							{
								$levelType = "rg".$lan."lvltipe";
								$levelType = $val->$levelType;
								$para = "rg".$lan."para";
								$para = $val->$para;
							}

							$tempData[$tpeNya][$noIndex]['idReg'] = $val->$idReg;
							$tempData[$tpeNya][$noIndex]['idperson'] = $val->idperson;
							$tempData[$tpeNya][$noIndex]['docName'] = $val->$docName;
							$tempData[$tpeNya][$noIndex]['docNo'] = $val->$docNo;
							$tempData[$tpeNya][$noIndex]['fullName'] = $val->fullName;
							$tempData[$tpeNya][$noIndex]['docExpDate'] = $val->$docExpDate;
							$tempData[$tpeNya][$noIndex]['country'] = $val->NmNegara;
							$tempData[$tpeNya][$noIndex]['place'] = $val->$place;
							$tempData[$tpeNya][$noIndex]['docIssDate'] = $val->$docIssDate;
							$tempData[$tpeNya][$noIndex]['levelType'] = $levelType;
							$tempData[$tpeNya][$noIndex]['para'] = $para;

							$noIndex++;
						}
					}
				}
					
				foreach ($tempData as $key => $val)
				{
					foreach ($val as $keys => $value)
					{
						$bintang = "";
						$tampil = "No";
						$curDate = date("Ymd");

						$enamBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$value['docExpDate']), "-6"));
						$satuBlnBelakang = str_replace("-","",$dataContext->intervalBulan(str_replace("-","",$value['docExpDate']), "-1"));

						$countryPlace = $value['country'];
						if($countryPlace == "-" OR $countryPlace == "")
						{
							$countryPlace = $value['place'];
						}

						if($typeButton == "certificates")
						{
							if($curDate >= $enamBlnBelakang AND $curDate <= str_replace("-","",$value['docExpDate']))
							{
								$bintang = "<span style=\"font-size:10px;\">*</span>";
								$tampil = "Yes";
							}
						}

						if($typeButton == "panama")
						{
							if($curDate >= $satuBlnBelakang AND $curDate <= str_replace("-","",$value['docExpDate']))
							{
								$bintang = "<span style=\"font-size:10px;\">*</span>";
								$tampil = "Yes";
							}
						}

						if($curDate > str_replace("-","",$value['docExpDate']))
						{
							$bintang = "<span style=\"font-size:10px;color:red;\">* *</span>";
							$tampil = "Yes";
						}

						if($tampil == "Yes")
						{
							$docName = $key." ".$value['docName'];
							if($key == "(G)")
							{
								$docName = $key." ".$value['docName']." - (".$value['levelType'].") ".$value['para'];
							}

							$trNya .= "<tr>";
								$trNya .= "<td style=\"width:20px;font-size:10px;border:0.5px solid black;vertical-align:top;text-align:center;height:20px;\">".$no."</td>";
								$trNya .= "<td style=\"width:120px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".strtoupper($value['fullName'])." ".$bintang."</td>";
								$trNya .= "<td style=\"width:190px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".$docName."</td>";
								$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".strtoupper($countryPlace)."</td>";
								$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;\">".$value['docNo']."</td>";
								$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;text-align:center;\">".$dataContext->convertReturnName($value['docIssDate'])."</td>";
								$trNya .= "<td style=\"width:100px;font-size:10px;border:0.5px solid black;vertical-align:top;text-align:center;\">".$dataContext->convertReturnName($value['docExpDate'])."</td>";
							$trNya .= "<tr>";
							$no++;
						}
					}						
				}
			}
		}
		else if($typeCert == "")
		{
			$trNya .= "<tr>";
				$trNya .= "<td colspan=\"7\" align=\"center\"><b><i>:: Select Type Certificate ::</i></b></td>";
			$trNya .= "</tr>";
		}

		if($typeButton == "passport")
		{
			$judulType = "PASSPORT";
			$teksInfoCert = "15 Month before its expiry date";
		}
		if($typeButton == "seaman")
		{
			$judulType = "SEAMAN BOOK";
			$teksInfoCert = "12 Month before its expiry date";
		}
		if($typeButton == "certificates")
		{
			$judulType = "CERTIFICATES";
			$teksInfoCert = "6 Month before its expiry date";
		}
		if($typeButton == "panama")
		{
			$judulType = "PANAMA CERTIFICATES";
			$teksInfoCert = "1 Month before its expiry date";
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['judulType'] = $judulType;
		$dataOut['teksInfoCert'] = $teksInfoCert;
		$dataOut['typeCertJudul'] = $typeCertJudul;
		$dataOut['dateNow'] = $dataContext->convertReturnName($dateNow);

		$this->load->view("frontend/exportExpiredCert",$dataOut);
	}

	

}
