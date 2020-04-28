@extends('layout')

@section('content')
<style>
    .table-bordered {
        border: 1px solid #aeaeae;
    }
    .table-bordered, .table-bordered tbody tr td, .table-bordered tbody tr th {
        border: 1px solid #aeaeae;
    }
</style>
    <!--Welcome, {{ Auth::getUser()->name }}-->

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ number_format($sor->total,'2',',','.') }}<sup style="font-size: 20px">%</sup></h3>
    
                    <p>LCL SOR %</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('lcl-report-inout') }}" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $yor->total }}<sup style="font-size: 20px">%</sup></h3>

                    <p>FCL YOR %</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('fcl-report-rekap') }}" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $countfclcont }}</h3>

                    <p>FCL Inventory Real Time</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $countlclmanifest }}</h3>

                    <p>LCL Inventory Real Time</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    
    <div class="row">
        <div class="col-sm-6">
            <table class="table table-bordered table-hover table-striped" style="background: #FFF;">
                <tbody>
                    <tr>
                        <th>TPS ASAL</th>
                        <!--<th>JML CONT (PLP)</th>-->
                        <th>FCL GATE IN BULAN {{strtoupper(date('F Y'))}}</th>
                    </tr>
                    @foreach($countbytps as $key=>$value)
                    <tr>
                        <td>{{ $key }}</td>
                        <!--<td align="center">{{ $value[0] }}</td>-->
                        <td align="center">{{ $value[1] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <th>TOTAL</th>
                        <!--<td align="center"><strong>{{ $totcounttpsp }}</strong></td>-->
                        <td align="center"><strong>{{ $totcounttpsg }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-sm-6">
            <table class="table table-bordered table-hover table-striped" style="background: #FFF;">
                <tbody>
                    <tr>
                        <th>TPS ASAL</th>
                        <!--<th>JML CONT (PLP)</th>-->
                        <th>LCL GATE IN BULAN {{strtoupper(date('F Y'))}}</th>
                    </tr>
                    @foreach($countbytpslcl as $key=>$value)
                    <tr>
                        <td>{{ $key }}</td>
                        <!--<td align="center">{{ $value[0] }}</td>-->
                        <td align="center">{{ $value[1] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <th>TOTAL</th>
                        <!--<td align="center"><strong>{{ $totcounttpsp }}</strong></td>-->
                        <td align="center"><strong>{{ $totcounttpsglcl }}</strong></td>
                    </tr>
                </tbody>
            </table>           
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <table class="table table-bordered table-hover table-striped" style="background: #FFF;">
                <tbody>
                    <tr>
                        <th>KODE DOKUMEN</th>
                        <th>JUMLAH DOKUMEN KELUAR FCL BULAN {{strtoupper(date('F Y'))}}</th>
                    </tr>
                    @foreach($countbydoc as $key=>$value)
                    <tr>
                        <th>{{ $key }}</th>
                        <td align="center">{{ $value }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-bordered table-hover table-striped" style="background: #FFF;">
                <tbody>
                    <tr>
                        <th>KODE DOKUMEN</th>
                        <th>JUMLAH DOKUMEN KELUAR LCL BULAN {{strtoupper(date('F Y'))}}</th>
                    </tr>
                    @foreach($countbydoclcl as $key=>$value)
                    <tr>
                        <th>{{ $key }}</th>
                        <td align="center">{{ $value }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection

@section('custom_js')

@endsection