<?php
/**
 * The test view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: test.html.php 1191 2010-11-13 07:30:35Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php if(isset($_GET['ajax']) and $_GET['ajax'] == 'yes') die(include "./caselist.html.php");?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='testtask'>"      . html::a($this->createLink('my', 'testtask'),  $lang->my->testTask) . "</span>";
    echo "<span id='assigntomeTab'>" . html::a($this->createLink('my', 'testcase', "type=assigntome"),  $lang->testcase->assignToMe) . "</span>";
    //echo "<span id='donebymeTab'>"   . html::a($this->createLink('my', 'testcase', "type=donebyme"),    $lang->testcase->doneByMe)   . "</span>";
    echo "<span id='openedbymeTab'>" . html::a($this->createLink('my', 'testcase', "type=openedbyme"),  $lang->testcase->openedByMe) . "</span>";
    ?>
  </div>
</div>

<form method='post' id='myCaseForm'>
<?php include './caselist.html.php';?>
</form>
<?php js::set('listName', 'caseList')?>
<script language="Javascript">$("#<?php echo $type;?>Tab").addClass('active'); </script>
<?php include '../../common/view/footer.html.php';?>
