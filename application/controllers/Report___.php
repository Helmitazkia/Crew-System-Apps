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
		$whereNya = " WHERE deletests = '0' ";

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
			if(strtoupper($rsl) == "OTHERFORM")
			{
				$this->getDataCVOtherForm($idPerson,$kdCmp);
			}
			else if(strtoupper($rsl) == "ADNYANA")
			{
				$this->getDataCVAdnyana($idPerson,$kdCmp);
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
			$dataOut['trSeaService'] = $this->getSeaServiceRecord($idPerson);

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

			$sqlPp = "SELECT * FROM tblcertdoc WHERE idperson = '".$idPerson."' AND display='Y' AND certname='passport' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1";
			$rslPp = $this->MCrewscv->getDataQuery($sqlPp);
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

			$sqlSea = "SELECT * FROM tblcertdoc WHERE nmnegara NOT LIKE '%Panama%' AND idperson = '".$idPerson."' AND display='Y' AND certname='seaman book' AND deletests=0 ORDER BY idcertdoc DESC LIMIT 0,1";
			$rslSea = $this->MCrewscv->getDataQuery($sqlSea);
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

			$sqlMedikal = "SELECT * FROM tblcertdoc WHERE idperson = '".$idPerson."' AND display='Y' AND certname='medical check up' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1";
			$rslMedikal = $this->MCrewscv->getDataQuery($sqlMedikal);
			if(count($rslMedikal) > 0)
			{
				$mdNo = $rslMedikal[0]->docno;
				$mdIssue = $dataContext->convertReturnName($rslMedikal[0]->issdate);
				$mdValid = $dataContext->convertReturnName($rslMedikal[0]->expdate);
			}

			$pnmNo = "";
			$pnmIsse = "";
			$pnmValid = "";

			$sqlPnm = "SELECT * FROM tblcertdoc WHERE nmnegara LIKE '%Panama%' AND idperson = '".$idPerson."' AND display='Y' AND certname='seaman book' AND deletests=0 ORDER BY idcertdoc DESC LIMIT 0,1";
			$rslPnm = $this->MCrewscv->getDataQuery($sqlPnm);
			if(count($rslPnm) > 0)
			{
				$pnmNo = $rslPnm[0]->docno;
				$pnmIsse = $dataContext->convertReturnName($rslPnm[0]->issdate);
				$pnmValid = $dataContext->convertReturnName($rslPnm[0]->expdate);
			}

			$gmdsNo = "";
			$gmdsIsse = "";
			$gmdsValid = "";

			$sqlGmds = "SELECT * FROM tblcertdoc WHERE idperson = '".$idPerson."' AND display='Y' AND certname='medical check up' AND deletests = 0 ORDER BY idcertdoc DESC LIMIT 0,1";
			$rslGmds = $this->MCrewscv->getDataQuery($sqlGmds);
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

			$sqlNat = "SELECT * FROM tblcertdoc WHERE nmnegara IN('indonesia','indonesian','national') AND idperson = '".$idPerson."' AND display='Y' AND license='COC' AND deletests = 0 ORDER BY idcertdoc ASC LIMIT 0,1";
			$rslNat = $this->MCrewscv->getDataQuery($sqlNat);
			if(count($rslNat) > 0)
			{
				$hcchNo = $rslGmds[0]->docno;
				$gmdsIsse = $dataContext->convertReturnName($rslGmds[0]->issdate);
				$gmdsValid = $dataContext->convertReturnName($rslGmds[0]->expdate);
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



			$dataOut['agama'] = $rsl[0]->religion;			
			$dataOut['maritalSt'] = $rsl[0]->maritalstsid;
			$dataOut['contactNo'] = $rsl[0]->mobileno;
			$dataOut['rank'] = $rsl[0]->applyfor;
			$dataOut['degree'] = $degreeNya;
			$dataOut['nextKin'] = $rsl[0]->famfullname;
			$dataOut['relKin'] = $rsl[0]->famrelateid;
			$dataOut['famtelp'] = $rsl[0]->famtelp;

			$photo = $rsl[0]->pic;
			if($photo != "")
			{
				$photo = "<img src=\"".base_url('imgProfile/'.$photo)."\" style=\"width:90px;height:120px;\">";
			}

			$dataOut['photo'] = $photo;
		}
		
		$this->load->view("frontend/exportPersonalIdAdnyana",$dataOut);
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
							'sdsd'=>'Seafarer With Designtad Security Duties',
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

		foreach ($labelTemp as $key => $val)
		{
			$docNo = "";
			$issPlace = "";
			$expDate = "";
			$issDate = "";

			$sql = "SELECT idcertdoc,docno,expdate,issdate,issplace
					FROM tblcertdoc 
					WHERE idperson='".$idPerson."' AND certname = '".$key."' AND display='Y' AND deletests=0 ORDER BY idcertdoc DESC limit 0,1";
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

	function getSeaServiceRecord($idPerson = "")
	{
		$dataContext = new DataContext();
		$trNya = "";

		$sql = "SELECT A.cmpexp,A.vslexp,A.grtexp,A.hpexp,A.fmdtexp,A.todtexp,A.rankexp,A.reasonexp,B.DefType
				FROM tblseaexp A
				LEFT JOIN tbltype B ON B.KdType = A.typeexp
				WHERE A.deletests = '0' AND B.deletests = '0' AND A.idperson='".$idPerson."' 
				ORDER BY A.todtexp DESC;" ;

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$fromDate = $dataContext->convertReturnName($val->fmdtexp);
			$toDate = $dataContext->convertReturnName($val->todtexp);

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


		return $trNya;
	}

	function certDocPersonal($idPerson = "",$certName = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$docNo = "";
		$expDate = "";
		$issDate = "";
		$issPlace = "";
		$dataTemp = array();

		$dataTemp = array('Basic Safety Training'=>'Basic Safety Training',
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
						'sdsd'=>'Seafarer With Designtad Security Duties',
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

		echo "<pre>";
		print_r($dataTemp);exit;

		$sql = "SELECT idcertdoc,docno,expdate,issdate,issplace
				FROM tblcertdoc 
				WHERE idperson='".$idPerson."' AND certname = '".$certName."' AND display='Y' AND deletests=0 ORDER BY idcertdoc DESC limit 0,1";
		$rsl = $this->MCrewscv->getDataQuery($sql);

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
