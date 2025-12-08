<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Statement / Pernyataan</title>
</head>

<body style="font-family: Arial, sans-serif; font-size: 13px; margin: 40px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <table style="width:100%; border-collapse:collapse; margin-bottom:10px;">
            <tr>
                <td style="width:80px; vertical-align:top;">
                    <img src="<?php echo base_url('assets/img/Logo Andhika 2017.jpg'); ?>" alt="Logo"
                        style="width:80px; height:auto;">
                </td>

                <td style="text-align:center; vertical-align:middle;">
                    <div style="font-size:15px; font-weight:bold;">STATEMENT/<i>PERNYATAAN</i><br>DATA BANK</div>
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
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 15px;">
        <tr>
            <td style="border: 1px solid #000; padding: 6px; width: 35%; font-weight: bold;">STATUS DATA BANK</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->status_data_bank; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">NAMA</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->fullname; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">NPWP</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->npwp; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">ALAMAT RUMAH</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->address; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">NO. TELP</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->phone; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">NO. TELP DARURAT</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->emergency_phone; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">HUBUNGAN</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->relation; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">NAMA BANK / CABANG / UNIT</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->bank_name; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">NO. REKENING</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->bank_account; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">PEMILIK REKENING</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->account_name; ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">ALAMAT BANK</td>
            <td style="border: 1px solid #000; padding: 6px;"><?php echo $crew->bank_address; ?></td>
        </tr>
    </table>

    <div style="font-size: 12px; line-height: 1.5; margin-bottom: 30px;">
        <b>Ketentuan</b><br>
        1. Perusahaan tidak bertanggung jawab atas keterlambatan pengiriman yang disebabkan oleh prosedur Bank yang
        ditunjuk atau kesalahan penulisan data Bank oleh Crew yang bersangkutan.<br>
        2. Crew harus melampirkan fotocopy rekening Bank yang ditunjuk.<br>
        3. Rekening Bank yang ditunjuk ini berlaku selama kontrak kerja dan tidak dapat diganti tanpa persetujuan dari
        Crew Manager dengan menyebutkan alasan yang jelas.
        <br><br>
        Saya menyetujui semua ketentuan yang berlaku dan mengakui formulir ini telah diisi dengan benar serta menerima
        semua konsekuensi dari isi form ini.
    </div>

    <table style="width:100%; margin-top:30px; font-size:13px; border-collapse:collapse;">
        <tr>
            <td style="width:50%; vertical-align:top; text-align:left;">
                Jakarta, <?php echo date('d F Y', strtotime($crew->created_at)); ?><br><br><br><br><br><br><br>
                <b><u><?php echo $crew->fullname; ?></u></b><br>
                <span style="font-size: 12px;">Crew</span>
            </td>
            <td style="width:50%; vertical-align:top; text-align:right;">
                Mengetahui :<br><br><br><br><br><br><br>
                <b><u>Eva Marliana</u></b><br>
                <span style="font-size: 12px;">Crew Executive</span>
            </td>
        </tr>
    </table>


</body>

</html>