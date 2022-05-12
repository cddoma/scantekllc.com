<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\RepairOrder as RO;

class ROTable extends DataTableComponent
{
    protected $model = RO::class;

    public function configure(): void
    {
        // $this->setTable('repair_orders');
        $this->setPrimaryKey('id');
        // $this->setPageName('repair_orders');
        // $this->setEmptyMessage('No Repair Orders found');
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setFilterPillsEnabled();
        $this->setFilterLayoutSlideDown();
        $this->setDefaultSort('id', 'desc');
        $this->setTableRowUrl(function($row) {
                return route('ro.show', $row);
            });
        if(boolval(\Auth::user()->super_admin)) {
            $this->setPerPage(100);
        } else {
            $this->setPerPage(10);
        }
    }

    public function builder(): Builder
    {
        $builder = RO::query()->where('team_id', \Auth::user()->current_team_id);
        if(boolval(\Auth::user()->super_admin)) {
            $builder = RO::query();
        }
        $builder
            ->leftJoin('repair_order_products', 'repair_orders.id', '=', 'repair_order_products.repair_order_id')
            ->leftJoin('products', 'repair_order_products.product_id', '=', 'products.id')
            ->groupBy('repair_orders.id')
            ->select(DB::raw('GROUP_CONCAT(products.name SEPARATOR \'<br>\') as services'));
        return $builder;
        //    ->when($this->getAppliedFilterWithValue('Super Admin'), fn($query, $super_admin) => $query->where('super_admin', $super_admin))
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->deselected()
                ->sortable()
                ->collapseOnTablet(),
            Column::make("Status", "status")
                ->sortable()
                ->searchable()
                ->collapseOnMobile()
                ->format(fn($value, $row, Column $column) => strtoupper($value)),
            Column::make("Priority", "priority")
                ->deselected()
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->sortable()
                ->collapseOnTablet(),
            Column::make("Shop", "team.name")
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->format(fn($value, $row, Column $column) => substr($value, 0, 12))
                ->sortable()
                ->searchable(),
            Column::make("RO", "ro")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),
            Column::make("Services")
                ->label(fn($row) => $row->{'services'})
                ->html(),
            Column::make("Vehicle", "vehicle.name")
                ->sortable()
                ->searchable(),
            Column::make("Year", "vehicle.year")
                ->deselected()
                ->sortable()
                ->searchable(),
            Column::make("Make", "vehicle.make")
                ->deselected()
                ->searchable()
                ->collapseOnMobile(),
            Column::make("Model", "vehicle.model")
                ->deselected()
                ->sortable()
                ->searchable(),
            Column::make("Created By", "createdby.name")
                ->sortable()
                ->collapseOnTablet(),
            Column::make("Created By Email", "createdby.email")
                ->searchable()
                ->hideIf(true),
            Column::make("Created At", "created_at")
                ->sortable()
                ->collapseOnTablet(),
            Column::make("Updated At", "updated_at")
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->deSelected()
                ->sortable()
                ->collapseOnTablet(),
        ];
    }
}
