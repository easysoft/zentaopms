<?php
/**
 * The model file of release module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: model.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php
class projectreleaseModel extends model
{
    /**
     * Get release by id.
     *
     * @param  int    $releaseID
     * @param  bool   $setImgSize
     * @access public
     * @return object
     */
    public function getByID($releaseID, $setImgSize = false)
    {
        $release = $this->dao->select('t1.*, t2.name as productName, t2.type as productType')
            ->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.id')->eq((int)$releaseID)
            ->orderBy('t1.id DESC')
            ->fetch();
        if(!$release) return false;

        $release->project = trim($release->project, ',');
        $release->branch  = trim($release->branch, ',');
        $release->build   = trim($release->build, ',');

        $release->branches = array();
        $branchIdList = explode(',', $release->branch);
        foreach($branchIdList as $branchID) $release->branches[$branchID] = $branchID;

        $this->loadModel('file');
        $release = $this->file->replaceImgURL($release, 'desc');
        $release->files      = $this->file->getByObject('release', $releaseID);
        $release->buildInfos = $this->dao->select('id,project,product,execution,name,scmPath,filePath')->from(TABLE_BUILD)->where('id')->in($release->build)->fetchAll('id');
        if(empty($release->files))$release->files = $this->file->getByObject('build', $release->build);
        if($setImgSize) $release->desc = $this->file->setImgSize($release->desc);
        return $release;
    }

    /**
     * Get list of releases.
     *
     * @param  int    $projectID
     * @param  string $type
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getList($projectID, $type = 'all', $orderBy = 't1.date_desc', $pager = null)
    {
        $releases = $this->dao->select('t1.*, t2.name as productName, t2.type as productType')->from(TABLE_RELEASE)->alias('t1')
            ->leftJoin(TABLE_PRODUCT)->alias('t2')->on('t1.product = t2.id')
            ->where('t1.deleted')->eq(0)
            ->andWhere("FIND_IN_SET($projectID, t1.project)")
            ->beginIF($type != 'all' && $type != 'review')->andWhere('t1.status')->eq($type)->fi()
            ->beginIF($type == 'review')->andWhere("FIND_IN_SET('{$this->app->user->account}', t1.reviewers)")->fi()
            ->orderBy($orderBy)
            ->page($pager)
            ->fetchAll();

        $buildIdList   = array();
        $productIdList = array();
        foreach($releases as $release)
        {
            $release->project = trim($release->project, ',');
            $release->branch  = trim($release->branch, ',');
            $release->build   = trim($release->build, ',');

            $buildIdList = array_merge($buildIdList, explode(',', $release->build));
            $productIdList[$release->product] = $release->product;
        }

        $branchGroup = $this->loadModel('branch')->getByProducts($productIdList);
        $builds      = $this->dao->select("id,project,product,branch,execution,name,scmPath,filePath")->from(TABLE_BUILD)->where('id')->in(array_unique($buildIdList))->fetchAll('id');
        foreach($releases as $release)
        {
            $branchName = '';
            if(isset($branchGroup[$release->product]))
            {
                $branches = $branchGroup[$release->product];
                foreach(explode(',', $release->branch) as $releaseBranch)
                {
                    if($releaseBranch == '') continue;
                    $branchName .= zget($branches, $releaseBranch, '');
                    $branchName .= ',';
                }
                $branchName = trim($branchName, ',');
            }
            $release->branchName = $branchName;

            $release->buildInfos = array();
            foreach(explode(',', $release->build) as $buildID)
            {
                if(empty($buildID)) continue;

                $build      = $builds[$buildID];
                $branchName = '';
                if(isset($branchGroup[$build->product]))
                {
                    $branches = $branchGroup[$build->product];
                    foreach(explode(',', $build->branch) as $buildBranch)
                    {
                        if($buildBranch == '') continue;
                        $branchName .= zget($branches, $buildBranch, '');
                        $branchName .= ',';
                    }
                    $branchName = trim($branchName, ',');
                }
                $build->branchName = $branchName;
                $release->buildInfos[$buildID] = $build;
            }
        }
        return $releases;
    }

    /**
     * Get last release.
     *
     * @param  int    $projectID
     * @access public
     * @return bool | object
     */
    public function getLast($projectID)
    {
        $project = (int)$projectID;
        return $this->dao->select('id, name')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere("FIND_IN_SET($projectID, project)")
            ->orderBy('date DESC')
            ->limit(1)
            ->fetch();
    }

    /**
     * Get released builds from project.
     *
     * @param  int    $projectID
     * @access public
     * @return array
     */
    public function getReleasedBuilds($projectID)
    {
        $releases = $this->dao->select('shadow,build')->from(TABLE_RELEASE)
            ->where('deleted')->eq(0)
            ->andWhere("FIND_IN_SET($projectID, project)")
            ->fetchAll();

        $buildIdList = array();
        foreach($releases as $release)
        {
            $buildIdList   = array_merge($buildIdList, explode(',', trim($release->build, ',')));
            $buildIdList[] = $release->shadow;
        }
        return $buildIdList;
    }

    /**
     * Link stories
     *
     * @param  int    $releaseID
     * @access public
     * @return void
     */
    public function linkStory($releaseID)
    {
        $release = $this->getByID($releaseID);
        $product = $this->loadModel('product')->getByID($release->product);

        foreach($this->post->stories as $i => $storyID)
        {
            if(strpos(",{$release->stories},", ",{$storyID},") !== false) unset($_POST['stories'][$i]);
        }

        $release->stories .= ',' . join(',', $this->post->stories);
        $this->dao->update(TABLE_RELEASE)->set('stories')->eq($release->stories)->where('id')->eq((int)$releaseID)->exec();

        if($release->stories)
        {
            $this->loadModel('story');
            $this->loadModel('action');
            foreach($this->post->stories as $storyID)
            {
                /* Reset story stagedBy field for auto compute stage. */
                $this->dao->update(TABLE_STORY)->set('stagedBy')->eq('')->where('id')->eq($storyID)->exec();
                if($product->type != 'normal') $this->dao->update(TABLE_STORYSTAGE)->set('stagedBy')->eq('')->where('story')->eq($storyID)->andWhere('branch')->eq($release->branch)->exec();

                $this->story->setStage($storyID);

                $this->action->create('story', $storyID, 'linked2release', '', $releaseID);
            }
        }
    }

    /**
     * Unlink story
     *
     * @param  int    $releaseID
     * @param  int    $storyID
     * @access public
     * @return void
     */
    public function unlinkStory($releaseID, $storyID)
    {
        $release = $this->getByID($releaseID);
        $release->stories = trim(str_replace(",$storyID,", ',', ",$release->stories,"), ',');
        $this->dao->update(TABLE_RELEASE)->set('stories')->eq($release->stories)->where('id')->eq((int)$releaseID)->exec();
        $this->loadModel('action')->create('story', $storyID, 'unlinkedfromrelease', '', $releaseID);

        $this->loadModel('story')->setStage($storyID);
    }

    /**
     * Link bugs.
     *
     * @param  int    $releaseID
     * @param  string $type
     * @access public
     * @return void
     */
    public function linkBug($releaseID, $type = 'bug')
    {
        $release = $this->getByID($releaseID);

        $field = $type == 'bug' ? 'bugs' : 'leftBugs';

        foreach($this->post->bugs as $i => $bugID)
        {
            if(strpos(",{$release->$field},", ",{$bugID},") !== false) unset($_POST['bugs'][$i]);
        }

        $release->$field .= ',' . join(',', $this->post->bugs);
        $this->dao->update(TABLE_RELEASE)->set($field)->eq($release->$field)->where('id')->eq((int)$releaseID)->exec();

        $this->loadModel('action');
        foreach($this->post->bugs as $bugID) $this->action->create('bug', $bugID, 'linked2release', '', $releaseID);
    }

    /**
     * Judge btn is clickable or not.
     *
     * @param  int    $release
     * @param  string $action
     * @static
     * @access public
     * @return bool
     */
    public static function isClickable($release, $action)
    {
        $action = strtolower($action);

        if($action == 'notify') return $release->bugs or $release->stories;
        return true;
    }

    /**
     * Build project release action menu.
     *
     * @param  object $release
     * @param  string $type
     * @access public
     * @return string
     */
    public function buildOperateMenu($release, $type = 'view')
    {
        $function = 'buildOperate' . ucfirst($type) . 'Menu';
        return $this->$function($release);
    }

    /**
     * Build project release view action menu.
     *
     * @param  object $release
     * @access public
     * @return string
     */
    public function buildOperateViewMenu($release)
    {
        $canBeChanged = common::canBeChanged('projectrelease', $release);
        if($release->deleted || !$canBeChanged || isonlybody()) return '';

        $menu   = '';
        $params = "releaseID=$release->id";

        if(common::hasPriv('projectrelease', 'changeStatus', $release))
        {
            $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';
            $menu .= html::a(inlink('changeStatus', "$params&status=$changedStatus"), '<i class="icon-' . ($release->status == 'normal' ? 'pause' : 'play') . '"></i> ' . $this->lang->release->changeStatusList[$changedStatus], 'hiddenwin', "class='btn btn-link' title='{$this->lang->release->changeStatusList[$changedStatus]}'");
        }

        $menu .= "<div class='divider'></div>";
        $menu .= $this->buildFlowMenu('release', $release, 'view', 'direct');
        $menu .= "<div class='divider'></div>";

        $editClickable   = $this->buildMenu('projectrelease', 'edit',   $params, $release, 'view', '', '', '', '', '', '', false);
        $deleteClickable = $this->buildMenu('projectrelease', 'delete', $params, $release, 'view', '', '', '', '', '', '', false);
        if(common::hasPriv('projectrelease', 'edit')   and $editClickable)   $menu .= html::a(helper::createLink('projectrelease', 'edit', $params), "<i class='icon-common-edit icon-edit'></i> " . $this->lang->edit, '', "class='btn btn-link' title='{$this->lang->edit}'");
        if(common::hasPriv('projectrelease', 'delete') and $deleteClickable) $menu .= html::a(helper::createLink('projectrelease', 'delete', $params), "<i class='icon-common-delete icon-trash'></i> " . $this->lang->delete, '', "class='btn btn-link' title='{$this->lang->delete}' target='hiddenwin'");


        return $menu;
    }

    /**
     * Build project release browse action menu.
     *
     * @param  object $release
     * @access public
     * @return string
     */
    public function buildOperateBrowseMenu($release)
    {
        $canBeChanged = common::canBeChanged('projectrelease', $release);
        if(!$canBeChanged) return '';

        $menu          = '';
        $params        = "releaseID=$release->id";
        $changedStatus = $release->status == 'normal' ? 'terminate' : 'normal';

        if(common::hasPriv('projectrelease', 'linkStory')) $menu .= html::a(inlink('view', "$params&type=story&link=true"), '<i class="icon-link"></i> ', '', "class='btn' title='{$this->lang->release->linkStory}'");
        if(common::hasPriv('projectrelease', 'linkBug'))   $menu .= html::a(inlink('view', "$params&type=bug&link=true"),   '<i class="icon-bug"></i> ',  '', "class='btn' title='{$this->lang->release->linkBug}'");
        $menu .= $this->buildMenu('projectrelease', 'changeStatus', "$params&status=$changedStatus", $release, 'browse', $release->status == 'normal' ? 'pause' : 'play', 'hiddenwin', '', '', '',$this->lang->release->changeStatusList[$changedStatus]);
        $menu .= $this->buildMenu('projectrelease', 'edit',   $params, $release, 'browse');
        $menu .= $this->buildMenu('projectrelease', 'notify', $params, $release, 'browse', 'bullhorn', '', 'iframe', true);
        $clickable = $this->buildMenu('projectrelease', 'delete', $params, $release, 'browse', '', '', '', '', '', '', false);
        if(common::hasPriv('projectrelease', 'delete', $release))
        {
            $deleteURL = helper::createLink('projectrelease', 'delete', "$params&confirm=yes");
            $class = 'btn';
            if(!$clickable) $class .= ' disabled';
            $menu .= html::a("javascript:ajaxDelete(\"$deleteURL\", \"releaseList\", confirmDelete)", '<i class="icon-trash"></i>', '', "class='{$class}' title='{$this->lang->release->delete}'");
        }

        return $menu;
    }
}
