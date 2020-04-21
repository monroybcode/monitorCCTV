<!-- Modal -->
<div id="modal-datos-usuario" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 60%;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Usuario</h4>
            </div>
            <div class="modal-body">
                <form  id="frmUsuario" name="frmUsuario" method="post" style="padding: 0 15px;">
                    <input type="hidden" id="id_usuario" name="id_usuario" value="">

                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-lg-6">
                            <input type="input" class="form-control input-sm" id="nombre" name="nombre" 
                                   placeholder="Nombre">
                            <div name="nombrediv" id="nombrediv" class="errordiv"></div>
                        </div>
                        <div class="col-lg-6">
                            <input type="input" class="form-control input-sm" id="login" name="login" 
                                   placeholder="Login">
                            <div name="logindiv" id="logindiv" class="errordiv"></div>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-lg-6">
                            <select class="form-control input-sm" id="rol" name="rol">
                                <option value="">Rol</option>
                                <?php
                                $sql = "select * from catalogo_valor where catalogo=1";
                                $resultado = $mysqli->query($sql);

                                while ($fila = $resultado->fetch_assoc()) {
                                    echo '<option value="' . $fila['id'] . '">' . $fila['descripcion'] . '</option>';
                                }
                                ?>
                            </select>
                            <div name="roldiv" id="roldiv" class="errordiv"></div>
                        </div>
                        <div class="col-lg-6">
                            <input type="text" class="form-control input-sm" id="puesto" name="puesto" 
                                   placeholder="Puesto">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 5px;">
                        <div class="col-lg-6">
                            <div class="col-lg-1 col-md-1">
                                <input type="checkbox" name="activo" id="activo" class="checkbox" checked="checked">
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <label for="activo">Activo</label>
                            </div>
                            <div class="col-lg-1 col-md-1">
                                <input type="checkbox" name="notificaciones" id="notificaciones" class="checkbox">
                            </div>
                            <div class="col-lg-6 col-md-6">
                                <label for="notificaciones">Notificaciones Email</label>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <input type="E-mail" class="form-control input-sm" id="email" name="email" 
                                   placeholder="email" disabled>
                            <div name="emaildiv" id="emaildiv" class="errordiv"></div>
                        </div>
                    </div>




                    <label>Area</label>
                    <br/>
                    <div class="col-lg-12 form-group">
                        <div class="col-lg-5" style="padding-right: 5px;padding-left: 0px;">
                            <select multiple="multiple" class="form-control" id="grupos" size="5">

                            </select>
                        </div>

                        <div class="col-lg-2" style="padding-right: 15px;padding-left: 15px;">
                            <button class="btn btn-default btn-block add-grupo">Agregar >></button>
                            <button class="btn btn-default btn-block remove-grupo"><< Quitar</button>
                        </div>

                        <div class="col-lg-5" style="padding-right: 0px;padding-left: 5px;">
                            <select multiple="multiple" class="form-control" id="grupos_usuario" size="5"></select>
                        </div>
                    </div>






                    <label>Hospital</label>
                    <br/>

                    <div class="col-lg-12 form-group">
                        <div class="col-lg-5" style="padding-right: 5px;padding-left: 0px;">
                            <select multiple="multiple" class="form-control" id="hospitales" size="5">

                            </select>
                        </div>

                        <div class="col-lg-2" style="padding-right: 15px;padding-left: 15px;">
                            <button class="btn btn-default btn-block add-hospital">Agregar >></button>
                            <button class="btn btn-default btn-block remove-hospital"><< Quitar</button>
                        </div>

                        <div class="col-lg-5" style="padding-right: 0px;padding-left: 5px;">
                            <select multiple="multiple" class="form-control" id="hospitales_usuario" size="5"></select>
                        </div>
                    </div>

                    <label>Categorías</label>
                    <br/>
                    <div class="col-lg-12 form-group">
                        <div class="col-lg-5" style="padding-right: 5px;padding-left: 0px;">
                            <select multiple="multiple" class="form-control" id="categorias" size="5" style="overflow-x: auto;">

                            </select>
                        </div>

                        <div class="col-lg-2" style="padding-right: 15px;padding-left: 15px;">
                            <button class="btn btn-default btn-block add-categoria">Agregar >></button>
                            <button class="btn btn-default btn-block remove-categoria"><< Quitar</button>
                        </div>

                        <div class="col-lg-5" style="padding-right: 0px;padding-left: 5px;">
                            <select multiple="multiple" class="form-control" id="categorias_usuario" size="5" style="overflow-x: auto;"></select>
                        </div>
                    </div>
                    
                    
                    <label>Tipo Notificaciones</label>
                    <br/>
                    <div class="col-lg-12 form-group">
                        <div class="col-lg-5" style="padding-right: 5px;padding-left: 0px;">
                            <select multiple="multiple" class="form-control" id="ntf_tipos" size="5" disabled="true">

                            </select>
                        </div>

                        <div class="col-lg-2" style="padding-right: 15px;padding-left: 15px;">
                            <button class="btn btn-default btn-block add-ntf" id="boton_antf" disabled="true">Agregar >></button>
                            <button class="btn btn-default btn-block remove-ntf" id="boton_qntf" disabled="true"><< Quitar</button>
                        </div>

                        <div class="col-lg-5" style="padding-right: 0px;padding-left: 5px;">
                            <select multiple="multiple" class="form-control" id="ntf_tipos_usuario" size="5" disabled="true"></select>
                        </div>
                    </div>
                    
                    
                    
                    

                    <div class="errordiv" id="add_erradduser" name="add_erradduser"></div>

                    <div class="form-group" style="text-align: end">
                        <button type="button" class="btn btn-default" id="btn-reset-pass" style="float: left">Resetear contraseña</button>
                        
                        <button type="button" class="btn btn-primary" onclick="javascript:guardarDatos()">Guardar</button>
                        
                        <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
                    </div>

                </form>
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
        
        
        $('.add-ntf').click(function () {
            return !$('#ntf_tipos option:selected').remove().appendTo('#ntf_tipos_usuario');
        });
        $('.remove-ntf').click(function () {
            return !$('#ntf_tipos_usuario option:selected').remove().appendTo('#ntf_tipos');
        });





    });



</script>