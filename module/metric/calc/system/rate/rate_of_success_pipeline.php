<?php
/**
 * 按系统统计的流水线执行成功率。
 * Rate of success pipeline.
 *
 * 范围：system
 * 对象：pipeline
 * 目的：rate
 * 度量名称：按系统统计的流水线执行成功率
 * 单位：%
 * 描述：按系统统计的流水线执行成功率是指在一定时间内的流水线执行成功数量/流水线执行数量，反映了自动化构建和部署过程的稳定性与可靠性。
 * 定义：系统的流水线执行成功数量/流水线执行数量   不统计已删除代码库 不统计已删除流水线
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
