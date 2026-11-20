		$(function() {
			load(1);
		});
		function load(page){
			var qs = document.getElementById("par_pais").src.match(/\w+=\w+/g);
			var query=$("#q").val();

			var _GET= {};
			var t,i = qs.length;
			while (i--) {

				 //t[0] nombre del parametro y t[1] su valor
				 t = qs[i].split("=");

				 //opción 1: a modo de PHP
				 _GET[t[0]] = t[1];

				//opción2: variables con el mismo nombre usando eval
				//eval('var '+t[0]+'="'+t[1]+'";');
			}

			var per_page=10;
			var cod_localidad = _GET['cod_localidad'];
			var cod_descripcion = _GET['cod_descripcion'];

			var parametros = {"action":"ajax","page":page,'query':query,'per_page':per_page,
							  'cod_localidad':cod_localidad, 'cod_descripcion':cod_descripcion};
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'ajax/listar_CP.php',
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

		$('#editProductModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var code = button.data('code') 
		  $('#edit_code').val(code)
		  var name = button.data('name') 
		  $('#edit_name').val(name).focusin();
		  var id = button.data('id') 
		  $('#edit_id').val(id)
		})
		
		$('#deleteProductModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var id = button.data('id') 
		  $('#delete_id').val(id)
		})
		
		$('#indexRegionesModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var pais = button.data('pais');
		  $('#pais').val(pais);
		})
		
		$( "#edit_product" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/editar_producto.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#editProductModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		
		$( "#add_product" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/guardar_producto.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#addProductModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		$( "#delete_cp" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "borrarCP.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#deleteProductModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
