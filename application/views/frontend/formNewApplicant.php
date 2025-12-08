<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/icon" href="<?php echo base_url(); ?>image/AndhikaTransparentBkGndBlue.png" />
    <title>FORM CREW RECRUITMENT</title>
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icon-font.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/animate.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/hover-min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery-ui.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script>
    $(document).ready(function() {
        $('#otherKapalCheckbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('#inputOtherKapal').show();
            } else {
                $('#inputOtherKapal').hide();
            }
        });

        $('#otherCrewCheckbox').on('change', function() {
            if ($(this).is(':checked')) {
                $('#inputOtherCrew').show();
            } else {
                $('#inputOtherCrew').hide();
            }
        });
    });

    $(document).ready(function() {
        $("input[name='crew_foreign']").on('change', function() {
            if ($(this).val() === 'Y') {
                $("#foreignCountryInput").show();
            } else {
                $("#foreignCountryInput").hide();
                $("input[name='foreign_country']").val('');
            }
        });
        $("#alasanGabung").on("input", function() {
            const words = $(this).val().trim().split(/\s+/).filter(w => w.length > 0);
            $("#wordCountHelp").text(`${words.length} / 150 kata`);
        });
    });

    function saveNewApplicant() {
        const formData = new FormData();

        const email = $("input[name='txtemail']").val();
        const nama = $("input[name='txtnama']").val();
        const tempatLahir = $("input[name='txttempat_lahir']").val();
        const handphone = $("input[name='txthandphone']").val();
        const posisi = $("select[name='position_applied']").val();
        const ijazah = $("select[name='ijazah_terakhir']").val();
        const pengalaman = $("select[name='pengalaman_terakhir']").val();
        const ipk = $("#ipk_terakhir").val();
        const gaji = $("input[name='last_salary']").val();
        const infoSource = $("input[name='info_source']:checked").val();
        const tanggalLahir = $("input[name='txttanggal_lahir']").val();
        const gender = $("select[name='gender']").val();
        const applicantId = $("input[name='txtIdNewApplicant']").val();
        const joinDate = $("input[name='join_date']").val();

        formData.append("txtIdNewApplicant", applicantId);

        if (!gender) return showError("Silakan pilih jenis kelamin.");
        formData.append("gender", gender);

        if (!nama || !tempatLahir || !handphone || !posisi || !ijazah || !tanggalLahir)
            return showError("Silakan lengkapi semua field wajib terlebih dahulu.");

        if (!infoSource)
            return showError("Silakan pilih sumber informasi");

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email))
            return showError("Format email tidak valid");

        const birthDate = new Date(tanggalLahir);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) age--;
        if (age < 18 || age > 55)
            return showError("Usia pelamar harus antara 18 hingga 55 tahun.");

        if (posisi.toLowerCase().includes("cadet")) {
            if (!ipk) return showError("Silakan isi IPK terakhir untuk posisi Cadet.");
            const sekolah = $("#sekolah").val().trim();
            const jurusan = $("#jurusan").val().trim();

            if (!sekolah) return showError("Silakan isi nama sekolah untuk posisi Cadet.");
            if (!jurusan) return showError("Silakan isi jurusan untuk posisi Cadet.");

            formData.append("ipk_terakhir", ipk);
            formData.append("sekolah", sekolah);
            formData.append("jurusan", jurusan);

            formData.append("pengalaman_terakhir", "");
            formData.append("crew_foreign", "N");
            formData.append("last_salary", "");
        } else {
            if (!pengalaman) return showError("Silakan isi pengalaman terakhir untuk posisi non-Cadet.");
            if (!gaji) return showError("Silakan isi gaji terakhir untuk posisi non-Cadet.");

            formData.append("pengalaman_terakhir", pengalaman);
            formData.append("ipk_terakhir", "");
            formData.append("last_salary", gaji);

            const crewForeign = $("input[name='crew_foreign']:checked").val();
            if (!crewForeign) return showError("Silakan pilih apakah pernah berlayar dengan crew asing.");
            formData.append("crew_foreign", crewForeign);

            if (crewForeign === 'Y') {
                const foreignCountry = $("input[name='foreign_country']").val().trim();
                if (!foreignCountry) return showError("Silakan isi negara tempat Anda berlayar dengan crew asing.");
                formData.append("foreign_country", foreignCountry);
            }
        }

        formData.append("txtemail", email);
        formData.append("txtnama", nama);
        formData.append("txttempat_lahir", tempatLahir);
        formData.append("txttanggal_lahir", tanggalLahir);
        formData.append("txthandphone", handphone);
        formData.append("position_applied", posisi);
        formData.append("ijazah_terakhir", ijazah);
        formData.append("pernah_join", $("input[name='pernah_join']:checked").val() || 'N');
        formData.append("info_source", infoSource);
        formData.append("join_date", joinDate);

        $("input[name='kapal[]']:checked").each(function() {
            formData.append("kapal[]", $(this).val());
        });

        const otherKapalCheckbox = $('#otherKapalCheckbox');
        const otherKapalInput = $('input[name="kapal_other"]');
        if (otherKapalCheckbox.is(':checked') && otherKapalInput.val()) {
            formData.append("kapal[]", "OTHER: " + otherKapalInput.val());
        }

        const cvFiles = $("input[name='cv_files[]']")[0].files;
        if (cvFiles.length === 0)
            return showError("Silakan unggah CV");

        for (let i = 0; i < cvFiles.length; i++) {
            const file = cvFiles[i];
            const sizeInMB = file.size / (1024 * 1024);
            if (sizeInMB > 5)
                return showError(`Ukuran file "${file.name}" melebihi 5MB. Silakan unggah file yang lebih kecil.`);
            formData.append("cv_files[]", file);
        }

        const overlay = document.getElementById("loadingOverlay");
        overlay.style.display = "flex";
        setTimeout(() => overlay.style.opacity = "1", 100);

        $.ajax({
            url: '<?php echo base_url('extendCrewEvaluation/saveNewApplicant') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                overlay.style.opacity = "0";
                setTimeout(() => overlay.style.display = "none", 300);

                const res = typeof response === 'string' ? JSON.parse(response) : response;
                if (res.status === 'success') {
                    alert('Formulir berhasil dikirim. Terima kasih!');
                    window.location.reload();
                } else {
                    showError(res.message || 'Terjadi kesalahan tidak dikenal');
                }
            },
            error: function(xhr, status, error) {
                overlay.style.opacity = "0";
                setTimeout(() => overlay.style.display = "none", 300);
                showError("Terjadi kesalahan koneksi: " + error);
            }
        });
    }



    // $(document).ready(function() {
    //     $("input[name='txtemail']").on('blur', function() {
    //         const email = $(this).val().trim();

    //         const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    //         if (!emailRegex.test(email)) {
    //             showError("Format email tidak valid");
    //             return;
    //         }

    //         $("#idLoading").show();

    //         $.ajax({
    //             url: '<?php echo base_url("extendCrewEvaluation/checkEmail"); ?>',
    //             type: 'POST',
    //             data: {
    //                 email: email
    //             },
    //             dataType: 'json',
    //             success: function(response) {
    //                 console.log("Response:",
    //                     response);
    //                 if (response.status === 'exists') {
    //                     fillFormWithExistingData(response.data);
    //                     alert(
    //                         "Data dengan email ini sudah ada. Data lama ditampilkan untuk diperbaiki."
    //                     );
    //                 } else if (response.status === 'not_found') {
    //                     resetForm();
    //                 }
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error("AJAX Error:", xhr.responseText, status, error);
    //                 showError("Gagal terhubung ke server.");
    //             },
    //             complete: function() {
    //                 $("#idLoading").hide();
    //             }
    //         });
    //     });
    // });

    // function resetForm() {
    //     $("input[name='txtnama']").val('');
    //     $("input[name='txttempat_lahir']").val('');
    //     $("input[name='txttanggal_lahir']").val('');
    //     $("input[name='txthandphone']").val('');
    //     $("select[name='position_applied']").val('');
    //     $("select[name='ijazah_terakhir']").val('');
    //     $("select[name='pengalaman_terakhir']").val('');
    //     $("input[name='last_salary']").val('');
    //     $("select[name='gender']").val('');

    //     $("input[name='pernah_join']").prop('checked', false);
    //     $("input[name='info_source']").prop('checked', false);
    //     $("input[name='kapal[]']").prop('checked', false);
    //     $("input[name='crew_foreign']").prop('checked', false);

    //     $('#otherKapalCheckbox').prop('checked', false);
    //     $('input[name="kapal_other"]').val('');
    //     $('#inputOtherKapal').hide();

    //     $('input[name="foreign_country"]').val('');
    //     $('#foreignCountryInput').hide();
    // }

    // function fillFormWithExistingData(data) {
    //     console.log("Mengisi data form, ID:", data.id);
    //     $("#txtIdNewApplicant").val(data.id);
    //     $("input[name='txtemail']").val(data.email);
    //     $("input[name='txtnama']").val(data.fullname);
    //     $("input[name='txttempat_lahir']").val(data.born_place);
    //     $("input[name='txttanggal_lahir']").val(data.born_date);
    //     $("input[name='txthandphone']").val(data.handphone);
    //     $("select[name='position_applied']").val(data.position_applied);
    //     $("select[name='ijazah_terakhir']").val(data.ijazah_terakhir);
    //     $("select[name='pengalaman_terakhir']").val(data.last_experience);
    //     $("input[name='last_salary']").val(data.last_salary);
    //     $("select[name='gender']").val(data.gender);
    //     //$("input[name='cv_files[]']").prop('required', !data.new_cv); 


    //     if (data.join_inAndhika === 'Y' || data.join_inAndhika === 'N') {
    //         $("input[name='pernah_join'][value='" + data.join_inAndhika + "']").prop('checked', true);
    //     }

    //     $(`input[name='info_source'][value='${data.info_source}']`).prop('checked', true);

    //     $("input[name='kapal[]']").prop('checked', false);
    //     if (data.kapalList && data.kapalList.length > 0) {
    //         data.kapalList.forEach(kapal => {
    //             if (kapal.startsWith('OTHER: ')) {
    //                 const otherValue = kapal.substring(7);
    //                 $('#otherKapalCheckbox').prop('checked', true);
    //                 $('input[name="kapal_other"]').val(otherValue);
    //                 $('#inputOtherKapal').show();
    //             } else {
    //                 const $checkbox = $(`input[name='kapal[]'][value='${kapal}']`);
    //                 if ($checkbox.length) {
    //                     $checkbox.prop('checked', true);
    //                 }
    //             }
    //         });
    //     }

    //     if (data.crew_foreign === 'Y') {
    //         $(`input[name='crew_foreign'][value='Y']`).prop('checked', true);
    //         $('input[name="foreign_country"]').val(data.foreign_country);
    //         $('#foreignCountryInput').show();
    //     } else {
    //         $(`input[name='crew_foreign'][value='N']`).prop('checked', true);
    //         $('#foreignCountryInput').hide();
    //     }

    //     if (data.new_cv) {
    //         const fileUrl = '<?php echo base_url("assets/uploads/CV_NewApplicant"); ?>' + '/' + data.new_cv;
    //         const fileLink = `<a href="${fileUrl}" target="_blank" class="btn btn-sm btn-primary mt-2">
    //                             <i class="fas fa-file-pdf"></i> Lihat CV Terakhir
    //                         </a>`;
    //         $('#cvPreviewContainer').html(fileLink);
    //     }

    // }

    function showError(message) {
        const errorDiv = document.getElementById('error-message');
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
        errorDiv.style.opacity = 1;

        setTimeout(() => {
            errorDiv.style.transition = 'opacity 0.5s ease';
            errorDiv.style.opacity = 0;
            setTimeout(() => errorDiv.style.display = 'none', 500);
        }, 5000);
    }

    function showRecruitment() {
        const welcome = document.getElementById('idWelcome');
        const btn = document.getElementById('btnNext');
        const form = document.getElementById('formRecruitment');

        welcome.style.opacity = '0';
        btn.style.display = 'none';

        setTimeout(() => {
            welcome.style.display = 'none';
            form.style.display = 'block';
            setTimeout(() => {
                form.style.opacity = '1';
            }, 50);
        }, 500);
    }

    function showHiddenQuali(id, type) {
        if (type === 'show') {
            $("#qualification_" + id).css("display", "");
            $("#showQuali_" + id).attr("onclick", "showHiddenQuali(" + id + ", 'hidden')");
            $("#showQuali_" + id).text("Sembunyikan Persyaratan");
        } else {
            $("#qualification_" + id).css("display", "none");
            $("#showQuali_" + id).attr("onclick", "showHiddenQuali(" + id + ", 'show')");
            $("#showQuali_" + id).text("Persyaratan");
        }

    }

    function toggleNextButton() {
        const chk = document.getElementById("chkUnderstand");
        const btn = document.getElementById("btnNext");
        if (chk.checked) {
            btn.disabled = false;
            btn.style.backgroundColor = "#1976d2";
            btn.style.cursor = "pointer";
            btn.style.transform = "scale(1.05)";
            btn.style.boxShadow = "0 6px 18px rgba(25,118,210,0.3)";
        } else {
            btn.disabled = true;
            btn.style.backgroundColor = "#aaa";
            btn.style.cursor = "not-allowed";
            btn.style.transform = "scale(1)";
            btn.style.boxShadow = "0 5px 15px rgba(0,0,0,0.15)";
        }
    }

    function showRecruitment() {
        document.getElementById('idWelcome').style.display = 'none';
        document.getElementById('formRecruitment').style.display = 'block';
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function goBackToWelcome() {
        document.getElementById('formRecruitment').style.display = 'none';
        document.getElementById('idWelcome').style.display = 'block';
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    document.addEventListener("DOMContentLoaded", function() {
        let posApplied = document.querySelector("select[name='position_applied']");
        let lastExpInput = document.getElementById("pengalaman_terakhir");

        function toggleLastExp() {
            if (posApplied.value.toLowerCase().includes("cadet")) {
                lastExpInput.setAttribute("disabled", "disabled");
                lastExpInput.removeAttribute("required");
                lastExpInput.value = "";
            } else {
                lastExpInput.removeAttribute("disabled");
                lastExpInput.setAttribute("required", "required");
            }
        }

        posApplied.addEventListener("change", toggleLastExp);
        toggleLastExp();
    });
    $(document).ready(function() {
        $("#alasanGabung").on("input", function() {
            let text = $(this).val().trim();
            let words = text.split(/\s+/).filter(w => w.length > 0);
            let count = words.length;

            $("#wordCountHelp")
                .text(count + " / 150 kata")
                .css("color", count > 150 ? "red" : "#666");
        });
    });
    $(document).ready(function() {
        let $posApplied = $("select[name='position_applied']");
        let $lastExpSelect = $("#pengalaman_terakhir");
        let $ipkInput = $("#ipk_terakhir");
        let $label = $("#labelLastExp");

        let $groupJenisKapal = $("#groupJenisKapal");
        let $groupCrewAsing = $("#groupCrewAsing");
        let $groupSalary = $("#groupSalary");
        let $groupJoin = $("#groupJoin");
        let $groupSekolahJurusan = $("#groupSekolahJurusan");

        function hideGroup($group) {
            if ($group.length) {
                $group.hide();
                $group.find("input, select, textarea").removeAttr("required");
            }
        }

        function showGroup($group) {
            if ($group.length) {
                $group.show();
                $group.find("input, select, textarea").attr("required", true);
            }
        }

        function toggleLastExp() {
            if ($posApplied.val().toLowerCase().includes("cadet")) {
                $lastExpSelect.addClass("d-none").removeAttr("required").val("");
                $ipkInput.removeClass("d-none").attr("required", true);
                $label.text("IPK Terakhir *");

                hideGroup($groupJenisKapal);
                hideGroup($groupCrewAsing);
                hideGroup($groupSalary);
                hideGroup($groupJoin);

                showGroup($groupSekolahJurusan);

            } else {
                $lastExpSelect.removeClass("d-none").attr("required", true);
                $ipkInput.addClass("d-none").removeAttr("required").val("");
                $label.text("Pengalaman / Jabatan Terakhir *");

                showGroup($groupJenisKapal);
                showGroup($groupCrewAsing);
                showGroup($groupSalary);
                showGroup($groupJoin);

                // Hide sekolah & jurusan
                hideGroup($groupSekolahJurusan);
            }
        }

        $posApplied.on("change", toggleLastExp);
        toggleLastExp();
    });
    </script>
</head>

<body style="background-color: #d1e9ef; font-family: Calibri, Candara, Segoe, 
    Segoe UI,Optima, Arial, sans-serif;">
    <div class="clearfix">
        <section class="header" style="padding-top:10px;padding-bottom:5px;">
            <div class="container">
                <div class="header-left">
                    <a class="navbar-brand" style="margin: 0px;">
                        <img src="<?php echo base_url(); ?>assets/img/andhika.gif" alt="logo" style="width:50px;">
                    </a>
                </div>
                <label style="padding:5px;font-size:30px;color:#000080;">ANDHIKA GROUP</label>
            </div>
        </section>
    </div>
    <section id="menu" style="background-color:#067780; min-height: 60px; width:100%;">
        <div class="container">
            <div class="menubar">
                <nav class="navbar navbar-default" style="margin-bottom:10px;">
                    <div class="navbar-header">
                        <a class="navbar-brand"
                            style="color:#FFFFFF;font-size:20px;font-weight:bold;padding:10px 0;font-family: serif;">
                            FORM RECRUITMENT CREW
                        </a>
                    </div>
                </nav>
            </div>
        </div>
    </section>

    <div class="container my-3">
        <div id="idWelcome" style="width: 100%; margin: 30px auto; padding: 35px; 
            font-family: 'Segoe UI', Tahoma, sans-serif; 
            background: linear-gradient(145deg, #ffffff, #f5f8fc); 
            border-radius: 20px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.12); 
            font-size: 16px; color: #333; line-height:1.8;">

            <h2 style="color: #c62828; margin-top: 0; margin-bottom:25px; 
               display:flex; align-items:center; gap:12px; 
               font-weight:800; letter-spacing:0.6px; 
               border-bottom:2px solid #f1f1f1; padding-bottom:10px;">
                📌 Catatan Penting
            </h2>

            <ul style="padding-left: 24px; margin-bottom: 28px;">
                <li style="margin-bottom: 15px;">
                    <strong>1. Lowongan Aktif:</strong>
                    <ul style="list-style-type: disc; padding-left: 22px; margin-top: 8px;">
                        <?php echo $liNamaJabatan; ?>
                    </ul>
                </li>

                <li style="margin-bottom: 15px;">
                    <strong>2. Jika Anda melamar untuk posisi yang tertera:</strong>
                    <ul style="list-style-type: circle; padding-left: 22px; margin-top: 8px;">
                        <li>📄 Pastikan seluruh dokumen dan data diisi dengan <strong>lengkap dan benar</strong>.</li>
                        <li>⚙️ Sistem akan menyaring otomatis berdasarkan kelengkapan data.</li>
                        <li style="color:#d00; font-weight:bold;">❗ Dokumen tidak lengkap atau tidak sesuai =
                            kemungkinan ditolak sistem.</li>
                        <li>⏳ Jika <strong>tidak ada respon dalam 14 hari</strong>, kemungkinan besar dokumen/data Anda
                            belum sesuai atau kriteria dasar tidak memenuhi.</li>
                    </ul>
                </li>

                <li>
                    <strong>3. Jika Anda melamar untuk posisi yang belum tersedia:</strong>
                    📂 CV akan masuk ke <em>Talent Pool</em> dan dipertimbangkan di masa mendatang.
                </li>
            </ul>

            <div style="background:#fff0f0; border-left:6px solid #c62828; 
                padding:18px 20px; border-radius:12px; margin-bottom:30px; 
                box-shadow: 0 0 12px rgba(198,40,40,0.15);">
                <p style="margin:0; font-size:15px; color:#444;">
                    <strong>🔒 Penting:</strong><br>
                    Andhika Group <u>tidak memungut biaya apapun</u> dalam proses rekrutmen.
                    🚫 Hati-hati terhadap penipuan yang mengatasnamakan perusahaan kami.
                </p>
            </div>

            <p style="margin-bottom: 35px; font-size:15px; color:#444;">
                🙏 Terima kasih atas perhatian dan kerja samanya.<br>
                Salam hangat,<br>
                <strong style="color:#c62828; font-size:16px;">Tim Crewing Andhika Group</strong>
            </p>

            <div style="margin-top: 20px; text-align:center;">
                <label style="display:inline-flex; align-items:center; gap:12px; 
                      font-size:15px; margin-bottom:22px; cursor:pointer;">
                    <input type="checkbox" id="chkUnderstand" onclick="toggleNextButton()"
                        style="transform:scale(1.3); cursor:pointer; accent-color:#1976d2;">
                    <span>Saya mengerti dan menyetujui informasi di atas.</span>
                </label>
                <br>
                <button id="btnNext" onclick="showRecruitment()" disabled style="padding:15px 45px; font-size:16px; background-color:#aaa; 
                   color:white; border:none; border-radius:40px; cursor:not-allowed; 
                   transition: all 0.3s ease; font-weight:600; 
                   box-shadow:0 5px 15px rgba(0,0,0,0.15);">
                    Next ➝
                </button>
            </div>
        </div>

        <div id="formRecruitment" style="display: none;">
            <div class="mx-auto bg-white p-4 p-md-5 rounded-5 shadow"
                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                <button type="button" class="btn btn-primary mt-3 ms-2 px-4 py-2 shadow-sm" onclick="goBackToWelcome()">
                    🔙 Kembali
                </button>

                <h2
                    style="font-weight:700; font-size:22px; margin-top:30px; color:#2563eb; border-left:6px solid #2563eb; padding-left:12px;">
                    📝 FORM RECRUITMENT CREW
                </h2>
                <p style="font-size:14px; color:#6b7280; margin-bottom:20px; line-height:1.6;">
                    Pastikan hanya mengunggah CV terbaru yang telah diperbarui. <br><br>
                    Hanya kandidat yang memenuhi syarat yang akan dihubungi.
                </p>
                <div class="row" style="margin-bottom:20px; margin-top:10px;">
                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Jabatan yang
                            Dilamar *</label>
                        <select class="form-select" name="position_applied" required
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                            <?php echo $optRank; ?>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom:20px;">
                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Email *</label>
                        <input type="email" name="txtemail" class="form-control" required
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                    </div>

                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Nama Lengkap
                            *</label>
                        <input type="text" name="txtnama" class="form-control" required
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                    </div>

                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Tempat Lahir
                            *</label>
                        <input type="text" name="txttempat_lahir" class="form-control" required
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                    </div>
                </div>

                <div class="row" style="margin-bottom:20px;">
                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Tanggal Lahir
                            *</label>
                        <input type="date" name="txttanggal_lahir" class="form-control" required
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                    </div>

                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Jenis Kelamin
                            *</label>
                        <select class="form-select" name="gender" required
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                            <option value="">- PILIH GENDER -</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Nomor Handphone
                            (WA) *</label>
                        <input type="tel" name="txthandphone" class="form-control" required pattern="[0-9]{10,15}"
                            placeholder="Masukkan nomor HP yang valid (10-15 digit)"
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                    </div>
                </div>

                <div class="row" style="margin-bottom:20px;">

                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Ijazah
                            Terakhir/Sertifikat
                            *</label>
                        <select class="form-select" name="ijazah_terakhir" required
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                            <option value="">- PILIH IJAZAH/SERTIFIKAT -</option>
                            <option value="ANT I">ANT I</option>
                            <option value="ANT II">ANT II</option>
                            <option value="ANT III">ANT III</option>
                            <option value="ANT IV">ANT IV</option>
                            <option value="ANT V">ANT V</option>
                            <option value="ATT I">ATT I</option>
                            <option value="ATT II">ATT II</option>
                            <option value="ATT III">ATT III</option>
                            <option value="ATT IV">ATT IV</option>
                            <option value="ATT V">ATT V</option>
                            <option value="ETO">ETO</option>
                            <option value="ETR">ETR</option>
                            <option value="RATING AS ABLE SEAFARER DECK ">RATING AS ABLE SEAFARER DECK </option>
                            <option value="RATINGS FORMING PART OF NAVIGATION WATCH">RATINGS FORMING PART OF NAVIGATION
                                WATCH</option>
                            <option value="RATING AS ABLE ENGINE">RATING AS ABLE ENGINE</option>
                            <option value="RATINGS FORMING PART OF A WATCH ENGINE ROOM">RATINGS FORMING PART OF A WATCH
                                ENGINE ROOM</option>
                            <option value="BASIC SAFETY TRAINING">BASIC SAFETY TRAINING</option>
                            <option value="SIO">SIO</option>
                            <option value="WELDER CERTIFICATE">WELDER CERTIFICATE</option>
                            <option value="FOOD HANDLING">FOOD HANDLING</option>
                            <option value="SHIP COOK">SHIP COOK</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-xs-12" style="margin-bottom:15px;" id="groupLastExp">
                        <label id="labelLastExp"
                            style="display:block; margin-bottom:6px; font-weight:600; color:#374151;">Pengalaman /
                            Jabatan Terakhir *</label>
                        <select class="form-select" name="pengalaman_terakhir" id="pengalaman_terakhir" required
                            style="border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                            <?php echo $optRank; ?>
                        </select>
                        <input type="number" step="0.01" min="0" max="4" class="form-control d-none" name="ipk_terakhir"
                            id="ipk_terakhir" placeholder="Contoh: 3.25"
                            style="margin-top:10px; border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                    </div>
                </div>
                <div class="row mt-5" id="groupSekolahJurusan" style="display:none;">
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-semibold">Sekolah *</label>
                        <input type="text" name="sekolah" id="sekolah" class="form-control"
                            placeholder="Contoh: Politeknik Pelayaran Surabaya"
                            style="margin-top:10px; border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                    </div>
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-semibold">Jurusan *</label>
                        <input type="text" name="jurusan" id="jurusan" class="form-control"
                            placeholder="Contoh: Nautika / Teknika"
                            style="margin-top:10px; border-radius:12px; padding:10px 14px; border:1px solid #d1d5db;">
                    </div>
                </div>


                <div class="row mt-3" id="groupJenisKapal">
                    <div class="col-md-6 col-12">
                        <label class="form-label fw-semibold">Pengalaman Berlayar di Jenis Kapal *</label>
                        <div class="row row-cols-2 g-2">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="BULK CARRIER">
                                <label class="form-check-label">BULK CARRIER</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="CARGO">
                                <label class="form-check-label">CARGO</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="GENERAL CARGO">
                                <label class="form-check-label">GENERAL CARGO</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="CONTAINER">
                                <label class="form-check-label">CONTAINER</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="TANKER PRODUCT">
                                <label class="form-check-label">TANKER PRODUCT</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="TANKER OIL">
                                <label class="form-check-label">TANKER OIL</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="CRUDE OIL">
                                <label class="form-check-label">CRUDE OIL</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="TANKER CHEMICAL">
                                <label class="form-check-label">TANKER CHEMICAL</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="TANKER GAS">
                                <label class="form-check-label">TANKER GAS</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="FLOATING CRANE">
                                <label class="form-check-label">FLOATING CRANE</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="TUG BOAT">
                                <label class="form-check-label">TUG BOAT</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="SUPPLY VESSEL">
                                <label class="form-check-label">SUPPLY VESSEL</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="CREW BOAT">
                                <label class="form-check-label">CREW BOAT</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kapal[]" value="RORO/PASSENGER">
                                <label class="form-check-label">RORO/PASSENGER</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="otherKapalCheckbox" name="kapal[]"
                                    value="OTHER">
                                <label class="form-check-label">OTHER</label>
                            </div>
                        </div>
                        <div class="mt-2" id="inputOtherKapal" style="display: none;">
                            <input type="text" class="form-control" name="kapal_other"
                                placeholder="Sebutkan jenis kapal lainnya">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4 col-12" id="groupCrewAsing">
                        <label class="form-label fw-semibold">Pernah Berlayar dengan Crew Asing? *</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="crew_foreign" value="Y" required>
                            <label class="form-check-label">Ya</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="crew_foreign" value="N">
                            <label class="form-check-label">Tidak</label>
                        </div>
                        <div id="foreignCountryInput" class="mt-2" style="display:none;">
                            <label class="form-label">Sebutkan Negara (wajib):</label>
                            <input type="text" name="foreign_country" class="form-control"
                                placeholder="Contoh: Jepang, Korea">
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 col-12" id="groupSalary">
                        <label class="form-label fw-semibold">Salary terakhir yang diterima? *</label>
                        <input type="text" name="last_salary" class="form-control" placeholder="Contoh: 1500 USD"
                            required>
                    </div>
                    <div class="col-md-4 col-12" id="groupJoin">
                        <label class="form-label fw-semibold">Apakah sudah pernah join di kapal Andhika Group
                            sebelumnya? *</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pernah_join" value="Y" required>
                            <label class="form-check-label">Ya</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pernah_join" value="N">
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-12" id="readyJoin">
                        <label class="form-label fw-semibold">Kesiapan Join Tanggal*</label>
                        <input type="date" name="join_date" class="form-control" id="join_date" required>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-12 col-12">
                            <label class="form-label fw-semibold">Dari Mana Anda mengetahui lowongan ini?</label>
                            <div class="row">
                                <div class="col-md-4 col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source" value="website"
                                            required>
                                        <label class="form-check-label">Website Resmi Perusahaan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="social_media">
                                        <label class="form-check-label">Media Sosial (Instagram, Facebook,
                                            Dll)</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="whatsapp_group">
                                        <label class="form-check-label">Group WhatsApp/Telegram Pelaut</label>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="referral">
                                        <label class="form-check-label">Rekomendasi Teman/Kerabat</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source" value="office">
                                        <label class="form-check-label">Langsung Dari Kantor Perusahaan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="job_fair">
                                        <label class="form-check-label">Job Fair Atau Event Rekrutmen</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="job_portal">
                                        <label class="form-check-label">Situs Lowongan Kerja (Jobstreet, Kalibr,
                                            dll)</label>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="agent_crewing">
                                        <label class="form-check-label">Agent Crewing</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="email_blast">
                                        <label class="form-check-label">Email Blast Perusahaan</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="alumni_sekolah_pelayaran">
                                        <label class="form-check-label">Alumni Sekolah Pelayaran</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="info_source"
                                            value="sudah_pernah_bekerja">
                                        <label class="form-check-label">Sudah Pernah Bekerja di Perusahaan Ini
                                            Sebelumnya</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6 col-12">
                            <label class="form-label fw-semibold">CV Terbaru *</label>
                            <input type="file" name="cv_files[]" class="form-control" accept=".pdf" multiple required>
                            <label style="font-size:13px; font-weight:bold; color:#dc3545;">Upload maksimum 5MB
                                dalam
                                format PDF.</label>
                            <div id="cvPreviewContainer" class="mt-2"></div>
                        </div>
                    </div>
                </div>
                <div id="error-message" class="alert alert-danger" style="display:none; position:fixed; top:20px; right:20px; 
                z-index:9999; font-size:20px; padding:25px 35px; 
                box-shadow: 0 6px 12px rgba(0,0,0,0.25); 
                border-radius: 10px; max-width: 500px; line-height: 1.6;">
                    <strong>⚠️ Error:</strong> Pesan kesalahan akan muncul di sini.
                </div>


                <input type="hidden" name="txtIdNewApplicant" id="txtIdNewApplicant" value="">
                <button type="button" class="btn btn-primary mt-4 px-4 py-2 shadow-sm" onclick="saveNewApplicant()">
                    🚀 Kirim Formulir
                </button>
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
        <div>Menyimpan data, mohon tunggu...</div>
    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.hc-sticky.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
</body>

</html>