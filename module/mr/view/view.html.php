<?php include '../../common/view/header.html.php'; ?>
<?php if(!isset($MR->id)): ?>
<div id='mainContent'>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->mr->notFound;?></span>
      <?php if(common::hasPriv('mr', 'create')): ?>
        <?php echo html::a($this->createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-info'"); ?>
      <?php endif; ?>
    </p>
  </div>
</div>
<?php /* If this mr is deleted in GitLab, then show this part to user. */?>
<?php elseif($MR->synced and (empty($rawMR) or !isset($rawMR->id))): ?>
<div id='mainContent'>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->mr->notFound; ?></span>
      <?php echo html::a($this->createLink('mr', 'browse'), "<i class='icon icon-plus'></i> " . $lang->mr->browse, '', "class='btn btn-info'"); ?>
    </p>
  </div>
</div>
<?php else: ?>
<div id='mainMenu' class='clearfix'>
  <div class="btn-toolbar pull-left">
    <?php if(!isonlybody()):?>
    <?php echo html::a($this->createLink('mr', 'browse'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'"); ?>
    <div class="divider"></div>
    <?php endif;?>
    <div class="page-title">
      <span class="label label-id"><?php echo $MR->id ?></span>
      <span class="text" title='<?php echo $MR->title; ?>'><?php echo  $MR->title; ?></span>
      <?php if($MR->synced):?>
      <span class="text" title='<?php echo $MR->title; ?>' style='color: blue'><?php echo html::a($rawMR->web_url, $lang->mr->viewInGit, "_blank", "class='btn btn-link btn-active-text' style='color: blue'"); ?></span>
      <?php endif;?>
    </div>
  </div>
</div>

<div class="main-content">
  <div class='tabs' id='tabsNav'>
    <ul class='nav nav-tabs'>
      <li class='active'><?php echo html::a('###', $lang->mr->view);?></li>
      <li><?php echo html::a(inlink('diff', "MRID={$MR->id}"), $lang->mr->viewDiff);?></li>
      <li><?php echo html::a(inlink('link', "MRID={$MR->id}&type=story"), html::icon($lang->icons['story'], 'text-primary') . ' ' . $lang->productplan->linkedStories);?></a></li>
      <li><?php echo html::a(inlink('link', "MRID={$MR->id}&type=bug"),   html::icon($lang->icons['bug'], 'text-red')   . ' ' . $lang->productplan->linkedBugs);?></a></li>
      <li><?php echo html::a(inlink('link', "MRID={$MR->id}&type=task"),  html::icon('todo', 'text-info')  . ' ' . $lang->mr->linkedTasks);?></a></li>
    </ul>
    <div class='tab-content main-row' id="mainContent">
      <div class="main-col col-8">
        <div class="cell">
          <div class="detail">
            <div class="detail-content">

              <table class="table table-content">
                <thead>
                  <tr>
                    <th colspan='2'>
                      <?php $sourceDisabled = ($MR->status == 'merged' and $MR->removeSourceBranch == '1') ? 'disabled' : '';?>
                      <span><?php echo $lang->mr->from . html::a($sourceProjectURL, $sourceProjectName . ":" . $MR->sourceBranch, "_blank", "class='btn btn-link btn-active-text' style='color: blue' $sourceDisabled") . $lang->mr->to . html::a($targetProjectURL, $targetProjectName . ":" . $MR->targetBranch, "_blank", "class='btn btn-link btn-active-text' style='color: blue'"); ?></span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th class="w-100px"><?php echo $lang->mr->status;?></th>
                    <td>
                    <?php if(!empty($MR->syncError) and $MR->synced === '0'):?>
                      <span class="text-danger"><?php echo $MR->syncError;?></span>
                    <?php else:?>
                      <?php echo zget($lang->mr->statusList, $MR->status);?>
                    <?php endif;?>
                    </td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->mr->mergeStatus; ?></th>
                    <?php if($MR->synced and empty($rawMR->changes_count)):?>
                    <td>
                      <?php echo $lang->mr->cantMerge; ?>
                      <?php if($MR->synced) echo '<code>' . $lang->mr->noChanges . '</code>';?>
                    </td>
                    <?php else:?>
                    <td><?php echo zget($lang->mr->mergeStatusList, !empty($rawMR->merge_status) ? $rawMR->merge_status : $MR->mergeStatus);?></td>
                    <?php endif;?>
                   </tr>
                   <tr>
                     <th><?php echo $lang->mr->MRHasConflicts; ?></th>
                     <?php $hasNoConflict = $MR->synced === '1' ? $rawMR->has_conflicts : (bool)$MR->hasNoConflict;?>
                     <td><?php echo ($hasNoConflict ? $lang->mr->hasConflicts : $lang->mr->hasNoConflict);?></td>
                  </tr>
                   <tr>
                     <th><?php echo $lang->mr->description;?></th>
                     <td>
                      <?php echo !empty($MR->description) ? $MR->description : $lang->noData; ?>
                     </td>
                  </tr>
                </tbody>
              </table>

            </div>
          </div>
        </div>

        <?php if($MR->synced and $rawMR->state == 'opened'): ?>
        <div class="cell"><?php echo sprintf($lang->mr->commandDocument, $httpRepoURL, $MR->sourceBranch, $branchPath, $MR->targetBranch, $branchPath, $MR->targetBranch); ?></div>
        <?php endif; ?>

        <?php if($this->app->getViewType() != 'xhtml'):?>
        <div class="cell"><?php include '../../common/view/action.html.php';?></div>
        <?php endif;?>

        <div class='main-actions'>
          <div class="btn-toolbar">
            <?php common::printBack(inlink('browse', '')); ?>
            <?php $acceptDisabled = ($MR->approvalStatus != 'approved' or (!empty($compile->id) and $compile->status != 'success')) ? ' disabled' : ''; ?>
            <?php if($MR->synced and $rawMR->state == 'opened' and !$rawMR->has_conflicts) common::printIcon('mr', 'accept', "mr=$MR->id", $MR, 'button', 'flow', 'hiddenwin', 'mergeButton btn', false, $acceptDisabled, $lang->mr->acceptMR);?>
            <?php if($MR->synced and $rawMR->state == 'opened'): ?>
              <?php if($rawMR->has_conflicts or (!empty($compile->id) and $compile->status != 'success') or $MR->approvalStatus == 'approved'):?>
              <?php common::printIcon('mr', 'approval', "MRID=$MR->id&action=approve", $MR, 'button', 'ok', 'hiddenwin', 'mergeButton', true, 'disabled', $lang->mr->approve);?>
              <?php else:?>
              <?php common::printIcon('mr', 'approval', "MRID=$MR->id&action=approve", $MR, 'button', 'ok', 'hiddenwin', 'mergeButton btn iframe showinonlybody', true, '', $lang->mr->approve);?>
              <?php endif;?>
              <?php common::printIcon('mr', 'approval', "MRID=$MR->id&action=reject", $MR, 'button', 'bug', 'hiddenwin', 'mergeButton btn iframe showinonlybody', true, ($MR->approvalStatus == 'rejected' ? 'disabled' : ''), $lang->mr->reject);?>
              <?php common::printIcon('mr', 'close', "MRID=$MR->id", $MR, 'button', 'off', 'hiddenwin', 'mergeButton');?>
              <?php common::printIcon('mr', 'edit', "MRID=$MR->id", $MR, 'button', 'edit');?>
            <?php endif;?>
            <?php if($MR->synced and $rawMR->state == 'closed') common::printIcon('mr', 'reopen', "mr=$MR->id", $MR, 'button', 'restart', 'hiddenwin', 'mergeButton'); ?>
            <?php if($projectOwner) common::printIcon('mr', 'delete', "MRID=$MR->id", $MR, 'button', 'trash', 'hiddenwin');?>
          </div>
        </div>
      </div>

      <div class="side-col col-4">
        <div class="cell">
          <details class="detail" open>
          <summary class="detail-title"><?php echo $lang->compile->job;?></summary>
            <div class="detail-content">
              <?php if($MR->compileID):?>
              <table class="table table-data">
                <tbody>
                  <tr>
                    <?php $class = in_array($this->app->getClientLang(), array('zh-cn','zh-tw')) ? 'w-90px' : 'w-100px'; ?>
                    <th class="<?php echo $class; ?>"><?php echo $lang->job->common;?></th>
                    <td><?php echo $compile->name;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->compile->atTime;?></th>
                    <td><?php echo $compile->createdDate;?></td>
                  </tr>
                  <?php if($compileJob and !empty($compileJob->id)):?>
                  <tr>
                    <th><?php echo $lang->compile->result;?></th>
                    <td>
                      <?php echo zget($lang->compile->statusList, $compile->status);?> &nbsp;&nbsp;
                      <?php echo html::a($this->createLink('job', 'view', "jobID=$compileJob->id&compileID=$compile->id", '', true), "<i class='icon icon-search'>{$lang->compile->logs}</i>", "", "class='iframe'");?>
                    </td>
                  </tr>
                 <?php endif;?>
                 </tbody>
              </table>
              <?php elseif($MR->needCI):?>
              <?php $compileUrl = $this->createLink('job', 'view', "jobID={$MR->jobID}");?>
              <div class='text-center'><?php echo html::a($compileUrl, $lang->compile->statusList[$MR->compileStatus], '_blank');?></div>
              <?php else:?>
              <div class='text-center'><?php echo $lang->mr->noCompileJob;?></div>
              <?php endif;?>
            </div>
          </details>
        </div>
      </div>
    </div>
  </div>
</div>

<?php endif; ?>
<script>
  /* Callback function for approval view. */
  function refresh() {
    location.reload();
  }
</script>
<?php include '../../common/view/footer.html.php'; ?>
