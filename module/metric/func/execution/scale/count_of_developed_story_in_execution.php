<?php
include dirname(__FILE__, 4) . DS . 'func.class.php';
class count_of_developed_story_in_execution
{
    public function getResult()
    {
        $storyCountList = $this->dao->select('count(t1.id) as count') ->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->where('t1.stage')->in('developed,testing,tested,verified,released')
            ->orWhere('t1.stage', true)->eq('closed')
            ->andWhere('t1.closedReason')->eq('done')
            ->markRight()
            ->groupBy('t2.project')
            ->fetchAll('project');
    }
}

