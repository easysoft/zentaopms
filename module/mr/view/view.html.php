<?php include '../../common/view/header.html.php';?>
<?php if(!isset($MR->id)):?>
  <div id='mainContent'>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->mr->notFound;?></span>
        <?php if(common::hasPriv('mr', 'create')):?>
        <?php echo html::a($this->createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
  </div>
<?php else:?>
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('mr', 'browse'), '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary'");?>
    <div class="divider"></div>
    <div class="page-title">
      <span class="label label-id"><?php echo $MR->id?></span>
      <span class="text" title='<?php echo $MR->title;?>'><?php echo  $MR->title;?></span>
      <span class="text" title='<?php echo $MR->title;?>' style='color: blue'><?php echo html::a($rawMR->web_url, $lang->mr->viewInGitlab, "_blank", "class='btn btn-link btn-active-text' style='color: blue'");?></span>
    </div>
  </div>

  <div id="mainContent" class="main-row">
    <div class="main-col">
      <div class="cell">
        <div class="detail">
          <div class="detail-title">
            <div><?php echo $lang->mr->from . html::a($sourceProjectURL, $sourceProjectName . ":" . $MR->sourceBranch, "_blank", "class='btn btn-link btn-active-text' style='color: blue'") . $lang->mr->to . html::a($targetProjectURL, $targetProjectName . ":" . $MR->targetBranch, "_blank", "class='btn btn-link btn-active-text' style='color: blue'");?></div>
          </div>
          <div class="detail-content article-content">
            <strong><?php echo $lang->mr->status;?></strong>
            <?php echo zget($lang->mr->statusList, $MR->status);?>
            <br>
            <?php if(isset($rawMR->head_pipeline->status)):?>
              <div>
                <strong><?php echo "{$lang->mr->pipeline}{$lang->mr->status}";?></strong>
                <?php echo zget($lang->mr->pipelineStatus, $rawMR->head_pipeline->status, $lang->mr->pipelineUnknown);?>
              </div>
            <?php endif;?>
            <strong><?php echo $lang->mr->mergeStatus;?> </strong>
            <?php echo zget($lang->mr->mergeStatusList, $rawMR->merge_status);?>
            <br>
            <strong><?php echo $lang->mr->MRHasConflicts;?></strong>
            <?php echo ($rawMR->has_conflicts ? $lang->mr->hasConflicts : $lang->mr->hasNoConflict);?>
          </div>
        </div>
      </div>
      <div class="cell">
        <div class="detail">
          <div class="detail-title"><?php echo $lang->mr->description;?></div>
          <div class="detail-content article-content">
            <?php echo !empty($MR->description) ? $MR->description : "<div class='text-center text-muted'>" . $lang->noData . '</div>';?>
          </div>
        </div>
      </div>
      <?php if($rawMR->state == 'opened'):?>
      <div class="cell"><?php echo sprintf($lang->mr->commandDocument, $httpRepoURL, $MR->sourceBranch, $branchPath, $MR->targetBranch, $branchPath, $MR->targetBranch);?></div>
      <?php endif;?>
    </div>
  </div>
  <br>
  <?php if($rawMR->state == 'opened' and !$rawMR->has_conflicts):?>
  <?php echo html::a(inlink( 'accept', "mr=$MR->id"), '<i class="icon icon-checked"></i> ' . $lang->mr->acceptMR, '', "id='mergeButton' class='btn btn-wide btn-primary'");?>
  <?php endif;?>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
