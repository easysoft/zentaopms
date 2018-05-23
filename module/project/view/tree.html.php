<?php
/**
 * The project tree view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Hao Sun <sunhao@cnezsoft.com>
 * @package     project
 * @version     $Id: tree.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php foreach ($lang->project->treeLevel as $name => $btnLevel):?>
      <?php
      $icon = '';
      if($name == 'root') $icon = ' <i class="icon-fold-all"></i>';
      if($name == 'all') $icon = ' <i class="icon-unfold-all"></i>';
      ?>
      <a href='' class='btn btn-link btn-tree-view' data-type='<?php echo $name ?>'><span class='text'><?php echo $btnLevel . $icon;?></span></button>
    <?php endforeach; ?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    if(!isset($browseType)) $browseType = '';
    if(!isset($orderBy))    $orderBy = '';
    $link = common::hasPriv('task', 'report', $project) ?  $this->createLink('task', 'report', "project=$projectID&browseType=$browseType") : '#';
    echo html::a($link, "<i class='icon icon-bar-chart muted'></i> <span class='text'>{$lang->task->reportChart}</span>", '', 'class="btn btn-link"');
    ?>
    <div class="btn-group">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-import muted"></i> <span class="text"><?php echo $lang->import ?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <?php
        $misc = common::hasPriv('project', 'importTask') ? '' : "class=disabled";
        $link = common::hasPriv('project', 'importTask') ?  $this->createLink('project', 'importTask', "project=$project->id") : '#';
        echo "<li>" . html::a($link, $lang->project->importTask, '', $misc) . "</li>";

        $misc = common::hasPriv('project', 'importBug') ? '' : "class=disabled";
        $link = common::hasPriv('project', 'importBug') ?  $this->createLink('project', 'importBug', "project=$project->id") : '#';
        echo "<li>" . html::a($link, $lang->project->importBug, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php
    $misc = common::hasPriv('task', 'export', $project) ? "class='btn btn-link'" : "class='btn btn-link disabled'";
    $link = common::hasPriv('task', 'export') ? $this->createLink('task', 'export', "project=$projectID&orderBy=$orderBy&type=$browseType") : '#';
    echo html::a($link, "<i class='icon icon-export muted'></i> <span class='text'>{$lang->export}</span>", '', $misc);

    $misc = common::hasPriv('task', 'create', $project) ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
    $link = common::hasPriv('task', 'create', $project) ?  $this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : '')) : '#';
    echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->task->create, '', $misc);
    ?>
  </div>
</div>

<div id="mainContent" class="main-row hide-side">
  <div class="main-col">
    <div class="cell">
      <ul class="tree" id="taskTree">
        <?php echo $tree;?>
      </ul>
    </div>
  </div>
  <div class="side-col">
    <div class="cell">
      <div id="itemContent" class="load-indicator loading"></div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
