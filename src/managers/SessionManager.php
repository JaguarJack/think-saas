<?php
namespace think\saas\managers;

use think\App;
use think\saas\support\Tenant;
use think\Session;

/**
 * Class SessionManager
 *
 * Session 驱动有 file 和 Cache 两种
 *
 * Session 主要通过 prefix 做区别
 *
 * @package think\saas\managers
 */
class SessionManager extends Session
{
    public function __construct(protected App $app)
    {
        parent::__construct($app);

        $this->addTenantPrefix();
    }

    /**
     * 添加租户前缀
     *
     * @return void
     */
    protected function addTenantPrefix(): void
    {
        $tenantPrefix = $this->app->get(Tenant::class)->tenantPrefix('');

        $config = $this->app->config->get('session');

        $config['prefix'] = $tenantPrefix . '_' . $config['prefix'];

        $this->app->config->set($config, 'session');
    }
}
