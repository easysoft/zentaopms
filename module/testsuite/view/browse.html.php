<?php
/**
 * The browse view file of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: browse.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('confirmDelete', $lang->testsuite->confirmDelete)?>
<?php js::set('flow', $this->config->global->flow);?>
<?php if($this->config->global->flow != 'onlyTest'):?>
<div id="titlebar">
  <div class="heading"> <?php echo $lang->testsuite->browse?> </div>
  <div class="actions"><?php common::printIcon('testsuite', 'create', "product=$productID");?></div>
</div>
<?php endif;?>
<table class='table tablesorter table-fixed' id='suiteList'>
  <thead>
  <?php $vars = "productID=$productID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
    <tr>
      <th class='w-id text-left'>   <?php common::printOrderLink('id',      $orderBy, $vars, $lang->idAB);?></th>
      <th class='w-200px text-left'><?php common::printOrderLink('name',    $orderBy, $vars, $lang->testsuite->name);?></th>
      <th><?php echo $lang->testsuite->desc;?></th>
      <th class='w-80px'><?php common::printOrderLink('addedBy',   $orderBy, $vars, $lang->testsuite->addedBy);?></th>
      <th class='w-150px'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->testsuite->addedDate);?></th>
      <th class='w-100px {sorter:false} text-left'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($suites as $suite):?>
  <tr class='text-left'>
    <td><?php echo sprintf('%03d', $suite->id);?></td>
    <td class='text-left' title="<?php echo $suite->name?>">
      <?php if($suite->type == 'public') echo "<span class='label label-info'>{$lang->testsuite->authorList['public']}</span> ";?>
      <?php echo html::a(inlink('view', "suiteID=$suite->id"), $suite->name);?>
    </td>
    <td><?php echo $suite->desc;?></td>
    <td><?php echo zget($users, $suite->addedBy);?></td>
    <td><?php echo $suite->addedDate;?></td>
    <td class='text-center'>
      <?php
      common::printIcon('testsuite', 'linkCase', "suiteID=$suite->id", '', 'list', 'link');
      common::printIcon('testsuite', 'edit',     "suiteID=$suite->id", '', 'list');

      if(common::hasPriv('testsuite', 'delete'))
      {
          $deleteURL = $this->createLink('testsuite', 'delete', "suiteID=$suite->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"suiteList\",confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->testsuite->delete}' class='btn-icon'");
      }
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
