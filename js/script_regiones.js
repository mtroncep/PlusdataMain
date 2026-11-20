// JavaScript Document
		$(function() {
			load(1);
		});
		function load(page){
			var qs = document.getElementById("par_pais").src.match(/\w+=\w+/g);
			var _GET= {};
			var t,i = qs.length;
			while (i--) {
				 t = qs[i].split("=");
				 _GET[t[0]] = t[1];
				eval('var '+t[0]+'="'+t[1]+'";');
			}
			var pais = _GET['pais'];
			var region = "";
			var provincia = "";
			
			var query=$("#q").val();
			var per_page=10;
			var parametros = {"action":"ajax","page":page,'query':query,'per_page':per_page,'pais':pais,
							 'region':region,'provincia':provincia};

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
				url:'ajax/listar_regiones.php',
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

		$('#editRegionModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
          var code = button.data('code')
		  $('#edit_code').val(code)
          var name = button.data('name')
		  $('#edit_name').val(name)
          var id = button.data('id')
		  $('#edit_id').val(id)
		})
		
		$('#addRegionModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget); // Button that triggered the modal
		  var pais = button.data('add_pais');
		  var name_region = button.data('name_region');
          $('#add_pais').val(pais);
		  $('#name_region').val("");
		})
		
		$('#deleteRegionModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var id = button.data('id') 
		  $('#delete_id').val(id)
		})
		
		$("#edit_region").submit(function( event ) {
			var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/editar_region.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#editRegionModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		
		$( "#add_region" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/guardar_region.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#addRegionModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
		
		$( "#delete_region" ).submit(function( event ) {
		  var parametros = $(this).serialize();
			$.ajax({
					type: "POST",
					url: "ajax/eliminar_region.php",
					data: parametros,
					 beforeSend: function(objeto){
						$("#resultados").html("Enviando...");
					  },
					success: function(datos){
					$("#resultados").html(datos);
					load(1);
					$('#deleteRegionModal').modal('hide');
				  }
			});
		  event.preventDefault();
		});
// JavaScript Document
