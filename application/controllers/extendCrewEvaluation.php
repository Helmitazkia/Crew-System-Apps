<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ExtendCrewEvaluation extends CI_Controller {

    function __construct()
	{
		parent::__construct();
		
		$this->load->model('MCrewscv');
		$this->load->helper(array('form', 'url'));
		$this->load->library('../controllers/DataContext');
	}
	
    function getDataPage($token, $idPerson, $personName, $rank, $vessel, $masterName, $chiefName, $chiefRank)
	{
		$sql = "
			SELECT link_status 
			FROM crew_evaluation_report
			WHERE link_token = '$token'
			LIMIT 1
		";

		$tokenData = $this->MCrewscv->getDataQuery($sql);

		if (!$tokenData) {
			echo "<h2>Invalid evaluation link.</h2>";
			exit;
		}

		if ($tokenData[0]->link_status !== "ACTIVE") {
			echo "<h2>This evaluation link has expired or already been used.</h2>";
			exit;
		}

		// Decode & load page
		$dataOut['idperson']     = base64_decode($idPerson);
		$dataOut['idpersonEncrypted'] = base64_encode(base64_encode(base64_encode($dataOut['idperson'])));
		$dataOut['personName']   = base64_decode($personName);
		$dataOut['rank']         = base64_decode($rank);
		$dataOut['vessel']       = base64_decode($vessel);
		$dataOut['masterName']   = base64_decode($masterName);
		$dataOut['chiefName']    = base64_decode($chiefName);
		$dataOut['chiefRank']    = base64_decode($chiefRank);

		$dataOut['token'] = $token;

		$this->load->view('frontend/extendCrewEvaluation', $dataOut);
	}



	function getFormNewApplicant()
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dataOut['optRank'] = $dataContext->getRankByOption("","name");
		$dataOut['liNamaJabatan'] = $dataContext->getRecruitment();
		$this->load->view('frontend/formNewApplicant', $dataOut);
	}

	function getLoginCrew() {
		
		$this->load->view('frontend/loginCrew');
	}

	function fna() {
		$this->getFormNewApplicant();
	}

	// function saveNewApplicant()
	// {
	// 	$data = $_POST;
	// 	$files = $_FILES;
	// 	$dataIns = array();

	// 	try {
	// 		if (empty($data['txtemail']) || !filter_var($data['txtemail'], FILTER_VALIDATE_EMAIL)) {
	// 			throw new Exception("Email tidak valid");
	// 		}

	// 		$email       = $data['txtemail'];
	// 		$fullname    = $data['txtnama'];
	// 		$born_place  = $data['txttempat_lahir'];
	// 		$born_date   = $data['txttanggal_lahir'];
	// 		$handphone   = preg_replace('/[^0-9]/', '', $data['txthandphone']);

	// 		$today = new DateTime();
	// 		$birth = DateTime::createFromFormat('Y-m-d', $born_date);
	// 		if (!$birth) throw new Exception("Format tanggal lahir tidak valid.");
	// 		$age = $today->diff($birth)->y;
	// 		if ($age < 18 || $age > 50) throw new Exception("Usia pelamar harus antara 18 hingga 50 tahun.");

	// 		$dataIns = array(
	// 			'email'                  => $email,
	// 			'fullname'               => $fullname,
	// 			'born_place'             => $born_place,
	// 			'born_date'              => $born_date,
	// 			'handphone'              => $handphone,
	// 			'position_applied'       => $data['position_applied'],
	// 			'ijazah_terakhir'        => $data['ijazah_terakhir'],
	// 			'last_experience'        => $data['pengalaman_terakhir'],
	// 			'last_salary'            => $data['last_salary'],
	// 			'join_inAndhika'         => ($data['pernah_join'] === 'Y') ? 'Y' : 'N',
	// 			'info_source'            => $data['info_source'],
	// 			'pengalaman_jeniskapal'  => isset($data['kapal']) ? implode(', ', $data['kapal']) : '',
	// 			'gender'                 => isset($data['gender']) ? $data['gender'] : null,
	// 			'berlayardengancrewasing'=> $data['crew_foreign'] === 'Y' ? 'Y - ' . (isset($data['foreign_country']) ? $data['foreign_country'] : '') : 'N',
	// 			'alasan_gabung'          => isset($data['alasan_gabung']) ? trim($data['alasan_gabung']) : '',
	// 			'submit_cv'              => date('Y-m-d H:i:s')
	// 		);
			
	// 		if (stripos($data['position_applied'], 'cadet') !== false) {
	// 			$dataIns['last_experience'] = ''; 
	// 		} else {
	// 			$dataIns['last_experience'] = $data['pengalaman_terakhir'];
	// 		}


	// 		$uploadDir = 'assets/uploads/CV_NewApplicant/';
	// 		if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

	// 		if (!empty($files['cv_files']['tmp_name'][0])) {
	// 			$tmpName = $files['cv_files']['tmp_name'][0];
	// 			$fileName = basename($files['cv_files']['name'][0]);
	// 			$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
	// 			$fileSize = $files['cv_files']['size'][0];
	// 			$fileError = $files['cv_files']['error'][0];

	// 			if ($fileError !== UPLOAD_ERR_OK) throw new Exception("Error upload file: $fileName");
	// 			if ($fileType !== 'pdf') throw new Exception("Hanya PDF yang diizinkan: $fileName");
	// 			if ($fileSize > 5 * 1024 * 1024) throw new Exception("File terlalu besar (maks 5MB): $fileName");

	// 			$newFileName = "NewApplicant_" . date('YmdHis') . "_." . $fileType;
	// 			if (!move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
	// 				throw new Exception("Gagal menyimpan CV: $fileName");
	// 			}

	// 			$dataIns['new_cv'] = $newFileName;
	// 		} else {
	// 			throw new Exception("Harap unggah CV");
	// 		}

	// 		$this->MCrewscv->insData('new_applicant', $dataIns);
	// 		$this->sendSubmitNotification($email, $fullname);


	// 		echo json_encode(array('status' => 'success', 'message' => 'Data berhasil disimpan.'));
	// 	} catch (Exception $e) {
	// 		echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
	// 	}
	// }

	function saveNewApplicant()
	{
		$data = $_POST; 
		$files = $_FILES;
		$dataIns = array();

		try {
			if (empty($data['txtemail']) || !filter_var($data['txtemail'], FILTER_VALIDATE_EMAIL)) {
				throw new Exception("Email tidak valid");
			}

			$applicantId = $data['txtIdNewApplicant'];
			$email       = $data['txtemail'];
			$fullname    = $this->db->escape_str(trim($data['txtnama']));
			$born_place  = $data['txttempat_lahir'];
			$born_date   = $data['txttanggal_lahir'];
			$handphone   = preg_replace('/[^0-9]/', '', $data['txthandphone']);
			$position    = $data['position_applied'];
			$joinDate = $data['join_date']; 

			$today = new DateTime();
			$birth = DateTime::createFromFormat('Y-m-d', $born_date);
			if (!$birth) throw new Exception("Format tanggal lahir tidak valid.");
			$age = $today->diff($birth)->y;
			if ($age < 18 || $age > 55) throw new Exception("Usia pelamar harus antara 18 hingga 55 tahun.");

			$dataIns = array(
				'id'                     => $applicantId,
				'email'                  => $email,
				'fullname'               => $fullname,
				'born_place'             => $born_place,
				'born_date'              => $born_date,
				'handphone'              => $handphone,
				'position_applied'       => $position,
				'position_existing'      => $position,
				'ijazah_terakhir'        => $data['ijazah_terakhir'],
				'join_inAndhika'         => ($data['pernah_join'] === 'Y') ? 'Y' : 'N',
				'join_date'  			 => $joinDate,
				'info_source'            => $data['info_source'],
				'gender'                 => isset($data['gender']) ? $data['gender'] : null,
			);

			if (stripos($position, 'cadet') !== false) {
				if (empty($data['ipk_terakhir'])) {
					throw new Exception("IPK wajib diisi untuk posisi Cadet.");
				}
				if (empty($data['sekolah']) || empty($data['jurusan'])) {
					throw new Exception("Sekolah dan Jurusan wajib diisi untuk posisi Cadet.");
				}

				$dataIns['ipk_terakhir']          = $data['ipk_terakhir'];
				$dataIns['last_experience']       = ''; 
				$dataIns['berlayardengancrewasing'] = 'N';
				$dataIns['pengalaman_jeniskapal'] = '';
				$dataIns['last_salary']           = '';
				$dataIns['sekolah']               = $data['sekolah'];
				$dataIns['jurusan']               = $data['jurusan'];

			} else {
				if (empty($data['pengalaman_terakhir'])) {
					throw new Exception("Pengalaman terakhir wajib diisi untuk posisi non-Cadet.");
				}
				if (empty($data['last_salary'])) {
					throw new Exception("Gaji terakhir wajib diisi untuk posisi non-Cadet.");
				}

				$crewForeign = ($data['crew_foreign'] === 'Y') 
					? 'Y - ' . (isset($data['foreign_country']) ? $data['foreign_country'] : '') 
					: 'N';

				$dataIns['last_experience']        = $data['pengalaman_terakhir'];
				$dataIns['ipk_terakhir']           = '';
				$dataIns['last_salary']            = $data['last_salary'];
				$dataIns['pengalaman_jeniskapal']  = isset($data['kapal']) ? implode(', ', $data['kapal']) : '';
				$dataIns['berlayardengancrewasing']= $crewForeign;
				$dataIns['sekolah']                = '';
				$dataIns['jurusan']                = '';
			}

			$uploadDir = 'assets/uploads/CV_NewApplicant/';
			if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

			if (!empty($files['cv_files']['tmp_name'][0])) {
				$tmpName  = $files['cv_files']['tmp_name'][0];
				$fileName = basename($files['cv_files']['name'][0]);
				$fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
				$fileSize = $files['cv_files']['size'][0];
				$fileError= $files['cv_files']['error'][0];

				if ($fileError !== UPLOAD_ERR_OK) throw new Exception("Error upload file: $fileName");
				if ($fileType !== 'pdf') throw new Exception("Hanya PDF yang diizinkan: $fileName");
				if ($fileSize > 5 * 1024 * 1024) throw new Exception("File terlalu besar (maks 5MB): $fileName");

				$newFileName = "NewApplicant_" . date('YmdHis') . "_." . $fileType;
				if (!move_uploaded_file($tmpName, $uploadDir . $newFileName)) {
					throw new Exception("Gagal menyimpan CV: $fileName");
				}

				$dataIns['new_cv'] = $newFileName;
			} else {
				throw new Exception("Harap unggah CV");
			}

			$this->MCrewscv->insData('new_applicant', $dataIns);
			$this->sendSubmitNotification($email, $fullname);

			echo json_encode(array('status' => 'success', 'message' => 'Data berhasil disimpan.'));			

		} catch (Exception $e) {
			echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
		}
	}

	// function checkEmail()
	// {
	// 	$email = $this->input->post('email');

	// 	if (empty($email)) {
	// 		echo json_encode(array('status' => 'error', 'message' => 'Email kosong'));
	// 		return;
	// 	}

	// 	$sql = "SELECT * FROM new_applicant WHERE email = '$email' AND deletests = 0 LIMIT 1";

	// 	try {
	// 		$existing = $this->MCrewscv->getDataQuery($sql); 
	// 		if (!empty($existing)) {
	// 			$data = $existing[0];

	// 			$data->kapalList = !empty($data->pengalaman_jeniskapal) ? explode(', ', $data->pengalaman_jeniskapal) : array();

	// 			if (strpos($data->berlayardengancrewasing, 'Y - ') === 0) {
	// 				$data->crew_foreign = 'Y';
	// 				$data->foreign_country = substr($data->berlayardengancrewasing, 4);
	// 			} else {
	// 				$data->crew_foreign = 'N';
	// 			}

	// 			echo json_encode(array('status' => 'exists', 'data' => $data));
	// 		} else {
	// 			echo json_encode(array('status' => 'not_found'));
	// 		}

	// 	} catch (Exception $e) {
	// 		echo json_encode(array('status' => 'error', 'message' => $e->getMessage()));
	// 	}
	// }

    function saveDataCrewEvaluation()
	{
		$data = $_POST;

		$idPerson = base64_decode(base64_decode(base64_decode($data['txtIdPerson'])));
		$userDate = $this->session->userdata('userCrewSystem') . "/" . date('Ymd') . "/" . date('H:i:s');

		try {

			$q = "
				SELECT id 
				FROM crew_evaluation_report 
				WHERE idperson = '$idPerson'
				ORDER BY id DESC LIMIT 1
			";

			$last = $this->MCrewscv->getDataQuery($q);

			if (!$last) {
				throw new Exception("No active evaluation report found!");
			}

			$reportId = $last[0]->id;

			$update = array(
				'vessel'                    => isset($data['vessel']) ? $data['vessel'] : '',
				'seafarer_name'             => isset($data['personName']) ? $data['personName'] : '',
				'rank'                      => isset($data['rank']) ? $data['rank'] : '',
				'date_of_report'            => isset($data['txtDateOfReport']) ? $data['txtDateOfReport'] : '',
				'reporting_period_from'     => isset($data['txtDateOfReportingPeriodFrom']) ? $data['txtDateOfReportingPeriodFrom'] : '',
				'reporting_period_to'       => isset($data['txtDateOfReportingPeriodTo']) ? $data['txtDateOfReportingPeriodTo'] : '',
				'reason_midway_contract'    => isset($data['reasonMidway']) ? $data['reasonMidway'] : '',
				'reason_signing_off'        => isset($data['reasonSigningOff']) ? $data['reasonSigningOff'] : '',
				'reason_leaving_vessel'     => isset($data['reasonLeaving']) ? $data['reasonLeaving'] : '',
				'reason_special_request'    => isset($data['reasonSpecialRequest']) ? $data['reasonSpecialRequest'] : '',
				'master_comments'           => isset($data['txtMasterComments']) ? $data['txtMasterComments'] : '',
				'reporting_officer_comments'=> isset($data['txtOfficerComments']) ? $data['txtOfficerComments'] : '',
				'promote'                   => isset($data['txtPromoted']) ? $data['txtPromoted'] : '',
				're_employ'                 => (isset($data['txtReemploy']) && $data['txtReemploy'] !== '') ? $data['txtReemploy'] : 'N',
				'reporting_officer_name'    => isset($data['chiefName']) ? $data['chiefName'] : '',
				'reporting_officer_rank'    => isset($data['chiefRank']) ? $data['chiefRank'] : '',
				'mastercoofullname'         => isset($data['masterName']) ? $data['masterName'] : '',
				'received_by_cm'            => isset($data['txtreceived']) ? $data['txtreceived'] : '',
				'date_of_receipt'           => isset($data['txtDateReceipt']) ? $data['txtDateReceipt'] : '',
				'st_submit_chief'           => 'Y',
			);

			$this->MCrewscv->updateData(
				array("id" => $reportId),
				$update,
				"crew_evaluation_report"
			);

			$criteriaList = array(
				"Ability/Knowledge of Job" 	=> "ability",
				"Safety Consciousness" 		=> "safety",
				"Dependability & Integrity" => "integrity",
				"Initiative" 				=> "initiative",
				"Conduct" 					=> "conduct",
				"Ability to get on with others" => "abilityGetOn",
				"Appearance (+ uniforms)" 	=> "appearance",
				"Sobriety" 					=> "sobriety",
				"English Language" 			=> "english",
				"Leadership (Officers)" 	=> "leadership"
			);

			foreach ($criteriaList as $criteriaName => $id) {

				$value = isset($data[$id]) ? $data[$id] : '';

				$this->MCrewscv->insData("crew_evaluation_criteria", array(
					'idperson'      => $idPerson,
					'id_report'     => $reportId,
					'criteria_name' => $criteriaName,
					'excellent'     => ($value == '4') ? 'Y' : '',
					'good'          => ($value == '3') ? 'Y' : '',
					'fair'          => ($value == '2') ? 'Y' : '',
					'poor'          => ($value == '1') ? 'Y' : '',
					'identify'      => isset($data["txtIdentify".ucfirst($id)]) ? $data["txtIdentify".ucfirst($id)] : '',
					'addUsrDate'    => $userDate
				));
			}

			$this->addDataMyAppLetter($reportId);

			echo json_encode(array(
				"status" => "success",
				"message" => "Crew Evaluation Saved",
				"id" => $reportId
			));

		} catch (Throwable $e) {

			echo json_encode(array(
				"status" => "error",
				"message" => $e->getMessage()
			));
		}
	}



	function sendSubmitNotification($recipientEmail, $fullName)
	{
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		$mail = new PHPMailer();

		try {
			$mail->isSMTP();
			$mail->Host       = 'smtp.zoho.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = 'noreply@andhika.com';
			$mail->Password   = 'PCWLzCWDQH8C';
			$mail->SMTPSecure = 'tls';
			$mail->Port       = 587;

			$mail->setFrom('noreply@andhika.com', 'Crewing PT Andhika Lines');
			$mail->addAddress($recipientEmail);

			$mail->AddEmbeddedImage(APPPATH . '../assets/img/logo_andhika.png', 'logo_andhika');
			
			$mail->isHTML(true);
			$mail->Subject = 'Terima Kasih - Lamaran Anda Telah Diterima';

			$mail->Body = "
				<div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;'>
					<div style='max-width: 600px; margin: auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e0e0e0;'>

						<div style='background-color: #ffffffff; padding: 20px; text-align: center;'>
							<img src='cid:logo_andhika' alt='PT Andhika Lines' style='max-width: 180px;'>
						</div>


						<div style='padding: 30px; color: #333; font-size: 14px; line-height: 1.6;'>
							<p>Yth. <strong>$fullName</strong>,</p>

							<p>Terima kasih atas lamaran Anda ke <strong>PT Andhika Lines</strong>.</p>
							<p>Formulir dan CV Anda telah berhasil kami terima. Tim Crewing kami akan meninjau informasi Anda dan menghubungi Anda lebih lanjut apabila terdapat kecocokan dengan kebutuhan kami saat ini.</p>

							<p>Hormat kami,<br>
							<strong>Tim Crewing</strong><br>
							PT Andhika Lines</p>
						</div>

						<hr style='border: none; border-top: 1px solid #ccc; margin-top: 40px;'>
						
						<div style='background-color: #f9f9f9; padding: 20px; font-size: 13px; color: #555;'>
							<p style='margin-bottom: 8px; font-weight: bold;'>Ikuti kami untuk informasi terbaru:</p>
							<ul style='list-style: none; padding-left: 0; margin: 0;'>
								<li style='margin-bottom: 6px;'>
								<img src='https://cdn-icons-png.flaticon.com/24/1384/1384031.png' alt='Instagram' style='vertical-align: middle; margin-right: 8px;'> 
								<a href='https://www.instagram.com/andhika.group/' style='text-decoration: none; color: #003366;'>@andhika.group</a>
								</li>
								<li style='margin-bottom: 6px;'>
								<img src='https://cdn-icons-png.flaticon.com/24/1384/1384031.png' alt='Instagram' style='vertical-align: middle; margin-right: 8px;'> 
								<a href='https://www.instagram.com/lifeatandhika/' style='text-decoration: none; color: #003366;'>@lifeatandhika</a>
								</li>
								<li>
								<img src='https://cdn-icons-png.flaticon.com/24/841/841364.png' alt='Website' style='vertical-align: middle; margin-right: 8px;'> 
								<a href='https://andhika.com/' style='text-decoration: none; color: #003366;'>www.andhika.com</a>
								</li>
							</ul>

							<p style='margin-top: 20px; font-size: 12px; color: #888; text-align: center;'>
								<em>Email ini dikirim otomatis oleh sistem Crewing PT Andhika Lines. Mohon tidak membalas email ini.</em>
							</p>
						</div>
					</div>
				</div>
			";

			if (!$mail->send()) {
				log_message('error', "Submit Email failed to $recipientEmail: " . $mail->ErrorInfo);
			} else {
				log_message('info', "Submit email sent to $recipientEmail");
			}
		} catch (Exception $e) {
			log_message('error', 'Exception while sending submit email: ' . $e->getMessage());
		}
	}


	function generateLink()
	{
		$data       = $_POST;
		$idPerson   = $data['txtModalGenLinkIdPerson'];
		$department = $data['department'];

		$sql = "SELECT 
					A.idperson,
					TRIM(CONCAT(D.fname, ' ', COALESCE(D.mname, ''), ' ', D.lname)) AS fullName,
					B.nmrank,
					C.nmvsl,
					C.mail_vessel,

					(SELECT TRIM(CONCAT(MP.fname,' ',COALESCE(MP.mname,''),' ',MP.lname))
					FROM tblcontract TC
					JOIN mstpersonal MP ON MP.idperson = TC.idperson
					WHERE TC.signonvsl = A.signonvsl 
					AND TC.signonrank = '044'
					AND TC.signoffdt = '0000-00-00'
					LIMIT 1) AS co_name,

					(SELECT TRIM(CONCAT(MP.fname,' ',COALESCE(MP.mname,''),' ',MP.lname))
					FROM tblcontract TC
					JOIN mstpersonal MP ON MP.idperson = TC.idperson
					WHERE TC.signonvsl = A.signonvsl 
					AND TC.signonrank = '041'
					AND TC.signoffdt = '0000-00-00'
					LIMIT 1) AS ce_name,

					(SELECT TRIM(CONCAT(MP.fname,' ',COALESCE(MP.mname,''),' ',MP.lname))
					FROM tblcontract TC
					JOIN mstpersonal MP ON MP.idperson = TC.idperson
					WHERE TC.signonvsl = A.signonvsl 
					AND TC.signonrank = '037'
					AND TC.signoffdt = '0000-00-00'
					LIMIT 1) AS mastername

				FROM tblcontract A
				LEFT JOIN mstrank B ON B.kdrank = A.signonrank
				LEFT JOIN mstvessel C ON C.kdvsl = A.signonvsl
				LEFT JOIN mstpersonal D ON D.idperson = A.idperson
				WHERE A.signoffdt = '0000-00-00'
				AND A.idperson = '$idPerson'";

		$crewData = $this->MCrewscv->getDataQuery($sql);

		$personName = $crewData[0]->fullName;
		$rank       = $crewData[0]->nmrank;
		$vessel     = $crewData[0]->nmvsl;
		$masterName = $crewData[0]->mastername;

		if ($department == "ENGINE") {
			$chiefName = $crewData[0]->ce_name;
			$chiefRank = "C/E";
		} else {
			$chiefName = $crewData[0]->co_name;
			$chiefRank = "C/O";
		}

		$token = bin2hex(openssl_random_pseudo_bytes(16));

		$q = "
			SELECT id 
			FROM crew_evaluation_report
			WHERE idperson = '$idPerson'
			ORDER BY id DESC LIMIT 1
		";

		$last = $this->MCrewscv->getDataQuery($q);

		if ($last) {
			$lastId = $last[0]->id;

			$this->MCrewscv->updateData(
				array("id" => $lastId),
				array(
					"link_token"  => $token,
					"link_status" => "ACTIVE"
				),
				"crew_evaluation_report"
			);
		} else {

			$this->MCrewscv->insData(
				"crew_evaluation_report",
				array(
					"idperson"    => $idPerson,
					"link_token"  => $token,
					"link_status" => "ACTIVE",
					"created_at"  => date('Y-m-d H:i:s')
				)
			);
		}

		$encoded = array(
			'idperson'    => base64_encode($idPerson),
			'personName'  => base64_encode($personName),
			'rank'        => base64_encode($rank),
			'vessel'      => base64_encode($vessel),
			'masterName'  => base64_encode($masterName),
			'chiefName'   => base64_encode($chiefName),
			'chiefRank'   => base64_encode($chiefRank),
		);

		echo json_encode(array(
			'url' => base_url("extendCrewEvaluation/getDataPage/$token/" .
				$encoded['idperson'].'/'.
				$encoded['personName'].'/'.
				$encoded['rank'].'/'.
				$encoded['vessel'].'/'.
				$encoded['masterName'].'/'.
				$encoded['chiefName'].'/'.
				$encoded['chiefRank']
			),
			'personName' => $personName,
			'rank'       => $rank,
			'vessel'     => $vessel,
			'masterName' => $masterName,
			'coName'     => $crewData[0]->co_name,
			'ceName'     => $crewData[0]->ce_name,
			'emailVessel'=> $crewData[0]->mail_vessel
		));
	}



	function exportPDFCrewEvaluation($id_report)
	{
		$dataOut = array();

		function getChecked($value)
		{
			return ($value === 'Y') ? '&#10004;' : '';
		}

		$sqlReport = "
			SELECT 
				r.*,
				v.os_name
			FROM crew_evaluation_report r
			LEFT JOIN mstvessel v 
				ON v.nmvsl = r.vessel AND v.deletests = 0
			WHERE r.id = '".$id_report."' 
			AND r.deletests = 0
			LIMIT 1
		";

		$reportData = $this->MCrewscv->getDataQuery($sqlReport);

		if (count($reportData) == 0) {
			die("Report not found.");
		}

		$row = $reportData[0];

		$vessel                    = $row->vessel;
		$seafarerName              = $row->seafarer_name;
		$rank                      = $row->rank;
		$dateOfReport              = $row->date_of_report;
		$reportPeriodFrom          = $row->reporting_period_from;
		$reportPeriodTo            = $row->reporting_period_to;
		$masterComments            = $row->master_comments;
		$reportingOfficerComments  = $row->reporting_officer_comments;
		$promote                   = $row->promote;
		$reportingOfficerName      = $row->reporting_officer_name;
		$reportingOfficerRank      = $row->reporting_officer_rank;
		$mastercoofullname         = $row->mastercoofullname;
		$receivedByCM              = $row->received_by_cm;
		$dateOfReceipt             = $row->date_of_receipt;
		$reEmploy                  = $row->re_employ;
		$remark_reject             = $row->remark_reject;
		$technicalSuperintendentFullName = $row->os_name;


		$reasonMidway      = getChecked($row->reason_midway_contract);
		$reasonLeaving     = getChecked($row->reason_leaving_vessel);
		$reasonSigningOff  = getChecked($row->reason_signing_off);
		$reasonSpecial     = getChecked($row->reason_special_request);

		$qrCodePathChief = !empty($row->qrcode_reporting_chief)
			? './assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_chief : '';

		$qrCodePathMaster = !empty($row->qrcode_reporting_master)
			? './assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_master : '';

		$qrCodePathTechnicalSuperintendent = !empty($row->qrcode_technicalsuperintendent)
			? './assets/imgQRCodeCrewCV/' . $row->qrcode_technicalsuperintendent : '';

		$qrCodePathCM = !empty($row->qrcode_reporting_cm)
			? './assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_cm : '';

		$sqlCriteria = "
			SELECT * FROM crew_evaluation_criteria
			WHERE deletests = 0 AND id_report = '".intval($id_report)."'
			ORDER BY id ASC
		";
		$criteriaData = $this->MCrewscv->getDataQuery($sqlCriteria);

		$criteriaTable = '';
		if (count($criteriaData) > 0) {
			foreach ($criteriaData as $criteriaRow) {

				$criteriaTable .= '<tr>';

				$criteriaTable .= '<td style="border:1px solid black; padding:10px;">'
									. $criteriaRow->criteria_name .
								'</td>';

				$criteriaTable .= '<td style="border:1px solid black; padding:10px; text-align:center;">'
									. getChecked($criteriaRow->excellent) .
								'</td>';

				$criteriaTable .= '<td style="border:1px solid black; padding:10px; text-align:center;">'
									. getChecked($criteriaRow->good) .
								'</td>';

				$criteriaTable .= '<td style="border:1px solid black; padding:10px; text-align:center;">'
									. getChecked($criteriaRow->fair) .
								'</td>';

				$criteriaTable .= '<td style="border:1px solid black; padding:10px; text-align:center;">'
									. getChecked($criteriaRow->poor) .
								'</td>';

				$criteriaTable .= '<td style="border:1px solid black; padding:10px; text-align:center;">'
									. $criteriaRow->identify .
								'</td>';

				$criteriaTable .= '<td style="border:1px solid black; padding:10px; text-align:center;">'
									. $criteriaRow->technical_comments .
								'</td>';

				$criteriaTable .= '</tr>';
			}
		}


		$dataOut = array(
			'id_report' => $id_report,
			'vessel' => $vessel,
			'seafarerName' => $seafarerName,
			'rank' => $rank,
			'dateOfReport' => $dateOfReport,
			'reportPeriodFrom' => $reportPeriodFrom,
			'reportPeriodTo' => $reportPeriodTo,
			'reasonMidway' => $reasonMidway,
			'reasonLeaving' => $reasonLeaving,
			'reasonSigningOff' => $reasonSigningOff,
			'reasonSpecial' => $reasonSpecial,
			'criteriaTable' => $criteriaTable,
			'masterComments' => $masterComments,
			'reportingOfficerComments' => $reportingOfficerComments,
			'promote' => $promote,
			'reportingOfficerName' => $reportingOfficerName,
			'reportingOfficerRank' => $reportingOfficerRank,
			'mastercoofullname' => $mastercoofullname,
			'receivedByCM' => $receivedByCM,
			'dateOfReceipt' => $dateOfReceipt,
			'reEmploy' => $reEmploy,
			'qrCodeImg' => $qrCodePathChief,
			'qrCodePathMaster' => $qrCodePathMaster,
			'qrCodePathSuperintendent' => $qrCodePathTechnicalSuperintendent,
			'qrCodePathCM' => $qrCodePathCM,
			'remark_reject' => $remark_reject,
			'technicalSuperintendentFullName' => $technicalSuperintendentFullName
		);

		require("application/views/frontend/pdf/mpdf60/mpdf.php");
		$mpdf = new mPDF('utf-8', 'A4');
		ob_start();
		$this->load->view('frontend/exportPDFCrewEvaluation', $dataOut);
		$html = ob_get_contents();
		ob_end_clean();
		$mpdf->WriteHTML(utf8_encode($html));
		$mpdf->Output("Crew_Evaluation_Report_".$seafarerName.".pdf", 'I');
		exit;
	}
	
	function printCrewEvaluation($idPerson = "") {
		$dataOut = array();
		$decryptedId = base64_decode(base64_decode(base64_decode($idPerson)));

		$vessel = '';
		$reEmploy = '';
		$seafarerName = '';
		$superintendentFullName = '';
		$rank = '';
		$dateOfReport = '';
		$reportPeriodFrom = '';
		$reportPeriodTo = '';
		$masterComments = '';
		$reportingOfficerComments = '';
		$promote = '';
		$reportingOfficerName = '';
		$reportingOfficerRank = '';
		$receivedByCM = '';
		$dateOfReceipt = '';
		$mastercoofullname = '';
		$reasonMidway = '';
		$reasonLeaving = '';
		$reasonSigningOff = '';
		$reasonSpecial = '';
		$criteriaTable = '';
		$qrCodePathChief = '';
		$qrCodePathMaster = '';
		$qrCodePathSuperintendent = '';
		$qrCodePathCM = '';
		$Btnact = "";
		$Btnreject = "";
		$remark_reject = "";
		$label_reject = "";

		function getChecked($value) {
			return ($value === 'Y') ? '&#10004;' : '';
		}
			
		$sqlReport = "
			SELECT r.*, v.os_name 
			FROM crew_evaluation_report r
			LEFT JOIN mstvessel v ON v.nmvsl = r.vessel
			WHERE r.idperson = '".$decryptedId."' 
			ORDER BY r.id DESC 
			LIMIT 0,1
		";
		$reportData = $this->MCrewscv->getDataQuery($sqlReport);


		$sqlCriteria = "SELECT * FROM crew_evaluation_criteria 
					WHERE deletests = '0' AND id_report = '".$reportData[0]->id."' ORDER BY id ASC";
					
		$criteriaData = $this->MCrewscv->getDataQuery($sqlCriteria);
		$isTSEditable = false;
		if ($reportData[0]->st_submit_master == 'Y' && $reportData[0]->st_submit_technicalsuperintendent == 'N') {
			$isTSEditable = true;
		}
	
		if (count($reportData) > 0) {
			$row = $reportData[0];

			$vessel = $row->vessel;
			$seafarerName = $row->seafarer_name;
			$superintendentFullName = $row->os_name;
			$rank = $row->rank;
			$dateOfReport = $row->date_of_report;
			$reportPeriodFrom = $row->reporting_period_from;
			$reportPeriodTo = $row->reporting_period_to;
			$masterComments = $row->master_comments;
			$reportingOfficerComments = $row->reporting_officer_comments;
			$promote = $row->promote;
			$reportingOfficerName = $row->reporting_officer_name;
			$reportingOfficerRank = $row->reporting_officer_rank;
			$mastercoofullname = $row->mastercoofullname;
			$receivedByCM = $row->received_by_cm;
			$dateOfReceipt = $row->date_of_receipt;
			$reEmploy = $row->re_employ;
			$remark_reject = $row->remark_reject;

			$reasonMidway = getChecked($row->reason_midway_contract);
			$reasonLeaving = getChecked($row->reason_leaving_vessel);
			$reasonSigningOff = getChecked($row->reason_signing_off);
			$reasonSpecial = getChecked($row->reason_special_request);
			
			if (!empty($row->qrcode_reporting_chief)) {
				$qrCodePathChief = "<img src=\"".base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_chief)."\" alt=\"QR Code\" class=\"img-responsive\" style=\"max-width:80px;\">";
			}
			else {
				$qrCodePathChief = "";
			}
			
			if (!empty($row->qrcode_reporting_master)) {
				$qrCodePathMaster = "<img src=\"".base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_master)."\" alt=\"QR Code\" class=\"img-responsive\" style=\"max-width:80px;\">";
			}
			else {
				$qrCodePathMaster = "";
			}
			 
			if (!empty($row->qrcode_technicalsuperintendent)) {
				$qrCodePathSuperintendent = "<img src=\"".base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_technicalsuperintendent)."\" alt=\"QR Code\" class=\"img-responsive\" style=\"max-width:80px;\">";
			}
			else{
				$qrCodePathSuperintendent = "";
			}
			
			if (!empty($row->qrcode_reporting_cm)) {
				$qrCodePathCM = "<img src=\"".base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_cm)."\" alt=\"QR Code\" class=\"img-responsive\" style=\"max-width:80px;\">";
			}
			else{
				$qrCodePathCM = "";
			}

			if (count($criteriaData) > 0) {
				foreach ($criteriaData as $criteriaRow) {

					$criteriaTable .= '<tr>';

					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: left;">'.$criteriaRow->criteria_name.'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->excellent).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->good).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->fair).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->poor).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.$criteriaRow->identify.'</td>';

					if ($isTSEditable) {

						$existingTS = isset($criteriaRow->technical_comments) ? $criteriaRow->technical_comments : '';

						$criteriaTable .= '
							<td style="border: 1px solid black; padding: 10px;">
								<input type="text" 
									class="form-control ts-comment"
									data-criteria-id="'.$criteriaRow->id.'" 
									style="width:100%;" 
									value="'.$existingTS.'"/>
							</td>
						';

					} 
					else {

						$displayTS = !empty($criteriaRow->technical_comments)
							? nl2br($criteriaRow->technical_comments)
							: '&nbsp;';

						$criteriaTable .= '
							<td style="border: 1px solid black; padding: 10px;">'.$displayTS.'</td>
						';
					}
					$criteriaTable .= '</tr>';
				}
			}

		}
		
		if ($reportData[0]->st_submit_chief == 'Y' && $reportData[0]->st_submit_master == 'N') {
			$Btnact = '
				<div class="col-md-6 d-flex align-items-end">
					<button type="button" class="btn btn-primary btn-block" id="btnApproveMaster" onclick="approveMaster();"><i class="fa fa-thumbs-up" style="font-size:15px"> Approve Master</i></button>
				</div>
			';
			$masterComments = '
				<div class="col-md-6">
					<label class="form-label">Master Comments</label>
					<textarea class="form-control" name="comments_master" rows="6" placeholder="Master\'s comments"
						id="txtMasterComments"></textarea>
				</div>
			';
		} else if ($reportData[0]->st_submit_master == 'Y' && $reportData[0]->st_submit_technicalsuperintendent == 'N') {
			
			$Btnact = '<button type="button" class="btn btn-primary btn-block" onclick="saveTSCommentsAndApprove();"><i class="fa fa-thumbs-up" style="font-size:15px"> Approve Technical Superintendent</i></button>';
			
		} else if ($reportData[0]->st_submit_technicalsuperintendent == 'Y' && $reportData[0]->st_submit_cm == 'N') {
			if ($row->st_reject !== 'Y') {
				
				$Btnact = '<button type="button" class="btn btn-primary btn-block" id="btnApproveCM" onclick="approveCM();"><i class="fa fa-thumbs-up" style="font-size:15px"> Approve CM</i></button>';
				
				$dateOfReceipt = '
					<div class="col-md-6">
						<label class="form-label">Date of Receipt</label>
						<input type="date" class="form-control" id="txtDateReceipt">
					</div>';
					
				$Btnreject = '<button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#evaluasiModal"><i class="fa fa-ban" style="font-size:15px"> Reject</i></button>';
			} else if ($reportData[0]->st_submit_cm == 'Y' || $row->st_reject == 'Y') {
				
				$Btnact = '<button type="button" class="btn btn-primary btn-block" id="btnPrintCrewEvaluation" onclick="exportPDF();">Print Crew Evaluation</button>';
			}
		} else if ($reportData[0]->st_submit_cm == 'Y') {
			$Btnact = '<button type="button" class="btn btn-primary btn-block" id="btnPrintCrewEvaluation" onclick="exportPDF();">Print Crew Evaluation</button>';
		}

		$dataOut = array(
			'id_report' => $reportData[0]->id,
			'vessel' => $vessel,
			'rank' => $rank,
			'dateOfReport' => $dateOfReport,
			'seafarerName' => $seafarerName,
			'superintendentFullName' => $superintendentFullName,
			'reportPeriodFrom' => $reportPeriodFrom,
			'reportPeriodTo' => $reportPeriodTo,
			'reasonMidway' => $reasonMidway,
			'reasonLeaving' => $reasonLeaving,
			'reasonSigningOff' => $reasonSigningOff,
			'reasonSpecial' => $reasonSpecial,
			'criteriaTable' => $criteriaTable,
			'masterComments' => $masterComments,
			'reportingOfficerComments' => $reportingOfficerComments,
			'promote' => $promote,
			'reportingOfficerName' => $reportingOfficerName,
			'reportingOfficerRank' => $reportingOfficerRank,
			'mastercoofullname' => $mastercoofullname,
			'receivedByCM' => $receivedByCM,
			'dateOfReceipt' => $dateOfReceipt,
			'reEmploy' => $reEmploy,
			'qrCodeImg' => $qrCodePathChief,
			'qrCodePathMaster' => $qrCodePathMaster,
			'qrCodePathSuperintendent' => $qrCodePathSuperintendent,
			'qrCodePathCM' => $qrCodePathCM,
			'btnAct' => $Btnact,
			'btnReject' => $Btnreject,
			'remark_reject' => $remark_reject,
			'label_reject' => $label_reject,
			'masterComments' => $masterComments
		);
		$this->load->view('frontend/reportCrewEvaluation', $dataOut);
	}

	function getBatchNo()
	{
		$batchNo = "1";
		$sql = " SELECT (batchno + 1) AS batchNo FROM tblempnosurat ORDER BY batchno DESC LIMIT 0,1 ";
		$data = $this->MCrewscv->getDataQueryDB6($sql);

		if(count($data) > 0)
		{
			$batchNo = $data[0]->batchNo;
		}

		return $batchNo;
	}

	function createQRCode($id = "", $type = '')
	{
		$config = array();
		$this->load->library('ciqrcode');

		$config['cacheable']	= true;
		$config['cachedir']		= './assets/imgQRCodeCrewCV/';
		$config['errorlog']		= './assets/imgQRCodeCrewCV/';
		$config['imagedir']		= './assets/imgQRCodeCrewCV/';
		$config['quality']		= true;
		$config['size']			= '1024';
		$config['black']		= array(224,255,255);
		$config['white']		= array(0,0,128);
		$this->ciqrcode->initialize($config);
			
		$imgName = base64_encode($id).'.jpg';
		
		if($type == 'approveMaster')
		{
			$imgName = 'approveMaster_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveChief')
		{
			$imgName = 'approveChief_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveCM')
		{
			$imgName = 'approveCM_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveTechnicalSuperintendent')
		{
			$imgName = 'approveTechnicalSuperintendent_'.base64_encode($id).'.jpg';
		}
		
		$params['data'] = "http://apps.andhika.com/myapps/myLetter/viewLetter/".base64_encode($id); 
		$params['level'] = 'H'; 
		$params['size'] = 5;
		$params['savename'] = FCPATH.$config['imagedir'].$imgName; 
		$params['logo'] = "./assets/img/andhika.png";

		$this->ciqrcode->generate($params); 

    	return $imgName;
	}

	function createQRCodeForm($id = "", $type = '')
	{
		$config = array();
		$this->load->library('ciqrcode');

		$config['cacheable']	= true;
		$config['cachedir']		= './assets/imgQRCodeCrewCV/';
		$config['errorlog']		= './assets/imgQRCodeCrewCV/';
		$config['imagedir']		= './assets/imgQRCodeCrewCV/';
		$config['quality']		= true;
		$config['size']			= '1024';
		$config['black']		= array(224,255,255);
		$config['white']		= array(0,0,128);
		$this->ciqrcode->initialize($config);
			
		$imgName = base64_encode($id).'.jpg';
		
		if($type == 'approveMaster')
		{
			$imgName = 'approveMaster_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveChief')
		{
			$imgName = 'approveChief_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveCM')
		{
			$imgName = 'approveCM_'.base64_encode($id).'.jpg';
		}
		if($type == 'approveSeafarer')
		{
			$imgName = 'approveSeafarer_'.base64_encode($id).'.jpg';
		}
		
		$params['data'] = "https://forms.gle/97K3hqyPFPk5sK2c9".base64_encode($id); 
		$params['level'] = 'H'; 
		$params['size'] = 5;
		$params['savename'] = FCPATH.$config['imagedir'].$imgName; 
		$params['logo'] = "./assets/img/andhika.png";

		$this->ciqrcode->generate($params); 

    	return $imgName;
	}

	function createNo($noNya = "")
	{
		$dt = strlen($noNya);
		$outNo = "";
		if($dt == 1)
		{
			$outNo = "000".$noNya;
		}
		else if($dt == 2)
		{
			$outNo = "00".$noNya;
		}
		else if($dt == 3)
		{
			$outNo = "0".$noNya;
		}
		else{
			$outNo = $noNya;
		}
		
		return $outNo;
	}

	function addDataMyAppLetter($txtIdEditCrew = "") 
	{
		$dateNow = date("Y-m-d");
		$yearNow = date("Y");
		$monthNow = date("m");
		$noSurat = "1";
		$initDivisi = "DKP";
		$initCmp = "AES";
		$insSql = array();
		$imgName = "";
 
		try {
			$sql = "SELECT * FROM crew_evaluation_report WHERE id = '".$txtIdEditCrew."' AND deletests = '0'";
			$rsl = $this->MCrewscv->getDataQuery($sql);
			
			if ($initCmp !== "") {
				$sqlSrv = "SELECT nosurat FROM tblEmpNoSurat
						WHERE cmpcode = '".$initCmp."' AND YEAR(tglsurat) = '".$yearNow."'
						ORDER BY nosurat DESC LIMIT 0,1";
				$rslSrv = $this->MCrewscv->getDataQueryDB6($sqlSrv);

				if (count($rslSrv) > 0) {
					$ns = explode("/", $rslSrv[0]->nosurat);
					$noSurat = $ns[0] + 1;
				}

				$batchno = $this->getBatchNo();
				$formatNoSrt = $this->createNo($noSurat) . "/" . $initCmp . "/" . $initDivisi . "/" . $monthNow . substr($yearNow, 2, 2);

				$insSql["batchno"] = $batchno;
				$insSql["cmpcode"] = $initCmp;
				$insSql["nosurat"] = $formatNoSrt;
				$insSql["issueddiv"] = $initDivisi;
				$insSql["signedby"] = $initDivisi;
				$insSql["address"] = "Crewing";
				$insSql["tglsurat"] = $dateNow;
				$insSql["ket"] = "Crew Evaluation Report / Crewing ";
				$insSql["copydoc"] = "0";
				$insSql["canceldoc"] = "0";
				$insSql["createdby"] = "Crewing System";

				$this->MCrewscv->insDataDb6($insSql,"tblEmpNoSurat");
				$insSql = array();	
				$imgName = $this->createQRCode($batchno, 'approveChief');
				
				$insSql["batchno"] = $batchno;
				$insSql["qrcode_reporting_chief"] = $imgName;

				$whereNya = "id = '".$txtIdEditCrew."'";
				$this->MCrewscv->updateData($whereNya, $insSql, "crew_evaluation_report");
				
			}
		} catch (Exception $e) {
			$imgName = "Failed => " . $e->getMessage();
		}
		return $imgName;
	}

	function approveMaster()
	{
		$data = $_POST;
		$idReport = $data['txtIdReport'];
		$comments = $data['txtMasterComments'];

		try {

			$sql = "SELECT batchno FROM crew_evaluation_report WHERE id='$idReport' AND deletests=0";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if (!$rsl) {
				throw new Exception("Report not found!");
			}

			$batchno = $rsl[0]->batchno ?: $this->getBatchNo();
			$qrFile = $this->createQRCode($batchno, 'approveMaster');

			$update = array(
				'master_comments'          => $comments,
				'qrcode_reporting_master'  => $qrFile,
				'st_submit_master'         => 'Y',
				'link_status'              => 'USED'   
			);

			$this->MCrewscv->updateData(array("id" => $idReport), $update, "crew_evaluation_report");

			echo json_encode(array(
				'status' => 'success',
				'message' => 'Master approval completed.'
			));

		} catch (Exception $e) {

			echo json_encode(array(
				'status' => 'error',
				'message' => $e->getMessage()
			));
		}
	}



	function approveTechnicalSuperintendent() {
		$data = $_POST;
		$txtIdReport = isset($data['txtIdReport']) ? $data['txtIdReport'] : null;
		if (!$txtIdReport) {
			echo json_encode(array(
				'status' => 'error',
				'message' => 'ID report tidak ditemukan'
			));
			return;
		}

		try {
			$sql = "SELECT batchno FROM crew_evaluation_report WHERE id = '".$txtIdReport."' AND deletests = 0";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if (count($rsl) == 0) {
				throw new Exception("Report tidak ditemukan!");
			}
			$batchno = $rsl[0]->batchno;
			
			if ($batchno == "" || $batchno == null) {
				$batchno = $this->getBatchNo();
			}
			
			$qrCodeFileName = $this->createQRCode($batchno, 'approveTechnicalSuperintendent');
			$updateData = array(
				'qrcode_technicalsuperintendent' => $qrCodeFileName,
				'st_submit_technicalsuperintendent' => 'Y'
			);

			$this->MCrewscv->updateData(
				array('id' => $txtIdReport),
				$updateData,
				'crew_evaluation_report'
			);

			$sql = "SELECT * FROM crew_evaluation_report WHERE id = '".$txtIdReport."' AND deletests = 0";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if (count($rsl) > 0) {
				$idPerson = $rsl[0]->idperson;
				$idPersonEncoded = base64_encode(base64_encode(base64_encode($idPerson)));

				$this->sendApprovalNotification($idPersonEncoded, 'muhamad.fikri@andhika.com');
			}

			$response = array(
				'status' => 'success',
				'message' => 'Approved Seafarer successfully!'
			);
		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => 'Error: ' . $e->getMessage()
			);
		}
		echo json_encode($response);
	}

	function saveTSComments()
	{
		$idReport = $this->input->post('txtIdReport');
		$comments = $this->input->post('comments');

		if (!$idReport || !is_array($comments)) {
			echo json_encode(array(
				'status'  => 'error',
				'message' => 'Invalid data'
			));
			return;
		}

		try {

			foreach ($comments as $c) {

				$criteriaId = $c['criteria_id'];
				$comment    = $c['comment'];

				$this->MCrewscv->updateData(
					array(
						'id'        => $criteriaId,
						'id_report' => $idReport
					),
					array(
						'technical_comments' => $comment
					),
					'crew_evaluation_criteria'
				);
			}

			echo json_encode(array(
				'status'  => 'success',
				'message' => 'Comments updated successfully'
			));

		} catch (Exception $e) {

			echo json_encode(array(
				'status'  => 'error',
				'message' => 'Error: ' . $e->getMessage()
			));
		}
	}

	function sendApprovalNotification($idPersonEncoded, $recipientEmail) {
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		$link = base_url("extendCrewEvaluation/printCrewEvaluation/$idPersonEncoded");

		$mail = new PHPMailer();

		try {
			$mail->isSMTP();
			$mail->Host       = 'smtp.zoho.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = 'noreply@andhika.com';
			$mail->Password   = 'PCWLzCWDQH8C'; 
			$mail->SMTPSecure = 'tls';
			$mail->Port       = 587;

			$mail->setFrom('noreply@andhika.com', 'Crewing System Notification');
			$mail->addAddress($recipientEmail);

			$mail->isHTML(true);
			$mail->Subject = 'Notifikasi Approve Crew Evaluation';
			$mail->Body = "
				<div style='font-family: Arial, sans-serif; font-size: 14px; color: #333;'>
					<p>Dear Bu Eva,</p>
					<p>Silakan lakukan pengecekan approve terhadap Crew Evaluation pada tautan berikut:</p>
					<p><a href='$link' style='color: #1a73e8;'>$link</a></p>
					
					<p>Terima kasih.</p>
					<br>
					<p><em>Email ini dikirim otomatis oleh Crewing System.</em></p>
				</div>
			";

			if (!$mail->send()) {
				log_message('error', 'Email failed: ' . $mail->ErrorInfo);
			} else {
				log_message('info', "Approval email sent to $recipientEmail");
			}
		} catch (Exception $e) {
			log_message('error', 'Exception sending email: ' . $e->getMessage());
		}
	}

	function sendEmailToShip()
	{	
		$idPerson = $this->input->post('idPerson');
		$message  = $this->input->post('message');
		$emailTo  = $this->input->post('emailTo'); // NEW

		if (!$idPerson || !$message) {
			echo json_encode(array("status" => "error", "error" => "Parameter tidak lengkap"));
			return;
		}

		$sql = "SELECT 
					TRIM(CONCAT(p.fname, ' ', COALESCE(p.mname, ''), ' ', p.lname)) AS name,
					r.nmrank AS rank,
					v.nmvsl AS vessel,
					v.mail_vessel AS emailVessel
				FROM tblcontract c
				LEFT JOIN mstpersonal p ON p.idperson = c.idperson
				LEFT JOIN mstrank r ON r.kdrank = c.signonrank
				LEFT JOIN mstvessel v ON v.kdvsl = c.signonvsl
				WHERE c.idperson = '$idPerson'
				AND c.signoffdt = '0000-00-00'";

		$data = $this->MCrewscv->getDataQuery($sql);

		if (!$data) {
			echo json_encode(array("status" => "error", "error" => "Data crew tidak ditemukan"));
			return;
		}

		$recipientEmail = $data[0]->emailVessel;
		$crewName       = $data[0]->name;
		$rank           = $data[0]->rank;
		$vessel         = $data[0]->vessel;
		$recipientEmail = $emailTo;

		if (!$recipientEmail) {
			echo json_encode(array("status" => "error", "error" => "Email kapal tidak ditemukan"));
			return;
		}

		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		$mail = new PHPMailer();

		try {
			$mail->isSMTP();
			$mail->Host       = 'smtp.zoho.com';
			$mail->SMTPAuth   = true;
			$mail->Username   = 'noreply@andhika.com';
			$mail->Password   = 'PCWLzCWDQH8C';
			$mail->SMTPSecure = 'tls';
			$mail->Port       = 587;

			$mail->setFrom('noreply@andhika.com', 'Crewing Department');
			$mail->addAddress($recipientEmail);

			$mail->isHTML(true);
			$mail->Subject = "Crew Evaluation Request $crewName / $vessel";
			$mail->Body    = nl2br($message); 

			if (!$mail->send()) {
				echo json_encode(array("status" => "error", "error" => $mail->ErrorInfo));
			} else {
				echo json_encode(array("status" => "success"));
			}

		} catch (Exception $e) {
			echo json_encode(array("status" => "error", "error" => $e->getMessage()));
		}
	}
	
	function rejectCrewEvaluation() {
		$data = $_POST;
		$txtIdReport = $data['txtIdReport'];
		$reasonReject = $data['txtReasonReject'];

		header('Content-Type: application/json');

		try {
			$updateData = array(
				'st_reject' => 'Y',
				'remark_reject' => $reasonReject
			);

			$this->MCrewscv->updateData(
				array('id' => $txtIdReport),
				$updateData,
				'crew_evaluation_report'
			);

			$response = array(
				'status' => 'success',
				'message' => 'Reject reason saved successfully.'
			);
		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => 'Error: ' . $e->getMessage()
			);
		}

		echo json_encode($response);
		exit; 
	}

	function approveCM() {
		$data = $_POST;
		$txtIdReport = $data['txtIdReport'];
		$txtDateReceipt = $data['txtDateReceipt'];
		try {
			$sql = "SELECT batchno FROM crew_evaluation_report WHERE id = '".$txtIdReport."' AND deletests = 0";
			$rsl = $this->MCrewscv->getDataQuery($sql);

			if (count($rsl) == 0) {
				throw new Exception("Report tidak ditemukan!");
			}
			$batchno = $rsl[0]->batchno;
			
			if ($batchno == "" || $batchno == null) {
				$batchno = $this->getBatchNo();
			}
	
			$qrCodeFileName = $this->createQRCode($batchno, 'approveCM');
			
			$updateData = array(
				'date_of_receipt' => $txtDateReceipt,
				'qrcode_reporting_cm' => $qrCodeFileName,
				'st_submit_cm' => 'Y'
			);
			
			$this->MCrewscv->updateData(
				array('id' => $txtIdReport),
				$updateData,
				'crew_evaluation_report'
			);
			
			$response = array(
				'status' => 'success',
				'message' => 'Approved CM successfully!'
			);
		} catch (Exception $e) {
			$response = array(
				'status' => 'error',
				'message' => 'Error: ' . $e->getMessage()
			);
		}
		echo json_encode($response);
	}
}