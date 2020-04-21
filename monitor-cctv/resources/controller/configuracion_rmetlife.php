<script>
    $(document).ready(function (e) {


        $("#tbl_distribucion").click(function (e) {
            $("#tbl_distribucion_e").show();
            $("#tbl_distribucion_t").hide();
            $("#tbl_distribucion_h").hide();

        });

        $("#tbl_tiemposd").click(function (e) {
            $("#tbl_distribucion_h").show();
            $("#tbl_distribucion_t").hide();
            $("#tbl_distribucion_e").hide();
        });

        $("#tbl_tiemposr").click(function (e) {
            $("#tbl_distribucion_t").show();
            $("#tbl_distribucion_e").hide();
            $("#tbl_distribucion_h").hide();
        });


    });

    function agregar_em() {
        $("#modal-agrega-rmetlife").modal("show");
        $(".modal-title-metlife").html('Agregar Email de Distribucion');
    }


    $(document).on('click', '.acciones', function () {

        var id = $(this).data('internalid');
        console.log(id);

        swal({
            type: "warning",
            title: '¿Estas seguro?',
            text: "",
            confirmButtonText: 'Eliminar',
            confirmButtonColor: '#d33',
            showLoaderOnConfirm: true,
            cancelButtonText: "Cancelar",
            showCancelButton: true,
        }).then(function () {
            $.ajax({
                type: "POST",
                url: "resources/controller/controller_config_metlife.php",
                data: "id=" + id + "&operacion=" + "eliminar" + "&tipo=" + "email",
                success: function (data) {
                    swal("Proceso realizado!", "", "success");
                    $("#tbl_distribucion_e").load(" #tbl_distribucion_e");

                }
            });
        }).catch(swal.noop);

    });

    $(document).on('click', '.editar_h', function () {
        var id = $(this).data('internalid2');
        $("#modal-editar-horas").modal("show");
        $(".modal-title-emetlife").html('Editar Horas de Vencimiento');
        $.ajax({
            type: "POST",
            url: "resources/controller/controller_config_metlife.php",
            data: "id=" + id + "&operacion=" + "consultar" + "&tipo=" + "horas",
            dataType: 'json',
            success: function (data) {
                $('#hospital_h').val(data.hospital);
                $('#categoria_h').val(data.categoria);
                $('#horas_h').val(data.horas);
                $('#id_oculto').val(data.id);

            }
        });
    });

    function crear_elemento(tipo) {
        if (tipo == 'email') {
            var formData = $("#frm_categoria-metlife").serialize() + '&operacion=' + 'agregar' + '&tipo=' + tipo;
        } else
        if (tipo == 'horas') {
            var formData = $("#frm_categoria-emetlife").serialize() + '&operacion=' + 'editar' + '&tipo=' + tipo;
        }

        $.ajax({
            type: "POST",
            url: "resources/controller/controller_config_metlife.php",
            data: formData,
            dataType: 'json',

            success: function (data) {

                if (tipo == 'email') {
                    swal("Se Guardo Correctamente!", "", "success");
                    $("#modal-agrega-rmetlife").modal("hide");
                    $("#tbl_distribucion_e").load(" #tbl_distribucion_e");
                } else
                if (tipo == 'horas') {
                    swal("Se Actualizo Correctamente!", "", "success");
                    $("#modal-editar-horas").modal('hide');
                    $("#tbl_distribucion_t").load(" #tbl_distribucion_t");

                }

            }
        });
    }

</script>

