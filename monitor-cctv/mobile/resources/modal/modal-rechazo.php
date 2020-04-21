<script>
    $(document).ready(function () {

        $("#btnGuardar").click(function (e) {

            $("#message-text").css('border-color', '#A7A9AB', 'important');
            $("#errorFrm2").css('display', 'none', 'important');

            faltaCampo = "no";

            if (document.subir_detalle.message.value === "") {
                
             $(".form-control").css('border-color', 'red', 'important');
                faltaCampo = "si";
            }
            console.log(faltaCampo);

            if (faltaCampo === "si") {
                swal("Error", "Es necesario el motivo de rechazo", "error");
                e.preventDefault();
            } else {
                enviarformulario();
            }

        });
    });

    function enviarformulario() {
        var formData = $("#subir_detalle").serialize();
        
        $.ajax({
            
            type:"POST",
            url:"resources/controller/motivo_rechazo.php",
            data:formData,
           

            success: function (data) {  
            correcto();
                
                
           
            }
        })
    }
    
    
      function correcto() {
             swal({
                title:"Correcto",
                text: "Reporte rechazado Satisfactoriamente",
                type: "success",
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                cancelButtonText: false,
                closeOnConfirm: false,
                closeOnCancel: false
            }).then(function () {
               
               document.location.href = 'Mobile-tickets.php';
            })
      }

</script>


<div class="modal fade" id="rechazar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%;">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #285e8e; color: white">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h5 class="modal-title" id=""> <span class="glyphicon glyphicon-ban-circle" ></span> Rechazo de Reporte # <?php echo $_GET['id']; ?></h4>
            </div>
            <div class="modal-body">
                <div id="errorFrm2" style="display: none; color: red;">
                    <h4><span class="glyphicon glyphicon-warning-sign"></span> Llene todos los campos y seleccione el XML</h4>
                </div>
                <form action="#" method="post"  id="subir_detalle" name="subir_detalle" role="form" enctype="multipart/form-data">
                    <div class="form-group" id="message">
                        <label  class="form-control-label">Motivo de rechazo:</label>
                        <textarea class="form-control" id="message" name="message" rows="10"></textarea>
                    </div>
                    <input hidden="true" value="<?php echo $_GET['id']; ?>" name="id">
                </form>
            </div>
            <div class="modal-footer">
                <button id="btnGuardar" type = "submit" class="btn btn-success btn-lg btn-block">Guardar</button> 
                <br>
                <button type="button" class="btn btn-secondary btn-lg btn-block" style="background-color: #FA5858; color: white;" data-dismiss="modal">Cancelar</button>

            </div>
        </div>
    </div>
</div>
