<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Statement of Contract Acceptance</title>
</head>

<body style="font-family:'Times New Roman', serif; font-size:17px; padding:60px 70px;">

    <!-- HEADER -->
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="width:90px; vertical-align:top;">
                <img src="./assets/img/Logo_Andhika_2017.jpg" style="width:80px;">
            </td>

            <td style="text-align:center; vertical-align:middle;">
                <div style="font-size:15px; font-weight:bold; margin-top:3px;">
                    STATEMENT OF CONTRACT ACCEPTANCE
                </div>
            </td>

            <td style="width:170px; text-align:right; vertical-align:top;">
                <div style="font-size:11px; font-weight:bold;">SRPS LICENSE NO:</div>
                <div style="font-size:10px;">SIUKAK 236.121 - R Tahun 2025</div>

                <div style="margin-top:5px;">
                    <img src="./assets/img/Bureau_Veritas_Logo.jpg  " style="width:60px; margin-right:3px;">
                    <img src="./assets/img/Iso.jpg" style="width:60px;">
                </div>
            </td>
        </tr>
    </table>

    <!-- BODY -->
    <div style="margin-top:25px;">

        <p style="text-align:justify;">
            I am the undersigned freely accept on these articles in the Employment Contract that has:
            <br>
            <i>Saya yang bertanda tangan di bawah ini telah menerima pasal-pasal dalam kontrak kerja yang telah:</i>
        </p>

        <ol style="padding-left:45px; line-height:1.45; margin-top:0;">
            <li style="margin-bottom:12px;">
                Reviewed the terms and condition of the employment contract, and
                <br><i>saya mempelajari syarat dan kondisi dalam kontrak tersebut, dan</i>
            </li>

            <li>
                Well briefed on the terms and condition of the employment contract
                <br><i>mendapat penjelasan dengan baik mengenai syarat dan kondisi kontrak kerja tersebut.</i>
            </li>
        </ol>

    </div>

    <!-- CREW DATA -->
    <table style="width:100%; margin-top:25px; font-size:17px;">
        <tr>
            <td style="width:180px;">Name<br><i>Nama</i></td>
            <td>: <b><?php echo $crew->nama_crew ?></b></td>
        </tr>
        <tr>
            <td>D O B<br><i>Tanggal Lahir</i></td>
            <td>: <b><?php echo $crew->tanggal_lahir ?></b></td>
        </tr>
        <tr>
            <td>Rank<br><i>Jabatan</i></td>
            <td>: <b><?php echo $crew->nama_rank ?></b></td>
        </tr>
        <tr>
            <td>Certificate<br><i>Ijazah</i></td>
            <td>: <b><?php echo $crew->serpel ?></b></td>
        </tr>
    </table>

    <p style="margin-top:30px; text-align:justify;">
        If I deny the above statement, I am willing to pay an indemnity of which have been issued by the company.<br>
        <i>Jika saya menyangkal pernyataan di atas, saya bersedia membayar ganti rugi yang telah dikeluarkan oleh
            perusahaan.</i>
    </p>

    <p style="margin-top:15px; text-align:justify;">
        I hereby confirm the above contained herein is correct, without compulsion.<br>
        <i>Demikian pernyataan ini saya buat dengan sebenarnya tanpa paksaan dari pihak lain.</i>
    </p>

    <p style="margin-top:25px;">
        Thank you.<br>
        <i>Terima kasih.</i>
    </p>

    <p style="margin-top:10px;">
        Jakarta, <b><?php echo $today ?></b>
    </p>

    <table style="width:100%; margin-top:40px;">
        <tr>
            <td style="width:50%; text-align:center;">
                Your Sincerely<br><i>Hormat Kami</i>
            </td>
            <td style="width:50%; text-align:center;">
                Acknowledged By<br><i>Mengetahui</i>
            </td>
        </tr>
    </table>

    <!-- SIGNATURE AREA -->
    <table style="width:100%; margin-top:80px;">
        <tr>
            <td style="width:50%; text-align:center;">
                <b><?php echo $crew->nama_crew ?></b><br>
                Seafarer
            </td>

            <td style="width:50%; text-align:center;">
                <b>EVA MARLIANA</b><br>
                Crew Manager
            </td>
        </tr>
    </table>

</body>

</html>