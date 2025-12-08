<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Introduction Letter</title>
</head>

<body style="font-family:'Times New Roman', serif; margin:40px; font-size:14px;">

    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:90px; vertical-align:top;">
                <img src="<?php echo base_url('assets/img/gambar_andhika.jpg'); ?>" style="width:80px;">
            </td>

            <td style="text-align:center;">
                <div style="font-size:18px; font-weight:bold; letter-spacing:8px;">INSTRUKSI</div>
                <div style="font-size:15px; font-weight:bold;">INSTRUCTION LETTER</div>
            </td>

            <td style="width:170px; text-align:right; font-size:11px;">
                <div style="font-weight:bold;">SRPS LICENSE NO:</div>
                <div>SIUPPAK 12.12 Tahun 2014</div>
                <div style="margin-top:5px;">
                    <img src="<?php echo base_url('assets/img/gambar_bureau.jpg'); ?>" style="width:60px;">
                    <img src="<?php echo base_url('assets/img/gambar_iso.jpg'); ?>" style="width:60px;">
                </div>
            </td>
        </tr>
    </table>

    <table style="width:100%; margin-top:25px; font-size:13px;">
        <tr>
            <td style="width:140px;">Berdasarkan</td>
            <td>: Kepentingan Dinas Perusahaan</td>
        </tr>
        <tr>
            <td>Base on</td>
            <td>: Shipping Company Official Regulation</td>
        </tr>
        <tr>
            <td></td>
            <td>: <?php echo $crew->entitas; ?></td>
        </tr>
    </table>

    <div style="margin-top:30px; text-align:center; font-weight:bold;">
        DIINSTRUKSIKAN<br>
        <span style="font-weight:normal;">INSTRUCTED</span>
    </div>

    <table style="width:100%; margin-top:20px; font-size:13px;">
        <tr>
            <td style="width:110px;">Kepada (To)</td>
            <td>: Master <?php echo $crew->vessel_name; ?></td>
        </tr>
        <tr>
            <td>Untuk (For)</td>
            <td>: ___________________________</td>
        </tr>
    </table>

    <div style="margin-top:20px; font-size:13px;">
        1. Membebaskan dari tugas dan tanggung jawab serta jabatan:<br>
        <i>Release from the duty/responsibility</i>
    </div>

    <table
        style="width:100%; border:1px solid #000; border-collapse:collapse; margin-top:7px; font-size:13px; text-align:center;">
        <tr>
            <td style="padding:6px; border:1px solid #000;">Nama</td>
            <td style="padding:6px; border:1px solid #000;">Jabatan</td>
            <td style="padding:6px; border:1px solid #000;">Reason</td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $crew->release_header_others; ?></td>
        </tr>

        <?php if (!empty($crew->release_rows)) { ?>
        <?php foreach ($crew->release_rows as $r) { ?>
        <tr>
            <td style="padding:6px; border:1px solid #000;"><?php echo $r->nama; ?></td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $r->jabatan; ?></td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $r->reason; ?></td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $r->others; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
    </table>

    <div style="margin-top:25px; font-size:13px;">
        2. Sebagai penggantinya ditetapkan:<br>
        <i>As the successor:</i>
    </div>

    <table
        style="width:100%; border:1px solid #000; border-collapse:collapse; margin-top:7px; font-size:13px; text-align:center;">
        <tr>
            <td style="padding:6px; border:1px solid #000;">Nama</td>
            <td style="padding:6px; border:1px solid #000;">Jabatan</td>
            <td style="padding:6px; border:1px solid #000;">B/S</td>
            <td style="padding:6px; border:1px solid #000;">OT</td>
            <td style="padding:6px; border:1px solid #000;">Leave Pay</td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $crew->successor_header_others; ?></td>
        </tr>

        <?php if (!empty($crew->successor_rows)) { ?>
        <?php foreach ($crew->successor_rows as $s) { ?>
        <tr>
            <td style="padding:6px; border:1px solid #000;"><?php echo $s->nama; ?></td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $s->jabatan; ?></td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $s->bs; ?></td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $s->ot; ?></td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $s->leavepay; ?></td>
            <td style="padding:6px; border:1px solid #000;"><?php echo $s->others; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
    </table>

    <div style="margin-top:25px; font-size:13px; line-height:1.5;">
        3. Pelaksanaan Sign On/Off di pelabuhan: <b><?php echo $crew->port_signonoff; ?></b><br>
        <i>The Signing On/Off at <?php echo $crew->port_signonoff; ?></i><br><br>

        4. Agar dilaksanakan dengan penuh tanggung jawab.<br>
        <i>Please follow with full responsibility.</i>
    </div>

    <div style="margin-top:35px; display:flex; justify-content:space-between; font-size:13px;">
        <div>
            Instruksi: Selesai<br>
            <i>Instruction: Done</i>
        </div>
        <div style="text-align:right;">
            Jakarta, <?php echo $crew->tanggal_instruction; ?><br>
            <?php echo $crew->entitas; ?>
        </div>
    </div>

    <div style="margin-top:60px; text-align:right; font-size:14px; font-weight:bold;">
        Eva Marliana<br>
        <span style="font-weight:normal;">Crewing Manager</span>
    </div>

</body>

</html>