<?php
/**
 * The view file of zahost module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao <zhaoke@cnezsoft.com>
 * @package     zahost
 * @version     $Id: view.html.php $
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<?php include $app->getModuleRoot() . 'common/view/kindeditor.html.php'; ?>
<?php js::set('hostID', $zahost->id);?>
<?php js::set('zahostLang', $lang->zahost);?>
<?php $browseLink = $this->session->zahostList ? $this->session->zahostList : $this->createLink('zahost', 'browse', "");?>
<?php $vars = "id={$zahost->hostID}&orderBy=%s";?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $browseLink, 'self', "data-app='{$app->tab}'", 'btn btn-secondary');?>
    <div class='divider'></div>
    <div class='page-title'>
      <span class='label label-id'><?php echo $zahost->id;?></span>
      <span class='text' title='<?php echo $zahost->name;?>'><?php echo $zahost->name;?></span>
      <?php if($zahost->deleted):?>
      <span class='label label-danger'><?php echo $lang->zahost->deleted;?></span>
      <?php endif; ?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class="col-8 main-col">
    <div class="cell">
      <div class="detail zahost-detail">
        <div class="detail-title"><?php echo $lang->zahost->baseInfo;?></div>
        <div class="detail-content article-content">
          <div class="main-row zanode-mt-8">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->extranet;?>:</div>
                <div class="col-7"><?php echo $zahost->extranet;?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->cpuCores;?>:</div>
                <div class="col-7"><?php echo $zahost->cpuCores . ' ' . $lang->zahost->cpuUnit;?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->zaHostType;?>:</div>
                <div class="col-7"><?php echo $lang->zahost->zaHostTypeList[$zahost->hostType];?></div>
              </div>
            </div>
          </div>
          <div class="main-row zanode-mt-8">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->status;?>:</div>
                <div class="col-7"><?php echo zget($lang->zahost->statusList, $zahost->status);?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->memory;?>:</div>
                <div class="col-7"><?php echo $zahost->memory . ' ' . $lang->zahost->unitList['GB'];;?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->vsoft;?>:</div>
                <div class="col-7"><?php echo zget($lang->zahost->softwareList, $zahost->vsoft);?></div>
              </div>
            </div>
          </div>
          <div class="main-row zanode-mt-8">
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->registerDate;?>:</div>
                <div class="col-7"><?php echo helper::isZeroDate($zahost->heartbeat) ? '' : $zahost->heartbeat;?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
                <div class="col-4 text-right"><?php echo $lang->zahost->diskSize;?>:</div>
                <div class="col-7"><?php echo $zahost->diskSize . ' ' . $lang->zahost->unitList['GB'];?></div>
              </div>
            </div>
            <div class="col-4">
              <div class="main-row">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="detail zahost-detail">
        <div class="detail-title"><?php echo $lang->zahost->desc;?></div>
        <div class="detail-content article-content"><?php echo !empty($zahost->desc) ? htmlspecialchars_decode($zahost->desc) : $lang->noData;?></div>
      </div>
    </div>
    <div class="cell">
      <div class="detail zahost-detail">
        <div class="detail-title">
          <?php echo $lang->zahost->init->statusTitle;?>
          <button type='button' id='checkServiceStatus' class='btn btn-info'><i class="icon icon-refresh"></i> <span class='checkStatus'><?php echo $lang->zahost->init->checkStatus;?></span></button>
        </div>
        <div class="detail-content article-content load-indicator" id='serviceContent'>
          <div class="main-row">
            <div id="statusContainer">

            </div>
            <div class='result'>
              <?php $imageLink  = html::a($this->createLink('zahost', 'browseImage', "hostID=$zahost->id", '', true), $lang->zahost->image->downloadImage, '', "class='iframe'");?>
              <?php $createNode = html::a($this->createLink('zanode', 'create', "hostID=$zahost->id"), $lang->zahost->createZanode);?>
              <div class='hide init-success'><?php echo sprintf($lang->zahost->init->initSuccessNotice, $imageLink, $createNode);?></div>
              <div class='hide init-fail'>
                <?php echo $lang->zahost->init->initFailNotice;?>
                <textarea style="display:none;" id="initBash"><?php echo $initBash; ?></textarea>
                <div class="zahost-init">
                <?php echo "$initBash <button type='button' class='btn btn-info btn-mini btn-init-copy'><i class='icon-common-copy icon-copy' title='" . $lang->zahost->copy .  "'></i></button>"; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php if(commonModel::hasPriv('zanode', 'browse')):?>
    <div class="cell">
      <div class="detail">
        <div class="detail-title"><?php echo $lang->zahost->browseNode;?></div>
        <?php if(!empty($nodeList)): ?>
        <div class="detail-content article-content">
        <?php echo "<iframe width='100%' id='nodesIframe' src='" . $this->createLink('zanode', 'nodeList', "hostID=$zahost->id", '', true) . "' frameborder='no' allowfullscreen='true' mozallowfullscreen='true' webkitallowfullscreen='true' allowtransparency='true' scrolling='auto' onload='setIframeHeight(this)' style='min-height:300px;'></iframe>";?>
        </div>
        <?php else: ?>
        <div class="detail-content article-content"><?php echo $lang->noData;?></div>
        <?php endif; ?>
      </div>
      <?php
      $canBeChanged = common::canBeChanged('zahost', $zahost);
      if($canBeChanged) $actionFormLink = $this->createLink('action', 'comment', "objectType=zahost&objectID=$zahost->hostID");
      ?>
    </div>
    <?php endif; ?>
    <?php $this->printExtendFields($zahost, 'div', "position=left&inForm=0&inCell=1");?>
    <div class='main-actions'>
      <div class="btn-toolbar">
        <?php echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $browseLink, 'self', "data-app='{$app->tab}'", 'btn btn-secondary');?>
        <div class='divider'></div>
        <?php echo $this->zahost->buildOperateMenu($zahost, 'view');?>
      </div>
    </div>
  </div>
  <div class="col-4 side-col">
    <div class='cell'><?php include '../../common/view/action.html.php';?></div>
  </div>
</div>

<div id='mainActions' class='main-actions'>
  <?php common::printPreAndNext($browseLink);?>
</div>
<?php include '../../common/view/footer.html.php';?>
