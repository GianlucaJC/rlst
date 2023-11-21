<?php
//test
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\documenti_utili;
use App\Models\italy_cities;
use App\Models\db_utenti;

use Illuminate\Support\Facades\Auth;


use DB;

class mainController extends Controller
{

private $id_user;
private $utentefillea;
private $id_prov_associate;

public function __construct()
	{
		$this->middleware('auth')->except(['index']);

		$this->middleware(function ($request, $next) {			
			$id=Auth::user()->id;
			$user = User::from('users as u')
			->where('u.id','=',$id)
			->join('online.db','db.N_TESSERA','u.name')
			->get();
			
			if (isset($user[0])) {
				$this->id_user=$id;
				$this->utentefillea=$user[0]->UTENTEFILLEA;
				$this->id_prov_associate=$user[0]->id_prov_associate;
			}
			return $next($request);
		});		
		
	}	


	public function saveinfo() {
		$request=request();
		$doc=new documenti_utili();
		$doc->filename=$request->input("nomefile");
		$doc->url_completo="allegati/documenti_utili/".$request->input("nomefile");
		$doc->id_funzionario=$this->id_user;
		$doc->id_prov=$this->id_prov_associate;
		$doc->motivo_visita=$request->input("motivo_visita");
		$doc->data_visita=$request->input("data_visita");
		$doc->indirizzo=$request->input("indirizzo");
		$doc->cap=$request->input("cap");
		$doc->comune=$request->input("comune");
		$doc->provincia=$request->input("provincia");
		$doc->note=$request->input("note");
		$doc->save();
	}

	public function infotessere() {
		$users=DB::table('online.db')
		->select('id','N_TESSERA','UTENTEFILLEA')
		->where('attiva','=',1)
		->get();
		$info=array();
		
		foreach ($users as $us) {
			$info[$us->N_TESSERA]=$us->UTENTEFILLEA;
		}	
		return $info;		
	}

	public function info_ter() {
		$province=DB::table('bdf.province')
		->select('id','provincia')->get();
		$info=array();
		
		foreach ($province as $provincia) {
			$info[$provincia->id]=$provincia->provincia;
		}	
		return $info;		
	}
	
	public function documenti_utili(Request $request) {
		$users=user::select('id','name')->orderBy('name')->get();
		$utenti=array();
		foreach ($users as $us) {
			$utenti[$us->id]=$us->name;
		}
		$info_ter=$this->info_ter();
		$infotessere=$this->infotessere();
		
		$dele_contr=$request->input("dele_contr");
		$save_frm=$request->input("save_frm");
		if ($save_frm=="save") $this->saveinfo();
		
		if (strlen($dele_contr)!=0) {
			$doc = documenti_utili::find($dele_contr);	
			$doc->delete();
			$doc_remove=$doc->url_completo;
			@unlink($doc_remove);
		}		
		$documenti_utili = DB::table('documenti_utili as d')
		->select('d.*')
		->where('d.dele','=',0)
		->orderBy("d.created_at","desc")->get();
		
		$all_loc=italy_cities::select("comune","cap","istat","provincia")
		->orderBy('comune')->get();

		$ref_user=$this->id_user;

		$utentefillea=$this->utentefillea;
		
		return view('all_views/gestione/documenti_utili')->with('documenti_utili', $documenti_utili)->with('utenti',$utenti)->with('ref_user',$ref_user)->with('all_loc',$all_loc)->with('utentefillea',$utentefillea)->with('infotessere',$infotessere)->with('info_ter',$info_ter);
	}


}
