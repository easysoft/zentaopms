<?php
/**
 * The create view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     repo
 * @version     $Id: create.html.php $
 */
?>
<?php
include '../../common/view/header.html.php';
js::import($jsRoot  . '/zui/tabs/tabs.min.js');
js::import($jsRoot . 'misc/base64.js');
js::set('isonlybody', isonlybody());
js::set('entry', $entry);
js::set('repoID', $repoID);
js::set('repo', $repo);
js::set('revision', $revision);
js::set('branchID', $branchID);
js::set('file', $pathInfo);
js::set('openedFiles', array($entry));
js::set('urlParams', "repoID=$repoID&objectID=$objectID&entry=%s&revision=$revision&showBug=$showBug&encoding=$encoding");
$encodePath = $this->repo->encodePath($entry);
?>
<?php if(!isonlybody()):?>
<div id="mainContent" class="main-row fade">
  <?php $sideWidth = common::checkNotCN() ? '260' : '230';?>
  <div class="side-col" id="sidebar" style="width: <?php echo $sideWidth + 10;?>px;padding-top: <?php echo isonlybody() ? 22 : 0;?>px;">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell file-tree" style="width: <?php echo $sideWidth;?>px;" data-min-width="<?php echo $sideWidth;?>">
      <div id="filesTree" class="cell load-indicator <?php if(isonlybody()) echo 'pull-left';?>">
        <div class='btn-group' id='sourceSwapper'>
          <button data-toggle='dropdown' type='button' class='btn btn-link repo-select text-ellipsis' title='<?php echo $branchID;?>'>
            <span class='text version-name pull-left text-ellipsis'><?php echo $branchID;?></span>
            <span class='caret pull-right' style='margin-bottom: -1px'></span>
          </button>
          <div id='dropMenuSource' class='dropdown-menu search-list' data-ride='searchList' data-url=''>
            <div class="input-control search-box has-icon-left has-icon-right search-example">
            <input type="search" class="form-control search-input" id="searchSource" />
              <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
              <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
            </div>
            <div class="table-row">
              <div class="table-col col-left">
                <div class="list-group" id="branchList"></div>
              </div>
            </div>
          </div>
        </div>
        <?php echo $this->repo->getFileTree($repo);?>
      </div>
    </div>
  </div>
<?php endif;?>
  <div class="main-col repoCode main">
    <div class="content panel">
      <div class='btn-toolbar'>
        <?php if(!isonlybody()):?>
        <div class="btn btn-left pull-left"><i class="icon icon-chevron-left"></i></div>
        <?php if(common::hasPriv('repo', 'blame') or common::hasPriv('repo', 'download')):?>
        <div class="dropdown pull-right">
          <button class="btn" type="button" data-toggle="context-dropdown"><i class="icon icon-ellipsis-v icon-rotate-90"></i></button>
          <ul class="dropdown-menu">
            <?php
            if(common::hasPriv('repo', 'blame')) echo '<li>' . html::a($this->repo->createLink('blame', "repoID=$repoID&objectID=$objectID&entry=$encodePath&revision=$revision&encoding=$encoding"), '<i class="icon icon-blame"></i> ' . $lang->repo->blame, '', "data-app='{$app->tab}'") . '</li>';
            if(common::hasPriv('repo', 'download')) echo '<li>' . html::a($this->repo->createLink('download', "repoID=$repoID&path=$encodePath&fromRevision=$revision"), '<i class="icon icon-download"></i> ' . $lang->repo->download, 'hiddenwin') . '</li>';
            ?>
          </ul>
        </div>
        <?php endif;?>
        <div class="btn btn-right  pull-right"><i class="icon icon-chevron-right"></i></div>
        <?php endif;?>
        <div class='panel-title'>
          <div class="tabs w-10" id="fileTabs"></div>
        </div>
      </div>
    </div>
  </div>
<?php if(!isonlybody()):?>
</div>
<?php endif;?>
<a href='' class='iframe' data-width='90%' id='linkObject'></a>
<?php include '../../common/view/footer.html.php';?>
