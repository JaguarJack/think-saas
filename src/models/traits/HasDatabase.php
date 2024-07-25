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
