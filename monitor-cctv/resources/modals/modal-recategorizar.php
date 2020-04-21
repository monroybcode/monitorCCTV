<!----modal starts here--->
<div id="mdl-recategorizar-ticket" class="modal fade" role='dialog'>
    <div class="modal-dialog" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <label>Clasificar</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm-recategorizar-ticket">                    

                    <div class="form-group">
                        <select class="form-control" id="categoria_r" name="categoria_r">
                            <option value="">Nueva Categoria</option>
                            <option value='8'>Aclaraciones Expediente Clínico</option>
                            <option value='6'>Errores en Precios</option>
                            <option value='7'>Cargos Incorrectos</option>
                        </select>
                    </div>


                    

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-recategorizar">Aplicar</button>
                <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
<!--Modal ends here--->