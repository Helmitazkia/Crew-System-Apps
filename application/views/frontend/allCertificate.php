<script type="text/javascript">
function saveData() {
    var formData = new FormData();

    var idEdit = $("#txtIdEditAllCertificate").val();
    var idPerson = $("#txtIdPersonAllCertificate").val();
    var useThisAll = "";
    var slcMstCert = $("#slcMstCertAllCert").val();
    var certDisplay = "";
    var slcLicense = $("#slcLicenseAllCert").val();
    var slcLevel = $("#slcLevelAllCert").val();
    var rank = $("#slcRankAllCert").val();
    var rankName = $("#slcRankAllCert option:selected").text();
    var slcVesselType = $("#slcVesselTypeAllCert").val();
    var slcCountryIssue = $("#slcCountryIssueAllCert").val();
    var slcCountryIssueName = $("#slcCountryIssueAllCert option:selected").text();
    var txtNoDocument = $("#txtNoDocumentAllCert").val();
    var txtDate_ofIssue = $("#txtDate_ofIssueAllCert").val();
    var txtDate_expiry = $("#txtDate_expiryAllCert").val();
    var txtPlaceofIssue = $("#txtPlaceofIssueAllCert").val();
    var txtIssuingAuthority = $("#txtIssuingAuthorityAllCert").val();
    var txtRemark = $("#txtRemarkAllCert").val();
    var slcRedSing = $("#slcRedSingAllCert").val();
    var fileUpload = $("#uploadFile").val();

    if ($('#chkUseThisAllCert').is(":checked")) {
        useThisAll = $("#chkUseThisAllCert").val();
    }
    if ($('#chkDisplayAllCert').is(":checked")) {
        certDisplay = $("#chkDisplayAllCert").val();
    }

    // console.log(rankName,slcVesselType);
    // return false;

    formData.append('idEdit', idEdit);
    formData.append('idPerson', idPerson);
    formData.append('useThisAll', useThisAll);
    formData.append('slcMstCert', slcMstCert);
    formData.append('certDisplay', certDisplay);
    formData.append('slcLicense', slcLicense);
    formData.append('slcLevel', slcLevel);
    formData.append('rank', rank);
    formData.append('rankName', rankName);
    formData.append('slcVesselType', slcVesselType);
    formData.append('slcCountryIssue', slcCountryIssue);
    formData.append('slcCountryIssueName', slcCountryIssueName);
    formData.append('txtNoDocument', txtNoDocument);
    formData.append('txtDate_ofIssue', txtDate_ofIssue);
    formData.append('txtDate_expiry', txtDate_expiry);
    formData.append('txtPlaceofIssue', txtPlaceofIssue);
    formData.append('txtIssuingAuthority', txtIssuingAuthority);
    formData.append('txtRemark', txtRemark);
    formData.append('slcRedSing', slcRedSing);

    formData.append('cekFileUpload', fileUpload);
    formData.append('fileUpload', $("#uploadFile").prop('files')[0]);

    // console.log(formData);
    // return false;

    $("#idLoadingForm").show();
    $.ajax("<?php echo base_url('personal/saveAllCertificate'); ?>", {
        method: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            alert(response);
            $("#idLoadingForm").hide();
            navProsesCrew();
        }
    });
}

function getDataEdit(id) {
    var idPerson = $("#txtIdPersonAllCertificate").val();
    $("#idLoadingForm").show();

    $.post('<?php echo base_url("personal/getDataProses"); ?>', {
            id: id,
            type: "editAllCert",
            idPerson: idPerson
        },
        function(data) {
            $("#txtIdEditAllCertificate").val(id);

            if (data.usecertdoc == 'Y') {
                $("#chkUseThisAllCert").prop('checked', true);
            } else {
                $("#chkUseThisAllCert").prop('checked', false);
            }

            if (data[0].display == 'Y') {
                $("#chkDisplayAllCert").prop('checked', true);
            } else {
                $("#chkDisplayAllCert").prop('checked', false);
            }

            if (slcMstCertAllCert) {
                slcMstCertAllCert.setValue(data[0].kdcert);
            } else {
                $("#slcMstCertAllCert").val(data[0].kdcert);
            }

            if (slcRankAllCert) {
                slcRankAllCert.setValue(data[0].kdrank);
            } else {
                $("#slcRankAllCert").val(data[0].kdrank);
            }
            
            if (slcVesselTypeAllCert) {
                slcVesselTypeAllCert.setValue(data[0].vsltype);
            } else {
                $("#slcVesselTypeAllCert").val(data[0].vsltype);
            }

            $("#slcLicenseAllCert").val(data[0].license);
            $("#slcVesselTypeAllCert").val(data[0].vsltype);
            $("#slcCountryIssueAllCert").val(data[0].kdnegara);
            $("#txtNoDocumentAllCert").val(data[0].docno);

            if (data[0].issdate == "0000-00-00") {
                $("#txtDate_ofIssueAllCert").val("");
            } else {
                $("#txtDate_ofIssueAllCert").val(data[0].issdate);
            }

            if (data[0].expdate == "0000-00-00") {
                $("#txtDate_expiryAllCert").val("");
            } else {
                $("#txtDate_expiryAllCert").val(data[0].expdate);
            }

            $("#idViewFile").empty();
            $("#idViewFile").append(data.btnFile);

            $("#txtPlaceofIssueAllCert").val(data[0].issplace);
            $("#txtIssuingAuthorityAllCert").val(data[0].issauth);
            $("#txtRemarkAllCert").val(data[0].remarks);
            $("#slcRedSingAllCert").val(data[0].redsign);
            $("#idLoadingForm").hide();
        },
        "json"
    );
}

