<?php
/**
 * The batch create case view of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testsuite
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('testcaseBatchCreateNum', $config->testcase->batchCreate);?>
<?php js::set('libID', $libID);?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?></span>
    <strong>
      <small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small>
      <?php echo $lang->testcase->batchCreate;?>
    </strong>
    <div class='actions'>
      <?php echo html::commonButton($lang->pasteText, "data-toggle='myModal' ")?>
    </div>
  </div>
</div>
<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table align='center' class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-50px'> <?php echo $lang->idAB;?></th> 
        <th class='w-180px'><?php echo $lang->testcase->module;?></th>
        <th><?php echo $lang->testcase->title;?> <span class='required'></span></th>
        <th class='w-100px'><?php echo $lang->testcase->type;?> <span class='required'></span></th>
        <th class='w-80px'> <?php echo $lang->testcase->pri;?></th>
        <th class='w-150px'><?php echo $lang->testcase->precondition;?></th>
        <th class='w-100px'><?php echo $lang->testcase->keywords;?></th>
        <th class='w-200px'><?php echo $lang->testcase->stage;?></th>
      </tr>
    </thead>

    <?php unset($lang->testcase->typeList['']);?>
    <?php for($i = 0; $i < $config->testcase->batchCreate; $i++):?>
    <?php
    if($i != 0) $currentModuleID = 'ditto';
    if($i != 0) $lang->testcase->typeList['ditto'] = $lang->testcase->ditto;
    if($i != 0) $lang->testcase->priList['ditto']  = $lang->testcase->ditto;
    $type = $i == 0 ? 'feature' : 'ditto';
    $pri  = $i == 0 ? 3 : 'ditto';
    ?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen'");?></td>
      <td style='overflow:visible'>
        <div class='input-group'>
        <?php echo html::hidden("color[$i]", '', "data-provide='colorpicker' data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->testcase->colorTag}' data-update-text='#title\\[{$i}\\]'");?>
        <?php echo html::input("title[$i]", '', "class='form-control' autocomplete='off'");?>
        </div>
      </td>
      <td><?php echo html::select("type[$i]", $lang->testcase->typeList, $type, "class=form-control");?></td>
      <td><?php echo html::select("pri[$i]", $lang->testcase->priList, $pri, "class=form-control");?></td>
      <td><?php echo html::textarea("precondition[$i]", '', "rows='1' class='form-control autosize'")?></td>
      <td><?php echo html::input("keywords[$i]", '', "class='form-control' autocomplete='off'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("stage[$i][]", $lang->testcase->stageList, '', "class='form-control chosen' multiple");?></td>
    </tr>
    <?php endfor;?>
    <tfoot>
      <tr><td colspan='8' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
    </tfoot>
  </table>
</form>
<table class='hide' id='trTemp'>
  <tbody>
    <tr class='text-center'>
      <td>%s</td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[%s]", $moduleOptionMenu, $currentModuleID, "class='form-control'");?></td>
      <td style='overflow:visible'>
        <div class='input-group'>
        <?php echo html::hidden("color[%s]", '', "data-wrapper='input-group-btn fix-border-right' data-pull-menu-right='false' data-btn-tip='{$lang->testcase->colorTag}' data-update-text='#title\\[%s\\]'");?>
        <?php echo html::input("title[%s]", '', "class='form-control' autocomplete='off'");?></td>
        </div>
      </td>
      <td><?php echo html::select("type[%s]", $lang->testcase->typeList, $type, "class=form-control");?></td>
      <td><?php echo html::select("pri[%s]", $lang->testcase->priList, $pri, "class=form-control");?></td>
      <td><?php echo html::textarea("precondition[%s]", '', "class='form-control'")?></td>
      <td><?php echo html::input("keywords[%s]", '', "class='form-control' autocomplete='off'");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("stage[%s][]", $lang->testcase->stageList, '', "class='form-control' multiple");?></td>
    </tr>
  </tbody>
</table>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
