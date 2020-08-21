<div class='panel-move-handler'></div>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="row tiles">
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->openedTodos?></div>
        <div class="tile-amount"><?php echo empty($data['todos']) ? 0 : html::a($this->createLink('my', 'todo', 'type=all'), (int)$data['todos']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->openedStories?></div>
        <div class="tile-amount"><?php echo empty($data['stories']) ? 0 : html::a($this->createLink('my', 'story', 'type=openedBy'), (int)$data['stories']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->resolvedTasks?></div>
        <div class="tile-amount"><?php echo empty($data['tasks']) ? 0 : html::a($this->createLink('my', 'task', 'type=finishedBy'), (int)$data['tasks']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->resolvedBugs?></div>
        <div class="tile-amount"><?php echo empty($data['bugs']) ? 0 : html::a($this->createLink('my', 'bug', 'type=resolvedBy'), (int)$data['bugs']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->openedTestcases?></div>
        <div class="tile-amount"><?php echo empty($data['cases']) ? 0 : html::a($this->createLink('my', 'testcase', 'type=openedbyme'), (int)$data['cases']);?></div>
      </div>
    </div>
  </div>
</div>
