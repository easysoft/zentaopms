<?php
/**
 * The batch create case view of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     caselib
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('testcaseBatchCreateNum', $config->testcase->batchCreate);?>
<?php js::set('libID', $libID);?>
<div id="mainContent" class="main-content fade">
  <div class="main-header">
    <h2><?php echo $lang->testcase->batchCreate;?></h2>
    <div class="pull-right btn-toolbar">
      <?php echo html::commonButton($lang->pasteText, "data-toggle='modal' data-target='#importLinesModal' ", 'btn btn-info')?>
    </div>
  </div>
  <form method='post' class='load-indicator main-form' enctype='multipart/form-data' target='hiddenwin' id="batchCreateForm">
    <table align='center' class='table table-form' id="tableBody">
      <thead>
        <tr class='text-center'>
          <th class='w-50px'> <?php echo $lang->idAB;?></th>
          <th class='w-180px'><?php echo $lang->testcase->module;?></th>
          <th class='required'><?php echo $lang->testcase->title;?></th>
          <th class='w-100px required'><?php echo $lang->testcase->type;?></th>
          <th class='w-80px'> <?php echo $lang->testcase->pri;?></th>
          <th class='w-150px'><?php echo $lang->testcase->precondition;?></th>
          <th class='w-100px'><?php echo $lang->testcase->keywords;?></th>
          <th class='w-200px'><?php echo $lang->testcase->stage;?></th>
        </tr>
      </thead>
      <tbody>
      <?php unset($lang->testcase->typeList['']);?>
      <?php for($i = 0; $i < $config->testcase->batchCreate; $i++):?>
      <?php
      if($i != 0) $currentModuleID = 'ditto';
      if($i != 0) $lang->testcase->typeList['ditto'] = $lang->testcase->ditto;
      if($i != 0) $lang->testcase->priList['ditto']  = $lang->testcase->ditto;
      $type = $i == 0 ? 'feature' : 'ditto';
      $pri  = $i == 0 ? 3 : 'ditto';
      ?>
      <tr>
        <td><?php echo $i+1;?></td>
        <td class='text-left' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen'");?></td>
        <td style='overflow:visible'>
          <div class="input-control has-icon-right">
            <?php echo html::input("title[$i]", '', "class='form-control title-import'");?>
            <div class="colorpicker">
              <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
              <ul class="dropdown-menu clearfix">
                <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
              </ul>
              <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[$i\\]'");?>
            </div>
          </div>
        </td>
        <td><?php echo html::select("type[$i]", $lang->testcase->typeList, $type, "class='form-control chosen'");?></td>
        <td><?php echo html::select("pri[$i]", $lang->testcase->priList, $pri, "class='form-control chosen'");?></td>
        <td><?php echo html::textarea("precondition[$i]", '', "rows='1' class='form-control autosize'")?></td>
        <td><?php echo html::input("keywords[$i]", '', "class='form-control'");?></td>
        <td class='text-left' style='overflow:visible'><?php echo html::select("stage[$i][]", $lang->testcase->stageList, '', "class='form-control chosen' multiple");?></td>
      </tr>
      <?php endfor;?>
      </tbody>
      <tfoot>
        <tr><td colspan='8' class='text-center form-actions'><?php echo html::submitButton()?> <?php echo  html::backButton();?></td></tr>
      </tfoot>
    </table>
  </form>
</div>
<table class='template' id='trTemp'>
  <tbody>
    <tr>
      <td>%s</td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[%s]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen'");?></td>
      <td style='overflow:visible'>
        <div class="input-control has-icon-right">
          <?php echo html::input("title[%s]", '', "class='form-control title-import'");?>
          <div class="colorpicker">
            <button type="button" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><span class="cp-title"></span><span class="color-bar"></span><i class="ic"></i></button>
            <ul class="dropdown-menu clearfix">
              <li class="heading"><?php echo $lang->testcase->colorTag;?><i class="icon icon-close"></i></li>
            </ul>
            <?php echo html::hidden("color[%s]", '', "data-provide='colorpicker-later' data-icon='color' data-wrapper='input-control-icon-right'  data-update-color='#title\\[%s\\]'");?>
          </div>
        </div>
      </td>
      <td><?php echo html::select("type[%s]", $lang->testcase->typeList, $type, "class='form-control chosen'");?></td>
      <td><?php echo html::select("pri[%s]", $lang->testcase->priList, $pri, "class='form-control chosen'");?></td>
      <td><?php echo html::textarea("precondition[%s]", '', "rows='1' class='form-control autosize'")?></td>
      <td><?php echo html::input("keywords[%s]", '', "class='form-control'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("stage[%s][]", $lang->testcase->stageList, '', "class='form-control chosen' multiple");?></td>
    </tr>
  </tbody>
</table>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
