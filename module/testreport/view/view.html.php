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
    <?php include './blockstories.html.php'?>
    <?php include './blockbugs.html.php'?>
    <?php include './blockbuilds.html.php'?>
    <?php include './blockcases.html.php'?>
    <?php include './blocklegacybugs.html.php'?>
    <?php include './blockbuginfo.html.php'?>
    <tr>
      <th><?php echo $lang->testreport->report?></th>
      <td colspan='2'><?php echo $report->report;?></td>
    </tr>
    <?php if(!$this->session->notHead):?>
    <tr>
      <th><?php echo $lang->files?></th>
      <td colspan='2'><?php echo $this->fetch('file', 'printFiles', array('files' => $report->files, 'fieldset' => 'false'));?></td>
    </tr>
    <?php endif;?>
  </table>
<?php if(!$this->session->notHead):?>
</div>
<?php include '../../common/view/footer.html.php';?>
<?php endif;?>
