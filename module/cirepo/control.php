<?php

/**
 * The control file of cirepo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class cirepo extends control
{
    /**
     * ci constructor.
     * @param string $moduleName
     * @param string $methodName
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->scm = $this->app->loadClass('scm');
    }

    /**
     * Browse repo.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->ci->list;
        $this->view->position[] = $this->lang->ci->common;
        $this->view->position[] = $this->lang->repo->common;

        $this->view->repoList   = $this->cirepo->listAll($orderBy, $pager);

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module     = 'cirepo';
        $this->display();
    }

    /**
     * Create a repo.
     *
     * @access public
     * @return void
     */
    public function create()
    {
        if($_POST)
        {
            $repoID = $this->cirepo->create();

            if(dao::isError()) die(js::error(dao::getError()));

            $link = $this->cirepo->createLink('showSyncComment', "repoID=$repoID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->repo->create . $this->lang->colon . $this->lang->repo->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->repo->common);
        $this->view->position[]    = $this->lang->repo->create;

        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted');

        $this->view->credentialsList  = $this->loadModel('cicredentials')->listForSelection("type='sshKey' or type='account'");
        $this->view->tips            = str_replace("{user}",exec('whoami'), $this->lang->repo->tips);
        $this->view->module          = 'cirepo';

        $this->display();
    }

    /**
     * Edit a repo.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function edit($repoID)
    {
        $repo = $this->cirepo->getByID($repoID);
        if($_POST)
        {
            $noNeedSync = $this->cirepo->update($repoID);
            if(dao::isError()) die(js::error(dao::getError()));
            if(!$noNeedSync)
            {
                $link = $this->cirepo->createLink('showSyncComment', "repoID=$repoID");
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browse')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->repo->edit . $this->lang->colon . $repo->name;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browse'), $this->lang->repo->common);
        $this->view->position[]    = $this->lang->repo->edit;

        $this->view->repo    = $repo;

        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted');

        $this->view->credentialsList  = $this->loadModel('cicredentials')->listForSelection("type='sshKey' or type='account'");
        $this->view->tips            = str_replace("{user}", exec('whoami'), $this->lang->repo->tips);
        $this->view->module          = 'cirepo';
        $this->display();
    }

    /**
     * Delete a repo.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function delete($id)
    {
        $this->cirepo->delete(TABLE_REPO, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * sync repo from remote.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function sync($repoID = 0)
    {
        $this->dao->update(TABLE_REPO)->set('synced')->eq(0)->where('id')->eq($repoID)->exec();

        $link = $this->cirepo->createLink('showSyncComment', "repoID=$repoID&needPull=true");
        $this->send(array('result' => 'success', 'locate' => $link));
    }

    /**
     * browse branches.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function browseBranch($repoID = 0)
    {
        $repo  = $this->cirepo->getByID($repoID);
        $branches = $this->cirepo->getBranchesFromDb($repoID);

        $this->view->repo       = $repo;
        $this->view->branches   = $branches;

        $this->view->module     = 'cirepo';
        $this->display();
    }

    /**
     * watch branch.
     *
     * @param int $repoID
     * @param int $branch
     * @param int $status
     */
    public function watchBranch($repoID = 0, $branch = 0, $status = 0)
    {
        $this->dao->update(TABLE_REPOBRANCH)
            ->set('watch=' . $status)
            ->where('repo')->eq($repoID)
            ->andWhere('branch')->eq($branch)
            ->exec();
    }

    /**
     * Show sync comment.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function showSyncComment($repoID = 0, $needPull = false)
    {
        if($repoID == 0) $repoID = $this->session->repoID;

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->showSyncComment;
        $this->view->position[] = $this->lang->repo->showSyncComment;

        $latestInDB = $this->cirepo->getLatestComment($repoID);
        $this->view->version = $latestInDB ? (int)$latestInDB->commit : 1;
        $this->view->repoID  = $repoID;
        $this->view->needPull  = $needPull;
        $this->display();
    }

    /**
     * Ajax sync comment.
     *
     * @param  int    $repoID
     * @param  string $type
     * @access public
     * @return void
     */
    public function ajaxSyncComment($repoID = 0, $type = 'batch', $needPull = false)
    {
        set_time_limit(0);
        $repo = $this->cirepo->getByID($repoID);

        if ($needPull) {
            $this->loadModel('git')->pull($repo);
        }

        if(empty($repo)) die();
        if($repo->synced) die('finish');

        $this->scm->setEngine($repo);

        $branchID = '';
        if($repo->SCM == 'Git' and empty($branchID))
        {
            $branches = $this->scm->branch();
            if($branches)
            {
                /* Init branchID. */
                if($this->cookie->syncBranch) $branchID = $this->cookie->syncBranch;
                if(!isset($branches[$branchID])) $branchID = '';
                if(empty($branchID)) $branchID = reset($branches);

                /* Get unsynced branches. */
                foreach($branches as $branch)
                {
                    unset($branches[$branch]);
                    if($branch == $branchID)
                    {
                        break;
                    }
                }
            }
        }

        $latestInDB = $this->dao->select('DISTINCT t1.*')->from(TABLE_REPOHISTORY)->alias('t1')
            ->leftJoin(TABLE_REPOBRANCH)->alias('t2')->on('t1.id=t2.revision')
            ->where('t1.repo')->eq($repoID)
//            ->beginIF($repo->SCM == 'Git' and $this->cookie->repoBranch)->andWhere('t2.branch')->eq($this->cookie->repoBranch)->fi()
            ->orderBy('t1.time')
            ->limit(1)
            ->fetch();

        $version  = empty($latestInDB) ? 1 : $latestInDB->commit + 1;
        $logs     = array();
        $revision = $version == 1 ? 'HEAD' : ($repo->SCM == 'Git' ? $latestInDB->commit : $latestInDB->revision);
        if($type == 'batch')
        {
            $logs = $this->scm->getCommits($revision, $this->config->repo->batchNum, $branchID);
        }
        else
        {
            $logs = $this->scm->getCommits($revision, 0, $branchID);
        }

        $commitCount = $this->cirepo->saveCommit($repoID, $logs, $version, $branchID);
        if(empty($commitCount))
        {
            if(!$repo->synced)
            {
                if($repo->SCM == 'Git')
                {
                    if($branchID) $this->cirepo->saveExistsLogBranch($repo->id, $branchID);

                    $branchID = reset($branches);
                    setcookie("syncBranch", $branchID, 0, $this->config->webRoot);

                    if($branchID) $this->cirepo->fixCommit($repoID);
                }

                if(empty($branchID))
                {
                    $this->cirepo->markSynced($repoID);
                    die('finish');
                }
            }
        }

        $this->dao->update(TABLE_REPO)->set('commits=commits + ' . $commitCount)->where('id')->eq($repoID)->exec();
        echo $type == 'batch' ?  $commitCount : 'finish';
    }
}