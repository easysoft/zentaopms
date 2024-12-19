<?php
/**
 * 按系统统计的流水线执行平均耗时。
 * Avg of compile time pipeline.
 *
 * 范围：system
 * 对象：pipeline
 * 目的：rate
 * 度量名称：按系统统计的流水线执行数
 * 单位：小时
 * 描述：按系统统计的流水线执行平均耗时是指在一定时间内的流水线执行时间/执行的数量，通过统计在一定时间范围内每次流水线执行的耗时，并计算出平均值，团队能够深入了解构建和部署过程的性能，及时识别潜在的瓶颈并优化工作流程。
 * 定义：系统的流水线执行时间/执行数量   不统计已删除代码库 不统计已删除流水线
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
