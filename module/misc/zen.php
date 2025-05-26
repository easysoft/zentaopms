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

        $packedData  = $packInt(zget($statistics, 'user', 0));  //用户数量
        $packedData .= $packInt(zget(zget($statistics, 'project',   array()), 'scrum', 0));         //敏捷项目数量
        $packedData .= $packInt(zget(zget($statistics, 'project',   array()), 'waterfall', 0));     //瀑布项目数量
        $packedData .= $packInt(zget(zget($statistics, 'project',   array()), 'kanban', 0));        //看板项目数量
        $packedData .= $packInt(zget(zget($statistics, 'project',   array()), 'agileplus', 0));     //融合敏捷项目数量
        $packedData .= $packInt(zget(zget($statistics, 'project',   array()), 'waterfallplus', 0)); //融合瀑布项目数量
        $packedData .= $packInt(zget(zget($statistics, 'project',   array()), 'ipd', 0));           //IPD项目数量
        $packedData .= $packInt(zget(zget($statistics, 'execution', array()), 'sprint', 0));        //迭代类型执行数量
        $packedData .= $packInt(zget(zget($statistics, 'execution', array()), 'stage', 0));         //阶段类型执行数量
        $packedData .= $packInt(zget(zget($statistics, 'execution', array()), 'kanban', 0));        //看板类型执行数量
        $packedData .= $packInt(zget($statistics, 'task', 0));            //任务数量
        $packedData .= $packInt(zget($statistics, 'product', 0));         //产品数量
        $packedData .= $packInt(zget($statistics, 'story', 0));           //需求数量
        $packedData .= $packInt(zget($statistics, 'bug', 0));             //Bug数量
        $packedData .= $packInt(zget($statistics, 'case', 0));            //用例数量
        $packedData .= $packInt(zget($statistics, 'doc', 0));             //文档数量
        $packedData .= $packStr(zget($statistics, 'OS', ''));             //操作系统
        $packedData .= $packStr(zget($statistics, 'phpversion', ''));     //PHP版本
        $packedData .= $packStr(zget($statistics, 'dbversion', ''));      //数据库版本

        return array('data' => bin2hex($packedData));
    }
}
