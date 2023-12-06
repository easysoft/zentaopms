<?php

/**
 * The view file of zanode module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@cnezsoft.com>
 * @package     zanode
 * @version     $Id: view.html.php $
 * @link        https://www.zentao.net
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
<?php js::set('hostType', $zanode->hostType); ?>
<?php js::set('webRoot', getWebRoot());?>
<?php $browseLink = $this->session->zanodeList ? $this->session->zanodeList : $this->createLink('zanode', 'browse', "");?>
<?php
$vars    = "id={$zanode->id}&orderBy=%s";
$account = strpos(strtolower($zanode->osName), "windows") !== false ? $config->zanode->defaultWinAccount : $config->zanode->defaultAccount;
$ssh = $zanode->hostType == 'physics' ? ('ssh ' . $zanode->extranet) : ($zanode->ssh ? 'ssh ' . $account . '@' . $zanode->ip . ' -p ' . $zanode->ssh : '');
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
    <?php if($zanode->hostType == 'physics'):?>
      <div class="detail zanode-detail">
        <div class="detail-title"><?php echo $lang->zanode->baseInfo; ?></div>
        <div class="detail-content article-content">
          <div class="main-row zanode-mt-8">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zanode->osName; ?>:</div>
                <div class="col-7"><?php echo zget($config->zanode->linuxList, $zanode->osName, zget($config->zanode->windowsList, $zanode->osName)); ?></div>
              </div>
            </div>
            <div class="col-5">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->sshAddress; ?>:</div>
                <div class="col-8 node-not-wrap"><?php echo $ssh;?><?php echo $ssh ? " <button type='button' class='btn btn-info btn-mini btn-ssh-copy'><i class='icon-common-copy icon-copy' title='" . $lang->zanode->copy .  "'></i></button>" : ''; ?></div>
              </div>
              <textarea style="display:none;" id="ssh-copy"><?php echo $ssh; ?></textarea>
            </div>
            <div class="col-3">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->cpuCores; ?>:</div>
                <div class="col-8"><?php echo $zanode->cpuCores . ' ' . $lang->zanode->cpuUnit; ?></div>
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
                <div class="col-3 text-right"><?php echo $lang->zanode->memory; ?>:</div>
                <div class="col-8"><?php echo $zanode->memory; ?>&nbsp;GB</div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->diskSize; ?>:</div>
                <div class="col-8"><?php echo $zanode->diskSize; ?>&nbsp;GB</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php else: ?>
      <div class="detail zanode-detail">
        <div class="detail-title"><?php echo $lang->zanode->baseInfo; ?></div>
        <div class="detail-content article-content">
          <div class="main-row zanode-mt-8">
            <div class="col-3">
              <div class="main-row">
                <div class="col-5 text-right"><?php echo $lang->zanode->osName; ?>:</div>
                <div class="col-6"><?php echo $zanode->osName; ?></div>
              </div>
            </div>
            <div class="col-5">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->sshCommand; ?>:</div>
                <div class="col-8 node-not-wrap"><?php echo $ssh;?><?php echo $ssh ? " <button type='button' class='btn btn-info btn-mini btn-ssh-copy'><i class='icon-common-copy icon-copy' title='" . $lang->zanode->copy .  "'></i></button>" : ''; ?></div>
              </div>
              <textarea style="display:none;" id="ssh-copy"><?php echo $ssh; ?></textarea>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->cpuCores; ?>:</div>
                <div class="col-8"><?php echo $zanode->cpuCores . ' ' . $lang->zanode->cpuUnit; ?></div>
              </div>
            </div>
          </div>
          <div class="main-row zanode-mt-8">
            <div class="col-3">
              <div class="main-row">
                <div class="col-5 text-right"><?php echo $lang->zanode->status; ?>:</div>
                <div class="col-6"><?php echo zget($lang->zanode->statusList, $zanode->status); ?></div>
              </div>
            </div>
            <div class="col-5">
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
            <div class="col-3">
              <div class="main-row">
                <div class="col-5 text-right"><?php echo $lang->zanode->hostName; ?>:</div>
                <div class="col-6"><?php echo $zanode->hostName; ?></div>
              </div>
            </div>
            <div class="col-5">
              <div class="main-row">
                <div class="col-3 text-right"><?php echo $lang->zanode->defaultPwd; ?>:</div>
                <div class="col-8 default-pwd"><?php
                echo '<span id="pwd-text">' . str_repeat('*', strlen($config->zanode->defaultPwd)) . '</span>'
                 . ' '
                 . "<button type='button' class='btn btn-info btn-mini btn-pwd-copy'><i class='icon-common-copy icon-copy' title='" . $lang->zanode->copy .  "'></i></button>"
                 . "<button type='button' class='btn btn-info btn-mini btn-pwd-show'><i class='icon-common-eye icon-eye' title='" . $lang->zanode->showPwd .  "'></i></button>";
                ?></div>
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
      <?php endif ?>
      <div class="detail zanode-detail">
        <div class="detail-title"><?php echo $lang->zanode->desc; ?></div>
        <div class="detail-content article-content"><?php echo !empty($zanode->desc) ? htmlspecialchars_decode($zanode->desc) : $lang->noData; ?></div>
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
          <div class="detail-content article-content statusContainer load-indicator" id='serviceContent'>
          <?php if($zanode->hostType != 'physics'):?>
            <div class="service-status hide">
              <span class='dot-symbol dot-zenagent text-danger'>●</span>
              <span>&nbsp;ZenAgent &nbsp;
                <span class="zenagent-status"><?php echo $lang->zanode->initializing; ?></span>
              </span>
            </div>
          <?php endif ?>
            <div class="service-status hide">
              <span class='dot-symbol dot-ztf text-danger'>●</span>
              <span>&nbsp;ZTF &nbsp;
                <span class="ztf-status"><?php echo $lang->zanode->initializing; ?></span>&nbsp;
              </span>
            </div>
            <div class="status-notice hide">
              <span class='init-success hide'><?php echo sprintf($lang->zanode->init->initSuccessNoticeTitle, "<a id='jumpManual' href='javascript:;'>{$lang->zanode->manual}</a>", html::a(helper::createLink('testcase', 'automation', "", '', true), $lang->zanode->automation, '', "class='iframe' title='{$lang->zanode->automation}' data-width='800px'", '')); ?></span>
              <?php if($zanode->hostType == 'physics'):?>
              <div class='hide init-fail'>
                <?php echo $zanode->hostType == 'physics' ? $lang->zanode->init->initFailNoticeOnPhysics : $lang->zanode->init->initFailNotice;?>
                <textarea style="display:none;" id="initBash"><?php echo $initBash; ?></textarea>
                <div class="zanode-init">
                <?php echo "$initBash <button type='button' class='btn btn-info btn-mini btn-init-copy'><i class='icon-common-copy icon-copy' title='" . $lang->zanode->copy .  "'></i></button>"; ?>
                </div>
              </div>
              <?php endif?>
            </div>
          </div>
        </div>
      </div>
      <?php if(common::hasPriv('zanode', 'browseSnapshot') && $zanode->hostType == ''):?>
      <div class="cell">
        <div class="detail zanode-detail">
          <div class="detail-title">
            <?php echo $lang->zanode->browseSnapshot;?>
            <div class="btn-toolbar pull-right" id='createActionMenu'>
              <?php
              if($zanode->status == 'running'){
                $snapshotAttr = "title='{$lang->zanode->createSnapshot}'";
                $snapshotAttr .= $zanode->status != 'running' ? ' class="btn btn-snap-create disabled"' : ' class="btn btn-primary btn-snap-create iframe"';
                common::printLink('zanode', 'createSnapshot', "zanodeID={$zanode->id}", "<i class='icon icon-plus'></i> " . $lang->zanode->createSnapshot, '', $snapshotAttr, true, true);
              }
              ?>
            </div>
          </div>
          <?php if(!empty($snapshotList)): ?>
          <div class="detail-content article-content">
          <?php echo "<iframe width='100%' id='nodesIframe' src='" . $this->createLink('zanode', 'browseSnapshot', "nodeID=$zanode->id", '', true) . "' frameborder='no' allowfullscreen='true' mozallowfullscreen='true' webkitallowfullscreen='true' allowtransparency='true' scrolling='auto' style='min-height:300px;'></iframe>";?>
          </div>
          <?php else: ?>
          <div class="detail-content article-content"><?php echo $lang->noData; ?></div>
          <?php endif; ?>
        </div>
      </div>
      <?php endif; ?>
    <?php $this->printExtendFields($zanode, 'div', "position=left&inForm=0&inCell=1"); ?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $browseLink, 'self', "data-app='{$app->tab}'", 'btn btn-secondary');?>
        <div class='divider'></div>
        <?php
        if (empty($zanode->deleted)) {
          $suspendAttr  = "title='{$lang->zanode->suspend}' target='hiddenwin'";
          $suspendAttr .= $zanode->hostType == 'physics' || $zanode->status != 'running' ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmSuspend}\")==false) return false;'";

          $resumeAttr  = "title='{$lang->zanode->resume}' target='hiddenwin'";
          $resumeAttr .= $zanode->hostType == 'physics' || $zanode->status == 'running' ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmResume}\")==false) return false;'";

          $rebootAttr  = "title='{$lang->zanode->reboot}' target='hiddenwin'";
          $rebootAttr .= $zanode->hostType == 'physics' || in_array($zanode->status, array('wait', 'creating_img', 'creating_snap', 'restoring', 'shutoff')) ? ' class="btn disabled"' : "class='btn' target='hiddenwin' onclick='if(confirm(\"{$lang->zanode->confirmReboot}\")==false) return false;'";

          $closeAttr = "title='{$lang->zanode->shutdown}'";
          $closeAttr .= $zanode->hostType == 'physics' || in_array($zanode->status, array('wait', 'creating_img', 'creating_snap', 'restoring')) ? ' class="btn disabled"' : ' class="btn iframe"';

          $startAttr = "title='{$lang->zanode->boot}'";
          $startAttr .= $zanode->hostType == 'physics' || in_array($zanode->status, array('wait', 'creating_img', 'creating_snap', 'restoring')) ? ' class="btn disabled"' : ' class="btn iframe"';

          $snapshotAttr = "title='{$lang->zanode->createSnapshot}'";
          $snapshotAttr .= $zanode->hostType == 'physics' || $zanode->status != 'running' ? ' class="btn disabled"' : ' class="btn iframe"';
          common::printLink('zanode', 'getVNC', "id={$zanode->id}", "<i class='icon icon-remote'></i> " . $lang->zanode->getVNC, in_array($zanode->status ,array('running', 'launch', 'wait')) ? '_blank' : '', "title='{$lang->zanode->getVNC}' class='btn desktop  " . ($zanode->hostType == '' && in_array($zanode->status ,array('running', 'launch', 'wait')) ? '':'disabled') . "'", '');

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
              common::printLink('zanode', 'start', "zanodeID={$zanode->id}", "<i class='icon icon-play'></i> " . $lang->zanode->bootNode, '', $startAttr);
          }
          else
          {
              common::printLink('zanode', 'close', "zanodeID={$zanode->id}", "<i class='icon icon-off'></i> " . $lang->zanode->shutdownNode, '', $closeAttr);
          }

          common::printLink('zanode', 'reboot', "zanodeID={$zanode->id}", "<i class='icon icon-restart'></i> " . $lang->zanode->rebootNode, '', $rebootAttr);
          common::printLink('zanode', 'createSnapshot', "zanodeID={$zanode->id}", "<img src='static/svg/snapshot.svg' /> " . $lang->zanode->createSnapshot, '', $snapshotAttr, true, true);

        }
        ?>
        <div class='divider'></div>
        <?php echo $this->zanode->buildOperateMenu($zanode, 'view'); ?>
        <a id='editSnapshot' href='' class='iframe'></a>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class='cell'><?php include '../../common/view/action.html.php'; ?></div>

    <div id='mainActions' class='main-actions'>
      <?php common::printPreAndNext($browseLink); ?>
    </div>
    <?php include '../../common/view/footer.html.php'; ?>
