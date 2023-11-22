(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        } else {
			$("#btn_save").text("Attendere...");
			$('#btn_save').prop('disabled',true);
			$("#save_frm").val("save")
		}	
		
        form.classList.add('was-validated')
      }, false)
    })
})()
$(document).ready( function () {
    $('#tbl_list_doc tfoot th').each(function () {
        var title = $(this).text();
		if (title.length!=0)
			$(this).html('<input class="cercacol" style="display:none" type="text" placeholder="' + title + '" />');
    });	
    var table=$('#tbl_list_doc').DataTable({
		dom: 'Bfrtip',
		"order": [[ 0, 'desc' ]],
		pageLength: 10,
		lengthMenu: [
		[10, 20, 50, 100, -1],
		[ '10 visite', '20 visite', '50 visite', '100 visite', 'Tutte' ]
		],
		buttons: [
			'excel','pageLength'
		],		
        initComplete: function () {
            // Apply the search
            this.api()
                .columns()
                .every(function () {
                    var that = this;
 
                    $('input', this.footer()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
        },
        language: {
			buttons: {
				pageLength: 'Numero visite per pagina %d'
			},			
			search: "Cerca globalmente",
            lengthMenu: 'Visualizza _MENU_ visite per pagina',
            zeroRecords: 'Nessuna visita trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti visite',
            infoFiltered: '(Filtrati da _MAX_ visite totali)',			
			"paginate": {
				"first":      "Primo",
				"last":       "Ultimo",
				"next":       "Successivo",
				"previous":   "Precedente"
			},			
        },

		
    });
	
	$('.dataTables_length').show();  


	
} );


function docins(value) {
	$("#id_v").val(value)
	$('#modalvalue').find('input:text').val('');
	$("#data_visita").val('')
	
	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#title_doc").html("Definizione della visita")
	$("#bodyvalue").html(html)
	$("#div_save").empty()
	$('#modalvalue').modal('show')	
	$("#nomefile").val('')
	filename=$("#id_ref"+value).data("filename")
	

	
	if (value==0) {
	} else {

		$("#motivo_visita").val($("#id_ref"+value).data("motivo_visita"))
		$("#data_visita").val($("#id_ref"+value).data("data_visita"))
		$("#indirizzo").val($("#id_ref"+value).data("indirizzo"))
		$("#istat").val($("#id_ref"+value).data("istat"))
		$("#cap").val($("#id_ref"+value).data("cap"))
		$("#provincia").val($("#id_ref"+value).data("provincia"))
		$("#frazione").val($("#id_ref"+value).data("frazione"))
		$("#select_azienda").val($("#id_ref"+value).data("azienda"))
		$("#azienda").val($("#id_ref"+value).data("azienda"))
		$("#note").val($("#id_ref"+value).data("note"))

		/*
		data-motivo_visita='{{ $documento->motivo_visita }}'
		data-data_visita='{{ $documento->data_visita }}'
		data-indirizzo='{{ $documento->indirizzo }}'
		data-cap='{{ $documento->cap }}'
		
		
		data-provincia='{{ $documento->provincia }}'
		data-frazione='{{ $documento->frazione }}'
		data-azienda='{{ $documento->azienda }}'
		data-note='{{ $documento->note }}'
		*/		
	}								

	/*
	$('.lista').select2({
		dropdownParent: $('#modalvalue')
	});
	*/
	
	
	fetch('class_allegati.php', {
		method: 'post',
		//cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached		
		headers: {
		  "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
		},
		body: 'operazione=refresh_tipo'
	})
	.then(response => {
		if (response.ok) {
		   return response.text();
		}
		
	})
	.then(resp=>{
		//$("#div_sezione"+sezione).html(resp);
		
		$("#bodyvalue").html(resp);
		
		//function set_class_allegati() in demo-config.js
		set_class_allegati.from="allegati_utili"
		set_class_allegati(); 

		html="<button id='btn_save' name='btn_save' type='submit' class='btn btn-primary'>Salva</button>";
		//saveinfodoc() in demo-config.js

		$("#allegato_dele_id").val('')
		$("#div_save").html(html);		
		$("#div_send_allegati").show()
		$("#div_elimina_allegati").hide()
		if (filename && filename.length>0) {
			$("#allegato_dele_id").val(value)
			$("#div_send_allegati").hide()
			$("#div_elimina_allegati").show()	
			
		}
	})
	.catch(status, err => {
		
		return console.log(status, err);
	})	
		
	
}


function set_loc(istat) {
	base_path = $("#url").val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	let CSRF_TOKEN = $("#token_csrf").val();
	$.ajax({
		type: 'POST',
		url: base_path+"/infoloc",
		data: {_token: CSRF_TOKEN,istat:istat},
		success: function (data) {
			cap="";provincia="";comune="";
			$.each(JSON.parse(data), function (i, item) {
				cap=item.cap
				provincia=item.provincia
				comune=item.comune
			});
			$("#comune").val(comune)
			$("#cap").val(cap)
			$("#provincia").val(provincia)
			
		}
	});			
}


function close_doc() {
	//impostato da dash.js o demo-config.js
	if( typeof close_doc.tipo == 'undefined' ) return false
	if (close_doc.tipo=="refresh") $("#frm_documenti").submit();	
}

function dele_element(value) {
	if(!confirm("Sicuri eliminare la visita?")) 
		event.preventDefault() 
	else 
		$('#dele_contr').val(value)	
}


function restore_element(value) {
	if(!confirm('Sicuri di ripristinare l\'elemento?')) 
		event.preventDefault() 
	else 
		$('#restore_contr').val(value)	
}