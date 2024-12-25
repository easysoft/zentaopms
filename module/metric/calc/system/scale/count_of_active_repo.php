<?php
/**
 * 按系统统计活跃的代码库数量。
 * Count of activated repo.
 *
 * 范围：system
 * 对象：repo
 * 目的：scale
 * 度量名称：按系统统计的活跃代码库数
 * 单位：个
 * 描述：活跃代码库数是衡量开发团队在一定时间内参与项目开发和维护的重要指标。通过统计在特定时间范围内有代码提交的代码库数量，团队能够有效评估其开发生态的活跃度和资源分配情况。
 * 定义：在所选时间内有代码提交的代码库数量 不统计已删除代码库
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
