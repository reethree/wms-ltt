<?php

namespace App\Http\Controllers\Tps;

use Illuminate\Http\Request;

use App\Http\Requests;
//use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller as BaseController;

use Artisaninweb\SoapWrapper\Facades\SoapWrapper;

use App\Models\Containercy as DBContainerCy;
use App\Models\Container as DBContainer;
use App\Models\TpsCoariCont as DBCoariCont;
use App\Models\TpsCoariContDetail as DBCoariContDetail;
use App\Models\TpsCodecoContFcl as DBCodecoCont; 
use App\Models\TpsCodecoContFclDetail as DBCoariCodecoDetail;


class TpsScheduleController extends BaseController
{
    protected $wsdl;
    protected $user;
    protected $password;
    protected $kode;
    protected $response;

    public function __construct() {

        $this->wsdl = 'https://tpsonline.beacukai.go.id/tps/service.asmx?WSDL';
        $this->user = 'TRMA';
        $this->password = 'TRMA12345678';
        $this->kode = 'TRMA';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }
    
    public function createXmlCoariCont()
    {
        $f_cont = DBContainerCy::where('status_coari', 'Ready')->get();
        $l_cont = DBContainer::where('status_coari', 'Ready')->get();
        
        if(count($f_cont) > 0){
            foreach ($f_cont as $cont_f):
                
                // Reff Number
                $reff_number = $this->_getReffNumber();  

                if($reff_number){
                    $coaricont = new \App\Models\TpsCoariCont;
                    $coaricont->REF_NUMBER = $reff_number;
                    $coaricont->TGL_ENTRY = date('Y-m-d');
                    $coaricont->JAM_ENTRY = date('H:i:s');
                    $coaricont->UID = 'Cronjob';
                    $coaricont->STATUS_REF = 'NEW';

                    if($coaricont->save()){
                        $coaricontdetail = new \App\Models\TpsCoariContDetail;
                        $coaricontdetail->TPSCOARICONTXML_FK = $coaricont->TPSCOARICONTXML_PK;
                        $coaricontdetail->REF_NUMBER = $reff_number;
                        $coaricontdetail->KD_DOK = 5;
                        $coaricontdetail->KD_TPS = 'TRMA';
                        $coaricontdetail->NM_ANGKUT = (!empty($cont_f->VESSEL) ? $cont_f->VESSEL : 0);
                        $coaricontdetail->NO_VOY_FLIGHT = (!empty($cont_f->VOY) ? $cont_f->VOY : 0);
                        $coaricontdetail->CALL_SIGN = (!empty($cont_f->CALL_SIGN) ? $cont_f->CALL_SIGN : 0);
                        $coaricontdetail->TGL_TIBA = (!empty($cont_f->ETA) ? date('Ymd', strtotime($cont_f->ETA)) : '');
                        $coaricontdetail->KD_GUDANG = 'TRMA';
                        $coaricontdetail->NO_CONT = $cont_f->NOCONTAINER;
                        $coaricontdetail->UK_CONT = $cont_f->SIZE;
                        $coaricontdetail->NO_SEGEL = $cont_f->NO_SEAL;
                        $coaricontdetail->JNS_CONT = 'F';
                        $coaricontdetail->NO_BL_AWB = str_replace('&', '', $cont_f->NO_BL_AWB);
                        $coaricontdetail->TGL_BL_AWB = (!empty($cont_f->TGL_BL_AWB) ? date('Ymd', strtotime($cont_f->TGL_BL_AWB)) : '');
                        $coaricontdetail->NO_MASTER_BL_AWB = $cont_f->NOMBL;
                        $coaricontdetail->TGL_MASTER_BL_AWB = (!empty($cont_f->TGL_MASTER_BL) ? date('Ymd', strtotime($cont_f->TGL_MASTER_BL)) : '');
                        $coaricontdetail->ID_CONSIGNEE = $cont_f->ID_CONSIGNEE;
                        $coaricontdetail->CONSIGNEE = str_replace('&', '', $cont_f->CONSIGNEE);
                        $coaricontdetail->BRUTO = (!empty($cont_f->WEIGHT) ? $cont_f->WEIGHT : 0);
                        $coaricontdetail->NO_BC11 = $cont_f->NO_BC11;
                        $coaricontdetail->TGL_BC11 = (!empty($cont_f->TGL_BC11) ? date('Ymd', strtotime($cont_f->TGL_BC11)) : '');
                        $coaricontdetail->NO_POS_BC11 = '';
                        $coaricontdetail->KD_TIMBUN = 'GD';
                        $coaricontdetail->KD_DOK_INOUT = 3;
                        $coaricontdetail->NO_DOK_INOUT = (!empty($cont_f->NO_PLP) ? $cont_f->NO_PLP : '');
                        $coaricontdetail->TGL_DOK_INOUT = (!empty($cont_f->TGL_PLP) ? date('Ymd', strtotime($cont_f->TGL_PLP)) : '');
                        $coaricontdetail->WK_INOUT = date('Ymd', strtotime($cont_f->TGLMASUK)).date('His', strtotime($cont_f->JAMMASUK));
                        $coaricontdetail->KD_SAR_ANGKUT_INOUT = 1;
                        $coaricontdetail->NO_POL = $cont_f->NOPOL;
                        $coaricontdetail->FL_CONT_KOSONG = 2;
                        $coaricontdetail->ISO_CODE = '';
                        $coaricontdetail->PEL_MUAT = $cont_f->PEL_MUAT;
                        $coaricontdetail->PEL_TRANSIT = $cont_f->PEL_TRANSIT;
                        $coaricontdetail->PEL_BONGKAR = $cont_f->PEL_BONGKAR;
                        $coaricontdetail->GUDANG_TUJUAN = 'TRMA';
                        $coaricontdetail->UID = \Auth::getUser()->name;
                        $coaricontdetail->NOURUT = 1;
                        $coaricontdetail->RESPONSE = '';
                        $coaricontdetail->STATUS_TPS = 1;
                        $coaricontdetail->KODE_KANTOR = '040300';
                        $coaricontdetail->NO_DAFTAR_PABEAN = $cont_f->NO_DAFTAR_PABEAN;
                        $coaricontdetail->TGL_DAFTAR_PABEAN = (!empty($cont_f->TGL_DAFTAR_PABEAN) ? date('Ymd', strtotime($cont_f->TGL_DAFTAR_PABEAN)) : '');
                        $coaricontdetail->NO_SEGEL_BC = '';
                        $coaricontdetail->TGL_SEGEL_BC = '';
                        $coaricontdetail->NO_IJIN_TPS = '';
                        $coaricontdetail->TGL_IJIN_TPS = '';
                        $coaricontdetail->RESPONSE_IPC = '';
                        $coaricontdetail->STATUS_TPS_IPC = '';
                        $coaricontdetail->NOPLP = '';
                        $coaricontdetail->TGLPLP = '';
                        $coaricontdetail->FLAG_REVISI = '';
                        $coaricontdetail->TGL_REVISI = '';
                        $coaricontdetail->TGL_REVISI_UPDATE = '';
                        $coaricontdetail->KD_TPS_ASAL = $cont_f->KD_TPS_ASAL;
                        $coaricontdetail->FLAG_UPD = '';
                        $coaricontdetail->RESPONSE_MAL0 = '';
                        $coaricontdetail->STATUS_TPS_MAL0 = '';
                        $coaricontdetail->TGL_ENTRY = date('Y-m-d');
                        $coaricontdetail->JAM_ENTRY = date('H:i:s');

                        if($coaricontdetail->save()){
                            $cont_f->status_coari = 'XML Created';
                            $cont_f->REF_NUMBER = $reff_number;
                            $cont_f->save();
                        }
                    }
                } 
                
            endforeach;
        }
        
        if(count($l_cont) > 0){
            foreach ($l_cont as $cont_l):
                
                // Reff Number
                $reff_number = $this->_getReffNumber();   
                if($reff_number){
                    $coaricont = new \App\Models\TpsCoariCont;
                    $coaricont->REF_NUMBER = $reff_number;
                    $coaricont->TGL_ENTRY = date('Y-m-d');
                    $coaricont->JAM_ENTRY = date('H:i:s');
                    $coaricont->UID = 'Cronjob';
                    $coaricont->STATUS_REF = 'NEW';

                    if($coaricont->save()){
                        $coaricontdetail = new \App\Models\TpsCoariContDetail;
                        $coaricontdetail->TPSCOARICONTXML_FK = $coaricont->TPSCOARICONTXML_PK;
                        $coaricontdetail->REF_NUMBER = $reff_number;
                        $coaricontdetail->KD_DOK = 5;
                        $coaricontdetail->KD_TPS = 'TRMA';
                        $coaricontdetail->NM_ANGKUT = (!empty($cont_l->VESSEL) ? $cont_l->VESSEL : 0);
                        $coaricontdetail->NO_VOY_FLIGHT = (!empty($cont_l->VOY) ? $cont_l->VOY : 0);
                        $coaricontdetail->CALL_SIGN = (!empty($cont_l->CALL_SIGN) ? $cont_l->CALL_SIGN : 0);
                        $coaricontdetail->TGL_TIBA = (!empty($cont_l->ETA) ? date('Ymd', strtotime($cont_l->ETA)) : '');
                        $coaricontdetail->KD_GUDANG = 'TRMA';
                        $coaricontdetail->NO_CONT = $cont_l->NOCONTAINER;
                        $coaricontdetail->UK_CONT = $cont_l->SIZE;
                        $coaricontdetail->NO_SEGEL = $cont_l->NO_SEAL;
                        $coaricontdetail->JNS_CONT = 'L';
                        $coaricontdetail->NO_BL_AWB = '';
                        $coaricontdetail->TGL_BL_AWB = '';
                        $coaricontdetail->NO_MASTER_BL_AWB = $cont_l->NOMBL;
                        $coaricontdetail->TGL_MASTER_BL_AWB = (!empty($cont_l->TGL_MASTER_BL) ? date('Ymd', strtotime($cont_l->TGL_MASTER_BL)) : '');
                        $coaricontdetail->ID_CONSIGNEE = str_replace(array('.','-'), array(''), $cont_l->ID_CONSOLIDATOR);
                        $coaricontdetail->CONSIGNEE = $cont_l->NAMACONSOLIDATOR;
                        $coaricontdetail->BRUTO = (!empty($cont_l->WEIGHT) ? $cont_l->WEIGHT : 0);
                        $coaricontdetail->NO_BC11 = $cont_l->NO_BC11;
                        $coaricontdetail->TGL_BC11 = (!empty($cont_l->TGL_BC11) ? date('Ymd', strtotime($cont_l->TGL_BC11)) : '');
                        $coaricontdetail->NO_POS_BC11 = '';
                        $coaricontdetail->KD_TIMBUN = 'GD';
                        $coaricontdetail->KD_DOK_INOUT = 3;
                        $coaricontdetail->NO_DOK_INOUT = (!empty($cont_l->NO_PLP) ? $cont_l->NO_PLP : '');
                        $coaricontdetail->TGL_DOK_INOUT = (!empty($cont_l->TGL_PLP) ? date('Ymd', strtotime($cont_l->TGL_PLP)) : '');
                        $coaricontdetail->WK_INOUT = date('Ymd', strtotime($cont_l->TGLMASUK)).date('His', strtotime($cont_l->JAMMASUK));
                        $coaricontdetail->KD_SAR_ANGKUT_INOUT = 1;
                        $coaricontdetail->NO_POL = $cont_l->NOPOL;
                        $coaricontdetail->FL_CONT_KOSONG = 2;
                        $coaricontdetail->ISO_CODE = '';
                        $coaricontdetail->PEL_MUAT = $cont_l->PEL_MUAT;
                        $coaricontdetail->PEL_TRANSIT = $cont_l->PEL_TRANSIT;
                        $coaricontdetail->PEL_BONGKAR = $cont_l->PEL_BONGKAR;
                        $coaricontdetail->GUDANG_TUJUAN = 'TRMA';
                        $coaricontdetail->UID = \Auth::getUser()->name;
                        $coaricontdetail->NOURUT = 1;
                        $coaricontdetail->RESPONSE = '';
                        $coaricontdetail->STATUS_TPS = 1;
                        $coaricontdetail->KODE_KANTOR = '040300';
                        $coaricontdetail->NO_DAFTAR_PABEAN = '';
                        $coaricontdetail->TGL_DAFTAR_PABEAN = '';
                        $coaricontdetail->NO_SEGEL_BC = '';
                        $coaricontdetail->TGL_SEGEL_BC = '';
                        $coaricontdetail->NO_IJIN_TPS = '';
                        $coaricontdetail->TGL_IJIN_TPS = '';
                        $coaricontdetail->RESPONSE_IPC = '';
                        $coaricontdetail->STATUS_TPS_IPC = '';
                        $coaricontdetail->NOPLP = $cont_l->NO_PLP;
                        $coaricontdetail->TGLPLP = (!empty($cont_l->TGL_PLP) ? date('Ymd', strtotime($cont_l->TGL_PLP)) : '');
                        $coaricontdetail->FLAG_REVISI = '';
                        $coaricontdetail->TGL_REVISI = '';
                        $coaricontdetail->TGL_REVISI_UPDATE = '';
                        $coaricontdetail->KD_TPS_ASAL = $cont_l->KD_TPS_ASAL;
                        $coaricontdetail->FLAG_UPD = '';
                        $coaricontdetail->RESPONSE_MAL0 = '';
                        $coaricontdetail->STATUS_TPS_MAL0 = '';
                        $coaricontdetail->TGL_ENTRY = date('Y-m-d');
                        $coaricontdetail->JAM_ENTRY = date('H:i:s');

                        if($coaricontdetail->save()){
                            $cont_l->status_coari = 'XML Created';
                            $cont_l->REF_NUMBER_IN = $reff_number;
                            $cont_l->save();                    
                        }
                    }
                } 
                
            endforeach;
        }
    }
    
