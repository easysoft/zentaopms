<?php
/**
 * The html template file of importNotice method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: execute.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
ol li{padding: 10px}
li .form-control {margin-top: 10px}
</style>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2>
      <?php echo $method == 'db' ? $lang->convert->jira->importFromDB : $lang->convert->jira->importFromFile;?>
    </h2>
  </div>
  <form class='form-ajax' method='post'>
    <div class='panel-title text-center text-red'><?php echo $lang->convert->jira->importNotice;?></div>
    <div class='panel-body'>
      <ol>
        <li><?php echo $lang->convert->jira->importSteps[$method][1];?></li>
        <li><?php echo $lang->convert->jira->importSteps[$method][2];?></li>
        <?php if($method == 'db'):?>
        <li><?php echo $lang->convert->jira->importSteps[$method][3];?></li>
        <?php else:?>
        <li><?php echo sprintf($lang->convert->jira->importSteps[$method][3], $app->getTmpRoot() . 'jirafile');?></li>
        <?php endif;?>
        <li><?php echo sprintf($lang->convert->jira->importSteps[$method][4], $app->getTmpRoot());?></li>
        <li>
          <?php echo $lang->convert->jira->importSteps[$method][5];?>
          <?php if($method == 'db'):?>
          <div class='required w-250px'><?php echo html::input('dbName', '', "class='form-control' placeholder='{$lang->convert->jira->dbNameNotice}'");?></div>
          <?php endif;?>
        </li>
      </ol>
    </div>
    <hr />
    <div class='panel-footer text-center form-actions'>
      <?php echo html::submitButton($lang->convert->jira->next) . html::backButton();?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
