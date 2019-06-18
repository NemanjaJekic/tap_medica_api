<?php

namespace App;

use Awobaz\Compoships\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'api_id',
        'name',
        'birth_date',
        'gender',
        'clinic_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appoitment::class, ['patient_id','clinic_id'], ['api_id','clinic_id']);
    }
}
