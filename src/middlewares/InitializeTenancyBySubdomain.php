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


namespace catch\saas\middlewares;

use think\Request;
use catch\saas\contracts\DomainContract;

class InitializeTenancyByDomain
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, \Closure $next): mixed
    {
        $host = $request->host();

        $domainModel = config('saas.domain_model');

        if (! $domainModel instanceof DomainContract) {
            throw new \Exception($domainModel . ' must implement ' . DomainContract::class);
        }

        // 获取租户
        $domainModel->getTenant($host);

        return $next($request);
    }
}
