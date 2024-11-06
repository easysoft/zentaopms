<?php
/**
 * 按项目统计的年度新增有效Bug数。
 * Count of annual created effective bug in project.
 *
 * 范围：project
 * 对象：bug
 * 目的：scale
 * 度量名称：按项目统计的年度新增有效Bug数
 * 单位：个
 * 描述：按项目统计的年度新增有效Bug数是指项目在某年度新发现的真正具有影响和价值的Bug数量。有效Bug通常是指导致项目不正常运行或影响用户体验的Bug。统计有效Bug数可以帮助评估项目的稳定性和质量也可以评估测试人员之前的协作或对项目的了解程度。
 * 定义：项目中Bug个数求和\n创建时间为某年\n解决方案为已解决和延期处理或者状态为激活\n过滤已删除的Bug\n过滤已删除的项目\n;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
