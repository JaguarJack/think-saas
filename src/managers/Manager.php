<?php
namespace think\saas\managers;

class Manager
{
    public function reBind(): void
    {
        $app = app();
        // 重新绑定DB对象
        $app->bind('think\Db', DbManager::class);
        // session 接管
        $app->bind('think\Session', SessionManager::class);
        // 日志 接管
        $app->bind('think\Log', LogManager::class);
        // 缓存接管
        $app->bind('think\Cache', CacheManager::class);
        // cookie 接管
        $app->bind('think\Cookie', CookieManager::class);
    }
}
