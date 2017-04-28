<?php
/**
 * The edit view file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <strong><small class='text-muted'><?php echo html::icon($lang->icons['edit']);?></small> <?php echo $lang->testreport->edit;?></strong>
    </div>
    <div class='actions'><?php echo html::backButton();?></div>
  </div>
  <div class='main'>
    <form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin'>
    <fieldset>
      <legend><?php echo $lang->testreport->legendBasic?></legend>
      <table class='table table-form'>
        <tr>
          <th class='w-80px'><?php echo $lang->testreport->startEnd?></th>
          <td class='w-p50'>
            <div class='input-group'>
              <?php echo html::input('begin', $report->begin, "class='form-control form-date'")?>
              <span class='input-group-addon'> ~ </span>
              <?php echo html::input('end', $report->end, "class='form-control form-date'")?>
              <?php
              echo html::hidden('product', $productIdList) . html::hidden('project', $report->project) . html::hidden('tasks', $tasks);
              ?>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <span class='input-group-addon'><?php echo $lang->testreport->owner?></span>
              <?php echo html::select('owner', $users, $report->owner, "class='form-control chosen'")?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testreport->member?></th>
          <td colspan='2'><?php echo html::select('members[]', $users, $report->members, "class='form-control chosen' multiple")?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testreport->title?></th>
          <td colspan='2'><?php echo html::input('title', $report->title, "class='form-control'")?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testreport->goal?></th>
          <td colspan='2'><?php echo $project->desc?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testreport->profile?></th>
          <td colspan='2'>
          <?php
          echo '<p>' . $storySummary . '</p>';
          echo '<p>' . sprintf($lang->testreport->buildSummary, empty($builds) ? 1 : count($builds)) . $caseSummary . '</p>';
          echo '<p>' . sprintf($lang->testreport->bugSummary, $bugInfo['countBugByTask'], count($legacyBugs), $bugInfo['bugConfirmedRate'] . '%', $bugInfo['bugCreateByCaseRate'] . '%') . '</p>';
          unset($bugInfo['countBugByTask']); unset($bugInfo['bugConfirmedRate']); unset($bugInfo['bugCreateByCaseRate']);
          ?>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testreport->report?></th>
          <td colspan='2'><?php echo html::textarea('report', $report->report, "class='form-control'")?></td>
        </tr>
        <tr>
          <th><?php echo $lang->files?></th>
          <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        <tr>
          <th></th>
          <td><?php echo html::submitButton() . html::backButton();?></td>
        </tr>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->testreport->legendStoryAndBug?></legend>
      <?php include './blockstories.html.php'?>
      <?php include './blockbugs.html.php'?>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->testreport->legendBuild?></legend>
      <?php include './blockbuilds.html.php'?>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->testreport->legendCase?></legend>
      <?php include './blockcases.html.php'?>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->testreport->legendLegacyBugs?></legend>
      <?php include './blocklegacybugs.html.php'?>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->testreport->legendReport?></legend>
      <?php include './blockbugreport.html.php'?>
    </fieldset>
  </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
