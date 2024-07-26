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

namespace think\saas\events;

class SaasBeforeInsert
{
    public function __construct(
        public array $data,
        public bool $multi = false
    ) {
    }
}
