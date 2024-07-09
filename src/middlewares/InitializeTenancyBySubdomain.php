<?php
namespace think\saas\middlewares;

use think\Request;
use think\saas\contracts\DomainContract;

class InitializeTenancyByDomain
{
    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle(Request $request, \Closure $next)
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
