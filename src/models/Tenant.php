<?php
namespace think\saas\models;

use think\Model;
use think\saas\models\traits\HasDatabase;
use think\saas\models\traits\HasDomains;
use think\saas\contracts\TenantContract;

class Tenant extends Model implements TenantContract
{
    use HasDomains, HasDatabase;


}
