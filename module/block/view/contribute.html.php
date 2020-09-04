<div class='panel-move-handler'></div>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="row tiles">
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdTodos;?></div>
        <div class="tile-amount"><?php echo empty($data['createdTodos']) ? 0 : html::a($this->createLink('my', 'todo', 'type=all'), (int)$data['createdTodos']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdStories;?></div>
        <div class="tile-amount"><?php echo empty($data['createdStories']) ? 0 : html::a($this->createLink('my', 'story', 'type=openedBy'), (int)$data['createdStories']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->finishedTasks;?></div>
        <div class="tile-amount"><?php echo empty($data['finishedTasks']) ? 0 : html::a($this->createLink('my', 'task', 'type=finishedBy'), (int)$data['finishedTasks']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->resolvedBugs;?></div>
        <div class="tile-amount"><?php echo empty($data['resolvedBugs']) ? 0 : html::a($this->createLink('my', 'bug', 'type=resolvedBy'), (int)$data['resolvedBugs']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdCases;?></div>
        <div class="tile-amount"><?php echo empty($data['createdCases']) ? 0 : html::a($this->createLink('my', 'testcase', 'type=openedbyme'), (int)$data['createdCases']);?></div>
      </div>
    </div>
  </div>
</div>
