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
                            <td>PT. LAUTAN TIRTA TRASPORTAMA</td>
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
                        <tr>
                            <td>No. HBL </td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->NOHBL }}</td>
                        </tr>
                        <tr>
                            <td>Truck No. Pol</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $manifest->NOPOL }}</td>
                        </tr>

                    </table>
                </td>
                <td style="vertical-align: top;">
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;">
                        <tr>
                            <td>Dikirim Kepada :</td>
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
                    <th rowspan="2">JENIS<br />DOKUMEN</th>
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
                    <td style="width: 15%;vertical-align: top;">{{ $manifest->KODE_DOKUMEN }}<br/>{{$manifest->NO_SPPB.' '.$manifest->TGL_SPPB}}</td>
                    <td style="width: 40%;vertical-align: top;">{{ $manifest->MARKING }}</td>
                    <td style="width: 10%;text-align: center;vertical-align: top;">{{ $manifest->QUANTITY }}/{{ $manifest->NAMAPACKING }}</td>
                    <td style="width: 10%;text-align: center;vertical-align: top;">{{ $manifest->MEAS }}</td>
                    <td style="width: 25%;vertical-align: top;">&nbsp;</td>
                </tr>
            </tbody>
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
            <tr>
                <td colspan="2" style="padding: 0;height: 50px;"><span style="border: 2px solid;padding: 6px;font-size: 10px;"><b>KOMPLAIN HANYA DILAYANI PADA HARI & TANGGAL YANG SAMA</b></span></td>
            </tr>
        </table>
        
        <!--<div style="page-break-after: always;"></div>-->
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>Penerima,<br />Nama Terang/EMKL/Importir</td>
                <td>Staff Adm,<br />Nama Terang</td>
                <td style="text-align: center;">Tanjung Priok, {{ date('d F Y') }}<br />PT. LAUTAN TIRTA TRANSPORTAMA<br />Kepala Gudang</td>
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