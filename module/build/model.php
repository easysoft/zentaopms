<?php
/**
 * The model file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: model.php 4970 2013-07-02 05:58:11Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
class buildModel extends model
{
    /**
     * Get build info.
     *
     * @param  int    $buildID
     * @param  bool   $setImgSize
     * @access public
     * @return object|bool
     */
    public function getByID($buildID, $setImgSize = false)
    {
        $build = $this->dao->select('t1.*, t2.name as executionName, t3.name as productName, t3.type as productType')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.id')->eq((int)$buildID)
            ->fetch();
        if(!$build) return false;

        $build = $this->loadModel('file')->replaceImgURL($build, 'desc');
        $build->files = $this->file->getByObject('build', $buildID);
        if($setImgSize) $build->desc = $this->file->setImgSize($build->desc);
        return $build;
    }

    /**
     * Get by ID list.
     *
     * @param  array $idList
     * @access public
     * @return array
     */
    public function getByList($idList)
    {
        return $this->dao->select('*')->from(TABLE_BUILD)->where('id')->in($idList)->fetchAll('id');
    }

    /**
     * Get builds of a project.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  int    $param
     * @param  string $orderBy
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getProjectBuilds($projectID = 0, $type = 'all', $param = 0, $orderBy = 't1.date_desc,t1.id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name as executionName, t2.id as executionID, t3.name as productName, t4.name as branchName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->leftJoin(TABLE_BRANCH)->alias('t4')->on('t1.branch = t4.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.project')->ne(0)
            ->beginIF($projectID)->andWhere('t1.project')->eq((int)$projectID)->fi()
            ->beginIF($type == 'product' and $param)->andWhere('t1.product')->eq($param)->fi()
            ->beginIF($type == 'bysearch')->andWhere($param)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get builds of a project by search.
     *
     * @param  int    $projectID
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getProjectBuildsBySearch($projectID, $queryID)
    {
        /* If there are saved query conditions, reset the session. */
        if((int)$queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('projectBuildQuery', $query->sql);
                $this->session->set('projectBuildForm', $query->form);
            }
        }
        if($this->session->projectBuildQuery == false) $this->session->set('projectBuildQuery', ' 1 = 1');

        $buildQuery = $this->session->projectBuildQuery;

        /* Distinguish between repeated fields. */
        $fields = array('id' => '`id`', 'name' => '`name`', 'product' => '`product`', 'desc' => '`desc`', 'project' => '`project`');
        foreach($fields as $field)
        {
            if(strpos($this->session->projectBuildQuery, $field) !== false)
            {
                $buildQuery = str_replace($field, "t1." . $field, $buildQuery);
            }
        }

        return $this->getProjectBuilds($projectID, 'bysearch', $buildQuery);
    }

    /**
     * Get builds of a execution.
     *
     * @param  int        $executionID
     * @param  string     $type      all|product|bysearch
     * @param  int|string $param     productID|buildQuery
     * @param  string     $orderBy
     * @param  object     $pager
     * @access public
     * @return array
     */
    public function getExecutionBuilds($executionID, $type = '', $param = '', $orderBy = 't1.date_desc,t1.id_desc', $pager = null)
    {
        return $this->dao->select('t1.*, t2.name as executionName, t3.name as productName, t4.name as branchName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->leftJoin(TABLE_BRANCH)->alias('t4')->on('t1.branch = t4.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($executionID)->andWhere('t1.execution')->eq((int)$executionID)->fi()
            ->beginIF($type == 'product' and $param)->andWhere('t1.product')->eq($param)->fi()
            ->beginIF($type == 'bysearch')->andWhere($param)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * Get builds of a execution by search.
     *
     * @param  int    $executionID
     * @param  int    $queryID
     * @access public
     * @return array
     */
    public function getExecutionBuildsBySearch($executionID, $queryID)
    {
        /* If there are saved query conditions, reset the session. */
        if((int)$queryID)
        {
            $query = $this->loadModel('search')->getQuery($queryID);
            if($query)
            {
                $this->session->set('executionBuildQuery', $query->sql);
                $this->session->set('executionBuildForm', $query->form);
            }
        }

        if($this->session->executionBuildQuery == false) $this->session->set('executionBuildQuery', ' 1 = 1');
        $buildQuery = $this->session->executionBuildQuery;

        /* Distinguish between repeated fields. */
        $fields = array('id' => '`id`', 'name' => '`name`', 'product' => '`product`', 'desc' => '`desc`');
        foreach($fields as $field)
        {
            if(strpos($this->session->executionBuildQuery, $field) !== false)
            {
                $buildQuery = str_replace($field, "t1." . $field, $buildQuery);
            }
        }

        return $this->getExecutionBuilds($executionID, 'bysearch', $buildQuery);
    }

    /**
     * Get story builds.
     *
     * @param  int    $storyID
     * @access public
     * @return array
     */
    public function getStoryBuilds($storyID)
    {
        return $this->dao->select('*')->from(TABLE_BUILD)
            ->where('deleted')->eq(0)
            ->andWhere("CONCAT(stories, ',')")->like("%,$storyID,%")
            ->orderBy('id_desc')
            ->fetchAll('id');
    }

    /**
     * Get builds in pairs.
     *
     * @param int|array  $products
     * @param string|int $branch
     * @param string     $params   noempty|notrunk|noterminate|withbranch, can be a set of them
     * @param string|int $objectID
     * @param string     $objectType
     * @param int|array  $buildIdList
     * @param bool       $replace
     * @access public
     * @return array
     */
    public function getBuildPairs($products, $branch = 'all', $params = 'noterminate, nodone', $objectID = 0, $objectType = 'execution', $buildIdList = '', $replace = true)
    {
        $sysBuilds      = array();
        $selectedBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => $this->lang->trunk);
        if($buildIdList)
        {
            $selectedBuilds = $this->dao->select('id, name')->from(TABLE_BUILD)
                ->where('id')->in($buildIdList)
                ->beginIF($products)->andWhere('product')->in($products)->fi()
                ->beginIF($objectType === 'execution')->andWhere('execution')->eq($objectID)->fi()
                ->beginIF($objectType === 'project')->andWhere('project')->eq($objectID)->fi()
                ->fetchPairs();
        }

        $allBuilds = $this->dao->select('t1.id, t1.name, t2.status as objectStatus, t3.id as releaseID, t3.status as releaseStatus, t4.name as branchName, t5.type as productType')->from(TABLE_BUILD)->alias('t1')
            ->beginIF($objectType === 'execution')->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')->fi()
            ->beginIF($objectType === 'project')->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')->fi()
            ->leftJoin(TABLE_RELEASE)->alias('t3')->on('t1.id = t3.build')
            ->leftJoin(TABLE_BRANCH)->alias('t4')->on('t1.branch = t4.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t5')->on('t1.product = t5.id')
            ->where('t1.deleted')->eq(0)
            ->beginIF($products)->andWhere('t1.product')->in($products)->fi()
            ->beginIF($objectType === 'execution' and $objectID)->andWhere('t1.execution')->eq($objectID)->fi()
            ->beginIF($objectType === 'project' and $objectID)->andWhere('t1.project')->eq($objectID)->fi()
            ->beginIF($branch !== 'all')->andWhere('t1.branch')->in("0,$branch")->fi()
            ->orderBy('t1.date desc, t1.id desc')->fetchAll('id');

        /* Set builds and filter done executions and terminate releases. */
        $builds = array();
        $this->app->loadLang('branch');
        foreach($allBuilds as $key => $build)
        {
            if(empty($build->releaseID) and (strpos($params, 'nodone') !== false) and ($build->objectStatus === 'done')) continue;
            if((strpos($params, 'noterminate') !== false) and ($build->releaseStatus === 'terminate')) continue;
            $branchName = $build->branchName ? $build->branchName : $this->lang->branch->main;

            $builds[$key] = $build->name;
            if(strpos($params, 'withbranch') !== false and $build->productType != 'normal') $builds[$key] = $branchName . '/' . $builds[$key];
        }

        if(!$builds) return $sysBuilds + $selectedBuilds;

        /* if the build has been released and replace is true, replace build name with release name. */
        if($replace)
        {
            $releases = $this->dao->select('build, name')->from(TABLE_RELEASE)
                ->where('build')->in(array_keys($builds))
                ->andWhere('product')->in($products)
                ->beginIF($branch !== 'all')->andWhere('branch')->in("0,$branch")->fi()
                ->andWhere('deleted')->eq(0)
                ->fetchPairs();
            foreach($releases as $buildID => $releaseName)
            {
                $branchName = $allBuilds[$buildID]->branchName ? $allBuilds[$buildID]->branchName : $this->lang->branch->main;
                if($allBuilds[$buildID]->productType != 'normal')
                {
                    $builds[$buildID] = (strpos($params, 'withbranch') !== false ? $branchName . '/' : '') . $releaseName;
                }
            }
        }

        return $sysBuilds + $builds + $selectedBuilds;
    }

    /**
     * Get last build.
     *
     * @param  int    $executionID
     * @access public
     * @return bool | object
     */
    public function getLast($executionID)
    {
        return $this->dao->select('id, name')->from(TABLE_BUILD)
            ->where('execution')->eq((int)$executionID)
            ->andWhere('deleted')->eq(0)
            ->orderBy('date DESC,id DESC')
            ->limit(1)
            ->fetch();
    }

    /**
     * Create a build
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function create($executionID)
    {
        $build = new stdclass();
        $build->stories = '';
        $build->bugs    = '';

        $execution = $this->loadModel('execution')->getByID($executionID);

        $build = fixer::input('post')
            ->setDefault('project', $execution->project)
            ->setDefault('product', 0)
            ->setDefault('branch', 0)
            ->cleanInt('product,branch')
            ->add('execution', (int)$executionID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', helper::now())
            ->stripTags($this->config->build->editor->create['id'], $this->config->allowedTags)
            ->remove('resolvedBy,allchecker,files,labels,uid')
            ->get();

        $build = $this->loadModel('file')->processImgURL($build, $this->config->build->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->create->requiredFields, 'notempty')
            ->check('name', 'unique', "product = {$build->product} AND branch = {$build->branch} AND deleted = '0'")
            ->checkFlow()
            ->exec();

        if(!dao::isError())
        {
            $buildID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $buildID, 'build');
            $this->file->saveUpload('build', $buildID);
            $this->loadModel('score')->create('build', 'create', $buildID);
            return $buildID;
        }
    }

    /**
     * Update a build.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function update($buildID)
    {
        $buildID  = (int)$buildID;
        $oldBuild = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->eq($buildID)->fetch();
        $build    = fixer::input('post')->stripTags($this->config->build->editor->edit['id'], $this->config->allowedTags)
            ->add('id', $buildID)
            ->setIF(!isset($_POST['branch']), 'branch', $oldBuild->branch)
            ->setDefault('product', $oldBuild->product)
            ->cleanInt('product,branch,execution')
            ->remove('allchecker,resolvedBy,files,labels,uid')
            ->get();

        $build = $this->loadModel('file')->processImgURL($build, $this->config->build->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->edit->requiredFields, 'notempty')
            ->where('id')->eq($buildID)
            ->check('name', 'unique', "id != $buildID AND product = {$build->product} AND branch = {$build->branch} AND deleted = '0'")
            ->checkFlow()
            ->exec();
        if(isset($build->branch) and $oldBuild->branch != $build->branch) $this->dao->update(TABLE_RELEASE)->set('branch')->eq($build->branch)->where('build')->eq($buildID)->exec();
        if(!dao::isError())
        {
            $this->file->updateObjectID($this->post->uid, $buildID, 'build');
            return common::createChanges($oldBuild, $build);
        }
    }

    /**
     * Update linked bug to resolved.
     *
     * @param  object    $build
     * @access public
     * @return void
     */
    public function updateLinkedBug($build)
    {
        $bugs = empty($build->bugs) ? '' : $this->dao->select('*')->from(TABLE_BUG)->where('id')->in($build->bugs)->fetchAll();
        $data = fixer::input('post')->get();
        $now  = helper::now();

        $resolvedPairs = array();
        if(isset($_POST['bugs']))
        {
            foreach($data->bugs as $key => $bugID)
            {
                if(isset($_POST['resolvedBy'][$bugID])) $resolvedPairs[$bugID] = $data->resolvedBy[$bugID];
            }
        }

        $this->loadModel('action');
        if(!$bugs) return false;
        foreach($bugs as $bug)
        {
            if($bug->status == 'resolved' or $bug->status == 'closed') continue;

            $bug->resolvedBy     = $resolvedPairs[$bug->id];
            $bug->resolvedDate   = $now;
            $bug->status         = 'resolved';
            $bug->confirmed      = 1;
            $bug->assignedDate   = $now;
            $bug->assignedTo     = $bug->openedBy;
            $bug->lastEditedBy   = $this->app->user->account;
            $bug->lastEditedDate = $now;
            $bug->resolution     = 'fixed';
            $bug->resolvedBuild  = $build->id;
            $this->dao->update(TABLE_BUG)->data($bug)->where('id')->eq($bug->id)->exec();
            $this->action->create('bug', $bug->id, 'Resolved', '', 'fixed', $bug->resolvedBy);
        }
    }

    /**
     * Link stories
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function linkStory($buildID)
    {
        $build = $this->getByID($buildID);

        foreach($this->post->stories as $i => $storyID)
        {
            if(strpos(",{$build->stories},", ",{$storyID},") !== false) unset($_POST['stories'][$i]);
        }

        $build->stories .= ',' . join(',', $this->post->stories);
        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($this->post->stories as $storyID) $this->action->create('story', $storyID, 'linked2build', '', $buildID);
    }

    /**
     * Unlink story
     *
     * @param  int    $buildID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory($buildID, $storyID)
    {
        $build = $this->getByID($buildID);
        $build->stories = trim(str_replace(",$storyID,", ',', ",$build->stories,"), ',');
        if($build->stories) $build->stories = ',' . $build->stories;

        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfrombuild', '', $buildID, '', false);
    }

    /**
     * Batch unlink story.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkStory($buildID)
    {
        $storyList = $this->post->unlinkStories;
        if(empty($storyList)) return true;

        $build = $this->getByID($buildID);
        $build->stories = ",$build->stories,";
        foreach($storyList as $storyID) $build->stories = str_replace(",$storyID,", ',', $build->stories);
        $build->stories = trim($build->stories, ',');
        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($this->post->unlinkStories as $unlinkStoryID) $this->action->create('story', $unlinkStoryID, 'unlinkedfrombuild', '', $buildID);
    }

    /**
     * Link bugs.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function linkBug($buildID)
    {
        $build = $this->getByID($buildID);

        foreach($this->post->bugs as $i => $bugID)
        {
            if(strpos(",{$build->bugs},", ",{$bugID},") !== false) unset($_POST['bugs'][$i]);
        }

        $build->bugs .= ',' . join(',', $this->post->bugs);
        $this->updateLinkedBug($build);
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($this->post->bugs as $bugID) $this->action->create('bug', $bugID, 'linked2bug', '', $buildID);
    }

    /**
     * Unlink bug.
     *
     * @param  int    $buildID
     * @param  int    $bugID
     * @access public
     * @return void
     */
    public function unlinkBug($buildID, $bugID)
    {
        $build = $this->getByID($buildID);
        $build->bugs = trim(str_replace(",$bugID,", ',', ",$build->bugs,"), ',');
        if($build->bugs) $build->bugs = ',' . $build->bugs;

        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfrombuild', '', $buildID, '', false);
    }

    /**
     * Batch unlink bug.
     *
     * @param  int    $buildID
     * @access public
     * @return void
     */
    public function batchUnlinkBug($buildID)
    {
        $bugList = $this->post->unlinkBugs;
        if(empty($bugList)) return true;

        $build = $this->getByID($buildID);
        $build->bugs = ",$build->bugs,";
        foreach($bugList as $bugID) $build->bugs = str_replace(",$bugID,", ',', $build->bugs);
        $build->bugs = trim($build->bugs, ',');
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();

        $this->loadModel('action');
        foreach($this->post->unlinkBugs as $unlinkBugID) $this->action->create('bug', $unlinkBugID, 'unlinkedfrombuild', '', $buildID);
    }

    /**
     * Build action menu.
     *
     * @param  object $build
     * @param  string $type
     * @param  string $extra
     * @access public
     * @return string
     */
    public function buildOperateMenu($build, $type = 'view', $extra = '')
    {
        $extraParams = array();
        if($extra) parse_str($extra, $extraParams);

        $menu   = '';
        $params = "buildID=$build->id";

        global $app;
        $tab = $app->tab;

        if($type == 'browse')
        {
            $executionID = $tab == 'execution' ? $extraParams['executionID'] : $build->execution;
            $execution   = $this->loadModel('execution')->getByID($executionID);
            $testtaskApp = (!empty($execution->type) and $execution->type == 'kanban') ? 'data-app="qa"' : "data-app='{$tab}'";

            if(common::hasPriv('build', 'linkstory') and common::canBeChanged('build', $build)) $menu .= $this->buildMenu('build', 'view', "{$params}&type=story&link=true", $build, $type, 'link', '', '', '', "data-app={$tab}", $this->lang->build->linkStory);

            $menu .= $this->buildMenu('testtask', 'create', "product=$build->product&execution={$executionID}&build=$build->id&projectID=$build->project", $build, $type, 'bullhorn', '', '', '', $testtaskApp);

            if($tab == 'execution' and !empty($execution->type) and $execution->type != 'kanban') $menu .= $this->buildMenu('execution', 'bug', "execution={$extraParams['executionID']}&productID={$extraParams['productID']}&branchID=all&orderBy=status&build=$build->id", $build, $type, '', '', '', '', $this->lang->execution->viewBug);
            if($tab == 'project' or empty($execution->type) or $execution->type == 'kanban')      $menu .= $this->buildMenu('build', 'view', "{$params}&type=generatedBug", $build, $type, 'bug', '', '', '', "data-app='$tab'", $this->lang->project->bug);

            $menu .= $this->buildMenu('build', 'edit',   $params, $build, $type);
            $menu .= $this->buildMenu('build', 'delete', $params, $build, $type, 'trash', 'hiddenwin');
        }
        else
        {
            $canBeChanged = common::canBeChanged('build', $build);
            if($build->deleted || !$canBeChanged) return '';

            $menu .= $this->buildFlowMenu('build', $build, 'view', 'direct');

            $editClickable   = $this->buildMenu('build', 'edit',   $params, $build, $type, '', '', '', '', '', '', false);
            $deleteClickable = $this->buildMenu('build', 'delete',   $params, $build, $type, '', '', '', '', '', '', false);
            if(common::hasPriv('build', 'edit')   and $editClickable)   $menu .= html::a(helper::createLink('build', 'edit',   $params), "<i class='icon-common-edit icon-edit'></i> "    . $this->lang->edit,   '', "class='btn btn-link' title='{$this->lang->build->edit}' data-app='{$app->tab}'");
            if(common::hasPriv('build', 'delete') and $deleteClickable) $menu .= html::a(helper::createLink('build', 'delete', $params), "<i class='icon-common-delete icon-trash'></i> " . $this->lang->delete, '', "class='btn btn-link' title='{$this->lang->build->delete}' target='hiddenwin' data-app='{$app->tab}'");
        }

        return $menu;
    }
}
