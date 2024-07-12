<?php
namespace think\saas\contracts;

interface TenantContract
{
    public function store(array $data) : int;
}
