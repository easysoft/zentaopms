<?php

/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: ${FILE_NAME} 5144 2020/1/8 8:10 下午 chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class ci extends control
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
     * CI index page.
     *
     * @access public
     * @return void
     */
    public function index()
    {
        $this->display();
    }

    /**
     * Browse credentials
     *
     * @param string $orderBy
     * @param int $recTotal
     * @param int $recPerPage
     * @param int $pageID
     */
    public function browseCredential($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->credential->common . $this->lang->colon . $this->lang->ci->list;
        $this->view->credentialList   = $this->ci->listCredential($orderBy, $pager);

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = $this->lang->credential->common;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'credential';
        $this->display();
    }

    /**
     * Create a credential.
     *
     * @access public
     * @return void
     */
    public function createCredential()
    {
        if($_POST)
        {
            $this->ci->createCredential();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseCredential')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->credential->create . $this->lang->colon . $this->lang->credential->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseCredential'), $this->lang->credential->common);
        $this->view->position[]    = $this->lang->credential->create;

        $this->view->module      = 'credential';
        $this->display();
    }

    /**
     * Edit a credential.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editCredential($id)
    {
        $credential = $this->ci->getCredentialByID($id);
        if($_POST)
        {
            $this->ci->updateCredential($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseCredential')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->credential->edit . $this->lang->colon . $credential->name;

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseCredential'), $this->lang->credential->common);
        $this->view->position[]    = $this->lang->credential->edit;

        $this->view->credential    = $credential;

        $this->view->module      = 'credential';
        $this->display();
    }

    /**
     * Delete a credential.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deleteCredential($id)
    {
        $this->ci->delete(TABLE_CREDENTIALS, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Browse jenkinss.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseJenkins($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->ci->list;
        $this->view->jenkinsList   = $this->ci->listJenkins($orderBy, $pager);

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = $this->lang->jenkins->common;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'jenkins';
        $this->display();
    }

    /**
     * Create a jenkins.
     *
     * @access public
     * @return void
     */
    public function createJenkins()
    {
        if($_POST)
        {
            $this->ci->createJenkins();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseJenkins')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->jenkins->create . $this->lang->colon . $this->lang->jenkins->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseJenkins'), $this->lang->jenkins->common);
        $this->view->position[]    = $this->lang->jenkins->create;

        $this->view->credentialList  = $this->ci->listCredentialForSelection("type='token' or type='account'");
        $this->view->module      = 'jenkins';

        $this->display();
    }

    /**
     * Edit a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editJenkins($id)
    {
        $jenkins = $this->ci->getJenkinsByID($id);
        if($_POST)
        {
            $this->ci->updateJenkins($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseJenkins')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->jenkins->edit . $this->lang->colon . $jenkins->name;

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseJenkins'), $this->lang->jenkins->common);
        $this->view->position[]    = $this->lang->jenkins->edit;

        $this->view->jenkins    = $jenkins;
        $this->view->credentialList  = $this->ci->listCredentialForSelection("type='token' or type='account'");

        $this->view->module      = 'jenkins';
        $this->display();
    }

    /**
     * Delete a jenkins.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deleteJenkins($id)
    {
        $this->ci->delete(TABLE_JENKINS, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Browse ci task.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseCitask($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->citask->common . $this->lang->colon . $this->lang->ci->list;
        $this->view->citaskList   = $this->ci->listCitask($orderBy, $pager);

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = $this->lang->citask->common;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'citask';
        $this->display();
    }

    /**
     * Create a ci task.
     *
     * @access public
     * @return void
     */
    public function createCitask()
    {
        if($_POST)
        {
            $this->ci->createCitask();
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseCitask')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->citask->create . $this->lang->colon . $this->lang->citask->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseCitask'), $this->lang->citask->common);
        $this->view->position[]    = $this->lang->citask->create;

        $this->view->repoList      = $this->ci->listRepoForSelection("true");
        $this->view->jenkinsList   = $this->ci->listJenkinsForSelection("true");
        $this->view->module        = 'citask';

        $this->display();
    }

    /**
     * Edit a ci task.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editCitask($id)
    {
        $citask = $this->ci->getCitaskByID($id);
        if($_POST)
        {
            $this->ci->updateCitask($id);
            if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseCitask')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->citask->edit . $this->lang->colon . $citask->name;

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseCitask'), $this->lang->citask->common);
        $this->view->position[]    = $this->lang->citask->edit;

        $this->view->citask        = $citask;

        $this->view->repoList      = $this->ci->listRepoForSelection("true");
        $this->view->jenkinsList   = $this->ci->listJenkinsForSelection("true");
        $this->view->module        = 'citask';
        $this->display();
    }

    /**
     * Delete a ci task.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deleteCitask($id)
    {
        $this->ci->delete(TABLE_CI_TASK, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Exec a ci task.
     *
     * @param  int    $id
     * @access public
     * @return void
     */
    public function exeCitask($id)
    {
        $this->ci->exeCitask($id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * Send a request to jenkins to check build status.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function checkCibuild($buildID)
    {
        $this->ci->checkCibuild($buildID);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
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
    public function browseRepo($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->ci->list;
        $this->view->position[] = $this->lang->ci->common;
        $this->view->position[] = $this->lang->repo->common;

        $this->view->repoList   = $this->ci->listRepo($orderBy, $pager);

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module     = 'repo';
        $this->display();
    }

    /**
     * Create a repo.
     *
     * @access public
     * @return void
     */
    public function createRepo()
    {
        if($_POST)
        {
            $repoID = $this->ci->createRepo();

            if(dao::isError()) die(js::error(dao::getError()));

            $link = $this->ci->createLink('showSyncComment', "repoID=$repoID");
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->repo->create . $this->lang->colon . $this->lang->repo->common;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseRepo'), $this->lang->repo->common);
        $this->view->position[]    = $this->lang->repo->create;

        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted');

        $this->view->credentialList  = $this->ci->listCredentialForSelection("type='sshKey' or type='account'");
        $this->view->module      = 'repo';

        $this->display();
    }

    /**
     * Edit a repo.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function editRepo($repoID)
    {
        $repo = $this->ci->getRepoByID($repoID);
        if($_POST)
        {
            $noNeedSync = $this->ci->updateRepo($repoID);
            if(dao::isError()) die(js::error(dao::getError()));
            if(!$noNeedSync)
            {
                $link = $this->ci->createLink('showSyncComment', "repoID=$repoID");
                $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => $link));
            }
            $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('browseRepo')));
        }

        $this->app->loadLang('action');
        $this->view->title         = $this->lang->repo->edit . $this->lang->colon . $repo->name;
        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = html::a(inlink('browseRepo'), $this->lang->repo->common);
        $this->view->position[]    = $this->lang->repo->edit;

        $this->view->repo    = $repo;

        $this->view->groups = $this->loadModel('group')->getPairs();
        $this->view->users  = $this->loadModel('user')->getPairs('noletter|noempty|nodeleted');

        $this->view->credentialList  = $this->ci->listCredentialForSelection("type='sshKey' or type='account'");
        $this->view->module      = 'repo';
        $this->display();
    }

    /**
     * Delete a repo.
     *
     * @param  int $id
     * @access public
     * @return void
     */
    public function deleteRepo($id)
    {
        $this->ci->delete(TABLE_REPO, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
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
        $repo  = $this->ci->getRepoByID($repoID);
        $branches = $this->ci->getBranchesFromDb($repoID);

        $this->view->repo       = $repo;
        $this->view->branches   = $branches;

        $this->view->module     = 'repo';
        $this->display();
    }

    /**
     * sync repo from remote.
     *
     * @param  int $repoID
     * @access public
     * @return void
     */
    public function syncRepo($repoID = 0)
    {
        $this->dao->update(TABLE_REPO)->set('synced')->eq(0)->where('id')->eq($repoID)->exec();

        $link = $this->ci->createLink('showSyncComment', "repoID=$repoID&needPull=true");
        $this->send(array('result' => 'success', 'locate' => $link));
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

        $latestInDB = $this->ci->getLatestComment($repoID, );
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
        $repo = $this->ci->getRepoByID($repoID);

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

        $commitCount = $this->ci->saveCommit($repoID, $logs, $version, $branchID);
        if(empty($commitCount))
        {
            if(!$repo->synced)
            {
                if($repo->SCM == 'Git')
                {
                    if($branchID) $this->ci->saveExistsLogBranch($repo->id, $branchID);

                    $branchID = reset($branches);
                    setcookie("syncBranch", $branchID, 0, $this->config->webRoot);

                    if($branchID) $this->ci->fixCommit($repoID);
                }

                if(empty($branchID))
                {
                    $this->ci->markSynced($repoID);
                    die('finish');
                }
            }
        }

        $this->dao->update(TABLE_REPO)->set('commits=commits + ' . $commitCount)->where('id')->eq($repoID)->exec();
        echo $type == 'batch' ?  $commitCount : 'finish';
    }
}