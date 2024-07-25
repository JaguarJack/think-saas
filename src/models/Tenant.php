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


namespace think\saas\models;

use think\Model;
use think\saas\models\traits\HasDatabase;
use think\saas\models\traits\HasDomains;
use think\saas\contracts\TenantContract;

class Tenant extends Model implements TenantContract
{
    use HasDomains, HasDatabase;

    protected $json = ['database'];

    /**
     * 保存租户信息
     *
     * @param array $data
     * @return int
     */
    public function store(array $data) : int
    {
        // 创建租户
        $this->save($data);

        // 创建租户数据库信息
        $pk = $this->getKey();
        if ($pk) {
            $this->database = $this->database()->getConfig();
            $this->save();
        }

        return $pk;
    }
}
