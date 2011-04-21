<?php
/**
 * The test view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: test.html.php 1191 2010-11-13 07:30:35Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
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

<?php $vars = "type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
<table class='table-1 fixed tablesorter'>
  <thead>
    <tr class='colhead'>
      <th class='w-id'> <?php echo $lang->idAB;?></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th><?php echo $lang->testcase->title;?></th>
      <th class='w-type'>  <?php echo $lang->typeAB;?></th>
      <th class='w-user'>  <?php echo $lang->openedByAB;?></th>
      <th class='w-status'><?php echo $lang->statusAB;?></th>
      <th class='w-80px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($cases as $case):?>
    <tr class='a-center'>
      <td><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), sprintf('%03d', $case->id));?></td>
      <td><?php echo $case->pri?></td>
      <td class='a-left'><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), $case->title);?></td>
      <td><?php echo $lang->testcase->typeList[$case->type];?></td>
      <td><?php echo $users[$case->openedBy];?></td>
      <td><?php echo $lang->testcase->statusList[$case->status];?></td>
      <td>
        <?php
        common::printLink('testcase', 'edit',   "caseID=$case->id", $lang->testcase->buttonEdit);
        ?>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody> 
  <tfoot>
         <tr>
           <td colspan=7>
               <?php $pager->show();?>
           </td>
         </tr>
  </tfoot>
 
</table>
<script language="Javascript">$("#<?php echo $type;?>Tab").addClass('active'); </script>
<?php include '../../common/view/footer.html.php';?>
