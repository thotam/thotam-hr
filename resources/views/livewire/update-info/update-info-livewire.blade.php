<div>
	<div class="col-12">
		<h1 class="text-center font-weight-bolder pt-3 mb-3">
			Thông tin Email
		</h1>

		<div class="row px-3">

			<div class="col-12">
				<div class="form-group">
					<label class="col-form-label text-indigo" for="mail_noibo">Mail nội bộ (Mail @upharma.com.vn, @upharma.vn, @upharma.info - nếu có):</label>
					<div id="mail_noibo_div">
						<input type="email" class="form-control px-2" wire:key="mail_noibo" wire:model.lazy="mail.noibo" id="mail_noibo" style="width: 100%" placeholder="Mail nội bộ ..." autocomplete="off">
					</div>
					@error('mail')
						<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
					@enderror
					@error('mail.noibo')
						<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
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
						<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
					@enderror
					@error('mail.canhan')
						<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
					@enderror
				</div>
			</div>

			@if ($hr->is_mkt_thanhvien || $hr->is_mkt_quanly)
				<div class="col-12">
					<div class="form-group">
						<label class="col-form-label text-indigo" for="mail_tt_mkt">Mail nhận thông tin từ phòng Truyền thông - Marketing:</label>
						<div id="mail_tt_mkt_div">
							<input type="email" class="form-control px-2" wire:key="mail_tt_mkt" wire:model.lazy="mail.tt_mkt" id="mail_tt_mkt" style="width: 100%" placeholder="Mail nhận thông tin nội bộ phòng TT-MKT ..." autocomplete="off">
						</div>
						<label class="pl-1 small invalid-feedback d-inline-block text-indigo"><i class="fas mr-1 fa-exclamation-circle"></i>Email của bạn để nhận thông tin từ phòng Truyền thông - Marketing, nếu để trống sẽ tự động lấy theo Mail cá nhân</label>
						@error('mail')
							<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
						@enderror
						@error('mail.tt_mkt')
							<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
						@enderror
					</div>
				</div>
			@endif

			{{-- <div class="col-12">
                <div class="form-group">
                    <label class="col-form-label text-indigo" for="mail_buddy">Mail nhận thông tin Buddy:</label>
                    <div id="mail_buddy_div">
                        <input type="email" class="form-control px-2" wire:key="mail_buddy" wire:model.lazy="mail.buddy" id="mail_buddy" style="width: 100%" placeholder="Mail nhận thông tin Buddy ..." autocomplete="off">
                    </div>
                    <label class="pl-1 small invalid-feedback d-inline-block text-indigo" ><i class="fas mr-1 fa-exclamation-circle"></i>Email của bạn để nhận thông tin liên quan tới Buddy, nếu để trống sẽ tự động lấy theo Mail cá nhân</label>
                    @error('mail')
                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                    @enderror
                    @error('mail.buddy')
                        <label class="pl-1 small invalid-feedback d-inline-block" ><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
                    @enderror
                </div>
            </div> --}}

		</div>
	</div>

	@if ($hr->is_mkt_quanly || $hr->is_mkt_thanhvien)
		<div class="col-12">
			<h1 class="text-center font-weight-bolder pt-3 mb-3">
				Thông tin Tài khoản sổ tay
			</h1>

			<div class="row px-3">

				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="col-form-label text-indigo" for="icpc1hn_taikhoan">Số điện thoại:</label>
						<div id="icpc1hn_taikhoan_div">
							<input type="text" class="form-control px-2" wire:key="icpc1hn_taikhoan" wire:model.lazy="icpc1hn_taikhoan" id="icpc1hn_taikhoan" style="width: 100%" placeholder="Số điện thoại ..." autocomplete="off">
						</div>
						@error('icpc1hn_taikhoan')
							<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
						@enderror
					</div>
				</div>

				<div class="col-12 col-md-6">
					<div class="form-group">
						<label class="col-form-label text-indigo" for="icpc1hn_matkhau">Mật khẩu:</label>
						<div id="icpc1hn_matkhau_div">
							<input type="text" class="form-control px-2" wire:key="icpc1hn_matkhau" wire:model.lazy="icpc1hn_matkhau" id="icpc1hn_matkhau" style="width: 100%" placeholder="Mật khẩu ..." autocomplete="off">
						</div>
						@error('icpc1hn_matkhau')
							<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
						@enderror
					</div>
				</div>

			</div>
		</div>
	@endif

	@if ($hr->is_kd_quanly || $hr->is_kd_thanhvien)

		<div class="col-12">
			<h1 class="text-center font-weight-bolder pt-3 mb-3">
				Thông tin Nhóm sản phẩm
			</h1>

			<div class="row px-3">

				<div class="col-12 bg-light">
					<div class="form-group mb-1 mt-1 text-google">
						<b>Nhóm sản phẩm:</b>
					</div>

					<div class="row px-3">

						@foreach ($nhom_sp_arrays as $nhom_sp_array)
							@if (!!$nhom_sp_array['nhom_san_phams'])
								<div class="col-12">
									<div class="form-group mb-1">
										<label class="col-form-label mb-1 text-indigo">{{ $nhom_sp_array['name'] }}:</label>
										<div class="row">

											@foreach ($nhom_sp_array['nhom_san_phams'] as $nhom_san_pham)
												<label class="form-check col-md-6 col-12 mb-1">
													<input class="form-check-input ml-0 mt-1" wire:model="nhom_san_phams.{{ $nhom_san_pham['id'] }}" type="checkbox" value="{{ $nhom_san_pham['id'] }}">
													<span class="form-check-label ml-4">{{ $nhom_san_pham['name'] }}</span>
												</label>
											@endforeach

										</div>
									</div>
								</div>
							@endif
						@endforeach

					</div>

					@error('nhom_san_phams')
						<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
					@enderror
					@error('nhom_san_phams.*')
						<label class="pl-1 small invalid-feedback d-inline-block"><i class="fas mr-1 fa-exclamation-circle"></i>{{ $message }}</label>
					@enderror

				</div>

			</div>
		</div>


	@endif

	<div class="text-right mt-4">
		<button wire:click.prevent="save_info({{ isset($reload) }})" thotam-blockui wire:loading.attr="disabled" class="btn btn-primary">Lưu</button>
	</div>
</div>
