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
<?php js::set('flow', $config->global->flow);?>
<?php if($config->global->flow != 'onlyTest'):?>
<div id="mainMenu" class='clearfix'>
  <div class="btn-toolbar pull-left">
    <a href class='btn btn-link btn-active-text'>
      <span class='text'><?php echo $lang->testsuite->browse?></span>
      <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>
    </a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('testsuite', 'create', "product=$productID", "<i class='icon icon-plus'></i> " . $lang->testsuite->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php endif;?>
<div id='mainContent' class='main-table' data-ride='table'>
  <?php if(empty($suites)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testsuite->noTestsuite;?></span>
      <?php if(common::hasPriv('testsuite', 'create')):?>
      <?php echo html::a($this->createLink('testsuite', 'create', "product=$productID"), "<i class='icon icon-plus'></i> " . $lang->testsuite->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class='table has-sort-head' id='suiteList'>
    <thead>
    <?php $vars = "productID=$productID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
      <tr>
        <th class='w-id text-left'>   <?php common::printOrderLink('id',   $orderBy, $vars, $lang->idAB);?></th>
        <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->testsuite->name);?></th>
        <th><?php echo $lang->testsuite->desc;?></th>
        <th class='w-90px'><?php common::printOrderLink('addedBy',   $orderBy, $vars, $lang->testsuite->addedBy);?></th>
        <th class='w-150px'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->testsuite->addedDate);?></th>
        <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($suites as $suite):?>
    <tr class='text-left'>
      <td><?php echo html::a(helper::createLink('testsuite', 'view', "suiteID=$suite->id"), sprintf('%03d', $suite->id));?></td>
      <td class='text-left' title="<?php echo $suite->name?>">
        <?php if($suite->type == 'public') echo "<span class='label label-success label-badge'>{$lang->testsuite->authorList['public']}</span> ";?>
        <?php if($suite->type == 'private') echo "<span class='label label-info label-badge'>{$lang->testsuite->authorList['private']}</span> ";?>
        <?php echo html::a(inlink('view', "suiteID=$suite->id"), $suite->name);?>
      </td>
      <td><?php echo $suite->desc;?></td>
      <td><?php echo zget($users, $suite->addedBy);?></td>
      <td><?php echo $suite->addedDate;?></td>
      <td class='c-actions'>
        <?php
        common::printIcon('testsuite', 'linkCase', "suiteID=$suite->id", $suite, 'list', 'link');
        common::printIcon('testsuite', 'edit',     "suiteID=$suite->id", $suite, 'list');

        if(common::hasPriv('testsuite', 'delete', $suite))
        {
            $deleteURL = $this->createLink('testsuite', 'delete', "suiteID=$suite->id&confirm=yes");
            echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"suiteList\", confirmDelete)", '<i class="icon icon-trash"></i>', '', "title='{$lang->testsuite->delete}' class='btn'");
        }
        ?>
      </td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
