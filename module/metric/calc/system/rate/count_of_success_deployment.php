<?php
/**
 * 按系统统计上线成功的上线计划总数。
 * Count of success deployment.
 *
 * 范围：system
 * 对象：deployment
 * 目的：rate
 * 度量名称：按系统统计的上线成功数
 * 单位：个
 * 描述：上线成功数是衡量团队在软件发布过程中的绩效和交付能力的重要指标。通过统计在一定时间范围内成功完成的上线操作数量，团队能够评估其发布流程的有效性和稳定性，及时识别问题并优化上线策略。
 * 定义：系统的上线成功的上线申请个数求和; 不统计已删除上线申请;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
