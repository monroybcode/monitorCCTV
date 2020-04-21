<div id="modal-agrega-rol" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Administracion de Roles</h4>
            </div>
            <div class="modal-body">
                <form name="frm_rol" id="frm_rol" method="post">
                    <input type="hidden" id="id_rol" name="id_rol">
                    <div class="form-group">
                        <label for="nombre_rol">Nombre de rol</label>
                        <input type="input" class="form-control" id="nombre_rol" name="nombre_rol" placeholder="Nombre de Rol" required autofocus onfocus="$('#nombrediv').html('');">
                        <div name="nombrediv" id="nombrediv" class="errordiv"></div>
                    </div>
                    <div class="form-group" style="text-align: end">
                        <button type="button" onclick="javascript:guardar_rol();"  class="btn btn-primary right">Guardar</button>
                        <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>