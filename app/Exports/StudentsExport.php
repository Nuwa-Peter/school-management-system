<?php

namespace App\Exports;

use App\Enums\Role;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return User::query()->where('role', Role::STUDENT);
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'First Name',
            'Last Name',
            'Other Name',
            'LIN',
            'Email',
            'Gender',
        ];
    }

    public function map($student): array
    {
        return [
            $student->unique_id,
            $student->first_name,
            $student->last_name,
            $student->other_name,
            $student->lin,
            $student->email,
            $student->gender,
        ];
    }
}
