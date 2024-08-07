<?php
/**
 * 按执行统计的来源Bug的任务消耗工时数
 * Consume of frombug task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：hour
 * 度量名称：按执行统计的来源Bug的任务消耗工时数
 * 单位：小时
 * 描述：按执行统计的来源Bug的任务消耗工时数是指执行中Bug转任务消耗的工时总和。该度量项反映了任务来源为Bug的资源使用情况，可以帮助团队识别缺陷管理中存在的问题。
 * 定义：执行中满足以下条件的任务消耗工时数求和，条件是：任务来源为Bug，过滤已删除的任务，过滤父任务，过滤已删除的执行，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
