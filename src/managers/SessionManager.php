<?php
// +----------------------------------------------------------------------
// | Think SaaS 开源软件
// +----------------------------------------------------------------------
// | CatchAdmin [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2024~2030 https://catchadmin.com/ All rights reserved.
// +----------------------------------------------------------------------
// | Author: JaguarJack <njphper@gmail.com>
// +----------------------------------------------------------------------


namespace catch\saas\managers;

use think\App;
use catch\saas\support\Tenant;
use think\Session;

/**
 * Class SessionManager
 *
 * Session 驱动有 file 和 Cache 两种
 *
 * Session 主要通过 prefix 做区别
 *
 * @package catch\saas\managers
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
