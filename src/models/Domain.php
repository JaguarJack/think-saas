<?php
namespace think\saas\models;

use think\model;
use think\saas\contracts\DomainContract;
use think\saas\models\traits\HasTenant;

class Domain extends Model implements DomainContract
{
    use HasTenant;

    public function getTenant(string $subDomain)
    {
        // TODO: Implement getTenant() method.
        $domain = $this->where('domain', $subDomain)->find();

        return $domain->tenant()->find();
    }
}
