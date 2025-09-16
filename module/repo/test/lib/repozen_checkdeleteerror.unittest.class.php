<?php
declare(strict_types = 1);
class repoZenTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('repo');
        $this->objectTao   = $tester->loadTao('repo');
    }

    /**
     * Test checkDeleteError method.
     *
     * @param  int $repoID
     * @access public
     * @return string
     */
    public function checkDeleteErrorTest(int $repoID): string
    {
        if(dao::isError()) return dao::getError();

        $error = '';

        // 检查设计关联
        $relationIds = $this->objectModel->dao->select('distinct AID as AID')->from(TABLE_RELATION)
            ->where('extra')->eq($repoID)
            ->andWhere('AType')->eq('design')
            ->fetchAll();

        if($relationIds)
        {
            $tmpDesignLinks = [];
            foreach ($relationIds as $value)
            {
                array_push($tmpDesignLinks, html::a(helper::createLink('design', 'view', 'designID=' . $value->AID), $value->AID, '_blank', '', false));
            }
            $error .= sprintf($this->objectModel->lang->repo->error->deleted, implode(', ', $tmpDesignLinks));
        }

        // 检查关联分支
        $linkBranchs = $this->objectModel->getLinkedBranch(0, '', $repoID);
        if(!empty($linkBranchs))
        {
            $tmpLinkBranchs = [];
            foreach($linkBranchs as $value)
            {
                if(!array_key_exists($value->AType, $tmpLinkBranchs)) $tmpLinkBranchs[$value->AType] = [];

                if(!in_array($value->BType, $tmpLinkBranchs[$value->AType])) array_push($tmpLinkBranchs[$value->AType], $value->BType);
            }
            foreach($tmpLinkBranchs as $type=>$value)
            {
                $error .= sprintf($this->objectModel->lang->repo->error->linkedBranch, $this->objectModel->lang->$type->common, html::a(
                    helper::createLink('repo', 'browse', 'repoID=' . $repoID),
                    implode(', ', $value), '_blank', '', false
                ));
            }
        }

        // 检查关联作业
        $jobs = $this->objectModel->dao->select('*')->from(TABLE_JOB)->where('repo')->eq($repoID)->andWhere('deleted')->eq('0')->fetchAll();
        if($jobs) $error .= sprintf($this->objectModel->lang->repo->error->linkedJob, html::a(helper::createLink('job', 'browse'), implode(', ', array_column($jobs, 'id')), '_blank', '', false));

        return $error;
    }
}