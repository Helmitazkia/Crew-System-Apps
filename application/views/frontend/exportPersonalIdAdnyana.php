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
				<tr>
					<td colspan="4" style="font-size:10px;vertical-align:top;text-align:left;">
						(*) Specify whether:
						<table style="width: 100%;">
							<tr>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									Dk Class
								</td>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									1 = Master FG<br>
									2 = 1st Mate FG<br>
									3 =	2nd Mate FG<br>
									4 =	NWKO
								</td>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									Eng Class
								</td>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									1 =	1st Class (M),(S),(M+S)<br>
									1 =	2nd Class (M),(S),(M+S)<br>
									3 & 4 = Class 4 (M)
								</td>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									ER/O Class 1<br>
									R/O Class 2<br>
									R/O R/T only
								</td>
							</tr>
						</table>
					</td>
					<td colspan="3" style="font-size:10px;vertical-align:top;text-align:left;">
						(**) Specify whether:
						<table style="width: 100%;">
							<tr>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									Incharge:	Highest Grade<br>
									Assistant:	Watch-keeper at<br>
									Assistant:	cargo watch
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table style="width:760px;margin-top:5px;" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="6" style="font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">
						STCW & Specialised Courses Attended Including Special Qualifications (***)
					</td>
				</tr>
				<tr>
					<td style="font-size:10px;vertical-align:top;border-left:1px;border-style:solid;">
						<input type="checkbox" <?php echo $perSurv; ?> >Personal Survival
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $basFireFig; ?>>Basic Fire Fighting
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $basMdcl; ?>>Basic Medical Emergency
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $humanRel; ?>>Human Relationship
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $profInSurv; ?>>Prof in Surv. Craft
					</td>
					<td style="font-size:10px;vertical-align:top;border-right:1px;border-style:solid;">
						<input type="checkbox" <?php echo $advFight; ?>>Adv.F.Fighting
					</td>
				</tr>
				<tr>
					<td style="font-size:10px;vertical-align:top;border-left:1px;border-style:solid;">
						<input type="checkbox" <?php echo $mdclFirstAid; ?>>Medical First Aid
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $mdclCare; ?>>Medical Care
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $arpa; ?>>ARPA
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $radarSim; ?>>Radar Simulator
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $gmdss; ?>>GMDSS
					</td>
					<td style="font-size:10px;vertical-align:top;border-right:1px;border-style:solid;">
						<input type="checkbox" <?php echo $bridgeRes; ?>>Bridge Resource Management
					</td>
				</tr>
				<tr>
					<td style="font-size:10px;vertical-align:top;border-left:1px;border-style:solid;">
						<input type="checkbox" <?php echo $tkrFam; ?>>Tkr Familiarisation
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $tkrSafetyPet; ?>>Tkr Safety (Pet)
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $tkrSafetyChm; ?>>Tkr Safety (Chm)
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $tkrSafetyLpg; ?>>Tkr Safety (LPG)
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $inertGas; ?>>Inert Gas System
					</td>
					<td style="font-size:10px;vertical-align:top;border-right:1px;border-style:solid;">
						<input type="checkbox" <?php echo $crudeOil; ?>>Crude Oil Washing
					</td>
				</tr>
				<tr>
					<td style="font-size:10px;vertical-align:top;border-left:1px;border-style:solid;">
						<input type="checkbox" <?php echo $shipHandling; ?>>Ship Handling Simulation
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $energyCons; ?>>Energy Conservation Training
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $shipSecurity; ?>>Ship Security Officer
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $sdsd; ?>>SDSD
					</td>
					<td style="font-size:10px;vertical-align:top;">
						<input type="checkbox" <?php echo $securityAwer; ?>>Security Awareness Training
					</td>
					<td style="font-size:10px;vertical-align:top;border-right:1px;border-style:solid;">
						<input type="checkbox" <?php echo $gocOru; ?>>GOC / ORU
					</td>
				</tr>
				<tr>
					<td style="font-size:10px;vertical-align:top;border-left:1px;border-bottom:1px;border-style:solid;">
						<input type="checkbox" <?php echo $ecDis; ?>>ECDIS
					</td>
					<td style="font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;">
						<input type="checkbox" <?php echo $engineRes; ?>>Engine Resource Management
					</td>
					<td style="font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;">
						<input type="checkbox" <?php echo $shipHandlingAndmanuver; ?>>Ship Handling and Manuvering
					</td>
					<td style="font-size:10px;vertical-align:top;border-bottom:1px;border-style:solid;">
						<input type="checkbox" <?php echo $shipSafetyOffice; ?>>Ship Safety Officer
					</td>
					<td colspan="2" style="font-size:10px;vertical-align:top;border-right:1px;border-bottom:1px;border-style:solid;">
						
					</td>
				</tr>
				<tr>
					<td colspan="6" style="font-size:10px;vertical-align:top;">
						(***) Tick ( <input type="checkbox"> ) for course done and still valid
					</td>
				</tr>
			</table>
			<table style="width:760px;margin-top:5px;" cellpadding="2" cellspacing="0">
				<tr>
					<td colspan="11" style="font-size:10px;border:1px solid black;vertical-align:middle;font-weight:bold;text-align:center;">
						Sea Experience
					</td>
				</tr>
				<tr>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						Company/ Manager
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						Vessel
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						Flag
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						Type<br>(****)
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						GRT
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						DWT
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						M/Engine
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						BHP
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						Rank
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						From
					</td>
					<td style="font-size:10px;vertical-align:middle;text-align:center;border:1px solid;">
						To
					</td>
				</tr>
				<?php echo $trSeaService; ?>
				<tr>
					<td colspan="11">
						<table style="width:100%;">
							<tr>
								<td colspan="5" style="font-size:10px;vertical-align:top;text-align:left;">
									(****) Use only following abbreviations for vessel type:
								</td>
							</tr>
							<tr>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									GCO - General Cargo
								</td>
								<td style="font-size:10px;vertical-align:top;">
									B/C - Bulk Carrier
								</td>
								<td style="font-size:10px;vertical-align:top;">
									CON - Cellular Container
								</td>
								<td style="font-size:10px;vertical-align:top;">
									NC - Tanker Crude
								</td>
								<td style="font-size:10px;vertical-align:top;">
									TNP - Tanker Product
								</td>
							</tr>
							<tr>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									TNS - Tanker Storage
								</td>
								<td style="font-size:10px;vertical-align:top;">
									INV - VLCC/ULCC
								</td>
								<td style="font-size:10px;vertical-align:top;">
									GAS - LPG/LNG Carrier
								</td>
								<td style="font-size:10px;vertical-align:top;">
									CHM - Chem Carrier
								</td>
								<td style="font-size:10px;vertical-align:top;">
									PAS - Passenger hips
								</td>
							</tr>
							<tr>
								<td style="font-size:10px;vertical-align:top;text-align:left;">
									R/O - Ro/Ro Carriers
								</td>
								<td style="font-size:10px;vertical-align:top;">
									DRG - Dredgers
								</td>
								<td style="font-size:10px;vertical-align:top;">
									SRV - Survey Vessels
								</td>
								<td style="font-size:10px;vertical-align:top;">
									LOG - Log/Timber
								</td>
								<td style="font-size:10px;vertical-align:top;">
									OSV - Offshore supply vessel
								</td>
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