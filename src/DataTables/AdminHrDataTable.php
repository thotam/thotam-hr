<?php

namespace Thotam\ThotamHr\DataTables;

use Auth;
use Thotam\ThotamHr\Models\HR;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AdminHrDataTable extends DataTable
{
    public $hr;

    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->hr = Auth::user()->hr;
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
            ->addColumn('action', function ($query) {
                $Action_Icon="<div class='action-div icon-4 px-0 mx-1 d-flex justify-content-around text-center'>";

                if ($this->hr->can("edit-user")) {
                    $Action_Icon.="<div class='col action-icon-w-50 action-icon' thotam-livewire-method='edit_user' thotam-model-id='$query->id'><i class='text-twitter fas fa-user-edit'></i></div>";
                }

                if ($this->hr->can("link-user")) {
                    $Action_Icon.="<div class='col action-icon-w-50 action-icon' thotam-livewire-method='link_user' thotam-model-id='$query->id'><i class='text-success fas fa-link'></i></div>";
                }

                if ($this->hr->can("reset-password-user")) {
                    $Action_Icon.="<div class='col action-icon-w-50 action-icon' thotam-livewire-method='reset_password' thotam-model-id='$query->id'><i class='text-linux fas fa-user-lock'></i></div>";
                }

                $Action_Icon.="</div>";

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

        return $query;
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->setTableId('hr-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom("<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'row'<'col-sm-12 table-responsive't>><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>")
                    ->parameters([
                        "autoWidth" => false,
                        "lengthMenu" => [
                            [10, 25, 50, -1],
                            [10, 25, 50, "Tất cả"]
                        ],
                        "order" => [],
                        'initComplete' => 'function(settings, json) {
                            var api = this.api();
                            window.addEventListener("dt_draw", function(e) {
                                api.draw(false);
                                e.preventDefault();
                            })
                            api.buttons()
                                .container()
                                .appendTo($("#datatable-buttons"));
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
                  ->orderable(true)
                  ->footer("Mã nhân sự"),
            Column::make("hoten")
                  ->title("Họ tên")
                  ->width(200)
                  ->searchable(true)
                  ->orderable(true)
                  ->footer("Họ tên"),
            Column::make("ngaysinh")
                  ->title("Ngày sinh")
                  ->width(25)
                  ->searchable(false)
                  ->orderable(true)
                  ->footer("Ngày sinh"),
            Column::make("ngaythuviec")
                  ->title("Ngày vào làm")
                  ->width(25)
                  ->searchable(false)
                  ->orderable(true)
                  ->footer("Ngày vào làm"),
            Column::computed('active')
                  ->title("Trạng thái")
                  ->orderable(true)
                  ->footer("Trạng thái")
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'HR_' . date('YmdHis');
    }
}