<div>

    <nav class="navbar navbar-default navbar-static-top menu-hsm" role="navigation" style="padding-left: 15px !important;">

      <!--  <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">                
            <li>
                <a href="#"  class="link-item-list link-principal link-item-navbar" id="tbl_distribucion" style="padding: 5px 15px;">
                    <span>Matriz Distribucion</span>
                </a>
            </li>
        </ul>
         <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">                
             <li>
                 <a href="#"  class="link-item-list link-principal link-item-navbar" id="tbl_tiemposd" style="padding: 5px 15px;">
                     <span>Matriz Tiempos de Distribucion</span>
                 </a>
             </li>
         </ul>--->
        <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">                
            <li>
                <a href="#"  class="link-item-list link-principal link-item-navbar" id="tbl_tiemposr" style="padding: 5px 15px;">
                    <span>Matriz de Tiempos de Respuesta</span>
                </a>
            </li>
        </ul>
        <ul class="tabs tabs-horizontal nav navbar-nav navbar-left">

            <li>
                <a href="#" class="link-item-list link-item-navbar dropdown-toggle link-administracion" data-toggle="dropdown" aria-expanded="false"  style="padding: 5px 15px;">Ajustes de Vista
                    <span class="fa fa-caret-down"></span></a>
                <ul class="dropdown-menu" style="border: solid 1px #adadad;">
                    <li class="list-group-item">
                        <button class="btn btn-primary" onclick="aguscalientes();" style="width: 100%" id="b_aguascalientes">Aguascalientes</button>
                    </li>

                    <li class="list-group-item">
                        <button class="btn btn-primary" onclick="merida();" style="width: 100%" id="b_merida">Merida</button>
                    </li>

                    <li class="list-group-item">
                        <button class="btn btn-primary" onclick="juarez();" style="width: 100%" id="b_juarez">Juarez</button>
                    </li>

                    <li class="list-group-item">
                        <button class="btn btn-success" onclick="todos();" style="width: 100%" id="todos">Todos</button>
                    </li>
                </ul>
            </li>

        </ul>
    </nav>   
</div>



