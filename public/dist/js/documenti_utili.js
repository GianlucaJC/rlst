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
			$(this).html('<input type="text" placeholder="' + title + '" />');
    });	
    var table=$('#tbl_list_doc').DataTable({
		"order": [[ 0, 'desc' ]],
		pageLength: 20,
		lengthMenu: [10, 15, 20, 50, 100, 200, 500],

		pagingType: 'full_numbers',		
		dom: 'Bfrtip',
		buttons: [
			'excel'
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
            lengthMenu: 'Visualizza _MENU_ visite per pagina',
            zeroRecords: 'Nessuna visita trovata',
            info: 'Pagina _PAGE_ di _PAGES_',
            infoEmpty: 'Non sono presenti visite',
            infoFiltered: '(Filtrati da _MAX_ visite totali)',
        },

		
    });


	
} );


function newdoc() {
	html=""
	html+="<center><div class='spinner-border text-secondary' role='status'></div></center>";
	$("#title_doc").html("Definizione della visita")
	$("#bodyvalue").html(html)
	$("#div_save").empty()
	$('#modalvalue').modal('show')	
	$("#nomefile").val('')

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

		
		$("#div_save").html(html);		
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