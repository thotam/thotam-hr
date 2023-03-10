<?php

namespace Thotam\ThotamHr\DataTables;

use Auth;
use Carbon\Carbon;
use Thotam\ThotamHr\Models\HR;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminHrDataTable extends DataTable
{
	public $hr, $table_id;

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->hr = Auth::user()->hr;
		$this->table_id = "hr-table";
	}
	/**
	 * Build DataTable class.
	 *
	 * @param mixed $query Results from query() method.
	 * @return \Yajra\DataTables\DataTableAbstract
	 */
	public function dataTable($query)
	{
		return datatables()
			->eloquent($query)
			->filter(function ($query) {
				if (!!request('ngaysinh_start_filter')) {
					$time = Carbon::createFromFormat('Y-m-d', request('ngaysinh_start_filter'))->startOfDay();
					$query->where('hrs.ngaysinh', ">=", $time);
				}
				if (!!request('ngaysinh_end_filter')) {
					$time = Carbon::createFromFormat('Y-m-d', request('ngaysinh_end_filter'))->endOfDay();
					$query->where('hrs.ngaysinh', "<=", $time);
				}

				if (!!request('ngaythuviec_start_filter')) {
					$time = Carbon::createFromFormat('Y-m-d', request('ngaythuviec_start_filter'))->startOfDay();
					$query->where('hrs.ngaythuviec', ">=", $time);
				}
				if (!!request('ngaythuviec_end_filter')) {
					$time = Carbon::createFromFormat('Y-m-d', request('ngaythuviec_end_filter'))->endOfDay();
					$query->where('hrs.ngaythuviec', "<=", $time);
				}

				if (request('active_filter') !== NULL && request('active_filter') != -999) {
					if (request('active_filter') == 1) {
						$query->where('hrs.active', true);
					} elseif (request('active_filter') == -1) {
						$query->where('hrs.active', 0);
					} else {
						$query->where('hrs.active', NULL);
					}
				}

				if (!!request('hoten_filter')) {
					$query->where('hrs.hoten', 'like', '%' . request('hoten_filter') . '%');
				}
			}, true)
			->addColumn('action', function ($query) {
				$Action_Icon = "<div class='action-div icon-4 px-0 mx-1 d-flex justify-content-around text-center'>";

				if ($this->hr->can("edit-hr")) {
					$Action_Icon .= "<div class='col action-icon-w-50 action-icon' thotam-livewire-method='edit_hr' thotam-model-id='$query->key'><i class='text-twitter fas fa-user-edit'></i></div>";
				}

				if ($this->hr->can("set-team-hr")) {
					$Action_Icon .= "<div class='col action-icon-w-50 action-icon' thotam-livewire-method='set_team_hr' thotam-model-id='$query->key'><i class='text-success fas fa-users'></i></div>";
				}

				if ($this->hr->can("set-permission-hr")) {
					$Action_Icon .= "<div class='col action-icon-w-50 action-icon' thotam-livewire-method='set_permission_hr' thotam-model-id='$query->key'><i class='text-indigo fas fa-cogs'></i></div>";
				}

				$Action_Icon .= "</div>";

				return $Action_Icon;
			})
			->editColumn('active', function ($query) {
				if ($query->active === NULL) {
					return "Chưa kích hoạt";
				} elseif (!!$query->active) {
					return "Đã kích hoạt";
				} else {
					return "Đã vô hiệu hóa";
				}
			})
			->editColumn('ngaythuviec', function ($query) {
				if (!!$query->ngaythuviec) {
					return $query->ngaythuviec->format("d-m-Y");
				} else {
					return NULL;
				}
			})
			->addColumn('nhoms', function ($query) {
				if (!!$query->thanhvien_of_nhoms->count()) {
					return $query->thanhvien_of_nhoms->pluck('full_name')->implode(', ');
				} else {
					return NULL;
				}
			})
			->editColumn('ngaysinh', function ($query) {
				if (!!$query->ngaysinh) {
					return $query->ngaysinh->format("d-m-Y");
				} else {
					return NULL;
				}
			});
	}

	/**
	 * Get query source of dataTable.
	 *
	 * @param \Thotam\ThotamHr\Models\HR $model
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function query(HR $model)
	{
		$query = $model->newQuery();

		if (!request()->has('order')) {
			$query->orderBy('key', 'asc');
		};

		return $query->with('thanhvien_of_nhoms:id,full_name');
	}

	/**
	 * Optional method if you want to use html builder.
	 *
	 * @return \Yajra\DataTables\Html\Builder
	 */
	public function html()
	{
		return $this->builder()
			->setTableId($this->table_id)
			->columns($this->getColumns())
			->minifiedAjax("", NULL, [
				"ngaysinh_start_filter" => '$("#' . $this->table_id . '-ngaysinh-start-filter").val()',
				"ngaysinh_end_filter" => '$("#' . $this->table_id . '-ngaysinh-end-filter").val()',
				"ngaythuviec_start_filter" => '$("#' . $this->table_id . '-ngaythuviec-start-filter").val()',
				"ngaythuviec_end_filter" => '$("#' . $this->table_id . '-ngaythuviec-end-filter").val()',
				"active_filter" => '$("#' . $this->table_id . '-active-filter").val()',
				"hoten_filter" => '$("#' . $this->table_id . '-hoten-filter").val()',
			])
			->dom("<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'row'<'col-sm-12 table-responsive't>><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>><'d-none'B>")
			->buttons(
				Button::make('excel')->addClass("btn btn-success waves-effect")->text('<span class="fas fa-file-excel mx-2"></span> Export'),
				Button::make('reload')->addClass("btn btn-info waves-effect")->text('<span class="fas fa-filter mx-2"></span> Filter'),
			)
			->parameters([
				"autoWidth" => false,
				"lengthMenu" => [
					[10, 25, 50, -1],
					[10, 25, 50, "Tất cả"]
				],
				"order" => [],
				'initComplete' => 'function(settings, json) {
                            var api = this.api();

                            $(document).on("click", "#filter_submit", function(e) {
                                api.draw(false);
                                e.preventDefault();
                            });

                            window.addEventListener("dt_draw", function(e) {
                                api.draw(false);
                                e.preventDefault();
                            })

                            $("thead#' . $this->table_id . '-thead").insertAfter(api.table().header());

                            api.buttons()
                                .container()
                                .removeClass("btn-group")
                                .appendTo($("#datatable-buttons"));

                            $("#datatable-buttons").removeClass("d-none")
                        }',
			]);
	}

	/**
	 * Get columns.
	 *
	 * @return array
	 */
	protected function getColumns()
	{
		return [
			Column::computed('action')
				->exportable(false)
				->printable(false)
				->width(60)
				->addClass('text-center')
				->title("")
				->footer(""),
			Column::make('key')
				->title("Mã nhân sự")
				->width(5)
				->searchable(true)
				->orderable(false)
				->footer("Mã nhân sự"),
			Column::make("hoten")
				->title("Họ tên")
				->width(200)
				->searchable(false)
				->orderable(false)
				->footer("Họ tên")
				->filterView(view('thotam-laravel-datatables-filter::input', ['c_placeholder' => "Họ tên"])->with("colum_filter_id")),
			Column::make("nhoms")
				->title("Nhóm")
				->width(200)
				->searchable(false)
				->orderable(false)
				->footer("Nhóm"),
			Column::make("ngaysinh")
				->title("Ngày sinh")
				->width(25)
				->searchable(false)
				->orderable(false)
				->footer("Ngày sinh")
				->filterView(view('thotam-laravel-datatables-filter::date-range')->with("colum_filter_id")),
			Column::make("ngaythuviec")
				->title("Ngày vào làm")
				->width(25)
				->searchable(false)
				->orderable(false)
				->footer("Ngày vào làm")
				->filterView(view('thotam-laravel-datatables-filter::date-range')->with("colum_filter_id")),
			Column::computed('active')
				->title("Trạng thái")
				->orderable(false)
				->footer("Trạng thái")
				->filterView(view('thotam-laravel-datatables-filter::select-single', ['selects' => $this->getTrangThaisProperty(), 'c_placeholder' => "Trạng thái"])->with("colum_filter_id"))
		];
	}

	/**
	 * Get filename for export.
	 *
	 * @return string
	 */
	protected function filename(): string
	{
		return 'HR_' . date('YmdHis');
	}

	public function getTrangThaisProperty()
	{
		return [
			"1" => "Đã kích hoạt",
			"0" => "Chưa kích hoạt",
			"-1" => "Đã vô hiệu hóa",
		];
	}
}
