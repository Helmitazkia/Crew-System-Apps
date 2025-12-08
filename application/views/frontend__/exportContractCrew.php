<?php
ini_set('memory_limit', '-1');
$nama_dokumen = "dataContractCrew";
require("pdf/mpdf60/mpdf.php");
$mpdf = new mPDF('utf-8', 'A4-L');
ob_start(); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
	<title>Data Contract Crew</title>
</head>
<body>
	<div style="width:1085px;">
		<div class="reportPDF" style="width:100%;min-height:0px;">
			<table style="width:100%;margin-top:5px;" cellpadding="0" cellspacing="0">
				<thead>
					<tr>
						<td colspan="11" style="text-align:center;font-size:24px;"><u><b>CREW LIST</b></u></td>
					</tr>
					<tr>
						<td colspan="6"><b><u>STATUS</u> : </b><span><?php echo $statusNya; ?></span></td>
						<td colspan="5" style="text-align:right;padding-right:10px;height:30px;"><?php echo $dateNow; ?></td>
					</tr>
					<tr>
						<td style="width:40px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="3">NO</td>
						<td style="width:120px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="3">RANK</td>
						<td style="width:190px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="3">NAME</td>
						<td style="width:90px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="3">DOB</td>
						<td style="width:50px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="3">AGE</td>
						<td style="vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;height:20px;" colspan="2">VESSEL</td>
						<td style="vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" colspan="3">DATE</td>
						<td style="width:200px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="3">REMARK</td>
					</tr>
					<tr>
						<td style="width:170px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="2">SIGN ON</td>
						<td style="width:130px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="2">LAST</td>
						<td style="width:90px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;" rowspan="2">SIGN ON</td>
						<td style="vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;height:20px;" colspan="2">SIGN OFF</td>
					</tr>
					<tr>
						<td style="width:90px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;height:20px;">ACTUAL</td>
						<td style="width:90px;vertical-align:middle;text-align:center;font-weight:bold;font-size:12px;border:1px solid black;background-color:#067780;color:#FFFFFF;height:20px;">SCHEDULE</td>
					</tr>
				</thead>
					<?php echo $trNya; ?>
				<tbody>
					
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