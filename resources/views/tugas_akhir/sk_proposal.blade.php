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
            line-height: 1;
            margin: 20px 25px; /* margin pas untuk A4 */
        }
        .container {
            width: auto;
            margin: 0 auto;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .text-center { text-align: center; }
        .text-justify { text-align: justify; }
        .font-bold { font-weight: bold; }
        p, h3, h4 { margin-bottom: 4px; }

        .header h3 {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            margin-bottom: 5px;
        }
        .header h4 {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1;
            margin-bottom: 5px;
        }

        table { width: 100%; border-collapse: collapse; }
        td { vertical-align: top; }

        /* Label utama (Menimbang, Mengingat, dst) */
        .label-column {
            width: 110px;
            font-weight: bold;
            vertical-align: top;
            padding-right: 5px;
        }
        .colon-column {
            width: 10px;
            vertical-align: top;
        }
        .content-column {
            vertical-align: top;
        }

        /* Daftar dengan nomor/huruf sejajar */
        .list-table {
            width: 100%;
            border-collapse: collapse;
            margin-left: -10px;
        }
        .list-table td {
            vertical-align: top;
            padding: 0px 0;
        }
        .list-num {
            width: 25px;
            text-align: right;
            padding-right: 3px;
            white-space: nowrap;
        }
        .list-text {
            text-align: justify;
        }

       .signature-section { 
            width: 38%; 
            margin-left: 62%; 
            margin-top: 10px; 
            line-height: 1.1; 
            text-align: left; 
        } 
        .signature-row { 
            display: flex; align-items: center; margin-top: 5px; 
        } 
        .signature-name { 
            font-weight: bold; font-size: 10pt; margin-top: -10px; margin-right: 10px; 
        } 
        .signature-blu { 
            margin-left: 20px; width: 70px; height: auto; 
        } 
        .signature-stamp { 
            margin-bottom: -50px; 
        } 
        .tembusan-section { 
            margin-top: -80px; line-height: 0.2; 
        } 
        .tembusan-section p { 
            font-size: 10px; margin-bottom: -5px; font-style: italic; font-family: "Times New Roman", Times, serif; 
        }

        @media print {
            body { margin: 10px 15px; }
            .container, table, p, h3, h4 {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header text-center">
            <img src="{{ public_path('storage/logo_uin.png') }}" alt="Logo" style="width: 70px; height: auto; margin-bottom: 5px;">
            <h3>
                KEPUTUSAN KETUA PROGRAM STUDI {{ strtoupper($ta->mahasiswa->prodi->nama_prodi) }}<br>
                FAKULTAS TARBIYAH DAN KEGURUAN UIN AR-RANIRY BANDA ACEH<br>
                NOMOR: {{ $sk->kode_sk ?? $sk->nomor_sk }}
            </h3>
            <h4>
                TENTANG: <br>
                PENGANGKATAN PEMBIMBING AWAL PROPOSAL MAHASISWA <br>
                DENGAN RAHMAT TUHAN YANG MAHA ESA
            </h4>
        </div>

        <p class="font-bold text-center" style="margin-top: 10px; text-transform: uppercase;">
            KETUA PROGRAM STUDI PENDIDIKAN TEKNOLOGI INFORMASI
        </p>

        <!-- Menimbang -->
        <table style="margin-top: 10px;">
            <tr>
                <td class="label-column">Menimbang</td>
                <td class="colon-column">:</td>
                <td class="content-column">
                    <table class="list-table">
                        <tr>
                            <td class="list-num">a. </td>
                            <td class="list-text">bahwa untuk kelancaran bimbingan proposal mahasiswa pada Program Studi Pendidikan Teknologi Informasi Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh maka dipandang perlu menunjuk Pembimbing awal proposal;</td>
                        </tr>
                        <tr>
                            <td class="list-num">b. </td>
                            <td class="list-text">bahwa yang namanya tersebut dalam Surat Keputusan ini dianggap cakap dan mampu untuk diangkat dalam jabatan sebagai Pembimbing Awal Proposal;</td>
                        </tr>
                        <tr>
                            <td class="list-num">c. </td>
                            <td class="list-text">bahwa berdasarkan pertimbangan sebagaimana dimaksud dalam huruf a dan huruf b, perlu menetapkan Keputusan Ketua Program Studi Pendidikan Teknologi Informasi Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh.</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Mengingat -->
            <tr>
                <td class="label-column">Mengingat</td>
                <td class="colon-column">:</td>
                <td class="content-column">
                    <table class="list-table">
                        <tr><td class="list-num">1. </td><td class="list-text">Undang-Undang Nomor 20 Tahun 2003, tentang Sistem Pendidikan Nasional;</td></tr>
                        <tr><td class="list-num">2. </td><td class="list-text">Undang-Undang Nomor 14 Tahun 2005, tentang Guru dan Dosen;</td></tr>
                        <tr><td class="list-num">3. </td><td class="list-text">Undang-Undang Nomor 12 Tahun 2012, tentang Pendidikan Tinggi;</td></tr>
                        <tr><td class="list-num">4. </td><td class="list-text">Peraturan Presiden Nomor 74 Tahun 2012, tentang Perubahan atas peraturan pemerintah RI Nomor 23 Tahun 2005 tentang pengelolaan keuangan Badan Layanan Umum;</td></tr>
                        <tr><td class="list-num">5. </td><td class="list-text">Peraturan Pemerintah Nomor 4 Tahun 2014, tentang penyelenggaraan Pendidikan Tinggi dan Pengelolaan Perguruan Tinggi;</td></tr>
                        <tr><td class="list-num">6. </td><td class="list-text">Peraturan Presiden Nomor 64 Tahun 2013, tentang perubahan Institusi Agama Islam negeri Ar-Raniry Banda Aceh Menjadi Universitas Islam Negeri Ar-Raniry Banda Aceh;</td></tr>
                        <tr><td class="list-num">7. </td><td class="list-text">Peraturan Menteri Agama RI Nomor 12 Tahun 2014, tentang Organisasi & Tata Kerja UIN Ar-Raniry Banda Aceh;</td></tr>
                        <tr><td class="list-num">8. </td><td class="list-text">Peraturan Menteri Agama Nomor 21 Tahun 2015, tentang Statuta UIN Ar-Raniry Banda Aceh;</td></tr>
                        <tr><td class="list-num">9. </td><td class="list-text">Keputusan Menteri Agama Nomor 492 Tahun 2003, tentang Pendelegasian Wewenang Pengangkatan, Pemindahan dan Pemberhentian PNS di Lingkungan Depag RI;</td></tr>
                        <tr><td class="list-num">10. </td><td class="list-text">Keputusan Menteri Keuangan Nomor 293/Kmk.05/2011, tentang penetapan institusi agama Islam Negeri UIN Ar-Raniry Banda Aceh pada Kementerian Agama sebagai Instansi Pemerintah yang menerapkan Pengelolaan Badan Layanan Umum;</td></tr>
                        <tr><td class="list-num">11. </td><td class="list-text">Surat Keputusan Rektor UIN Ar-Raniry Nomor 01 Tahun 2015, Tentang Pendelegasian Wewenang Kepada Dekan dan Direktur Pascasarjana di Lingkungan UIN Ar-Raniry Banda Aceh;</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <h4 class="text-center" style="margin: 12px 0;">MEMUTUSKAN</h4>

        <!-- Keputusan -->
        <table>
            <tr>
                <td class="label-column">Menetapkan</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Keputusan Ketua Program Studi {{ $ta->mahasiswa->prodi->nama_prodi }} Fakultas Tarbiyah dan Keguruan UIN Ar-Raniry Banda Aceh tentang Dosen Pembimbing proposal Skripsi.
                </td>
            </tr>
            <tr>
                <td class="label-column">KESATU</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Menunjuk Saudara: <br>
                    <span class="font-bold" style="padding-left: 15px;">{{ $pembimbing->name }}</span><br>
                    Sebagai Pembimbing Proposal Skripsi:
                    <table style="margin-top: 5px;">
                        <tr><td style="width: 110px; padding-left: 15px;">Nama</td><td style="width: 5px;">:</td><td>{{ $ta->mahasiswa->name }}</td></tr>
                        <tr><td style="padding-left: 15px;">NIM</td><td>:</td><td>{{ $ta->mahasiswa->no_induk }}</td></tr>
                        @if($bidangPeminatan != null)
                        <tr><td style="padding-left: 15px;">Peminatan</td><td>:</td><td>{{ $bidangPeminatan->nama }} ({{ $bidangPeminatan->kode }})</td></tr>
                        @endif
                        <tr><td style="padding-left: 15px;">Program Studi</td><td>:</td><td>{{ $ta->mahasiswa->prodi->nama_prodi }} ({{ $ta->mahasiswa->prodi->kode_prodi }})</td></tr>
                        <tr><td style="padding-left: 15px; vertical-align: top;">Judul Skripsi</td><td>:</td><td class="text-justify">{{ $sk->judul_akhir }}</td></tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="label-column">KEDUA</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Kepada pembimbing yang tercantum namanya di atas diberikan honorarium sesuai dengan peraturan perundang-undangan yang berlaku;
                </td>
            </tr>
            <tr>
                <td class="label-column">KETIGA</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Pembiayaan akibat keputusan ini dibebankan pada DIPA UIN Ar-Raniry Banda Aceh Nomor SP DIPA-025.04.2.423925/{{ now()->year }}, Tgl. {{ now()->format('d F Y') }} Tahun Anggaran {{ now()->year }};
                </td>
            </tr>
            <tr>
                <td class="label-column">KEEMPAT</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Surat Keputusan ini berlaku sampai {{ \Carbon\Carbon::parse($sk->tanggal_expired)->translatedFormat('d F Y') }}.
                </td>
            </tr>
            <tr>
                <td class="label-column">KELIMA</td>
                <td class="colon-column">:</td>
                <td class="content-column text-justify">
                    Surat Keputusan ini berlaku sejak tanggal ditetapkan dengan ketentuan bahwa segala sesuatu akan diubah dan diperbaiki kembali sebagaimana mestinya, apabila dikemudian hari ternyata terdapat kekeliruan dalam Surat Keputusan ini.
                </td>
            </tr>
        </table>

        <!-- Tanda tangan -->
        <div class="signature-section">
            <table>
            <tr>
                <td style="width: 100px;">Ditetapkan di : </td> <td>Banda Aceh</td>
            </tr>
            <tr>
                <td>Pada tanggal</td> <td>: {{ \Carbon\Carbon::parse($sk->tanggal_sk)->translatedFormat('d F Y') }}</td>
            </tr>
            </table>
            <p style="margin-top: 5px;">Ka. Prodi.</p>
            <div class="signature-row">
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
