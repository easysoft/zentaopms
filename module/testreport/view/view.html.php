<?php
/**
 * The view view file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testreport
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php if(!$this->session->notHead):?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><strong><?php echo $report->id;?></strong></span>
      <strong><?php echo $report->title;?></strong>
    </div>
    <div class='actions'>
      <?php
      $browseLink  = $this->session->reportList != false ? $app->session->reportList : $browseLink;
      $actionLinks = '';
      ob_start();

      echo "<div class='btn-group'>";
      if(common::hasPriv('testreport', 'create')) echo html::a(inLink('create', "objectID=$report->objectID&objectType=$report->objectType"),  "<i class='icon-refresh'></i>", '', "class='btn' title='{$lang->testreport->recreate}'");
      common::printIcon('testreport', 'edit', "reportID=$report->id", '', 'button');
      common::printIcon('testreport', 'delete', "reportID=$report->id", '', 'button', '', 'hiddenwin');
      echo '</div>';

      echo "<div class='btn-group'>";
      common::printRPN($browseLink);
      echo '</div>';

      $actionLinks = ob_get_contents();
      ob_end_clean();
      echo $actionLinks;
      ?>
    </div>
  </div>
  <?php endif;?>
  <div class='main'>
    <ul class='nav nav-tabs'>
      <li class='active'><?php echo html::a('###', $lang->testreport->legendBasic, '', "data-toggle='tab' data-target='#basic'")?></li>
      <li><?php echo html::a('###', $lang->testreport->legendStoryAndBug, '', "data-toggle='tab' data-target='#storyAndBug'")?></li>
      <li><?php echo html::a('###', $lang->testreport->legendBuild, '', "data-toggle='tab' data-target='#tabBuild'")?></li>
      <li><?php echo html::a('###', $lang->testreport->legendCase, '', "data-toggle='tab' data-target='#tabCase'")?></li>
      <li><?php echo html::a('###', $lang->testreport->legendLegacyBugs, '', "data-toggle='tab' data-target='#tabLegacyBugs'")?></li>
      <li><?php echo html::a('###', $lang->testreport->legendReport, '', "data-toggle='tab' data-target='#tabReport'")?></li>
      <li><?php echo html::a('###', $lang->testreport->legendComment, '', "data-toggle='tab' data-target='#tabComment'")?></li>
      <?php if(!$this->session->notHead):?>
      <li><?php echo html::a('###', $lang->history, '', "data-toggle='tab' data-target='#tabHistory'")?></li>
      <?php if($this->app->user->admin):?>
      <li><?php echo html::a('###', $lang->testreport->legendMore, '', "data-toggle='tab' data-target='#tabMore'")?></li>
      <?php endif;?>
      <?php endif;?>
    </ul>
    <div class='tab-content'>
      <div class='tab-pane' id='basic'>
        <table class='table table-form'>
          <tr>
            <th class='w-100px'><?php echo $lang->testreport->startEnd?></th>
            <td class='w-p50'> <?php echo $report->begin . ' ~ ' . $report->end;?></td>
            <td></td>
          </tr>
          <tr>
            <th><?php echo $lang->testreport->owner?></th>
            <td><?php echo zget($users, $report->owner);?></td>
          </tr>
          <tr>
            <th><?php echo $lang->testreport->member?></th>
            <td colspan='2'><?php foreach(explode(',', $report->members) as $member)echo zget($users, $member) . ' &nbsp; ';?></td>
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
          <?php if(!$this->session->notHead):?>
          <tr>
            <th><?php echo $lang->files?></th>
            <td><?php echo $this->fetch('file', 'printFiles', array('files' => $report->files, 'fieldset' => 'false'));?></td>
          </tr>
          <?php endif;?>
        </table>
      </div>
      <div class='tab-pane' id='storyAndBug'>
        <?php include './blockstories.html.php'?>
        <?php include './blockbugs.html.php'?>
      </div>
      <div class='tab-pane' id='tabBuild'><?php include './blockbuilds.html.php'?></div>
      <div class='tab-pane' id='tabCase'><?php include './blockcases.html.php'?></div>
      <div class='tab-pane' id='tabLegacyBugs'><?php include './blocklegacybugs.html.php'?></div>
      <div class='tab-pane active' id='tabReport'><?php include './blockbugreport.html.php'?></div>
      <div class='tab-pane' id='tabComment'><div class='article-content'><?php echo empty($report->report) ? $lang->testreport->none : $report->report;?></div></div>
      <?php if(!$this->session->notHead):?>
      <div class='tab-pane' id='tabHistory'><?php include '../../common/view/action.html.php';?></div>
      <?php if($this->app->user->admin):?>
      <div class='tab-pane' id='tabMore'><div class='article-content'><?php echo $lang->testreport->moreNotice;?></div></div>
      <?php endif;?>
      <?php endif;?>
    </div>
  </div>
<?php if(!$this->session->notHead):?>
</div>
<?php include '../../common/view/footer.html.php';?>
<?php endif;?>
