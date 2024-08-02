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

namespace think\saas\commands;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\saas\models\Tenant;
use think\saas\support\Str;
use think\saas\support\sync\multi\Schema;
class InstallSaas extends Command
{

    protected function configure()
    {
        $this->setName('saas:install')
            ->setDescription('saas 安装');
    }

    /**
     * 执行
     *
     * @param Input $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        // 发布文件
        $this->publishConfig();
        $this->publishMigrations();
    }

    /**
     * 发布配置
     *
     * @return void
     */
    protected function publishConfig(): void
    {
        $target = $this->app->getRootPath() . 'config' . DIRECTORY_SEPARATOR . 'saas.php';

        $this->publish(
            dirname(__DIR__, 2). DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'saas.php',

            $target
        );
    }

    /**
     * 发布 migrations
     *
     * @return void
     */
    protected function publishMigrations(): void
    {
        $migrationsPath = dirname(__DIR__, 2). DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'migrations';

        $migrations = glob($migrationsPath . DIRECTORY_SEPARATOR . '*.php');

        foreach ($migrations as $migration) {
            $target = $this->app->getRootPath() . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . basename($migration);

            $this->publish($migration, $target);
        }
    }

    /**
     * @param $source
     * @param $target
     * @return bool|int
     */
    protected function publish($source, $target): bool|int
    {
        return file_put_contents($target, file_get_contents($source));
    }
}