    public function createXmlCodecoCont()
    {
        $f_cont = DBContainerCy::where('status_codeco', 'Ready')->get();
        $l_cont = DBContainer::where('status_codeco', 'Ready')->get();
        
        if(count($f_cont) > 0){
            
            foreach ($f_cont as $cont_f):
            
                $reff_number = $this->_getReffNumber();   
                if($reff_number){

                    $codecocont = new \App\Models\TpsCodecoContFcl();
                    $codecocont->NOJOBORDER = $cont_f->NoJob;
                    $codecocont->REF_NUMBER = $reff_number;
                    $codecocont->TGL_ENTRY = date('Y-m-d');
                    $codecocont->JAM_ENTRY = date('H:i:s');
                    $codecocont->UID = 'Cronjob';
                    $codecocont->STATUS_REF = 'NEW';

                    if($codecocont->save()){
                        $codecocontdetail = new \App\Models\TpsCodecoContFclDetail;
                        $codecocontdetail->TPSCODECOCONTXML_FK = $codecocont->TPSCODECOCONTXML_PK;
                        $codecocontdetail->REF_NUMBER = $reff_number;
                        $codecocontdetail->NOJOBORDER = $cont_f->NoJob;
                        $codecocontdetail->KD_DOK = 6;
                        $codecocontdetail->KD_TPS = 'TRMA';
                        $codecocontdetail->NM_ANGKUT = (!empty($cont_f->VESSEL) ? $cont_f->VESSEL : 0);
                        $codecocontdetail->NO_VOY_FLIGHT = (!empty($cont_f->VOY) ? $cont_f->VOY : 0);
                        $codecocontdetail->CALL_SIGN = (!empty($cont_f->CALLSIGN) ? $cont_f->CALLSIGN : 0);
                        $codecocontdetail->TGL_TIBA = (!empty($cont_f->ETA) ? date('Ymd', strtotime($cont_f->ETA)) : '');
                        $codecocontdetail->KD_GUDANG = 'TRMA';
                        $codecocontdetail->NO_CONT = $cont_f->NOCONTAINER;
                        $codecocontdetail->UK_CONT = $cont_f->SIZE;
                        $codecocontdetail->NO_SEGEL = $cont_f->NOSEGEL;
                        $codecocontdetail->JNS_CONT = 'F';
                        $codecocontdetail->NO_BL_AWB = '';
                        $codecocontdetail->TGL_BL_AWB = '';
                        $codecocontdetail->NO_MASTER_BL_AWB = $cont_f->NOMBL;
                        $codecocontdetail->TGL_MASTER_BL_AWB = (!empty($cont_f->TGLMBL) ? date('Ymd', strtotime($cont_f->TGLMBL)) : '');
                        $codecocontdetail->ID_CONSIGNEE = $cont_f->ID_CONSIGNEE;
                        $codecocontdetail->CONSIGNEE = str_replace('&', '', $cont_f->CONSIGNEE);
                        $codecocontdetail->BRUTO = (!empty($cont_f->WEIGHT) ? $cont_f->WEIGHT : 0);
                        $codecocontdetail->NO_BC11 = $cont_f->NO_BC11;
                        $codecocontdetail->TGL_BC11 = (!empty($cont_f->TGL_BC11) ? date('Ymd', strtotime($cont_f->TGL_BC11)) : '');
                        $codecocontdetail->NO_POS_BC11 = $cont_f->NO_POS_BC11;
                        $codecocontdetail->KD_TIMBUN = 'LAP';
                        $codecocontdetail->KD_DOK_INOUT = (!empty($cont_f->KD_DOK_INOUT) ? $cont_f->KD_DOK_INOUT : 3);
                        $codecocontdetail->NO_DOK_INOUT = (!empty($cont_f->NO_SPPB) ? $cont_f->NO_SPPB : '');
                        $codecocontdetail->TGL_DOK_INOUT = (!empty($cont_f->TGL_SPPB) ? date('Ymd', strtotime($cont_f->TGL_SPPB)) : '');
                        $codecocontdetail->WK_INOUT = date('Ymd', strtotime($cont_f->TGLRELEASE)).date('His', strtotime($cont_f->JAMRELEASE));
                        $codecocontdetail->KD_SAR_ANGKUT_INOUT = 1;
                        $codecocontdetail->NO_POL = $cont_f->NOPOL_OUT;
                        $codecocontdetail->FL_CONT_KOSONG = 2;
                        $codecocontdetail->ISO_CODE = '';
                        $codecocontdetail->PEL_MUAT = $cont_f->PEL_MUAT;
                        $codecocontdetail->PEL_TRANSIT = $cont_f->PEL_TRANSIT;
                        $codecocontdetail->PEL_BONGKAR = $cont_f->PEL_BONGKAR;
                        $codecocontdetail->GUDANG_TUJUAN = 'TRMA';
                        $codecocontdetail->UID = \Auth::getUser()->name;
                        $codecocontdetail->NOURUT = 1;
                        $codecocontdetail->RESPONSE = '';
                        $codecocontdetail->STATUS_TPS = 1;
                        $codecocontdetail->KODE_KANTOR = '040300';
                        $codecocontdetail->NO_DAFTAR_PABEAN = (!empty($cont_f->NO_PIB) ? $cont_f->NO_PIB : '');
                        $codecocontdetail->TGL_DAFTAR_PABEAN = (!empty($cont_f->TGL_PIB) ? date('Ymd', strtotime($cont_f->TGL_PIB)) : '');
                        $codecocontdetail->NO_SEGEL_BC = '';
                        $codecocontdetail->TGL_SEGEL_BC = '';
                        $codecocontdetail->NO_IJIN_TPS = '';
                        $codecocontdetail->TGL_IJIN_TPS = '';
                        $codecocontdetail->RESPONSE_IPC = '';
                        $codecocontdetail->STATUS_TPS_IPC = '';
                        $codecocontdetail->NOSPPB = (!empty($cont_f->NO_SPPB) ? $cont_f->NO_SPPB : '');;
                        $codecocontdetail->TGLSPPB = (!empty($cont_f->TGL_SPPB) ? date('Ymd', strtotime($cont_f->TGL_SPPB)) : '');;
                        $codecocontdetail->FLAG_REVISI = '';
                        $codecocontdetail->TGL_REVISI = '';
                        $codecocontdetail->TGL_REVISI_UPDATE = '';
                        $codecocontdetail->KD_TPS_ASAL = $cont_f->KD_TPS_ASAL;
                        $codecocontdetail->RESPONSE_MAL0 = '';
                        $codecocontdetail->STATUS_TPS_MAL0 = '';
                        $codecocontdetail->TGL_ENTRY = date('Y-m-d');
                        $codecocontdetail->JAM_ENTRY = date('H:i:s');

                        if($codecocontdetail->save()){
                            $cont_f->status_codeco = 'XML Created';
                            $cont_f->REF_NUMBER_OUT = $reff_number;
                            $cont_f->save();

                        }
                    }

                }

            endforeach;
        }
        
        if(count($l_cont) > 0){
            
            foreach ($l_cont as $cont_l):
            
                $reff_number = $this->_getReffNumber();   
                if($reff_number){

                    $codecocont = new \App\Models\TpsCodecoContFcl();
                    $codecocont->NOJOBORDER = $cont_l->NoJob;
                    $codecocont->REF_NUMBER = $reff_number;
                    $codecocont->TGL_ENTRY = date('Y-m-d');
                    $codecocont->JAM_ENTRY = date('H:i:s');
                    $codecocont->UID = 'Cronjob';
                    $codecocont->STATUS_REF = 'NEW';

                    if($codecocont->save()){
                        $codecocontdetail = new \App\Models\TpsCodecoContFclDetail;
                        $codecocontdetail->TPSCODECOCONTXML_FK = $codecocont->TPSCODECOCONTXML_PK;
                        $codecocontdetail->REF_NUMBER = $reff_number;
                        $codecocontdetail->NOJOBORDER = $cont_l->NoJob;
                        $codecocontdetail->KD_DOK = 6;
                        $codecocontdetail->KD_TPS = 'TRMA';
                        $codecocontdetail->NM_ANGKUT = (!empty($cont_l->VESSEL) ? $cont_l->VESSEL : 0);
                        $codecocontdetail->NO_VOY_FLIGHT = (!empty($cont_l->VOY) ? $cont_l->VOY : 0);
                        $codecocontdetail->CALL_SIGN = (!empty($cont_l->CALL_SIGN) ? $cont_l->CALL_SIGN : 0);
                        $codecocontdetail->TGL_TIBA = (!empty($cont_l->ETA) ? date('Ymd', strtotime($cont_l->ETA)) : '');
                        $codecocontdetail->KD_GUDANG = 'TRMA';
                        $codecocontdetail->NO_CONT = $cont_l->NOCONTAINER;
                        $codecocontdetail->UK_CONT = $cont_l->SIZE;
                        $codecocontdetail->NO_SEGEL = $cont_l->NO_SEAL;
                        $codecocontdetail->JNS_CONT = 'L';
                        $codecocontdetail->NO_BL_AWB = '';
                        $codecocontdetail->TGL_BL_AWB = '';
                        $codecocontdetail->NO_MASTER_BL_AWB = $cont_l->NOMBL;
                        $codecocontdetail->TGL_MASTER_BL_AWB = (!empty($cont_l->TGL_MASTER_BL) ? date('Ymd', strtotime($cont_l->TGL_MASTER_BL)) : '');
                        $codecocontdetail->ID_CONSIGNEE = str_replace(array('.','-'), array(''),$cont_l->ID_CONSOLIDATOR);
                        $codecocontdetail->CONSIGNEE = $cont_l->NAMACONSOLIDATOR;
                        $codecocontdetail->BRUTO = (!empty($cont_l->WEIGHT) ? $cont_l->WEIGHT : 0);
                        $codecocontdetail->NO_BC11 = $cont_l->NO_BC11;
                        $codecocontdetail->TGL_BC11 = (!empty($cont_l->TGL_BC11) ? date('Ymd', strtotime($cont_l->TGL_BC11)) : '');
                        $codecocontdetail->NO_POS_BC11 = '';
                        $codecocontdetail->KD_TIMBUN = 'GD';
                        $codecocontdetail->KD_DOK_INOUT = 40;
                        $codecocontdetail->NO_DOK_INOUT = (!empty($cont_l->NO_PLP) ? $cont_l->NO_PLP : '');
                        $codecocontdetail->TGL_DOK_INOUT = (!empty($cont_l->TGL_PLP) ? date('Ymd', strtotime($cont_l->TGL_PLP)) : '');
                        $codecocontdetail->WK_INOUT = date('Ymd', strtotime($cont_l->TGLBUANGMTY)).date('His', strtotime($cont_l->JAMBUANGMTY));
                        $codecocontdetail->KD_SAR_ANGKUT_INOUT = 1;
                        $codecocontdetail->NO_POL = $cont_l->NOPOL_MTY;
                        $codecocontdetail->FL_CONT_KOSONG = 1;
                        $codecocontdetail->ISO_CODE = '';
                        $codecocontdetail->PEL_MUAT = $cont_l->PEL_MUAT;
                        $codecocontdetail->PEL_TRANSIT = $cont_l->PEL_TRANSIT;
                        $codecocontdetail->PEL_BONGKAR = $cont_l->PEL_BONGKAR;
                        $codecocontdetail->GUDANG_TUJUAN = 'TRMA';
                        $codecocontdetail->UID = \Auth::getUser()->name;
                        $codecocontdetail->NOURUT = 1;
                        $codecocontdetail->RESPONSE = '';
                        $codecocontdetail->STATUS_TPS = '';
                        $codecocontdetail->KODE_KANTOR = '040300';
                        $codecocontdetail->NO_DAFTAR_PABEAN = '';
                        $codecocontdetail->TGL_DAFTAR_PABEAN = '';
                        $codecocontdetail->NO_SEGEL_BC = '';
                        $codecocontdetail->TGL_SEGEL_BC = '';
                        $codecocontdetail->NO_IJIN_TPS = '';
                        $codecocontdetail->TGL_IJIN_TPS = '';
                        $codecocontdetail->RESPONSE_IPC = '';
                        $codecocontdetail->STATUS_TPS_IPC = '';
                        $codecocontdetail->NOSPPB = '';
                        $codecocontdetail->TGLSPPB = '';
                        $codecocontdetail->FLAG_REVISI = '';
                        $codecocontdetail->TGL_REVISI = '';
                        $codecocontdetail->TGL_REVISI_UPDATE = '';
                        $codecocontdetail->KD_TPS_ASAL = $cont_l->KD_TPS_ASAL;
                        $codecocontdetail->RESPONSE_MAL0 = '';
                        $codecocontdetail->STATUS_TPS_MAL0 = '';
                        $codecocontdetail->TGL_ENTRY = date('Y-m-d');
                        $codecocontdetail->JAM_ENTRY = date('H:i:s');

                        if($codecocontdetail->save()){
                            $cont_l->status_codeco = 'XML Created';
                            $cont_l->REF_NUMBER_OUT = $reff_number;
                            $cont_l->save();

                        }
                    }

                }
            
            endforeach;
        }
    }
    
