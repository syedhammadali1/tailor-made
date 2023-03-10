<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RequestMeasurement extends Model
{
    use HasFactory;
    protected $fillable = [
        'appointment_id', 'measurements_text', 'measurements_image',
        'neck_circumference1','shoulder_to_shoulder2','chest_circumference3',
        'circumference_under_the_breast3a','waist_circumference4','back_length5',
        'shoulder_to_wrist6','shoulder_to_elbow7','wrist_to_elbow8','biceps9',
        'forearm10','wrist_circumference11','waist_to_ankle_length12',
        'hip_circumference13','thigh_circumference14','circumference_of_knees15',
        'calf_circumference16','crotch_to_ankle17','knees_to_ankle18',
        'ankle_circumference19','neck_to_ankle20','foot_a','foot_b','foot_c','foot_d','foot_e','foot_f',
        'measurement_video','uuid'
        ];



        /**
         * Get the user that owns the RequestMeasurement
         *
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function appointment()
        {
            return $this->belongsTo(RequestAppointment::class, 'appointment_id', 'id');
        }


}
