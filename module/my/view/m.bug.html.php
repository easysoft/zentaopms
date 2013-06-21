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
<?php foreach($bugs as $bug):?>
  <div  data-role="collapsible-set">
    <div data-role="collapsible" data-collapsed="true">
      <h1><?php echo $bug->title;?></h1>
      <div><?php echo $bug->steps;?></div>
      <div data-role='navbar'>
        <ul>
        <?php
        if($this->session->bugList)
        {
            $browseLink = $this->session->bugList;
        }
        else
        {
            $browseLink = $this->createLink('my', 'bug');
        }
        common::printIcon('bug', 'confirmBug', "bugID=$bug->id", $bug, 'button', '', '', 'iframe', true);
        common::printIcon('bug', 'assignTo',   "bugID=$bug->id", '',   'button', '', '', 'iframe', true);
        common::printIcon('bug', 'resolve',    "bugID=$bug->id", $bug, 'button', '', '', 'iframe', true);
        common::printIcon('bug', 'close',      "bugID=$bug->id", $bug, 'button', '', '', 'iframe', true);
        common::printIcon('bug', 'activate',   "bugID=$bug->id", $bug, 'button', '', '', 'iframe', true);
        echo '<li>' . html::a($browseLink, $lang->goback) . '</li>';
        ?>
        </ul>
      </div>
    </div>
  </div>
<?php endforeach;?>
<?php include '../../common/view/m.footer.html.php';?>
