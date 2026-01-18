<?php

namespace App\Models;

use App\Models\Traits\HasUniqueReferenceNumber;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasUniqueReferenceNumber;
    
    protected $fillable = [
        'user_id',
        'provider_id',
        'service_id',
        'reference_number',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes',
        'cancellation_reason',
        'cancelled_at',
        'rescheduled_at',
        'original_data'
    ];

    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'booking_date' => 'date',       
            'start_time' => 'datetime', 
            'end_time' => 'datetime',   
            'cancelled_at' => 'datetime',
            'rescheduled_at' => 'datetime'
        ];
    }

     /**
     *Relationships
     *
     */
    public function service() : BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function provider() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
