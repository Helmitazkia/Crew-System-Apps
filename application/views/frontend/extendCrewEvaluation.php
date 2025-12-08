<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>image/AndhikaTransparentBkGndBlue.png" />
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icon-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/hover-min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script>
    $(document).ready(function() {
        $("[id^=txtDate]").datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });
    });

    function saveDataCrewEvaluation() {
        var formData = new FormData();
        var idPerson = $("#txtIdPerson").val();

        function validateDate(id) {
            let date = $("#" + id).val();
            return date && date !== "0000-00-00" ? date : null;
        }

        formData.append("txtIdPerson", idPerson);

        formData.append("vessel", $("#vessel").val());
        formData.append("personName", $("#personName").val());
        formData.append("rank", $("#rank").val());
        formData.append("txtDateOfReport", validateDate("txtDateOfReport"));
        formData.append("txtDateOfReportingPeriodFrom", validateDate("txtDateOfReportingPeriodFrom"));
        formData.append("txtDateOfReportingPeriodTo", validateDate("txtDateOfReportingPeriodTo"));
        formData.append("txtDateReceipt", validateDate("txtDateReceipt"));

        formData.append("reasonMidway", $("#reasonMidway").is(":checked") ? 'Y' : '');
        formData.append("reasonSigningOff", $("#reasonSigningOff").is(":checked") ? 'Y' : '');
        formData.append("reasonLeaving", $("#reasonLeaving").is(":checked") ? 'Y' : '');
        formData.append("reasonSpecialRequest", $("#reasonSpecialRequest").is(":checked") ? 'Y' : '');

        formData.append("txtMasterComments", $("#txtMasterComments").length ? $("#txtMasterComments").val() : '');

        formData.append("txtOfficerComments", $("#txtOfficerComments").val());
        formData.append("chiefName", $("#chiefName").val());
        formData.append("chiefRank", $("#chiefRank").val())
        formData.append("masterName", $("#masterName").val());
        formData.append("txtPromoted", $("input[name='txtPromoted']:checked").val() || 'N');
        formData.append("txtReemploy", $("input[name='txtReemploy']:checked").val() || 'N');
        formData.append("txtreceived", $("#txtreceived").val());

        var criteriaList = {
            "Ability/Knowledge of Job": "ability",
            "Safety Consciousness": "safety",
            "Dependability & Integrity": "integrity",
            "Initiative": "initiative",
            "Conduct": "conduct",
            "Ability to get on with others": "abilityGetOn",
            "Appearance (+ uniforms)": "appearance",
            "Sobriety": "sobriety",
            "English Language": "english",
            "Leadership (Officers)": "leadership"
        };

        for (let criteriaName in criteriaList) {
            let criteriaId = criteriaList[criteriaName];
            let selectedValue = $("input[name='" + criteriaId + "']:checked").val() || '';
            formData.append(criteriaId, selectedValue);
            formData.append("txtIdentify" + criteriaId.charAt(0).toUpperCase() + criteriaId.slice(1), $("#txtIdentify" +
                criteriaId.charAt(0).toUpperCase() + criteriaId.slice(1)).val() || '');
            // formData.append("txtTSComments" + criteriaId.charAt(0).toUpperCase() + criteriaId.slice(1), $(
            //     "#txtTSComments" + criteriaId.charAt(0).toUpperCase() + criteriaId.slice(1)).val() || '');
        }

        $("#idLoadingSpinner").show();

        $.ajax({
            url: "<?php echo base_url('extendCrewEvaluation/saveDataCrewEvaluation'); ?>",
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                $("#idLoadingSpinner").hide();
                if (response.status === "success") {
                    alert(response.message);
                    var encryptedId = encodeURIComponent(idPerson);
                    var printUrl =
                        "<?php echo base_url('extendCrewEvaluation/printCrewEvaluation/'); ?>" +
                        "/" +
                        encryptedId;
                    var printWindow = window.open(printUrl, '_blank');
                    if (printWindow) {
                        printWindow.focus();
                    }
                    $("#formCrewEvaluation").find("input[type=text], input[type=date], textarea")
                        .val("");
                    $("#formCrewEvaluation").find("input[type=radio], input[type=checkbox]").prop(
                        "checked",
                        false);
                    $("#formCrewEvaluation").find("select").val("");
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                $("#idLoading").hide();
                console.error("AJAX Error:", xhr.responseText);
                alert("Error saving data. Please try again.");
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
                            Form Crew Evaluation
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </section>
    <div style="background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <div class="row" id="formCrewEvaluation">
            <div class="col-md-2 col-xs-12">
                <label style="font-size:16px;">Vessel :</label>
                <input type="text" id="vessel" class="form-control" value="<?php echo $vessel; ?>" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">Seafarer's Name</label>
                <input type="text" id="personName" class="form-control" value="<?php echo $personName; ?>" readonly>
            </div>
            <div class="col-md-2">
                <label style="font-size:12px;">Rank:</label>
                <input type="text" id="rank" class="form-control" value="<?php echo $rank; ?>" readonly>
            </div>
            <div class="col-md-2">
                <label class="form-label">Date of Report</label>
                <input type="date" class="form-control" id="txtDateOfReport">
            </div>
            <div class="col-md-2">
                <label class="form-label">Reporting Period From</label>
                <input type="date" class="form-control" id="txtDateOfReportingPeriodFrom">
            </div>
            <div class="col-md-2">
                <label class="form-label">Reporting Period To</label>
                <input type="date" class="form-control" id="txtDateOfReportingPeriodTo">
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4 style="font-family: calibri; margin-bottom: 10px;">Reason for the Report:</h4>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="reasonMidway" value="Y">
                    <label class="form-check-label" for="reasonMidway" style="margin-right: 10px;">Midway
                        through contract</label>

                    <input class="form-check-input" type="checkbox" id="reasonSigningOff" value="Y">
                    <label class="form-check-label" for="reasonSigningOff" style="margin-right: 10px;">Seafarer signing
                        off vessel</label>

                    <input class="form-check-input" type="checkbox" id="reasonLeaving" value="Y">
                    <label class="form-check-label" for="reasonLeaving" style="margin-right: 10px;">Reporting crew
                        leaving vessel</label>

                    <input class="form-check-input" type="checkbox" id="reasonSpecialRequest" value="Y">
                    <label class="form-check-label" for="reasonSpecialRequest" style="margin-right: 10px;">Special
                        request</label>
                </div>
            </div>
        </div>
        <hr>
        <div class="mb-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Excellent (4)</th>
                        <th>Good (3)</th>
                        <th>Fair (2)</th>
                        <th>Poor (1)</th>
                        <th>Identify Training Needs</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ability/Knowledge of Job</td>
                        <td><input type="radio" name="ability" value="4"></td>
                        <td><input type="radio" name="ability" value="3"></td>
                        <td><input type="radio" name="ability" value="2"></td>
                        <td><input type="radio" name="ability" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifyAbility"></td>
                    </tr>
                    <tr>
                        <td>Safety Consciousness</td>
                        <td><input type="radio" name="safety" value="4"></td>
                        <td><input type="radio" name="safety" value="3"></td>
                        <td><input type="radio" name="safety" value="2"></td>
                        <td><input type="radio" name="safety" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifySafety"></td>
                        </td>
                    </tr>
                    <tr>
                        <td>Dependability & Integrity</td>
                        <td><input type="radio" name="integrity" value="4"></td>
                        <td><input type="radio" name="integrity" value="3"></td>
                        <td><input type="radio" name="integrity" value="2"></td>
                        <td><input type="radio" name="integrity" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifyIntegrity"></td>
                        </td>
                    </tr>
                    <tr>
                        <td>Initiative</td>
                        <td><input type="radio" name="initiative" value="4"></td>
                        <td><input type="radio" name="initiative" value="3"></td>
                        <td><input type="radio" name="initiative" value="2"></td>
                        <td><input type="radio" name="initiative" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifyInitiative"></td>
                    </tr>
                    <tr>
                        <td>Conduct</td>
                        <td><input type="radio" name="conduct" value="4"></td>
                        <td><input type="radio" name="conduct" value="3"></td>
                        <td><input type="radio" name="conduct" value="2"></td>
                        <td><input type="radio" name="conduct" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifyConduct"></td>
                    </tr>
                    <tr>
                        <td>Ability to get on with others</td>
                        <td><input type="radio" name="abilityGetOn" value="4"></td>
                        <td><input type="radio" name="abilityGetOn" value="3"></td>
                        <td><input type="radio" name="abilityGetOn" value="2"></td>
                        <td><input type="radio" name="abilityGetOn" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifyAbilityGetOn"></td>
                    </tr>
                    <tr>
                        <td>Appearance (+ uniforms)</td>
                        <td><input type="radio" name="appearance" value="4"></td>
                        <td><input type="radio" name="appearance" value="3"></td>
                        <td><input type="radio" name="appearance" value="2"></td>
                        <td><input type="radio" name="appearance" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifyAppearance"></td>
                    </tr>
                    <tr>
                        <td>Sobriety</td>
                        <td><input type="radio" name="sobriety" value="4"></td>
                        <td><input type="radio" name="sobriety" value="3"></td>
                        <td><input type="radio" name="sobriety" value="2"></td>
                        <td><input type="radio" name="sobriety" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifySobriety"></td>
                    </tr>
                    <tr>
                        <td>English Language</td>
                        <td><input type="radio" name="english" value="4"></td>
                        <td><input type="radio" name="english" value="3"></td>
                        <td><input type="radio" name="english" value="2"></td>
                        <td><input type="radio" name="english" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifyEnglish"></td>
                    </tr>
                    <tr>
                        <td>Leadership (Officers)</td>
                        <td><input type="radio" name="leadership" value="4"></td>
                        <td><input type="radio" name="leadership" value="3"></td>
                        <td><input type="radio" name="leadership" value="2"></td>
                        <td><input type="radio" name="leadership" value="1"></td>
                        <td><input type="text" class="form-control input-sm" id="txtIdentifyLeadership"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <label>&bullet; General Comments highlighting strengths / weaknesses:</label>
            </div>
            <div class="col-md-6">
                <label class="form-label">Officer Comments</label>
                <textarea class="form-control" name="comments_officer" rows="6"
                    placeholder="Reporting Officer's comments" id="txtOfficerComments"></textarea>
            </div>
            <div class="col-md-3">
                <label class="form-label">Re-employ</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="txtReemploy" value="Y">
                    <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="txtReemploy" value="N">
                    <label class="form-check-label">No</label>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Promote</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="txtPromoted" value="Y">
                    <label class="form-check-label">Yes</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="txtPromoted" value="N">
                    <label class="form-check-label">No</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="txtPromoted" value="Conditional">
                    <label class="form-check-label">Yes, provided the following conditions are
                        met</label>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <label>&bullet; Reporting Officer:</label>
            </div>
            <div class="col-md-2 col-xs-12">
                <label style="font-size:12px;">Fullname :</label>
                <input type="text" id="chiefName" class="form-control" value="<?php echo $chiefName; ?>" readonly>
            </div>
            <div class="col-md-2">
                <label style="font-size:12px;">Rank:</label>
                <input type="text" id="chiefRank" class="form-control" value="<?php echo $chiefRank; ?>" readonly>
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="txtmastercoofullname" style="font-size:12px;">Master / COO Full
                    Name:</label>
                <input type="text" id="masterName" class="form-control" value="<?php echo $masterName; ?>" readonly>
            </div>
            <div class="col-md-2 col-xs-12">
                <label for="txtreceived" style="font-size:12px;">Received by CM :</label>
                <input type="text" class="form-control input-sm" id="txtreceived" value="EVA MARLIANA" readonly>
            </div>
        </div>
        <div class="row" style="margin-top: 10px;">
            <input type="hidden" id="txtIdPerson" value="<?php echo $idpersonEncrypted; ?>">
            <input type="hidden" id="txtIdEditCrew" value="">
            <div class="col-md-4">
                <button class="btn btn-primary btn-block btn-xs" onclick="saveDataCrewEvaluation();">Submit</button>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.hc-sticky.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</body>

</html>