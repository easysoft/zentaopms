<?php include '../../common/view/m.header.html.php';?>
  <div data-role='navbar' id='subMenu'>
    <ul>
      <?php foreach($config->mobile->bugBar as $menu):?>
      <?php $active = $type == $menu ? 'ui-btn-active' : ''?>
      <li>
      <?php 
      $subMenuName = $menu == 'assignedTo' ? $lang->bug->assignToMe : $lang->bug->{$menu . 'Me'};
      echo html::a($this->createLink('my', 'bug', "type=$menu"), $subMenuName, '', "class='$active' data-theme='d'");
      ?>
      </li>
      <?php endforeach;?>
    </ul>
  </div>
</div>
<?php $this->session->set('bugType', $type);?>
<?php foreach($bugs as $bug):?>
  <div data-role="collapsible-set">
    <div data-role="collapsible" data-collapsed="<?php echo $this->session->bugID == $bug->id ? 'false' : 'true'?>" class='collapsible'>
      <?php if($this->session->bugID == $bug->id) echo "<script>showDetail('bug', $bug->id);</script>";?>
      <h1 onClick="showDetail('bug', <?php echo $bug->id;?>)"><?php echo $bug->title;?></h1>
      <div><?php echo $bug->steps;?></div>
      <div id='item<?php echo $bug->id;?>'></div>
      <div data-role='content' class='text-center'>
        <?php
        common::printIcon('bug', 'confirmBug', "bugID=$bug->id", $bug, 'button', '', '', 'iframe');
        common::printIcon('bug', 'assignTo',   "bugID=$bug->id", '',   'button', '', '', 'iframe');
        common::printIcon('bug', 'resolve',    "bugID=$bug->id", $bug, 'button', '', '', 'iframe');
        common::printIcon('bug', 'close',      "bugID=$bug->id", $bug, 'button', '', '', 'iframe');
        common::printIcon('bug', 'activate',   "bugID=$bug->id", $bug, 'button', '', '', 'iframe');
        ?>
      </div>
      <?php if(end($bugs) == $bug) echo "<hr />";?>
    </div>
  </div>
<?php endforeach;?>
<?php $pager->show('left', 'mobile')?>
<?php include '../../common/view/m.footer.html.php';?>
