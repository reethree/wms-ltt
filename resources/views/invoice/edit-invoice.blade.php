@extends('layout')

@section('content')
<section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
          {{ $manifest->NAMACONSOLIDATOR }}
          <small class="pull-right">Date: {{ date('d F, Y') }}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-xs-12 text-center margin-bottom">
            <h2><b>INVOICE</b></h2>
        </div>
      <div class="col-sm-6 invoice-col">
          <table>
              <tr>
                  <td><b>Kepada Yth,</b></td>
                  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                  <td><b>{{ $manifest->CONSIGNEE }}</b></td>
              </tr>
              <tr>
                  <td colspan="3">&nbsp;</td>
              </tr>
              <tr>
                  <td><b>Ex. Kapal</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
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
                  <td>{{ $manifest->NOCONTAINER }} / {{ $manifest->SIZE }}</td>
              </tr>
          </table>
      </div>
      <!-- /.col -->
      <div class="col-sm-6 invoice-col">
          <table>
              <tr>
                  <td><b>No. Invoice</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ 'SAJ/LTT-CFS'.date('Y').'/'.$invoice->number }}</td>
              </tr>
              <tr>
                  <td><b>Tgl. Masuk</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d/m/Y', strtotime($manifest->tglmasuk)) }}</td>
              </tr>
              <tr>
                  <td><b>Tgl. ETA</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d/m/Y', strtotime($manifest->ETA)) }}</td>
              </tr>
              <tr>
                  <td><b>Tgl. Keluar</b></td>
                  <td>&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                  <td>{{ date('d/m/Y ', strtotime($manifest->tglrelease)) }}</td>
              </tr>
          </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
    <br /><br />
    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
          <h4>Lama Penumpukan : {{$invoice->days.' Hari'}}</h4>
        <table class="table table-striped" border="0">
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
                <td>{{$item->item_cbm}}</td>
                <td>{{$item->item_qty}}</td>
                <td align="right">Rp.</td>
                <td align="right">{{ number_format($item->item_amount) }}</td>
                <td align="right">{{ ($item->item_tax > 0) ? 'PPn '.$item->item_tax.'%' : '' }}</td>
                <td align="right">Rp.</td>
                <td align="right">{{ number_format($item->item_subtotal) }}</td>
            </tr>
            @endforeach          
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-6">
        <p>Terbilang:</p>
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; color: #000;">
          {{ $terbilang }}
        </p>
      </div>
      <!-- /.col -->
      <div class="col-xs-6">
        <!--<p class="lead">Amount Due 2/22/2014</p>-->

        <div class="table-responsive">
          <table class="table">
            <tbody>
            <tr>
              <th style="width:50%" align="right">Total Tanpa Pajak</th>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->subtotal_amount) }}</td>
            </tr>
            <tr>
              <th align="right">PPn</th>
              <td align="right">Rp.</td>
              <td align="right">{{ number_format($invoice->total_tax) }}</td>
            </tr>
            <tr>
              <th align="right">Total</th>
              <td align="right"><b>Rp.</b></td>
              <td align="right"><b>{{ number_format($invoice->total_amount) }}</b></td>
            </tr>
          </tbody></table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
      <div class="col-xs-12">
          <button id="print-invoice-btn" class="btn btn-primary"><i class="fa fa-print"></i> Print</button>
<!--        <button type="button" class="btn btn-success pull-right"><i class="fa fa-credit-card"></i> Submit Payment
        </button>
        <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
          <i class="fa fa-download"></i> Generate PDF
        </button>-->
      </div>
    </div>
  </section>

@endsection

@section('custom_css')

@endsection

@section('custom_js')

<script type="text/javascript">
    $('#print-invoice-btn').click(function() {
        window.open("{{ route('invoice-print',$invoice->id) }}","preview invoice ","width=600,height=600,menubar=no,status=no,scrollbars=yes");
    });
</script>

@endsection