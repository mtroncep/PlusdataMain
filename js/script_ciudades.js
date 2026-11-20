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
			
			//window.alert('Parametros: ' + _GET['pais'] + ' ' + _GET['region'] + ' ' + _GET['provincia']);
			
			var pais = _GET['pais'];
			var region = _GET['region'];
			var provincia = _GET['provincia'];
			var comarca = _GET['comarca'];
			var municipio = _GET['municipio'];

			var query=$("#q").val();
			var per_page=10;
			var parametros = {"action":"ajax","page":page,'query':query,'per_page':per_page,
							  'pais':pais,'region':region,'provincia':provincia,'comarca':comarca,
							  'municipio':municipio};
			
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
				url:'ajax/listar_ciudades.php',
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

		$('#editCiudadModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var code = button.data('code') 
		  $('#edit_code').val(code)
		  var name = button.data('name') 
		  $('#edit_name').val(name)
		  var id = button.data('id') 
		  $('#edit_id').val(id)
		})
		
		$('#addCiudadModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget); // Button that triggered the modal
		  var pais = button.data('add_pais');
		  var region = button.data('add_region');
		  var provincia = button.data('add_provincia');
		  var comarca = button.data('add_comarca');
		  var municipio = button.data('add_municipio');
		  $('#add_pais').val(pais);
		  $('#add_region').val(region);
		  $('#add_provincia').val(provincia);
		  $('#add_comarca').val(comarca);
		  $('#add_municipio').val(municipio);
		  $('#name_ciudad').val("");
		})
		
		$('#deleteCiudadModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var id = button.data('id') 
		  $('#delete_id').val(id)
		})
		
		$("#edit_ciudad").submit(function( event ) {
			var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/editar_ciudad.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#editCiudadModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		
		$( "#add_ciudad" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/guardar_ciudad.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#addCiudadModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		$( "#delete_ciudad" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/eliminar_ciudad.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#deleteCiudadModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
// JavaScript Document
