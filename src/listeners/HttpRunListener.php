<?php
namespace think\saas\listeners;


// 应用初始化
use think\saas\contracts\DomainContract;

class HttpRunListener
{
    public function __construct(
        protected DomainContract $domain)
    {

    }

    public function handle($event)
    {
        // 这里来处理租户，因为这是应用最早启动的时候
        // 因为此时配置文件已经加载
        // 所以这里可以从主库获取数据
        // 然后保存到缓存中

        dd($this->getTenant());
    }


    protected function getHost()
    {
        return request()->host();
    }


    protected function getTenant()
    {
        return $this->domain->getTenant(request()->host());
    }
}
