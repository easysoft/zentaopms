<?php
/**
 * The edit view file of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Wang Yidong, Zhu Jinyong
 * @package     mr
 * @version     $Id: create.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('hostID', $MR->hostID);?>
<?php js::set('projectID', $MR->sourceProject);?>
<!-- If this mr is deleted in GitLab, then show this part to user. -->
<?php if(empty($rawMR) or !isset($rawMR->id)): ?>
  <div id='mainContent'>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->mr->notFound;?></span>
        <?php echo html::a($this->createLink('mr', 'browse'), "<i class='icon icon-plus'></i> " . $lang->mr->browse, '', "class='btn btn-info'");?>
      </p>
    </div>
  </div>
<?php die; endif;?>

<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <div class='center-block'>
      <div class='main-header'>
        <h2><?php echo $lang->mr->edit;?></h2>
      </div>
      <form id='mrForm' method='post' class='form-ajax'>
        <table class='table table-form'>
          <tr>
            <th><?php echo $lang->mr->server;?></th>
            <td><?php echo $this->loadModel('pipeline')->getByID($MR->hostID)->name;?></td>
          </tr>
          <tr>
             <th style="white-space: nowrap;"><?php echo $lang->mr->sourceProject;?></th>
             <td>
               <div>
                 <span class='fix-border text-left'>
                 <?php echo $host->type == 'gitlab' ? $this->loadModel('gitlab')->apiGetSingleProject($MR->hostID, $MR->sourceProject)->name_with_namespace : $MR->sourceProject;?>:
                 <?php echo $MR->sourceBranch;?>
                 </span>
               </div>
             </td>
          </tr>
          <tr>
             <th style="white-space: nowrap;"><?php echo $lang->mr->targetProject;?></th>
             <td>
               <div class='input-group'>
                 <?php if($MR->status == 'merged' or $MR->status == 'closed' or $host->type == 'gogs'):?>
                 <span class='fix-border text-left'>
                 <?php echo $host->type == 'gitlab' ? $this->loadModel('gitlab')->apiGetSingleProject($MR->hostID, $MR->targetProject)->name_with_namespace : $MR->targetProject;?>:
                 <?php echo $MR->targetBranch;?>
                 </span>
                 <?php else:?>
                 <span class='input-group-addon fix-border'>
                 <?php echo $host->type == 'gitlab' ? $this->loadModel('gitlab')->apiGetSingleProject($MR->hostID, $MR->targetProject)->name_with_namespace : $MR->targetProject;?>:
                 </span>
                 <?php echo html::select('targetBranch', $targetBranchList, $MR->targetBranch, "class='form-control chosen'");?>
                 <?php endif;?>
               </div>
             </td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->title;?></th>
            <td class='required'><?php echo html::input('title', $MR->title, "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->description;?></th>
            <td colspan='1'><?php echo html::textarea('description', $MR->description, "rows='3' class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->devops->repo;?></th>
            <td colspan='1' class='required'><?php echo html::select('repoID', $repoList, $MR->repoID, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->removeSourceBranch;?></th>
            <td colspan='1'>
              <?php
              $attr = '';
              if($MR->canDeleteBranch and $MR->removeSourceBranch == '1') $attr .= 'checked ';
              if(!$MR->canDeleteBranch) $attr .= 'disabled';
              ?>
              <div class="checkbox-primary" title="<?php echo $lang->mr->notDelbranch;?>">
                <input type="checkbox" <?php echo $attr;?> name="removeSourceBranch" value="1" id="removeSourceBranch">
                <label for="removeSourceBranch"></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->needCI;?></th>
            <td colspan='1'>
              <div class="checkbox-primary">
                <?php $checked = $MR->needCI == '1' ? 'checked' : '' ?>
                <input type="checkbox" <?php echo $checked;?> name="needCI" value="1" id="needCI">
                <label for="needCI"></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->job->common;?></th>
            <td colspan='1' class='required'><?php echo html::select('jobID', $jobList, $MR->jobID, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->squash;?></th>
            <td colspan='1'>
              <div class="checkbox-primary">
                <?php $checked = $MR->squash == '1' ? 'checked' : '' ?>
                <input type="checkbox" <?php echo $checked;?> name="squash" value="1" id="squash">
                <label for="squash"></label>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->mr->assignee;?></th>
            <td><?php echo html::select('assignee', $users, $assignee, "class='form-control chosen'");?></td>
          </tr>
          <tr>
            <td colspan='2' class='text-center form-actions'>
              <?php echo html::submitButton();?>
              <?php if(!isonlybody()) echo html::a(inlink('browse', ""), $lang->goback, '', 'class="btn btn-wide"');?>
            </td>
            <th></th>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
