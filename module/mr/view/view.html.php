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
<?php elseif(empty($rawMR) or !isset($rawMR->id)): ?>
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
    <?php echo html::a($this->createLink('mr', 'browse'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'"); ?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $MR->id ?></span>
      <span class="text" title='<?php echo $MR->title; ?>'><?php echo  $MR->title; ?></span>
      <span class="text" title='<?php echo $MR->title; ?>' style='color: blue'><?php echo html::a($rawMR->web_url, $lang->mr->viewInGitlab, "_blank", "class='btn btn-link btn-active-text' style='color: blue'"); ?></span>
    </div>
  </div>
</div>

<div id="mainContent" class="main-content">
  <div class='tabs' id='tabsNav'>
    <ul class='nav nav-tabs'>
      <li class='active'><?php echo html::a('###', $lang->mr->overview);?></li>
      <li><?php echo html::a(inlink('diff', "mr={$MR->id}"), $lang->mr->viewDiff);?></li>
      <li><?php echo html::a(inlink('link', "mr={$MR->id}") . "#story", html::icon($lang->icons['story'], 'text-info') . ' ' . $lang->productplan->linkedStories);?></a></li>
      <li><?php echo html::a(inlink('link', "mr={$MR->id}") . "#bugs",  html::icon($lang->icons['bug'], 'text-info') . ' ' . $lang->productplan->linkedBugs);?></a></li>
      <li><?php echo html::a(inlink('link', "mr={$MR->id}") . "#tasks", html::icon($lang->icons['todo'], 'text-info') . ' ' . $lang->mr->linkedTasks);?></a></li>
    </ul>
    <div class='tab-content main-row'>
      <div class="main-col col-8">
        <div class="cell">
          <div class="detail">
            <div class="detail-content">

              <table class="table table-content">
                <thead>
                  <tr>
                    <th colspan='2'>
                      <span><?php echo $lang->mr->from . html::a($sourceProjectURL, $sourceProjectName . ":" . $MR->sourceBranch, "_blank", "class='btn btn-link btn-active-text' style='color: blue'") . $lang->mr->to . html::a($targetProjectURL, $targetProjectName . ":" . $MR->targetBranch, "_blank", "class='btn btn-link btn-active-text' style='color: blue'"); ?></span>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th class="w-100px"><?php echo $lang->mr->status;?></th>
                    <td><?php echo zget($lang->mr->statusList, $MR->status);?></td>
                  </tr>
                  <?php if(isset($rawMR->head_pipeline->status)): ?>
                  <tr>
                    <th><?php echo "{$lang->mr->pipeline}{$lang->mr->status}";?></th>
                    <td><?php echo zget($lang->mr->pipelineStatus, $rawMR->head_pipeline->status, $lang->mr->pipelineUnknown); ?></td>
                  </tr>
                  <?php endif; ?>
                  <tr>
                    <th><?php echo $lang->mr->mergeStatus; ?></th>
                    <?php if(empty($rawMR->changes_count)):?>
                    <td>
                      <?php echo $lang->mr->cantMerge; ?>
                      <code class=''><?php echo $lang->mr->noChanges;?></code>
                    </td>
                    <?php else:?>
                    <td><?php echo zget($lang->mr->mergeStatusList, $rawMR->merge_status);?></td>
                    <?php endif;?>
                   </tr>
                   <tr>
                     <th><?php echo $lang->mr->MRHasConflicts; ?></th>
                     <td><?php echo ($rawMR->has_conflicts ? $lang->mr->hasConflicts : $lang->mr->hasNoConflict);?></td>
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

        <?php if($rawMR->state == 'opened'): ?>
        <div class="cell"><?php echo sprintf($lang->mr->commandDocument, $httpRepoURL, $MR->sourceBranch, $branchPath, $MR->targetBranch, $branchPath, $MR->targetBranch); ?></div>
        <?php endif; ?>

        <div class='main-actions'>
          <div class="btn-toolbar">
            <?php common::printBack(inlink('browse', '')); ?>
            <?php if($rawMR->state == 'opened' and !$rawMR->has_conflicts) echo html::a(inlink('accept', "mr=$MR->id"), '<i class="icon icon-flow"></i> ' . $lang->mr->acceptMR, '', "id='mergeButton' class='btn'"); ?>
            <?php if($rawMR->state == 'opened'): ?>
              <?php if($rawMR->has_conflicts or $MR->compileStatus != 'success'):?>
              <?php echo html::a(inlink('approval', "mr=$MR->id&action=approve&onlybody=yes"), '<i class="icon icon-ok"></i> ' . $lang->mr->approve, '', "id='mergeButton' class='btn iframe showinonlybody' disabled"); ?>
              <?php else:?>
              <?php echo html::a(inlink('approval', "mr=$MR->id&action=approve&onlybody=yes"), '<i class="icon icon-ok"></i> ' . $lang->mr->approve, '', "id='mergeButton' class='btn iframe showinonlybody'"); ?>
              <?php endif;?>
              <?php echo html::a(inlink('approval', "mr=$MR->id&action=reject&onlybody=yes"), '<i class="icon icon-bug"></i> ' . $lang->mr->reject, '', "id='mergeButton' class='btn iframe showinonlybody'"); ?>
              <?php echo html::a(inlink('close', "mr=$MR->id"), '<i class="icon icon-close"></i> ' . $lang->mr->close, '', "id='mergeButton' class='btn'"); ?>
              <?php echo html::a(inlink('edit', "mr=$MR->id"), '<i class="icon icon-edit"></i> ' . str_replace($lang->mr->common, '', $lang->mr->edit), '', "id='mergeButton' class='btn'"); ?>
            <?php endif;?>
            <?php if($rawMR->state == 'closed') echo html::a(inlink('reopen', "mr=$MR->id"), '<i class="icon icon-restart"></i> ' . $lang->mr->reopen, '', "id='mergeButton' class='btn'"); ?>
            <?php echo html::a(inlink('delete', "mr=$MR->id"), '<i class="icon icon-trash"></i> ' . str_replace($lang->mr->common, '', $lang->mr->delete), '', "id='mergeButton' class='btn'"); ?>
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
                    <th class="w-90px"><?php echo $lang->job->common;?></th>
                    <td><?php echo $compile->name;?></td>
                  </tr>
                  <tr>
                    <th><?php echo $lang->compile->atTime;?></th>
                    <td><?php echo $compileJob ? $compileJob->lastExec : $lang->mr->compileUnexecuted;?></td>
                  </tr>
                  <?php if($compileJob):?>
                  <tr>
                    <th><?php echo $lang->compile->result;?></th>
                    <td>
                      <?php echo zget($lang->compile->statusList, $compile->status);?> &nbsp;&nbsp;
                      <?php echo html::a($this->createLink('job', 'view', "jobID=$compileJob->id&compileID=$compile->id&onlybody=yes"), "<i class='icon icon-search'>{$lang->compile->logs}</i>", "", "class='iframe'");?>
                    </td>
                  </tr>
                 <?php endif;?>
                 </tbody>
              </table>
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
