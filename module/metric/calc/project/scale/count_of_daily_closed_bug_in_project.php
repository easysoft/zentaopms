<?php
/**
 * 按项目统计的每日关闭Bug数。
 * Count of daily closed bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的每日关闭Bug数
 * 单位：个
 * 描述：按项目统计的每日关闭Bug数是指每天在项目中每日关闭的Bug的数量。该度量项可以帮助我们了解开发团队对已解决的Bug进行确认与关闭的速度和效率，通过对比不同时间段的关闭Bug数，可以评估开发团队的协作和问题处理能力。
 * 定义：项目中Bug数求和，关闭时间为某日，过滤已删除的Bug，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
