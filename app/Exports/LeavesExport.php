<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Leave;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;


class LeavesExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Leave::with('leaveType','employee')->orderBy('id','DESC')->withTrashed()->get();
    }

    public function headings(): array
    {
        return [
            ['REKAPITULASI IZIN PEGAWAI'],
            [
                'Nama Pegawai',
                'Jenis Izin',
                'Tanggal Mulai',
                'Tanggal Akhir',
                'Alasan',
                'Status by Leader',
                'Status by HR',
                'tanggal pengajuan',
            ]
        ];
    }

    public function map($leave): array
    {
        return [
            $leave->employee->firstname ?? ''.' '.$leave->employee->lastname ?? '',
            $leave->leaveType->type,
            $leave->from,
            $leave->to,
            $leave->reason,
            $leave->status_by_leader,
            $leave->status,
            $leave->created_at->format('d-m-Y'),
        ];
    }
}
