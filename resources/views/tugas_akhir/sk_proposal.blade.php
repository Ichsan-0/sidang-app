<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Surat Keputusan</title>
    <style>
        /* Reset margin dan padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;
            line-height: 1.5;
            margin: 10px 15px; /* Margin sangat kecil agar muat 1 halaman */
        }
        .container {
            width: auto;
            margin: 0 auto;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .text-center {
            text-align: center;
        }
        .text-justify {
            text-align: justify;
        }
        .font-bold {
            font-weight: bold;
        }
        p, h3, h4 {
            margin-bottom: 3px;
        }
        .header h3 {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 3px;
        }
        .header h4 {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 3px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            line-height: 1.2;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }
        td {
            padding: 1px 0;
            vertical-align: top;
        }
        .label-column {
            width: 120px;
            font-weight: bold;
            vertical-align: top;
            padding-right: 5px;
        }
        .colon-column {
            width: 5px;
            vertical-align: top;
        }
        .content-column {
            vertical-align: top;
        }
        /* List item dibuat blok agar enter antar item */
        .list-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 6px;
        }
        .list-point {
            min-width: 20px;
            text-align: right;
            font-family: "Times New Roman", Times, serif;
            margin-right: 5px;
        }
        .list-text {
            flex: 1;
            text-align: justify;
            font-family: "Times New Roman", Times, serif;
        }
        .signature-section {
            width: 38%;
            margin-left: 62%;
            margin-top: 10px;
            line-height: 1.1;
            text-align: left;
        }
        .signature-row {
            display: flex;
            align-items: center;
            margin-top: 5px;
        }
        .signature-name {
            font-weight: bold;
            font-size: 10pt;
            margin-top: -10px; /* naikkan agar lebih dekat ke QR code */
            margin-right: 10px;
        }
        .signature-blu {
            margin-left: 20px;
            width: 70px;
            height: auto;
        }
        .signature-stamp {
            margin-bottom: -50px;
        }
        .tembusan-section {
            margin-top: -80px;
            line-height: 0.2;
        }
        .tembusan-section p {
            font-size: 10px;
            margin-bottom: -5px;
            font-style: italic;
            font-family: "Times New Roman", Times, serif;
        }
        /* Label keputusan (KESATU, KEDUA, ...) */
        .label-column.uppercase {
            text-transform: uppercase;
        }
        /* Atur agar halaman tidak terpotong saat print */
        @media print {
            body {
                margin: 10px 15px;
            }
            .container, table, p, h3, h4 {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            tr {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header text-center">
            <img src="{{ public_path('storage/logo_uin.png') }}" alt="Logo" class="logo" style="width: 70px; height: auto; margin-bottom: 2px;">
            <h3>KEPUTUSAN KETUA PROGRAM STUDI {{ strtoupper($ta->mahasiswa->prodi->nama_prodi) }}<br>
                FAKULTAS TARBIYAH DAN KEGURUAN UIN AR-RANIRY BANDA ACEH <br>
                NOMOR: {{ $sk->kode_sk ?? $sk->nomor_sk }}
            </h3>
            <h4>TENTANG: <br>
            PENGANGKATAN PEMBIMBING AWAL PROPOSAL MAHASISWA <br>
            DENGAN RAHMAT TUHAN YANG MAHA ESA</h4>
        </div>

        <p class="font-bold text-center" style="text-transform: uppercase; margin-top: 10px;">KETUA PROGRAM STUDI PENDIDIKAN TEKNOLOGI INFORMASI</p>

        <table style="margin-top: 5px;">
            <tr>
                <td class="label-column">Menimbang</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    <div class="list-item">
                        <span class="list-point">a. </span>
                        <span class="list-text">bahwa untuk kelancaran bimbingan proposal mahasiswa pada Program Studi Pendidikan Teknologi Informasi Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh maka dipandang perlu menunjuk Pembimbing awal proposal;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">b. </span>
                        <span class="list-text">bahwa yang namanya tersebut dalam Surat Keputusan ini dianggap cakap dan mampu untuk diangkat dalam jabatan sebagai Pembimbing Awal Proposal;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">c. </span>
                        <span class="list-text">bahwa berdasarkan pertimbangan sebagaimana dimaksud dalam huruf a dan huruf b, perlu menetapkan Keputusan Ketua Program Studi Pendidikan Teknologi Informasi Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh.</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label-column" style="padding-top: 2px;">Mengingat</td>
                <td class="colon-column" style="padding-top: 2px;">:</td>
                <td class="content-column text-justify" style="padding-top: 2px;">
                    <div class="list-item">
                        <span class="list-point">1. </span>
                        <span class="list-text">Undang-Undang Nomor 20 Tahun 2003, tentang Sistem Pendidikan Nasional;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">2. </span>
                        <span class="list-text">Undang-Undang Nomor 14 Tahun 2005, tentang Guru dan Dosen;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">3. </span>
                        <span class="list-text">Undang-Undang Nomor 12 Tahun 2012, tentang Pendidikan Tinggi;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">4. </span>
                        <span class="list-text">Peraturan Presiden Nomor 74 Tahun 2012, tentang Perubahan atas peraturan pemerintah RI Nomor 23 Tahun 2005 tentang pengelolaan keuangan Badan Layanan Umum;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">5. </span>
                        <span class="list-text">Peraturan Pemerintah Nomor 4 Tahun 2014, tentang penyelenggaraan Pendidikan Tinggi dan Pengelolaan Perguruan Tinggi;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">6. </span>
                        <span class="list-text">Peraturan Presiden Nomor 64 Tahun 2013, tentang perubahan Institusi Agama Islam negeri Ar-Raniry Banda Aceh Menjadi Universitas Islam Negeri Ar-Raniry Banda Aceh;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">7. </span>
                        <span class="list-text">Peraturan Menteri Agama RI Nomor 12 Tahun 2014, tentang Organisasi & Tata Kerja UIN Ar-Raniry Banda Aceh;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">8. </span>
                        <span class="list-text">Peraturan Menteri Agama Nomor 21 Tahun 2015, tentang Statuta UIN Ar-Raniry Banda Aceh;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">9. </span>
                        <span class="list-text">Keputusan Menteri Agama Nomor 492 Tahun 2003, tentang Pendelegasian Wewenang Pengangkatan, Pemindahan dan Pemberhentian PNS di Lingkungan Depag RI;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">10. </span>
                        <span class="list-text">Keputusan Menteri Keuangan Nomor 293/Kmk.05/2011, tentang penetapan institusi agama Islam Negeri UIN Ar-Raniry Banda Aceh pada Kementerian Agama sebagai Instansi Pemerintah yang menerapkan Pengelolaan Badan Layanan Umum;</span>
                    </div>
                    <div class="list-item">
                        <span class="list-point">11. </span>
                        <span class="list-text">Surat Keputusan Rektor UIN Ar-Raniry Nomor 01 Tahun 2015, Tentang Pendelegasian Wewenang Kepada Dekan dan Direktur Pascasarjana di Lingkungan UIN Ar-Raniry Banda Aceh;</span>
                    </div>
                </td>
            </tr>
        </table>

        <h4 class="text-center" style="margin: 8px 0;">MEMUTUSKAN</h4>

        <table>
            <tr>
                <td class="label-column">Menetapkan</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Keputusan Ketua Program Studi {{ $ta->mahasiswa->prodi->nama_prodi }} Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh tentang Dosen Pembimbing proposal Skripsi.
                </td>
            </tr>
            <tr>
                <td class="label-column uppercase">KESATU</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Menunjuk Saudara :<br>
                    <span class="font-bold" style="padding-left: 10px;">{{ $pembimbing->name }}</span><br>
                    Sebagai Pembimbing Proposal Skripsi:
                    <table>
                        <tr>
                            <td style="width: 100px; padding-left: 10px;">Nama</td>
                            <td style="width: 5px;">:</td>
                            <td>{{ $ta->mahasiswa->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 10px;">NIM</td>
                            <td>:</td>
                            <td>{{ $ta->mahasiswa->no_induk }}</td>
                        </tr>
                        @if($bidangPeminatan != null)
                        <tr>
                            <td style="padding-left: 10px; vertical-align: top;">Peminatan</td>
                            <td style="vertical-align: top;">:</td>
                            <td>{{ $bidangPeminatan->nama }} ({{ $bidangPeminatan->kode }})</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding-left: 10px; vertical-align: top;">Program Studi</td>
                            <td style="vertical-align: top;">:</td>
                            <td>{{ $ta->mahasiswa->prodi->nama_prodi }} ({{ $ta->mahasiswa->prodi->kode_prodi }})</td>
                        </tr>
                        <tr>
                            <td style="padding-left: 10px; vertical-align: top;">Judul Skripsi</td>
                            <td style="vertical-align: top;">:</td>
                            <td class="text-justify">{{ $sk->judul_akhir }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="label-column uppercase">KEDUA</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Kepada pembimbing yang tercantum namanya di atas diberikan honorarium sesuai dengan peraturan perundang-undangan yang berlaku;
                </td>
            </tr>
            <tr>
                <td class="label-column uppercase">KETIGA</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Pembiayaan akibat keputusan ini dibebankan pada DIPA UIN Ar-Raniry Banda Aceh Nomor : SP DIPA-025.04.2.423925/2024, Tgl. 24 November 2023 Tahun Anggaran 2024;
                </td>
            </tr>
            <tr>
                <td class="label-column uppercase">KEEMPAT</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Surat Keputusan ini berlaku sampai {{ \Carbon\Carbon::parse($sk->tanggal_expired)->translatedFormat('d F Y') }} 
                </td>
            </tr>
            <tr>
                <td class="label-column uppercase">KELIMA</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Surat Keputusan ini berlaku sejak tanggal ditetapkan dengan ketentuan bahwa segala sesuatu akan diubah dan diperbaiki kembali sebagaimana mestinya, apabila dikemudian hari ternyata terdapat kekeliruan dalam Surat Keputusan ini.
                </td>
            </tr>
        </table>
        
        <div class="signature-section">
            <table>
                <tr>
                    <td style="width: 100px;">Ditetapkan di</td>
                    <td style="width: 5px;">:</td>
                    <td>Banda Aceh</td>
                </tr>
                <tr>
                    <td>Pada tanggal</td>
                    <td>:</td>
                    <td>{{ \Carbon\Carbon::parse($sk->tanggal_sk)->translatedFormat('d F Y') }}</td>
                </tr>
            </table>
            <p style="margin-top: 5px;">Ka. Prodi.</p>
            <div class="signature-row">
                <!-- Stempel -->
                <div class="signature-stamp">
                    @if(isset($qrBase64))
                    <img src="{{ $qrBase64 }}" alt="QR Code" style="width:120px; margin-right:15px;">
                    @endif
                </div>
                <span class="signature-name">{{ $penandatangan->name }}</span>
                <img src="{{ public_path('storage/blu.png') }}" alt="Logo BLU" class="signature-blu">
            </div>
        </div>

        <div class="tembusan-section">
            <p class="font-bold">Tembusan</p>
            <p>1. Rektor UIN Ar-Raniry Banda Aceh</p>
            <p>2. Ketua Prodi PIAUD FTK;</p>
            <p>3. Pembimbing yang bersangkutan untuk dimaklumi dan dilaksanakan</p>
            <p>4. Mahasiswa yang bersangkutan.</p>
        </div>
    </div>
</body>
</html>