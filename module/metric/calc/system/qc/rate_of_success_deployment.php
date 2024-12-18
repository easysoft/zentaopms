<?php
/**
 * 按系统统计的上线成功率。
 * Rate of success deployment in system.
 *
 * 范围：system
 * 对象：deployment
 * 目的：rate
 * 度量名称：按系统统计合并请求通过率
 * 单位：百分比
 * 描述：上线成功率是衡量团队在软件发布过程中的稳定性和可靠性的重要指标。通过统计上线成功率，团队能够评估其发布流程的有效性，及时识别潜在问题并优化上线策略。
 * 定义：系统的上线成功的上线申请数量/（上线中/上线成功/上线失败的上线申请个数） 不统计已删除上线申请
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
