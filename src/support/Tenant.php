<?php
declare(strict_types=1);

namespace think\saas\support;

use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\facade\Log;
use think\Model;
use think\saas\events\GetTenants;
use think\saas\events\InitializeTenantDatabase;
use think\saas\events\InitializeTenantDatabaseData;
use think\saas\exceptions\CreateDatabaseFailed;
use think\saas\exceptions\TenantNotFoundException;

/**
 * 租户
 */
class Tenant
{
    /**
     * 租户ID
     *
     * @var int
     */
    protected int $tenantId;

    /**
     * 租户模型
     *
     * @var Model|null
     */
    protected ?Model $tenant = null;

    /**
     * 是否初始化
     *
     * @var bool
     */
    public bool $initialized = false;

    /**
     * @param mixed $tenant
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws TenantNotFoundException
     */
    public function initialize(mixed $tenant): void
    {
        // 已经初始化了
        if ($this->initialized) {
            // 如果是当前租户 则直接返回
            if ($this->tenantId === $tenant->getKey()) {
                return;
            }

            $this->tenant = null;
        }

        $tenantModel = $this->model();
        if ($tenant instanceof $tenantModel) {
            $this->tenant = $tenant;
        } else {
            $this->tenant = $tenantModel->find($tenant);
        }

        if (! $this->tenant) {
            throw new TenantNotFoundException();
        }

        $this->tenantId = $this->tenant->id;
        // 初始化成功
        $this->initialized = true;

        // 链接数据库
        $this->connectDatabase();
    }

    /**
     * @return Model
     */
    public function model(): Model
    {
        /* @var Model $tenantModel */
        return app(config('saas.tenant_model'));
    }

    /**
     *
     * 创建租户
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int
    {
        return $this->model()->store($data);
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
     * @param $id
     * @return bool
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws TenantNotFoundException|CreateDatabaseFailed
     */
    public function createDatabase($id): bool
    {
        $tenant = $this->find($id);

        $tenant->database = $tenant->database()->getConfig();

        try {
            Db::startTrans();
            // 创建租户数据库
            $tenant->save();
            // 创建数据库
            if (Db::databaseExists($tenant->database['database'])) {
                throw new \Exception('数据库['. $tenant->database['database'].']已经存在：' );
            }
            Db::createDatabase($tenant->database['database']);
            Db::commit();
            return true;
        } catch (\Throwable $e) {
            Db::rollback();
            Log::info('create database failed: ' . $e->getMessage());
            throw new CreateDatabaseFailed(
                '创建数据库失败：' . $e->getMessage()
            );
        }
    }

    /**
     *
     * 初始化数据库
     *
     * @param $id
     * @return bool
     */
    public function initDatabase($id): bool
    {
        return Db::transaction(function () use ($id) {
            // 链接数据库
            $this->initialize($id);
            $this->connectDatabase();

            // 同步表结构
            event(new InitializeTenantDatabase());
            // 初始化租户数据
            event(new InitializeTenantDatabaseData());

            return true;
        });
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


    /**
     * 链接租户数据库
     *
     * @return void
     */
    public function connectDatabase(): void
    {
        if ($this->isSingleDatabase()) {
            return;
        }

        $config = app()->config;

        $config->set(['default' => 'tenant'], 'database');

        // 直接覆盖租户配置
        $connections = $config->get('database.connections', []);
        $connections['tenant'] = (array) $this->tenant->database;

        $config->set([
            'connections' => $connections,
        ], 'database');

        app()->db->setConfig($config);
    }

    /**
     * @param callable $callback
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException|TenantNotFoundException
     */
    public function run(callable $callback): void
    {
        if (app()->event->hasListener(GetTenants::class)) {
            $tenants= event(new GetTenants());
        } else {
            $tenants = $this->model()->select();
        }

        $tenants->each(function ($tenant) use ($callback) {
                $this->initialize($tenant);

                $callback($this);

                $this->end();
            });
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->tenantId;
    }

    /**
     * 回滚初始化
     *
     * @return void
     */
    public function end(): void
    {
        if (! $this->initialized) {
            return;
        }

        $this->initialized = false;

        $this->tenant = null;

        $this->tenantId = 0;
    }
}
