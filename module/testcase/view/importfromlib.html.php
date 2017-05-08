<?php
/**
 * The importfromlib view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <div class='input-group pull-left' style='font-weight:normal;'>
      <span class='input-group-addon'><strong style='font-size:15px'><?php echo $lang->testcase->selectLib;?></strong></span>
      <?php echo html::select('fromlib', $libraries, $libID, "onchange='reload(this.value)' class='form-control chosen'");?>
    </div>
  </div>
</div>
<form class='form-condensed' method='post' target='hiddenwin' id='importFromLib'>
  <table class='table tablesorter table-fixed table-selectable'>
    <thead>
    <?php $vars = "productID=$productID&branch=$branch&libID=$libID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
    <tr class='colhead'>
      <th class='w-id'>   <?php common::printOrderLink('id',  $orderBy, $vars, $lang->idAB);?></th>
      <?php if($branches):?>
      <th class='w-100px'><?php echo $lang->testcase->branch ?></th>
      <?php endif;?>
      <th class='w-pri'>  <?php common::printOrderLink('pri',   $orderBy, $vars, $lang->priAB);?></th>
      <th>                <?php common::printOrderLink('title', $orderBy, $vars, $lang->testcase->title);?></th>
      <th class='w-200px'><?php echo $lang->testcase->fromModule ?></th>
      <th class='w-200px'><?php echo $lang->testcase->module ?></th>
      <th class='w-100px'><?php common::printOrderLink('type',  $orderBy, $vars, $lang->testcase->type)?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($cases as $case):?>
    <tr class='text-center'>
      <td class='cell-id'>
        <input type='checkbox' name='caseIdList[<?php echo $case->id?>]' value='<?php echo $case->id;?>' />
        <?php if(!common::printLink('testcase', 'view', "caseID=$case->id", sprintf('%03d', $case->id))) printf('%03d', $case->id);?>
      </td>
      <?php if($branches):?>
      <td><?php echo html::select("branch[{$case->id}]", $branches, $branch, "class='form-control'")?></td>
      <?php endif;?>
      <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo $case->pri == '0' ? '' : zget($lang->testcase->priList, $case->pri, $case->pri);?></span></td>
      <td class='text-left nobr'><?php if(!common::printLink('testcase', 'view', "caseID=$case->id", $case->title)) echo $case->title;?></td>
      <td class='text-left'><?php echo zget($libModules, $case->module, '');?></th>
      <td class='text-left' data-module='<?php echo $case->module?>' style='overflow:visible'><?php echo html::select("module[{$case->id}]", $modules, 0, "class='from-control chosen' onchange='setModule(this)'");?></th>
      <td><?php echo zget($lang->testcase->typeList, $case->type);?></th>
    </tr>
    <?php endforeach;?>
    <tfoot>
      <tr>
        <td colspan='<?php echo empty($branches) ? 6 : 7?>'>
          <div class='table-actions clearfix'>
            <?php echo html::selectButton();?>
            <?php echo html::submitButton($lang->testcase->import);?>
            <?php echo html::linkButton($lang->goback, $this->session->caseList);?>
          </div>
          <?php $pager->show();?>
        </td>
      </tr>
    </tfoot>
    </tbody>
  </table>
  <div></div>
</form>
<?php js::set('productID', $productID)?>
<?php js::set('branch', $branch)?>
<?php include '../../common/view/footer.html.php';?>
