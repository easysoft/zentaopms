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
<?php js::set('nodeID', $zanode->id) ?>
<?php js::set('zanodeLang', $lang->zanode); ?>
<?php js::set('nodeStatus', $zanode->status); ?>
<?php $browseLink = $this->session->zanodeList ? $this->session->zanodeList : $this->createLink('zanode', 'browse', ""); ?>
<?php
$vars    = "id={$zanode->id}&orderBy=%s";
$account = strpos($zanode->osName, "windows") ? $config->zanode->defaultWinAccount : $config->zanode->defaultAccount;
?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $browseLink, 'self', "data-app='{$app->tab}'", 'btn btn-secondary');?>
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
          <div class="main-row zanode-mt-8">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->osName; ?>:</div>
                <div class="col-7"><?php echo $zanode->osName; ?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->sshAddress; ?>:</div>
                <div class="col-8 node-not-wrap">ssh <?php echo $account . '@' . $zanode->ip . ' -p ' . $zanode->ssh; ?><?php echo " <button type='button' class='btn btn-info btn-mini btn-ssh-copy'><i class='icon-common-copy icon-copy' title='" . $lang->zanode->copy .  "'></i></button>"; ?></div>
              </div>
              <textarea style="display:none;" id="ssh-copy">ssh <?php echo $account . '@' . $zanode->ip . ' -p ' . $zanode->ssh; ?></textarea>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->cpuCores; ?>:</div>
                <div class="col-8"><?php echo $zanode->cpuCores . ' ' . $lang->zahost->cpuUnit; ?></div>
              </div>
            </div>
          </div>
          <div class="main-row zanode-mt-8">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->status; ?>:</div>
                <div class="col-7"><?php echo zget($lang->zanode->statusList, $zanode->status); ?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->defaultUser; ?>:</div>
                <div class="col-8"><?php echo $account; ?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->memory; ?>:</div>
                <div class="col-8"><?php echo $zanode->memory; ?>&nbsp;GB</div>
              </div>
            </div>
          </div>
          <div class="main-row main-row-last zanode-mt-8">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->hostName; ?>:</div>
                <div class="col-7"><?php echo $zanode->hostName; ?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->defaultPwd; ?>:</div>
                <div class="col-8"><?php echo $config->zanode->defaultPwd . ' ' . "<button type='button' class='btn btn-info btn-mini btn-pwd-copy'><i class='icon-common-copy icon-copy' title='" . $lang->zanode->copy .  "'></i></button>"; ?></div>
                <textarea style="display:none;" id="pwd-copy"><?php echo $config->zanode->defaultPwd; ?></textarea>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->diskSize; ?>:</div>
                <div class="col-8"><?php echo $zanode->diskSize; ?>&nbsp;GB</div>
              </div>
            </div>
            <div class="col-4"></div>
          </div>
        </div>
      </div>

      <?php
      $canBeChanged = common::canBeChanged('zanode', $zanode);
      if ($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=zanode&objectID=$zanode->id");
      ?>
    </div>
    <div class="cell">
        <div class="detail zanode-detail">
          <div class="detail-title status-container">
            <?php echo $lang->zanode->init->statusTitle; ?>
            <button type='button' id='checkServiceStatus' class='btn btn-info'><i class="icon icon-refresh"></i> <span class='checkStatus'><?php echo $lang->zanode->init->checkStatus;?></span></button>
          </div>
          <div class="detail-content statusContainer load-indicator" id='serviceContent'>
            <div class="service-status">
              <span class='dot-symbol dot-zenagent text-danger'>●</span>
              <span>&nbsp;&nbsp;ZenAgent &nbsp;
                <span class="zenagent-status"><?php echo $lang->zanode->initializing; ?></span>
              </span>
            </div>
            <div class="service-status">
              <span class='dot-symbol dot-ztf text-danger'>●</span>
              <span>&nbsp;&nbsp;ZTF &nbsp;
                <span class="ztf-status"><?php echo $lang->zanode->initializing; ?></span>&nbsp;
                <a class='node-init-install hide' target='hiddenwin' href='javascript:;' data-href='<?php echo $this->createLink('zanode', 'ajaxInstallService', 'nodeID=' . $zanode->id . '&service=ztf');?>'><i class="icon icon-download icon-sm ztf-install-icon"></i><span class="ztf-install"><?php echo $lang->zanode->install ?></span></a>
              </span>
            </div>
            <div class="status-notice">
              <span class='init-success hide'><?php echo sprintf($lang->zanode->init->initSuccessNoticeTitle, "<a id='jumpManual' href='javascript:;'>{$lang->zanode->manual}</a>", html::a(helper::createLink('testcase', 'automation', "", '', true), $lang->zanode->automation, '', "class='iframe' title='{$lang->zanode->automation}' data-width='50%'", '')); ?></span>
              <span class='init-fail hide'><?php echo $lang->zanode->init->initFailNoticeTitle . '<br/>' . $lang->zanode->init->initFailNoticeDesc;?></span>
            </div>
          </div>
        </div>
      </div>
      <div class="cell">
        <div class="detail zanode-detail">
          <div class="detail-title"><?php echo $lang->zanode->desc; ?></div>
          <div class="detail-content article-content"><?php echo !empty($zanode->desc) ? htmlspecialchars_decode($zanode->desc) : $lang->noData; ?></div>
        </div>
      </div>
    <?php $this->printExtendFields($zanode, 'div', "position=left&inForm=0&inCell=1"); ?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $browseLink, 'self', "data-app='{$app->tab}'", 'btn btn-secondary');?>
        <div class='divider'></div>
        <?php
        if (empty($zanode->deleted)) {
          $suspendAttr  = "title='{$lang->zanode->suspend}' target='hiddenwin'";
          $suspendAttr .= $zanode->status != 'running' && $zanode->status != 'wait' ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmSuspend}\")==false) return false;'";

          $resumeAttr  = "title='{$lang->zanode->resume}' target='hiddenwin'";
          $resumeAttr .= $zanode->status == 'running' || $zanode->status == 'wait' ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmResume}\")==false) return false;'";

          $rebootAttr  = "title='{$lang->zanode->reboot}' target='hiddenwin'";
          $rebootAttr .= $zanode->status == 'shutoff' ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmReboot}\")==false) return false;'";
          common::printLink('zanode', 'getVNC', "id={$zanode->id}", "<i class='icon icon-remote'></i> " . $lang->zanode->getVNC, in_array($zanode->status ,array('running', 'launch', 'wait')) ? '_blank' : '', "title='{$lang->zanode->getVNC}' class='btn desktop  " . (in_array($zanode->status ,array('running', 'launch', 'wait')) ? '':'disabled') . "'", '');

          if($zanode->status == "suspend")
          {
              common::printLink('zanode', 'resume', "zanodeID={$zanode->id}", "<i class='icon icon-resume'></i> " . $lang->zanode->resumeNode, '', $resumeAttr);
          }
          else
          {
              common::printLink('zanode', 'suspend', "zanodeID={$zanode->id}", "<i class='icon icon-moon'></i> " . $lang->zanode->suspendNode, '', $suspendAttr);
          }

          if($zanode->status == "shutoff")
          {
              common::printLink('zanode', 'start', "zanodeID={$zanode->id}", "<i class='icon icon-play'></i> " . $lang->zanode->bootNode, '', "title='{$lang->zanode->boot}' class='btn '");
          }
          else
          {
              common::printLink('zanode', 'close', "zanodeID={$zanode->id}", "<i class='icon icon-off'></i> " . $lang->zanode->shutdownNode, '', "title='{$lang->zanode->shutdown}' class='btn '");
          }

          common::printLink('zanode', 'reboot', "zanodeID={$zanode->id}", "<i class='icon icon-restart'></i> " . $lang->zanode->rebootNode, '', $rebootAttr);

        }
        ?>
        <div class='divider'></div>
        <?php echo $this->zanode->buildOperateMenu($zanode, 'view'); ?>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class='cell'><?php include '../../common/view/action.html.php'; ?></div>

    <div id='mainActions' class='main-actions'>
      <?php common::printPreAndNext($browseLink); ?>
    </div>
    <?php include '../../common/view/footer.html.php'; ?>
