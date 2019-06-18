<?php

namespace App;

use Awobaz\Compoships\Database\Eloquent\Model;

class Appoitment extends Model
{

    protected $fillable = [
        'api_id',
        'booked_date',
        'booked_time',
        'status',
        'clinic_id',
        'patient_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'api_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, ['patient_id', 'clinic_id'], ['api_id','clinic_id']);
    }

    public function speciality()
    {
        return $this->belongsTo(Speciality::class, 'speciality_id', 'api_id');
    }

}
