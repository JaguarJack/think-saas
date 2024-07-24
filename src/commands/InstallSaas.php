<?php
namespace think\saas\commands;

use think\console\Command;
use think\console\Input;
use think\console\input\Option;
use think\console\Output;
use think\saas\support\sync\Schema;


class InstallSaas extends Command
{

    protected function configure()
    {
        $this->setName('saas:install')
            ->setDescription('saas 安装');
    }

    public function execute(Input $input, Output $output)
    {
        $schema = new Schema();
        dd($schema->getOriginTablesStructure());
        // 发布文件
        $this->publishConfig();
        $this->publishMigrations();
    }

    protected function publishConfig(): void
    {
        $target = $this->app->getRootPath() . 'config' . DIRECTORY_SEPARATOR . 'saas.php';

        $this->publish(
            dirname(__DIR__, 2). DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'saas.php',

            $target
        );
    }

    protected function publishMigrations(): void
    {
        $migrationsPath = dirname(__DIR__, 2). DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'migrations';

        $migrations = glob($migrationsPath . DIRECTORY_SEPARATOR . '*.php');

        foreach ($migrations as $migration) {
            $target = $this->app->getRootPath() . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . basename($migration);

            $this->publish($migration, $target);
        }
    }

    protected function publish($source, $target): bool|int
    {
        return file_put_contents($target, file_get_contents($source));
    }
}
