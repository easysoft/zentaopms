<?php
/**
 * The allLibs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php $spliter = (empty($this->app->user->feedback) && !$this->cookie->feedbackView) ? true : false;?>
<div class="main-row <?php if($spliter) echo 'split-row';?>" id="mainRow">
  <?php if($spliter):?>
  <?php include './side.html.php';?>
  <?php endif;?>
  <?php if($this->cookie->browseType == 'bylist'):?>
  <?php include dirname(__FILE__) . '/alllibsbylist.html.php';?>
  <?php else:?>
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <?php if($type == 'custom')  $panelTitle = $lang->doc->custom;?>
          <?php if($type == 'product') $panelTitle = $lang->productCommon;?>
          <?php if($type == 'project') $panelTitle = $lang->projectCommon;?>
          <i class="icon icon-folder-open-o text-muted"></i> <?php echo $panelTitle;?>
        </div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
            <?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-bars'></i>", '', "title='{$lang->doc->browseTypeList['list']}' class='btn btn-icon'");?>
            <?php echo html::a('javascript:setBrowseType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='{$lang->doc->browseTypeList['grid']}' class='btn btn-icon text-primary'");?>
          </div>
        </nav>
      </div>
      <div class="panel-body">
        <div class="row row-grid files-grid" data-size="300">
          <?php if($type == 'product') $icon = 'icon-product text-secondary';?>
          <?php if($type == 'project') $icon = 'icon-project text-green';?>
          <?php if($type == 'custom')  $icon = 'icon-folder text-yellow';?>
          <?php foreach($libs as $lib):?>
          <?php $link = $type != 'custom' ? $this->createLink('doc', 'objectLibs', "type=$type&objectID=$lib->id") : $this->createLink('doc', 'browse', "libID=$lib->id");?>
          <div class="col">
            <a class="file" href="<?php echo $link;?>">
              <i class="file-icon icon <?php echo $icon;?>"></i>
              <div class="file-name"><?php echo ($type == 'custom' && strpos($lib->collector, $this->app->user->account) !== false ? "<i class='icon icon-star text-yellow'></i> " : '') . $lib->name;?></div>
              <?php if($type == 'custom'):?>
              <div class="text-primary file-info"><?php echo $itemCounts[$lib->id] . $lang->doc->item;?></div>
              <?php else:?>
              <div class="text-primary file-info"><?php echo count($subLibs[$lib->id]) . $lang->doc->item;?></div>
              <?php endif;?>
            </a>
            <?php if($type == 'custom'):?>
            <div class="actions">
              <?php $star = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
              <?php $collectTitle = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
              <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$lib->id&objectType=doclib");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
              <?php common::printLink('doc', 'editLib', "libID=$lib->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link iframe'")?>
              <?php common::printLink('doc', 'deleteLib', "libID=$lib->id", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
              <?php common::printLink('tree', 'browse', "rootID=$lib->id&type=doc", "<i class='icon icon-cog'></i>", '', "title='{$lang->tree->manage}' class='btn btn-link'")?>
            </div>
            <?php endif;?>
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    </div>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
