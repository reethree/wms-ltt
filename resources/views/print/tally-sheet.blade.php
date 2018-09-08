@extends('print')

@section('title')
    {{ 'Manifest Tally Sheet' }}
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
    <div id="details" class="clearfix" style="font-weight: bold;">
        <div id="title" style="font-size: 30px;font-weight: bold;text-decoration: underline;">TALLY SHEET</div>
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;font-weight: bold;">
                        <!-- <tr>
                            <td>Consolidator</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->NAMACONSOLIDATOR }}</td>
                        </tr> -->
                        <tr>
                            <td style="width:100px;">NO. TRUCK</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->NOPOL }}</td>
                        </tr>
                        <tr>
                            <td>NO. CONTAINER</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->NOCONTAINER }}/{{ $container->SIZE }}</td>
                        </tr>
                        <tr>
                            <td>EX. KAPAL</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->VESSEL }}</td>
                        </tr>
                        <tr>
                            <td>TGL. TIBA</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ date("d-m-Y", strtotime($container->ETA)) }}</td>
                        </tr>
                        <tr>
                            <td>No. B/L</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->NOMBL }}</td>
                        </tr>
                        <tr>
                            <td>GUDANG</td>
                            <td class="padding-10 text-center">:</td>
                            <td>LTT</td>
                        </tr>
                    </table> 
                </td>
                <td>
                    <table border="0" cellspacing="0" cellpadding="0" style="font-size: 12px;font-weight: bold;">
                        <tr>
                            <td style="width:50px;">VOY</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->VOY }}</td>
                        </tr>
                        <tr>
                            <td>SEAL</td>
                            <td class="padding-10 text-center">:</td>
                            <td>{{ $container->NO_SEAL }}</td>
                        </tr>
                    </table>
                </td>
            </tr>          
            
        </table>
    </div>
    <div class="clearfix"></div>
    <table border="1" cellspacing="0" cellpadding="0" style="font-weight: bold;">
        <thead>
            <tr>
                <th>NO</th>
                <th>CONSIGNEE</th>
                <th>MERK<br />JENIS BARANG</th>
                <!-- <th>PCKGs</th> -->
                <th>TALLY</th>
                <th>TOTAL</th>
                <!-- <th>WEIGHT<br/>MEAS</th> -->
                <!-- <th>NO TALLY<br/>NO HBL</th> -->
                <th>REMARK</th>
            </tr>
        </thead>
        <tbody>
            <?php $i =1; ?>
            @foreach($manifests as $manifest)
            <tr>
                <td class="text-center" style="border: 1px solid;">{{ $i }}</td>
                <td style="border: 1px solid;">{{ $manifest->CONSIGNEE }}</td>
                <td style="border: 1px solid;">{{ $manifest->DESCOFGOODS }}</td>
                <!-- <td class="text-center">{{ $manifest->QUANTITY.' '.$manifest->NAMAPACKING }}</td> -->
                <td style="width: 150px;border: 1px solid;">&nbsp;</td>
                <td class="text-center" style="border: 1px solid;">{{ $manifest->QUANTITY.' '.$manifest->NAMAPACKING }}</td>
                <!-- <td>{{ $manifest->WEIGHT }}<br/>{{ $manifest->MEAS }}</td> -->
                <!-- <td>{{ $manifest->NOTALLY }}<br/>{{ $manifest->NOHBL }}</td> -->
                <td style="width: 100px;border: 1px solid;">&nbsp;</td>
            </tr>
            <?php $i++; ?>
            @endforeach         
        </tbody>
    </table>
    <table border="0" cellspacing="0" cellpadding="0" style="font-weight: bold;">
        <tbody>
            <tr>
                <td class="text-center">Mengetahui,<br />Ka. Gudang</td>
                <td class="text-center">Mengetahui,<br />Surveyor</td>
                <td class="text-center">Mengetahui,<br />Bea & Cukai</td>
                <td class="text-center">
                    Tg. Priok, {{date('d F Y')}}<br />
                    Petugas Tally<br />
                    <p>PT. LAUTAN TIRTA TRANSPORTAMA</p>
                </td>
            </tr>
            <tr>
                <td class="text-center" style="padding-top: 80px;">(..........................)</td>
                <td class="text-center" style="padding-top: 80px;">(..........................)</td>
                <td class="text-center" style="padding-top: 80px;">(..........................)</td>
                <td class="text-center" style="padding-top: 80px;">(..........................)</td>
            </tr>
        </tbody>
    </table>
@stop