    public function createXmlCoariKms()
    {
        
    }

    public function createXmlCodecoKms()
    {
        
    }
    
    public function sendXmlCoariCont()
    {
        $dataHeader = DBCoariCont::where(array('UID'=>'Cronjob','STATUS_REF'=>'NEW'))->first();
        
        if(count($dataHeader) > 0){
            
            $dataDetail = \App\Models\TpsCoariContDetail::where('TPSCOARICONTXML_FK', $dataHeader->TPSCOARICONTXML_PK)->first();
        
//            if($dataDetail->STATUS_TPS == 2){
//                $reff_number = $this->getReffNumber();
//                $dataDetail->REF_NUMBER = $reff_number;
//                $dataDetail->FLAG_REVISI = (empty($dataDetail->FLAG_REVISI) ? 0 : $dataDetail->FLAG_REVISI) + 1;
//                $dataDetail->TGL_REVISI = date('Y-m-d H:i:s');
//                $dataDetail->STATUS_TPS = 1;
//
//                $dataDetail->save();
//            }

            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><DOCUMENT></DOCUMENT>');

            $xmldata = $xml->addAttribute('xmlns', 'cococont.xsd');
            $xmldata = $xml->addchild('COCOCONT');
            $header = $xmldata->addchild('HEADER');
            $detail = $xmldata->addchild('DETIL');
            $cont = $detail->addChild('CONT');

            $header->addChild('KD_DOK', ($dataDetail->KD_DOK != '') ? $dataDetail->KD_DOK : '');
            $header->addChild('KD_TPS', ($dataDetail->KD_TPS != '') ? $dataDetail->KD_TPS : '');
            $header->addChild('NM_ANGKUT', ($dataDetail->NM_ANGKUT != '') ? $dataDetail->NM_ANGKUT : '');
            $header->addChild('NO_VOY_FLIGHT', ($dataDetail->NO_VOY_FLIGHT != '') ? $dataDetail->NO_VOY_FLIGHT : '');
            $header->addChild('CALL_SIGN', ($dataDetail->CALL_SIGN != '') ? $dataDetail->CALL_SIGN : '');
            $header->addChild('TGL_TIBA', ($dataDetail->TGL_TIBA != '') ? $dataDetail->TGL_TIBA : '');
            $header->addChild('KD_GUDANG', ($dataDetail->KD_GUDANG != '') ? $dataDetail->KD_GUDANG : '');
            $header->addChild('REF_NUMBER', ($dataHeader->REF_NUMBER != '') ? $dataDetail->REF_NUMBER : '');

            $cont->addChild('NO_CONT', ($dataDetail->NO_CONT != '') ? $dataDetail->NO_CONT : '');
            $cont->addChild('UK_CONT', ($dataDetail->UK_CONT != '') ? $dataDetail->UK_CONT : '');
            $cont->addChild('NO_SEGEL', ($dataDetail->NO_SEGEL != '') ? $dataDetail->NO_SEGEL : '');
            $cont->addChild('JNS_CONT', ($dataDetail->JNS_CONT != '') ? $dataDetail->JNS_CONT : '');
            $cont->addChild('NO_BL_AWB', ($dataDetail->NO_BL_AWB != '') ? $dataDetail->NO_BL_AWB : '');
            $cont->addChild('TGL_BL_AWB', ($dataDetail->TGL_BL_AWB != '') ? $dataDetail->TGL_BL_AWB : '');
            $cont->addChild('NO_MASTER_BL_AWB', ($dataDetail->NO_MASTER_BL_AWB != '') ? $dataDetail->NO_MASTER_BL_AWB : '');
            $cont->addChild('TGL_MASTER_BL_AWB', ($dataDetail->TGL_MASTER_BL_AWB != '') ? $dataDetail->TGL_MASTER_BL_AWB : '');
            $cont->addChild('ID_CONSIGNEE', ($dataDetail->ID_CONSIGNEE != 000000000000000) ? $dataDetail->ID_CONSIGNEE : '');
            $cont->addChild('CONSIGNEE', ($dataDetail->CONSIGNEE != '') ? $dataDetail->CONSIGNEE : '');
            $cont->addChild('BRUTO', ($dataDetail->BRUTO != '') ? $dataDetail->BRUTO : '');
            $cont->addChild('NO_BC11', ($dataDetail->NO_BC11 != '') ? $dataDetail->NO_BC11 : '');
            $cont->addChild('TGL_BC11', ($dataDetail->TGL_BC11 != '') ? $dataDetail->TGL_BC11 : '');        
            $cont->addChild('NO_POS_BC11', ($dataDetail->NO_POS_BC11 != '') ? $dataDetail->NO_POS_BC11 : '');
            $cont->addChild('KD_TIMBUN', ($dataDetail->KD_TIMBUN != '') ? $dataDetail->KD_TIMBUN : '');
            $cont->addChild('KD_DOK_INOUT', ($dataDetail->KD_DOK_INOUT != '') ? $dataDetail->KD_DOK_INOUT : '');
            $cont->addChild('NO_DOK_INOUT', ($dataDetail->NO_DOK_INOUT != '') ? $dataDetail->NO_DOK_INOUT : '');
            $cont->addChild('TGL_DOK_INOUT', ($dataDetail->TGL_DOK_INOUT != '') ? $dataDetail->TGL_DOK_INOUT : '');
            $cont->addChild('WK_INOUT', ($dataDetail->WK_INOUT != '') ? $dataDetail->WK_INOUT : '');
            $cont->addChild('KD_SAR_ANGKUT_INOUT', ($dataDetail->KD_SAR_ANGKUT_INOUT != '') ? $dataDetail->KD_SAR_ANGKUT_INOUT : '');
            $cont->addChild('NO_POL', ($dataDetail->NO_POL != '') ? $dataDetail->NO_POL : '');
            $cont->addChild('FL_CONT_KOSONG', ($dataDetail->FL_CONT_KOSONG != '') ? $dataDetail->FL_CONT_KOSONG : '');
            $cont->addChild('ISO_CODE', ($dataDetail->ISO_CODE != '') ? $dataDetail->ISO_CODE : '');
            $cont->addChild('PEL_MUAT', ($dataDetail->PEL_MUAT != '') ? $dataDetail->PEL_MUAT : '');
            $cont->addChild('PEL_TRANSIT', ($dataDetail->PEL_TRANSIT != '') ? $dataDetail->PEL_TRANSIT : '');
            $cont->addChild('PEL_BONGKAR', ($dataDetail->PEL_BONGKAR != '') ? $dataDetail->PEL_BONGKAR : '');
            $cont->addChild('GUDANG_TUJUAN', ($dataDetail->GUDANG_TUJUAN != '') ? $dataDetail->GUDANG_TUJUAN : '');
            $cont->addChild('KODE_KANTOR', ($dataDetail->KODE_KANTOR != '') ? $dataDetail->KODE_KANTOR : '');
            $cont->addChild('NO_DAFTAR_PABEAN', ($dataDetail->NO_DAFTAR_PABEAN != '') ? $dataDetail->NO_DAFTAR_PABEAN : '');
            $cont->addChild('TGL_DAFTAR_PABEAN', ($dataDetail->TGL_DAFTAR_PABEAN != '') ? $dataDetail->TGL_DAFTAR_PABEAN : '');
            $cont->addChild('NO_SEGEL_BC', ($dataDetail->NO_SEGEL_BC != '') ? $dataDetail->NO_SEGEL_BC : '');
            $cont->addChild('TGL_SEGEL_BC', ($dataDetail->TGL_SEGEL_BC != '') ? $dataDetail->TGL_SEGEL_BC : '');
            $cont->addChild('NO_IJIN_TPS', ($dataDetail->NO_IJIN_TPS != '') ? $dataDetail->NO_IJIN_TPS : '');
            $cont->addChild('TGL_IJIN_TPS', ($dataDetail->TGL_IJIN_TPS != '') ? $dataDetail->TGL_IJIN_TPS : '');

            // SEND
            \SoapWrapper::add(function ($service) {
                $service
                    ->name('CoarriCodeco_Container')
                    ->wsdl($this->wsdl)
                    ->trace(true)                                                                                                                                                  
                    ->cache(WSDL_CACHE_NONE)                                        
                    ->options([
                        'stream_context' => stream_context_create([
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        ])
                    ]);                                                   
            });

            $datas = [
                'Username' => $this->user, 
                'Password' => $this->password,
                'fStream' => $xml->asXML()
            ];

            // Using the added service
            \SoapWrapper::service('CoarriCodeco_Container', function ($service) use ($datas) {        
                $this->response = $service->call('CoarriCodeco_Container', [$datas])->CoarriCodeco_ContainerResult;      
            });
            
            if($this->response){
            
                $dataDetail->STATUS_TPS = 2;
                $dataDetail->RESPONSE = $this->response;

                if ($dataDetail->save()){

                    $dataHeader->STATUS_REFF = 'SENT';
                    $dataHeader->save();
                    
                    // Update Container Reff
                    if($dataDetail->JNS_CONT == 'F'){
                        DBContainerCy::where(array('NOCONTAINER' => $dataDetail->NO_CONT, 'REF_NUMBER' => $dataDetail->REF_NUMBER))->update(['status_coari' => 'XML Sent']);
                    }elseif($dataDetail->JNS_CONT == 'L'){
                        DBContainer::where(array('NOCONTAINER' => $dataDetail->NO_CONT, 'REF_NUMBER_IN' => $dataDetail->REF_NUMBER))->update(['status_coari' => 'XML Sent']);
                    }
                }
            }
            
            var_dump($this->response);
        }
    }
    
