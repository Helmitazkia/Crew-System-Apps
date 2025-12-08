<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>SURAT PERINTAH JALAN</title>
</head>

<body style="font-family:'Times New Roman', serif; font-size:13px; margin:40px; line-height:1.4;">

    <!-- HEADER -->
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:80px; text-align:left; vertical-align:top;">
                <img src="<?php echo base_url('assets/img/Logo Andhika 2017.jpg'); ?>" alt="Logo"
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

    <!-- DETAIL DATA -->
    <table style="width:100%; border-collapse:collapse; font-size:13px;">
        <tr>
            <td style="width:150px; vertical-align:top;">Berdasarkan<br><i>(Base on)</i></td>
            <td style="width:10px; vertical-align:top;">:</td>
            <td style="vertical-align:top;">
                <?php echo $crew->base_on ?: 'Kepentingan Perusahaan'; ?><br>
                <i>(Company Occupation)</i>
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
                        <td><?php echo $crew->name; ?></td>
                    </tr>
                    <tr>
                        <td>Jabatan <i>(Rank)</i></td>
                        <td>:</td>
                        <td><?php echo $crew->rank; ?></td>
                    </tr>
                    <tr>
                        <td>Tujuan <i>(Destination)</i></td>
                        <td>:</td>
                        <td><?php echo $crew->destination; ?></td>
                    </tr>
                    <tr>
                        <td>Keperluan <i>(Purpose)</i></td>
                        <td>:</td>
                        <td><?php echo $crew->purpose; ?></td>
                    </tr>
                    <tr>
                        <td>Berangkat Tanggal <i>(Date of Depart)</i></td>
                        <td>:</td>
                        <td><?php echo !empty($crew->depart_date) ? date('d/m/Y', strtotime($crew->depart_date)) : '-'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Tiba Tanggal <i>(Date of Arrival)</i></td>
                        <td>:</td>
                        <td><?php echo !empty($crew->arrival_date) ? date('d/m/Y', strtotime($crew->arrival_date)) : '-'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Kendaraan <i>(Transportation)</i></td>
                        <td>:</td>
                        <td><?php echo $crew->transportation ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td>Catatan <i>(Note)</i></td>
                        <td>:</td>
                        <td><?php echo $crew->note ?: '-'; ?></td>
                    </tr>
                    <tr>
                        <td>Pengikut <i>(Accompany)</i></td>
                        <td>:</td>
                        <td><?php echo !empty($accompany) ? count($accompany) . ' Orang' : '-'; ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="height:20px;"></div>

    <!-- TABLE PENGIKUT -->
    <table style="width:100%; border-collapse:collapse; border:1px solid #000; text-align:center; font-size:13px;">
        <thead style="background:#f2f2f2;">
            <tr>
                <td style="border:1px solid #000; padding:6px; width:50%; font-weight:bold;">Nama/Name</td>
                <td style="border:1px solid #000; padding:6px; width:50%; font-weight:bold;">Jabatan/Rank</td>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($accompany)): ?>
            <?php foreach ($accompany as $a): ?>
            <tr>
                <td style="border:1px solid #000; padding:6px;"><?php echo $a->name; ?></td>
                <td style="border:1px solid #000; padding:6px;"><?php echo $a->rank; ?></td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td style="border:1px solid #000; padding:20px;">-</td>
                <td style="border:1px solid #000; padding:20px;">-</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div style="height:40px;"></div>

    <!-- SIGNATURE -->
    <div style="text-align:right; margin-right:50px;">
        Jakarta,
        <?php echo !empty($crew->created_at) ? date('d F Y', strtotime($crew->created_at)) : date('d F Y'); ?><br>
        <?php echo $crew->company_name ?: 'PT. Andhika Lines'; ?><br><br><br><br><br><br>
        <div style="font-weight:bold; text-decoration:underline;">Eva Marliana</div>
        <div style="font-style:italic;">Crewing Manager</div>
    </div>

    <div style="height:50px;"></div>

    <!-- CC LIST -->
    <div style="font-size:12px;">
        <i>(Cc)</i><br>
        1. Manager Adm & Keu<br>
        2. Master <?php echo $crew->purpose ?: ''; ?><br>
        3. File
    </div>

</body>

</html>