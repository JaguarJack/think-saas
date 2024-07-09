<?php


declare(strict_types=1);

namespace think\saas\models\traits;


use think\saas\support\DatabaseConfig;

trait HasDatabase
{
    public function database(): DatabaseConfig
    {
        return new DatabaseConfig($this);
    }
}
