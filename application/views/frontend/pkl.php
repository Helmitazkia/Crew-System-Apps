<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Seafarer Employment Agreement</title>
    <!-- Minimal styles; most styles inline to keep rendering konsisten in modal/PDF engines -->
    <style>
    @page {
        margin: 40px;
    }

    html,
    body {
        margin: 0;
        padding: 0;
        background: white;
    }

    .page {
        box-sizing: border-box;
        width: 100%;
        padding: 40px;
        font-family: "Times New Roman", Times, serif;
        line-height: 1.5;
        color: #000;
    }

    .center {
        text-align: center;
    }

    .bold {
        font-weight: bold;
    }

    .underline {
        text-decoration: underline;
    }

    .muted-italic {
        font-style: italic;
        font-size: 10px;
    }

    .section-title {
        margin-top: 20px;
        margin-bottom: 6px;
    }

    .two-col {
        width: 100%;
        border-collapse: collapse;
        margin-top: 30px;
    }

    .two-col td {
        vertical-align: top;
        padding: 4px;
    }

    .data-row {
        margin: 4px 0;
    }

    .small-table {
        width: 80%;
        margin: 10px auto;
        border-collapse: collapse;
        font-size: 11px;
    }

    .small-table td {
        padding: 6px 8px;
    }

    @media print {
        .page {
            padding: 15mm;
        }
    }
    </style>
</head>

