<div id="mdl-aut-rech-ticket" class="modal fade" role='dialog'>
    <div class="modal-dialog" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <label class="title-op"></label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="frm-aut-rech-ticket" style="text-align: center;">
                    <input type="hidden" id="operacion" name="operacion" value="">
                    <textarea placeholder="Nota*" required="true" id="txt-note" name="txt-note" class="form-control txt-nota-aut-rech"></textarea>
                    <br/>
                    <input type="file" class="form-control form-inline input-sm" name="adjunto[]" id="adjunto" multiple>  
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btn-aplica-rech-aut">Aplicar</button>
                <button type="button" class="btn btn-default cancelar" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
<!--Modal ends here--->