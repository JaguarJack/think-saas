<?php

declare(strict_types=1);

namespace think\saas\models\traits;

use think\model\relation\HasMany;
use think\saas\models\Domain;

trait HasDomains
{

    public function domain(): HasMany
    {
        return $this->hasMany(config('saas.domain_model', Domain::class), 'tenant_id');
    }
}
