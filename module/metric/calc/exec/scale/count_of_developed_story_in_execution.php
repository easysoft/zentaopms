<?php
/**
 * 执行中研发完成的需求总数。
 * Count of developed story in execution.
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_developed_story_in_execution extends baseCalc
{
    public $dao = null;
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t2.project,count(t1.id) as value') ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->where('t1.stage')->in('developed,testing,tested,verified,released')
            ->orWhere('(t1.stage')->eq('closed')
            ->andWhere('t1.closedReason')->eq('done')
            ->markRight(1)
            ->groupBy('t2.project');
    }

    public function calculate($data)
    {
        if($data->project == '' || $data->project == 0) return;
        $this->result[] = $data;
    }

    public function getResult()
    {
        return $this->result;
    }
}
