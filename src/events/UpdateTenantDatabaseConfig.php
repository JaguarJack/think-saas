<?php
namespace think\saas\events;


class UpdateTenantDatabaseConfig
{
    public function __construct(
        public  array $config
    ){}
}
