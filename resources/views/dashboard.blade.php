<style>

 #tb_attivita td {
  padding: 0.06rem;
  vertical-align: top;
  border-top: 1px solid #dee2e6;
}
</style>
@extends('all_views.viewmaster.index')

@section('title', 'RLST')

@section('extra_style') 
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ URL::asset('/') }}plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"> 
 <!-- per upload -->
  <link href="{{ URL::asset('/') }}dist/css/upload/jquery.dm-uploader.min.css" rel="stylesheet">
  <!-- per upload -->  
  <link href="{{ URL::asset('/') }}dist/css/upload/styles.css?ver=1.1" rel="stylesheet">  

<link href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css" rel="stylesheet">

@endsection

@section('notifiche') 

	@if (1==1)
      <li class="nav-item dropdown notif" onclick="azzera_notif()">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge"></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-header">Avvisi</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file-signature"></i>  Elenco avvisi...
            <span class="float-right text-muted text-sm"></span>
          </a>
          <div class="dropdown-divider"></div>

          <div class="dropdown-divider"></div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">Vai al dettaglio</a>
        </div>
      </li>
	@endif  
@endsection

@section('content_main')
  <input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
  <input type="hidden" value="{{url('/')}}" id="url" name="url">
  <!-- Content Wrapper. Contains page content -->


  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
	
    <!-- /.content-header -->

    <!-- Main content -->
	<form method='post' action="{{ route('dashboard') }}" id='frm_dash' name='frm_dash' autocomplete="off" class="needs-validation" autocomplete="off">
    <div class="content">
	<input name="_token" type="hidden" value="{{ csrf_token() }}" id='token_csrf'>
	
     
	 <div class="container-fluid">

		@php ($num_noti=0)
		@if($num_noti>0)
			<div class="alert alert-warning" role="alert">
				<b>Attenzione</b><hr>
				Hai <b>{{$num_noti}}</b>
				@if ($num_noti==1) 
					nuovo documento inserito da altri riferito ad un'azienda a te associata.
				@else
					nuovi documenti inseriti da altri riferiti ad una o più aziende a te associate.
				@endif
				<br>
				<a href=""  class="link-primary">
					Clicca quì per accedere all'area documenti
				</a>	
			</div>
		@endif	
		



	


      </div><!-- /.container-fluid -->
    </div>



               

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
	  <div id='div_wait' class='mb-3'></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" id='btn_close' onclick='close_doc()'>Chiudi</button>
        <div id='div_save'></div>
      </div>
	  
    </div>
  </div>
</div>	
	
	</form>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  
 @endsection
 
@section('content_plugin')
	<!-- jQuery -->
	<script src="{{ URL::asset('/') }}plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{{ URL::asset('/') }}plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{{ URL::asset('/') }}dist/js/adminlte.min.js"></script>
	
	<script src="{{ URL::asset('/') }}dist/js/dash.js?ver=1.374"></script>

	<!--select2 !-->
	<script src="{{ URL::asset('/') }}plugins/select2/js/select2.full.min.js"></script>	
	<!-- per upload -->
	<script src="{{ URL::asset('/') }}dist/js/upload/jquery.dm-uploader.min.js"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-ui.js?ver=1.311"></script>
	<script src="{{ URL::asset('/') }}dist/js/upload/demo-config.js?ver=2.401"></script>	
	
@endsection
