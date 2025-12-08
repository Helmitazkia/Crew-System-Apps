<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Crewing System">
    <meta name="author" content="andhika group">
    <title>Crew Portal</title>

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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script>
    $(document).ready(function() {
        function adjustNavDisplay() {
            var width = $(window).width();
            var $navMenu = $('#navMenu');
            var $toggleBtn = $('#toggleNav');

            if (width <= 768) {
                $toggleBtn.show();
                $navMenu.css({
                    'display': 'none',
                    'width': '100%',
                    'flex-direction': 'column',
                    'background-color': '#067780',
                    'text-align': 'left'
                });
            } else {
                $toggleBtn.hide();
                $navMenu.css({
                    'display': 'flex',
                    'width': 'auto',
                    'flex-direction': 'row',
                    'background-color': 'transparent'
                });
            }
        }
        $(window).on('resize load', adjustNavDisplay);

        $('#toggleNav').on('click', function() {
            var $menu = $('#navMenu');
            if ($menu.css('display') === 'none') {
                $menu.css('display', 'flex');
            } else {
                $menu.css('display', 'none');
            }
        });
    });

    function saveDataPersonalCrew() {
        var formData = new FormData();
        var idPerson = $("#txtIdPersonCrew").val();

        var fullname = $("#txtFnameCrew").val().trim();
        var nameParts = splitFullName(fullname);

        formData.append('idperson', idPerson);
        formData.append('fname', nameParts.fname);
        formData.append('mname', nameParts.mname);
        formData.append('lname', nameParts.lname);
        formData.append('pob', $("#txtPobCrew").val());
        formData.append('dob', $("#txtDobCrew").val());
        formData.append('paddress', $("#txtAddressCrew").val());
        formData.append('ssn', $("#txtSsnCrew").val());
        formData.append('ptn', $("#txtPtnCrew").val());
        formData.append('txtKodePelautCrew', $("#txtKodePelautCrew").val());
        formData.append('mobileno', $("#txtMobileNoCrew").val());
        formData.append('telpno', $("#txtTelpNoCrew").val());
        formData.append('next_of_kin', $("#txtNextOfKinCrew").val());
        formData.append('email', $("#txtEmailCrew").val());
        //home
        formData.append('norek', $("#txtNorekHomeCrew").val());
        formData.append('bank_name', $("#txtNamaBankHomeCrew").val());
        formData.append('norek_name', $("#txtPemilikHomeCrew").val());
        //board
        formData.append('norek_boat', $("#txtNorekBoardCrew").val());
        formData.append('bank_name_boat', $("#txtNamaBankBoardCrew").val());
        formData.append('norek_name_boat', $("#txtPemilikBoardCrew").val());
        formData.append('applyfor', $("#slcApplyForCrew").val());
        formData.append('crew_vessel_type', $("#slcVesselTypeCrew").val());
        formData.append('religion', $("#slcReligionCrew").val());
        formData.append('newapplicent', '1');

        var picFile = $("#filePicCrew")[0].files[0];
        if (picFile) {
            formData.append('pic', picFile);
        }

        $("#btnSaveCrew").prop('disabled', true);
        $("#btnSaveCrew").html('<i class="glyphicon glyphicon-hourglass"></i> Saving...');

        $.ajax({
            url: "<?php echo base_url('crew/saveDataPersonalCrew'); ?>",
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    if (response.idperson) {
                        $("#txtIdPersonCrew").val(response.idperson);
                        checkPersonalDataStatus();
                        loadCrewData(response.idperson);
                    }
                } else {
                    alert(response.error || "Terjadi kesalahan saat menyimpan data.");
                }

                $("#btnSaveCrew").prop('disabled', false);
                $("#btnSaveCrew").html('💾 Save Data Crew');
            },
            error: function(xhr, status, error) {
                alert("Error: " + error);
                $("#btnSaveCrew").prop('disabled', false);
                $("#btnSaveCrew").html('💾 Save Data Crew');
            }
        });
    }

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

        formData.append('fileUpload', $("#uploadFile").prop('files')[0]);

        $("#idLoadingSpinner").fadeIn();
        $.ajax("<?php echo base_url('crew/saveAllCertificate'); ?>", {
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
                $("#idLoadingSpinner").fadeOut();
                location.reload();
                $("#sectionCertificate").show();
            }
        });
    }

    $(document).ready(function() {
        $.ajax({
            url: "<?php echo base_url('crew/getLatestIdPerson'); ?>",
            type: "GET",
            dataType: "json",
            success: function(data) {
                if (data && !data.error) {
                    console.log("Data new_applicant:", data);

                    $("#txtFnameCrew").val(data.fullName);
                    $("#txtPersonalName").val(data.fullName);
                    $("#txtDobCrew").val(data.dob);
                    $("#txtEmailCrew").val(data.email);
                    $("#txtMobileNoCrew").val(data.mobileno);
                    $("#slcApplyForCrew").val(data.applyfor);
                    $("#slcVesselTypeCrew").val(data.crew_vessel_type);

                    let genderVal = data.gender === "Male" ? "M" :
                        data.gender === "Female" ? "F" : "";
                    $("#slcGenderCrew").val(genderVal);

                    $("#slcPobCrew").val(data.pob_code);
                    $("#slcReligionCrew").val(data.religion);
                    $("#txtIdPersonCrew").val(data.idperson);
                    $("#txtIdPersonAllCertificate").val(data.idperson);
                } else {
                    console.warn("Data new_applicant tidak ditemukan atau kosong");
                }
            },
            error: function() {
                console.error("Gagal mengambil data dari new_applicant");
            }
        });
    });

    function splitFullName(fullname) {
        var words = fullname.split(' ').filter(word => word.length > 0);
        var result = {
            fname: '',
            mname: '',
            lname: ''
        };

        if (words.length === 0) {
            return result;
        }

        if (words.length <= 3) {

            result.fname = words[0] || '';
            result.mname = words[1] || '';
            result.lname = words[2] || '';
        } else if (words.length === 4) {
            // Untuk 4 kata: 1-1-2
            result.fname = words[0];
            result.mname = words[1];
            result.lname = words.slice(2).join(' ');
        } else if (words.length === 5) {
            // Untuk 5 kata: 1-1-3
            result.fname = words[0];
            result.mname = words[1];
            result.lname = words.slice(2).join(' ');
        } else if (words.length === 6) {
            // Untuk 6 kata: 2-2-2
            result.fname = words.slice(0, 2).join(' ');
            result.mname = words.slice(2, 4).join(' ');
            result.lname = words.slice(4, 6).join(' ');
        } else {
            // Untuk lebih dari 6 kata: 2-2-sisanya
            result.fname = words.slice(0, 2).join(' ');
            result.mname = words.slice(2, 4).join(' ');
            result.lname = words.slice(4).join(' ');
        }

        return result;
    }

    $(function() {

        function hideAll() {
            const ids = ['#sectionPersonal', '#sectionCertificate', '#sectionViewData',
                '#sectionEmpty', '#sectionPersonalID'
            ];
            $.each(ids, function(_, id) {
                $(id).css({
                    display: 'none',
                    opacity: 0,
                    transform: 'translateY(10px)'
                });
            });

            const navs = ['#navPersonal', '#navCertificate', '#navView', '#navPersonalID'];
            $.each(navs, function(_, n) {
                $(n).css({
                    boxShadow: '0 6px 16px rgba(8,34,67,0.04)',
                    transform: 'scale(1)'
                });
            });
        }

        window.switchSection = function(id) {
            hideAll();

            const $el = $('#' + id);
            if (!$el.length) return;

            $el.css('display', 'block');
            setTimeout(() => {
                $el.css({
                    opacity: 1,
                    transform: 'translateY(0)',
                    transition: 'all .4s ease'
                });
            }, 30);

            const map = {
                'sectionPersonal': '#navPersonal',
                'sectionCertificate': '#navCertificate',
                'sectionViewData': '#navView',
                'sectionPersonalID': '#navPersonalID'
            };

            $.each(map, function(k, selector) {
                const $btn = $(selector);
                if (k === id) {
                    $btn.css({
                        boxShadow: '0 10px 26px rgba(33,66,132,0.14)',
                        transform: 'translateY(-2px)',
                        transition: 'all .25s ease'
                    });
                } else {
                    $btn.css({
                        boxShadow: '0 6px 16px rgba(8,34,67,0.04)',
                        transform: 'scale(1)',
                        transition: 'all .25s ease'
                    });
                }
            });

            if (id) {
                $('#sectionEmpty').hide();
            } else {
                $('#sectionEmpty').show().css({
                    opacity: 1,
                    transform: 'translateY(0)'
                });
            }
        };

        $("#navView").on("click", function() {
            let idperson = $("#txtIdPersonCrew").val();
            if (idperson) {
                loadCrewData(idperson);
                switchSection("sectionViewData");
            } else {
                alert("ID Person belum diisi!");
            }
        });

        $("#navPersonalID").on("click", function() {
            let idperson = $("#txtIdPersonCrew").val();
            if (idperson) {
                switchSection("sectionPersonalID");
            } else {
                alert("ID Person belum diisi!");
            }
        });

        hideAll();
        $('#sectionEmpty').css({
            display: 'block',
            opacity: 1,
            transform: 'translateY(0)'
        });
        checkPersonalDataStatus();
    });

    $(document).on("focus", "#slcMstCertAllCert", function() {
        $.ajax({
            url: "<?php echo base_url('crew/getCrewCertificatesOption'); ?>",
            type: "POST",
            data: {},
            dataType: "html",
            success: function(res) {
                console.log("RAW:", res);
                $('#slcMstCertAllCert').html(res);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                $('#slcMstCertAllCert').html("<option value=''>Error</option>");
            }
        });
    });




    $(document).on('change', '#slcMstCertAllCert', function() {

        let certId = $(this).val();
        let idPerson = $("#txtIdPersonCrew").val();

        if (!certId || !idPerson) return;

        $("#idLoadingSpinner").css("display", "flex");

        $.ajax({
            url: "<?php echo base_url("crew/getCertificateDetailByCertId"); ?>",
            type: "POST",
            data: {
                idPerson: idPerson,
                cert_id: certId
            },
            dataType: "json",
            success: function(res) {

                $("#idLoadingSpinner").hide();

                if (!res) {
                    alert("Certificate data not found!");
                    return;
                }

                // isi data
                $("#txtNoDocumentAllCert").val(res.docno || "");
                $("#txtDate_ofIssueAllCert").val(res.issdate === "0000-00-00" ? "" : res.issdate);
                $("#txtDate_expiryAllCert").val(res.expdate === "0000-00-00" ? "" : res.expdate);
                $("#txtPlaceofIssueAllCert").val(res.issplace || "");
                $("#txtIssuingAuthorityAllCert").val(res.issauth || "");
                $("#txtRemarkAllCert").val(res.remarks || "");

                $("#slcLicenseAllCert").val(res.license || "-");
                $("#slcLevelAllCert").val(res.level || "-");
                $("#slcRankAllCert").val(res.kdrank || "");
                $("#slcVesselTypeAllCert").val(res.vsltype || "");
                $("#slcCountryIssueAllCert").val(res.kdnegara || "");

                if (res.certificate_file) {
                    $("#previewCertificateFile").html(`
                    <a href="${base_url}/uploadCertificate/${res.certificate_file}"
                       target="_blank"
                       style="color:blue;font-weight:bold;">
                       View Uploaded File
                    </a>
                `);
                } else {
                    $("#previewCertificateFile").html(
                        `<span style="color:red;">No file uploaded</span>`
                    );
                }
            },
            error: function() {
                $("#idLoadingSpinner").hide();
                alert("Error connecting to server.");
            }
        });
    });

    function saveDataPersonalID() {
        var formData = new FormData();

        var idPerson = $("#txtIdPerson").val();
        var txtIssueAtPlace = $("#txtIssueAtPlace").val();
        var slcCountryIssuePI = $("#slcCountryIssuePI").val();
        var txtDate_issuePI = $("#txtDate_issuePI").val();
        var txtDate_validUntiPI = $("#txtDate_validUntiPI").val();
        var txtTypeDocPI = $("#txtTypeDocPI").val();
        var txtNoDocPI = $("#txtNoDocPI").val();

        formData.append("idPerson", idPerson);
        formData.append("txtIssueAtPlace", txtIssueAtPlace);
        formData.append("slcCountryIssuePI", slcCountryIssuePI);
        formData.append("txtDate_issuePI", txtDate_issuePI);
        formData.append("txtDate_validUntiPI", txtDate_validUntiPI);
        formData.append("txtTypeDocPI", txtTypeDocPI);
        formData.append("txtNoDocPI", txtNoDocPI);

        let fileObj = $("#uploadFile").prop("files")[0];

        if (fileObj) {
            formData.append("cekFileUpload", "yes");
            formData.append("fileUpload", fileObj);
        } else {
            formData.append("cekFileUpload", "no");
        }

        $("#idLoadingSpinner").show();

        $.ajax("<?php echo base_url('crew/saveDataPersonalId'); ?>", {
            method: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                alert(response);
                window.reload();
            }
        });
    }




    // function loadCertificatePreview(res) {
    //     let file = res.certificate_file;
    //     let previewContainer = $("#idCertificatePreviewContainer");

    //     if (!previewContainer.length) {
    //         console.error("Preview container #idCertificatePreviewContainer tidak ditemukan!");
    //         return;
    //     }

    //     previewContainer.html("");

    //     if (!file) {
    //         previewContainer.html(
    //             "<div style='margin-top:10px;color:#888;font-size:12px;'>No file uploaded</div>"
    //         );
    //         return;
    //     }

    //     let fileUrl = "<?php echo base_url('uploadCertificate/'); ?> " + file;

    //     let ext = file.split('.').pop().toLowerCase();

    //     if (ext === "pdf") {
    //         previewContainer.html(`
    //         <iframe 
    //             src="${fileUrl}" 
    //             style="width:100%;height:350px;border:1px solid #ddd;border-radius:8px;">
    //         </iframe>
    //         <div style="margin-top:10px;">
    //             <a href="${fileUrl}" target="_blank" 
    //             style="color:#007bff;font-weight:600;">Open Full View</a>
    //         </div>
    //     `);
    //         return;
    //     }

    //     if (["jpg", "jpeg", "png", "gif"].includes(ext)) {
    //         previewContainer.html(`
    //         <img src="${fileUrl}" 
    //              style="max-width:100%;max-height:350px;border:1px solid #ddd;border-radius:8px;" />
    //         <div style="margin-top:10px;">
    //             <a href="${fileUrl}" target="_blank" 
    //             style="color:#007bff;font-weight:600;">Open Full View</a>
    //         </div>
    //     `);
    //         return;
    //     }

    //     previewContainer.html(`
    //         <div style='color:#cc0000;font-size:13px;margin-top:10px;'>
    //             Cannot preview this file type.<br>
    //             <a href="${fileUrl}" target="_blank">Download file</a>
    //         </div>
    //     `);
    // }


    function checkPersonalDataStatus() {
        let idperson = $("#txtIdPersonCrew").val();

        if (!idperson) return;

        $.ajax({
            url: "<?php echo base_url('crew/checkPersonalData'); ?> ",
            method: "POST",
            data: {
                idperson: idperson
            },
            dataType: "json",
            success: function(res) {
                if (res.exists) {
                    $("#navPersonal")
                        .prop("disabled", true)
                        .css({
                            background: "linear-gradient(90deg,#b3b3b3,#d1d1d1)",
                            color: "#eee",
                            cursor: "not-allowed",
                            boxShadow: "none",
                            transform: "none"
                        })
                        .attr("title", "Data Personal sudah tersimpan");

                } else {
                    $("#navPersonal")
                        .prop("disabled", false)
                        .css({
                            background: "linear-gradient(90deg,#0066d6,#36b7ff)",
                            color: "#fff",
                            cursor: "pointer",
                            boxShadow: "0 6px 16px rgba(8,61,119,0.12)"
                        })
                        .attr("title", "Isi Data Personal");
                }
            },
            error: function(xhr, status, error) {
                console.error("Gagal cek data personal:", error);
            }
        });
    }

    $(document).ready(function() {
        const $toggleNav = $('#toggleNav');
        const $navMenu = $('#navMenu');

        function handleResize() {
            if ($(window).width() <= 768) {
                $toggleNav.css('display', 'flex');
                $navMenu.css({
                    'flex-direction': 'column',
                    'align-items': 'flex-start',
                    'background-color': '#056b6b',
                    'width': '100%',
                    'padding': '10px',
                    'border-radius': '6px',
                    'position': 'absolute',
                    'top': '60px',
                    'left': '0',
                    'max-height': '0',
                    'opacity': '0',
                    'overflow': 'hidden',
                    'transition': 'all 0.3s ease'
                });
            } else {
                $toggleNav.hide();
                $navMenu.css({
                    'display': 'flex',
                    'flex-direction': 'row',
                    'align-items': 'center',
                    'position': 'relative',
                    'max-height': 'none',
                    'opacity': '1',
                    'background-color': 'transparent',
                    'overflow': 'visible'
                });
            }
        }
        $toggleNav.on('click', function() {
            if ($navMenu.css('max-height') === '0px' || $navMenu.css(
                    'max-height') === 'none') {
                $navMenu.css({
                    'max-height': '200px',
                    'opacity': '1'
                });
            } else {
                $navMenu.css({
                    'max-height': '0',
                    'opacity': '0'
                });
            }
        });
        $(window).on('resize', handleResize);
        handleResize();
    });

    function loadCrewData(idperson) {
        $.ajax({
            url: "<?php echo base_url('crew/getCrewDataWithCertificate'); ?>",
            type: "GET",
            data: idperson ? {
                idperson: idperson
            } : {},
            dataType: "json",
            success: function(response) {
                if (response.error) {
                    console.warn(response.error);
                    return;
                }

                let p = response.personal;
                if (p) {
                    let photo = "";
                    if (p.pic && p.pic.trim() !== "") {
                        let picUrl = "<?php echo base_url('imgProfile/'); ?>" + p
                            .pic;
                        photo = `
                        <tr>
                            <td colspan="2" style="text-align:center;padding:10px;">
                                <img src="${picUrl}" style="width:120px;height:120px;border-radius:50%;object-fit:cover;
                                            border:3px solid #e3e8ee;box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                            </td>
                        </tr>`;
                    } else {
                        photo = `
                        <tr>
                            <td colspan="2" style="text-align:center;padding:10px;">
                                <div style="width:120px;height:120px;border-radius:50%;background:#f3f4f6;
                                            display:flex;align-items:center;justify-content:center;
                                            color:#aaa;font-size:40px;margin:0 auto;">
                                    👤
                                </div>
                            </td>
                        </tr>`;
                    }

                    let htmlBio = `
                        ${photo}
                        <tr><td style="padding:8px;">Nama Lengkap</td><td style="padding:8px;">${p.fullName || "-"}</td></tr>
                        <tr><td style="padding:8px;">Tempat / Tanggal Lahir</td><td style="padding:8px;">${p.pob || "-"}, ${p.dob || "-"}</td></tr>
                        <tr><td style="padding:8px;">Jenis Kelamin</td><td style="padding:8px;">${p.gender || "-"}</td></tr>
                        <tr><td style="padding:8px;">Agama</td><td style="padding:8px;">${p.religion || "-"}</td></tr>
                        <tr><td style="padding:8px;">Email</td><td style="padding:8px;">${p.email || "-"}</td></tr>
                        <tr><td style="padding:8px;">No HP</td><td style="padding:8px;">${p.mobileno || "-"}</td></tr>
                        <tr><td style="padding:8px;">Jabatan</td><td style="padding:8px;">${p.applyfor || "-"}</td></tr>
                        <tr><td style="padding:8px;">Tipe Kapal</td><td style="padding:8px;">${p.crew_vessel_type || "-"}</td></tr>
                    `;

                    $("#tblBioCrew").html(htmlBio);
                }

                let certs = response.certificates;
                let htmlCert = "";
                if (certs && certs.length > 0) {
                    certs.forEach(certificate => {
                        let fileLink = "-";

                        if (certificate.certificate_file) {
                            let certUrl = "<?php echo base_url(); ?>" + certificate
                                .certificate_file;
                            fileLink = `
                                <a href="${certUrl}" 
                                target="_blank" 
                                style="text-decoration:none;color:#007bff;">
                                    📂 Lihat File
                                </a>`;
                        } else {
                            fileLink =
                                `<span style="color:#999;">${certificate.certificate_status}</span>`;
                        }

                        htmlCert += `
                        <tr>
                            <td style="padding:8px;border-bottom:1px solid #eee;">${certificate.certname || "-"}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">${certificate.docno || "-"}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">${certificate.expdate || "-"}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">${certificate.nmnegara || "-"}</td>
                            <td style="padding:8px;border-bottom:1px solid #eee;">
                                ${fileLink}
                                <a href="#" class="linkDeleteCert"
                                data-idcert="${certificate.idcertdoc}"
                                style="margin-left:8px;color:#dc3545;cursor:pointer;text-decoration:none;">
                                    ❌ Delete
                                </a>
                            </td>
                        </tr>`;
                    });

                } else {
                    htmlCert = `
                    <tr><td colspan="5" style="text-align:center;padding:12px;">
                        Belum ada sertifikat di-upload
                    </td></tr>`;
                }

                $("#tblCertificateList").html(htmlCert);
            },
            error: function() {
                console.error("Gagal mengambil data crew");
            }
        });
    }
    $(document).on("click", ".linkDeleteCert", function(e) {

        let idcert = $(this).data("idcert");

        if (!confirm("Yakin ingin menghapus sertifikat ini?")) return;

        $.ajax({
            url: "<?php echo base_url('crew/deleteCertificate'); ?>",
            type: "POST",
            dataType: "json",
            data: {
                idcert: idcert
            },
            success: function(res) {

                if (res.status === "success") {
                    alert("Sertifikat berhasil dihapus!");
                    loadCrewData($("#txtIdPersonCrew").val());
                } else {
                    alert("Gagal menghapus: " + (res.message || "Unknown error"));
                }
            },
            error: function(xhr) {
                alert("Terjadi error dalam menghapus sertifikat");
                console.error(xhr.responseText);
            }
        });
    });

    $('#txtTypeDocPI').on('change', function() {
        let jenis = $(this).val();

        const mapPlaceholder = {
            "KTP": "Nomor KTP",
            "Kartu Keluarga": "Nomor KK",
            "NPWP": "Nomor NPWP",
            "Buku Rekening": "Nomor Rekening",
            "Passport": "Passport Number",
            "Seaman Book": "Seaman Book Number"
        };

        let text = mapPlaceholder[jenis] || "No Document";
        $('#txtNoDocPI').attr('placeholder', text);
        $('#labelNoDoc').text(text);
    });
    $('#btnClearFile').on('click', function() {
        $('#uploadFile').val('');
    });
    </script>

