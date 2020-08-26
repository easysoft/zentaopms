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
    <div class="page-title">
      <strong>
        <?php
        echo html::a($this->repo->createLink('browse', "repoID=$repoID"), $repo->name);
        if(!empty($path))
        {
            $paths    = explode('/', $path);
            $postPath = '';
            foreach($paths as $pathName)
            {
                $postPath .= $pathName . '/';
                echo '/' . ' ' . html::a($this->repo->createLink('browse', "repoID=$repoID", "path=" . $this->repo->encodePath($postPath) . "&revision=$revision"), trim($pathName, '/'));
            }
        }
        ?>
      </strong>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <span class='last-sync-time'><?php echo $lang->repo->notice->lastSyncTime . $cacheTime?></span>
    <?php echo html::a($this->repo->createLink('browse', "repoID=$repoID&path=&revision=$revision&refresh=1", "path=" . $this->repo->encodePath($path)), "<i class='icon icon-refresh'></i> ". $lang->refresh, '', "class='btn btn-primary'");?>
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
          $link = $info->kind == 'dir' ? $this->repo->createLink('browse', "repoID=$repoID", "path=" . $this->repo->encodePath($infoPath)) : $this->repo->createLink('view', "repoID=$repoID&entry=", 'entry=' . $this->repo->encodePath($infoPath));
          echo html::a($link, $info->name, '', "title='{$info->name}'");
          ?>
          </td>
          <td align='center'><?php echo $repo->SCM == 'Git' ? substr($info->revision, 0, 10) : $info->revision;?></td>
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
