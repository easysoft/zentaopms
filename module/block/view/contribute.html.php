<div class='panel-move-handler'></div>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="row tiles">
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdTodos;?></div>
        <div class="tile-amount"><?php echo empty($data['createdTodos']) ? 0 : html::a($this->createLink('my', 'todo', 'type=all'), (int)$data['createdTodos']);?></div>
      </div>
      <?php if($config->URAndSR):?>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdRequirements;?></div>
        <div class="tile-amount"><?php echo empty($data['createdRequirements']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=requirement'), (int)$data['createdRequirements']);?></div>
      </div>
      <?php endif;?>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdStories;?></div>
        <div class="tile-amount"><?php echo empty($data['createdStories']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=story'), (int)$data['createdStories']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->finishedTasks;?></div>
        <div class="tile-amount"><?php echo empty($data['finishedTasks']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=task&type=finishedBy'), (int)$data['finishedTasks']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdBugs;?></div>
        <div class="tile-amount"><?php echo empty($data['createdBugs']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=bug'), (int)$data['createdBugs']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->resolvedBugs;?></div>
        <div class="tile-amount"><?php echo empty($data['resolvedBugs']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=bug&type=resolvedBy'), (int)$data['resolvedBugs']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdCases;?></div>
        <div class="tile-amount"><?php echo empty($data['createdCases']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=testcase&type=openedbyme'), (int)$data['createdCases']);?></div>
      </div>
      <?php if($this->config->edition == 'max' or $this->config->edition == 'ipd'):?>
      <?php if(helper::hasFeature('risk')):?>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdRisks;?></div>
        <div class="tile-amount"><?php echo empty($data['createdRisks']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=risk'), (int)$data['createdRisks']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->resolvedRisks;?></div>
        <div class="tile-amount"><?php echo $data['resolvedRisks'];?></div>
      </div>
      <?php endif;?>
      <?php if(helper::hasFeature('issue')):?>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdIssues;?></div>
        <div class="tile-amount"><?php echo empty($data['createdIssues']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=issue'), (int)$data['createdIssues']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->resolvedIssues;?></div>
        <div class="tile-amount"><?php echo $data['resolvedIssues'];?></div>
      </div>
      <?php endif;?>
      <?php endif;?>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdDocs;?></div>
        <div class="tile-amount"><?php echo empty($data['createdDocs']) ? 0 : html::a($this->createLink('my', 'contribute', 'mode=doc&type=openedbyme'), (int)$data['createdDocs']);?></div>
      </div>
    </div>
  </div>
</div>
