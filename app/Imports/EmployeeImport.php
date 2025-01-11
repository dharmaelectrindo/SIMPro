<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Check
        $employee = Employee::where('npk', $row['npk'])->first();

        $data = [
            'id' => $row['id'] ?? "",
            'npk' => $row['npk'] ?? "",
            'employee_name' => $row['employee_name'] ?? "",
            'email' => $row['email'] ?? "",
            'employee_position' => $row['employee_position'] ?? "",
            'mobile_number' => $row['mobile_number'] ?? "",
        ];

        if ($employee) {
            // Update
            $employee->update($data);
        } else {
            // Create
            Employee::create($data);
        }
    }
}
