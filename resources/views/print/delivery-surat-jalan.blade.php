@extends('print-with-header')

@section('title')
    {{ 'Delivery Surat Jalan' }}
@stop

@section('content')
    <style>
        @media print {
            @page {
                size: auto;   /* auto is the initial value */
                margin-top: 48px;
                margin-bottom: 48px;/* this affects the margin in the printer settings */
                background: #FFF;
                color: #000;
                font-weight: bold;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
    <a href="#" class="print-btn" type="button" onclick="window.print();">PRINT</a> 
    <div id="details" class="clearfix">
        <div id="title" style="text-decoration: underline;">SURAT JALAN</div>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;">
                        <tr>
                            <td>Dari Gudang / Lap</td>
                            <td class="padding-10 text-center">:</td>
                            <td>PT. Lautan Tirta Transportama</td>
                        </tr>
                        <tr>
                            <td>Ex. Kapal</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->VESSEL }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal Tiba</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ date("d F Y", strtotime($manifest->ETA)) }}</td>
                        </tr>
<!--                        <tr>
                            <td>Kepada Yth.</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->NAMACONSOLIDATOR }}</td>
                        </tr>
                        <tr>
                            <td>No. Surat Jalan </td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->NOTALLY }}</td>
                        </tr>
                        <tr>
                            <td>Ex. Kapal</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->VESSEL }}</td>
                        </tr>
                        <tr>
                            <td>Voy</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->VOY }}</td>
                        </tr>
                        <tr>
                            <td>No. HBL </td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->NOHBL }}</td>
                        </tr>
                        <tr>
                            <td>No. Truck</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->NOPOL }}</td>
                        </tr>-->

                    </table>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="border: 1px solid;">&nbsp;&nbsp;</td>
                            <td>Barang dalam keadaan baik, lengkap dan sesuai DO.</td>
                        </tr>
                        <tr><td style="border-bottom: 1px solid;"></td><td></td></tr>
                        <tr>
                            <td style="border: 1px solid;">&nbsp;&nbsp;</td>
                            <td>Barang dalam keadaan rusak/cacat/tidak lengkap (Lampiran berita acara)</td>
                        </tr>
                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;">
                        <tr>
                            <td>DIKIRIM KEPADA</td>
                        </tr>
                        <tr>
                            <td>Yth. {{ $manifest->CONSIGNEE }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        
        
        <table border="1" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th rowspan="2">MERK / No.</th>
                    <th rowspan="2">JENIS BARANG</th>
                    <th colspan="2">JUMLAH BARANG</th>
                    <th rowspan="2">KETERANGAN</th>
                </tr>
                <tr>
                    <th>QUANTITY</th>
                    <th class="text-center">MT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 15%;vertical-align: top;">{{ $manifest->NAMACONSOLIDATOR }}</td>
                    <td style="width: 40%;vertical-align: top;">{{ $manifest->MARKING }}</td>
                    <td style="width: 10%;text-align: center;vertical-align: top;">{{ $manifest->QUANTITY }}/{{ $manifest->NAMAPACKING }}</td>
                    <td style="width: 10%;text-align: center;vertical-align: top;">{{ $manifest->MEAS }}</td>
                    <td style="width: 25%;vertical-align: top;">{{ str_limit($manifest->DESCOFGOODS, 150) }}</td>
                </tr>
            </tbody>
        </table>
              
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="text-align: right;">Tanjung Priok, {{ date('d F Y') }}</td>
            </tr>
        </table>
        <!--<div style="page-break-after: always;"></div>-->
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>Penerima,<br />Nama Terang / Stempel Perusahaan</td>
                <td>Staff ADM.,<br />Nama Terang</td>
                <td style="text-align: center;">PT. LAUTAN TIRTA TRANSPORTAMA<br />Kepala Gudang</td>
            </tr>
<!--            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td style="text-align: center;">(..................)</td>
                <td style="text-align: center;">(..................)</td>
                <td style="text-align: center;">(..................)</td>
            </tr>-->
        </table>
    </div>
        
@stop