<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ButtonGroupColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\RepairOrder as RO;
use App\Models\RepairOrderProduct as ROProduct;
use Illuminate\Support\Facades\DB;

class ROProductsTable extends DataTableComponent
{
    protected $model = ROProduct::class;
    public $ro_id;

    protected $listeners = ['refreshROProducts' => '$refresh'];

    public function mount($ro_id)
    {
        $this->ro_id = $ro_id;
    }

    public function configure(): void
    {
        $this->setPageName('Repairs');
        $this->setEmptyMessage('No Services have been added to this Repair Order yet');
        // $this->setPerPageAccepted([10, 25, 50, 100]);
        // $this->setFilterPillsEnabled();
        // $this->setFilterLayoutSlideDown();
        // $this->setDefaultSort('id', 'asc');
        $this->setPrimaryKey('id');
        $this->setSearchDisabled();
        $this->setSearchVisibilityDisabled();
        // $this->setPaginationDisabled();
        $this->setPaginationVisibilityDisabled();
        $this->setPerPageVisibilityDisabled();
        $this->setColumnSelectDisabled();
        $this->setFiltersVisibilityDisabled();
        $this->setSortingDisabled();
    }

    public function builder(): Builder
    {
        return ROProduct::where('repair_order_id', $this->ro_id)
            ->leftJoin('products', 'repair_order_products.product_id', '=', 'products.id')
            ->groupBy('repair_order_products.id')
            ->select(DB::raw('coalesce(repair_order_products.name, products.name) as coalname'), DB::raw('coalesce(repair_order_products.price, products.default_price) as coalprice'));
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->hideIf(1),
                // ->hideIf(!boolval(\Auth::user()->super_admin)),
            Column::make("Name", "name")
                ->format(fn($value, $row, Column $column) => $row->{'coalname'}),
            Column::make("Price", "price")
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->format(fn($value, $row, Column $column) => $row->{'coalprice'}),
            ButtonGroupColumn::make('Actions')
                ->attributes(function($row) {
                    return [
                        'class' => 'space-x-2',
                    ];
                })
                ->buttons([
                    
                    LinkColumn::make('Delete')
                        ->title(fn($row) => 'Delete')
                        ->location(fn($row) => '#')
                        ->attributes(function($row) {
                            return [
                                'class' => 'inline-flex items-center px-4 py-2 bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-600 focus:outline-none focus:border-red-600 focus:ring focus:ring-gray-300 disabled:opacity-25 transition',
                                'wire:click' => 'delete('.$row->id.')',
                            ];
                        }),
                ]),
        ];
    }

    /*
    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];

    public function deleteSelected() {

        foreach($this->getSelected() as $id)
        {
            ROProduct::findOrFail($id)->delete();
        }
    }
    */

    public function delete($id) {
        ROProduct::findOrFail($id)->delete();
    }
}
