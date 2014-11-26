<?php
/**
 * The linkcase view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: linkcase.html.php 4411 2013-02-22 00:56:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testtask']);?> <strong><?php echo $task->id;?></strong></span>
    <strong><?php echo html::a($this->createLink('testtask', 'view', 'taskID=' . $task->id), $task->name, '_blank');?></strong>
    <small class='text-muted'> <?php echo $lang->testtask->linkCase;?> <?php echo html::icon($lang->icons['link']);?></small>
  </div>
  <div class='actions'>
    <?php
    echo "<div class='btn-group'>";
    $lang->testtask->linkCase = $lang->testtask->linkByStory;
    common::printIcon('testtask', 'linkCase', "taskID=$taskID&param=bystory", '', 'button', 'link');
    $lang->testtask->linkCase = $lang->testtask->linkByBug;
    common::printIcon('testtask', 'linkCase', "taskID=$taskID&param=bybug", '', 'button', 'link');
    echo '</div>';
    echo "<div class='btn-group'>";
    common::printRPN($this->session->testtaskList);
    echo '</div>';
    ?>
  </div>
  <div id='querybox' class='show'></div>
</div>
<form method='post'>
<table class='table table-condensed table-hover table-striped tablesorter table-fixed'>
  <caption class='text-left text-special'>
    <?php echo html::icon('unlink');?> &nbsp;<strong><?php echo $lang->testtask->unlinkedCases;?></strong> (<?php echo count($cases);?>)
  </caption>
  <thead>
    <tr>
      <th class='w-id'><?php echo $lang->idAB;?></th>
      <th class='w-60px'><nobr><?php echo $lang->testtask->linkVersion;?></nobr></th>
      <th class='w-pri'><?php echo $lang->priAB;?></th>
      <th><?php echo $lang->testcase->title;?></th>
      <th class='w-type'><?php echo $lang->testcase->type;?></th>
      <th class='w-user'><?php echo $lang->openedByAB;?></th>
      <th class='w-status'><?php echo $lang->statusAB;?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($cases as $case):?>
  <tr class='text-center'>
    <td class='text-left'>
      <input type='checkbox' name='cases[]' value='<?php echo $case->id;?>' />
      <?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), sprintf('%03d', $case->id));?>
    </td>
    <td class='text-center'><?php echo html::select("versions[$case->id]", array_combine(range($case->version, 1), range($case->version, 1)), '', 'class="form-control input-sm" style="padding: 0 5px; height: 20px"');?> </td>
    <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
    <td class='text-left'>
      <?php
      echo $case->title . ' ( ';
      for($i = $case->version; $i >= 1; $i --)
      {
          echo html::a($this->createLink('testcase', 'view', "caseID=$case->id&version=$i"), "#$i", '_blank');
      }
      echo ')';
      ?>
    </td>
    <td><?php echo $lang->testcase->typeList[$case->type];?></td>
    <td><?php echo $users[$case->openedBy];?></td>
    <td class='case-<?php echo $case->status?>'><?php echo $lang->testcase->statusList[$case->status];?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot> 
  <tr>
    <td colspan='7'>
      <?php if($cases):?>
        <div class='table-actions clearfix'><?php echo "<div class='btn-group'>" . html::selectButton() . '</div>' . html::submitButton();?></div>
      <?php endif;?>
      <div class='text-right'><?php $pager->show();?></div>
    </td>
  </tr>
  </tfoot>
</table>
</form>
<?php include '../../common/view/footer.html.php';?>
