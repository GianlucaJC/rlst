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
private $regione_associata;
private $tb_default;
private $admin_pro;
private $admin_reg;
private $admin_naz;


public function __construct()
	{
		$this->middleware('auth')->except(['index']);

		$this->middleware(function ($request, $next) {			
			$id=Auth::user()->id;
			$user = User::from('users as u')
			->where('u.id','=',$id)
			->get();
			
			if (isset($user[0])) {
				$this->id_user=$id;
				$this->utentefillea=$user[0]->utentefillea;
				$this->id_prov_associate=$user[0]->id_prov_associate;
				$this->tb_default=$user[0]->tb_default;
				$this->admin_pro=$user[0]->admin_pro;
				$this->admin_reg=$user[0]->admin_reg;
				$this->admin_naz=$user[0]->admin_naz;
				$reg_a=$this->regione_ass($this->id_prov_associate);
				$this->regione_associata=$reg_a;
			}
			return $next($request);
		});		
		
	}	

	public function regione_ass($id_prov) {
		$reg=DB::table('bdf.province')
		->select('id_regione')
		->where('id',"=",$id_prov)
		->get();
		$regione=0;
		if (isset($reg[0])) $regione=$reg[0]->id_regione;
		return $regione;
	}
	
	public function prov_in_reg($id_regione) {
		$ids=DB::table('bdf.province')
		->select('id')
		->where('id_regione',"=",$id_regione)
		->get();
		return $ids;
	}
	
	
	public function saveinfo() {
		$request=request();
		$id_v=$request->input("id_v");
		if ($id_v=="0" || strlen($id_v)==0)
			$doc=new documenti_utili();
		else
			$doc=documenti_utili::find($id_v);
		
		if (strlen($request->input("nomefile"))!=0) {
			$doc->filename=$request->input("nomefile");
			$doc->url_completo="allegati/documenti_utili/".$request->input("nomefile");
		}
		$doc->id_funzionario=$this->id_user;
		$doc->id_prov=$this->id_prov_associate;
		$doc->motivo_visita=$request->input("motivo_visita");
		$doc->data_visita=$request->input("data_visita");
		$doc->indirizzo=$request->input("indirizzo");
		$doc->cap=$request->input("cap");
		$doc->istat=$request->input("istat");
		$doc->comune=$request->input("comune");
		$doc->frazione=$request->input("frazione");
		$doc->provincia=$request->input("provincia");
		$doc->azienda=$request->input("azienda");
		$doc->note=$request->input("note");
		$doc->save();
	}

	public function infotessere() {
		$users=User::select('id','name','utentefillea')->get();
		$info=array();
		
		foreach ($users as $us) {
			$info[$us->name]=$us->utentefillea;
		}	
		return $info;		
	}

	public function info_regione() {
		$regioni=DB::table('bdf.regioni')
		->select('id','regione')->get();
		$info=array();
		
		foreach ($regioni as $regione) {
			$info[$regione->id]=$regione->regione;
		}
		return $info;
	}
	
	public function info_ter() {
		$province=DB::table('bdf.province')
		->select('id','provincia','id_regione')->get();
		$info=array();
		$regione=$this->info_regione();
		foreach ($province as $provincia) {
			$info[$provincia->id]['territorio']=$provincia->provincia;
			$info[$provincia->id]['id_regione']=$provincia->id_regione;
			if (isset($regione[$provincia->id_regione])) 
				$info[$provincia->id]['regione']=$regione[$provincia->id_regione];
			else 
				$info[$provincia->id]['regione']="--";
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
		
		$btn_dele_allegato=$request->input("btn_dele_allegato");
		if ($btn_dele_allegato=="dele") {
			$allegato_dele_id=$request->input("allegato_dele_id");
			documenti_utili::where('id', $allegato_dele_id)
			  ->update(['filename' => null,'file_user'=>null, 'url_completo'=>null]);			
		}
		
		if (strlen($dele_contr)!=0) {
			$doc = documenti_utili::find($dele_contr);	
			$doc->delete();
			$doc_remove=$doc->url_completo;
			@unlink($doc_remove);
		}		
		
		$id_prov_associate=$this->id_prov_associate;
		$tb_default=$this->tb_default;
		$reg_ref=substr($tb_default,3,4);
		$admin_pro=$this->admin_pro;
		$admin_reg=$this->admin_reg;
		$admin_naz=$this->admin_naz;
		$cond=0;
		$ids=array();
		if ($admin_reg==1) {
			$cond=1;
			$regx=$this->prov_in_reg($this->regione_associata);
			foreach($regx as $rr) {
				$ids[]=$rr->id;
			}
		}	
		if ($admin_naz==1) $cond=2;
		

		
		$documenti_utili = DB::table('documenti_utili as d')
		->select('d.*')
		->where('d.dele','=',0)
		->when($cond==0, function ($documenti_utili) use ($id_prov_associate) {	
			return $documenti_utili->where('d.id_prov', "=",$id_prov_associate);
		})
		->when($cond==1, function ($documenti_utili) use ($ids) {
			return $documenti_utili->whereIn('id_prov',$ids);
		})		
		->orderBy("d.created_at","desc")->get();
		
		$all_loc=italy_cities::select("comune","cap","istat","provincia")
		->orderBy('comune')->get();

		$ref_user=$this->id_user;

		$utentefillea=$this->utentefillea;
		$aziende=$this->aziende($this->tb_default);
		
		
		return view('all_views/gestione/documenti_utili')->with('documenti_utili', $documenti_utili)->with('utenti',$utenti)->with('ref_user',$ref_user)->with('all_loc',$all_loc)->with('utentefillea',$utentefillea)->with('infotessere',$infotessere)->with('info_ter',$info_ter)->with('aziende',$aziende);
	}
	
	public function aziende($tb_default) {
		$aziende=DB::table("anagrafe.$tb_default")
		->select('denom')
		->where('attivi','=','S')
		->groupBy('denom')
		->orderBy('denom')
		->get();

		return $aziende;
	}


}
