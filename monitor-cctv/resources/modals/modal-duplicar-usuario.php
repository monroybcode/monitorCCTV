<!-- Modal -->
<div id="modal-duplicar-usuario" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 60%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="titulo-modal-duplicar">Duplicar Usuario</h4>
            </div>
            <div class="modal-body">
                <div class="busqueda-usuario">
                    <div class="row">
                        <div class="col-md-12">
                            <form id="filtrosb2">      
                                Búsqueda de usuarios
                                <div class="row">
                                    <div class="col-md-3 col-lg-3">                                                              
                                        <select class="form-control" id="cmbbuscahospitalpres" name="sltHospital">
                                            <?php consulta_hospitales_adm_usr(); ?>  
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-lg-3">                                                              
                                        <select class="form-control" id="cmbbuscaclasificacion" name="sltRol">
                                            <?php consulta_roles_adm_usr(); ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3 col-lg-3">                                                              
                                        <input class="form-control" id="txtDescripcion" name="txtVarios" type="text" data-toggle="tooltip" title="Busca por login, nombre o email" onkeydown="if (event.keyCode == 13) { return false;}"/>
                                    </div>
                                    <div class="col-md-2 col-lg-2"> 
                                        <button type="button" class="btn btn-link" id="consultarUsrd"  style="margin: -5px 0 0 0;">
                                            <span class="glyphicon glyphicon-search"></span> CONSULTAR
                                        </button>                      
                                    </div>
                                    <div class="col-md-1 col-lg-1"> 
                                    
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="tabla-usuarios-busq" style="overflow-y:scroll; height:500px;">
                        
                    </div>
                </div>
                <div class="form-duplicar">
                    <form  id="frmUsuario2" name="frmUsuario2" method="post" style="padding: 0 15px;">
                        <input type="hidden" id="id_usuario2" name="id_usuario" value="">

                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-lg-6">
                                <input type="input" class="form-control input-sm" id="nombre2" name="nombre" 
                                       placeholder="Nombre">
                                <div name="nombrediv" id="nombrediv2" class="errordiv"></div>
                            </div>
                            <div class="col-lg-6">
                                <input type="input" class="form-control input-sm" id="login2" name="login" 
                                       placeholder="Login">
                                <div name="logindiv" id="logindiv2" class="errordiv"></div>
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-lg-6">
                                <select class="form-control input-sm" id="rol2" name="rol">
                                    <option value="">Rol</option>
                                    <?php
                                    $sql = "select * from catalogo_valor where catalogo=1";
                                    $resultado = $mysqli->query($sql);

                                    while ($fila = $resultado->fetch_assoc()) {
                                        echo '<option value="' . $fila['id'] . '">' . $fila['descripcion'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <div name="roldiv" id="roldiv2" class="errordiv2"></div>
                                
                            </div>
                            <div class="col-lg-6">
                                <input type="text" class="form-control input-sm" id="puesto2" name="puesto" 
                                       placeholder="Puesto">
                            </div>
                        </div>
                        <div class="row" style="margin-bottom: 5px;">
                            <div class="col-lg-6">
                                <div class="col-lg-1">
                                    <input type="checkbox" name="activo" id="activo2" class="checkbox" checked="checked">
                                </div>
                                <div class="col-lg-4">
                                    <label for="activo2">Activo</label>
                                </div>
                                <div class="col-lg-1">
                                    <input type="checkbox" name="notificaciones" id="notificaciones2" class="checkbox">
                                </div>
                                <div class="col-lg-6">
                                    <label for="notificaciones2">Notificaciones Email</label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <input type="E-mail" class="form-control input-sm" id="email2" name="email" 
                                       placeholder="email" disabled>
                                <div name="emaildiv" id="emaildiv2" class="errordiv"></div>
                            </div>
                        </div>




                        <label>Grupos</label>
                        <br/>
                        <div class="col-lg-12 form-group">
                            <div class="col-lg-5" style="padding-right: 5px;padding-left: 0px;">
                                <select multiple="multiple" class="form-control" id="grupos2" size="5">

                                </select>
                            </div>

                            <div class="col-lg-2" style="padding-right: 15px;padding-left: 15px;">
                                <button class="btn btn-default btn-block add-grupo">Agregar >></button>
                                <button class="btn btn-default btn-block remove-grupo"><< Quitar</button>
                            </div>

                            <div class="col-lg-5" style="padding-right: 0px;padding-left: 5px;">
                                <select multiple="multiple" class="form-control" id="grupos_usuario2" size="5"></select>
                            </div>
                        </div>






                        <label>Hospital</label>
                        <br/>

                        <div class="col-lg-12 form-group">
                            <div class="col-lg-5" style="padding-right: 5px;padding-left: 0px;">
                                <select multiple="multiple" class="form-control" id="hospitales2" size="5">

                                </select>
                            </div>

                            <div class="col-lg-2" style="padding-right: 15px;padding-left: 15px;">
                                <button class="btn btn-default btn-block add-hospital">Agregar >></button>
                                <button class="btn btn-default btn-block remove-hospital"><< Quitar</button>
                            </div>

                            <div class="col-lg-5" style="padding-right: 0px;padding-left: 5px;">
                                <select multiple="multiple" class="form-control" id="hospitales_usuario2" size="5"></select>
                            </div>
                        </div>

                        <label>Categorías</label>
                        <br/>
                        <div class="col-lg-12 form-group">
                            <div class="col-lg-5" style="padding-right: 5px;padding-left: 0px;">
                                <select multiple="multiple" class="form-control" id="categorias2" size="5" style="overflow-x: auto;">

                                </select>
                            </div>

                            <div class="col-lg-2" style="padding-right: 15px;padding-left: 15px;">
                                <button class="btn btn-default btn-block add-categoria">Agregar >></button>
                                <button class="btn btn-default btn-block remove-categoria"><< Quitar</button>
                            </div>

                            <div class="col-lg-5" style="padding-right: 0px;padding-left: 5px;">
                                <select multiple="multiple" class="form-control" id="categorias_usuario2" size="5" style="overflow-x: auto;"></select>
                            </div>
                        </div>
                        
                           <div class="col-lg-12 form-group">
                            <div class="col-lg-5" style="padding-right: 5px;padding-left: 0px;">
                                <select multiple="multiple" class="form-control" id="ntf_tipos_usuario2" size="5" style="overflow-x: auto;">

                                </select>
                            </div>

                            <div class="col-lg-2" style="padding-right: 15px;padding-left: 15px;">
                                <button class="btn btn-default btn-block add-categoria">Agregar >></button>
                                <button class="btn btn-default btn-block remove-categoria"><< Quitar</button>
                            </div>

                            <div class="col-lg-5" style="padding-right: 0px;padding-left: 5px;">
                                <select multiple="multiple" class="form-control" id="ntf_tipos2" size="5" style="overflow-x: auto;"></select>
                            </div>
                        </div>

                        <div class="errordiv" id="add_erradduser2" name="add_erradduser"></div>

                        <div class="form-group" style="text-align: end">
                            
                            <button type="button" class="btn btn-primary" onclick="javascript:guardarDatosDuplicadog()">Registrar</button>
                            
                            <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    $().ready(function () {
        $('.add-hospital').click(function () {
            return !$('#hospitales option:selected').remove().appendTo('#hospitales_usuario');
        });
        $('.remove-hospital').click(function () {
            return !$('#hospitales_usuario option:selected').remove().appendTo('#hospitales');
        });





        $('.add-grupo').click(function () {
            return !$('#grupos option:selected').remove().appendTo('#grupos_usuario');
        });
        $('.remove-grupo').click(function () {
            return !$('#grupos_usuario option:selected').remove().appendTo('#grupos');
        });



        $('.add-categoria').click(function () {
            return !$('#categorias option:selected').remove().appendTo('#categorias_usuario');
        });
        $('.remove-categoria').click(function () {
            return !$('#categorias_usuario option:selected').remove().appendTo('#categorias');
        });





    });



</script>