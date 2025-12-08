<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Crew extends CI_Controller {
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

    function getData()
    {
        $dataContext = new DataContext();
        $dataOut = array();
        $dataOut['optCountry'] = $dataContext->getCountryByOption("","kode");
		$dataOut['optCity'] = $dataContext->getCityByOption("","kode");
		$dataOut['optMaritalStatus'] = $dataContext->getMaritalStatus();
		$dataOut['optReligion'] = $dataContext->getReligion();
		$dataOut['optVessel'] = $dataContext->getVesselByOption("","name");
		$dataOut['optRank'] = $dataContext->getRankByOption("","name");
		$dataOut['optBlood'] = $dataContext->getBloodType();
		$dataOut['optSize'] = $dataContext->getUkuran();
		$dataOut['getVesselType'] = $dataContext->getVesselType();
		$dataOut['optTax'] = $dataContext->getTaxByOption();
        $dataOut['optMstCert'] = $dataContext->getMstCertificateByOption("");
        $dataOut['optType'] = $dataContext->getVesselTypeByOption("","kode");
        $this->load->view('crewlogin/menucrew', $dataOut);
    }

    function getLoginCrew() {
		
		$this->load->view('crewlogin/loginCrew');
	}
    
    function saveDataPersonalCrew()
    {
        $userInit = $this->session->userdata('userInitCrewSystem');
        $data = $_POST;
        $dataIns = array();
        $dateNow = date("Ymd/h:i:s");

        $idPerson = isset($data['idperson']) ? trim($data['idperson']) : '';

        if ($idPerson == '' || $idPerson == null) {
            $getLatest = $this->MCrewscv->getDataQuery("
                SELECT idperson 
                FROM mstpersonal 
                WHERE deletests = 0 
                ORDER BY idperson DESC 
                LIMIT 1
            ");

            if ($getLatest && count($getLatest) > 0) {
                $idPerson = $getLatest[0]->idperson;
            }
        }

        $nameParts = $this->splitFullName($data['fname']);

		$dataIns['kodepelaut'] = $data['txtKodePelautCrew'];
        $dataIns['fname'] = preg_replace('/\s+/', ' ', trim($nameParts['fname']));
        $dataIns['mname'] = preg_replace('/\s+/', ' ', trim($nameParts['mname']));
        $dataIns['lname'] = preg_replace('/\s+/', ' ', trim($nameParts['lname']));
        $dataIns['pob'] = $data['pob'];
        $dataIns['dob'] = $data['dob'];
        $dataIns['paddress'] = $data['paddress'];
        $dataIns['ssn'] = $data['ssn']; 
        $dataIns['ptn'] = $data['ptn'];
        $dataIns['mobileno'] = $data['mobileno']; 
        $dataIns['telpno'] = $data['telpno']; 
        $dataIns['next_of_kin'] = $data['next_of_kin']; 
        $dataIns['email'] = $data['email'];
        $dataIns['norek'] = $data['norek'];
		$dataIns['bank_name'] = $data['bank_name'];
		$dataIns['norek_name'] = $data['norek_name'];
		$dataIns['norek_boat'] = $data['norek_boat'];
		$dataIns['bank_name_boat'] = $data['bank_name_boat'];
		$dataIns['norek_name_boat'] = $data['norek_name_boat'];
        $dataIns['applyfor'] = $data['applyfor']; 
        $dataIns['crew_vessel_type'] = $data['crew_vessel_type']; 
        $dataIns['religion'] = $data['religion'];
		
        
        $dataIns['newapplicent'] = 1;
        $dataIns['updusrdt'] = $userInit."/".$dateNow;

		$dataIns['pic'] = "";
		if (isset($_FILES['pic']) && $_FILES['pic']['error'] == 0) {
			$targetDir = "./imgProfile/";
			if (!file_exists($targetDir)) {
				mkdir($targetDir, 0777, true);
			}

			$fileTmp = $_FILES['pic']['tmp_name'];
			$fileName = basename($_FILES['pic']['name']);
			$ext = pathinfo($fileName, PATHINFO_EXTENSION);
			$newName = "pic_" . $idPerson . "." . strtolower($ext); 
			$targetFile = $targetDir . $newName;

			$allowed = array('jpg', 'jpeg', 'png');
			if (in_array(strtolower($ext), $allowed)) {
				if (move_uploaded_file($fileTmp, $targetFile)) {
					$dataIns['pic'] = $newName; 
				}
			}
		}


        try {
            if ($idPerson != "") {
				$oldData = $this->MCrewscv->getDataQuery("SELECT * FROM mstpersonal WHERE idperson = '".$idPerson."' AND deletests = 0");

				if (!empty($oldData)) {
					$old = $oldData[0];
					foreach ($dataIns as $key => $val) {
						if (!empty($val) && (empty($old->$key) || $old->$key == null)) {
							$updateData[$key] = $val;
						}
					}

					if (!empty($updateData)) {
						$updateData['updusrdt'] = $userInit."/".$dateNow;
						$this->MCrewscv->updateData("idperson = '".$idPerson."'", $updateData, "mstpersonal");
					}

					$stData = array(
						"success" => true,
						"message" => "Data crew berhasil disimpan!.",
						"idperson" => $idPerson
					);
				}
			}

        } catch (Exception $ex) {
            $stData = array("success" => false, "error" => "Failed => ".$ex->getMessage());
        }
        
        header('Content-Type: application/json');
        echo json_encode($stData);
    }

    function saveAllCertificate()
	{
		$dataContext = new DataContext();
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/H:i:s");
		$data = $_POST;
		$dataIns = array();
		$dataInsPersonal = array();

		$idEdit = $data['idEdit'];
		$idPerson = $data['idPerson'];
		$displayCert = "N";
		$useThisAllCert = "N";
		$dir = FCPATH . "uploadCertificate";

		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}

		$dataIns['idperson'] = $idPerson;

		if ($data['slcMstCert'] != "") {
			$rsl = $this->MCrewscv->getData("*", "mstcert", "deletests = '0' AND kdcert = '".$data['slcMstCert']."'");

			if (count($rsl) > 0) {
				$dataIns['kdcert'] = $rsl[0]->kdcert;
				$dataIns['certgroup'] = $rsl[0]->certgroup;
				$dataIns['certname'] = $rsl[0]->certname;
				$dataIns['dispname'] = $rsl[0]->dispname;
			}
		}

		if ($data['useThisAll'] != "") {
			$useThisAllCert = "Y";
		}

		if ($data['certDisplay'] != "") {
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
			$this->MCrewscv->updateData($whereNya, $dataInsPersonal, "mstpersonal");

			
			if ($idEdit == "") {
				$dataIns['addusrdt'] = $userInit."/".$dateNow;
				$idEdit = $this->MCrewscv->insData("tblcertdoc", $dataIns, "IdCertDoc");
			} else {
				$dataIns['updusrdt'] = $userInit."/".$dateNow;
				$whereNya = "idcertdoc = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya, $dataIns, "tblcertdoc");
			}

			if (isset($_FILES['fileUpload']) && $_FILES['fileUpload']['name'] != "") {
				$fileName = $_FILES['fileUpload']['name'];
				$newFileName = "certificateDoc_" . $idPerson . "_" . $idEdit;

				$fileUploadNya = $dataContext->uploadFile(
					$_FILES['fileUpload']['tmp_name'],
					$dir,
					$fileName,
					$newFileName
				);

				if ($fileUploadNya != "") {
					$dataInsFile = array(
						'certificate_file' => $fileUploadNya,
						'certificate_status' => 'Sudah diupload oleh crew'
					);
				} else {
					$dataInsFile = array(
						'certificate_status' => 'Gagal upload file'
					);
				}

				$whereNya = "idcertdoc = '".$idEdit."' AND idperson = '".$idPerson."'";
				$this->MCrewscv->updateData($whereNya, $dataInsFile, "tblcertdoc");
			}

			$stData = "Submit Success..!!";

		} catch (Exception $ex) {
			$stData = "Failed => " . $ex->getMessage();
		}

		print $stData;
	}

    function getLatestIdPerson()
	{
		$idperson = $this->session->userdata('idPersonUserCrewLoginSystem');

		if (empty($idperson)) {
			print json_encode(array('error' => 'Session idperson tidak ditemukan.'));
			return;
		}

		$query = "
			SELECT 
				p.idperson, 
				TRIM(CONCAT(p.fname, ' ', p.mname, ' ', p.lname)) AS fullName,
				p.email,
				p.mobileno,
				p.dob,
				k.KdKota AS pob_code,
				k.NmKota AS pob_name,
				p.applyfor,
				p.crew_vessel_type,
				p.gender,
				p.religion
			FROM mstpersonal p
			LEFT JOIN tblkota k ON k.KdKota = p.pob AND k.Deletests = '0'
			WHERE p.deletests = '0'
			AND p.idperson = '".$idperson."'
			LIMIT 1
		";

		$rsl = $this->MCrewscv->getDataQuery($query);

		header('Content-Type: application/json');
		if (count($rsl) > 0) {
			print json_encode($rsl[0]);
		} else {
			print json_encode(array('error' => 'Data personal tidak ditemukan untuk ID ini.'));
		}
	}

    private function splitFullName($fullname)
    {
        $words = array_filter(explode(' ', trim($fullname)));
        $result = array(
            'fname' => '',
            'mname' => '',
            'lname' => ''
        );

        if (empty($words)) {
            return $result;
        }

        $wordCount = count($words);

        if ($wordCount <= 3) {
            $result['fname'] = isset($words[0]) ? $words[0] : '';
            $result['mname'] = isset($words[1]) ? $words[1] : '';
            $result['lname'] = isset($words[2]) ? $words[2] : '';
        } else if ($wordCount == 4) {
            $result['fname'] = $words[0];
            $result['mname'] = $words[1];
            $result['lname'] = implode(' ', array_slice($words, 2));
        } else if ($wordCount == 5) {
            $result['fname'] = $words[0];
            $result['mname'] = $words[1];
            $result['lname'] = implode(' ', array_slice($words, 2));
        } else if ($wordCount == 6) {
            $result['fname'] = implode(' ', array_slice($words, 0, 2));
            $result['mname'] = implode(' ', array_slice($words, 2, 2));
            $result['lname'] = implode(' ', array_slice($words, 4, 2));
        } else {
            $result['fname'] = implode(' ', array_slice($words, 0, 2));
            $result['mname'] = implode(' ', array_slice($words, 2, 2));
            $result['lname'] = implode(' ', array_slice($words, 4));
        }

        return $result;
    }
    
	function getCrewDataWithCertificate($idperson = "")
	{
		$idperson = $this->input->get('idperson');
		if (empty($idperson)) {
			$idperson = $this->session->userdata('idPersonUserCrewLoginSystem');
		}

		if (empty($idperson)) {
			print json_encode(array('error' => 'idperson tidak ditemukan di request atau session.'));
			return;
		}

		$queryPersonal = "
			SELECT 
				p.idperson, 
				TRIM(CONCAT(p.fname, ' ', p.mname, ' ', p.lname)) AS fullName,
				p.email,
				p.mobileno,
				p.dob,
				k.NmKota AS pob,
				p.applyfor,
				p.crew_vessel_type,
				p.gender,
				p.religion,
				p.pic
			FROM mstpersonal p
			LEFT JOIN tblkota k ON k.KdKota = p.pob AND k.Deletests = '0'
			WHERE p.idperson = '".$idperson."' AND p.deletests = '0'
			LIMIT 1
		";
		$personal = $this->MCrewscv->getDataQuery($queryPersonal);

		$queryCert = "
			SELECT 
				idcertdoc,
				idperson,
				certname,
				docno,
				expdate,
				nmnegara,
				certificate_file
			FROM tblcertdoc 
			WHERE idperson = '".$idperson."' AND deletests = '0'
			ORDER BY addusrdt DESC
		";
		$certList = $this->MCrewscv->getDataQuery($queryCert);

		foreach ($certList as &$cert) {
			$cleanFile = str_replace(array('\\', '//'), '/', trim($cert->certificate_file));

			$hasUploadFolder = (strpos($cleanFile, 'uploadCertificate/') !== false);

			if (!$hasUploadFolder && $cleanFile != '') {
				$cleanFile = 'uploadCertificate/' . $cleanFile;
			}

			$filePath = FCPATH . $cleanFile;

			if ($cleanFile != '' && file_exists($filePath)) {
				$cert->certificate_status = "Tersedia";
				$cert->certificate_file = $cleanFile;
			} else {
				$cert->certificate_status = "Belum diupload oleh crew";
				$cert->certificate_file = "";
			}
		}


		header('Content-Type: application/json');
		print json_encode(array(
			'personal' => $personal ? $personal[0] : null,
			'certificates' => $certList
		));
	}

	function deleteCertificate()
	{
		$idcert = $this->input->post('idcert');

		if (!$idcert) {
			echo json_encode(array('status' => 'error', 'message' => 'idcert tidak ditemukan'));
			return;
		}
		
		$query = "
			SELECT certificate_file 
			FROM tblcertdoc 
			WHERE idcertdoc = '".$idcert."' 
			AND deletests = '0'
			LIMIT 1
		";
		$certData = $this->MCrewscv->getDataQuery($query);

		if ($certData && !empty($certData[0]->certificate_file)) {

			$file = trim($certData[0]->certificate_file);
			$file = str_replace(array('\\', '//'), '/', $file);

			if (strpos($file, 'uploadCertificate/') === false) {
				$file = 'uploadCertificate/' . $file;
			}
			$fullPath = FCPATH . $file;
			if (file_exists($fullPath)) {
				unlink($fullPath);
			}
		}

		$this->db->where('idcertdoc', $idcert);
		$this->db->update('tblcertdoc', array(
			'deletests'   => '1'
		));

		$this->MCrewscv->delData("tblcertdoc", array("idcertdoc" => $idcert));

		echo json_encode(array('status' => 'success', 'message' => 'Sertifikat berhasil dihapus'));
	}

	function saveDataPersonalId()
	{
		$dataContext = new DataContext();
		$data = $_POST;
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");
		$stData = "";
		$dir = "./uploadFile";

		try {
			$idPerson = $data['idPerson'];


			$newId = $dataContext->getNewId("idperdoc","tblpersonaldoc","WHERE idperson = '".$idPerson."'");

			$dataIns = array();
			$dataIns['idperdoc'] = $newId;
			$dataIns['idperson'] = $idPerson;
			$dataIns['docissplc'] = $data['txtIssueAtPlace'];
			$dataIns['docissctryid'] = $data['slcCountryIssuePI'];
			$dataIns['docissdt'] = $data['txtDate_issuePI'];
			$dataIns['docexpdt'] = $data['txtDate_validUntiPI'];
			$dataIns['doctp'] = $data['txtTypeDocPI'];
			$dataIns['docno'] = $data['txtNoDocPI'];
			$dataIns['addusrdt'] = $userInit."/".$dateNow;

			$this->MCrewscv->insData("tblpersonaldoc", $dataIns);

			if (isset($data['cekFileUpload']) && $data['cekFileUpload'] == "yes") {

				$fileName = $_FILES["fileUpload"]["name"];
				$newFileName = "personalId_".$idPerson."_".$newId;

				$uploaded = $dataContext->uploadFile(
					$_FILES["fileUpload"]['tmp_name'],
					$dir,
					$fileName,
					$newFileName
				);

				$this->MCrewscv->updateData(
					"idperdoc = '".$newId."' AND idperson = '".$idPerson."'",
					array("doc_file" => $uploaded),
					"tblpersonaldoc"
				);
			}

			$stData = "Submit Success..!!";

		} catch (Exception $ex) {
			$stData = "Failed => ".$ex->getMessage();
		}

		print $stData;
	}


	function getCertificateDetailByCertId()
	{
		$idPerson = $this->input->post('idPerson');
		$certId   = $this->input->post('cert_id');

		if (!$idPerson || !$certId) {
			echo json_encode(array());
			return;
		}

		$sqlDoc = "
			SELECT *
			FROM tblcertdoc
			WHERE idperson = '$idPerson'
			AND kdcert = '$certId'
			AND deletests = '0'
			LIMIT 1
		";

		$doc = $this->MCrewscv->getDataQuery($sqlDoc);

		if ($doc) {
			echo json_encode($doc[0]);
			return;
		}

		echo json_encode(array(
			'kdcert'           => $certId,
			'docno'            => '',
			'issdate'          => '',
			'expdate'          => '',
			'issplace'         => '',
			'issauth'          => '',
			'remarks'          => '',
			'license'          => '',
			'level'            => '',
			'kdrank'           => '',
			'vsltype'          => '',
			'kdnegara'         => '',
			'certificate_file' => ''
		));
	}

	function getCrewCertificatesOption()
	{
		$opt = "<option value=\"\">Select Certificate</option>";

		$rsl = $this->MCrewscv->getData(
			"*",
			"mstcert",
			"deletests = '0'",
			"certgroup, certname ASC"
		);

		foreach ($rsl as $val) {
			$displayName = "(" . $val->certgroup . ") " . $val->certname;
			$opt .= "<option value=\"" . $val->kdcert . "\">" . $displayName . "</option>";
		}

		echo $opt; 
	}


	function checkPersonalData()
	{
		$idPerson = $this->input->post('idperson');
		$exists = false;

		if ($idPerson) {
			$check = $this->MCrewscv->getDataQuery("
				SELECT idperson FROM mstpersonal 
				WHERE idperson = '".$idPerson."' AND deletests = 0
			");
			if (!empty($check)) {
				$exists = true;
			}
		}

		echo json_encode(array("exists" => $exists));
	}

    function loginCrew() 
	{	
		$data = $_POST;
		$user = strtolower(trim($data['user']));
		$pass = md5(strtolower(trim($data['pass'])));

		$sql = "SELECT * FROM crew_login WHERE username = '".$user."' AND password = '".$pass."' AND sts_delete = '0'";
		$cekLogin = $this->MCrewscv->getDataQuery($sql);

		if(count($cekLogin) > 0) {	
			$this->session->set_userdata('idUserCrewLoginSystem',$cekLogin[0]->id);
			$this->session->set_userdata('idPersonUserCrewLoginSystem',$cekLogin[0]->idperson);
			$this->session->set_userdata('fullnameUserCrewLoginSystem',$cekLogin[0]->fullname);
			$this->session->set_userdata('userCrewLoginSystem',$cekLogin[0]->username);

			$result = array("status" => true);
		} else {
			$result = array("status" => false);
		}

		echo json_encode($result);
	}

    function logOut()
	{
		$this->session->unset_userdata('idUserCrewLoginSystem');
		$this->session->unset_userdata('idPersonUserCrewLoginSystem');
		$this->session->unset_userdata('fullnameUserCrewLoginSystem');
		$this->session->unset_userdata('userCrewLoginSystem');
		// $this->session->sess_destroy();
		redirect(base_url());
	}
}