<?php
declare(strict_types=1);
/**
 * The helper functions and classes for ZentaoPHP file of zin of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun <sunhao@easycorp.ltd>
 * @package     zin
 * @link        https://www.zentao.net
 */

namespace zin;

require_once __DIR__ . DS . 'zentao.func.php';

/**
 * The global event bind class.
 *
 * @example
 * bind::click('alert("ok")');
 */
class bind
{
    /**
     * Bind event by magic __callStatic, the method name as the event name.
     *
     * @param  string $name
     * @param  array  $args
     * @return directive
     * @access public
     */
    public static function __callStatic(string $name, array $args): directive
    {
        list($callback, $others) = array_merge($args, array(array()));
        return bind($name, $callback, $others);
    }
}
