<?php
/**
 * The revision view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     repo
 * @version     $Id: revision.html.php $
 */
?>
<?php
session_start();
$_SESSION['repoList'] = $this->app->getURI(true);
session_write_close();
$pathInfo = empty($path) ? '' : '&path=' . $this->repo->encodePath($path);
$preDir   = empty($parentDir) ? $pathInfo : '&path=' . $this->repo->encodePath($parentDir);
$typeInfo = $type == 'file' ? '&type=file' : '';
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php if(!isonlybody()):?>
    <?php $browseLink = $app->session->revisionList != false ? $app->session->revisionList : $this->repo->createLink('browse', "repoID={$repoID}&branchID=" . helper::safe64Encode(base64_encode($branchID)) . "&objectID=$objectID{$preDir}");?>
    <?php echo html::a($browseLink, "<i class='icon icon-back'></i> " . $lang->goback, '', "class='btn btn-link back-btn'");?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <?php echo $lang->repo->revisionA . ' ' . ($repo->SCM == 'Subversion' ? $revision : $this->repo->getGitRevisionName($revision, $log->commit));?>
    </div>
  </div>
</div>

<div id='mainContent' class='main-row'>
  <div class='main-col col-8'>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'>
          <span><?php echo $lang->repo->changes;?></span>
          <span class='pull-right compare-all'><?php if(common::hasPriv('repo', 'diff')) echo html::a($this->repo->createLink('diff', "repoID=$repoID&objectID=$objectID&entry=&fromRevision=$oldRevision&toRevision=$revision"), $lang->repo->diffAll, '', "data-app='{$app->tab}'");?></span>
        </div>
        <div class='detail-content'>
          <table class='table no-margin'>
            <?php foreach($changes as $path => $change):?>
            <tr>
              <td><?php echo "<span class='label label-info label-badge'>" . $change['action'] . '</span> ' . $path;?></td>
              <td class='w-80px text-center'><?php echo zget($change, 'view', '') . zget($change, 'diff', '');?></td>
            </tr>
            <?php endforeach;?>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class='side-col col-4'>
    <div class='cell'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->repo->info;?></div>
        <div class='detail-content'>
          <table class='table table-data'>
            <tr>
              <th class='w-80px'><?php echo $lang->repo->committer;?></th>
              <td><?php echo $log->committer;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->repo->revisionA;?></th>
              <td><?php echo substr($log->revision, 0, 10);?></td>
            </tr>
            <?php if($repo->SCM != 'Subversion'):?>
            <tr>
              <th><?php echo $lang->repo->commit;?></th>
              <td><?php echo $log->commit;?></td>
            </tr>
            <?php endif;?>
            <tr>
              <th><?php echo $lang->repo->comment;?></th>
              <td><?php echo $log->comment;?></td>
            </tr>
            <tr>
              <th><?php echo $lang->repo->time;?></th>
              <td><?php echo $log->time;?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php if(!isonlybody()):?>
<div id="mainActions" class='main-actions'>
  <nav class="container">
    <?php if(!empty($preAndNext->pre))  echo html::a($this->repo->createLink('revision', "repoID=$repoID&objectID=$objectID&revision={$preAndNext->pre}" . $pathInfo . $typeInfo, 'html'), "<i class='icon-pre icon-chevron-left'></i>", '', "data-app='{$app->tab}' id='prevPage' class='btn btn-info' title='{$preAndNext->pre}'");?>
    <?php if(!empty($preAndNext->next)) echo html::a($this->repo->createLink('revision', "repoID=$repoID&objectID=$objectID&revision={$preAndNext->next}" . $pathInfo . $typeInfo, 'html'), "<i class='icon-pre icon-chevron-right'></i>", '', "data-app='{$app->tab}' id='nextPage' class='btn btn-info' title='{$preAndNext->next}'");?>
  </nav>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
