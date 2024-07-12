<?php
namespace think\saas\exceptions;

class TenantNotFoundException extends SaasException
{
    protected $message = '租户不存在';


    protected $code = 404;
}
