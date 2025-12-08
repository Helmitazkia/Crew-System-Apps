<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Report Crew Evaluation</title>
</head>

<body
    style="width: 210mm; min-height: 297mm; margin: 5mm auto; padding: 5mm; background: #fff; font-family: Calibri, Candara, Segoe, 'Segoe UI', Optima, Arial, sans-serif; font-size: 10pt; color: #000; box-sizing: border-box;">
    <div style="text-align: left; margin-bottom: 5px;">
        <img src="./assets/img/andhika.gif" alt="logo" style="width:40px; vertical-align:middle;">
        <span style="font-size:24px; color:#000080; vertical-align:middle;">ANDHIKA GROUP</span>
    </div>
    <div style="background-color:#067780; padding:6px;">
        <span style="color:#fff; font-size:15px; font-weight:bold; align-items:center;">Report Crew
            Evaluation</span>
        <?php echo $label_reject; ?>
    </div>
    <div style="background:#fff; margin-bottom: 6px;">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="border:1px solid black; padding:6px;" width="50%">Vessel: <?php echo $vessel; ?></td>
                <td style="border:1px solid black; padding:6px;" width="50%">Date of Report (dd-mm-yyyy):
                    <?php echo $dateOfReport; ?></td>
            </tr>
            <tr>
                <td style="border:1px solid black; padding:6px;">Seafarer's Name: <?php echo $seafarerName; ?></td>
                <td style="border:1px solid black; padding:6px;">Reporting Period From:
                    <?php echo $reportPeriodFrom; ?>
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black; padding:6px;">Rank: <?php echo $rank; ?></td>
                <td style="border:1px solid black; padding:6px;">Reporting Period To: <?php echo $reportPeriodTo; ?>
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black; padding:6px;" colspan="2"><strong>Reason for the Report:</strong>
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black; padding:6px;">
                    <div><span
                            style="display:inline-block; width:15px; height:15px; border:1px solid black; text-align:center; line-height:15px; margin-right:5px;"><?php echo $reasonMidway; ?></span>
                        Midway through contract</div>
                    <div><span
                            style="display:inline-block; width:15px; height:15px; border:1px solid black; text-align:center; line-height:15px; margin-right:5px;"><?php echo $reasonLeaving; ?></span>
                        Reporting crew leaving vessel</div>
                </td>
                <td style="border:1px solid black; padding:6px;">
                    <div><span
                            style="display:inline-block; width:15px; height:15px; border:1px solid black; text-align:center; line-height:15px; margin-right:5px;"><?php echo $reasonSigningOff; ?></span>
                        Seafarer signing off vessel</div>
                    <div><span
                            style="display:inline-block; width:15px; height:15px; border:1px solid black; text-align:center; line-height:15px; margin-right:5px;"><?php echo $reasonSpecial; ?></span>
                        Special request</div>
                </td>
            </tr>
        </table>
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <th style="border:1px solid black; padding:5px;">Criteria</th>
                <th style="border:1px solid black; padding:5px;">Excellent (4)</th>
                <th style="border:1px solid black; padding:5px;">Good (3)</th>
                <th style="border:1px solid black; padding:5px;">Fair (2)</th>
                <th style="border:1px solid black; padding:5px;">Poor (1)</th>
                <th style="border:1px solid black; padding:5px;">Identify Training Needs</th>
                <th style="border:1px solid black; padding:5px;">TS Comments</th>
            </tr>
            <?php echo $criteriaTable; ?>
        </table>
        <div style="border:1px solid black; padding:8px;">
            <strong>General Comments highlighting strengths/weaknesses:</strong><br>
            Master: <?php echo $masterComments; ?><br>
            Reporting Officer: <?php echo $reportingOfficerComments; ?><br>
            Reason Rejected: <?php echo $remark_reject; ?><br>

            <strong>Re Employ:</strong><br>
            <span
                style="display:inline-block; width:12px; height:12px; border:1px solid black; text-align:center; line-height:12px; margin-right:5px;"><?php echo ($reEmploy === 'Y') ? '&#10004;' : '&nbsp;'; ?></span>
            Yes
            <span
                style="display:inline-block; width:12px; height:12px; border:1px solid black; text-align:center; line-height:12px; margin-left:10px; margin-right:5px;"><?php echo ($reEmploy === 'N') ? '&#10004;' : '&nbsp;'; ?></span>
            No<br>

            <strong>Promote:</strong><br>
            <span
                style="display:inline-block; width:12px; height:12px; border:1px solid black; text-align:center; line-height:12px; margin-right:5px;"><?php echo ($promote === 'Y') ? '&#10004;' : '&nbsp;'; ?></span>
            Yes
            <span
                style="display:inline-block; width:12px; height:12px; border:1px solid black; text-align:center; line-height:12px; margin-left:10px; margin-right:5px;"><?php echo ($promote === 'N') ? '&#10004;' : '&nbsp;'; ?></span>
            No
            <span
                style="display:inline-block; width:12px; height:12px; border:1px solid black; text-align:center; line-height:12px; margin-left:10px; margin-right:5px;"><?php echo ($promote === 'C') ? '&#10004;' : '&nbsp;'; ?></span>
            Yes, Provided conditions are met
        </div>

        <table style="width:100%; border-collapse:collapse; font-size:10px;">
            <tr>
                <td style="border:1px solid black; padding:6px; vertical-align:top;">
                    Full Name: <?php echo $reportingOfficerName; ?><br>
                    <strong>Rank: <?php echo $reportingOfficerRank; ?><br></strong>
                    Signature:<br>
                    <img src="<?php echo $qrCodeImg; ?>" alt="QR Code" style="width:65px; height:auto; margin-top:6px;">
                </td>
                <td style="border:1px solid black; padding:6px; vertical-align:top;">
                    <strong>Master / COO</strong><br>
                    Full Name: <?php echo $mastercoofullname; ?><br>
                    Signature:<br>
                    <img src="<?php echo $qrCodePathMaster; ?>" alt="QR Code"
                        style="width:65px; height:auto; margin-top:6px;">
                </td>
            </tr>
            <tr>
                <td style="border:1px solid black; padding:6px; vertical-align:top;">
                    <strong>Technical Superintendent</strong><br>
                    Full Name: <?php echo $technicalSuperintendentFullName; ?><br>
                    Signature:<br>
                    <img src="<?php echo $qrCodePathSuperintendent; ?>" alt="QR Code"
                        style="width:65px; height:auto; margin-top:6px;">
                </td>
                <td style="border:1px solid black; padding:6px; vertical-align:top;">
                    <strong>Received by CM</strong><br>
                    <?php echo $receivedByCM; ?><br>
                    Date of Receipt: <?php echo $dateOfReceipt; ?><br>
                    Signature:<br>
                    <img src="<?php echo $qrCodePathCM; ?>" alt="QR Code"
                        style="width:65px; height:auto; margin-top:6px;">
                </td>
            </tr>
        </table>


        <table style="width:100%; font-size:10px; margin-top:5px;">
            <tr>
                <td style="text-align:left;">Form OPS-015/ Rev.03/ 27-07-2018</td>
                <td style="text-align:right;">Distribution: Original - Office / Copy - Ship File</td>
            </tr>
        </table>




    </div>

</body>

</html>