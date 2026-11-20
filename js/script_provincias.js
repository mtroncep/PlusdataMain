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
			var pais = _GET['pais'];
			var region = _GET['region'];
			var provincia = "";
			var query=$("#q").val();
			var per_page=10;
			var parametros = {"action":"ajax","page":page,'query':query,'per_page':per_page,
							  'pais':pais,'region':region,'provincia':provincia};
			
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
				url:'ajax/listar_provincias.php',
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

		$('#editProvinciaModal').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget) // Button that triggered the modal

			var name = button.data('name') 
			$('#edit_name').val(name)
			
			var activa = button.data('activa')
			$('#edit_activa').val(activa)

			var code = button.data('code') 
			$('#edit_code').val(code)

			var id = button.data('id')
			$('#edit_id').val(id)
		})
		
		$('#addProvinciaModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget); // Button that triggered the modal
		  var pais = button.data('add_pais');
		  var region = button.data('add_region');
		  $('#add_pais').val(pais);
		  $('#add_region').val(region);
		  $('#name_provincia').val("");
		})
		
		$('#deleteProvinciaModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var id = button.data('id') 
		  $('#delete_id').val(id)
		})
		
		$("#edit_provincia").submit(function( event ) {
			var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/editar_provincia.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#editProvinciaModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		
		$( "#add_provincia" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/guardar_provincia.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#addProvinciaModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		$( "#delete_provincia" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/eliminar_provincia.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#deleteProvinciaModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
// JavaScript Document
