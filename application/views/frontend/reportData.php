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
    });

    function searchData() {
        var txtSearch = $("#txtSearch").val();

        $("#idLoading").show();
        $.post('<?php echo base_url("report/getData/search"); ?>', {
                txtSearch: txtSearch
            },
            function(data) {
                $("#idTbody").empty();
                $("#idTbody").append(data.trNya);

                $("#idLoading").hide();
            },
            "json"
        );
    }

    function pickUpData(id, lblName) {
        $("#lblPickPerson").empty();
        $("#lblPickPerson").append(lblName);
        $("#txtIdPerson").val(id);

        $("#btnPrintPrincipal").attr("disabled", false);
        $("#btnExportPrincipal").attr("disabled", false);
        $("#btnPrintTransmital").attr("disabled", false);
        $("#btnPrintTraining").attr("disabled", false);
        $("#btnPrintReport").attr("disabled", false);
    }

    function printDataPrincipal() {
        var idPerson = $("#txtIdPerson").val();
        var company = $("#slcCompanyPrins").val();

        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }

        window.open("<?php echo base_url('report/navReport');?>/" + idPerson + "/" + company, "_blank");
    }

    $(document).ready(function() {
        if ($("#txtTotalWagesHidden").length === 0) {
            $("<input>", {
                type: "hidden",
                id: "txtTotalWagesHidden",
                name: "txtTotalWagesHidden"
            }).appendTo("body");
        }

        function updateTotal() {
            let basic = parseFloat($("#txtBasicWage").val()) || 0;
            let overtime = parseFloat($("#txtFixOvertime").val()) || 0;
            let leave = parseFloat($("#txtLeavePay").val()) || 0;
            let tanker = parseFloat($("#txtTankerAllowance").val()) || 0;

            let total = basic + overtime + leave + tanker;

            $("#txtTotalWages").text(total.toLocaleString("id-ID"));
            $("#txtTotalWagesHidden").val(total);
        }

        $("#txtBasicWage, #txtFixOvertime, #txtLeavePay, #txtTankerAllowance").on("input", updateTotal);

        $("#txtVesselFor").on("change", function() {
            let selectedCode = $(this).val();
            let selectedVessel = window.vesselData.find(vessel => vessel.kdvsl == selectedCode);

            if (!selectedVessel) {
                $("#tankerAllowanceWrapper").hide();
                $("#txtTankerAllowance").val("");
                updateTotal();
                return;
            }

            if (selectedVessel.nmvsl.trim().toUpperCase() === "MT. ANDHIKA VIDYANATA") {
                $("#tankerAllowanceWrapper").show();
            } else {
                $("#tankerAllowanceWrapper").hide();
                $("#txtTankerAllowance").val("");
            }

            updateTotal();
        });

        $("#btnSaveAndPrint").on("click", function() {
            var idperson = $("#txtIdPerson").val();
            var txtVesselFor = $("#txtVesselFor").val();
            var txtduration = $("#txtduration").val();
            var flag = $("#txtFlag").val();
            var imo = $("#txtIMO").val();
            var grt_hp = $("#txtGRT").val().trim();
            var txtSafetyCert = $("#txtSafetyCert").val();
            var txtCompetencyCert = $("#txtCompetencyCert").val();

            if (!idperson) {
                alert("⚠️ ID Person kosong!");
                return false;
            }

            $.ajax({
                url: "<?php echo base_url('pkl/saveVesselData'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    idperson: idperson,
                    dob: $("#txtDoBInput").val(),
                    kodepelaut: $("#txtSeafarerCodeInput").val(),
                    passportno: $("#txtPassportNoInput").val(),
                    seamanbookno: $("#txtSeamanBookNoInput").val(),
                    paddress: $("#txtAddressInput").val(),
                    txtVesselFor: txtVesselFor,
                    txtduration: txtduration,
                    flag: flag,
                    imo: imo,
                    grt_hp: grt_hp,
                    txtSafetyCert: txtSafetyCert,
                    txtCompetencyCert: txtCompetencyCert,
                    txtBasicWage: $("#txtBasicWage").val(),
                    txtFixOvertime: $("#txtFixOvertime").val(),
                    txtLeavePay: $("#txtLeavePay").val(),
                    txtTotalWages: $("#txtTotalWagesHidden").val()
                },
                beforeSend: function() {
                    $("#btnSaveAndPrint").prop("disabled", true).text("Saving...");
                },
                success: function(res) {
                    $("#btnSaveAndPrint").prop("disabled", false).text("Save & Print PKL");

                    if (res.success) {
                        alert("✅ " + res.message);

                        if (res.company_name) {
                            $("#txtShippingCompanyName").html("<b>" + res.company_name +
                                "</b>");
                        }

                        window.open("<?php echo base_url('pkl/getPKL'); ?>/" + idperson,
                            "_blank");
                        $("#pklModal").modal("hide").val("");
                    } else {
                        alert("⚠️ " + res.message);
                    }
                },
                error: function(xhr, status, error) {
                    $("#btnSaveAndPrint").prop("disabled", false).text("Save & Print PKL");
                    console.error(error);
                    alert("❌ Terjadi kesalahan saat menyimpan data kapal.");
                }
            });
        });
    });

    $(document).on("click", "#btnSaveAndPrintWages", function() {
        var idPerson = $("#txtIdPerson").val();

        if (!idPerson) {
            alert("⚠️ ID Person kosong!");
            return false;
        }

        let data = {
            idperson: idPerson,
            basic_wages: $("#basic").val(),
            fot: $("#fot").val(),
            tanker_allow: $("#tanker").val(),
            leave_pay: $("#leave").val(),
            bs_percent: $("#bs").val(),
            hs_percent: $("#hs").val(),
            total_pay: $("#totalPay").text().replace(/,/g, "")
        };

        $.ajax({
            url: "<?php echo base_url('wages/saveWagesData'); ?>",
            type: "POST",
            dataType: "json",
            data: data,
            beforeSend: function() {
                $("#btnSaveAndPrintWages").prop("disabled", true).text("Saving...");
            },
            success: function(res) {
                $("#btnSaveAndPrintWages").prop("disabled", false).text("Print");

                if (res.success) {
                    alert("✅ " + res.message);
                    $("#wagesModal").modal("hide");

                    window.open("<?php echo base_url('wages/getWages/'); ?>" + "/" +
                        idPerson,
                        "_blank");
                } else {
                    alert("⚠️ " + res.message);
                }
            },
            error: function(xhr, status, error) {
                $("#btnSaveAndPrintWages").prop("disabled", false).text("Print");
                console.error(error);
                alert("❌ Terjadi kesalahan saat menyimpan data gaji.");
            }
        });
    });

    function cetakPKLCrew() {
        var idPerson = $("#txtIdPerson").val();

        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }

        $("#txtIdPerson").val(idPerson);
        $('#pklLoading').show();
        $('#pklData').hide();

        $.ajax({
            url: "<?php echo base_url('report/getVesselByOption'); ?>",
            type: "GET",
            data: {
                onlyComplete: 1
            },
            dataType: "json",
            success: function(vslRes) {
                if (vslRes.success) {
                    $("#txtVesselFor").html(vslRes.options);
                    window.vesselData = vslRes.data;
                } else {
                    $("#txtVesselFor").html("<option value=''>No vessels found</option>");
                }
                $('#pklModal').modal('show');
                $.ajax({
                    url: "<?php echo base_url('report/getPKL/'); ?>/" + idPerson,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            var crew = response.crew;

                            $('#seafarerName').text(crew.fullname || '-');
                            $('#txtDoBInput').val(crew.dob || '');
                            $('#txtPoB').html('Place of Birth / Tempat Lahir : <b>' + (crew
                                .pob || '-') + '</b>');;
                            $('#txtSeafarerCodeInput').val(crew.kodepelaut || '');
                            $('#txtAddressInput').val(crew.address || '');
                            $("#txtRank").text(crew.rankName || '-');
                            $("#txtRankOther").text(crew.rankName || '-');
                            $('#txtPassportNoInput').val(crew.passportno || '');
                            $('#txtSeamanBookNoInput').val(crew.seamanbookno || '');

                            $('#txtSeafarerNameSignature').html('<b>' + (crew
                                .fullname || '-') + '</b>');

                            if (crew.vesselfor) {
                                $('#txtVesselFor').val(crew.vesselfor).trigger('change');
                            }

                            $('#pklLoading').hide();
                            $('#pklData').show();
                        } else {
                            alert('Crew data not found');
                            $('#pklLoading').hide();
                            $('#pklData').show();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        alert('Error loading data');
                        $('#pklLoading').hide();
                        $('#pklData').show();
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
                $("#txtVesselFor").html("<option value=''>Error loading vessels</option>");
            }
        });
    }

    $(document).ready(function() {
        $('#duration').on('input', function() {
            $('#durationText').text($(this).val() || '____');
        });
    });

    function cetakWagesCrew() {
        var idPerson = $("#txtIdPerson").val();

        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }

        $("#wagesModal").modal('show');
        $('#wagesLoading').show();
        $('#wagesContent').hide();

        $.ajax({
            url: "<?php echo base_url('report/getWages/'); ?>/" + idPerson,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#wagesLoading').hide();
                $('#wagesContent').show();

                if (!response.success) {
                    alert('Crew data not found');
                    return;
                }

                let p = response.personal;
                let c = response.contracts[0];

                let html = $("#wagesTemplate").html();

                html = html.replaceAll("<<Nama>>", `${p.fname} ${p.mname || ''} ${p.lname || ''}`);
                html = html.replaceAll("<<Rank>>", c.signonrank || "-");
                html = html.replaceAll("<<Kapal>>", c.signonvsl || "-");
                html = html.replaceAll("<<Sign On Date>>", c.signondt || "-");
                html = html.replaceAll("<<Port>>", c.signonport || "-");
                html = html.replaceAll("<<Masa Layar>>", c.signoffdt || "-");

                html = html.replaceAll("<<Basic Wages>>",
                    `<input id='basic' type='number' value='0' style='width:90%;text-align:center;border:1px solid #ccc;'>`
                );
                html = html.replaceAll("<<FOT>>",
                    `<input id='fot' type='number' value='0' style='width:90%;text-align:center;border:1px solid #ccc;'>`
                );
                html = html.replaceAll("<<Tanker Allowance>>",
                    `<input id='tanker' type='number' value='0' style='width:90%;text-align:center;border:1px solid #ccc;'>`
                );
                html = html.replaceAll("<<Leave Pay Nett>>",
                    `<input id='leave' type='number' value='0' style='width:90%;text-align:center;border:1px solid #ccc;'>`
                );
                html = html.replaceAll("<<BS>>",
                    `<input id='bs' type='number' value='0' style='width:90%;text-align:center;border:1px solid #ccc;'>`
                );
                html = html.replaceAll("<<HS>>",
                    `<input id='hs' type='number' value='0' style='width:90%;text-align:center;border:1px solid #ccc;'>`
                );
                html = html.replaceAll("<<Total Pay>>",
                    `<span id='totalPay'>0</span>`);

                html = html.replaceAll("<<Nama Telp Darurat>>", p.famfullname || "-");
                html = html.replaceAll("<<Hubungan Telp Darurat>>", p.famrelateid || "-");
                html = html.replaceAll("<<Telp Darurat>>", p.famtelp || p.fammobile || "-");

                $("#wagesContent").html(html);

                // hitung total pay realtime
                $("#basic, #fot, #tanker, #leave, #bs, #hs").on("input", function() {
                    let basic = parseFloat($("#basic").val()) || 0;
                    let fot = parseFloat($("#fot").val()) || 0;
                    let tanker = parseFloat($("#tanker").val()) || 0;
                    let leave = parseFloat($("#leave").val()) || 0;
                    let bs = parseFloat($("#bs").val()) || 0;
                    let hs = parseFloat($("#hs").val()) || 0;

                    let total = basic + fot + tanker + leave + bs + hs;
                    $("#totalPay").text(formatNumber(total));
                });
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Error loading data');
                $('#wagesLoading').hide();
                $('#wagesContent').show();
            }
        });
    }

    function cetakSPJCrew() {
        var idPerson = $("#txtIdPerson").val();

        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }

        $("#btnSPJCrew").prop("disabled", true).text("Loading...");

        $("#modalSPJ").modal('show');

        $.ajax({
            url: "<?php echo base_url('report/getSPJ'); ?>",
            type: "POST",
            data: {
                idperson: idPerson
            },
            dataType: "json",
            success: function(response) {

                $("#btnSPJCrew").prop("disabled", false).html('<i class="fa fa-print"></i> Print SPJ');

                if (!response || (!response.fullname && !response.rank)) {
                    alert("Crew data not found!");
                    return;
                }

                $("#crew_name").val(response.fullname || "");
                $("#crew_rank").val(response.rank || "");
                $("#purpose").val(response.vessel ? "Sign on to " + response.vessel : "");

                $("#sign_name").val(response.fullname || "");
                $("#sign_rank").val(response.rank || "");

                $("#destination, #depart_date, #arrival_date, #transportation, #note, #accompany").val("");
                $("#base_on").val("Kepentingan Perusahaan");

                if (response.vessel) {
                    $("#vessel_name_cc").text(response.vessel);
                } else {
                    $("#vessel_name_cc").text("[Nama Kapal]");
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert("Error loading data!");
                $("#btnSPJCrew").prop("disabled", false).html('<i class="fa fa-print"></i> Print SPJ');
            }
        });
    }

    $(document).ready(function() {

        $("#addAccompany").on("click", function() {
            var newRow = `
                <tr>
                    <td style="border:1px solid #000; padding:6px;">
                        <input type="text" name="sign_name[]" class="form-control"
                            placeholder="Nama pengikut" style="width:90%; border:1px solid #ccc; padding:4px;">
                    </td>
                    <td style="border:1px solid #000; padding:6px; display:flex; align-items:center; justify-content:center; gap:6px;">
                        <input type="text" name="sign_rank[]" class="form-control"
                            placeholder="Rank pengikut" style="width:85%; border:1px solid #ccc; padding:4px;">
                        <button type="button" class="removeRow"
                            style="border:none; background:#dc3545; color:#fff; font-weight:bold; font-size:16px; width:28px; height:28px; border-radius:4px; cursor:pointer;">−</button>
                    </td>
                </tr>
            `;
            $("#accompanyWrapper").append(newRow);
        });

        $(document).on("click", ".removeRow", function() {
            $(this).closest("tr").remove();
        });

        $(document).on("click", "#btnSaveSPJ", function() {
            let idPerson = $("#txtIdPerson").val();
            if (!idPerson) {
                alert("⚠️ ID Person kosong!");
                return false;
            }

            let spjData = {
                idperson: idPerson,
                base_on: $("#base_on").val(),
                name: $("#crew_name").val(),
                rank: $("#crew_rank").val(),
                destination: $("#destination").val(),
                purpose: $("#purpose").val(),
                depart_date: $("#depart_date").val(),
                arrival_date: $("#arrival_date").val(),
                transportation: $("#transportation").val(),
                note: $("#note").val(),
            };

            let names = [];
            $("#signatureTable").find('input[name="sign_name[]"]').each(function() {
                names.push($(this).val().trim());
            });
            let ranks = [];
            $("#signatureTable").find('input[name="sign_rank[]"]').each(function() {
                ranks.push($(this).val().trim());
            });

            let accompanyArr = [];
            $("#accompanyWrapper tr").each(function() {
                let nm = $(this).find('input[name="sign_name[]"]').val().trim();
                let rk = $(this).find('input[name="sign_rank[]"]').val().trim();
                if (nm !== "" || rk !== "") {
                    accompanyArr.push({
                        name: nm,
                        rank: rk
                    });
                }
            });

            spjData.accompany = accompanyArr;

            $.ajax({
                url: "<?php echo base_url('spj/saveSPJ'); ?>",
                type: "POST",
                dataType: "json",
                contentType: "application/json",
                processData: false,
                data: JSON.stringify(spjData),
                beforeSend: function() {
                    $("#btnSaveSPJ").prop("disabled", true).text("Saving...");
                },
                success: function(res) {
                    $("#btnSaveSPJ").prop("disabled", false).text("Save & Print");
                    if (res.success) {
                        alert("✅ " + res.message);
                        window.open("<?php echo base_url('spj/getSpj/'); ?>" + "/" + res
                            .spj_id,
                            "_blank");
                        $("#modalSPJ").hide();
                    } else {
                        alert("⚠️ " + res.message);
                    }
                },
                error: function(xhr, status, error) {
                    $("#btnSaveSPJ").prop("disabled", false).text("Save & Print");
                    console.error(error);
                    alert("❌ Terjadi kesalahan saat menyimpan SPJ.");
                }
            });

        });
    });

    function cetakDataBankCrew() {
        var idPerson = $("#txtIdPerson").val();

        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }

        $("#btnStatementCrew").prop("disabled", true).text("Loading...");

        $.ajax({
            url: "<?php echo base_url('report/getDataBankCrew'); ?>",
            type: "POST",
            data: {
                idperson: idPerson
            },
            dataType: "json",
            success: function(response) {
                $("#btnStatementCrew").prop("disabled", false).html(
                    '<i class="fa fa-print"></i> Print DataBank');

                if (!response || (!response.fullname && !response.address)) {
                    alert("Crew data not found!");
                    return;
                }

                $("#modalDataBank").modal('show');

                $("#namaCrew").val(response.fullname || "-");
                $("#npwp").val(response.npwp || "-");
                $("#alamatRumah").val(response.address || "-");
                $("#telp").val(response.phone || "-");
                $("#telpDarurat").val(response.emergency_phone || "-");
                $("#hubungan").val(response.relation || "-");
                $("#bank").val(response.bank_name || "-");
                $("#noRekening").val(response.bank_account || "-");
                $("#pemilikRekening").val(response.account_name || "-");
                $("#alamatBank").val(response.bank_address || "");

                var today = new Date().toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric'
                });
                $("#tanggal").text("Jakarta, " + today);
                $("#namaCrewFooter").text(response.fullname || "-");
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert("Error loading data!");
                $("#btnStatementCrew").prop("disabled", false).html(
                    '<i class="fa fa-print"></i> Print DataBank');
            }
        });
    }

    function cetakIntroduction() {

        var idperson = $("#txtIdPerson").val();

        if (idperson == "") {
            alert("Id Person Kosong!");
            return;
        }

        $.ajax({
            url: "<?php echo base_url('report/getIntroductionData'); ?>",
            type: "POST",
            data: {
                idperson: idperson
            },
            dataType: "json",
            success: function(res) {

                if (!res.success) {
                    alert("Data tidak ditemukan!");
                    return;
                }

                $("#introModal").modal("show");

                $(".entitas-fill").text(res.entitas);
                $(".kapal-fill").text(res.vessel_name);
                $(".tanggal-fill").text(res.today);
                $(".port-fill").text(res.port);

                $("#hid_entitas").val(res.entitas);
                $("#hid_vessel").val(res.vessel_name);
                $("#hid_port").val(res.port);
                $("#hid_tanggal").val(res.today);

                window.selectedCrewName = res.fullname;
                window.selectedRankName = res.rankname;

                $("input[name='nama_release[]']").val(res.fullname);
                $("input[name='jabatan_release[]']").val(res.rankname);

                $("input[name='nama_successor[]']").val(res.fullname);
                $("input[name='jabatan_successor[]']").val(res.rankname);

            },
            error: function(err) {
                alert("Error fetching data.");
                console.log(err);
            }
        });
    }


    $(document).ready(function() {

        function refreshReleaseMinusButton() {
            let rows = $("#releaseTable tr.release-row");

            rows.find(".btnDeleteRelease").hide();

            if (rows.length > 1) {
                rows.slice(1).find(".btnDeleteRelease").show();
            }
        }

        $("#btnAddReleaseRow").click(function() {
            let newRow = `
            <tr class="release-row">
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="nama_release[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="jabatan_release[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="reason_release[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="otherRelease[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000; width:40px; text-align:center;">
                    <button type="button" class="btn btn-danger btn-sm btnDeleteRelease" 
                        style="padding:2px 6px; display:none;">-</button>
                </td>
            </tr>`;

            $("#releaseTable").append(newRow);
            refreshReleaseMinusButton();
        });

        $(document).on("click", ".btnDeleteRelease", function() {
            $(this).closest("tr").remove();
            refreshReleaseMinusButton();
        });

        function refreshSuccessorMinusButton() {
            let rows = $("#successorTable tr.successor-row");

            rows.find(".btnDeleteSuccessor").hide();

            if (rows.length > 1) {
                rows.slice(1).find(".btnDeleteSuccessor").show();
            }
        }

        $("#btnAddSuccessorRow").click(function() {
            let newRowSuccessor = `
            <tr class="successor-row">
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="nama_successor[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="jabatan_successor[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="bs_successor[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="ot_successor[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="leavepay_successor[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000;">
                    <input type="text" name="othersSuccessor[]" class="form-control" style="border:none; height:38px;">
                </td>
                <td style="padding:0; border:1px solid #000; width:40px; text-align:center;">
                    <button type="button" class="btn btn-danger btn-sm btnDeleteSuccessor" 
                        style="padding:2px 6px; display:none;">-</button>
                </td>
            </tr>`;


            $("#successorTable").append(newRowSuccessor);
            refreshSuccessorMinusButton();
        });

        $(document).on("click", ".btnDeleteSuccessor", function() {
            $(this).closest("tr").remove();
            refreshSuccessorMinusButton();
        });

    });

    $(document).on("click", "#btnSaveAndPrintStatement", function() {
        var idPerson = $("#txtIdPerson").val();

        if (!idPerson) {
            alert("⚠️ ID Person kosong!");
            return false;
        }

        let data = {
            idperson: idPerson,
            status_data_bank: $("#statusBank").val(),
            fullname: $("#namaCrew").val(),
            npwp: $("#npwp").val(),
            address: $("#alamatRumah").val(),
            phone: $("#telp").val(),
            emergency_phone: $("#telpDarurat").val(),
            relation: $("#hubungan").val(),
            bank_name: $("#bank").val(),
            bank_account: $("#noRekening").val(),
            account_name: $("#pemilikRekening").val(),
            bank_address: $("#alamatBank").val()
        };

        $.ajax({
            url: "<?php echo base_url('statement/saveStatementCrew'); ?>",
            type: "POST",
            dataType: "json",
            data: data,
            beforeSend: function() {
                $("#btnSaveAndPrintStatement").prop("disabled", true).text("Saving...");
            },
            success: function(res) {
                $("#btnSaveAndPrintStatement").prop("disabled", false).text("Print");

                if (res.success) {
                    alert("✅ " + res.message);
                    $("#modalDataBank").modal("hide");

                    window.open("<?php echo base_url('statement/getDataStatement/'); ?>" + "/" +
                        idPerson, "_blank");
                } else {
                    alert("⚠️ " + res.message);
                }
            },
            error: function(xhr, status, error) {
                $("#btnSaveAndPrintStatement").prop("disabled", false).text("Print");
                console.error(error);
                alert("❌ Terjadi kesalahan saat menyimpan Statement Crew.");
            }
        });
    });

    $(document).on("click", "#btnSaveAndPrintIntroduction", function() {

        var idPerson = $("#txtIdPerson").val();

        if (!idPerson) {
            alert("ID Person kosong!");
            return;
        }

        let dataSend = {
            idperson: idPerson,
            entitas: $("#hid_entitas").val(),
            vessel_name: $("#hid_vessel").val(),
            port: $("#hid_port").val(),
            tanggal: $("#hid_tanggal").val(),

            release_header_others: $("input[name='release_header_others']").val(),
            successor_header_others: $("input[name='successor_header_others']").val(),
        };

        let release = [];
        $("tr.release-row").each(function() {
            release.push({
                nama: $(this).find("input[name='nama_release[]']").val(),
                jabatan: $(this).find("input[name='jabatan_release[]']").val(),
                reason: $(this).find("input[name='reason_release[]']").val(),
                others: $(this).find("input[name='otherRelease[]']").val()
            });
        });
        dataSend.release = JSON.stringify(release);

        let successor = [];
        $("tr.successor-row").each(function() {
            successor.push({
                nama: $(this).find("input[name='nama_successor[]']").val(),
                jabatan: $(this).find("input[name='jabatan_successor[]']").val(),
                bs: $(this).find("input[name='bs_successor[]']").val(),
                ot: $(this).find("input[name='ot_successor[]']").val(),
                leavepay: $(this).find("input[name='leavepay_successor[]']").val(),
                others: $(this).find("input[name='othersSuccessor[]']").val()
            });
        });
        dataSend.successor = JSON.stringify(successor);

        $.ajax({
            url: "<?php echo base_url('introduction/saveIntroduction'); ?>",
            type: "POST",
            data: dataSend,
            dataType: "json",
            beforeSend: function() {
                $("#btnSaveAndPrintIntroduction").prop("disabled", true).text("Saving...");
            },
            success: function(res) {
                $("#btnSaveAndPrintIntroduction").prop("disabled", false).text("Print");

                if (res.success) {
                    alert("✓ " + res.message);
                    $("#introModal").modal("hide");

                    window.open(
                        "<?php echo base_url('introduction/getIntroduction/'); ?>" + "/" + res
                        .id,
                        "_blank"
                    );
                } else {
                    alert("⚠️ " + res.message);
                }
            },
            error: function(err) {
                $("#btnSaveAndPrintIntroduction").prop("disabled", false).text("Print");
                alert("❌ Terjadi kesalahan saat menyimpan Introduction.");
            }
        });
    });

    function formatNumber(num) {
        return new Intl.NumberFormat('en-US', {
            minimumFractionDigits: 0
        }).format(num);
    }

    $(document).on("change", "#txtVesselFor", function() {
        var selected = $(this).val();
        if (!window.vesselData) return;

        var vessel = window.vesselData.find(v => v.kdvsl === selected);

        if (vessel) {
            $("#txtIMO").val(vessel.imo || "");
            $("#txtGRT").val(vessel.grt || "");
            $("#txtSafetyCert").val(vessel.serpel || "");

            $("#txtShippingCompanyName").html("<b>" + (vessel.nmcmp || "-") + "</b>");
            $("#txtShippingCompanyNameIndonesia").html("<b>" + (vessel.nmcmp || "-") + "</b>");
            $("#txtShippingCompanyNameTTD").html("<b>" + (vessel.nmcmp || "-") + "</b>");
            $("#txtCompanyName").html("<b>" + (vessel.nmcmp || "-") + "</b>");

            if (vessel.st_own === "Y") {
                $("#txtCrewingPosition").html("CREWING MANAGER");
            } else {
                $("#txtCrewingPosition").html("HEAD OF CREWING DIVISION");
            }

        } else {
            $("#txtIMO").val("");
            $("#txtGRT").val("");
            $("#txtSafetyCert").val("");
            $("#txtVesselForLabel").html("");
        }
    });

    function cetakStatement() {
        let idPerson = $("#txtIdPerson").val();

        if (!idPerson) {
            alert("ID Person tidak ditemukan!");
            return;
        }

        $.ajax({
            url: "<?php echo base_url('report/getDataStatement'); ?>",
            type: "POST",
            data: {
                idperson: idPerson
            },
            dataType: "json",
            success: function(res) {

                if (res.status) {

                    let d = res.data;

                    $("#txtNameCrew").text(d.nama_crew || "-");
                    $("#txtNameCrewStatement").text(d.nama_crew || "-");
                    $("#txtStatementDate").text(d.tanggal_statement || "-");
                    $("#txtKapal").text(d.nama_kapal || "-");
                    $("#txtRankStatement").text(d.nama_rank || "-");
                    $("#txtCrewNameStatement").text(d.nama_crew || "-");
                    $("#modalStatement").modal("show");

                } else {
                    alert(res.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Terjadi kesalahan AJAX!");
            }
        });
    }

    $(document).on("click", "#btnSaveAndPrintStatementCrew", function() {
        let idPerson = $("#txtIdPerson").val();

        window.open("<?php echo base_url('statementEmploy/printStatementCrew/') ?>/" + idPerson, "_blank");
    });

    function cetakAcceptence() {
        let idPerson = $("#txtIdPerson").val();

        if (!idPerson) {
            alert("ID Person tidak ditemukan!");
            return;
        }

        $.ajax({
            url: "<?php echo base_url('report/acceptence'); ?>",
            type: "POST",
            data: {
                idperson: idPerson
            },
            dataType: "json",
            success: function(res) {

                if (res.status) {

                    let d = res.data;

                    $("#stmName").text(d.nama_crew || "-");
                    $("#stmDob").text(d.tanggal_lahir || "-");
                    $("#stmRank").text(d.nama_rank || "-");
                    $("#stmNameFooter").text(d.nama_crew || "-");
                    $("#stmTanggal").text(res.today || "-");
                    $("#stmSerpel").text(d.serpel || "-");
                    $("#modalStatementContract").modal("show");

                } else {
                    alert(res.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Terjadi kesalahan AJAX!");
            }
        });
    }


    $(document).on("click", "#btnPrintStatement", function() {
        let idPerson = $("#txtIdPerson").val();

        window.open("<?php echo base_url('report/acceptence_pdf/') ?>/" + idPerson, "_blank");
    });

    function cetakCovid19() {

        let idPerson = $("#txtIdPerson").val();

        if (!idPerson) {
            alert("ID Person tidak ditemukan!");
            return;
        }

        $.ajax({
            url: "<?php echo base_url('report/getCovid19Data'); ?>",
            type: "POST",
            data: {
                idperson: idPerson
            },
            dataType: "json",
            success: function(res) {

                if (res.status) {

                    let d = res.data;

                    $("#txtNameCovid").text(d.fullname || "-");
                    $("#txtRankCovid").text(d.rankname || "-");
                    $("#txtDateCovid").text(res.today || "-");
                    $("#modalCovid").modal("show");

                } else {
                    alert(res.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert("Terjadi kesalahan AJAX!");
            }
        });
    }

    $(document).on("click", "#btnPrintPrevention", function() {
        let idPerson = $("#txtIdPerson").val();

        window.open("<?php echo base_url('report/printPrevention/') ?>/" + idPerson, "_blank");
    });

    function cetakLetter() {

        let idPerson = $("#txtIdPerson").val();

        if (!idPerson) {
            alert("ID Person tidak ditemukan!");
            return;
        }


        $.ajax({
            url: "<?php echo base_url("report/getStatementCrew"); ?>",
            type: "POST",
            data: {
                idperson: idPerson
            },
            dataType: "json",
            success: function(res) {

                if (!res.success) {
                    alert(res.message);
                    return;
                }

                let d = res.data;

                $("#nmCrew").text(": " + d.fullname);
                $("#namaCrewSuratPernyataan").text(d.fullname);
                $("#tmptTglLahir").text(": " + d.place_of_birth + ", " + d.date_of_birth);
                $("#jabatanNamaKapal").text(": " + d.rankname + " / " + d.vesselnm);
                $("#passport").text(": " + d.passport_no);
                $("#txtDuration").text(d.duration);
                $("#tanggalSuratPernyataan").text(res.today);
                $("#modalSuratPernyataan").modal("show");
            },
            error: function() {
                alert("Terjadi kesalahan pada server.");
            }
        });
    }

    $(document).on("click", "#printCrewStatement", function() {
        let idPerson = $("#txtIdPerson").val();

        window.open("<?php echo base_url('report/printStatement/') ?>/" + idPerson, "_blank");
    });

    function cetakSeafarerContract() {
        var idPerson = $("#txtIdPerson").val();

        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }

        $("#legalModalSeafarerContract").modal('show');
    }

    function transmital() {
        var idPerson = $("#txtIdPerson").val();
        if (idPerson == "") {
            alert("Person Empty..!!!");
            return false;
        }
        window.open("<?php echo base_url('report/transmital');?>/" + idPerson + "/",
            "_blank");
    }

    function reloadPage() {
        window.location = "<?php echo base_url('report/');?>";
    }

    function saveDataTrainEvaluation() {
        var formData = new FormData();
        formData.append("txtIdEditTrain", $("#txtIdEditTrain").val());
        formData.append("txtIdPerson", $("#txtIdPerson").val());
        formData.append("txtemployeeName", $("#txtemployeeName").val());
        formData.append("txtdesignation", $("#txtdesignation").val());
        formData.append("txtDateOfTraining", $("#txtDateOfTraining").val());
        formData.append("txtplaceOfTraining", $("#txtplaceOfTraining").val());
        formData.append("txtsubject", $("#txtsubject").val());
        formData.append("txtDateOfEvaluation", $("#txtDateOfEvaluation").val());
        formData.append("txtevaluator", $("#txtevaluator").val());
        formData.append("suggestion", $("#suggestion").val());
        formData.append("advise", $("#advise").val());

        $("input[name='score1']:checked").each(function() {
            formData.append("score1[]", $(this).val());
        });
        $("input[name='score2']:checked").each(function() {
            formData.append("score2[]", $(this).val());
        });
        $("input[name='score3']:checked").each(function() {
            formData.append("score3[]", $(this).val());
        });
        $("input[name='score4']:checked").each(function() {
            formData.append("score4[]", $(this).val());
        });

        $("#idLoading").show();

        $.ajax({
            url: "<?php echo base_url('report/saveDataTrainEvaluation'); ?>",
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                $("#idLoading").hide();

                if (response.status === "success") {
                    alert(response.message);

                    let idperson = $("#txtIdPerson").val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('report/getTrainingEvaluation'); ?>",
                        data: {
                            idperson: idperson
                        },
                        dataType: "json",
                        success: function(response) {
                            $("#idTbodyTraining").html(response.trTraining);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching updated data:", error);
                        }
                    });

                    $("#trainingForm")[0].reset();
                    $("#formContainer").css("opacity", "0");
                    setTimeout(() => {
                        $("#formContainer").hide();
                        $("#tableContainer").show();
                        setTimeout(() => {
                            $("#tableContainer").css("opacity", "1");
                        }, 50);
                    }, 300);

                    setTimeout(() => {
                        $("#btnAdd").show();
                        setTimeout(() => {
                            $("#btnAdd").css("opacity", "1");
                        }, 50);
                    }, 300);
                } else {
                    alert("Error: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                alert("Error: " + xhr.status + " - " + xhr.statusText);
                $("#idLoading").hide();
            }
        });
    }

    function saveDataCrewEvaluation() {
        var formData = new FormData();
        var idPerson = $("#txtIdPerson").val();
        var txtIdEditCrew = $("#txtIdEditCrew").val();

        function validateDate(id) {
            let date = $("#" + id).val();
            return date && date !== "0000-00-00" ? date : null;
        }

        formData.append("txtIdEditCrew", txtIdEditCrew || '');
        formData.append("txtIdPerson", idPerson || '');
        formData.append("txtVessel", $("#slcVesselHeader").val() || '');
        formData.append("txtSeafarerName", $("#txtSeafarerName").val() || '');
        formData.append("txtRank", $("#slcRankHeader").val() || '');
        formData.append("txtDateOfReport", validateDate("txtDateOfReport") || '');
        formData.append("txtDateReportingPeriodFrom", validateDate("txtDateReportingPeriodFrom") || '');
        formData.append("txtDateReportingPeriodTo", validateDate("txtDateReportingPeriodTo") || '');

        formData.append("reasonMidway", $("#reasonMidway").is(":checked") ? 'Y' : '');
        formData.append("reasonSigningOff", $("#reasonSigningOff").is(":checked") ? 'Y' : '');
        formData.append("reasonLeaving", $("#reasonLeaving").is(":checked") ? 'Y' : '');
        formData.append("reasonSpecialRequest", $("#reasonSpecialRequest").is(":checked") ? 'Y' : '');

        formData.append("txtMasterComments", $("#txtMasterComments").val() || '');
        formData.append("txtOfficerComments", $("#txtOfficerComments").val() || '');
        formData.append("txtPromoted", $("input[name='txtPromoted']:checked").val() || 'N');
        formData.append("txtReemploy", $("input[name='txtReemploy']:checked").val() || 'N');
        formData.append("txtfullname", $("#txtfullname").val() || '');
        formData.append("txtreceived", $("#txtreceived").val() || '');
        formData.append("txtmastercoofullname", $("#txtmastercoofullname").val() || '');
        formData.append("slcRank", $("#slcRank").val() || '');
        formData.append("txtDateReceipt", validateDate("txtDateReceipt") || '');

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
            formData.append("txtIdentify" + criteriaId.charAt(0).toUpperCase() + criteriaId.slice(1), $(
                "#txtIdentify" +
                criteriaId.charAt(0).toUpperCase() + criteriaId.slice(1)).val() || '');
        }

        $("#idLoading").show();

        $.ajax({
            url: "<?php echo base_url('report/saveDataCrewEvaluation'); ?>?_=" + new Date().getTime(),
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                $("#idLoading").hide();
                if (response.status === "success") {
                    alert(response.message);

                    let idperson = $("#txtIdPerson").val();
                    $.ajax({
                        type: "POST",
                        url: "<?php echo base_url('report/getCrewEvaluation'); ?>",
                        data: {
                            idperson: idperson
                        },
                        dataType: "json",
                        success: function(response) {
                            $("#idTbodyCrewEvaluation").html(response.trCrewEvaluation);
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching updated data:", error);
                        }
                    });

                    $("#crewForm")[0].reset();
                    $("#formContainerCrew").css("opacity", "0");
                    setTimeout(() => {
                        $("#formContainerCrew").hide();
                        $("#tableContainerCrew").show();
                        setTimeout(() => {
                            $("#tableContainerCrew").css("opacity", "1");
                        }, 50);
                    }, 300);

                    setTimeout(() => {
                        $("#btnAddCrewEvaluation").show();
                        setTimeout(() => {
                            $("#btnAddCrewEvaluation").css("opacity", "1");
                        }, 50);
                    }, 300);
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
        return false;
    }

    $(document).on("click", "#btnPrintReport", function() {
        getCrewEvaluation();
    });

    function getCrewEvaluation() {
        var idPerson = $("#txtIdPerson").val();

        $("#idTbodyCrewEvaluation").html('<tr><td colspan="8">Loading...</td></tr>');

        $.ajax({
            url: "<?php echo base_url('report/getCrewEvaluation'); ?>?_=" + new Date().getTime(),
            method: "POST",
            data: {
                idperson: idPerson
            },
            dataType: "json",
            cache: false,
            success: function(response) {
                let htmlContent = response.trCrewEvaluation ||
                    '<tr><td colspan="8">No evaluation data found</td></tr>';
                $("#idTbodyCrewEvaluation").html(htmlContent);
            },
            error: function(xhr, status, error) {
                console.error("Error:", xhr.responseText);
                $("#idTbodyCrewEvaluation").html('<tr><td colspan="8">Error loading data</td></tr>');
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function() {
        let btnAddCrewEvaluation = document.getElementById("btnAddCrewEvaluation");
        let btnCancelFormCrew = document.getElementById("btnCancelFormCrew");

        if (btnAddCrewEvaluation) {
            btnAddCrewEvaluation.addEventListener("click", function() {
                let tableCrew = document.getElementById("tableContainerCrew");
                let formCrew = document.getElementById("formContainerCrew");

                document.getElementById("crewForm").reset();

                btnAddCrewEvaluation.style.opacity = "0";
                setTimeout(() => {
                    btnAddCrewEvaluation.style.display = "none";
                }, 300);

                tableCrew.style.opacity = "0";
                setTimeout(() => {
                    tableCrew.style.display = "none";
                    formCrew.style.display = "block";
                    setTimeout(() => {
                        formCrew.style.opacity = "1";
                    }, 50);
                }, 300);
            });
        }

        if (btnCancelFormCrew) {
            btnCancelFormCrew.addEventListener("click", function() {
                let tableCrew = document.getElementById("tableContainerCrew");
                let formCrew = document.getElementById("formContainerCrew");

                formCrew.style.opacity = "0";
                setTimeout(() => {
                    formCrew.style.display = "none";
                    tableCrew.style.display = "block";
                    setTimeout(() => {
                        tableCrew.style.opacity = "1";
                    }, 50);
                }, 300);

                setTimeout(() => {
                    btnAddCrewEvaluation.style.display = "block";
                    setTimeout(() => {
                        btnAddCrewEvaluation.style.opacity = "1";
                    }, 50);
                }, 300);
            });
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        let btnAdd = document.getElementById("btnAdd");
        let btnCancel = document.getElementById("btnCancel");

        if (btnAdd) {
            btnAdd.addEventListener("click", function() {
                let table = document.getElementById("tableContainer");
                let form = document.getElementById("formContainer");

                document.getElementById("trainingForm").reset();

                btnAdd.style.opacity = "0";
                setTimeout(() => {
                    btnAdd.style.display = "none";
                }, 300);

                table.style.opacity = "0";
                setTimeout(() => {
                    table.style.display = "none";
                    form.style.display = "block";
                    setTimeout(() => {
                        form.style.opacity = "1";
                    }, 50);
                }, 300);
            });
        }

        if (btnCancel) {
            btnCancel.addEventListener("click", function() {
                let table = document.getElementById("tableContainer");
                let form = document.getElementById("formContainer");

                form.style.opacity = "0";
                setTimeout(() => {
                    form.style.display = "none";
                    table.style.display = "block";
                    setTimeout(() => {
                        table.style.opacity = "1";
                    }, 50);
                }, 300);

                setTimeout(() => {
                    btnAdd.style.display = "block";
                    setTimeout(() => {
                        btnAdd.style.opacity = "1";
                    }, 50);
                }, 300);
            });
        }
    });

    $(document).ready(function() {
        $("#btnPrintTraining").on("click", function() {
            let idperson = $("#txtIdPerson").val();

            if (idperson) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url('report/getTrainingEvaluation'); ?>",
                    data: {
                        idperson: idperson
                    },
                    dataType: "json",
                    success: function(response) {
                        $("#idTbodyCrewEvaluation").html(response.trTraining);
                        $("#trainingEvaluationModal").modal("show");
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                    }
                });
            } else {
                alert("Please select an employee first.");
            }
        });
    });

    function setCheckboxValue(name, value) {
        $('input[name="' + name + '"]').each(function() {
            if ($(this).val() == value) {
                $(this).prop('checked', true);
            } else {
                $(this).prop('checked', false);
            }
        });
    }

    function editData(id) {
        $.ajax({
            url: '<?php echo base_url('report/getDataEdit'); ?>',
            type: 'POST',
            data: {
                txtIdEditTrain: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    $('#txtIdEditTrain').val(id);
                    $('#txtemployeeName').val(response.employeeName);
                    $('#txtdesignation').val(response.designation);
                    $('#txtDateOfTraining').val(response.dateOfTraining);
                    $('#txtplaceOfTraining').val(response.placeOfTraining);
                    $('#txtsubject').val(response.subject);
                    $('#txtDateOfEvaluation').val(response.dateOfEvaluation);
                    $('#txtevaluator').val(response.evaluatorNameDesignation);
                    $('#suggestion').val(response.training_material_suggestion);
                    $('#advise').val(response.future_training_expectation);


                    setCheckboxValue('score1', response.employee_job_understanding);
                    setCheckboxValue('score2', response.quality_productivity_skill);
                    setCheckboxValue('score3', response.initiative_and_ideas);
                    setCheckboxValue('score4', response.general_performance);

                    let table = document.getElementById("tableContainer");
                    let form = document.getElementById("formContainer");
                    let btnAdd = document.getElementById("btnAdd");

                    table.style.opacity = "0";
                    setTimeout(() => {
                        table.style.display = "none";
                        form.style.display = "block";
                        setTimeout(() => {
                            form.style.opacity = "1";
                        }, 50);
                    }, 300);

                    setTimeout(() => {
                        btnAdd.style.opacity = "0";
                        setTimeout(() => {
                            btnAdd.style.display = "none";
                        }, 50);
                    }, 300);
                    $("#btnAdd").hide();
                    $("#tableContainer").hide();
                } else {
                    alert("Gagal mengambil data.");
                }
            },
            error: function() {
                alert("Terjadi kesalahan saat mengambil data.");
            }
        });
    }

    function editDataCrewEvaluation(id) {
        $.ajax({
            url: '<?php echo base_url('report/getDataEditCrewEvaluation'); ?>',
            type: 'POST',
            data: {
                id: id
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === "success") {
                    $('#txtIdEditCrew').val(id);
                    $('#txtIdPerson').val(response.report.idperson);

                    console.log('idperson:', response.report.idperson);
                    console.log('txtIdPerson value:', $('#txtIdPerson').val());

                    $('#slcVesselHeader').val(response.report.vessel);
                    $('#txtSeafarerName').val(response.report.seafarer_name);
                    $('#slcRankHeader').val(response.report.rank);
                    $('#txtDateOfReport').val(response.report.date_of_report);
                    $('#ttxtDateReportingPeriodFrom').val(response.report.reporting_period_from);
                    $('#txtDateReportingPeriodTo').val(response.report.reporting_period_to);

                    $('#reasonMidway').prop('checked', response.report.reason_midway_contract === 'Y');
                    $('#reasonSigningOff').prop('checked', response.report.reason_signing_off === 'Y');
                    $('#reasonLeaving').prop('checked', response.report.reason_leaving_vessel === 'Y');
                    $('#reasonSpecialRequest').prop('checked', response.report
                        .reason_special_request ===
                        'Y');

                    $('#txtMasterComments').val(response.report.master_comments);
                    $('#txtOfficerComments').val(response.report.reporting_officer_comments);

                    $(`input[name="txtPromoted"][value="${response.report.promote}"]`).prop('checked',
                        true);
                    $(`input[name="txtReemploy"][value="${response.report.re_employ}"]`).prop('checked',
                        true);

                    $('#txtfullname').val(response.report.reporting_officer_name);
                    $('#txtreceived').val(response.report.received_by_cm);
                    $('#slcRank').val(response.report.reporting_officer_rank);
                    $("#txtmastercoofullname").val(response.report.mastercoofullname);
                    $('#txtDateReceipt').val(response.report.date_of_receipt);

                    const criteriaMapping = {
                        'Ability/Knowledge of Job': 'ability',
                        'Safety Consciousness': 'safety',
                        'Dependability & Integrity': 'integrity',
                        'Initiative': 'initiative',
                        'Conduct': 'conduct',
                        'Ability to get on with others': 'abilityGetOn',
                        'Appearance (+ uniforms)': 'appearance',
                        'Sobriety': 'sobriety',
                        'English Language': 'english',
                        'Leadership (Officers)': 'leadership'
                    };

                    Object.entries(criteriaMapping).forEach(([name, key]) => {
                        const criteria = response.criteria[name] || {};
                        const value = criteria.excellent === 'Y' ? 4 :
                            criteria.good === 'Y' ? 3 :
                            criteria.fair === 'Y' ? 2 :
                            criteria.poor === 'Y' ? 1 : '';

                        $(`input[name="${key}"][value="${value}"]`).prop('checked', true);

                        $(`#txtIdentify${key.charAt(0).toUpperCase() + key.slice(1)}`).val(
                            criteria
                            .identify || '');
                    });

                    toggleFormCrew(true);

                } else {
                    alert("Failed to load data: " + (response.message || 'Unknown error'));
                }
            },
            error: function(xhr) {
                alert("Error loading data. Status: " + xhr.status);
            }
        });
    }

    function toggleFormCrew(showForm) {
        if (showForm) {
            $("#btnAddCrewEvaluation").hide();
            $("#formContainerCrew").css("display", "block");
            $("#tableContainerCrew").css("opacity", "0");
            setTimeout(() => {
                $("#tableContainerCrew").hide();
                $("#formContainerCrew").css("opacity", "1");
            }, 50);
        } else {
            $("#formContainerCrew").css("opacity", "0");
            setTimeout(() => {
                $("#formContainerCrew").hide();
                $("#tableContainerCrew").show();
                $("#btnAddCrewEvaluation").show();
                setTimeout(() => {
                    $("#tableContainerCrew").css("opacity", "1");
                }, 50);
            }, 300);
        }
    }

    function updateRowNumbers() {
        $("#idTbodyTraining tr").each(function(index) {
            $(this).find("td:first").text(index + 1);
        });
    }

    function deleteData(id, idPerson) {
        if (confirm("Delete data...??")) {
            $("#idLoading").show();

            $.post('<?php echo base_url("report/delData"); ?>/', {
                id: id,
                idPerson: idPerson
            }, function(response) {
                $("#idLoading").hide();
                if (response.status === "Success") {
                    $(`tr[data-id="${id}"]`).remove();
                    updateRowNumbers();
                    alert("Data berhasil dihapus.");
                } else {
                    alert("Gagal menghapus data: " + response.message);
                }
            }, "json").fail(function(xhr) {
                $("#idLoading").hide();
                alert("Error: " + xhr.statusText);
            });
        }
    }

    function deleteDataCrewEvaluation(id, idPerson) {
        if (confirm("Delete this evaluation?")) {
            $.ajax({
                url: "<?php echo base_url('report/delDataCrewEvaluation'); ?>",
                method: "POST",
                data: {
                    id: id,
                    idPerson: idPerson
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "Success") {
                        $("tr[data-id='" + id + "']").remove();
                        $("#idTbodyCrewEvaluation tr").each(function(index) {
                            $(this).find("td:first").text(index + 1);
                        });
                    }
                    alert(response.message || "Deleted successfully");
                },
                error: function(xhr) {
                    alert("Error deleting data");
                    console.error(xhr.responseText);
                }
            });
        }
    }


    function ViewPrintCrewEvaluation(idPerson) {
        window.open("<?php echo base_url('report/exportPDFCrewEvaluation'); ?>/" + idPerson + "/",
            "_blank");
    }

    function loadPageDataReady(page = 1) {
        $('#tableDataReady').attr('data-current-page', page);

        const searchValue = $("#containerReady").attr('data-search');

        $.ajax({
            url: '<?php echo base_url("report/searchDataReady") ?>',
            type: 'GET',
            data: {
                page: page,
                search: searchValue
            },
            success: function(response) {
                $('#idTbodylistCrewNewModal').html(response);
                highlightSearchResults('idTbodylistCrewNewModal', searchValue);
            },
            error: function() {
                alert('Gagal mengambil data Ready.');
            }
        });
    }

    function deleteNewApplicant(id, idPerson) {
        if (confirm("Delete this applicant?")) {
            $.ajax({
                url: "<?php echo base_url('report/delNewApplicant'); ?>",
                method: "POST",
                data: {
                    id: id,
                    idPerson: idPerson
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === "Success") {
                        $("tr[data-id='" + id + "']").remove();
                        $("#idTbodylistCrewNewModal tr").each(function(index) {
                            $(this).find("td:first").text(index + 1);
                        });
                    }
                    alert(response.message || "Deleted successfully");
                },
                error: function(xhr) {
                    alert("Error deleting data");
                    console.error(xhr.responseText);
                }
            });
        }
    }

    function loadPageDataQualified(page = 1, sortBy = '', sortOrder = '') {
        $('#tableDataQualifiedCrew').attr('data-current-page', page);

        const searchValue = $("#containerQualifiedCrew").attr('data-search');

        if (sortBy && sortOrder) {
            $('#tableDataQualifiedCrew').attr('data-sort-by', sortBy);
            $('#tableDataQualifiedCrew').attr('data-sort-order', sortOrder);
        }

        $.ajax({
            url: '<?php echo base_url("report/searchDataQualifiedCrew") ?>',
            type: 'GET',
            data: {
                page: page,
                search: searchValue,
                sortBy: sortBy || $('#tableDataQualifiedCrew').attr('data-sort-by') || '',
                sortOrder: sortOrder || $('#tableDataQualifiedCrew').attr('data-sort-order') || ''
            },
            success: function(response) {
                $('#idTbodyQualifiedCrew').html(response);
                highlightSearchResults('idTbodyQualifiedCrew', searchValue);
                updateSortIndicators();
            },
            error: function() {
                alert('Gagal mengambil data Qualified Crew.');
            }
        });
    }

    function sortTable(column) {
        const currentSortBy = $('#tableDataQualifiedCrew').attr('data-sort-by');
        const currentSortOrder = $('#tableDataQualifiedCrew').attr('data-sort-order');

        let newSortOrder = 'ASC';

        if (currentSortBy === column) {
            newSortOrder = currentSortOrder === 'ASC' ? 'DESC' : 'ASC';
        }

        $('#tableDataQualifiedCrew').attr('data-sort-by', column);
        $('#tableDataQualifiedCrew').attr('data-sort-order', newSortOrder);

        $('.sort-indicator').html('');

        const arrow = newSortOrder === 'ASC' ? '▲' : '▼';
        $(`[data-column='${column}'] .sort-indicator`).html(
            `<span style="font-weight:bold;color:#fff;">${arrow}</span>`);

        loadPageDataQualified(1, column, newSortOrder);
    }

    function updateSortIndicators() {
        $('.sortable').removeClass('sort-asc sort-desc');

        const currentSortBy = $('#tableDataQualifiedCrew').attr('data-sort-by');
        const currentSortOrder = $('#tableDataQualifiedCrew').attr('data-sort-order');

        if (currentSortBy) {
            const header = $(`th[data-column="${currentSortBy}"]`);
            if (header.length) {
                header.addClass(currentSortOrder === 'ASC' ? 'sort-asc' : 'sort-desc');
            }
        }
    }

    function goToPageQualified() {
        const pageInput = document.getElementById('goToPageInput');
        if (!pageInput) return;

        let page = parseInt(pageInput.value);
        const maxPage = parseInt(pageInput.max);
        const minPage = parseInt(pageInput.min);

        if (isNaN(page) || page < minPage || page > maxPage) {
            alert(`Masukkan nomor halaman antara ${minPage} - ${maxPage}`);
            return;
        }

        loadPageDataQualified(page);
    }

    $(document).on('keypress', '#goToPageInput', function(e) {
        if (e.which == 13) {
            goToPageQualified();
        }
    });


    function loadPageDataPipeline(page = 1) {
        $('#tableDataPipelineCrew').attr('data-current-page', page);
        const searchValue = $("#containerPipeline").attr('data-search');
        const genderValue = $("#filterGenderPipeline").val();

        $.ajax({
            url: '<?php echo base_url("report/searchDataPipeline") ?>',
            type: 'GET',
            data: {
                page: page,
                search: searchValue,
                gender: genderValue
            },
            success: function(response) {
                $('#idTbodyPipelineCrew').html(response);
                highlightSearchResults('idTbodyPipelineCrew', searchValue);
            },
            error: function() {
                alert('Gagal mengambil data Pipeline Crew.');
            }
        });
    }


    function loadPageDataInterview(page = 1) {
        $('#tableDataInterviewCrew').attr('data-current-page', page);

        const searchValue = $("#containerInterview").attr('data-search');

        $.ajax({
            url: '<?php echo base_url("report/searchDataInterview") ?>',
            type: 'GET',
            data: {
                page: page,
                search: searchValue
            },
            success: function(response) {
                $('#idTbodyInterviewCrew').html(response);
                highlightSearchResults('idTbodyInterviewCrew', searchValue);
            },
            error: function() {
                alert('Gagal mengambil data Interview Crew.');
            }
        });
    }

    $(document).ready(function() {
        loadDefaultCharts();

        $('#selectTanggalTopFourStart, #selectTanggalTopFourEnd').on('input change', function() {
            checkShowResetButton();
        });

        $('#btnSearchTopFour').on('click', function() {
            const startDate = $('#selectTanggalTopFourStart').val();
            const endDate = $('#selectTanggalTopFourEnd').val();

            const slcRank = $('#checkboxRankContainer input[name="rankCheckbox[]"]:checked')
                .map(function() {
                    return this.value;
                })
                .get();

            const vessel = $("input[name='vessel[]']:checked")
                .map(function() {
                    return this.value;
                })
                .get();

            if (!startDate) {
                alert("Please select start date.");
                return;
            } else if (!endDate) {
                alert("Please select end date.");
                return;
            } else if (new Date(startDate) > new Date(endDate)) {
                alert("Start date cannot be later than end date.");
                return;
            }

            $.ajax({
                url: '<?php echo base_url("report/getApplicantDataByDate") ?>',
                type: 'POST',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    slcRank: slcRank,
                    vessel: vessel
                },
                dataType: 'json',
                success: function(res) {
                    renderApplicantPieChart(res.newApplicants);
                    renderTalentPoolPieChart(res.talentPool);
                }
            });
        });

        $('#btnResetTopFour').on('click', function() {
            $('#selectTanggalTopFourStart').val('');
            $('#selectTanggalTopFourEnd').val('');
            $('#checkboxRankContainer input[type="checkbox"]').val('').prop('checked', false);
            $(this).hide();
            loadDefaultCharts();
        });
    });


    function checkShowResetButton() {
        const startDate = $('#selectTanggalTopFourStart').val();
        const endDate = $('#selectTanggalTopFourEnd').val();
        if (startDate || endDate) {
            $('#btnResetTopFour').show();
        } else {
            $('#btnResetTopFour').hide();
        }
    }

    function loadDefaultCharts() {
        $.ajax({
            url: '<?php echo base_url("report/getDataApplicantPositionSummaryCombined") ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                renderApplicantPieChart(data);
                renderTalentPoolPieChart(data);
            }
        });
    }

    function listApproval() {
        $("#modalApproval").modal("show");

        $.ajax({
            url: "<?php echo base_url('report/getDataApproval'); ?>",
            type: "POST",
            dataType: "json",
            success: function(res) {
                $("#tbApprovalBody").html(res);
            }
        });
    }

    function renderApplicantPieChart(data) {

        const normalized = data.map(item => {
            const applicantTotal =
                item.qualified +
                item.pickup +
                item.not_position +
                item.not_qualified_total +
                item.not_reference_total +
                item.interview +
                item.mcu +
                item.not_qualified_experience +
                item.not_qualified_certificate +
                item.not_qualified_interview;

            return {
                name: item.name,
                y: applicantTotal
            };
        }).filter(item => item.y > 0);

        normalized.sort((a, b) => b.y - a.y);

        for (let i = 0; i < normalized.length; i++) {
            normalized[i].sliced = i < 2;
            normalized[i].selected = i < 2;
        }

        Highcharts.chart('newApplicantChartContainer', {
            chart: {
                type: 'pie',
                backgroundColor: null,
                plotShadow: false,
                width: 700,
                height: 700
            },
            title: {
                text: 'Pipeline Applicants Distribution by Position',
                style: {
                    color: 'var(--highcharts-title-color)',
                    fontSize: '1.5em'
                }
            },
            subtitle: {
                text: 'Excludes new applicants (not yet in pipeline)',
                style: {
                    color: 'var(--highcharts-subtitle-color)',
                    fontSize: '1.2em'
                }
            },
            tooltip: {
                pointFormat: '<span style="color:{point.color}">●</span> <b>{point.name}</b>: {point.y} applicant(s) ' +
                    '<b>({point.percentage:.1f}%)</b>',
                style: {
                    fontSize: '15px'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: ({point.percentage:.1f}%)',
                        style: {
                            color: 'black',
                            fontSize: '10px',
                            textOutline: 'none'
                        },
                        distance: 25
                    }
                }
            },
            series: [{
                name: 'Applicants',
                colorByPoint: true,
                data: normalized
            }],
            credits: {
                enabled: false
            }
        });
    }



    function renderTalentPoolPieChart(data) {
        var filtered = data.map(function(item) {

            var pipelineTotal =
                (item.qualified || 0) +
                (item.pickup || 0) +
                (item.not_position || 0) +
                (item.not_qualified_total || 0) +
                (item.not_reference_total || 0) +
                (item.interview || 0) +
                (item.mcu || 0) +
                (item.not_qualified_experience || 0) +
                (item.not_qualified_certificate || 0) +
                (item.not_qualified_interview || 0);

            item.pipelineTotal = pipelineTotal;
            return item;

        }).filter(function(item) {
            return item.pipelineTotal > 0;
        });

        filtered.sort(function(a, b) {
            return b.pipelineTotal - a.pipelineTotal;
        });

        var totalApplicants = filtered.reduce(function(sum, item) {
            return sum + item.pipelineTotal;
        }, 0);

        var seriesData = [];
        var drilldownSeries = [];

        filtered.forEach(function(item) {

            var percentage = ((item.pipelineTotal / totalApplicants) * 100).toFixed(1);

            seriesData.push({
                name: item.name + ' (' + percentage + '%)',
                y: item.pipelineTotal,
                drilldown: item.name
            });

            var drillData = [{
                    name: 'Qualified',
                    y: item.qualified
                },
                {
                    name: 'Pick Up',
                    y: item.pickup
                },
                {
                    name: 'No Position',
                    y: item.not_position
                },
                {
                    name: 'Not Qualified (Total)',
                    y: item.not_qualified_total
                },
                {
                    name: 'Not Qualified - Experience',
                    y: item.not_qualified_experience
                },
                {
                    name: 'Not Qualified - Certificate',
                    y: item.not_qualified_certificate
                },
                {
                    name: 'Not Qualified - Interview',
                    y: item.not_qualified_interview
                },
                {
                    name: 'Not Reference',
                    y: item.not_reference_total
                },
                {
                    name: 'Interview',
                    y: item.interview
                },
                {
                    name: 'MCU',
                    y: item.mcu
                }
            ].filter(function(d) {
                return d.y > 0;
            });

            drilldownSeries.push({
                id: item.name,
                name: item.name + ' Pipeline Details (Total: ' + item.pipelineTotal + ')',
                data: drillData
            });
        });


        Highcharts.chart('talentPoolChartContainer', {
            chart: {
                type: 'pie',
                backgroundColor: '#ffffff',
                width: 700,
                height: 700
            },
            title: {
                text: 'Pipeline Applicants by Position',
                style: {
                    color: '#000',
                    fontSize: '1.5em'
                }
            },
            subtitle: {
                text: 'Click the slices to view status breakdown<br><span style="font-size:11px;color:#555;">Pipeline total includes detailed not-qualified categories</span>',
                useHTML: true,
                style: {
                    color: '#000000',
                    fontSize: '1.1em'
                }
            },
            plotOptions: {
                pie: {
                    borderRadius: 5,
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '{point.name}: {point.y}',
                        distance: 15,
                        style: {
                            color: '#000000',
                            fontSize: '10px',
                            textOutline: 'none'
                        }
                    }
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y}</b> applicant(s) ' +
                    '({point.percentage:.1f}%)'
            },
            series: [{
                name: 'Applicants',
                colorByPoint: true,
                data: seriesData
            }],
            drilldown: {
                series: drilldownSeries
            },
            credits: {
                enabled: false
            }
        });
    }


    function pickUpDataApplicant(applicantId) {
        Swal.fire({
            title: 'Konfirmasi',
            html: 'Apakah crew ini <b>lolos interview</b> dan akan lanjut ke proses <span style="color:#067780;font-weight:600;">MCU</span>?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#067780',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, lanjut ke MCU',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $("#idLoadingSpinner").fadeIn();

            $.ajax({
                url: '<?php echo base_url("report/saveDataNewApplicent") ?>',
                type: 'POST',
                data: {
                    id: applicantId,
                    status: 6
                },
                dataType: 'json',
                success: function(response) {
                    $("#idLoadingSpinner").fadeOut();

                    if (response.status === 'error') {
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message ||
                                'Terjadi kesalahan saat memproses data.',
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.message ||
                            'Crew telah lolos interview dan lanjut ke MCU.',
                        icon: 'success',
                        confirmButtonColor: '#067780'
                    });

                    let currentPage = $('#tableDataPickup').attr('data-current-page') || 1;

                    if ($("#idTbodyInterviewCrew tr").length <= 2) {
                        currentPage = Math.max(1, currentPage - 1);
                    }

                    loadPageDataInterview(currentPage);
                },
                error: function(xhr, status, error) {
                    $("#idLoadingSpinner").fadeOut();

                    Swal.fire({
                        title: 'Error!',
                        text: 'Error caused by system: ' + error,
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });
    }


    function setMCUApplicant(applicantId) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah crew ini Lolos MCU?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#067780',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Lolos MCU',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $("#idLoadingSpinnerMCU").fadeIn();

            $.ajax({
                url: '<?php echo base_url("report/setMCUApplicant") ?>',
                type: 'POST',
                data: {
                    id: applicantId,
                    status: 6
                },
                dataType: 'json',
                success: function(response) {
                    $("#idLoadingSpinnerMCU").fadeOut();

                    if (response.status === 'error') {
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                        return;
                    }

                    if (response.status === 'success') {
                        const row = $("#idTbodyMCUCrew").find(`tr[data-id='${applicantId}']`);
                        if (row.length) row.fadeOut(400, () => row.remove());

                        Swal.fire({
                            title: 'Berhasil!',
                            html: `
                                <p>${response.message}</p>
                            `,
                            icon: 'success',
                            confirmButtonColor: '#067780'
                        });

                        let currentPage = $('#tableDataMCUCrew').attr('data-current-page') || 1;
                        if ($("#idTbodyMCUCrew tr").length <= 2) {
                            currentPage = Math.max(1, currentPage - 1);
                            loadPageDataMCU(currentPage);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $("#idLoadingSpinner").fadeOut();
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem: ' + error,
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });
    }

    function setNotFitApplicant(applicantId) {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah crew ini <b>TIDAK LULUS</b> MCU?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#067780',
            confirmButtonText: 'Ya, Tidak Lulus MCU',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $("#idLoadingSpinnerMCU").fadeIn();

            $.ajax({
                url: '<?php echo base_url("report/setNotFitApplicant") ?>',
                type: 'POST',
                data: {
                    id: applicantId,
                    status: 7
                },
                dataType: 'json',
                success: function(response) {
                    $("#idLoadingSpinnerMCU").fadeOut();

                    if (response.status === 'error') {
                        Swal.fire({
                            title: 'Gagal!',
                            text: response.message,
                            icon: 'error',
                            confirmButtonColor: '#d33'
                        });
                        return;
                    }

                    if (response.status === 'success') {
                        const row = $("#idTbodyMCUCrew").find(`tr[data-id='${applicantId}']`);
                        if (row.length) row.fadeOut(400, () => row.remove());

                        Swal.fire({
                            title: 'Berhasil!',
                            html: `
                                <p>${response.message}</p>
                            `,
                            icon: 'success',
                            confirmButtonColor: '#067780'
                        });

                        let currentPage = $('#tableDataMCUCrew').attr('data-current-page') || 1;
                        if ($("#idTbodyMCUCrew tr").length <= 2) {
                            currentPage = Math.max(1, currentPage - 1);
                            loadPageDataMCU(currentPage);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    $("#idLoadingSpinnerMCU").fadeOut();
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem: ' + error,
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });
    }

    function uploadMCU(applicantId) {

        $("#mcuApplicantId").val(applicantId);
        $("#modalMCUCrew").modal("hide");

        setTimeout(() => {
            $("#modalUploadMCU").modal("show");
        }, 400);
    }

    $(document).on("click", "#modalUploadMCU .btn-secondary, #modalUploadMCU .close", function() {
        $("#modalMCUCrew").modal("hide");
        setTimeout(() => {
            $("#modalMCUCrew").modal("show");
        }, 400);
    });

    $(document).off('submit', '#formUploadMCU').on('submit', '#formUploadMCU', function(e) {
        e.preventDefault();
        e.stopPropagation();

        let formData = new FormData(this);

        $.ajax({
            url: '<?php echo base_url("report/uploadMCUFile"); ?>',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,
            beforeSend: function() {
                $("#idLoadingSpinner").fadeIn();
            },
            success: function(response) {
                if (response.success) {
                    alert("File MCU berhasil diupload.");
                    $("#modalUploadMCU").modal('hide');

                    const applicantId = $("#mcuApplicantId").val();
                    const $row = $("#row_" + applicantId);
                    const $btnCell = $row.find("td:last");

                    const uploadedAt = new Date().toLocaleString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    $btnCell.find("button.btn-warning").remove();

                    $btnCell.prepend(`
                        <div style='margin-top:5px;'>
                            <button class="btn btn-success btn-xs btn-block" disabled>
                                <i class='fas fa-check-circle'></i> MCU Uploaded
                            </button>
                            <small style='display:block;margin-top:2px;color:#0b513e;font-size:11px;'>
                                Uploaded at: ${uploadedAt}
                            </small>
                        </div>
                    `);
                } else {
                    alert("Upload gagal: " + response.error);
                }
            },
            error: function(xhr, status, error) {
                alert("Error upload file: " + error);
            },
            complete: function() {
                $("#idLoadingSpinner").fadeOut();
            }
        });
    });


    function loadPageDataMCU(page = 1) {
        $('#tableDataMCUCrew').attr('data-current-page', page);

        const searchValue = $("#containerMCU").attr('data-search');

        $.ajax({
            url: '<?php echo base_url("report/searchDataMCUcrew") ?>',
            type: 'GET',
            data: {
                page: page,
                search: searchValue
            },
            success: function(response) {
                $('#idTbodyMCUCrew').html(response);
                highlightSearchResults('idTbodyMCUCrew', searchValue);
            },
            error: function() {
                alert('Gagal mengambil data MCU Crew.');
            }
        });
    }

    function pickUpDataApplicantDraftCrew(applicantId) {
        if (!confirm("Want to pick up this applicant?")) return;

        $.ajax({
            url: '<?php echo base_url("report/saveDataNewApplicent") ?>',
            type: 'POST',
            data: {
                id: applicantId
            },
            dataType: 'json',
            success: function(response) {
                if (response.error) {
                    alert("Gagal: " + response.error);
                    return;
                }
                if (response.success) {
                    alert(response.message);

                    let currentPage = $('#tableDataDraftCrew').attr('data-current-page') || 1;

                    if ($("#idTbodyDraftCrew tr").length <= 2) {
                        currentPage = Math.max(1, currentPage - 1);
                    }

                    loadPageDataDraft(currentPage);

                    let currentPickupPage = $('#tableDataPickup').attr('data-current-page') || 1;
                }
            },
            error: function(xhr, status, error) {
                alert('Error caused by sistem: ' + error);
            }
        });
    }

    function notPositionCrew(id, name) {
        Swal.fire({
            title: 'Konfirmasi',
            html: `Apakah anda yakin ingin menandai <b>${name}</b> sebagai <span style="color:#d33;font-weight:600;">Not Position</span>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#067780',
            confirmButtonText: 'Ya, Tandai',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $("#idLoadingSpinner").fadeIn();

            $.ajax({
                url: '<?php echo base_url("report/setNotPositionCrew") ?>',
                type: "POST",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    $("#idLoadingSpinner").fadeOut();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Crew telah ditandai sebagai Not Position.',
                        icon: 'success',
                        confirmButtonColor: '#067780'
                    });

                    let currentPage = $('#tableDataReady').attr('data-current-page') || 1;

                    if ($("#idTbodylistCrewNewModal tr").length <= 2) {
                        currentPage = Math.max(1, currentPage - 1);
                    }

                    loadPageDataReady(currentPage);
                },
                error: function(xhr, status, error) {
                    $("#idLoadingSpinner").fadeOut();

                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem: ' + error,
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });
    }


    function QualifiedCrew(id, name) {
        Swal.fire({
            title: 'Konfirmasi',
            html: `Apakah anda yakin ingin menandai <b>${name}</b> sebagai <span style="color:#067780;font-weight:600;">Qualified</span>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#067780',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Set Qualified',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $("#idLoadingSpinner").fadeIn();

            $.ajax({
                url: '<?php echo base_url("report/setQualifiedCrew") ?>',
                type: "POST",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    $("#idLoadingSpinner").fadeOut();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Crew telah ditandai sebagai Qualified.',
                        icon: 'success',
                        confirmButtonColor: '#067780'
                    });

                    let currentPage = $('#tableDataReady').attr('data-current-page') || 1;

                    if ($("#idTbodylistCrewNewModal tr").length <= 2) {
                        currentPage = Math.max(1, currentPage - 1);
                    }

                    loadPageDataReady(currentPage);
                },
                error: function(xhr, status, error) {
                    $("#idLoadingSpinner").fadeOut();

                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem: ' + error,
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });
    }


    function notQualifiedCrew(id, name) {
        if (!confirm("Not Qualified for these crew?")) return;

        $.ajax({
            url: '<?php echo base_url("report/setNotQualifiedCrew") ?>',
            type: "POST",
            data: {
                id: id
            },
            success: function(response) {
                alert("Crew has not qualified.");

                let currentPage = $('#tableDataReady').attr('data-current-page') || 1;

                if ($("#idTbodyQualifiedCrew tr").length <= 2) {
                    currentPage = Math.max(1, currentPage - 1);
                }

                loadPageDataReady(currentPage);

                let currentRejectedPage = $('#tableDataRejectedCrew').attr('data-current-page') ||
                    1;
                loadPageDataRejectedCrew(currentRejectedPage);
            },
            error: function(xhr, status, error) {
                alert('Error caused by system: ' + error);
            }
        });
    }

    function notReffCrew(id, name) {
        $("#notReffId").val(id);
        $("#notReffReason").val("");
        $("#modalInterviewCrew").modal("hide");
        $("#modalNotReff").modal("show");

        setTimeout(() => {
            $("#notReffReason").focus();
        }, 500);
    }

    $(document).on("click", "#modalNotReff .btn-secondary, #modalNotReff .close", function() {
        $("#modalNotReff").modal("hide");
        setTimeout(function() {
            $("#modalInterviewCrew").modal("show");
        }, 400);
    });


    function submitNotReff() {
        let id = $("#notReffId").val();
        let reason = $("#notReffReason").val().trim();

        if (reason === "") {
            alert("Alasan wajib diisi!");
            return;
        }

        $("#idLoadingSpinnerNotReff").fadeIn();
        $.ajax({
            url: '<?php echo base_url("report/setNotRefference") ?>',
            type: "POST",
            data: {
                id: id,
                reason: reason
            },
            success: function(response) {
                let res = JSON.parse(response);
                alert(res.message);

                if (res.status === "success") {
                    $("#modalNotReff").modal("hide");

                    let currentPage = $('#tableDataInterviewCrew').attr('data-current-page') || 1;
                    if ($("#idTbodyInterviewCrew tr").length <= 2) {
                        currentPage = Math.max(1, currentPage - 1);
                    }
                    loadPageDataInterview(currentPage);
                }
            },
            error: function(xhr, status, error) {
                alert('Error caused by system: ' + error);
            },
            complete: function() {
                $("#idLoadingSpinnerNotReff").fadeOut();
            }
        });
    }

    function interviewCrewQualify(id, name) {
        Swal.fire({
            title: 'Konfirmasi',
            html: `Apakah anda yakin ingin menandai <b>${name}</b> untuk proses <span style="color:#067780;font-weight:600;">Interview</span>?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#067780',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Set Interview',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (!result.isConfirmed) return;

            $("#idLoadingSpinnerQualifiedCrew").fadeIn();

            $.ajax({
                url: '<?php echo base_url("report/setInterviewCrewQualify") ?>',
                type: "POST",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    $("#idLoadingSpinnerQualifiedCrew").fadeOut();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Crew telah berhasil ditandai untuk proses Interview.',
                        icon: 'success',
                        confirmButtonColor: '#067780'
                    });

                    let currentPage = $('#tableDataQualifiedCrew').attr('data-current-page') ||
                        1;

                    if ($("#idTbodyQualifiedCrew tr").length <= 2) {
                        currentPage = Math.max(1, currentPage - 1);
                    }

                    loadPageDataQualified(currentPage);
                },
                error: function(xhr, status, error) {
                    $("#idLoadingSpinnerQualifiedCrew").fadeOut();

                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem: ' + error,
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        });
    }


    function positionAvailableCrew(id, name) {
        if (!confirm("Posisi yang dilamar tersedia. Kembalikan crew ini ke status Ready?")) return;

        $.ajax({
            url: '<?php echo base_url("report/setPositionAvailableCrew") ?>',
            type: "POST",
            data: {
                id: id
            },
            dataType: "json",
            success: function(response) {
                alert(response.message);

                let currentPage = $('#tableDataPipeline').attr('data-current-page') || 1;
                if ($("#idTbodyPipelineCrew tr").length <= 2) {
                    currentPage = Math.max(1, currentPage - 1);
                }
                loadPageDataPipeline(currentPage);
            },
            error: function(xhr, status, error) {
                alert('Error caused by system: ' + error);
            }
        });
    }

    function searchTable(inputElement, dataType) {
        console.log("test ini");
        const searchValue = inputElement.value.toLowerCase();
        let container = null;
        let table = null;

        switch (dataType) {
            case 'DataReady':
                container = document.getElementById('containerReady');
                table = document.getElementById('tableDataReady');
                $(container).attr('data-search', searchValue);
                break;
            case 'DataPipeline':
                container = document.getElementById('containerPipeline');
                table = document.getElementById('tableDataPipelineCrew');
                $(container).attr('data-search', searchValue);
                break;

            case 'DataInterview':
                container = document.getElementById('containerInterview');
                table = document.getElementById('tableDataInterviewCrew');
                $(container).attr('data-search', searchValue);
                break;
            case 'DataQualifiedCrew':
                container = document.getElementById('containerQualifiedCrew');
                table = document.getElementById('tableDataQualifiedCrew');
                $(container).attr('data-search', searchValue);
            case 'DataMCUcrew':
                container = document.getElementById('containerMCU');
                table = document.getElementById('tableDataMCUCrew');
                $(container).attr('data-search', searchValue);
                break;
        }

        if (!table) {
            console.warn("Tabel tidak ditemukan untuk pencarian:", dataType);
            return;
        }

        table.style.opacity = '0.5';

        const base_url = "<?php echo base_url(); ?>";

        $.ajax({
            url: base_url + "report/search" + dataType,
            type: 'GET',
            data: {
                search: searchValue
            },
            success: function(response) {
                let tbodyId;
                switch (dataType) {
                    case 'DataReady':
                        tbodyId = 'idTbodylistCrewNewModal';
                        break;
                    case 'DataPipeline':
                        tbodyId = 'idTbodyPipelineCrew';
                        break;
                    case 'DataInterview':
                        tbodyId = 'idTbodyInterviewCrew';
                        break;
                    case 'DataQualifiedCrew':
                        tbodyId = 'idTbodyQualifiedCrew';
                        break;
                    case 'DataMCUcrew':
                        tbodyId = 'idTbodyMCUCrew';
                        break;
                    default:
                        console.warn("Tipe data tidak dikenali:", dataType);
                        return;
                }

                $(`#${tbodyId}`).html(response);
                table.style.opacity = '';
                highlightSearchResults(tbodyId, searchValue);
            },
            error: function() {
                alert('Gagal melakukan pencarian');
                table.style.opacity = '';
            }
        });
    }


    function unReject(id) {
        if (!confirm("Unreject this applicant?")) return;

        $.ajax({
            url: '<?php echo base_url("report/unRejectCrew") ?>',
            type: "POST",
            data: {
                id: id
            },
            success: function(response) {
                let res = JSON.parse(response);
                if (res.status === 'success') {
                    alert("Applicant successfully unrejected.");

                    let currentPage = parseInt($('#tableDataRejectedCrew').attr(
                        'data-current-page')) || 1;
                    let totalRows = $('#idTbodyRejectedCrew tr').length;

                    if (totalRows === 1 && currentPage > 1) {
                        currentPage = currentPage - 1;
                    }

                    loadPageDataRejectedCrew(currentPage);
                    loadPageDataReady(1);
                } else {
                    alert("Failed to unreject: " + res.message);
                }
            },
            error: function(xhr, status, error) {
                alert("Error occurred: " + error);
            }
        });
    }

    function highlightSearchResults(tbodyId, searchTerm) {
        if (!searchTerm) return;

        const keywords = searchTerm.trim().split(/\s+/).filter(k => k.length > 0);
        if (keywords.length === 0) return;

        const markStyle = "background-color: yellow; padding:0 2px; border-radius:3px; font-weight:600;";

        const highlightTextOnly = ($el, regexes) => {
            $el.contents().each(function() {
                if (this.nodeType === 3) {
                    let text = this.nodeValue;
                    let replaced = text;
                    regexes.forEach(regex => {
                        replaced = replaced.replace(regex, match =>
                            `<mark style="${markStyle}">${match}</mark>`
                        );
                    });
                    if (replaced !== text) {
                        $(this).replaceWith(replaced);
                    }
                } else if (this.nodeType === 1) {
                    highlightTextOnly($(this), regexes);
                }
            });
        };

        const regexes = keywords.map(word => {
            const escaped = word.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            return new RegExp(escaped, 'gi');
        });

        $(`#${tbodyId} tr`).each(function() {
            $(this).find('.position-applied, .pengalaman-jenis-kapal, .fullname, .email, .last-experience')
                .each(function() {
                    highlightTextOnly($(this), regexes);
                });
        });
    }


    $(document).ready(function() {
        const bulanMap = {
            '01': 'Januari',
            '02': 'Februari',
            '03': 'Maret',
            '04': 'April',
            '05': 'Mei',
            '06': 'Juni',
            '07': 'Juli',
            '08': 'Agustus',
            '09': 'September',
            '10': 'Oktober',
            '11': 'November',
            '12': 'Desember'
        };

        const categoriesX = Array.from({
            length: 31
        }, (_, i) => (i + 1).toString());
        const monthlyData = {};
        let bulanSekarangKey = '';

        $('#selectBulan').datepicker({
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            dateFormat: 'MM yy',
            onClose: function(dateText, inst) {
                const month = inst.selectedMonth + 1;
                const year = inst.selectedYear;
                const bulanStr = String(month).padStart(2, '0');
                const bulanKey = `${bulanMap[bulanStr]} ${year}`;
                $('#selectBulan').val(bulanKey);
            },
            beforeShow: function(input, inst) {
                $(input).datepicker('widget').addClass('hide-calendar');
            }
        });

        $("<style>")
            .prop("type", "text/css")
            .html(`
                .hide-calendar .ui-datepicker-calendar { display: none; }
                .hide-calendar .ui-datepicker-close { display: none; }
            `).appendTo("head");


        $.ajax({
            url: "<?php echo base_url('report/getSubmitCV'); ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {
                data.forEach(item => {
                    const [year, month, day] = item.tanggal.split("-");
                    const bulanKey = `${bulanMap[month]} ${year}`;
                    const tgl = parseInt(day);

                    if (!monthlyData[bulanKey]) {
                        monthlyData[bulanKey] = Array(31).fill(0);
                    }
                    monthlyData[bulanKey][tgl - 1] = parseInt(item.jumlah);
                });

                const now = new Date();
                const bulanNow = String(now.getMonth() + 1).padStart(2, '0');
                bulanSekarangKey = `${bulanMap[bulanNow]} ${now.getFullYear()}`;
            }
        });

        $('#btnSearchBulan').on('click', function() {
            const bulanKey = $('#selectBulan').val();
            tampilkanBulan(bulanKey);
        });

        $('#listCrewNewModal').on('shown.bs.modal', function() {
            if (bulanSekarangKey && monthlyData[bulanSekarangKey]) {
                $('#selectBulan').val(bulanSekarangKey);
                tampilkanBulan(bulanSekarangKey);
            } else {
                $('#totalSubmitCV').html(
                    '<div class="alert alert-warning">Data bulan ini tidak tersedia.</div>');
            }
        });

        function tampilkanBulan(bulanKey) {
            if (monthlyData[bulanKey]) {
                renderChart(bulanKey);
            } else {
                renderChartKosong(bulanKey);
            }
        }

        function renderChart(bulanKey) {
            const data = monthlyData[bulanKey];
            const total = data.reduce((acc, val) => acc + val, 0);

            Highcharts.chart('totalSubmitCV', {
                chart: {
                    type: 'line',
                    backgroundColor: '#ffffff',
                    animation: {
                        duration: 800
                    }
                },
                title: {
                    useHTML: true,
                    text: `Submit CV - ${bulanKey}<br><span style="font-size: 16px; color: #333;">Total: <b>${total}</b></span>`,
                    style: {
                        color: '#000000',
                        fontSize: '1.5em'
                    }
                },
                xAxis: {
                    categories: categoriesX,
                    title: {
                        text: 'Tanggal Dalam Bulan',
                        style: {
                            color: '#000000',
                            fontSize: '1.2em'
                        }
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Submit CV',
                        style: {
                            color: '#000000',
                            fontSize: '1.2em'
                        }
                    },
                    allowDecimals: false
                },
                tooltip: {
                    enabled: false
                },
                plotOptions: {
                    series: {
                        marker: {
                            enabled: true,
                            radius: 5,
                            fillColor: '#007bff',
                            states: {
                                hover: {
                                    enabled: true,
                                    radius: 7
                                }
                            }
                        },
                        dataLabels: {
                            enabled: true,
                            formatter: function() {
                                return this.y > 0 ? this.y : '';
                            },
                            style: {
                                color: '#000000',
                                fontSize: '10px',
                                textOutline: 'none'
                            }
                        },
                        animation: {
                            duration: 1200
                        }
                    }
                },
                series: [{
                    name: bulanKey,
                    data: data,
                    color: '#007bff'
                }]
            }, function(chart) {
                chart.series[0].points.forEach((point, i) => {
                    if (point.graphic) {
                        point.graphic.attr({
                            opacity: 0
                        });
                        setTimeout(() => {
                            point.graphic.animate({
                                opacity: 1
                            }, {
                                duration: 500
                            });
                        }, i * 200);
                    }
                    if (point.dataLabel) {
                        point.dataLabel.attr({
                            opacity: 0
                        });
                        setTimeout(() => {
                            point.dataLabel.animate({
                                opacity: 1
                            }, {
                                duration: 500
                            });
                        }, i * 200);
                    }
                });
            });
        }

        function renderChartKosong(bulanKey) {
            Highcharts.chart('totalSubmitCV', {
                chart: {
                    type: 'line',
                    backgroundColor: '#ffffff'
                },
                title: {
                    text: `Submit CV - ${bulanKey} (tidak ada data)`
                },
                xAxis: {
                    categories: categoriesX,
                    title: {
                        text: 'Tanggal Dalam Bulan'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Jumlah Submit CV'
                    },
                    allowDecimals: false
                },
                tooltip: {
                    enabled: false
                },
                series: [{
                    name: bulanKey,
                    data: Array(31).fill(0),
                    color: '#000000',
                    dataLabels: {
                        enabled: false
                    }
                }]
            });
        }

    });

    function adjustModalStack(modalId, zIndex) {
        setTimeout(() => {
            $('.modal-backdrop').last().css('z-index', zIndex - 1);
            $(modalId).css('z-index', zIndex);
        }, 10);
    }

    function switchModal(hideSelector, showSelector, callback) {
        $(hideSelector).modal('hide');
        setTimeout(() => {
            $(showSelector).modal({
                backdrop: 'static',
                keyboard: false
            }).modal('show');

            if (callback && typeof callback === 'function') callback();
        }, 400);
    }

    $(document).on('click', 'button[id^="btnOpen"]', function() {
        const btnId = $(this).attr('id');
        let targetModal = '';
        let loaderFunction = null;

        if (btnId === 'btnOpenNewApplicants') {
            targetModal = '#modalAllApplicants';
            loaderFunction = loadPageDataReady;
        } else if (btnId === 'btnOpenInterviewApplicants') {
            targetModal = '#modalInterviewCrew';
            loaderFunction = loadPageDataInterview;
        } else if (btnId === 'btnOpenPipelineApplicants') {
            targetModal = '#modalPipelineCrew';
            loaderFunction = loadPageDataPipeline;
        } else if (btnId === 'btnOpenQualifyApplicants') {
            targetModal = '#modalQualifiedCrew';
            loaderFunction = loadPageDataQualified;
        } else if (btnId === 'btnOpenMCUApplicant') {
            targetModal = '#modalMCUCrew';
            loaderFunction = loadPageDataMCU;
        }

        if (targetModal) {
            switchModal('#listCrewNewModal', targetModal, () => {
                adjustModalStack(targetModal, 1060);
                loaderFunction?.(1);
            });
        }
    });

    $(document).on('click', 'button[id^="btnBack"]', function() {
        const target = $(this).data('target');
        const currentModal = $(this).closest('.modal');
        if (target && currentModal.length) {
            switchModal(`#${currentModal.attr('id')}`, target);
        }
    });

    function notPositionCrewQualified() {

    }

    function showNotQualifyModal(btn) {
        const id = $(btn).data('id');
        const name = $(btn).data('name');
        const position = $(btn).data('position');
        const lastExperience = $(btn).data('last-experience');
        const position_existing = $(btn).data('position-existing');

        $('#modalNotQualifyName').html(`<i class='fas fa-user'></i> ${name}`);
        $('#modalNotQualifyPosition').html(`<i class='fas fa-briefcase'></i> ${position}`);
        $('#modalNotQualifyLastExperience').html(`<i class='fas fa-calendar-alt'></i> ${lastExperience}`);
        $('#modalNotQualifyPositionExisting').html(`<i class='fas fa-calendar-alt'></i> ${position_existing}`);
        $('#txtNotQualifyReason').val('');
        $('#hiddenCrewId').val(id);

        const $currentModal = $(btn).closest('.modal');
        if ($currentModal.length) {
            $currentModal.modal('hide');
        }

        $('#modalNotQualify').on('shown.bs.modal', function() {
            $('.modal-backdrop').last().css('z-index', 1070);
            $('#modalNotQualify').css('z-index', 1071);
        }).modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function showQualifyPipelineModal(btn) {
        const id = $(btn).data('id');
        const name = $(btn).data('name');
        const position = $(btn).data('position');
        const pengalaman_jeniskapal = $(btn).data('pengalaman-jenis-kapal') || '';

        $('#modalQualifyPipelineName').html(`<i class='fas fa-user'></i> ${name}`);
        $('#modalQualifyPipelinePosition').html(`<i class='fas fa-briefcase'></i> ${position}`);
        $('#hiddenCrewIdPipeline').val(id);

        $.ajax({
            url: "<?php echo base_url('report/getRankByOption'); ?>",
            type: "GET",
            dataType: "json",
            success: function(res) {
                let options = "";
                res.forEach(r => {
                    options += `<option value="${r.value}">${r.text}</option>`;
                });
                $("#rankSelectPipeline").html(options);

                const rankSelect = $("#rankSelectPipeline");
                let found = false;

                rankSelect.find("option").each(function() {
                    if ($(this).val().toUpperCase() === position.toUpperCase()) {
                        $(this).prop("selected", true);
                        found = true;
                        return false;
                    }
                });

                if (!found) {
                    rankSelect.find("option").each(function() {
                        if (
                            $(this).val().toUpperCase().includes(position.toUpperCase()) ||
                            position.toUpperCase().includes($(this).val().toUpperCase())
                        ) {
                            $(this).prop("selected", true);
                            return false;
                        }
                    });
                }
            }
        });

        const vesselTypes = [
            "BULK CARRIER", "CARGO", "GENERAL CARGO", "CONTAINER", "TANKER PRODUCT",
            "TANKER OIL", "CRUDE OIL", "TANKER CHEMICAL", "TANKER GAS", "FLOATING CRANE",
            "TUG BOAT", "SUPPLY VESSEL", "CREW BOAT", "RORO/PASSENGER"
        ];

        let vessels = pengalaman_jeniskapal.split(",").map(v => v.trim()).filter(v => v);

        let html = `
            <div class="vessel-container" 
                style="
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 6px 12px;
                    align-items: start;
                    padding: 5px 0;
                    border: 1px solid #ddd;
                    border-radius: 6px;
                    background: #f9f9f9;
                ">
        `;

        vesselTypes.forEach((v, index) => {
            const checked = vessels.includes(v) ? "checked" : "";
            const borderRight = (index + 1) % 3 !== 0 ? "border-right: 1px solid #e0e0e0;" : "";
            html += `
            <div class="form-check" 
                style="margin: 4px 8px; padding-right:8px; ${borderRight}">
                <input class="form-check-input vessel-check" type="checkbox" value="${v}" ${checked}>
                <label class="form-check-label" style="font-size: 13px;">${v}</label>
            </div>`;
        });

        let otherValue = "";
        vessels.forEach(v => {
            if (!vesselTypes.includes(v)) {
                otherValue = v;
            }
        });

        const otherChecked = otherValue ? "checked" : "";
        html += `
            <div class="form-check" style="margin: 4px 8px;">
                <input class="form-check-input vessel-check" type="checkbox" id="otherKapalCheckbox" value="OTHER" ${otherChecked}>
                <label class="form-check-label" style="font-size: 13px;">OTHER</label>
            </div>
        `;

        html += `</div>`;

        html += `
            <div class="mt-2" id="inputOtherKapal" style="display:${otherValue ? 'block' : 'none'};">
                <input type="text" class="form-control form-control-sm" 
                    id="otherKapalInput" 
                    placeholder="Sebutkan jenis kapal lainnya" 
                    value="${otherValue}">
            </div>
        `;

        $("#modalQualifyPipelineVesselTypeExperience").html(html);

        $(document).off("change", "#otherKapalCheckbox").on("change", "#otherKapalCheckbox", function() {
            if ($(this).is(":checked")) {
                $("#inputOtherKapal").show();
            } else {
                $("#inputOtherKapal").hide().find("input").val("");
            }
        });

        const $currentModal = $(btn).closest('.modal');
        if ($currentModal.length) {
            $currentModal.modal('hide');
        }

        $('#modalQualifyPipeline').on('shown.bs.modal', function() {
            $('.modal-backdrop').last().css('z-index', 1070);
            $('#modalQualifyPipeline').css('z-index', 1071);
        }).modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    function submitQualifiedCrewPipeline() {
        const id = $('#hiddenCrewIdPipeline').val();
        const position_existing = $('#rankSelectPipeline').val();

        let vessels = [];
        $('.vessel-check:checked').each(function() {
            const val = $(this).val();
            if (val === 'OTHER') {
                const otherVal = $('#otherKapalInput').val().trim();
                if (otherVal) vessels.push(otherVal);
            } else {
                vessels.push(val);
            }
        });
        const pengalaman_jeniskapal = vessels.join(', ');

        if (!id || !position_existing) {
            alert('Please select rank and vessel type!');
            return;
        }

        $('#idLoadingSpinnerQualifyPipeline').show();

        $.ajax({
            url: "<?php echo base_url('report/setQualifiedCrewPipeline'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                id: id,
                position_existing: position_existing,
                pengalaman_jeniskapal: pengalaman_jeniskapal
            },
            success: function(res) {
                $('#idLoadingSpinnerQualifyPipeline').hide();
                if (res.status === 'success') {
                    alert(res.message);
                    $('#modalQualifyPipeline').modal('hide');

                    let currentPage = $('#tableDataPipelineCrew').attr('data-current-page') || 1;
                    if ($("#idTbodyPipelineCrew tr").length <= 2) {
                        currentPage = Math.max(1, currentPage - 1);
                    }
                    loadPageDataPipeline(currentPage);
                } else {
                    alert('Error', res.message, 'error');
                }
            },
            error: function() {
                $('#idLoadingSpinnerQualifyPipeline').hide();
                alert('Error', 'Terjadi kesalahan pada server', 'error');
            }
        });
    }


    $(document).on('click', '#btnCancelNotQualified', function() {
        $('#modalNotQualify').modal('hide');

        $('#modalNotQualify').on('hidden.bs.modal', function() {
            $('#modalQualifiedCrew').modal({
                backdrop: 'static',
                keyboard: false
            });

            setTimeout(() => {
                $('.modal-backdrop').last().css('z-index', 1059);
                $('#modalQualifiedCrew').css('z-index', 1060);
            }, 10);
        });
    });

    $(document).on('click', '#btnCancelQualifiedPipeline', function() {
        $('#modalQualifyPipeline').modal('hide');

        $('#modalQualifyPipeline').on('hidden.bs.modal', function() {
            $('#modalPipelineCrew').modal({
                backdrop: 'static',
                keyboard: false
            });

            setTimeout(() => {
                $('.modal-backdrop').last().css('z-index', 1059);
                $('#modalPipelineCrew').css('z-index', 1060);
            }, 10);
        });
    })

    function showNotQualifyModalLayer1(btn) {
        const id = $(btn).data('id');
        const name = $(btn).data('name');
        const position = $(btn).data('position');
        const lastExperience = $(btn).data('last-experience');

        $('#modalNotQualifyNameLayer1').html(`<i class='fas fa-user'></i> ${name}`);
        $('#modalNotQualifyPositionLayer1').html(`<i class='fas fa-briefcase'></i> ${position}`);
        $('#modalNotQualifyLastExperienceLayer1').html(`<i class='fas fa-calendar-alt'></i> ${lastExperience}`);
        $('#txtNotQualifyReason1').val('');
        $('#hiddenCrewIdLayer1').val(id);
        $('#certificateCheckboxContainer').html('<i>Loading sertifikat...</i>');

        $.ajax({
            url: '<?php echo base_url("report/getCertificatesByPosition") ?>',
            type: 'GET',
            data: {
                position: position
            },
            success: function(response) {
                let html = '';
                if (response.length > 0) {
                    response.forEach(cert => {
                        html += `
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input cert-checkbox" type="checkbox" 
                            value="${cert.id}" 
                            data-certname="${cert.certificate_name}"
                            id="cert_${cert.id}" name="notQualifiedCertificates[]">
                        <label class="form-check-label" for="cert_${cert.id}" style="font-size: 11px;">
                            ${cert.certificate_name}
                        </label>
                    </div>
                </div>`;
                    });
                } else {
                    html =
                        '<div class="col-12"><em>Tidak ada sertifikat untuk posisi ini.</em></div>';
                }
                $('#certificateCheckboxContainer').html(html);
            },
            error: function() {
                $('#certificateCheckboxContainer').html('<em>Gagal memuat sertifikat.</em>');
            }
        });

        $(document).off('change',
            '#certificateCheckboxContainer input[type="checkbox"], #rankCheckboxContainer input[type="checkbox"]'
        );
        $(document).on('change',
            '#certificateCheckboxContainer input[type="checkbox"], #rankCheckboxContainer input[type="checkbox"]',
            function() {
                const textarea = $('#txtNotQualifyReason1');
                const currentReason = textarea.val() || "";

                const lines = currentReason.split("\n");
                const manualLines = lines.filter(line => {
                    const t = (line || "").trim().toLowerCase();
                    return !(t.startsWith('sertifikat yang belum terpenuhi:') || t.startsWith(
                        'dengan melengkapi sertifikat di atas'));
                });

                const manualText = manualLines.join("\n").trim();

                let selectedCerts = [];
                $('#certificateCheckboxContainer input[type="checkbox"]:checked').each(function() {
                    const certName = $(this).data('certname');
                    if (certName) selectedCerts.push(certName.trim());
                });

                let selectedRanks = [];
                $('#rankCheckboxContainer input[type="checkbox"]:checked').each(function() {
                    let rankName = $(this).closest('label').text().trim();
                    if (!rankName) {
                        rankName = $(this).val();
                    }
                    if (rankName) selectedRanks.push(rankName);
                });

                let parts = [];
                if (manualText) parts.push(manualText);
                if (selectedCerts.length > 0) {
                    parts.push('Sertifikat yang belum terpenuhi: ' + selectedCerts.join(', '));
                }
                if (selectedRanks.length > 0) {
                    parts.push('Dengan melengkapi sertifikat di atas, Anda bisa melamar untuk posisi: ' +
                        selectedRanks.join(', '));
                }

                textarea.val(parts.join("\n"));
            });

        const $currentModal = $(btn).closest('.modal');
        if ($currentModal.length) {
            $currentModal.modal('hide');
        }

        $('#modalNotQualifyLayer1').on('shown.bs.modal', function() {
            $('.modal-backdrop').last().css('z-index', 1070);
            $('#modalNotQualifyLayer1').css('z-index', 1071);
        }).modal({
            backdrop: 'static',
            keyboard: false
        });
    }

    $(document).on('click', '#btnCancelNotQualifiedLayer1', function() {
        $('#modalNotQualifyLayer1').modal('hide');
        $('#modalNotQualifyLayer1').on('hidden.bs.modal', function() {
            $('#modalAllApplicants').modal({
                backdrop: 'static',
                keyboard: false
            });

            setTimeout(() => {
                $('.modal-backdrop').last().css('z-index', 1059);
                $('#modalAllApplicants').css('z-index', 1060);
            }, 10);
        });
    });

    $(document).on('click', '#btnCancelNotQualifiedLayer1', function() {
        $('#modalNotQualifyLayer1').modal('hide');

        $('#modalNotQualifyLayer1').on('hidden.bs.modal', function() {
            $('#modalAllApplicants').modal({
                backdrop: 'static',
                keyboard: false
            });

            setTimeout(() => {
                $('.modal-backdrop').last().css('z-index', 1059);
                $('#modalAllApplicants').css('z-index', 1060);
            }, 10);
        });
    });

    function submitNotQualifiedLayer1() {
        const id = $("#hiddenCrewIdLayer1").val();
        const reason = $("#txtNotQualifyReason1").val().trim();

        if (reason === "") {
            alert("Alasan wajib diisi!");
            $("#txtNotQualifyReason1").focus();
            return;
        }

        let selectedCertificates = [];
        $("#certificateCheckboxContainer input[type='checkbox']:checked").each(function() {
            selectedCertificates.push($(this).data('certname'));
        });

        let selectedRanks = [];
        $("#rankCheckboxContainer input[type='checkbox']:checked").each(function() {
            selectedRanks.push($(this).val());
        });

        $("#idLoadingSpinnerLayer1").fadeIn();
        $.ajax({
            url: "<?php echo base_url('report/setNotQualifiedCrewLayer1'); ?>",
            method: "POST",
            data: {
                id: id,
                reason: reason,
                missing_certificates: selectedCertificates.join(', '),
                suggested_ranks: selectedRanks.join(', ')
            },
            success: function(res) {
                const response = JSON.parse(res);
                if (response.status === "success") {
                    alert("Berhasil dikirim.");
                    $('#modalNotQualifyLayer1').modal('hide');
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert("Terjadi kesalahan server.");
            },
            complete: function() {
                $("#idLoadingSpinnerLayer1").fadeOut();
            }
        });
    }

    function submitNotQualifiedCrew() {
        const id = $('#hiddenCrewId').val();
        const reason = $('#txtNotQualifyReason').val().trim();

        if (!reason) {
            alert("Please enter the reason.");
            $('#txtNotQualifyReason').focus();
            return false;
        }
        $("#idLoadingSpinnerNotQualify").fadeIn();
        $.ajax({
            url: '<?php echo base_url("report/setNotQualifiedCrew") ?>',
            type: 'POST',
            data: {
                id: id,
                reason: reason
            },
            success: function(response) {
                alert("Crew marked as Not Qualified.");
                $('#modalNotQualify').modal('hide');

                let currentPage = $('#tableDataQualifiedCrew').attr('data-current-page') || 1;
                if ($("#idTbodylistCrewNewModal tr").length <= 2) {
                    currentPage = Math.max(1, currentPage - 1);
                }
                loadPageDataQualifiedCrew(currentPage);
            },
            error: function(xhr, status, error) {
                alert('System error: ' + error);
            },
            complete: function() {
                $("#idLoadingSpinnerNotQualify").fadeOut();
            }
        });
    }

    $(document).ready(function() {
        $("[id^=selectTanggalTopFour]").datepicker({
            dateFormat: 'yy-mm-dd',
            showButtonPanel: true,
            changeMonth: true,
            changeYear: true,
            defaultDate: new Date(),
        });
    });

    function toggleReason(id) {
        const reasonDiv = document.getElementById(id);
        if (reasonDiv.style.display === 'none' || reasonDiv.style.display === '') {
            reasonDiv.style.display = 'block';
        } else {
            reasonDiv.style.display = 'none';
        }
    }

    /*Print Form Mlc start */
    function click_form_mlc() {
        var get_idperson = $("#txtIdPerson").val();

        if (!get_idperson) {
            alert("Person Empty!");
            return;
        }

        $("#modal-form-mlc").modal("show");
        // console.log("ID Person:", get_idperson);

        $.ajax({
            url: "<?php echo base_url('report/get_data_form_mlc'); ?>",
            type: "POST",
            data: {
                idperson: get_idperson
            },
            dataType: "json",
            success: function(res) {
                if (!res.success || !res.data || res.data.length === 0) {
                    alert("Data tidak ditemukan!");
                    return;
                }

                var data = res.data[0];
                $("#name-crew-mlc").text(data.fullname);
                $("#jabatan-crew-mlc").text(data.nmrank);
                $("#date-crew-mlc").text(data.signondt);
                $("#vessel-crew-mlc").text(data.nmvsl);
            },
            error: function(xhr, status, error) {
                console.log("AJAX Error:", error);
                alert("Error fetching data.");
            }
        });
    }

    $(document).ready(function() {
        $('.check-box').on('change', function() {
            const $this = $(this);
            const isYes = $this.hasClass('yes-checkbox');
            const pairedName = isYes ?
                $this.data('no-checkbox') :
                $this.data('yes-checkbox');

            if ($this.is(':checked')) {
                $(`[name="${pairedName}"]`).prop('checked', false);
            }
        });

        function getAllCheckboxValues() {
            const values = {};
            let allFilled = true;
            const missingStatements = [];

            for (let i = 1; i <= 9; i++) {
                const $yesCheckbox = $(`[name="statement_${i}"]`);
                const $noCheckbox = $(`[name="statement_${i}_no"]`);


                if (!$yesCheckbox.is(':checked') && !$noCheckbox.is(':checked')) {
                    allFilled = false;
                    missingStatements.push(i);
                }

                values[`statement_${i}`] = $yesCheckbox.is(':checked') ? 1 :
                    ($noCheckbox.is(':checked') ? 0 : null);
            }

            return {
                values: values,
                allFilled: allFilled,
                missingStatements: missingStatements
            };
        }


        function showCheckboxValues() {
            const result = getAllCheckboxValues();

            // console.log('=== CHECKBOX VALUES ===');
            // console.log('Nilai per statement:');

            // Tampilkan dalam format tabel
            for (let i = 1; i <= 9; i++) {
                const value = result.values[`statement_${i}`];
                const status = value === 1 ? 'Yes (1)' :
                    value === 0 ? 'No (0)' :
                    'Belum dipilih';
                //console.log(`Statement ${i}: ${status}`);
            }

            // // Tampilkan dalam bentuk object
            // console.log('Data Object:', result.values);

            // // Tampilkan summary
            // console.log('Summary:');
            // console.log(`- Total statements: 9`);
            // console.log(`- Terisi: ${Object.values(result.values).filter(v => v !== null).length}`);
            // console.log(`- Belum terisi: ${Object.values(result.values).filter(v => v === null).length}`);

            return result;
        }

        $('#btn-print-form-mlc').on('click', function() {
            console.clear(); // Clear console dulu

            const result = getAllCheckboxValues();
            if (!result.allFilled) {
                alert(
                    `Harap pilih semua statement!\n\nStatement yang belum dipilih: ${result.missingStatements.join(', ')}`
                );

                $('tr').removeClass('missing-row');
                result.missingStatements.forEach(num => {
                    $(`[name="statement_${num}"]`).closest('tr').addClass('missing-row');
                });

                return;
            }


            showCheckboxValues();
            generatePDF(result.values);
        });

        function generatePDF(checkboxValues) {
            var idperson = $("#txtIdPerson").val();
            var name_crew = $("#name-crew-mlc").text();
            var jabatan_crew = $("#jabatan-crew-mlc").text();
            var date_crew = $("#date-crew-mlc").text();
            var vessel_crew = $("#vessel-crew-mlc").text();

            // Gabungkan semua data
            const data = {
                ...checkboxValues,
                idperson: idperson,
                fullname: name_crew,
                nmrank: jabatan_crew,
                signondt: date_crew,
                nmvsl: vessel_crew
            };

            // console.log('Data untuk dikirim ke server:', data);
            submitPostData(data);
        }

        function submitPostData(data) {
            const form = document.createElement('form');
            form.id = 'tempMlcForm';
            form.method = 'POST';
            form.action = '<?php echo base_url("report/generate_mlc_pdf"); ?>';
            form.target = '_blank';
            form.style.display = 'none';

            // Tambahkan data
            Object.keys(data).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = data[key];
                form.appendChild(input);
            });


            const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
            const csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

            if (csrfName && csrfHash) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = csrfName;
                csrfInput.value = csrfHash;
                form.appendChild(csrfInput);
            }

            // Tambahkan ke body dan submit
            document.body.appendChild(form);
            form.submit();

            // Cleanup setelah 1 detik
            setTimeout(() => {
                if (document.getElementById('tempMlcForm')) {
                    document.body.removeChild(form);
                }
            }, 1000);
        }
    });

    /*Print Form Mlc End */


    /*Print Form defbreafing Start */

    function generateBreafingPDF() {
        var idperson = $("#txtIdPerson").val();
        console.log(idperson);

        const data = {
            idperson: idperson
        };

        submitPostData_Breafing(data);
    }

        $(document).ready(function () {
            $('#btn-form-bereafing').on('click', function () {
                console.clear();
                generateBreafingPDF(); // ✅ sekarang kebaca
            });
        });

        function click_form_defbreafing() {
            var get_idperson = $("#txtIdPerson").val();

            if (!get_idperson) {
                alert("Person Empty!");
                return;
            }

            $("#modal-form-debriefing").modal("show");

            $.ajax({
                url: "<?php echo base_url('report/get_data_form_defbreafing'); ?>",
                type: "POST",
                data: {
                    idperson: get_idperson
                },
                dataType: "json",
                success: function(res) {
                    // console.log(res);
                    if (!res.success || !res.data) {
                        alert("Data tidak ditemukan!");
                        return;
                    }

                    // // // Contoh isi ke HTML
                    $("#val-vessel-defbreafing").text(res.data[0].nama_kapal);
                    $("#val-palabuhan-defbreafing").text(res.data[0].pelabuhan);
                    $("#val-jabatan-defbreafing").text(res.data[0].jabatan);
                    $("#val-telp-defbreafing").text(res.data[0].no_telp);
                    $("#val-namecrew-defbreafing").text(res.data[0].nama_crew);
                    $("#val-tgljoin-defbreafing").text(res.data[0].tgl_join);
                    $("#val-tglsignoff-defbreafing").text(res.data[0].tgl_signoff);
                    $("#val-siapjoin-defbreafing").text(res.data[0].tgl_join);
                    

                    // $("#txtJabatan").text(res.data.jabatan);
                    // $("#txtNamaCrew").text(res.data.nama_crew);
                    // $("#txtNoTelp").text(res.data.no_telp);
                    // $("#txtTglJoin").text(res.data.tgl_join);
                    // $("#txtTglSignOff").text(res.data.tgl_signoff);
                
                },
                error: function(xhr, status, error) {
                    console.log("AJAX Error:", xhr.responseText);
                    alert("Error fetching data.");
                }
            });
        }

        function generateBreafingPDF() {

            var idperson = $("#txtIdPerson").val();

            if (!idperson) {
                alert("ID Person kosong!");
                return;
            }

            // Ambil data dari tampilan (pakai .text())
            const data = {
                idperson: idperson,
                vessel     : $("#val-vessel-defbreafing").text(),
                pelabuhan  : $("#val-palabuhan-defbreafing").text(),
                jabatan    : $("#val-jabatan-defbreafing").text(),
                no_telp    : $("#val-telp-defbreafing").text(),
                nama_crew  : $("#val-namecrew-defbreafing").text(),
                tgl_join   : $("#val-tgljoin-defbreafing").text(),
                tgl_signoff: $("#val-tglsignoff-defbreafing").text(),
                siap_join  : $("#val-siapjoin-defbreafing").text()
            };

            console.log("Data dikirim ke server:", data);

            submitPostData_Breafing(data);
        }


        function submitPostData_Breafing(data) {
            const form = document.createElement('form');
            form.id = 'tempBreafingForm';
            form.method = 'POST';
            form.action = '<?php echo base_url("report/generatePDF_Breafing"); ?>';
            form.target = '_blank';
            form.style.display = 'none';

            // Tambahkan data
            Object.keys(data).forEach(key => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = data[key];
                form.appendChild(input);
            });


            const csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
            const csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

            if (csrfName && csrfHash) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = csrfName;
                csrfInput.value = csrfHash;
                form.appendChild(csrfInput);
            }

            // Tambahkan ke body dan submit
            document.body.appendChild(form);
            form.submit();

            // Cleanup setelah 1 detik
            setTimeout(() => {
                if (document.getElementById('tempBreafingForm')) {
                    document.body.removeChild(form);
                }
            }, 1000);
        }

    



    </script>
</head>


<style>
.long-line {

    width: 100%;
    border-bottom: 1px solid #000;

}

.statement-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 13px;
}

.statement-table th,
.statement-table td {
    border: 1px solid #000;
    padding: 6px;
    vertical-align: top;
}

.statement-table th {
    text-align: center;
    font-weight: bold;
}

.col-no {
    width: 40px;
    text-align: center;
}

.col-statement {
    width: auto;
    text-align: left;
}

.col-yes,
.col-no-check {
    width: 70px;
    text-align: center;
}

.check-box {
    width: 18px;
    height: 18px;
    border: 1.5px solid #000;
    display: inline-block;
}

.subtext {
    font-style: italic;
    font-size: 12px;
    margin-top: 3px;
}

.remarks-title {
    font-size: 13px;
    margin: 8px 0 4px 0;
}

.remarks-box {
    width: 100%;
    height: 70px;
    border: 1px solid #000;
    margin-bottom: 14px;
}

.sign-container {
    display: flex;
    gap: 20px;
    /* Jarak antar box */
    margin-top: 10px;
}

.sign-table-wrapper {
    flex: 1;
}

.sign-grid {
    width: 100%;
    border-collapse: collapse;
    text-align: center;
    font-size: 13px;
}

.sign-box {
    border: 1px solid #000;
    height: 90px;
    vertical-align: bottom;
    padding-bottom: 8px;
}
</style>

<body>

    <div class="container-fluid" style="background-color:#D4D4D4;min-height:500px;">
        <div class="form-panel" style="margin-top:5px;padding-bottom:15px;" id="idDataTable">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <legend style="text-align:right;margin-bottom:5px;">
                                <img id="idLoading" src="<?php echo base_url('assets/img/loading.gif');?>"
                                    style="margin-right:10px;display:none;">
                                <b><i>:: Report Data ::</i></b>
                            </legend>
                        </div>
                    </div>
                    <div class="row" style="margin-top:5px;">
                        <div class="col-md-5 col-xs-12">
                            <div class="row" style="margin-top:5px;">
                                <div class="col-md-8 col-xs-12">
                                    <input type="text" class="form-control input-sm" id="txtSearch"
                                        oninput="searchData();" placeholder="Crew Name..">
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <button class="btn btn-success btn-sm btn-block" title="Refresh"
                                        onclick="reloadPage();"><i class="fa fa-refresh"></i> Refresh</button>
                                </div>
                            </div>
                            <div class="row" style="margin-top:5px;height:510px;overflow: auto;" id="divIdDataTable">
                                <div class="col-md-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-border table-striped table-bordered table-condensed table-advance table-hover"
                                            style="background-color:#D7EAEC;width:100%;">
                                            <thead>
                                                <tr style="background-color:#067780;color:#FFF;height:30px;">
                                                    <th style="vertical-align:middle;width:10%;text-align:center;">No
                                                    </th>
                                                    <th style="vertical-align:middle;width:80%;text-align:center;">Crew
                                                    </th>
                                                    <th style="vertical-align:middle;width:10%;text-align:center;">#
                                                    </th>
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
                        <div class="col-md-7 col-xs-12">
                            <input type="hidden" id="txtIdPerson" value="">
                            <legend>
                                <div class="row">
                                    <div class="col-md-8 col-xs-12">
                                        <span>Principal</span>
                                        <span id="lblPickPerson" style="float:right;color:blue;"></span>
                                    </div>
                                </div>
                            </legend>
                            <div class="row">
                                <div class="col-md-7 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-12">
                                            <label for="slcCompanyPrins">Company</label>
                                            <span style="float:right;"><b>:</b></span>
                                        </div>
                                        <div class="col-md-8 col-xs-12">
                                            <select class="form-control input-sm" id="slcCompanyPrins">
                                                <?php echo $optCompany; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-3">
                                    <button class="btn btn-primary btn-sm btn-block" title="Cetak"
                                        onclick="printDataPrincipal();" id="btnPrintPrincipal">
                                        <i class="fa fa-print"></i> PRINT
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-success btn-sm btn-block" title="Cetak"
                                        onclick="transmital();" id="btnPrintTransmital" disabled="disabled">
                                        <i class="fa fa-print"></i> Transmital
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-danger btn-sm btn-block" title="Cetak" id="btnPrintTraining"
                                        disabled="disabled" data-toggle="modal" data-target="#trainingEvaluationModal">
                                        <i class="fa fa-print"></i> Training Evaluation
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak" id="btnPrintReport"
                                        disabled="disabled" data-toggle="modal" data-target="#crewEvaluationModal">
                                        <i class=" fa fa-print"></i> Report Evaluation
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak" id="btnListCrew"
                                        data-toggle="modal" data-target="#listCrewNewModal">
                                        <i class="fa fa-print"></i> List Applicant
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakPKLCrew();" id="btnPKLCrew">
                                        <i class="fa fa-print"></i> Print PKL
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakWagesCrew();" id="btnWagesCrew">
                                        <i class="fa fa-print"></i> Print Statement Of Wages
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakSPJCrew();" id="btnSPJCrew">
                                        <i class="fa fa-print"></i> Print Official Travel Letter
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakDataBankCrew();" id="btnStatementCrew">
                                        <i class="fa fa-print"></i> Print Data Bank Crew
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakIntroduction();" id="btnIntroductionCrew">
                                        <i class="fa fa-print"></i> Print Instroduction Letter
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakStatement();" id="btnIntroductionCrew">
                                        <i class="fa fa-print"></i> Print Statement of Employment
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakAcceptence();" id="btnIntroductionCrew">
                                        <i class="fa fa-print"></i> Print Acceptence Letter
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakCovid19();" id="btnIntroductionCrew">
                                        <i class="fa fa-print"></i> Print Covid-19 Prevention
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak" onclick="cetakLetter();"
                                        id="btnIntroductionCrew">
                                        <i class="fa fa-print"></i> Print Letter Statement
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="cetakSeafarerContract();" id="btnIntroductionCrew">
                                        <i class="fa fa-print"></i> Print Seafarer Contract Suntechno
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="listApproval();" id="btnIntroductionCrew">
                                        <i class="fa fa-list"></i> Approval Evaluation
                                    </button>
                                </div>
                                <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="click_form_mlc();">
                                        <i class="fa fa-print"></i> Print Form MLC
                                    </button>
                                </div>
                                 <div class="col-md-3" style="margin-top: 10px;">
                                    <button class="btn btn-info btn-sm btn-block" title="Cetak"
                                        onclick="click_form_defbreafing();">
                                        <i class="fa fa-print"></i> Print Form Debriefing
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="loadingOverlay" style="
        display: none;
        position: fixed;
        z-index: 99999;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(2px);
        justify-content: center;
        align-items: center;
        flex-direction: column;
        color: white;
        font-size: 1.5em;
        text-align: center;
        transition: opacity 0.3s ease;
        opacity: 0;
    ">
        <div style="margin-bottom: 15px;">
            <svg width="80" height="80" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg" stroke="#fff">
                <g fill="none" fill-rule="evenodd" stroke-width="4">
                    <circle cx="22" cy="22" r="20" stroke-opacity="0.3" />
                    <path d="M42 22c0-11.046-8.954-20-20-20">
                        <animateTransform attributeName="transform" type="rotate" from="0 22 22" to="360 22 22" dur="1s"
                            repeatCount="indefinite" />
                    </path>
                </g>
            </svg>
        </div>
        <div>Processing, Please Wait 😊.</div>
    </div>
</body>
<div class="modal fade" id="crewEvaluationModal" tabindex="-1" aria-labelledby="modalTitle">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Crew Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-primary mb-3" id="btnAddCrewEvaluation">
                    <i class='fa fa-plus-circle'></i> Add Data
                </button>
                <div id="formContainerCrew"
                    style="display: none; margin-top: 10px; opacity: 0; transition: opacity 0.3s ease-in-out;">
                    <form id="crewForm" onsubmit="return saveDataCrewEvaluation(event);">
                        <div class="row">
                            <div class="col-md-2 col-xs-12">
                                <label for="slcVesselHeader" style="font-size:12px;">Vessel :</label>
                                <select class="form-control input-sm" id="slcVesselHeader">
                                    <?php echo $optVessel; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Seafarer's Name</label>
                                <input type="text" class="form-control" id="txtSeafarerName">
                            </div>
                            <div class="col-md-2">
                                <label for="slcRankHeader" style="font-size:12px;">Rank:</label>
                                <select class="form-control input-sm" id="slcRankHeader">
                                    <?php echo $optRank; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date of Report</label>
                                <input type="text" class="form-control" id="txtDateOfReport">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Reporting Period From</label>
                                <input type="text" class="form-control" id="txtDateReportingPeriodFrom">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">To</label>
                                <input type="text" class="form-control" id="txtDateReportingPeriodTo">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 style="font-family: calibri; margin-bottom: 10px; font-weight: bold;">&bullet;
                                    Reason
                                    for the
                                    Report:</h4>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="reasonMidway" value="Y">
                                    <label class="form-check-label" for="reasonMidway"
                                        style="margin-right: 10px;">Midway
                                        through contract</label>

                                    <input class="form-check-input" type="checkbox" id="reasonSigningOff" value="Y">
                                    <label class="form-check-label" for="reasonSigningOff"
                                        style="margin-right: 10px;">Seafarer signing off vessel</label>

                                    <input class="form-check-input" type="checkbox" id="reasonLeaving" value="Y">
                                    <label class="form-check-label" for="reasonLeaving"
                                        style="margin-right: 10px;">Reporting crew leaving vessel</label>

                                    <input class="form-check-input" type="checkbox" id="reasonSpecialRequest" value="Y">
                                    <label class="form-check-label" for="reasonSpecialRequest"
                                        style="margin-right: 10px;">Special request</label>
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
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyAbility">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Safety Consciousness</td>
                                        <td><input type="radio" name="safety" value="4"></td>
                                        <td><input type="radio" name="safety" value="3"></td>
                                        <td><input type="radio" name="safety" value="2"></td>
                                        <td><input type="radio" name="safety" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifySafety">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Dependability & Integrity</td>
                                        <td><input type="radio" name="integrity" value="4"></td>
                                        <td><input type="radio" name="integrity" value="3"></td>
                                        <td><input type="radio" name="integrity" value="2"></td>
                                        <td><input type="radio" name="integrity" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyIntegrity">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Initiative</td>
                                        <td><input type="radio" name="initiative" value="4"></td>
                                        <td><input type="radio" name="initiative" value="3"></td>
                                        <td><input type="radio" name="initiative" value="2"></td>
                                        <td><input type="radio" name="initiative" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyInitiative">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Conduct</td>
                                        <td><input type="radio" name="conduct" value="4"></td>
                                        <td><input type="radio" name="conduct" value="3"></td>
                                        <td><input type="radio" name="conduct" value="2"></td>
                                        <td><input type="radio" name="conduct" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyConduct">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Ability to get on with others</td>
                                        <td><input type="radio" name="abilityGetOn" value="4"></td>
                                        <td><input type="radio" name="abilityGetOn" value="3"></td>
                                        <td><input type="radio" name="abilityGetOn" value="2"></td>
                                        <td><input type="radio" name="abilityGetOn" value="1"></td>
                                        <td><input type="text" class="form-control input-sm"
                                                id="txtIdentifyAbilityGetOn">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Appearance (+ uniforms)</td>
                                        <td><input type="radio" name="appearance" value="4"></td>
                                        <td><input type="radio" name="appearance" value="3"></td>
                                        <td><input type="radio" name="appearance" value="2"></td>
                                        <td><input type="radio" name="appearance" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyAppearance">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Sobriety</td>
                                        <td><input type="radio" name="sobriety" value="4"></td>
                                        <td><input type="radio" name="sobriety" value="3"></td>
                                        <td><input type="radio" name="sobriety" value="2"></td>
                                        <td><input type="radio" name="sobriety" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifySobriety">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>English Language</td>
                                        <td><input type="radio" name="english" value="4"></td>
                                        <td><input type="radio" name="english" value="3"></td>
                                        <td><input type="radio" name="english" value="2"></td>
                                        <td><input type="radio" name="english" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyEnglish">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Leadership (Officers)</td>
                                        <td><input type="radio" name="leadership" value="4"></td>
                                        <td><input type="radio" name="leadership" value="3"></td>
                                        <td><input type="radio" name="leadership" value="2"></td>
                                        <td><input type="radio" name="leadership" value="1"></td>
                                        <td><input type="text" class="form-control input-sm" id="txtIdentifyLeadership">
                                        </td>
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
                                <label class="form-label">Master Comments</label>
                                <textarea class="form-control" name="comments_master" rows="6"
                                    placeholder="Master's comments" id="txtMasterComments"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Officer Comments</label>
                                <textarea class="form-control" name="comments_officer" rows="6"
                                    placeholder="Reporting Officer's comments" id="txtOfficerComments"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">&bullet; Re-employ</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="txtReemploy" value="Y">
                                    <label class="form-check-label">Yes</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="txtReemploy" value="N">
                                    <label class="form-check-label">No</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">&bullet; Promote</label>
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
                                <label for="txtfullname" style="font-size:12px;">Fullname :</label>
                                <input type="text" class="form-control input-sm" id="txtfullname">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtreceived" style="font-size:12px;">Received by CM :</label>
                                <input type="text" class="form-control input-sm" id="txtreceived">
                            </div>
                            <div class="col-md-2 col-xs-12">
                                <label for="txtmastercoofullname" style="font-size:12px;">Master / COO Full
                                    Name:</label>
                                <input type="text" class="form-control input-sm" id="txtmastercoofullname">
                            </div>
                            <div class="col-md-2">
                                <label for="slcRank" style="font-size:12px;">Rank:</label>
                                <select class="form-control input-sm" id="slcRank">
                                    <?php echo $optRank; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Date of Receipt</label>
                                <input type="date" class="form-control" id="txtDateReceipt">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                            <input type="hidden" id="txtIdPerson" value="">
                            <input type="hidden" id="txtIdEditCrew" value="">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-success btn-block btn-xs"
                                    data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary btn-block btn-xs">Submit</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger btn-block btn-xs"
                                    id="btnCancelFormCrew">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive" id="tableContainerCrew" style="margin-top: 10px;">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr style="background-color:#067780; color:#FFF;">
                                <th style="text-align:center; width: 40px;" rowspan="2">No</th>
                                <th style="text-align:center; width: 100px;" rowspan="2">Vessel</th>
                                <th style="text-align:left; width: 100px;" rowspan="2">Seafarer's Name</th>
                                <th style="text-align:center; width: 100px;" rowspan="2">Rank</th>
                                <th style="text-align:center; width: 100px;" rowspan="2">Date of Period</th>
                                <th style="text-align:center;" colspan="2">Reporting Period</th>
                                <th style="text-align:center; width: 80px;" rowspan="2">Action</th>
                            </tr>
                            <tr style="background-color:#067780; color:#FFF;">
                                <th style="text-align:center; width: 80px; border-top: 1px solid white;">From</th>
                                <th style="text-align:center; width: 80px; border-top: 1px solid white;">To</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodyCrewEvaluation">
                            <?php echo isset($trCrewEvaluation) ? $trCrewEvaluation : ''; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="listCrewNewModal" class="modal fade" style="z-index: 1050;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 5px; background-color: #16839B;">
                <div class="row">
                    <div class="col-md-10 col-xs-10">
                        <h5 class="modal-title" style="color: #fff;margin-left:10px;"><i>:: List Applicant ::</i></h5>
                    </div>
                    <div class="col-md-2 col-xs-2">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            style="padding:7px;font-size:30px;opacity:1;color:#FFFFFF;">&times;</button>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row" style="margin-left: 5px; margin-bottom: 15px;">
                    <div class="col-md-2">
                        <label for="selectTanggalTopFourStart">Start Date :</label>
                        <input id="selectTanggalTopFourStart" class="form-control" placeholder="Select Start Date">
                    </div>
                    <div class="col-md-2">
                        <label for="selectTanggalTopFourEnd">End Date :</label>
                        <input id="selectTanggalTopFourEnd" class="form-control" placeholder="Select End Date">
                    </div>
                    <div class="col-md-3">
                        <label for="filterVessel"
                            style="font-weight:600;font-size:14px;display:block;margin-bottom:8px;">
                            Filter By Vessel:
                        </label>
                        <div id="checkboxVesselContainer"
                            style="border:1px solid #ccc; border-radius:6px; padding:12px; max-height:150px; overflow-y:auto; background:#fafafa;">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="BULK CARRIER"
                                    id="vesselBulk">
                                <label class="form-check-label" for="vesselBulk">BULK CARRIER</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="CARGO"
                                    id="vesselCargo">
                                <label class="form-check-label" for="vesselCargo">CARGO</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="GENERAL CARGO"
                                    id="vesselGeneral">
                                <label class="form-check-label" for="vesselGeneral">GENERAL CARGO</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="CONTAINER"
                                    id="vesselContainer">
                                <label class="form-check-label" for="vesselContainer">CONTAINER</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="TANKER PRODUCT"
                                    id="vesselTP">
                                <label class="form-check-label" for="vesselTP">TANKER PRODUCT</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="TANKER OIL"
                                    id="vesselTO">
                                <label class="form-check-label" for="vesselTO">TANKER OIL</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="CRUDE OIL"
                                    id="vesselCO">
                                <label class="form-check-label" for="vesselCO">CRUDE OIL</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="TANKER CHEMICAL"
                                    id="vesselTC">
                                <label class="form-check-label" for="vesselTC">TANKER CHEMICAL</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="TANKER GAS"
                                    id="vesselTG">
                                <label class="form-check-label" for="vesselTG">TANKER GAS</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="FLOATING CRANE"
                                    id="vesselFC">
                                <label class="form-check-label" for="vesselFC">FLOATING CRANE</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="TUG BOAT"
                                    id="vesselTB">
                                <label class="form-check-label" for="vesselTB">TUG BOAT</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="SUPPLY VESSEL"
                                    id="vesselSV">
                                <label class="form-check-label" for="vesselSV">SUPPLY VESSEL</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="CREW BOAT"
                                    id="vesselCB">
                                <label class="form-check-label" for="vesselCB">CREW BOAT</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="vessel[]" value="RORO/PASSENGER"
                                    id="vesselRORO">
                                <label class="form-check-label" for="vesselRORO">RORO/PASSENGER</label>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="selectedVessel" name="selectedVessel" value="">
                    <div class="col-md-3">
                        <label class="d-block mb-2" style="font-weight:600;font-size:14px;">
                            Select Rank :
                        </label>
                        <div id="checkboxRankContainer"
                            style="border:1px solid #ccc; border-radius:6px; padding:12px; max-height:300px; overflow-y:auto; background:#fafafa;">
                            <?php echo $checkboxRank; ?>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <label>&nbsp</label>
                        <button id="btnSearchTopFour" class="btn btn-primary btn-sm btn-block">Search</button>
                    </div>
                    <div class="col-md-1">
                        <label>&nbsp</label>
                        <button id="btnResetTopFour" class="btn btn-danger btn-sm btn-block"
                            style="display:none;">Reset</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div id="newApplicantChartContainer" style="min-height: 400px;"></div>
                    </div>
                    <div class="col-md-6">
                        <!-- Chart -->
                        <div id="talentPoolChartContainer" style="min-height: 400px;"></div>
                    </div>
                </div>

                <div style="display:flex; gap:7px; margin-bottom:1rem;">
                    <button id="btnOpenNewApplicants" class="btn btn-success btn-sm" style="flex:1;">
                        View All New Applicants
                    </button>
                    <button id="btnOpenQualifyApplicants" class="btn btn-warning btn-sm" style="flex:1;">
                        View All Qualify Applicants
                    </button>
                    <button id="btnOpenInterviewApplicants" class="btn btn-warning btn-sm" style="flex:1;">
                        View All Interview Applicants
                    </button>
                    <button id="btnOpenMCUApplicant" class="btn btn-danger btn-sm" style="flex:1;">
                        View All MCU Applicants
                    </button>
                    <button id="btnOpenPipelineApplicants" class="btn btn-danger btn-sm" style="flex:1;">
                        View All Pipeline Applicants
                    </button>
                </div>

                <div class="row" style="padding-top:10px;">
                    <div class="col-md-12">
                        <input type="text" id="selectBulan" class="form-control d-inline-block"
                            style="width:auto;display:inline-block;background-color:#FFFFFF;cursor:pointer;" readonly>
                        <button id="btnSearchBulan" class="btn btn-primary">Search</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div id="totalSubmitCV" style="min-height: 400px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="trainingEvaluationModal" tabindex="-1" aria-labelledby="modalTitle">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="padding: 10px;background-color:#16839B;">
                <h5 class="modal-title" id="modalTitle" style="color: white;">Training Evaluation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <button type="button" class="btn btn-primary" id="btnAdd"><i class='fa fa-plus-circle'></i> Add
                    Data</button>

                <div id="formContainer"
                    style="display: none; margin-top: 10px; opacity: 0; transition: opacity 0.3s ease-in-out;">
                    <form id="trainingForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="txtemployeeName">Employee Name</label>
                                    <input type="text" class="form-control" id="txtemployeeName" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtdesignation">Designation</label>
                                    <input type="text" class="form-control" id="txtdesignation" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtDateOfTraining">Date Of Training</label>
                                    <input type="date" class="form-control" id="txtDateOfTraining" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtplaceOfTraining">Place Of Training</label>
                                    <input type="text" class="form-control" id="txtplaceOfTraining" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtsubject">Subject</label>
                                    <input type="text" class="form-control" id="txtsubject" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtDateOfEvaluation">Date Of Evaluation</label>
                                    <input type="date" class="form-control" id="txtDateOfEvaluation" required>
                                </div>
                                <div class="form-group">
                                    <label for="txtevaluator">Evaluator Name & Designation</label>
                                    <input type="text" class="form-control" id="txtevaluator" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Employee Understanding with the job after training:</label>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score1" value="1"> 1
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score1" value="2"> 2
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score1" value="3"> 3
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score1" value="4"> 4
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Improvement for employee with Quality/Productivity and skill after
                                        training:</label>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <label style="di    splay: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score2" value="1"> 1
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score2" value="2"> 2
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score2" value="3"> 3
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score2" value="4"> 4
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Improvement for employee in initiations and idea after training:</label>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score3" value="1"> 1
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score3" value="2"> 2
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score3" value="3"> 3
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score3" value="4"> 4
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>General performance about this employee after training:</label>
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score4" value="1"> 1
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score4" value="2"> 2
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score4" value="3"> 3
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 5px;">
                                            <input type="checkbox" name="score4" value="4"> 4
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Suggestion for material and style to improve employee's job
                                        performance:</label>
                                    <textarea class="form-control" id="suggestion"></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Advise and expectation in the next training program:</label>
                                    <textarea class="form-control" id="advise"></textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <input type="hidden" id="txtIdEditTrain" value="">
                            <input type="hidden" id="txtIdPerson" value="">
                            <div class="col-md-4">
                                <button type="button" class="btn btn-success btn-block btn-xs"
                                    data-dismiss="modal">Close</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-primary btn-block btn-xs"
                                    onclick="saveDataTrainEvaluation();">Submit</button>
                            </div>
                            <div class="col-md-4">
                                <button type="button" class="btn btn-danger btn-block btn-xs"
                                    id="btnCancel">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive" id="tableContainer" style="margin-top: 10px;">
                    <table class="table table-bordered table-striped" id="tblTrainEvaluation">
                        <thead>
                            <tr style="background-color:#067780; color:#FFF; height:35px;">
                                <th style="text-align:center; width: 40px;">No</th>
                                <th style="text-align:left; padding-left:10px;">Employee Name</th>
                                <th style="text-align:center;">Designation</th>
                                <th style="text-align:center;">Date Of Training</th>
                                <th style="text-align:center;">Place Of Training</th>
                                <th style="text-align:center;">Subject</th>
                                <th style="text-align:center;">Date Of Evaluation</th>
                                <th style="text-align:center;">Evaluator Name & Designation</th>
                                <th style="text-align:center; width:250px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodyTraining">
                            <?php echo isset($trTraining) ? $trTraining : ''; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalAllApplicants" class="modal fade" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 98%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #067780; color: white;">
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
                        <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                            stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                dur="1s" repeatCount="indefinite" />
                        </circle>
                    </svg>

                    <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                        ⏳ Please wait... Processing data
                    </p>
                </div>
                <h5 class="modal-title" id="modalTitleApplicants" style="color: white; float:right;">Data New Applicant
                </h5>
                <button class=" btn btn-default btn-sm" data-target="#listCrewNewModal" data-dismiss="modal"
                    id="btnBack"><i class="fa fa-reply-all"></i>&nbsp&nbspBack</button>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                <div id="containerReady" data-search="">
                    <div class="form-group" style="position: relative; margin-top: 10px;">
                        <input type="text" class="form-control" placeholder="Search position applied, name, email"
                            onkeyup="searchTable(this, 'DataReady')"
                            style="padding-left: 35px; transition: all 0.3s ease-in-out; border: 1px solid #ccc; border-radius: 30px;">
                        <i class="fas fa-search"
                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #aaa;"></i>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="tableDataReady" data-current-page="1"
                        style="font-size: 13px;">
                        <thead class="thead-dark text-center">
                            <tr style="background-color:#067780; color:white;">
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">No</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Email</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;" class="fullname">
                                    Fullname</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Born Place</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Born&nbspDate</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Phone</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;"
                                    class="position-applied">Position Applied</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Diploma</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Experience</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;"
                                    class="pengalaman-jenis-kapal">Vessel Type Experience</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Foreign Crew</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Last Salary</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Prev Join (Andhika)
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Join Date
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Date&nbspSubmit</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodylistCrewNewModal"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalPipelineCrew" class="modal fade" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 98%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #067780; color: white;">
                <h5 class="modal-title" id="modalTitleApplicants" style="color: white; float:right;">Data Pipeline crew
                </h5>
                <button class="btn btn-default btn-sm" id="btnBackPipeline" data-target="#listCrewNewModal"><i
                        class="fa fa-reply-all"></i>&nbsp&nbspBack</button>

            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                <div id="containerPipeline" data-search="" data-gender="">
                    <div class="form-row" style="margin-top: 10px; margin-bottom: 10px;">
                        <div class="col-md-3" style="margin-bottom: 10px;">
                            <select class="form-control" id="filterGenderPipeline" onchange="loadPageDataPipeline(1)">
                                <option value="">-- All Gender --</option>
                                <option value="P">Perempuan</option>
                                <option value="L">Laki-laki</option>
                            </select>
                        </div>
                        <div class="col-md-6" style="position: relative;">
                            <input type="text" class="form-control" placeholder="Search position applied, name, email"
                                onkeyup="searchTable(this, 'DataPipeline')"
                                style="padding-left: 35px; border: 1px solid #ccc; border-radius: 30px;">
                            <i class="fas fa-search"
                                style="position: absolute; top: 50%; transform: translateY(-50%); color: #aaa; margin-left: 15px;"></i>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableDataPipelineCrew" data-current-page="1"
                        style="font-size: 13px;">
                        <thead class="thead-dark text-center">
                            <tr style="background-color:#067780; color:white;">
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">No</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;" class="email">Email
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;" class="fullname">
                                    Fullname</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Born Place</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Born&nbspDate</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Phone</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;"
                                    class="position-applied">Position Applied</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Diploma</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Experience</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;"
                                    class="pengalaman-jenis-kapal">Vessel Type</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Foreign Crew</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Last Salary</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Prev Join (Andhika)
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Join Date
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Date&nbspSubmit</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodyPipelineCrew"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalInterviewCrew" class="modal fade" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 98%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #067780; color: white;">
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
                        <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                            stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                dur="1s" repeatCount="indefinite" />
                        </circle>
                    </svg>

                    <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                        ⏳ Please wait... Processing data
                    </p>
                </div>

                <h5 class="modal-title" id="modalTitleApplicants" style="color: white; float:right;">Data Interview Crew
                </h5>

                <button class="btn btn-default btn-sm" id="btnBackInterview" data-target="#listCrewNewModal"><i
                        class="fa fa-reply-all"></i>&nbsp&nbspBack</button>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                <div id="containerInterview" data-search="">
                    <div class="form-group" style="position: relative; margin-top: 10px;">
                        <input type="text" class="form-control" placeholder="Search position applied, name, email"
                            onkeyup="searchTable(this, 'DataInterview')"
                            style="padding-left: 35px; transition: all 0.3s ease-in-out; border: 1px solid #ccc; border-radius: 30px;">
                        <i class="fas fa-search"
                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #aaa;"></i>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableDataInterviewCrew" data-current-page="1"
                        style="font-size: 13px;">
                        <thead class="thead-dark text-center">
                            <tr style="background-color:#067780; color:white;">
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">No</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;" class="email">Email
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;" class="fullname">
                                    Fullname</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Born Place</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Born&nbspDate</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Phone</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;"
                                    class="position-applied">Position Applied</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Diploma</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Experience</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;"
                                    class="pengalaman-jenis-kapal">Vessel Type</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Foreign Crew</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Last Salary</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Prev Join (Andhika)
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Join Date
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Date&nbspSubmit</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodyInterviewCrew"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNotQualifyLayer1">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content"
            style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1); position: relative;">
            <div class="modal-header"
                style="background-color: #067780; color: white; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <div id="idLoadingSpinnerLayer1" style="
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
                        <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                            stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                dur="1s" repeatCount="indefinite" />
                        </circle>
                    </svg>

                    <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                        ⏳ Please wait... Processing data
                    </p>
                </div>
                <h5 class="modal-title" id="modalNotQualifyLabel"
                    style="margin: 0; display: flex; align-items: center; color:white;">
                    <i class="fas fa-user-times" style="margin-right: 8px; color: white;"></i> Reason for Not Qualify
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="font-size: 1.4rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 20px;">
                <div class="row">
                    <div class="col-md-4">
                        <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Nama Pelamar</p>
                        <p id="modalNotQualifyNameLayer1" style="font-weight: bold; color: #333; margin-bottom: 15px;">
                            <i class="fas fa-user" style="margin-right: 8px;"></i> -
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Posisi yang Dilamar</p>
                        <p id="modalNotQualifyPositionLayer1" style="color: #333; margin-bottom: 15px;">
                            <i class="fas fa-briefcase" style="margin-right: 8px;"></i> -
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Pengalaman Terakhir</p>
                        <p id="modalNotQualifyLastExperienceLayer1" style="color: #333; margin-bottom: 15px;">
                            <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i> -
                        </p>
                    </div>
                </div>
                <div id="certificateCheckboxList" style="margin-top: 20px;">
                    <label style="font-weight: bold; color: #555;">Sertifikat yang Tidak Dipenuhi</label>
                    <div id="certificateCheckboxContainer" class="row" style="padding-left: 10px;">
                    </div>
                </div>
                <div>
                    <label for="txtNotQualifyReason1"
                        style="font-weight: bold; color: #555; margin-bottom: 8px; display: block;">Alasan Tidak
                        Memenuhi Kualifikasi</label>
                    <textarea id="txtNotQualifyReason1" class="form-control" rows="4"
                        placeholder="Tuliskan alasan secara jelas dan profesional..."
                        style="width: 100%; border-radius: 6px; border: 1px solid #ccc; padding: 10px;"></textarea>
                    <input type="hidden" id="hiddenCrewIdLayer1">
                </div>
                <div>
                    <label style="font-weight: bold; color: #555;">Saran Rank</label>
                    <div id="rankCheckboxContainer" class="row" style="padding-left: 10px;">
                        <?php echo $checkboxRank; ?>
                    </div>
                </div>
            </div>

            <div class="modal-footer"
                style="background-color: #f8f9fa; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; padding: 15px 20px; border-top: 1px solid #e0e0e0;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    style="border-radius: 6px; padding: 6px 16px;" id="btnCancelNotQualifiedLayer1">
                    <i class="fas fa-times-circle" style="margin-right: 5px;"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="submitNotQualifiedLayer1()"
                    style="border-radius: 6px; padding: 6px 16px;">
                    <i class="fas fa-paper-plane" style="margin-right: 5px;"></i> Submit
                </button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalNotQualify">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <div class="modal-header"
                style="background-color: #067780; color: white; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <div id="idLoadingSpinnerNotQualify" style="
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
                        <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                            stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                dur="1s" repeatCount="indefinite" />
                        </circle>
                    </svg>

                    <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                        ⏳ Please wait... Processing data
                    </p>
                </div>
                <h5 class="modal-title" id="modalNotQualifyLabel"
                    style="margin: 0; display: flex; align-items: center; color:white;">
                    <i class="fas fa-user-times" style="margin-right: 8px; color: white;"></i> Reason for Not Qualify
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="font-size: 1.4rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 20px;">
                <div class="row">
                    <div class="col-md-12">
                        <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Nama Pelamar</p>
                        <p id="modalNotQualifyName" style="font-weight: bold; color: #333; margin-bottom: 15px;">
                            <i class="fas fa-user" style="margin-right: 8px;"></i> -
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Posisi yang Dilamar</p>
                        <p id="modalNotQualifyPosition" style="color: #333; margin-bottom: 15px;">
                            <i class="fas fa-briefcase" style="margin-right: 8px;"></i> -
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Posisi yang Disarankan</p>
                        <p id="modalNotQualifyPositionExisting" style="color: #333; margin-bottom: 15px;">
                            <i class="fas fa-briefcase" style="margin-right: 8px;"></i> -
                        </p>
                    </div>
                    <div class="col-md-4">
                        <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Pengalaman Terakhir</p>
                        <p id="modalNotQualifyLastExperience" style="color: #333; margin-bottom: 15px;">
                            <i class="fas fa-calendar-alt" style="margin-right: 8px;"></i> -
                        </p>
                    </div>
                </div>
                <div>
                    <label for="txtNotQualifyReason"
                        style="font-weight: bold; color: #555; margin-bottom: 8px; display: block;">Alasan Tidak
                        Memenuhi Kualifikasi</label>
                    <textarea id="txtNotQualifyReason" class="form-control" rows="4"
                        placeholder="Tuliskan alasan secara jelas dan profesional..."
                        style="width: 100%; border-radius: 6px; border: 1px solid #ccc; padding: 10px;"></textarea>
                    <input type="hidden" id="hiddenCrewId">
                </div>
            </div>

            <div class="modal-footer"
                style="background-color: #f8f9fa; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; padding: 15px 20px; border-top: 1px solid #e0e0e0;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    style="border-radius: 6px; padding: 6px 16px;" id="btnCancelNotQualified">
                    <i class="fas fa-times-circle" style="margin-right: 5px;"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="submitNotQualifiedCrew()"
                    style="border-radius: 6px; padding: 6px 16px;">
                    <i class="fas fa-paper-plane" style="margin-right: 5px;"></i> Submit
                </button>
            </div>
        </div>
    </div>
</div>

<div id="modalQualifiedCrew" class="modal fade" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 98%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #067780; color: white;">
                <div id="idLoadingSpinnerQualifiedCrew" style="
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
                        <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                            stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                dur="1s" repeatCount="indefinite" />
                        </circle>
                    </svg>

                    <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                        ⏳ Please wait... Processing data
                    </p>
                </div>
                <h5 class="modal-title" id="modalTitleApplicants" style="color: white; float:right;">Data Qualified Crew
                </h5>
                <button class="btn btn-default btn-sm" id="btnBackQualified" data-target="#listCrewNewModal"><i
                        class="fa fa-reply-all"></i>&nbsp&nbspBack</button>

            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                <div id="containerQualifiedCrew" data-search="">
                    <div class="form-group" style="position: relative; margin-top: 10px;">
                        <input type="text" class="form-control" placeholder="Search position applied, name, email"
                            onkeyup="searchTable(this, 'DataQualifiedCrew')"
                            style="padding-left: 35px; transition: all 0.3s ease-in-out; border: 1px solid #ccc; border-radius: 30px;">
                        <i class="fas fa-search"
                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #aaa;"></i>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableDataQualifiedCrew" data-current-page="1"
                        data-sort-by="" data-sort-order=""
                        style="font-size:13px; border-collapse:collapse; width:100%;">
                        <thead class="thead-dark text-center">
                            <tr style="background-color:#067780; color:white;">
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">No</th>

                                <th onclick="sortTable('email')" data-column="email"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Email <span class="sort-indicator" style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th onclick="sortTable('fullname')" data-column="fullname"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Fullname <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th onclick="sortTable('born_place')" data-column="born_place"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Born Place <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th onclick="sortTable('born_date')" data-column="born_date"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Born Date <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Phone</th>

                                <th onclick="sortTable('position_applied')" data-column="position_applied"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Position Applied <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th onclick="sortTable('position_existing')" data-column="position_existing"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Position Existing <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th onclick="sortTable('ijazah_terakhir')" data-column="ijazah_terakhir"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Diploma <span class="sort-indicator" style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th onclick="sortTable('last_experience')" data-column="last_experience"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Experience <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th onclick="sortTable('pengalaman_jeniskapal')" data-column="pengalaman_jeniskapal"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Vessel Type <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Foreign Crew</th>

                                <th onclick="sortTable('last_salary')" data-column="last_salary"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Salary <span class="sort-indicator" style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Prev Join (Andhika)
                                </th>

                                <th onclick="sortTable('join_date')" data-column="join_date"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Join Date <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th onclick="sortTable('submit_cv')" data-column="submit_cv"
                                    style="vertical-align:middle;text-align:center;font-size:12px;cursor:pointer;position:relative;user-select:none;"
                                    onmouseover="this.style.backgroundColor='#055a61'"
                                    onmouseout="this.style.backgroundColor='#067780'">
                                    Date Submit <span class="sort-indicator"
                                        style="margin-left:5px;font-size:10px;"></span>
                                </th>

                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodyQualifiedCrew"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNotReff" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-header" style="background-color: #067780">
                <div id="idLoadingSpinnerNotReff" style="
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
                        <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                            stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                dur="1s" repeatCount="indefinite" />
                        </circle>
                    </svg>

                    <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                        ⏳ Please wait... Processing data
                    </p>
                </div>
                <h5 class="modal-title" style="color: white;">Alasan:</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="notReffId">
                <div class="form-group">
                    <label for="notReffReason">Alasan</label>
                    <textarea class="form-control" id="notReffReason" rows="3"
                        placeholder="Tuliskan alasan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="submitNotReff()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div id="modalMCUCrew" class="modal fade" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 98%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #067780; color: white;">
                <div id="idLoadingSpinnerMCU" style="
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
                        <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                            stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                dur="1s" repeatCount="indefinite" />
                        </circle>
                    </svg>

                    <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                        ⏳ Please wait... Processing data
                    </p>
                </div>

                <h5 class="modal-title" id="modalTitleApplicants" style="color: white; float:right;">Data MCU Crew
                </h5>

                <button class="btn btn-default btn-sm" id="btnBackInterview" data-target="#listCrewNewModal"><i
                        class="fa fa-reply-all"></i>&nbsp&nbspBack</button>
            </div>
            <div class="modal-body" style="max-height: 80vh; overflow-y: auto;">
                <div id="containerMCU" data-search="">
                    <div class="form-group" style="position: relative; margin-top: 10px;">
                        <input type="text" class="form-control" placeholder="Search position applied, name, email"
                            onkeyup="searchTable(this, 'DataMCUcrew')"
                            style="padding-left: 35px; transition: all 0.3s ease-in-out; border: 1px solid #ccc; border-radius: 30px;">
                        <i class="fas fa-search"
                            style="position: absolute; top: 50%; left: 10px; transform: translateY(-50%); color: #aaa;"></i>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="tableDataMCUCrew" data-current-page="1"
                        style="font-size: 13px;">
                        <thead class="thead-dark text-center">
                            <tr style="background-color:#067780; color:white;">
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">No</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;" class="email">Email
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;" class="fullname">
                                    Fullname</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Born Place</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Born&nbspDate</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Phone</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;"
                                    class="position-applied">Position Applied</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Diploma</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Experience</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;"
                                    class="pengalaman-jenis-kapal">Vessel Type</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Foreign Crew</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Last Salary</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Prev Join (Andhika)
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Join Date
                                </th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Date&nbspSubmit</th>
                                <th style="vertical-align:middle;text-align:center;font-size:12px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="idTbodyMCUCrew"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalQualifyPipeline">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
            <div class="modal-header"
                style="background-color: #067780; color: white; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                <div id="idLoadingSpinnerQualifyPipeline" style="
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
                        <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                            stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                            <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                dur="1s" repeatCount="indefinite" />
                        </circle>
                    </svg>

                    <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                        ⏳ Please wait... Processing data
                    </p>
                </div>
                <h5 class="modal-title" id="modalQualifyPipelineLabel"
                    style="margin: 0; display: flex; align-items: center; color:white;">
                    <i class="fas fa-user-check" style="margin-right: 8px; color: white;"></i> Qualify
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                    style="font-size: 1.4rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding: 20px;">
                <div style="margin-bottom: 20px;">
                    <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Candidate Name</p>
                    <p id="modalQualifyPipelineName" style="font-weight: bold; color: #333; margin-bottom: 15px;">
                        <i class="fas fa-user" style="margin-right: 8px;"></i>
                    </p>

                    <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;">Current Rank Apply</p>
                    <p id="modalQualifyPipelinePosition" style="color: #333; margin-bottom: 15px;">
                        <i class="fas fa-briefcase" style="margin-right: 8px;"></i>
                    </p>

                    <div class="form-group mt-2">
                        <label for="rankSelectPipeline" style="font-weight:600;">Select Rank For Downgrade:</label>
                        <select class="form-control form-control-sm" id="rankSelectPipeline" name="rankSelectPipeline">

                        </select>
                    </div>

                    <p style="margin-bottom: 5px; color: #6c757d; font-size: 13px;"><i class="fas fa-ship"
                            style="margin-right: 8px; color: black;"></i>Vessel Type Experience</p>
                    <div id="modalQualifyPipelineVesselTypeExperience" style="color: #333; margin-bottom: 15px;">
                        <i class='fas fa-ship' style='margin-right:8px;'></i>
                    </div>
                </div>
                <input type="hidden" id="hiddenCrewIdPipeline">
            </div>

            <div class="modal-footer"
                style="background-color: #f8f9fa; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; padding: 15px 20px; border-top: 1px solid #e0e0e0;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    style="border-radius: 6px; padding: 6px 16px;" id="btnCancelQualifiedPipeline">
                    <i class="fas fa-times-circle" style="margin-right: 5px;"></i> Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="submitQualifiedCrewPipeline()"
                    style="border-radius: 6px; padding: 6px 16px;">
                    <i class="fas fa-paper-plane" style="margin-right: 5px;"></i> Submit
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUploadMCU" style="z-index:1080 !important;">
    <div class="modal-dialog modal-dialog-centered" style="z-index:1080 !important;">
        <div class="modal-content" style="position:relative; z-index:1080 !important;">
            <form id="formUploadMCU" enctype="multipart/form-data" method="post" action="javascript:void(0);">
                <div class="modal-header" style="background-color:#067780; z-index:1080 !important;">
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
                            <circle cx="25" cy="25" r="20" fill="none" stroke="white" stroke-width="5"
                                stroke-linecap="round" stroke-dasharray="31.4 31.4" transform="rotate(-90 25 25)">
                                <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25"
                                    dur="1s" repeatCount="indefinite" />
                            </circle>
                        </svg>

                        <p style="margin-top:20px; font-size:16px; color:#fff; font-weight:bold; text-align:center;">
                            ⏳ Please wait... Processing data
                        </p>
                    </div>
                    <h5 class="modal-title text-white" style="color:white;">Upload File MCU</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                        style="color:white; opacity:1;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body" style="z-index:1080 !important;">
                    <input type="hidden" name="applicantId" id="mcuApplicantId">
                    <div class="form-group">
                        <label for="file_mcu">Pilih File MCU (PDF)</label>
                        <input type="file" name="file_mcu" id="file_mcu" accept=".pdf" required class="form-control">
                    </div>
                </div>

                <div class="modal-footer" style="z-index:1080 !important;">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload & Lanjut MCU</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="pklModal" tabindex="-1" role="dialog" aria-labelledby="pklModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" style="max-width: 90%;" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="pklContent">
                    <div id="pklLoading" style="display:none;">
                        <div class="text-center">
                            <i class="fa fa-spinner fa-spin fa-3x"></i><br>Loading PKL...
                        </div>
                    </div>

                    <h2 style="text-align:center; margin:0; font-weight:bold;">SEAFARER EMPLOYMENT AGREEMENT</h2>
                    <h3 style="text-align:center;">Between</h3>
                    <p style="text-align:center; font-weight:bold;" id="txtCompanyName"></p>
                    <p style="text-align:center;">And</p>
                    <p style="text-align:center; font-weight:bold;">An Indonesian Citizen</p> <br><br>

                    <p>
                        Today on………………………………….have came to me
                        …....................................................................as Head of Section<br>
                        Pada hari ini.......................................telah datang kepada
                        saya..................................................................Pejabat Penyijil
                        Seaworthiness,
                    </p><br>

                    <p>
                        for and on behalf of <b>THE HARBOUR MASTER and PORT AUTHORITY of TG. PRIOK</b><br>
                        dengan ini mewakili atas nama <b>KANTOR KESYAHBANDARAN dan OTORITAS PELABUHAN TG. PRIOK.</b>
                    </p>

                    <p>
                        Mrs. <b>EVA MARLIANA</b> as <b>CREWING MANAGER</b> domicile at Menara Kadin Indonesia Floor 20th
                        unit D Jl. HR Rasuna Said
                        Blok X-5 Kav.2-3 Kuningan Jakarta 12950 Indonesia<br>
                        Saudari EVA MARLIANA jabatan CREWING MANAGER berdomisili di Menara Kadin Indonesia Lantai 20
                        Rasuna Said Blok X-5 Kav.2-3 Kuningan Jakarta 12950 Indonesia,
                    </p><br>

                    <p>
                        who state in terms of acting for and on behalf of the<br>
                        dalam hal ini bertindak untuk dan atas nama perusahaan pelayaran<br>
                        shipping company <b>PT <span id="txtShippingCompanyName"></span></b>
                        domicile at Menara Kadin Indonesia Floor 20th unit D Jl. HR Rasuna Said Blok X-5 Kav.2-3
                        Kuningan Jakarta 12950 Indonesia<br>
                        perusahaan pelayaran <b>PT <span id="txtShippingCompanyNameIndonesia"></span></b>
                        berdomisili di Rasuna Said Blok X-5 Kav.2-3 Kuningan Jakarta 12950 Indonesia,
                    </p><br>

                    <p>
                        hereinafter referred as the COMPANY and<br>
                        selanjutnya disebut PERUSAHAAN dan seorang Bernama<br>
                        hereinafter called the seafarer
                    </p>

                    <div style="margin-top:10px;">
                        <p style="margin:4px 0;">
                            dalam hal ini disebut Pelaut (name) :
                            <b id="seafarerName"></b>
                        </p>
                        <label>Date of Birth:</label>
                        <input type="text" id="txtDoBInput" name="txtDoB" class="form-control">

                        <p style="margin:4px 0;" id="txtPoB">Place of Birth/Tempat Lahir : &lt;&lt;Tempat Lahir&gt;&gt;
                        </p>

                        <label>Seafarer Code:</label>
                        <input type="text" id="txtSeafarerCodeInput" name="txtSeafarerCode" class="form-control">

                        <label>Home Address:</label>
                        <input type="text" id="txtAddressInput" name="txtAddress" class="form-control">

                    </div>

                    <div id="dataPerson">
                        <label>Passport No / No. Paspor:</label>
                        <input type="text" id="txtPassportNoInput" class="form-control" style="margin-bottom:6px;">

                        <label>Seaman Book No / No. Buku Pelaut:</label>
                        <input type="text" id="txtSeamanBookNoInput" class="form-control" style="margin-bottom:6px;">
                    </div>

                    <p>
                        Whereby the following terms and conditions of employment are mutually agreed upon.<br>
                        Dalam hal mana, syarat-syarat serta kondisi pengerjaan berikut telah disepakati.
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE I : ENGAGEMENT
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL I : PENGERJAAN</p>

                    <p>
                        The Company will engage the Seafarer in accordance with this Seafarer Employment Agreement, its
                        enclosure and amendments (if any), and to be executed with utmost good faith.<br>
                        <i>Perusahaan akan mempekerjakan Pelaut sesuai dengan Perjanjian Kerja Pelaut ini dengan
                            lampiran-lampiran dan perubahan-perubahan (bila ada), dan akan dilaksanakan dengan itikad
                            yang sebaik-baiknya.</i>
                    </p><br>

                    <p>
                        During the period this Seafarer Employment Agreement, the Seafarer shall be employed by the
                        Company<br>
                        <i>Selama masa berlakunya Perjanjian Kerja Pelaut ini. Pelaut akan dipekerjakan oleh
                            Perusahaan.</i>
                    </p>

                    <div style="margin-top:10px;">
                        <p style="margin:4px 0;">
                            On board the / di atas Kapal :
                            <select id="txtVesselFor"
                                style="width:260px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;"></select>
                        <div id="txtVesselForLabel" style="margin-top:8px;"></div>
                        </p>

                        <p style="margin:4px 0;">
                            Flag/Bendera :
                            <input type="text" id="txtFlag" value="Indonesia"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;">
                        </p>

                        <p style="margin:4px 0;">
                            IMO No :
                            <input type="text" id="txtIMO" placeholder="<<IMO>>"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;">
                        </p>

                        <p style="margin:4px 0;">
                            GRT/HP :
                            <input type="text" id="txtGRT" placeholder="<<GRT/HP>>"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;">
                        </p>

                        <p style="margin:4px 0;">
                            Safety Certificate/SERKES :
                            <input type="text" id="txtSafetyCert" placeholder="<<SERKES>>"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;">
                        </p>

                        <p style="margin:4px 0;">
                            Certificate of Competency/SERPEL :
                            <input type="text" id="txtCompetencyCert" placeholder="<<SERPEL>>"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;">
                        </p>
                    </div>


                    <div style="page-break-after: always;"></div>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:10px;">
                        ARTICLE II : EFFECTIVE DATE AND DURATION OF AGREEMENT
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL II : MULAI BERLAKUNYA DAN JANGKA WAKTU
                        PERJANJIAN</p>

                    <p>
                        a. Effective date: this contract and all its provision shall take effect on ………………………………..<br>
                        <i>Tanggal berlakunya: Perjanjian ini dan semua ketentuan-ketentuannya akan mulai berlaku pada
                            tanggal
                            ………………………………..</i>
                    </p>

                    <p style="margin-top:10px; line-height:1.5;">
                        b. Duration: This contract shall continue to be valid for
                        <input type="number" name="txtduration" id="txtduration" min="1" max="60" placeholder="Months"
                            style="width:80px; text-align:center; border:1px solid #aaa; border-radius:4px; padding:2px 4px; font-size:14px;">
                        MONTHS /
                        ……………………………….. unless terminated by either party upon 30 (thirty) days written notice to the
                        other part with a 30-days
                        notice prior to termination.<br>
                        <i>
                            Masa berlakunya: Perjanjian ini akan tetap berlaku selama
                            <span id="durationText">____</span> bulan /
                            ………………………………... atau diakhiri oleh salah satu pihak dengan pemberitahuan tertulis 30 (tiga
                            puluh) hari sebelumnya
                            kepada pihak yang lain.
                        </i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE III : WAGES AND OVERTIME
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL III : GAJI DAN UPAH LEMBUR</p>

                    <p>
                        During the period of this Seafarer Employment Agreement, the Seafarer shall be employed by the
                        Company in the capacity of <b><span id="txtRank"></span></b><br>
                        <i>Selama masa berlakunya Perjanjian Kerja Pelaut ini, Pelaut akan dipekerjakan oleh
                            Perusahaan
                            dalam jabatan sebagai <b><span id="txtRankOther"></span></b></i>
                    </p>

                    <div style="font-family:Inter, sans-serif;line-height:1.6;">
                        <p style="margin-bottom:10px;">
                            and be paid a monthly basic wages of Rp:
                            <input type="number" id="txtBasicWage" placeholder="Enter Basic Wage"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;text-align:right;">
                            <br><i>dan akan dibayarkan gaji pokok bulanan sebesar</i>
                        </p>

                        <p style="margin-bottom:10px;">
                            Fix Overtime Rp:
                            <input type="number" id="txtFixOvertime" placeholder="Enter Fix Overtime"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;text-align:right;">
                            <br><i>upah lembur</i>
                        </p>

                        <p style="margin-bottom:10px;">
                            Leave Pay Rp:
                            <input type="number" id="txtLeavePay" placeholder="Enter Leave Pay"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;text-align:right;">
                            <br><i>uang cuti</i>
                        </p>

                        <p id="tankerAllowanceWrapper" style="margin-bottom:10px; display:none;">
                            Tanker Allowance Rp:
                            <input type="number" id="txtTankerAllowance" placeholder="Enter Tanker Allowance"
                                style="width:200px;margin-left:6px;padding:4px;border:1px solid #ccc;border-radius:6px;text-align:right;">
                            <br><i>Tunjangan Kapal Tanker</i>
                        </p>

                        <div style="margin-top:15px;">
                            <i>Uang pengganti hari libur</i><br>
                            Total wages Rp <b id="txtTotalWages">0</b><br>
                            <i>Total gaji</i>
                        </div>
                    </div>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE IV : ALLOTMENT
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL IV : UANG DELEGASI</p>

                    <p>
                        1. The Seafarer covered by this Seafarer Employment Agreement should file, either with the
                        Company or the Master of the vessel a signed
                        allotment not to be applied against a minimum of 80% of the accrued basic wages.<br>
                        <i>Pelaut yang dilindungi oleh Perjanjian Kerja Pelaut ini harus mengajukan baik kepada
                            Perusahaan atau kepada Nakhoda kapal, sesuai nota
                            delegasi yang ditandatangani yang akan diperhitungkan dengan upah sebesar paling sedikit 80%
                            dari upah pokok sebulan.</i>
                    </p><br>

                    <p>
                        2. The Company shall thereupon arrange to remit a monthly allotment payable in IDR or its
                        equivalent in local currency to the person named in the
                        allotment note.<br>
                        <i>Perusahaan akan mengatur pengiriman delegasi bulanan dalam mata uang rupiah atau jumlah yang
                            sama nilainya dalam mata uang setempat,
                            kepada orang yang namanya disebut dalam nota delegasi.</i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE V : WORKING HOURS
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL V : JAM KERJA</p>

                    <p>
                        <b>1. Day Worker/Pekerjaan Harian</b><br>
                        The hours of work day workers shall be 8 (eight) hours per day Monday through Friday preferably
                        between 8 AM to 5 PM, and 4 (four) hours
                        per day on Saturday between 8 AM to 12 Noon.<br>
                        <i>Jam kerja bagi pekerja harian adalah 8 (delapan) jam sehari dimulai Senin sampai dengan
                            Jumat, sebaiknya antara 8 pagi sampai jam 5 sore,
                            dan 4 (empat) jam sehari pada hari Sabtu yang sebaiknya antara jam 8 pagi sampai jam 12
                            tengah hari.</i>
                    </p><br>

                    <p>
                        <b>2. Regular Watch./Jaga Biasa</b><br>
                        <b>Deck Department and Engine Department</b><br>
                        <b><i>Bagian Deck dan Bagian Mesin</i></b><br>
                        In port, crew members of these departments shall stand their regular watches as required by the
                        Master of the vessel. Overtime
                        rates shall apply for watches stood of work performed in port on Saturday afternoon, Sunday and
                        Holidays.<br>
                        At sea, crew members of these departments shall stand their regular watches as required by the
                        Master of the vessel.<br>
                        <i>Di Pelabuhan awak kapal wajib menjalankan tugas jaga biasa sesuai perintah Nakhoda kapal.
                            Upah lembur akan diberlakukan untuk jaga
                            yang dilakukan atau pekerjaan yang dilaksanakan di pelabuhan pada hari Sabtu sesudah tengah
                            hari, pada hari Minggu dan Hari Raya Resmi.
                            Di laut, awak kapal bagian ini wajib menjalankan tugas jaga biasa sesuai perintah Nakhoda
                            kapal.</i>
                    </p>

                    <!-- Pemisah halaman -->
                    <div style="page-break-after: always;"></div>

                    <!-- ========================== HALAMAN 3 ========================== -->

                    <p><b><i>3. Catering Department / Bagian Pelayanan</i><br></b>
                        The working hours of Catering Department members shall be 8 (eight) hours each day in a spread
                        preferably between 6 AM to 7 PM.
                        When the crewmembers of the Catering Department are on day work, the hours of work shall
                        preferably between 8 AM to 12 Noon and 1 PM to 5 PM.<br>
                        <i>Jam kerja awak kapal bagian pelayanan adalah 8 (delapan) jam sehari sebaiknya di rentang
                            antara jam 6 pagi sampai jam 7 sore.
                            Bila awak kapal bagian pelayanan bekerja harian, jam kerja sebaiknya adalah jam 8 pagi
                            sampai jam 12 tengah hari dan jam 1 siang sampai jam 5 sore.</i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE VI : REST HOUR
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL VI: JAM ISTIRAHAT</p>

                    <p>
                        Each Seafarer shall have a minimum of 10 hours rest in any 24 hour period may be divided into no
                        more than 2 periods, one of which
                        shall be at least 6 hours in length, and the interval between consecutive periods of rest shall
                        not exceed 14 hours.<br>
                        <i>Setiap Pelaut harus memiliki minimal 10 jam istirahat dalam setiap 24 jam dapat dibagi
                            menjadi tidak lebih dari 2 periode,
                            salah satunya harus setidaknya 6 jam, dan interval antara periode istirahat berturut-turut
                            tidak boleh melebihi 14 jam.</i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE VII : EXCESS BAGGAGE
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL VII: KELEBIHAN BARANG BAWAAN</p>

                    <p>
                        While traveling to or from a vessel under this Seafarer Employment Agreement, the Seafarer shall
                        be responsible for any expenses
                        caused by excess baggage beyond the limitation imposed by the Transportation Company used for
                        travel.<br>
                        <i>Ketika dalam perjalanan ke atau dari kapal dibawah Perjanjian Kerja Pelaut ini, Pelaut harus
                            bertanggung jawab atas biaya yang
                            timbul karena kelebihan barang bawaan di atas batas ketentuan yang ditetapkan oleh
                            Perusahaan Pengangkutan yang dipergunakan
                            untuk melakukan perjalanan.</i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE VIII : DISCIPLINE
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL VIII: DISIPLIN</p>

                    <p>
                        1. The seafarer, while employed on board a vessel of the Company, shall comply with all lawful
                        orders of his superiors and
                        division heads and will obey all Company’s rule. Recognizing the necessity for discipline on
                        board Company vessel and at
                        the same time in order to protect a Seafarer against unfair treatment, the Company agrees to
                        post on the bulletin board of each
                        vessel a list of rules which shall constitute reason for which Seafarer may be discharge without
                        further notice. The rules shall
                        be written in such a way to enable the Seafarer to understand.<br>
                        <i>Pelaut selama dipekerjakan diatas kapal milik Perusahaan, wajib mentaati setiap perintah yang
                            sah dari atasannya dan
                            kepala bagiannya serta akan mentaati peraturan Perusahaan. Mengakui pentingnya disiplin
                            diatas kapal milik Perusahaan pada
                            saat yang sama demi melindungi Pelaut terhadap tindakan yang tidak adil. Perusahaan setuju
                            untuk menempelkan dikapal suatu
                            peraturan yang menetapkan pemberitahuan pendahuluan. Peraturan ini harus tertulis sedemikian
                            rupa sehingga memungkinkah
                            bagi Pelaut untuk dapat dimengerti.</i>
                    </p><br>

                    <p>
                        2. In accordance with ANNEX 1 (Discipline of Working Regulation)<br>
                        <i>Sesuai dengan ANNEX 1( disiplin peraturan kerja ).</i>
                    </p><br>

                    <p>
                        3. For other offence not on the posted list, Seafarer shall not be discharged without first
                        having been notified in
                        writing that a repetition on the offence will make him liable to dismissal.<br>
                        <i>Untuk pelanggaran lain yang tidak dimuat didalam daftar, Pelaut tidak akan dipecat tanpa
                            sebelumnya
                            diberitahu secara tertulis bahwa pengulangan pelanggaran tersebut akan membuatnya dapat
                            dipecat.</i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE IX : REPATRIATION
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL IX : PEMULANGAN</p>

                    <p>
                        On termination of employment, the Seafarer shall be paid for our provided with transportation of
                        kind class, as determined by the Company, to return
                        to the place where he has been employed/place of engagement (if immigration laws permitting), or
                        to the airport or seaport nearest the Seafarer’s
                        home, to be determined by the Company in its sole discretion, and he shall be paid his wages
                        (not to include overtime or travel time) up to and
                        including his arrival in Jakarta.<br>
                        <i>Pada saat pengakhiran pengerjaan, Pelaut akan dibayarkan atau diberikan sarana angkutan
                            sesuai jenis dan kelas yang ditentukan oleh
                            Perusahaan, untuk kembali ketempat dimana dia diterima untuk dipekerjakan (bila peraturan
                            keimigrasian mengijinkan) atau Bandar udara atau
                            pelabuhan laut terdekat dari tempat tinggal Pelaut sesuai yang ditentukan Perusahaan, dan
                            kepadanya akan dibayarkan upahnya (tidak termasuk
                            upah lembur atau waktu perjalanan), sampai dengan tanggal tiba di bandar udara atau
                            pelabuhan terdekat. Dimana ada Pejabat Penyijil Awak
                            Kapal</i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE X : INSURANCE
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL X : PERTANGGUNGAN</p>

                    <p>
                        1. The Company shall be responsible for pay and shall bear any and all hospitalization and
                        medical expenses incurred in respect
                        of any seafarer who becomes ailing or injured seafarer on board of a vessel as Government
                        Regulation no 7 year 2000
                        regulations.<br>
                        <i>Perusahaan wajib menanggung biaya perawatan dan pengobatan pelaut yang sakit dan cidera
                            selama berada
                            diatas kapal sesuai dengan Peraturan Pemerintah no. 7 tahun 2000.</i>
                    </p>


                    <!-- Pemisah halaman -->
                    <div style="page-break-after: always;"></div>

                    <!-- ========================== HALAMAN 4 ========================== -->

                    <p>
                        2. Sick or injured seafarer due to any accident such that they will no longer be able to return
                        to work or have to be hospitalized,
                        in addition to payment for any and all hospitalization and medical costs, water transportation
                        employer shall also be obliged
                        to pay full salary if such ship crews are still on board or taken care on board of a vessel.<br>
                        <i>Pelaut yang sakit atau cedera akibat kecelakaan sehingga tidak dapat bekerja atau harus
                            dirawat, perusahaan wajib
                            membiayai perawatan dan pengobatan juga gaji penuh jika pelaut tetap berada atau dirawat di
                            kapal.</i>
                    </p><br>

                    <p>
                        3. For loss and/or damage of crew’s effects, due to the ship accident, the Company shall cover
                        as Flag State Regulation.<br>
                        <i>Besar ganti rugi atas kehilangan barang-barang milik pelaut akibat tenggelam atau terbakar
                            sesuai dengan
                            peraturan dari negara bendera.</i>
                    </p><br>

                    <p>
                        4. Accident / Kecelakaan<br>
                        A Seafarer who suffered permanent 100% disability resulting of an accident during his contract
                        period will be entitled to
                        compensation a minimum of Rp. 500.000.000.<br>
                        <i>Pelaut yang mengalami kecelakaan kerja didalam tugasnya berhak menerima pembayaran
                            pertanggungan bila
                            kecelakaan berakibat cacat tetap yang menyebabkan hilangnya kemampuan kerja pada
                            kedudukannya yang
                            semula sejumlah minimum Rp. 500.000.000.</i>
                    </p><br>

                    <p>
                        In case of permanent partial disability the amount of the compensation will be calculated
                        according the following table:<br>
                        <i>Dalam hal cacat tetap sebagian jumlah pembayaran pertanggungan akan dihitung sesuai dengan
                            tabel berikut:</i>
                    </p>

                    <table style="width:80%;margin:10px auto;border-collapse:collapse;font-size:14px;">
                        <tr>
                            <td>Loss of one finger of any hand / Kehilangan satu jari tangan</td>
                            <td style="text-align:right;">10%</td>
                        </tr>
                        <tr>
                            <td>Loss of one hand / Kehilangan satu lengan</td>
                            <td style="text-align:right;">40%</td>
                        </tr>
                        <tr>
                            <td>Loss of both hand / Kehilangan kedua lengan</td>
                            <td style="text-align:right;">100%</td>
                        </tr>
                        <tr>
                            <td>Loss of one palm / Kehilangan satu telapak tangan</td>
                            <td style="text-align:right;">30%</td>
                        </tr>
                        <tr>
                            <td>Loss of both palm / Kehilangan kedua telapak tangan</td>
                            <td style="text-align:right;">80%</td>
                        </tr>
                        <tr>
                            <td>Loss of one finger of any foot / Kehilangan satu jari kaki</td>
                            <td style="text-align:right;">5%</td>
                        </tr>
                        <tr>
                            <td>Loss of one leg / Kehilangan satu kaki</td>
                            <td style="text-align:right;">40%</td>
                        </tr>
                        <tr>
                            <td>Loss of two leg / Kehilangan kedua kaki</td>
                            <td style="text-align:right;">100%</td>
                        </tr>
                        <tr>
                            <td>Loss of one eye / Kehilangan satu mata</td>
                            <td style="text-align:right;">30%</td>
                        </tr>
                        <tr>
                            <td>Loss of both eyes / Kehilangan kedua mata</td>
                            <td style="text-align:right;">100%</td>
                        </tr>
                        <tr>
                            <td>Loss of hearing in one ear / Kehilangan satu telinga</td>
                            <td style="text-align:right;">15%</td>
                        </tr>
                        <tr>
                            <td>Loss of hearing in both ears / Kehilangan kedua telinga</td>
                            <td style="text-align:right;">40%</td>
                        </tr>
                    </table>

                    <p>
                        5. Loss of live / death in service / Kematian Alami / kematian akibat kecelakaan kerja<br>
                        a. In case an accident including accident occurring whilst traveling to and from the vessel,
                        caused the death of a
                        Seafarer, his next of kin, i.e. his lawful wife and children shall receive a compensation a
                        minimum of Rp. 500.000.000.<br>
                        <i>Dalam hal kecelakaan yang menyebabkan kematian Pelaut, ahli warisnya yang sah, dalam hal ini
                            istri dan anak-anaknya
                            akan menerima pertanggungan minimum sebesar Rp. 500.000.000.</i>
                    </p><br>

                    <p>
                        b. The Company will make arrangements to cover also the death of Seafarer by natural cause. Such
                        arrangements
                        should cover the amount a minimum Rp. 400.000.000.<br>
                        <i>Perusahaan juga akan mengatur pertanggungan yang mencakup kematian Pelaut karena disebabkan
                            alamiah. Pengaturan
                            demikian harus mencakup jumlah minimum sebesar Rp 400.000.000.</i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE XI : TERMINATION OF EMPLOYMENT
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL XI : PEMUTUSAN/PENGAKHIRAN HUBUNGAN KERJA</p>

                    <p>
                        The Company shall be entitled to terminate this agreement anytime, although without prior notice
                        in following circumstances:<br>
                        <i>Perusahaan berhak pada setiap waktu mengakhiri hubungan kerja atau perjanjian ini, sekalipun
                            tanpa pemberitahuan terlebih
                            dahulu karena alasan-alasan sebagai berikut:</i>
                    </p>

                    <p>
                        a) The Seafarer not competent, bad attitude, negligent, not comply with the command or do other
                        acts who adverse the
                        Company.<br>
                        <i>Pelaut kurang cakap, berkelakuan buruk, lengah atau lalai dalam kewajiban, tidak patuh
                            perintah dimaksud
                            atau melakukan perbuatan lain yang merugikan perusahaan.</i>
                    </p><br>

                    <p>
                        b) If the Seafarer commits any act contrary to or in violation of the laws or regulations of the
                        Republic of Indonesia, he shall be
                        disembarked at the location or port where the incident occurred and handed over to the local
                        authorities.<br>
                        <i>Bila Pelaut ternyata melakukan perbuatan-perbuatan yang bertentangan dengan hukum pihak atau
                            melanggar peraturan
                            Pemerintah Republik Indonesia, maka ia akan diturunkan di tempat/pelabuhan dimana peristiwa
                            tersebut
                            terjadi dan diserahkan kepada yang berwajib.</i>
                    </p>

                    <!-- Pemisah halaman -->
                    <div style="page-break-after: always;"></div>

                    <!-- ========================== HALAMAN 5 ========================== -->

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE XII : HARASSMENT AND BULLYING
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL XII : PELECEHAN DAN PERUNDUNGAN</p>

                    <p>
                        The Seafarer has a right to work in an environment free from harassment and bullying and to be
                        treated<br>
                        <i>Pelaut berhak untuk bekerja dalam lingkungan yang bebas dari pelecehan dan perundungan serta
                            diperlakukan</i><br>
                        with dignity and respect. Even unintentional harassment or bullying is unacceptable.<br>
                        <i>dengan martabat dan rasa hormat. Bahkan pelecehan atau perundungan yang tidak disengaja pun
                            tidak dapat diterima.</i>
                    </p><br>

                    <p>
                        The Employer/Employer’s Company or Agent will treat all complaints of harassment and bullying
                        seriously and in strict confidence.<br>
                        <i>Perusahaan Pemberi Kerja/Agen Pemberi Kerja akan menangani semua keluhan terkait pelecehan
                            dan perundungan dengan serius dan
                            dalam kerahasiaan yang ketat.</i>
                    </p><br>

                    <p>
                        If a complaint cannot be resolved amicably on board by the Master and the Crew and the Vessel’s
                        PIC ashore, then the<br>
                        <i>Jika keluhan tidak dapat diselesaikan secara damai di atas kapal oleh Nakhoda, Kru dan PIC
                            Kapal didarat, maka</i><br>
                        procedure contemplated in the on board Ship Management Manual to be followed.<br>
                        <i>procedure yang tercantum dalam Ship Management Manual di atas kapal harus diikuti.</i>
                    </p><br>

                    <p>
                        The Seafarer can also lodge complaint with the Vessel’s PIC’s ashore and be sent to the email
                        ID: <b>aes@andhika.com</b><br>
                        <i>Pelaut juga dapat mengajukan keluhan kepada PIC Kapal di darat dan mengirimkannya ke :
                            <b>aes@andhika.com</b>.</i>
                    </p><br>

                    <p>
                        Appropriate process/proceedings/enquiry will be initiated and necessary disciplinary action will
                        be taken based on the result of such process.<br>
                        <i>Proses, penyelidikan, dan tindakan disipliner yang sesuai akan dilakukan berdasarkan hasil
                            dari proses tersebut.</i>
                    </p>

                    <p style="text-align:center; font-weight:bold; text-decoration:underline; margin-top:25px;">
                        ARTICLE XIII : PIRACY OR ARMED ROBBERY AGAINST SHIPS
                    </p>
                    <p style="text-align:center; font-weight:bold;">PASAL XIII : PEMBAJAKAN ATAU PERAMPOKAN BERSENJATA
                        TERHADAP KAPAL</p>

                    <p>
                        a). Seafarer's employment agreement (SEA) shall continue to have effect and wages shall continue
                        to be paid while a seafarer is held captive
                        on or off the ship as a result of acts of piracy or armed robbery against ships.<br>
                        <i>Perjanjian kerja pelaut (SEA) yang akan terus berlaku dan upah akan terus dibayarkan saat
                            seorang pelaut ditahan di dalam atau di luar kapal
                            sebagai akibat dari tindakan pembajakan atau perampokan bersenjata terhadap kapal.</i>
                    </p><br>

                    <p>
                        b). If a seafarer is held captive on or off the ship as a result of acts of piracy or armed
                        robbery against ships, wages and other entitlements
                        under the seafarers employment agreement or applicable national laws, shall continue to be paid
                        during the entire period of captivity and until
                        the seafarer is released and duly repatriated or, where the seafarer dies while in captivity,
                        until the date of death as determined in accordance
                        with applicable national laws or regulations.<br>
                        <i>Jika seorang pelaut ditahan di dalam atau di luar kapal sebagai akibat dari tindakan
                            pembajakan atau perampokan bersenjata terhadap kapal, upah
                            dan hak lainnya berdasarkan perjanjian kerja pelaut atau undang-undang nasional yang
                            berlaku, akan terus dibayar selama seluruh periode
                            penahanan dan sampai pelaut tersebut dibebaskan dan dipulangkan dengan semestinya atau, jika
                            pelaut tersebut meninggal saat di sandera, sampai
                            tanggal kematian sebagaimana ditentukan sesuai dengan hukum atau peraturan nasional yang
                            berlaku.</i>
                    </p><br>

                    <p>
                        c). Seafarers are entitled to repatriation if they are detained on or off the ship as a result
                        of piracy or armed robbery of the ship.<br>
                        <i>Para pelaut berhak atas pemulangan jika mereka ditahan di dalam atau di luar kapal sebagai
                            akibat dari tindakan pembajakan atau perampokan
                            bersenjata terhadap kapal.</i>
                    </p><br>

                    <p>
                        Languages in this agreement are made in the English language and the Indonesian language. In the
                        event of any inconsistency or different
                        interpretation between the English text and Indonesian text, the Indonesian text shall prevail
                        and the relevant English text shall be deemed to be
                        automatically amended to conform with and to make the relevant English text consistent with the
                        relevant Indonesia text.<br>
                        <i>Bahasa pada perjanjian ini dibuat dalam bahasa Inggris dan bahasa Indonesia. Dalam hal
                            terdapat ketidaksesuaian dan perbedaan antara teks
                            bahasa Inggris dan teks bahasa Indonesia, maka teks bahasa Indonesia yang akan berlaku dan
                            teks bahasa Inggris akan secara otomatis
                            diubah untuk menyesuaikan dengan dan untuk membuat teks bahasa Inggris konsisten dengan teks
                            bahasa Indonesia.</i>
                    </p><br>

                    <p>
                        This agreement has adopted the MLC requirements and is made in 4 (four) copies intended for the
                        licensing of Ship Crew, Seafarers,
                        Companies and Ship Master.<br>
                        <i>Perjanjian ini telah mengadop persyaratan MLC dan dibuat rangkap 4 (empat) yang diperuntukan
                            penyijil Awak Kapal, Pelaut,
                            Perusahaan dan Nahkoda Kapal.</i>
                    </p>

                    <div style="page-break-after: always;"></div>


                    <div
                        style="font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; text-align:center; line-height:1.8; margin-top:60px;">

                        <p>
                            In witness of the aforesaid terms and condition both parties sign this agreement this day
                            ………………………………………..<br>
                            <i>Sebagai kesaksian dari ketentuan dan syarat-syarat diatas, kedua belah pihak
                                menandatangani Perjanjian ini tanggal
                                ……………………………………..</i>
                        </p>

                        <table style="width:100%; margin-top:40px; border-collapse:collapse;">
                            <tr>
                                <td style="width:50%; text-align:center; vertical-align:top;">
                                    <b>PT <span id="txtShippingCompanyNameTTD"></span></b><br>
                                    <b>Perusahaan</b><br><br><br><br>
                                    (<b> EVA MARLIANA </b>)<br>
                                    <b id="txtCrewingPosition">CREWING MANAGER</b>
                                </td>
                                <td style="width:50%; text-align:center; vertical-align:top;">
                                    <b>THE SEAFARER</b><br>
                                    <b>Pelaut</b><br><br><br><br>
                                    <span id="txtSeafarerNameSignature"><b>&lt;&lt;Nama Crew&gt;&gt;</b></span>
                                </td>
                            </tr>
                        </table>

                        <div style="margin-top:100px; text-align:center;">
                            <p style="font-weight:bold;">ACKNOWLEDGED by,</p>
                            <p style="font-weight:bold;">MENGETAHUI :</p>
                            <br><br><br>
                            <p>.........................................................</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="txtIdPerson" value="">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveAndPrint">Print</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="wagesModal" tabindex="-1" role="dialog" aria-labelledby="wagesModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width:90%;" role="document">
        <div class="modal-content" style="border-radius:10px;overflow:hidden;">

            <div class="modal-header" style="border-bottom:1px solid #ccc;padding:10px 20px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size:26px;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" style="padding:20px 36px;">
                <div id="wagesContent">
                    <div id="wagesLoading" style="display:none;">
                        <div class="text-center">
                            <i class="fa fa-spinner fa-spin fa-3x"></i><br>Loading PKL...
                        </div>
                    </div>
                </div>

                <script type="text/html" id="wagesTemplate">
                <div
                    style="max-width:1100px;margin:0 auto;padding:20px 30px;font-family:'Times New Roman',serif;line-height:1.5;font-size:13px;color:#222;">

                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:30px;">
                        <div style="width:120px;flex-shrink:0;">
                            <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" alt="Left Logo"
                                style="width:100px;height:auto;display:block;">
                        </div>

                        <div style="flex:1;text-align:center;line-height:1.2;margin-top:8px;">
                            <h1 style="margin:0;padding:0;font-size:18px;font-weight:700;">SURAT PERNYATAAN GAJI</h1>
                            <h2 style="margin:4px 0 0 0;padding:0;font-size:13px;font-weight:700;">STATEMENT OF WAGES
                            </h2>
                        </div>

                        <div
                            style="flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;text-align:right;">
                            <div style="font-size:12px;font-weight:700;">SRPS LICENSE NO:</div>
                            <div style="font-size:12px;font-weight:700;margin-top:2px;margin-bottom:8px;">SIUKAK 236.121
                                - R Tahun 2025
                            </div>

                            <div style="display:flex;flex-direction:row;align-items:center;gap:10px;">
                                <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>"
                                    alt="Right Left" style="width:120px;height:auto;display:block;">
                                <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" alt="Right Right"
                                    style="width:150px;height:auto;display:block;">
                            </div>
                        </div>
                    </div>

                    <!-- SECTION I -->
                    <table style="width:100%;border-collapse:collapse;margin-bottom:16px;">
                        <tr>
                            <td style="width:30%;font-weight:700;padding:6px 8px;">I herewith the undersigned</td>
                            <td style="width:2%;padding:6px 8px;">:</td>
                            <td style="padding:6px 8px;"></td>
                        </tr>
                        <tr>
                            <td style="font-style:italic;padding:6px 8px;">Yang bertanda tangan di bawah ini</td>
                            <td style="padding:6px 8px;">:</td>
                            <td style="padding:6px 8px;"></td>
                        </tr>
                    </table>

                    <!-- CREW DETAILS -->
                    <table style="width:100%;border-collapse:collapse;">
                        <tr>
                            <td colspan="2" style="height:4px;"></td>
                        </tr>
                        <tr>
                            <td style="width:28%;padding:8px;border:1px solid #222;background:#f7f7f7;font-weight:700;">
                                Name/Nama</td>
                            <td style="padding:8px;border:1px solid #222;">
                                <<Nama>>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px;border:1px solid #222;background:#f7f7f7;font-weight:700;">
                                Position/Jabatan</td>
                            <td style="padding:8px;border:1px solid #222;">
                                <<Rank>>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px;border:1px solid #222;background:#f7f7f7;font-weight:700;">
                                Vessel/Kapal</td>
                            <td style="padding:8px;border:1px solid #222;">
                                <<Kapal>>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px;border:1px solid #222;background:#f7f7f7;font-weight:700;">Sign On
                                date/Tanggal Naik Kapal</td>
                            <td style="padding:8px;border:1px solid #222;">
                                <<Sign On Date>>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px;border:1px solid #222;background:#f7f7f7;font-weight:700;">Port of
                                Embarkation/Pelabuhan</td>
                            <td style="padding:8px;border:1px solid #222;">
                                <<Port>>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:8px;border:1px solid #222;background:#f7f7f7;font-weight:700;">Sea
                                Service/Masa Layar</td>
                            <td style="padding:8px;border:1px solid #222;">
                                <<Masa Layar>>
                            </td>
                        </tr>
                    </table>

                    <!-- WAGES SECTION -->
                    <div style="margin-top:18px;">
                        <p style="margin:0 0 6px 0;font-weight:700;">Understand & agree the total salary and salary pay
                            system as company regulation as follows:</p>
                        <p style="margin:0;font-style:italic;">Mengerti & menyetujui jumlah gaji dan sistem
                            pembayarannya sesuai dengan peraturan perusahaan sebagai berikut:</p>
                    </div>

                    <table style="width:100%;border-collapse:collapse;margin-top:12px;text-align:center;">
                        <thead>
                            <tr>
                                <th style="border:1px solid #222;padding:10px;background:#fafafa;">Basic Wages</th>
                                <th style="border:1px solid #222;padding:10px;background:#fafafa;">FOT</th>
                                <th style="border:1px solid #222;padding:10px;background:#fafafa;">Tanker Allow.</th>
                                <th style="border:1px solid #222;padding:10px;background:#fafafa;">Leave Pay</th>
                                <th style="border:1px solid #222;padding:10px;background:#fafafa;">B/S (%)</th>
                                <th style="border:1px solid #222;padding:10px;background:#fafafa;">H/S (%)</th>
                                <th style="border:1px solid #222;padding:10px;background:#fafafa;">Total Pay</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="border:1px solid #222;padding:10px;">
                                    <<Basic Wages>>
                                </td>
                                <td style="border:1px solid #222;padding:10px;">
                                    <<FOT>>
                                </td>
                                <td style="border:1px solid #222;padding:10px;">
                                    <<Tanker Allowance>>
                                </td>
                                <td style="border:1px solid #222;padding:10px;">
                                    <<Leave Pay Nett>>
                                </td>
                                <td style="border:1px solid #222;padding:10px;">
                                    <<BS>>
                                </td>
                                <td style="border:1px solid #222;padding:10px;">
                                    <<HS>>
                                </td>
                                <td style="border:1px solid #222;padding:10px;">
                                    <<Total Pay>>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- NEXT OF KIN -->
                    <div style="font-weight:700;margin-top:24px;margin-bottom:8px;">B. Next Of Kin / Keluarga Terdekat
                    </div>
                    <table style="width:60%;border-collapse:collapse;">
                        <tr>
                            <td
                                style="padding:10px;border:1px solid #222;background:#f7f7f7;font-weight:700;width:35%;">
                                Name/Nama</td>
                            <td style="padding:10px;border:1px solid #222;">
                                <<Nama Telp Darurat>>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px;border:1px solid #222;background:#f7f7f7;font-weight:700;">
                                Relationship/Hub</td>
                            <td style="padding:10px;border:1px solid #222;">
                                <<Hubungan Telp Darurat>>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:10px;border:1px solid #222;background:#f7f7f7;font-weight:700;">No Tlp/HP
                            </td>
                            <td style="padding:10px;border:1px solid #222;">
                                <<Telp Darurat>>
                            </td>
                        </tr>
                    </table>

                    <!-- FOOTER -->
                    <p style="font-style:italic;margin-top:16px;">I hereby confirm the above contained herein is
                        correct, without compulsion.<br>
                        <em>Demikian pernyataan ini saya buat dengan sebenarnya, tanpa paksaan dari pihak lain.</em>
                    </p>

                    <div style="display:flex;justify-content:space-between;margin-top:40px;">
                        <div style="width:30%;min-height:90px;text-align:left;">
                            <div style="font-weight:700;">Acknowledge,</div>
                            <div style="margin-top:36px;">Mengetahui,</div>
                            <div style="margin-top:24px;">Head of Crewing Division</div>
                        </div>

                        <div style="width:30%;"></div>

                        <div style="width:30%;min-height:90px;text-align:right;">
                            <div style="font-weight:700;">Seafarer,</div>
                            <div style="margin-top:36px;">Pelaut,</div>
                        </div>
                    </div>

                </div>
                </script>
            </div>

            <div class="modal-footer" style="border-top:1px solid #ccc;padding:10px 20px;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveAndPrintWages">Print</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSPJ" tabindex="-1" aria-labelledby="modalSuratLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:10px;">

            <div class="modal-body" style="padding:40px; font-size:13px; line-height:1.4;">

                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:80px; text-align:left; vertical-align:top;">
                            <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" alt="Logo"
                                style="width:80px; height:auto;">
                        </td>
                        <td style="text-align:center;">
                            <div style="font-size:16px; font-weight:bold;">SURAT PERINTAH JALAN</div>
                            <div style="font-size:12px; font-style:italic;">(Official Travel Letter)</div>
                        </td>
                        <td style="width:80px;"></td>
                    </tr>
                </table>

                <div style="height:20px;"></div>

                <table style="width:100%; border-collapse:collapse; font-size:13px;">
                    <tr>
                        <td style="width:150px; vertical-align:top;">Berdasarkan<br><i>(Base on)</i></td>
                        <td style="width:10px; vertical-align:top;">:</td>
                        <td style="vertical-align:top;">
                            <input type="text" id="base_on" class="form-control" value="Kepentingan Perusahaan"
                                style="width:100%; border:1px solid #ccc; padding:4px; font-size:13px;">
                            <div style="font-size:11px; font-style:italic;">(Company Occupation)</div>
                        </td>
                    </tr>

                    <tr>
                        <td style="vertical-align:top;">Diberikan perintah kepada<br><i>(Given to)</i></td>
                        <td style="vertical-align:top;">:</td>
                        <td style="vertical-align:top;">
                            <table style="border-collapse:collapse; margin-top:4px; width:100%;">
                                <tr>
                                    <td style="width:150px;">Nama <i>(Name)</i></td>
                                    <td style="width:10px;">:</td>
                                    <td><input type="text" id="crew_name" class="form-control"
                                            style="width:100%; border:1px solid #ccc; padding:4px;"></td>
                                </tr>
                                <tr>
                                    <td>Jabatan <i>(Rank)</i></td>
                                    <td>:</td>
                                    <td><input type="text" id="crew_rank" class="form-control"
                                            style="width:100%; border:1px solid #ccc; padding:4px;"></td>
                                </tr>
                                <tr>
                                    <td>Tujuan <i>(Destination)</i></td>
                                    <td>:</td>
                                    <td><input type="text" id="destination" class="form-control"
                                            style="width:100%; border:1px solid #ccc; padding:4px;"></td>
                                </tr>
                                <tr>
                                    <td>Keperluan <i>(Purpose)</i></td>
                                    <td>:</td>
                                    <td><input type="text" id="purpose" class="form-control"
                                            placeholder="Sign on to [Nama Kapal]"
                                            style="width:100%; border:1px solid #ccc; padding:4px;"></td>
                                </tr>
                                <tr>
                                    <td>Berangkat Tanggal <i>(Date of Depart)</i></td>
                                    <td>:</td>
                                    <td><input type="date" id="depart_date"
                                            style="width:60%; border:1px solid #ccc; padding:4px;"></td>
                                </tr>
                                <tr>
                                    <td>Tiba Tanggal <i>(Date of Arrival)</i></td>
                                    <td>:</td>
                                    <td><input type="date" id="arrival_date"
                                            style="width:60%; border:1px solid #ccc; padding:4px;"></td>
                                </tr>
                                <tr>
                                    <td>Kendaraan <i>(Transportation)</i></td>
                                    <td>:</td>
                                    <td><input type="text" id="transportation" class="form-control"
                                            placeholder="Contoh: Pesawat / Mobil / Kapal"
                                            style="width:100%; border:1px solid #ccc; padding:4px;"></td>
                                </tr>
                                <tr>
                                    <td>Catatan <i>(Note)</i></td>
                                    <td>:</td>
                                    <td><textarea id="note" rows="2"
                                            style="width:100%; border:1px solid #ccc; padding:4px;"></textarea></td>
                                </tr>
                                <tr>
                                    <td>Pengikut <i>(Accompany)</i></td>
                                    <td>:</td>
                                    <td>
                                        <div id="accompanyInfo" style="font-style:italic; color:#555;">Tambahkan
                                            pengikut di bawah ini.</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>

                <div style="height:20px;"></div>

                <table id="signatureTable"
                    style="width:100%; border-collapse:collapse; border:1px solid #000; text-align:center; font-size:13px;">
                    <thead>
                        <tr>
                            <td style="border:1px solid #000; padding:6px; width:50%; font-weight:bold;">Nama/Name</td>
                            <td style="border:1px solid #000; padding:6px; width:50%; font-weight:bold;">Jabatan/Rank
                            </td>
                        </tr>
                    </thead>
                    <tbody id="accompanyWrapper">
                        <tr class="accompany-item">
                            <td style="border:1px solid #000; padding:6px;">
                                <input type="text" class="form-control acc-name" name="sign_name[]"
                                    placeholder="Nama pengikut" style="width:90%; border:1px solid #ccc; padding:4px;">
                            </td>
                            <td
                                style="border:1px solid #000; padding:6px; display:flex; align-items:center; justify-content:center; gap:6px;">
                                <input type="text" class="form-control acc-rank" name="sign_rank[]"
                                    placeholder="Rank pengikut" style="width:85%; border:1px solid #ccc; padding:4px;">
                                <button type="button" id="addAccompany"
                                    style="border:none; background:#28a745; color:#fff; font-weight:bold; font-size:16px; width:28px; height:28px; border-radius:4px; cursor:pointer;">+</button>
                            </td>
                        </tr>
                    </tbody>
                </table>


                <div style="height:40px;"></div>

                <div style="text-align:right; margin-right:50px;">
                    Jakarta, &lt;&lt;Tanggal&gt;&gt;<br>
                    &lt;&lt;Entitas&gt;&gt;<br><br><br><br><br><br>
                    <div style="font-weight:bold; text-decoration:underline;">Eva Marliana</div>
                    <div style="font-style:italic;">Crewing Manager</div>
                </div>

                <div style="height:50px;"></div>

                <div id="cc_list" style="font-size:12px;">
                    <i>(Cc)</i><br>
                    1. Manager Adm & Keu<br>
                    2. Master <span id="vessel_name_cc">[Nama Kapal]</span><br>
                    3. File
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveSPJ">Save & Print</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalDataBank" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:850px;">
        <div class="modal-content" style="border:1px solid #000; border-radius:6px;">

            <div class="modal-header" style="border-bottom:none; padding-bottom:0;">
                <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
                    <tr>
                        <td style="width:80px; vertical-align:top;">
                            <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" alt="Logo"
                                style="width:80px; height:auto;">
                        </td>

                        <td style="text-align:center; vertical-align:middle;">
                            <div style="font-size:15px; font-weight:bold;">STATEMENT/<i>PERNYATAAN</i></div>
                        </td>

                        <td style="width:170px; text-align:right; vertical-align:top;">
                            <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                            <div style="font-size:10px;">SIUKAK 236.121
                                - R Tahun 2025</div>
                            <div style="margin-top:3px;">
                                <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>" alt="BV"
                                    style="width:65px; margin-right:3px;">
                                <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" alt="ISO" style="width:65px;">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="modal-body" style="padding:20px;">

                <table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold; width:35%;">STATUS DATA BANK
                        </td>
                        <td style="border:1px solid #000; padding:6px;">
                            <select id="statusBank" class="form-control">
                                <option value="Tetap">Tetap</option>
                                <option value="Baru">Baru</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">NAMA</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="namaCrew" type="text" style="width:100%;" value="<<Nama Crew>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">NPWP</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="npwp" type="text" style="width:100%;" value="<<NPWP>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">ALAMAT RUMAH</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="alamatRumah" type="text" style="width:100%;" value="<<Alamat Rumah>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">NO. TELP</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="telp" type="text" style="width:100%;" value="<<Telp>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">NO. TELP DARURAT</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="telpDarurat" type="text" style="width:100%;" value="<<Telp Darurat>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">HUBUNGAN</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="hubungan" type="text" style="width:100%;"
                                    value="<<Hubungan Telp Darurat>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">NAMA BANK / CABANG / UNIT</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="bank" type="text" style="width:100%;" value="<<Bank>> - <<Cabang>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">NO. REKENING</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b> <input id="noRekening" type="text" style="width:100%;" value="<<No. Rekening>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">PEMILIK REKENING</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="pemilikRekening" type="text" style="width:100%;"
                                    value="<<Pemilik Rekening>>"></b>
                        </td>
                    </tr>
                    <tr>
                        <td style="border:1px solid #000; padding:6px; font-weight:bold;">ALAMAT BANK</td>
                        <td style="border:1px solid #000; padding:6px;">
                            <b><input id="alamatBank" type="text" style="width:100%;" value="<<Alamat Bank>>"></b>
                        </td>
                    </tr>
                </table>

                <div style="font-size:12px; line-height:1.5; margin-bottom:25px;">
                    <b>Ketentuan</b><br>
                    1. Perusahaan tidak bertanggung jawab atas keterlambatan pengiriman yang disebabkan oleh prosedur
                    Bank yang ditunjuk atau kesalahan penulisan data Bank oleh Crew yang bersangkutan.<br>
                    2. Crew harus melampirkan fotocopy rekening Bank yang ditunjuk.<br>
                    3. Rekening Bank yang ditunjuk ini berlaku selama kontrak kerja dan tidak dapat diganti tanpa
                    persetujuan dari Crew Manager dengan menyebutkan alasan yang jelas.
                    <br><br>
                    Saya menyetujui semua ketentuan yang berlaku dan mengakui formulir ini telah diisi dengan benar
                    serta menerima semua konsekuensi dari isi form ini.
                </div>

                <div style="width:100%; display:flex; justify-content:space-between; margin-top:20px;">
                    <div style="text-align:left;">
                        <span id="tanggal">Jakarta, <<Tanggal>></span><br><br><br><br>
                        <b><u id="namaCrewFooter">
                                <<Nama Crew>>
                            </u></b><br>
                        <span style="font-size:12px;">Crew</span>
                    </div>
                    <div style="text-align:right;">
                        Mengetahui :<br><br><br><br>
                        <b><u id="crewExecutive">
                                Eva Marliana
                            </u></b><br>
                        <span style="font-size:12px;">Crew Executive</span>
                    </div>
                </div>

            </div>

            <div class="modal-footer" style="border-top:none; justify-content:center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveAndPrintStatement">Print</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="introModal" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:850px;">
        <div class="modal-content"
            style="border:1px solid #000; border-radius:6px; padding:25px; font-family:'Times New Roman', serif;">

            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:90px; vertical-align:top;">
                        <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" style="width:80px;">
                    </td>

                    <td style="text-align:center; vertical-align:middle;">
                        <div style="font-size:18px; letter-spacing:10px; font-weight:bold;">INSTRUKSI</div>
                        <div style="font-size:15px; font-weight:bold; margin-top:3px;">INSTRUCTION LETTER</div>
                    </td>

                    <td style="width:170px; text-align:right; vertical-align:top;">
                        <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                        <div style="font-size:10px;">SIUKAK 236.121
                            - R Tahun 2025</div>
                        <div style="margin-top:5px;">
                            <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>"
                                style="width:60px; margin-right:3px;">
                            <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" style="width:60px;">
                        </div>
                    </td>
                </tr>
            </table>

            <table style="width:100%; margin-top:25px; font-size:13px;">
                <tr>
                    <td style="width:120px;">Berdasarkan</td>
                    <td>: Kepentingan Dinas Perusahaan</td>
                </tr>
                <tr>
                    <td>Base on</td>
                    <td>: Shipping Company Official Regulation</td>
                </tr>
                <tr>
                    <td></td>
                    <td>: <span class="entitas-fill"></span></td>
                </tr>
            </table>

            <div style="margin-top:30px; text-align:center; font-weight:bold;">
                DIINSTRUKSIKAN<br>
                <span style="font-weight:normal;">INSTRUCTED</span>
            </div>

            <table style="width:100%; margin-top:20px; font-size:13px;">
                <tr>
                    <td style="width:110px;">Kepada (To)</td>
                    <td>: Master <span class="kapal-fill"></span></td>
                </tr>
                <tr>
                    <td>Untuk (For)</td>
                    <td>: _______________________________</td>
                </tr>
            </table>

            <!-- TABLE 1 RELEASE -->
            <div style="margin-top:18px; font-size:13px;">
                1. Membebaskan dari tugas dan tanggung jawab serta jabatan:
                <br><i>Release from the duty/responsibility...</i>
            </div>

            <div style="margin-top:7px; text-align:right;">
                <button type="button" id="btnAddReleaseRow" class="btn btn-sm btn-success" style="padding:2px 10px;">+
                    Add Row</button>
            </div>

            <table id="releaseTable"
                style="width:100%; border:1px solid #000; border-collapse:collapse; margin-top:5px; text-align:center; font-size:13px;">
                <tr class="release-header">
                    <td style="padding:6px; border:1px solid #000;">Nama / Name</td>
                    <td style="padding:6px; border:1px solid #000;">Jabatan / Rank</td>
                    <td style="padding:6px; border:1px solid #000;">Alasan / Reason</td>
                    <td style="padding:6px; border:1px solid #000; text-align:center;">
                        <input type="text" name="release_header_others" value="Others"
                            style="width:100%; border:1px solid #666; padding:3px; text-align:center;">
                    </td>

                    <td style="padding:6px; border:1px solid #000; width:40px;"></td>
                </tr>

                <tr class="release-row">
                    <td style="padding:0; border:1px solid #000;">
                        <input type="text" name="nama_release[]" class="form-control" style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid #000;">
                        <input type="text" name="jabatan_release[]" class="form-control"
                            style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid:#000;">
                        <input type="text" name="reason_release[]" class="form-control"
                            style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid:#000;">
                        <input type="text" name="otherRelease[]" class="form-control" style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid:#000; width:40px; text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnDeleteRelease"
                            style="padding:2px 6px; display:none;">-</button>
                    </td>
                </tr>
            </table>

            <div style="margin-top:20px; font-size:13px;">
                2. Sebagai penggantinya ditetapkan sebagai berikut:
                <br><i>As the successor:</i>
            </div>

            <div style="margin-top:7px; text-align:right;">
                <button type="button" id="btnAddSuccessorRow" class="btn btn-sm btn-success" style="padding:2px 10px;">+
                    Add Row</button>
            </div>

            <table id="successorTable"
                style="width:100%; border:1px solid #000; border-collapse:collapse; margin-top:5px; font-size:13px; text-align:center;">
                <tr>
                    <td style="padding:6px; border:1px solid #000;">Nama</td>
                    <td style="padding:6px; border:1px solid #000;">Jabatan</td>
                    <td style="padding:6px; border:1px solid #000;">B/S</td>
                    <td style="padding:6px; border:1px solid #000;">OT</td>
                    <td style="padding:6px; border:1px solid #000;">Leave Pay</td>
                    <td style="padding:6px; border:1px solid #000; text-align:center;">
                        <input type="text" name="successor_header_others" value="Others"
                            style="width:100%; border:1px solid #666; padding:3px; text-align:center;">
                    </td>

                    <td style="padding:6px; border:1px solid #000; width:40px;"></td>
                </tr>

                <tr class="successor-row">
                    <td style="padding:0; border:1px solid #000;">
                        <input type="text" name="nama_successor[]" class="form-control"
                            style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid #000;">
                        <input type="text" name="jabatan_successor[]" class="form-control"
                            style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid #000;">
                        <input type="text" name="bs_successor[]" class="form-control" style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid #000;">
                        <input type="text" name="ot_successor[]" class="form-control" style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid #000;">
                        <input type="text" name="leavepay_successor[]" class="form-control"
                            style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid #000;">
                        <input type="text" name="othersSuccessor[]" class="form-control"
                            style="border:none; height:38px;">
                    </td>
                    <td style="padding:0; border:1px solid #000; width:40px; text-align:center;">
                        <button type="button" class="btn btn-danger btn-sm btnDeleteSuccessor"
                            style="padding:2px 6px; display:none;">-</button>
                    </td>
                </tr>
            </table>

            <div style="margin-top:18px; font-size:13px; line-height:1.5;">
                3. Selesai pelaksanaan sign off, agar sub 1a menghadapi Direksi
                <span class="entitas-fill"></span>
                Cq. Manager Personalia Laut untuk menerima instruksi selanjutnya.<br>

                <i>After completing the contract, sub 1a must report to
                    <span class="entitas-fill"></span>
                    Director Cq. Marine Personal Division Manager to receive next instruction.
                </i><br><br>

                4. Pelaksanaan Sign On/Off di pelabuhan: <span class="port-fill"></span><br>
                <i>The Signing On/Off at: <span class="port-fill"></span></i><br><br>

                5. Apabila terdapat kekeliruan dikemudian hari, akan diadakan pembetulan seperlunya.<br>
                <i>If found any mistake in the future, it will be corrected.</i><br><br>

                6. Agar dilaksanakan dengan penuh tanggung jawab.<br>
                <i>Please follow with full responsibility.</i>
            </div>

            <div style="margin-top:35px; display:flex; justify-content:space-between; font-size:13px;">
                <div>
                    Instruksi: Selesai<br>
                    <i>Instruction: Done</i>
                </div>
                <div style="text-align:right;">
                    Jakarta, <span class="tanggal-fill"></span><br>
                    <span class="entitas-fill"></span>
                </div>
            </div>

            <div style="margin-top:60px; text-align:right; font-size:14px; font-weight:bold;">
                Eva Marliana<br>
                <span style="font-weight:normal;">Crewing Manager</span>
            </div>

            <div class="modal-footer" style="border-top:none; justify-content:center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <input type="hidden" id="hid_entitas">
                <input type="hidden" id="hid_vessel">
                <input type="hidden" id="hid_port">
                <input type="hidden" id="hid_tanggal">

                <button type="button" class="btn btn-primary" id="btnSaveAndPrintIntroduction">Print</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalBriefingChecklist" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header" style="border-bottom:none; padding-bottom:0;">
                <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
                    <tr>
                        <td style="width:80px; vertical-align:top;">
                            <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" alt="Logo"
                                style="width:80px; height:auto;">
                        </td>

                        <td style="width:170px; text-align:right; vertical-align:top;">
                            <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                            <div style="font-size:10px;">SIUKAK 236.121
                                - R Tahun 2025</div>
                            <div style="margin-top:3px;">
                                <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>" alt="BV"
                                    style="width:65px; margin-right:3px;">
                                <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" alt="ISO" style="width:65px;">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="modal-body" style="font-family:'Times New Roman'; font-size:14px; line-height:1.3;">

                <p style="margin:0 0 10px 0;">
                    <input type="checkbox"> Please tick the column during briefing / test
                <h5 class="modal-title">BRIEFING CHECK LIST PRIOR JOINING VESSEL</h5>
                </p>

                <div style="display:flex; gap:20px;">


                    <div style="width:33%;">

                        <div style="font-weight:bold; border-bottom:1px solid #000; margin-bottom:5px;">ABOUT AGENCY
                        </div>

                        <label><input type="checkbox"> Crew Manning Agent</label><br>
                        <label><input type="checkbox"> Company Policy</label><br>
                        <label><input type="checkbox"> Organization</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none; font-size:13px;"></textarea>
                            Date .........................................
                        </div>

                        <div
                            style="font-weight:bold; border-bottom:1px solid #000; margin-top:15px; margin-bottom:5px;">
                            ABOUT PRINCIPALS</div>

                        <label><input type="checkbox"> Organisation</label><br>
                        <label><input type="checkbox"> QM System</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                        <div
                            style="font-weight:bold; border-bottom:1px solid #000; margin-top:15px; margin-bottom:5px;">
                            EMPLOYMENT CONTRACT</div>

                        <label><input type="checkbox"> Service Period</label><br>
                        <label><input type="checkbox"> Vessels route</label><br>
                        <label><input type="checkbox"> Type of vessel</label><br>
                        <label><input type="checkbox"> Insurance</label><br>
                        <label><input type="checkbox"> Collective Bargaining Agreement</label><br>
                        <label><input type="checkbox"> MLC & Indonesia Govt Regulation no.7-2000</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                        <div
                            style="font-weight:bold; border-bottom:1px solid #000; margin-top:15px; margin-bottom:5px;">
                            SALARY</div>

                        <label><input type="checkbox"> As per contract</label><br>
                        <label><input type="checkbox"> Bank Account</label><br>
                        <label><input type="checkbox"> Onboard / Home Salary</label><br>
                        <label><input type="checkbox"> NPWP</label><br>
                        <label><input type="checkbox"> Deduction (if any)</label><br>
                        <label><input type="checkbox"> Exchange rates - company</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                    </div>

                    <div style="width:33%;">

                        <!-- HEALTH SAFETY -->
                        <div style="font-weight:bold; border-bottom:1px solid #000; margin-bottom:5px;">HEALTH SAFETY N
                            ENVIRONMENT</div>

                        <label><input type="checkbox"> Health</label><br>
                        <label><input type="checkbox"> Safety</label><br>
                        <label><input type="checkbox"> Environment Protection</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                        <div
                            style="font-weight:bold; border-bottom:1px solid #000; margin-top:15px; margin-bottom:5px;">
                            IN HOUSE TRAINING</div>

                        <label><input type="checkbox"> English</label><br>
                        <label><input type="checkbox"> ISM Code/Safety</label><br>
                        <label><input type="checkbox"> Risk Management</label><br>
                        <label><input type="checkbox"> Deck or Engine Knowledge</label><br>
                        <label><input type="checkbox"> Operating Procedure Manual</label><br>
                        <label><input type="checkbox"> Others</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                    </div>

                    <div style="width:33%;">

                        <div style="font-weight:bold; border-bottom:1px solid #000; margin-bottom:5px;">DISCIPLINE &
                            COMPLAIN</div>

                        <label><input type="checkbox"> Personal Protective Equipment</label><br>
                        <label><input type="checkbox"> Complaints/Problems onboard</label><br>
                        <label><input type="checkbox"> Disciplinary Procedure</label><br>
                        <label><input type="checkbox"> Drug and Alcohol Policy</label><br>
                        <label><input type="checkbox"> Anti Smuggling</label><br>
                        <label><input type="checkbox"> Jump Ship</label><br>
                        <label><input type="checkbox"> Pornography prohibition</label><br>
                        <label><input type="checkbox"> Borrow money on board</label><br>
                        <label><input type="checkbox"> Online gambling</label><br>
                        <label><input type="checkbox"> Online Loan</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                        <div
                            style="font-weight:bold; border-bottom:1px solid #000; margin-top:15px; margin-bottom:5px;">
                            MEDICAL</div>

                        <label><input type="checkbox"> Pre-employment medical check up</label><br>
                        <label><input type="checkbox"> Drug and Alcohol test</label><br>
                        <label><input type="checkbox"> Crew Medical coverage</label><br>
                        <label><input type="checkbox"> Sick onboard / medical report</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                        <div
                            style="font-weight:bold; border-bottom:1px solid #000; margin-top:15px; margin-bottom:5px;">
                            LATEST INCIDENTS</div>

                        <label><input type="checkbox"> Crew Incident</label><br>
                        <label><input type="checkbox"> Fire / Piracy</label><br>
                        <label><input type="checkbox"> Engine problem</label><br>
                        <label><input type="checkbox"> Other Emergency situations</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                        <div
                            style="font-weight:bold; border-bottom:1px solid #000; margin-top:15px; margin-bottom:5px;">
                            TRAVEL TO JOIN VESSEL</div>

                        <label><input type="checkbox"> Agents Address</label><br>
                        <label><input type="checkbox"> Emergency contact</label><br>
                        <label><input type="checkbox"> Schedule of join (Date & Time)</label><br>
                        <label><input type="checkbox"> Airport rules</label>

                        <div style="border:1px solid #000; padding:5px; margin-top:5px;">
                            Well understood by:<br>
                            <textarea style="width:100%; height:32px; resize:none;"></textarea>
                            Date .........................................
                        </div>

                    </div>

                </div>

                <hr>

                <p style="margin-top:10px;">
                    I, &lt;&lt;Nama Crew&gt;&gt; | &lt;&lt;Rank&gt;&gt; | __________
                    was carried out and briefed on the above by Mr/Ms &lt;&lt;Crew Executive&gt;&gt;
                    date &lt;&lt;Tanggal&gt;&gt; prior joining vessel &lt;&lt;Nama Kapal&gt;&gt;
                </p>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modalStatement" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"
            style="border:1px solid #000; border-radius:6px; padding:40px 55px; font-family:'Times New Roman', serif; font-size:14px;">

            <div style="display:flex; align-items:flex-start; width:100%; margin-bottom:15px;">

                <div style="width:100px;">
                    <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" style="width:90px;">
                </div>

                <div style="flex:1; text-align:center; margin-top:10px;">
                    <div style="font-size:20px; font-weight:bold; letter-spacing:2px;">
                        STATEMENT/<em>PERNYATAAN</em>
                    </div>
                </div>

                <div style="width:180px; text-align:right; font-size:11px;">
                    <div style="font-weight:bold;">SRPS LICENSE NO:</div>
                    <div>SIUKAK 236.121
                        - R Tahun 2025</div>

                    <div style="margin-top:6px;">
                        <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>"
                            style="width:70px; margin-right:3px;">
                        <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" style="width:70px;">
                    </div>
                </div>
            </div>

            <div style="margin-top:10px; margin-left:15px; width:92%;">
                <p style="margin-bottom:5px;">
                    I <span style="font-weight:700;" id="txtNameCrew">&lt;&lt;Nama Crew&gt;&gt;</span>
                    hereby declare that I have never give Money or / and other forms of gifts to any of our Andhika Eka
                    Karya Sejahtera office staff in return for favors.
                </p>

                <p style="font-style:italic; margin-top:10px;">
                    Saya <span style="font-weight:700;" id="txtNameCrewStatement">&lt;&lt;Nama
                        Crew&gt;&gt;</span>
                    dengan ini
                    menyatakan dengan sesungguhnya bahwa saya tidak pernah memberi uang dan / atau Semacamnya kepada
                    siapapun staf Personalia Laut Andhika Eka Karya Sejahtera untuk diterima dan ditempatkan di atas
                    kapal.
                </p>
            </div>

            <div style="margin-top:30px; margin-left:20px; width:70%; font-size:13px;">
                <table style="border-collapse:collapse;">
                    <tr>
                        <td style="width:110px;">Date<br><span style="font-style:italic;">tanggal</span></td>
                        <td style="width:10px;">:</td>
                        <td><span style="font-weight:700;" id="txtStatementDate">&lt;&lt;Tanggal&gt;&gt;</span>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top:8px;">Vessel<br><span style="font-style:italic;">Kapal</span></td>
                        <td>:</td>
                        <td><span style="font-weight:700;" id="txtKapal">&lt;&lt;Kapal&gt;&gt;</span></td>
                    </tr>

                    <tr>
                        <td style="padding-top:8px;">Rank<br><span style="font-style:italic;">Jabatan</span></td>
                        <td>:</td>
                        <td><span style="font-weight:700;" id="txtRankStatement">&lt;&lt;Rank&gt;&gt;</span></td>
                    </tr>
                </table>
            </div>

            <div style="
                margin-top:40px; 
                display:flex; 
                justify-content:space-between; 
                width:90%; 
                margin-left:20px;
                margin-right:20px;
            ">
                <div style="text-align:left;">
                    Thank you.<br>
                    <span style="font-style:italic;">Terima kasih</span>
                </div>

                <div style="text-align:right;">
                    Acknowledge:<br>
                    <span style="font-style:italic;">Mengetahui</span>
                </div>
            </div>

            <div style="
                margin-top:60px; 
                display:flex; 
                justify-content:space-between; 
                width:90%; 
                margin-left:20px;
                margin-right:20px;
                align-items:flex-end;
            ">
                <div style="text-align:left;">
                    <div style="margin-bottom:55px;" id="txtCrewNameStatement">&lt;&lt;Nama Crew&gt;&gt;</div>
                    <div style="border-top:1px solid #333; width:160px; padding-top:5px;">
                        Seafarer
                    </div>
                </div>

                <div style="text-align:right;">
                    <div style="font-size:13px; font-weight:700; text-decoration:underline; margin-bottom:3px;">
                        EVA MARLIANA
                    </div>
                    <div style="font-size:12px;">Crew Manager</div>
                </div>
            </div>
            <hr>
            <div class="modal-footer" style="border-top:none; justify-content:center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveAndPrintStatementCrew">Print</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalStatementContract" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:900px;">
        <div class="modal-content" style="padding:60px 70px; font-family:'Times New Roman', serif; font-size:17px;">

            <table style="width:100%; border-collapse:collapse;">
                <tr>
                    <td style="width:90px; vertical-align:top;">
                        <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" style="width:80px;">
                    </td>

                    <td style="text-align:center; vertical-align:middle;">
                        <div style="font-size:15px; font-weight:bold; margin-top:3px;">STATEMENT OF CONTRACT ACCEPTANCE
                        </div>
                    </td>

                    <td style="width:170px; text-align:right; vertical-align:top;">
                        <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                        <div style="font-size:10px;">SIUKAK 236.121
                            - R Tahun 2025</div>
                        <div style="margin-top:5px;">
                            <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>"
                                style="width:60px; margin-right:3px;">
                            <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" style="width:60px;">
                        </div>
                    </td>
                </tr>
            </table>

            <div class="modal-body">

                <div style="margin-left:40px; margin-right:20px;">

                    <p style="text-align:justify; line-height:1.4; margin-bottom:20px;">
                        I am the undersigned freely accept on these articles in the Employment Contract that has:
                        <br>
                        <i>Saya yang bertanda tangan di bawah ini telah menerima pasal-pasal dalam kontrak kerja yang
                            telah:</i>
                    </p>

                    <ol style="padding-left:45px; line-height:1.45; margin-top:0; margin-bottom:20px;">
                        <li style="margin-bottom:12px;">
                            Reviewed the terms and condition of the employment contract, and
                            <br>
                            <i>saya mempelajari syarat dan kondisi dalam kontrak tersebut, dan</i>
                        </li>

                        <li>
                            Well briefed on the terms and condition of the employment contract
                            <br>
                            <i>mendapat penjelasan dengan baik mengenai syarat dan kondisi kontrak kerja tersebut.</i>
                        </li>
                    </ol>

                </div>


                <table style="width:100%; margin-top:25px; font-size:17px;">
                    <tr>
                        <td style="width:180px;">Name<br><i>Nama</i></td>
                        <td>: <b><span id="stmName">&lt;&lt;Nama Crew&gt;&gt;</span></b></td>
                    </tr>
                    <tr>
                        <td>D O B<br><i>Tanggal Lahir</i></td>
                        <td>: <b><span id="stmDob">&lt;&lt;Tanggal Lahir&gt;&gt;</span></b></td>
                    </tr>
                    <tr>
                        <td>Rank<br><i>Jabatan</i></td>
                        <td>: <b><span id="stmRank">&lt;&lt;Rank&gt;&gt;</span></b></td>
                    </tr>
                    <tr>
                        <td>Certificate<br><i>Ijazah</i></td>
                        <td>: <b><span id="stmSerpel">&lt;&lt;SERPEL&gt;&gt;</span></b></td>
                    </tr>
                </table>

                <p style="margin-top:30px; text-align:justify;">
                    If I deny the above statement, I am willing to pay an indemnity of which have been issued by the
                    company.<br>
                    <i>Jika saya menyangkal pernyataan di atas, saya bersedia membayar ganti rugi yang telah dikeluarkan
                        oleh perusahaan.</i>
                </p>

                <p style="margin-top:15px; text-align:justify;">
                    I hereby confirm the above contained herein is correct, without compulsion.<br>
                    <i>Demikian pernyataan ini saya buat dengan sebenarnya tanpa paksaan dari pihak lain.</i>
                </p>

                <p style="margin-top:25px;">
                    Thank you.<br>
                    <i>Terima kasih.</i>
                </p>

                <p style="margin-top:10px;">
                    Jakarta, <b><span id="stmTanggal">&lt;&lt;Tanggal&gt;&gt;</span></b>
                </p>

                <div style="
                    margin-top:40px;
                    width:100%;
                    display:flex;
                    justify-content:space-between;
                    font-weight:bold;
                ">
                    <div style="width:45%; text-align:center;">
                        Your Sincerely<br>
                        <i>Hormat Kami</i>
                    </div>

                    <div style="width:45%; text-align:center;">
                        Acknowledged by<br>
                        <i>Mengetahui</i>
                    </div>
                </div>

                <div style="
                    margin-top:70px; 
                    width:100%; 
                    display:flex; 
                    justify-content:space-between; 
                    align-items:flex-start;
                ">

                    <div style="
                        width:45%; 
                        text-align:center;
                        min-height:80px;     
                        display:flex;
                        flex-direction:column;
                        justify-content:flex-end;
                    ">
                        <b><span id="stmNameFooter">&lt;&lt;Nama Crew&gt;&gt;</span></b>
                        <div>Seafarer</div>
                    </div>

                    <div style="
                        width:45%; 
                        text-align:center;
                        min-height:80px;    
                        display:flex;
                        flex-direction:column;
                        justify-content:flex-end;
                    ">
                        <b>EVA MARLIANA</b>
                        <div>Crewing Manager</div>
                    </div>

                </div>



            </div>

            <div class="modal-footer" style="border-top:none; margin-top:40px;">
                <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" id="btnPrintStatement">Print</button>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modalCovid" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:900px;">
        <div class="modal-content" style="padding:0; border-radius:8px;">

            <div style="position:relative; width:100%;">
                <div style="
                    position:absolute;
                    top:50%;
                    left:-4%;
                    text-align:center;
                    width:100%;
                ">
                    <h2 style="margin:0; font-size:22px; font-weight:bold;">COVID-19</h2>
                    <h3 style="margin:0; font-size:18px; font-weight:bold;">PREVENTION</h3>
                </div>
                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:90px; vertical-align:top; padding:10px 15px;">
                            <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>"
                                style="width:80px; display:block; margin:auto;">
                        </td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <div style="padding:20px;">
                <table style="width:100%; border-collapse:collapse; font-size:14px;">
                    <tbody>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">

                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        Avoid these modes of travel if you have a fever or a cough.<br>
                                        <i>Hindari perjalanan moda transportasi ini apabila anda sedang sakit demam atau
                                            batuk.</i>
                                    </div>

                                    <!-- Gambar -->
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar1.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>

                                </div>

                            </td>
                        </tr>

                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        Eat only well-cooked food.<br>
                                        <i>Makanlah makanan yang dimasak matang.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar2.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>

                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        Avoid spitting in public.<br>
                                        <i>Hindarilah meludah di keramaian.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar3.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        Avoid close contact and travel with sick animals, particularly in wet
                                        markets.<br>
                                        <i>Hindari kontak jarak dekat dan bepergian dengan binatang yang sakit, terutama
                                            di
                                            pasar tradisional.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar4.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        When coughing and sneezing, cover your mouth and nose with a tissue or flexed
                                        elbow.
                                        Discard tissue immediately and clean hands.<br>
                                        <i>Ketika batuk dan bersin, tutuplah mulut dan hidung dengan tisu atau siku.
                                            Buang tisu
                                            ke tempat sampah dan bersihkan tangan.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar5.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        Frequently clean hands with alcohol-based hand rub or wash with soap at least 20
                                        seconds.<br>
                                        <i>Sering membersihkan tangan dengan hand sanitizer atau sabun dan air panas
                                            selama 20
                                            detik.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar6.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>

                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        Avoid touching eyes, nose, mouth.<br>
                                        <i>Hindari menyentuh mata, hidung, dan mulut.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar7.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        Avoid close contact with people suffering fever or cough.<br>
                                        <i>Hindari kontak dekat dengan orang yang menderita demam atau batuk.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar8.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        If wearing a mask, ensure it covers mouth and nose. Do not touch the mask.<br>
                                        <i>Jika memakai masker, pastikan menutupi mulut dan hidung, dan jangan sering
                                            menyentuhnya.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar9.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        If you become sick while traveling, tell the crew or ground staff.<br>
                                        <i>Jika sakit saat bepergian, beritahu petugas.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar10.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:12px; border:1px solid #ddd;">
                                <div style="display:flex; align-items:flex-start; gap:12px;">
                                    <div style="flex:1; font-size:14px;">
                                        Seek medical care early if you become sick and share history with the
                                        provider.<br>
                                        <i>Cari perawatan medis lebih awal jika sakit, dan ceritakan riwayat kesehatan
                                            pada
                                            penyedia layanan kesehatan.</i>
                                    </div>
                                    <div>
                                        <img src="<?php echo base_url("assets/img/gambar11.jpg"); ?>"
                                            style="width:120px; display:block;">
                                    </div>
                                </div>

                            </td>
                        </tr>

                    </tbody>
                </table>

                <table
                    style="width:100%; margin-top:25px; border-collapse:collapse; font-size:14px; text-align:center;">

                    <tr>
                        <td colspan="4" style="padding:5px 0;">
                            As International Chamber of Shipping Maritime Publications 2020<br>
                            Have read understand and will be implemented.
                        </td>
                    </tr>

                    <tr>
                        <td style="border:1px solid #000; padding:10px 5px; font-weight:bold;">RANK</td>
                        <td style="border:1px solid #000; padding:10px 5px; font-weight:bold;">NAME</td>
                        <td style="border:1px solid #000; padding:10px 5px; font-weight:bold;">SIGN</td>
                        <td style="border:1px solid #000; padding:10px 5px; font-weight:bold;">DATE</td>
                    </tr>

                    <tr>
                        <td style="border:1px solid #000; padding:10px 5px;" id="txtRankCovid">&lt;&lt;Rank&gt;&gt;</td>
                        <td style="border:1px solid #000; padding:10px 5px;" id="txtNameCovid">&lt;&lt;Nama Crew&gt;&gt;
                        </td>
                        <td style="border:1px solid #000; padding:10px 5px;">&nbsp;</td>
                        <td style="border:1px solid #000; padding:10px 5px;" id="txtDateCovid">&lt;&lt;Tanggal&gt;&gt;
                        </td>
                    </tr>

                </table>
            </div>
            <div class="modal-footer" style="border-top:none; justify-content:center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnPrintPrevention">Print</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSuratPernyataan" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="
            background:#fff; 
            width:80%; 
            margin:40px auto; 
            padding:20px 30px; 
            border-radius:8px; 
            border:1px solid #ccc;
            font-family:'Times New Roman', serif;
            font-size:14px;
            line-height:1.5;
            max-height:90vh;
            overflow-y:auto;">

            <h2 style="text-align:center; margin:0 0 20px 0; font-weight:bold; text-decoration:underline;">
                SURAT PERNYATAAN
            </h2>

            <p>Yang bertanda tangan di bawah ini:</p>

            <table style="width:100%; margin-bottom:15px; font-size:14px;">
                <tr>
                    <td style="width:200px;">Nama</td>
                    <td id="nmCrew">: -</td>
                </tr>
                <tr>
                    <td>Tempat & tgl. Lahir</td>
                    <td id="tmptTglLahir">: -</td>
                </tr>
                <tr>
                    <td>Jabatan / Nama Kapal</td>
                    <td id="jabatanNamaKapal">: -</td>
                </tr>
                <tr>
                    <td>No. Passport</td>
                    <td id="passport">: -</td>
                </tr>
            </table>


            <p>Dengan ini menyatakan sebagai berikut:</p>

            <p>Masa kerja di atas kapal dengan jabatan tersebut di atas berdasarkan Perjanjian Kerja Laut (PKL) yang
                dibuat
                antara saya dan PT. Andhini Eka Karya Sejahtera (selanjutnya disebut “Perusahaan”) tanggal
                ………………………………..
                adalah selama (<span id="txtDuration"><b></b></span>) Bulan. Namun saya memberi hak penuh kepada
                Perusahaan
                untuk
                menentukan
                pelabuhan tempat diturunkan (sign off) dari atas kapal dalam waktu 1 bulan sebelum atau sesudah
                berakhirnya
                masa PKL.</p>

            <p>Selama masa PKL, saya bersedia untuk tunduk dan patuh pada setiap ketentuan yang dikeluarkan oleh
                Perusahaan
                termasuk tetapi tidak terbatas: ketentuan jam kerja di atas kapal berdasarkan perundang-undangan yang
                berlaku disesuaikan dengan kegiatan operasional kapal yang ditetapkan oleh Nahkoda kapal dan/atau oleh
                Perusahaan.</p>

            <p>Saya setuju menerima gaji sebagaimana disebutkan dalam PKL dengan prosedur pembayaran sesuai ketentuan
                yang
                berlaku di Perusahaan dan perhitungan gaji dimulai sejak tanggal bekerja di atas kapal (sign on) dan
                akan
                berakhir sejak tanggal turun (sign off) dari kapal.</p>

            <p>Saya setuju menerima uang cuti (leave pay) yang besarnya ditentukan oleh Perusahaan dan pembayarannya
                dilakukan setelah turun dari kapal dan melaporkan diri ke Perusahaan dengan prosedur pembayaran sesuai
                ketentuan yang berlaku di Perusahaan.</p>

            <p>Saya bersedia dan tidak akan melakukan penuntutan di bidang keuangan ataupun lainnya, apabila Perusahaan
                memutuskan PKL dan/atau menurunkan (sign off) Saya dari kapal, dengan alasan sebagai berikut:<br>
                1. Secara tertulis Atasan menyatakan Saya: tidak cakap (incompetent) atau berkelakuan buruk atau lalai
                dalam
                kewajiban atau tidak patuh atau melanggar peraturan perusahaan atau tidak memiliki sertifikat yang
                disyaratkan;<br>
                2. Komplain tertulis dari atasan, pemilik kapal, pemilik barang, principal atau pihak ketiga lainnya
                berkaitan dengan tugas dan tanggung jawab Saya, yang dapat mempengaruhi usaha/bisnis Perusahaan.</p>

            <p>Saya berjanji akan mematuhi dan siap sedia dipindahkan ke kapal lain dengan dibuatkan PKL yang baru tanpa
                mempengaruhi masa kerja PKL ini, atas perintah atau pertimbangan Perusahaan. Apabila Saya menolak atas
                perintah pemindahan tersebut, maka Saya bersedia menerima konsekuensi sesuai ketentuan Perusahaan yang
                berlaku.</p>

            <p>Apabila Saya diturunkan dari atas kapal dan/atau diputuskan PKL karena alasan sebagaimana disebut butir 6
                di
                atas, maka saya bersedia dan berjanji akan membayar biaya pemulangan sampai di tempat dimana Saya
                dipekerjakan ditambah biaya pengurusan dan pengiriman pengganti Saya.</p>

            <p>Apabila secara sepihak atas permintaan sendiri Saya mengakhiri masa PKL, maka Saya bersedia memberikan
                tenggang waktu kepada Perusahaan paling sedikit satu (1) bulan:<br>
                - Bila masa kerja kurang dari 3 bulan → saya bersedia membayar biaya pemulangan + pengganti.<br>
                - Bila masa kerja lebih dari 3 bulan namun belum selesai PKL → saya bersedia membayar biaya pemulangan.
            </p>

            <p>Demikian pernyataan ini dibuat dalam keadaan sadar tanpa paksaan dari pihak manapun dengan disaksikan
                saksi-saksi di bawah ini.</p>

            <br><br>

            <table style="width:100%; margin-top:20px; font-size:14px;">
                <tr>
                    <td style="width:50%; text-align:center;">
                        Saksi I<br><br><br><br><br>
                        (...............................)
                    </td>
                    <td style="width:50%; text-align:center;">
                        Jakarta, <span id="tanggalSuratPernyataan"></span><br>
                        Yang membuat pernyataan<br><br><br><br>
                        (<span id="namaCrewSuratPernyataan">&lt;&lt;Nama Crew&gt;&gt;</span>)
                    </td>
                </tr>
                <td style="text-align:center;">
                    Saksi II<br><br><br><br><br>
                    (...............................)
                </td>
                <td style="text-align:center;">
                    Meterai 10000
                </td>
                </tr>
            </table>

            <hr style="margin:35px 0;">

            <h3 style="text-align:center; margin-bottom:20px; text-decoration:underline; font-weight:bold;">
                DAFTAR PELANGGARAN & TINDAKAN DISIPLIN
            </h3>

            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <tr>
                    <td style="width:60%; vertical-align:top; padding:10px; border:1px solid #000; font-weight:bold;">
                        Pelanggaran Hukum:
                    </td>
                    <td style="width:40%; vertical-align:top; padding:10px; border:1px solid #000; font-weight:bold;">
                        Tindakan Disiplin
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Pelanggaran undang-undang Republik Indonesia, Negara Bendera Kapal atau Negara Pelabuhan di mana
                        Kapal berada mengenai penyelundupan barang-barang, memiliki bahan porno, menggunakan atau
                        menjual-belikan obat bius atau menjual-belikan senjata api, atau melanggar setiap undang-undang
                        yang
                        menyebabkan keterlambatan Kapal.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Pernyataan tidak benar kepada pejabat bea cukai.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pelanggaran Pertama: Peringatan<br>
                        Pelanggaran Kedua: Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Pelanggaran undang-undang yang sifatnya ringan.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Sesuai Kebijaksanaan Nakhoda
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Desersi: meninggalkan tugas atau menghasut orang lain meninggalkan tugas.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Lalai dalam tugas jaga sehingga mengakibatkan kapal tidak layak laut.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Meninggalkan waktu tugas jaga tanpa pengganti yang diberi kuasa oleh Kepala Bagian, tidur selama
                        tugas jaga, atau berjaga di bawah pengaruh alkohol atau obat bius.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Meninggalkan kapal tanpa izin Nakhoda atau Kepala Bagian.
                    </td>
                    <td style="padding:10px; border:1px solid#000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Menolak bekerja lembur sebagaimana diinstruksikan oleh Kepala Bagian atau wakilnya, kecuali
                        alasan
                        sakit yang diterima baik oleh Kepala atau wakil Kepala Bagian.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Ketidakmampuan untuk berjaga disebabkan mabuk.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pelanggaran Pertama: Peringatan<br>
                        Pelanggaran Kedua: Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Menolak untuk mentaati perintah sah dari atasan, atau menghasut orang lain untuk melakukan hal
                        tersebut.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Memukul atau berusaha memukul rekan pelaut atau menghasut orang lain untuk melakukan hal
                        tersebut.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Berkelakuan tidak patuh pada atasan atau menghasut orang lain untuk berkelakuan tidak patuh.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Membawa seorang tamu ke kapal tanpa izin Nakhoda.
                        Bagi yang bertugas jaga (Jurumudi/Duty Officer), tidak mengidentifikasi setiap orang yang
                        berkunjung
                        ke kapal.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Ketinggalan kapal atau tidak kembali ke kapal sebagaimana diperintahkan oleh Nakhoda atau
                        wakilnya.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Setiap pelanggaran atas aturan-aturan dalam lampiran yang mengakibatkan keterlambatan kapal.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Pencurian atau percobaan pencurian, merusak dengan sengaja, atau menimbulkan kerusakan pada
                        harta
                        perusahaan atau orang lain.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Tidak memenuhi kewajiban sesuai jabatannya yang mengakibatkan kerusakan atau cedera pada kapal,
                        anak
                        buah, penumpang, atau muatan.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Kebijaksanaan Perusahaan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Perbuatan melanggar peraturan atau tindakan yang merusak nama baik kapal atau perusahaan baik di
                        kapal maupun di darat.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pelanggaran Pertama: Peringatan<br>
                        Pelanggaran Kedua: Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Tidak mampu dan/atau tidak sesuai dengan standar perusahaan dalam melaksanakan tugas jabatan
                        atau
                        perintah yang diberikan oleh atasan.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Dengan sengaja membuat pernyataan atau laporan yang tidak benar untuk keuntungan pribadi atau
                        orang
                        lain.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Penggelapan atau penggunaan tidak benar dana perusahaan atau barang-barang kapal.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
                <tr>
                    <td style="padding:10px; border:1px solid #000;">
                        Menyerang atau mencoba menyerang atasan dengan kata-kata dan/atau perbuatan.
                    </td>
                    <td style="padding:10px; border:1px solid #000; text-align:center;">
                        Pemecatan
                    </td>
                </tr>
            </table>
            <div class="modal-footer" style="border-top:none; justify-content:center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="printCrewStatement">Print</button>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="legalModalSeafarerContract" tabindex="-1" aria-labelledby="legalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-body">
                <div class="container-fluid" style="font-size:14px; line-height:1.6;">
                    <table
                        style="width:100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4;">
                        <thead>
                            <tr>
                                <th style="width:50%; text-align:center; font-weight:bold; padding: 8px;">
                                    SEAFARER WORKING CONTRACT</th>
                                <th style="width:50%; text-align:center; font-weight:bold; padding: 8px;">
                                    PERJANJIAN KERJA PELAUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    This Seafarer Working Contract, being enclosure and part of the Agreement signed
                                    between KESATUAN PELAUT INDONESIA and FINE OCEAN MARINE CO.LTD, BUSAN SOUTH OF KOREA
                                    CQ INTEROCEAN SHIPPING and MANNING Pte.ltd, 78A Duxton Road Singapore 089537 as
                                    Collective Bargaining Agreement (CBA) on ………………… ……………………………. made by and
                                    between:<br>
                                    <strong>PT ANDHINI EKA KARYA SEJAHTERA</strong><br>
                                    of MENARA KADIN INDONESIA BLDG 20TH FL, JL HR RASUNA SAID BLOK X-5, KAV.2-3
                                    ,KUNINGAN, JAKARTA SELATAN<br>
                                    hereinafter referred as the <strong>COMPANY</strong><br>
                                    And <strong>
                                        <span id="seafarerNameSuntechno"></span>
                                    </strong> (hereinafter called the seafarer)<br>
                                    Date of Birth : <span id="dobSeafarerSuntechno"></span><br>
                                    Place of Birth : <span id="placeOfBirthSuntechno"></span><br>
                                    Nationality : INDONESIA<br>
                                    Passport No : <span id="passportNoSuntechno"></span><br>
                                    Seaman Book No : <span id="seamanBookNoSuntechno"></span><br>
                                    Seafarer code : <span id="seafarerCodeSuntechno"></span><br>
                                    Home Address : <span id="addresSeafarerSuntechno"></span><br><br>
                                    <strong>Name of Ship :</strong><span id="vesselSeafarerSuntechno"></span><br>
                                    <strong>Flag :</strong><span id="flagSeafarerSuntechno"></span>><br>
                                    <strong>Ship Owner :</strong><span id="shipOwnerSeafarerSuntechno"></span><br>
                                    <strong>GRT / KW :</strong><span id="grtKw"></span><br>
                                    <strong>Area Navigation :</strong> URV<br>
                                    <strong>Crew Certificate :</strong><span
                                        id="crewCertificateSuntechno"></span><br><br>
                                    Whereby the following terms and
                                    conditions of employment are mutually
                                    agreed upon.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    Perjanjian Kerja Pelaut ini, yang merupakan lampiran serta bagian dari perjanjian
                                    yang ditandatangani antara KESATUAN PELAUT INDONESIA dan FINE OCEAN MARINE CO.LTD,
                                    BUSAN SOUTH OF KOREA CQ INTEROCEAN SHIPPING and MANNING Pte.Ltd, 78A Duxton Road,
                                    Singapore 089537 sesuai dengan Kesepakatan Kerja Bersama (KKB) pada tanggal :
                                    …………………………………………….<br>
                                    dibuat oleh dan antara:<br>
                                    <strong>PT ANDHINI EKA KARYA SEJAHTERA</strong><br>
                                    beralamat di MENARA KADIN INDONESIA BLDG 20TH FL, JL HR RASUNA SAID BLOK X-5,
                                    KAV.2-3, KUNINGAN, JAKARTA SELATAN. selanjutnya disebut sebagai
                                    <strong>PERUSAHAAN</strong><br>
                                    Dan <strong>
                                        <span id="seafarerNameSuntechno"></span>
                                    </strong> (dalam hal ini disebut Pelaut)<br>
                                    Tanggal Lahir : <span id="dobSeafarerSuntechno"></span><br><br>
                                    Tempat Lahir : <span id="placeOfBirthSuntechno"></span><br>
                                    Kebangsaan : INDONESIA<br>
                                    Passport No : <span id="passportNoSuntechno"></span><br>
                                    Buku Pelaut No : <span id="seamanBookNoSuntechno"></span><br>
                                    Kode Pelaut : <span id="seafarerCodeSuntechno"></span><br>
                                    Alamat : <span id="addresSeafarerSuntechno"></span><br><br>
                                    <strong>Nama Kapal :</strong>
                                    <<Kapal>><br>
                                        <strong>Bendera :</strong>
                                        <<Bendera Kapal>><br>
                                            <strong>Pemilik Kapal :</strong>
                                            <<Shipowner>><br>
                                                <strong>GRT / KW :</strong>
                                                <<GRT /KW>><br>
                                                    <strong>Daerah Navigasi :</strong> URV<br>
                                                    <strong>Kru Sertifikat :</strong>
                                                    <<SERPEL>><br><br>
                                                        Dalam hal mana, syarat-syarat serta
                                                        kondisi pengerjaan berikut telah
                                                        disepakati.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE I : ENGAGEMENT</strong><br>
                                    The Company will engage the Seafarer in accordance with the Agreement with the
                                    KESATUAN PELAUT INDONESIA, its enclosure and amendments (if any), and to be executed
                                    with utmost good faith.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL I : PENGERJAAN</strong><br>
                                    Perusahaan akan mempekerjakan Pelaut sesuai dengan Perjanjian dengan Kesatuan Pelaut
                                    Indonesia dengan lampiran-lampiran dan perubahan-perubahan (bila ada), dan akan
                                    dilaksanakan dengan itikad yang sebaik-baiknya.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE II : WAGES AND OVERTIME</strong><br>
                                    During the period this Individual Working Contract, the Seafarer shall be employed
                                    by the Company in the capacity of: <<Rank>> on board the <<Kapal>> and be paid a
                                            monthly basic wage: <<Gaji Pokok>> Fixed Over Time: <<Over Time>> Tanker
                                                    Allowance: <<Tunjangan Kapal Tanker>> Leave Pay: <<Leave Pay>>
                                                            TOTAL: <<Total Gaji>>
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL II : GAJI DAN UPAH LEMBUR</strong><br>
                                    Selama masa berlakunya Perjanjian Kerja Perorangan ini, Pelaut akan dipekerjakan
                                    oleh Perusahaan dalam kedudukan sebagai <<Rank>> diatas kapal <<Kapal>> dan akan
                                            dibayarkan gaji dasar bulanan sebesar: <<Gaji Pokok>> Upah Lembur: <<Over
                                                    Time>> Tunjangan Kapal Tanker: <<Tunjangan Kapal Tanker>> Uang
                                                        Pengganti hari-hari libur: <<Leave Pay>> Jumlah: <<Total Gaji>>
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE III : LEAVE PAY</strong><br>
                                    The Seafarer covered by an Individual Working Contract shall receive at least three
                                    (3) days leave pay a month at the Seafarer’s basic wage rate (without overtime) or
                                    as mentioned in the Agreement.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL III : UANG PENGGANTI HARI-HARI LIBUR</strong><br>
                                    Pelaut yang bekerja berdasarkan Perjanjian Kerja Perorangan ini akan menerima uang
                                    pengganti hari-hari libur paling sedikit 3 (tiga) hari perbulan atas dasar gaji
                                    pokok yang berlaku atau seperti dalam Perjanjian.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE IV : ALLOTMENT</strong><br>
                                    1. The Seafarer covered by this Individual Working Contract should file, either with
                                    the Company or the Master of the vessel a signed allotment not to be applied against
                                    a minimum of 80% of the accrued basic wages.<br>
                                    2. The Company shall thereupon arrange to remit a monthly allotment payable in
                                    …USD.. or its equivalent in local currency to the person named in the allotment
                                    note. Such remittance shall be made to the person named in the allotment note at the
                                    end of each month.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL IV : UANG DELEGASI</strong><br>
                                    1. Pelaut yang dilindungi oleh Perjanjian Kerja Perorangan ini harus mengajukan baik
                                    kepada Perusahaan atau kepada Nakhoda kapal, sesuai nota delegasi yang
                                    ditandatangani yang akan diperhitungkan dengan upah sebesar paling sedikit 80% dari
                                    upah pokok sebulan.<br>
                                    2. Perusahaan akan mengatur pengiriman delegasi bulanan dalam mata uang USD atau
                                    jumlah yang sama nilainya dalam mata uang setempat, kepada orang yang namanya
                                    disebut dalam nota delegasi. Pengiriman semacam ini akan dikirimkan kepada orang
                                    yang namanya disebut dalam nota delegasi pada setiap akhir bulan.
                                </td>
                            </tr>
                            <!-- ARTICLE V to XIV would continue here in the same pattern -->
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE V : WORKING HOURS</strong><br>
                                    Day Worker<br>
                                    The hours of work day worker shall be 8 (eight) hours per day Monday through Friday
                                    preferably between 8 AM to 5 PM, and 4 (four) hours per day on Saturday between 8 AM
                                    to 12 Noon.<br>
                                    Regular Watch. Deck Department and Engine Department<br>
                                    In port, crew members of these departments shall stand their regular watches as
                                    required by the Master of the vessel. Overtime rate shall apply for watches stood of
                                    work performed in port on Saturday afternoon, Sunday and Holidays.<br>
                                    At sea, crew members of these departments shall stand their regular watches as
                                    required by the Master of the vessel.<br>
                                    Catering Department<br>
                                    The working hours of Catering Department members shall be 8 (eight) hours each day
                                    in a spread preferably between 6 AM to 7 PM. When the crewmembers of the Catering
                                    Department are on day work, the hours of work shall preferably between 8 AM to 12
                                    Noon and 1 PM to 5 PM.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL V : JAM KERJA</strong><br>
                                    Pekerjaan Harian<br>
                                    Jam kerja bagi pekerja harian adalah 8 (delapan) jam sehari dimulai Senin sampai
                                    dengan Jumat, sebaiknya antara 8 pagi sampai jam 5 sore, dan 4 (empat) jam sehari
                                    pada hari Sabtu yang sebaiknya antara jam 8 pagi sampai jam 12 tengah hari.<br>
                                    Jaga Biasa. Bagian Deck dan Bagian Mesin<br>
                                    Di Pelabuhan awak kapal wajib menjalankan tugas jaga biasa sesuai perintah Nakhoda
                                    kapal. Upah lembur akan diberlakukan untuk jaga yang dilakukan atau pekerjaan yang
                                    dilaksanakan di pelabuhan pada hari Sabtu sesudah tengah hari, pada hari Minggu dan
                                    Hari Raya Resmi.<br>
                                    Di laut, awak kapal bagian ini wajib menjalankan tugas jaga biasa sesuai perintah
                                    Nakhoda kapal.<br>
                                    Bagian Pelayanan<br>
                                    Jam kerja awak kapal bagian pelayanan adalah 8 (delapan) jam sehari sebaiknya di
                                    rentang antara jam 6 pagi sampai jam 7 sore. Bila awak kapal bagian pelayanan
                                    bekerja harian, jam kerja sebaiknya adalah jam 8 pagi sampai jam 12 tengah hari dan
                                    jam 1 siang sampai jam 5 sore.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE VI : REST HOURS</strong><br>
                                    Each Seafarer shall have minimum of 10 hours rest in any 24 hour period may be
                                    divided into no more than 2 periods, one of which shall be at least 6 hours in
                                    length, and the interval between consecutive periods of rest shall not exceed 14
                                    hours.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL VI : JAM ISTIRAHAT</strong><br>
                                    Setiap Pelaut harus memiliki minimal 10 jam istirahat dalam setiap 24 jam dapat
                                    dibagi menjadi tidak lebih dari 2 periode, salah satunya harus setidaknya 6 jam, dan
                                    interval antara periode istirahat berturut-turut tidak boleh melebihi 14 jam.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE VII : EXCESS BAGGAGE</strong><br>
                                    While traveling to or from a vessel under this Individual Working Contract, the
                                    Seafarer shall be responsible for any expenses caused by excess baggage beyond the
                                    limitation imposed by the Transportation Company used for travel.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL VII : KELEBIHAN BARANG BAWAAN</strong><br>
                                    Ketika dalam perjalanan ke atau dari kapal dibawah Perjanjian Kerja Perorangan ini,
                                    Pelaut harus bertanggung jawab atas biaya yang timbul karena kelebihan barang bawaan
                                    di atas batas ketentuan yang ditetapkan oleh Perusahaan Pengangkutan yang
                                    dipergunakan untuk melakukan perjalanan.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE VIII : DISCIPLINE</strong><br>
                                    The seafarer, while employed on board a vessel of the Company, shall comply with all
                                    lawful orders of his superiors and division heads and will obey all Company’s rule.
                                    Recognizing the necessity for discipline on board Company vessel and at the same
                                    time in order to protect a Seafarer against unfair treatment, the Company agrees to
                                    post on the bulletin board of each vessel a list of rules which shall constitute
                                    reason for which Seafarer may be discharged without further notice. Such rules shall
                                    be written in such a way to enable the Seafarer to understand.<br>
                                    For other offence not on the posted list, Seafarer shall not be discharged without
                                    first having been notified in writing that a repetition on the offence will make him
                                    liable to dismissal.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL VIII : DISIPLIN</strong><br>
                                    Pelaut selama dipekerjakan diatas kapal milik Perusahaan, wajib mentaati setiap
                                    perintah yang sah dari atasannya dan kepala bagiannya serta akan mentaati peraturan
                                    Perusahaan. Mengakui pentingnya disiplin diatas kapal milik Perusahaan pada saat
                                    yang sama demi melindungi Pelaut terhadap tindakan yang tidak adil. Perusahaan
                                    setuju untuk menempelkan dikapal suatu peraturan yang menetapkan pemberitahuan
                                    pendahuluan. Peraturan semacam ini harus tertulis sedemikian rupa sehingga
                                    memungkinkah bagi Pelaut untuk dapat dimengerti.<br>
                                    Untuk pelanggaran lain yang tidak dimuat di dalam daftar, Pelaut tidak akan dipecat
                                    tanpa sebelumnya diberitahu secara tertulis bahwa pengulangan pelanggaran tersebut
                                    akan membuatnya dapat dipecat.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE IX : TRANSPORTATION AND WAGES UPON TERMINATION</strong><br>
                                    On termination of employment, the Seafarer shall be paid for our provided with
                                    transportation of kind class, as determined by the Company, to return to the place
                                    where he has been employed/place of engagement (if immigration laws permitting), or
                                    to the airport or seaport nearest the Seafarers home, to be determined by the
                                    Company in its sole discretion, and he shall be paid his wages (not to include
                                    overtime or travel time) up to and including his arrival in Jakarta.<br>
                                    The entitlement to repatriation may lapse if the seafarers concerned do not claim it
                                    within a reasonable period of time to be defined by national laws or regulations or
                                    collective agreements.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL IX : PENGANGKUTAN DAN UPAH SAAT DIAKHIRINYA PENGERJAAN</strong><br>
                                    Pada saat pengakhiran pengerjaan, Pelaut akan dibayarkan atau diberikan sarana
                                    angkutan sesuai jenis dan kelas yang ditentukan oleh Perusahaan, untuk kembali
                                    ketempat dimana dia diterima untuk dipekerjakan (bila peraturan keimigrasian
                                    mengijinkan) atau Bandar udara atau pelabuhan laut terdekat dari tempat tinggal
                                    Pelaut sesuai yang ditentukan Perusahaan, dan kepadanya akan dibayarkan upahnya
                                    (tidak termasuk upah lembur atau waktu perjalanan), sampai dengan tanggal tiba di
                                    bandar udara atau pelabuhan terdekat.<br>
                                    Hak repatriasi dapat hilang jika awak kapal yang bersangkutan tidak mengklaimnya
                                    dalam jangka waktu yang wajar yang ditentukan oleh undang-undang atau peraturan
                                    nasional atau perjanjian bersama.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE X : INSURANCE</strong><br>
                                    The Company shall, as a condition of employment, arrange insurance for its
                                    liabilities towards Seafarer serving under this Agreement, with regard to:<br>
                                    Crews effects<br>
                                    Personal accident<br>
                                    Loss of life/death in service<br>
                                    For loss and/or damage of crews effects, due to the ship accident, the maximum
                                    benefit amounts to USD 2000……………- This benefit does not cover money and securities.
                                    Benefit will be calculated according to the actual value of the object loss or
                                    damage. Benefit shall not be paid if the loss or damage caused by seafarer
                                    itself.<br>
                                    Accident<br>
                                    A Seafarer who suffered permanent 100% disability resulting of an accident during
                                    his contract period will be entitled to compensation of USD 40,000 for rating and
                                    USD 60,000 for officer.<br>
                                    In case of permanent partial disability the amount of the compensation will be
                                    calculated according the following table:<br>
                                    Loss of one arm 40%<br>
                                    Loss of two arms 100%<br>
                                    Loss of one palm 30%<br>
                                    Loss of two palms 80%<br>
                                    Loss of one leg from the thigh 40%<br>
                                    Loss of two legs from the thigh 100%<br>
                                    Loss of one foot 30%<br>
                                    Loss of two foots 80%<br>
                                    Loss of one eye 30%<br>
                                    Loss of two eyes 100%<br>
                                    Loss hearing of one ear 15%<br>
                                    Loss hearing of two ears 40%<br>
                                    Loss of one finger 10%<br>
                                    Loss of one toe 5%<br>
                                    As to any permanent partial disability not specified in this table the appropriate
                                    percentage shall be determined by the company’s Medical Director taking into account
                                    the seriousness of the disability related to the seriousness of the disabilities
                                    specified in this table.<br>
                                    In case of loss of several members/parts of the body, the amount of the compensation
                                    will be determined by adding the respective percentages, however the compensation
                                    shall not exceed the amount as in paragraph 3 a above.<br>
                                    4. Loss of live/death in service<br>
                                    In case an accident including accident occurring whilst traveling to and from the
                                    vessel, caused the death of a Seafarer, his next of skin, i.e. his lawful wife and
                                    children shall receive a compensation of USD 40,000 for rating and USD 60,000 for
                                    officer plus USD 8,000 for each child under the age 18 years but not exceeding three
                                    children.<br>
                                    The Company will make arrangements to cover also the death of Seafarer by other
                                    causes. Such arrangements should cover the amount USD 15,000. From this amount the
                                    Company may deduct all relevant cost for returning the body of the deceased
                                    Seafarer, but not to exceed US$ 3,000.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL X : PERTANGGUNGAN</strong><br>
                                    Perusahaan wajib, sebagai persyaratan pengerjaan mengatur pertanggungan bagi setiap
                                    Pelaut yang bekerja dibawah Perjanjian Kerja Perorangan seperti disebut dalam Pasal
                                    I yang menyangkut:<br>
                                    Barang bawaaan dan milik pribadi<br>
                                    Kecelakaan pribadi<br>
                                    Kematian alami/kematian akibat kecelakaan kerja<br>
                                    Untuk kehilangan dana/atau kerusakan barang bawaan milik pribadi, ganti kerugian
                                    berjumlah USD 2000………………. Pertanggungan ini tidak mencakup uang dan surat berharga.
                                    Ganti kerugian akan dihitung sesuai dengan nilai nyata barang yang hilang atau
                                    rusak. Tidak dilakukan pembayaran ganti rugi bila kehilangan atau kerusakan
                                    disebabkan oleh kelalaian atau kecerobohan Pelaut yang dipertanggungkan.<br>
                                    Kecelakaan<br>
                                    Pelaut yang mengalami kecelakaan kerja didalam tugasnya berhak menerima pembayaran
                                    pertanggungan bila kecelakaan berakibat cacat tetap yang menyebabkan hilangnya
                                    kemampuan kerja pada kedudukannya yang semula sejumlah USD 40,000 untuk rating dan
                                    USD 60,000 untuk officer.<br>
                                    Dalam hal cacat tetap sebagian jumlah pembayaran pertanggungan akan dihitung sesuai
                                    dengan tabel berikut:<br>
                                    Kehilangan satu lengan 40 %<br>
                                    Kehilangan dua lengan 100%<br>
                                    Kehilangan satu telapak tangan 30%<br>
                                    Kehilangan dua telapak tangan 80 %<br>
                                    Kehilangan satu kaki dari paha 40 %<br>
                                    Kehilangan dua kaki dar paha 100 %<br>
                                    Kehilangan satu kaki 30 %<br>
                                    Kehilangan dua kaki 80 %<br>
                                    Kehilangan satu mata 30 %<br>
                                    Kehilangan dua mata 100 %<br>
                                    Kehilangan pendengaran dari satu telinga 15 %<br>
                                    Kehilangan pendengaran dari dua telinga 40 %<br>
                                    Kehilangan satu jari tangan 10%<br>
                                    Kehilangan satu jari kaki 5%<br>
                                    Dalam hal cacat tetap sebagian yang tidak tercantum dalam daftar ini, Direktur
                                    Kesehatan perusahaan wajib memberikan persentase yang tepat berdasarkan pertimbangan
                                    dan akibat cacat tetap sebagian dalam daftar secara spesifik.<br>
                                    Dalam hal kehilangan beberapa bagian anggota badan, jumlah pembayaran pertanggungan
                                    ditentukan dengan cara menjumlah persentase yang bersangkutan, namun demikian jumah
                                    pertanggungan tidak akan melebihi jumlah sebagaimana yang tertera pada paragraph 3 a
                                    diatas.<br>
                                    Kematian Alami/kematian akibat kecelakaan kerja<br>
                                    Dalam hal kecelakaan yang menyebabkan kematian Pelaut, ahli warisnya yang sah, dalam
                                    hal ini istri dan anak-anaknya akan menerima pertanggungan sebesar USD 40,000 untuk
                                    rating dan USD 60,000 untuk officer ditambah USD 8,000 setiap anak dibawah 18 tahun
                                    tetapi tidak lebih dari 3 anak.<br>
                                    Perusahaan juga akan mengatur pertanggungan yang mencakup kematian Pelaut karena
                                    disebabkan penyebab lain. Pengaturan demikian harus mencakup jumlah sebesar USD
                                    15,000 Dari jumlah ini Perusahaan akan memotong semua biaya yang terkait untuk
                                    pemulangan jenazah, akan tetapi tidak boleh lebih dari US$ 3.000 (tiga ribu dollar
                                    Amerika Serikat Saja).
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE XI : EQUIPMENT FOR COLD CLIMATE</strong><br>
                                    In cold climate and winter times and in areas having temperature of 15 degrees
                                    centigrade or less, the Seafarer shall be provided with winter clothing and
                                    equipment. Such clothing and equipment shall at least consist of:<br>
                                    A winter overcoat or jacket<br>
                                    Scarf and head cover r the equivalent<br>
                                    Winter working shoes<br>
                                    Winter working gloves<br>
                                    Winter working clothes<br>
                                    Such equipment and clothing shall remain the property of the Company.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL XI : PERLENGKAPAN MUSIM DINGIN</strong><br>
                                    Di tempat beriklim dingin dan kawasan-kawasan yang bersuhu 15 derajat celcius atau
                                    kurang. Perusahaan wajib menyediakan pakaian musim dingin dan perlengkapan kepada
                                    Pelaut. Pakaian dan perlengkapan sekurang-kurangnya terdiri dari:<br>
                                    Overcoat atau jaket musim dingin<br>
                                    Pelindung leher, penutup kepala atau yang senilai<br>
                                    Sepatu kerja musim dingin<br>
                                    Sarung tangan musim dingin<br>
                                    Pakaian kerja musim dingin<br>
                                    Perlengkapan dan pakaian ini akan tetap menjadi milik Perusahaan.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE XII : PIRACY OR ARMED ROBBERY AGAINST SHIPS</strong><br>
                                    Seafarer’s Working Contract shall continue to have effect and wages shall continue
                                    to be paid while a seafarer is held captive on or off the ship as a result of acts
                                    of piracy or armed robbery against ships.<br>
                                    If a seafarer is held captive on or off the ship as a result of acts of piracy or
                                    armed robbery against ships, wages and other entitlements under the seafarer’s
                                    employment agreement or applicable national laws, shall continue to be paid during
                                    the entire period of captivity and until the seafarer is released and duly
                                    repatriated or, where the seafarer dies while in captivity, until the date of death
                                    as determined in accordance with applicable national laws or regulations.<br>
                                    Seafarers are entitled to repatriation if they are detained on or off the ship as a
                                    result of piracy or armed robbery of the ship.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL XII : PEMBAJAKAN ATAU PERAMPOKAN BERSENJATA TERHADAP
                                        KAPAL</strong><br>
                                    Kontrak Kerja Pelaut akan terus berlaku dan upah akan terus dibayarkan selama pelaut
                                    ditahan didalam atau diluar kapal sebagai akibat dari tindakan pembajakan atau
                                    perampokan bersenjata terhadap kapal.<br>
                                    Jika seseorang pelaut ditahan didalam atau diluar kapal sebagai akibat dari tindakan
                                    pembajakan atau perampokan bersenjata terhadap kapal, upah dan hak lainnya
                                    berdasarkan perjanjian kerja pelaut atau hukum nasional yang berlaku, akan terus
                                    dibayarkan selama seluruh periode penahanan , dan sampai awak kapal dibebaskan dan
                                    dipulangkan dengan sepatutnya atau, dimana awak kapal meninggal saat berada dalam
                                    tahanan, sampai tanggal kematian sebagaimana ditentukan sesuai dengan hukum atau
                                    peraturan nasional yang berlaku.<br>
                                    Pelaut berhak atas pemulangan jika mereka ditahan didalam atau diluar kapal sebagai
                                    akibat dari pembajakan atau perampokan bersenjata di kapal.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE XIII : DISPUTES</strong><br>
                                    A dispute or grievance in connection with the terms and provisions of this contract
                                    shall be adjusted in accordance with the following procedures:<br>
                                    Any seafarer who feels that he has been unjustly treated or been subjected to any
                                    unfair consideration shall endeavor to have said grievance adjusted by the
                                    designated representative of the Seafarer abroad the vessel in the following
                                    manner:<br>
                                    (i). Presentation of the complaint to his immediate superior.<br>
                                    (ii). Appeal to the head of the Department in which the employee involved as
                                    employed.<br>
                                    (iii). Appeal to the Master of the Vessel.<br>
                                    If the grievance cannot be solved under the provisions of paragraph 1, the decision
                                    of the Master shall govern at sea and in foreign ports. The disputes shall be
                                    referred to the representative of the Union, who, if he believes it has merit, shall
                                    attempt to solve it with the local representative of the company. The Company
                                    reserves the right, where necessary, to its head office for final settlement.
                                    Similarly, the representatives of the Union reserve the right. Where necessary, to
                                    refer a dispute to his National Office for disposition with the head office of the
                                    Company. It is understood, however, that this right will be used sparingly and that
                                    both parties will make every efforts to settle the disputes in the port where they
                                    arrive as amicably as possible.<br>
                                    During the process as mentioned in paragraph 1 and 2 above, the Seafarer shall
                                    perform his duties as usual.
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL XIII : PERSELISIHAN</strong><br>
                                    Suatu perselisihan atau keluh kesah yang timbul sehubungan dengan syarat-syarat
                                    ketentuan Perjanjian ini harus diselesaikan sesuai dengan tata cara berikut:<br>
                                    Setiap pelaut yang merasa bahwa dirinya diperlakukan kurang adil atau menjadi
                                    sasaran pertimbangan yang tidak adil akan berusaha menyelesaikan keluh kesah
                                    tersebut melalui wakil Pelaut yang ditunjuk diatas kapal dengan cara sebagai
                                    berikut:<br>
                                    (i). Mengajukan masalahnya kepada atasannya langsung.<br>
                                    (ii). Mengajukan kepada Kepala Bagiannya dimana yang bersangkutan dipekerjakan.<br>
                                    (iii). Mengajukan kepada Nakhoda Kapal.<br>
                                    Bila keluh kesah tak dapat dipecahkan berdasarkan ayat (1), keputusan Nakhoda akan
                                    tetap berlaku dilaut dan dipelabuhan asing. Perselisihan kemudian akan diajukan
                                    kepada wakil Serikat Buruh, yang bila memungkinkan akan berusaha untuk memecahkannya
                                    bersama wakil Perusahaan. Perusahaan tetap memiliki hak, bila perlu untuk meneruskan
                                    perselisihan ini ke kantor pusatnya untuk mendapatkan penyelesaian terakhir.
                                    Demikian pula Serikat Buruh mempunyai hak, bila perlu, untuk meneruskan perselisihan
                                    tersebut kepada kantor pusatnya untuk mempersoalkannya dengan kantor pusat
                                    Perusahaan. Harus diingat bahwa hal semacam ini bagaimanapun akan dipergunakan bila
                                    dianggap perlu, dan bahwa kedua belah pihak akan berusaha untuk menyelesaikan
                                    perselisihan di pelabuhan dimana perselisihan timbul dengan cara yang
                                    sebaik-baiknya.<br>
                                    Selama proses seperti tersebut dalam paragraf 1 dan 2 diatas. Pelaut harus tetap
                                    melaksanakan tugasnya seperti biasa.
                                </td>
                            </tr>
                            <tr>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>ARTICLE XIV : EFFECTIVE DATE AND DURATION OF AGREEMENT</strong><br>
                                    Effective date: this contract and all its provision shall take effect on ……………….<br>
                                    Duration: This contract shall continue to be valid until <<Durasi>> MONTH unless
                                        terminated by either party upon 30 (thirty) days written notice to the other
                                        party.<br><br>
                                        In witness of the aforesaid terms and condition both parties sign this contract
                                        this day: ..............<br><br>
                                        <strong>PT. ANDINI EKA KARYA SEJAHTERA<br>
                                            (AS AGENT ONLY)<br>
                                            Company/Perusahaan</strong><br><br><br>
                                        <strong>(EVA MARLIANA)<br>
                                            HEAD OF CREWING DIVISION</strong>
                                </td>
                                <td style="vertical-align: top; padding: 8px;">
                                    <strong>PASAL XIV : MULAI BERLAKUNYA DAN JANGKA WAKTU PERJANJIAN</strong><br>
                                    Tanggal berlakunya: Perjanjian ini dan semua ketentuan-ketentuannya akan mulai
                                    berlaku pada tanggal:….…………………….<br>
                                    Masa berlakunya: Perjanjian ini akan tetap berlaku sampai <<Durasi>> BULAN atau
                                        diakhiri oleh salah satu pihak dengan pemberitahuan tertulis 30 (tiga puluh)
                                        hari sebelumnya kepada pihak yang lain.<br><br>
                                        Sebagai kesaksian dari ketentuan dan syarat-syarat diatas, kedua belah pihak
                                        menandatangani Perjanjian ini tanggal :…………….<br><br>
                                        <strong>THE SEAFARER<br>
                                            Pelaut</strong><br><br><br>
                                        <strong>(<<NAMA CREW>>)</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalApproval" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header" style="background-color:#067780;  color:white;">
                <h4 class="modal-title" style="color:white;">Approval Evaluation List</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="color:white;">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered" style="font-size:15xpx;">
                        <thead style="background-color:#067780;color:white;">
                            <tr>
                                <th rowspan="2" style="text-align:center;">No</th>
                                <th rowspan="2" style="text-align:center;">Crew Name</th>
                                <th rowspan="2" style="text-align:center;">Rank</th>
                                <th rowspan="2" style="text-align:center;">Vessel Name</th>
                                <th colspan="3" style="text-align:center;">Date</th>
                                <th colspan="4" style="text-align:center;">Approve</th>
                            </tr>
                            <tr>
                                <th style="text-align:center;">Date Of Evaluation</th>
                                <th style="text-align:center;">Report Periode From</th>
                                <th style="text-align:center;">Report Periode To</th>
                                <th style="text-align:center;">Chief</th>
                                <th style="text-align:center;">Master</th>
                                <th style="text-align:center;">OS</th>
                                <th style="text-align:center;">Crewing</th>
                            </tr>
                        </thead>
                        <tbody id="tbApprovalBody"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<body>
    <div class="modal fade" id="modal-form-mlc" tabindex="-1">
        <div class="modal-dialog modal-lg" style="max-width:850px;">
            <div class="modal-content"
                style="border:1px solid #000; border-radius:6px; padding:25px; font-family:'Times New Roman', serif;">

                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:90px; vertical-align:top;">
                            <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" style="width:80px;">
                        </td>

                        <td style="text-align:center; vertical-align:middle;">
                            <br>
                            <div style="font-size:12px; font-weight:bold;margin-top:20px;margin-left:70px;">MLC
                                DECLARATION FORM</div>
                            <div class="long-line-header"
                                style="width: 36%;border-bottom: 1px solid #000;margin-left:205px;"></div>
                            <div style="font-size:15px; font-weight:bold; margin-top:1px;margin-left:70px;">FORM
                                PERNYATAAN MLC</div>
                            <br>
                        </td>

                        <td style="width:170px; text-align:right; vertical-align:top;">
                            <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                            <div style="font-size:10px;">SIUPPAK 12.12 Tahun 2014</div>
                            <div style="margin-top:5px;">
                                <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>"
                                    style="width:60px; margin-right:3px;">
                                <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" style="width:60px;">
                            </div>
                        </td>
                    </tr>
                </table>
                <table class="statement-table">
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-statement">Statement</th>
                        <th class="col-yes">Yes<br>Ya</th>
                        <th class="col-no-check">No<br>Tidak</th>
                    </tr>
                    <tr>
                        <td class="col-no">1</td>
                        <td class="col-statement">
                            All items contained in my employment contract have been explained to me and I am aware of
                            them.
                            <div class="subtext">
                                Semua hal yang terdapat dalam kontrak kerja saya telah dijelaskan kepada saya dan saya
                                memahaminya.
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_1" value="1"
                                data-no-checkbox="statement_1_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_1_no" value="0"
                                data-yes-checkbox="statement_1">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no">2</td>
                        <td class="col-statement">
                            A full sample agreement incorporating all terms and conditions to apply (including the CBA)
                            has been provided to me prior to entering the agreement.
                            <div class="subtext">
                                Contoh perjanjian yang lengkap yang menggabungkan semua ketentuan dan persyaratan
                                melamar (termasuk Kontrak Kerja Bersama) telah diberikan kepada saya sebelum memulai
                                perjanjian ini.
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_2" value="1"
                                data-no-checkbox="statement_2_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_2_no" value="0"
                                data-yes-checkbox="statement_2">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no">3</td>
                        <td class="col-statement">
                            I was given adequate time to review the contract and seek advice on the terms and conditions
                            in the agreement.
                            <div class="subtext">
                                Saya diberikan waktu yang mencukupi untuk memeriksa kontrak dan meminta nasihat mengenai
                                ketentuan dan persyaratan dalam perjanjian tersebut..
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_3" value="1"
                                data-no-checkbox="statement_3_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_3_no" value="0"
                                data-yes-checkbox="statement_3">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no">4</td>
                        <td class="col-statement">
                            I freely entered into the agreement with a suficient understanding of my rights and
                            responsibilities.
                            <div class="subtext">
                                Saya bebas mengadakan perjanjian dengan pemahaman yang memadai mengenai hak dan
                                tanggungjawab saya.
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_4" value="1"
                                data-no-checkbox="statement_4_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_4_no" value="0"
                                data-yes-checkbox="statement_4">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no">5</td>
                        <td class="col-statement">
                            I was given an original set of my Seafarers Employment Agreement, which I must carry with me
                            on board.
                            <div class="subtext">
                                Saya diberikan satu berkas Perjanjian Kerja Pelaut yang asli, yang saya harus bawa di
                                atas kapal.
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_5" value="1"
                                data-no-checkbox="statement_5_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_5_no" value="0"
                                data-yes-checkbox="statement_5">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no">6</td>
                        <td class="col-statement">
                            No fees or other charges for my recruitment or placement or for providing employment to me
                            have incurred directly or indirectly, in whole or part.
                            <div class="subtext">
                                Tidak diadakan biaya maupun beban lainnya untuk perekrutan dan penempatan saya atau
                                untuk memberikan pekerjaan kepada saya secara langsung atau tidak langsung, secara
                                keseluruhan atau sebagian.
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_6" value="1"
                                data-no-checkbox="statement_6_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_6_no" value="0"
                                data-yes-checkbox="statement_6">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no">7</td>
                        <td class="col-statement">
                            No joining advances or any other exploitation incurred with regard to the employment.
                            <div class="subtext">
                                Tidak ada biaya untuk bergabung ataupun eksploitasi lainnya sehubungan dengan pekerjaan
                                tersebut.
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_7" value="1"
                                data-no-checkbox="statement_7_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_7_no" value="0"
                                data-yes-checkbox="statement_7">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no">8</td>
                        <td class="col-statement">
                            The Company's Complaint procedure has been explained to me and I am fully aware of the
                            process to be followed and the record to be used.
                            <div class="subtext">
                                Prosedur keluhan perusahaan telah dijelaskan kepada saya dan saya sepenuhnya mengetahui
                                proses yang harus diikuti dan catatan yang akan digunakan.
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_8" value="1"
                                data-no-checkbox="statement_8_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_8_no" value="0"
                                data-yes-checkbox="statement_8">
                        </td>
                    </tr>
                    <tr>
                        <td class="col-no">9</td>
                        <td class="col-statement">
                            The terms and conditions of employment and my particular conditions applicable to the job
                            for which I am engaged have been explained to me.
                            <div class="subtext">
                                Ketentuan dan persyaratan pekerjaan serta persyaratan tertentu yang berlaku terhadap
                                pekerjaan di mana saya terlibat telah dijelaskan kepada saya.
                            </div>
                        </td>
                        <td class="col-yes">
                            <input type="checkbox" class="check-box yes-checkbox" name="statement_9" value="1"
                                data-no-checkbox="statement_9_no">
                        </td>
                        <td class="col-no-check">
                            <input type="checkbox" class="check-box no-checkbox" name="statement_9_no" value="0"
                                data-yes-checkbox="statement_9">
                        </td>
                    </tr>
                </table>
                <br />
                <ul style="font-size:13px; padding-left:10px; margin:0;">
                    <li>
                        By ticking the YES box you indicate that the documented statement is correct.<br>
                        <div class="long-line"></div>
                        Dengan mencentang kotak YA yang anda tandai bahwa pernyataan yang dituliskan adalah benar.
                    </li>
                    <br>
                    <li>
                        By ticking the NO box you indicate that the documented statement is NOT correct.<br>
                        <div class="long-line"></div>
                        Dengan mencentang kotak TIDAK yang anda tandai bahwa pernyataan yang dituliskan adalah TIDAK
                        benar.
                    </li>
                    <br>
                    <li>
                        If any statement is answered NO you may enter your remarks below.<br>
                        <div class="long-line"></div>
                        Jika pernyataan dijawab TIDAK anda dapat mencantumkan keterangan anda di bawah ini.
                    </li>
                </ul>
                <!-- REMARKS -->
                <div class="remarks-title">
                    <strong>Remarks:</strong><br>
                    <em>Keterangan:</em>
                </div>

                <!-- <div class="remarks-box"></div> -->

                <!-- SIGNATURE / DETAILS -->
                <div class="sign-container">
                    <div class="sign-table-wrapper">
                        <table class="sign-grid">
                            <tr>
                                <td class="sign-box">
                                    <div class="sign-title">Seafarer's Name</div>
                                    <div class="long-line-header"
                                        style="width: 35%;border-bottom: 1px solid #000;margin-left:80px;"></div>
                                    <div class="sign-sub" id="name-crew-mlc">Nama Pelaut</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="sign-table-wrapper">
                        <table class="sign-grid">
                            <tr>
                                <td class="sign-box">
                                    <div class="sign-title">Rank</div>
                                    <div class="long-line-header"
                                        style="width: 10%;border-bottom: 1px solid #000;margin-left:112px;"></div>
                                    <div class="sign-sub" id="jabatan-crew-mlc">Jabatan</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="sign-table-wrapper">
                        <table class="sign-grid">
                            <tr>
                                <td class="sign-box">
                                    <div class="sign-title">Date</div>
                                    <div class="long-line-header"
                                        style="width: 10%;border-bottom: 1px solid #000;margin-left:112px;"></div>
                                    <div class="sign-sub" id="date-crew-mlc">Tanggal</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="sign-container">
                    <div class="sign-table-wrapper">
                        <table class="sign-grid">
                            <tr>
                                <td class="sign-box">
                                    <div class="sign-title">Eva Marliana</div>
                                    <div class="long-line-header"
                                        style="width: 20%;border-bottom: 1px solid #000;margin-left:155px;"></div>
                                    <div class="sign-sub">Crew Manager</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="sign-table-wrapper">
                        <table class="sign-grid">
                            <tr>
                                <td class="sign-box">

                                    <div class="sign-title">Vessel to Join</div>
                                    <div class="long-line-header"
                                        style="width: 20%;border-bottom: 1px solid #000;margin-left:155px;"></div>
                                    <div class="sign-sub" id="vessel-crew-mlc">Kapal yang akan dituju</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="txtIdPerson" value="">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="btn-print-form-mlc"
                        onclick="click_print_sdfsdf()">Print</button>
                </div>
            </div>

        </div>
    </div>



     <div class="modal fade debriefing-modal" id="modal-form-debriefing" tabindex="-1"
        aria-labelledby="debriefingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <div class="debriefing-body">
                        <table style="width:100%; border-collapse:collapse;">
                            <tr>
                                <td style="width:90px; vertical-align:top;">
                                    <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" style="width:80px;">
                                </td>

                                <td style="text-align:center; vertical-align:middle;">
                                    <div style="font-size:20px; font-weight:bold; margin-top:1px;margin-left:70px;margin-top:80px;">DEBRIEFING </div>
                                     <div class="long-line-header"
                                      style="width: 20%;border-bottom: 1px solid #000;margin-left:310px;"></div>
                                    <br>
                                </td>

                                <td style="width:170px; text-align:right; vertical-align:top;">
                                    <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                                    <div style="font-size:10px;">SIUPPAK 12.12 Tahun 2014</div>
                                    <div style="margin-top:5px;">
                                        <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>"
                                            style="width:60px; margin-right:3px;">
                                        <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" style="width:60px;">
                                    </div>
                                </td>
                            </tr>
                        </table>

                            <table class="data-utama-table"
                                style="width:100%; border-collapse:collapse; margin-top:15px;">
                                <tr>
                                    <td style="width:25%; font-weight:bold;">Nama Kapal</td>
                                    <td style="width:25%;">:<smal id="val-vessel-defbreafing" style="padding:7px;"></smal></td>
                                    <td style="width:25%; font-weight:bold;">Pelabuhan</td>
                                    <td style="width:25%;">:<smal id="val-palabuhan-defbreafing" style="padding:7px;"></smal></td>
                                </tr>

                                <tr>
                                    <td style="font-weight:bold;">Jabatan</td>
                                    <td>:<smal id="val-jabatan-defbreafing" style="padding:7px;"></smal></td>
                                    <td style="font-weight:bold;">No. Telepon / HP</td>
                                    <td>:<smal id="val-telp-defbreafing" style="padding:7px;"></smal></td>
                                </tr>

                                <tr>
                                    <td style="font-weight:bold;">Nama Crew</td>
                                    <td>:<smal id="val-namecrew-defbreafing" style="padding:7px;"></smal></td>
                                </tr>

                                <tr>
                                    <td style="font-weight:bold;">Tgl. Join</td>
                                    <td>:<smal id="val-tgljoin-defbreafing" style="padding:7px;"></smal></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight:bold;">Tgl. Sign Off</td>
                                    <td>:<smal id="val-tglsignoff-defbreafing" style="padding:7px;"></smal></td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <td style="font-weight:bold;">Kesiapan Join</td>
                                    <td>:<smal id="val-siapjoin-defbreafing" style="padding:7px;"></smal></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            </table>


                            <!-- CERTIFICATE -->
                            <div class="section-title">
                                Certificates and documents yang harus diperbaharui atau dilengkapi :
                            </div>
                            <div class="remarks-box"></div>

                            <!-- PERTANYAAN -->
                            <table class="question-table" style="margin-top:15px;">
                                <tr>
                                    <th style="width:40px;">No</th>
                                    <th>Pertanyaan</th>
                                    <th style="width:45%;">Jawaban</th>
                                </tr>

                                <tr>
                                    <td align="center">1</td>
                                    <td>Apa rencana kegiatan anda selama masa cuti?</td>
                                    <td class="answer-box"></td>
                                </tr>

                                <tr>
                                    <td align="center">2</td>
                                    <td>Seperti apa dan bagaimana penerapan kesehatan, keselamatan dan keamanan kerja di
                                        kapal?</td>
                                    <td class="answer-box"></td>
                                </tr>

                                <tr>
                                    <td align="center">3</td>
                                    <td>Training crew apa saja yang dilakukan di kapal?</td>
                                    <td class="answer-box"></td>
                                </tr>

                                <tr>
                                    <td align="center">4</td>
                                    <td>
                                        Masalah-masalah apa yang anda hadapi di kapal dan bagaimana penyelesaiannya?
                                        <br><br>
                                        <strong>Masalah :</strong><br><br>
                                        <strong>Penyelesaian :</strong>
                                    </td>
                                    <td class="answer-box"></td>
                                </tr>
                                <tr>
                                    <td align="center">5</td>
                                    <td>Bagaimana kondisi kerja tim di kapal?</td>
                                    <td class="answer-box"></td>
                                </tr>

                                <tr>
                                    <td align="center">6</td>
                                    <td>Berikan tanggapan anda mengenai kebersihan di atas kapal.</td>
                                    <td class="answer-box"></td>
                                </tr>

                                <tr>
                                    <td align="center">7</td>
                                    <td>Berikan tanggapan anda mengenai makanan di atas kapal.</td>
                                    <td class="answer-box"></td>
                                </tr>

                                <tr>
                                    <td align="center">8</td>
                                    <td>Bagaimana kondisi kesehatan anda saat ini / setelah sign off?</td>
                                    <td class="answer-box"></td>
                                </tr>

                                <tr>
                                    <td align="center">9</td>
                                    <td>Sebutkan harapan dan saran anda.</td>
                                    <td class="answer-box"></td>
                                </tr>
                            </table>

                            <!-- REMARKS -->
                            <div class="section-title">
                                Remarks / Comment :
                                <br><em>*diisi oleh crew executive</em>
                            </div>
                            <div class="remarks-box"></div>

                            <!-- TANDA TANGAN -->
                            <table class="info-table" style="margin-top:15px;">
                                <tr>
                                    <td class="label" style="color:black;font-size:13px;">Tanggal : <small><?php echo date("d M Y", strtotime(date('Y-m-d'))); ?></small></td>
                                </tr>
                            </table>

                            <table class="signature-table" style="margin-top:25px; width:100%;">
                                <tr>
                                    <td>Crew Manager</td>
                                    <td>Crew Executive</td>
                                    <td>Seafarer</td>
                                </tr>
                            </table>

                            <div class="footer">
                                CD. 42 / 1-03-2017
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" id="btn-form-bereafing">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </div>
                </div>
           
            </div>
        </div>
    </div>

    
    <style>
        .debriefing-body {
            font-family: "Times New Roman", serif;
            font-size: 13px;
            padding: 25px;
        }

        .debriefing-container {
            width: 100%;
            border: 1px solid #000;
            padding: 15px;
            background-color: white;
        }

        .debriefing-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .label {
            width: 200px;
        }

        .line {
            border-bottom: 1px solid #000;
            height: 18px;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
        }

        .question-table th,
        .question-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        .question-table th {
            text-align: center;
        }

        .answer-box {
            height: 60px;
            background-color: #f8f9fa;
        }

        .remarks-box {
            width: 100%;
            height: 70px;
            border: 1px solid #000;
            margin-bottom: 14px;
            background-color: #f8f9fa;
        }

        .signature-table td {
            height: 90px;
            vertical-align: bottom;
            text-align: center;
            width: 33.33%;
            border-top: 1px solid #000;
            padding-top: 10px;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
        }

        /* Modal khusus untuk form debriefing */
        .debriefing-modal .modal-dialog {
            max-width: 90%;
            width: 1000px;
        }

        .debriefing-modal .modal-content {
            min-height: 90vh;
        }

        .debriefing-modal .modal-body {
            padding: 0;
        }

        @media print {

            .modal-header,
            .modal-footer {
                display: none !important;
            }

            .modal-dialog {
                max-width: 100% !important;
                margin: 0 !important;
            }

            .modal-content {
                border: none !important;
                box-shadow: none !important;
            }

            .modal-body {
                padding: 0 !important;
            }
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-center {
            text-align: center;
            flex-grow: 1;
        }

        .header-right {
            text-align: right;
        }

        .company-logo {
            width: 80px;
            height: auto;
        }

        .cert-logos {
            display: flex;
            gap: 3px;
            justify-content: flex-end;
            margin-top: 5px;
        }

        .cert-logos img {
            width: 60px;
            height: auto;
        }
    </style>

</html>