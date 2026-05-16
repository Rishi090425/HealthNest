<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id', 'changes', 'ip_address'
    ];

    protected $casts = ['changes' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record an audit log entry.
     */
    public static function record(string $action, ?string $modelType = null, ?int $modelId = null, ?array $changes = null): void
    {
        static::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'model_type' => $modelType,
            'model_id'   => $modelId,
            'changes'    => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}
