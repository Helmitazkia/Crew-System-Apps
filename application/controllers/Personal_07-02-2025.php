<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// require_once('DataContext.php');
class Personal extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}

	function index()
	{
		$this->load->view('frontend/login');
		// $this->getData();
	}

	function getData($searchNya = "",$pageNya = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$btnProses = "";
		$limitNya = "";
		$dataOut["listPage"] = "";
		$display = "15";
		$dbSeaExp = "";

		$whereNya = " WHERE A.deletests = '0' AND (A.fname != '' OR A.mname != '' OR A.lname != '') ";

		if($searchNya == "search")
		{
			$txtSearch = $_POST['txtSearch'];
			$typeSearch = $_POST['typeSearch'];
			
			if($typeSearch == "id")
			{
				$whereNya .= " AND A.idperson = '".$txtSearch."' ";
			}
			else if($typeSearch == "name")
			{
				$whereNya .= " AND CONCAT(A.fname,' ',A.mname,' ',A.lname) LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "age")
			{
				$whereNya .= " AND (SUBSTRING(CURDATE(),1,4)-SUBSTRING(A.dob,1,4)) = '".$txtSearch."' ";
			}
			else if($typeSearch == "rank")
			{
				$whereNya .= " AND C.rankexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp C ON C.idperson = A.idperson";
			}
			else if($typeSearch == "applied")
			{
				$whereNya .= " AND A.applyfor LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "vessel")
			{
				$whereNya .= " AND C.vslexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp C ON C.idperson = A.idperson";
			}
			else if($typeSearch == "idPerson")
			{
				$whereNya .= " AND A.idperson = '".$txtSearch."' ";
			}
		}
		else if($searchNya == "searchByContract")
		{
			$whereNya .= " AND A.idperson LIKE '%".$pageNya."%' ";//parameter pagenNya di isi IdPerson dari proses by contract
		}

		if($searchNya == "" || $searchNya == "-")
		{
			$url = base_url('personal/getData/-/')."/";
			$sqlCount = "SELECT A.idperson FROM mstpersonal A LEFT JOIN tblkota B ON A.pob = B.KdKota ".$whereNya." GROUP BY A.idperson";
			$dataCount = $this->MCrewscv->getDataQuery($sqlCount);
			$dataPage = $this->getPaging(count($dataCount),$pageNya,$display,$url);
			$limitNya = $dataPage['limit'];
			$dataOut["listPage"] = $dataPage['listPage'];
			if($pageNya != "")
			{
				$no = ($pageNya-1) * $display + 1;
			}
		}

		$sql = "SELECT A.idperson,TRIM(CONCAT(A.fname,' ',A.mname,' ' ,A.lname)) AS fullName,A.applyfor,A.gender,A.religion,A.dob,CASE WHEN A.inAktif='0' THEN 'Aktif' WHEN A.inAktif='1' THEN 'Non Aktif' END AS inAktif,CASE WHEN A.lower_rank = 0 THEN 'No' WHEN A.lower_rank = 1 THEN 'Yes' END AS lowerrank,A.inBlacklist,B.NmKota,A.newapplicent
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				".$dbSeaExp."
				".$whereNya."
				GROUP BY A.idperson
				ORDER BY fullName ASC ".$limitNya;

		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$bgColor = "";
				$btnProses = "<button class=\"btn btn-primary btn-xs\" onclick=\"getDataProses('".$val->idperson."');\" title=\"Proses\">Proses</button>";

				$rowSt = $dataContext->cekPersonOnVessel($val->idperson);

				if($rowSt != 'onboard')
				{
					$btnProses .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delDataPersonal('".$val->idperson."');\" title=\"Delete\">Delete</button>";
				}

				if($val->newapplicent == "1")
				{
					$bgColor = "background-color:#B565ED;";
				}

				if($val->inAktif == "Non Aktif" && $val->inBlacklist == "1")
				{
					$bgColor = "background-color:#D9534F;";
				}
				else if($val->inAktif == "Non Aktif")
				{
					$bgColor = "background-color:#F0AD4E;";
				}
				else if($val->inBlacklist == "1")
				{
					$bgColor = "background-color:#D9534F;";
				}
				else if($rowSt == "onboard")
				{
					$bgColor = "background-color:#5BC0DE;";
				}else if($rowSt == "onleave")
				{
					$bgColor = "background-color:#5CB85C;";
				}

				$trNya .= "<tr>";
					$trNya .= "<td align=\"center\" style=\"font-size:11.5px;".$bgColor."\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">(".$val->idperson.") ".$val->fullName."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".strtoupper($val->applyfor)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->gender."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->religion."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".$val->NmKota.", ".$dataContext->convertReturnName($val->dob)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->inAktif."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->lowerrank."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$btnProses."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}

		$dataOut['typeDisplay'] = "all";
		$dataOut['trNya'] = $trNya;
		$dataOut['optCountry'] = $dataContext->getCountryByOption("","kode");
		$dataOut['optCity'] = $dataContext->getCityByOption("","kode");
		$dataOut['optMaritalStatus'] = $dataContext->getMaritalStatus();
		$dataOut['optReligion'] = $dataContext->getReligion();
		$dataOut['optVessel'] = $dataContext->getVesselByOption("","name");
		$dataOut['optRank'] = $dataContext->getRankByOption("","name");
		$dataOut['optBlood'] = $dataContext->getBloodType();
		$dataOut['optSize'] = $dataContext->getUkuran();

		if($searchNya == "search")
		{
			print json_encode($dataOut);			
		}else{
			$this->load->view('frontend/personal',$dataOut);
		}
	}

	function getPaging($countData = "",$pageNya = "",$display = "",$url = "")
	{
		$limitNya = array();
		$listPage = "";
		$count = $countData;
		$page = $pageNya;
		$sLimit = "0";
		$eLimit = $display;
		$ttlList = ceil($count/$display);
		$linkLast = $url.$ttlList;

		$listPage = "Total : ".number_format($count,0)." Data";
		if($page != "")
		{
			$sLimit = ($display * ($page -1));
			$eLimit = $display;
			$bfrPage = $page - 1;
			$aftPage = $page + 1;

			$linkBfr = $url.$bfrPage;
			$linkAft = $url.$aftPage;			

			$listPage .= "<nav>";
            	$listPage .= "<ul class=\"pagination pagination-sm\">";
            		$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".$url."\">First</a></li>";
	         	if($page == 2)
	         	{
	         		$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".$url."\">".$bfrPage."</a></li>";
	         		$listPage .= "<li class=\"page-item active\"><span class=\"page-link\">".$page."</span></li>";
	         	}else{	         		
	         		$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".$linkBfr."\">".$bfrPage."</a></li>";
	               	$listPage .= "<li class=\"page-item active\"><span class=\"page-link\">".$page."</span></li>";
	         	}
	                
	        	if($page < $ttlList)
	        	{
	              	$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".$linkAft."\">".$aftPage."</a></li>";
	              	if(($page + 1 ) < $ttlList)
	              	{
	              		$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".$linkLast."\">Last</a></li>";
	              	}	             	
	             }
           		$listPage .= "</ul>";
      		$listPage .= "</nav>";
		}else{
			$listPage .= "<nav>";
				$listPage .= "<ul class=\"pagination pagination-sm\">";
					$listPage .= "<li class=\"page-item disabled\"><span class=\"page-link\">First</span></li>";
				if($ttlList >= 3)
				{
					$ttlList = 3;
				}
				for ($lan=1; $lan <= $ttlList; $lan++)
				{
					if($lan == 1)
					{
						$listPage .= "<li class=\"page-item active\"><span class=\"page-link\">".$lan."</span></li>";
					}else{
						$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".$url.$lan."\">".$lan."</a></li>";
					}
				}
				if($ttlList > 2)
				{
					$listPage .= "<li class=\"page-item\"><a class=\"page-link\" href=\"".$linkLast."\">Last</a></li>";
				}
				$listPage .= "</ul>";
			$listPage .= "</nav>";
		}		
		$limitNya['limit'] = "LIMIT ".$sLimit.",".$eLimit;
		$limitNya['listPage'] = $listPage;
		return $limitNya;
	}

	function getDataProses()
	{
		$id = $_POST['id'];
		$type = $_POST['type'];
		$dataOut = array();
		$fullName = "";

		if($type == "editProses")
		{
			$tempRsl = array();
			$tempChck = array();
			$imgPic = "";
			$imgWages = "";
			$imgIntv = "";
			$imgEvaluation = "";
			$imgStatementForm = "";

			$sql = "SELECT * FROM mstpersonal WHERE deletests = '0' AND idperson = '".$id."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$fullName = $rsl[0]->fname." ".$rsl[0]->mname." ".$rsl[0]->lname;
				$tempRsl = array(	"txtFname" => $rsl[0]->fname,
									"txtMname" => $rsl[0]->mname,
									"txtLname" => $rsl[0]->lname,
									"slcCountryNational" => $rsl[0]->nationalid,
									"slcCountryOrigin" => $rsl[0]->ctryOfOrgn,
									"txtDate_DOB" => $rsl[0]->dob,
									"slcCityBirth" => $rsl[0]->pob,
									"slcMaritalStatus" => $rsl[0]->maritalstsid,
									"txtFatherName" => $rsl[0]->fathernm,
									"txtMotherName" => $rsl[0]->mothernm,
									"txtSosSecNumber" => $rsl[0]->ssn,
									"slcSosSecIssuingCountry" => $rsl[0]->ssnctryid,
									"txtTaxNumber" => $rsl[0]->ptn,
									"slcTaxIssCountry" => $rsl[0]->ptnctryid,
									"txtCesScore" => $rsl[0]->scorces,
									"txtmarlinTest" => $rsl[0]->scormarlintes,
									"txtDate_training" => $rsl[0]->ismdate,
									"txtEvaluation" => $rsl[0]->ismeval,
									"txtWifeName" => $rsl[0]->wname,
									"slcReligion" => $rsl[0]->religion,
									"slcRankApply" => $rsl[0]->applyfor,
									"slcVesselApply" => $rsl[0]->vesselfor,
									"txtDate_available" => $rsl[0]->availdt,
									"txtAddressPrimary" => $rsl[0]->paddress,
									"slcCity" => $rsl[0]->pcity,
									"slcNearestAirport" => $rsl[0]->pnrstport,
									"txtPostCode" => $rsl[0]->ppostcode,
									"slcCountry" => $rsl[0]->pctryid,
									"txtMobileNo" => $rsl[0]->mobileno,
									"txtHomeNo" => $rsl[0]->telpno,
									"txtFax" => $rsl[0]->faxno,
									"txtEmail" => $rsl[0]->email,
									"txtBankName" => $rsl[0]->bank_name,
									"txtAccNo" => $rsl[0]->norek,
									"txtAccountName" => $rsl[0]->norek_name,
									"slcBloodType" => $rsl[0]->golDrh,
									"txtEyeColor" => $rsl[0]->eyecol,
									"txtWeight" => $rsl[0]->wght,
									"txtHeight" => $rsl[0]->hght,
									"txtShoes" => $rsl[0]->shoesz,
									"txtCollar" => $rsl[0]->collar,
									"txtChest" => $rsl[0]->chest,
									"txtWaist" => $rsl[0]->waist,
									"txtInsLed" => $rsl[0]->Insdleg,
									"txtCap" => $rsl[0]->cap,
									"slcSizeClothes" => $rsl[0]->clothszid,
									"slcSizeSweater" => $rsl[0]->sweaterszid,
									"slcSizeBoilersuit" => $rsl[0]->boilerszid,
									"txtAnyAllergi" => $rsl[0]->alergy,
									"txtAdditionalRemark" => $rsl[0]->remarks,
									"txtSignPlace" => $rsl[0]->signplc,
									"txtDate_sign" => $rsl[0]->signdt,
									"txtNextOfKin" => $rsl[0]->next_of_kin
								);

				if($rsl[0]->pic != "")
				{
					$imgPic = "<img src=\"".base_url('imgProfile/'.$rsl[0]->pic)."\" style=\"width:100px;\">";
				}

				if($rsl[0]->file_statement_wages != "")
				{
					$imgWages = "<a href=\"".base_url('imgProfile/'.$rsl[0]->file_statement_wages)."\" target=\"_blank\" class=\"btn btn-success btn-xs btn-block\" style=\"margin-top:5px;\">View</a>";
					$imgWages .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delFileWages('".$rsl[0]->idperson."','".$rsl[0]->file_statement_wages."');\">Delete</button>";
				}

				if($rsl[0]->file_interview != "")
				{
					$imgIntv = "<a href=\"".base_url('imgProfile/'.$rsl[0]->file_interview)."\" target=\"_blank\" class=\"btn btn-success btn-xs btn-block\" style=\"margin-top:5px;\">View</a>";
					$imgIntv .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delFileIntvw('".$rsl[0]->idperson."','".$rsl[0]->file_interview."');\">Delete</button>";
				}
				
				if($rsl[0]->file_evaluation != "")
				{
					$imgEvaluation = "<a href=\"".base_url('imgProfile/'.$rsl[0]->file_evaluation)."\" target=\"_blank\" class=\"btn btn-success btn-xs btn-block\" style=\"margin-top:5px;\">View</a>";
					$imgEvaluation .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delFileEvaluation('".$rsl[0]->idperson."','".$rsl[0]->file_evaluation."');\">Delete</button>";
				}
				
				if($rsl[0]->file_statement != "")
				{
					$imgStatementForm = "<a href=\"".base_url('imgProfile/'.$rsl[0]->file_statement)."\" target=\"_blank\" class=\"btn btn-success btn-xs btn-block\" style=\"margin-top:5px;\">View</a>";
					$imgStatementForm .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delFileStatementForm('".$rsl[0]->idperson."','".$rsl[0]->file_statement."');\">Delete</button>";
				}

				$chkNonAktif = false;
				$chkBlackList = false;
				$chkNonCrew = false;
				$chkNewApp = false;
				$chkEmail = false;
				$chkFax = false;
				$chkMobilePhone = false;
				$chkHomePhone = false;
				$chkPost = false;
				$rdGender = "";
				$rdWillingLowerRank = "";
				$rdHeightPhobia = "";
				$rdFeel = "";

				if($rsl[0]->inAktif == "1"){ $chkNonAktif = true; }
				if($rsl[0]->inBlacklist == "1"){ $chkBlackList = true; }
				if($rsl[0]->noncrew == "1"){ $chkNonCrew = true; }
				if($rsl[0]->newapplicent == "1"){ $chkNewApp = true; }
				if($rsl[0]->conmthEmail == "1"){ $chkEmail = true; }
				if($rsl[0]->conmthFax == "1"){ $chkFax = true; }
				if($rsl[0]->conmthMob == "1"){ $chkMobilePhone = true; }
				if($rsl[0]->conmthHom == "1"){ $chkHomePhone = true; }
				if($rsl[0]->conmthPost == "1"){ $chkPost = true; }

				if($rsl[0]->gender == "Male")
				{
					$rdGender = "rdGenderMale";
				}else{
					$rdGender = "rdGenderFemale";
				}

				if($rsl[0]->lower_rank == "1")
				{
					$rdWillingLowerRank = "rdLowerRankYes";
				}else{
					$rdWillingLowerRank = "rdLowerRankNo";
				}

				if($rsl[0]->heightphob == "y")
				{
					$rdHeightPhobia = "rdHeightPhobiaYes";
				}else{
					$rdHeightPhobia = "rdHeightPhobiaNo";
				}

				if($rsl[0]->claustrophob == "y")
				{
					$rdFeel = "rdFeelClaustrophobicYes";
				}else{
					$rdFeel = "rdFeelClaustrophobicNo";
				}

				$tempChck = array(	"chkEmail" => $chkEmail,
									"chkFax" => $chkFax,
									"chkMobilePhone" => $chkMobilePhone,
									"chkHomePhone" => $chkHomePhone,
									"chkPost" => $chkPost,
									"chkNonAktif" => $chkNonAktif,
									"chkBlackList" => $chkBlackList,
									"chkNonCrew" => $chkNonCrew,
									"chkNewApp" => $chkNewApp,
									$rdGender => "checked",
									$rdWillingLowerRank => "checked",
									$rdHeightPhobia => "checked",
									$rdFeel => "checked"
								);
			}
			$dataOut['rslCheck'] = $tempChck;
			$dataOut['rslVal'] = $tempRsl;
			$dataOut['imgPic'] = $imgPic;
			$dataOut['imgWages'] = $imgWages;
			$dataOut['imgIntv'] = $imgIntv;
			$dataOut['imgEvaluation'] = $imgEvaluation;
			$dataOut['imgStatementForm'] = $imgStatementForm;
			$dataOut['fullName'] = trim(strtoupper($fullName));
		}
		else if($type == "editNominee")
		{
			$sql = "SELECT * FROM mstpersonal WHERE deletests = '0' AND idperson = '".$id."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['fullname'] = $rsl[0]->famfullname;
				$dataOut['relationship'] = $rsl[0]->famrelateid;
				$dataOut['gender'] = $rsl[0]->famgender;
				$dataOut['kodePos'] = $rsl[0]->famposcode;
				$dataOut['address'] = $rsl[0]->famaddrs;
				$dataOut['nationality'] = $rsl[0]->famntnid;
				$dataOut['city'] = $rsl[0]->famcityid;
				$dataOut['country'] = $rsl[0]->famctryid;
				$dataOut['email'] = $rsl[0]->famemail;
				$dataOut['telp'] = $rsl[0]->famtelp;
				$dataOut['mobile'] = $rsl[0]->fammobile;
			}
		}
		else if($type == "editDataFamily")
		{
			$idPerson = $_POST['idPerson'];

			$sql = "SELECT * FROM tblfamily WHERE deletests = '0' AND idfm = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idfm'] = $rsl[0]->idfm;
				$dataOut['relationship'] = $rsl[0]->fmrel;
				$dataOut['gender'] = $rsl[0]->fmsex;
				$dataOut['fmfname'] = $rsl[0]->fmfname;
				$dataOut['fmlname'] = $rsl[0]->fmlname;
				$dataOut['fmdob'] = $rsl[0]->fmdob;
				$dataOut['fmpassno'] = $rsl[0]->fmpassno;
				$dataOut['fmissdt'] = $rsl[0]->fmissdt;
				$dataOut['fmplc'] = $rsl[0]->fmplc;
				$dataOut['fmexpdt'] = $rsl[0]->fmexpdt;
				$dataOut['fmvisa'] = $rsl[0]->fmvisa;
			}
		}
		else if($type == "editAllCert")
		{
			$idPerson = $_POST['idPerson'];
			$btnFile = "";

			$sql = "SELECT * FROM tblcertdoc WHERE deletests = '0' AND idcertdoc = '".$id."' AND idperson = '".$idPerson."' ";
			$dataOut = $this->MCrewscv->getDataQuery($sql);

			$sqlPers = "SELECT usecertdoc FROM mstpersonal WHERE deletests = '0' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sqlPers);
			
			$dataOut['usecertdoc'] = $rsl[0]->usecertdoc;

			if($dataOut[0]->certificate_file != "")
			{
				$btnFile = "<a class=\"btn btn-info btn-xs btn-block\" href=\"".base_url('uploadCertificate')."/".$dataOut[0]->certificate_file."\" target=\"_blank\" title=\"View File\">View File</a>";
				$btnFile .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete File\" onclick=\"delFile('".$id."','".$dataOut[0]->certificate_file."');\">Delete</button>";
			}
			$dataOut['btnFile'] = $btnFile;
		}
		else if($type == "editPersonalId")
		{
			$idPerson = $_POST['idPerson'];
			$btnFile = "";

			$sql = "SELECT * FROM tblpersonaldoc WHERE deletests = '0' AND idperdoc = '".$id."' AND idperson = '".$idPerson."' ";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if(count($rsl) > 0)
			{
				$dataOut['idperdoc'] = $rsl[0]->idperdoc;
				$dataOut['issuePlace'] = $rsl[0]->docissplc;
				$dataOut['country'] = $rsl[0]->docissctryid;
				$dataOut['dateIssue'] = $rsl[0]->docissdt;
				$dataOut['dateValid'] = $rsl[0]->docexpdt;
				$dataOut['typeDoc'] = $rsl[0]->doctp;
				$dataOut['noDoc'] = $rsl[0]->docno;

				if($rsl[0]->doc_file != "")
				{
					$btnFile = "<a class=\"btn btn-info btn-xs btn-block\" href=\"".base_url('uploadFile')."/".$rsl[0]->doc_file."\" target=\"_blank\" title=\"View File\">View File</a>";
					$btnFile .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete File\" onclick=\"delFile('".$id."','".$rsl[0]->doc_file."');\">Delete</button>";
				}

				$dataOut['btnFile'] = $btnFile;
			}
		}
		print json_encode($dataOut);
	}

	function saveDataPersonal()
	{
		$dataContext = new DataContext();
		$userInit = $this->session->userdata('userInitCrewSystem');
		$data = $_POST;
		$dataIns = array();
		$idEdit = $data['idEdit'];
		$dir = "./imgProfile";
		$dateNow = date("Ymd/h:i:s");

		$dataIns['fname'] = $data['fname'];
		$dataIns['mname'] = $data['mname'];
		$dataIns['lname'] = $data['lname'];
		$dataIns['nationalid'] = $data['nationality'];
		$dataIns['ctryOfOrgn'] = $data['countryOriginal'];
		$dataIns['dob'] = $data['tanggalLahir'];
		$dataIns['pob'] = $data['placeBirth'];
		$dataIns['wght'] = $data['weight'];
		$dataIns['hght'] = $data['height'];
		$dataIns['shoesz'] = $data['shoes'];
		$dataIns['collar'] = $data['collar'];
		$dataIns['chest'] = $data['chest'];
		$dataIns['waist'] = $data['waist'];
		$dataIns['Insdleg'] = $data['insideLed'];
		$dataIns['cap'] = $data['cap'];
		$dataIns['clothszid'] = $data['clothesSize'];
		$dataIns['sweaterszid'] = $data['sweaterSize'];
		$dataIns['boilerszid'] = $data['boilerSuitSize'];
		$dataIns['maritalstsid'] = $data['maritalStatus'];
		$dataIns['gender'] = $data['gender'];
		$dataIns['wname'] = $data['wifeName'];
		$dataIns['religion'] = $data['agama'];
		$dataIns['paddress'] = $data['addressPrimary'];
		$dataIns['pcity'] = $data['city'];
		$dataIns['pnrstport'] = $data['nearestAirPort'];
		$dataIns['ppostcode'] = $data['kodePos'];
		$dataIns['pctryid'] = $data['country'];
		$dataIns['telpno'] = $data['homeNo'];
		$dataIns['faxno'] = $data['fax'];
		$dataIns['mobileno'] = $data['mobileNo'];
		$dataIns['email'] = $data['email'];
		$dataIns['bank_name'] = $data['txtBankName'];
		$dataIns['norek'] = $data['accountNo'];
		$dataIns['norek_name'] = $data['txtAccountName'];
		$dataIns['golDrh'] = $data['golDarah'];
		$dataIns['eyecol'] = $data['eyeColor'];
		$dataIns['ssn'] = $data['sosSecNo'];
		$dataIns['ssnctryid'] = $data['sosSecIssuingCountry'];
		$dataIns['ptn'] = $data['personalTaxNo'];
		$dataIns['ptnctryid'] = $data['personalTaxNoCountry'];
		$dataIns['scorces'] = $data['cesScore'];
		$dataIns['scormarlintes'] = $data['marlinTestScore'];
		$dataIns['ismdate'] = $data['dateTraining'];
		$dataIns['ismeval'] = $data['evaluation'];
		$dataIns['applyfor'] = $data['rankApply'];
		$dataIns['vesselfor'] = $data['vesselApply'];
		$dataIns['lower_rank'] = $data['WillingAcceptLowRank'];
		$dataIns['availdt'] = $data['dateAvailable'];

		if($data['contactEmail'] != "") { $dataIns['conmthEmail'] = $data['contactEmail']; }
		if($data['contactFax'] != "") { $dataIns['conmthFax'] = $data['contactFax']; }
		if($data['contactMobilePhone'] != "") { $dataIns['conmthMob'] = $data['contactMobilePhone']; }
		if($data['contactHomePhone'] != "") { $dataIns['conmthHom'] = $data['contactHomePhone']; }
		if($data['contactPost'] != "") { $dataIns['conmthPost'] = $data['contactPost']; }
		
		$dataIns['fathernm'] = $data['fatherName'];
		$dataIns['mothernm'] = $data['motherName'];
		$dataIns['heightphob'] = $data['heightPobia'];
		$dataIns['claustrophob'] = $data['feelClaustroPhobic'];
		$dataIns['alergy'] = $data['anyAllergy'];
		$dataIns['remarks'] = $data['additionalRemark'];
		$dataIns['signplc'] = $data['signPlace'];
		$dataIns['signdt'] = $data['signDate'];
		$dataIns['inAktif'] = $data['nonAktif'];
		$dataIns['inBlacklist'] = $data['blacklist'];
		$dataIns['noncrew'] = $data['nonCrew'];
		$dataIns['newapplicent'] = $data['newApplicent'];
		$dataIns['next_of_kin'] = $data['nextOfKin'];
		$dataIns['deletests'] = "0";
		
		try {
			if($idEdit == "")// save data
			{
				$idPerson = $dataContext->getNewIdPerson("");

				if($data['cekFileUpload'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileUpload"]["name"];
					$newFileName = "pic_".$idPerson;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileUpload"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['pic'] = $fileUploadNya;
				}

				if($data['cekFileWages'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileStatementWages"]["name"];
					$newFileName = "statementOfWages_".$idPerson;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileStatementWages"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['file_statement_wages'] = $fileUploadNya;
				}

				if($data['cekFileInterview'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileInterview"]["name"];
					$newFileName = "interview_".$idPerson;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileInterview"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['file_interview'] = $fileUploadNya;
				}
				
				if($data['cekFileEvaluation'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileEvaluation"]["name"];
					$newFileName = "evaluation_".$idPerson;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileEvaluation"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['file_evaluation'] = $fileUploadNya;
				}
				
				if($data['cekFileStateForm'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileStateForm"]["name"];
					$newFileName = "statementForm_".$idPerson;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileStateForm"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['file_statement'] = $fileUploadNya;
				}

				$dataIns['idperson'] = $idPerson;

				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("mstpersonal",$dataIns);
			}else{
				if($data['cekFileUpload'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileUpload"]["name"];
					$newFileName = "pic_".$idEdit;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileUpload"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['pic'] = $fileUploadNya;
				}

				if($data['cekFileWages'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileStatementWages"]["name"];
					$newFileName = "statementOfWages_".$idEdit;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileStatementWages"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['file_statement_wages'] = $fileUploadNya;
				}

				if($data['cekFileInterview'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileInterview"]["name"];
					$newFileName = "interview_".$idEdit;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileInterview"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['file_interview'] = $fileUploadNya;
				}
				
				if($data['cekFileEvaluation'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileEvaluation"]["name"];
					$newFileName = "evaluation_".$idEdit;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileEvaluation"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['file_evaluation'] = $fileUploadNya;
				}
				
				if($data['cekFileStateForm'] != "")
				{
					$fileUploadNya = "";
					$fileName = $_FILES["fileStateForm"]["name"];
					$newFileName = "statementForm_".$idEdit;
					$fileUploadNya = $dataContext->uploadFile($_FILES["fileStateForm"]['tmp_name'],$dir,$fileName,$newFileName);
					$dataIns['file_statement'] = $fileUploadNya;
				}

				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idperson = '".$idEdit."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"mstpersonal");
			}
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}
		
		print $stData;
	}

	function navButtonHead($type = "")
	{
		if($type == "onboard")
		{
			$this->getDataOnboard();
		}
		else if($type == "onleave")
		{
			$this->getDataOnLeave();
		}
		else if($type == "nonaktif")
		{
			$this->getDataNonAktif();
		}
		else if($type == "notofemp")
		{
			$this->getDataNotForEmp();
		}
		else if($type == "newapplicent")
		{
			$this->getDataNewApplicent();
		}
	}

	function navProsesCrew()
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$idPerson = $_POST['idPerson'];
		$slcNav = $_POST['slcNav'];
		$divNya = "";
		$labelName = "";

		if($slcNav == "family")
		{
			$divNya .= "<div id=\"idDivFormFamilyDetail\" style=\"display:;\">";
				$divNya .= "<div class=\"row\" id=\"idTableNominee\">";
					$divNya .= "<div class=\"col-md-12 col-xs-12\" style=\"background-color:#B0B0B0;padding:10px;\">";
						$divNya .= "<legend style=\"text-align:left;margin-bottom:5px;\"><b><i>:: Nominee / Next of Kin / Family Details ::</i></b></legend>";
						$divNya .= "<div id=\"idFormNomineeFamDet\" style=\"display:none;\">";
							$divNya .= "<div class=\"row\">";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtNomineeFamDet\" style=\"font-size:12px;\">Full Name of Nominee :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtNomineeFamDet\" title=\"Full Name of Nominee for compensation in case of\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"slcRelationshipFamDet\" style=\"font-size:12px;\">Relationship :</label>";
									$divNya .= "<select class=\"form-control input-sm\" id=\"slcRelationshipFamDet\">";
										$divNya .= "<option value=\"-\">-</option>";
										$divNya .= "<option selected=\"selected\" value=\"Spouse\">Spouse</option>";
										$divNya .= "<option value=\"Partner\">Partner</option>";
										$divNya .= "<option value=\"Child\">Child</option>";
										$divNya .= "<option value=\"Parent\">Parent</option>";
										$divNya .= "<option value=\"Grand Parent\">Grand Parent</option>";
										$divNya .= "<option value=\"Other Relative\">Other Relative</option>";
									$divNya .= "</select>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"rdGenderMaleFamDet\" style=\"font-size:12px;\">Gender :</label><br>";
									$divNya .= "<label class=\"radio-inline\">";
										$divNya .= "<input type=\"radio\" name=\"rdGenderFamDet\" id=\"rdGenderMaleFamDet\" value=\"Male\"> Male";
									$divNya .= "</label>";
									$divNya .= "<label class=\"radio-inline\">";
										$divNya .= "<input type=\"radio\" name=\"rdGenderFamDet\" id=\"rdGenderFemaleFamDet\" value=\"Female\"> Female";
									$divNya .= "</label>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtPostCodeFamDet\" style=\"font-size:12px;\">Post Code :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtPostCodeFamDet\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-4 col-xs-12\">";
									$divNya .= "<label for=\"txtAddressFamDet\" style=\"font-size:12px;\">Address :</label>";
									$divNya .= "<textarea class=\"form-control input-sm\" id=\"txtAddressFamDet\"></textarea>";
								$divNya .= "</div>";
							$divNya .= "</div>";
							$divNya .= "<div class=\"row\">";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"slcNationalityFamDet\" style=\"font-size:12px;\">Nationality :</label>";
									$divNya .= "<select class=\"form-control input-sm\" id=\"slcNationalityFamDet\">";
										$divNya .= $dataContext->getCountryByOption("","kode");
									$divNya .= "</select>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"slcCityFamDet\" style=\"font-size:12px;\">City :</label>";
									$divNya .= "<select class=\"form-control input-sm\" id=\"slcCityFamDet\">";
										$divNya .= $dataContext->getCityByOption("","kode");
									$divNya .= "</select>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"slcCountryFamDet\" style=\"font-size:12px;\">Country :</label>";
									$divNya .= "<select class=\"form-control input-sm\" id=\"slcCountryFamDet\">";
										$divNya .= $dataContext->getCountryByOption("","kode");
									$divNya .= "</select>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtEmailFamDet\" style=\"font-size:12px;\">Email :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtEmailFamDet\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtTelpFamDet\" style=\"font-size:12px;\">Telephone :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtTelpFamDet\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtMobileFamDet\" style=\"font-size:12px;\">Mobile :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtMobileFamDet\">";
								$divNya .= "</div>";
							$divNya .= "</div>";
							$divNya .= "<div class=\"row\" style=\"margin-top:15px;\">";
								$divNya .= "<div class=\"col-md-6 col-xs-12\">";
									$divNya .= "<button id=\"btnSave\" class=\"btn btn-primary btn-xs btn-block\" title=\"Save Data\" onclick=\"saveDataNominee();\"><i class=\"glyphicon glyphicon-saved\"></i> Submit</button>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-6 col-xs-12\">";
									$divNya .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Reset\" onclick=\"navProsesCrew();\"><i class=\"glyphicon glyphicon-ban-circle\"></i> Reset</button>";
								$divNya .= "</div>";
							$divNya .= "</div>";
						$divNya .= "</div>";
						$divNya .= "<div id=\"idDatatableNomineeFamDet\">";
							$divNya .= $this->getDataTableNominee($idPerson);
						$divNya .= "</div>";
					$divNya .= "</div>";
				$divNya .= "</div>";
				$divNya .= "<div class=\"row\" id=\"idTableFamilyData\" style=\"margin-top:5px;\">";
					$divNya .= "<div class=\"col-md-12 col-xs-12\" style=\"background-color:#B0B0B0;padding:10px;\">";
						$divNya .= "<legend style=\"text-align:left;\"><b><i>:: Family Data ::</i></b></legend>";
						$divNya .= "<div id=\"idFormFamilyDataFamDet\" style=\"display:none;\">";
							$divNya .= "<div class=\"row\">";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"slcRelationshipFamily\" style=\"font-size:12px;\">Relationship :</label>";
									$divNya .= "<select class=\"form-control input-sm\" id=\"slcRelationshipFamily\">";
										$divNya .= "<option value=\"Child\">Child</option>";
										$divNya .= "<option value=\"Spouse\">Spouse / Partner</option>";										
									$divNya .= "</select>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"rdChildMaleFamDet\" style=\"font-size:12px;\">Child :</label><br>";
									$divNya .= "<label class=\"radio-inline\">";
										$divNya .= "<input type=\"radio\" name=\"rdGenderFamilyData\" id=\"rdChildMaleFamDet\" value=\"1\"> Male";
									$divNya .= "</label>";
									$divNya .= "<label class=\"radio-inline\">";
										$divNya .= "<input type=\"radio\" name=\"rdGenderFamilyData\" id=\"rdChildFemaleFamDet\" value=\"2\"> Female";
									$divNya .= "</label>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtFirstNameFamDet\" style=\"font-size:12px;\">First Name :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtFirstNameFamDet\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtLastNameFamDet\" style=\"font-size:12px;\">Last Name :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtLastNameFamDet\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtDate_DOBFamDet\" style=\"font-size:12px;\">Date of Birth :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtDate_DOBFamDet\" placeholder=\"Date\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtPassportNoFamDet\" style=\"font-size:12px;\">Passport No :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtPassportNoFamDet\">";
								$divNya .= "</div>";
							$divNya .= "</div>";
							$divNya .= "<div class=\"row\">";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtDate_issuedFamDet\" style=\"font-size:12px;\">Issued :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtDate_issuedFamDet\" placeholder=\"Date\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtPlaceFamDet\" style=\"font-size:12px;\">Place :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtPlaceFamDet\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"txtDate_ValidUntilFamDet\" style=\"font-size:12px;\">Valid Until :</label>";
									$divNya .= "<input type=\"text\" class=\"form-control input-sm\" id=\"txtDate_ValidUntilFamDet\" placeholder=\"Date\">";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-2 col-xs-12\">";
									$divNya .= "<label for=\"slcVisaFamDet\" style=\"font-size:12px;\">Visa :</label>";
									$divNya .= "<select class=\"form-control input-sm\" id=\"slcVisaFamDet\">";
										$divNya .= "<option selected=\"selected\" value=\"\"></option>";
										$divNya .= "<option value=\"USA\">USA</option>";
										$divNya .= "<option value=\"Canada\">Canada</option>";
										$divNya .= "<option value=\"Brazil\">Brazil</option>";
										$divNya .= "<option value=\"Schengen\">Schengen</option>";
										$divNya .= "<option value=\"UK\">UK</option>";
										$divNya .= "<option value=\"Other\">Other</option>";
									$divNya .= "</select>";
								$divNya .= "</div>";
							$divNya .= "</div>";
							$divNya .= "<div class=\"row\" style=\"margin-top:15px;\">";
								$divNya .= "<input type=\"hidden\" id=\"txtIdEditDataFamily\" value=\"\">";
								$divNya .= "<div class=\"col-md-6 col-xs-12\">";
									$divNya .= "<button id=\"btnSave\" class=\"btn btn-primary btn-xs btn-block\" title=\"Save Data\" onclick=\"saveFamilyData();\"><i class=\"glyphicon glyphicon-saved\"></i> Submit</button>";
								$divNya .= "</div>";
								$divNya .= "<div class=\"col-md-6 col-xs-12\">";
									$divNya .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Cancel\" onclick=\"navProsesCrew();\"><i class=\"glyphicon glyphicon-ban-circle\"></i> Reset</button>";
								$divNya .= "</div>";
							$divNya .= "</div>";
						$divNya .= "</div>";
						$divNya .= "<div id=\"idDatatableFamilyDataFamDet\">";
							$divNya .= $this->getDataTableFamilyData($idPerson);
						$divNya .= "</div>";
					$divNya .= "</div>";
				$divNya .= "</div>";

			$divNya .= "</div>";
			$labelName = "<b><i>:: Family Details ::</i></b>";
		}

		$dataOut['divForm'] = $divNya;
		$dataOut['labelName'] = $labelName;

		print json_encode($dataOut);
	}

	function updateNominee()
	{
		$status = "";
		$dataUpt = array();
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");

		$idPerson = $_POST['idPerson'];
		$dataUpt['famfullname'] = $_POST['fullName'];
		$dataUpt['famrelateid'] = $_POST['relationship'];
		$dataUpt['famgender'] = $_POST['gender'];
		$dataUpt['famposcode'] = $_POST['kodePos'];
		$dataUpt['famaddrs'] = $_POST['address'];
		$dataUpt['famntnid'] = $_POST['nationality'];
		$dataUpt['famcityid'] = $_POST['kota'];
		$dataUpt['famctryid'] = $_POST['country'];
		$dataUpt['famemail'] = $_POST['email'];
		$dataUpt['famtelp'] = $_POST['telp'];
		$dataUpt['fammobile'] = $_POST['mobile'];

		try {
			$dataUpt['updusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataUpt,"mstpersonal");

			$status = "Success..!!";
		} catch (Exception $ex) {
			$status = "Failed => ".$ex->getMessage();
		}

		print json_encode($status);
	}

	function getPersonalId($idPerson = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;

		$sql = "SELECT A.*,B.NmNegara
				FROM tblpersonaldoc A
				LEFT JOIN tblnegara B ON A.docissctryid = B.KdNegara AND B.deletests = '0' 
				WHERE A.idperson = '".$idPerson."' AND A.deletests = '0' ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$btnView = "";
				$btnAct = "<button class=\"btn btn-success btn-xs btn-block\" onclick=\"getDataEdit('".$val->idperdoc."');\" title=\"Edit Data\">Edit</button>";
				$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" onclick=\"delData('".$val->idperdoc."','".$idPerson."');\" title=\"Delete Data\">Del</button>";

				if($val->doc_file != "")
				{
					$btnView = "<a class=\"btn btn-info btn-xs btn-block\" href=\"".base_url('uploadFile')."/".$val->doc_file."\" target=\"_blank\" title=\"View File\">View File</a>";
				}

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:12px;text-align:center;\">".$no.$btnView."</td>";
					$trNya .= "<td style=\"font-size:12px;\">".$val->doctp."</td>";
					$trNya .= "<td style=\"font-size:12px;text-align:center;\">".$val->NmNegara."</td>";
					$trNya .= "<td style=\"font-size:12px;text-align:center;\">".$val->docno."</td>";
					$trNya .= "<td style=\"font-size:12px;text-align:center;\">".$dataContext->convertReturnName($val->docissdt)."</td>";
					$trNya .= "<td style=\"font-size:12px;\">".$val->docissplc."</td>";
					$trNya .= "<td style=\"font-size:12px;text-align:center;\">".$dataContext->convertReturnName($val->docexpdt)."</td>";
					$trNya .= "<td style=\"font-size:12px;\">".$btnAct."</td>";
				$trNya .= "</tr>";
				$no++;
			}
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['labelName'] = "<b><i>:: Personal Id ::</i></b>";
		$dataOut['optCountry'] = $dataContext->getCountryByOption("","kode");

		$this->load->view('frontend/personal_id',$dataOut);
	}

	function saveDataPersonalId()
	{
		$dataContext = new DataContext();
		$data = $_POST;
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dataIns = array();
		$dateNow = date("Ymd/h:i:s");
		$stData = "";
		$idEdit = $data['idEdit'];
		$idPerson = $data['idPerson'];
		$dir = "./uploadFile";

		try {
			
			$dataIns['idperson'] = $idPerson;
			$dataIns['docissplc'] = $data['txtIssueAtPlace'];
			$dataIns['docissctryid'] = $data['slcCountryIssuePI'];
			$dataIns['docissdt'] = $data['txtDate_issuePI'];
			$dataIns['docexpdt'] = $data['txtDate_validUntiPI'];
			$dataIns['doctp'] = $data['txtTypeDocPI'];
			$dataIns['docno'] = $data['txtNoDocPI'];
			
			if($idEdit == "")
			{
				$dataIns['idperdoc'] = $dataContext->getNewId("idperdoc","tblpersonaldoc","WHERE idperson = '".$idPerson."'");
				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblpersonaldoc",$dataIns);
				$idEdit = $dataIns['idperdoc'];
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idperdoc = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblpersonaldoc");
			}

			if($data['cekFileUpload'] != "")
			{
				$dataIns = array();

				$fileUploadNya = "";
				$fileName = $_FILES["fileUpload"]["name"];
				$newFileName = "personalId_".$idPerson."_".$idEdit;
				$fileUploadNya = $dataContext->uploadFile($_FILES["fileUpload"]['tmp_name'],$dir,$fileName,$newFileName);
				$dataIns['doc_file'] = $fileUploadNya;

				$whereNya = "idperdoc = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblpersonaldoc");
			}
			
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();;
		}

		print $stData;
	}

	function getDataOnboard($searchNya = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$btnProses = "";
		$dbSeaExp = "";

		$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND B.signoffdt = '0000-00-00' AND A.inaktif = '0' AND D.deletests = '0' ";

		if($searchNya == "search")
		{
			$txtSearch = $_POST['txtSearch'];
			$typeSearch = $_POST['typeSearch'];
			
			if($typeSearch == "id")
			{
				$whereNya .= " AND A.idperson = '".$txtSearch."' ";
			}
			else if($typeSearch == "name")
			{
				$whereNya .= " AND CONCAT(A.fname,' ',A.mname,' ',A.lname) LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "age")
			{
				$whereNya .= " AND (SUBSTRING(CURDATE(),1,4)-SUBSTRING(A.dob,1,4)) = '".$txtSearch."' ";
			}
			else if($typeSearch == "rank")
			{
				$whereNya .= " AND E.rankexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp E ON E.idperson = A.idperson";
			}
			else if($typeSearch == "applied")
			{
				$whereNya .= " AND A.applyfor LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "vessel")
			{
				$whereNya .= " AND E.vslexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp E ON E.idperson = A.idperson";
			}
		}

		$sql = " SELECT A.idperson,B.idcontract,TRIM(CONCAT( A.fname, ' ', A.mname, ' ', A.lname )) AS fullName, A.applyfor, A.gender, A.religion, A.dob,C.NmKota,CASE WHEN A.inAktif = '0' THEN 'Aktif' WHEN A.inAktif = '1' THEN 'Non Aktif' END AS inAktif,CASE WHEN A.lower_rank = 0 THEN 'No' WHEN A.lower_rank =1 THEN 'Yes' END AS lowerrank,(SUBSTRING( CURDATE( ) , 1, 4 ) - SUBSTRING( A.dob, 1, 4 )) AS umur, A.pic, B.signoffdt
			FROM mstpersonal A
			LEFT JOIN tblcontract B ON A.idperson = B.idperson
			LEFT JOIN tblkota C ON A.pob = C.KdKota
			LEFT JOIN mstvessel D ON D.kdvsl = B.signonvsl AND D.nmvsl != '' AND D.nmvsl != '-'
			".$dbSeaExp."
			".$whereNya."
			GROUP BY A.idperson,D.nmvsl
			";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$btnProses = "<button class=\"btn btn-warning btn-xs btn-block\" onclick=\"getDataProses('".$val->idperson."');\" title=\"Proses\">Proses</button>";

				$trNya .= "<tr>";
					$trNya .= "<td align=\"center\" style=\"font-size:11.5px;background-color:#5BC0DE;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">(".$val->idperson.") ".$val->fullName."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".strtoupper($val->applyfor)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->gender."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->religion."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".$val->NmKota.", ".$dataContext->convertReturnName($val->dob)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->inAktif."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->lowerrank."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$btnProses."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}

		$dataOut['typeDisplay'] = "onboard";
		$dataOut['trNya'] = $trNya;
		$dataOut["listPage"] = "";

		if($searchNya == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/personal',$dataOut);
		}
		
	}

	function getDataOnLeave($searchNya = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$btnProses = "";
		$dbSeaExp = "";

		$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND A.inAktif  = '0' AND A.inBlacklist='0'
			AND B.idcontract IN (SELECT MAX(idcontract) FROM tblcontract WHERE idperson=B.idperson AND deletests=0)
			AND (B.signoffdt != '0000-00-00' AND B.signoffdt <= CURDATE( )) ";

		if($searchNya == "search")
		{
			$txtSearch = $_POST['txtSearch'];
			$typeSearch = $_POST['typeSearch'];
			
			if($typeSearch == "id")
			{
				$whereNya .= " AND A.idperson = '".$txtSearch."' ";
			}
			else if($typeSearch == "name")
			{
				$whereNya .= " AND CONCAT(A.fname,' ',A.mname,' ',A.lname) LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "age")
			{
				$whereNya .= " AND (SUBSTRING(CURDATE(),1,4)-SUBSTRING(A.dob,1,4)) = '".$txtSearch."' ";
			}
			else if($typeSearch == "rank")
			{
				$whereNya .= " AND D.rankexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp D ON D.idperson = A.idperson";
			}
			else if($typeSearch == "applied")
			{
				$whereNya .= " AND A.applyfor LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "vessel")
			{
				$whereNya .= " AND D.vslexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp D ON D.idperson = A.idperson";
			}
		}

		$sql = " SELECT A.idperson,B.idcontract,TRIM( CONCAT( A.fname, ' ', A.mname, ' ', A.lname ) ) AS fullName, A.applyfor, A.gender, A.religion, A.dob,C.NmKota,CASE WHEN A.inAktif = '0' THEN 'Aktif' WHEN A.inAktif = '1' THEN 'Non Aktif' END AS inAktif,CASE WHEN A.lower_rank = 0 THEN 'No' WHEN A.lower_rank =1 THEN 'Yes' END AS lowerrank,(SUBSTRING( CURDATE( ) , 1, 4 ) - SUBSTRING( A.dob, 1, 4 )) AS umur, A.pic, B.signoffdt
			FROM mstpersonal A
			LEFT JOIN tblcontract B ON A.idperson = B.idperson
			LEFT JOIN tblkota C ON A.pob = C.KdKota
			".$dbSeaExp."
			".$whereNya."
			GROUP BY A.idperson
			ORDER BY fullName ASC";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$btnProses = "<button class=\"btn btn-warning btn-xs\" onclick=\"getDataProses('".$val->idperson."');\" title=\"Proses\">Proses</button>";
				$btnProses .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delDataPersonal('".$val->idperson."');\" title=\"Delete\">Delete</button>";

				$trNya .= "<tr>";
					$trNya .= "<td align=\"center\" style=\"font-size:11.5px;background-color:#5CB85C;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">(".$val->idperson.") ".$val->fullName."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".strtoupper($val->applyfor)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->gender."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->religion."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".$val->NmKota.", ".$dataContext->convertReturnName($val->dob)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->inAktif."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->lowerrank."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$btnProses."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}

		$dataOut['typeDisplay'] = "onleave";
		$dataOut['trNya'] = $trNya;
		$dataOut["listPage"] = "";

		if($searchNya == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/personal',$dataOut);
		}
	}

	function getDataNonAktif($searchNya = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$btnProses = "";
		$dbSeaExp = "";

		$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND A.inAktif  = '1' AND A.inBlacklist='0' ";

		if($searchNya == "search")
		{
			$txtSearch = $_POST['txtSearch'];
			$typeSearch = $_POST['typeSearch'];
			
			if($typeSearch == "id")
			{
				$whereNya .= " AND A.idperson = '".$txtSearch."' ";
			}
			else if($typeSearch == "name")
			{
				$whereNya .= " AND CONCAT(A.fname,' ',A.mname,' ',A.lname) LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "age")
			{
				$whereNya .= " AND (SUBSTRING(CURDATE(),1,4)-SUBSTRING(A.dob,1,4)) = '".$txtSearch."' ";
			}
			else if($typeSearch == "rank")
			{
				$whereNya .= " AND C.rankexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp C ON C.idperson = A.idperson";
			}
			else if($typeSearch == "applied")
			{
				$whereNya .= " AND A.applyfor LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "vessel")
			{
				$whereNya .= " AND C.vslexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp C ON C.idperson = A.idperson";
			}
		}

		$sql = " SELECT A.idperson,TRIM( CONCAT( A.fname, ' ', A.mname, ' ', A.lname ) ) AS fullName, A.applyfor, A.gender, A.religion, A.dob,B.NmKota,CASE WHEN A.inAktif = '0' THEN 'Aktif' WHEN A.inAktif = '1' THEN 'Non Aktif' END AS inAktif,CASE WHEN A.lower_rank = 0 THEN 'No' WHEN A.lower_rank =1 THEN 'Yes' END AS lowerrank,(SUBSTRING( CURDATE( ) , 1, 4 ) - SUBSTRING( A.dob, 1, 4 )) AS umur, A.pic
			FROM mstpersonal A
			LEFT JOIN tblkota B ON A.pob = B.KdKota
			".$dbSeaExp."
			".$whereNya."
			GROUP BY A.idperson
			ORDER BY fullName ASC";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$btnProses = "<button class=\"btn btn-warning btn-xs\" onclick=\"getDataProses('".$val->idperson."');\" title=\"Proses\">Proses</button>";
				$btnProses .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delDataPersonal('".$val->idperson."');\" title=\"Delete\">Delete</button>";

				$trNya .= "<tr>";
					$trNya .= "<td align=\"center\" style=\"font-size:11.5px;background-color:#F0AD4E;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">(".$val->idperson.") ".$val->fullName."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".strtoupper($val->applyfor)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->gender."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->religion."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".$val->NmKota.", ".$dataContext->convertReturnName($val->dob)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->inAktif."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->lowerrank."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$btnProses."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}

		$dataOut['typeDisplay'] = "nonaktif";
		$dataOut['trNya'] = $trNya;
		$dataOut["listPage"] = "";

		if($searchNya == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/personal',$dataOut);
		}
	}

	function getDataNotForEmp($searchNya = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$btnProses = "";
		$dbSeaExp = "";

		$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND A.inBlacklist = '1' ";

		if($searchNya == "search")
		{
			$txtSearch = $_POST['txtSearch'];
			$typeSearch = $_POST['typeSearch'];
			
			if($typeSearch == "id")
			{
				$whereNya .= " AND A.idperson = '".$txtSearch."' ";
			}
			else if($typeSearch == "name")
			{
				$whereNya .= " AND CONCAT(A.fname,' ',A.mname,' ',A.lname) LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "age")
			{
				$whereNya .= " AND (SUBSTRING(CURDATE(),1,4)-SUBSTRING(A.dob,1,4)) = '".$txtSearch."' ";
			}
			else if($typeSearch == "rank")
			{
				$whereNya .= " AND C.rankexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp C ON C.idperson = A.idperson";
			}
			else if($typeSearch == "applied")
			{
				$whereNya .= " AND A.applyfor LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "vessel")
			{
				$whereNya .= " AND C.vslexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp C ON C.idperson = A.idperson";
			}
		}

		$sql = " SELECT A.idperson,TRIM( CONCAT( A.fname, ' ', A.mname, ' ', A.lname ) ) AS fullName, A.applyfor, A.gender, A.religion, A.dob,B.NmKota,CASE WHEN A.inAktif = '0' THEN 'Aktif' WHEN A.inAktif = '1' THEN 'Non Aktif' END AS inAktif,CASE WHEN A.lower_rank = 0 THEN 'No' WHEN A.lower_rank =1 THEN 'Yes' END AS lowerrank,(SUBSTRING( CURDATE( ) , 1, 4 ) - SUBSTRING( A.dob, 1, 4 )) AS umur, A.pic
			FROM mstpersonal A
			LEFT JOIN tblkota B ON A.pob = B.KdKota
			".$dbSeaExp."
			".$whereNya."
			GROUP BY A.idperson
			ORDER BY fullName ASC";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$btnProses = "<button class=\"btn btn-warning btn-xs\" onclick=\"getDataProses('".$val->idperson."');\" title=\"Proses\">Proses</button>";
				$btnProses .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delDataPersonal('".$val->idperson."');\" title=\"Delete\">Delete</button>";

				$trNya .= "<tr>";
					$trNya .= "<td align=\"center\" style=\"font-size:11.5px;background-color:#D9534F;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">(".$val->idperson.") ".$val->fullName."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".strtoupper($val->applyfor)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->gender."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->religion."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".$val->NmKota.", ".$dataContext->convertReturnName($val->dob)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->inAktif."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->lowerrank."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$btnProses."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}

		$dataOut['typeDisplay'] = "notforemp";
		$dataOut['trNya'] = $trNya;
		$dataOut["listPage"] = "";

		if($searchNya == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/personal',$dataOut);
		}
	}

	function getDataNewApplicent($searchNya = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;
		$btnProses = "";
		$dbSeaExp = "";

		$whereNya = " WHERE A.deletests = '0' AND B.deletests = '0' AND A.newapplicent = '1' ";

		if($searchNya == "search")
		{
			$txtSearch = $_POST['txtSearch'];
			$typeSearch = $_POST['typeSearch'];
			
			if($typeSearch == "id")
			{
				$whereNya .= " AND A.idperson = '".$txtSearch."' ";
			}
			else if($typeSearch == "name")
			{
				$whereNya .= " AND CONCAT(A.fname,' ',A.mname,' ',A.lname) LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "age")
			{
				$whereNya .= " AND (SUBSTRING(CURDATE(),1,4)-SUBSTRING(A.dob,1,4)) = '".$txtSearch."' ";
			}
			else if($typeSearch == "rank")
			{
				$whereNya .= " AND C.rankexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp C ON C.idperson = A.idperson";
			}
			else if($typeSearch == "applied")
			{
				$whereNya .= " AND A.applyfor LIKE '%".$txtSearch."%' ";
			}
			else if($typeSearch == "vessel")
			{
				$whereNya .= " AND C.vslexp LIKE '%".$txtSearch."%' ";
				$dbSeaExp = "LEFT JOIN tblseaexp C ON C.idperson = A.idperson";
			}
		}

		$sql = " SELECT A.idperson,TRIM( CONCAT( A.fname, ' ', A.mname, ' ', A.lname ) ) AS fullName, A.applyfor, A.gender, A.religion, A.dob,B.NmKota,CASE WHEN A.inAktif = '0' THEN 'Aktif' WHEN A.inAktif = '1' THEN 'Non Aktif' END AS inAktif,CASE WHEN A.lower_rank = 0 THEN 'No' WHEN A.lower_rank =1 THEN 'Yes' END AS lowerrank,(SUBSTRING( CURDATE( ) , 1, 4 ) - SUBSTRING( A.dob, 1, 4 )) AS umur, A.pic
			FROM mstpersonal A
			LEFT JOIN tblkota B ON A.pob = B.KdKota
			".$dbSeaExp."
			".$whereNya."
			GROUP BY A.idperson
			ORDER BY fullName ASC";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$btnProses = "<button class=\"btn btn-warning btn-xs\" onclick=\"getDataProses('".$val->idperson."');\" title=\"Proses\">Proses</button>";
				$btnProses .= " <button class=\"btn btn-danger btn-xs\" onclick=\"delDataPersonal('".$val->idperson."');\" title=\"Delete\">Delete</button>";

				$trNya .= "<tr>";
					$trNya .= "<td align=\"center\" style=\"font-size:11.5px;background-color:#B565ED;\">".$no."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">(".$val->idperson.") ".$val->fullName."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".strtoupper($val->applyfor)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->gender."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->religion."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\">".$val->NmKota.", ".$dataContext->convertReturnName($val->dob)."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->inAktif."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$val->lowerrank."</td>";
					$trNya .= "<td style=\"font-size:11.5px;\" align=\"center\">".$btnProses."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}

		$dataOut['typeDisplay'] = "newapplicent";
		$dataOut['trNya'] = $trNya;
		$dataOut["listPage"] = "";

		if($searchNya == "search")
		{
			print json_encode($dataOut);
		}else{
			$this->load->view('frontend/personal',$dataOut);
		}
	}

	function getPersonalDataAllCertificate($idPerson = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$trNya = "";
		$no = 1;

		$sql = "SELECT * FROM tblcertdoc WHERE idperson = '".$idPerson."' AND deletests = '0' ORDER BY certgroup,certname ASC ";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $key => $val)
		{
			$certName = "(".$val->certgroup.") ".$val->certname;
			$btnAct = "<button class=\"btn btn-info btn-xs\" title=\"Edit Data\" onclick=\"getDataEdit('".$val->idcertdoc."');\">Edit</button>";
			$btnAct .= " <button class=\"btn btn-danger btn-xs\" title=\"Delete Data\" onclick=\"delData('".$val->idcertdoc."','".$val->idperson."');\">Del</button>";
			$btnAct .= " <button class=\"btn btn-primary btn-xs\" title=\"View Data\" onclick=\"getDataEdit('".$val->idcertdoc."');\">View</button>";

			$displayCek = "&nbsp;";
			if($val->display == "Y")
			{
				$displayCek = "&radic;";
			}

			if($val->certificate_file != "")
			{
				$certName = "<a href=\"".base_url('uploadCertificate')."/".$val->certificate_file."\" target=\"_blank\">".$certName."</a>";
			}

			$trNya .= "<tr>";
				$trNya .= "<td style=\"font-size:11px;padding:1px;\" align=\"center\">".$no."</td>";
				$trNya .= "<td style=\"font-size:11px;padding:1px;\" align=\"center\">".$displayCek."</td>";
				$trNya .= "<td style=\"font-size:11px;padding:1px;\">".$certName."</td>";
				$trNya .= "<td style=\"font-size:11px;padding:1px;\" align=\"center\">".$btnAct."</td>";
			$trNya .= "</tr>";

			$no++;
		}

		$dataOut['trNya'] = $trNya;
		$dataOut['optMstCert'] = $dataContext->getMstCertificateByOption("");
		$dataOut['optRank'] = $dataContext->getMstRankByOption("");
		$dataOut['optCountry'] = $dataContext->getCountryByOption("","kode");
		$dataOut['optType'] = $dataContext->getVesselTypeByOption("","kode");

		$this->load->view('frontend/allCertificate',$dataOut);
	}

	function getDataTableNominee($idPerson = "")
	{
		$dataContext = new DataContext();
		$divNya = "";
		$trNya = "";

		$sql = "SELECT A.idperson,A.famfullname,A.famrelateid,A.famgender,A.famaddrs,A.famposcode,A.famntnid,A.famctryid,A.famemail,A.famtelp,A.fammobile,B.NmKota 
				FROM mstpersonal A 
				LEFT JOIN tblkota B ON A.famcityid = B.KdKota
				WHERE A.deletests = '0' AND A.idperson = '".$idPerson."'
				";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			$nationality = "";
			if($rsl[0]->famntnid != "000")
			{
				$nationality = $dataContext->getDataByReq("NmNegara","tblnegara","KdNegara = '".$rsl[0]->famntnid."' ");
			}

			$country = "";
			if($rsl[0]->famctryid != "000")
			{
				$country = $dataContext->getDataByReq("NmNegara","tblnegara","KdNegara = '".$rsl[0]->famctryid."' ");
			}			
			
			$btnUpdateNominee = "<button class=\"btn btn-info btn-xs\" title=\"Update\" onclick=\"updateNominee('".$idPerson."');\">Update</button>";

			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\">".$rsl[0]->famfullname."<br>".$btnUpdateNominee."</td>";
				$trNya .= "<td align=\"center\">".$rsl[0]->famrelateid."</td>";
				$trNya .= "<td align=\"center\">".$rsl[0]->famgender."</td>";
				$trNya .= "<td>".$rsl[0]->famaddrs."<br>".$rsl[0]->famposcode."</td>";
				$trNya .= "<td align=\"center\">".$nationality."</td>";
				$trNya .= "<td>".$rsl[0]->NmKota."</td>";
				$trNya .= "<td align=\"center\">".$country."</td>";
				$trNya .= "<td>".$rsl[0]->famemail."</td>";
				$trNya .= "<td align=\"center\">".$rsl[0]->famtelp."<br>".$rsl[0]->fammobile."</td>";
			$trNya .= "</tr>";
		}else{
			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\" colspan=\"8\">- Data Empty - </td>";
			$trNya .= "</tr>";
		}

		$divNya .= "<div class=\"row\">";
			$divNya .= "<div class=\"col-md-1 col-xs-12\">";
				$divNya .= "<button class=\"btn btn-success btn-xs btn-block\" title=\"Refresh\" onclick=\"navProsesCrew();\"><i class=\"fa fa-refresh\"></i> Refresh</button>";
			$divNya .= "</div>";
		$divNya .= "</div>";
		$divNya .= "<div class=\"row\" style=\"margin-top:5px;\">";
			$divNya .= "<div class=\"col-md-12 col-xs-12\">";
				$divNya .= "<div class=\"table-responsive\">";
					$divNya .= "<table class=\"table table-border table-striped table-bordered table-condensed table-advance table-hover\">";
						$divNya .= "<thead>";
							$divNya .= "<tr style=\"background-color:#067780;color:#FFF;height:30px;\">";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">Full Name</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">Relationship</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">Gender</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:20%;text-align:center;\">Address<br>Post Code</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">Nationality</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">City</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">Country</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">Email</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">Phone<br>Mobile</th>";
							$divNya .= "</tr>";
						$divNya .= "</thead>";
						$divNya .= "<tbody>";
							$divNya .= $trNya;
						$divNya .= "</tbody>";
					$divNya .= "</table>";
				$divNya .= "</div>";
			$divNya .= "</div>";
		$divNya .= "</div>";


		return $divNya;
	}

	function getDataTableFamilyData($idPerson = "")
	{
		$dataContext = new DataContext();
		$divNya = "";
		$trNya = "";
		$no = 1;

		$sql = "SELECT idfm, idperson, fmrel, CASE WHEN fmsex = '1' THEN 'Male' WHEN fmsex = '2' THEN 'Female' END AS relationship, fmfname, fmlname, fmdob, fmpassno, fmissdt, fmplc, fmexpdt, fmvisa FROM tblfamily WHERE idperson = '".$idPerson."' AND Deletests = '0' ORDER BY fmrel, fmfname ASC";
		$rsl = $this->MCrewscv->getDataQuery($sql);

		if(count($rsl) > 0)
		{
			foreach ($rsl as $key => $val)
			{
				$btnAct = "<button class=\"btn btn-info btn-xs btn-block\" title=\"Update Data\" onclick=\"updateFamilyData('".$val->idfm."','".$val->idperson."');\">Update</button>";
				$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" title=\"Delete\" onclick=\"delData('".$val->idfm."','".$val->idperson."');\">Delete</button>";

				$dob = "";
				$issued = "";
				$expired = "";

				if($val->fmdob != "0000-00-00")
				{
					$dob = $dataContext->convertReturnName($val->fmdob);
				}

				if($val->fmissdt != "0000-00-00")
				{
					$issued = $dataContext->convertReturnName($val->fmissdt);
				}

				if($val->fmexpdt != "0000-00-00")
				{
					$expired = $dataContext->convertReturnName($val->fmexpdt);
				}

				$trNya .= "<tr>";
					$trNya .= "<td style=\"font-size:12px;\" align=\"center\">".$no."</td>";
					$trNya .= "<td style=\"font-size:12px;\" align=\"center\">".$val->fmrel."</td>";
					$trNya .= "<td style=\"font-size:12px;\">".$val->fmfname."</td>";
					$trNya .= "<td style=\"font-size:12px;\">".$val->fmlname."</td>";
					$trNya .= "<td style=\"font-size:12px;\" align=\"center\">".$dob."</td>";
					$trNya .= "<td style=\"font-size:12px;\">".$val->fmpassno."</td>";
					$trNya .= "<td style=\"font-size:12px;\" align=\"center\">".$issued."</td>";
					$trNya .= "<td style=\"font-size:12px;\">".$val->fmplc."</td>";
					$trNya .= "<td style=\"font-size:12px;\" align=\"center\">".$expired."</td>";
					$trNya .= "<td style=\"font-size:12px;\">".$val->fmvisa."</td>";
					$trNya .= "<td style=\"font-size:12px;\" align=\"center\">".$btnAct."</td>";
				$trNya .= "</tr>";

				$no++;
			}
		}else{
			$trNya .= "<tr>";
				$trNya .= "<td align=\"center\" colspan=\"11\">- Data Empty - </td>";
			$trNya .= "</tr>";
		}

		$divNya .= "<div class=\"row\">";
			$divNya .= "<div class=\"col-md-1 col-xs-12\">";
				$divNya .= "<button class=\"btn btn-primary btn-xs btn-block\" title=\"Add Data\" onclick=\"addNewFamilyData();\"><i class=\"fa fa-plus\"></i> Add</button>";
			$divNya .= "</div>";
			$divNya .= "<div class=\"col-md-1 col-xs-12\">";
				$divNya .= "<button class=\"btn btn-success btn-xs btn-block\" title=\"Refresh\" onclick=\"navProsesCrew();\"><i class=\"fa fa-refresh\"></i> Refresh</button>";
			$divNya .= "</div>";
		$divNya .= "</div>";
		$divNya .= "<div class=\"row\" style=\"margin-top:5px;\">";
			$divNya .= "<div class=\"col-md-12 col-xs-12\">";
				$divNya .= "<div class=\"table-responsive\">";
					$divNya .= "<table class=\"table table-border table-striped table-bordered table-condensed table-advance table-hover\">";
						$divNya .= "<thead>";
							$divNya .= "<tr style=\"background-color:#067780;color:#FFF;height:30px;\">";
								$divNya .= "<th style=\"vertical-align:middle;width:3%;text-align:center;\">NO</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">RELATIONSHIP</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">FIRST NAME</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">LAST NAME</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">DOB</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">PASSPORT NO</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">ISSUED</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">PLACE</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">VALID UNTIL</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">VISA</th>";
								$divNya .= "<th style=\"vertical-align:middle;width:10%;text-align:center;\">ACTION</th>";
							$divNya .= "</tr>";
						$divNya .= "</thead>";
						$divNya .= "<tbody>";
							$divNya .= $trNya;
						$divNya .= "</tbody>";
					$divNya .= "</table>";
				$divNya .= "</div>";
			$divNya .= "</div>";
		$divNya .= "</div>";

		return $divNya;
	}

	function saveFamilyData()
	{
		$dataContext = new DataContext();
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");
		$data = $_POST;
		$dataIns = array();
		$idEdit = $data['idEdit'];
		$idPerson = $data['idPerson'];
		
		$dataIns['idperson'] = $idPerson;
		$dataIns['fmrel'] = $data['relationShip'];
		$dataIns['fmsex'] = $data['childGender'];
		$dataIns['fmfname'] = $data['firstName'];
		$dataIns['fmlname'] = $data['lastName'];
		$dataIns['fmdob'] = $data['dob'];
		$dataIns['fmpassno'] = $data['passportNo'];
		$dataIns['fmissdt'] = $data['issueDate'];
		$dataIns['fmplc'] = $data['place'];
		$dataIns['fmexpdt'] = $data['dateValid'];
		$dataIns['fmvisa'] = $data['visa'];

		try {
			if($idEdit == "")// save data
			{
				$dataIns['idfm'] = $dataContext->getNewId("idfm","tblfamily","WHERE idperson = '".$idPerson."'");

				$dataIns['addusrdt'] = $userInit."/".$dateNow;

				$this->MCrewscv->insData("tblfamily",$dataIns);
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idfm = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblfamily");
			}
			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}
		
		print json_encode($stData);
	}

	function saveAllCertificate()
	{
		$dataContext = new DataContext();
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");
		$data = $_POST;
		$dataIns = array();
		$dataInsPersonal = array();
		$idEdit = $data['idEdit'];
		$idPerson = $data['idPerson'];
		$displayCert = "N";
		$useThisAllCert = "N";
		$dir = "./uploadCertificate";

		$dataIns['idperson'] = $idPerson;

		if($data['slcMstCert'] != "")
		{
			$rsl = $this->MCrewscv->getData("*","mstcert","deletests = '0' AND kdcert = '".$data['slcMstCert']."'");

			if(count($rsl) > 0)
			{
				$certGrp = $rsl[0]->certgroup;
				$certName = 
				$displayName = $rsl[0]->dispname;

				
				$dataIns['kdcert'] = $rsl[0]->kdcert;
				$dataIns['certgroup'] = $rsl[0]->certgroup;
				$dataIns['certname'] = $rsl[0]->certname;
				$dataIns['dispname'] = $rsl[0]->dispname;
			}
		}

		if($data['useThisAll'] != "")
		{
			$useThisAllCert = "Y";
		}

		if($data['certDisplay'] != "")
		{
			$displayCert = "Y";
		}
		
		$dataIns['license'] = $data['slcLicense'];
		$dataIns['level'] = $data['slcLevel'];
		$dataIns['kdrank'] = $data['rank'];
		$dataIns['nmrank'] = $data['rankName'];
		$dataIns['vsltype'] = $data['slcVesselType'];
		$dataIns['kdnegara'] = $data['slcCountryIssue'];
		$dataIns['nmnegara'] = $data['slcCountryIssueName'];
		$dataIns['docno'] = $data['txtNoDocument'];
		$dataIns['issdate'] = $data['txtDate_ofIssue'];
		$dataIns['expdate'] = $data['txtDate_expiry'];
		$dataIns['issplace'] = $data['txtPlaceofIssue'];
		$dataIns['issauth'] = $data['txtIssuingAuthority'];
		$dataIns['remarks'] = $data['txtRemark'];
		$dataIns['redsign'] = $data['slcRedSing'];
		$dataIns['display'] = $displayCert;

		$dataInsPersonal['usecertdoc'] = $useThisAllCert;

		try {

				$whereNya = "idperson = '".$idPerson."' AND deletests = '0'";
				$this->MCrewscv->updateData($whereNya,$dataInsPersonal,"mstpersonal");

			if($idEdit == "")// save data
			{
				$dataIns['addusrdt'] = $userInit."/".$dateNow;
				
				$idEdit = $this->MCrewscv->insData("tblcertdoc",$dataIns,"IdCertDoc");
			}else{
				$dataIns['updusrdt'] = $userInit."/".$dateNow;

				$whereNya = "idcertdoc = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblcertdoc");
			}

			if($data['cekFileUpload'] != "")
			{
				$dataIns = array();

				$fileUploadNya = "";
				$fileName = $_FILES["fileUpload"]["name"];
				$newFileName = "certificateDoc_".$idPerson."_".$idEdit;
				$fileUploadNya = $dataContext->uploadFile($_FILES["fileUpload"]['tmp_name'],$dir,$fileName,$newFileName);
				$dataIns['certificate_file'] = $fileUploadNya;

				$whereNya = "idcertdoc = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya,$dataIns,"tblcertdoc");
			}

			$stData = "Submit Success..!!";
		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}
		
		print $stData;
		// print json_encode($stData);
	}

	function deleteData()
	{
		$dataContext = new DataContext();
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");
		$type = $_POST['type'];
		$status = "";
		$dataDel = array();

		if($type == "deleteFamilyData")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idfm = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblfamily");

			$status = "Success..!!";
		}
		else if($type == "deleteAllCertificate")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];			

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idcertdoc = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblcertdoc");

			$status = "Success..!!";
		}
		else if($type == "deleteAllCertificateFile")
		{
			$dir = "./uploadCertificate";
			$id = $_POST['id'];
			$file = $_POST['file'];

			$dataDel['certificate_file'] = "";

			$whereNya = "idcertdoc = '".$id."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblcertdoc");
			$dataContext->delFile($file,$dir);

			$status = "Success..!!";
		}
		else if($type == "deletePersonalId")
		{
			$id = $_POST['id'];
			$idPerson = $_POST['idPerson'];

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idperdoc = '".$id."' AND idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblpersonaldoc");

			$status = "Success..!!";
		}
		else if($type == "deletePersonalIdFile")
		{
			$dir = "./uploadFile";
			$id = $_POST['id'];
			$file = $_POST['file'];

			$dataDel['doc_file'] = "";

			$whereNya = "idperdoc = '".$id."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"tblpersonaldoc");
			$dataContext->delFile($file,$dir);

			$status = "Success..!!";
		}
		else if($type == "deleteStatementWages")
		{
			$dir = "./imgProfile";
			$idPerson = $_POST['idPerson'];
			$file = $_POST['fileName'];

			$dataDel['file_statement_wages'] = "";

			$whereNya = "idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"mstpersonal");
			
			$dataContext->delFile($file,$dir);

			$status = "Success..!!";
		}
		else if($type == "deleteDataPersonal")
		{
			$idPerson = $_POST['id'];

			$dataDel['deletests'] = "1";
			$dataDel['delusrdt'] = $userInit."/".$dateNow;

			$whereNya = "idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"mstpersonal");

			$status = "Success..!!";
		}
		else if($type == "deleteFileInterview")
		{
			$dir = "./imgProfile";
			$idPerson = $_POST['idPerson'];
			$file = $_POST['fileName'];

			$dataDel['file_interview'] = "";

			$whereNya = "idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"mstpersonal");
			
			$dataContext->delFile($file,$dir);

			$status = "Success..!!";
		}
		else if($type == "deleteFileEvaluation")
		{
			$dir = "./imgProfile";
			$idPerson = $_POST['idPerson'];
			$file = $_POST['fileName'];

			$dataDel['file_evaluation'] = "";

			$whereNya = "idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"mstpersonal");
			
			$dataContext->delFile($file,$dir);

			$status = "Success..!!";
		}
		else if($type == "deleteFileSTatementForm")
		{
			$dir = "./imgProfile";
			$idPerson = $_POST['idPerson'];
			$file = $_POST['fileName'];

			$dataDel['file_statement'] = "";

			$whereNya = "idperson = '".$idPerson."'";
			$this->MCrewscv->updateData($whereNya,$dataDel,"mstpersonal");
			
			$dataContext->delFile($file,$dir);

			$status = "Success..!!";
		}

		print json_encode($status);
	}

	function login()
	{
		$data = $_POST;
		$user = $data['user'];
		$pass = md5($data['pass']);
		$status = '';
		$whereNya = "userName = '".$user."' AND userPass = '".$pass."' AND status = '0' ";

		$cekLogin = $this->MCrewscv->getData("*","login",$whereNya);

		if(count($cekLogin) > 0)
		{	
			$this->session->set_userdata('idUserCrewSystem',$cekLogin[0]->userId);
			$this->session->set_userdata('userInitCrewSystem',$cekLogin[0]->userInit);
			$this->session->set_userdata('fullNameCrewSystem',$cekLogin[0]->userFullNm);
			$this->session->set_userdata('userCrewSystem',$cekLogin[0]->userName);
			$this->session->set_userdata('userJenisCrewSystem',$cekLogin[0]->userJenis);
			$status = true;
		}else{
			$status = false;
		}
		print json_encode($status);
	}

	function logOut()
	{
		$this->session->unset_userdata('idUserCrewSystem');
		$this->session->unset_userdata('userInitCrewSystem');
		$this->session->unset_userdata('fullNameCrewSystem');
		$this->session->unset_userdata('userCrewSystem');
		$this->session->unset_userdata('userJenisCrewSystem');
		// $this->session->sess_destroy();
		redirect(base_url());
	}


}
