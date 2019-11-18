<?php

namespace App\Http\Controllers\Invoice;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MechanicController extends Controller
{
    
    public function tarifIndex()
    {
        if ( !$this->access->can('show.mechanic.tarif.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Tarif Mechanic";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Tarif Mechanic'
            ]
        ];        

        return view('mechanic.tarif')->with($data);
    }
    
    public function tarifCreate()
    {
        if ( !$this->access->can('show.mechanic.tarif.create') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Create Tarif Mechanic";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('mechanic-tarif'),
                'title' => 'Tarif Mechanic'
            ],
            [
                'action' => '',
                'title' => 'Create'
            ]
        ];        

        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        
        return view('mechanic.create-tarif')->with($data);
    }
    
    public function tarifStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'consolidator_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['_token']);
        $data['uid'] = \Auth::getUser()->name;
        $data['by_invoice'] = (isset($data['by_invoice'])) ? 'Y' : 'N';
        
        $consolidator = \App\Models\Consolidator::find($request->consolidator_id);
        $data['consolidator_name'] = $consolidator->NAMACONSOLIDATOR;
        
        $insert_id = \DB::table('mechanic_tarif')->insertGetId($data);
        
        if($insert_id){
            return redirect()->route('mechanic-tarif')->with('success', 'Tarif Mechanic has been created.');
        }
        
        return back()->with('error', 'Tarif Mechanic cannot create, please try again.')->withInput();
        
    }
    
    public function tarifEdit($id)
    {
        if ( !$this->access->can('show.mechanic.tarif.edit') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Edit Tarif Mechanic";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('mechanic-tarif'),
                'title' => 'Tarif Mechanic'
            ],
            [
                'action' => '',
                'title' => 'Edit'
            ]
        ];        

        $data['consolidators'] = \App\Models\Consolidator::select('TCONSOLIDATOR_PK as id','NAMACONSOLIDATOR as name')->get();
        $data['tarif'] = \DB::table('mechanic_tarif')->find($id);
        
        return view('mechanic.edit-tarif')->with($data);
    }
    
    public function tarifUpdate(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'consolidator_id' => 'required'
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        
        $data = $request->except(['_token']);
        $data['uid'] = \Auth::getUser()->name;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['by_invoice'] = (isset($data['by_invoice'])) ? 'Y' : 'N';
        
        $consolidator = \App\Models\Consolidator::find($request->consolidator_id);
        $data['consolidator_name'] = $consolidator->NAMACONSOLIDATOR;
        
        $update = \DB::table('mechanic_tarif')->where('id', $id)->update($data);
        
        if($update){
            return redirect()->route('mechanic-tarif')->with('success', 'Tarif Mechanic has been created.');
        }
        
        return back()->with('error', 'Tarif Mechanic cannot create, please try again.')->withInput();
    }
    
    public function tarifDelete($id)
    {
        $delete = \DB::table('mechanic-tarif')->where('id', $id)->delete();
        if($delete){
            // Delete
            return back()->with('success', 'Tarif Mechanic has been deleted.');
        }
        return back()->with('error', 'Tarif Mechanic cannot delete, please try again.')->withInput();
    }
    
    public function indexContainer()
    {
        if ( !$this->access->can('show.mechanic.container.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Data Container";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Data Container'
            ]
        ];        

        $data['tarifs'] = \DB::table('mechanic_tarif')->get();
        
        return view('mechanic.container')->with($data);
    }
    
    public function indexRekap()
    {
        if ( !$this->access->can('show.mechanic.rekap.index') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Rekap Mechanic";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => '',
                'title' => 'Rekap Mechanic'
            ]
        ];        

        return view('mechanic.rekap')->with($data);
    }
    
    public function printRekap($id)
    {
        $data['rekap'] = \DB::table('mechanic_rekap')->find($id);
        $data['itemsMain'] = \DB::table('mechanic_rekap_item')->where(array('rekap_id' => $id, 'type' => 'main'))->get();
        $data['itemsAdd'] = \DB::table('mechanic_rekap_item')->where(array('rekap_id' => $id, 'type' => 'add'))->get();
        $data['container'] = \App\Models\Container::find($data['rekap']->container_id);
        $data['consolidator'] = \App\Models\Consolidator::find($data['rekap']->consolidator_id);
        $data['terbilang'] = ucwords($this->terbilang($data['rekap']->total))." Rupiah";
        
        return view('print.rekap-mechanic')->with($data);
        
//        $pdf = \PDF::loadView('print.rekap-mechanic', $data)->setPaper('a4');
//        return $pdf->stream($data['invoice']->no_invoice.'-'.date('dmy').'.pdf');
    }
    
    public function createRekap(Request $request)
    {
//        return $request->all();
        
        $manifest_ids = explode(',',$request->manifest_id);
        $manifest = \App\Models\Manifest::whereIn('TMANIFEST_PK', $manifest_ids)->get();
        $tarif = \DB::table('mechanic_tarif')->find($request->tarif_id);
        
        if($tarif){
            //Insert Rekap Mechanic
            $dataRekap = $request->except(['_token','rounding','tarif_id']);
            $dataRekap['uid'] = \Auth::getUser()->name;

            $insert_id = \DB::table('mechanic_rekap')->insertGetId($dataRekap);

            if($insert_id){
                $subtotal_amount = array();
                //Insert Rekap Item
                foreach ($manifest as $item):

                    if($tarif->by_invoice == 'Y'){
                        
                        $amount = \App\Models\Invoice::select('total_amount as total')->where(array('manifest_id' => $item->TMANIFEST_PK, 'template_type' => 'Forwarder', 'renew' => 'N'))->first();
                        
                        if($amount){
                            $data['rekap_id'] = $insert_id;
                            $data['hbl'] = $item->NOHBL; 
                            $data['consignee'] = $item->CONSIGNEE;
                            $data['kgs'] = $item->WEIGHT;
                            $data['cbm'] = $item->MEAS;
                            $data['tarif'] = $tarif->tarif1;
                            $data['maxcbm'] = 0;
                            $data['amount'] = $amount->total;
                            $data['type'] = 'main';
                            $data['uid'] = \Auth::getUser()->name;

                            $subtotal_amount[] = $data['amount'];

                            \DB::table('mechanic_rekap_item')->insert($data);
                        }else{
                            continue;
                        }
                        
                    }else{
                        // Perhitungan CBM
                        $weight = $item->WEIGHT / 1000;
                        $meas = $item->MEAS;
                        $cbm = array($weight, $meas);

                        if(isset($request->rounding)){
                            $maxcbm = ceil(max($cbm));
                        }else{
                            $maxcbm = max($cbm);
                        }
                        
                        if($tarif->min_cbm == 0){
                            $harga = $tarif->tarif1;
                        }else{
                            if($maxcbm < $tarif->min_cbm){ $maxcbm = $tarif->min_cbm; }
                            $harga = $maxcbm*$tarif->tarif1;
                        }

                        $data['rekap_id'] = $insert_id;
                        $data['hbl'] = $item->NOHBL; 
                        $data['consignee'] = $item->CONSIGNEE;
                        $data['kgs'] = $item->WEIGHT;
                        $data['cbm'] = $item->MEAS;
                        $data['tarif'] = $tarif->tarif1;
                        $data['maxcbm'] = $maxcbm;
                        $data['amount'] = $harga;
                        $data['type'] = 'main';
                        $data['uid'] = \Auth::getUser()->name;

                        $subtotal_amount[] = $data['amount'];

                        \DB::table('mechanic_rekap_item')->insert($data);
                    }

                endforeach;

                //Update Rekap Mechanic
                $dataUpdate['subtotal'] = array_sum($subtotal_amount);
                $dataUpdate['ppn'] = ($dataUpdate['subtotal']*$dataRekap['tax'])/100;
                $dataUpdate['total'] = $dataUpdate['subtotal']+$dataUpdate['ppn'];

                \DB::table('mechanic_rekap')->where('id', $insert_id)->update($dataUpdate);

                return back()->with('success', 'Rekap Mechanic has been created.');
            }
        }else{
            return back()->with('error', 'Tarif for consolidator not found, please try again.');
        }

        return back()->with('error', 'Rekap Mechanic cannot create, please try again.')->withInput();
        
    }
    
    public function updateRekap(Request $request)
    {
        $rekap_id = $request->rekap_id;
        $dataRekap = \DB::table('mechanic_rekap')->find($rekap_id);
        
        $data = $request->except(['_token','rekap_id']);
        $subtotal_amount = array();
        
        foreach ($data as $key=>$value):
            $explode = explode('|', $key);
            $item_id = $explode[1];

            $item = \DB::table('mechanic_rekap_item')->find($item_id);
            
            $dataItem['tarif'] = $value;
            $dataItem['amount'] = $item->maxcbm*$value;
            $subtotal_amount[] = $dataItem['amount'];
            
            //Update Item
            \DB::table('mechanic_rekap_item')->where('id', $item_id)->update($dataItem);
        endforeach;

        //Update Rekap Mechanic
        $dataUpdate['subtotal'] = array_sum($subtotal_amount);
        $dataUpdate['ppn'] = ($dataUpdate['subtotal']*$dataRekap->tax)/100;
        $dataUpdate['total'] = $dataUpdate['subtotal']+$dataUpdate['ppn']+$dataRekap->cost;

        $update = \DB::table('mechanic_rekap')->where('id', $rekap_id)->update($dataUpdate);
        
        if($update){
            return back()->with('success', 'Rekap Mechanic has been updated.');
        }
        
        return back()->with('error', 'Rekap Mechanic cannot update, please try again.')->withInput();
    }
    
    public function detailRekap($id)
    {
        if ( !$this->access->can('show.mechanic.rekap.detail') ) {
            return view('errors.no-access');
        }
        
        $data['page_title'] = "Rekap Mechanic Detail";
        $data['page_description'] = "";
        $data['breadcrumbs'] = [
            [
                'action' => route('mechanic-rekap'),
                'title' => 'Rekap Mechanic'
            ],
            [
                'action' => '',
                'title' => 'Detail'
            ]
        ];        
        
        $data['rekap'] = \DB::table('mechanic_rekap')->find($id);
        $data['itemsMain'] = \DB::table('mechanic_rekap_item')->where(array('rekap_id' => $id, 'type' => 'main'))->get();
        $data['itemsAdd'] = \DB::table('mechanic_rekap_item')->where(array('rekap_id' => $id, 'type' => 'add'))->get();
        $data['container'] = \App\Models\Container::find($data['rekap']->container_id);
        $data['tarif'] = \DB::table('mechanic_tarif')->where('consolidator_id', $data['rekap']->consolidator_id)->first();
        $data['terbilang'] = ucwords($this->terbilang($data['rekap']->total))." Rupiah";
        
        return view('mechanic.detail-rekap')->with($data);
    }
    
    public function deleteRekap($id)
    {
        $delete = \DB::table('mechanic_rekap')->where('id', $id)->delete();
        if($delete){
            // Delete
            \DB::table('mechanic_rekap_item')->where('rekap_id', $id)->delete();
            return back()->with('success', 'Rekap Mechanic has been deleted.');
        }
        return back()->with('error', 'Rekap Mechanic cannot delete, please try again.')->withInput();
    }
    
    public function addCustomItemRekap(Request $request)
    {
//        return $request->all();
        $rekap = \DB::table('mechanic_rekap')->find($request->rekap_id);
        
        if($rekap){
            
            $data = $request->except(['_token']);
            $data['type'] = 'add';
            $data['uid'] = \Auth::getUser()->name;
            $insert_id = \DB::table('mechanic_rekap_item')->insertGetId($data);
            
            if($insert_id){
                
                $sum_cost = \DB::table('mechanic_rekap_item')->where(array('rekap_id' => $rekap->id, 'type' => 'add', 'ppn' => 0))->sum('amount');
                $sum_cost_ppn = \DB::table('mechanic_rekap_item')->where(array('rekap_id' => $rekap->id, 'type' => 'add', 'ppn' => 1))->sum('amount');
                $ppn = (($rekap->subtotal*$rekap->tax)/100)+(($sum_cost_ppn*10)/100);
                $total = $rekap->subtotal+$ppn+$sum_cost+$sum_cost_ppn;
                
                \DB::table('mechanic_rekap')->where('id', $rekap->id)->update(['cost' => $sum_cost+$sum_cost_ppn, 'ppn' => $ppn, 'total' => $total]);
//                return $sum_cost_ppn.' '.$ppn.' '.$total;
                return back()->with('success', 'Item berhasil di tambah.');
            }
            
            return back()->with('error', 'Tidak dapat menambah item rekap.');
            
        }
        
        return back()->with('error', 'Something went wrong, please try again later.');
    }
    
    public function removeCustomItemRekap($id)
    {
        $item_rekap = \DB::table('mechanic_rekap_item')->find($id);
        
        if($item_rekap){          
            // Update Mechanic
            $rekap = \DB::table('mechanic_rekap')->find($item_rekap->rekap_id);
            if($rekap){
                \DB::table('mechanic_rekap_item')->where('id', $id)->delete();
                
                $sum_cost = \DB::table('mechanic_rekap_item')->where(array('rekap_id' => $rekap->id, 'type' => 'add', 'ppn' => 0))->sum('amount');
                $sum_cost_ppn = \DB::table('mechanic_rekap_item')->where(array('rekap_id' => $rekap->id, 'type' => 'add', 'ppn' => 1))->sum('amount');
                $ppn = (($rekap->subtotal*$rekap->tax)/100)+(($sum_cost_ppn*10)/100);
                $total = $rekap->subtotal+$ppn+$sum_cost+$sum_cost_ppn;
                
//                $rekap->cost = $sum_cost;
//                $rekap->total = $total;
                
                $update = \DB::table('mechanic_rekap')->where('id', $rekap->id)->update(['cost' => $sum_cost+$sum_cost_ppn, 'ppn' => $ppn, 'total' => $total]);
                if($update){
                    return back()->with('success', 'Item mechanic berhasil di hapus.');
                }              
            }          
        }
        
        return back()->with('error', 'Something went wrong, please try again later.');     
    }
}
