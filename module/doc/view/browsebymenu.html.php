<style>
#filesPanel {margin-bottom:0px;}
#filesPanel a {color: #114f8e}
#filesPanel a:hover,
#filesPanel a:focus,
#filesPanel a:active {color: #2e6dad}
#filesPanel .panel-footer {position: relative; padding: 5px; height: 40px}
#filesPanel .panel-footer > a {display: block; float: left; padding: 5px; line-height: 20px;}
#filesPanel .panel-footer > a:hover {background-color: #e5e5e5;}
#filesPanel .pager {position: absolute; top: 4px; right: 4px}
#filesPanel .panel-heading > a {font-weight: bold;}
#filesPanel .file {float: left; width: 90px; position: relative; background-color: transparent; margin: 0 10px 10px 0; border: 1px solid transparent; transition: all .2s;}
#filesPanel .file:hover {border-color: #ddd; background-color: #EBF2F9}
#filesPanel .file > a {display: block;}
#filesPanel .file-icon {display: block; height: 60px; text-align: center; line-height: 60px; font-size: 50px; opacity: .7}
#filesPanel .file:hover .file-icon {opacity: 1}
#filesPanel .file-name {text-align: center; height: 2em; line-height: 14px; overflow: hidden;}
#filesPanel .file > .actions {position: absolute; bottom: -30px; left: -1px; right: -1px; background-color: #333; background-color: rgba(0,0,0,.5); margin: 0; opacity: 0; transition: opacity .2s;}
#filesPanel .file:hover > .actions {opacity: 1}
#filesPanel .file > .actions > a {line-height: 30px; display: inline-block; padding: 0 8px; color: #fff;}
</style>
<div id='featurebar'>
  <div class='crumb pull-left'><i class='icon icon-tags'></i> <?php echo $crumb;?></div>
  <ul class='nav'>
    <li <?php if($orderBy == 'addedDate_desc') echo "class='active'";?>><?php echo html::a(inlink('browse', "libID=$libID&browseType=$browseType&param=$param&orderBy=addedDate_desc&from=$from"), $lang->doc->orderByOpen)?></li>
    <li <?php if($orderBy == 'editedDate_desc') echo "class='active'";?>><?php echo html::a(inlink('browse', "libID=$libID&browseType=$browseType&param=$param&orderBy=editedDate_desc&from=$from"), $lang->doc->orderByEdit)?></li>
    <li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;<?php echo $lang->doc->searchDoc;?></a></li>
  </ul>
  <div class='actions'>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon icon-cog'></i> <?php echo $lang->actions?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <?php if(empty($moduleID)):?>
        <?php
        if(common::hasPriv('doc', 'editLib')) echo '<li>' . html::a(inlink('editLib', "rootID=$libID"), $lang->doc->editLib, '', "data-toggle='modal' data-type='iframe' data-width='600px'") . '</li>';
        if(common::hasPriv('doc', 'deleteLib')) echo '<li>' . html::a(inlink('deleteLib', "rootID=$libID"), $lang->doc->deleteLib, 'hiddenwin') . '</li>';
        ?>
        <?php else:?>
        <?php
        if(common::hasPriv('tree', 'edit')) echo '<li>' . html::a($this->createLink('tree', 'edit', "moduleID={$moduleID}&type=doc"), $lang->doc->editType, '', " class='' data-toggle='modal' data-type='ajax'") . '</li>';
        if(common::hasPriv('tree', 'delete')) echo '<li>' . html::a($this->createLink('tree', 'delete', "rootID=$libID&moduleID=$moduleID"), $lang->doc->deleteType, 'hiddenwin') . '</li>';
        ?>
        <?php endif;?>
        <?php if(common::hasPriv('tree', 'browse')) echo '<li>' . html::a($this->createLink('tree', 'browse', "rootID=$libID&view=doc&moduleID=$moduleID"), $lang->doc->manageType) . '</li>';?>
        <li><?php echo html::a(inlink('ajaxFixedMenu', "libID=$libID&type=" . ($fixedMenu ? 'remove' : 'fixed')), $fixedMenu ? $lang->doc->removeMenu : $lang->doc->fixedMenu, "hiddenwin");?></li>
      </ul>
    </div>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon icon-th'></i> <?php echo $lang->doc->browseTypeList['menu']?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-tags'></i> {$lang->doc->browseTypeList['tree']}");?></li>
      </ul>
    </div>
    <?php common::printIcon('doc', 'create', "libID=$libID&moduleID=$moduleID");?>
  </div>
  <div id='querybox' class='<?php if($browseType == 'bysearch') echo 'show';?>'></div>
</div>
<div class='main'>
  <div class='panel' id='filesPanel'>
    <div class='panel-body clearfix'>
      <?php foreach($modules as $module):?>
      <div class='file file-dir'>
        <a href='<?php echo inlink('browse', "libID=$libID&browseType=bymenu&param=$module->id&orderBy=$orderBy&from=$from")?>'>
          <i class='file-icon icon icon-folder-close-alt'></i>
          <div class='file-name' title='<?php echo $module->name?>'><?php echo $module->name?></div>
        </a>
      </div>
      <?php endforeach;?>
      <?php foreach($docs as $doc):?>
      <div class='file'>
        <a href='<?php echo inlink('view', "docID=$doc->id")?>'>
          <i class='file-icon icon icon-file'></i>
          <div class='file-name' title='<?php echo $doc->title?>'><?php echo $doc->title?></div>
        </a>
      </div>
      <?php endforeach;?>
    </div>
    <?php if($docs):?>
    <div class='panel-footer'><?php $pager->show();?></div>
    <?php endif;?>
  </div>
</div>
<script>
$(function()
{
    $('#filesPanel .panel-body').css('min-height', $('.outer').height() - $('#featurebar').height() - 60);
})
</script>
