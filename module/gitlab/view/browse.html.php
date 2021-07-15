<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('gitlab', 'create')) common::printLink('gitlab', 'create', "", "<i class='icon icon-plus'></i> " . $lang->gitlab->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->gitlab->id);?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->name);?></th>
          <th class='text-left'><?php common::printOrderLink('url', $orderBy, $vars, $lang->gitlab->url);?></th>
          <th class='w-150px c-actions-4'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabList as $id => $gitlab): ?>
        <tr>
          <td class='text-center'><?php echo $id;?></td>
          <td class='text' title='<?php echo $gitlab->name;?>'><?php echo $gitlab->name;?></td>
          <td class='text' title='<?php echo $gitlab->url;?>'><?php echo $gitlab->url;?></td>
          <td class='c-actions text-left'>
            <?php
            common::printIcon('gitlab', 'edit', "gitlabID=$id", '', 'list',  'edit');
            common::printIcon('gitlab', 'bindUser', "id=$id", '', 'list',  'group');
            if(common::hasPriv('gitlab', 'delete')) echo html::a($this->createLink('gitlab', 'delete', "gitlabID=$id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->gitlab->delete}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($gitlabList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
