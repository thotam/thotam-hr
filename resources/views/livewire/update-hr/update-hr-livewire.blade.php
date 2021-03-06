<div>
    @if (!!$update_hr)
        <p class="text-big text-indigo">Vì thông tin bạn cung cấp chưa chính xác nên hệ thống không thể tự động cấp quyền cho bạn, vui lòng cập nhật thông tin hoặc chở xử lý bằng tay</b></p>
    @else
        <p class="text-big text-indigo">Vui lòng cung cấp thông tin để được hỗ trợ.</p>
    @endif

    <div class="coming-soon-subscribe pt-3 mt-2 mb-auto">
        <div class="form-row">
            <div class="col-12 mb-3">
                <input type="text" wire:model.lazy="hoten" class="form-control px-2 form-control-lg form-control font-secondary" placeholder="Họ và tên...">
                @error('hoten')
                    <label class="pl-1 mb-0 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <input type="text" wire:model.lazy="hr_key" class="form-control px-2 form-control-lg form-control font-secondary" placeholder="Mã nhân sự...">
                @error('hr_key')
                    <label class="pl-1 mb-0 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                @enderror
            </div>

            <div class="col-12 mb-3">
                <input type="text" wire:model.lazy="nhom" class="form-control px-2 form-control-lg form-control font-secondary" placeholder="Nhóm và quản lý của bạn...">
                @error('nhom')
                    <label class="pl-1 mb-0 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                @enderror
            </div>

            <div class="col-12 mb-3" id="ngaysinh_div">
                <div wire:ignore>
                    <input type="text" wire:model="ngaysinh" thotam-container="ngaysinh_div" thotam-orientation="top" class="form-control px-2 form-control-lg form-control font-secondary" readonly placeholder="Ngày sinh..." x-init="thotam_datepicker($el, @this, 0, 2)">
                </div>
                @error('ngaysinh')
                    <label class="pl-1 mb-0 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                @enderror
            </div>

            <div class="col-12 mb-3" id="ngaythuviec_div">
                <div wire:ignore>
                    <input type="text" wire:model="ngaythuviec" thotam-container="ngaythuviec_div" thotam-orientation="top" class="form-control px-2 form-control-lg form-control font-secondary" readonly placeholder="Ngày vào làm..." x-init="thotam_datepicker($el, @this)">
                </div>
                @error('ngaythuviec')
                    <label class="pl-1 mb-0 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                @enderror
            </div>

            <div class="col-sm-3 mt-2 mt-sm-0">
                <button type="submit" class="btn btn-primary btn-lg btn-block font-secondary text-expanded px-0"  wire:click.prevent="save()" wire:loading.attr="disabled">
                    <small class="font-weight-bold">{{ !!$update_hr ? "Cập nhật" : "Xác nhận" }}</small>
                </button>
            </div>
        </div>
    </div>

</div>
