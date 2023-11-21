<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\italy_cap;
use App\Models\italy_cities;
use App\Models\italy_provinces;

use Mail;
use DB;


class AjaxController extends Controller
	{
		
	function info_locx(Request $request) {
		
		$filename=$request->input("filename");
		$id_sinistro=$request->input("id_sinistro");
		$tipo_allegato=$request->input("tipo_allegato");

		if ($tipo_allegato=="2")
			sinistri::where('id', $id_sinistro)
			->update(['file_cid' => $filename]);			
		else {
			$support = new support_sinistri;
			$support->filename=$filename;
			$support->id_sinistro=$id_sinistro;
			$support->save();
		}
		
		$risp=array();

		$risp['status']="OK";
		$risp['esito']="insert";
		echo json_encode($risp);		
	}		

	public function infoloc(Request $request){
		$istat=$request->input("istat");
	
		$info=italy_cities::select('comune','cap','provincia')
		->where('istat','=',$istat)
		->get();
		return json_encode($info);
	}
	

}
