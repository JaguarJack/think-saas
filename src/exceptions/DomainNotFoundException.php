<?php
namespace think\saas\exceptions;

class DomainNotFoundException extends SaasException
{
    protected $message = '域名不存在';


    protected $code = 404;
}
