<div class='panel-move-handler'></div>
<div class="panel-body conatiner-fluid">
  <div class="table-row">
    <div class="row tiles">
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdTodo;?></div>
        <div class="tile-amount"><?php echo empty($data['createdTodo']) ? 0 : html::a($this->createLink('my', 'todo', 'type=all'), (int)$data['createdTodo']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdStory;?></div>
        <div class="tile-amount"><?php echo empty($data['createdStory']) ? 0 : html::a($this->createLink('my', 'story', 'type=openedBy'), (int)$data['createdStory']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->finishedTask;?></div>
        <div class="tile-amount"><?php echo empty($data['finishedTask']) ? 0 : html::a($this->createLink('my', 'task', 'type=finishedBy'), (int)$data['finishedTask']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->resolvedBug;?></div>
        <div class="tile-amount"><?php echo empty($data['resolvedBug']) ? 0 : html::a($this->createLink('my', 'bug', 'type=resolvedBy'), (int)$data['resolvedBug']);?></div>
      </div>
      <div class="col-xs-4 tile">
        <div class="tile-title"><?php echo $lang->block->createdCase;?></div>
        <div class="tile-amount"><?php echo empty($data['createdCase']) ? 0 : html::a($this->createLink('my', 'testcase', 'type=openedbyme'), (int)$data['createdCase']);?></div>
      </div>
    </div>
  </div>
</div>
