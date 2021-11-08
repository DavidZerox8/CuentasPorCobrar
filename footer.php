<div class="navbar navbar-default navbar-fixed-bottom">
    <div class="container">
        <p class="navbar-text pull-left">&copy <?php echo date('Y');?> -
            <a href="https://www.copacabanatextil.com/" target="_blank" style="color: #ecf0f1"> Copacabana Textil, S.A. De C.V.</a>
        </p>
    </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="vendor/jquery/jquery-1.11.3.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<!-- Latest compiled and minified JavaScript -->
<script src="vendor/boots/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<script language="javascript">
    function guardar() {

        campos = {
            "fecha": $("#fecha_ap").val(),
        };
        //Este es de la caja de texto    
        id_c = {
            "id": $("#cliente_c").val(),
        };
        //Este es del select
        id_c2 = {
            "id_c2": $("#cliente_cc").val(),
        };

        $.ajax({
            data: {
                fecha: campos,
                id_c: id_c,
                id_c2: id_c2
            },
            url: 'fechas.php',
            method: "POST",
            success: function(response) {
                $("#fecha_ven").val(response);
            }
        });

    }

    function obtener() {

        tipo = {
            "tipo": $("#tipo").val(),
        };

        factura = {
            "factura": $("#documento").val(),
        };

        $.ajax({
            data: {
                tipo: tipo,
                factura: factura
            },
            url: 'liquidaciones.php',
            method: "POST",
            success: function(response) {
                $("#monto").val(response);
            }
        });

    }

    //la chida para obtener las facturas dinamicamente
    $(document).ready(function() {
        $("#cliente_cc").on('change', function() {
            $("#cliente_cc option:selected").each(function() {
                elegido = $(this).val();
                $.post("datos_m.php", {
                    elegido: elegido
                }, function(data) {
                    $("#documento_f").html(data);
                });
            });
        });
    });



    //la chida para obtener las facturas dinamicamente
    $(document).ready(function() {
        $("#concepto").on('change', function() {
            $("#concepto option:selected").each(function() {
                elegido = $(this).val();
                $.post("datos.php", {
                    elegido: elegido
                }, function(data) {
                    //$("#documento").html(data);
                    if (data == 'Abono') {
                        //document.getElementById('principal').getElementsByClassName('prueba');
                        document.getElementById("fecha_ven").style.display = "none";
                        document.getElementById("buscar_f").style.display = "block";
                    } else {
                        document.getElementById("fecha_ven").style.display = "block";
                        document.getElementById("buscar_f").style.display = "none";
                    }

                });
            });
        });
    });


    $(document).ready(function() {
        $("#cliente").on('change', function() {
            $("#cliente option:selected").each(function() {
                elegido = $(this).val();
                $.post("check.php", {
                    elegido: elegido
                }, function(data) {
                    $("#facturas").html(data);
                });
            });
        });
    });

    $(document).ready(function() {
        $('#Modal1').modal('toggle')
    });


    $('#exampleModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        //var recipient = button.data('whatever') // Extract info from data-* attributes
        var recipient = $('#cliente_cc').val();
        // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
        // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
        var modal = $(this)
        modal.find('.modal-title').text('Por favor, elija una factura disponible.')
        modal.find('.modal-body #recipient-name').val(recipient)
    })


    function pparametros() {
        var value = $('#datos[]').val();
        $('#factura').val(value);
    }


    function guardar_f() {

        campos = {
            "fecha": $("#fecha_ap2").val(),
        };

        id_c = {
            "id": $("#cliente").val(),
        };

        $.ajax({
            data: {
                fecha: campos,
                id_c: id_c
            },
            url: 'fecha.php',
            method: "POST",
            success: function(response) {
                $("#fecha_ven2").val(response);
            }
        });

    }

    function reset() {
        $("#guardar_cliente")[0].reset();
        location.reload();
    }


    $("#btn_f").click(function() {
        let valoresCheck = [];
        $("input[type=checkbox]:checked").each(function() {
            valoresCheck.push(this.value);
        });

        $.ajax({
            data: {
                valores: valoresCheck
            },
            url: 'prueba.php',
            method: "POST",
            success: function(response) {

                let str = response;
                let arr = str.split(';', 2);

                $("#factura").val(arr[0]);
                $("#monto").val(arr[1].trim());
            }
        });

    });

</script>
