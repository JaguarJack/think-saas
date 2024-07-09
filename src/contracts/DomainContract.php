<?php
namespace think\saas\contracts;

interface DomainContract
{
    public function getTenant(string $domain);
}
