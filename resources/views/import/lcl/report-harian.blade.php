@extends('layout')

@section('content')
<style>
    .datepicker.dropdown-menu {
        z-index: 9999 !important;
    }
    th.ui-th-column div{
        white-space:normal !important;
        height:auto !important;
        padding:2px;
    }
</style>

<div class="box">
    <div class="box-body table-responsive">
        <div class="row" style="margin-bottom: 30px;margin-right: 0;">
            <div class="col-md-12">
                <form action="" method="GET">
                    <div class="col-xs-12">Search By Date</div>
                    <div class="col-xs-12">&nbsp;</div>
                    <div class="col-xs-3">
                        <div class="input-group date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input type="text" id="date" name="date" class="form-control pull-right datepicker" value="{{$date}}">
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <button id="searchByDateBtn" class="btn btn-default" type="submit">Search</button>
                    </div>
                </form>
                <div class="col-xs-7">
                    <button class="btn btn-info pull-right" id="btn-print-in"><i class="fa fa-print"></i> Cetak Laporan Pemasukan</button>
                </div>
            </div>
        </div>
        {{
            GridRender::setGridId("lclDailyReportInGrid")
            ->enableFilterToolbar()
            ->setGridOption('filename', 'LCL_DailyReportContIn_'.Auth::getUser()->name)
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/lcl/manifest/grid-data?report=1&date='.$date.'&type=in&_token='.csrf_token()))
            ->setGridOption('rowNum', 50)
            ->setGridOption('shrinkToFit', true)
            ->setGridOption('sortname','TMANIFEST_PK')
            ->setGridOption('sortorder','DESC')
            ->setGridOption('rownumbers', true)
            ->setGridOption('caption', 'Data Pemasukan Cargo LCL')
            ->setGridOption('height', '300')
            ->setGridOption('rowList',array(50,100,200))
            ->setGridOption('useColSpanStyle', true)
            ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
            ->setNavigatorOptions('view',array('closeOnEscape'=>false))
            ->setFilterToolbarOptions(array('autosearch'=>true))
            ->addColumn(array('key'=>true,'index'=>'TMANIFEST_PK','hidden'=>true))
    
            ->addColumn(array('label'=>'Ex Container','index'=>'NOCONTAINER', 'width'=>150))
            ->addColumn(array('label'=>'Ex Kapal','index'=>'VESSEL','width'=>160,'align'=>'center'))
            ->addColumn(array('label'=>'Tgl. Tiba','index'=>'ETA', 'width'=>120,'align'=>'center','hidden'=>false))
            ->addColumn(array('label'=>'Tgl. OB','index'=>'tglmasuk', 'width'=>120,'align'=>'center','hidden'=>false))
            ->addColumn(array('label'=>'Consignee','index'=>'CONSIGNEE', 'width'=>350))
            ->addColumn(array('label'=>'Jumlah','index'=>'QUANTITY', 'width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Packing','index'=>'NAMAPACKING', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Kode Kemas','index'=>'KODE_KEMAS', 'width'=>100,'align'=>'center')) 
            ->addColumn(array('label'=>'KGS','index'=>'WEIGHT', 'width'=>120,'align'=>'center'))               
            ->addColumn(array('label'=>'M3','index'=>'MEAS', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'No. B/L','index'=>'NOHBL','width'=>160))
            ->addColumn(array('label'=>'Tgl. B/L','index'=>'TGL_HBL', 'width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'TPS Asal','index'=>'KD_TPS_ASAL', 'width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'No. BC 1.1','index'=>'NO_BC11', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Tgl. BC 1.1','index'=>'TGL_BC11', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'No. POS BC11','index'=>'NO_POS_BC11', 'width'=>150,'align'=>'center'))
            ->renderGrid()
        }}
        
        <br />
        <div class="row" style="margin-bottom: 30px;margin-right: 0;">
            <div class="col-sm-4">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>Jumlah B/L</th>
                            <td align="center">{{ $bl_in[0]->Jumlah }}</td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td align="center">{{ $bl_in[0]->Quantity }}</td>
                        </tr>
                        <tr>
                            <th>Weight</th>
                            <td align="center">{{ $bl_in[0]->Weight }}</td>
                        </tr>
                        <tr>
                            <th>Measurement</th>
                            <td align="center">{{ $bl_in[0]->Meas }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <hr /><br />
        <div class="row" style="margin-bottom: 30px;margin-right: 0;">
            <div class="col-md-12">
                <button class="btn btn-info pull-right" id="btn-print-out"><i class="fa fa-print"></i> Cetak Laporan Pengeluaran</button>
            </div>
        </div>
        {{
            GridRender::setGridId("lclDailyReportOutGrid")
            ->enableFilterToolbar()
            ->setGridOption('filename', 'LCL_DailyReportContOut_'.Auth::getUser()->name)
            ->setGridOption('mtype', 'POST')
            ->setGridOption('url', URL::to('/lcl/manifest/grid-data?report=1&date='.$date.'&type=out&_token='.csrf_token()))
            ->setGridOption('rowNum', 50)
            ->setGridOption('shrinkToFit', true)
            ->setGridOption('sortname','TMANIFEST_PK')
            ->setGridOption('sortorder','DESC')
            ->setGridOption('rownumbers', true)
            ->setGridOption('caption', 'Data Pengeluaran Cargo LCL')
            ->setGridOption('height', '300')
            ->setGridOption('rowList',array(50,100,200))
            ->setGridOption('useColSpanStyle', true)
            ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
            ->setNavigatorOptions('view',array('closeOnEscape'=>false))
            ->setFilterToolbarOptions(array('autosearch'=>true))
            ->addColumn(array('key'=>true,'index'=>'TMANIFEST_PK','hidden'=>true))
    
            ->addColumn(array('label'=>'Ex Container','index'=>'NOCONTAINER', 'width'=>150))
            ->addColumn(array('label'=>'Ex Kapal','index'=>'VESSEL','width'=>160,'align'=>'center'))
            ->addColumn(array('label'=>'Tgl. Tiba','index'=>'ETA', 'width'=>120,'align'=>'center','hidden'=>false))
            ->addColumn(array('label'=>'Tgl. OB','index'=>'tglmasuk', 'width'=>120,'align'=>'center','hidden'=>false))
            ->addColumn(array('label'=>'Consignee','index'=>'CONSIGNEE', 'width'=>350))
            ->addColumn(array('label'=>'Jumlah','index'=>'QUANTITY', 'width'=>80,'align'=>'center'))
            ->addColumn(array('label'=>'Packing','index'=>'NAMAPACKING', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Kode Kemas','index'=>'KODE_KEMAS', 'width'=>100,'align'=>'center')) 
            ->addColumn(array('label'=>'KGS','index'=>'WEIGHT', 'width'=>120,'align'=>'center'))               
            ->addColumn(array('label'=>'M3','index'=>'MEAS', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'No. B/L','index'=>'NOHBL','width'=>160))
            ->addColumn(array('label'=>'Kode Dokumen','index'=>'KD_DOK_INOUT', 'width'=>120,'align'=>'center','hidden'=>true))
            ->addColumn(array('label'=>'Nama Dokumen','index'=>'KODE_DOKUMEN', 'width'=>120))
            ->addColumn(array('label'=>'No. Dokumen','index'=>'NO_SPPB', 'width'=>160,'align'=>'center'))
            ->addColumn(array('label'=>'Tgl. Dokumen','index'=>'TGL_SPPB', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Pengeluaran','index'=>'tglrelease', 'width'=>150,'align'=>'center'))
            ->addColumn(array('label'=>'TPS Asal','index'=>'KD_TPS_ASAL', 'width'=>100,'align'=>'center'))
            ->addColumn(array('label'=>'No. BC 1.1','index'=>'NO_BC11', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'Tgl. BC 1.1','index'=>'TGL_BC11', 'width'=>120,'align'=>'center'))
            ->addColumn(array('label'=>'No. POS BC11','index'=>'NO_POS_BC11', 'width'=>150,'align'=>'center'))
            ->renderGrid()
        }}
        <br />
        <div class="row" style="margin-bottom: 30px;margin-right: 0;">
            <div class="col-sm-6">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                            <th>Quantity</th>
                            <th>Weight</th>
                            <th>Measurement</th>
                            <th>SOR %</th>
                        </tr>
                    
                    </thead>
                    <tbody>
                        <?php 
                            $meas_count = $bl_awal[0]->Meas+$bl_in[0]->Meas-$bl_out[0]->Meas;
                            $k_trisi = $meas_count*1000;     
                            $tot_sor = ($k_trisi / ($sor->kapasitas_default*1000)) * 100;
                        ?>
                        <tr>
                            <th>Stok Awal</th>
                            <td style="text-align: center;">{{ $bl_awal[0]->Jumlah }}</td>
                            <td style="text-align: center;">{{ $bl_awal[0]->Quantity }}</td>
                            <td style="text-align: center;">{{ $bl_awal[0]->Weight }}</td>
                            <td style="text-align: center;">{{ $bl_awal[0]->Meas }}</td>
                            <td rowspan="4" style="text-align: center;vertical-align: middle;">{{ number_format($tot_sor,'2',',','.') }}</td>
                        </tr>
                        <tr>
                            <th>Masuk</th>
                            <td style="text-align: center;">{{ $bl_in[0]->Jumlah }}</td>
                            <td style="text-align: center;">{{ $bl_in[0]->Quantity }}</td>
                            <td style="text-align: center;">{{ $bl_in[0]->Weight }}</td>
                            <td style="text-align: center;">{{ $bl_in[0]->Meas }}</td>
                        </tr>
                        <tr>
                            <th>Keluar</th>
                            <td style="text-align: center;">{{ $bl_out[0]->Jumlah }}</td>
                            <td style="text-align: center;">{{ $bl_out[0]->Quantity }}</td>
                            <td style="text-align: center;">{{ $bl_out[0]->Weight }}</td>
                            <td style="text-align: center;">{{ $bl_out[0]->Meas }}</td>
                        </tr>
                        <tr>
                            <th>Stok Akhir</th>
                            <td style="text-align: center;">{{ $bl_awal[0]->Jumlah+$bl_in[0]->Jumlah-$bl_out[0]->Jumlah }}</td>
                            <td style="text-align: center;">{{ $bl_awal[0]->Quantity+$bl_in[0]->Quantity-$bl_out[0]->Quantity }}</td>
                            <td style="text-align: center;">{{ $bl_awal[0]->Weight+$bl_in[0]->Weight-$bl_out[0]->Weight }}</td>
                            <td style="text-align: center;">{{ $bl_awal[0]->Meas+$bl_in[0]->Meas-$bl_out[0]->Meas }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-4">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>No.</th>
                            <th>Jenis Dokumen</th>
                            <th>Jumlah</th>
                        </tr>
                        <?php $sumdoc = 0;$i = 1;?>
                        @foreach($countbydoc as $key=>$value)
                        <tr>
                            <th style="text-align: center;">{{$i}}</th>
                            <th>{{ $key }}</th>
                            <td align="center">{{ $value }}</td>
                        </tr>
                        <?php $sumdoc += $value;$i++;?>
                        @endforeach
                        <tr>
                            <th colspan="2">Jumlah Total</th>
                            <th align="center" style="text-align: center;">{{$sumdoc}}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_css')

<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/datepicker/datepicker3.css") }}">

@endsection

@section('custom_js')

<script src="{{ asset("/bower_components/AdminLTE/plugins/datepicker/bootstrap-datepicker.js") }}"></script>
<script type="text/javascript">
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd',
        zIndex: 99
    });
    
    $('#btn-print-in').click(function() {
        var date = $('#date').val();
        window.open("{{ route('lcl-report-harian-cetak', array('','')) }}/"+date+"/in","preview wo fiat muat","width=1200,height=600,menubar=no,status=no,scrollbars=yes");
    });
    
    $('#btn-print-out').click(function() {
        var date = $('#date').val();
        window.open("{{ route('lcl-report-harian-cetak', array('','')) }}/"+date+"/out","preview wo fiat muat","width=1200,height=600,menubar=no,status=no,scrollbars=yes");
    });
</script>

@endsection