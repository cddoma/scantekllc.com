<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Team;

class UserTable extends DataTableComponent
{
    protected $model = User::class;

    public function configure(): void
    {
        $this->setPageName('users');
        $this->setEmptyMessage('No Users found');
        // $this->setPaginationStatus(true);
        // $this->setPerPage(10);
        $this->setFilterPillsEnabled();
        // $this->setFilterLayoutPopover();
        $this->setFilterLayoutSlideDown();
        $this->setPrimaryKey('id')
            ->setTableRowUrl(function($row) {
                return route('profile.show', $row);
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

    public function getYears($start = 2000): array
    {
        $years = [];
        $vpic = new BiegalskiLLC\NHTSAVehicleAPI\VehicleApi();
        foreach($vpic->listYears($start) as $year) {
            $years[$year] = $year;
        }
        return $years;
    }

*/

    public function builder(): Builder
    {
        return User::query()
            ->when($this->getAppliedFilterWithValue('Account'), fn($query, $team) => $query->where('current_team_id', '=', $team))
            ->when($this->getAppliedFilterWithValue('Super Admin'), fn($query, $super_admin) => $query->where('super_admin', $super_admin));
    }

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

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),
            Column::make("Super Admin", "super_admin")
                ->sortable()
                ->collapseOnMobile(),
            Column::make("Name", "name")
                ->sortable()
                ->searchable(),
            Column::make("Email", "email")
                ->sortable()
                ->searchable()
                ->collapseOnMobile(),
            Column::make("Account", "currentTeam.name")
                ->sortable()
                ->searchable(),
            Column::make("Created at", "created_at")
                ->sortable()
                ->collapseOnMobile(),
            Column::make("Updated at", "updated_at")
                ->sortable()
                ->collapseOnMobile(),
        ];
    }
}
