<?php
namespace think\saas\commands;


use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\saas\exceptions\TenantNotFoundException;
use think\saas\support\Tenant;

/**
 * 租户 command 基类
 */
abstract class TenantCommand extends Command
{
    abstract public function handle(Input $input, Output $output);

    /**
     * @param Input $input
     * @param Output $output
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws TenantNotFoundException
     */
    protected function execute(Input $input, Output $output): void
    {
        $tenant = new Tenant();
        $tenant->run(function ($input, $output) {
            return $this->handle($input, $output);
        });
    }
}
