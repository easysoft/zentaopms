<?php
/**
 * The browse view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     repo
 * @version     $Id: browse.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <div class='btn-group'>
      <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;"><span class='text' title='<?php echo $repo->name;?>'><?php echo $repo->name;?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
        foreach($repos as $id => $repoName)
        {
            echo "<li>" . html::a($this->createLink('repo', 'browse', "repoID=$id&branchID=&objectID=$objectID"), $repoName, '', "title='{$repoName}' class='text-ellipsis' data-app='{$app->openApp}'") . "</li>";
        }
        ?>
      </ul>
    </div>
    <?php if(!empty($branches)):?>
    <div class='btn-group'>
      <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;"><span class='text' title='<?php echo $branches[$branchID];?>'><?php echo $branches[$branchID];?></span> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
        foreach($branches as $id => $branchName)
        {
            echo "<li>" . html::a($this->createLink('repo', 'browse', "repoID=$repoID&branchID=$id&objectID=$objectID"), $branchName, '', "title='{$branchName}' class='text-ellipsis' data-app='{$app->openApp}'") . "</li>";
        }
        ?>
      </ul>
    </div>
    <?php endif;?>
    <div class="page-title">
      <strong>
        <?php
        echo html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=$branchID&objectID=$objectID"), $repo->name, '', "data-app='{$app->openApp}'");
        $paths= explode('/', $path);
        $fileName = array_pop($paths);
        $postPath = '';
        foreach($paths as $pathName)
        {
            $postPath .= $pathName . '/';
            echo '/' . ' ' . html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=$branchID&objectID=$objectID&path=" . $this->repo->encodePath($postPath)), trim($pathName, '/'), '', "data-app='{$app->openApp}'");
        }
        echo '/' . ' ' . $fileName;
        ?>
      </strong>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <span class='last-sync-time'><?php echo $lang->repo->notice->lastSyncTime . $cacheTime?></span>
    <?php echo html::a($this->repo->createLink('browse', "repoID=$repoID&branchID=$branchID&objectID=$objectID&path=" . $this->repo->encodePath($path) . "&revision=$revision&refresh=1"), "<i class='icon icon-refresh'></i> " . $lang->refresh, '', "class='btn btn-primary' data-app={$app->openApp}");?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="main-col main-table">
    <table class='table table-fixed'>
      <thead>
        <tr>
          <th width='30'></th>
          <th style="min-width: 150px;"><?php echo $lang->repo->name?></th>
          <th width='80' class='text-center'><?php echo $lang->repo->revisions?></th>
          <th width='80'><?php echo $lang->repo->time?></th>
          <th width='120'><?php echo $lang->repo->committer?></th>
          <th><?php echo $lang->repo->comment?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($infos as $info):?>
        <?php if(empty($info->name)) continue;?>
        <tr>
          <td class="icon">
            <span class="<?php echo $info->kind == 'dir' ? 'directory' : 'file';?> mini-icon"></span>
          </td>
          <td>
          <?php
          $infoPath = trim($path . '/' . $info->name, '/');
          $link = $info->kind == 'dir' ? $this->repo->createLink('browse', "repoID=$repoID&branchID=$branchID&objectID=$objectID&path=" . $this->repo->encodePath($infoPath)) : $this->repo->createLink('view', "repoID=$repoID&objectID=$objectID&entry=" . $this->repo->encodePath($infoPath));
          echo html::a($link, $info->name, '', "title='{$info->name}' data-app={$app->openApp}");
          ?>
          </td>
          <td align='center'><?php echo $repo->SCM == 'Subversion' ? $info->revision : substr($info->revision, 0, 10);?></td>
          <td><?php echo substr($info->date, 0, 10)?></td>
          <td><?php echo $info->committer?></td>
          <?php $comment = htmlspecialchars($info->comment, ENT_QUOTES);?>
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
