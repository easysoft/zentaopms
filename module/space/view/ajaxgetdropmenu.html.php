<?php
js::set('spaceID', $spaceID);
js::set('module',  $module);
js::set('method',  $method);
?>
<div class="table-row">
  <div class="table-col col-left">
    <div class='list-group'>
      <ul class='tree tree-simple' data-ride='tree' id='spaceTree' data-name='tree-space'>
        <?php foreach($spaces as $space): ?>
        <li class="tree-single-item"><?php echo html::a(helper::createLink('space', 'browse', "spaceID=$space->id"), $space->name, '', 'class="search-list-item ' . ($space->id == $spaceID ? 'selected' : '') . '"');?></li>
        <?php endforeach;?>
      </ul>
    </div>
  </div>
</div>
