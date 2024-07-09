<?php

declare(strict_types=1);

namespace think\saas\models\traits;

use think\model\relation\HasMany;

trait HasDomains
{

    public function domain(): HasMany
    {
        return $this->hasMany(config('saas.domain_model'), 'tenant_id');
    }


    public function createDomain($data): bool|\think\Model
    {
        if (! is_array($data)) {
            $data = ['domain' => $data];
        }

        return $this->domain()->save($data);
    }
}
