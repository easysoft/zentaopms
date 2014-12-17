<?php
/**
 * The batch create view of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('testcaseBatchCreateNum', $config->testcase->batchCreate);?>
<?php js::set('productID', $productID);?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['testcase']);?></span>
    <strong><small class='text-muted'><?php echo html::icon($lang->icons['batchCreate']);?></small> <?php echo $lang->testcase->batchCreate;?></strong>
    <?php if($story):?>
    <small class='text-muted'><?php echo html::icon($lang->icons['story']) . ' ' . $story->title ?></small>
    <?php endif;?>
    <div class='actions'><?php echo html::commonButton($lang->pasteText, "data-toggle='myModal' ")?></div>
  </div>
</div>

<form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin'>
  <table align='center' class='table table-form table-fixed'>
    <thead>
      <tr>
        <th class='w-50px'><?php echo $lang->idAB;?></th> 
        <th class='w-300px'><?php echo $lang->testcase->module;?></th>
        <th class='w-180px'><?php echo $lang->testcase->type;?></th>
        <th class='w-400px'><?php echo $lang->testcase->story;?></th>
        <th><?php echo $lang->testcase->title;?> <span class='required'></span></th>
      </tr>
    </thead>

    <?php for($i = 0; $i < $config->testcase->batchCreate; $i++):?>
    <?php $moduleOptionMenu['same'] = $lang->testcase->same; if($i != 0) $currentModuleID = 'same';?>
    <?php $lang->testcase->typeList['same'] = $lang->testcase->same; $type = ($i == 0 ? 'feature' : 'same');?>
    <?php $pri = 3;?>
    <tr class='text-center'>
      <td><?php echo $i+1;?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[$i]", $moduleOptionMenu, $currentModuleID, "class='form-control chosen'");?></td>
      <td><?php echo html::select("type[$i]", $lang->testcase->typeList, $type, "class=form-control");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("story[$i]", $storyList, $story ? $story->id : '', 'class="form-control chosen"');?></td>
      <td><?php echo html::input("title[$i]", '', "class='form-control'");?></td>
    </tr>
    <?php endfor;?>
    <tfoot>
      <tr><td colspan='5' class='text-center'><?php echo html::submitButton() . html::backButton();?></td></tr>
    </tfoot>
  </table>
</form>
<table class='hide' id='trTemp'>
  <tbody>
    <tr class='text-center'>
      <td>%s</td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("module[%s]", $moduleOptionMenu, $currentModuleID, "class='form-control'");?></td>
      <td><?php echo html::select("type[%s]", $lang->testcase->typeList, $type, "class=form-control");?></td>
      <td class='text-left' style='overflow:visible'><?php echo html::select("story[%s]", '', '', 'class="form-control"');?></td>
      <td><?php echo html::input("title[%s]", '', "class='form-control'");?></td>
    </tr>
  </tbody>
</table>
<?php include '../../common/view/pastetext.html.php';?>
<?php include '../../common/view/footer.html.php';?>
