<?php
/**
 * The edit view file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->testreport->edit;?></h2>
      <div class='btn-toolbar pull-right'><?php echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', 'btn btn-link');?></div>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendBasic?></div>
        <table class='table table-form'>
          <tr>
            <th class='c-date'><?php echo $lang->testreport->startEnd?></th>
            <td class='w-p50'>
              <div class='input-group'>
                <?php echo html::input('begin', $begin, "class='form-control form-date' onchange=changeDate()")?>
                <span class='input-group-addon'> ~ </span>
                <?php echo html::input('end', $end, "class='form-control form-date' onchange=changeDate()")?>
                <div class='input-group-btn hidden' id='refresh'>
                  <a onclick=refreshPage() class='btn' data-toggle='modal' data-type='iframe'><?php echo $lang->refresh?></a>
                </div>
                <?php echo html::hidden('product', $productIdList) . html::hidden('execution', $execution->id) . html::hidden('tasks', $tasks);?>
              </div>
            </td>
            <td>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->testreport->owner?></span>
                <?php echo html::select('owner', $users, $report->owner, "class='form-control chosen'")?>
              </div>
            </td>
            <td class='w-50px'></td>
          </tr>
          <tr>
            <th><?php echo $lang->testreport->members?></th>
            <td colspan='2'><?php echo html::select('members[]', $users, $report->members, "class='form-control picker-select' multiple")?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->testreport->title?></th>
            <td colspan='2'><?php echo html::input('title', $report->title, "class='form-control'")?></td>
            <td></td>
          </tr>
          <?php if(!empty($execution->desc)):?>
          <tr>
            <th><?php echo $lang->testreport->goal?></th>
            <td colspan='2'>
              <?php echo $execution->desc?>
              <a data-toggle='tooltip' class='text-warning' title='<?php echo $lang->testreport->goalTip;?>'><i class='icon-help'></i></a>
            </td>
            <td></td>
          </tr>
          <?php endif;?>
          <tr>
            <th id='profile'><?php echo $lang->testreport->profile?></th>
            <td colspan='2'>
            <?php
            echo '<p>' . $storySummary . '</p>';
            echo '<p>' . sprintf($lang->testreport->buildSummary, empty($builds) ? 1 : count($builds)) . $caseSummary . '</p>';
            echo '<p>' . sprintf($lang->testreport->bugSummary, $bugSummary['foundBugs'], count($legacyBugs), $bugSummary['activatedBugs'], $bugSummary['countBugByTask'], $bugSummary['bugConfirmedRate'] . '%', $bugSummary['bugCreateByCaseRate'] . '%') . '</p>';
            ?>
            </td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->testreport->report?></th>
            <td colspan='2'><?php echo html::textarea('report', $report->report, "class='form-control'")?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->files?></th>
            <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
            <td></td>
          </tr>
          <tr>
            <td class='text-center form-actions' colspan='4'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </table>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendStoryAndBug?></div>
        <?php include './blockstories.html.php'?>
        <?php include './blockbugs.html.php'?>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendBuild?></div>
        <?php include './blockbuilds.html.php'?>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendCase?></div>
        <?php include './blockcases.html.php'?>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendLegacyBugs?></div>
        <?php include './blocklegacybugs.html.php'?>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendReport?></div>
        <?php include './blockbugreport.html.php'?>
      </div>
    </form>
  </div>
</div>
<?php js::set('reportID', $report->id);?>
<?php js::set('method', 'edit');?>
<?php include '../../common/view/footer.html.php';?>
