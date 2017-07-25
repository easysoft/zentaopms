<?php
/**
 * The model file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
     * @return object
     */
    public function getByID($buildID, $setImgSize = false)
    {
        $build = $this->dao->select('t1.*, t2.name as projectName, t3.name as productName, t3.type as productType')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
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
     * @access public
     * @return array
     */
    public function getProjectBuilds($projectID)
    {
        return $this->dao->select('t1.*, t2.name as projectName, t3.name as productName, t4.name as branchName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->leftJoin(TABLE_BRANCH)->alias('t4')->on('t1.branch = t4.id')
            ->where('t1.project')->eq((int)$projectID)
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.date DESC, t1.id desc')
            ->fetchAll();
    }

    /**
     * Get builds of a project in pairs. 
     * 
     * @param  int    $projectID 
     * @param  int    $productID 
     * @param  string $params       noempty|notrunk, can be a set of them
     * @access public
     * @return array
     */
    public function getProjectBuildPairs($projectID, $productID, $branch = 0, $params = '')
    {
        $sysBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => $this->lang->trunk);

        $projectBuilds = $this->dao->select('t1.id, t1.name, t1.project, t2.status as projectStatus, t3.id as releaseID, t3.status as releaseStatus, t4.name as branchName')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_RELEASE)->alias('t3')->on('t1.id = t3.build')
            ->leftJoin(TABLE_BRANCH)->alias('t4')->on('t1.branch = t4.id')
            ->where('t1.project')->eq((int)$projectID)
            ->beginIF($productID)->andWhere('t1.product')->eq((int)$productID)->fi()
            ->beginIF($branch)->andWhere('t1.branch')->in("0,$branch")->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.date desc, t1.id desc')->fetchAll('id');

        /* Set builds and filter terminate releases. */
        $builds = array();
        foreach($projectBuilds as $buildID => $build)
        {
            if(empty($build->releaseID) and (strpos($params, 'nodone') !== false) and ($build->projectStatus === 'done')) continue;
            if((strpos($params, 'noterminate') !== false) and ($build->releaseStatus === 'terminate')) continue;
            $builds[$buildID] = $build->name;
        }
        if(!$builds) return $sysBuilds;

        /* if the build has been released, replace build name with release name. */
        $releases = $this->dao->select('build, name')->from(TABLE_RELEASE)
            ->where('build')->in(array_keys($builds))
            ->beginIF($branch)->andWhere('branch')->in("0,$branch")->fi()
            ->andWhere('deleted')->eq(0)
            ->fetchPairs();
        foreach($releases as $buildID => $releaseName) $builds[$buildID] = $releaseName;

        return $sysBuilds + $builds;
    }

    /**
     * Get builds of a product in pairs. 
     * 
     * @param  mix    $products     int|array
     * @param  string $params       noempty|notrunk, can be a set of them
     * @access public
     * @return string
     */
    public function getProductBuildPairs($products, $branch = 0, $params = 'noterminate, nodone', $replace = true)
    {
        $sysBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => $this->lang->trunk);

        $productBuilds = $this->dao->select('t1.id, t1.name, t1.project, t2.status as projectStatus, t3.id as releaseID, t3.status as releaseStatus, t4.name as branchName')->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_RELEASE)->alias('t3')->on('t1.id = t3.build')
            ->leftJoin(TABLE_BRANCH)->alias('t4')->on('t1.branch = t4.id')
            ->where('t1.product')->in($products)
            ->beginIF($branch)->andWhere('t1.branch')->in("0,$branch")->fi()
            ->andWhere('t1.deleted')->eq(0)
            ->orderBy('t1.date desc, t1.id desc')->fetchAll('id');

        /* Set builds and filter done projects and terminate releases. */
        $builds = array();
        foreach($productBuilds as $key => $build)
        {
            if(empty($build->releaseID) and (strpos($params, 'nodone') !== false) and ($build->projectStatus === 'done')) continue;
            if((strpos($params, 'noterminate') !== false) and ($build->releaseStatus === 'terminate')) continue;
            $builds[$key] = ((strpos($params, 'withbranch') !== false and $build->branchName) ? $build->branchName . '/' : '') . $build->name;
        }

        if(!$builds) return $sysBuilds;

        /* if the build has been released and replace is true, replace build name with release name. */
        if($replace)
        {
            $releases = $this->dao->select('build, name')->from(TABLE_RELEASE)
                ->where('build')->in(array_keys($builds))
                ->andWhere('product')->in($products)
                ->beginIF($branch)->andWhere('branch')->in("0,$branch")->fi()
                ->andWhere('deleted')->eq(0)
                ->fetchPairs();
            foreach($releases as $buildID => $releaseName) $builds[$buildID] = ((strpos($params, 'withbranch') !== false and $productBuilds[$buildID]->branchName) ? $productBuilds[$buildID]->branchName . '/' : '') . $releaseName;
        }

        return $sysBuilds + $builds;
    }

    /**
     * Get last build.
     * 
     * @param  int    $projectID 
     * @access public
     * @return bool | object
     */
    public function getLast($projectID)
    {
        return $this->dao->select('id, name')->from(TABLE_BUILD) 
            ->where('project')->eq((int)$projectID)
            ->orderBy('date DESC,id DESC')
            ->limit(1)
            ->fetch();
    }

    /**
     * Create a build
     * 
     * @param  int    $projectID 
     * @access public
     * @return void
     */
    public function create($projectID)
    {
        $build = new stdclass();
        $build->stories = '';
        $build->bugs    = '';

        $build = fixer::input('post')
            ->setDefault('product', 0)
            ->setDefault('branch', 0)
            ->add('project', (int)$projectID)
            ->stripTags($this->config->build->editor->create['id'], $this->config->allowedTags)
            ->remove('resolvedBy,allchecker,files,labels,uid')
            ->get();

        $build = $this->loadModel('file')->processImgURL($build, $this->config->build->editor->create['id'], $this->post->uid);
        $this->dao->insert(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->create->requiredFields, 'notempty')
            ->check('name', 'unique', "product = {$build->product} AND branch = {$build->branch} AND deleted = '0'")
            ->exec();
        if(!dao::isError())
        {
            $buildID = $this->dao->lastInsertID();
            $this->file->updateObjectID($this->post->uid, $buildID, 'build');
            $this->file->saveUpload('build', $buildID);
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
        $oldBuild = $this->dao->select('*')->from(TABLE_BUILD)->where('id')->eq((int)$buildID)->fetch();
        $build    = fixer::input('post')->stripTags($this->config->build->editor->edit['id'], $this->config->allowedTags)
            ->remove('allchecker,resolvedBy,files,labels,uid')
            ->get();
        if(!isset($build->branch)) $build->branch = $oldBuild->branch;

        $build = $this->loadModel('file')->processImgURL($build, $this->config->build->editor->edit['id'], $this->post->uid);
        $this->dao->update(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$buildID)
            ->check('name', 'unique', "id != $buildID AND product = {$build->product} AND branch = {$build->branch} AND deleted = '0'")
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
                if(isset($_POST['resolvedBy'][$key]))$resolvedPairs[$bugID] = $data->resolvedBy[$key];
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
            $bug->resolvedBuild  = $build->name;
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

        $build->stories .= ',' . join(',', $this->post->stories);
        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();
        foreach($this->post->stories as $storyID)
        {
            $this->loadModel('action')->create('story', $storyID, 'linked2build', '', $buildID);
        }
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
        $this->dao->update(TABLE_BUILD)->set('stories')->eq($build->stories)->where('id')->eq((int)$buildID)->exec();
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfrombuild', '', $buildID);
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
        foreach($this->post->unlinkStories as $unlinkStoryID)
        {
            $this->loadModel('action')->create('story', $unlinkStoryID, 'unlinkedfrombuild', '', $buildID);
        }
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

        $build->bugs .= ',' . join(',', $this->post->bugs);
        $this->updateLinkedBug($build);
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();
        foreach($this->post->bugs as $bugID)
        {
            $this->loadModel('action')->create('bug', $bugID, 'linked2bug', '', $buildID);
        }
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
        $this->dao->update(TABLE_BUILD)->set('bugs')->eq($build->bugs)->where('id')->eq((int)$buildID)->exec();
        $this->loadModel('action')->create('bug', $bugID, 'unlinkedfrombuild', '', $buildID);
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
        foreach($this->post->unlinkBugs as $unlinkBugID)
        {
            $this->loadModel('action')->create('bug', $unlinkBugID, 'unlinkedfrombuild', '', $buildID);
        }
    }
}
