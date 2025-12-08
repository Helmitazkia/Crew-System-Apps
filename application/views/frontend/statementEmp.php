<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Statement Pernyataan</title>
    <style>
    body {
        font-family: "Times New Roman", serif;
        font-size: 14px;
    }

    .border-box {
        padding: 40px 55px;
    }
    </style>
</head>

<body>

    <div class="border-box">

        <table style="width:100%; border-collapse:collapse; margin-bottom:15px;">
            <tr>
                <td style="width:120px; vertical-align:top;">
                    <img src="<?php echo base_url('assets/img/Logo_Andhika_2017.jpg'); ?>" style="width:100px;">
                </td>

                <td style="text-align:center; vertical-align:middle;">
                    <div style="font-size:20px; font-weight:bold; letter-spacing:2px; margin-top:10px;">
                        STATEMENT / <em>Pernyataan</em>
                    </div>
                </td>

                <td style="width:200px; text-align:right; font-size:11px; vertical-align:top;">
                    <div style="font-weight:bold;">SRPS LICENSE NO:</div>
                    <div>SIUKAK 236.121 - R Tahun 2025</div>

                    <div style="margin-top:6px;">
                        <img src="<?php echo base_url('assets/img/Bureau_Veritas_Logo.jpg'); ?>"
                            style="width:70px; margin-right:3px;">
                        <img src="<?php echo base_url('assets/img/Iso.jpg'); ?>" style="width:70px;">
                    </div>
                </td>
            </tr>
        </table>


        <div style="margin-top:10px; margin-left:15px; width:92%;">
            <p>
                I <strong><?php echo $crew->nama_crew ?></strong>
                hereby declare that I have never given money or gifts to any Andhika Eka Karya Sejahtera staff.
            </p>

            <p style="font-style:italic; margin-top:10px;">
                Saya <strong><?php echo $crew->nama_crew ?></strong>
                dengan ini menyatakan sesungguhnya bahwa saya tidak pernah memberi uang atau hadiah
                kepada staf Personalia Laut Andhika Eka Karya Sejahtera.
            </p>
        </div>

        <div style="margin-top:30px; margin-left:20px; width:70%; font-size:13px;">
            <table style="border-collapse:collapse;">
                <tr>
                    <td style="width:110px;">Date<br><i>tanggal</i></td>
                    <td style="width:10px;">:</td>
                    <td><strong><?php echo $today ?></strong></td>
                </tr>

                <tr>
                    <td style="padding-top:8px;">Vessel<br><i>Kapal</i></td>
                    <td>:</td>
                    <td><strong><?php echo $crew->nama_kapal ?></strong></td>
                </tr>

                <tr>
                    <td style="padding-top:8px;">Rank<br><i>Jabatan</i></td>
                    <td>:</td>
                    <td><strong><?php echo $crew->nama_rank ?></strong></td>
                </tr>
            </table>
        </div>

        <table style="width:100%; border-collapse:collapse; margin-top:50px;">
            <tr>
                <td style="width:50%; text-align:left; vertical-align:top;">
                    Thank you.<br>
                    <i>Terima kasih</i>
                </td>

                <td style="width:50%; text-align:right; vertical-align:top;">
                    Acknowledge:<br>
                    <i>Mengetahui</i>
                </td>
            </tr>
        </table>

        <!-- Jarak tanda tangan diperbesar -->
        <table style="width:100%; border-collapse:collapse; margin-top:90px;">
            <tr>
                <!-- TTD CREW -->
                <td style="width:50%; text-align:left; vertical-align:bottom;">
                    <!-- Turunkan nama crew lebih jauh -->
                    <div style="margin-bottom:75px;"><?php echo $crew->nama_crew; ?></div>
                    <div style="border-top:1px solid #333; width:160px; padding-top:5px;">
                        Seafarer
                    </div>
                </td>

                <!-- TTD CREW MANAGER -->
                <td style="width:50%; text-align:right; vertical-align:bottom;">
                    <div style="font-size:13px; font-weight:700; text-decoration:underline; margin-bottom:3px;">
                        EVA MARLIANA
                    </div>
                    <div style="font-size:12px;">Crew Manager</div>
                </td>
            </tr>
        </table>



    </div>

</body>

</html>