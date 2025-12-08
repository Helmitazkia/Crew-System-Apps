<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Crew Evaluation Report</title>
    <style>
    body {
        font-family: Arial, 'Calibri', sans-serif;
        font-size: 12px;
        margin: 20px;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .main-table {
        border: 1px solid black;
        width: 100%;
        margin-bottom: 15px;
        border-collapse: collapse;
    }

    .main-table td,
    .main-table th {
        padding: 8px;
        vertical-align: top;
    }

    .checkmark {
        font-size: 16px;
        margin-right: 8px;
        width: 18px;
        display: inline-block;
        vertical-align: middle;
    }

    .criteria-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .criteria-table td,
    .criteria-table th {
        border: 1px solid black;
        padding: 5px;
        text-align: center;
        width: 10%;
    }

    .criteria-table td:first-child {
        width: 100%;
        text-align: left;
    }

    .signature-box {
        border: 1px solid black;
        padding: 10px;
        margin: 10px 0;
    }

    .footer {
        margin-top: 20px;
        font-size: 10px;
    }
    </style>
</head>

<body>
    <div class="header">
        <div style="font-size: 18px; font-weight: bold; text-align: left;">
            <img src="<?php echo base_url(); ?>assets/img/logo_ady.png" style="width: 50%; display: block;">
        </div>

        <div style="font-size: 16px; font-weight: bold; margin-top: 5px; border: 1px solid;">CREW EVALUATION REPORT
        </div>
    </div>
    <div class="header">
        <table class="main-table">
            <tr>
                <td width="50%">Vessel: <?php echo (!empty($vessel)) ? $vessel : '______'; ?></td>
                <td width="50%">Date of Report (dd/mm/yy):
                    <?php echo (!empty($dateOfReport)) ? $dateOfReport : '__ / __ / __'; ?></td>
            </tr>
            <tr>
                <td>Seafarer's Name : <?php echo (!empty($seafarerName)) ? $seafarerName : '______'; ?></td>
                <td>Reporting Period From:
                    <?php echo (!empty($reportPeriodFrom)) ? $reportPeriodFrom : '__ / __ / __'; ?>
                </td>
            </tr>
            <tr>
                <td>Rank : <?php echo (!empty($rank)) ? $rank : '______'; ?></td>
                <td>Reporting Period To: <?php echo (!empty($reportPeriodTo)) ? $reportPeriodTo : '__ / __ / __'; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2"><strong>Reason for the Report:</strong></td>
            </tr>
            <tr>
                <td class="reason-column">
                    <div class="reason-item">
                        [<?php echo $reasonMidway; ?>] Midway through contract
                    </div>
                    <div class="reason-item">
                        [<?php echo $reasonLeaving; ?>] Reporting crew leaving vessel
                    </div>
                </td>
                <td class="reason-column">
                    <div class="reason-item">
                        [<?php echo $reasonSigningOff; ?>] Seafarer signing off vessel
                    </div>
                    <div class="reason-item">
                        [<?php echo $reasonSpecial; ?>] Special request
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="criteria-table">
        <tr>
            <th>Criteria</th>
            <th>Excellent (4)</th>
            <th>Good (3)</th>
            <th>Fair (2)</th>
            <th>Poor (1)</th>
            <th>Identify Training Needs</th>
        </tr>
        <?php echo $criteriaTable; ?>
    </table>

    <div class="signature-box">
        <div class="col-md-6">
            <div style="margin-bottom: 5px;">
                <label><strong>General Comments highlighting strengths/weaknesses:</strong></label>
            </div>
            <div style="margin-bottom: 5px;">
                Master: <?php echo $masterComments; ?>
            </div>
            <div style="margin-bottom: 5px;">
                Reporting Officer: <?php echo $reportingOfficerComments; ?>
            </div>
        </div>
        <div class=" col-md-6">
            <label>Re Employ:</label>
            [<span class="checkmark"
                style="margin: 20px;"><?php echo ($reEmploy === 'Y') ? '&#10004;' : '&nbsp;&nbsp;'; ?></span>] Yes
            [<span class="checkmark"
                style="margin: 20px;"><?php echo ($reEmploy === 'N') ? '&#10004;' : '&nbsp;&nbsp;'; ?></span>]
            No<br>
            <label>Promote:</label>
            [<span class="checkmark"><?php echo ($promote === 'Y') ? '&#10004;' : '&nbsp;&nbsp;'; ?></span>] Yes
            [<span class="checkmark"><?php echo ($promote === 'N') ? '&#10004;' : '&nbsp;&nbsp;'; ?></span>] No
            [<span class="checkmark"><?php echo ($promote === 'C') ? '&#10004;' : '&nbsp;&nbsp;'; ?></span>] Yes,
            Provided
            conditions are met
        </div>
    </div>

    <div class="signature-box">
        <strong>Acknowledge</strong><br>
        Seafarer's signature: ___________________________
    </div>

    <table class="main-table">
        <tr>
            <td>
                Reporting Officer<br>
                Full Name: <?php echo $reportingOfficerName; ?><br>
                Rank: <?php echo $reportingOfficerRank; ?>
            </td>
            <td>
                Master / COO<br>
                Full Name: <?php echo $mastercoofullname; ?>
            </td>
            <td>
                Received by CM: <?php echo $receivedByCM; ?><br>
                Date of Receipt: <?php echo $dateOfReceipt; ?>
            </td>
        </tr>
    </table>

    <div style="display: flex; width: 100%;">
        <div style="text-align: left;">Form OPS-015/
            Rev.03/ 27-07-2018
        </div>
        <div style="text-align: right; margin-top: -20px;">Distribution: Original - Office / Copy - Ship File</div>
    </div>
</body>

</html>