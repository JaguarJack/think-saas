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


namespace catch\saas\models;

use think\Model;
use catch\saas\models\traits\HasDatabase;
use catch\saas\models\traits\HasDomains;
use catch\saas\contracts\TenantContract;
use catch\saas\models\traits\HasPermissions;

class Tenant extends Model implements TenantContract
{
    use HasDomains, HasDatabase, HasPermissions;

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
