<?php
namespace think\saas\exceptions;

class CreateDatabaseFailed extends SaasException
{
    protected $message = '创建租户数据库失败';


    protected $code = 500;
}