function delFile(id, file) {
    var cfm = confirm("Delete File...??");
    if (cfm) {
        $("#idLoading").show();
        $.post('<?php echo base_url("personal/deleteData"); ?>/', {
                id: id,
                file: file,
                type: "deleteAllCertificateFile"
            },
            function(data) {
                alert(data);
                $("#idViewFile").empty();
            },
            "json"
        );
    }
}

function delData(id, idPerson) {
    var cfm = confirm("Delete data...??");
    if (cfm) {
        $("#idLoading").show();
        $.post('<?php echo base_url("personal/deleteData"); ?>/', {
                id: id,
                idPerson: idPerson,
                type: "deleteAllCertificate"
            },
            function(data) {
                alert(data);
                navProsesCrew();
            },
            "json"
        );
    }
}

    var slcRankAllCert;
    var slcVesselTypeAllCert;
    var slcMstCertAllCert;

    $(document).ready(function () {  
        slcRankAllCert = new TomSelect("#slcRankAllCert", {
            create: false,
            searchField: ["text"]
        });

        slcVesselTypeAllCert = new TomSelect("#slcVesselTypeAllCert", {
            create: false,
            searchField: ["text"]
        });

        slcMstCertAllCert = new TomSelect("#slcMstCertAllCert", {
            create: false,
            searchField: ["text"]
        });

    });

function exportPDF() {
    var idPerson = $("#txtIdPersonAllCertificate").val();
    window.open("<?php echo base_url('personal/exportPDFCertificate/'); ?>" + '/' + idPerson, '_blank');
}
</script>

<div class="row" style="margin-top:5px;">
    <div class="col-md-1 col-xs-12">
        <button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="navProsesCrew();"><i
                class="fa fa-refresh"></i> Refresh</button>
    </div>
    <div class="col-md-1 col-xs-12">
        <button class="btn btn-warning btn-xs btn-block" title="Export PDF" onclick="exportPDF();"><i
                class="fa fa-download"></i>
            Export PDF</button>
    </div>