<body>
    <div class="page" id="page-1">
        <h2 class="center bold" style="margin:0; font-size:11px;">SEAFARER EMPLOYMENT AGREEMENT</h2>
        <h3 class="center" style="margin:6px 0 8px 0; font-size:11px;">Between</h3>

        <p id="txtCompanyName" class="center bold" style="font-size:11px; margin:0;">
            <b><?php echo $crew->company_name; ?></b>
        </p>

        <p class="center" style="font-size:11px; margin-top:8px;">And</p>

        <p class="center bold" style="font-size:11px; margin-top:2px;">An Indonesian Citizen</p>

        <div style="height:12px;"></div>

        <p style="font-size:11px; margin:8px 0;">
            Today on ............................................ have came to me
            ..................................................................... as Head of Section.<br>
            <span class="muted-italic">Pada hari ini.......................................telah datang kepada
                saya..................................................................Pejabat Penyijil
                Seaworthiness,</span>
        </p>

        <p style="font-size:11px; margin:8px 0;">
            for and on behalf of <b>THE HARBOUR MASTER and PORT AUTHORITY of TG. PRIOK</b>.<br>
            <span class="muted-italic">dengan ini mewakili atas nama <b>KANTOR KESYAHBANDARAN dan OTORITAS PELABUHAN TG.
                    PRIOK.</b></span>
        </p>

        <p style="font-size:11px; margin:8px 0;">
            Mrs. <b>EVA MARLIANA</b> as <b>CREWING MANAGER</b> domicile at Menara Kadin Indonesia Floor 20th unit D
            Jl. HR Rasuna Said Blok X-5 Kav.2-3 Kuningan Jakarta 12950 Indonesia.<br>
            <span class="muted-italic">Saudari EVA MARLIANA jabatan CREWING MANAGER berdomisili di Menara Kadin
                Indonesia Lantai 20 Rasuna Said Blok X-5 Kav.2-3 Kuningan Jakarta 12950 Indonesia,</span>
        </p>

        <p style="font-size:11px; margin:8px 0;">
            who state in terms of acting for and on behalf of the shipping company
            <b><?php echo $crew->company_name; ?></b>,
            domicile at Menara Kadin Indonesia Floor 20th unit D Jl. HR Rasuna Said Blok X-5 Kav.2-3 Kuningan Jakarta
            12950 Indonesia.<br>
            <span class="muted-italic">dalam hal ini bertindak untuk dan atas nama perusahaan pelayaran
                <b><?php echo $crew->company_name; ?></b> berdomisili di Rasuna Said Blok X-5 Kav.2-3 Kuningan Jakarta
                12950 Indonesia,</span>
        </p>

        <p style="font-size:11px; margin:8px 0;">
            hereinafter referred as the <b>COMPANY</b> and a person named hereinafter called the <b>SEAFARER</b>.<br>
            <span class="muted-italic">selanjutnya disebut PERUSAHAAN dan seorang Bernama, selanjutnya disebut
                Pelaut</span>
        </p>

        <div id="dataPerson" style="margin-top:10px; font-size:11px;">
            <table style="width:100%; border-collapse:collapse; font-size:11px;">
                <tr>
                    <td style="width:200px; white-space:nowrap;">Name / Nama</td>
                    <td>: <b><?php echo strtoupper($crew->fullname); ?></b></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Date of Birth / Tanggal Lahir</td>
                    <td>: <?php echo date('d M Y', strtotime($crew->dob)); ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Place of Birth / Tempat Lahir</td>
                    <td>: <?php echo $crew->pob; ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Seafarer Code / Kode Pelaut</td>
                    <td>: <?php echo $crew->seafarer_code ?: '-'; ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Home Address / Alamat Rumah</td>
                    <td>: <?php echo $crew->paddress; ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Passport No / No. Paspor</td>
                    <td>: <?php echo $crew->passportno ?: '-'; ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Seaman Book No / No. Buku Pelaut</td>
                    <td>: <?php echo $crew->seamanbookno ?: '-'; ?></td>
                </tr>
            </table>
        </div>

        <p style="font-size:11px; margin-top:12px;">
            Whereby the following terms and conditions of employment are mutually agreed upon.<br>
            <span class="muted-italic">Dalam hal mana, syarat-syarat serta kondisi pengerjaan berikut telah
                disepakati.</span>
        </p>

        <p class="center bold underline section-title" style="margin-top:22px; font-size:11px;">ARTICLE I : ENGAGEMENT
            <br> PASAL I : PENGERJAAN
        </p>

        <p style="font-size:11px;">
            The Company will engage the Seafarer in accordance with this Seafarer Employment Agreement, its
            enclosure and amendments (if any), and to be executed with utmost good faith.<br>
            <span class="muted-italic">Perusahaan akan mempekerjakan Pelaut sesuai dengan Perjanjian Kerja Pelaut ini
                dengan lampiran-lampiran dan perubahan-perubahan (bila ada), dan akan dilaksanakan dengan itikad yang
                sebaik-baiknya.</span>
        </p>

        <p style="font-size:11px;">
            During the period this Seafarer Employment Agreement, the Seafarer shall be employed by the Company.<br>
            <span class="muted-italic">Selama masa berlakunya Perjanjian Kerja Pelaut ini. Pelaut akan dipekerjakan oleh
                Perusahaan.</span>
        </p>

        <div style="margin-top:10px; font-size:11px;">
            <table style="width:100%; border-collapse:collapse; font-size:11px;">
                <tr>
                    <td style="width:200px; white-space:nowrap;">On board the / di atas Kapal</td>
                    <td>: <b><?php echo $crew->vessel_name ?: '-'; ?></b></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Flag / Bendera</td>
                    <td>: <?php echo strtoupper($crew->flag) ?: '-'; ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">IMO No</td>
                    <td>: <?php echo $crew->imo ?: '-'; ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">GRT / HP</td>
                    <td>: <?php echo $crew->grt_hp ?: '-'; ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Safety Certificate / SERKES</td>
                    <td>: <?php echo $crew->safety_cert ?: '-'; ?></td>
                </tr>
                <tr>
                    <td style="white-space:nowrap;">Certificate of Competency / SERPEL</td>
                    <td>: <?php echo $crew->competency_cert ?: '-'; ?></td>
                </tr>
            </table>
        </div>


    </div>

    <div class="page" id="page-2">
        <p class="center bold underline" style="font-size:11px; margin-top:-20px;">ARTICLE II : EFFECTIVE DATE AND
            DURATION
            OF AGREEMENT <br> PASAL II : MULAI BERLAKUNYA DAN JANGKA WAKTU
            PERJANJIAN</p>

        <p style="font-size:11px; margin-top:10px;">
            a. Effective date: this contract and all its provision shall take effect on
            .............................................................<br>
            <span class="muted-italic">Tanggal berlakunya: Perjanjian ini dan semua ketentuan-ketentuannya akan mulai
                berlaku pada tanggal .....................................</span>
        </p>

        <p style="font-size:11px;">
            b. Duration: This contract shall continue to be valid for <b><?php echo $crew->duration; ?></b>
            MONTHS unless
            terminated by either party upon 30 (thirty) days written notice to the other party.<br>
            <span class="muted-italic">Masa berlakunya: Perjanjian ini akan tetap berlaku selama
                <b><?php echo $crew->duration; ?></b> bulan atau diakhiri oleh salah satu pihak dengan
                pemberitahuan tertulis 30 (tiga puluh) hari sebelumnya kepada pihak yang lain.</span>
        </p>

        <p class="center bold underline section-title" style="margin-top:20px; font-size:11px;">ARTICLE III : WAGES AND
            OVERTIME <br> PASAL III : GAJI DAN UPAH LEMBUR
        </p>

        <p style="font-size:11px; margin-top:10px;">
            During the period of this Seafarer Employment Agreement, the Seafarer shall be employed by the
            Company in the capacity of <span><?php echo $crew->applyfor ?: '-'; ?></span>.<br>
            <span class="muted-italic">Selama masa berlakunya Perjanjian Kerja Pelaut ini, Pelaut akan dipekerjakan oleh
                Perusahaan dalam jabatan sebagai <span><?php echo $crew->applyfor ?: '-'; ?></span></span>
        </p>

        <p style="font-size:11px; margin-top:8px;">
            and be paid a monthly basic wages of Rp
            <b><?php echo number_format($salary['basic'], 0, ',', '.'); ?></b>.<br>
            <span class="muted-italic">dan akan dibayarkan gaji pokok bulanan sebesar Rp
                <?php echo number_format($salary['basic'], 0, ',', '.'); ?></span><br>
            Fix Overtime Rp <b><?php echo number_format($salary['fix'], 0, ',', '.'); ?></b>.<br>
            <span class="muted-italic">upah lembur Rp
                <?php echo number_format($salary['fix'], 0, ',', '.'); ?></span><br>
            Leave Pay Rp <b><?php echo number_format($salary['leave'], 0, ',', '.'); ?></b>.<br>
            <span class="muted-italic">Uang pengganti hari libur Rp
                <?php echo number_format($salary['leave'], 0, ',', '.'); ?></span><br>
            Total wages Rp <b><?php echo number_format($salary['total'], 0, ',', '.'); ?></b>.<br>
            <span class="muted-italic">Total gaji Rp <?php echo number_format($salary['total'], 0, ',', '.'); ?></span>
        </p>

        <p class="center bold underline section-title" style="margin-top:20px; font-size:11px;">ARTICLE IV : ALLOTMENT
            <br> PASAL IV : UANG DELEGASI
        </p>

        <p style="font-size:11px;">
            1. The Seafarer covered by this Seafarer Employment Agreement should file, either with the
            Company or the Master of the vessel a signed allotment not to be applied against a minimum of 80% of the
            accrued basic wages.<br>
            <span class="muted-italic">Pelaut yang dilindungi oleh Perjanjian Kerja Pelaut ini harus mengajukan baik
                kepada Perusahaan atau kepada Nakhoda kapal, sesuai nota delegasi yang ditandatangani yang akan
                diperhitungkan dengan upah sebesar paling sedikit 80% dari upah pokok sebulan.</span>
        </p>

        <p style="font-size:11px;">
            2. The Company shall thereupon arrange to remit a monthly allotment payable in IDR or its equivalent in
            local currency to the person named in the allotment note.<br>
            <span class="muted-italic">Perusahaan akan mengatur pengiriman delegasi bulanan dalam mata uang rupiah atau
                jumlah yang sama nilainya dalam mata uang setempat, kepada orang yang namanya disebut dalam nota
                delegasi.</span>
        </p>

        <p class="center bold underline section-title" style="margin-top:20px; font-size:11px;">ARTICLE V : WORKING <br>
            PASAL V : JAM KERJA
            HOURS
        </p>

        <p style="font-size:11px;">
            <b>1. Day Worker / Pekerjaan Harian</b><br>
            The hours of work day workers shall be 8 (eight) hours per day Monday through Friday preferably between 8 AM
            to 5 PM, and 4 (four) hours per day on Saturday between 8 AM to 12 Noon.<br>
            <span class="muted-italic">Jam kerja bagi pekerja harian adalah 8 (delapan) jam sehari dimulai Senin sampai
                dengan Jumat, sebaiknya antara 8 pagi sampai jam 5 sore, dan 4 (empat) jam sehari pada hari Sabtu yang
                sebaiknya antara jam 8 pagi sampai jam 12 tengah hari.</span>
        </p>

        <p style="font-size:11px;">
            <b>2. Regular Watch / Jaga Biasa</b><br>
            <b>Deck Department and Engine Department</b><br>
            In port, crew members of these departments shall stand their regular watches as required by the Master of
            the vessel. Overtime rates shall apply for watches stood of work performed in port on Saturday afternoon,
            Sunday and Holidays. At sea, crew members of these departments shall stand their regular watches as required
            by the Master of the vessel.<br>
            <span class="muted-italic">Di Pelabuhan awak kapal wajib menjalankan tugas jaga biasa sesuai perintah
                Nakhoda kapal. Upah lembur akan diberlakukan untuk jaga yang dilakukan atau pekerjaan yang dilaksanakan
                di pelabuhan pada hari Sabtu sesudah tengah hari, pada hari Minggu dan Hari Raya Resmi. Di laut, awak
                kapal bagian ini wajib menjalankan tugas jaga biasa sesuai perintah Nakhoda kapal.</span>
        </p>

    </div>

    <div class="page" id="page-3">
        <p style="font-size:11px;">
            <b><i>3. Catering Department / Bagian Pelayanan</i></b><br>
            The working hours of Catering Department members shall be 8 (eight) hours each day in a spread preferably
            between 6 AM to 7 PM. When the crewmembers of the Catering Department are on day work, the hours of work
            shall preferably between 8 AM to 12 Noon and 1 PM to 5 PM.<br>
            <span class="muted-italic">Jam kerja awak kapal bagian pelayanan adalah 8 (delapan) jam sehari sebaiknya di
                rentang antara jam 6 pagi sampai jam 7 sore. Bila awak kapal bagian pelayanan bekerja harian, jam kerja
                sebaiknya adalah jam 8 pagi sampai jam 12 tengah hari dan jam 1 siang sampai jam 5 sore.</span>
        </p>
        <p class="center bold underline section-title" style="margin-top:20px; font-size:11px;">ARTICLE VI : REST HOUR
            <br> PASAL VI : JAM ISTIRAHAT
        </p>

        <p style="font-size:11px;">
            Each Seafarer shall have a minimum of 10 hours rest in any 24 hour period may be divided into no more than 2
            periods, one of which shall be at least 6 hours in length, and the interval between consecutive periods of
            rest shall not exceed 14 hours.<br>
            <span class="muted-italic">Setiap Pelaut harus memiliki minimal 10 jam istirahat dalam setiap 24 jam dapat
                dibagi menjadi tidak lebih dari 2 periode, salah satunya harus setidaknya 6 jam, dan interval antara
                periode istirahat berturut-turut tidak boleh melebihi 14 jam.</span>
        </p>

        <p class="center bold underline section-title" style="margin-top:20px; font-size:11px;">ARTICLE VII : EXCESS
            BAGGAGE <br> PASAL VII : KELEBIHAN BARANG BAWAAN
        </p>

        <p style="font-size:11px;">
            While traveling to or from a vessel under this Seafarer Employment Agreement, the Seafarer shall be
            responsible for any expenses caused by excess baggage beyond the limitation imposed by the Transportation
            Company used for travel.<br>
            <span class="muted-italic">Ketika dalam perjalanan ke atau dari kapal dibawah Perjanjian Kerja Pelaut ini,
                Pelaut harus bertanggung jawab atas biaya yang timbul karena kelebihan barang bawaan di atas batas
                ketentuan yang ditetapkan oleh Perusahaan Pengangkutan yang dipergunakan untuk melakukan
                perjalanan.</span>
        </p>

        <p class="center bold underline section-title" style="margin-top:20px; font-size:11px;">ARTICLE VIII :
            DISCIPLINE <br> PASAL VIII : DISIPLIN
        </p>

        <p style="font-size:11px;">
            a. The seafarer, while employed on board a vessel of the Company, shall comply with all lawful orders of his
            superiors and division heads and will obey all Company's rules. Recognizing the necessity for discipline on
            board Company vessel and at the same time in order to protect a Seafarer against unfair treatment, the
            Company agrees to post on the bulletin board of each vessel a list of rules which shall constitute reason
            for which Seafarer may be discharged without further notice. The rules shall be written in such a way to
            enable the Seafarer to understand.<br>
            <span class="muted-italic">Pelaut selama dipekerjakan diatas kapal milik Perusahaan, wajib mentaati setiap
                perintah yang sah dari atasannya dan kepala bagiannya serta akan mentaati peraturan Perusahaan. Mengakui
                pentingnya disiplin diatas kapal milik Perusahaan pada saat yang sama demi melindungi Pelaut terhadap
                tindakan yang tidak adil. Perusahaan setuju untuk menempelkan dikapal suatu peraturan yang menetapkan
                pemberitahuan pendahuluan. Peraturan ini harus tertulis sedemikian rupa sehingga memungkinkah bagi
                Pelaut untuk dapat dimengerti.</span>
        </p>

        <p style="font-size:11px;">
            b. In accordance with ANNEX 1 (Discipline of Working Regulation).<br>
            <span class="muted-italic">Sesuai dengan ANNEX 1( disiplin peraturan kerja ).</span>
        </p>

        <p style="font-size:11px;">
            c. For other offence not on the posted list, Seafarer shall not be discharged without first having been
            notified in writing that a repetition on the offence will make him liable to dismissal.<br>
            <span class="muted-italic">Untuk pelanggaran lain yang tidak dimuat didalam daftar, Pelaut tidak akan
                dipecat tanpa sebelumnya diberitahu secara tertulis bahwa pengulangan pelanggaran tersebut akan
                membuatnya dapat dipecat.</span>
        </p>
        <p class="center bold underline section-title" style="margin-top:0; font-size:11px;">ARTICLE IX : REPATRIATION
            <br>
            PASAL IX : PEMULANGAN
        </p>


        <p style="font-size:11px;">
            On termination of employment, the Seafarer shall be paid for or provided with transportation of kind class,
            as determined by the Company, to return to the place where he has been employed/place of engagement (if
            immigration laws permitting), or to the airport or seaport nearest the Seafarer's home, to be determined by
            the Company in its sole discretion, and he shall be paid his wages (not to include overtime or travel time)
            up to and including his arrival in Jakarta.
            <span class="muted-italic">Pada saat pengakhiran pengerjaan, Pelaut akan dibayarkan atau diberikan sarana
                angkutan sesuai jenis dan kelas yang ditentukan oleh Perusahaan, untuk kembali ketempat dimana dia
                diterima untuk dipekerjakan (bila peraturan keimigrasian mengijinkan) atau Bandar udara atau pelabuhan
                laut terdekat dari tempat tinggal Pelaut sesuai yang ditentukan Perusahaan, dan kepadanya akan
                dibayarkan upahnya (tidak termasuk upah lembur atau waktu perjalanan), sampai dengan tanggal tiba di
                bandar udara atau pelabuhan terdekat.</span>
        </p>
    </div>

    <div class="page" id="page-4">
        <p class="center bold underline section-title" style="margin-top:-50px; font-size:11px;">ARTICLE X : INSURANCE
            <br>
            PASAL X : PERTANGGUNGAN
        </p>


        <p style="font-size:11px;">
            1. The Company shall be responsible for and shall bear any and all hospitalization and medical expenses
            incurred in respect of any seafarer who becomes ailing or injured on board of a vessel as per Government
            Regulation no. 7 year 2000 regulations.<br>
            <span class="muted-italic">Perusahaan wajib menanggung biaya perawatan dan pengobatan pelaut yang sakit dan
                cidera selama berada diatas kapal sesuai dengan Peraturan Pemerintah no. 7 tahun 2000.</span>
        </p>

        <p style="font-size:11px;">
            2. Sick or injured seafarer due to any accident such that they will no longer be able to return to work or
            have to be hospitalized, in addition to payment for any and all hospitalization and medical costs, the
            employer shall also be obliged to pay full salary if such ship crews are still on board or taken care on
            board of a vessel.<br>
            <span class="muted-italic">Pelaut yang sakit atau cedera akibat kecelakaan sehingga tidak dapat bekerja atau
                harus dirawat, perusahaan wajib membiayai perawatan dan pengobatan juga gaji penuh jika pelaut tetap
                berada atau dirawat di kapal.</span>
        </p>

        <p style="font-size:11px;">
            3. For loss and/or damage of crew's effects, due to the ship accident, the Company shall cover as Flag State
            Regulation.<br>
            <span class="muted-italic">Besar ganti rugi atas kehilangan barang-barang milik pelaut akibat tenggelam atau
                terbakar sesuai dengan peraturan dari negara bendera.</span>
        </p>

        <p style="font-size:11px;">
            4. Accident / Kecelakaan<br>
            A Seafarer who suffered permanent 100% disability resulting of an accident during his contract period will
            be entitled to compensation a minimum of Rp. 500.000.000.<br>
            <span class="muted-italic">Pelaut yang mengalami kecelakaan kerja didalam tugasnya berhak menerima
                pembayaran pertanggungan bila kecelakaan berakibat cacat tetap yang menyebabkan hilangnya kemampuan
                kerja pada kedudukannya yang semula sejumlah minimum Rp. 500.000.000.</span>
        </p>

        <p style="font-size:11px;">
            In case of permanent partial disability the amount of the compensation will be calculated according the
            following table:<br>
            <span class="muted-italic">Dalam hal cacat tetap sebagian jumlah pembayaran pertanggungan akan dihitung
                sesuai dengan tabel berikut:</span>
        </p>

        <table class="small-table" style="border-collapse:collapse;">
            <tr>
                <td>Loss of one finger of any hand / Kehilangan satu jari tangan</td>
                <td style="text-align:right;">10%</td>
            </tr>
            <tr>
                <td>Loss of one hand / Kehilangan satu lengan</td>
                <td style="text-align:right;">40%</td>
            </tr>
            <tr>
                <td>Loss of both hand / Kehilangan kedua lengan</td>
                <td style="text-align:right;">100%</td>
            </tr>
            <tr>
                <td>Loss of one palm / Kehilangan satu telapak tangan</td>
                <td style="text-align:right;">30%</td>
            </tr>
            <tr>
                <td>Loss of both palm / Kehilangan kedua telapak tangan</td>
                <td style="text-align:right;">80%</td>
            </tr>
            <tr>
                <td>Loss of one finger of any foot / Kehilangan satu jari kaki</td>
                <td style="text-align:right;">5%</td>
            </tr>
            <tr>
                <td>Loss of one leg / Kehilangan satu kaki</td>
                <td style="text-align:right;">40%</td>
            </tr>
            <tr>
                <td>Loss of two leg / Kehilangan kedua kaki</td>
                <td style="text-align:right;">100%</td>
            </tr>
            <tr>
                <td>Loss of one eye / Kehilangan satu mata</td>
                <td style="text-align:right;">30%</td>
            </tr>
            <tr>
                <td>Loss of both eyes / Kehilangan kedua mata</td>
                <td style="text-align:right;">100%</td>
            </tr>
            <tr>
                <td>Loss of hearing in one ear / Kehilangan satu telinga</td>
                <td style="text-align:right;">15%</td>
            </tr>
            <tr>
                <td>Loss of hearing in both ears / Kehilangan kedua telinga</td>
                <td style="text-align:right;">40%</td>
            </tr>
        </table>
        <p style="font-size:11px; margin-left: 20px;">
            5. Loss of life / death in service / Kematian Alami / kematian akibat kecelakaan kerja
        </p>

        <p style="font-size:11px; margin-left: 20px;">
            a. In case an accident including accident occurring whilst traveling to and from the vessel, caused the
            death of a Seafarer, his next of kin, i.e. his lawful wife and children shall receive a compensation a
            minimum of Rp. 500.000.000.<br>
            <span class="muted-italic">Dalam hal kecelakaan yang menyebabkan kematian Pelaut, ahli warisnya yang sah,
                dalam hal ini istri dan anak-anaknya akan menerima pertanggungan minimum sebesar Rp. 500.000.000.</span>
        </p>

        <p style="font-size:11px; margin-left: 20px;">
            b. The Company will make arrangements to cover also the death of Seafarer by natural cause. Such
            arrangements should cover the amount a minimum Rp. 400.000.000.<br>
            <span class="muted-italic">Perusahaan juga akan mengatur pertanggungan yang mencakup kematian Pelaut karena
                disebabkan alamiah. Pengaturan demikian harus mencakup jumlah minimum sebesar Rp 400.000.000.</span>
        </p>
    </div>

    <div class="page" id="page-5">
        <p class="center bold underline" style="font-size:11px; margin-top:-50px;">
            ARTICLE XI : TERMINATION OF EMPLOYMENT <br>
            PASAL XI : PEMUTUSAN/PENGAKHIRAN HUBUNGAN KERJA
        </p>

        <p style="font-size:11px;">
            The Company shall be entitled to terminate this agreement anytime, although without prior notice in
            following circumstances:< <span class="muted-italic">Perusahaan berhak pada setiap waktu mengakhiri hubungan
                kerja atau perjanjian
                ini, sekalipun tanpa pemberitahuan terlebih dahulu karena alasan-alasan sebagai berikut:</span>
        </p>

        <p style="font-size:11px;">
            a) The Seafarer not competent, bad attitude, negligent, not comply with the command or do other acts who
            adverse the Company.<br>
            <span class="muted-italic">Pelaut kurang cakap, berkelakuan buruk, lengah atau lalai dalam kewajiban, tidak
                patuh perintah dimaksud atau melakukan perbuatan lain yang merugikan perusahaan.</span>
        </p>

        <p style="font-size:11px;">
            b) If the Seafarer commits any act contrary to or in violation of the laws or regulations of the Republic of
            Indonesia, he shall be disembarked at the location or port where the incident occurred and handed over to
            the local authorities.<br>
            <span class="muted-italic">Bila Pelaut ternyata melakukan perbuatan-perbuatan yang bertentangan dengan hukum
                pihak atau melanggar peraturan Pemerintah Republik Indonesia, maka ia akan diturunkan di
                tempat/pelabuhan dimana peristiwa tersebut terjadi dan diserahkan kepada yang berwajib.</span>
        </p>

        <p class="center bold underline section-title" style="margin-top:18px; font-size:11px;">ARTICLE XII : HARASSMENT
            AND BULLYING <br>
            PASAL XII : PELECEHAN DAN PERUNDUNGAN
        </p>


        <p style="font-size:11px;">
            The Seafarer has a right to work in an environment free from harassment and bullying and to be treated with
            dignity and respect. Even unintentional harassment or bullying is unacceptable.<br>
            <span class="muted-italic">Pelaut berhak untuk bekerja dalam lingkungan yang bebas dari pelecehan dan
                perundungan serta diperlakukan dengan martabat dan rasa hormat. Bahkan pelecehan atau perundungan yang
                tidak disengaja pun tidak dapat diterima.</span>
        </p>

        <p style="font-size:11px;">
            The Employer/Employers Company or Agent will treat all complaints of harassment and bullying seriously and
            in strict confidence.<br>
            <span class="muted-italic">Perusahaan Pemberi Kerja/Agen Pemberi Kerja akan menangani semua keluhan terkait
                pelecehan dan perundungan dengan serius dan dalam kerahasiaan yang ketat.</span>
        </p>

        <p style="font-size:11px;">
            If a complaint cannot be resolved amicably on board by the Master and the Crew and the Vessels PIC ashore,
            then the procedure contemplated in the on board Ship Management Manual to be followed.<br>
            <span class="muted-italic">Jika keluhan tidak dapat diselesaikan secara damai di atas kapal oleh Nakhoda,
                Kru dan PIC Kapal didarat, maka procedure yang tercantum dalam Ship Management Manual di atas kapal
                harus diikuti.</span>
        </p>

        <p style="font-size:11px;">
            The Seafarer can also lodge complaint with the Vessels PICs ashore and be sent to the email ID:
            <b>aes@andhika.com</b>.<br>
            <span class="muted-italic">Pelaut juga dapat mengajukan keluhan kepada PIC Kapal di darat dan mengirimkannya
                ke : <b>aes@andhika.com</b>.</span>
        </p>

        <p style="font-size:11px;">
            Appropriate process/proceedings/enquiry will be initiated and necessary disciplinary action will be taken
            based on the result of such process.<br>
            <span class="muted-italic">Proses, penyelidikan, dan tindakan disipliner yang sesuai akan dilakukan
                berdasarkan hasil dari proses tersebut.</span>
        </p>
    </div>

    <div class="page" id="page-6">
        <p class="center bold underline section-title" style="margin-top:-50px; font-size:11px;">ARTICLE XIII : PIRACY
            OR
            ARMED ROBBERY AGAINST SHIPS <br>
            PASAL XIII : PEMBAJAKAN ATAU PERAMPOKAN BERSENJATA TERHADAP KAPAL
        </p>


        <p style="font-size:11px;">
            a) Seafarer's employment agreement (SEA) shall continue to have effect and wages shall continue to be paid
            while a seafarer is held captive on or off the ship as a result of acts of piracy or armed robbery against
            ships.<br>
            <span class="muted-italic">Perjanjian kerja pelaut (SEA) yang akan terus berlaku dan upah akan terus
                dibayarkan saat seorang pelaut ditahan di dalam atau di luar kapal sebagai akibat dari tindakan
                pembajakan atau perampokan bersenjata terhadap kapal.</span>
        </p>

        <p style="font-size:11px;">
            b) If a seafarer is held captive on or off the ship as a result of acts of piracy or armed robbery against
            ships, wages and other entitlements under the seafarers employment agreement or applicable national laws,
            shall continue to be paid during the entire period of captivity and until the seafarer is released and duly
            repatriated or, where the seafarer dies while in captivity, until the date of death as determined in
            accordance with applicable national laws or regulations.<br>
            <span class="muted-italic">Jika seorang pelaut ditahan di dalam atau di luar kapal sebagai akibat dari
                tindakan pembajakan atau perampokan bersenjata terhadap kapal, upah dan hak lainnya berdasarkan
                perjanjian kerja pelaut atau undang-undang nasional yang berlaku, akan terus dibayar selama seluruh
                periode penahanan dan sampai pelaut tersebut dibebaskan dan dipulangkan dengan semestinya atau, jika
                pelaut tersebut meninggal saat di sandera, sampai tanggal kematian sebagaimana ditentukan sesuai dengan
                hukum atau peraturan nasional yang berlaku.</span>
        </p>

        <p style="font-size:11px;">
            c) Seafarers are entitled to repatriation if they are detained on or off the ship as a result of piracy or
            armed robbery of the ship.<br>
            <span class="muted-italic">Para pelaut berhak atas pemulangan jika mereka ditahan di dalam atau di luar
                kapal sebagai akibat dari tindakan pembajakan atau perampokan bersenjata terhadap kapal.</span>
        </p>

        <p style="font-size:11px;">
            Languages in this agreement are made in the English language and the Indonesian language. In the event of
            any inconsistency or different interpretation between the English text and Indonesian text, the Indonesian
            text shall prevail and the relevant English text shall be deemed to be automatically amended to conform with
            and to make the relevant English text consistent with the relevant Indonesian text.<br>
            <span class="muted-italic">Bahasa pada perjanjian ini dibuat dalam bahasa Inggris dan bahasa Indonesia.
                Dalam hal terdapat ketidaksesuaian dan perbedaan antara teks bahasa Inggris dan teks bahasa Indonesia,
                maka teks bahasa Indonesia yang akan berlaku dan teks bahasa Inggris akan secara otomatis diubah untuk
                menyesuaikan dengan dan untuk membuat teks bahasa Inggris konsisten dengan teks bahasa Indonesia.</span>
        </p>

        <p style="font-size:11px;">
            This agreement has adopted the MLC requirements and is made in 4 (four) copies intended for the licensing of
            Ship Crew, Seafarers, Companies and Ship Master.<br>
            <span class="muted-italic">Perjanjian ini telah mengadop persyaratan MLC dan dibuat rangkap 4 (empat) yang
                diperuntukan penyijil Awak Kapal, Pelaut, Perusahaan dan Nahkoda Kapal.</span>
        </p>

        <div
            style="font-family:Inter, system-ui, -apple-system, 'Segoe UI', Roboto, 'Helvetica Neue', Arial; text-align:center; line-height:1.8; margin-top:10px; font-size:11px;">
            <p>
                In witness of the aforesaid terms and condition both parties sign this agreement this day
                .....................................<br>
                <span class="muted-italic">Sebagai kesaksian dari ketentuan dan syarat-syarat diatas, kedua belah pihak
                    menandatangani Perjanjian ini tanggal ....................................</span>
            </p>

            <table class="two-col" style="margin-top:40px; font-size:11px;">
                <tr>
                    <td style="width:50%; text-align:center; vertical-align:top; font-size:11px;">
                        <div class="bold"><?php echo $crew->company_name; ?></div>
                        <div class="bold"> (OWNER)</div>
                        <div style="margin-top:36px;"></div>
                        <br><br><br><br><br></br>
                        <b><u>(EVA MARLIANA)</u></b><br>
                        <b><?php echo $crewing_position; ?></b>
                    </td>
                    <td style="width:50%; text-align:center; vertical-align:top; font-size:11px;">
                        <div class="bold">THE SEAFARER</div>
                        <div class="bold">Pelaut</div>
                        <div style="margin-top:36px;"></div>
                        <br><br><br><br><br></br>
                        <p class="bold"><?php echo strtoupper($crew->fullname); ?></p>
                    </td>
                </tr>
            </table>

            <div style="margin-top:18px; text-align:center; font-size:11px;">
                <p class="bold">ACKNOWLEDGED by, <br> MENGETAHUI :</p>
                <br><br><br><br><br></br>
                <p>.........................................................</p>
            </div>
        </div>
    </div>

</body>

</html>