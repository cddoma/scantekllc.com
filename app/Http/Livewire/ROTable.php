<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\RepairOrder as RO;
use \Carbon\Carbon;

class ROTable extends DataTableComponent
{
    protected $model = RO::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('Repair Orders');
        $this->setEmptyMessage('No Repair Orders found');
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setFilterPillsEnabled();
        $this->setFilterLayoutSlideDown();
        $this->setDefaultSort('updated_at', 'desc');
        $this->setTableRowUrl(function($row) {
                return route('ro.show', (!empty(trim($row->ro)) ? $row->ro : $row->id));
            });
        if(boolval(\Auth::user()->super_admin)) {
            $this->setPerPage(100);
        } else {
            $this->setPerPage(10);
        }
    }

    public function builder(): Builder
    {
        $builder = RO::query()->where('repair_orders.team_id', \Auth::user()->current_team_id);
        if(boolval(\Auth::user()->super_admin)) {
            $builder = RO::query();
        }
        $builder
            ->leftJoin('repair_order_products', 'repair_orders.id', '=', 'repair_order_products.repair_order_id')
            ->leftJoin('products', 'repair_order_products.product_id', '=', 'products.id')
            ->groupBy('repair_orders.id')
            ->addSelect(DB::raw('GROUP_CONCAT(products.name SEPARATOR \'<br>\') as services'));
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
            Column::make("SHOP", "team.name")
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->sortable()
                ->searchable(),
            Column::make("RO", "ro")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),
            Column::make("SERVICES")
                ->label(fn($row) => $row->{'services'})
                ->html()
                ->collapseOnTablet(),
            Column::make("Elapsed Time", "created_at as created_at2")
                ->collapseOnMobile()
                ->format(fn($value, $row, Column $column) => (Carbon::parse($row->created_at))->diffForHumans()),
            Column::make("VEHICLE", "vehicle.name")
                ->sortable()
                ->searchable(),
            Column::make("Service Advisor", "service_advisor")
                ->deselected()
                ->sortable()
                ->searchable(),
            Column::make("PRIORITY", "priority")
                ->deselected()
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->sortable()
                ->collapseOnTablet(),
            Column::make("YEAR", "vehicle.year")
                ->deselected()
                ->sortable()
                ->searchable(),
            Column::make("MAKE", "vehicle.make")
                ->deselected()
                ->searchable()
                ->collapseOnMobile(),
            Column::make("MODEL", "vehicle.model")
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
                ->format(fn($value, $row, Column $column) => '<span class="text-center" style="display:inline-grid;line-height:1;margin-top:-0.25em;">'.(Carbon::parse($value))->format("m-d-Y<br>g:i A").'</span>')->html()
                ->collapseOnTablet(),
            Column::make("Updated At", "updated_at")
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->deselected()
                ->sortable()
                ->format(fn($value, $row, Column $column) => (Carbon::parse($value))->diffForHumans())
                ->collapseOnTablet(),
            // Column::make("STATUS", "status")
            //     ->sortable()
            //     ->searchable()
            //     ->collapseOnMobile()
            //     ->format(fn($value, $row, Column $column) => strtoupper($value)),
        ];
    }
}
