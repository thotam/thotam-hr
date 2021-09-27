<div wire:ignore.self class="modal fade" id="import_modal" tabindex="-1" role="dialog" aria-labelledby="import_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content py-2">
            <div class="modal-header">
                <h4 class="modal-title text-purple"><span class="fas fa-user-cog mr-3"></span>{{ $modal_title }}</h4>
                <button type="button" wire:click.prevent="cancel()" thotam-blockui class="close" data-dismiss="modal" wire:loading.attr="disabled" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($importStatus)
                <div class="modal-body">
                    <div class="container-fluid mx-0 px-0">
                        <form>
                            <div class="row">

                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label class="col-form-label text-indigo">File Export từ BFO:</label>
                                        <div id="file_data_bfo_div" wire:ignore>
                                            <input type="file" class="form-control px-2 thotam-filestyle" thotam-icon="fas fa-file-excel" thotam-placeholder="File Export từ BFO" thotam-btnClass="btn-indigo" thotam-text="Chọn tệp" wire:model="file_data_bfo" id="file_data_bfo" accept="application/vnd.ms-excel" style="width: 100%" placeholder="Chọn File Export từ BFO ..." autocomplete="off">
                                        </div>
                                        @error('file_data_bfo')
                                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <div class="modal-footer mx-auto">
                <button wire:click.prevent="cancel()" thotam-blockui class="btn btn-danger" wire:loading.attr="disabled" data-dismiss="modal">Đóng</button>
                <button wire:click.prevent="do_import_hr()" thotam-blockui class="btn btn-success" wire:loading.attr="disabled">Xác nhận</button>
            </div>

        </div>
    </div>

</div>
