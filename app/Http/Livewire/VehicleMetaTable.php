<?php

namespace App\Http\Livewire;

use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Vehicle;
use App\Models\VehicleMeta;

class VehicleMetaTable extends DataTableComponent
{
    protected $model = VehicleMeta::class;
    public $vehicleId;
    public $paginationEnabled = false;
    public $showPagination = false;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
        $this->setPerPageAccepted([10, 25, 50, 100, 1000]);
        $this->setPerPage(100);
        $this->setEmptyMessage('Enter the VIN to pull meta data.');
        $this->setPaginationVisibilityDisabled();
        $this->setPerPageVisibilityDisabled();
        $this->setColumnSelectDisabled();
        $this->perPageAll = true;
    }

    public function mount($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }

    public function builder(): Builder
    {
        return VehicleMeta::query()
            ->where('vehicle_id', $this->vehicleId)
            ->select('key', 'value');
    }

    public function columns(): array
    {
        return [
            Column::make("Key", "key")
                ->searchable()
                ->sortable(),
            Column::make("Value", "value")
                ->searchable()
                ->sortable(),
        ];
    }

    public function save() {
        $this->skipRender();
        return false;
    }
}
