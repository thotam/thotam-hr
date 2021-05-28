<div>
    <div class="col-12">
        <h1 class="text-center font-weight-bolder pt-3 mb-3">
            Thông tin Email
        </h1>

        <div class="row px-3">

            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label text-indigo" for="mail_noibo">Mail nội bộ:</label>
                    <div id="mail_noibo_div">
                        <input type="email" class="form-control px-2" wire:key="mail_noibo" wire:model.lazy="mail.noibo" id="mail_noibo" style="width: 100%" placeholder="Mail nội bộ ..." autocomplete="off">
                    </div>
                    @error('mail')
                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                    @enderror
                    @error('mail.noibo')
                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                    @enderror
                </div>
            </div>

            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label text-indigo" for="mail_canhan">Mail cá nhân:</label>
                    <div id="mail_canhan_div">
                        <input type="email" class="form-control px-2" wire:key="mail_canhan" wire:model.lazy="mail.canhan" id="mail_canhan" style="width: 100%" placeholder="Mail cá nhân ..." autocomplete="off">
                    </div>
                    @error('mail')
                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                    @enderror
                    @error('mail.canhan')
                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                    @enderror
                </div>
            </div>

            @if ($hr->is_mkt_thanhvien || $hr->is_mkt_quanly)
                <div class="col-12">
                    <div class="form-group">
                        <label class="col-form-label text-indigo" for="mail_tt_mkt">Mail TT-MKT:</label>
                        <div id="mail_tt_mkt_div">
                            <input type="email" class="form-control px-2" wire:key="mail_tt_mkt" wire:model.lazy="mail.tt_mkt" id="mail_tt_mkt" style="width: 100%" placeholder="Mail nhận thông tin nội bộ phòng TT-MKT ..." autocomplete="off">
                        </div>
                        @error('mail')
                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                        @enderror
                        @error('mail.tt_mkt')
                            <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                        @enderror
                    </div>
                </div>
            @endif

            <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label text-indigo" for="mail_buddy">Mail Buddy:</label>
                    <div id="mail_buddy_div">
                        <input type="email" class="form-control px-2" wire:key="mail_buddy" wire:model.lazy="mail.buddy" id="mail_buddy" style="width: 100%" placeholder="Mail nhận thông tin Buddy ..." autocomplete="off">
                    </div>
                    @error('mail')
                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                    @enderror
                    @error('mail.buddy')
                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                    @enderror
                </div>
            </div>

        </div>
    </div>

    <div class="text-right mt-4">
        <button wire:click.prevent="save_info()" thotam-blockui wire:loading.attr="disabled" class="btn btn-primary">Lưu</button>
    </div>
</div>
