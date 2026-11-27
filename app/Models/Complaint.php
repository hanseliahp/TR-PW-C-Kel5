<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Complaint extends Model
{
    public const STATUS_BARU = 'baru';

    public const STATUS_DIPROSES = 'diproses';

    public const STATUS_SELESAI = 'selesai';

    protected $fillable = [
        'category_id',
        'reporter_name',
        'location',
        'reported_at',
        'description',
        'status',
    ];

    protected $attributes = [
        'status' => self::STATUS_BARU, // BR-04
    ];

    protected function casts(): array
    {
        return [
            'reported_at' => 'date',
        ];
    }

    /** Daftar status valid (BR-03), dipakai untuk validasi: Rule::in(Complaint::statuses()) */
    public static function statuses(): array
    {
        return [self::STATUS_BARU, self::STATUS_DIPROSES, self::STATUS_SELESAI];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function followUps(): HasMany
    {
        return $this->hasMany(FollowUp::class)->latest();
    }
}
