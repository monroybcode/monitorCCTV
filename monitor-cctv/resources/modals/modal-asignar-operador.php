<div class="modal fade" id="mdl-asignar">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <label>
                    Asignar
                </label>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="frm-asigna-operador" role="form">
                    <input type="hidden" value="" name="stts_asignar_operador" id="stts_asignar_operador">
                    <!--div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="col-lg-3 col-lg-offset-3 col-md-3 col-md-offset-3 col-sm-4 col-sm-offset-2 col-xs-5 col-xs-offset-1">
                                Información <input type="checkbox" name="informacion" id="informacion" value="informacion" onclick="desmarcar('autorizacion')"/>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5">
                                Autorización <input type="checkbox" name="autorizacion" id="autorizacion" value="autorizacion" onclick="desmarcar('informacion')"/>
                            </div>
                        </div>
                    </div-->


                    <div class="form-group form-inline">
                        <label class="label-group">Responsable</label>
                        <select name="operador" id="operador" class="form-control control-group">
                        </select>
                    </div>


                    <div class="form-group form-inline">
                        <label class="label-group">Nota</label>
                        <textarea placeholder="" required="true" rows="5" id="txt-note" name="txt-note" class="form-control txt-nota-asignar control-group"></textarea>           
                        <!--<br/>-->
                        <!--<input type="file" class="form-control form-inline input-sm" name="adjunto[]" id="adjunto" multiple>-->  
                    </div>




                </form>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-asigna-operador">Aplicar</button>
                <button type="button" class="btn btn-link cancelar" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
