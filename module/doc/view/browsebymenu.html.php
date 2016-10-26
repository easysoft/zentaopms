<style>
.menu,.file{text-align:center;}
.menu .menu-name .icon,.file .file-name .icon{display:block;font-size:60px;}
.menu .menu-name .name,.file .file-name .name{display:block; overflow:hidden;height:36px;}
</style>
<div id='featurebar'>
  <ul class='nav'>
    <li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;<?php echo $lang->doc->searchDoc;?></a></li>
  </ul>
  <div class='actions'>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon icon-th'></i> <?php echo $lang->doc->browseTypeList['menu']?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-tags'></i> {$lang->doc->browseTypeList['tree']}");?></li>
      </ul>
    </div>
    <?php common::printIcon('doc', 'create', "libID=$libID&moduleID=$moduleID&product=0&prject=0&from=doc");?>
  </div>
  <div id='querybox' class='<?php if($browseType == 'bysearch') echo 'show';?>'></div>
</div>
<div class='main'>
  <div class='panel'>
    <div class='panel-heading'>
      <strong>
        <?php echo html::a(inlink('browse', "libID=$libID"), $libName) . ' / ';?>
        <?php foreach($parents as $module):?>
        <?php echo html::a(inlink('browse', "libID=$libID&browseType=bymenu&param=$module->id"), $module->name) . ' / '?>
        <?php endforeach;?>
      </strong>
    </div>
    <div class='panel-body row'>
      <?php foreach($modules as $module):?>
      <div class='col-md-1 menu'>
        <a href='<?php echo inlink('browse', "libID=$libID&browseType=bymenu&param=$module->id")?>'>
          <div class='menu-name'>
            <i class='icon icon-folder-open-alt'></i>
            <div class='name' title='<?php echo $module->name?>'><?php echo $module->name?></div>
          </div>
        </a>
        <div class='actions'>
          <?php
          if(common::hasPriv('tree', 'edit')) echo html::a($this->createLink('tree', 'edit', "moduleID={$module->id}&type=doc"), '<i class="icon-pencil"></i>', '', " class='btn-icon' data-toggle='modal' data-type='ajax' title='$lang->edit'");
          if(common::hasPriv('tree', 'delete')) echo html::a($this->createLink('tree', 'delete', "rootID=$libID&moduleID=$module->id"), '<i class="icon-remove"></i>', 'hiddenwin', "class='btn-icon' title='{$lang->delete}'");
          ?>
        </div>
      </div>
      <?php endforeach;?>
      <?php foreach($docs as $doc):?>
      <div class='col-md-1 file'>
        <a href='<?php echo inlink('view', "docID=$doc->id")?>'>
          <div class='file-name'>
            <i class='icon icon-file'></i>
            <div class='name' title='<?php echo $doc->title?>'><?php echo $doc->title?></div>
          </div>
        </a>
        <div class='actions'>
          <?php 
          if(common::hasPriv('doc', 'edit')) echo html::a($this->createLink('doc', 'edit', "docID={$doc->id}"), '<i class="icon-pencil"></i>', '', " class='btn-icon' data-toggle='tooltip' title='$lang->edit'");
          if(common::hasPriv('doc', 'delete')) echo html::a($this->createLink('doc', 'delete', "docID=$doc->id"), '<i class="icon-remove"></i>', 'hiddenwin', "class='btn-icon' title='{$lang->delete}'");
          ?>
        </div>
      </div>
      <?php endforeach;?>
    </div>
    <div class='panel-footer'>
      <?php common::printLink('doc', 'editLib', "rootID=$libID", $lang->doc->editLib, '', "data-toggle='modal' data-type='iframe' data-width='600px'");?>
      <?php common::printLink('doc', 'deleteLib', "rootID=$libID", $lang->doc->deleteLib, 'hiddenwin');?>
      <?php common::printLink('tree', 'browse', "rootID=$libID&view=doc", $lang->doc->manageType);?>
      <?php echo html::a(inlink('ajaxFixedMenu', "libID=$libID&type=" . ($fixedMenu ? 'remove' : 'fixed')), $fixedMenu ? $lang->doc->removeMenu : $lang->doc->fixedMenu, "hiddenwin");?>
      <?php $pager->show();?>
    </div>
  </div>
</div>
