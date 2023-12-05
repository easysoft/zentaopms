<?php
/**
 * The jenkins task view file of repo module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Gang Zeng <zenggang@cnezsoft.com>
 * @package     repo
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
#dropMenuTasks .tree li {padding: 3px 0 0 10px;}
#dropMenuTasks .tree li.has-list.open:before {border-left: 0px;}
#dropMenuTasks .tree li > a {max-width: 100%; line-height: 20px; border-radius: 2px; padding-top: 5px;}
#dropMenuTasks .col-left {padding: 0;}
#dropMenuTasks .label {margin-left: 3px;}
#dropMenuTasks .hide-in-search {padding-left: 8px;}
#dropMenuTasks .hide-in-search .hidden {display: block !important; visibility: inherit !important;}
#dropMenuTasksRepo > div.table-row > div > div > ul > li > div {padding-left: 10px;}
#dropMenuTasks ul.tree-angles {margin-bottom: 0;}
#dropMenuTasks {margin: 0;}
#dropMenuTasks ul > li > ul > li > a:hover {color: white; background-color: #0c64eb; text-decoration: none;}
#dropMenuTasks .tree .has-list > ul > li {padding-top: 0;}
.search-list .list-group {padding: 7px 10px;}
#dropMenuTasks .label-type {margin: 1px 10px; line-height: 20px;}
.tree li>.list-toggle {top: 0px;}
.tree .one-level>.list-toggle {top: 3px;}
</style>
<div class="table-row">
  <div class="table-col col-left">
    <div class="list-group" id="jenkinsTaskList">
      <ul class='tree tree-angles' data-ride='tree' data-idx='0'>
      <?php foreach($tasks as $groupName => $task):?>
        <?php if(empty($task)) continue;?>
        <?php if(is_array($task)):?>
        <li data-idx='$groupName' data-id='<?php echo $groupName?>' class='has-list open in one-level'>
          <i class='list-toggle icon'></i>
          <div class='label-type'>
            <a class='text-muted not-list-item'><?php echo $groupName;?></a>
          </div>
          <ul data-idx='<?php echo $groupName;?>'>
          <?php foreach($task as $task2name => $task2):?>
            <?php if(is_array($task2)):?>
            <li data-idx='$groupName' data-id='<?php echo $task2name?>' class='has-list open in'>
              <i class='list-toggle icon'></i>
              <div class='label-type'>
                <a class='text-muted not-list-item'><?php echo $task2name;?></a>
              </div>
              <ul data-idx='<?php echo $task2name;?>'>
              <?php foreach($task2 as $task3name => $task3):?>
                <?php if(is_array($task3)):?>
                <li data-idx='$groupName' data-id='<?php echo $task3name?>' class='has-list open in'>
                  <i class='list-toggle icon'></i>
                  <div class='label-type'>
                    <a class='text-muted not-list-item'><?php echo $task3name;?></a>
                  </div>
                  <ul data-idx='<?php echo $task3name;?>'>
                  <?php foreach($task3 as $task4name => $task4):?>
                    <?php if(is_array($task4)) continue;?>
                    <li data-idx='<?php echo $task4name;?>' data-id='<?php echo $task4name;?>'>
                      <a href='###' id='<?php echo $task4name?>' class='' text-ellipsis' onclick='setJenkinsJob("<?php echo $task4;?>","<?php echo $task4name;?>")' title='<?php echo $task4;?>' data-key='<?php echo $task4;?>'><?php echo $task4;?></a>
                    </li>
                  <?php endforeach;?>
                  </ul>
                </li>
                <?php else:?>
                <li data-idx='<?php echo $task3name;?>' data-id='<?php echo $task3name;?>'>
                  <a href='###' id='<?php echo $task3name?>' onclick='setJenkinsJob("<?php echo $task3;?>","<?php echo $task3name;?>")' class='' text-ellipsis' title='<?php echo $task3;?>' data-key='<?php echo $task3;?>'><?php echo $task3;?></a>
                </li>
                <?php endif;?>
              <?php endforeach;?>
              </ul>
            </li>
            <?php else:?>
            <li data-idx='<?php echo $task2name;?>' data-id='<?php echo $task2name;?>'>
              <a href='###' onclick='setJenkinsJob("<?php echo $task2;?>","<?php echo $task2name;?>")' id='<?php echo $task2name?>' class='' text-ellipsis' title='<?php echo $task2;?>' data-key='<?php echo $task2;?>'><?php echo $task2;?></a>
            </li>
            <?php endif;?>
          <?php endforeach;?>
          </ul>
        </li>
        <?php else:?>
        <li data-idx='<?php echo $task;?>' data-id='<?php echo $task;?>'>
          <a href='###' id='<?php echo $groupName;?>' class='text-ellipsis' onclick='setJenkinsJob("<?php echo $task;?>","<?php echo $groupName;?>")' title='<?php echo $task;?>' data-key='<?php echo $task;?>' ><?php echo $task;?></a>
        </li>
        <?php endif;?>
      <?php endforeach;?>
      </ul>
    </div>
  </div>
</div>
<script>
$('#jenkinsTaskList .tree').tree();
</script>
