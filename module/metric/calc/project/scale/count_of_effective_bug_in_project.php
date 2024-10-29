<?php
/**
 * 按项目统计的有效Bug数。
 * Count of effective bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的有效Bug数
 * 单位：个
 * 描述：按项目统计的有效Bug数是指项目中真正具有影响和价值的Bug数量。有效Bug通常是指导致项目不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估项目的稳定性和质量，也可以评估测试人员之间的协作或对项目的了解程度。
 * 定义：项目中所有Bug个数求和;解决方案为已解决、延期处理或状态为激活;过滤已删除的Bug;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
