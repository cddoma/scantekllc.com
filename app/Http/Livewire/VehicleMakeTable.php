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

class VehicleMakeTable extends DataTableComponent
{
    protected $model = VehicleMake::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPageName('Supported Makes');
        $this->setEmptyMessage('No Makes found');
        $this->setPerPageAccepted([10, 25, 50, 100]);
        $this->setPerPage(10);
        $this->setFilterPillsEnabled();
        $this->setFilterLayoutSlideDown();
        $this->setTableRowUrl(function($row) {
            return route('vehicles.make.models', $row->vpic_id);
        });
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
            $make = VehicleMake::where('id', $id)->first();
            VehicleModelYear::where('vpic_make_id', $make->vpic_id)->delete();
            VehicleModel::where('vpic_make_id', $make->vpic_id)->delete();
            VehicleMakeType::where('vpic_make_id', $make->vpic_id)->delete();
            $make->delete();
        }
    }


    public function builder(): Builder
    {
        if(Auth::user()->super_admin) {
            
        }
        return VehicleMake::query()
            ->leftJoin('vehicle_make_types', 'vehicle_makes.vpic_id', '=', 'vehicle_make_types.vpic_make_id')
            ->groupBy('vehicle_makes.vpic_id')
            ->addSelect(DB::raw('GROUP_CONCAT(vehicle_make_types.name) as types'));
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
            Column::make("make", "name")
                ->sortable()
                ->searchable(),
            Column::make("Models", "id as 21")
                ->format(fn($value, $row, Column $column) => VehicleModel::where('vpic_make_id', $row->vpic_id)->count() ),
            Column::make("Min Year", "id as 23")
                ->format(fn($value, $row, Column $column) => VehicleModelYear::where('vpic_make_id', $row->vpic_id)->orderBy('year')->first()->year ?? '' ),
            Column::make("Max Year", "id as 22")
                ->format(fn($value, $row, Column $column) => VehicleModelYear::where('vpic_make_id', $row->vpic_id)->orderBy('year', 'desc')->first()->year ?? '' ),
        ];
    }

    public function save() {
        $this->skipRender();
        return false;
    }
}
