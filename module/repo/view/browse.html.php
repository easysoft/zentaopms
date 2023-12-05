<?php
/**
 * The browse view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     repo
 * @version     $Id: browse.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('repoID',   $repoID);?>
<?php js::set('branch',   $branchID);?>
<?php js::set('lang',     $lang->repo);?>
<?php js::set('cloneUrl', $cloneUrl);?>
<?php js::set('syncedRF', $syncedRF);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php if(!empty($branchesAndTags)):?>
    <?php if($this->app->tab == 'project'):?>
    <div class='btn-group'>
    <?php echo $this->repo->getSwitcher($repoID);?>
    </div>
    <?php endif;?>
    <div class='btn-group'>
      <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;"><span class='text' title='<?php echo $branchesAndTags[$branchID];?>'><?php echo $branchesAndTags[$branchID];?></span> <span class='caret'></span></a>
      <div id='dropMenuBranch' class='dropdown-menu search-list' data-ride='searchList' data-url=''>
        <div class="input-control search-box has-icon-left has-icon-right search-example">
        <input type="search" class="form-control search-input" id="searchSource"/>
          <label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
          <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
        </div>
        <div class="table-row">
          <div class="table-col col-left">
            <div class="list-group" id="branchList">
              <ul class="tree tree-angles" data-ride="tree" data-idx="0">
                <li data-idx='branch' data-id='branch' class='has-list open in' style='cursor: pointer;'>
                  <i class='list-toggle icon'></i>
                  <div class='hide-in-search'><a class='text-muted not-list-item' title='<?php echo $lang->repo->branch;?>'><?php echo $lang->repo->branch;?></a></div>
                  <ul data-idx='branch'>
                    <?php
                    foreach($branches as $branchName)
                    {
                        $selected       = ($branchName == $branchID and $branchOrTag == 'branch') ? 'selected' : '';
                        $base64BranchID = helper::safe64Encode(base64_encode($branchName));
                        $branchLink     = $this->createLink('repo', 'browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID");
                        echo "<li data-idx='$branchName' data-id='branch-$branchName'><a href='$branchLink' data-app='{$app->tab}' id='branch-$branchName' class='$selected branch-or-tag text-ellipsis' title='$branchName' data-key='$branchName'>$branchName</a></li>";
                    }
                    ?>
                  </ul>
                </li>
                <li data-idx='tag' data-id='tag' class='has-list open in' style='cursor: pointer;'>
                  <i class='list-toggle icon'></i>
                  <div class='hide-in-search'><a class='text-muted not-list-item' title='<?php echo $lang->repo->tag;?>'><?php echo $lang->repo->tag;?></a></div>
                  <ul data-idx='tag'>
                    <?php
                    foreach($tags as $tagName)
                    {
                        $selected    = ($tagName == $branchID and $branchOrTag == 'tag') ? 'selected' : '';
                        $base64TagID = helper::safe64Encode(base64_encode($tagName));
                        $tagLink     = $this->createLink('repo', 'browse', "repoID=$repoID&branchID=$base64TagID&objectID=$objectID&path=&revision=HEAD&refresh=0&branchOrTag=tag");
                        echo "<li data-idx='$tagName' data-id='tag-$tagName'><a href='$tagLink' id='tag-$tagName' class='$selected branch-or-tag text-ellipsis' title='$tagName' data-key='$tagName' data-app='{$app->tab}'>$tagName</a></li>";
                    }
                    ?>
                  </ul>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif;?>
    <div class="page-title">
      <strong>
        <?php
        $base64BranchID = helper::safe64Encode(base64_encode($branchID));
        echo html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID"), $repo->name, '', "data-app='{$app->tab}'");
        $paths= explode('/', $path);
        $fileName = array_pop($paths);
        $postPath = '';
        foreach($paths as $pathName)
        {
            $postPath .= $pathName . '/';
            echo '/' . ' ' . html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID&path=" . $this->repo->encodePath($postPath)), trim($pathName, '/'), '', "data-app='{$app->tab}'");
        }
        echo '/' . ' ' . $fileName;
        ?>
      </strong>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <span class='last-sync-time'><?php echo $lang->repo->notice->lastSyncTime . $cacheTime?></span>
    <?php echo html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=" . $base64BranchID . "&objectID=$objectID&path=" . $this->repo->encodePath($path) . "&revision=$revision&refresh=1"), "<i class='icon icon-refresh'></i> " . $lang->refresh, '', "class='btn btn-primary' data-app={$app->tab}");?>
    <?php if(common::hasPriv('repo', 'downloadCode')): ?>
    <button type="button" class="btn btn-primary" data-toggle="popover" id="downloadCode" title="<?php echo $lang->repo->downloadCode;?>"><i class='icon icon-sm icon-download'></i> <?php echo $lang->repo->download;?> <i class='icon icon-sm icon-caret-down'></i></button>
    <?php endif;?>
    <?php if(common::hasPriv('repo', 'create') and $currentProject) echo html::a(helper::createLink('repo', 'create', "objectID=$objectID"), "<i class='icon icon-plus'></i> " . $this->lang->repo->createAction, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col main-table">
    <table class='table table-fixed'>
      <thead>
        <tr>
          <th class='c-name'><?php echo $lang->repo->name?></th>
          <th class='c-version'><?php echo $lang->repo->revisions?></th>
          <th class='c-date'><?php echo $lang->repo->time?></th>
          <th class='c-user'><?php echo $lang->repo->committer?></th>
          <th><?php echo $lang->repo->comment?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($infos as $info):?>
        <?php if(empty($info->name)) continue;?>
        <tr>
          <td>
            <span class="<?php echo $info->kind == 'dir' ? 'directory' : 'file';?> mini-icon"></span>
            <?php
            $infoPath = trim($path . '/' . $info->name, '/');
            $link = $info->kind == 'dir' ? $this->repo->createLink('browse', "repoID=$repoID&branchID=$base64BranchID&objectID=$objectID&path=" . $this->repo->encodePath($infoPath)) : $this->repo->createLink('view', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($infoPath));
            echo html::a($link, $info->name, '', "title='{$info->name}' data-app={$app->tab}");
            ?>
          </td>
          <td><?php echo $repo->SCM == 'Subversion' ? $info->revision : substr($info->revision, 0, 10);?></td>
          <td><?php echo substr($info->date, 0, 10)?></td>
          <td><?php echo $info->account?></td>
          <?php $comment = htmlSpecialString($info->comment, ENT_QUOTES);?>
          <td class='comment' title='<?php echo $comment?>'><?php echo $comment?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </div>
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-right"></i></div>
    <div class='side-body'><?php include 'ajaxsidecommits.html.php';?></div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
