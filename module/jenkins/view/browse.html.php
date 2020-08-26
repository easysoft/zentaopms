<?php
/**
 * The browse view file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2017 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     jenkins
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='jenkinsList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
          <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->jenkins->id); ?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->jenkins->name); ?></th>
          <th class='text-left'><?php common::printOrderLink('url', $orderBy, $vars, $lang->jenkins->url); ?></th>
          <th class='w-100px c-actions-4'><?php echo $lang->actions; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($jenkinsList as $id => $jenkins): ?>
        <tr>
          <td class='text-center'><?php echo $id; ?></td>
          <td class='text' title='<?php echo $jenkins->name; ?>'><?php echo $jenkins->name; ?></td>
          <td class='text' title='<?php echo $jenkins->url; ?>'><?php echo $jenkins->url; ?></td>
          <td class='c-actions text-left'>
            <?php
            common::printIcon('jenkins', 'edit', "jenkinsID=$id", '', 'list',  'edit');
            if(common::hasPriv('jenkins', 'delete')) echo html::a($this->createLink('jenkins', 'delete', "jenkinsID=$id"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->jenkins->delete}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if($jenkinsList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif; ?>
  </form>
</div>
<?php include '../../common/view/footer.html.php'; ?>
