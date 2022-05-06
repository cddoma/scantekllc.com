<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\RepairOrder as RO;

class ROTable extends DataTableComponent
{
    protected $model = RO::class;

    public function configure(): void
    {
        $this->setPageName('Repair Orders');
        $this->setEmptyMessage('No Repair Orders found');
        // $this->setPaginationStatus(true);
        // $this->setPerPage(10);
        $this->setFilterPillsEnabled();
        // $this->setFilterLayoutPopover();
        $this->setFilterLayoutSlideDown();
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function($row) {
                return route('ro.show', $row);
            });
    }

    public function builder(): Builder
    {
        if(boolval(\Auth::user()->super_admin)) {
            return RO::query();
        }
        return RO::where('team_id', \Auth::user()->current_team_id);
        //    ->when($this->getAppliedFilterWithValue('Super Admin'), fn($query, $super_admin) => $query->where('super_admin', $super_admin))
            ;
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->deSelected()
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),
            Column::make("Status", "status")
                ->deSelected()
                ->sortable()
                ->searchable(),
            Column::make("Priority", "priority")
                ->deSelected()
                ->sortable()
                ->searchable(),
            // Column::make("Owner", "owner.name")
            //     ->sortable()
            //     ->searchable(),
            Column::make("Shop", "team.name")
                ->sortable()
                ->collapseOnMobile(),
            Column::make("Vehicle", "vehicle.name")
                ->sortable()
                ->collapseOnMobile(),
            Column::make("Created By", "createdby.name")
                ->deSelected()
                ->sortable()
                ->collapseOnMobile(),
            Column::make("Created At", "created_at")
                ->deSelected()
                ->sortable()
                ->collapseOnMobile(),
            Column::make("Updated At", "updated_at")
                ->deSelected()
                ->sortable()
                ->collapseOnMobile(),
        ];
    }
}
