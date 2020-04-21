<!-- Modal -->
<div id="modal-rol-funciones" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width: 45%;">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="titulo"></h4>
            </div>
            <div class="modal-body no-padding">
                <div class="panel-body" style="height: 300px;overflow-y: scroll;font-family: HelveticaNeue; padding: 15px;">
                    <form role="form" name="frm_funciones_de_rol" id="frm_funciones_de_rol">
                        <input type="hidden" id="id_rol_tratado" name="id_rol_tratado">
                        <div id="tbl_lista_funciones">
                        </div>
                    </form>
                </div>
                <div class="err" id="add_err3"></div> 

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="guardar_funciones_rol()">Guardar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Cerrar</button>
            </div>

        </div>

    </div>
</div>
<!-- fin modal!-->