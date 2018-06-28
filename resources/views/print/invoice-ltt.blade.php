@extends('print-with-noheader')

@section('title')
    {{ 'Invoice '.$invoice->no_invoice }}
@stop

@section('content')
<style>
    @media print {
        @page {
            size: auto;   /* auto is the initial value */
            margin-top: 0;
            margin-bottom: 0;/* this affects the margin in the printer settings */
        }
        .print-btn {
            display: none;
        }
    }
</style>
<a href="#" class="print-btn" type="button" onclick="window.print();">PRINT</a>
<div id="details" class="clearfix" style="margin: 114px 75px 90px 38px;display: block;">
    <div class="row invoice-info">
        <div class="col-sm-6 invoice-col" style="width: 50%; float: left;">
          <table border="0" cellspacing="0" cellpadding="0">
              <tr>
                  <td colspan="3">Kepada Yth, </td>
              </tr>
              <tr>
                  <td colspan="3"><b>{{ $manifest->CONSIGNEE }}</b></td>
              </tr>
                <tr>
                  <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;</td>
              </tr>
              <tr>
                  <td style="width: 100px;"><b>Ex. Kapal</b></td>
                  <td style="width: 10px;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->VESSEL }}</td>
              </tr>
              <tr>
                  <td><b>No. B/L / D/O</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->NOHBL }}</td>
              </tr>
              <tr>
                  <td><b>Gross Weight</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ number_format($manifest->WEIGHT, 4) }} KGS</td>
              </tr>
              <tr>
                  <td><b>Measurment</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ number_format($manifest->MEAS, 4) }} CBM</td>
              </tr>
              <tr>
                  <td><b>Container</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ $manifest->NOCONTAINER }} / {{ $manifest->SIZE."'" }}</td>
              </tr>
          </table>
      </div>
        <div class="col-sm-6 invoice-col" style="width: 50%; float: left;">
            <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width: 100px;"><b>No. Invoice</b></td>
                    <td style="width: 10px;">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ 'SAJ/LTT-CFS'.date('Y').'/'.$invoice->number }}</td>
                </tr>
                <tr>
                    <td><b>Tgl. Invoice</b></td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ date('d F Y') }}</td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                </tr>
                <tr>
                    <td><b>Tgl. ETA</b></td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ date('d/m/Y', strtotime($manifest->ETA)) }}</td>
                </tr>
                <tr>
                    <td><b>Tgl. Masuk</b></td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ date('d/m/Y', strtotime($manifest->tglmasuk)) }}</td>
                </tr>
                <tr>
                    <td><b>Tgl. Keluar</b></td>
                    <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td>{{ date('d/m/Y ', strtotime($manifest->tglrelease)) }}</td>
                </tr>
            </table>
        </div>
    </div>
    
    
    <div class="row">
      <div class="col-xs-12 table-responsive">
          
          <table border="1" cellspacing="0" cellpadding="0">
              <thead>
                <tr>
                  <th>Jasa</th>
                  <th>M3/Ton</th>
                  <th>Jumlah</th>     
                  <th class="text-center" colspan="2">Harga</th>
                  <th class="text-center">&nbsp;</th>
                  <th class="text-center" colspan="2">Sub Total</th>
                </tr>
                </thead>
                <tbody>
                  @foreach($items as $item)
                  <tr>
                      <td>{{$item->item_name}}</td>
                      <td style="text-align: center;">{{$item->item_cbm}}</td>
                      <td style="text-align: center;">{{$item->item_qty}}</td>
                      <td align="right" style="border-right: none !important;">Rp.</td>
                      <td style="text-align: right;border-left: none !important;">{{ number_format($item->item_amount) }}</td>
                      <td align="right">{{ ($item->item_tax > 0) ? 'PPn '.$item->item_tax.'%' : '' }}</td>
                      <td align="right" style="border-right: none !important;">Rp.</td>
                      <td style="text-align: right;border-left: none !important;">{{ number_format($item->item_subtotal) }}</td>
                  </tr>
                  @endforeach          
                </tbody>
          </table>
      </div>
    </div>
    
    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-6" style="width: 70%;float: left;">
          <p style="font-size: 12px;"><b>TERBILANG :</b> {{ $terbilang }}</p>
      </div>
      <!-- /.col -->
      <div class="col-xs-6" style="width: 30%;float: right;">
        <!--<p class="lead">Amount Due 2/22/2014</p>-->

        <div class="table-responsive">
          <table>
            <tbody>
            <tr>
                <td style="width:100%" align="right"><b>Total Tanpa Pajak</b></td>
              <td align="right" style="width:10px;text-align: right;">Rp.</td>
              <td align="right" style="text-align: right;">{{ number_format($invoice->subtotal_amount) }}</td>
            </tr>
            <tr>
              <td align="right">PPn 10%</td>
              <td align="right" style="text-align: right;">Rp.</td>
              <td align="right" style="text-align: right;">{{ number_format($invoice->total_tax) }}</td>
            </tr>
            <tr>
                <td align="right"><b>Total</b></td>
              <td align="right" style="text-align: right;"><b>Rp.</b></td>
              <td align="right" style="text-align: right;"><b>{{ number_format($invoice->total_amount) }}</b></td>
            </tr>
          </tbody></table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <div class="table-responsive">
        <table border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>Jakarta Utara, {{date('d F Y')}}</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><b>{{$invoice->officer}}</b></td>
            </tr>
            <tr>
                <td><b>Lautan Tirta Transportama</b></td>
            </tr>
        </table>
    </div>
    <div>
        <p style="font-size: 12px;">Kwitansi yang dilunasi jika terdapat kekeliruan supaya diajukan dalam batas waktu 2x24 Jam.<br />Lewat Batas waktu tersebut tidak akan dilayani.</p>
    </div>
</div>

@stop