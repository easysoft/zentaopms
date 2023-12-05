<?php
declare(strict_types=1);
/**
 * The control file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: control.php 4178 2013-01-20 09:32:11Z wwccss $
 * @link        http://www.zentao.net
 */
class projectrelease extends control
{
    public $products = array();

    /**
     * 构造函数，自动加载模块。
     * Construct function, load module auto.
     *
     * @param  string $moduleName
     * @param  string $methodName
     * @access public
     * @return void
     */
    public function __construct(string $moduleName = '', string $methodName = '')
    {
        parent::__construct($moduleName, $methodName);
        $this->loadModel('product');
        $this->loadModel('release');
        $this->loadModel('project');
    }

    /**
     * 项目发布列表。
     * Browse releases.
     *
     * @param  int    $projectID
     * @param  int    $executionID
     * @param  string $type
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function browse(int $projectID = 0, int $executionID = 0, string $type = 'all', string $orderBy = 't1.date_desc', int $recTotal = 0, int $recPerPage = 15, int $pageID = 1)
    {
        /* 设置发布列表和版本列表 session。*/
        /* Set releaseList and buildList session. */
        $uri = $this->app->getURI(true);
        $this->session->set('releaseList', $uri, 'project');
        $this->session->set('buildList', $uri);

        /* 设置菜单。*/
        /* Set menu. */
        if($projectID)   $this->project->setMenu($projectID);
        if($executionID) $this->loadModel('execution')->setMenu($executionID, $this->app->rawModule, $this->app->rawMethod);

        $this->app->loadClass('pager', true);
        $pager = new pager($recTotal, $recPerPage, $pageID);

        $releases = $this->projectrelease->getList($projectID, $type, $orderBy, $pager);

        /* 判断是否展示分支。*/
        /* Judge whether to show branch. */
        $showBranch = false;
        foreach($releases as $release)
        {
            if($release->productType != 'normal')
            {
                $showBranch = true;
                break;
            }
        }

        $project   = $this->project->getByID($projectID);
        $execution = $this->loadModel('execution')->getByID($executionID);

