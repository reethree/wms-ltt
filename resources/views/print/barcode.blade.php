@foreach($barcodes as $barcode)

    @if($barcode->ref_type == 'Manifest')
        <div style="margin: 20px 0">
            <div style="text-align: center;margin: 0 auto;">
                <span style="font-size:10px;">{{strtoupper($barcode->ref_action).' - '.date('d/m/Y H:i')}}</span>
                <h3 style="margin: 10px;">GATE PASS</h3>
                <h5 style="margin: 10px;">TPS LAUTAN TIRTA TRANSPORTAMA</h5>
                {!!QrCode::margin(0)->size(100)->generate($barcode->barcode)!!}
                <p style="font-size: 10px;margin: 0;padding: 0;">{{$barcode->barcode}}</p>
                <p style="font-size: 13px;font-weight: bold;">
                    {{$barcode->NOHBL}}<br />
                    {{$barcode->CONSIGNEE}}                   
                </p>
                <p style="font-size: 13px;">
                    NO. POS {{$barcode->NO_POS_BC11}}<br />
                    NO. BC11 {{$barcode->NO_BC11}}<br />
                    TGL. BC11 {{date('d/m/Y', strtotime($barcode->TGL_BC11))}}<br />
                    @if($barcode->NOPOL_RELEASE)
                    No. POL {{$barcode->NOPOL_RELEASE}}
                    @endif
                </p>
            </div>
        </div>
        <div style="display:block; page-break-before:always;"></div>
    @else
        <div style="margin: 20px 0">
            <div style="text-align: center;margin: 0 auto;">
                <span style="font-size:10px;">{{strtoupper($barcode->ref_action).' - '.date('d/m/Y H:i')}}</span>
                <h3 style="margin: 10px;">GATE PASS</h3>
                <h5 style="margin: 10px;">TPS LAUTAN TIRTA TRANSPORTAMA</h5>
                {!!QrCode::margin(0)->size(100)->generate($barcode->barcode)!!}
                <p style="font-size: 10px;margin: 0;padding: 0;">{{$barcode->barcode}}</p>
                <p style="font-size: 13px;font-weight: bold;">
                    {{$barcode->NOCONTAINER}}<br />
                    {{strtoupper($barcode->ref_type).' '.$barcode->SIZE."'"}} - {{$barcode->KD_TPS_ASAL}}<br />
                    {{$barcode->NO_SEAL}}
                </p>
                <p style="font-size: 13px;">
                    {{$barcode->VESSEL}} - VOYAGE {{$barcode->VOY}}
                </p>
                <p style="font-size: 13px;">
                    NO. PLP {{$barcode->NO_PLP}}<br />
                    TGL. PLP {{date('d/m/Y', strtotime($barcode->TGL_PLP))}}
                </p>
            </div>
        </div>
        <div style="display:block; page-break-before:always;"></div>
    @endif
@endforeach
