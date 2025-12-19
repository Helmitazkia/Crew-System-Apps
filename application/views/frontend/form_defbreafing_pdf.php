<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Form Debriefing</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 13px;
        }

        .container {
            width: 100%;
            padding: 25px;
            /* border: 1px solid #000; */
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 1px 1px;
            vertical-align: top;
            margin-bottom: 14px;
        }

        .label {
            width: 200px;
        }

        .line {
            border-bottom: 1px solid #000;
            height: 14px;
        }

        .section-title {
            margin-top: 20px;
            font-weight: bold;
        }

        .question-table th,
        .question-table td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }

        .question-table th {
            text-align: center;
        }

        .answer-box {
            height: 110px;
        }


        .remarks-box {
            width: 100%;
            height: 130px;
            border: 1px solid #000;
            margin-bottom: 14px;
        }

        .signature-table td {
            height: 70px;
            vertical-align: bottom;
            text-align: center;
            padding-top:150px;
        }

        .footer {
            margin-top: 20px;
            font-size: 11px;
        }

        .page {
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: auto;
        }

        .answer-box.big {
            height: 120px;
        }

        @media print {
            body {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <td style="width:90px; vertical-align:top;">
                    <img src="./assets/img/Logo_Andhika_2017.jpg" style="width:80px;">
                </td>

                <td style="text-align:right; padding-right:120px; vertical-align:middle;">
                   <br>
                   <br>
                   <div style="font-size:20px; font-weight:bold; margin-top:1px;margin-left:70px;">DEBRIEFING</div>
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

        <div class="container page">
            <!-- <table class="data-utama-table" style="width:100%; border-collapse:collapse; margin-top:15px;" border="1">
                <tr>
                    <td style="width:25%; font-weight:bold;">Nama Kapal</td>
                    <td style="width:25%;">:<smal style="padding-left:900px;"></smal><?php echo $crew->vessel ?>ewrwer</td>
                    <td style="width:25%; font-weight:bold;">Pelabuhan</td>
                    <td style="width:25%;">:<smal style="padding:7px;"></smal><?php echo $crew->pelabuhan ?></td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Jabatan</td>
                    <td>:</td>
                    <td style="font-weight:bold;">No. Telepon / HP</td>
                    <td>:</td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Nama Crew</td>
                    <td>:</td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Tgl. Join</td>
                    <td>:</td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Tgl. Sign Off</td>
                    <td>:</td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Kesiapan Join</td>
                    <td>:</td>
                    <td></td>
                    <td></td>
                </tr>
            </table> -->
            <table class="data-utama-table" style="width:100%; border-collapse:collapse; margin-top:15px;">
                <tr>
                    <td style="width:18%; font-weight:bold;">Nama Kapal</td>
                    <td style="width:2%; text-align:center;">:</td>
                    <td style="width:30%; padding-left:4px;">
                        <?php echo $crew->vessel ?>
                    </td>

                    <td style="width:18%; font-weight:bold;">Pelabuhan</td>
                    <td style="width:2%; text-align:center;">:</td>
                    <td style="width:30%; padding-left:4px;">
                        <?php echo $crew->pelabuhan ?>
                    </td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Jabatan</td>
                    <td style="text-align:center;">:</td>
                    <td style="padding-left:4px;">
                        <?php echo $crew->jabatan ?>
                    </td>

                    <td style="font-weight:bold;">No. Telepon / HP</td>
                    <td style="text-align:center;">:</td>
                    <td style="padding-left:4px;">
                        <?php echo $crew->no_telp ?>
                    </td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Nama Crew</td>
                    <td style="text-align:center;">:</td>
                    <td style="padding-left:4px;">
                        <?php echo $crew->nama_crew ?>
                    </td>

                    <td></td><td></td><td></td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Tgl. Join</td>
                    <td style="text-align:center;">:</td>
                    <td style="padding-left:4px;">
                        <?php echo $crew->tgl_join ?>
                    </td>
                </tr>
                <tr>
                    <td style="font-weight:bold;">Tgl. Sign Off</td>
                    <td style="text-align:center;">:</td>
                    <td style="padding-left:4px;">
                        <?php echo$crew->tgl_signoff ?>
                    </td>
                </tr>

                <tr>
                    <td style="font-weight:bold;">Kesiapan Join</td>
                    <td style="text-align:center;">:</td>
                    <td style="padding-left:4px;">
                        <?php echo $crew->siap_join ?>
                    </td>

                    <td></td><td></td><td></td>
                </tr>
            </table>


            <div class="section-title">
                Certificates and documents yang harus diperbaharui atau dilengkapi :
            </div>
            <div class="remarks-box" style="padding-left:4px;"><?php echo$crew->certificates ?></div>

            <!-- PERTANYAAN 5–9 -->
            <table class="question-table">
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
                    <td>Penerapan K3 di kapal?</td>
                    <td class="answer-box"></td>
                </tr>

                <tr>
                    <td align="center">3</td>
                    <td>Training crew apa saja?</td>
                    <td class="answer-box"></td>
                </tr>

                <tr>
                    <td align="center">4</td>
                    <td>
                        Masalah yang dihadapi dan penyelesaiannya?
                        <br><br>
                        <strong>Masalah :</strong><br><br>
                        <strong>Penyelesaian :</strong>
                    </td>
                    <td class="answer-box"></td>
                </tr>
                 <tr>
                    <td align="center">5</td>
                    <td>Bagaimana kondisi kerja tim di kapal?</td>
                    <td class="answer-box big"></td>
                </tr>
            </table>

        </div>
        <div class="container">

            <!-- HEADER PAGE 2 -->
            <table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
                <tr>
                    <td style="width:90px; vertical-align:top;">
                        <img src="./assets/img/Logo_Andhika_2017.jpg" style="width:80px;">
                    </td>
                    <td style="text-align:center; font-size:12px;padding-left:70px;">
                        <strong>PT. ANDHINI EKA KARYA SEJAHTERA</strong><br>
                        Head Office : Menara Kadin Indonesia Lt. 20 Unit D.<br>
                        Jl. HR. Rasuna Said Blok X-5 Kav 2-3<br>
                        Kel. Kuningan Timur, Kec. Setiabudi<br>
                        Jakarta Selatan 12950, Indonesia
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

            <!-- PERTANYAAN 5–9 -->
            <table class="question-table">
                <tr>
                    <th style="width:40px;">No</th>
                    <th>Pertanyaan</th>
                    <th style="width:45%;">Jawaban</th>
                </tr>
                <tr>
                    <td align="center">6</td>
                    <td>Kebersihan di atas kapal?</td>
                    <td class="answer-box big"></td>
                </tr>

                <tr>
                    <td align="center">7</td>
                    <td>Makanan di atas kapal?</td>
                    <td class="answer-box big"></td>
                </tr>

                <tr>
                    <td align="center">8</td>
                    <td>Kondisi kesehatan setelah sign off?</td>
                    <td class="answer-box big"></td>
                </tr>

                <tr>
                    <td align="center">9</td>
                    <td>Harapan dan saran?</td>
                    <td class="answer-box big"></td>
                </tr>
            </table>

            <!-- REMARKS -->
            <div class="section-title">
                Remarks / Comment :
                <br><em>*diisi oleh crew executive</em>
            </div>
            <div class="remarks-box"></div>

            <!-- SIGN -->
            <table class="info-table" style="margin-top:15px;">
                <tr>
                    <td class="label" style="color:black;font-size:13px;">Tanggal : <small><?php echo date("d M Y", strtotime(date('Y-m-d'))); ?></small></td>
                </tr>
            </table>

            <table class="signature-table">
                <tr>
                    <td>Crew Manager</td>
                    <td>Crew Executive</td>
                    <td>Seafarer</td>
                </tr>
            </table>

        </div>


    </div>

</body>

</html>