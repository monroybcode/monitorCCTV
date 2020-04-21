<!----modal starts here--->
<div id="mdl-tratar-ticket" class="modal fade" role='dialog'>
    <div class="modal-dialog" role="document">
        <div class="modal-content" >
            <div class="modal-header">
                <label>Tratar Evento</label>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm-tratar-ticket">                    

                    <div class="form-group form-inline">
                        <label class="label-group">Nota</label>
                        <textarea placeholder="" rows="5" required="true" id="txt-note" name="txt-note" class="form-control txt-nota-tratar control-group"></textarea>
                    </div>


                    <div class="form-group form-inline" hidden="true">
                        <label class="label-group">Adjuntar</label>
                        <input type="file" class="form-control form-inline input-sm control-group" name="adjunto[]" id="adjunto" multiple> 
                    </div>
                    
                     <div class="form-group form-inline">
                        <label class="label-group">Url:</label>
                        <input type="text" class="form-control form-inline input-sm control-group" name="url" id="url"> 
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-aplicar">Aplicar</button>
                <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
            </div>

        </div>
    </div>
</div>
<!--Modal ends here--->