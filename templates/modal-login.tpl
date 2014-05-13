<div class="modal fade" id="login-form-modal" tabindex="-1" role="dialog" aria-labelledby="login-form-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="modal-header" style="margin-bottom: 1em;">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    {$login_url}
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <label>Name</label>
                        <input type="text" class="form-control" placeholder="Name">
                    </div>

                    <div class="col-xs-6">
                        <label>Email</label>
                        <input type="text" class="form-control" placeholder="Email">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success">Login</button>
            </div>
        </div>
    </div>
</div>

<style>
    #login-form-modal.modal {
        top: 32px;
        z-index: 99999;
        font-style: normal;
        text-align: left;
    }
</style>