    public function sendXmlCodecoCont()
    {
        $dataHeader = DBCodecoCont::where(array('UID'=>'Cronjob','STATUS_REF'=>'NEW'))->first();
        
        if(count($dataHeader) > 0){
            $dataDetail = \App\Models\TpsCodecoContFclDetail::where('TPSCODECOCONTXML_FK', $dataHeader->TPSCODECOCONTXML_PK)->first();
        
            $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><DOCUMENT></DOCUMENT>');

            $xmldata = $xml->addAttribute('xmlns', 'cococont.xsd');
            $xmldata = $xml->addchild('COCOCONT');
            $header = $xmldata->addchild('HEADER');
            $detail = $xmldata->addchild('DETIL');
            $cont = $detail->addChild('CONT');

            $header->addChild('KD_DOK', ($dataDetail->KD_DOK != '') ? $dataDetail->KD_DOK : '');
            $header->addChild('KD_TPS', ($dataDetail->KD_TPS != '') ? $dataDetail->KD_TPS : '');
            $header->addChild('NM_ANGKUT', ($dataDetail->NM_ANGKUT != '') ? $dataDetail->NM_ANGKUT : '');
            $header->addChild('NO_VOY_FLIGHT', ($dataDetail->NO_VOY_FLIGHT != '') ? $dataDetail->NO_VOY_FLIGHT : '');
            $header->addChild('CALL_SIGN', ($dataDetail->CALL_SIGN != '') ? $dataDetail->CALL_SIGN : '');
            $header->addChild('TGL_TIBA', ($dataDetail->TGL_TIBA != '') ? $dataDetail->TGL_TIBA : '');
            $header->addChild('KD_GUDANG', ($dataDetail->KD_GUDANG != '') ? $dataDetail->KD_GUDANG : '');
            $header->addChild('REF_NUMBER', ($dataHeader->REF_NUMBER != '') ? $dataDetail->REF_NUMBER : '');

            $cont->addChild('NO_CONT', ($dataDetail->NO_CONT != '') ? $dataDetail->NO_CONT : '');
            $cont->addChild('UK_CONT', ($dataDetail->UK_CONT != '') ? $dataDetail->UK_CONT : '');
            $cont->addChild('NO_SEGEL', ($dataDetail->NO_SEGEL != '') ? $dataDetail->NO_SEGEL : '');
            $cont->addChild('JNS_CONT', ($dataDetail->JNS_CONT != '') ? $dataDetail->JNS_CONT : '');
            $cont->addChild('NO_BL_AWB', ($dataDetail->NO_BL_AWB != '') ? $dataDetail->NO_BL_AWB : '');
            $cont->addChild('TGL_BL_AWB', ($dataDetail->TGL_BL_AWB != '') ? $dataDetail->TGL_BL_AWB : '');
            $cont->addChild('NO_MASTER_BL_AWB', ($dataDetail->NO_MASTER_BL_AWB != '') ? $dataDetail->NO_MASTER_BL_AWB : '');
            $cont->addChild('TGL_MASTER_BL_AWB', ($dataDetail->TGL_MASTER_BL_AWB != '') ? $dataDetail->TGL_MASTER_BL_AWB : '');
            $cont->addChild('ID_CONSIGNEE', ($dataDetail->ID_CONSIGNEE != 000000000000000) ? $dataDetail->ID_CONSIGNEE : '');
            $cont->addChild('CONSIGNEE', ($dataDetail->CONSIGNEE != '') ? htmlspecialchars($dataDetail->CONSIGNEE) : '');
            $cont->addChild('BRUTO', ($dataDetail->BRUTO != '') ? $dataDetail->BRUTO : '');
            $cont->addChild('NO_BC11', ($dataDetail->NO_BC11 != '') ? $dataDetail->NO_BC11 : '');
            $cont->addChild('TGL_BC11', ($dataDetail->TGL_BC11 != '') ? $dataDetail->TGL_BC11 : '');        
            $cont->addChild('NO_POS_BC11', ($dataDetail->NO_POS_BC11 != '') ? $dataDetail->NO_POS_BC11 : '');
            $cont->addChild('KD_TIMBUN', ($dataDetail->KD_TIMBUN != '') ? $dataDetail->KD_TIMBUN : '');
            $cont->addChild('KD_DOK_INOUT', ($dataDetail->KD_DOK_INOUT != '') ? $dataDetail->KD_DOK_INOUT : '');
            $cont->addChild('NO_DOK_INOUT', ($dataDetail->NO_DOK_INOUT != '') ? $dataDetail->NO_DOK_INOUT : '');
            $cont->addChild('TGL_DOK_INOUT', ($dataDetail->TGL_DOK_INOUT != '') ? $dataDetail->TGL_DOK_INOUT : '');
            $cont->addChild('WK_INOUT', ($dataDetail->WK_INOUT != '') ? $dataDetail->WK_INOUT : '');
            $cont->addChild('KD_SAR_ANGKUT_INOUT', ($dataDetail->KD_SAR_ANGKUT_INOUT != '') ? $dataDetail->KD_SAR_ANGKUT_INOUT : '');
            $cont->addChild('NO_POL', ($dataDetail->NO_POL != '') ? $dataDetail->NO_POL : '');
            $cont->addChild('FL_CONT_KOSONG', ($dataDetail->FL_CONT_KOSONG != '') ? $dataDetail->FL_CONT_KOSONG : '');
            $cont->addChild('ISO_CODE', ($dataDetail->ISO_CODE != '') ? $dataDetail->ISO_CODE : '');
            $cont->addChild('PEL_MUAT', ($dataDetail->PEL_MUAT != '') ? $dataDetail->PEL_MUAT : '');
            $cont->addChild('PEL_TRANSIT', ($dataDetail->PEL_TRANSIT != '') ? $dataDetail->PEL_TRANSIT : '');
            $cont->addChild('PEL_BONGKAR', ($dataDetail->PEL_BONGKAR != '') ? $dataDetail->PEL_BONGKAR : '');
            $cont->addChild('GUDANG_TUJUAN', ($dataDetail->GUDANG_TUJUAN != '') ? $dataDetail->GUDANG_TUJUAN : '');
            $cont->addChild('KODE_KANTOR', ($dataDetail->KODE_KANTOR != '') ? $dataDetail->KODE_KANTOR : '');
            $cont->addChild('NO_DAFTAR_PABEAN', ($dataDetail->NO_DAFTAR_PABEAN != '') ? $dataDetail->NO_DAFTAR_PABEAN : '');
            $cont->addChild('TGL_DAFTAR_PABEAN', ($dataDetail->TGL_DAFTAR_PABEAN != '') ? $dataDetail->TGL_DAFTAR_PABEAN : '');
            $cont->addChild('NO_SEGEL_BC', ($dataDetail->NO_SEGEL_BC != '') ? $dataDetail->NO_SEGEL_BC : '');
            $cont->addChild('TGL_SEGEL_BC', ($dataDetail->TGL_SEGEL_BC != '') ? $dataDetail->TGL_SEGEL_BC : '');
            $cont->addChild('NO_IJIN_TPS', ($dataDetail->NO_IJIN_TPS != '') ? $dataDetail->NO_IJIN_TPS : '');
            $cont->addChild('TGL_IJIN_TPS', ($dataDetail->TGL_IJIN_TPS != '') ? $dataDetail->TGL_IJIN_TPS : '');

            // SEND
            \SoapWrapper::add(function ($service) {
                $service
                    ->name('CoarriCodeco_Container')
                    ->wsdl($this->wsdl)
                    ->trace(true)                                                                                                                                                 
                    ->cache(WSDL_CACHE_NONE)                                        
                    ->options([
                        'stream_context' => stream_context_create([
                            'ssl' => array(
                                'verify_peer' => false,
                                'verify_peer_name' => false,
                                'allow_self_signed' => true
                            )
                        ])
                    ]);                                                   
            });

            $datas = [
                'Username' => $this->user, 
                'Password' => $this->password,
                'fStream' => $xml->asXML()
            ];

            // Using the added service
            \SoapWrapper::service('CoarriCodeco_Container', function ($service) use ($datas) {        
                $this->response = $service->call('CoarriCodeco_Container', [$datas])->CoarriCodeco_ContainerResult;      
            });
            
            if($this->response){
            
                $dataDetail->STATUS_TPS = 2;
                $dataDetail->RESPONSE = $this->response;

                if ($dataDetail->save()){

                    $dataHeader->STATUS_REFF = 'SENT';
                    $dataHeader->save();
                    
                    // Update Container Reff
                    if($dataDetail->JNS_CONT == 'F'){
                        DBContainerCy::where(array('NOCONTAINER' => $dataDetail->NO_CONT, 'REF_NUMBER_OUT' => $dataDetail->REF_NUMBER))->update(['status_codeco' => 'XML Sent']);
                    }elseif($dataDetail->JNS_CONT == 'L'){
                        DBContainer::where(array('NOCONTAINER' => $dataDetail->NO_CONT, 'REF_NUMBER_OUT' => $dataDetail->REF_NUMBER))->update(['status_codeco' => 'XML Sent']);
                    }
                }
            }

            var_dump($this->response);
        }
    }
    
    private function _getReffNumber($uid = '')
    {
        $reff = \DB::table('tpsurutxml')->select('REF_NUMBER as id')
                ->where('TGL_ENTRY', date('Y-m-d'))
                ->orderBy('TPSURUTXML_PK', 'DESC')
                ->first();
        
        if(count($reff) > 0){
            $reff_id = substr($reff->id, -4);
        }else{
            $reff_id = 0;
        }
        
        $new_ref = 'TRMA'.date('ymd').str_pad(intval($reff_id+1), 4, '0', STR_PAD_LEFT);
        
        $insert = \DB::table('tpsurutxml')->insert(
            ['REF_NUMBER' => $new_ref, 'TGL_ENTRY' => date('Y-m-d'), 'UID' => (empty($uid) ? \Auth::getUser()->name : $uid), 'TAHUN' => date('Y')]
        );
        
        if($insert){
            return $new_ref;
        }
        
        return false;
    }
   
}
