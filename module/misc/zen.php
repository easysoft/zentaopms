<?php
declare(strict_types=1);
/**
 * The zen file of misc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang<wangyuting@easycorp.ltd>
 * @package     misc
 * @link        https://www.zentao.net
 */
class miscZen extends misc
{
    /**
     * 打印 hello world。
     * print hello world.
     *
     * @access public
     * @return string
     */
    public function hello(): string
    {
        return 'hello world from hello()<br />';
    }

    /**
     * Encode the statistics data into a binary string.
     *
     * @param  array  $statistics Statistics data.
     * @access public
     * @return array
     */
    public function encodeStatistics(array $statistics): array
    {
        if(empty($statistics)) return array();

        $packInt = function($value) { return pack('C', $value); };
        $packStr = function($value) { return pack('a*', $value) . pack('a*', "\0"); };

        $packedData  = $packInt(zget($statistics, 'user', 0));
        $packedData .= $packInt(zget($statistics, 'execution', 0));
        $packedData .= $packInt(zget($statistics, 'task', 0));
        $packedData .= $packInt(zget($statistics, 'product', 0));
        $packedData .= $packInt(zget($statistics, 'story', 0));
        $packedData .= $packInt(zget($statistics, 'doc', 0));
        $packedData .= $packInt(zget($statistics, 'bug', 0));
        $packedData .= $packInt(zget($statistics, 'case', 0));
        $packedData .= $packInt(zget(zget($statistics, 'project', array()), 'scrum', 0));
        $packedData .= $packInt(zget(zget($statistics, 'project', array()), 'waterfall', 0));
        $packedData .= $packInt(zget(zget($statistics, 'project', array()), 'kanban', 0));
        $packedData .= $packInt(zget(zget($statistics, 'project', array()), 'agileplus', 0));
        $packedData .= $packInt(zget(zget($statistics, 'project', array()), 'waterfallplus', 0));
        $packedData .= $packInt(zget(zget($statistics, 'project', array()), 'ipd', 0));
        $packedData .= $packStr(zget($statistics, 'OS', ''));
        $packedData .= $packStr(zget($statistics, 'phpversion', ''));
        $packedData .= $packStr(zget($statistics, 'dbversion', ''));

        return array('data' => bin2hex($packedData));
    }
}
