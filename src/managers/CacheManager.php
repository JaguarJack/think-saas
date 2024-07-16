<?php
namespace think\saas\managers;

use think\Cache;
use think\saas\support\Tenant;

class CacheManager extends Cache
{
    /**
     * 获取缓存配置
     * @access public
     * @param null|string $name    名称
     * @param mixed       $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = null, $default = null): mixed
    {
        if (!is_null($name)) {
            return $this->addTenantPrefix($this->app->config->get('cache.' . $name, $default));
        }

        return $this->addTenantPrefix($this->app->config->get('cache'));
    }

    /**
     * 添加租户前缀
     *
     * @param $config
     * @return mixed
     */
    protected function addTenantPrefix($config): mixed
    {
        $tenantPrefix = $this->app->get(Tenant::class)->tenantPrefix();

        if (isset($config['stores'])) {
            foreach ($config['stores'] as &$store) {
                $store['prefix'] = $this->addPrefixIfNotSet($tenantPrefix, $store['prefix']);
                $store['tag_prefix'] = $this->addPrefixIfNotSet($tenantPrefix, $store['tag_prefix']);
            }
        } else {
            $config['prefix'] = $this->addPrefixIfNotSet($tenantPrefix, $config['prefix']);
            $config['tag_prefix'] = $this->addPrefixIfNotSet($tenantPrefix, $config['tag_prefix']);
        }

        return $config;
    }

    /**
     * @param $tenantPrefix
     * @param $prefix
     * @return string
     */
    protected function addPrefixIfNotSet($tenantPrefix, $prefix): string
    {
        return $prefix ? ($tenantPrefix . ':' . $prefix) : $tenantPrefix;
    }
}
