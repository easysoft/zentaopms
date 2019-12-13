<?php
/**
 * The control file of ci module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chenqi <chenqi@cnezsoft.com>
 * @package     product
 * @version     $Id: control.php 5144 2019-12-11 06:37:03Z chenqi@cnezsoft.com $
 * @link        http://www.zentao.net
 */
class ci extends control
{
    /**
     * Construct function.
     *
     * @access public
     * @return void
     */
    public function __construct($moduleName = '', $methodName = '')
    {
        parent::__construct($moduleName, $methodName);

        /* Load need modules. */
        $this->loadModel('credential');
        $this->loadModel('user');

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
     * Browse credentials.
     *
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browseCredential($orderBy = 'id_desc', $recTotal = 0, $recPerPage = 20, $pageID = 1)
    {
        $this->app->loadClass('pager', $static = true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $this->view->title      = $this->lang->credential->common . $this->lang->colon . $this->lang->credential->list;
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
        $this->ci->delete(TABLE_CREDENTIAL, $id);
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

        $this->view->title      = $this->lang->jenkins->common . $this->lang->colon . $this->lang->jenkins->list;
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

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->list;
        $this->view->repoList   = $this->ci->listRepo($orderBy, $pager);

        $this->view->position[]    = $this->lang->ci->common;
        $this->view->position[]    = $this->lang->repo->common;

        $this->view->orderBy    = $orderBy;
        $this->view->pager      = $pager;
        $this->view->module      = 'repo';
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
     * @param  int    $id
     * @access public
     * @return void
     */
    public function editRepo($repoID)
    {
        $repo = $this->ci->getRepoByID($repoID);
        if($_POST)
        {
            $needSync = $this->ci->updateRepo($repoID);
            if(dao::isError()) die(js::error(dao::getError()));
            if(!$needSync)
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
     * @param  int    $id
     * @access public
     * @return void
     */
    public function deleteRepo($id)
    {
        $this->ci->delete(TABLE_JENKINS, $id);
        if(dao::isError()) $this->send(array('result' => 'fail', 'message' => dao::getError()));

        $this->send(array('result' => 'success'));
    }

    /**
     * browse branches.
     *
     * @param  int    $repoID
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
     * watch branche.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function watchBranch($repoID = 0, $branch=0, $status=0)
    {
        $this->dao->update(TABLE_REPOBRANCH)
            ->set('watch=' . $status)
            ->where('repo')->eq($repoID)
            ->andWhere('branch')->eq($branch)
            ->exec();
        echo true;
    }

    /**
     * Show sync comment.
     *
     * @param  int    $repoID
     * @access public
     * @return void
     */
    public function showSyncComment($repoID = 0)
    {
        if($repoID == 0) $repoID = $this->session->repoID;

        $this->view->title      = $this->lang->repo->common . $this->lang->colon . $this->lang->repo->showSyncComment;
        $this->view->position[] = $this->lang->repo->showSyncComment;

        $latestInDB = $this->ci->getLatestComment($repoID, );
        $this->view->version = $latestInDB ? (int)$latestInDB->commit : 1;
        $this->view->repoID  = $repoID;
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
    public function ajaxSyncComment($repoID = 0, $type = 'batch')
    {
        set_time_limit(0);
        $repo = $this->ci->getRepoByID($repoID);
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