</head>

<body
    style="background-color: <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? '#ffffff' : '#d1e9ef'; ?>; font-family: Calibri, Candara, Segoe, Segoe UI,Optima, Arial, sans-serif;">
    <div class="clearfix">
        <section class="header" style="padding-top:10px;padding-bottom:5px;">
            <div class="container">
                <div class="header-left"> <a class="navbar-brand" href="" style="margin: 0px;"> <img
                            src="<?php echo base_url(); ?>assets/img/andhika.gif" alt="logo" style="width:50px;"> </a>
                </div> <label style="padding:5px;font-size:30px;color:#000080; font-family: calibri;"> ANDHIKA GROUP
                </label>
            </div>
        </section>
    </div>
    <section id="header-menu" style="width:100%;background-color:#067780;">
        <div class="container" style="max-width:1200px;margin:0 auto;padding:10px 20px;">
            <nav class="navbar"
                style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;position:relative;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <label style="color:#fff;font-size:20px;font-family:Calibri;font-weight:bold;margin:0;">
                        Crew Personal Information
                    </label>
                </div>
                <button id="toggleNav"
                    style="background:none;border:none;cursor:pointer;display:none;flex-direction:column;gap:5px;">
                    <span style="width:25px;height:2px;background:#fff;"></span>
                    <span style="width:25px;height:2px;background:#fff;"></span>
                    <span style="width:25px;height:2px;background:#fff;"></span>
                </button>
                <div id="navMenu" style="display:flex;align-items:center;gap:20px;transition:max-height 0.3s ease, opacity 0.3s ease;
                       overflow:hidden;">
                    <div style="color:#e0f7fa;font-family:Calibri;font-size:16px;">
                        <?php 
                        $fullname = $this->session->userdata('fullnameUserCrewLoginSystem');
                        if($fullname){
                            echo '<i class="fa fa-user" style="margin-right:6px;"></i> <b>'.$fullname.'</b>';
                        } else {
                            echo '<i class="fa fa-user"></i> Guest';
                        }
                    ?>
                    </div>
                    <a href="<?php echo base_url('crew/logOut'); ?>" style="color:#fff;background-color:#045f60;border-radius:6px;
                          padding:8px 15px;text-decoration:none;font-family:Calibri;
                          font-size:15px;transition:background 0.3s;">
                        <i class="fa fa-sign-out" style="margin-right:6px;"></i> Logout
                    </a>
                </div>
            </nav>
        </div>
    </section>

    <div
        style="font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial;max-width:1100px;margin:36px auto;padding:20px;color:#24313a;">

        <div style="display:flex;justify-content:center;gap:14px;flex-wrap:wrap;margin-bottom:34px;">
            <button id="navPersonal" onclick="switchSection('sectionPersonal')"
                style="padding:12px 26px;border:none;border-radius:12px;background:linear-gradient(90deg,#0066d6,#36b7ff);color:#fff;font-weight:700;cursor:pointer;box-shadow:0 6px 16px rgba(8,61,119,0.12);transition:all .22s;">
                🧭 Data Personal
            </button>

            <button id="navCertificate" onclick="switchSection('sectionCertificate')"
                style="padding:12px 26px;border:none;border-radius:12px;background:linear-gradient(90deg,#00c6ff,#6c63ff);color:#fff;font-weight:700;cursor:pointer;box-shadow:0 6px 16px rgba(49,42,133,0.12);transition:all .22s;">
                📄 Upload Certificate
            </button>

            <button id="navPersonalID" onclick="switchSection('sectionPersonalID')"
                style="padding:12px 26px;border:none;border-radius:12px;background:linear-gradient(90deg,#5a4bff,#00d4ff);color:#fff;font-weight:700;cursor:pointer;box-shadow:0 6px 16px rgba(42,83,255,0.12);transition:all .22s;">
                👤 Personal ID
            </button>

            <button id="navView" onclick="switchSection('sectionViewData')"
                style="padding:12px 26px;border:none;border-radius:12px;background:linear-gradient(90deg,#5a4bff,#00d4ff);color:#fff;font-weight:700;cursor:pointer;box-shadow:0 6px 16px rgba(42,83,255,0.12);transition:all .22s;">
                👤 Data Summary
            </button>
        </div>
        <div id="sectionEmpty" style="text-align:center;color:#8a98a6;padding:80px 10px;border-radius:12px;">
            <p style="font-size:16px;font-style:italic;margin:0;">Silakan pilih menu di atas untuk melanjutkan ⬆️</p>
        </div>

        <div id="sectionPersonal" style="display:none;opacity:0;transform:translateY(10px);transition:all .36s ease;">
            <div style="background:#fff;border-radius:14px;box-shadow:0 8px 30px rgba(8,34,67,0.06);overflow:hidden;">
                <div
                    style="background:linear-gradient(135deg,#007bff,#6c63ff);color:#fff;padding:22px 18px;text-align:center;">
                    <h3 style="margin:0;font-weight:700;font-size:18px;letter-spacing:0.2px;">🧭 Data Personal Crew</h3>
                </div>

                <div class="row" style="padding:26px 28px;">
                    <div
                        style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;align-items:start;">

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Nama
                                Lengkap</label>
                            <input id="txtFnameCrew" type="text"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;box-sizing:border-box;">
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Tempat
                                Lahir</label>
                            <select id="slcPobCrew"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                                <?php echo $optCity; ?>
                            </select>
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Tanggal
                                Lahir</label>
                            <input id="txtDobCrew" type="date"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Jenis
                                Kelamin</label>
                            <select id="slcGenderCrew"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                                <option value="">Pilih</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Kode
                                Pelaut / Seafarer Code</label>
                            <input id="txtKodePelautCrew" type="text"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;box-sizing:border-box;">
                        </div>

                        <div style="grid-column:1 / -1;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Alamat
                                Lengkap</label>
                            <textarea id="txtAddressCrew" rows="2"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;resize:none;"></textarea>
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">No
                                KTP</label>
                            <input id="txtSsnCrew" type="text"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">No
                                NPWP</label>
                            <input id="txtPtnCrew" type="text"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">No
                                HP</label>
                            <input id="txtMobileNoCrew" type="text"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Telpon
                                Rumah</label>
                            <input id="txtTelpNoCrew" type="text"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Kontak
                                Darurat</label>
                            <input id="txtNextOfKinCrew" type="text"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Nama
                                Kontak Darurat</label>
                            <input id="txtNextOfKinNameCrew" type="text"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Email</label>
                            <input id="txtEmailCrew" type="email"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div
                            style="grid-column:1 / -1;display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px;margin-top:10px;">
                            <div style="background:#f9fbfd;border:1px solid #e3e8ee;border-radius:12px;padding:18px;">
                                <legend style="font-size:15px;font-weight:700;color:#2b3c4d;margin-bottom:10px;">Home
                                    Salary</legend>
                                <label
                                    style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">No
                                    Rekening</label>
                                <input id="txtNorekHomeCrew" type="text"
                                    style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;margin-bottom:10px;">
                                <label
                                    style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Nama
                                    Bank</label>
                                <input id="txtNamaBankHomeCrew" type="text"
                                    style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;margin-bottom:10px;">
                                <label
                                    style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Pemilik
                                    Rekening</label>
                                <input id="txtPemilikHomeCrew" type="text"
                                    style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                            </div>

                            <div style="background:#f9fbfd;border:1px solid #e3e8ee;border-radius:12px;padding:18px;">
                                <legend style="font-size:15px;font-weight:700;color:#2b3c4d;margin-bottom:10px;">Board
                                    Salary</legend>
                                <label
                                    style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">No
                                    Rekening</label>
                                <input id="txtNorekBoardCrew" type="text"
                                    style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;margin-bottom:10px;">
                                <label
                                    style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Nama
                                    Bank</label>
                                <input id="txtNamaBankBoardCrew" type="text"
                                    style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;margin-bottom:10px;">
                                <label
                                    style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Pemilik
                                    Rekening</label>
                                <input id="txtPemilikBoardCrew" type="text"
                                    style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                            </div>
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Jabatan
                                / Rank</label>
                            <select id="slcApplyForCrew"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                                <?php echo $optRank; ?>
                            </select>
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Type
                                Kapal</label>
                            <select id="slcVesselTypeCrew"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                                <?php echo $getVesselType; ?>
                            </select>
                        </div>

                        <div>
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Agama</label>
                            <select id="slcReligionCrew"
                                style="width:100%;padding:10px 12px;border-radius:10px;border:1px solid #e3e8ee;">
                                <?php echo $optReligion; ?>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">
                            Foto Crew
                        </label>
                        <input id="filePicCrew" type="file" accept="image/*"
                            style="width:100%;padding:10px;border:1px solid #e3e8ee;border-radius:10px;">
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:28px;">
                        <input type="hidden" id="txtIdPersonCrew" value="">
                        <button onclick="saveDataPersonalCrew();"
                            style="padding:12px 22px;border-radius:10px;border:none;background:linear-gradient(90deg,#0066d6,#36b7ff);color:#fff;font-weight:700;cursor:pointer;box-shadow:0 8px 20px rgba(3,96,197,0.18);">💾
                            Simpan Data</button>
                        <button onclick="reloadPage();"
                            style="padding:12px 22px;border-radius:10px;border:1px solid #e6e9ee;background:#f8fafc;color:#28343b;font-weight:700;cursor:pointer;">❌
                            Batal</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="sectionCertificate"
            style="display:none;opacity:0;transform:translateY(10px);transition:all .36s ease;margin-top:18px;">
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
            <div style="background:#fff;border-radius:14px;box-shadow:0 8px 30px rgba(8,34,67,0.04);overflow:hidden;">
                <div
                    style="background:linear-gradient(135deg,#00c6ff,#6c63ff);color:#fff;padding:20px 18px;text-align:center;">
                    <h3 style="margin:0;font-weight:700;font-size:18px;">📄 Upload Certificate / Document</h3>
                </div>

                <div style="padding:24px;">
                    <div class="col-md-4 col-xs-12">
                        <input type="checkbox" id="chkDisplayAllCert" value="Y" checked="checked">
                        <label for="chkDisplayAllCert" style="font-size:12px;">Display</label>
                    </div>
                    <div style="text-align:center;margin-bottom:16px;color:#55686f;">
                        <input type="checkbox" id="chkUseThisAllCert" value="Y" checked
                            style="transform:translateY(2px);">
                        <label for="chkUseThisAllCert" style="margin-left:8px;font-weight:600;">Use this for All
                            Certificates</label>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:12px;">
                        <div style="flex:1;min-width:240px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Certificate
                                Name</label>
                            <select id="slcMstCertAllCert"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                            </select>

                        </div>

                        <div style="flex:1;min-width:240px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Upload
                                File <span style="color:#d9534f;font-size:12px;">(Max 2MB)</span></label>
                            <input id="uploadFile" type="file" class="form-control"
                                style="width:100%;padding:8px;border-radius:10px;border:1px solid #e3e8ee;">
                            <div id="previewCertificateFile" style="margin-top:10px;"></div>
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:12px;">
                        <div style="flex:1;min-width:200px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">License</label>
                            <select id="slcLicenseAllCert"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                                <option value="-">-</option>
                                <option value="COC">COC</option>
                                <option value="Endorsement">Endorsement</option>
                            </select>
                        </div>

                        <div style="flex:1;min-width:200px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Level</label>
                            <select id="slcLevelAllCert"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                                <option value="-">-</option>
                                <option value="Incharge">Incharge</option>
                                <option value="Asst.">Asst.</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:12px;">
                        <div style="flex:1;min-width:220px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Rank</label>
                            <select id="slcRankAllCert"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                                <?php echo $optRank; ?>
                            </select>
                        </div>

                        <div style="flex:1;min-width:220px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Vessel
                                Type</label>
                            <select id="slcVesselTypeAllCert"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                                <?php echo $optType; ?>
                            </select>
                        </div>

                        <div style="flex:1;min-width:220px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Country
                                of Issue</label>
                            <select id="slcCountryIssueAllCert"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                                <?php echo $optCountry; ?>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:12px;">
                        <div style="flex:1;min-width:200px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">No
                                Document</label>
                            <input id="txtNoDocumentAllCert" type="text"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div style="flex:1;min-width:200px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Date
                                of Issue</label>
                            <input id="txtDate_ofIssueAllCert" type="date"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div style="flex:1;min-width:200px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Date
                                of Expiry</label>
                            <input id="txtDate_expiryAllCert" type="date"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:12px;">
                        <div style="flex:1;min-width:220px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Place
                                of Issue</label>
                            <input id="txtPlaceofIssueAllCert" type="text"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div style="flex:1;min-width:220px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Issuing
                                Authority</label>
                            <input id="txtIssuingAuthorityAllCert" type="text"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                        </div>

                        <div style="flex:1;min-width:220px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Remark</label>
                            <textarea id="txtRemarkAllCert" rows="2"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;resize:none;"></textarea>
                        </div>
                    </div>

                    <div style="display:flex;flex-wrap:wrap;gap:14px;margin-bottom:18px;">
                        <div style="flex:1;min-width:160px;">
                            <label
                                style="display:block;font-size:13px;font-weight:600;color:#213244;margin-bottom:6px;">Red
                                Sign</label>
                            <select id="slcRedSingAllCert"
                                style="width:100%;padding:10px;border-radius:10px;border:1px solid #e3e8ee;">
                                <option value="N">NO</option>
                                <option value="Y">YES</option>
                            </select>
                        </div>
                    </div>

                    <div style="display:flex;justify-content:flex-end;gap:10px;">
                        <input type="hidden" id="txtIdPersonAllCertificate" value="">
                        <input type="hidden" id="txtIdEditAllCertificate" value="">
                        <button onclick="saveData();"
                            style="padding:11px 22px;border-radius:10px;border:none;background:linear-gradient(90deg,#0066d6,#36b7ff);color:#fff;font-weight:700;cursor:pointer;box-shadow:0 8px 20px rgba(3,96,197,0.12);">
                            💾 Submit
                        </button>
                        <button
                            style="padding:11px 22px;border-radius:10px;border:1px solid #e6e9ee;background:#f8fafc;color:#28343b;font-weight:700;cursor:pointer;">
                            ❌ Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="sectionViewData"
            style="display:none;opacity:0;transform:translateY(10px);transition:all .36s ease;margin-top:18px;">
            <div style="background:#fff;border-radius:14px;box-shadow:0 8px 30px rgba(8,34,67,0.04);overflow:hidden;">
                <div
                    style="background:linear-gradient(135deg,#6c63ff,#00c6ff);color:#fff;padding:20px 18px;text-align:center;">
                    <h3 style="margin:0;font-weight:700;font-size:18px;">👤 Data Diri & Sertifikat</h3>
                </div>

                <div style="padding:24px;">
                    <h4 style="margin:0 0 12px 0;color:#0066d6;font-weight:700;">📋 Biodata Crew</h4>
                    <table style="width:100%;border-collapse:collapse;margin-bottom:18px;font-size:14px;color:#2b3a41;">
                        <tbody id="tblBioCrew">

                        </tbody>
                    </table>

                    <h4 style="margin:8px 0 12px 0;color:#0066d6;font-weight:700;">📜 Sertifikat Tersimpan</h4>
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;font-size:14px;">
                            <thead>
                                <tr style="background:#007bff;color:#fff;">
                                    <th style="padding:10px 12px;text-align:left;">Nama Sertifikat</th>
                                    <th style="padding:10px 12px;text-align:left;">No Dokumen</th>
                                    <th style="padding:10px 12px;text-align:left;">Tanggal Expired</th>
                                    <th style="padding:10px 12px;text-align:left;">Negara</th>
                                    <th style="padding:10px 12px;text-align:left;">File</th>
                                </tr>
                            </thead>
                            <tbody id="tblCertificateList">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="sectionPersonalID"
            style="display:none; opacity:0; transform:translateY(10px); transition:all .36s ease; margin-top:18px;">

            <div
                style="background:#fff; border-radius:14px; box-shadow:0 8px 30px rgba(8,34,67,0.04); overflow:hidden;">
                <div
                    style="background:linear-gradient(135deg,#5a4bff,#00d4ff); color:#fff; padding:20px 18px; text-align:center;">
                    <h3 style="margin:0; font-weight:700; font-size:18px;">👤 Personal ID Document</h3>
                </div>

                <div style="padding:26px 30px;">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="fw-semibold mb-1" style="font-size:13px; color:#213244;">Issue at
                                (Place)</label>
                            <input id="txtIssueAtPlace" type="text" class="form-control" placeholder="Issue at Place"
                                style="border-radius:10px; border:1px solid #e3e8ee;">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold mb-1" style="font-size:13px; color:#213244;">Country of
                                Issue</label>
                            <select id="slcCountryIssuePI" class="form-control"
                                style="border-radius:10px; border:1px solid #e3e8ee;">
                                <?php echo $optCountry; ?>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="fw-semibold mb-1" style="font-size:13px; color:#213244;">Date of Issue</label>
                            <input id="txtDate_issuePI" type="date" class="form-control"
                                style="border-radius:10px; border:1px solid #e3e8ee;">
                        </div>

                        <div class="col-md-3">
                            <label class="fw-semibold mb-1" style="font-size:13px; color:#213244;">Valid Until</label>
                            <input id="txtDate_validUntiPI" type="date" class="form-control"
                                style="border-radius:10px; border:1px solid #e3e8ee;">
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-3">
                            <label class="fw-semibold mb-1" style="font-size:13px; color:#213244;">Type of
                                Document</label>
                            <select id="txtTypeDocPI" class="form-control"
                                style="border-radius:10px; border:1px solid #e3e8ee;">
                                <option value="">-- Select Document --</option>
                                <option>KTP</option>
                                <option>Kartu Keluarga</option>
                                <option>NPWP</option>
                                <option>Buku Rekening</option>
                                <option>Passport</option>
                                <option>Seaman Book</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label id="labelNoDoc" class="fw-semibold mb-1" style="font-size:13px; color:#213244;">No
                                Document</label>
                            <input id="txtNoDocPI" type="text" class="form-control" placeholder="Document Number"
                                style="border-radius:10px; border:1px solid #e3e8ee;">
                        </div>
                        <div class="col-md-3">
                            <label class="fw-semibold mb-1" style="font-size:13px; color:#213244;">File</label>
                            <input id="uploadFile" type="file" class="form-control"
                                style="border-radius:10px; border:1px solid #e3e8ee;">
                            <div id="idViewFile" class="mt-2"></div>
                        </div>
                        <div class="col-md-3">
                            <label class="mb-1">&nbsp;</label>
                            <button onclick="document.getElementById('uploadFile').value='';"
                                class="btn btn-warning w-100 fw-bold" style="border-radius:10px;">
                                Clear
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-10">
                        <input type="hidden" id="txtIdPersonCrew" value="">
                        <button onclick="saveDataPersonalID();" class="fw-bold" style="padding:12px 24px; border-radius:10px; border:none;
                               background:linear-gradient(90deg,#0066d6,#36b7ff);
                               color:#fff; box-shadow:0 8px 20px rgba(3,96,197,0.18);">
                            💾 Submit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.hc-sticky.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>

</body>

</html>