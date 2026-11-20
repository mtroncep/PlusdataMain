// JavaScript Document
		$(function() {
			load(1);
		});
		function load(page){
			var qs = document.getElementById("par_pais").src.match(/\w+=\w+/g);
			var _GET= {};
			var t,i = qs.length;
			while (i--) {

				 //t[0] nombre del parametro y t[1] su valor
				 t = qs[i].split("=");

				 //opción 1: a modo de PHP
				 _GET[t[0]] = t[1];

				//opción2: variables con el mismo nombre usando eval
				eval('var '+t[0]+'="'+t[1]+'";');
			}
			
			//window.alert(_GET['nucleo']);
			
			var pais = _GET['pais'];
			var region = _GET['region'];
			var provincia = _GET['provincia'];
			var comarca = _GET['comarca'];
			var municipio = _GET['municipio'];
			var localidad = _GET['localidad'];
			var barrio = _GET['barrio'];
			var nucleo = _GET['nucleo'];

			var query=$("#q").val();
			var per_page=10;
			var parametros = {"action":"ajax","page":page,'query':query,'per_page':per_page,
							  'pais':pais,'region':region,'provincia':provincia,'comarca':comarca,
							  'municipio':municipio,'localidad':localidad,'barrio':barrio,'nucleo':nucleo
							 };
			
			$("#ubicacion").fadeIn('slow');
			$("#ubicacion").html(" ");
			$.ajax({
				url:'ajax/listar_ubicacion.php',
				data: parametros,
				 beforeSend: function(objeto){
			  },
				success:function(data){
					$("#ubicacion").html(data).fadeIn('slow');
				}
			})

			$("#loader").fadeIn('slow');
			$.ajax({
				url:'ajax/listar_manzanas.php',
				data: parametros,
				 beforeSend: function(objeto){
				$("#loader").html("Cargando...");
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$("#loader").html("");
				}
			})
		}

		$('#editManzanaModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var code = button.data('code') 
		  $('#edit_code').val(code)
		  var name = button.data('name') 
		  $('#edit_name').val(name)
		  var id = button.data('id') 
		  $('#edit_id').val(id)
		})
		
		$('#addManzanaModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget); // Button that triggered the modal
		  var pais = button.data('add_pais');
		  var region = button.data('add_region');
		  var provincia = button.data('add_provincia');
		  var comarca = button.data('add_comarca');
		  var municipio = button.data('add_municipio');
		  var localidad = button.data('add_localidad');
		  var barrio = button.data('add_barrio');
		  var nucleo = button.data('add_nucleo');
		  $('#add_pais').val(pais);
		  $('#add_region').val(region);
		  $('#add_provincia').val(provincia);
		  $('#add_comarca').val(comarca);
		  $('#add_municipio').val(municipio);
		  $('#add_localidad').val(localidad);
		  $('#add_barrio').val(barrio);
		  $('#add_nucleo').val(nucleo);
		  $('#name_manzana').val("");
		})
		
		$('#deleteManzanaModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var id = button.data('id') 
		  $('#delete_id').val(id)
		})
		
		$("#edit_manzana").submit(function( event ) {
			var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/editar_manzana.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#editManzanaModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		
		$( "#add_manzana" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/guardar_manzana.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#addManzanaModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		$( "#delete_manzana" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/eliminar_manzana.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#deleteManzanaModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
// JavaScript Document
