<div wire:ignore.self class="modal fade" id="add_edit_modal" tabindex="-1" role="dialog" aria-labelledby="add_edit_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content py-2">
            <div class="modal-header">
                <h4 class="modal-title text-purple"><span class="fas fa-user-cog mr-3"></span>{{ $modal_title }}</h4>
                <button type="button" wire:click.prevent="cancel()" thotam-blockui class="close" data-dismiss="modal" wire:loading.attr="disabled" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($addStatus || $editStatus)
                <div class="modal-body">
                    <div class="container-fluid mx-0 px-0">
                        <form>
                            <div class="row">

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label" for="key">Mã nhân sự:</label>
                                        <div id="key_div">
                                            <input type="text" class="form-control px-2" wire:model.lazy="key" id="key" style="width: 100%" @if ($hr->key == $old_hr->key) readonly @endif placeholder="Mã nhân sự ..." autocomplete="off">
                                        </div>
                                        @error('key')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label" for="hoten">Họ và tên:</label>
                                        <div id="hoten_div">
                                            <input type="text" class="form-control px-2" wire:model.lazy="hoten" id="hoten" style="width: 100%" placeholder="Họ và tên ..." autocomplete="off">
                                        </div>
                                        @error('hoten')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label" for="ngaysinh">Ngày sinh:</label>
                                        <div id="ngaysinh_div">
                                            <input type="text" class="form-control px-2 thotam-datepicker" thotam-startview="3" thotam-container="ngaysinh_div" wire:model="ngaysinh" id="ngaysinh" style="width: 100%" placeholder="Ngày sinh ..." readonly autocomplete="off">
                                        </div>
                                        @error('ngaysinh')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="col-form-label" for="ngaythuviec">Ngày thử việc:</label>
                                        <div id="ngaythuviec_div">
                                            <input type="text" class="form-control px-2 thotam-datepicker" thotam-startview="3" thotam-container="ngaythuviec_div" wire:model="ngaythuviec" id="ngaythuviec" style="width: 100%" placeholder="Ngày thử việc ..." readonly autocomplete="off">
                                        </div>
                                        @error('ngaythuviec')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                                @if ($editStatus)
                                    <div class="col-12">
                                        <div class="input-group form-group border-bottom thotam-border py-2">
                                            <div class="input-group-prepend mr-4">
                                                <label class="col-form-label col-6 text-left pt-0 input-group-text border-0" for="active">Kích hoạt nhân sự:</label>
                                            </div>
                                            <label class="switcher switcher-square">
                                                <input type="checkbox" class="switcher-input form-control" wire:model="active" id="active" style="width: 100%">
                                                <span class="switcher-indicator">
                                                    <span class="switcher-yes"></span>
                                                    <span class="switcher-no"></span>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="modal-footer mx-auto">
                <button wire:click.prevent="cancel()" thotam-blockui class="btn btn-danger" wire:loading.attr="disabled" data-dismiss="modal">Đóng</button>
                <button wire:click.prevent="save_hr()" thotam-blockui class="btn btn-success" wire:loading.attr="disabled">Xác nhận</button>
            </div>

        </div>
    </div>

</div>
