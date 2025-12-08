<?php $this->load->view('frontend/menu'); ?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <script type="text/javascript">
    $(document).ready(function() {
        $("[id^=txtDate]").datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });

        $("#slcSearchCompany").change(function() {
            var cmpNya = $(this).val();

            $("#idLoading").show();
            $.post('<?php echo base_url("dataContext/getVesselByOption/json/kode"); ?>' + '/' +
                cmpNya, {},
                function(data) {
                    $("#slcSearchVessel").empty();
                    $("#slcSearchVessel").append(data);
                    $("#idLoading").hide();
                },
                "json"
            );
        });

    });

    function saveDataContract() {
        var idEdit = $("#txtIdEditContract").val();
        var idPerson = $("#txtIdEditContractIdPerson").val();
        var signOffDate = $("#txtDate_signOffCont").val();
        var signOffRemark = $("#slcSignOffRemarkCont").val();
        var replacement = $("#slcReplacementCont").val();
        var addForCont = $("input[name='addForCont']:checked").val();
        var statusNya = $("#txtStatusContract").val();

        if (typeof $("input[name='addForCont']:checked").val() === "undefined") {
            addForCont = "";
        }

        $("#idLoadingFormCont").show();
        $.post('<?php echo base_url("contract/updateDataCrewStatus"); ?>', {
                idEdit: idEdit,
                idPerson: idPerson,
                signOffDate: signOffDate,
                signOffRemark: signOffRemark,
                replacement: replacement,
                addForCont: addForCont
            },
            function(data) {
                alert(data);
                getDataEditSignOff(idPerson, statusNya);
            },
            "json"
        );
    }

    function getDataEditSignOff(idPerson, status) {
        $("#idLoadingFormCont").show();
        resetFormContract();

        $.post('<?php echo base_url("contract/getDataEditSignOff"); ?>', {
                idPerson: idPerson,
                status: status
            },
            function(data) {
                $("#idTbodyForm").empty();
                $("#idTbodyForm").append(data.trNya);

                $("#slcReplacementCont").empty();
                $("#slcReplacementCont").append(data.optReplacement);

                $("#teksJudulName").text(data.fullName);
                $("#btnIdBackCrewCont").attr("onclick", "searchData('" + status + "');");

                $("#txtStatusContract").val(status);
                $("#idDataTableCont").hide();
                $("#idFormCont").show(100);
                $("#idLoadingFormCont").hide();
            },
            "json"
        );
    }

    function getDataEdit(id, idPerson) {
        $("#idLoadingForm").show();

        $.post('<?php echo base_url("contract/getDataEdit"); ?>', {
                id: id,
                idPerson: idPerson
            },
            function(data) {
                $("#txtIdEditContract").val(id);
                $("#txtIdEditContractIdPerson").val(idPerson);
                $("#slcCompanyCont").val(data.company);
                $("#txtDate_signOnCont").val(data.signOnDate);
                $("#slcSignOnRankCont").val(data.rank);
                $("#slcSignOnVesselCont").val(data.vessel);
                $("#txtSignOnPortCont").val(data.port);
                $("#txtSignOnDescCont").val(data.signOnDesc);
                $("#txtLastVesselCont").val(data.lastVessel);
                $("#txtNoPkl").val(data.noPkl);
                $("#txtDate_EstSignOffCont").val(data.estSignOffDate);
                $("#txtRemarkCont").val(data.estRemark);
                $("#txtDate_signOffCont").val(data.signOffDate);
                $("#slcSignOffRemarkCont").val(data.remark);
                $("#slcReplacementCont").val(data.reason);

                if (data.add == "1") {
                    $("#addForCont1").prop("checked", true);
                }
                if (data.for == "1") {
                    $("#addForCont2").prop("checked", true);
                }

                $("#btnIdSubmitContract").attr("disabled", false);
                $("#btnIdCancelContract").attr("disabled", false);

                $("#idDataTableCont").hide();
                $("#idFormCont").show(100);
                $("#idLoadingForm").hide();
            },
            "json"
        );
    }

    function searchData(statusNya) {
        var status = $("#slcSearchStatus").val();

        if (statusNya != "") {
            status = statusNya;
            $("#slcSearchStatus").val(statusNya);
            $("#slcSearchCompany").val("");
            $("#slcSearchVessel").val("");
            $("#slcSearchRank").val("");
        }

        if (status == 'all') {
            $('#idTDCertOnboard, #idTRCertOnboard').show();
            $("#idTableCertificate").css('display', 'inline-block');
            $('#colSSBT, #colOTCBHS, #colCTCBHS, #colERST').show();

        } else if (status == 'onboard') {
            $('#idTDCertOnboard, #idTRCertOnboard').show();
            $("#idTableCertificate").css('display', 'inline-block');
            $('#colSSBT, #colOTCBHS, #colCTCBHS, #colERST').show();
        } else {
            $('#idTDCertOnboard, #idTRCertOnboard').hide();
            $("#idTableCertificate").css('display', '');

            $('#colSSBT, #colOTCBHS, #colCTCBHS, #colERST').hide();
        }

        var company = $("#slcSearchCompany").val();
        var vessel = $("#slcSearchVessel").val();
        var rank = $("#slcSearchRank").val();

        $("#idLoadingFormCont, #idLoadingDocCont, #idLoading").show();
        $("#idTbody").empty();

        $.post('<?php echo base_url("contract/getDataCrewStatus/search"); ?>', {
                status: status,
                company: company,
                vessel: vessel,
                rank: rank
            },
            function(data) {
                $("#idTbody").empty().append(data);

                $("#idLoading, #idLoadingFormCont, #idLoadingDocCont").hide();

                $("#idFormCont, #idFormViewDocument").hide();
                $("#idDataTableCont").show(100);
            },
            "json"
        );
    }

    function printData() {
        var status = $("#slcSearchStatus").val();
        var company = $("#slcSearchCompany").val();
        var vessel = $("#slcSearchVessel").val();
        var rank = $("#slcSearchRank").val();

        if (status == "") {
            status = "-";
        }
        if (company == "") {
            company = "-";
        }
        if (vessel == "") {
            vessel = "-";
        }
        if (rank == "") {
            rank = "-";
        }

        window.open("<?php echo base_url('contract/printData');?>/" + status + "/" + company + "/" + vessel + "/" +
            rank, "_blank");
    }

    function viewDocument(idPerson, status) {
        $("#idLoading").show();

        $.post('<?php echo base_url("contract/getDataDocument"); ?>', {
                idPerson: idPerson
            },
            function(data) {
                $("#btnIdBackViewDocCont").attr("onclick", "searchData('" + status + "');");

                $("#idTbodyDocument").empty();
                $("#idTbodyDocument").append(data.trNya);

                $("#teksJudulNameDoc").text(data.fullName);
                $("#lblAllCertDoc").text(data.useAllCertDoc);

                $("#idDataTableCont").hide();
                $("#idFormViewDocument").show(100);
                $("#idLoading").hide();
            },
            "json"
        );
    }

    function viewDocumentDetail(id) {
        $("#idLoadingDocCont").show();

        $.post('<?php echo base_url("contract/getDataDetailDocument"); ?>', {
                id: id
            },
            function(data) {
                $("#lblCertNameDoc").text(data.certname);
                $("#lblDisplayDoc").html(data.display);
                $("#lblLicenseDoc").text(data.license);
                $("#lblLevelDoc").text(data.level);
                $("#lblRankDoc").text(data.nmrank);
                $("#lblVesselTypeDoc").text(data.vsltype);
                $("#lblCountryDoc").text(data.nmnegara);
                $("#lblNoDoc").text(data.docno);
                $("#lblDateIssueDoc").text(data.issdate);
                $("#lblDateExpDoc").text(data.expdate);
                $("#lblPlaceIssueDoc").text(data.issplace);
                $("#lblIssueAuthoDoc").text(data.issauth);
                $("#lblRemarkDoc").text(data.remarks);
                $("#lblRedSignalDoc").text(data.redsign);

                $("#idLoadingDocCont").hide();
            },
            "json"
        );
    }

    function viewDocumentDetailNo(idRegDet, idPerson, tblNo) {
        $("#idLoadingDocCont").show();

        $.post('<?php echo base_url("contract/getDataDetailDocumentNo"); ?>', {
                idRegDet: idRegDet,
                idPerson: idPerson,
                tblNo: tblNo
            },
            function(data) {
                $("#lblCertNameDoc").text(data.certname);
                $("#lblDisplayDoc").html(data.display);
                $("#lblLicenseDoc").text(data.license);
                $("#lblLevelDoc").text(data.level);
                $("#lblRankDoc").text(data.nmrank);
                $("#lblVesselTypeDoc").text(data.vsltype);
                $("#lblCountryDoc").text(data.nmnegara);
                $("#lblNoDoc").text(data.docno);
                $("#lblDateIssueDoc").text(data.issdate);
                $("#lblDateExpDoc").text(data.expdate);
                $("#lblPlaceIssueDoc").text(data.issplace);
                $("#lblIssueAuthoDoc").text(data.issauth);
                $("#lblRemarkDoc").text(data.remarks);
                $("#lblRedSignalDoc").text(data.redsign);

                $("#idLoadingDocCont").hide();
            },
            "json"
        );
    }

    function resetFormContract() {
        $("#slcCompanyCont").val('017');
        $("#txtDate_signOnCont").val('');
        $("#slcSignOnRankCont").val('060');
        $("#slcSignOnVesselCont").val('060');
        $("#txtSignOnPortCont").val('');
        $("#txtSignOnDescCont").val('');
        $("#txtLastVesselCont").val('');
        $("#txtMonthCalculate").val('');
        $("#txtDate_EstSignOffCont").val('');
        $("#txtRemarkCont").val('');
        $("#txtNoPkl").val('');
        $("#txtDate_signOffCont").val('');
        $("#slcSignOffRemarkCont").val('021');
        $('input[name="addForCont"]').prop('checked', false);
        $("#btnIdSubmitContract").attr("disabled", true);
        $("#btnIdCancelContract").attr("disabled", true);
    }

    function viewPersonalProses(idPerson) {
        window.open("<?php echo base_url('personal/getData/searchByContract');?>" + "/" + idPerson, '_blank');
    }

    function reloadPage() {
        window.location = "<?php echo base_url('contract/getDataCrewStatus');?>";
    }

    document.addEventListener("DOMContentLoaded", function() {
        var table = document.getElementById("idTableCertificate");
        var thead = table.querySelector("thead");
        var tableWrapper = table.parentElement;

        var cloneThead = thead.cloneNode(true);
        var stickyHeader = document.createElement("div");
        stickyHeader.appendChild(cloneThead);

        var tableStyle = getComputedStyle(table);
        Object.assign(stickyHeader.style, {
            position: "fixed",
            top: "0",
            left: tableWrapper.getBoundingClientRect().left + "px",
            width: tableWrapper.offsetWidth + "px",
            overflow: "hidden",
            zIndex: "1000",
            display: "none",
            borderCollapse: tableStyle.borderCollapse,
            backgroundColor: tableStyle.backgroundColor
        });

        function syncColumnWidths() {
            var originalThs = thead.querySelectorAll("th");
            var cloneThs = cloneThead.querySelectorAll("th");

            originalThs.forEach((th, index) => {
                var cloneTh = cloneThs[index];
                var computedStyle = getComputedStyle(th);

                cloneTh.style.width = th.offsetWidth + "px";

                cloneTh.style.borderTop = computedStyle.borderTop;
                cloneTh.style.borderBottom = computedStyle.borderBottom;
                cloneTh.style.borderLeft = computedStyle.borderLeft;
                cloneTh.style.borderRight = computedStyle.borderRight;

                cloneTh.style.padding = computedStyle.padding;
                cloneTh.style.boxSizing = "border-box";

                cloneTh.style.backgroundColor = computedStyle.backgroundColor;
                cloneTh.style.color = computedStyle.color;
            });
        }

        syncColumnWidths();

        tableWrapper.appendChild(stickyHeader);

        function updateStickyHeader() {
            var rect = table.getBoundingClientRect();
            var wrapperRect = tableWrapper.getBoundingClientRect();

            if (rect.top < 0 && rect.bottom > 0) {
                stickyHeader.style.display = "block";
                stickyHeader.style.left = wrapperRect.left + "px";
                syncColumnWidths();
            } else {
                stickyHeader.style.display = "none";
            }
        }

        tableWrapper.addEventListener("scroll", function() {
            stickyHeader.style.left = (tableWrapper.getBoundingClientRect().left) + "px";
        });

        window.addEventListener("resize", function() {
            stickyHeader.style.width = tableWrapper.offsetWidth + "px";
            syncColumnWidths();
        });

        window.addEventListener("scroll", updateStickyHeader);
    });

    function openEvaluasiModal(idPerson) {
        $("#txtModalGenLinkIdPerson").val(idPerson);
        $('#generatedLinkContainer').html("");

        $('#evaluasiModal')
            .data('idperson', idPerson)
            .modal('show');
    }

    function generateLink(department) {
        const idPerson = $('#txtModalGenLinkIdPerson').val();

        $.ajax({
            url: '<?php echo base_url('extendCrewEvaluation/generateLink'); ?>',
            type: 'POST',
            data: {
                txtModalGenLinkIdPerson: idPerson,
                department: department
            },
            success: function(response) {
                const res = JSON.parse(response);
                const link = res.url;
                $("#txtEmailKapalTo").val(res.emailVessel);

                $('#generatedLinkContainer').html(`
                    <label class="form-label">Link Evaluasi:</label>
                    <div class="input-group">
                        <input type="text" id="generatedLink" class="form-control" value="${link}" readonly>
                       <button type="button" class="btn btn-success" id="copyGenerateLink" onclick="copyGeneratedLink(event);">Copy</button>
                    </div>
                `);

                autoFillEmailTemplate(res, department, link);

                fillFormData(department, res);
            },
            error: function(xhr, status, error) {
                alert("Gagal generate link!");
                console.error(error);
            }
        });
    }

    function fillFormData(department, data) {
        $('#slcVesselHeader').val(data.vessel);
        $('#txtSeafarerName').val(data.personName);
        $('#slcRankHeader').val(data.rank);
        if (department === 'DECK') {
            $('#txtfullname').val(data.coName);
            $('#slcRank').val('Chief Officer');
        } else if (department === 'ENGINE') {
            $('#txtfullname').val(data.ceName);
            $('#slcRank').val('Chief Engineer');
        }
        $('#txtmastercoofullname').val(data.masterName);
    }

    function copyGeneratedLink() {
        const input = document.getElementById("generatedLink");
        if (!input) {
            console.error("Element #generatedLink tidak ditemukan.");
            return;
        }

        input.select();
        input.setSelectionRange(0, 99999);

        try {
            const success = document.execCommand("copy");
            if (success) {
                alert("Link telah disalin ke clipboard!");
            } else {
                throw new Error("Perintah copy gagal");
            }
        } catch (err) {
            console.error("Gagal menyalin link:", err);
            alert("Browser tidak mendukung salin otomatis. Silakan salin secara manual.");
        }
    }

    function autoFillEmailTemplate(data, department, link) {
        let crewName = data.personName;
        let rank = data.rank;
        let vesselName = data.vessel;

        let emailText =
            `Dear Master,

            Berikut kami kirimkan link evaluasi untuk crew berikut:

            Nama Crew : ${crewName}
            Rank      : ${rank}
            Kapal     : ${vesselName}
            Department: ${department}

            Silakan melakukan evaluasi melalui link berikut:
            ${link}

            Terima kasih.

            Best regards,
            Crewing Department
        `;

        $("#txtEmailKapalMessage").val(emailText);
    }

    function sendEmailToShip() {
        const idPerson = $("#txtModalGenLinkIdPerson").val();
        const message = $("#txtEmailKapalMessage").val();
        const emailTo = $("#txtEmailKapalTo").val();

        if (!message.trim()) {
            alert("Pesan email masih kosong.");
            return;
        }
        if (!emailTo.trim()) {
            alert("Email kapal kosong!");
            return;
        }

        $.ajax({
            url: "<?php echo base_url('extendCrewEvaluation/sendEmailToShip'); ?>",
            type: "POST",
            data: {
                idPerson: idPerson,
                message: message,
                emailTo: emailTo
            },
            success: function(response) {
                const res = JSON.parse(response);

                if (res.status === "success") {
                    alert("Email berhasil dikirim ke kapal!");
                    $('#evaluasiModal').modal('hide');
                } else {
                    alert("Gagal mengirim email: " + res.error);
                }
            }
        });
    }

    function enableEditEmail() {
        const input = document.getElementById("txtEmailKapalTo");
        input.removeAttribute("readonly");
        input.focus();
    }
    </script>
