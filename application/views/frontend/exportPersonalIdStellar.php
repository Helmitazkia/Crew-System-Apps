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
			<table style="width:760px;margin-top:0px;" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<td style="width:660px;">
							<img style="width:128px;" src="<?php echo base_url('image/stellar.png'); ?>">
						</td>
						<td style="width:90px;vertical-align:top;text-align:center;" rowspan="2">
							<?php echo $photo; ?>
						</td>
					</tr>
					<tr>
						<td style="font-size:11px;font-weight:bold;font-family:serif;text-align:center;">
							CREW EMPLOYMENT APPLICATION FORM
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="width:760px;" colspan="2">
							<table style="width:100%;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:11px;font-weight:bold;font-family:serif;" colspan="4">
										Personal Particulars of Applicant
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Name of Applicant
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">
										<?php echo $fullName; ?>
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;">
										Date of Birth
									</td>
									<td style="width:200px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;">
										<?php echo $dob; ?>
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Nationality
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">
										<?php echo $negara; ?>
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;">
										Place of Birth
									</td>
									<td style="width:200px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;">
										<?php echo $placeOfBirth; ?>
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Passport Number
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">
										<?php echo $passPortNo; ?>
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;">
										Religion
									</td>
									<td style="width:200px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;">
										<?php echo $agama; ?>
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Address in Singapore
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">
										
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;">
										Telephone No
									</td>
									<td style="width:200px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;">
										<?php echo $telpNo; ?>
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Marital Status
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">
										<?php echo $maritalSt; ?>
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;">
										Relationship
									</td>
									<td style="width:200px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;">
										<?php echo $relKin; ?>
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Name of next of kin
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">
										<?php echo $nextKin; ?>
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;">
										Telephone No
									</td>
									<td style="width:200px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;">
										<?php echo $contactNo; ?>
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Contact Address
									</td>
									<td colspan="3" style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;">
										<?php echo $address; ?>
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Name of Parents & Age
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;">
										<?php echo $parents; ?>
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-bottom:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;">
										No. of Children & Age
									</td>
									<td style="width:200px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;">
										
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="width:760px;" colspan="2">
							<table style="width:100%;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Position Applied for
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">
										<?php echo $rank; ?>
									</td>
									<td style="width:320px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-right:1px;border-top:1px;border-style:solid;">
										Salary Expected in $$
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Type of Cert
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">
										<?php echo $typeOfCert; ?>
									</td>
									<td style="width:320px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-right:1px;border-top:1px;border-style:solid;">
										Restrictions:
									</td>
								</tr>
								<tr>
									<td style="width:130px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;background-color:#D4D4D4;height:20px;">
										Previous Position
									</td>
									<td style="width:250px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;">
										<?php echo $rankExp; ?>
									</td>
									<td style="width:320px;font-size:11px;vertical-align:top;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-bottom:1px;border-style:solid;">
										Previous Salary in $$
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="width:760px;" colspan="2">
							<table style="width:100%;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:11px;font-weight:bold;font-family:serif;" colspan="9">
										Previous Employment Record (Kerja lalu)
									</td>
								</tr>
								<tr>
									<td style="width:120px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;height:20px;">
										Name of Company
									</td>
									<td style="width:100px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;">
										Vessel Name
									</td>
									<td style="width:100px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;">
										Vessel Type
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-bottom:1px;border-style:solid;text-align:center;">
										Engine Type
									</td>
									<td style="width:60px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-bottom:1px;border-style:solid;text-align:center;">
										GRT
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-bottom:1px;border-style:solid;text-align:center;">
										Rank
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-bottom:1px;border-style:solid;text-align:center;">
										From
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-bottom:1px;border-style:solid;text-align:center;">
										To
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-bottom:1px;border-bottom:1px;border-style:solid;text-align:center;">
										Months
									</td>
								</tr>
								<?php echo $trSeaService; ?>
							</table>
						</td>
					</tr>
					<tr>
						<td style="width:760px;" colspan="2">
							<table style="width:100%;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:11px;font-weight:bold;font-family:serif;border:1px solid;" colspan="2">
										Name of Last Company : <?php echo $lastCompany; ?>
									</td>
								</tr>
								<tr>
									<td style="width:400px;font-size:11px;font-weight:bold;font-family:serif;border-left:1px;border-right:1px;border-style:solid;">
										Contact Telephone Number
									</td>
									<td style="width:360px;font-size:11px;font-weight:bold;font-family:serif;border-right:1px; border-style:solid;">
										Person in-Charge
									</td>
								</tr>
								<tr>
									<td style="font-size:11px;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;" colspan="2">
										<span style="font-weight:bold;"><u>DECLARATION</u></span>
										<p>I hereby certify that the above information provided by me is true, complete and accurate to the best of my knowledge.</p><br>
										<p>I further understand that any willful act on my part in withholding information or making false statement in the employment application form is in itself sufficient ground for dismissal from the company.</p>
									</td>
								</tr>
								<tr>
									<td style="font-size:11px;font-family:serif;vertical-align:bottom;border-left:1px;border-style:solid;height:50px;">
										Name of Applicant : <?php echo $fullName; ?>
									</td>
									<td style="font-size:11px;font-family:serif;vertical-align:bottom;border-right:1px;border-style:solid;">
										Signature : _______________
									</td>
								</tr>
								<tr>
									<td style="font-size:11px;font-family:serif;border-left:1px;border-right:1px;border-bottom:1px;border-style:solid;height:25px;" colspan="2">
										Date : <?php echo $signDate; ?>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="width:760px;" colspan="2">
							<table style="width:100%;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="width:20px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;height:20px;">
										No
									</td>
									<td style="width:150px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;">
										ITEMS
									</td>
									<td style="width:150px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;">
										CERTIFICATE NUMBER
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;">
										DATE OF ISSUE
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;">
										ISSUED BY
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-style:solid;text-align:center;">
										EXPIRY
									</td>
									<td style="width:80px;font-size:11px;vertical-align:middle;font-weight:bold;font-family:serif;border-left:1px;border-top:1px;border-bottom:1px;border-right:1px;border-style:solid;text-align:center;">
										REMARKS
									</td>
								</tr>
								<?php echo $trDocPersonal; ?>
							</table>
						</td>
					</tr>
					<tr>
						<td style="width:760px;" colspan="2">
							<table style="width:100%;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:11px;font-weight:bold;font-family:serif;" colspan="5">
										For Office Use Only
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;text-align:center;height:20px;">
										1.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;border-top:1px;border-style:solid;">
										Standard of Conversational English
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;border-top:1px;border-style:solid;text-align:center;">
										
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-top:1px;border-style:solid;text-align:center;">
										Please Indicate
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-top:1px;border-right:1px;border-style:solid;text-align:center;">
										Good / Fair / Poor
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										2.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Standard of written English
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Indicate
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										Good / Fair / Poor
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										3.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Standard of reading in English
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Indicate
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										Good / Fair / Poor
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										4.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Knowledge of ISM
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Indicate
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										Good / Fair / Poor
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										5.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Have you been convicted or jailed before ?
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Yes/No
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Specify
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										_______________
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										6.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Have your employment ever been terminated before ?
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Yes/No
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Specify
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										_______________
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										7.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Have you been denied entry to Singapore before ?
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Yes/No
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Specify
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										_______________
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										1.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Any Physical Disability
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Yes/No
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Specify
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										_______________
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										2.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Any Major illness/ Accident
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Yes/No
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Specify
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										_______________
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										3.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Date of last Medical check-up
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Yes/No
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Specify
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										_______________
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;text-align:center;height:20px;">
										4.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;">
										Do you drink ?
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Yes/No
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;">
										Please Specify
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;text-align:center;">
										_______________
									</td>
								</tr>
								<tr>
									<td style="width:10px;font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-bottom:1px;border-style:solid;text-align:center;height:20px;">
										5.
									</td>
									<td style="width:280px;font-size:11px;vertical-align:top;font-family:serif;border-bottom:1px;border-style:solid;">
										Do you smoke ?
									</td>
									<td style="width:80px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;border-bottom:1px;border-style:solid;">
										Yes/No
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;text-align:center;border-bottom:1px;border-style:solid;">
										Please Specify
									</td>
									<td style="width:120px;font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-bottom:1px;border-style:solid;text-align:center;">
										_______________
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style="width:760px;" colspan="2">
							<table style="width:100%;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td colspan="2" style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-style:solid;">Interviewed by :</td>
									<td colspan="2" style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-top:1px;border-right:1px;border-style:solid;">2nd Interview by :</td>
								</tr>
								<tr>
									<td style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;">
										Designation
									</td>
									<td style="font-size:11px;vertical-align:top;font-family:serif;">
										Date :
									</td>
									<td style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;">
										Designation
									</td>
									<td style="font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;">
										Date :
									</td>
								</tr>
								<tr>
									<td colspan="2" style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;height:50px;">Comments :</td>
									<td colspan="2" style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-right:1px;border-style:solid;">Comments :</td>
								</tr>
								<tr>
									<td style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;">
										Approved by
									</td>
									<td style="font-size:11px;vertical-align:top;font-family:serif;">
										Designation
									</td>
									<td colspan="2" style="padding-left: 20px; font-size:11px;vertical-align:top;font-family:serif;border-right:1px;border-style:solid;">
										Date :
									</td>
								</tr>
								<tr>
									<td colspan="2" style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-style:solid;">Vessel to join :</td>
									<td colspan="2" style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-left:1px;border-right:1px;border-style:solid;">Boilersuite size :</td>
								</tr>
								<tr>
									<td colspan="2" style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-bottom:1px; border-style:solid;">Working Shift Arrangement: 2/2 4/2 6/2</td>
									<td colspan="2" style="font-size:11px;vertical-align:top;font-family:serif;border-left:1px;border-bottom:1px;border-left:1px;border-right:1px;border-style:solid;">Safety shoe size :</td>
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