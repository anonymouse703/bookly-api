<?php

namespace App\Models;

use App\Enums\Service\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'provider_id',
        'name',
        'address',
        'latitude',
        'longtitude',
        'description',
        'price',
        'duration',
        'status',
        'rating',
        'reviews_count'
    ];

    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

     /**
     *Relationships
     *
     */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function provider() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
