<?php
/**
 * The create view file of testreport module of ZenTaoPMS.
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
<?php js::set('objectType', $objectType);?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2 class='clearfix'>
        <div class='heading'><?php echo $lang->testreport->create;?></div>
        <?php if(!empty($taskPairs)):?>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->testtask->common;?></span>
          <?php echo html::select('selectTask', $taskPairs, $objectID, "class='form-control chosen'");?>
        </div>
        <?php endif;?>
      </h2>
    </div>
    <form method='post' enctype='multipart/form-data' target='hiddenwin'>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendBasic?></div>
        <div class="detail-content">
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
                  <?php
                  echo html::hidden('product', $productIdList) . (html::hidden('execution', isset($execution->id) ? $execution->id : 0)) . html::hidden('tasks', $tasks);
                  echo html::hidden('objectID', $objectID) . html::hidden('objectType', $objectType);
                  ?>
                </div>
              </td>
              <td>
                <div class='input-group'>
                  <span class='input-group-addon'><?php echo $lang->testreport->owner?></span>
                  <?php echo html::select('owner', $users, $owner, "class='form-control chosen'")?>
                </div>
              </td>
              <td class='w-50px'></td>
            </tr>
            <tr>
              <th><?php echo $lang->testreport->members?></th>
              <td colspan='2'><?php echo html::select('members[]', $users, $members, "class='form-control picker-select' multiple")?></td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->testreport->title?></th>
              <td colspan='2'><?php echo html::input('title', $reportTitle, "class='form-control'")?></td>
              <td></td>
            </tr>
            <?php if(!empty($execution->desc)):?>
            <tr>
              <th><?php echo $lang->testreport->goal?></th>
              <td colspan='2'>
                <?php echo $execution->desc;?>
                <a data-toggle='tooltip' class='text-warning' title='<?php echo $lang->testreport->goalTip;?>'><i class='icon-help'></i></a>
              </td>
              <td></td>
            </tr>
            <?php endif;?>
            <tr>
              <th id='profile'><?php echo $lang->testreport->profile?></th>
              <td colspan='2'>
              <?php
              echo '<div>' . $storySummary . '</div>';
              echo '<div>' . sprintf($lang->testreport->buildSummary, empty($builds) ? 1 : count($builds)) . $caseSummary . '</div>';
              echo '<div>' . sprintf($lang->testreport->bugSummary, $bugSummary['foundBugs'], count($legacyBugs), $bugSummary['activatedBugs'],  $bugSummary['countBugByTask'], $bugSummary['bugConfirmedRate'] . '%', $bugSummary['bugCreateByCaseRate'] . '%') . '</div>';
              ?>
              </td>
              <td></td>
            </tr>
            <tr>
              <th><?php echo $lang->testreport->report?></th>
              <td colspan='2'><?php echo html::textarea('report', '', "class='form-control'")?></td>
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
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendStoryAndBug?></div>
        <div class="detail-content">
          <?php include './blockstories.html.php'?>
          <?php include './blockbugs.html.php'?>
        </div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendBuild?></div>
        <div class="detail-content">
          <?php include './blockbuilds.html.php'?>
        </div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendCase?></div>
        <div class="detail-content">
          <?php include './blockcases.html.php'?>
        </div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendLegacyBugs?></div>
        <div class="detail-content">
          <?php include './blocklegacybugs.html.php'?>
        </div>
      </div>
      <div class='detail'>
        <div class='detail-title'><?php echo $lang->testreport->legendReport?></div>
        <div class="detail-content">
          <?php include './blockbugreport.html.php'?>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
objectID   = $("#objectID").val();
objectType = $("#objectType").val();
extra      = '<?php echo $extra;?>';
method     = 'create';
</script>
<?php include '../../common/view/footer.html.php';?>
