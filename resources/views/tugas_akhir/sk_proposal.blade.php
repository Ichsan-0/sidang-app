<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Keputusan</title>
    <style>
        body { font-family: "Times New Roman", serif; font-size: 11pt; line-height: 1.25; }
        .center { text-align: center; }
        .justify { text-align: justify; }
        p { margin: 3px 0; }
        ol { margin: 0; padding-left: 20px; }
        table { width: 100%; margin-top: 6px; }
        .tembusan { margin-top: 20px; }
        .ttd { margin-top: 40px; text-align: center; }
    </style>
</head>
<body>

    <div class="center" style="margin-bottom: 8px;">
    </div>
    <h4 class="center" style="margin-bottom: 8px;">
        KEPUTUSAN KETUA PROGRAM STUDI PENDIDIKAN TEKNOLOGI INFORMASI<br>
        FAKULTAS TARBIYAH DAN KEGURUAN UIN AR-RANIRY BANDA ACEH
    </h4>
    <p class="center" style="margin-bottom: 8px;">
        <b>NOMOR: {{ $sk->kode_sk }}</b>
    </p>
    <p class="center"><b>TENTANG:<br>PENGANGKATAN PEMBIMBING AWAL PROPOSAL MAHASISWA</b></p>
    <p class="center"><b>DENGAN RAHMAT TUHAN YANG MAHA ESA</b></p>
    <p class="justify"><b>KETUA PROGRAM STUDI PENDIDIKAN TEKNOLOGI INFORMASI</b></p>

    <p><b>Menimbang :</b></p>
    <ol type="a">
        <li class="justify">bahwa untuk kelancaran bimbingan proposal mahasiswa pada Program Studi Pendidikan Teknologi Informasi Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh maka dipandang perlu menunjuk Pembimbing awal proposal;</li>
        <li class="justify">bahwa yang namanya tersebut dalam Surat Keputusan ini dianggap cakap dan mampu untuk diangkat dalam jabatan sebagai Pembimbing Awal Proposal;</li>
        <li class="justify">bahwa berdasarkan pertimbangan sebagaimana dimaksud dalam huruf a dan huruf b, perlu menetapkan Keputusan Ketua Program Studi Pendidikan Teknologi Informasi Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh.</li>
    </ol>

    <p><b>Mengingat :</b></p>
    <ol>
        <li class="justify">Undang-Undang Nomor 20 Tahun 2003, tentang Sistem Pendidikan Nasional;</li>
        <li class="justify">Undang-Undang Nomor 14 Tahun 2005, tentang Guru dan Dosen;</li>
        <li class="justify">Undang-Undang Nomor 12 Tahun 2012, tentang Pendidikan Tinggi;</li>
        <li class="justify">Peraturan Presiden Nomor 74 Tahun 2012, tentang Perubahan atas peraturan pemerintah RI Nomor 23 Tahun 2005 tentang pengelolaan keuangan Badan Layanan Umum;</li>
        <li class="justify">Peraturan Pemerintah Nomor 4 Tahun 2014, tentang penyelenggaraan Pendidikan Tinggi dan Pengelolaan Perguruan Tinggi;</li>
        <li class="justify">Peraturan Presiden Nomor 64 Tahun 2013, tentang perubahan IAIN Ar-Raniry menjadi UIN Ar-Raniry;</li>
        <li class="justify">Peraturan Menteri Agama RI Nomor 12 Tahun 2014, tentang Organisasi & Tata Kerja UIN Ar-Raniry Banda Aceh;</li>
        <li class="justify">Peraturan Menteri Agama Nomor 21 Tahun 2015, tentang Statuta UIN Ar-Raniry Banda Aceh;</li>
        <li class="justify">Keputusan Menteri Agama Nomor 492 Tahun 2003, tentang Pendelegasian Wewenang Pengangkatan, Pemindahan dan Pemberhentian PNS di Lingkungan Depag RI;</li>
        <li class="justify">Keputusan Menteri Keuangan Nomor 293/Kmk.05/2011, tentang penetapan UIN Ar-Raniry Banda Aceh sebagai Instansi Pemerintah BLU;</li>
        <li class="justify">Surat Keputusan Rektor UIN Ar-Raniry Nomor 01 Tahun 2015, Tentang Pendelegasian Wewenang kepada Dekan dan Direktur Pascasarjana di UIN Ar-Raniry Banda Aceh;</li>
    </ol>

    <p class="center"><b>MEMUTUSKAN</b></p>
    <p><b>Menetapkan:</b> Keputusan Ketua Program Studi Pendidikan Teknologi Informasi Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh tentang Dosen Pembimbing proposal Skripsi.</p>

    <p><b>KESATU :</b> Menunjukkan Saudara <b>{{ $pembimbing->nama }}</b> sebagai Pembimbing Proposal Skripsi:</p>
    <table>
        <tr><td width="150">Nama</td><td>: {{ $ta->mahasiswa->nama }}</td></tr>
        <tr><td>NIM</td><td>: {{ $ta->mahasiswa->nim }}</td></tr>
        <tr><td>Peminatan</td><td>: {{ $ta->mahasiswa->peminatan }}</td></tr>
        <tr><td>Program Studi</td><td>: {{ $ta->mahasiswa->prodi->nama }}</td></tr>
        <tr><td>Judul Skripsi</td><td>: {{ $ta->judul_revisi ?? $ta->judul }}</td></tr>
    </table>

    <p><b>KEDUA :</b> Kepada pembimbing yang tercantum namanya di atas diberikan honorarium sesuai dengan peraturan perundang-undangan yang berlaku;</p>
    <p><b>KETIGA :</b> Pembiayaan akibat keputusan ini dibebankan pada DIPA UIN Ar-Raniry Banda Aceh Nomor SP DIPA-025.04.2.423925/{{ now()->year }}, Tgl. {{ now()->format('d F Y') }} Tahun Anggaran {{ now()->year }};</p>
    <p><b>KEEMPAT :</b> Surat Keputusan ini berlaku sampai <b>sampai kapan kau mau lah!!</b></p>
    <p><b>KELIMA :</b> Surat Keputusan ini berlaku sejak tanggal ditetapkan dengan ketentuan bahwa segala sesuatu akan diubah dan diperbaiki kembali sebagaimana mestinya, apabila dikemudian hari ternyata terdapat kekeliruan dalam Surat Keputusan ini.</p>

    <div class="tembusan">
        <p><b>Tembusan:</b></p>
        <ol>
            <li>Rektor UIN Ar-Raniry Banda Aceh;</li>
            <li>Ketua Prodi PIAUD FTK;</li>
            <li>Pembimbing yang bersangkutan;</li>
            <li>Mahasiswa yang bersangkutan.</li>
        </ol>
    </div>

    <p class="center" style="margin-top: 25px;">
        Ditetapkan di : Banda Aceh<br>
        Pada tanggal : jssjjjjsjjsj<br>
    </p>

    <div class="ttd">
        <p>Ka. Prodi,</p>
        <br><br><br>
        <p><b>{{ $sk->pejabat ?? 'Mira Maisura' }}</b></p>
    </div>

</body>
</html>