</div>
<div class="row" style="margin-top:5px;">
    <div class="col-md-6 col-xs-12">
        <div class="table-responsive">
            <table class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                style="background-color:#D7EAEC;">
                <thead>
                    <tr style="background-color:#067780;color:#FFF;height:30px;">
                        <th style="vertical-align:middle;width:7%;text-align:center;">No</th>
                        <th style="vertical-align:middle;width:8%;text-align:center;">Display</th>
                        <th style="vertical-align:middle;width:50%;text-align:center;">Certificates Name</th>
                        <th style="vertical-align:middle;width:10%;text-align:center;">Expired Certificates</th>
                        <th style="vertical-align:middle;width:20%;text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody id="idTbody">
                    <?php echo $trNya; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-6 col-xs-12">
        <legend style="margin-bottom:10px;"><b><i>:: Certificate / Document ::</i></b></legend>
        <div class="row">
            <div class="col-md-5 col-xs-12">
                <input type="checkbox" id="chkUseThisAllCert" value="Y" checked="checked">
                <label for="chkUseThisAllCert" style="font-size:12px;">(Use this All Certificate / Document)</label>
            </div>
            <div class="col-md-7 col-xs-12">
                <label for="slcMstCertAllCert">Certificate Name :</label>
                <!-- <select class="form-control input-sm" id="slcMstCertAllCert">
                    <?php echo $optMstCert; ?>
                </select> -->
                <select id="slcMstCertAllCert" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px;
                    background:#f9fafb; font-size:14px;">
                    <?php echo $optMstCert; ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 col-xs-12">
                <label for="uploadFile">Certificate File <span style="color:red;font-size:10px;">(Max : 2 MB)</span>
                    :</label>
                <input type="file" class="form-control" id="uploadFile" value="">
            </div>
            <div class="col-md-4 col-xs-12">
                <label>&nbsp;</label>
                <button class="btn btn-warning btn-xs btn-block" title="Clear File"
                    onclick="$('#uploadFile').val('');">Clear</button>
                <div id="idViewFile" style="margin-top:5px;"></div>
            </div>
        </div>
        <legend style="margin-top:10px;margin-bottom:10px;"><b><i>:: Certificate / Document Description ::</i></b>
        </legend>
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <input type="checkbox" id="chkDisplayAllCert" value="Y" checked="checked">
                <label for="chkDisplayAllCert" style="font-size:12px;">Display</label>
            </div>
            <div class="col-md-4 col-xs-12">
                <label for="slcLicenseAllCert">License :</label>
                <select class="form-control input-sm" id="slcLicenseAllCert">
                    <option value="-">-</option>
                    <option value="COC">COC</option>
                    <option value="Endorsement">Endorsement</option>
                </select>
            </div>
            <div class="col-md-4 col-xs-12">
                <label for="slcLevelAllCert">Level :</label>
                <select class="form-control input-sm" id="slcLevelAllCert">
                    <option value="-">-</option>
                    <option value="Incharge">Incharge</option>
                    <option value="Asst.">Asst.</option>
                </select>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <label for="slcRankAllCert">Rank :</label>
                <!-- <select class="form-control input-sm" id="slcRankAllCert">
                    <?php echo $optRank; ?>
                </select> -->
                <select  id="slcRankAllCert" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px;
                    background:#f9fafb; font-size:14px;">
                    <?php echo $optRank; ?>
                </select>
            </div>
            <div class="col-md-4 col-xs-12">
                <label for="slcVesselTypeAllCert">Vessel Type :</label>
                <!-- <select class="form-control" class="form-control input-sm" id="slcVesselTypeAllCert">
                    <?php echo $optType ?>
                </select> -->
                <select  id="slcVesselTypeAllCert" style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px;
                    background:#f9fafb; font-size:14px;">
                    <?php echo $optType; ?>
                </select>
            </div>
            <!-- <div class="col-md-4 col-xs-12">
                <label for="slcCountryIssueAllCert">Country of Issue :</label>
                <select class="form-control input-sm" id="slcCountryIssueAllCert">
                    <?php echo $optCountry; ?>
                </select>
            </div> -->
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <label for="txtNoDocumentAllCert">No Document :</label>
                <input type="text" class="form-control input-sm" id="txtNoDocumentAllCert" value=""
                    placeholder="Document No">
            </div>
            <div class="col-md-4 col-xs-12">
                <label for="txtDate_ofIssueAllCert">Date of Issue :</label>
                <input type="text" class="form-control input-sm" id="txtDate_ofIssueAllCert" value=""
                    placeholder="Date">
            </div>
            <div class="col-md-4 col-xs-12">
                <label for="txtDate_expiryAllCert">Date of Expiry :</label>
                <input type="text" class="form-control input-sm" id="txtDate_expiryAllCert" value="" placeholder="Date">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <label for="txtPlaceofIssueAllCert">Place of Issue :</label>
                <input type="text" class="form-control input-sm" id="txtPlaceofIssueAllCert" value=""
                    placeholder="Place of Issue">
            </div>
            <div class="col-md-4 col-xs-12">
                <label for="txtIssuingAuthorityAllCert">Issuing Authority / Body :</label>
                <input type="text" class="form-control input-sm" id="txtIssuingAuthorityAllCert" value=""
                    placeholder="Issuing Authority / Body">
            </div>
            <div class="col-md-4 col-xs-12">
                <label for="txtRemarkAllCert">Remark :</label>
                <textarea class="form-control input-sm" id="txtRemarkAllCert"></textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <label for="slcRedSingAllCert">Red sign :</label>
                <select class="form-control input-sm" id="slcRedSingAllCert">
                    <option value="N">NO</option>
                    <option value="Y">YES</option>
                </select>
            </div>
            <div class="col-md-4 col-xs-12">
                <input type="hidden" id="txtIdPersonAllCertificate" value="">
                <input type="hidden" id="txtIdEditAllCertificate" value="">
                <label>&nbsp</label>
                <button class="btn btn-primary btn-sm btn-block" onclick="saveData();"><i
                        class="glyphicon glyphicon-saved"></i> Submit</button>
            </div>
            <div class="col-md-4 col-xs-12">
                <label>&nbsp</label>
                <button class="btn btn-danger btn-sm btn-block" onclick="navProsesCrew();"><i
                        class="glyphicon glyphicon-ban-circle"></i>Cancel</button>
            </div>
        </div>
    </div>
</div>