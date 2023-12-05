<?php

/**
 * The create view file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Guodong
 * @package     mr
 * @version     $Id: create.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'misc/base64.js');?>
<?php js::set('hosts', $hosts);?>
<?php js::set('repo', $repo);?>
<?php js::set('mrLang', $lang->mr);?>
<?php js::set('branchPrivs', array());?>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->mr->create;?></h2>
      </div>
      <form id='mrForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->mr->server;?></th>
            <td class='required'><?php echo html::select('hostID', array('') + $hostPairs, zget($repo, 'gitService', ''), "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th style="white-space: nowrap;"><?php echo $lang->mr->sourceProject;?></th>
            <td>
              <div class='input-group required'>
                <?php echo html::select('sourceProject', array(''), '', "class='form-control chosen'");?>
                <span class='input-group-addon fix-border branch-btn'><?php echo $lang->mr->sourceBranch ?></span>
                <?php echo html::select('sourceBranch', array(''), '', "class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th style="white-space: nowrap;"><?php echo $lang->mr->targetProject;?></th>
            <td>
              <div class='input-group required'>
                <?php echo html::select('targetProject', array(''), '', "class='form-control chosen'");?>
                <span class='input-group-addon fix-border branch-btn'><?php echo $lang->mr->targetBranch ?></span>
                <?php echo html::select('targetBranch', array(''), '', "class='form-control chosen'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->title;?></th>
            <td class='required'><?php echo html::input('title', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->description;?></th>
            <td colspan='1'><?php echo html::textarea('description', '', "rows='3' class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->devops->repo;?></th>
            <td colspan='1' class='required'><?php echo html::select('repoID', array(''), '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->removeSourceBranch;?></th>
            <td colspan='1'>
              <div class="checkbox-primary" title="<?php echo $lang->mr->notDelbranch;?>">
                <input type="checkbox" name="removeSourceBranch" value="1" id="removeSourceBranch">
                <label for="removeSourceBranch"></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->needCI;?></th>
            <td colspan='1'>
              <div class="checkbox-primary">
                <input type="checkbox" name="needCI" value="1" id="needCI">
                <label for="needCI"></label>
              </div>
            </td>
          </tr>
          <tr class='hidden'>
            <th><?php echo $lang->job->common;?></th>
            <td colspan='1' class='required'><?php echo html::select('jobID', array(''), '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->squash;?></th>
            <td colspan='1'>
              <div class="checkbox-primary">
                <input type="checkbox" name="squash" value="1" id="squash">
                <label for="squash"></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->assignee;?></th>
            <td><?php echo html::select('assignee', $users, '', "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browse', ""), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
            <td></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