</head>

<body>
    <div class="container-fluid" style="background-color:#D4D4D4;min-height:950px;">
        <div class="form-panel" id="idFormCont" style="display:none;background-color:#E0E0E0;padding:10px;">
            <legend style="text-align:right;margin-bottom:5px;">
                <div class="row">
                    <div class="col-md-3 col-xs-12">
                    </div>
                    <div class="col-md-6 col-xs-12" style="text-align:center;">
                        <span id="teksJudulName" style="font-weight:bold;"></span>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <img id="idLoadingFormCont" src="<?php echo base_url('assets/img/loading.gif');?>"
                            style="margin-right:10px;display:none;">
                        <span id="teksJudulPage"><b><i>:: Sign Off Data ::</i></b></span>
                    </div>
                </div>
            </legend>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="row">
                                <div class="col-md-4 col-xs-12">
                                    <label for="slcCompanyCont">Company Name</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <select class="form-control input-sm" id="slcCompanyCont" disabled="disabled">
                                        <?php echo $optCompany; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="txtDate_signOnCont">Sign on Date</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <input type="text" class="form-control" id="txtDate_signOnCont" value=""
                                        placeholder="Date" disabled="disabled">
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="slcSignOnRankCont">Sign on Rank</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <select class="form-control input-sm" id="slcSignOnRankCont" disabled="disabled">
                                        <?php echo $optRank; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="slcSignOnVesselCont">Sign on Vessel</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <select class="form-control input-sm" id="slcSignOnVesselCont" disabled="disabled">
                                        <?php echo $optVessel; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="txtSignOnPortCont">Sign on Port</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control input-sm" id="txtSignOnPortCont" value=""
                                        placeholder="Port" disabled="disabled">
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="txtSignOnDescCont">Sign on Description</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-8 col-xs-12">
                                    <textarea id="txtSignOnDescCont" class="form-control input-sm"
                                        disabled="disabled"></textarea>
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="txtLastVesselCont">Last Vessel</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control input-sm" id="txtLastVesselCont" value=""
                                        placeholder="Last Vessel" disabled="disabled">
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="txtNoPkl">No. PKL</label><label style="float:right;">:</label>
                                </div>
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control input-sm" id="txtNoPkl" value=""
                                        placeholder="No PKL" disabled="disabled">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <div class="row">
                                <div class="col-md-4 col-xs-12" style="text-align: right;">
                                    <label for="txtMonthCalculate">Month</label>
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <input type="text" class="form-control input-sm" id="txtMonthCalculate" value=""
                                        placeholder="1,2,3.." disabled="disabled">
                                </div>
                                <div class="col-md-2 col-xs-12">
                                    <button class="btn btn-info btn-xs btn-block" title="Calculate"
                                        style="margin-top:5px;" onclick="calculateDate();"
                                        disabled="disabled">Calculate</button>
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="txtDate_EstSignOffCont">Estimate Sign off Date</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <input type="text" class="form-control input-sm" id="txtDate_EstSignOffCont"
                                        value="" placeholder="Date" disabled="disabled">
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="txtRemarkCont">Remarks</label><label style="float:right;">:</label>
                                </div>
                                <div class="col-md-8 col-xs-12">
                                    <textarea id="txtRemarkCont" class="form-control input-sm"
                                        disabled="disabled"></textarea>
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="txtDate_signOffCont">Sign off Date</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-3 col-xs-12">
                                    <input type="text" class="form-control input-sm" id="txtDate_signOffCont" value=""
                                        placeholder="Date">
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="slcSignOffRemarkCont">Sign off remarks</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <select class="form-control input-sm" id="slcSignOffRemarkCont">
                                        <?php echo $optSignOffRemark; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12">
                                    <label for="slcReplacementCont">Replacement Candidate</label><label
                                        style="float:right;">:</label>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <select class="form-control input-sm" id="slcReplacementCont">
                                        <option value="">-</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top:2px;">
                                <div class="col-md-4 col-xs-12"></div>
                                <div class="col-md-8 col-xs-12">
                                    <label class="radio-inline">
                                        <input type="radio" name="addForCont" id="addForCont1" value="add"> Additional
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="addForCont" id="addForCont2" value="for"> Foreign Crew
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:15px;">
                        <input type="hidden" id="txtIdEditContract" value="">
                        <input type="hidden" id="txtIdEditContractIdPerson" value="">
                        <input type="hidden" id="txtStatusContract" value="">
                        <div class="col-md-4 col-xs-12">
                            <button id="btnIdSubmitContract" class="btn btn-primary btn-xs btn-block"
                                title="Submit Data" onclick="saveDataContract();" disabled="disabled">
                                <i class="glyphicon glyphicon-saved"></i> Submit</button>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <button id="btnIdCancelContract" class="btn btn-danger btn-xs btn-block" title="Cancel Data"
                                onclick="resetFormContract();" disabled="disabled">
                                <i class="glyphicon glyphicon-ban-circle"></i> Cancel</button>
                        </div>
                        <div class="col-md-4 col-xs-12">
                            <button class="btn btn-success btn-xs btn-block" title="Back" id="btnIdBackCrewCont">
                                <i class="fa fa-mail-reply-all"></i> Back</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="margin-top:10px;">
                <div class="col-md-12 col-xs-12">
                    <div class="table-responsive">
                        <table
                            class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                            style="background-color:#D7EAEC;">
                            <thead>
                                <tr style="background-color:#067780;color:#FFF;height:30px;">
                                    <th style="vertical-align:middle;width:3%;text-align:center;">No</th>
                                    <th style="vertical-align:middle;width:15%;text-align:center;">Company</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Sign On</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Sign Off</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Sign On Rank</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Sign On Vessel</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Sign On Port</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Sign On Desc</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Last Vessel</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Est. Sign Off</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">No. PKL</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Remark</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">sign Off Remark</th>
                                    <th style="vertical-align:middle;width:5%;text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="idTbodyForm">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-panel" id="idFormViewDocument" style="display:none;background-color:#E0E0E0;padding:10px;">
            <legend style="text-align:right;margin-bottom:5px;">
                <div class="row">
                    <div class="col-md-2 col-xs-12">
                        <button class="btn btn-success btn-xs btn-block" title="Back" id="btnIdBackViewDocCont">
                            <i class="fa fa-mail-reply-all"></i> Back</button>
                    </div>
                    <div class="col-md-7 col-xs-12" style="text-align:center;">
                        <span id="teksJudulNameDoc" style="font-weight:bold;"></span>
                    </div>
                    <div class="col-md-3 col-xs-12">
                        <img id="idLoadingDocCont" src="<?php echo base_url('assets/img/loading.gif');?>"
                            style="margin-right:10px;display:none;">
                        <span><b><i>:: View Document ::</i></b></span>
                    </div>
                </div>
            </legend>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="table-responsive">
                        <table
                            class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                            style="background-color:#D7EAEC;">
                            <thead>
                                <tr style="background-color:#067780;color:#FFF;height:30px;">
                                    <th style="vertical-align:middle;width:7%;text-align:center;">No</th>
                                    <th style="vertical-align:middle;width:10%;text-align:center;">Group</th>
                                    <th style="vertical-align:middle;width:70%;text-align:center;">Certificates Name
                                    </th>
                                    <th style="vertical-align:middle;width:13%;text-align:center;">Action</th>
                                </tr>
                            </thead>
                            <tbody id="idTbodyDocument">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-4 col-xs-12">
                                    <label style="font-size:12px;">Use All Certificate / Document</label>
                                    <label style="float:right;font-weight:bold;">:</label>
                                </div>
                                <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                                    <span id="lblAllCertDoc" style="font-size:12px;"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Choosen Certificate</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblCertNameDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <legend style="margin-top:10px;margin-bottom:10px;color:#067780;text-align:center;"><b><i><u>::
                                    Certificate / Document Description ::</u></i></b></legend>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Display</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblDisplayDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">License</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblLicenseDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Level</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblLevelDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Rank</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblRankDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Vessel Type</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblVesselTypeDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Country of Issue</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblCountryDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">No Document</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblNoDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Date of Issue</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblDateIssueDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Date of Expiry</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblDateExpDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Place of Issue</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblPlaceIssueDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Issuing Authority / Body</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblIssueAuthoDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Remark</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblRemarkDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 col-xs-12">
                            <label style="font-size:12px;">Red sign</label>
                            <label style="float:right;font-weight:bold;">:</label>
                        </div>
                        <div class="col-md-8 col-xs-12" style="padding-left:0px;">
                            <span id="lblRedSignalDoc" style="font-size:12px;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-panel" style="margin-top:5px;" id="idDataTableCont">
            <div class="row">
                <div class="col-md-4 col-xs-12">
                    <div class="btn-group btn-group-justified" role="group" aria-label="Status Crew">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info btn-xs" title="On Board"
                                style="font-weight:bold;">On Board</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success btn-xs" title="On Leave"
                                style="font-weight:bold;">On Leave</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-warning btn-xs" title="Non Aktif"
                                style="font-weight:bold;">Non Aktif</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-danger btn-xs" title="Not for Employeed"
                                style="font-weight:bold;">Not for Emp.</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 col-xs-12">
                    <legend style="text-align:right;margin-bottom:5px;">
                        <img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>"
                            style="margin-right:10px;display:none;">
                        <b><i>:: Contract Crew Status List New ::</i></b>
                    </legend>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-2 col-xs-12">
                            <select class="form-control input-sm" id="slcSearchStatus">
                                <option value="">- Status -</option>
                                <option value="all">All</option>
                                <option value="nonaktif">Non Aktif</option>
                                <option value="notforemp">Not for Emp.</option>
                                <option value="onboard">On Board</option>
                                <option value="onleave">On Leave</option>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <select class="form-control input-sm" id="slcSearchCompany">
                                <option value="">- Company -</option>
                                <?php echo $optCompany; ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <select class="form-control input-sm" id="slcSearchVessel">
                                <option value="">- Vessel -</option>
                                <?php echo $optVessel; ?>
                            </select>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <select class="form-control input-sm" id="slcSearchRank">
                                <option value="">- Rank -</option>
                                <?php echo $optRank; ?>
                            </select>
                        </div>
                        <div class="col-md-1 col-xs-12">
                            <button class="btn btn-info btn-xs btn-block" title="Refresh" onclick="searchData('');">
                                <i class="fa fa-check-square-o"></i> Show</button>
                        </div>
                        <div class="col-md-1 col-xs-12">
                            <button class="btn btn-success btn-xs btn-block" title="Refresh" onclick="reloadPage();"><i
                                    class="fa fa-refresh"></i> Refresh</button>
                        </div>
                        <div class="col-md-1 col-xs-12">
                            <button class="btn btn-primary btn-xs btn-block" title="Export" onclick="printData();">
                                <i class="fa fa-print"></i> Export</button>
                        </div>
                    </div>
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-12 col-xs-12">
                            <div class="table-responsive" style="overflow-x: auto;white-space: nowrap;">
                                <table id="idTableCertificate"
                                    class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                                    style="background-color:#D7EAEC;width:100%;">
                                    <thead>
                                        <tr style="background-color:#067780;color:#FFF;height:30px;">
                                            <th style="width:5%;vertical-align:middle;text-align:center;" rowspan="2">No
                                            </th>
                                            <th style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                Rank</th>
                                            <th style="width:15%;vertical-align:middle;text-align:center;" rowspan="2">
                                                Full Name</th>
                                            <th style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                Nationality</th>
                                            <th style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                Birth</th>
                                            <th style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                Last Vessel</th>
                                            <th style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                Sign On</th>
                                            <th style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                Sign Off</th>
                                            <th style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                Est. Sign Off</th>
                                            <th id="colSeamanBook" style="vertical-align:middle;text-align:center;"
                                                colspan="2">Seaman Book</th>
                                            <th id="colPassport" style="vertical-align:middle;text-align:center;"
                                                colspan="2">Passport</th>
                                            <th id="colSSBT" style="width:10%;vertical-align:middle;text-align:center;"
                                                rowspan="2">
                                                SSBT</th>
                                            <th id="colOTCBHS"
                                                style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                OTCBHS</th>
                                            <th id="colCTCBHS"
                                                style="width:10%;vertical-align:middle;text-align:center;" rowspan="2">
                                                CTCBHS</th>
                                            <th id="colERST" style="width:10%;vertical-align:middle;text-align:center;"
                                                rowspan="2">
                                                ERST</th>
                                            <th id="colEndorsement" style="vertical-align:middle;text-align:center;"
                                                colspan="2">Endorsement</th>
                                            <th style="width:5%;vertical-align:middle;width:50px;text-align:center;"
                                                rowspan="2">Action</th>
                                        </tr>
                                        <tr style="background-color:#067780;color:#FFF;height:30px;">
                                            <th style="vertical-align:middle;width:20%;text-align:center;">Iss Date</th>
                                            <th style="vertical-align:middle;width:20%;text-align:center;">Exp Date</th>
                                            <th style="vertical-align:middle;width:20%;text-align:center;">Iss Date</th>
                                            <th style="vertical-align:middle;width:20%;text-align:center;">Exp Date
                                            </th>
                                            <th style="vertical-align:middle;width:20%;text-align:center;">Panama</th>
                                            <th style="vertical-align:middle;width:20%;text-align:center;">Other</th>
                                        </tr>
                                    </thead>
                                    <tbody id="idTbody">
                                        <?php echo $trNya; ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button onclick="topFunction()" id="myBtnToOnTop" title="Go to top"><i
            class="glyphicon glyphicon-eject"></i></button>

    <div class="modal fade" id="evaluasiModal" tabindex="-1" aria-labelledby="evaluasiModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #067780;">
                    <h5 class="modal-title" id="evaluasiModalLabel" style="color: white;">Pilih Department</h5>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="txtModalGenLinkIdPerson" value="">
                    <button class="btn btn-primary m-5" onclick="generateLink('DECK')">🚢 DECK</button>
                    <button class="btn btn-warning m-5" onclick="generateLink('ENGINE')">⚙️
                        ENGINE</button>
                    <div id="generatedLinkContainer" style="margin: 10px 0;"></div>
                    <hr class="my-4">
                    <label class="form-label">Email Kapal:</label>
                    <div class="input-group" style="margin-bottom: 10px;">
                        <input type="text" id="txtEmailKapalTo" class="form-control" readonly>
                        <button class="btn btn-secondary" onclick="enableEditEmail()">Edit</button>
                    </div>

                    <h5 class="text-start" style="margin-bottom: 10px;">📩 Kirim Email Evaluasi ke Kapal</h5>

                    <textarea id="txtEmailKapalMessage" class="form-control" rows="4"
                        placeholder="Tulis pesan email untuk kapal..."></textarea>

                    <div class="text-end" style="margin-top: 10px;">
                        <button class="btn btn-primary" onclick="sendEmailToShip()">Kirim Email</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>