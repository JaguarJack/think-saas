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

namespace think\saas\support;

class Tree
{
    protected static string $pk = 'id';

    /**
     * @param array $items
     * @param int $pid
     * @param string $pidField
     * @param string $children
     * @return array
     */
    public static function done(array $items, int $pid = 0, string $pidField = 'parent_id', string $children = 'children'): array
    {
        $tree = [];

        foreach ($items as $item) {
            if ($item[$pidField] == $pid) {
                $child = self::done($items, $item[self::$pk], $pidField);
                if (count($child)) {
                    $item[$children] = $child;
                }
                $tree[] = $item;
            }
        }

        return $tree;
    }

    /**
     * @param string $pk
     * @return Tree
     */
    public static function setPk(string $pk): Tree
    {
        self::$pk = $pk;

        return new self;
    }

}
