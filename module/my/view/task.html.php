<?php
/**
 * The task view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: task.html.php 5101 2013-07-12 00:44:27Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($_GET['ajax']) and $_GET['ajax'] == 'yes') die(include "./tasklist.html.php");?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<script language="Javascript">var type='<?php echo $type;?>';</script>
<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='assignedToTab'>" . html::a(inlink('task', "type=assignedTo"),  $lang->my->taskMenu->assignedToMe) . "</span>";
    echo "<span id='openedByTab'>"   . html::a(inlink('task', "type=openedBy"),    $lang->my->taskMenu->openedByMe)   . "</span>";
    echo "<span id='finishedByTab'>" . html::a(inlink('task', "type=finishedBy"),  $lang->my->taskMenu->finishedByMe) . "</span>";
    echo "<span id='closedByTab'>"   . html::a(inlink('task', "type=closedBy"),    $lang->my->taskMenu->closedByMe)   . "</span>";
    echo "<span id='canceledByTab'>" . html::a(inlink('task', "type=canceledBy"),  $lang->my->taskMenu->canceledByMe) . "</span>";
    ?>
  </div>
</div>
<form method='post' id='myTaskForm'>
<?php include "./tasklist.html.php";?>
</form>
<?php js::set('listName', 'tasktable')?>
<?php include '../../common/view/footer.html.php';?>
