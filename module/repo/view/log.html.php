<?php
/**
 * The log view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     repo
 * @version     $Id: log.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('repoID', $repoID);?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php
    echo html::backButton("<i class='icon icon-back icon-sm'></i>" . $lang->goback, '', 'btn btn-link');
    echo '<div class="divider"></div>';
    ?>
    <div class="page-title">
      <strong>
      <?php
      echo html::a($this->repo->createLink('log', "repoID=$repoID&objectID=$objectID"), $repo->name, '', "data-app='{$app->openApp}'");
      $paths= explode('/', $entry);
      $fileName = array_pop($paths);
      $postPath = '';
      foreach($paths as $pathName)
      {
          $postPath .= $pathName . '/';
          echo '/' . ' ' . html::a($this->repo->createLink('log', "repoID=$repoID&ojbectID=$objectID&entry=" . $this->repo->encodePath($postPath)), trim($pathName, '/'), '', "data-app='{$app->openApp}'");
      }
      echo '/' . ' ' . $fileName;
      ?>
      </strong>
    </div>
  </div>
</div>

<div id="mainContent">
  <nav id="contentNav">
    <ul class="nav nav-default">
      <?php $encodeEntry = $this->repo->encodePath($entry);?>
      <li><a><?php echo $lang->repo->log;?></a></li>
      <li><?php echo html::a($this->repo->createLink('view', "repoID=$repoID&objectID=$objectID&entry=$encodeEntry&revision=$revision"), $lang->repo->view, '', "data-app='{$app->openApp}'");?></li>
      <?php if($info->kind == 'file'):?>
      <li><?php echo html::a($this->repo->createLink('blame', "repoID=$repoID&objectID=$objectID*&entry=$encodeEntry&revision=$revision"), $lang->repo->blame, '', "data-app='{$app->openApp}'");?></li>
      <li><?php echo html::a($this->repo->createLink('download', "repoID=$repoID&path=$encodeEntry&fromRevision=$revision"), $lang->repo->download, 'hiddenwin');?></li>
      <?php endif;?>
    </ul>
  </nav>
  <form id='logForm' class='main-table' data-ride='table' method='post'>
    <table class='table table-fixed' id='logList'>
      <thead>
        <tr>
          <th class='w-40px'></th>
          <th class='w-110px'><?php echo $lang->repo->revision?></th>
          <?php if($repo->SCM != 'Subversion'):?>
          <th class='w-90px'><?php echo $lang->repo->commit?></th>
          <?php endif;?>
          <th class='w-150px'><?php echo $lang->repo->date?></th>
          <th class='w-100px'><?php echo $lang->repo->committer?></th>
          <th><?php echo $lang->repo->comment?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($logs as $log):?>
        <tr>
          <td>
            <div class='checkbox-primary'>
              <input type='checkbox' name='revision[]' value="<?php echo $log->revision?>" />
              <label></label>
            </div>
          </td>
          <td class='versions'><?php echo html::a($this->repo->createLink('revision', "repoID=$repoID&objectID=$objectID&revision=" . $log->revision), substr($log->revision, 0, 10), '', "data-app='{$app->openApp}'");?></td>
          <?php if($repo->SCM != 'Subversion'):?>
          <td><?php echo $log->commit?></td>
          <?php endif;?>
          <td><?php echo $log->time;?></td>
          <td><?php echo $log->committer;?></td>
          <td class='comment'><?php echo $log->comment;?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'>
      <?php echo html::submitButton($lang->repo->diff, '', count($logs) < 2 ? 'disabled btn btn-primary' : 'btn btn-primary')?>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
