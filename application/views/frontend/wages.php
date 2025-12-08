<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Statement of Wages</title>
</head>

<body style="font-family:'Times New Roman', serif; margin:40px; font-size:12px; color:#111; line-height:1.5;">
    <div style="max-width:750px; margin:0 auto;">

        <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
            <tr>
                <td style="width:80px; vertical-align:top;">
                    <img src="<?php echo base_url('assets/img/Logo Andhika 2017.jpg'); ?>" alt="Logo"
                        style="width:80px; height:auto;">
                </td>

                <td style="text-align:center; vertical-align:middle;">
                    <div style="font-size:15px; font-weight:bold;">SURAT PERNYATAAN GAJI</div>
                    <div style="font-size:11px;">STATEMENT OF WAGES</div>
                </td>

                <td style="width:170px; text-align:right; vertical-align:top;">
                    <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                    <div style="font-size:10px;">SIUPPAK 12.12 Tahun 2014</div>
                    <div style="margin-top:3px;">
                        <img src="<?php echo base_url('assets/img/Bureau Veritas Logo.jpg'); ?>" alt="BV"
                            style="width:65px; margin-right:3px;">
                        <img src="<?php echo base_url('assets/img/iso.jpg'); ?>" alt="ISO" style="width:65px;">
                    </div>
                </td>
            </tr>
        </table>

        <table style="width:100%; margin-top:15px; font-size:11px;">
            <tr>
                <td style="width:45%;">I herewith the undersigned</td>
                <td style="width:3%;">:</td>
                <td></td>
            </tr>
            <tr>
                <td style="font-style:italic;">Yang bertanda tangan di bawah ini</td>
                <td>:</td>
                <td></td>
            </tr>
        </table>

        <table style="width:100%; border-collapse:collapse; margin-top:8px; font-size:11px;">
            <tr>
                <td style="width:35%; border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">
                    Name/Nama</td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->fullname; ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">Position/Jabatan
                </td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->position; ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">Vessel/Kapal</td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->vessel_name ?: $crew->vessel; ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">Sign On
                    date/Tanggal Naik Kapal</td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->sign_on_date; ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">Port of
                    Embarkation/Pelabuhan</td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->embarkation_port; ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">Sea Service/Masa
                    Layar</td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->sea_service; ?></td>
            </tr>
        </table>

        <p style="margin-top:10px; font-size:11px;">
            <b>Understand & agree the total salary and salary pay system as company regulation as follows:</b><br>
            <i>Mengerti & menyetujui jumlah gaji dan sistem pembayarannya sesuai dengan peraturan perusahaan sebagai
                berikut:</i>
        </p>

        <table style="width:100%; border-collapse:collapse; margin-top:6px; font-size:11px; text-align:center;">
            <tr style="background:#f2f2f2; font-weight:bold;">
                <td style="border:1px solid #000; padding:6px;">Basic Wages</td>
                <td style="border:1px solid #000; padding:6px;">FOT</td>
                <td style="border:1px solid #000; padding:6px;">Tanker Allow.</td>
                <td style="border:1px solid #000; padding:6px;">Leave Pay</td>
                <td style="border:1px solid #000; padding:6px;">B/S (%)</td>
                <td style="border:1px solid #000; padding:6px;">H/S (%)</td>
                <td style="border:1px solid #000; padding:6px;">Total Pay</td>
            </tr>
            <tr>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->basic_wages; ?></td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->fot; ?></td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->tanker_allow; ?></td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->leave_pay; ?></td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->bs_percent; ?></td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->hs_percent; ?></td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->total_pay; ?></td>
            </tr>
        </table>

        <div style="margin-top:14px; font-weight:bold;">B. Next Of Kin / Keluarga Terdekat</div>
        <table style="width:75%; border-collapse:collapse; margin-top:6px; font-size:11px;">
            <tr>
                <td style="width:40%; border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">
                    Name/Nama</td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->next_of_kin_name; ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">Relationship/Hub
                </td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->next_of_kin_relation; ?></td>
            </tr>
            <tr>
                <td style="border:1px solid #000; padding:6px; background:#f2f2f2; font-weight:bold;">No Tlp/HP</td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $crew->next_of_kin_phone; ?></td>
            </tr>
        </table>

        <p style="margin-top:10px; font-size:11px;">
            I hereby confirm the above contained herein is correct, without compulsion.<br>
            <i>Demikian pernyataan ini saya buat dengan sebenarnya, tanpa paksaan dari pihak lain.</i>
        </p>

        <table style="width:100%; margin-top:35px; font-size:11px;">
            <tr>
                <td style="width:50%; vertical-align:top;">
                    <b>Acknowledge,</b><br>
                    Mengetahui,<br><br><br><br><br><br><br><br><br><br>
                    Head of Crewing Division
                </td>
                <td style="width:50%; text-align:right; vertical-align:top;">
                    <b>Seafarer,</b><br>
                    Pelaut,<br><br><br><br><br><br><br><br><br><br>
                    <?php echo $crew->fullname; ?>
                </td>
            </tr>
        </table>
    </div>
</body>




</html>