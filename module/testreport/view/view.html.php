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
      common::printIcon('testreport', 'export', "reportID=$report->id", '', 'button', 'download-alt', '', 'iframe');
      if(common::hasPriv('testreport', 'create')) echo html::a(inLink('create', "objectID=$report->objectID&objectType=$report->objectType"),  "<i class='icon-refresh'></i>", '', "class='btn' title='{$lang->testreport->recreate}'");
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
    <fieldset>
      <legend><?php echo $lang->testreport->legendBasic?></legend>
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
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->testreport->legendStoryAndBug?></legend>
      <table class='table table-form'>
        <?php include './blockstories.html.php'?>
        <?php include './blockbugs.html.php'?>
      </table>
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
      <legend><?php echo $lang->testreport->legendBug?></legend>
      <table class='table table-form'>
        <?php include './blocklegacybugs.html.php'?>
        <?php include './blockbuginfo.html.php'?>
      </table>
    </fieldset>
    <fieldset>
      <legend><?php echo $lang->testreport->report?></legend>
      <div><?php echo $report->report;?></div>
    </fieldset>
    <?php if(!$this->session->notHead):?>
    <?php echo $this->fetch('file', 'printFiles', array('files' => $report->files, 'fieldset' => 'true'));?>
    <?php endif;?>
  </div>
<?php if(!$this->session->notHead):?>
</div>
<?php include '../../common/view/footer.html.php';?>
<?php endif;?>
