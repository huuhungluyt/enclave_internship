<div class="modal fade" id="popupAddMajor" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">

            <form id="formAddMajor" method="POST" class="form-horizontal" >




                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">ADD NEW MAJOR</h4>
                </div>




                <div class="modal-body" style="margin: 10px;">


                    <div class="form-group">
                        <label class="control-label col-sm-4" >Name</label>
                        <div class="col-sm-8">
                            <input type="hidden" name="notiId">
                            <input type="text" class="form-control" name="majorName">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-sm-4">Description</label>
                        <div class="col-sm-8">
                            <textarea name="majorDescription" rows="4" cols="41" maxlength="200" style="resize: none;"></textarea>
                        </div>
                    </div>


                </div>




                <div class="modal-footer">
                    <input type="submit" class="btn btn-success " value='Submit'>
                    <button class="btn btn-default btn-close-popup" data-dismiss="modal">Close</button>
                </div>



            </form>

        </div>
    </div>
</div>