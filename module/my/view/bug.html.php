<?php
/**
 * The bug view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: bug.html.php 5107 2013-07-12 01:46:12Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($_GET['ajax']) and $_GET['ajax'] == 'yes') die(include "./buglist.html.php")?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='assignedToTab'>"  . html::a(inlink('bug', "type=assignedTo"),  $lang->bug->assignToMe)    . "</span>";
    echo "<span id='openedByTab'>"    . html::a(inlink('bug', "type=openedBy"),    $lang->bug->openedByMe)    . "</span>";
    echo "<span id='resolvedByTab'>"  . html::a(inlink('bug', "type=resolvedBy"),  $lang->bug->resolvedByMe)  . "</span>";
    echo "<span id='closedByTab'>"    . html::a(inlink('bug', "type=closedBy"),    $lang->bug->closedByMe)    . "</span>";
    ?>
  </div>
</div>
<form method='post' action='<?php echo $this->createLink('bug', 'batchEdit', "productID=0");?>'>
<?php include "./buglist.html.php"?>
</form>
<?php js::set('listName', 'bugList')?>
<script language='javascript'>$("#<?php echo $type;?>Tab").addClass('active');</script>
<?php include '../../common/view/footer.html.php';?>
