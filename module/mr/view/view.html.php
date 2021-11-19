<?php include '../../common/view/header.html.php'; ?>
<?php if(!isset($MR->id)): ?>
  <div id='mainContent'>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->mr->notFound; ?></span>
        <?php if(common::hasPriv('mr', 'create')): ?>
          <?php echo html::a($this->createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-info'"); ?>
        <?php endif; ?>
      </p>
    </div>
  </div>
<!-- If this mr is deleted in GitLab, then show this part to user. -->
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
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('mr', 'browse'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'"); ?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $MR->id ?></span>
      <span class="text" title='<?php echo $MR->title; ?>'><?php echo  $MR->title; ?></span>
      <span class="text" title='<?php echo $MR->title; ?>' style='color: blue'><?php echo html::a($rawMR->web_url, $lang->mr->viewInGitlab, "_blank", "class='btn btn-link btn-active-text' style='color: blue'"); ?></span>
    </div>
  </div>

  <div id="mainContent" class="main-row">
    <div class="main-col">
      <div class="cell">
        <div class="detail">
          <div class="detail-title">
            <div><?php echo $lang->mr->from . html::a($sourceProjectURL, $sourceProjectName . ":" . $MR->sourceBranch, "_blank", "class='btn btn-link btn-active-text' style='color: blue'") . $lang->mr->to . html::a($targetProjectURL, $targetProjectName . ":" . $MR->targetBranch, "_blank", "class='btn btn-link btn-active-text' style='color: blue'"); ?></div>
          </div>
          <div class="detail-content article-content">
            <strong><?php echo $lang->mr->status; ?></strong>
            <?php echo zget($lang->mr->statusList, $MR->status); ?>
          </div>
        </div>
      </div>
      <div class="cell">
        <div class="detail">
          <div class="detail-title"><?php echo $lang->mr->description; ?></div>
          <div class="detail-content article-content">
            <?php echo !empty($MR->description) ? $MR->description : "<div class='text-center text-muted'>" . $lang->noData . '</div>'; ?>
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
            <?php echo html::a(inlink('approval', "mr=$MR->id&action=approve&onlybody=yes"), '<i class="icon icon-ok"></i> ' . $lang->mr->approve, '', "id='mergeButton' class='btn iframe showinonlybody'"); ?>
            <?php echo html::a(inlink('approval', "mr=$MR->id&action=reject&onlybody=yes"), '<i class="icon icon-bug"></i> ' . $lang->mr->reject, '', "id='mergeButton' class='btn iframe showinonlybody'"); ?>
            <?php echo html::a(inlink('close', "mr=$MR->id"), '<i class="icon icon-close"></i> ' . $lang->mr->close, '', "id='mergeButton' class='btn'"); ?>
            <?php echo html::a(inlink('edit', "mr=$MR->id"), '<i class="icon icon-edit"></i> ' . str_replace($lang->mr->common, '', $lang->mr->edit), '', "id='mergeButton' class='btn'"); ?>
          <?php endif; ?>
          <?php if($rawMR->state == 'closed') echo html::a(inlink('reopen', "mr=$MR->id"), '<i class="icon icon-restart"></i> ' . $lang->mr->reopen, '', "id='mergeButton' class='btn'"); ?>
          <?php echo html::a(inlink('delete', "mr=$MR->id"), '<i class="icon icon-trash"></i> ' . str_replace($lang->mr->common, '', $lang->mr->delete), '', "id='mergeButton' class='btn'"); ?>
        </div>
      </div>
    </div>
    <div class="side-col col-4">
      <div class="cell">
        <div class='tabs'>
          <ul class='nav nav-tabs'>
            <li class='active'><a href='#legendBasic' data-toggle='tab'><?php echo $lang->mr->view; ?></a></li>
          </ul>
          <div class='tab-content'>
            <div class='tab-pane active' id='legendBasic'>
              <strong><?php echo $lang->mr->status; ?></strong>
              <?php echo zget($lang->mr->statusList, $MR->status); ?>
              <br>
              <?php if(isset($rawMR->head_pipeline->status)): ?>
                <div>
                  <strong><?php echo "{$lang->mr->pipeline}{$lang->mr->status}"; ?></strong>
                  <?php echo zget($lang->mr->pipelineStatus, $rawMR->head_pipeline->status, $lang->mr->pipelineUnknown); ?>
                </div>
              <?php endif; ?>
              <strong><?php echo $lang->mr->mergeStatus; ?> </strong>
              <?php
              if(empty($rawMR->changes_count)):
                echo $lang->mr->noChanges;
              else:
                echo zget($lang->mr->mergeStatusList, $rawMR->merge_status); ?>
                <br>
                <strong><?php echo $lang->mr->MRHasConflicts; ?></strong>
                <?php echo ($rawMR->has_conflicts ? $lang->mr->hasConflicts : $lang->mr->hasNoConflict); ?>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>
      <div class="cell">
        <div class='tabs'>
          <ul class='nav nav-tabs'>
            <li class='active'><a href='#legendBasic' data-toggle='tab'><?php echo $lang->mr->approvalStatus; ?></a></li>
          </ul>
          <div class='tab-content'>
            <div class='tab-pane active' id='legendBasic'>
              <strong><?php echo $lang->mr->approvalStatus; ?></strong>
              <?php echo zget($lang->mr->approvalStatusList, $MR->approvalStatus, $lang->mr->approvalStatusList['notReviewed']); ?>
              <br>
              <?php if($MR->approvalStatus != '' and $MR->approvalStatus != 'notReviewed'): ?>
                <strong><?php echo $lang->mr->reviewer; ?></strong>
                <?php echo $MR->approver; ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <div class="cell">
        <div class='tabs'>
          <ul class='nav nav-tabs'>
            <li class='active'><a href='#legendStories' data-toggle='tab'><?php echo $lang->productplan->linkedStories; ?></a></li>
            <li><a href='#legendBugs' data-toggle='tab'><?php echo $lang->productplan->linkedBugs; ?></a></li>
            <li><a href='#legendTasks' data-toggle='tab'><?php echo $lang->mr->linkedTasks ?></a></li>
          </ul>
          <div class='tab-content'>
                <div class='tab-pane active' id='legendStories'>
                  <ul class="list-unstyled">
                      <?php
                      foreach($stories as $id => $story)
                      {
                          echo "<li title='$story->title'>" . html::a($this->createLink('story', 'view', "id=$id", '', true), "#$id $story->title", '', "class='iframe' data-width='80%'") . '</li>';
                      }
                      ?>
                    <li><?php echo html::a($this->createLink('mr', 'link', "MRID=$MR->id&type=story&orderBy=id_desc&link=true", '', true), $lang->productplan->linkStory, '', "class='btn btn-info iframe' data-width='95%' id='linkButton'"); ?></li>
                  </ul>
                </div>
                <div class="tab-pane" id='legendBugs'>
                  <ul class="list-unstyled">
                      <?php
                      foreach($bugs as $id => $bug)
                      {
                          echo "<li title='$bug->title'>" . html::a($this->createLink('bug', 'view', "id=$id", '', true), "#$id $bug->title", '', "class='iframe' data-width='80%'") . '</li>';
                      }
                      ?>
                    <li><?php echo html::a($this->createLink('mr', 'link', "MRID=$MR->id&type=bug&orderBy=id_desc&link=true", '', true), $lang->productplan->linkBug, '', "class='btn btn-info iframe' data-width='95%' id='linkButton'"); ?></li>
                  </ul>
                </div>
            <div class="tab-pane" id='legendTasks'>
              <ul class="list-unstyled">
                      <?php
                      foreach($tasks as $id => $task)
                      {
                          echo "<li title='$task->name'>" . html::a($this->createLink('task', 'view', "id=$id", '', true), "#$id $task->name", '', "class='iframe' data-width='80%'") . '</li>';
                      }
                      ?>
                    <li><?php echo html::a($this->createLink('mr', 'link', "MRID=$MR->id&type=task&orderBy=id_desc&link=true", '', true), $lang->mr->linkTask, '', "class='btn btn-info iframe' data-width='95%' id='linkButton'"); ?></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <br>
<?php endif; ?>
<script>
  /* Callback function for approval view. */
  function refresh() {
    location.reload();
  }
</script>
<?php include '../../common/view/footer.html.php'; ?>
