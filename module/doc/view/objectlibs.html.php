<?php
/**
 * The objectLibs view file of doc module of ZenTaoPMS.
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
<div class="fade main-row <?php if($this->from == 'doc') echo 'split-row';?>" id="mainRow">
  <?php if($this->from == 'doc'):?>
  <?php include './side.html.php';?>
  <?php endif;?>
  <?php if($this->cookie->browseType == 'bylist'):?>
  <?php include dirname(__FILE__) . '/objectlibsbylist.html.php';?>
  <?php else:?>
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <i class="icon icon-folder-open-o text-muted"></i> <?php echo $object->name;?>
        </div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
            <?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-bars'></i>", '', "title='{$lang->doc->browseTypeList['list']}' class='btn btn-icon'");?>
            <?php echo html::a('javascript:setBrowseType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='{$lang->doc->browseTypeList['grid']}' class='btn btn-icon text-primary'");?>
          </div>
          <div class="dropdown">
            <button type="button" title="<?php echo $lang->customConfig;?>" class="btn btn-icon" data-toggle="dropdown"><i class="icon icon-cog"></i></button>
            <div class="dropdown-menu pull-right col-lg" id="pageSetting">
              <form class='with-padding load-indicator' id='pageSettingForm' method='post' target='hiddenwin' action='<?php echo $this->createLink('custom', 'ajaxSaveCustomFields', 'module=doc&section=custom&key=objectLibs');?>'>
                <div><?php echo $lang->customConfig;?></div>
                <div class="row row-grid with-padding">
                  <?php foreach($customObjectLibs as $libType => $libTypeName):?>
                  <div class="col-xs-12">
                    <?php $checked = (strpos(",$showLibs,", ",$libType,") !== false) ? " checked ='checked'" : "";?>
                    <div class="checkbox-primary">
                      <input type="checkbox" name="fields[]" value="<?php echo $libType;?>" <?php echo $checked;?> id="fields<?php echo $libType;?>">
                      <label for="fields<?php echo $libType;?>"><?php echo $libTypeName;?></label>
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
                <div>
                  <?php echo html::submitButton($lang->save);?> &nbsp;
                  <?php echo html::commonButton($lang->cancel, '', "btn close-dropdown");?>
                </div>
              </form>
            </div>
          </div>
        </nav>
      </div>
      <div class='panel-body'>
        <div class="row row-grid files-grid" data-size="300">
          <?php foreach($libs as $libID => $lib):?>
          <?php if($libID == 'project' and $from != 'doc') continue;?>
          <?php if(strpos($config->doc->custom->objectLibs, 'files') === false && $libID == 'files') continue;?>
          <?php if(strpos($config->doc->custom->objectLibs, 'customFiles') === false && isset($lib->main) && !$lib->main) continue;?>

          <?php $libLink = inlink('browse', "libID=$libID&browseType=all&param=0&orderBy=id_desc&from=$from");?>
          <?php if($libID == 'project') $libLink = inlink('allLibs', "type=project&product=$object->id");?>
          <?php if($libID == 'files')   $libLink = inlink('showFiles', "type=$type&objectID=$object->id");?>

          <?php $icon = 'icon-folder text-yellow';?>
          <?php if($libID == 'files') $icon = 'icon-paper-clip text-brown';?>

          <div class="col">
            <a class="file" href="<?php echo $libLink;?>">
              <i class="file-icon icon <?php echo $icon;?>"></i>
              <div class="file-name"><?php echo ($libID != 'project' && $libID != 'files' && strpos($lib->collector, $this->app->user->account) !== false ? "<i class='icon icon-star text-yellow'></i> " : '') . $lib->name;?></div>
              <div class="text-primary file-info"><?php echo  $lib->allCount . $lang->doc->item;?></div>
            </a>
            <?php if($libID != 'project' and $libID != 'files'):?>
            <?php $star = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <?php $collectTitle = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
            <div class="actions">
              <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$libID&objectType=doclib");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
              <?php common::printLink('doc', 'editLib', "libID=$libID", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link iframe'")?>
              <?php if(empty($lib->main)) common::printLink('doc', 'deleteLib', "libID=$libID", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
              <?php common::printLink('tree', 'browse', "rootID=$libID&type=doc", "<i class='icon icon-cog'></i>", '', "title='{$lang->doc->manageType}' class='btn btn-link'")?>
            </div>
            <?php endif;?>
          </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>
  </div>
  <?php endif;?>
</div>
<?php js::set('type', 'doc');?>
<?php include '../../common/view/footer.html.php';?>
