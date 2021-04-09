<div wire:ignore.self class="modal fade" id="set_permission_modal" tabindex="-1" role="dialog" aria-labelledby="set_permission_modal" aria-hidden="true" data-toggle="modal" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content py-2">
            <div class="modal-header">
                <h4 class="modal-title text-purple"><span class="fas fa-user-cog mr-3"></span>{{ $modal_title }}</h4>
                <button type="button" wire:click.prevent="cancel()" thotam-blockui class="close" data-dismiss="modal" wire:loading.attr="disabled" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            @if ($setPermissionStatus)
                <div class="modal-body">
                    <div class="container-fluid mx-0 px-0">
                        <form>
                            <div class="row">

                                <div class="col-12 col-md-3">
                                    <div class="form-group">
                                        <label class="col-form-label">Mã nhân viên:</label>
                                        <div>
                                            <span type="text" class="form-control px-2 h-auto">{{ $key }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-5">
                                    <div class="form-group">
                                        <label class="col-form-label">Họ và tên:</label>
                                        <div>
                                            <span type="text" class="form-control px-2 h-auto">{{ $hoten }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <label class="col-form-label">Ngày vào làm:</label>
                                        <div>
                                            <span type="text" class="form-control px-2 h-auto">{{ $ngaythuviec }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if (!!$role_arrays)
                                    <div class="col-12">
                                        <div class="form-group mb-1">
                                            <label class="col-form-label mb-1 text-danger">Role:</label>
                                            <div class="row mx-auto">
                                                @foreach ($role_arrays as $key => $value)
                                                    <div class="col-12">
                                                        <div class="form-group mb-1">
                                                            <label class="col-form-label mb-1 text-indigo">{{ $key }}:</label>
                                                            <div class="row mx-1">

                                                                @foreach ($value as $role)
                                                                    <label class="form-check col-md-4 col-sm-6 col-12 mb-1">
                                                                        <input class="form-check-input ml-0 mt-1" wire:model="roles.{{ $role["id"] }}" type="checkbox" value="{{ $role["name"] }}">
                                                                        <span class="form-check-label ml-4">{{ $role["description"] }}</span>
                                                                    </label>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if (!!$permission_arrays)
                                    <div class="col-12">
                                        <div class="form-group mb-1">
                                            <label class="col-form-label mb-1 text-danger">Permission:</label>
                                            <div class="row mx-auto">
                                                @foreach ($permission_arrays as $key => $value)
                                                    <div class="col-12">
                                                        <div class="form-group mb-1">
                                                            <label class="col-form-label mb-1 text-indigo">{{ $key }}:</label>
                                                            <div class="row mx-1">

                                                                @foreach ($value as $permission)
                                                                    <label class="form-check col-md-4 col-sm-6 col-12 mb-1">
                                                                        <input class="form-check-input ml-0 mt-1" wire:model="permissions.{{ $permission["id"] }}" type="checkbox" value="{{ $permission["name"] }}">
                                                                        <span class="form-check-label ml-4">{{ $permission["description"] }}</span>
                                                                    </label>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
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
                <button wire:click.prevent="save_set_permission_hr()" thotam-blockui class="btn btn-success" wire:loading.attr="disabled">Xác nhận</button>
            </div>

        </div>
    </div>

</div>
