<?php
declare(strict_types=1);
/**
 * The zui toggle class file of zin lib.
 *
 * @copyright   Copyright 2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @version     $Id
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'toggle.func.php';

class toggle
{
    public static function __callStatic(string $name, array $args): directive
    {
        return toggle($name, empty($args) ? array() : $args[0]);
    }
}
