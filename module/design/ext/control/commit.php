<?php
class design extends control
{
    public function commit($designID, $begin = '', $end = '', $recTotal = 0, $recPerPage = 50, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID); 

        $program = $this->loadModel('project')->getByID($this->session->program);
        $begin   = $begin ? date('Y-m-d', strtotime($begin)) : $program->begin;
        $end     = $end ? date('Y-m-d', strtotime($end)) : helper::today();

        $repoID     = $this->session->repoID;
        $repo       = $this->loadModel('repo')->getRepoByID($repoID);
        $revisions  = $this->repo->getCommits($repo, '', 'HEAD', '', $pager, $begin, $end);

        if($_POST)
        {
            $this->design->linkCommit($designID);

            $result['result']  = 'success';
            $result['message'] = $this->lang->saveSuccess;
            $result['locate']  = 'parent';
            $this->send($result);
        }

        $linkedRevisions = array();
        $relations = $this->loadModel('common')->getRelations('design', $designID, 'commit');
        foreach($relations as $relation) $linkedRevisions[] = $relation->BID;

        $this->view->title           = $this->lang->design->commit;
        $this->view->position[]      = $this->lang->design->commit;
        $this->view->repoID          = $repoID;
        $this->view->repo            = $repo;
        $this->view->revisions       = $revisions;
        $this->view->linkedRevisions = $linkedRevisions;
        $this->view->designID        = $designID;
        $this->view->begin           = $begin;
        $this->view->end             = $end;
        $this->view->design          = $this->design->getByID($designID);
        $this->view->pager           = $pager;
        $this->display();
    }
}
