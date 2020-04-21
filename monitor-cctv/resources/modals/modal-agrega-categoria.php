<div id="modal-agrega-categoria" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Administracion de Categorias</h4>
            </div>
            <div class="modal-body">
                <form name="frm_categoria" id="frm_categoria" method="post">
                    <div class="row">
                        <div class="col-md-9">
                            <input type="hidden" id="id_categoria" name="id_categoria">
                            <div class="form-group">
                                <label for="nombre_categoria">Nombre de Categoria</label>
                                <input type="input" class="form-control" id="nombre_categoria" name="nombre_categoria" placeholder="Nombre de Categoria" required autofocus onfocus="$('#nombrediv').html('');">
                                <div name="nombrediv" id="nombrediv" class="errordiv"></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            
                            <div class="radio">
                                <label><input type="radio" name="ind_tipo" id="ind_tipo_principal" value="2" cheked>Principal</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="DetallePrincipal">
                        <div class="col-md-6">
                            <div class="form-group" id="divselectpadre" hidden="true">
                                <label>Categoria Padre</label>
                                <select class="form-control" name="categoria_padre" id="categoria_padre"  onchange="$('#catpaddiv').html('');">
                                <option value="" selected>Selecciona una Categoria</option>
                                <?php
                                $sql = "select id,nombre from categoria where tipo_categoria=1";
                                $resultado = $mysqli->query($sql);

                                while ($fila = $resultado->fetch_assoc()) {
                                    echo '<option value="' . $fila['id'] . '">' . $fila['nombre'] . '</option>';
                                }
                                ?>
                                </select>
                                <div name="catpaddiv" id="catpaddiv" class="errordiv"></div>
                            </div>
                            <div class="form-group" hidden="true">
                                <label for="url_formatos">URL Formatos</label>
                                <input class="form-control" type="text" name="url_formatos" id="url_formatos">
                            </div>
                            <div style="text-align:center;" hidden="true">
                                <!--<label>Subcategorias</label>&nbsp;&nbsp;&nbsp;-->
                                <button type="button" class="btn btn-primary" id="agregar_subcategoria"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Agregar Subcategoria</button>
                            <div class="col-md-12 subcategorias_dinamicas" >
                            </br>
                                
                            </div>
                        </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" hidden="true">
                                <label>Grupo</label>
                                <select class="form-control" name="grupo" id="grupo">
                                <option value="" selected>Selecciona una Grupo</option>
                                <?php
                                $sql = "select id_grupo,nombre from grupos";
                                $resultado = $mysqli->query($sql);

                                while ($fila = $resultado->fetch_assoc()) {
                                    echo '<option value="' . $fila['id_grupo'] . '">' . $fila['nombre'] . '</option>';
                                }
                                ?>
                                </select>
                            </div>
                            <div class="form-group" hidden="tue">
                                <label for="desc_ayuda">Descripcion Ayuda</label>
                                <textarea class="form-control" rows="3" id="desc_ayuda" name="desc_ayuda"></textarea>
                            </div>
                            <div class="checkbox">
                                <label for="ind_activo"><input type="checkbox" name="ind_activo" id="ind_activo" disabled checked>Activo</label>
                            </div>
                        </div>
                        
                    </div>
                    <div class="form-group" style="text-align: end">
                        <button type="button" onclick="javascript:guardar_categoria();"  class="btn btn-primary right">Guardar</button>
                        <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>