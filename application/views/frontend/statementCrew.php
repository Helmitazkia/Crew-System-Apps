<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Surat Pernyataan</title>
</head>

<body
    style="background:#fff; padding:20px 30px; font-family:'Times New Roman', serif; font-size:14px; line-height:1.5;">

    <h2 style="text-align:center; margin:0 0 20px 0; font-weight:bold; text-decoration:underline;">
        SURAT PERNYATAAN
    </h2>

    <p>Yang bertanda tangan di bawah ini:</p>

    <table style="width:100%; margin-bottom:15px; font-size:14px;">
        <tr>
            <td style="width:200px;">Nama</td>
            <td>: <?php echo $crew->fullname; ?></td>
        </tr>
        <tr>
            <td>Tempat & tgl. Lahir</td>
            <td>: <?php echo $crew->place_of_birth . ", " . $crew->date_of_birth; ?></td>
        </tr>
        <tr>
            <td>Jabatan / Nama Kapal</td>
            <td>: <?php echo $crew->rankname . " / " . $crew->vesselnm; ?></td>
        </tr>
        <tr>
            <td>No. Passport</td>
            <td>: <?php echo $crew->passport_no; ?></td>
        </tr>
    </table>

    <p>Dengan ini menyatakan sebagai berikut:</p>

    <p>Masa kerja di atas kapal dengan jabatan tersebut di atas berdasarkan Perjanjian Kerja Laut (PKL)
        dibuat antara saya dan PT. Andhini Eka Karya Sejahtera (selanjutnya disebut <b>Perusahaan</b>) tanggal
        tanggal .................adalah selama <?php echo $crew->duration; ?> Bulan. Namun saya memberi hak penuh
        kepada
        Perusahaan untuk menentukan pelabuhan tempat diturunkan (sign off) dari atas kapal dalam waktu
        1 bulan sebelum atau sesudah berakhirnya masa PKL.</p>

    <p>Selama masa PKL, saya bersedia untuk tunduk dan patuh pada setiap ketentuan yang dikeluarkan oleh
        Perusahaan termasuk tetapi tidak terbatas: ketentuan jam kerja di atas kapal berdasarkan
        perundang-undangan yang berlaku disesuaikan dengan kegiatan operasional kapal yang ditetapkan oleh
        Nahkoda kapal dan/atau oleh Perusahaan.</p>

    <p>Saya setuju menerima gaji sebagaimana disebutkan dalam PKL dengan prosedur pembayaran sesuai ketentuan
        yang berlaku di Perusahaan dan perhitungan gaji dimulai sejak tanggal bekerja di atas kapal (sign on)
        dan akan berakhir sejak tanggal turun (sign off) dari kapal.</p>

    <p>Saya setuju menerima uang cuti (leave pay) yang besarnya ditentukan oleh Perusahaan dan
        pembayarannya dilakukan setelah turun dari kapal dan melaporkan diri ke Perusahaan sesuai dengan ketentuan.</p>

    <p>Saya bersedia dan tidak akan melakukan penuntutan di bidang keuangan ataupun lainnya, apabila Perusahaan
        memutuskan PKL dan/atau menurunkan (sign off) Saya dari kapal, dengan alasan sebagai berikut:<br>
        1. Secara tertulis Atasan menyatakan Saya: tidak cakap, berkelakuan buruk, lalai, tidak patuh, atau
        melanggar peraturan perusahaan;<br>
        2. Komplain tertulis dari atasan, pemilik kapal, pemilik barang, principal atau pihak ketiga lainnya
        yang mempengaruhi usaha/bisnis Perusahaan.</p>

    <p>Saya berjanji akan mematuhi dan siap sedia dipindahkan ke kapal lain dengan dibuatkan PKL yang baru tanpa
        mempengaruhi masa kerja PKL ini. Bila saya menolak, saya siap menerima konsekuensi sesuai ketentuan Perusahaan.
    </p>

    <p>Apabila Saya diturunkan dari kapal dan/atau diputuskan PKL karena alasan pada butir 6 di atas, maka saya
        bersedia dan berjanji membayar biaya pemulangan sampai di tempat saya dipekerjakan ditambah biaya
        pengurusan dan pengiriman pengganti Saya.</p>

    <p>Apabila secara sepihak atas permintaan sendiri saya mengakhiri masa PKL, maka saya bersedia memberikan
        tenggang waktu paling sedikit 1 bulan dan bersedia membayar biaya-biaya sesuai ketentuan.</p>

    <p>Demikian pernyataan ini dibuat dalam keadaan sadar tanpa paksaan dari pihak manapun.</p>

    <br><br>

    <table style="width:100%; margin-top:20px;">
        <tr>
            <td style="width:50%; text-align:center;">
                Saksi I<br><br><br><br><br>
                (.......................................)
            </td>
            <td style="width:50%; text-align:center;">
                Jakarta, <?php echo $today; ?><br>
                Yang membuat pernyataan<br><br><br><br>
                (<?php echo $crew->fullname; ?>)
            </td>
        </tr>
        <tr>
            <td style="text-align:center;">
                Saksi II<br><br><br><br><br>
                (.......................................)
            </td>
            <td style="text-align:center;">
                <br><br><br><br><br>
                Meterai 10000
            </td>
        </tr>
    </table>

    <hr style="margin:35px 0;">

    <h3 style="text-align:center; margin-bottom:5px; text-decoration:underline; font-weight:bold;">
        DAFTAR PELANGGARAN & TINDAKAN DISIPLIN
    </h3>

    <table style="width:100%; border-collapse:collapse; font-size:14px;">
        <tr>
            <td style="width:60%; padding:10px; border:1px solid #000; font-weight:bold;">Pelanggaran Hukum:</td>
            <td style="width:40%; padding:10px; border:1px solid #000; font-weight:bold; text-align:center;">Tindakan
                Disiplin</td>
        </tr>

        <?php
        $pelanggaran = array(
            array(
                "Pelanggaran undang-undang Republik Indonesia, Negara Bendera Kapal atau Negara Pelabuhan di mana Kapal berada mengenai penyelundupan barang-barang, memiliki bahan porno, menggunakan atau menjual-belikan obat bius atau menjual-belikan senjata api, atau melanggar setiap undang-undang yang menyebabkan keterlambatan Kapal.",
                "Pemecatan"
            ),
            array(
                "Pernyataan tidak benar kepada pejabat bea cukai.",
                "Pelanggaran Pertama: Peringatan<br>Pelanggaran Kedua: Pemecatan"
            ),
            array(
                "Pelanggaran undang-undang yang sifatnya ringan.",
                "Sesuai Kebijaksanaan Nakhoda"
            ),
            array(
                "Desersi: meninggalkan tugas atau menghasut orang lain meninggalkan tugas.",
                "Pemecatan"
            ),
            array(
                "Lalai dalam tugas jaga sehingga mengakibatkan kapal tidak layak laut.",
                "Pemecatan"
            ),
            array(
                "Meninggalkan waktu tugas jaga tanpa pengganti yang diberi kuasa oleh Kepala Bagian, tidur selama tugas jaga, atau berjaga di bawah pengaruh alkohol atau obat bius.",
                "Pemecatan"
            ),
            array(
                "Meninggalkan kapal tanpa izin Nakhoda atau Kepala Bagian.",
                "Pemecatan"
            ),
            array(
                "Menolak bekerja lembur sebagaimana diinstruksikan oleh Kepala Bagian atau wakilnya, kecuali alasan sakit yang diterima baik oleh Kepala atau wakil Kepala Bagian.",
                "Pemecatan"
            ),
            array(
                "Ketidakmampuan untuk berjaga disebabkan mabuk.",
                "Pelanggaran Pertama: Peringatan<br>Pelanggaran Kedua: Pemecatan"
            ),
            array(
                "Menolak untuk mentaati perintah sah dari atasan, atau menghasut orang lain untuk melakukan hal tersebut.",
                "Pemecatan"
            ),
            array(
                "Memukul atau berusaha memukul rekan pelaut atau menghasut orang lain untuk melakukan hal tersebut.",
                "Pemecatan"
            ),
            array(
                "Berkelakuan tidak patuh pada atasan atau menghasut orang lain untuk berkelakuan tidak patuh.",
                "Pemecatan"
            ),
            array(
                "Membawa seorang tamu ke kapal tanpa izin Nakhoda. Bagi yang bertugas jaga (Jurumudi/Duty Officer), tidak mengidentifikasi setiap orang yang berkunjung ke kapal.",
                "Pemecatan"
            ),
            array(
                "Ketinggalan kapal atau tidak kembali ke kapal sebagaimana diperintahkan oleh Nakhoda atau wakilnya.",
                "Pemecatan"
            ),
            array(
                "Setiap pelanggaran atas aturan-aturan dalam lampiran yang mengakibatkan keterlambatan kapal.",
                "Pemecatan"
            ),
            array(
                "Pencurian atau percobaan pencurian, merusak dengan sengaja, atau menimbulkan kerusakan pada harta perusahaan atau orang lain.",
                "Pemecatan"
            ),
            array(
                "Tidak memenuhi kewajiban sesuai jabatannya yang mengakibatkan kerusakan atau cedera pada kapal, anak buah, penumpang, atau muatan.",
                "Kebijaksanaan Perusahaan"
            ),
            array(
                "Perbuatan melanggar peraturan atau tindakan yang merusak nama baik kapal atau perusahaan baik di kapal maupun di darat.",
                "Pelanggaran Pertama: Peringatan<br>Pelanggaran Kedua: Pemecatan"
            ),
            array(
                "Tidak mampu dan/atau tidak sesuai dengan standar perusahaan dalam melaksanakan tugas jabatan atau perintah yang diberikan oleh atasan.",
                "Pemecatan"
            ),
            array(
                "Dengan sengaja membuat pernyataan atau laporan yang tidak benar untuk keuntungan pribadi atau orang lain.",
                "Pemecatan"
            ),
            array(
                "Penggelapan atau penggunaan tidak benar dana perusahaan atau barang-barang kapal.",
                "Pemecatan"
            ),
            array(
                "Menyerang atau mencoba menyerang atasan dengan kata-kata dan/atau perbuatan.",
                "Pemecatan"
            )
        );


        foreach ($pelanggaran as $p) {
            echo "
            <tr>
                <td style='padding:10px; border:1px solid #000;'>{$p[0]}</td>
                <td style='padding:10px; border:1px solid #000; text-align:center;'>{$p[1]}</td>
            </tr>";
        }
        ?>
    </table>

</body>

</html>