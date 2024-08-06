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

use think\Cookie;
use catch\saas\support\Tenant;

class CookieManager extends Cookie
{

    /**
     * 获取cookie
     * @access public
     * @param  mixed  $name 数据名称
     * @param  string $default 默认值
     * @return mixed
     */
    public function get(string $name = '', $default = null)
    {
        return $this->request->cookie($this->getCookieName($name), $default);
    }

    /**
     * 是否存在Cookie参数
     * @access public
     * @param  string $name 变量名
     * @return bool
     */
    public function has(string $name): bool
    {
        return $this->request->has($this->getCookieName($name), 'cookie');
    }

    /**
     * Cookie 保存
     *
     * @access public
     * @param  string $name  cookie名称
     * @param  string $value cookie值
     * @param  int    $expire 有效期
     * @param  array  $option 可选参数
     * @return void
     */
    protected function setCookie(string $name, string $value, int $expire, array $option = []): void
    {
        $this->cookie[$this->getCookieName($name)] = [$value, $expire, $option];
    }

    /**
     * @param $name
     * @return string
     */
    protected function getCookieName($name): string
    {
        return $this->getTenantPrefix() . ':' . $name;
    }

    /**
     * @return string
     */
    protected function getTenantPrefix(): string
    {
        $tenant = app()->make(Tenant::class);

        return $tenant->tenantPrefix();
    }
}
