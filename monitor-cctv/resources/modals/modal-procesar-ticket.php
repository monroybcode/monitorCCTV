<div class="modal fade" id="mdl-procesar-ticket">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <label>
                    Procesar
                </label>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm-procesa-ticket">
                    <div class="form-group">
                        <textarea placeholder="Nota*" required="true" id="txt-note" name="txt-note" class="form-control txt-procesar-ticket"></textarea>
                        <br/>
                        <input type="file" class="form-control form-inline input-sm" name="adjunto[]" id="adjunto" multiple>  
                    </div>
                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btn-procesa-solicitud">Aplicar</button>
                <button type="button" class="btn btn-default cancelar" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>