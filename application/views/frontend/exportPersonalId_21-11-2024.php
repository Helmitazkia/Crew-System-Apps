<?php
ini_set('memory_limit', '-1');
$nama_dokumen = "crewPersonal";
require("pdf/mpdf60/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4-P');
ob_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
	<title>Expired Certificates Report</title>
</head>
<body>
	<div style="width:745px;height:1080px;">
		<div class="reportPDF" style="width:745px;min-height:0px;">
			<table style="width:745px;margin-top:0px;" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<td style="width:12%;text-align:center;">
							<img src="./image/logo_aes.png" style="width:50px;">
						</td>
						<td style="width:70%;">
							<span style="font-size:18px;font-weight:bold;color:#000099;font-family:'Arial Black';">PT. ANDHINI EKA KARYA SEJAHTERA</span><br>
							<span style="font-size:11px;font-weight:bold;color:#000099;font-family:'Arial Black';;">(ANDHIKA  GROUP)</span>
						</td>
						<td>
							<img src="./image/iso9001.png" style="width:18%;">
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="3" style="width:745px;">
							<table style="width:745px;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:12px;font-weight:bold;font-family:serif;" align="center" colspan="5"><u>PERSONAL ID</u></td>
								</tr>
								<tr>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Full Name</b></td>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $fullName; ?></td>
									<td style="width:120px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Rank</b></td>
									<td style="width:180px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $rank; ?></td>
									<td style="width:95px;font-size:10px;border:1px solid black;vertical-align:top;" rowspan="7" align="center">
										<?php echo $photo; ?></td>
								</tr>
								<tr>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Place & DOB</b></td>
									<td style="width:230px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $dob; ?></td>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>English Degree Point</b></td>
									<td style="width:180px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $degree; ?></td>
								</tr>
								<tr>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Nationality</td>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $negara; ?></td>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Next of Kin (NOK)</b></td>
									<td style="width:180px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $nextKin; ?></td>
								</tr>
								<tr>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Marital Status</b></td>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $maritalSt; ?></td>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Relation of NOK</b></td>
									<td style="width:180px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $relKin; ?></td>
								</tr>
								<tr>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Religion</b></td>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $agama; ?></td>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Contact Number NOK</b></td>
									<td style="width:180px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $famtelp; ?></td>
								</tr>
								<tr>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Contact Number</b></td>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $contactNo; ?></td>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Estimate Date of Join</b></td>
									<td style="width:180px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $availDate; ?></td>
								</tr>
								<tr>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Current Address</b></td>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;"><?php echo $address; ?></td>
									<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;"><b>Employee Code</b></td>
									<td style="width:180px;font-size:10px;border:1px solid black;vertical-align:top;"></td>
								</tr>
							</table>
							<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:12px;font-weight:bold;font-family:serif;" align="center" colspan="5"><u>Certficate Of Competency ( COC ) / Endorsment</u></td>
								</tr>
								<tr>
									<td style="width:265px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Details</b></td>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Document No</b></td>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Place Issued</b></td>
									<td style="width:90px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Date Issued</b></td>
									<td style="width:90px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Expiry Date</b></td>
								</tr>
								<tr>
									<td style="width:265px;font-size:10px;border:1px solid black;"><?php echo $cocName; ?></td>
									<td style="width:150px;font-size:10px;border:1px solid black;"><?php echo $cocDocNo; ?></td>
									<td style="width:150px;font-size:10px;border:1px solid black;"><?php echo $cocIssPlace; ?></td>
									<td style="width:90px;font-size:10px;border:1px solid black;text-align:center;"><?php echo $cocIssDate; ?></td>
									<td style="width:90px;font-size:10px;border:1px solid black;text-align:center;"><?php echo $cocExpDate; ?></td>
								</tr>
								<tr>
									<td style="width:265px;font-size:10px;border:1px solid black;"><?php echo $endorsName; ?></td>
									<td style="width:150px;font-size:10px;border:1px solid black;"><?php echo $endorsDocNo; ?></td>
									<td style="width:150px;font-size:10px;border:1px solid black;"><?php echo $endorsIssPlace; ?></td>
									<td style="width:90px;font-size:10px;border:1px solid black;text-align:center;"><?php echo $endorsIssDate; ?></td>
									<td style="width:90px;font-size:10px;border:1px solid black;text-align:center;"><?php echo $endorsExpDate; ?></td>
								</tr>
							</table>
							<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:12px;font-weight:bold;font-family:serif;" align="center" colspan="5"><u>ID Document</u></td>
								</tr>
								<tr>
									<td style="width:265px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Details</b></td>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Document No</b></td>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Place Issued</b></td>
									<td style="width:90px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Date Issued</b></td>
									<td style="width:90px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Expiry Date</b></td>
								</tr>

								<?php echo $trIdDoc; ?>
							</table>
							<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:12px;font-weight:bold;font-family:serif;" align="center" colspan="5"><u>Certificates of Proficiency (COP) as per STCW '2010</u></td>
								</tr>
								<tr>
									<td style="width:265px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Course Title</b></td>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Document No</b></td>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Place Issued</b></td>
									<td style="width:90px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Date Issued</b></td>
									<td style="width:90px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Expiry Date</b></td>
								</tr>
								<?php echo $trCop; ?>
							</table>
							<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:12px;font-weight:bold;font-family:serif;" align="center" colspan="5"><u>Tanker Specialist Certificates of Proficiency as per STCW `2010</u></td>
								</tr>
								<tr>
									<td style="width:265px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Details</b></td>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Document No</b></td>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Place Issued</b></td>
									<td style="width:90px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Date Issued</b></td>
									<td style="width:90px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Expiry Date</b></td>
								</tr>
								<?php echo $trTankerCert; ?>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<table style="width:745px;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:12px;font-weight:bold;font-family:serif;" align="center" colspan="9"><u>Sea Service Record</u></td>
								</tr>
								<tr>
									<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;" rowspan="2"><b>Company</b></td>
									<td style="width:130px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;" rowspan="2"><b>Vessel</b></td>
									<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;" rowspan="2"><b>Type</b></td>
									<td style="width:50px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;" rowspan="2"><b>G.T</b></td>
									<td style="width:50px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;" rowspan="2"><b>H.P</b></td>
									<td style="font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;" colspan="2"><b>Period</b></td>
									<td style="width:60px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;" rowspan="2"><b>Rank</b></td>
									<td style="width:60px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;" rowspan="2"><b>Reason</b></td>
								</tr>
								<tr>
									<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;">From</td>
									<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"`>To</td>
								</tr>
								<?php echo $trSeaService; ?>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="3">
							<table style="margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="width:50px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;background-color:#C6C6C6;"><b>Date</b></td>
									<td style="width:200px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;"></td>
									<td style="width:400px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;" rowspan="3"></td>
								</tr>
								<tr>
									<td style="font-size:10px;border-left:1px solid black;" colspan="2" >Comment :</td>
								</tr>
								<tr>
									<td style="height:50px;font-size:10px;border-left:1px solid black;border-bottom:1px solid black;vertical-align:middle;text-align:center;" colspan="2" ></td>
								</tr>
							</table>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</body>
</html>
 
<?php
$html = ob_get_contents();
ob_end_clean();
$mpdf->WriteHTML(utf8_encode($html));
$mpdf->Output($nama_dokumen.".pdf" ,'I');
exit;
?>