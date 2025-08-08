<?php

namespace App\Imports;

use App\Enums\Role;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'other_name' => $row['other_name'],
            'lin' => $row['lin'],
            'email' => $row['email'],
            'gender' => $row['gender'],
            'password' => \Illuminate\Support\Facades\Hash::make('password'), // Default password
            'role' => Role::STUDENT,
        ]);
    }
}
