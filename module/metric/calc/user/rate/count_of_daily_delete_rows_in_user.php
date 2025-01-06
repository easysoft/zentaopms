<?php
/**
 * 按人员统计人日代码删除行数。
 * Count of daily add rows in user.
 *
 * 范围：user
 * 对象：commit
 * 目的：rate
 * 度量名称：按人员统计人日代码删除行数
 * 单位：行
 * 描述：按提交人统计的日代码删除行数是指单个提交人每日的全部代码提交操作的代码新删除数量。代码提交次数反映了提交人每日的开发活动频率和代码更新情况。
 * 定义：所有代码删除行数求和 提交人为某人 提交时间为某日。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yang Li <liyang@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_delete_rows_in_user extends baseCalc
{
    public $dataset = 'getUsers';

    public $fieldList = array('t1.account', 't1.email');

    public $useSCM = true;

    public $result = array();

    public $rows = array();

    public function calculate($row)
    {
        if(!$row->email) $row->email = $row->account . '@gitfox.io';
        $this->rows[] = $row;
    }
