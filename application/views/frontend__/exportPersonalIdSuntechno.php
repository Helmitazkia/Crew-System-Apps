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
	<div style="width:760px;height:1080px;">
		<div class="reportPDF" style="width:745px;min-height:0px;">
			<table style="width:745px;margin-top:0px;" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td style="width:100px;font-size:10px;vertical-align:middle;text-align:center;"><?php echo $photo; ?></td>
					<td style="width:540px;font-size:20px;vertical-align:middle;font-weight:bold;text-align:center;">BIO DATA</td>
					<td style="width:105px;font-size:10px;vertical-align:middle;font-weight:bold;text-align:center;">DATE <span style="border-bottom:1px;border-style:solid;"><?php echo $dateNow; ?></span></td>
				</tr>
				<tr>
					<td colspan="3">
						<table style="width:745px;margin-top:10px;" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width:65px;font-size:10px;vertical-align:top;"><b>Rank :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $rank; ?></td>
								<td style="width:65px;font-size:10px;vertical-align:top;text-align:right;"><b>Employed :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"></td>
								<td></td>
								<td style="width:80px;font-size:10px;vertical-align:top;text-align:right;"><b>Vessel's Name :</b></td>
								<td style="width:120px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;" colspan="2"><?php echo $vesselFor; ?></td>
							</tr>
							<tr>
								<td style="width:65px;font-size:10px;vertical-align:top;"><b>Name :</b></td>
								<td style="font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;" colspan="2"><?php echo $fullName; ?></td>
								<td style="width:100px;font-size:10px;vertical-align:top;text-align:left;" colspan="2"><b>(Sur)(Given)(Middle)</b></td>
								<td style="width:80px;font-size:10px;vertical-align:top;text-align:right;"><b>TEL :</b></td>
								<td style="width:120px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;" colspan="2"><?php echo $contactNo; ?></td>
							</tr>
							<tr>
								<td style="width:65px;font-size:10px;vertical-align:top;"><b>Address :</b></td>
								<td style="font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;" colspan="7"><?php echo $address; ?></td>
							</tr>
							<tr>
								<td style="width:65px;font-size:10px;vertical-align:top;"><b>Birth Date :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $dob; ?></td>
								<td style="width:65px;font-size:10px;vertical-align:top;text-align:right;"><b>Age :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $age; ?></td>
								<td style="width:80px;font-size:10px;vertical-align:top;text-align:right;"><b>Birth Place :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $kotaLahir; ?></td>
								<td style="width:80px;font-size:10px;vertical-align:top;text-align:right;"><b>Nationality :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $negara; ?></td>
							</tr>
							 <tr>
								<td style="width:65px;font-size:10px;vertical-align:top;"><b>Civil Status :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $maritalSt; ?></td>
								<td style="width:65px;font-size:10px;vertical-align:top;text-align:right;"><b>Weight :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $wght; ?></td>
								<td style="width:80px;font-size:10px;vertical-align:top;text-align:right;"><b>Height :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $hght; ?></td>
								<td style="width:80px;font-size:10px;vertical-align:top;text-align:right;"><b>Eye Color :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $eyeColor; ?></td>
							</tr>
							<tr>
								<td style="width:65px;font-size:10px;vertical-align:top;"><b>Shoes Size :</b></td>
								<td style="width:110px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $shoesz; ?></td>
								<td style="width:70px;font-size:10px;vertical-align:top;text-align:right;"><b>Clothes Size :</b></td>
								<td style="width:100px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;"><?php echo $clothszid; ?></td>
							</tr>
							<tr>
								<td style="width:100px;font-size:9.8px;vertical-align:top;" colspan="2"><b>Name of Wife (or Nearest Relative) :</b></td>
								<td style="width:130px;font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;" colspan="3"><?php echo $wname; ?></td>
							</tr>
						</table>
					</td>
				</tr>
				 <tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="3" style="font-size:11px;font-weight:bold;">
									1. EDUCATIONAL ATTAINMENT
								</td>
							</tr>
							<tr>
								<td style="width:245px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Year</td>
								<td style="width:250px;font-size:10px;border-top:1px; border-bottom:1px; border-style:solid;vertical-align:middle;text-align:center;">School</td>
								<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Course Finished</td>
							</tr>
							<?php echo $educationSun; ?>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="3" style="font-size:11px;font-weight:bold;">
									2. LICENCE & ENDORSEMENT
								</td>
							</tr>
							<tr>
								<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Type</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Rank</td>
								<td style="width:150px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Number</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px; border-style:solid;vertical-align:middle;text-align:center;">Issued Date</td>
								<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Expiry Date</td>
							</tr>
							<?php echo $LicenseCert; ?>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="3" style="font-size:11px;font-weight:bold;">
									3. CERTIFICATES
								</td>
							</tr>
							<tr>								
								<td style="width:200px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Type</td>
								<td style="width:100px;font-size:10px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Issued By</td>
								<td style="width:200px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Number</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Issued Date</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Expiry Date</td>								
							</tr>
							<?php echo $certificates; ?>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="5" style="font-size:11px;font-weight:bold;">
									4. OTHER CERTIFICATE (SOLAS/MARPOL/TANKER/OTHERS)
								</td>
							</tr>
							<tr>
								<td style="width:120px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Item</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Number</td>
								<td style="width:60px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Issued Date</td>
								<td style="width:60px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Expired Date</td>
								<td style="width:120px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Item</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px;border-style:solid;vertical-align:middle;text-align:center;">Number</td>
								<td style="width:70px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Issued Date</td>
								<td style="width:70px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Expired Date</td>
							</tr>
							<?php echo $otherCert; ?>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="3" style="font-size:11px;font-weight:bold;">
									5. PHYSICAL INSPECTION, YELLOW CARD
								</td>
							</tr>
							<tr>
								<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Item</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px; border-right:1px; border-style:solid;vertical-align:middle;text-align:center;">Date Issued</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px; border-style:solid;vertical-align:middle;text-align:center;">Expiry Date</td>
								<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Remarks</td>
							</tr>
							<?php echo $getPhysical; ?>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="3" style="font-size:11px;font-weight:bold;">
									6. Vaccination for COVID-19
								</td>
							</tr>
							<tr>
								<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Vaccine Name</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">1st Shot</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px; border-style:solid;vertical-align:middle;text-align:center;">2st Shot</td>
								<td style="width:150px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Remarks</td>
							</tr>
							<?php echo $getVaccine; ?>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="3" style="font-size:11px;font-weight:bold;">
									7. ENGLISH LINGUISTICS
								</td>
							</tr>
							<tr>
								<td style="width:205px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">English</td>
								<td style="width:540px;font-size:10px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Evaluation</td>
							</tr>
							<?php echo $getLanguage; ?>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="3" style="font-size:11px;font-weight:bold;">
									8. TRAINING FOR (ISM SYSTEM)   *MASTER, CHIEF ENGINEER ONLY
								</td>
							</tr>
							<tr>
								<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Training for (STM'S ISM SYSTEM)</td>
								<td style="width:100px;font-size:10px;border-top:1px; border-bottom:1px; border-style:solid;vertical-align:middle;text-align:center;">Date</td>
								<td style="width:390px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Evaluation</td>
							</tr>
							<?php echo $trIsm; ?>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="3" style="font-size:11px;font-weight:bold;">
									9. CREW MATRIX
								</td>
							</tr>
							<tr>
								<td style="width:245px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Years With Operator(STC)</td>
								<td style="width:500px;font-size:10px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;"><?php echo $yearyOpSun; ?></td>
							</tr>
							<tr>
								<td style="width:245px;font-size:10px;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;vertical-align:middle;text-align:center;">Years in rank</td>
								<td style="width:500px;font-size:10px;border-right:1px;border-bottom:1px;border-style:solid;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;"><?php echo $yearyRankSun; ?></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td colspan="2" style="font-size:11px;font-weight:bold;">
									10. SEAMAN'S HISTORY
								</td>
							</tr>
							<tr>
								<td colspan="2" style="font-size:10px;vertical-align:top;">SEA SERVICE (WITHIN THE LAST FIVE YEARS) LISTING MOSTRECENT VESSEL LAST</td>
							</tr>
							<tr>
								<td rowspan="4" style="width:50px;font-size:10px;vertical-align:top;">Note :</td>
								<td style="width:645px;font-size:10px;vertical-align:top;">1) Indicated whether vessel is M/V (Motor Vessel), S/S or S/T (Steam Turbine), etc.</td>
							</tr>
							<tr>
								<td style="width:645px;font-size:10px;vertical-align:top;">2) Under TYPE indicate whether Bulk, Log, VLCC, Chemical, LPG, etc.</td>
							</tr>
							<tr>
								<td style="width:645px;font-size:10px;vertical-align:top;">3) For Deck Officers and Ratings indicate Gross Tonnage of Vessel.</td>
							</tr>
							<tr>
								<td style="width:645px;font-size:10px;vertical-align:top;">4) For Engine Officers and Rating indicate Engine Type and HP (Horse-power)</td>
							</tr>
							<tr>
								<td colspan="2">
									<table style="width:745px;margin-top:5px;" cellpadding="2" cellspacing="0">
										<tr>
											<td style="width:175px;font-size:10px;border:1px solid black;vertical-align:middle;text-align:center;">Vessel's Name Flag</td>
											<td style="width:120px;font-size:10px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Type<br>Rank</td>
											<td style="width:80px;font-size:10px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Gross Ton<br>Engine Type</td>
											<td style="width:150px;font-size:10px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Company.</td>
											<td style="width:70px;font-size:10px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:middle;text-align:center;">Sign On/Off</td>
											<td style="width:120px;font-size:10px;border-top:1px;border-right:1px;border-bottom:1px;border-style:solid;vertical-align:middle;text-align:center;">Reason of<br>Sign Off</td>
										</tr>
										<?php echo $trSeaService; ?>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr style="margin-top:5px;">
					<td colspan="3">
						<table style="width:500px;margin-top:5px;" cellpadding="2" cellspacing="0">
							<tr>
								<td style="width:100px;font-size:10px;border-bottom:1px;border-style:solid;vertical-align:middle;text-align:center;">Crew's Name &nbsp;&nbsp; :</td>
								<td style="width:150px;font-size:10px;border-bottom:1px;border-style:solid;vertical-align:middle;"><?php echo $fullName; ?></td>
							</tr>
							<tr>
								<td style="width:100px;font-size:10px;border-bottom:1px;border-style:solid;vertical-align:middle;text-align:center;">Team Leader &nbsp;&nbsp; :</td>
								<td style="width:150px;font-size:10px;border-bottom:1px;border-style:solid;vertical-align:middle;"><?php echo $teamLead; ?></td>
							</tr>
						</table>
					</td>
				</tr>
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