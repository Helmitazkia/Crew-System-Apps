<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.9">
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
        const gaji = $("input[name='last_salary']").val();
        const infoSource = $("input[name='info_source']:checked").val();
        const tanggalLahir = $("input[name='txttanggal_lahir']").val();
        const gender = $("select[name='gender']").val();

        if (!gender) {
            showError("Silakan pilih jenis kelamin.");
            return;
        }
        formData.append("gender", gender);

        const crewForeign = $("input[name='crew_foreign']:checked").val();
        if (!crewForeign) {
            showError("Silakan pilih apakah pernah berlayar dengan crew asing.");
            return;
        }
        formData.append("crew_foreign", crewForeign);

        if (crewForeign === 'Y') {
            const foreignCountry = $("input[name='foreign_country']").val().trim();
            if (!foreignCountry) {
                showError("Silakan isi negara tempat Anda berlayar dengan crew asing.");
                return;
            }
            formData.append("foreign_country", foreignCountry);
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showError('Format email tidak valid');
            return;
        }

        if (!nama || !tempatLahir || !handphone || !posisi || !ijazah || !pengalaman || !gaji) {
            showError("Silakan lengkapi semua field wajib terlebih dahulu.");
            return;
        }

        if (!infoSource) {
            showError('Silakan pilih sumber informasi');
            return;
        }

        if (!tanggalLahir) {
            showError("Silakan isi tanggal lahir.");
            return;
        }

        const birthDate = new Date(tanggalLahir);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        if (age < 18 || age > 50) {
            showError("Usia pelamar harus antara 18 hingga 50 tahun.");
            return;
        }

        formData.append("txtemail", email);
        formData.append("txtnama", nama);
        formData.append("txttempat_lahir", tempatLahir);
        formData.append("txttanggal_lahir", tanggalLahir);
        formData.append("txthandphone", handphone);
        formData.append("position_applied", posisi);
        formData.append("ijazah_terakhir", ijazah);
        formData.append("pengalaman_terakhir", pengalaman);
        formData.append("last_salary", gaji);
        formData.append("pernah_join", $("select[name='pernah_join']").val() || 'N');
        formData.append("info_source", infoSource);

        $("input[name='kapal[]']:checked").each(function() {
            formData.append("kapal[]", $(this).val());
        });

        const otherKapalCheckbox = $('#otherKapalCheckbox');
        const otherKapalInput = $('input[name="kapal_other"]');
        if (otherKapalCheckbox.is(':checked') && otherKapalInput.val()) {
            formData.append("kapal[]", "OTHER: " + otherKapalInput.val());
        }

        const cvFiles = $("input[name='cv_files[]']")[0].files;
        if (cvFiles.length === 0) {
            showError("Silakan unggah CV");
            return;
        }

        for (let i = 0; i < cvFiles.length; i++) {
            const file = cvFiles[i];
            const sizeInMB = file.size / (1024 * 1024);
            if (sizeInMB > 5) {
                showError(`Ukuran file "${file.name}" melebihi 5MB. Silakan unggah file yang lebih kecil.`);
                return;
            }

            formData.append("cv_files[]", file);
        }

        $("#idLoading").show();
        $.ajax({
            url: '<?php echo base_url('extendCrewEvaluation/saveNewApplicant') ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                try {
                    const res = typeof response === 'string' ? JSON.parse(response) : response;
                    if (res.status === 'success') {
                        alert('Formulir berhasil dikirim. Terima kasih!');
                        window.location.reload();
                    } else {
                        showError(res.message || 'Terjadi kesalahan tidak dikenal');
                    }
                } catch (e) {
                    console.error('Parsing JSON gagal:', e, response);
                    showError('Respon server tidak valid');
                }
            }
        });
    }

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
        const checkbox = document.getElementById('chkUnderstand');
        const button = document.getElementById('btnNext');
        if (checkbox.checked) {
            button.disabled = false;
            button.style.backgroundColor = "#007bff";
            button.style.cursor = "pointer";
        } else {
            button.disabled = true;
            button.style.backgroundColor = "#aaa";
            button.style.cursor = "not-allowed";
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
        <div id="idWelcome"
            style="border: 1px solid #ccc; padding: 20px; font-family: Arial, sans-serif; background-color: #f9f9f9; border-radius: 10px; font-size: 15px;">
            <h4 style="color: #d9534f; margin-top: 0;">📌 Catatan Penting:</h4>
            <ul style="padding-left: 20px; line-height: 1.8;">
                <li><strong>1. Lowongan Aktif:</strong></li>
                <ul style="list-style-type: disc; padding-left: 20px;">
                    <?php echo $liNamaJabatan; ?>
                </ul>

                <li>2. Jika Anda melamar untuk <strong>posisi yang tertera</strong>, harap perhatikan:
                    <ul style="list-style-type: circle; padding-left: 20px;">
                        <li>Pastikan seluruh dokumen dan data diisi dengan <strong>lengkap dan benar</strong>.</li>
                        <li>Sistem akan menyaring otomatis berdasarkan kelengkapan data.</li>
                        <li style="color:#d00;"><strong>❗ Dokumen tidak lengkap atau tidak sesuai = kemungkinan ditolak
                                sistem.</strong></li>
                        <li>Jika <strong>tidak ada respon dalam 14 hari</strong>, kemungkinan besar dokumen/data anda
                            belum sesuai, atau kriteria dasar tidak memenuhi. Silahkan lengkapi kembali/menyesuaikan
                            kembali dengan kriteria dasar sebelum melamar ulang.</li>
                    </ul>
                </li>

                <li>3. Jika Anda melamar untuk <strong>posisi yang belum tersedia</strong>, CV akan masuk ke <em>Talent
                        Pool</em> dan dipertimbangkan di masa mendatang.</li>
            </ul>

            <p style="margin-top: 20px;"><strong>🔒 Penting:</strong><br>
                <strong>PT Andhika Group tidak memungut biaya apapun</strong> dalam proses rekrutmen. Hati-hati terhadap
                penipuan yang mengatasnamakan perusahaan kami.
            </p>

            <p>🙏 Terima kasih atas perhatian dan kerja samanya.<br>
                Salam hangat,<br>
                <strong>Tim Crewing Andhika Group</strong>
            </p>

            <div style="margin-top: 20px;">
                <input type="checkbox" id="chkUnderstand" onclick="toggleNextButton()">
                <label for="chkUnderstand">Saya mengerti dan menyetujui informasi di atas.</label>

                <button id="btnNext" onclick="showRecruitment()" disabled
                    style="margin-top:20px; padding:10px 20px; font-size:14px; background-color:#aaa; color:white; border:none; border-radius:5px; cursor:not-allowed;">
                    Next
                </button>
            </div>
        </div>

        <div id="formRecruitment" style="display: none;">
            <div class="mx-auto bg-white p-4 p-md-5 rounded-5 shadow"
                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                <button type="button" class="btn btn-primary mt-3 ms-2 px-4 py-2 shadow-sm" onclick="goBackToWelcome()">
                    🔙 Kembali
                </button>

                <h2 class="text-primary fs-4 mt-5">📝 FORM RECRUITMENT CREW <img id="idLoading"
                        src="<?php echo base_url('assets/img/loading.gif');?>" style="margin-right:10px;display:none;">
                </h2>

                <p class="text-secondary small mb-4">
                    Pastikan hanya mengunggah CV terbaru yang telah diperbarui. <br><br>
                    Hanya kandidat yang memenuhi syarat yang akan dihubungi.
                </p>

                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Email *</label>
                        <input type="email" name="txtemail" class="form-control" required>

                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Nama Lengkap *</label>
                        <input type="text" name="txtnama" class="form-control" required>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Tempat Lahir *</label>
                        <input type="text" name="txttempat_lahir" class="form-control" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Tanggal Lahir *</label>
                        <input type="date" name="txttanggal_lahir" class="form-control" required>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Jenis Kelamin *</label>
                        <select class="form-select" name="gender" required>
                            <option value="">- PILIH GENDER -</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Nomor Handphone (WA) *</label>
                        <input type="tel" name="txthandphone" class="form-control" required pattern="[0-9]{10,15}"
                            placeholder="Masukkan nomor HP yang valid (10-15 digit)">
                    </div>

                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Jabatan yang Dilamar *</label>
                        <select class="form-select" name="position_applied" required>
                            <?php echo $optRank; ?>
                        </select>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Ijazah Terakhir *</label>
                        <select class="form-select" name="ijazah_terakhir" required>
                            <option value="">- PILIH IJAZAH -</option>
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
                            <option value="RATINGS FORMING PART OF NAVIGATION WATCH  ">RATINGS FORMING PART OF
                                NAVIGATION
                                WATCH</option>
                            <option value="RATING AS ABLE ENGINE">RATING AS ABLE ENGINE</option>
                            <option value="RATINGS FORMING PART OF A WATCH ENGINE ROOM">RATINGS FORMING PART OF A WATCH
                                ENGINE ROOM
                            </option>
                            <option value="BASIC SAFETY TRAINING">BASIC SAFETY TRAINING</option>
                            <option value="SIO">SIO</option>
                            <option value="WELDER CERTIFICATE">WELDER CERTIFICATE</option>
                            <option value="FOOD HANDLING">FOOD HANDLING</option>
                            <option value="SHIP COOK">SHIP COOK</option>
                        </select>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Pengalaman / Jabatan Terakhir *</label>
                        <select class="form-select" name="pengalaman_terakhir" required>
                            <?php echo $optRank; ?>
                        </select>
                    </div>
                </div>

                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Pengalaman Berlayar di Jenis Kapal *</label>
                        <div class="row row-cols-2">
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
                                <div class="form-group mt-2" id="inputOtherKapal" style="display: none;">
                                    <input type="text" class="form-control" name="kapal_other"
                                        placeholder="Sebutkan jenis kapal lainnya">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Pernah Berlayar dengan Crew Asing? *</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="crew_foreign" value="Y" required>
                            <label class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="crew_foreign" value="N">
                            <label class="form-check-label">No</label>
                        </div>
                        <div id="foreignCountryInput" style="display:none; margin-top:10px;">
                            <label class="form-label">Sebutkan Negara (wajib):</label>
                            <input type="text" name="foreign_country" class="form-control"
                                placeholder="Contoh: Jepang, Korea">
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Salary terakhir yang diterima? *</label>
                        <input type="text" name="last_salary" class="form-control" placeholder="Contoh: 1500 USD"
                            required>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Apakah sudah pernah join di kapal Andhika Group
                            sebelumnya?
                            *</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pernah_join" value="Y" required>
                            <label class="form-check-label">Ya</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="pernah_join" value="N" required>
                            <label class="form-check-label">Tidak</label>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">Dari Mana Anda mengetahui lowongan ini?</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source" value="website"
                                        required>
                                    <label class="form-check-label">Website Resmi Perusahaan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source" value="social_media"
                                        required>
                                    <label class="form-check-label">Media Sosial (Instagram, Facebook, Dll)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source"
                                        value="whatsapp_group" required>
                                    <label class="form-check-label">Group WhatsApp/Telegram Pelaut</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source" value="referral"
                                        required>
                                    <label class="form-check-label">Rekomendasi Teman/Kerabat</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source" value="office"
                                        required>
                                    <label class="form-check-label">Langsung Dari Kantor Perusahaan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source" value="job_fair"
                                        required>
                                    <label class="form-check-label">Job Fair Atau Event Rekrutmen</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source" value="job_portal"
                                        required>
                                    <label class="form-check-label">Situs Lowongan Kerja (Jobstreet, Kalibr,
                                        dll)</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source"
                                        value="agent_crewing" required>
                                    <label class="form-check-label">Agent Crewing</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source" value="email_blast"
                                        required>
                                    <label class="form-check-label">Email Blast Perusahaan</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source"
                                        value="alumni_sekolah_pelayaran" required>
                                    <label class="form-check-label">Alumni Sekolah Pelayaran</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="info_source"
                                        value="sudah_pernah_bekerja" required>
                                    <label class="form-check-label">Sudah Pernah Bekerja di Perusahaan Ini
                                        Sebelumnya</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <label class="form-label fw-semibold">CV Terbaru *</label>
                        <input type="file" name="cv_files[]" class="form-control" accept=".pdf" multiple required>
                        <label class="form-label fw-bold">Upload maksimum 5MB dalam format PDF.</label>
                    </div>
                </div>
                <div id="error-message" class="alert alert-danger" style="display:none; position:fixed; top:20px; right:20px; 
                z-index:9999; font-size:20px; padding:25px 35px; 
                box-shadow: 0 6px 12px rgba(0,0,0,0.25); 
                border-radius: 10px; max-width: 500px; line-height: 1.6;">
                    <strong>⚠️ Error:</strong> Pesan kesalahan akan muncul di sini.
                </div>


                <input type="hidden" name="txtIdNewApplicant" value="<?php echo uniqid('NewApplicant'); ?>">
                <button type="button" class="btn btn-primary mt-4 px-4 py-2 shadow-sm" onclick="saveNewApplicant()">
                    🚀 Kirim Formulir
                </button>
            </div>
        </div>

    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.hc-sticky.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/owl.carousel.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.9.2.custom.min.js"></script>
</body>

</html>