<?php
ini_set('memory_limit', '-1');
$nama_dokumen = "expiredCertificates";
require("pdf/mpdf60/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4-P');
ob_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
	<title>Expired Certificates Report</title>
</head>
<body>
	<div style="width:1085px;">
		<div class="reportPDF" style="width:100%;min-height:0px;">
			<table style="width:100%;margin-top:5px;" cellpadding="0" cellspacing="0" border="0">
				<thead>
					<tr>
						<td colspan="7" style="text-align:center;font-size:14px;font-family:serif;">
							<u><b>EXPIRED CERTIFICATES REPORT</b></u>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="font-size:10px;font-family:serif;height:20px;">
							<i><span><?php echo $typeCertJudul; ?></span><b> :: </b><span><?php echo $judulType; ?></span></i>
						</td>
						<td colspan="3" style="font-size:10px;text-align:right;padding-right:10px;">
							<?php echo $dateNow; ?>		
						</td>
					</tr>
					<tr>
						<td style="width:20px;vertical-align:middle;text-align:center;font-weight:bold;font-size:10px;border:0.5px solid black;background-color:#067780;color:#FFFFFF;height:25px;font-family:serif;">NO</td>
						<td style="width:120px;vertical-align:middle;text-align:center;font-weight:bold;font-size:10px;border:0.5px solid black;background-color:#067780;color:#FFFFFF;font-family:serif;">CREW NAME</td>
						<td style="width:190px;vertical-align:middle;text-align:center;font-weight:bold;font-size:10px;border:0.5px solid black;background-color:#067780;color:#FFFFFF;font-family:serif;">CERTIFICATE NAME</td>
						<td style="width:100px;vertical-align:middle;text-align:center;font-weight:bold;font-size:10px;border:0.5px solid black;background-color:#067780;color:#FFFFFF;font-family:serif;">COUNTRY / PLACE</td>
						<td style="width:100px;vertical-align:middle;text-align:center;font-weight:bold;font-size:10px;border:0.5px solid black;background-color:#067780;color:#FFFFFF;font-family:serif;">NO DOCUMENT</td>
						<td style="width:100px;vertical-align:middle;text-align:center;font-weight:bold;font-size:10px;border:0.5px solid black;background-color:#067780;color:#FFFFFF;font-family:serif;">DATE OF ISSUE</td>
						<td style="width:100px;vertical-align:middle;text-align:center;font-weight:bold;font-size:10px;border:0.5px solid black;background-color:#067780;color:#FFFFFF;font-family:serif;">DATE OF EXPIRY</td>
					</tr>
				</thead>					
				<tbody>
					<?php echo $trNya; ?>
					<tr>
						<td colspan="7" style="font-size:9px;padding-top:5px;">Additional Information</td>
					</tr>
					<tr><td colspan="7" style="font-size:9px;"> - <?php echo $teksInfoCert; ?></td></tr>
					<tr><td colspan="7" style="font-size:9px;"> - (*) Will Expire</td></tr>
					<tr><td colspan="7" style="font-size:9px;"> - (**) Over Due</td></tr>
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