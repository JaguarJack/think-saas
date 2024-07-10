<?php
declare(strict_types=1);

namespace think\saas\support;

use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\Model;
use think\saas\contracts\TenantContract;
use think\saas\models\Tenant as TenantModel;

class Tenant
{
    protected ?int $tenantId = null;

    protected ?TenantModel $tenant = null;


    /**
     * @return TenantModel|Model
     */
    public function model(): TenantModel|Model
    {
        return app()->make(TenantContract::class);
    }

    /**
     * 查找
     *
     * @param $id
     * @return array|mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function find($id): mixed
    {
        return $this->model()->find($id);
    }

    /**
     * 删除租户
     *
     * @param $id
     * @return mixed
     */
    public function delete($id): mixed
    {
        return Db::transaction(function () use ($id) {
            $tenant = $this->find($id);
            $tenant->domains()->delete();

            $tenant->delete();
        });
    }

    /**
     * 租户域名
     *
     * @param $id
     * @return mixed
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function domains($id): mixed
    {
        return $this->find($id)->domain()->select();
    }

    /**
     * 创建域名
     *
     * @param $id
     * @param $data
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function createDomain($id, $data): bool
    {
        if (! is_array($data)) {
            $data = ['domain' => $data];
        }

        return $this->find($id)->domain()->save($data);
    }


    /**
     * 是否是单数据库模型
     *
     * @return bool
     */
    public function isSingleDatabase(): bool
    {
        return config('saas.is_single_database', false);
    }
}
