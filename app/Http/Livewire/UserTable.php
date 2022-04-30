<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Columns\LinkColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use App\Models\User;
use App\Models\Team;
use Carbon\Carbon;

class UserTable extends DataTableComponent
{
    protected $model = User::class;
    protected Agent $agent;

    public function configure(): void
    {
        $this->setPageName('Users');
        $this->setEmptyMessage('No Users found');
        $this->setPaginationEnabled();
        $this->setPerPageVisibilityEnabled();
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setPerPage(100);
        $this->setFilterPillsEnabled();
        $this->setFilterLayoutPopover();
        //$this->setFilterLayoutSlideDown();
        $this->setPrimaryKey('id');
        $this->setTableRowUrl(function($row) {
            return route('users.show', $row);
        });
        $this->setDefaultSort('sessions.last_activity', 'desc');
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
        // $latest_sessions = DB::table('sessions')
        //     ->select('id', DB::raw('MAX(last_activity)'))
        //     ->groupBy('user_id');
        // $sessions = DB::table('sessions')
        //     ->select('user_id', 'user_agent', DB::raw('DATE_SUB(FROM_UNIXTIME(last_activity), INTERVAL 4 HOUR) as last_active') )
        //     ->joinSub($latest_sessions, 'latest_sessions', function ($join) {
        //         $join->on('latest_sessions.id', '=', 'sessions.id');
        //     });
        return User::query()
            // ->leftJoinSub($sessions, 'sessions', function ($join) {
            //     $join->on('users.id', '=', 'sessions.user_id');
            // })
            ->groupBy('id')
            ->addSelect('profile_photo_path')
            ->when($this->getAppliedFilterWithValue('Account'), fn($query, $team) => $query->where('current_team_id', '=', $team))
            ->when($this->getAppliedFilterWithValue('Super Admin'), fn($query, $super_admin) => $query->where('super_admin', $super_admin))
            ;
            // ->select('users.*', 'sessions.user_agent as user_agent', 'sessions.last_active as last_active');
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
                ->collapseOnMobile()
                ->deselected(),
            Column::make("Shop", "currentTeam.name")
                ->sortable()
                ->searchable(),
            ImageColumn::make('Photo', 'profile_photo_path')
                ->location(
                    fn($row) => !empty($row->profile_photo_path) ? env('APP_URL_CDN').'/storage/'.$row->profile_photo_path : $row->profile_photo_url
                )
                ->attributes(fn($row) => [
                    'class' => 'rounded-full h-10 w-10 inline',
                    // 'style' => 'margin:-.25em; padding:0;',
                    'alt' => $row->name,
                    'title' => '['.$row->{'currentTeam.name'} . '] ' . $row->name,
                ])
                ->collapseOnMobile()
                ->deselected(),
            Column::make("Name", "name")
                ->sortable()
                ->searchable()
                ->format(fn($value, $row, Column $column) => (boolval($row->super_admin) ? '[ADMIN] ' : '') . $value),
            Column::make("Email", "email")
                ->sortable()
                ->searchable()
                ->collapseOnMobile()
                ->deselected(),
            Column::make("Created at", "created_at")
                ->sortable()
                ->collapseOnMobile()
                ->deselected(),
            Column::make("Updated at", "updated_at")
                ->sortable()
                ->collapseOnMobile()
                ->deselected(),
            Column::make('Last Active', 'session.last_activity')
                ->collapseOnMobile()
                ->sortable()
                ->format(fn($value, $row, Column $column) => 
                    (!empty($value) ? 
                        Carbon::createFromTimestamp($value)->subHours(4)->toDateTimeString() . '  ' .
                        ( $this->getAgent($row->user_agent)->isDesktop($row->user_agent) ? 
                        '<svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor" class="w-8 h-8 text-gray-500 inline mr-1">
                                    <path d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>'
                                : '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" class="w-8 h-8 text-gray-500 inline mr-1">
                                        <path d="M0 0h24v24H0z" stroke="none"></path><rect x="7" y="4" width="10" height="16" rx="1"></rect><path d="M11 5h2M12 17v.01"></path>
                                    </svg>'
                        )
                            . ($this->getAgent($row->user_agent)->platform() ?? '') 
                            . ' - ' 
                            . ($this->getAgent($row->user_agent)->browser() ?? '')
                        : '')
                )
                ->html(),
            Column::make('Impersonate', 'session.user_id')
                ->hideIf(!boolval(\Auth::user()->super_admin))
                ->format(fn($value, $row, Column $column) =>  '
                <a href="'.route('impersonate', $row->id).'" class="inline" title="Login as '.$row->name.'">
                    <button type="button" class="inline-flex items-center px-4 py-2 bg-gray-900 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-100 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition"><img class="h-5 w-5" src="/icon/mask.png" /></button>
                </a>')
                ->html()
        ];
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return \Jenssegers\Agent\Agent
     */
    protected function getAgent($ua)
    {
        if(empty($this->agent)) {
            $this->agent = new Agent;
        }
        $this->agent->setUserAgent($ua);
        return $this->agent;
    }
}
