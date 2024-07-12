<?php
namespace think\saas\models;

use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model;
use think\saas\contracts\DomainContract;
use think\saas\exceptions\TenantNotFoundException;
use think\saas\models\traits\HasTenant;
use think\saas\exceptions\DomainNotFoundException;

class Domain extends Model implements DomainContract
{
    use HasTenant;

    /**
     * @param string $Domain
     * @return mixed
     * @throws DomainNotFoundException
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException|TenantNotFoundException
     */
    public function getTenant(string $Domain)
    {
        // TODO: Implement getTenant() method.
        $domain = $this->where('domain', $Domain)->find();

        if (!$domain) {
            throw new DomainNotFoundException();
        }

        $tenant = $domain->tenant()->find();

        if (! $tenant) {
            throw new TenantNotFoundException();
        }

        return $tenant;
    }
}
