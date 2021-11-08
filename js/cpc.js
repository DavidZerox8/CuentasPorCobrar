		$(document).ready(function () {
		    load(1);
		});

		function load(page) {
		    var q = $("#qq").val();
		    $("#loader").fadeIn('slow');
		    $.ajax({
		        url: './ajax/buscar_cpc.php?action=ajax&page=' + page + '&q=' + q,
		        beforeSend: function (objeto) {
		            $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		        },
		        success: function (data) {
		            $(".outer_div").html(data).fadeIn('slow');
		            $('#loader').html('');

		        }
		    })
		}

        function loads(page) {
		    var q = $("#q").val();
		    $("#loader").fadeIn('slow');
		    $.ajax({
		        url: './ajax/buscar_cpc.php?action=ajax&page=' + page + '&q=' + q,
		        beforeSend: function (objeto) {
		            $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		        },
		        success: function (data) {
		            $(".outer_div").html(data).fadeIn('slow');
		            $('#loader').html('');

		        }
		    })
		}



		function eliminar(id) {
		    var q = $("#q").val();
		    if (confirm("Realmente deseas eliminar el cliente")) {
		        $.ajax({
		            type: "GET",
		            url: "./ajax/buscar_cpc.php",
		            data: "id=" + id,
		            "q": q,
		            beforeSend: function (objeto) {
		                $("#resultados").html("Mensaje: Cargando...");
		            },
		            success: function (datos) {
		                $("#resultados").html(datos);
		                load(1);
		            }
		        });
		    }
		}



		$("#guardar_cliente").submit(function (event) {
            
            if (confirm("¿Realmente deseas registrar esta cuenta por cobrar?")){
		    $('#guardar_datos').attr("disabled", true);               
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "ajax/nuevo_cpc.php",
		        data: parametros,
		        beforeSend: function (objeto) {
		            $("#resultados_ajax").html("Mensaje: Cargando...");
		        },
		        success: function (datos) {
		            $("#resultados_ajax").html(datos);
		            $('#guardar_datos').attr("disabled", false);                    
		            load(1);
                    //location.reload();
		        }
		    });
		    event.preventDefault();
        } else{
            return false;
        }
		})


		$("#editar_cliente").submit(function (event) {
            if (confirm("¿Realmente deseas actualizar esta cuenta por cobrar?")){
		    $('#actualizar_datos').attr("disabled", true);
            $('#mod_concepto').prop('disabled', false);     
		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "ajax/editar_cpc.php",
		        data: parametros,
		        beforeSend: function (objeto) {
		            $("#resultados_ajax2").html("Mensaje: Cargando...");
		        },
		        success: function (datos) {
		            $("#resultados_ajax2").html(datos);
		            $('#actualizar_datos').attr("disabled", false);
                    $('#mod_concepto').prop('disabled', true); 
		            load(1);
		        }
		    });
		    event.preventDefault();
            } else{
            return false;
            }
		})

		function obtener_datos(id) {

		    var id_cpc = $("#cpc" + id).val();
		    var id_cliente = $("#id_cliente" + id).val();
		    var concepto = $("#concepto" + id).val();
		    var factura = $("#factura" + id).val();
		    var fecha_ap = $("#fecha_aplic" + id).val();
		    var fecha_ven = $("#fecha_ven" + id).val();
		    var monto = $("#monto" + id).val();
		    var obs = $("#obs" + id).val();

		    $("#mod_id").val(id_cpc);
		    $("#mod_cliente").val(id_cliente);
		    $("#mod_concepto").val(concepto);
		    $("#mod_factura").val(factura);
		    $("#mod_fecha_ap").val(fecha_ap);
		    $("#mod_fecha_ven").val(fecha_ven);
		    $("#mod_monto").val(monto);
		    $("#mod_obs").val(obs);


		}


		function documentar(id) {

		    if (confirm("¿Realmente deseas documentar esta factura?")) {

		        $.ajax({
		            data: "id_cpc=" + id,
		            url: 'documentar.php',
		            method: "POST",
		            success: function (response) {
		                alert(response);
		                location.reload();
		            }
		        });

		    }

		}


		$("#documentar_facturas").submit(function (event) {
		    $('#guardar_datosf').attr("disabled", true);

		    var parametros = $(this).serialize();
		    $.ajax({
		        type: "POST",
		        url: "_check.php",
		        data: parametros,
		        beforeSend: function (objeto) {
		            $("#resultados_ajax2").html("Mensaje: Cargando...");
		        },
		        success: function (datos) {
		            alert(datos);
		            $('#guardar_datosf').attr("disabled", false);
		            location.reload();;
		        }
		    });
		    event.preventDefault();
		})


		function buscar_d() {

		    campos = {
		        "fecha": $("#fecha_ap").val(),
		    };

		    id_c = {
		        "id": $("#cliente").val(),
		    };

		    $.ajax({
		        url: './ajax/buscar_cpc.php?action=ajax&page=' + page + '&q=' + q,
		        beforeSend: function (objeto) {
		            $('#loader').html('<img src="./img/ajax-loader.gif"> Cargando...');
		        },
		        success: function (data) {
		            $(".outer_div").html(data).fadeIn('slow');
		            $('#loader').html('');

		        }
		    });

		}
