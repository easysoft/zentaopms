<?php

/**
 * The browse view file of zanode module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     zanode
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php js::set('showFeature', false);?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php'; ?>
<div id='mainContent' class='main-table'>
  <?php $vars = "id={$hostID}&orderBy=%s"; ?>
    <?php if (empty($nodeList)) : ?>
      <div class="table-empty-tip">
        <p>
          <span class="text-muted"><?php echo $lang->zanode->empty; ?></span>
          <?php if (common::hasPriv('zanode', 'create')) common::printLink('zanode', 'create', '', '<i class="icon icon-plus"></i> ' . $lang->zanode->create, '', 'class="btn btn-info"'); ?>
        </p>
      </div>
    <?php else : ?>
      <table class='table has-sort-head table-fixed' id='nodeList'>
        <thead>
          <tr>
            <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->zahost->name);?></th>
            <th class='c-cpu'><?php common::printOrderLink('cpuCores', $orderBy, $vars, $lang->zanode->cpuCores);?></th>
            <th class='c-memory'><?php common::printOrderLink('memory', $orderBy, $vars, $lang->zanode->memory);?></th>
            <th class='c-diskSize'><?php common::printOrderLink('diskSize', $orderBy, $vars, $lang->zanode->diskSize);?></th>
            <th class='c-os'><?php common::printOrderLink('osName', $orderBy, $vars, $lang->zanode->osName);?></th>
            <th class='c-status'><?php common::printOrderLink('status', $orderBy, $vars, $lang->zanode->status);?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($nodeList as $node) : ?>
            <tr>
              <td title='<?php echo $node->name ?>'><?php echo html::a($this->inlink('view', "id=$node->id"), $node->name, '_blank', ""); ?></td>
              <td><?php echo zget($config->zanode->os->cpuCores, $node->cpuCores); ?></td>
              <td><?php echo $node->memory . $this->lang->zahost->unitList['GB']; ?></td>
              <td><?php echo $node->diskSize . $this->lang->zahost->unitList['GB']; ?></td>
              <td><?php echo $node->osName; ?></td>
              <td><?php echo zget($lang->zanode->statusList, $node->status); ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

<?php endif; ?>
</div>

<?php include $app->getModuleRoot() . 'common/view/footer.html.php'; ?>
<script>
    // $('body').addClass('body-modal')
</script>