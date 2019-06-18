<?php


namespace App\Services;

use App\Appoitment;
use App\Clinic;
use App\Doctor;
use App\Patient;
use App\Speciality;

abstract class ApiService implements ApiInterface
{
    public function filterBy(string $property, string $value, string $operator = '=')
    {
        $data = $this->data->where($property, $operator, $value);

        $this->data = $data;

        return $this;
    }

    public function store()
    {
        $clinicData = $this->data->first();

        $clinic = Clinic::firstOrCreate([
            'id' => $clinicData['clinicId'],
            'name' => $clinicData['clinicName'],
        ]);

        foreach( $this->data as $element )
        {
            $patient =  Patient::updateOrCreate([
                'api_id' => $element['patientId'],
                'name' => $element['patientName'],
                'birth_date' => $element['dateOfBirth'],
                'gender' => $element['gender'],
                'clinic_id' => $element['clinicId'],
            ]);

            $specialty = Speciality::updateOrCreate([
                'api_id' => $element['specialtyId'],
                'name' => $element['specialtyName'],
            ]);

            $doctor = Doctor::updateOrCreate([
                'api_id' => $element['doctorId'],
                'name' => $element['doctorName'],
            ]);

            $appoitment = Appoitment::updateOrCreate([
                'api_id' => $element['id'],
                'booked_date' => $element['booked_date'],
                'booked_time' => $element['booked_time'],
                'status' => $element['status'],
                'created_at' => $element['created_at'],
                'clinic_id' => $element['clinicId'],
                'patient_id' => $element['patientId'],
            ]);

            $clinic->appointments()->save($appoitment);
            $patient->appointments()->save($appoitment);
            $specialty->appointments()->save($appoitment);
            $doctor->appointments()->save($appoitment);
        }

        return ['success' => 'Clinic Api data has been successfully stored'];
    }
}