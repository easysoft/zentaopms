<?php
/**
 * 按执行统计的来源Bug的任务消耗工时占比
 * Consume rate of frombug task in execution
 *
 * 范围：execution
 * 对象：task
 * 目的：hour
 * 度量名称：按执行统计的来源Bug的任务消耗工时占比
 * 单位：小时
 * 描述：按执行统计的来源Bug的任务消耗工时占比是指执行中Bug转任务消耗的工时与执行中所有任务消耗工时的比值。该度量项反映了任务来源为Bug的资源使用情况，可以帮助团队识别缺陷管理中存在的问题，例如历史遗留缺陷过多导致执行一直在补旧账。
 * 定义：复用：按执行统计的来源Bug的任务消耗工时数、按执行统计的任务消耗工时数；公式：按执行统计的来源Bug的任务消耗工时数/按执行统计的任务消耗工时数。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
