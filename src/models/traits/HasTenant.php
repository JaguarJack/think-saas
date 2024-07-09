<?php

namespace think\saas\models\traits;

use think\model\relation\BelongsTo;
use think\saas\models\Tenant;

trait HasTenant
{
    /**
     * @return BelongsTo
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'id', 'tenant_id');
    }
}
