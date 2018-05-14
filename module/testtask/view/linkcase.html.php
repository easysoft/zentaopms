<?php
/**
 * The linkcase view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: linkcase.html.php 4411 2013-02-22 00:56:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php common::printBack($this->session->testtaskList, 'btn btn-link');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $task->id;?></span>
      <?php echo $task->name;?>
    </div>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    $lang->testtask->linkCase = $lang->testtask->linkByStory;
    common::printLink('testtask', 'linkCase', "taskID=$taskID&type=bystory", $lang->testtask->linkByStory, '', "class='btn btn-primary'");

    echo "<span class='dropdown'>";
    echo "<button class='btn btn-primary' type='button' data-toggle='dropdown'>{$lang->testtask->linkBySuite} <span class='caret'></span></button>";
    echo "<ul class='dropdown-menu' style='max-height:240px;overflow-y:auto'>";
    if($suiteList)
    {
        foreach($suiteList as $suiteID => $suite)
        {
            $active = ($type == 'bysuite' and (int)$param == $suiteID) ? "class='active'" : '';
            $suiteName = $suite->name;
            if($suite->type == 'public') $suiteName .= " <span class='label label-info'>{$lang->testsuite->authorList[$suite->type]}</span>";
            echo "<li $active>" . html::a(inlink('linkCase', "taskID=$taskID&type=bysuite&param=$suiteID"), $suiteName) . "</li>";
        }
    }
    else
    {
        echo "<li>" . html::a('###', $lang->testsuite->noticeNone) . "</li>";
    }
    echo "</ul></span>";

    echo "<span class='dropdown'>";
    echo "<button class='btn btn-primary' type='button' data-toggle='dropdown'>{$lang->testtask->linkByBuild} <span class='caret'></span></button>";
    echo "<ul class='dropdown-menu' style='max-height:240px;overflow-y:auto'>";
    if($testTask)
    {
        foreach($testTask as $tmpID => $tmpTitle)
        {
            $active = ($type == 'bybuild' and (int)$param == $tmpID) ? "class='active'" : '';
            echo "<li $active>" . html::a(inlink('linkCase', "taskID=$taskID&type=bybuild&param=$tmpID"), $tmpTitle) . "</li>";
        }
    }
    else
    {
        echo "<li>" . html::a('###', $lang->testtask->noticeNoOther) . "</li>";
    }
    echo "</ul></span>";
    ?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <form class='main-table table-case' data-ride='table' method='post'>
    <table class='table'>
      <div class="table-header">
        <div class="table-statistic"><strong><?php echo $lang->testtask->unlinkedCases;?></strong> (<?php echo $pager->recTotal;?>)</div>
      </div>
      <thead>
        <tr>
          <th class='c-id'>
            <?php if($cases):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php echo $lang->idAB;?>
          </th>
          <th class='w-80px text-center'><nobr><?php echo $lang->testtask->linkVersion;?></nobr></th>
          <th class='c-pri'><?php echo $lang->priAB;?></th>
          <th><?php echo $lang->testcase->title;?></th>
          <th class='c-type'><?php echo $lang->testcase->type;?></th>
          <th class='c-user'><?php echo $lang->openedByAB;?></th>
          <th class='w-80px'><?php echo $lang->testtask->lastRunAccount;?></th>
          <th class='w-120px'><?php echo $lang->testtask->lastRunTime;?></th>
          <th class='w-80px'><?php echo $lang->testtask->lastRunResult;?></th>
          <th class='c-status'><?php echo $lang->statusAB;?></th>
        </tr>
      </thead>
      <tbody>
      <?php foreach($cases as $case):?>
      <tr>
        <td class='cell-id'>
          <?php echo html::checkbox('cases', array($case->id => sprintf('%03d', $case->id)));?>
        </td>
        <td class='text-center'><?php echo html::select("versions[$case->id]", array_combine(range($case->version, 1), range($case->version, 1)), '', 'class="form-control"');?> </td>
        <td><span class='label-pri label-pri-<?php echo $case->pri;?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
        <td class='text-left'>
          <?php
          echo $case->title . ' ( ';
          for($i = $case->version; $i >= 1; $i --)
          {
              echo html::a($this->createLink('testcase', 'view', "caseID=$case->id&version=$i", '', true), "#$i", '', "class='iframe' data-width='95%'");
          }
          echo ')';
          ?>
        </td>
        <td><?php echo $lang->testcase->typeList[$case->type];?></td>
        <td><?php echo $users[$case->openedBy];?></td>
        <td><?php echo $users[$case->lastRunner];?></td>
        <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
        <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        <td class='case-<?php echo $case->status?>'><?php echo $lang->testcase->statusList[$case->status];?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
    </table>
    <?php if($cases):?>
    <div class='table-footer'>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <div class="table-actions btn-toolbar"><?php echo html::submitButton('', '', 'btn');?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
