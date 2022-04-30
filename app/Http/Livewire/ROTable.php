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

/*
    public array $bulkActions = [
        'exportSelected' => 'Export CSV',
    ];
    public function exportSelected()
    {
        foreach($this->getSelected() as $item)
        {
            
        }
    }
*/

    public function builder(): Builder
    {
        if(boolval(\Auth::user()->super_admin)) {
            return RO::query();
        }
        return RO::where('team_id', \Auth::user()->current_team_id);
        //    ->when($this->getAppliedFilterWithValue('Super Admin'), fn($query, $super_admin) => $query->where('super_admin', $super_admin))
            ;
    }

/*
    public function getTeams(): array
    {
        $teams = [];
        foreach(Team::all() as $team) {
            $teams[$team->id] = $team->name;
        }
        return $teams;
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Account')
                ->options($this->getTeams())
                ->filter(function(Builder $builder, int $value) {
                    $builder->where('current_team_id', $value);
                }),
            SelectFilter::make('Super Admin')
                ->options([ '' => 'All', 0 => 'No', 1 => 'Yes'])
                ->filter(function(Builder $builder, int $value) {
                    if ($value == '1') {
                        $builder->where('super_admin', 1);
                    } elseif ($value == '0') {
                        $builder->where('super_admin', '<', 1);
                    }
                }),
        ];
    }
*/

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),
            Column::make("Status", "status")
                ->sortable()
                ->searchable(),
            Column::make("Priority", "priority")
                ->sortable()
                ->searchable(),
            // Column::make("Owner", "owner.name")
            //     ->sortable()
            //     ->searchable(),
            Column::make("Created at", "created_at")
                ->sortable()
                ->collapseOnMobile(),
            Column::make("Updated at", "updated_at")
                ->sortable()
                ->collapseOnMobile(),
        ];
    }
}