<div style="width: 90%; height: 50%; padding-top: 25px; margin: 0 5%;  position: relative">

     <!--  <div id="tbl_distribucion_e">
        <div class="pull-right" style="z-index: 1; position: relative; padding-right: 0px; padding-bottom: 10px" >
            <a href="#" class="btn btn-sm btn-default" id="configuracion" onclick="agregar_em()"><span class="glyphicon glyphicon-plus" ><span  style="font-family: HelveticaNeueRoman;">&nbsp;AGREGAR</span></span></a>
        </div>
        <br>
        <table class="table-s table table-hover table-responsive tbl-det-tickets" style="width: 100%" >
            <thead class="box box-primary">
                <tr>
                    <td style="width: 30%; text-align: center;" class="tot">Nombre</td>
                    <td style="width: 20%; text-align: center;" class="tot">Email</td>
                    <td style="width: 20%; text-align: center;" class="tot">Hospital</td>
                    <td style="width: 20%; text-align: center;" class="tot">Eliminar</td>
                </tr>
            </thead>

         <tbody>
              
              /*  include 'resources/connection/conexion.php';
                $mysqli->query("SET NAMES 'UTF8'");

                $sql = "SELECT 
                            matriz_distribucion.*, hospital.nombre as n_hospital
                        FROM
                            matriz_distribucion
                            inner join hospital on matriz_distribucion.hospital = hospital.id";
                // echo $sql;

                $resultados = $mysqli->query($sql);

                while ($fila = $resultados->fetch_assoc()) {

                    echo '<tr>';
                    echo '<td style="background: #eae9e9;">' . $fila['Nombre'] . '</td>';
                    echo '<td class="tot" style=" text-align: left;">' . ($fila['email']) . '</td>';
                     echo '<td class="tot" style=" text-align: left;">' . ($fila['n_hospital']) . '</td>';


                    echo '<td class="tot" style=" text-align: center;"><span title="Editar" class="acciones" data-internalid="' . $fila['idmatriz_distribucion'] . '" ><a class="glyphicon glyphicon-trash"></a></span></td>';

                    echo '</tr>';
                }*/
           
            </tbody>
        </table>
    </div>--->

    <div id="tbl_distribucion_h">
        <table class="table-s table table-hover table-responsive tbl-det-tickets"   style="width: 50%; margin: 0 25%">
            <thead class="box box-primary">
                <tr>
                    <td style="width: 20%; text-align: center;" class="tot">Horario</td>

                    <td style="width: 20%; text-align: center;" class="tot">Editar</td>
                    <td style="width: 20%; text-align: center;" class="tot">Eliminar</td>
                </tr>
            </thead>

            <tbody>
                <?php
                include 'resources/connection/conexion.php';
                $mysqli->query("SET NAMES 'UTF8'");

                $sql = "SELECT 
                        *
                     FROM
                        horario_distribucion";
                // echo $sql;

                $resultados = $mysqli->query($sql);

                while ($fila = $resultados->fetch_assoc()) {

                    echo '<tr>';
                    echo '<td style="background: #eae9e9;">' . $fila['horario'] . '</td>';


                    echo '<td class="tot" style=" text-align: center;"><a class="glyphicon glyphicon-pencil"></a></td>';
                    echo '<td class="tot" style=" text-align: center;"><a class="glyphicon glyphicon-trash"></a></td>';

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="tbl_distribucion_t">
        <table class="table-s table table-hover table-responsive tbl-det-tickets"  >
            <thead class="box box-primary">
                <tr>
                    <td style="width: 20%; text-align: center;" class="tot">Hospital</td>
                    <td style="width: 20%; text-align: center;" class="tot">Categoria</td>
                    <td style="width: 20%; text-align: center;" class="tot">Tiempos</td>
                    <td style="width: 20%; text-align: center;" class="tot">Editar</td>
                </tr>
            </thead>

            <tbody>
                <?php
                include 'resources/connection/conexion.php';
                $mysqli->query("SET NAMES 'UTF8'");

                $sql = "SELECT 
                       id_tiempo, tiempos_resolucion.tiempo, hospital.nombre as hospital, categoria.nombre
                     FROM
                         tiempos_resolucion
                             LEFT JOIN
                         hospital ON tiempos_resolucion.id_hospital = hospital.id
                             LEFT JOIN
                         categoria ON tiempos_resolucion.categoria = categoria.id order by hospital, tiempo desc";
                // echo $sql;

                $resultados = $mysqli->query($sql);

                while ($fila = $resultados->fetch_assoc()) {

                    echo '<tr>';
                    echo '<td style="background: #eae9e9;">' . $fila['hospital'] . '</td>';
                    echo '<td class="tot" style=" text-align: left;">' . ($fila['nombre']) . '</td>';
                    echo '<td class="tot" style=" text-align: center;">' . ($fila['tiempo']) . '</td>';
                    echo '<td class="tot" style=" text-align: center;"><span title="Editar" class="editar_h" data-internalid2="' . $fila['id_tiempo'] . '" ><a class="glyphicon glyphicon-pencil"></a></span></td>';

                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<!-----------------------------------------------MODALES------------------------------------------->
<div id="modal-agrega-rmetlife" class="modal fade" role="dialog" >
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title-metlife"></h4>
            </div>
            <div class="modal-body">
                <form name="frm_categoria_metlife" id="frm_categoria-metlife" method="post">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group">
                                <label>Nombre</label>
                                <select class="form-control" name="nombre_email" id="nombre_email"  onchange="$('#catpaddiv').html('');">
                                    <option value="" selected>Selecciona...</option>
                                    <?php
                                    $sql = "select id_usuario,nombre from usuarios where ind_activo=1";
                                    $resultado = $mysqli->query($sql);

                                    while ($fila = $resultado->fetch_assoc()) {
                                        echo '<option value="' . $fila['id_usuario'] . '">' . $fila['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                            
                              <div class="form-group">
                                <label>Hospital</label>
                                <select class="form-control" name="nombre_hospital" id="nombre_hospital"  onchange="$('#catpaddiv').html('');">
                                    <option value="" selected>Selecciona...</option>
                                    <?php
                                    $sql = "select id,nombre from hospital where id in(1102, 1103, 1104)";
                                    $resultado = $mysqli->query($sql);

                                    while ($fila = $resultado->fetch_assoc()) {
                                        echo '<option value="' . $fila['id'] . '">' . $fila['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group" style="text-align: end">
                        <button type="button" onclick="crear_elemento('email')"  class="btn btn-primary right">Guardar</button>
                        <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal-editar-horas" class="modal fade" role="dialog" >
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title-emetlife"></h4>
            </div>
            <div class="modal-body">
                <form name="frm_categoria_emetlife" id="frm_categoria-emetlife" method="post">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input  id="id_oculto" name="id_oculto" type="text" hidden="true">
                                <label>Hospital</label>
                                <input class="form-control" id="hospital_h" type="text" disabled="true">
                                <label>Categoria</label>
                                <input class="form-control" id="categoria_h" type="text" disabled="true">
                                <label>Horas</label>
                                <input class="form-control" id="horas_h" type="number" name="horas"  step="5"  required="required">
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="form-group" style="text-align: end">
                        <button type="button" onclick="crear_elemento('horas')"  class="btn btn-primary right">Actualizar</button>
                        <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>