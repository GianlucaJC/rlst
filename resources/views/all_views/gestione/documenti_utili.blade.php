@extends('all_views.viewmaster.index')

@section('title', 'RLST')
@section('extra_style') 

  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> 

 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  
<!-- x button export -->
<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">
<!-- -->
@endsection



<style>
	tfoot input {
        width: 100%;
        padding: 3px;
        box-sizing: border-box;
    }
</style>
@section('content_main')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Elenco visite effettuate</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
			  <li class="breadcrumb-item active">Elenco visite</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
		<!-- form new voce attestato !-->	
		
		 

		<form method='post' action="{{ route('documenti_utili') }}" id='frm_documenti1' name='frm_documenti1' autocomplete="off">
			<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
			<input type="hidden" value="{{url('/')}}" id="url" name="url">

        <div class="row">
          <div class="col-md-12">
		  
				<table id='tbl_list_doc' class="display">
					<thead>
						<tr>
							<th>Data ora</th>
							
							<th>Operatore</th>
							<th>Territorio operatore</th>
							<th>Motivo visita</th>
							<th>Data visita</th>
							<th>Indirizzo</th>
							<th>Cap-Comune-Provincia</th>
							<th>Note</th>
							<th>Documento</th>
							<th>Elimina</th>
						</tr>
					</thead>
					<tbody>

						@foreach($documenti_utili as $documento)
							<tr>
								<td>
									{{ $documento->created_at}}
								</td>

								<td>
								<?php
									if (isset($infotessere[$utenti[$documento->id_funzionario]])) 
										echo $infotessere[$utenti[$documento->id_funzionario]];
								?>

								</td>

								<td>
									<?php
										if (isset($info_ter[$documento->id_prov])) 
											echo $info_ter[$documento->id_prov];
									?>
								</td>


								<td>{{ $documento->motivo_visita }}</td>
								<td>{{ $documento->data_visita }}</td>
								<td>{{ $documento->indirizzo }}</td>
								<td>
									{{ $documento->cap }}-
									{{ $documento->comune }}-
									{{ $documento->provincia }}
								</td>
								<td>{{ $documento->note }}</td>

								<td>
									<a href="{{$documento->url_completo}}" target='_blank'>
										@if (strlen($documento->file_user)==0)
											{{ $documento->filename }}	
										@else
											{{ $documento->file_user }}
										@endif
									</a>
								</td>

								<td>
								@if ($documento->id_funzionario==$ref_user)
									<a href='#' onclick="dele_element({{$documento->id}})">
										<button type="submit" name='dele_ele' class="btn btn-danger"><i class="fas fa-trash"></i></button>	
									</a>
									@endif
								</td>

							</tr>
						@endforeach
						
					</tbody>
					<tfoot>
						<tr>
							<th>Data ora</th>
							
							<th>Operatore</th>
							<th>Territorio operatore</th>
							<th>Motivo visita</th>
							<th>Data visita</th>
							<th>Indirizzo</th>
							<th>Cap-Comune-Provincia</th>
							<th>Note</th>
							<th>Documento</th>
							<th></th>
						</tr>
					</tfoot>					
				</table>
				<input type='hidden' id='dele_contr' name='dele_contr'>
				<input type='hidden' id='restore_contr' name='restore_contr'>
			
          </div>

        </div>
		
		<button type="button" class="mt-2 btn btn-primary btn-lg btn-block" onclick='newdoc()'>Nuova Visita</button>

		<button type="button" class="mt-2 btn btn-info btn-lg" onclick="$('.cercacol').show(220)">Ricerche</button>

		</form>
		
		
		
		<form method='post' action="{{ route('documenti_utili') }}" id='frm_documenti2' name='frm_documenti2' autocomplete="off" class="needs-validation">
		<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
		<input type='hidden' name='nomefile' id='nomefile'>
		<input type='hidden' name='save_frm' id='save_frm'>
		  <!-- Modal -->
		<div class="modal fade bd-example-modal-lg" id="modalvalue" tabindex="-1" role="dialog" aria-labelledby="title_doc" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
			<div class="modal-content">
			  <div class="modal-header">
				<h5 class="modal-title" id="title_doc">Inserimento dati</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				  <span aria-hidden="true">&times;</span>
				</button>
			  </div>
			  <div class="modal-body" id='bodyvalue'>
				...
			  </div>
				<div class="modal-body">
				

					<div class="row mb-3">
						<!-- div disattivato !-->		
						<div class="col-md-6">
							<div class="form-floating">
								<input class="form-control" id="motivo_visita" name='motivo_visita' type="text" placeholder="Definizione"  required value="{{$appalti[0]->motivo_visita ?? ''}}" />
								<label for="motivo_visita">Motivo della visita*</label>
							</div>
						</div>		

						<div class="col-md-6">
							<div class="form-floating">
								<input class="form-control" id="data_visita" name='data_visita' type="date"  required value="{{$appalti[0]->data_visita ?? ''}}" />
								<label for="data_visita">Data della visita*</label>
							</div>
						</div>	
					</div>	
					
					<div class="row mb-3">			
						<input type='hidden' name='comune' id='comune'>
						<div class="col-md-12">
							<div class="form-floating">
							<select class='form-select  lista' id='localita' aria-label='Loc' name='localita' required onchange="set_loc(this.value)" >
								<option value=''>Select...</option>
								@foreach($all_loc as $loc)
									<option value="{{$loc->istat}}"
									>{{$loc->comune}}</option>
								@endforeach
							</select>
								
							<label for="localita">Località*</label>
							</div>
						</div>
					</div>
					
					<div class="row mb-3">
						<div class="col-md-6">
							<div class="form-floating">
								<input class="form-control" id="indirizzo" name='indirizzo' type="text" placeholder="Via, Piazza, etc."  required value="{{$appalti[0]->indirizzo ?? ''}}" />
								<label for="motivo_visita">Indirizzo*</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-floating">
								<input class="form-control" id="cap" name='cap' type="text" placeholder="Cap"  required value="{{$appalti[0]->cap ?? ''}}" />
								<label for="cap">Cap*</label>
							</div>
						</div>
						<div class="col-md-3">
							<div class="form-floating">
								<input class="form-control" id="provincia" name='provincia' type="text" placeholder="Provincia"  required value="{{$appalti[0]->provincia ?? ''}}" />
								<label for="cap">Provincia*</label>
							</div>
						</div>					
					</div>

					<div class="row mb-3">
						<div class="col-md-12">
							<div class="form-floating">
								<textarea class="form-control" id="note" name="note" rows="4">{{$appalti[0]->note ?? ''}}</textarea>
								<label for="note">Note</label>
							</div>
						</div>
					</div>					
					
				</div>
				


			  <div id='div_wait' class='mb-3'></div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal" id='btn_close' onclick='close_doc()'>Chiudi</button>
				<div id='div_save'></div>
			  </div>
			  
			</div>
		  </div>
		</div>	

		</form>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
	

	
	
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  
 @endsection
 
 @section('content_plugin')
	<!-- jQuery -->
	<script src="plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="dist/js/adminlte.min.js"></script>

	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>	
	
	<!-- inclusione standard
		per personalizzare le dipendenze DataTables in funzione delle opzioni da aggiungere: https://datatables.net/download/
	!-->
	
	<!-- dipendenze DataTables !-->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.css"/>
		 
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
		<script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.12.1/b-2.2.3/b-colvis-2.2.3/b-html5-2.2.3/b-print-2.2.3/datatables.min.js"></script>
	<!-- fine DataTables !-->
	
	

	<script src="{{ URL::asset('/') }}dist/js/documenti_utili.js?ver=1.570"></script>
	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.301"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.400"></script>

@endsection