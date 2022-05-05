<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VehicleMake;
use App\Models\VehicleMakeType;
use App\Models\VehicleModel;
use App\Models\VehicleModelYear;

class VehicleModelTable extends DataTableComponent
{
    protected $model = VehicleModel::class;
    public $make_id;

    public function configure(): void
    {
        $this->setPrimaryKey('vehicle_models.id');
        $this->setPageName('Supported Models');
        $this->setEmptyMessage('No Models found');
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setPerPage(10);
        $this->setFilterPillsEnabled();
        $this->setFilterLayoutSlideDown();
        // $this->setDefaultSort('sessions.last_activity', 'desc');
    }

    public function mount($make_id = '') {
        $this->make_id = $make_id;
    }

    public array $bulkActions = [
        'deleteSelected' => 'Delete',
    ];
    public function deleteSelected()
    {
        foreach($this->getSelected() as $id)
        {
            $model = VehicleModel::where('id', $id)->first();
            VehicleModelYear::where('vpic_make_id', $model->vpic_id)->delete();
            $model->delete();
        }
    }


    public function builder(): Builder
    {
        if(Auth::user()->super_admin) {
            
        }
        return VehicleModel::query()->where('vehicle_models.vpic_make_id', $this->make_id)
            ->join('vehicle_makes', 'vehicle_models.vpic_make_id', '=', 'vehicle_makes.vpic_id')
            ->join('vehicle_make_types', 'vehicle_makes.vpic_id', '=', 'vehicle_make_types.vpic_make_id');
    }

    public function getTypes(): array
    {
        return [
            "",
            "2" => "Passenger Car",
            "7" => "Multipurpose Passenger Vehicle (MPV)",
            "1" => "Motorcycle",
            "3" => "Truck ",
            "10" => "Incomplete Vehicle",
            "5" => "Bus",
            "6" => "Trailer",
            "9" => "Low Speed Vehicle (LSV)",
            "13" => "Off Road Vehicle",
        ];

    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Type')
                ->options($this->getTypes())
                ->filter(function(Builder $builder, $value) {
                    $builder->where('vehicle_make_types.vpic_id', $value);
                }),
            SelectFilter::make('Make')
                ->options([$this->make_id => VehicleMake::where('vpic_id', $this->make_id)->select('name')->first()->name ?? ''])
                ->filter(function(Builder $builder, int $value) {
                    $builder->where('vpic_make_id', $this->make_id);
                }),
        ];
    }

    public function columns(): array
    {
        return [
            Column::make("ID", "id")
                ->deSelected()
                ->sortable(),
            Column::make("vpic ID", "vpic_id")
                ->deSelected()
                ->sortable(),
            Column::make("Make", "make.name")
                ->sortable()
                ->searchable(),
            Column::make("Model", "name")
                ->sortable()
                ->searchable(),
            Column::make("Min Year", "id as 23")
                ->deSelected()
                ->format(fn($value, $row, Column $column) => VehicleModelYear::where('vpic_model_id', $row->vpic_id)->orderBy('year')->first()->year ?? '' ),
            Column::make("Max Year", "id as 22")
                ->deSelected()
                ->format(fn($value, $row, Column $column) => VehicleModelYear::where('vpic_model_id', $row->vpic_id)->orderBy('year', 'desc')->first()->year ?? '' ),
        ];
    }

    public function save() {
        $this->skipRender();
        return false;
    }
}
