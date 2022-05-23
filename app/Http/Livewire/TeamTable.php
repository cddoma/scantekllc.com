<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Team;
use \Carbon\Carbon;

class TeamTable extends DataTableComponent
{
    protected $model = Team::class;

    public function configure(): void
    {
        $this->setPageName('Shops');
        $this->setEmptyMessage('No Shops found');
        $this->setPaginationEnabled();
        $this->setPerPageVisibilityEnabled();
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setPerPage(100);
        $this->setFilterPillsEnabled();
        // $this->setFilterLayoutPopover();
        $this->setFilterLayoutSlideDown();
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function($row) {
                return route('shops.show', $row);
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
        return Team::query()
            // ->leftJoin('repair_orders', 'teams.id', '=', 'repair_orders.team_id')
            // ->groupBy('teams.id')
            // ->selectRaw('COUNT(repair_orders.id) AS ros')
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
                ->collapseOnMobile()
                ->deselected(),
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Manager")
                ->collapseOnMobile()
                ->sortable()
                ->searchable(),
            Column::make("Phone")
                ->collapseOnMobile()
                ->sortable()
                ->searchable()
                ->format(fn($value, $row, Column $column) => '<a href="tel:'.$value.'">'.$value.'</a>')
                ->html(),
            Column::make("Email")
                ->collapseOnMobile()
                ->sortable()
                ->searchable()
                ->format(fn($value, $row, Column $column) => '<a href="mailto:'.$value.'">'.$value.'</a>')
                ->html(),
            Column::make("Address")
                ->collapseOnMobile()
                ->deselected()
                ->sortable()
                ->searchable(),
            Column::make("RO's", 'user_id as 2')
                //->sortable(),
                ->format(fn($value, $row, Column $column) => $row->roCount()),
            Column::make("USERs", 'user_id')
                //->sortable()
                ->format(fn($value, $row, Column $column) => $row->userCount()),
            Column::make("Created at", "created_at")
                ->sortable()
                ->collapseOnMobile()
                ->format(fn($value, $row, Column $column) => (Carbon::parse($value))->diffForHumans())
                ->deselected(),
            Column::make("Updated at", "updated_at")
                ->sortable()
                ->collapseOnMobile()
                ->format(fn($value, $row, Column $column) => (Carbon::parse($value))->diffForHumans())
                ->deselected(),
        ];
    }
}
