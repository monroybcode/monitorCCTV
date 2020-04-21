<!----modal starts here--->
<div id="modal-edita-nota" class="modal fade" role='dialog'>
    <div class="modal-dialog" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <label>Tratar Evento</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="frm-edita-nota" style="text-align: center;">  
                    <input type="hidden" id="id_nota_hdn" name="id_nota_hdn" value="">
                    <textarea rows="5" placeholder="Nota*" required="true" id="txt-note" name="txt-note" class="form-control txt-nota-editar"></textarea>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btn-aplicar-editar-nota">Aplicar</button>
                <button type="button" class="btn btn-default cancelar" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
<!--Modal ends here--->