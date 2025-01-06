<?php
/**
 * 按代码库统计的日代码删除行数。
 * Count of daily delete rows in codebase.
 *
 * 范围：repo
 * 对象：commit
 * 目的：rate
 * 度量名称：按代码库统计的日代码删除行数
 * 单位：行
 * 描述：按代码库统计的的日代码删除行数是指代码库在某日的代码提交删除的代码行数量。这个度量项可以反映代码库单日开发活动频率和代码更新情况。
 * 定义：代码库中代码提交删除行数求和，提交时间为某日。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Yang Li<liyang@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
