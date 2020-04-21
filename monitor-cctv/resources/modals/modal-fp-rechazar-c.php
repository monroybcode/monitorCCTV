<div class="modal fade" id="mdl-fp-rechazar-c-ticket">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <label>
                    Rechazar
                </label>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm-fp-rechazar-c-ticket">
                    <div class="form-group form-inline">
                        <label class="label-group">Nota</label>
                        <textarea placeholder="" rows="5" id="txt-note" name="txt-note" class="form-control txt-nota-cerrar control-group"></textarea>
                    </div>


                    <div class="form-group form-inline">
                        <label class="label-group">Adjuntar</label>
                        <input type="file" class="form-control form-inline input-sm control-group" name="adjunto[]" id="adjunto" multiple>  
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-fp-rechazar-c-ticket">Aplicar</button>
                <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>