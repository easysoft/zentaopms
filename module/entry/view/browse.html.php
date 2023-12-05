<?php
/**
 * The browse view file of entry module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     entry
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-right'>
    <?php echo html::a($this->createLink('entry', 'create'), "<i class='icon icon-plus'></i> {$lang->entry->create}", '', "class='btn btn-primary'"); ?>
  </div>
</div>
<?php js::set('confirmDelete', $lang->entry->confirmDelete);?>
<div id='mainContent'>
  <form id='ajaxForm' class='main-table' method='post' data-ride='table'>
    <table id='entryList' class='table has-sort-head table-fix'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal=$pager->recTotal&recPerPage=$pager->recPerPage&pageID=$pager->pageID";?>
          <th class='c-id'><?php  common::printOrderLink('id',   $orderBy, $vars, $lang->entry->id);?></th>
          <th><?php common::printOrderLink('name', $orderBy, $vars, $lang->entry->name);?></th>
          <th class='c-code'><?php common::printOrderLink('code', $orderBy, $vars, $lang->entry->code);?></th>
          <th class='c-key'><?php common::printOrderLink('key',  $orderBy, $vars, $lang->entry->key);?></th>
          <th class='c-ip'><?php common::printOrderLink('ip',   $orderBy, $vars, $lang->entry->ip);?></th>
          <th><?php echo common::printOrderLink('desc', $orderBy, $vars, $lang->entry->desc);?></th>
          <th class='c-actions-3'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($entries as $id => $entry):?>
        <tr>
          <td><?php echo $id;?></td>
          <td title='<?php echo $entry->name;?>'><?php echo $entry->name;?></td>
          <td><?php echo $entry->code;?></td>
          <td title='<?php echo $entry->key;?>'><?php echo $entry->key;?></td>
          <td title='<?php echo $entry->ip;?>'><?php echo $entry->ip;?></td>
          <td title='<?php echo $entry->desc;?>'><?php echo $entry->desc;?></td>
          <td class='c-actions'>
            <?php
            common::printIcon('entry', 'log', "entryID=$id", '', 'list', 'file-text');
            common::printIcon('entry', 'edit', "entryID=$id", '', 'list');
            if(common::hasPriv('entry', 'delete'))
            {
                $deleteURL = $this->createLink('entry', 'delete', "entryID=$id&confirm=yes");
                echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"entryList\", confirmDelete)", '<i class="icon-trash"></i>', '', "title='{$lang->entry->delete}' class='btn'");
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($entries):?>
    <div class='table-footer'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
