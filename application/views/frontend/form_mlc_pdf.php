<!DOCTYPE html>
<html>

<head>
    <title>FORM PERNYATAAN MLC</title>
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

    /* === GLOBAL FONT SIZE FOR PDF === */
    body {
        font-family: "Times New Roman", serif;
        font-size: 11px;
        /* dari default 13px */
    }

    /* HEADER */
    .long-line {
        border-bottom: 0.8px solid #000;
    }

    /* STATEMENT TABLE */
    .statement-table {
        font-size: 11px;
    }

    .statement-table th,
    .statement-table td {
        padding: 4px;
        /* dari 6px */
    }

    /* SUBTEXT */
    .subtext {
        font-size: 10px;
        margin-top: 2px;
    }

    /* YES / NO CHECK BOX */
    .check-box {
        width: 14px;
        height: 14px;
        border: 1.2px solid #000;
    }

    /* REMARKS */
    .remarks-title {
        font-size: 11px;
    }

    .remarks-box {
        height: 55px;
        /* dari 70px */
    }

    /* LIST TEXT */
    ul {
        font-size: 11px;
    }

    .sign-grid {
        width: 100%;
        border-collapse: separate;
        /* PENTING */
        border-spacing: 15px 12px;
        /* jarak antar box */
        font-size: 11px;
    }

    .sign-box {
        border: 1px solid #000;
        height: 75px;
        vertical-align: bottom;
        padding-bottom: 6px;
    }
</style>


<body>
    <div class="modal fade" id="modal-form-mlc" tabindex="-1">
        <div class="modal-dialog modal-lg" style="max-width:850px;">
            <div class="modal-content"
                style="border:1px solid #000; border-radius:6px; padding:25px; font-family:'Times New Roman', serif;">

                <table style="width:100%; border-collapse:collapse;">
                    <tr>
                        <td style="width:90px; vertical-align:top;">
                            <img src="./assets/img/Logo_Andhika_2017.jpg" style="width:80px;">
                        </td>

                        <td style="text-align:center; vertical-align:middle;">
                            <br>
                            <div style="font-size:12px; font-weight:bold;margin-top:0px;margin-left:70px;">MLC
                                DECLARATION FORM <br>
                                <div style="font-size:15px; font-weight:bold; margin-top:1px;margin-left:70px;">FORM
                                    PERNYATAAN MLC</div>
                                <br>
                        </td>

                        <td style="width:170px; text-align:right; vertical-align:top;">
                            <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                            <div style="font-size:10px;">SIUKAK 236.121
                                - R Tahun 2025</div>
                            <div style="margin-top:5px;">
                                <img src="./assets/img/Bureau_Veritas_Logo.jpg" style="width:60px; margin-right:3px;">
                                <img src="./assets/img/Iso.jpg" style="width:60px;">
                            </div>
                        </td>
                    </tr>
                </table>
                <br>
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
                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_1'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_1'] == 0) ? '✓' : ''; ?>
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
                        <!-- Di dalam table -->
                        <!-- HTML entities yang lebih support -->

                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_2'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_2'] == 0) ? '✓' : ''; ?>
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
                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_3'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_3'] == 0) ? '✓' : ''; ?>
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
                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_4'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_4'] == 0) ? '✓' : ''; ?>
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
                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_5'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_5'] == 0) ? '✓' : ''; ?>
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
                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_6'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_6'] == 0) ? '✓' : ''; ?>
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
                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_7'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_7'] == 0) ? '✓' : ''; ?>
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
                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_8'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_8'] == 0) ? '✓' : ''; ?>
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
                        <td class="col-yes"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 16px;">
                            <?php echo ($all_data['statement_9'] == 1) ? '✓' : ''; ?>
                        </td>
                        <td class="col-no-check"
                            style="text-align: center; padding-top: 10px; padding-bottom: 10px;font-family: 'DejaVu Sans'; font-size: 20px;">
                            <?php echo ($all_data['statement_9'] == 0) ? '✓' : ''; ?>
                        </td>
                    </tr>
                </table>


                <br />
                <ul style="font-size:13px; padding-left:18px; margin:0;">
                    <li>
                        By ticking the YES box you indicate that the documented statement is correct.<br>
                        <div class="long-line"></div>
                        Dengan mencentang kotak YA yang anda tandai bahwa pernyataan yang dituliskan adalah benar.
                    </li>
                    <br />

                    <li>
                        By ticking the NO box you indicate that the documented statement is NOT correct.<br>
                        <div class="long-line"></div>
                        Dengan mencentang kotak TIDAK yang anda tandai bahwa pernyataan yang dituliskan adalah TIDAK
                        benar.
                    </li>
                    <br />
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

                <div class="sign-container">
                    <div class="sign-table-wrapper">
                        <table style="border-collapse:separate; border-spacing:15px 0;">
                            <tr>
                                <td style="
                                        border:1px solid #000;
                                        width: 10%;
                                        height:200px;
                                        vertical-align:bottom;
                                        text-align:center;
                                        padding-bottom:8px;
                                    ">
                                    <div style="font-weight:bold;">Seafarer's Name</div>
                                    <div style="font-size:12px;"> <?php echo $crew->fullname ?></div>
                                </td>

                                <td style="
                                        border:1px solid #000;
                                        width:10%;
                                        height:70px;
                                        vertical-align:bottom;
                                        text-align:center;
                                        padding-bottom:8px;
                                    ">
                                    <div style="font-weight:bold;">Rank</div>
                                    <div style="font-size:12px;"><?php echo $crew->nmrank ?></div>
                                </td>
                                <td style="
                                        border:1px solid #000;
                                        width:10%;
                                        height:70px;
                                        vertical-align:bottom;
                                        text-align:center;
                                        padding-bottom:8px;
                                    ">
                                    <div style="font-weight:bold;">Date</div>
                                    <div style="font-size:12px;"><?php echo $crew->signondt ?></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <br>
                <div class="sign-container">
                    <div class="sign-table-wrapper">
                        <table style="border-collapse:separate; border-spacing:15px 0;">
                            <tr>
                                <td style="
                                        border:1px solid #000;
                                        width: 144px;
                                        height:120px;
                                        vertical-align:bottom;
                                        text-align:center;
                                        padding-bottom:8px;
                                    ">
                                    <div style="font-weight:bold;font-size:13px;">Eva Marliana</div>
                                    <div style="font-size:12px;">Crew Manager</div>
                                </td>

                                <td style="
                                        border:1px solid #000;
                                        width:10%;
                                        height:120px;
                                        vertical-align:bottom;
                                        text-align:center;
                                        padding-bottom:8px;
                                    ">
                                    <div style="font-weight:bold;font-size:13px;">Vessel to Join</div>
                                    <div style="font-size:12px;"><?php echo $crew->nmvsl ?></div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>