<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Crewing System">
    <meta name="author" content="andhika group">
    <title>Crew Evaluation Report</title>
    <link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>image/AndhikaTransparentBkGndBlue.png" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icon-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/hover-min.css">
    <!-- <link rel="stylesheet" href="assets/css/magnific-popup.css"> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owl.carousel.min.css">
    <!-- <link rel="stylesheet" href="assets/css/owl.theme.default.min.css"/> -->
    <!-- <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <!-- <link rel="stylesheet" href="assets/css/bootsnav.css"/> -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
    <script>
    function approveMaster() {
        var txtIdReport = $('#txtIdReport').val();
        var txtMasterComments = $('#txtMasterComments').val();

        $.ajax({
            url: '<?php echo base_url("extendCrewEvaluation/approveMaster"); ?>',
            type: 'POST',
            data: {
                txtIdReport: txtIdReport,
                txtMasterComments: txtMasterComments
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
                console.error(xhr.responseText);
            }
        });
    }

    function approveTechnicalSuperintendent() {
        var txtIdReport = $('#txtIdReport').val();
        if (!txtIdReport) {
            alert("ID report tidak ditemukan!");
            return;
        }

        $("#idLoading").show();
        $.ajax({
            url: '<?php echo base_url("extendCrewEvaluation/approveTechnicalSuperintendent"); ?>',
            type: 'POST',
            data: {
                txtIdReport: txtIdReport
            },
            dataType: 'json',
            success: function(response) {
                $("#idLoading").hide();
                if (response.status === 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                $("#idLoading").hide();
                alert('AJAX error: ' + xhr.responseText);
            }
        });
    }

    function saveTSCommentsAndApprove() {
        var txtIdReport = $('#txtIdReport').val();
        var comments = [];

        $('.ts-comment').each(function() {
            comments.push({
                criteria_id: $(this).data('criteria-id'),
                comment: $(this).val()
            });
        });

        $("#idLoadingSpinner").show();

        $.ajax({
            url: '<?php echo base_url("extendCrewEvaluation/saveTSComments"); ?>',
            type: 'POST',
            data: {
                txtIdReport: txtIdReport,
                comments: comments
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    approveTechnicalSuperintendent();
                } else {
                    $("#idLoadingSpinner").hide();
                    alert("Failed: " + response.message);
                }
            },
            error: function(xhr) {
                $("#idLoadingSpinner").hide();
                alert("AJAX error: " + xhr.responseText);
            }
        });
    }


    function approveCM() {
        var txtIdReport = $('#txtIdReport').val();
        var txtDateReceipt = $('#txtDateReceipt').val();
        $.ajax({
            url: '<?php echo base_url("extendCrewEvaluation/approveCM"); ?>',
            type: 'POST',
            data: {
                txtIdReport: txtIdReport,
                txtDateReceipt: txtDateReceipt
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    }

    function exportPDF() {
        var idReport = document.getElementById('txtIdReport').value;
        var printUrl = "<?php echo base_url('extendCrewEvaluation/exportPDFCrewEvaluation'); ?>/" + idReport;
        var printWindow = window.open(printUrl, '_blank');
        if (printWindow) {
            printWindow.focus();
        } else {
            alert('Mohon izinkan pop-up untuk situs ini.');
        }
    }

    function rejectCM() {
        const txtIdReport = document.getElementById("txtIdReport").value;
        const reasonReject = document.getElementById("reasonReject").value;

        if (reasonReject === "") {
            alert("Alasan penolakan harus diisi!");
            return;
        }

        $.ajax({
            url: '<?php echo base_url("extendCrewEvaluation/rejectCrewEvaluation") ?>',
            method: 'POST',
            data: {
                txtIdReport: txtIdReport,
                txtReasonReject: reasonReject
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert("Submit Reject Successfully!.");
                    $('#evaluasiModal').modal('hide');
                    $("#btnApproveCM").hide();
                    location.reload();
                } else {
                    alert("Gagal menolak: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("Response Error:", xhr.responseText);
                alert("Terjadi kesalahan saat menolak laporan: " + error);
            }
        });
    }
    </script>
</head>

<body style="background-color: #d1e9ef; font-family: Calibri, Candara, Segoe, 
    Segoe UI,Optima, Arial, sans-serif;">
    <div id="idLoadingSpinner" style="
                    display:none;
                    position:fixed;
                    top:0; left:0;
                    width:100%; height:100%;
                    background:rgba(0,0,0,0.6);
                    z-index:9999;
                    justify-content:center;
                    align-items:center;
                    flex-direction:column;
                    ">

        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 50 50"
            style="margin:auto; background:none; display:block;">
            <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5" stroke-linecap="round"
                stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="1s"
                    repeatCount="indefinite" />
            </circle>
        </svg>

        <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
            ⏳ Please wait... Processing data
        </p>
    </div>
    <div class="clearfix visible-lg-block visible-md-block">
        <section class="header" style="padding-top:10px;padding-bottom:5px;">
            <div class="container">
                <div class="header-left">
                    <a class="navbar-brand" href="" style="margin: 0px;">
                        <img src="<?php echo base_url(); ?>assets/img/andhika.gif" alt="logo" style="width:50px;">
                    </a>
                </div>
                <label style="padding:5px;font-size:30px;color:#000080;">ANDHIKA GROUP</label>
            </div>
        </section>
    </div>
    <section id="menu" style="background-color:#067780;height:50px;width:100%;">
        <div class="container">
            <div class="menubar">
                <nav class="navbar navbar-default" style="margin-bottom:0px;">
                    <div class="navbar-header">
                        <a class="navbar-brand"
                            style="color:#FFFFFF;font-size:28px;font-weight:bold;margin-top:0px;padding-top:10px;font-family: serif;">
                            <img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>"
                                style="margin-right:10px;display:none;">
                            Report Crew Evaluation
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </section>
    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td width="50%">Vessel: <?php echo $vessel; ?></td>
                        <td width="50%">Date of Report (dd-mm-yyyy): <?php echo $dateOfReport; ?></td>
                    </tr>
                    <tr>
                        <td>Seafarer's Name: <?php echo $seafarerName; ?></td>
                        <td>Reporting Period From: <?php echo $reportPeriodFrom; ?></td>
                    </tr>
                    <tr>
                        <td>Rank: <?php echo $rank; ?></td>
                        <td>Reporting Period To: <?php echo $reportPeriodTo; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Reason for the Report:</strong></td>
                    </tr>
                    <tr>
                        <td>
                            <div>
                                <span
                                    style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px 0 0;">
                                    <?php echo $reasonMidway; ?>
                                </span> Midway through contract
                            </div>
                            <div>
                                <span
                                    style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px 0 0;">
                                    <?php echo $reasonLeaving; ?>
                                </span> Reporting crew leaving vessel
                            </div>
                        </td>
                        <td>
                            <div>
                                <span
                                    style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px 0 0;">
                                    <?php echo $reasonSigningOff; ?>
                                </span> Seafarer signing off vessel
                            </div>
                            <div>
                                <span
                                    style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px 0 0;">
                                    <?php echo $reasonSpecial; ?>
                                </span> Special request
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr class="table table-bordered"
                            style="width:100%; border-collapse:collapse; background-color:#067780; color:#FFFFFF;">
                            <th style="text-align: left;">Criteria</th>
                            <th style="text-align: center;">Excellent (4)</th>
                            <th style="text-align: center;">Good (3)</th>
                            <th style="text-align: center;">Fair (2)</th>
                            <th style="text-align: center;">Poor (1)</th>
                            <th style="text-align: center;">Identify Training Needs</th>
                            <th style="text-align: center;">Technical Superintendent Comments (if any)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $criteriaTable; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p><strong>General Comments highlighting strengths/weaknesses:</strong></p><br>
                        <p>Master: <?php echo $masterComments; ?></p><br>
                        <p>Reporting Officer: <?php echo $reportingOfficerComments; ?></p><br>
                        <p>Reason Reject: <?php echo $remark_reject; ?></p><br>

                        <p><strong>Re Employ:</strong><br>
                            <span
                                style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px;">
                                <?php echo ($reEmploy === 'Y') ? '&#10004;' : '&nbsp;'; ?>
                            </span> Yes
                            <span
                                style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px;">
                                <?php echo ($reEmploy === 'N') ? '&#10004;' : '&nbsp;'; ?>
                            </span> No
                        </p>
                        <p><strong>Promote:</strong><br>
                            <span
                                style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px;">
                                <?php echo ($promote === 'Y') ? '&#10004;' : '&nbsp;'; ?>
                            </span> Yes
                            <span
                                style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px;">
                                <?php echo ($promote === 'N') ? '&#10004;' : '&nbsp;'; ?>
                            </span> No
                            <span
                                style="display:inline-block;width:14px;height:14px;border:1px solid black;text-align:center;line-height:14px;margin:0 5px;">
                                <?php echo ($promote === 'C') ? '&#10004;' : '&nbsp;'; ?>
                            </span> Yes, Provided conditions are met
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <tr>
                        <td>
                            Reporting Officer<br>
                            Full Name: <?php echo $reportingOfficerName; ?><br>
                            Rank: <?php echo $reportingOfficerRank; ?><br>
                            Signature:<br>
                            <?php echo $qrCodeImg; ?>
                        </td>
                        <td>
                            Master / COO<br>
                            Full Name: <?php echo $mastercoofullname; ?><br>
                            Signature:<br>
                            <?php echo $qrCodePathMaster; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            SuperIntendent<br>
                            Full Name: <?php echo $superintendentFullName; ?><br>
                            Signature:<br>
                            <?php echo $qrCodePathSuperintendent; ?>
                        </td>
                        <td>
                            Received by CM: <?php echo $receivedByCM; ?><br>
                            Date of Receipt: <?php echo $dateOfReceipt; ?><br>
                            Signature:<br>
                            <?php echo $qrCodePathCM; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6">
                Form OPS-015/ Rev.03/ 27-07-2018
            </div>
            <div class="col-xs-6 text-right">
                Distribution: Original - Office / Copy - Ship File
            </div>
        </div>
        <div class="row text-center" style="margin-top: 15px;">
            <input type="hidden" id="txtIdReport" value="<?php echo $id_report; ?>">
            <div class="col-md-4 col-md-offset-4">
                <?php echo $btnAct; ?>
                <?php echo $btnReject; ?>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.hc-sticky.min.js">
    </script>
    <!-- <script type="text/javascript" src="assets/js/jquery.magnific-popup.min.js"></script> -->
    <!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <!-- <script type="text/javascript" src="assets/js/jquery.counterup.min.js"></script> -->
    <!-- <script type="text/javascript" src="assets/js/waypoints.min.js"></script> -->
    <!-- <script type="text/javascript" src="assets/js/jak-menusearch.js"></script> -->
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js">
    </script>
    </script>
</body>

<div class="modal fade" id="evaluasiModal" tabindex="-1" aria-labelledby="evaluasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #067780;">
                <h5 class="modal-title" id="evaluasiModalLabel" style="color: white;">Reason Reject</h5>
            </div>
            <div class="modal-body">
                <input type="hidden" id="txtIdReport" value="">
                <div class="form-group">
                    <label for="reasonReject">Reason Reject</label>
                    <textarea class="form-control" id="reasonReject" rows="5"
                        placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" onclick="rejectCM()">Submit</button>
            </div>
        </div>
    </div>
</div>



</html>