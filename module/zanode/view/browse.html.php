<?php

/**
 * The browse view file of zanode module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      xiawenlong <xiawenlong@cnezsoft.com>
 * @package     zanode
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php'; ?>
<?php js::set('confirmDelete', $lang->zanode->confirmDelete) ?>
<?php js::set('confirmBoot', $lang->zanode->confirmBoot) ?>
<?php js::set('confirmReboot', $lang->zanode->confirmReboot) ?>
<?php js::set('confirmShutdown', $lang->zanode->confirmShutdown) ?>
<?php js::set('actionSuccess', $lang->zanode->actionSuccess) ?>
<div id='mainMenu' class='clearfix'>
  <div class='pull-left btn-toolbar'>
    <?php echo html::a($this->createLink('zanode', 'browse'), "<span class='text'>{$lang->zanode->all}</span>", '', "class='btn btn-link btn-active-text'"); ?>
    <a href='#' id='bysearchTab' class='btn btn-link querybox-toggle'><i class='icon-search icon'></i>&nbsp;<?php echo $lang->zanode->byQuery; ?></a>
  </div>

  <?php if (common::hasPriv('zanode', 'create')) : ?>
    <div class="btn-toolbar pull-right" id='createActionMenu'>
      <?php
      $misc = "class='btn btn-primary' data-width='600px'";
      $link = $this->createLink('zanode', 'create');
      echo html::a($link, "<i class='icon icon-plus'></i>" . $lang->zanode->create, '', $misc);
      ?>
    </div>
  <?php endif; ?>
</div>
<div id='queryBox' class='cell <?php if ($browseType == 'bysearch') echo 'show'; ?>' data-module='zanode'></div>
<div id='mainContent' class='main-table'>
  <?php $vars = "browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
  <div class="table-responsive">
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
            <th class='c-id'><?php common::printOrderLink('t1.id', $orderBy, $vars, $lang->idAB); ?></th>
            <th class='c-name'><?php common::printOrderLink('t1.name', $orderBy, $vars, $lang->zanode->name); ?></th>
            <th class='c-ip'><?php common::printOrderLink('t1.extranet', $orderBy, $vars, $lang->zanode->extranet); ?></th>
            <th class='c-cpu'><?php common::printOrderLink('t1.cpuCores', $orderBy, $vars, $lang->zanode->cpuCores); ?></th>
            <th class='c-memory'><?php common::printOrderLink('t1.memory', $orderBy, $vars, $lang->zanode->memory); ?></th>
            <th class='c-number'><?php common::printOrderLink('t1.diskSize', $orderBy, $vars, $lang->zanode->diskSize); ?></th>
            <th class='c-os'><?php common::printOrderLink('t1.osName', $orderBy, $vars, $lang->zanode->osName); ?></th>
            <th class='c-status'><?php common::printOrderLink('t1.status', $orderBy, $vars, $lang->zanode->status); ?></th>
            <th class='c-host'><?php common::printOrderLink('t2.name', $orderBy, $vars, $lang->zanode->hostName); ?>
            <th class='c-actions-6 text-center'><?php echo $lang->actions ?></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($nodeList as $node) : ?>
            <tr>
              <td><?php echo $node->id; ?></td>
              <td title='<?php echo $node->name ?>'><?php echo html::a($this->inlink('view', "id=$node->id"), $node->name, '', ""); ?></td>
              <td><?php echo $node->extranet; ?></td>
              <td><?php echo zget($config->zanode->os->cpuCores, $node->cpuCores); ?></td>
              <td><?php echo $node->memory . $this->lang->zahost->unitList['GB']; ?></td>
              <td><?php echo $node->diskSize . $this->lang->zahost->unitList['GB']; ?></td>
              <td><?php echo $node->osName; ?></td>
              <td><?php echo zget($lang->zanode->statusList, $node->status); ?></td>
              <td title="<?php echo $node->hostName; ?>"><?php echo $node->hostName; ?></td>
              <td class='c-actions'>
                <?php
                $suspendAttr  = "title='{$lang->zanode->suspend}' target='hiddenwin'";
                $suspendAttr .= $node->status != 'running' && $node->status != 'wait' ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmSuspend}\")==false) return false;'";

                $resumeAttr  = "title='{$lang->zanode->resume}' target='hiddenwin'";
                $resumeAttr .= $node->status == 'running' || $node->status == 'wait' ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmResume}\")==false) return false;'";

                $rebootAttr  = "title='{$lang->zanode->reboot}' target='hiddenwin'";
                $rebootAttr .= $node->status == 'shutoff' ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmReboot}\")==false) return false;'";

                common::printLink('zanode', 'getVNC', "id={$node->id}", "<i class='icon icon-desktop'></i> ", '_blank', "title='{$lang->zanode->getVNC}' class='btn desktop  " . (in_array($node->status ,array('running', 'launch', 'wait')) ? '':'disabled') . "'", '');
                if($node->status == "suspend")
                {
                    common::printLink('zanode', 'resume', "zanodeID={$node->id}", "<i class='icon icon-back'></i> ", '', $resumeAttr);
                }
                else
                {
                    common::printLink('zanode', 'suspend', "zanodeID={$node->id}", "<i class='icon icon-pause'></i> ", '', $suspendAttr);
                }

                if($node->status == "shutoff")
                {
                    common::printLink('zanode', 'start', "zanodeID={$node->id}", "<i class='icon icon-magic'></i> ", '', "class='btn ' title='{$lang->zanode->boot}'");
                }
                else
                {
                    common::printLink('zanode', 'close', "zanodeID={$node->id}", "<i class='icon icon-off'></i> ", '', "class='btn ' title='{$lang->zanode->shutdown}'");
                }

                common::printLink('zanode', 'reboot', "zanodeID={$node->id}", "<i class='icon icon-restart'></i> ", '', $rebootAttr);
                common::printIcon('zanode', 'edit', "id={$node->id}", $node, 'list');

                echo "<div class='btn-group'>";
                echo "<button type='button' class='btn dropdown-toggle' data-toggle='context-dropdown' title='{$this->lang->more}'><i class='icon-ellipsis-v'></i></button>";
                echo "<ul class='dropdown-menu pull-right text-center' role='menu'>";
                common::printLink('zanode', 'createImage', "zanodeID={$node->id}", "<i class='icon icon-export'></i> ", '', "class='btn btn-action iframe createImage' title='{$lang->zanode->createImage}' data-width='55%'", '', true);
                common::printIcon('zanode', 'init', "hostID={$node->id}", $node, 'list', 'refresh', '', 'btn btn-action init', false, "data-placement='bottom'", $lang->zanode->init->title);
                common::printLink('zanode', 'delete', "zanodeID={$node->id}", "<i class='icon icon-trash'></i> ", '', "title='{$lang->zanode->destroy}' class='btn btn-action' target='hiddenwin'");
                echo "</ul>";
                echo "</div>";
                ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
  </div>
  <div class='table-footer'>
    <?php $pager->show('right', 'pagerjs'); ?>
  </div>
<?php endif; ?>
</div>

<?php include $app->getModuleRoot() . 'common/view/footer.html.php'; ?>
