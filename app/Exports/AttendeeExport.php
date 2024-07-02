<?php

namespace App\Exports;

use App\Models\EmployeeAttendance;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendeeExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return EmployeeAttendance::with('employee','appeal')->orderBy('id','DESC')->get();
    }

    public function headings(): array
    {
        return [
            ['REKAPITULASI PRESENSI PEGAWAI'],
            [
                'nama',
                'tipe',
                'jam',
                'tanggal',
                'WFH/WFC',
                'Alasan WFH/WFC',
                'status perizinan HR',
                'status berdasarkan waktu'
            ]
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->employee->firstname.' '.$attendance->employee->lastname,
            $attendance->type,
            $attendance->time,
            $attendance->created_at->format('d-m-Y'),
            $attendance->distance ? 'Ya' : 'Tidak',
            $attendance->permission_reason,
            $attendance->status_by_hr ?? '-',
            $attendance->status,
        ];
    }
}
