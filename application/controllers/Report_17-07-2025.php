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
			$dataOut['optVessel'] = $dataContext->getVesselByOption("", "kode");
			$dataOut['optRank'] = $dataContext->getRankByOption("","name");
			$dataOut['optCompany'] = $dataContext->getCompanyByOption("","kode");
			$this->load->view('frontend/reportData',$dataOut);
		}else{
			print json_encode($dataOut);
		}
	}

	function getDataApplicantPositionSummary()
	{
		$sql = "
			SELECT position_applied, COUNT(*) as total 
			FROM new_applicant 
			WHERE deletests = 0 AND st_data = 0 
			GROUP BY position_applied
		";

		$data = $this->MCrewscv->getDataQuery($sql); 

		$result = array();
		foreach ($data as $row) {
			$result[] = array(
				'name' => $row->position_applied,
				'y' => (int) $row->total
			);
		}

		echo json_encode($result);
	}

	function getListApplicantsByPosition()
	{
		$position = $this->input->get('position');

		$sql = "
			SELECT fullname, email, handphone, position_applied 
			FROM new_applicant 
			WHERE deletests = 0 AND st_data = 0 AND position_applied = '".$position."'
		";

		$data = $this->MCrewscv->getDataQuery($sql, array($position));
		echo json_encode($data);
	}

	function getDataApplicantPositionSummaryTalentPool()
	{
		$sql = "
			SELECT position_applied, COUNT(*) as total 
			FROM new_applicant 
			WHERE deletests = 0 AND st_data > 1
			GROUP BY position_applied
		";

		$data = $this->MCrewscv->getDataQuery($sql); 

		$result = array();
		foreach ($data as $row) {
			$result[] = array(
				'name' => $row->position_applied,
				'y' => (int) $row->total
			);
		}

		echo json_encode($result);
	}


	function getListApplicantsByPositionTalentPool()
	{
		$position = $this->input->get('position', TRUE);
		$position = $this->db->escape_str($position);

		$sql = "
			SELECT fullname, email, handphone, position_applied 
			FROM new_applicant 
			WHERE deletests = 0 AND st_data > 1 AND position_applied = '" . $position . "'
		";

		$data = $this->MCrewscv->getDataQuery($sql);
		echo json_encode($data);
	}


	function searchDataReady() {
		$search = $this->input->get('search');
		$page = $this->input->get('page');
		$this->getDataNewApplicent($search, $page);
	}

	function getSubmitCV()
	{
		$sql = "
			SELECT DATE(submit_cv) AS tanggal, COUNT(*) AS jumlah
			FROM new_applicant
			WHERE deletests = '0' 
				AND submit_cv IS NOT NULL
				AND submit_cv != '0000-00-00'
			GROUP BY DATE(submit_cv)
			ORDER BY DATE(submit_cv) ASC
		";

		$data = $this->MCrewscv->getDataQuery($sql);

		echo json_encode($data);
	}

	function searchDataDraft() {
		$search = $this->input->get('search');
		$page = $this->input->get('page');
		$this->getDataDraftCrew($search, $page);
	}

	function searchDataRejected() {
		$search = $this->input->get('search');
		$page = $this->input->get('page');
		$this->getDataRejectedCrew($search, $page);
	}

	function getDataNewApplicent($search = "", $page = 1)
	{
		$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
		$limit = 10;
		$offset = ($page - 1) * $limit;

		$trListNewApplicant = "";
		$no = $offset + 1;

		$sqlTotal = "SELECT COUNT(*) as total FROM new_applicant 
			WHERE deletests = '0' AND st_data = '0' 
			AND (position_applied LIKE '%$search%' OR fullname LIKE '%$search%')";
		$resultTotal = $this->MCrewscv->getDataQuery($sqlTotal);
		$totalRows = isset($resultTotal[0]) ? $resultTotal[0]->total : 0;
		$totalPages = ceil($totalRows / $limit);
		$start = $offset + 1;
		$end = min($offset + $limit, $totalRows);

		$infoTotalData = "<tr><td colspan='15' class='text-left' style='padding: 10px; font-weight: bold;'>
			Menampilkan data $start - $end dari total $totalRows data
		</td></tr>";

		$sql = "SELECT * FROM new_applicant 
			WHERE deletests = '0' AND st_data = '0' 
			AND (position_applied LIKE '%$search%' OR fullname LIKE '%$search%')
			ORDER BY submit_cv DESC
			LIMIT $limit OFFSET $offset";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $val)
		{ 
			$cvUrl = base_url('assets/uploads/CV_NewApplicant/' . $val->new_cv);
			$btnAct = "<a href=\"$cvUrl\" target=\"_blank\" class=\"btn btn-sm btn-danger\">
				<i class='fa fa-file-pdf-o'></i> View CV
			</a>";
			
			// $btnAct .= "<button class=\"btn btn-primary btn-xs btn-block\" style=\"margin-top:5px;\" onclick=\"pickUpDataApplicant('".$val->id."');\"><i class='fas fa-truck-pickup' style='font-size:15px'></i> Pick Up</button>";

			$btnAct .= "<button class=\"btn btn-warning btn-xs btn-block\" style=\"margin-top:5px;\" onclick=\"notPositionCrew('".$val->id."', '<b><i>:: ".$val->fullname." ::</i></b>');\">
				<i class=\"fas fa-exclamation-triangle\"></i> Not Position
			</button>";

			$btnAct .= "<button class=\"btn btn-info btn-xs btn-block\" style=\"margin-top:5px;\" onclick=\"notQualifiedCrew('".$val->id."', '<b><i>:: ".$val->fullname." ::</i></b>');\">
				<i class=\"fas fa-info-circle\"></i> Not Qualify
			</button>";

			// $btnAct .= "<button class=\"btn btn-success btn-xs btn-block\" style=\"margin-top:5px;\" onclick=\"handleCrewStatus('4', '".$val->id."', '<b><i>:: ".$val->fullname." ::</i></b>');\">
			// 	<i class=\"fas fa-user-check\"></i> Not Reff
			// </button>";

			$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" style=\"margin-top:5px;\" onclick=\"interviewCrew('".$val->id."', '<b><i>:: ".$val->fullname." ::</i></b>');\">
				<i class=\"fas fa-user-tie\"></i> Interview
			</button>";


			$trListNewApplicant .= "<tr id='row_$val->id'>";
			$trListNewApplicant .= "<td class='text-center'>$no</td>";
			$trListNewApplicant .= "<td>$val->email</td>";
			$trListNewApplicant .= "<td class='fullname'>$val->fullname</td>";
			$trListNewApplicant .= "<td>$val->born_place</td>";
			$trListNewApplicant .= "<td>$val->born_date</td>";
			$trListNewApplicant .= "<td>$val->handphone</td>";
			$trListNewApplicant .= "<td class='position-applied'>$val->position_applied</td>";
			$trListNewApplicant .= "<td>$val->ijazah_terakhir</td>";
			$trListNewApplicant .= "<td>$val->last_experience</td>";
			$trListNewApplicant .= "<td>$val->pengalaman_jeniskapal</td>";
			$trListNewApplicant .= "<td>$val->berlayardengancrewasing</td>";
			$trListNewApplicant .= "<td>$val->last_salary</td>";
			$trListNewApplicant .= "<td>$val->join_inAndhika</td>";
			$trListNewApplicant .= "<td>$val->new_cv</td>";
			$trListNewApplicant .= "<td>$val->submit_cv</td>";
			$trListNewApplicant .= "<td class='text-center'>$btnAct</td>";
			$trListNewApplicant .= "</tr>";

			$no++;
		}

		$pagination = '<tr><td colspan="15" class="text-center">';

		if ($page > 1) {
			$prevPage = $page - 1;
			$pagination .= "<button class='btn btn-sm btn-info' style='margin: 0 5px;' onclick='loadPageDataReady($prevPage)'>⟨ Sebelumnya</button>";
		}

		$pagination .= "<span style='margin: 0 10px; font-weight: bold;'>Halaman $page dari $totalPages</span>";

		if ($page < $totalPages) {
			$nextPage = $page + 1;
			$pagination .= "<button class='btn btn-sm btn-info' style='margin: 0 5px;' onclick='loadPageDataReady($nextPage)'>Selanjutnya ⟩</button>";
		}

		$pagination .= '</td></tr>';

		echo $trListNewApplicant . $pagination . $infoTotalData;
	}

	function getDataDraftCrew($search = "", $page = 1)
	{
		$page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
		$limit = 10;	
		$offset = ($page - 1) * $limit;

		$trListDataDraftCrew = "";
		$no = $offset + 1;
 
		$sqlTotal = "SELECT COUNT(*) as total FROM new_applicant 
                WHERE deletests = '0' AND st_data = '2' 
                AND (position_applied LIKE '%$search%' OR fullname LIKE '%$search%')";
		
				
		$resultTotal = $this->MCrewscv->getDataQuery($sqlTotal);
		$totalRows = isset($resultTotal[0]) ? $resultTotal[0]->total : 0;
		$totalPages = ceil($totalRows / $limit);
		$start = $offset + 1;
		$end = min($offset + $limit, $totalRows);
		
		$infoTotalData = "<tr><td colspan='15' class='text-left' style='padding: 10px; font-weight: bold;'>
				Menampilkan data $start - $end dari total $totalRows data
			</td></tr>";
		
		$sql = "SELECT * FROM new_applicant 
            WHERE deletests = '0' AND st_data = '2' 
            AND (position_applied LIKE '%$search%' OR fullname LIKE '%$search%')
            LIMIT $limit OFFSET $offset";
			
		$rsl = $this->MCrewscv->getDataQuery($sql);

		foreach ($rsl as $val)
		{
			$cvUrl = base_url('assets/uploads/CV_NewApplicant/' . $val->new_cv);
			$btnAct = "<a href=\"$cvUrl\" target=\"_blank\" class=\"btn btn-sm btn-danger\">
				<i class='fa fa-file-pdf-o'></i> View PDF
			</a>";
			$btnAct .= "<button class=\"btn btn-primary btn-xs btn-block\" style=\"margin-top:5px;\" onclick=\"pickUpDataApplicantDraftCrew('".$val->id."');\"><i class='fas fa-truck-pickup' style='font-size:15px'></i> Pick Up</button>";
			
			$btnAct .= "<button class=\"btn btn-danger btn-xs btn-block\" style=\"margin-top:5px;\" onclick=\"RejectDraftCrew('".$val->id."', '<b><i>:: ".$val->fullname." ::</i></b>');\">
				<i class=\"fas fa-ban\"></i> Reject Crew
			</button>";

			$trListDataDraftCrew .= "<tr id='row_$val->id'>";
			$trListDataDraftCrew .= "<td class='text-center'>$no</td>";
			$trListDataDraftCrew .= "<td>$val->email</td>";
			$trListDataDraftCrew .= "<td class='fullname'>$val->fullname</td>";
			$trListDataDraftCrew .= "<td>$val->born_place</td>";
			$trListDataDraftCrew .= "<td>$val->born_date</td>";
			$trListDataDraftCrew .= "<td>$val->handphone</td>";
			$trListDataDraftCrew .= "<td class='position-applied'>$val->position_applied</td>";
			$trListDataDraftCrew .= "<td>$val->ijazah_terakhir</td>";
			$trListDataDraftCrew .= "<td>$val->last_experience</td>";
			$trListDataDraftCrew .= "<td>$val->pengalaman_jeniskapal</td>";
			$trListDataDraftCrew .= "<td>$val->berlayardengancrewasing</td>";
			$trListDataDraftCrew .= "<td>$val->last_salary</td>";
			$trListDataDraftCrew .= "<td>$val->join_inAndhika</td>";
			$trListDataDraftCrew .= "<td>$val->new_cv</td>";
			$trListDataDraftCrew .= "<td>$val->submit_cv</td>";
			$trListDataDraftCrew .= "<td class='text-center'>$btnAct</td>";
			$trListDataDraftCrew .= "</tr>";

			$no++;
		}

		$pagination = '<tr><td colspan="15" class="text-center">';

		if ($page > 1) {
			$prevPage = $page - 1;
			$pagination .= "<button class='btn btn-sm btn-info' style='margin: 0 5px;' onclick='loadPageDataDraft($prevPage)'>⟨ Sebelumnya</button>";
		}

		$pagination .= "<span style='margin: 0 10px; font-weight: bold;'>Halaman $page dari $totalPages</span>";

		if ($page < $totalPages) {
			$nextPage = $page + 1;
			$pagination .= "<button class='btn btn-sm btn-info' style='margin: 0 5px;' onclick='loadPageDataDraft($nextPage)'>Selanjutnya ⟩</button>";
		}

		$pagination .= '</td></tr>';

		echo $trListDataDraftCrew . $pagination . $infoTotalData;
	}

	function setNotPositionCrew()
	{
		$id = $this->input->post('id');

		if ($id) {
			$sql = "SELECT email, fullname FROM new_applicant WHERE id = '".$id."' AND deletests = '0'";
			$result = $this->MCrewscv->getDataQuery($sql);

			if (empty($result)) {
				echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
				return;
			}

			$applicant = $result[0];

			$data = array('st_data' => 2);
			$where = array('id' => $id);
			$this->MCrewscv->updateData($where, $data, 'new_applicant');

			$this->sendNotPositionNotification($applicant->email, $applicant->fullname);

			echo json_encode(array('status' => 'success', 'message' => 'Data berhasil disimpan sebagai draft crew.'));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'ID tidak valid'));
		}
	}

	function sendNotPositionNotification($recipientEmail, $fullName)
	{
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		if (
			!preg_match('/@gmail\.com$/', $recipientEmail) &&
			!preg_match('/@yahoo\.com$/', $recipientEmail) &&
			!preg_match('/@outlook\.com$/', $recipientEmail)
		) {
			log_message('info', "Email not sent: domain not allowed ($recipientEmail)");
			return;
		}

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
			$mail->Sender = 'noreply@andhika.com';
			$mail->addAddress($recipientEmail);
			
			$mail->AddEmbeddedImage(APPPATH . '../assets/img/logo_andhika.png', 'logo_andhika');
			
			$mail->isHTML(true);
			$mail->Subject = 'Terima Kasih - Data Anda Telah Diterima';

			$mail->AltBody = "Yth. $fullName,\n\n"
				. "Terima kasih atas ketertarikan Anda untuk bergabung bersama PT Andhika Lines.\n"
				. "Saat ini, kami belum memiliki posisi yang tersedia yang sesuai dengan profil Anda. Namun, "
				. "informasi Anda telah kami masukkan ke dalam database Talent Pool kami, dan akan kami "
				. "pertimbangkan kembali apabila terdapat kebutuhan yang relevan di masa mendatang.\n\n"
				. "Hormat kami,\n"
				. "Tim Crewing\n"
				. "PT Andhika Lines\n\n"
				. "Email ini dikirim otomatis oleh sistem Crewing PT Andhika Lines. Mohon tidak membalas email ini.";


			$mail->Body = "
			<div style='font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 30px;'>
				<div style='max-width: 600px; margin: auto; background-color: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #e0e0e0;'>

					<div style='background-color: #003366; padding: 20px; text-align: center;'>
						<img src='cid:logo_andhika' alt='PT Andhika Lines' style='max-width: 180px;'>
					</div>

					<div style='padding: 30px; color: #333; font-size: 14px; line-height: 1.6;'>
						<p>Yth. <strong>$fullName</strong>,</p>

						<p>Terima kasih atas ketertarikan Anda untuk bergabung bersama <strong>PT Andhika Lines</strong>.</p>
						<p>Saat ini, kami belum memiliki posisi yang tersedia yang sesuai dengan profil Anda. Namun, informasi Anda telah kami masukkan ke dalam database <strong>Talent Pool</strong> kami, dan akan kami pertimbangkan kembali apabila terdapat kebutuhan yang relevan di masa mendatang.</p>

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
			</div>";

			if (!$mail->send()) {
				log_message('error', 'Draft Email failed to ' . $recipientEmail . ': ' . $mail->ErrorInfo);
			} else {
				log_message('info', "Draft email sent to $recipientEmail");
			}
		} catch (Exception $e) {
			log_message('error', 'Exception while sending Draft email: ' . $e->getMessage());
		}
	}

	function setNotQualifiedCrew()
	{
		$id = $this->input->post('id');

		if ($id) {
			$sql = "SELECT email, fullname FROM new_applicant WHERE id = '".$id."' AND deletests = '0'";
			$result = $this->MCrewscv->getDataQuery($sql);


			if (empty($result)) {
				echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
				return;
			}

			$applicant = $result[0];

			$data = array('st_data' => 3);
			$where = array('id' => $id);
			$this->MCrewscv->updateData($where, $data, 'new_applicant');

			$this->sendNotQualifiedNotification($applicant->email, $applicant->fullname);

			echo json_encode(array('status' => 'success', 'message' => 'Crew has not qualified.'));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'ID tidak valid'));
		}
	}

	function sendNotQualifiedNotification($recipientEmail, $fullName)
	{
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		if (
			!preg_match('/@gmail\.com$/', $recipientEmail) &&
			!preg_match('/@yahoo\.com$/', $recipientEmail) &&
			!preg_match('/@outlook\.com$/', $recipientEmail)
		) {
			log_message('info', "Email not sent: domain not allowed ($recipientEmail)");
			return;
		}

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
			$mail->Sender = 'noreply@andhika.com';
			$mail->addAddress($recipientEmail);
			
			$mail->AddEmbeddedImage(APPPATH . '../assets/img/logo_andhika.png', 'logo_andhika');
			
			$mail->isHTML(true);
			$mail->Subject = 'Informasi Terkait Lamaran Anda';

			$mail->AltBody = "Yth. $fullName,\n\n"
				. "Terima kasih atas minat Anda untuk bergabung bersama PT Andhika Lines.\n"
				. "Saat ini kami memiliki posisi yang sesuai dengan minat Anda. Namun, berdasarkan dokumen yang kami terima, terdapat beberapa sertifikasi atau dokumen yang perlu dilengkapi atau disesuaikan dengan persyaratan posisi yang dilamar.\n\n"
				. "Kami mendorong Anda untuk melengkapi dokumen tersebut agar proses seleksi dapat berjalan lebih lanjut. Informasi mengenai persyaratan posisi dapat Anda lihat pada pengumuman lowongan kami, atau hubungi tim kami melalui WhatsApp di [no_wa].\n\n"
				. "CV Anda telah kami simpan dalam database Talent Pool dan akan kami pertimbangkan kembali apabila terdapat kebutuhan yang sesuai di masa mendatang.\n\n"
				. "Hormat kami,\nTim Crewing\nPT Andhika Lines\n\n"
				. "Email ini dikirim otomatis oleh sistem Crewing PT Andhika Lines. Mohon tidak membalas email ini.";
			
			$mail->Body = "
				<div style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
					<div style='max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #ddd;'>
						<div style='background-color: #003366; padding: 20px; text-align: center;'>
							<img src='cid:logo_andhika' alt='PT Andhika Lines' style='max-width: 180px;'>
						</div>
						<h2 style='color:rgb(0, 0, 0); margin-top: 0;'>Informasi Terkait Lamaran Anda</h2>

						<p style='font-size: 14px; color: #333;'>Yth. <strong>$fullName</strong>,</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Terima kasih atas minat Anda untuk bergabung bersama <strong>PT Andhika Lines</strong>.
						</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Saat ini kami memiliki posisi yang tersedia dan sesuai dengan minat Anda. Namun, berdasarkan dokumen yang kami terima, terdapat beberapa sertifikasi atau dokumen yang perlu dilengkapi atau disesuaikan dengan persyaratan posisi yang dilamar.
						</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Kami mendorong Anda untuk melengkapi atau menyesuaikan dokumen tersebut agar proses seleksi dapat berjalan lebih lanjut. Informasi mengenai persyaratan posisi dapat Anda temukan pada pengumuman lowongan kami, atau Anda dapat menghubungi tim kami untuk klarifikasi lebih lanjut melalui WhatsApp di <strong>[no_wa]</strong>.
						</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							CV Anda telah kami simpan dalam database <strong>Talent Pool</strong> dan akan kami pertimbangkan kembali apabila terdapat kebutuhan yang sesuai di masa mendatang.
						</p>

						<p style='margin-top: 30px; font-size: 14px; color: #333;'>
							Hormat kami,<br>
							<strong>Tim Crewing</strong><br>
							PT Andhika Lines
						</p>

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
				</div>";

			if (!$mail->send()) {
				log_message('error', 'Reject Email failed to ' . $recipientEmail . ': ' . $mail->ErrorInfo);
			} else {
				log_message('info', "Reject email sent to $recipientEmail");
			}
		} catch (Exception $e) {
			log_message('error', 'Exception while sending Reject email: ' . $e->getMessage());
		}
	}

	function setNotRefference()
	{
		$id = $this->input->post('id');

		if ($id) {
			$sql = "SELECT email, fullname FROM new_applicant WHERE id = '".$id."' AND deletests = '0'";
			$result = $this->MCrewscv->getDataQuery($sql);


			if (empty($result)) {
				echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
				return;
			}

			$applicant = $result[0];

			$data = array('st_data' => 4);
			$where = array('id' => $id);
			$this->MCrewscv->updateData($where, $data, 'new_applicant');

			$this->sendNotRefferenceNotification($applicant->email, $applicant->fullname);

			echo json_encode(array('status' => 'success', 'message' => 'Crew has not qualified.'));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'ID tidak valid'));
		}
	}

	function sendNotRefferenceNotification($recipientEmail, $fullName)
	{
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		if (
			!preg_match('/@gmail\.com$/', $recipientEmail) &&
			!preg_match('/@yahoo\.com$/', $recipientEmail) &&
			!preg_match('/@outlook\.com$/', $recipientEmail)
		) {
			log_message('info', "Email not sent: domain not allowed ($recipientEmail)");
			return;
		}

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
			$mail->Sender = 'noreply@andhika.com';
			$mail->addAddress($recipientEmail);
			
			$mail->AddEmbeddedImage(APPPATH . '../assets/img/logo_andhika.png', 'logo_andhika');
			
			$mail->isHTML(true);
			$mail->Subject = 'Informasi Terkait Lamaran Anda';

			$mail->AltBody = "Yth. $fullName,\n\n"
				. "Terima kasih atas minat Anda untuk bergabung bersama PT Andhika Lines.\n"
				. "Saat ini kami memiliki posisi yang sesuai dengan minat Anda. Namun, berdasarkan dokumen yang kami terima, terdapat beberapa sertifikasi atau dokumen yang perlu dilengkapi atau disesuaikan dengan persyaratan posisi yang dilamar.\n\n"
				. "Kami mendorong Anda untuk melengkapi dokumen tersebut agar proses seleksi dapat berjalan lebih lanjut. Informasi mengenai persyaratan posisi dapat Anda lihat pada pengumuman lowongan kami, atau hubungi tim kami melalui WhatsApp di [no_wa].\n\n"
				. "CV Anda telah kami simpan dalam database Talent Pool dan akan kami pertimbangkan kembali apabila terdapat kebutuhan yang sesuai di masa mendatang.\n\n"
				. "Hormat kami,\nTim Crewing\nPT Andhika Lines\n\n"
				. "Email ini dikirim otomatis oleh sistem Crewing PT Andhika Lines. Mohon tidak membalas email ini.";

			
			$mail->Body = "
				<div style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
					<div style='max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #ddd;'>
						<div style='background-color: #003366; padding: 20px; text-align: center;'>
							<img src='cid:logo_andhika' alt='PT Andhika Lines' style='max-width: 180px;'>
						</div>
						<h2 style='color:rgb(0, 0, 0); margin-top: 0;'>Informasi Terkait Lamaran Anda</h2>

						<p style='font-size: 14px; color: #333;'>Yth. <strong>$fullName</strong>,</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Terima kasih atas minat Anda untuk bergabung bersama <strong>PT Andhika Lines</strong>.
						</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Saat ini kami memiliki posisi yang tersedia dan sesuai dengan minat Anda. Namun, berdasarkan dokumen yang kami terima, terdapat beberapa sertifikasi atau dokumen yang perlu dilengkapi atau disesuaikan dengan persyaratan posisi yang dilamar.
						</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Kami mendorong Anda untuk melengkapi atau menyesuaikan dokumen tersebut agar proses seleksi dapat berjalan lebih lanjut. Informasi mengenai persyaratan posisi dapat Anda temukan pada pengumuman lowongan kami, atau Anda dapat menghubungi tim kami untuk klarifikasi lebih lanjut melalui WhatsApp di <strong>[no_wa]</strong>.
						</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							CV Anda telah kami simpan dalam database <strong>Talent Pool</strong> dan akan kami pertimbangkan kembali apabila terdapat kebutuhan yang sesuai di masa mendatang.
						</p>

						<p style='margin-top: 30px; font-size: 14px; color: #333;'>
							Hormat kami,<br>
							<strong>Tim Crewing</strong><br>
							PT Andhika Lines
						</p>

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
				</div>";

			if (!$mail->send()) {
				log_message('error', 'Reject Email failed to ' . $recipientEmail . ': ' . $mail->ErrorInfo);
			} else {
				log_message('info', "Reject email sent to $recipientEmail");
			}
		} catch (Exception $e) {
			log_message('error', 'Exception while sending Reject email: ' . $e->getMessage());
		}
	}

	function setInterviewCrew() 
	{
		$id = $this->input->post('id');

		if ($id) {
			$sql = "SELECT email, fullname FROM new_applicant WHERE id = '".$id."' AND deletests = '0'";
			$result = $this->MCrewscv->getDataQuery($sql);

			if (empty($result)) {
				echo json_encode(array('status' => 'error', 'message' => 'Data tidak ditemukan'));
				return;
			}

			$applicant = $result[0];

			$data = array('st_data' => 5);
			$where = array('id' => $id);
			$this->MCrewscv->updateData($where, $data, 'new_applicant');
			
			$this->sendInterviewNotification($applicant->email, $applicant->fullname);
			
			echo json_encode(array('status' => 'success', 'message' => 'Crew has been set for interview.'));
		} else {
			echo json_encode(array('status' => 'error', 'message' => 'ID tidak valid'));
		}
	}

	function sendInterviewNotification($recipientEmail, $fullName)
	{
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		if (
			!preg_match('/@gmail\.com$/', $recipientEmail) &&
			!preg_match('/@yahoo\.com$/', $recipientEmail) &&
			!preg_match('/@outlook\.com$/', $recipientEmail)
		) {
			log_message('info', "Email not sent: domain not allowed ($recipientEmail)");
			return;
		}

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
			$mail->Sender = 'noreply@andhika.com';
			$mail->addAddress($recipientEmail);

			$mail->AddEmbeddedImage(APPPATH . '../assets/img/logo_andhika.png', 'logo_andhika');
			
			$mail->isHTML(true);
			$mail->Subject = 'Konfirmasi Proses Tes dan Interview';

			$mail->AltBody = "Yth. $fullName,\n\n"
				. "Terima kasih atas minat Anda untuk bergabung bersama PT Andhika Lines.\n"
				. "Kami informasikan bahwa Anda telah lolos seleksi administrasi awal dan akan kami proses ke tahap berikutnya berupa tes dan interview.\n"
				. "Tim kami akan segera menghubungi Anda untuk menyampaikan informasi jadwal dan lokasi pelaksanaan.\n"
				. "Mohon untuk menyiapkan dokumen yang diperlukan dan hadir tepat waktu.\n\n"
				. "Hormat kami,\nTim Crewing\nPT Andhika Lines";

			$mail->Body = "
				<div style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
					<div style='max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #ddd;'>
						<div style='background-color: #ffffffff; padding: 20px; text-align: center;'>
							<img src='cid:logo_andhika' alt='PT Andhika Lines' style='max-width: 180px;'>
						</div>
						<h2 style='color:rgb(0, 0, 0); margin-top: 0;'>Konfirmasi Proses Tes dan Interview</h2>

						<p style='font-size: 14px; color: #333;'>Yth. <strong>$fullName</strong>,</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Terima kasih atas minat Anda untuk bergabung bersama <strong>PT Andhika Lines</strong>.
						</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Kami informasikan bahwa Anda telah lolos seleksi administrasi awal dan akan kami proses ke tahap berikutnya berupa <strong>tes dan interview</strong>.
						</p>

						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Tim kami akan segera menghubungi Anda untuk menyampaikan informasi jadwal dan lokasi pelaksanaan.
							Mohon untuk menyiapkan dokumen yang diperlukan dan hadir tepat waktu.
						</p>

						<p style='margin-top: 30px; font-size: 14px; color: #333;'>
							Hormat kami,<br>
							<strong>Tim Crewing</strong><br>
							PT Andhika Lines
						</p>

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
				</div>";

			if (!$mail->send()) {
				log_message('error', 'Interview Email failed to ' . $recipientEmail . ': ' . $mail->ErrorInfo);
			} else {
				log_message('info', "Interview email sent to $recipientEmail");
			}
		} catch (Exception $e) {
			log_message('error', 'Exception while sending Interview email: ' . $e->getMessage());
		}
	}

	function unRejectCrew()
	{
		$id = $this->input->post('id');

		if (!$id) {
			echo json_encode(array('status' => 'error', 'message' => 'Invalid ID'));
			return;
		}

		$where = array('id' => $id, 'st_data' => 3);
		$data  = array('st_data' => 0);
		$table = 'new_applicant';

		$this->MCrewscv->updateData($where, $data, $table);

		echo json_encode(array('status' => 'success'));
	}

	function saveDataNewApplicent() {
		$id = $this->input->post('id');
	
		$sql = "SELECT * FROM new_applicant WHERE id = '".$id."' AND deletests = '0'";
		$data = $this->MCrewscv->getDataQuery($sql);
	
		if (empty($data)) {
			echo json_encode(array('error' => 'Data tidak ditemukan'));
			return;
		}
	
		$applicant = $data[0];
	
		$checkSql = "SELECT * FROM mstpersonal WHERE email = '".$applicant->email."' AND deletests = '0'";
		$check = $this->MCrewscv->getDataQuery($checkSql);
	
		if (!empty($check)) {
			echo json_encode(array('error' => 'Email sudah terdaftar di data personal.'));
			return;
		}
	
		$lastIdSql = "SELECT idperson FROM mstpersonal WHERE idperson IS NOT NULL AND idperson != '' ORDER BY idperson DESC LIMIT 1";
		$lastData = $this->MCrewscv->getDataQuery($lastIdSql);
	
		$newIdPerson = '000001'; 
		if (!empty($lastData)) {
			$lastIdPerson = $lastData[0]->idperson;
			$numeric = intval($lastIdPerson) + 1;
			$newIdPerson = str_pad($numeric, 6, '0', STR_PAD_LEFT);
		}
	
		$parts = explode(' ', trim($applicant->fullname));
		$firstName = $parts[0];
		$middleName = isset($parts[1]) ? $parts[1] : '';
		$lastName = isset($parts[2]) ? implode(' ', array_slice($parts, 2)) : '';
	
		$insertData = array(
			'idperson'     => $newIdPerson,
			'fname'        => $firstName,
			'mname'        => $middleName,
			'lname'        => $lastName,
			'email'        => $applicant->email,
			'mobileno'     => $applicant->handphone,
			'dob'          => $applicant->born_date,
			'pob'          => $applicant->born_place,
			'applyfor'     => $applicant->position_applied,
			'newapplicent' => '1',
			'addusrdt'     => $this->session->userdata('userCrewSystem') . "/" . date('Ymd') . "/" . date('H:i:s')
		);
		
		$this->MCrewscv->insData('mstpersonal', $insertData);
	
		$cleanFullname = strtolower(str_replace(' ', '', $applicant->fullname));
		$generatedUsername = substr($cleanFullname, 0, 5);
		$generatedPassword = substr($cleanFullname, 0, 5);
	
		$crewLoginData = array(
			'idperson'   => $newIdPerson,
			'fullname'   => $applicant->fullname,
			'username'   => $generatedUsername,
			'password'   => md5($generatedPassword),
			'AddUsrDt'   =>$this->session->userdata('userCrewSystem') . "/" . date('Ymd') . "/" . date('H:i:s'),
			'sts_delete' => 0
		);
		$this->MCrewscv->insData('crew_login', $crewLoginData);
	
		$this->db->where('id', $id);
		$this->db->update('new_applicant', array('st_data' => '1'));
	
		$this->sendPickUpNotification(
			$applicant->email,
			$applicant->fullname,
			$generatedUsername,
			$generatedPassword
		);

		header('Content-Type: application/json');
		echo json_encode(array(
			'success'   => true,
			'message'   => 'Data berhasil dipindahkan ke data personal dan akun login dibuat.',
			'idperson'  => $newIdPerson,
			'username'  => $generatedUsername,
			'password'  => $generatedPassword
		));
	}
	

	function sendPickUpNotification($recipientEmail, $fullName, $username, $password)
	{
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.phpmailer.php';
		require_once APPPATH . 'third_party/PHPMailer/PHPMailer/class.smtp.php';

		if (
			!preg_match('/@gmail\.com$/', $recipientEmail) &&
			!preg_match('/@yahoo\.com$/', $recipientEmail) &&
			!preg_match('/@outlook\.com$/', $recipientEmail)
		) {
			log_message('info', "Email not sent: domain not allowed ($recipientEmail)");
			return;
		}

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
			$mail->Sender = 'noreply@andhika.com';

			$mail->addAddress($recipientEmail);

			$mail->isHTML(true);
			$mail->Subject = 'Konfirmasi Penerimaan CV - PT Andhika Lines';

			$mail->AltBody = "Yth. Bapak/Ibu $fullName,\n\n"
				. "Terima kasih telah mengirimkan CV Anda kepada kami.\n\n"
				. "Kami telah menerima data Anda dan dinyatakan sesuai untuk diproses lebih lanjut oleh tim Crewing PT Andhika Lines.\n\n"
				. "Mohon pastikan kontak Anda tetap aktif untuk mempermudah komunikasi.\n\n"
				. "Hormat kami,\nCrewing Team\nPT Andhika Lines\n\n"
				. "Email ini dikirim otomatis oleh sistem Crewing PT Andhika Lines. Mohon tidak membalas email ini.";

				$mail->Body = "
				<div style='font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;'>
					<div style='max-width: 600px; margin: auto; background: #ffffff; padding: 30px; border-radius: 8px; border: 1px solid #ddd;'>
						<h2 style='color: #333333; margin-top: 0;'>Konfirmasi Penerimaan CV</h2>
			
						<p style='font-size: 14px; color: #333;'>Yth. Bapak/Ibu <strong>$fullName</strong>,</p>
			
						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Terima kasih telah mengirimkan CV Anda kepada kami. Kami telah menerima data Anda dan dinyatakan sesuai untuk diproses lebih lanjut.
						</p>
			
						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Tim Crewing <strong>PT Andhika Lines</strong> akan menghubungi Anda untuk tahapan selanjutnya apabila diperlukan.
							Mohon pastikan kontak Anda tetap aktif untuk mempermudah komunikasi.
						</p>
			
						<p style='font-size: 14px; color: #333; line-height: 1.6;'>
							Akun login Anda telah dibuat. Silakan simpan informasi berikut dengan baik:
						</p>
			
						<ul style='font-size: 14px; color: #333;'>
							<li><strong>Username:</strong> $username</li>
							<li><strong>Password:</strong> $password</li>
						</ul>
			
						<p style='margin-top: 30px; font-size: 14px; color: #333;'>
							Hormat kami,<br>
							<strong>Crewing Team</strong><br>
							PT Andhika Lines
						</p>
			
						<hr style='border: none; border-top: 1px solid #ccc; margin-top: 40px;'>
			
						<p style='font-size: 12px; color: #777; text-align: center;'>
							Email ini dikirim otomatis oleh sistem Crewing PT Andhika Lines. Mohon tidak membalas email ini.
						</p>
					</div>
				</div>
			";
			

			if (!$mail->send()) {
				log_message('error', 'Pick Up Email failed to ' . $recipientEmail . ': ' . $mail->ErrorInfo);
			} else {
				log_message('info', "Pick Up email sent to $recipientEmail");
			}
		} catch (Exception $e) {
			log_message('error', 'Exception while sending Pick Up email: ' . $e->getMessage());
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
				$this->getDataCVOtherForm($idPerson,$kdCmp);
			}
		}
	}

	function printTrainEvaluation($id = "", $idPerson = "")
	{
		$dataOut = array();
		$sql = "SELECT * FROM tblevaluation WHERE deletests = '0' AND id = '".$id."' AND idperson = '".$idPerson."'";

		$rsl = $this->MCrewscv->getDataQuery($sql);

		$employeeName = '';
		$designation = '';
		$dateOfTraining = '';
		$placeOfTraining = '';
		$subject = '';
		$dateOfEvaluation = '';
		$evaluatorNameDesignation = '';
		
		if (!empty($rsl) && count($rsl) > 0) {
			$row = $rsl[0]; 

			$employeeName = !empty($row->employeeName) ? htmlspecialchars($row->employeeName) : 'N/A';
			$designation = !empty($row->designation) ? htmlspecialchars($row->designation) : 'N/A';
			$dateOfTraining = !empty($row->dateOfTraining) ? htmlspecialchars($row->dateOfTraining) : 'N/A';
			$placeOfTraining = !empty($row->placeOfTraining) ? htmlspecialchars($row->placeOfTraining) : 'N/A';
			$subject = !empty($row->subject) ? htmlspecialchars($row->subject) : 'N/A';
			$dateOfEvaluation = !empty($row->dateOfEvaluation) ? htmlspecialchars($row->dateOfEvaluation) : 'N/A';
			$evaluatorNameDesignation = !empty($row->evaluatorNameDesignation) ? htmlspecialchars($row->evaluatorNameDesignation) : 'N/A';
			$trainingMaterialSuggestion = !empty($row->training_material_suggestion) ? htmlspecialchars($row->training_material_suggestion) : 'N/A';
			$futureTrainingExpectation = !empty($row->future_training_expectation) ? htmlspecialchars($row->future_training_expectation) : 'N/A';

			function getCheckmark($value, $expected) {
				return ((int) $value === (int) $expected) ? '&#10004;' : ''; 
			}

			$evalTable = "";
			foreach ($rsl as $row) {
				$evalTable .= '<tr>';

				$evalTable .= '<td>1</td>';
				$evalTable .= '<td>Employee Understanding with the job after training</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->employee_job_understanding, 1) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->employee_job_understanding, 2) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->employee_job_understanding, 3) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->employee_job_understanding, 4) . '</td>';
				$evalTable .= '</tr>';

				$evalTable .= '<tr>';
				$evalTable .= '<td>2</td>';
				$evalTable .= '<td>Improvement for employee with Quality / productivity and skill after training</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->quality_productivity_skill, 1) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->quality_productivity_skill, 2) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->quality_productivity_skill, 3) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->quality_productivity_skill, 4) . '</td>';
				$evalTable .= '</tr>';

				$evalTable .= '<tr>';
				$evalTable .= '<td>3</td>';
				$evalTable .= '<td>Improvement for employee in initiations and idea after training</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->initiative_and_ideas, 1) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->initiative_and_ideas, 2) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->initiative_and_ideas, 3) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->initiative_and_ideas, 4) . '</td>';
				$evalTable .= '</tr>';

				$evalTable .= '<tr>';
				$evalTable .= '<td>4</td>';
				$evalTable .= '<td>General performance about this employee after training</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->general_performance, 1) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->general_performance, 2) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->general_performance, 3) . '</td>';
				$evalTable .= '<td style="text-align:center;">' . getCheckmark($row->general_performance, 4) . '</td>';
				$evalTable .= '</tr>';

				$evalTable .= '<tr>';
				$evalTable .= '<td>5</td>';
				$evalTable .= '<td>Suggestion for material and style to improve employee\'s job performance</td>';
				$evalTable .= '<td colspan="4" style="padding: 10px; height: 80px; vertical-align: top; border: 1px solid #000;">
								' . nl2br(htmlspecialchars($row->training_material_suggestion)) . '
							</td>';
				$evalTable .= '</tr>';

				$evalTable .= '<tr>';
				$evalTable .= '<td>6</td>';
				$evalTable .= '<td>Advise and expectation in the next training program</td>';
				$evalTable .= '<td colspan="4" style="padding: 10px; height: 80px; vertical-align: top; border: 1px solid #000;">
								' . nl2br(htmlspecialchars($row->future_training_expectation)) . '
							</td>';
				$evalTable .= '</tr>';


			}

		}

		$dataOut = array(
			'employeeName' => $employeeName,
			'designation' => $designation,
			'dateOfTraining' => $dateOfTraining,
			'placeOfTraining' => $placeOfTraining,
			'subject' => $subject,
			'dateOfEvaluation' => $dateOfEvaluation,
			'evaluatorNameDesignation' => $evaluatorNameDesignation,
			'training_material_suggestion' => $trainingMaterialSuggestion,
			'future_training_expectation' => $futureTrainingExpectation,
			'evalTable' => $evalTable
		);

		require("application/views/frontend/pdf/mpdf60/mpdf.php");
		$mpdf = new mPDF('utf-8', 'A4');
		ob_start();
		$this->load->view('frontend/exportTrainEvaluation', $dataOut);
		$html = ob_get_contents();
		ob_end_clean();
		$mpdf->WriteHTML(utf8_encode($html));
		$mpdf->Output("Training_Evaluation.pdf", 'I');
		exit;
	}

	function exportPDFCrewEvaluation($id_report)
	{
		$dataOut = array();
		$label_reject = "";
		 
		function getChecked($value) {
			return ($value === 'Y') ? '&#10004;' : '';
		}
		
		$sqlReport = "SELECT * FROM crew_evaluation_report WHERE id = '".$id_report."' AND deletests = 0";		
		$reportData = $this->MCrewscv->getDataQuery($sqlReport);

		$row = $reportData[0];

		$vessel = $row->vessel;
		$seafarerName = $row->seafarer_name;
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
		
		if ($row->st_reject == 'Y') {
			$label_reject = '<span class="badge badge-danger" style="font-size:18px;padding:10px;background-color:red;margin-left:20px;">REJECTED</span>';
		}

		$reasonMidway = getChecked($row->reason_midway_contract);
		$reasonLeaving = getChecked($row->reason_leaving_vessel);
		$reasonSigningOff = getChecked($row->reason_signing_off);
		$reasonSpecial = getChecked($row->reason_special_request);

		$qrCodePathChief = !empty($row->qrcode_reporting_chief) ? base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_chief) : '';
		$qrCodePathMaster = !empty($row->qrcode_reporting_master) ? base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_master) : '';
		$qrCodePathSeafarer = !empty($row->qrcode_seafarer) ? base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_seafarer) : '';
		$qrCodePathCM = !empty($row->qrcode_reporting_cm) ? base_url('assets/imgQRCodeCrewCV/' . $row->qrcode_reporting_cm) : '';

		$sqlCriteria = "SELECT * FROM crew_evaluation_criteria 
						WHERE deletests = '0' AND id_report = '".intval($id_report)."' ORDER BY id ASC";
		$criteriaData = $this->MCrewscv->getDataQuery($sqlCriteria);

		$criteriaTable = '';
		if (count($criteriaData) > 0) {
			foreach ($criteriaData as $criteriaRow) {
				$criteriaTable .= '<tr>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: left;">'.$criteriaRow->criteria_name.'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->excellent).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->good).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->fair).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.getChecked($criteriaRow->poor).'</td>';
					$criteriaTable .= '<td style="border: 1px solid black; padding: 10px; text-align: center;">'.$criteriaRow->identify.'</td>';
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
			'qrCodePathSeafarer' => $qrCodePathSeafarer,
			'qrCodePathCM' => $qrCodePathCM,
			'remark_reject' => $remark_reject,
			'label_reject' => $label_reject,
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


	function transmital($idPerson = "")
	{
		$dataOut = array();
		
		$sqlCert = "SELECT certname, docno, issdate, expdate FROM tblcertdoc 
					WHERE idperson = '".$idPerson."' AND deletests = '0' ORDER BY certname ASC";
		$certResults = $this->MCrewscv->getDataQuery($sqlCert, array($idPerson));

		$sqlCrew = "SELECT 
					TRIM(CONCAT(mp.fname, ' ', mp.mname, ' ', mp.lname)) AS fullName,
					mr.nmrank AS rankName,
					mv.nmvsl AS vesselName
				FROM tblcontract tc
				JOIN mstpersonal mp ON tc.idperson = mp.idperson
				LEFT JOIN mstrank mr ON tc.signonrank = mr.kdrank
				LEFT JOIN mstvessel mv ON tc.signonvsl = mv.kdvsl
				WHERE tc.idperson = '".$idPerson."' AND tc.deletests = '0'";
		
		$crewResult = $this->MCrewscv->getDataQuery($sqlCrew, array($idPerson));

		$crewName = $crewResult ? $crewResult[0]->fullName : 'Unknown';
		$crewRank = $crewResult ? $crewResult[0]->rankName : 'Unknown';
		$vesselName = $crewResult ? $crewResult[0]->vesselName : 'Unknown';
		
		$certTable = '';
		if (!empty($certResults)) {
			foreach ($certResults as $cert) {
				$certTable .= '<tr>';
				$certTable .= '<td class="cert-name" style="text-align: left;">' . htmlspecialchars($cert->certname) . '</td>';
				$certTable .= '<td><input type="text" style="width: 50px; border: none; text-align: center;"></td>';
				$issDate = ($cert->issdate && $cert->issdate !== '0000-00-00') ? date('d M Y', strtotime($cert->issdate)) : 'N/A';
				$certTable .= '<td style="border: none; text-align: center;">' . $issDate . '</td>';
				$expDate = ($cert->expdate && $cert->expdate !== '0000-00-00') ? date('d M Y', strtotime($cert->expdate)) : 'Unlimited';
				$certTable .= '<td style="border: none; text-align: center;">' . $expDate . '</td>';
				$certTable .= '<td class="document-number" style="border-bottom: 1px solid black; text-align: left;">' . htmlspecialchars($cert->docno) . '</td>';
				$certTable .= '</tr>';
			}        
			$certTable .= '<tr>';
				$certTable .= '<td colspan="5" style="text-align: left; font-weight: bold;">Other Certificate:</td>';
			$certTable .= '</tr>';
			for ($i=1; $i <= 10; $i++) { 
				$certTable .= '<tr>';
				$certTable .= '<td class="cert-name" style="text-align: left; border-bottom: 1px dotted black;"></td>';
				$certTable .= '<td><input type="text" style="width: 50px; border: none; text-align: center;"></td>';
				$certTable .= '<td style="border-bottom: 1px dotted black; text-align: center;"></td>';
				$certTable .= '<td style="border-bottom: 1px dotted black; text-align: center;"></td>';
				$certTable .= '<td style="border-bottom: 1px dotted black; text-align: center;"></td>';
				$certTable .= '</tr>';
			}
		}

		$dataOut['crewName'] = $crewName;
		$dataOut['crewRank'] = $crewRank;
		$dataOut['vesselName'] = $vesselName;
		$dataOut['certTable'] = $certTable;

		$nama_dokumen = "Transmital_Name_" . $crewName;
		require("application/views/frontend/pdf/mpdf60/mpdf.php");
		$mpdf = new mPDF('utf-8', 'A4');

		ob_start();
		$this->load->view('frontend/exportTransmital', $dataOut);
		$html = ob_get_contents();
		ob_end_clean();
		$mpdf->WriteHTML(utf8_encode($html));
		$mpdf->Output($nama_dokumen . ".pdf", 'I');
		exit;
	}

	function getCrewEvaluation()
	{
		$idPerson = $this->input->post('idperson');
		$dataOut = array();
		$trCrewEvaluation = "";
		$no = 1;

		$sql = "SELECT * FROM crew_evaluation_report WHERE idperson = '".$idPerson."' AND deletests = '0'";
		$rsl = $this->MCrewscv->getDataQuery($sql, array($idPerson));

		if ($rsl && count($rsl) > 0) {
			foreach ($rsl as $val) {
				$btnAct = "<div class=\"btn-group\" role=\"group\">";
				$btnAct .= "<button class=\"btn btn-success btn-xs\" title=\"View\" onclick=\"ViewPrintCrewEvaluation('".$val->id."', '".$val->idperson."');\">
								<i class='fa fa-eye'></i> View
							</button>";
				$btnAct .= "</div>";

				$trCrewEvaluation .= "<tr data-id=\"".$val->id."\" style=\"vertical-align: middle;\">";
					$trCrewEvaluation .= "<td style=\"text-align:center;\">".$no."</td>";
					$trCrewEvaluation .= "<td style=\"text-align:left; padding-left:10px;\">".$val->vessel."</td>";
					$trCrewEvaluation .= "<td style=\"text-align:center;\">".$val->seafarer_name."</td>";
					$trCrewEvaluation .= "<td style=\"text-align:center;\">".$val->rank."</td>";
					$trCrewEvaluation .= "<td style=\"text-align:center;\">".$val->date_of_report."</td>";
					$trCrewEvaluation .= "<td style=\"text-align:center;\">".$val->reporting_period_from."</td>";
					$trCrewEvaluation .= "<td style=\"text-align:center;\">".$val->reporting_period_to."</td>";
					$trCrewEvaluation .= "<td style=\"text-align:center;\">".$btnAct."</td>";
				$trCrewEvaluation .= "</tr>";

				$no++;
			}
		}
		else {
			$trCrewEvaluation = '<tr><td colspan="8" style="text-align:center;">No evaluation data found</td></tr>';
		}

		$dataOut['trCrewEvaluation'] = $trCrewEvaluation;
		echo json_encode($dataOut);
		exit; 

	}
	
	function getTrainingEvaluation()
	{
		$idPerson = $this->input->post('idperson');
		$dataOut = array();
		$trTraining = "";
		$no = 1;

		$sql = "SELECT * FROM tblevaluation WHERE idperson = '".$idPerson."' AND deletests = '0'";
		$rsl = $this->MCrewscv->getDataQuery($sql, array($idPerson));

		if (count($rsl) > 0) {
			foreach ($rsl as $val) {
				$btnAct = "<div class=\"btn-group\" role=\"group\">";
				$btnAct .= "<button class=\"btn btn-danger btn-xs\" style=\"margin-right: 10px;\" title=\"Delete\" onclick=\"deleteData('".$val->id."','".$val->idperson."');\">
								<i class='fa fa-trash'></i> Delete
							</button>";
				$btnAct .= "<button class=\"btn btn-primary btn-xs\" style=\"margin-right: 10px;\" title=\"Edit\" onclick=\"editData('".$val->id."');\">
								<i class='fa fa-edit'></i> Edit
							</button>";
				$btnAct .= "<button class=\"btn btn-success btn-xs\" title=\"View\" onclick=\"ViewPrint('".$val->id."');\">
								<i class='fa fa-eye'></i> View
							</button>";
				$btnAct .= "</div>";

				$trTraining .= "<tr data-id=\"".$val->id."\" style=\"vertical-align: middle;\">";
					$trTraining .= "<td style=\"text-align:center;\">".$no."</td>";
					$trTraining .= "<td style=\"text-align:left; padding-left:10px;\">".$val->employeeName."</td>";
					$trTraining .= "<td style=\"text-align:center;\">".$val->designation."</td>";
					$trTraining .= "<td style=\"text-align:center;\">".$val->dateOfTraining."</td>";
					$trTraining .= "<td style=\"text-align:center;\">".$val->placeOfTraining."</td>";
					$trTraining .= "<td style=\"text-align:center;\">".$val->subject."</td>";
					$trTraining .= "<td style=\"text-align:center;\">".$val->dateOfEvaluation."</td>";
					$trTraining .= "<td style=\"text-align:center;\">".$val->evaluatorNameDesignation."</td>";
					$trTraining .= "<td style=\"text-align:center;\">".$btnAct."</td>";
				$trTraining .= "</tr>";

				$no++;
			}
		}

		$dataOut['trTraining'] = $trTraining;
		echo json_encode($dataOut);
	}

	function getDataEditCrewEvaluation()
	{
		$dataOut = array();
		$id = $this->input->post('id', true);

		try {
			$sqlReport = "SELECT * FROM crew_evaluation_report WHERE id = '".$id."' AND deletests = '0'";
			$reportData = $this->MCrewscv->getDataQuery($sqlReport, array($id));
			
			if(empty($reportData)) {
				throw new Exception("Data not found");
			}
			
			$idPerson = $reportData[0]->idperson;
			
			$sqlCriteria = "SELECT * FROM crew_evaluation_criteria WHERE idperson = '".$idPerson."' AND deletests = '0'";
			$criteriaData = $this->MCrewscv->getDataQuery($sqlCriteria, array($idPerson));

			$mappedCriteria = array();
			foreach($criteriaData as $criteria) {
				$mappedCriteria[$criteria->criteria_name] = array(
					'excellent' => $criteria->excellent,
					'good' => $criteria->good,
					'fair' => $criteria->fair,
					'poor' => $criteria->poor,
					'identify' => $criteria->identify
				);
			}

			$dataOut = array(
				'status' => 'success',
				'report' => array(
					'idperson' => $idPerson,
					'vessel' => $reportData[0]->vessel,
					'seafarer_name' => $reportData[0]->seafarer_name,
					'rank' => $reportData[0]->rank,
					'date_of_report' => $reportData[0]->date_of_report,
					'reporting_period_from' => $reportData[0]->reporting_period_from,
					'reporting_period_to' => $reportData[0]->reporting_period_to,
					'reason_midway_contract' => $reportData[0]->reason_midway_contract,
					'reason_signing_off' => $reportData[0]->reason_signing_off,
					'reason_leaving_vessel' => $reportData[0]->reason_leaving_vessel,
					'reason_special_request' => $reportData[0]->reason_special_request,
					'master_comments' => $reportData[0]->master_comments,
					'reporting_officer_comments' => $reportData[0]->reporting_officer_comments,
					're_employ' => $reportData[0]->re_employ,
					'promote' => $reportData[0]->promote,
					'reporting_officer_name' => $reportData[0]->reporting_officer_name,
					'reporting_officer_rank' => $reportData[0]->reporting_officer_rank,
					'mastercoofullname' => $reportData[0]->mastercoofullname,
					'received_by_cm' => $reportData[0]->received_by_cm,
					'date_of_receipt' => $reportData[0]->date_of_receipt
				),
				'criteria' => $mappedCriteria
			);

		} catch (Exception $e) {
			$dataOut = array(
				'status' => 'error',
				'message' => $e->getMessage()
			);
		}

		echo json_encode($dataOut);
	}

	function getDataEdit()
	{
		$dataOut = array();
		$id = $this->input->post('txtIdEditTrain', true);

		$sql = "SELECT * FROM tblevaluation WHERE id = '".$id."'";
		$rsl = $this->MCrewscv->getDataQuery($sql, array($id));

		if (!empty($rsl)) {
			$dataOut = array(
				'status' => 'success',
				'employeeName' => $rsl[0]->employeeName,
				'designation' => $rsl[0]->designation,
				'dateOfTraining' => $rsl[0]->dateOfTraining,
				'placeOfTraining' => $rsl[0]->placeOfTraining,
				'subject' => $rsl[0]->subject,
				'dateOfEvaluation' => $rsl[0]->dateOfEvaluation,
				'evaluatorNameDesignation' => $rsl[0]->evaluatorNameDesignation,
				'employee_job_understanding' => $rsl[0]->employee_job_understanding,
				'quality_productivity_skill' => $rsl[0]->quality_productivity_skill,
				'initiative_and_ideas' => $rsl[0]->initiative_and_ideas,
				'general_performance' => $rsl[0]->general_performance,
				'training_material_suggestion' => $rsl[0]->training_material_suggestion,
				'future_training_expectation' => $rsl[0]->future_training_expectation
			);
		} else {
			$dataOut['status'] = 'error';
			$dataOut['message'] = 'Data not found';
		}

		echo json_encode($dataOut);
	}

	function saveDataCrewEvaluation() {
		$data = $_POST;
		$dataIns = array();
		$criteriaData = array();
		$stData = "";
		$txtIdEditCrew = isset($data['txtIdEditCrew']) ? $data['txtIdEditCrew'] : '';
		$idPerson = isset($data['txtIdPerson']) ? $data['txtIdPerson'] : '';
		$userDateTimeNow = $this->session->userdata('userCrewSystem') . "/" . date('Ymd') . "/" . date('H:i:s');

		try {
			
			$dataIns['vessel'] = isset($data['txtVessel']) ? $data['txtVessel'] : '';
			$dataIns['seafarer_name'] = isset($data['txtSeafarerName']) ? $data['txtSeafarerName'] : '';
			$dataIns['rank'] = isset($data['txtRank']) ? $data['txtRank'] : '';
			$dataIns['date_of_report'] = (!empty($data['txtDateOfReport']) && $data['txtDateOfReport'] != "0000-00-00") ? $data['txtDateOfReport'] : null;
			$dataIns['reporting_period_from'] = (!empty($data['txtDateReportingPeriodFrom']) && $data['txtDateReportingPeriodFrom'] != "0000-00-00") ? $data['txtDateReportingPeriodFrom'] : null;
			$dataIns['reporting_period_to'] = (!empty($data['txtDateReportingPeriodTo']) && $data['txtDateReportingPeriodTo'] != "0000-00-00") ? $data['txtDateReportingPeriodTo'] : null;
			$dataIns['idperson'] = $idPerson;
			
			$dataIns['reason_midway_contract'] = isset($data['reasonMidway']) ? $data['reasonMidway'] : '';
			$dataIns['reason_signing_off'] = isset($data['reasonSigningOff']) ? $data['reasonSigningOff'] : '';
			$dataIns['reason_leaving_vessel'] = isset($data['reasonLeaving']) ? $data['reasonLeaving'] : '';
			$dataIns['reason_special_request'] = isset($data['reasonSpecialRequest']) ? $data['reasonSpecialRequest'] : '';

			$dataIns['master_comments'] = isset($data['txtMasterComments']) ? $data['txtMasterComments'] : '';
			$dataIns['reporting_officer_comments'] = isset($data['txtOfficerComments']) ? $data['txtOfficerComments'] : '';
			$dataIns['promote'] = isset($data['txtPromoted']) ? $data['txtPromoted'] : 'N';
			$dataIns['re_employ'] = isset($data['txtReemploy']) ? $data['txtReemploy'] : 'N';
			$dataIns['reporting_officer_name'] = isset($data['txtfullname']) ? $data['txtfullname'] : '';
			$dataIns['reporting_officer_rank'] = isset($data['slcRank']) ? $data['slcRank'] : '';
			$dataIns{'mastercoofullname'} = isset($data['txtmastercoofullname']) ? $data['txtmastercoofullname'] : '';
			$dataIns['received_by_cm'] = isset($data['txtreceived']) ? $data['txtreceived'] : '';
			$dataIns['date_of_receipt'] = (!empty($data['txtDateReceipt']) && $data['txtDateReceipt'] != "0000-00-00") ? $data['txtDateReceipt'] : null;
			$data['addUsrDate'] = $userDateTimeNow;

			if (empty($txtIdEditCrew)) {
				$insertId = $this->MCrewscv->insData("crew_evaluation_report", $dataIns);
				$mode = "insert";
				$id = $insertId;
			} else {
				$whereNya = "id = '" . $txtIdEditCrew . "'";
				$this->MCrewscv->updateData($whereNya, $dataIns, "crew_evaluation_report");
				$mode = "update";
				$id = $txtIdEditCrew;
			}

			$criteriaList = array(
				"Ability/Knowledge of Job" => "ability",
				"Safety Consciousness" => "safety",
				"Dependability & Integrity" => "integrity",
				"Initiative" => "initiative",
				"Conduct" => "conduct",
				"Ability to get on with others" => "abilityGetOn",
				"Appearance (+ uniforms)" => "appearance",
				"Sobriety" => "sobriety",
				"English Language" => "english",
				"Leadership (Officers)" => "leadership"
			);

			if (!empty($txtIdEditCrew)) {
				$this->MCrewscv->delData("crew_evaluation_criteria", "idperson = '" . $idPerson . "'");
			}

			foreach ($criteriaList as $criteriaName => $criteriaId) {
				$value = isset($data[$criteriaId]) ? $data[$criteriaId] : '';
				$criteriaData = array(
					'idperson' => $idPerson,
					'criteria_name' => $criteriaName,
					'excellent' => ($value == '4') ? 'Y' : '',
					'good' => ($value == '3') ? 'Y' : '',
					'fair' => ($value == '2') ? 'Y' : '',
					'poor' => ($value == '1') ? 'Y' : '',
					'identify' => isset($data["txtIdentify" . ucfirst($criteriaId)]) ? $data["txtIdentify" . ucfirst($criteriaId)] : '',
					'addUsrDate' => $userDateTimeNow
				);

				$this->MCrewscv->insData("crew_evaluation_criteria", $criteriaData);
			}

			$stData = array(
				"status" => "success",
				"message" => "Save Success..!!",
				"mode" => $mode,
				"id" => $id
			);
		} catch (\Throwable $th) {
			$stData = array(
				"status" => "error",
				"message" => "Error: " . $th->getMessage()
			);
		}

		echo json_encode($stData);
	}

	function saveDataTrainEvaluation()
	{
		$data = $_POST;
		$dataIns = array();
		$stData = "";
		$txtIdEditTrain = $data['txtIdEditTrain']; 
		$userDateTimeNow = $this->session->userdata('userCrewSystem')."/".date('Ymd')."/".date('H:i:s');

		try {
			$dataIns['employeeName'] = $data['txtemployeeName'];
			$dataIns['designation'] = $data['txtdesignation'];
			$dataIns['dateOfTraining'] = $data['txtDateOfTraining'];
			$dataIns['placeOfTraining'] = $data['txtplaceOfTraining'];
			$dataIns['subject'] = $data['txtsubject'];
			$dataIns['dateOfEvaluation'] = $data['txtDateOfEvaluation'];
			$dataIns['evaluatorNameDesignation'] = $data['txtevaluator'];
			$dataIns['idperson'] = $data['txtIdPerson'];

			
			$dataIns['employee_job_understanding'] = isset($data['score1']) ? implode(",", $data['score1']) : "";
			$dataIns['quality_productivity_skill'] = isset($data['score2']) ? implode(",", $data['score2']) : "";
			$dataIns['initiative_and_ideas'] = isset($data['score3']) ? implode(",", $data['score3']) : "";
			$dataIns['general_performance'] = isset($data['score4']) ? implode(",", $data['score4']) : "";
			$dataIns['training_material_suggestion'] = $data['suggestion'];
			$dataIns['future_training_expectation'] = $data['advise'];

			if(empty($txtIdEditTrain)) {
				$dataIns['addusrdate'] = $userDateTimeNow;
				$insertId = $this->MCrewscv->insData("tblevaluation", $dataIns);
				$mode = "insert";
				$id = $insertId;
			} else {
				$dataIns['updusrdate'] = $userDateTimeNow;
				$whereNya = "id = '".$txtIdEditTrain."'";
				$this->MCrewscv->updateData($whereNya, $dataIns, "tblevaluation");
				$mode = "update";
				$id = $txtIdEditTrain;
			}

			$stData = array(
				"status" => "success",
				"message" => "Save Success..!!",
				"mode" => $mode,
				"id" => $id
			);
		} catch (Exception $ex) {
			$stData = array(
				"status" => "error",
				"message" => "Failed => ".$ex->getMessage()
			);
		}

		echo json_encode($stData);
	}

	function delData()
	{
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");

		$id = $_POST['id'];
		$idPerson = $_POST['idPerson'];			

		$dataDel = array(
			'deletests' => "1",
			'delUserDt' => $userInit . "/" . $dateNow
		);

		$whereNya = "id = '".$id."' AND idperson = '".$idPerson."'";
		$this->MCrewscv->updateData($whereNya, $dataDel, "tblevaluation");

		echo json_encode(array("status" => "Success"));
	}
 
	function delDataCrewEvaluation() 
	{
		$userInit = $this->session->userdata('userInitCrewSystem');
		$dateNow = date("Ymd/h:i:s");

		$id = $this->input->post('id');
		$idPerson = $this->input->post('idPerson');

		try {
			$dataDel = array(
				'deletests' => "1",
				'delUserDt' => $userInit . "/" . $dateNow
			);
			  
			$whereReport = array(
				"id" => $id,
				"idperson" => $idPerson
			);
			$this->MCrewscv->updateData($whereReport, $dataDel, "crew_evaluation_report");
 
			$whereCriteria = array(
				"idperson" => $idPerson
			);
			$this->MCrewscv->updateData($whereCriteria, $dataDel, "crew_evaluation_criteria");

			echo json_encode(array("status" => "Success"));

		} catch (Exception $e) {
			echo json_encode(array(
				"status" => "Error",
				"message" => "Delete failed: " . $e->getMessage()
			));
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
				//$photo = "<img src=\"".base_url('imgProfile/'.$photo)."\" style=\"width:90px;height:120px;\">";
				// $photo = "<img src=\"./imgProfile/".$photo."\" style=\"width:90px;height:120px;\">";
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
					if(strtolower($val->certname) == strtolower("proficiency in survival craft") AND 
						strtolower($val->certname) == strtolower("Proficiency In Survival Craft and Rescue Boat"))
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

	function getDataCVSuntechno($idPerson = "", $company = "")
	{
		$dataContext = new DataContext();
		$dataOut = array();
		$dateNow = date("Y-m-d");
		$dateNowTime = date("Y-m-d h:i:s");

		$sql = "SELECT A.*, TRIM(CONCAT(A.fname,' ', A.mname,' ' , A.lname)) AS fullName, 
					B.NmKota, C.NmNegara, D.NmKota AS pKota, 
					DATE_FORMAT(FROM_DAYS(DATEDIFF(CURRENT_DATE, dob)), '%y') AS age
				FROM mstpersonal A
				LEFT JOIN tblkota B ON A.pob = B.KdKota
				LEFT JOIN tblnegara C ON A.nationalid = C.KdNegara
				LEFT JOIN tblkota D ON A.pcity = D.KdKota
				WHERE A.deletests = '0' AND A.idperson = '".$idPerson."'";

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

			$sqlContract = "SELECT COUNT(*) AS total_contracts 
							FROM tblcontract 
							WHERE idperson = '".$idPerson."' 
							AND kdcmprec = '005' 
							AND signoffdt IS NOT NULL";
			$contractResult = $this->MCrewscv->getDataQuery($sqlContract);

			$sqlContractMonth = "SELECT SUM(TIMESTAMPDIFF(MONTH, signondt, signoffdt)) AS total_months 
								FROM tblcontract 
								WHERE idperson = '".$idPerson."' 
								AND kdcmprec = '005' 
								AND signoffdt IS NOT NULL";
			$contractMonthResult = $this->MCrewscv->getDataQuery($sqlContractMonth);

			$totalMonthsOp = ($contractMonthResult[0]->total_months) ? $contractMonthResult[0]->total_months : 0;
			$yearsOp = ($contractResult[0]->total_contracts) ? $contractResult[0]->total_contracts : 0;
			$monthsOnlyOp = $totalMonthsOp % 12;

			$dataOut['yearyOpSun'] = $yearsOp . ' years ' . $monthsOnlyOp . ' months';

			$sqlRank = "SELECT SUM(TIMESTAMPDIFF(YEAR, signondt, signoffdt)) AS total_years 
						FROM tblcontract 
						WHERE idperson = '".$idPerson."' 
						AND kdcmprec = '005' 
						AND signoffdt IS NOT NULL";
			$rankResult = $this->MCrewscv->getDataQuery($sqlRank);

			$sqlRankMonth = "SELECT SUM(TIMESTAMPDIFF(MONTH, signondt, signoffdt)) AS total_months 
							FROM tblcontract 
							WHERE idperson = '".$idPerson."' 
							AND kdcmprec = '005' 
							AND signoffdt IS NOT NULL";
			$rankMonthResult = $this->MCrewscv->getDataQuery($sqlRankMonth);

			$totalMonthsRank = ($rankMonthResult[0]->total_months) ? $rankMonthResult[0]->total_months : 0;
			$yearsRank = ($rankResult[0]->total_years) ? $rankResult[0]->total_years : 0;
			$monthsOnlyRank = $totalMonthsRank % 12;

			$dataOut['yearyRankSun'] = $yearsRank . ' years ' . $monthsOnlyRank . ' months';

			$dataOut['trSeaService'] = $this->getSeaServiceRecord($idPerson, "suntechno");
			$dataOut['teamLead'] = $this->detilperdir($idPerson);
		}

		$this->load->view("frontend/exportPersonalIdSuntechno", $dataOut);
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
							'PROFICIENCY IN SURVIVAL CRAFT AND RESCUE BOAT'=>'Prof. Survival Craft & Rescue Boat',
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
			$labelTemp = array('basic training for oil and chemical tanker cargo operations'=>'BASIC TRAINING FOR OIL AND CHEMICAL TANKER CARGO OPERATIONS',
							'tanker safety (oil)'=>'ADVANCE TRAINING FOR OIL TANKER CARGO OPERATION',
							'tanker safety (chemical)'=>'ADVANCE TRAINING FOR CHEMICAL TANKER CARGO OPERATION',
							// 'tanker familiarisation (gas)'=>'Basic Liquid Gas Tanker',
							'tanker safety (gas)'=>'BASIC TRAINING FOR LIQUEFIED GAS TANKER CARGO OPERATION');
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
						$expDate = "Unlimited";
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