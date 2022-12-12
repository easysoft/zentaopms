<?php

/**
 * The view file of zanode module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@cnezsoft.com>
 * @package     zanode
 * @version     $Id: view.html.php $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php'; ?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php'; ?>
<?php js::set('confirmDelete', $lang->zanode->confirmDelete) ?>
<?php js::set('confirmBoot', $lang->zanode->confirmBoot) ?>
<?php js::set('confirmReboot', $lang->zanode->confirmReboot) ?>
<?php js::set('confirmShutdown', $lang->zanode->confirmShutdown) ?>
<?php js::set('actionSuccess', $lang->zanode->actionSuccess) ?>
<?php $browseLink = $this->session->zanodeList ? $this->session->zanodeList : $this->createLink('zanode', 'browse', ""); ?>
<?php $vars = "id={$zanode->id}&orderBy=%s"; ?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, "data-app='{$app->tab}'", 'btn btn-secondary'); ?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $zanode->id; ?></span>
      <span class='text' title='<?php echo $zanode->name; ?>'><?php echo $zanode->name; ?></span>
      <?php if ($zanode->deleted) : ?>
        <span class='label label-danger'><?php echo $lang->zanode->deleted; ?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class="col-8 main-col">
    <div class="cell">
      <div class="detail zanode-detail">
        <div class="detail-title"><?php echo $lang->zanode->view; ?></div>
        <div class="detail-content article-content">
          <div class="main-row">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right">IP:</div>
                <div class="col-8"><?php echo $zanode->extranet; ?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->osName; ?>:</div>
                <div class="col-8"><?php echo $zanode->osName; ?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->memory; ?>:</div>
                <div class="col-8"><?php echo $zanode->memory; ?></div>
              </div>
            </div>
          </div>
          <div class="main-row">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->hostName; ?>:</div>
                <div class="col-8"><?php echo $zanode->hostName; ?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->cpuCores; ?>:</div>
                <div class="col-8"><?php echo $zanode->cpuCores; ?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->diskSize; ?>:</div>
                <div class="col-8"><?php echo $zanode->diskSize; ?></div>
              </div>
            </div>
          </div>
          <div class="main-row">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->status; ?>:</div>
                <div class="col-8"><?php echo zget($lang->zanode->statusList, $zanode->status); ?></div>
              </div>
            </div>
            <div class="col-4"></div>
            <div class="col-4"></div>
            <div class="col-4"></div>
          </div>
        </div>
      </div>
      <div class="detail zanode-detail">
        <div class="detail-title"><?php echo $lang->zanode->desc; ?></div>
        <div class="detail-content article-content"><?php echo !empty($zanode->desc) ? $zanode->desc : $lang->noData; ?></div>
      </div>
      <?php
      $canBeChanged = common::canBeChanged('zanode', $zanode);
      if ($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=zanode&objectID=$zanode->id");
      ?>
    </div>
    <?php $this->printExtendFields($zanode, 'div', "position=left&inForm=0&inCell=1"); ?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php echo html::backButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', 'btn btn-secondary'); ?>
        <div class='divider'></div>
        <?php
        if (empty($zanode->deleted))
        {
          if($zanode->status == "running"){
            common::printLink('zanode', 'suspend', "id={$zanode->id}", "<i class='icon icon-restart'></i> " . $lang->zanode->suspend, '', "title='{$lang->zanode->suspend}' class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmSuspend}\")==false) return false;'");
          }
          elseif($zanode->status == "suspend")
          {
            common::printLink('zanode', 'resume', "id={$zanode->id}", "<i class='icon icon-restart'></i> " . $lang->zanode->resume, '', "title='{$lang->zanode->resume}' class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmResume}\")==false) return false;'");
          }
          common::printLink('zanode', 'getVNC', "id={$zanode->id}", "<i class='icon icon-desktop'></i> " . $lang->zanode->getVNC, '', "title='{$lang->zanode->getVNC}' class='btn iframe " . (common::hasPriv('zahost', 'getVNC') && $zanode->status == 'running' ? '':'disabled') . "'", '', true);
        }
        ?>
        <div class='divider'></div>
        <?php echo $this->zanode->buildOperateMenu($zanode, 'view'); ?>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class='cell'><?php include '../../common/view/action.html.php'; ?></div>
  </div>
</div>

<div id='mainActions' class='main-actions'>
  <?php common::printPreAndNext($browseLink); ?>
</div>
<?php include '../../common/view/footer.html.php'; ?>
