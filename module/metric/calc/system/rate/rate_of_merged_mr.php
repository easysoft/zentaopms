<?php
/**
 * 按系统统计合并请求通过率。
 * Count of merged pull requests in codebase.
 *
 * 范围：code
 * 对象：mergeRequest
 * 目的：rate
 * 度量名称：按系统统计合并请求通过率
 * 单位：个
 * 描述：按系统统计的合并请求通过率是指已合并合并请求/总的合并请求数。通过统计在一定时间范围内提交的合并请求中合并的比例，团队能够有效监控其代码审查过程的健康状况，并及时识别潜在的改进空间。
 * 定义：系统已合并合并请求/总的合并请求数 不统计已删除的合并请求 不统计已删除代码库里的合并请求;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    liyang <liyang@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_merged_mr extends baseCalc
{
    public $dataset = 'getMRs';

    public $fieldList = array('t1.status');

    public $result = array('count' => 0, 'merged' => 0);

}