        $this->view->title       = (isset($project->name) ? $project->name : $execution->name) . $this->lang->colon . $this->lang->release->browse;
        $this->view->products    = $this->product->getProductPairsByProject($projectID);
        $this->view->pageSummary = $this->release->getPageSummary($releases, $type);
        $this->view->projectID   = $projectID;
        $this->view->executionID = $executionID;
        $this->view->type        = $type;
        $this->view->from        = $this->app->tab;
        $this->view->project     = $project;
        $this->view->execution   = $execution;
        $this->view->releases    = $releases;
        $this->view->pager       = $pager;
        $this->view->orderBy     = $orderBy;
        $this->view->showBranch  = $showBranch;
        $this->display();
    }

    /**
     * 创建一个发布。
     * Create a release.
     *
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function create(int $projectID)
    {
        /* Set create config. */
        $this->config->projectrelease->create = $this->config->release->create;

        if(!empty($_POST))
        {
            $release = form::data($this->config->release->form->create)
                ->add('product', $this->post->product ? $this->post->product : 0)
                ->add('branch', $this->post->branch ? $this->post->branch : 0)
                ->setIF($projectID, 'project', $projectID)
                ->setIF($this->post->build === false, 'build', 0)
                ->get();

            /* Check build if build is required. */
            if(strpos($this->config->release->create->requiredFields, 'build') !== false && empty($release->build)) dao::$errors['build'] = sprintf($this->lang->error->notempty, $this->lang->release->build);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $releaseID = $this->release->create($release, $this->post->sync ? true : false);

            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));

            $this->loadModel('action')->create('release', $releaseID, 'opened');

            $message = $this->executeHooks($releaseID);
            if($message) $this->lang->saveSuccess = $message;

            if($this->viewType == 'json') return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'id' => $releaseID));
            if(isInModal()) return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'closeModal' => true));
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "releaseID=$releaseID")));
        }

        /* Set menu. */
        $this->project->setMenu($projectID);
        $this->projectreleaseZen->commonAction($projectID);

        /* Get the builds that can select. */
        $builds         = $this->loadModel('build')->getBuildPairs(array($this->view->product->id), 'all', 'notrunk|withbranch|hasproject', $projectID, 'project', '', false);
        $releasedBuilds = $this->projectrelease->getReleasedBuilds($projectID);
        foreach($releasedBuilds as $build) unset($builds[$build]);

        $this->view->title       = $this->view->project->name . $this->lang->colon . $this->lang->release->create;
        $this->view->projectID   = $projectID;
        $this->view->builds      = $builds;
        $this->view->lastRelease = $this->projectrelease->getLast($projectID);
        $this->view->users       = $this->loadModel('user')->getPairs('noclosed');
        $this->display('release', 'create');
    }

    /**
     * 编辑一个发布。
     * Edit a release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function edit(int $releaseID)
    {
        /* Set edit config. */
        $this->config->projectrelease->edit = $this->config->release->edit;
        $release = $this->release->getByID($releaseID);

        if(!empty($_POST))
        {
            $releaseData = form::data($this->config->release->form->edit)->setIF($this->post->build === false, 'build', 0)->get();
            $changes     = $this->release->update($releaseData, $release);
            if(dao::isError()) return $this->send(array('result' => 'fail', 'message' => dao::getError()));
            if($changes)
            {
                $actionID = $this->loadModel('action')->create('release', $releaseID, 'Edited');
                if(!empty($changes)) $this->action->logHistory($actionID, $changes);
            }

            $message = $this->executeHooks($releaseID);
            if($message) $this->lang->saveSuccess = $message;
            return $this->send(array('result' => 'success', 'message' => $this->lang->saveSuccess, 'locate' => inlink('view', "releaseID={$releaseID}")));
        }

        /* Set menu. */
        if(!$this->session->project)
        {
            $releaseProject = explode(',', $release->project);
            $this->session->set('project', $releaseProject[0], 'project');
        }
        $this->project->setMenu($this->session->project);
        $this->projectreleaseZen->commonAction($this->session->project, $release->product, $release->branch);

        /* Get the builds that can select. */
        $builds         = $this->loadModel('build')->getBuildPairs(array($release->product), $release->branch, 'notrunk|withbranch|hasproject', $this->session->project, 'project', $release->build, false);
        $bindBuilds     = $this->build->getByList(explode(',', $release->build));
        $releasedBuilds = $this->projectrelease->getReleasedBuilds($this->session->project);
        foreach($releasedBuilds as $releasedBuild)
        {
            if(!isset($bindBuilds[$releasedBuild])) unset($builds[$releasedBuild]);
        }

        $this->view->title   = $this->view->product->name . $this->lang->colon . $this->lang->release->edit;
        $this->view->release = $release;
        $this->view->builds  = $builds;
        $this->view->users   = $this->loadModel('user')->getPairs('noclosed');
        $this->display('release', 'edit');
    }

    /**
     * 查看一个发布。
     * View a release.
     *
     * @param  int    $releaseID
     * @param  string $type
     * @param  string $link
     * @param  string $param
     * @param  string $orderBy
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function view(int $releaseID, string $type = 'story', string $link = 'false', string $param = '', string $orderBy = 'id_desc', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        echo $this->fetch('release', 'view', "releaseID={$releaseID}&type={$type}&link={$link}&param={$param}&orderBy={$orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 提示发布。
     * Notify for release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function notify(int $releaseID)
    {
        echo $this->fetch('release', 'notify', "releaseID={$releaseID}&projectID={$this->session->project}");
    }

    /**
     * 删除一个发布。
     * Delete a release.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function delete(int $releaseID)
    {
        return $this->fetch('release', 'delete', "releaseID={$releaseID}");
    }

    /**
     * 导出项目发布到 HTML。
     * Export the stories of release to HTML.
     *
     * @access public
     * @return void
     */
    public function export()
    {
        if(!empty($_POST))
        {
            $type = $this->post->type;

            $html = '';
            if($type == 'story' || $type == 'all')   $html .= $this->projectreleaseZen->generateStoryHtml();
            if($type == 'bug'   || $type == 'all')   $html .= $this->projectreleaseZen->generateBugHtml();
            if($type == 'leftbug' || $type == 'all') $html .= $this->projectreleaseZen->generateBugHtml('left');
            $html = "<html><head><meta charset='utf-8'><title>{$this->post->fileName}</title><style>table, th, td{font-size:12px; border:1px solid gray; border-collapse:collapse;}</style></head><body>$html</body></html>";

            return print($this->fetch('file', 'sendDownHeader', array('fileName' => $this->post->fileName, 'html', $html)));
        }

        $this->display('release', 'export');
    }

    /**
     * 项目发布关联需求。
     * Link stories
     *
     * @param  int    $releaseID
     * @param  string $browseType
     * @param  int    $param
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkStory(int $releaseID = 0, string $browseType = '', int $param = 0, int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        echo $this->fetch('release', 'linkStory', "releaseID={$releaseID}&browseType={$browseType}&param={$param}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 项目发布取消关联需求。
     * Unlink a story from a release.
     *
     * @param  int    $releaseID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory(int $releaseID, int $storyID)
    {
        echo $this->fetch('release', 'unlinkStory', "releaseID={$releaseID}&storyID={$storyID}");
    }

    /**
     * 项目发布批量取消关联需求。
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function batchUnlinkStory(int $releaseID)
    {
        echo $this->fetch('release', 'batchUnlinkStory', "releaseID={$releaseID}");
    }

    /**
     * 项目发布关联 bug。
     * Link bugs.
     *
     * @param  int    $releaseID
     * @param  string $browseType
     * @param  int    $param
     * @param  string $type
     * @param  int    $recTotal
     * @param  int    $recPerPage
     * @param  int    $pageID
     * @access public
     * @return void
     */
    public function linkBug(int $releaseID = 0, string $browseType = '', int $param = 0, $type = 'bug', int $recTotal = 0, int $recPerPage = 100, int $pageID = 1)
    {
        echo $this->fetch('release', 'linkBug', "releaseID={$releaseID}&browseType={$browseType}&param={$param}&type={$type}&recTotal={$recTotal}&recPerPage={$recPerPage}&pageID={$pageID}");
    }

    /**
     * 取消关联 bug。
     * Unlink bug.
     *
     * @param  int    $releaseID
     * @param  int    $bugID
     * @param  string $type
     * @access public
     * @return void
     */
    public function unlinkBug(int $releaseID, int $bugID, string $type = 'bug')
    {
        echo $this->fetch('release', 'unlinkBug', "releaseID={$releaseID}&bugID={$bugID}&type={$type}");
    }

    /**
     * 项目发布批量取消关联 bug。
     * Batch unlink story.
     *
     * @param  int    $releaseID
     * @param  string $type
     * @access public
     * @return void
     */
    public function batchUnlinkBug(int $releaseID, string $type = 'bug')
    {
        echo $this->fetch('release', 'batchUnlinkBug', "releaseID={$releaseID}&type={$type}");
    }

    /**
     * 停止维护或者激活发布。
     * Terminate or active the release.
     *
     * @param  int    $releaseID
     * @param  string $status
     * @access public
     * @return void
     */
    public function changeStatus(int $releaseID, string $status)
    {
        return $this->fetch('release', 'changeStatus', "releaseID={$releaseID}&status={$status}");
    }

    /**
     * 通过 ajax 加载版本。
     * Ajax load builds.
     *
     * @param  int    $projectID
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function ajaxLoadBuilds(int $projectID, int $productID)
    {
        $builds         = $this->loadModel('build')->getBuildPairs(array($productID), 'all', 'notrunk,withbranch,hasproject', $projectID, 'project', '', false);
        $releasedBuilds = $this->projectrelease->getReleasedBuilds($projectID);

        $buildList = array();
        foreach($builds as $buildID => $buildName)
        {
            if(in_array($buildID, $releasedBuilds)) continue;

            $buildList[] = array('text' => $buildName, 'value' => $buildID);
        }

        return print(json_encode($buildList));
    }
}
