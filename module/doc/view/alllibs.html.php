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
<div class="main-row split-row" id="mainRow">
  <?php include './side.html.php';?>
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <?php if($type == 'custom')  $panelTitle = $lang->doc->custom;?>
          <?php if($type == 'product') $panelTitle = $lang->productCommon;?>
          <?php if($type == 'project') $panelTitle = $lang->projectCommon;?>
          <i class="icon icon-folder-open-o text-muted"></i> <?php echo $panelTitle;?>
        </div>
      </div>
      <div class="panel-body">
        <div class="row row-grid files-grid" data-size="300">
          <?php if($type == 'product') $icon = 'icon-cube text-secondary';?>
          <?php if($type == 'project') $icon = 'icon-stack text-green';?>
          <?php if($type == 'custom')  $icon = 'icon-folder text-yellow';?>
          <?php foreach($libs as $lib):?>
          <?php $link = $type != 'custom' ? $this->createLink('doc', 'objectLibs', "type=$type&objectID=$lib->id") : $this->createLink('doc', 'browse', "libID=$lib->id");?>
          <div class="col">
            <a class="file" href="<?php echo $link;?>">
              <i class="file-icon icon <?php echo $icon;?>"></i>
              <div class="file-name"><?php echo $lib->name;?></div>
              <?php if($type == 'custom'):?>
              <div class="text-primary file-info"><?php echo $itemCounts[$lib->id] . $lang->doc->item;?></div>
              <?php else:?>
              <div class="text-primary file-info"><?php echo count($subLibs[$lib->id]) . $lang->doc->item;?></div>
              <?php endif;?>
            </a>
            <?php if($type == 'custom'):?>
            <div class="actions">
              <?php common::printLink('doc', 'collect', "objectID=$lib->id&objectType=lib", "<i class='icon icon-star-empty'></i>", '', "title='{$lang->doc->collect}' class='btn btn-link'")?>
              <?php common::printLink('doc', 'editLib', "libID=$lib->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link' data-toggle='modal'")?>
              <?php common::printLink('doc', 'deleteLib', "libID=$lib->id", "<i class='icon icon-trash'></i>", '', "title='{$lang->delete}' class='btn btn-link'")?>
            </div>
            <?php endif;?>
          </div>
          <?php endforeach;?>
        </div>
      </div>
      <div class='table-footer'><?php echo $pager->show('right', 'pagerjs');?></div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
