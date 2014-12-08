<?php
/**
 * The model file of build module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
        $build = $this->dao->select('t1.*, t2.name as projectName, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
            ->where('t1.id')->eq((int)$buildID)
            ->fetch();
        if(!$build) return false;

        $build->files = $this->loadModel('file')->getByObject('build', $buildID);
        if($setImgSize) $build->desc = $this->file->setImgSize($build->desc);
        return $build;
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
        return $this->dao->select('t1.*, t2.name as projectName, t3.name as productName')
            ->from(TABLE_BUILD)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->leftJoin(TABLE_PRODUCT)->alias('t3')->on('t1.product = t3.id')
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
    public function getProjectBuildPairs($projectID, $productID, $params = '')
    {
        $sysBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => 'Trunk');

        $builds = $this->dao->select('id,name')->from(TABLE_BUILD)
            ->where('project')->eq((int)$projectID)
            ->beginIF($productID)->andWhere('product')->eq((int)$productID)->fi()
            ->andWhere('deleted')->eq(0)
            ->orderBy('date desc, id desc')->fetchPairs();
        if(!$builds) return $sysBuilds;
        $releases = $this->dao->select('build,name')->from(TABLE_RELEASE)
            ->where('build')->in(array_keys($builds))
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
    public function getProductBuildPairs($products, $params = '')
    {
        $sysBuilds = array();
        if(strpos($params, 'noempty') === false) $sysBuilds = array('' => '');
        if(strpos($params, 'notrunk') === false) $sysBuilds = $sysBuilds + array('trunk' => 'Trunk');

        $productBuilds = $this->dao->select('id,name,project')->from(TABLE_BUILD)
            ->where('product')->in($products)
            ->andWhere('deleted')->eq(0)
            ->orderBy('date desc, id desc')->fetchAll('id');
        $releases = $this->dao->select('build,name,deleted')->from(TABLE_RELEASE)
           ->where('product')->in($products)
           ->fetchAll('build');

        $builds = array();
        foreach($productBuilds as $key => $build)
        {
            if($build->project) 
            {
                $builds[$key] = isset($releases[$key]) ? $releases[$key]->name : $build->name;
            }
            else if(isset($releases[$key]) and !$releases[$key]->deleted)
            {
                $builds[$key] = $releases[$key]->name; 
            }
        }

        if(!$builds) return $sysBuilds;
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
            ->orderBy('date DESC')
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
            ->add('project', (int)$projectID)
            ->stripTags($this->config->build->editor->create['id'], $this->config->allowedTags)
            ->remove('resolvedBy,allchecker,files,labels')
            ->get();

        $this->dao->insert(TABLE_BUILD)->data($build)->autoCheck()->batchCheck($this->config->build->create->requiredFields, 'notempty')->check('name', 'unique', "product = {$build->product}")->exec();
        if(!dao::isError())
        {
            $buildID = $this->dao->lastInsertID();
            $this->loadModel('file')->saveUpload('build', $buildID);
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
        $oldBuild = $this->getByID($buildID);
        $build = fixer::input('post')
            ->stripTags($this->config->build->editor->edit['id'], $this->config->allowedTags)
            ->remove('allchecker,resolvedBy,files,labels')
            ->get();

        $this->dao->update(TABLE_BUILD)->data($build)
            ->autoCheck()
            ->batchCheck($this->config->build->edit->requiredFields, 'notempty')
            ->where('id')->eq((int)$buildID)
            ->check('name','unique', "id != $buildID AND product = {$build->product}")
            ->exec();
        if(!dao::isError()) return common::createChanges($oldBuild, $build);
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
        $now  = helper::now();

        $resolvedPairs = array();
        if(isset($_POST['bugs']))
        {
            foreach($this->post->bugs as $key => $bugID)
            {
                if(isset($_POST['resolvedBy'][$key]))$resolvedPairs[$bugID] = $this->post->resolvedBy[$key];
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
    }
}
