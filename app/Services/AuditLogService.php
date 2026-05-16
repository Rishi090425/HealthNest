<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService
{
    public static function log($action, $description = null, $modelId = null, $modelType = null)
    {
        return AuditLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'description' => $description,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
            'model_id'    => $modelId,
            'model_type'  => $modelType,
        ]);
    }
}
