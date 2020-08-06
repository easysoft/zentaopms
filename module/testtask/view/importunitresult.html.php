<?php
/**
 * The create view of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: create.html.php 4728 2013-05-03 06:14:34Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php js::import($jsRoot . 'misc/date.js');?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->testtask->importUnitResult;?></h2>
    </div>
    <form method='post' target='hiddenwin' id='dataform' enctype='multipart/form-data'>
      <table class='table table-form'>
        <?php if($config->global->flow != 'onlyTest'):?>
        <tr>
          <th class='w-80px'><?php echo $lang->testtask->project;?></th>
          <td class='w-p35-f'><?php echo html::select('project', $projects, '', "class='form-control chosen' onchange='loadProjectRelated(this.value)'");?></td><td></td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-80px'><?php echo $lang->testtask->build;?></th>
          <td class='w-p35-f'>
            <div class='input-group' id='buildBox'>
            <?php echo html::select('build', empty($builds) ? '' : $builds, '', "class='form-control chosen'");?>
            <?php if(isset($projectID) and $projectID and empty($builds)):?>
            <span class='input-group-addon'><?php echo html::a(helper::createLink('build', 'create', "projectID=$projectID", '', true), $lang->build->create, '', "data-toggle='modal' data-type='iframe' data-width='95%'")?> </span>
            </div>
            <div class='hidden'><?php echo '&nbsp; ' .  html::a("javascript:void(0)", $lang->refresh, '', "class='refresh' onclick='loadProjectBuilds($projectID)'");?></div>
            <?php endif;?>
          </td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->job->frame;?></th>
          <td><?php echo html::select('frame', $lang->job->frameList, '', "class='form-control chosen'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->owner;?></th>
          <td>
            <div id='ownerAndPriBox' class='input-group'>
              <?php echo html::select('owner', $users, $this->app->user->account, "class='form-control chosen'");?>
              <span class='input-group-addon fix-border'><?php echo $lang->testtask->pri;?></span>
              <?php echo html::select('pri', $lang->testtask->priList, 0, "class='form-control chosen'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->begin;?></th>
          <td>
            <div class='input-group'>
              <?php echo html::input('begin', date('Y-m-d'), "class='form-control form-date' onchange='suitEndDate()'");?>
              <span class='input-group-addon fix-border'><?php echo $lang->testtask->end;?></span>
              <?php echo html::input('end', date('Y-m-d', time() + 24 * 3600), "class='form-control form-date'");?>
            </div>
          </td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->resultFile;?></th>
          <td><?php echo html::file('resultFile', "class='form-control'");?></td>
          <td><?php echo $lang->testtask->unitXMLFormat;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->name;?></th>
          <td colspan='2'><?php echo html::input('name', sprintf($lang->testtask->titleOfAuto, date('Y-m-d H:i:s')), "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->desc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', '', "rows=10 class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->testtask->mailto;?></th>
          <td colspan='2'>
            <div id='mailtoGroup' class='input-group'>
            <?php
            echo html::select('mailto[]', $users, '', "multiple class='form-control chosen'");
            echo $this->fetch('my', 'buildContactLists');
            ?>
            </div>
          </td>
        </tr>
        <?php $this->printExtendFields('', 'table');?>
        <tr>
          <td class='text-center form-actions' colspan='3'>
            <?php echo html::submitButton();?>
            <?php echo html::backButton();?>
            <?php echo html::hidden('product', $productID);?>
            <?php echo html::hidden('status', 'done');?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
