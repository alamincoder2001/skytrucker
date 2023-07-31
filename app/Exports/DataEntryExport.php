<?php

namespace App\Exports;

use App\Models\DataEntry;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DataEntryExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public $dateFrom;
    public $dateTo;
    public $areaId;
    public $leaderId;
    public $bpId;

    public function dataclause($dateFrom, $dateTo, $areaId, $leaderId, $bpId)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo   = $dateTo;
        $this->areaId   = $areaId;
        $this->leaderId = $leaderId;
        $this->bpId     = $bpId;
        return $this;
    }

    public function map($data_entry): array
    {
        return [
            $data_entry->id,
            $data_entry->name,
            $data_entry->mobile,
            $data_entry->new_sim,
            $data_entry->new_sim_gift,
            $data_entry->app_install,
            $data_entry->app_install_gift,
            $data_entry->toffee,
            $data_entry->toffee_gift,
            $data_entry->sell_package,
            $data_entry->sell_gb,
            $data_entry->recharge_package,
            $data_entry->recharge_amount,
            $data_entry->voice,
            $data_entry->voice_amount,
            $data_entry->area->name,
            $data_entry->location,
            $data_entry->program,
            $data_entry->experience,
            $data_entry->app_experience,
            $data_entry->gaming,
            $data_entry->event,
            $data_entry->service,
            $data_entry->future,
            $data_entry->status,
            $data_entry->ip_address,
            $data_entry->otp,
            $data_entry->user->name,
            $data_entry->updated_by,
            $data_entry->created_at,
            $data_entry->updated_at,
        ];
    }

    public function headings(): array
    {
        return ["id", "Name", "Mobile", "New Sim", "New Sim Gift", "App Install", "App Install Gift", "Toffee", "Toffee Gift", "Sell Package", "Sell gb", "Recharge Package", "Recharge Amount", "Voice", "Voice Amount", "Area Name", "Location", "Program", "Experience", "App Experience", "Gaming", "Event", "Service", "Future", "Status", "ip_address", "otp", "added_by", "update_by", "created_at", "updated_at"];
    }

    public function query()
    {
        if ($this->areaId != 0) {
            return DataEntry::query()->where('area_id', $this->areaId)->whereBetween(DB::raw('DATE(created_at)'), array($this->dateFrom, $this->dateTo));
        } else if ($this->leaderId != 0) {
            return DataEntry::query()->where('added_by', $this->leaderId)->whereBetween(DB::raw('DATE(created_at)'), array($this->dateFrom, $this->dateTo));
        } else if ($this->bpId != 0) {
            return DataEntry::query()->where('added_by', $this->bpId)->whereBetween(DB::raw('DATE(created_at)'), array($this->dateFrom, $this->dateTo));
        } else {
            return DataEntry::query()->whereBetween(DB::raw('DATE(created_at)'), array($this->dateFrom, $this->dateTo));
        }
    }
}
