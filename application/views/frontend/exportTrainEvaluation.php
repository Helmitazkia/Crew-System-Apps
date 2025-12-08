<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Training Evaluation</title>
    <style>
    body {
        font-family: 'Calibri';
        font-size: 11px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid black;
        padding: 4px;
    }

    th {
        text-align: center;
        background-color: #f2f2f2;
    }

    .no-border {
        border: none;
    }

    .signature {
        text-align: center;
        margin-top: 30px;
        font-size: 10px;
    }

    .signature-line {
        display: block;
        margin-top: 20px;
        text-align: center;
    }

    .note {
        font-size: 10px;
    }
    </style>
</head>

<body>
    <table class="no-border">
        <tr>
            <td width="10%" class="no-border">
                <img src="<?php echo base_url(); ?>/assets/img/andhika.gif" width="70">
            </td>
            <td class="no-border" style="font-size: 20px;">
                <strong>PT. ANDHINI EKAKARYA SEJAHTERA</strong><br>
            </td>
        </tr>
    </table>

    <h3 style="text-align: center; text-decoration: underline;">TRAINING EVALUATION</h3>

    <table>
        <tr>
            <td width="30%"><strong>Employee Name</strong></td>
            <td><?php echo $employeeName; ?></td>
        </tr>
        <tr>
            <td><strong>Designation</strong></td>
            <td><?php echo $designation; ?></td>
        </tr>
        <tr>
            <td><strong>Date of Training</strong></td>
            <td><?php echo $dateOfTraining; ?></td>
        </tr>
        <tr>
            <td><strong>Place of Training</strong></td>
            <td><?php echo $placeOfTraining; ?></td>
        </tr>
        <tr>
            <td><strong>Subject</strong></td>
            <td><?php echo $subject; ?></td>
        </tr>
        <tr>
            <td><strong>Date of Evaluation</strong></td>
            <td><?php echo $dateOfEvaluation; ?></td>
        </tr>
        <tr>
            <td><strong>Evaluator Name & Designation</strong></td>
            <td><?php echo $evaluatorNameDesignation; ?></td>
        </tr>
    </table>

    <p style=" font-size: 12px; text-align: center;">
        <strong><u>Point Scale</u></strong>: (1) Excellent, (2) Good, (3) Satisfactory, (4) Unsatisfactory
    </p>

    <table border="1" cellspacing="0" cellpadding="5" width="100%">
        <tr>
            <th width="5%">No</th>
            <th>ITEMS</th>
            <th width="5%">1</th>
            <th width="5%">2</th>
            <th width="5%">3</th>
            <th width="5%">4</th>
        </tr>
        <?php echo $evalTable; ?>
    </table>


    <p class="note">
        <strong><u>Notes:</u></strong> This form is filled by employee supervisor or head of department /
        division
    </p>

    <div class="signature">
        <strong>SIGNATURE,</strong>
        <div class="signature-line">....................................................</div>
    </div>

    <p class="note" style="text-align: right;">CD-35/21/10/20</p>
</body>

</html>