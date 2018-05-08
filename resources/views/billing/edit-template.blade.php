@extends('layout')

@section('content')

@include('partials.form-alert')

<div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Form Billing Template</h3>
    </div>
    <!-- /.box-header -->
    <form class="form-horizontal" action="{{ route('billing-template-update',$template->id) }}" method="POST">
        <div class="box-body">            
            <div class="row">
                <div class="col-md-6">
                    <input name="_token" type="hidden" value="{{ csrf_token() }}">
                    <div class="form-group">
                      <label for="name" class="col-sm-3 control-label">Template Name</label>
                      <div class="col-sm-8">
                          <input type="text" name="name" class="form-control" id="name" required value="{{$template->name}}">
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="roles" class="col-sm-3 control-label">Consolidator</label>
                      <div class="col-sm-8">
                            <select class="form-control select2 select2-hidden-accessible" name="consolidator_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                <option value="">Choose Consolidator</option>
                                @foreach($consolidators as $consolidator)
                                    <option value="{{ $consolidator->id }}" @if($template->consolidator_id == $consolidator->id) {{'selected'}} @endif>{{ $consolidator->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
<!--                    <div class="form-group">
                      <label for="recap_tax" class="col-sm-3 control-label">Recap Taxs</label>
                      <div class="col-sm-8">
                          <input type="number" name="recap_tax" class="form-control" id="recap_tax" value="{{$template->recap_tax}}">
                      </div>
                    </div>-->
                    <div class="form-group">
                      <label for="min_meas" class="col-sm-3 control-label">Min. Meas</label>
                      <div class="col-sm-5">
                          <input type="number" name="min_meas" class="form-control" id="min_meas" required value="{{$template->min_meas}}">
                      </div>
                    </div>
                </div>
                <div class="col-md-6"> 
                    <div class="form-group">
                      <label for="warehouse" class="col-sm-3 control-label">Warehouse</label>
                      <div class="col-sm-8">
                          <input type="checkbox" name="warehouse" id="warehouse" value="1" @if($template->warehouse == 'Y') {{'checked'}} @endif />
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="rounding" class="col-sm-3 control-label">Rounding</label>
                      <div class="col-sm-8">
                          <input type="checkbox" name="rounding" id="rounding" value="1" @if($template->rounding == 'Y') {{'checked'}} @endif/>
                      </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
            <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Simpan</button>
            <a href="{{ route('billing-template') }}" class="btn btn-danger pull-right" style="margin-right: 10px;"><i class="fa fa-close"></i> Keluar</a>
        </div>
        <!-- /.box-footer -->
    </form>
</div>

<script>
 
    function gridCompleteEvent()
    {
        var ids = jQuery("#itemTemplateGrid").jqGrid('getDataIDs'),
            apv = ''; 
    
        $('#btn-group-5').enableButtonGroup();   
            
        for(var i=0;i < ids.length;i++){ 
            var cl = ids[i];
            
            rowdata = $('#itemTemplateGrid').getRowData(cl);
            
            jQuery("#itemTemplateGrid").jqGrid('setRowData',ids[i],{action:''}); 
        } 
    }
    
    function onSelectRowEvent()
    {
        $('#btn-group-2').enableButtonGroup();
    }
    
    $(document).ready(function()
    {
        $('#btn-toolbar').disabledButtonGroup();
        $('#btn-group-4').enableButtonGroup();
        $('#btn-group-3').enableButtonGroup();
        $('#btn-group-1').enableButtonGroup();
        $('#btn-group-6').enableButtonGroup();

      //Binds onClick event to the "Refresh" button.
      $('#btn-refresh').click(function()
      {
            $('#itemTemplateGrid').jqGrid().trigger("reloadGrid");
            
            $('#item-template-form')[0].reset();
            $("#formula").val('N').trigger("change");
            $("#active").val('Y').trigger("change");
            $('#id').val("");
            
            //Disables all buttons within the toolbar
            $('#btn-toolbar').disabledButtonGroup();
            $('#btn-group-4').enableButtonGroup();
            $('#btn-group-3').enableButtonGroup();
            $('#btn-group-1').enableButtonGroup();
      });
        
      //Bind onClick event to the "Edit" button.
      $('#btn-edit').click(function()
      {
        //Gets the selected row id.
        rowid = $('#itemTemplateGrid').jqGrid('getGridParam', 'selrow');
        rowdata = $('#itemTemplateGrid').getRowData(rowid);
        $('#id').val(rowid);
        populateFormFields(rowdata, '');
        
        $("#item_name").val(rowdata.name);
        $("#formula").val(rowdata.formula).trigger("change");
        $("#active").val(rowdata.active).trigger("change");
        
//        console.log(rowdata);
        $('#btn-toolbar').disabledButtonGroup();
        $('#btn-group-1').enableButtonGroup();
        $('#btn-group-3').enableButtonGroup();
      });

      //Bind onClick event to the "Delete" button.
      $('#btn-delete').click(function()
      {
        if(!confirm('Apakah anda yakin?')){return false;}
        //Gets the selected row id
        rowid = $('#itemTemplateGrid').jqGrid('getGridParam', 'selrow');
        rowdata = $('#itemTemplateGrid').getRowData(rowid);
        
        $.ajax({
          type: 'GET',
          dataType : 'json',
          url: $('#item-template-form').attr('action') + '/delete/'+rowid,
          error: function (jqXHR, textStatus, errorThrown)
          {
            $('#app-loader').addClass('hidden');
            $('#main-panel-fieldset').removeAttr('disabled');
            alert('Something went wrong, please try again later.');
          },
          beforeSend:function()
          {
            $('#app-loader').removeClass('hidden');
            $('#main-panel-fieldset').attr('disabled','disabled');
          },
          success:function(json)
          {
            if(json.success) {
                $('#btn-toolbar').showAlertAfterElement('alert-success alert-custom', json.message, 5000);
            } else {
                $('#btn-toolbar').showAlertAfterElement('alert-danger alert-custom', json.message, 5000);
            }

            //Triggers the "Refresh" button funcionality.
            $('#btn-refresh').click();
          }
        });

      });

      //Bind onClick event to the "Save" button.
    $('#btn-save').click(function(){
        if(!confirm('Apakah anda yakin?')){return false;}
          
        var url = $('#item-template-form').attr('action');

        if($('#id').val()) {
            url += '/edit/'+$('#id').val();
        } else {
            url += '/create';
        }
        
        //Send an Ajax request to the server.
        $.ajax({
          type: 'POST',
          data: JSON.stringify($('#item-template-form').formToObject('')),
          dataType : 'json',
          url: url,
          error: function (jqXHR, textStatus, errorThrown)
          {
            $('#app-loader').addClass('hidden');
            $('#main-panel-fieldset').removeAttr('disabled');
            alert('Something went wrong, please try again later.');
          },
          beforeSend:function()
          {
            $('#app-loader').removeClass('hidden');
            $('#main-panel-fieldset').attr('disabled','disabled');
          },
          success:function(json)
          {
            if(json.success) {
              $('#btn-toolbar').showAlertAfterElement('alert-success alert-custom', json.message, 5000);
            } else {
              $('#btn-toolbar').showAlertAfterElement('alert-danger alert-custom', json.message, 5000);
            }

            //Triggers the "Close" button funcionality.
            $('#btn-refresh').click();
          }
        });
    });
    

});
    
</script>

<div class="box box-default">
    <div class="box-header with-border">
      <h3 class="box-title">Item Template</h3>
    </div>
    <div class="form-horizontal">
        <div class="box-body">            
            <div class="row" style="margin-bottom: 30px;">
                <div class="col-md-12">         
                    {{
                        GridRender::setGridId("itemTemplateGrid")
                        ->enableFilterToolbar()
                        ->setGridOption('mtype', 'POST')
                        ->setGridOption('url', URL::to('/billing/template/item/grid-data?templateid='.$template->id.'&_token='.csrf_token()))
                        ->setGridOption('rowNum', 10)
                        ->setGridOption('shrinkToFit', true)
                        ->setGridOption('sortname','id')
                        ->setGridOption('rownumbers', true)
                        ->setGridOption('height', '250')
                        ->setGridOption('rowList',array(10,20,50))
                        ->setGridOption('useColSpanStyle', true)
                        ->setNavigatorOptions('navigator', array('viewtext'=>'view'))
                        ->setNavigatorOptions('view',array('closeOnEscape'=>false))
                        ->setFilterToolbarOptions(array('autosearch'=>true))
                        ->setGridEvent('gridComplete', 'gridCompleteEvent')
                        ->setGridEvent('onSelectRow', 'onSelectRowEvent')
                        ->addColumn(array('key'=>true,'index'=>'id','hidden'=>true))
                        ->addColumn(array('label'=>'Item Name','index'=>'name','width'=>250))
                        ->addColumn(array('label'=>'Price','index'=>'price','width'=>160,'align'=>'right', 'formatter'=>'currency', 'formatoptions'=>array('decimalSeparator'=>',', 'thousandsSeparator'=> '.', 'decimalPlaces'=> '2')))
                        ->addColumn(array('label'=>'Formula','index'=>'formula', 'width'=>100,'align'=>'center'))
                        ->addColumn(array('label'=>'Taxes(%)','index'=>'tax', 'width'=>80,'align'=>'center'))
                        ->addColumn(array('label'=>'Active','index'=>'active','width'=>80,'align'=>'center'))
                        ->addColumn(array('label'=>'Created','index'=>'created_at','width'=>140,'align'=>'center'))
                        ->addColumn(array('label'=>'UID','index'=>'uid','width'=>140,'align'=>'center'))
                        ->renderGrid()
                    }}
                    
                    <div id="btn-toolbar" class="section-header btn-toolbar" role="toolbar" style="margin: 10px 0;">
                        <div id="btn-group-1" class="btn-group">
                            <button class="btn btn-default" id="btn-refresh"><i class="fa fa-refresh"></i> New/Refresh</button>
                        </div>
                        <div id="btn-group-2" class="btn-group">
                            <button class="btn btn-default" id="btn-edit"><i class="fa fa-edit"></i> Edit</button>
                            <button class="btn btn-default" id="btn-delete"><i class="fa fa-minus"></i> Delete</button>
                        </div>
                        <div id="btn-group-3" class="btn-group toolbar-block">
                            <button class="btn btn-default" id="btn-save"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
                </div>
            </div>
            
            <form class="form-horizontal" id="item-template-form" action="{{ route('billing-template-item') }}" method="POST">
                <div class="row">
                    <div class="col-md-6">
                        <input name="_token" type="hidden" value="{{ csrf_token() }}">
                        <input name="billing_template_id" id="billing_template_id" type="hidden" value="{{ $template->id }}">
                        <input name="id" id="id" type="hidden">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Item Name</label>
                            <div class="col-sm-8">
                                <input type="text" id="item_name" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Price</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        IDR                                    </div>
                                    <input type="number" id="price" name="price" class="form-control pull-right" required> 
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                          <label class="col-sm-3 control-label">Formula</label>
                          <div class="col-sm-8">
                                <select class="form-control select2" id="formula" name="formula" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                    <option value="N">Normal</option>
                                    <option value="X">CBM x Durasi x Harga</option>
                                </select>
                          </div>
                        </div>

                    </div>
                    <div class="col-md-6"> 
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tax(%)</label>
                            <div class="col-sm-8">
                                <div class="input-group">
                                    <input type="number" id="tax" name="tax" class="form-control pull-right" required value="10">
                                    <div class="input-group-addon">
                                        %
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Active</label>
                            <div class="col-sm-2">
                                <select class="form-control select2" id="active" name="active" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                    <option value="N">N</option>
                                    <option value="Y" selected>Y</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('custom_css')

<!-- Select2 -->
<link href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />

<!-- Bootstrap Switch -->
<link rel="stylesheet" href="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.css") }}">
@endsection

@section('custom_js')

<!-- Select2 -->
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>

<!-- Bootstrap Switch -->
<script src="{{ asset("/bower_components/AdminLTE/plugins/bootstrap-switch/bootstrap-switch.min.js") }}"></script>

<script type="text/javascript">
    $('select').select2(); 
  
//    $.fn.bootstrapSwitch.defaults.size = 'mini';
    $.fn.bootstrapSwitch.defaults.onColor = 'danger';
    $.fn.bootstrapSwitch.defaults.onText = 'Ya';
    $.fn.bootstrapSwitch.defaults.offText = 'Tidak';
    
    $("input[type='checkbox']").bootstrapSwitch();
  
</script>

@endsection
