<?php
/**
 * The browse view file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     jenkins
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php'; ?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a($this->createLink('jenkins', 'browse'), "<span class='text'>{$lang->jenkins->serverList}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('jenkins', 'create')) common::printLink('jenkins', 'create', "", "<i class='icon icon-plus'></i> " . $lang->jenkins->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($jenkinsList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(common::hasPriv('jenkins', 'create')):?>
    <?php echo html::a($this->createLink('jenkins', 'create'), "<i class='icon icon-plus'></i> " . $lang->jenkins->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='jenkinsList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->jenkins->id); ?></th>
          <th class='c-name w-300px'><?php common::printOrderLink('name', $orderBy, $vars, $lang->jenkins->name); ?></th>
          <th class='text'><?php common::printOrderLink('url', $orderBy, $vars, $lang->jenkins->url); ?></th>
          <th class='c-actions-2'><?php echo $lang->actions; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($jenkinsList as $id => $jenkins): ?>
        <tr>
          <td class='text'><?php echo $id; ?></td>
          <td class='text' title='<?php echo $jenkins->name; ?>'><?php echo $jenkins->name; ?></td>
          <td class='text' title='<?php echo $jenkins->url; ?>'><?php echo html::a($jenkins->url, $jenkins->url, '_blank'); ?></td>
          <td class='c-actions'>
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
<?php endif;?>
<?php include '../../common/view/footer.html.php'; ?>
