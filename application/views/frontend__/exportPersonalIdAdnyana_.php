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
						<td colspan="4" align="center">
							<span style="font-size:14px;font-weight:bold;font-family:'Arial Black';">PRELIMINARY APPLICATION FOR EMPLOYMENT</span>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="width:760px;">
							<table style="width:760px;margin-top:10px;" cellpadding="2" cellspacing="0">
								<tr>
									<td style="font-size:11px;font-weight:bold;font-family:serif;border:1px solid black;height:20px;" align="center" colspan="3"><u>PERSONAL DATA</u></td>
									<td style="width:95px;font-size:10px;border:1px solid black;vertical-align:top;" rowspan="7" align="center">
										<?php echo $photo; ?></td>
								</tr>
								<tr>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;">
										<b>FIRST NAME :</b><br>
										<span style="padding-left: 15px;"><?php echo $fname; ?></span>
									</td>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;">
										<b>MIDDLE NAME :</b><br>
										<?php echo $mname; ?>
									</td>
									<td style="width:270px;font-size:10px;border:1px solid black;vertical-align:top;">
										<b>LAST NAME / SURNAME :</b><br>
										<?php echo $lname; ?>
									</td>
								</tr>
								<tr>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;">
										<b>NATIONALITY :</b><br>
										<label><?php echo $negara; ?></label>
									</td>
									<td style="width:250px;font-size:10px;border:1px solid black;vertical-align:top;">
										<b>DATE OF BIRTH :</b><br>
										<?php echo $dob; ?>
									</td>
									<td style="width:270px;font-size:10px;border:1px solid black;vertical-align:top;">
										<b>PLACE OF BIRTH :</b><br>
										<?php echo $pob; ?>
									</td>
								</tr>
								<tr>
									<td colspan="3" style="padding:0px;border:1px solid black;">
										<table style="width:100%;" cellpadding="1" cellspacing="0" border="0">
											<tr>
												<td style="font-size:12px;" colspan="2">
													Post Applied For : <?php echo $applyFor; ?>
												</td>
											</tr>
											<tr>
												<td style="font-size:12px;width:50%;">
													Willing to accept lower rank : <?php echo $lowerRank; ?>
												</td>
												<td style="font-size:12px;width:50%;">
													Available from : <?php echo $availDate; ?>
												</td>
											</tr>
										</table>
									</td>
								</tr>								
							</table>
						</td>
					</tr>
				</tbody>
			</table>
			<table style="width:760px;margin-top:5px;" cellpadding="2" cellspacing="0">
				<tr>
					<td style="width:270px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Contact Address</td>
					<td style="width:180px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Documents</td>
					<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Number</td>
					<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Issued on</td>
					<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Valid until</td>
				</tr>
				<tr>
					<td style="width:270px;font-size:10px;border-bottom:1px;border-left:1px;border-right:1px;border-style:solid;height:80px;vertical-align:top;"><?php echo $address; ?></td>
					<td style="width:180px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:top;"><?php echo $docNya; ?></td>
					<td style="width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:top;text-align:center;"><?php echo $numberNya; ?></td>
					<td style="width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:top;text-align:center;"><?php echo $issDate; ?></td>
					<td style="width:100px;font-size:10px;border-bottom:1px;border-right:1px;border-style:solid;vertical-align:top;text-align:center;"><?php echo $validDate; ?></td>
				</tr>
			</table>
			<table style="width:760px;margin-top:5px;" cellpadding="2" cellspacing="0">
				<tr>
					<td style="font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;" colspan="4">Highest Competency Certificate Held</td>
					<td style="font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;" colspan="3">Dangerous Cargo Endorsement (**)</td>
				</tr>
				<tr>
					<td style="width:170px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Issuing Authority</td>
					<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Number</td>
					<td style="width:200px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Grade (*)</td>
					<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Valid until</td>
					<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Petroleum</td>
					<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Chemical</td>
					<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">Gas</td>
				</tr>
				<tr>
					<td style="width:170px;font-size:10px;border:1px solid black;vertical-align:top;text-align:left;height:80px;"><?php echo $hcchIssAutho; ?></td>
					<td style="width:100px;font-size:10px;border:1px solid black;vertical-align:top;text-align:center;"><?php echo $hcchNo; ?></td>
					<td style="width:200px;font-size:10px;border:1px solid black;vertical-align:top;text-align:center;"><?php echo $HcchGrade; ?></td>
					<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:top;text-align:center;"><?php echo $hcchValid; ?></td>
					<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:top;text-align:center;"><?php echo $hcchPetroleum; ?></td>
					<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:top;text-align:center;"><?php echo $hcchChemical; ?></td>
					<td style="width:80px;font-size:10px;border:1px solid black;vertical-align:top;text-align:center;"><?php echo $hcchGas; ?></td>
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