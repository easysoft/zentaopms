<?php
/**
 * The browse view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     lib
 * @version     $Id: browse.html.php 958 2010-07-22 08:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<script language='Javascript'>
var browseType = '<?php echo $browseType;?>';
</script>
<?php js::set('confirmDelete', $lang->doc->confirmDelete)?>
<?php js::set('libID', $libID);?>
<<<<<<< HEAD
<?php if($this->from != 'doc') js::set('type', 'doc');?>

<div class='main-row split-row' id='mainRow'>
  <?php include './side.html.php';?>
  <?php if($this->cookie->browseType == 'bygrid'):?>
  <?php include dirname(__FILE__) . '/browsebygrid.html.php';?>
  <?php else:?>
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal"><i class="icon icon-folder-open-o text-muted"></i> <?php echo $title;?></div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
            <?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-bars'></i>", '', "title='{$lang->doc->browseTypeList['list']}' class='btn btn-gray btn-icon text-primary'");?>
            <?php echo html::a('javascript:setBrowseType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='{$lang->doc->browseTypeList['grid']}' class='btn btn-gray btn-icon'");?>
          </div>
        </nav>
      </div>
      <div class="panel-body has-table">
        <table class="table table-borderless table-hover table-files">
          <thead>
            <tr class="muted">
              <th class="c-name"><?php echo $lang->doc->title;?></th>
              <th class="c-actions"></th>
              <th class="c-num"><?php echo $lang->doc->size;?></th>
              <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
              <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
              <th class="c-datetime"><?php echo $lang->doc->editedDate;?></th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($modules)):?>
            <?php foreach($modules as $module):?>
            <?php $star = strpos($module->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <tr>
              <td class="c-name"><?php echo html::a(inlink('browse', "libID=$libID&browseType=bymodule&param=$module->id&orderBy=$orderBy&from=$from"), "<i class='icon icon-folder text-yellow'></i> &nbsp;" . $module->name);?></td>
              <td class="c-actions">
                <?php common::printLink('doc', 'collect', "objectID=$module->id&objectType=module", "<i class='icon {$star}'></i>", 'hiddenwin', "title='{$lang->doc->collect}' class='btn btn-link'")?>
              </td>
              <td class="c-num"></td>
              <td class="c-user"></td>
              <td class="c-datetime"></td>
              <td class="c-datetime"></td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php foreach($docs as $doc):?>
            <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <tr>
              <td class="c-name"><?php echo html::a(inlink('view', "docID=$doc->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title);?></td>
              <td class="c-actions">
                <?php common::printLink('doc', 'collect', "objectID=$doc->id&objectType=doc", "<i class='icon {$star}'></i>", 'hiddenwin', "title='{$lang->doc->collect}' class='btn btn-link'")?>
                <?php common::printLink('doc', 'edit', "docID=$doc->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link'")?>
                <?php common::printLink('doc', 'delete', "docID=$doc->id", "<i class='icon icon-trash'></i>", '', "title='{$lang->delete}' class='btn btn-link'")?>
              </td>
              <td class="c-num"></td>
              <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
              <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'm-d h:i');?></td>
              <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'm-d h:i');?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
=======
<?php if(!$fixedMenu and $this->from != 'doc') js::set('type', 'doc');?>
<?php if($this->cookie->browseType == 'bymenu'):?>
<?php include dirname(__FILE__) . '/browsebymenu.html.php';?>
<?php elseif($this->cookie->browseType == 'bytree'):?>
<?php include dirname(__FILE__) . '/browsebytree.html.php';?>
<?php else:?>
<div id='featurebar'>
  <ul class='nav'>
    <li id='allTab'><?php echo html::a(inlink('browse', "libID=$libID&browseType=all&param=0&orderBy=$orderBy&from=$from"), $lang->doc->allDoc)?></li>
    <li id='openedbymeTab'><?php echo html::a(inlink('browse', "libID=$libID&browseType=openedByMe&param=0&orderBy=$orderBy&from=$from"), $lang->doc->openedByMe)?></li>
    <li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;<?php echo $lang->doc->searchDoc;?></a></li>
  </ul>
  <div class='actions'>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon icon-list'></i> <?php echo $lang->doc->browseTypeList['list']?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <li><?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-list'></i> {$lang->doc->browseTypeList['list']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bymenu")', "<i class='icon icon-th'></i> {$lang->doc->browseTypeList['menu']}");?></li>
        <li><?php echo html::a('javascript:setBrowseType("bytree")', "<i class='icon icon-branch'></i> {$lang->doc->browseTypeList['tree']}");?></li>
      </ul>
    </div>
    <div class="btn-group">
      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class='icon icon-cog'></i> <?php echo $lang->actions?> <span class="caret"></span></button>
      <ul class="dropdown-menu" role="menu">
        <?php
        if(common::hasPriv('doc', 'editLib')) echo '<li>' . html::a(inlink('editLib', "rootID=$libID"), $lang->doc->editLib, '', "data-toggle='modal' data-type='iframe' data-width='800px'") . '</li>';
        if(common::hasPriv('doc', 'deleteLib')) echo '<li>' . html::a(inlink('deleteLib', "rootID=$libID"), $lang->doc->deleteLib, 'hiddenwin') . '</li>';
        ?>
        <li><?php echo html::a(inlink('ajaxFixedMenu', "libID=$libID&type=" . ($fixedMenu ? 'remove' : 'fixed')), $fixedMenu ? $lang->doc->removeMenu : $lang->doc->fixedMenu, "hiddenwin");?></li>
      </ul>
    </div>
    <?php common::printIcon('doc', 'create', "libID=$libID&moduleID=$moduleID");?>
  </div>
  <div id='querybox' class='<?php if($browseType == 'bysearch') echo 'show';?>'></div>
</div>
<div class='side' id='treebox'>
  <a class='side-handle' data-id='treebox'><i class='icon-caret-left'></i></a>
  <div class='side-body'>
    <div class='panel panel-sm'>
      <div class='panel-heading text-ellipsis'><?php echo html::icon('folder-close-alt');?> <strong><?php echo $libName;?></strong></div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'><?php common::printLink('tree', 'browse', "rootID=$libID&view=doc", $lang->doc->manageType);?></div>
>>>>>>> f6289cc53c13786e3242f8792030e7752898794f
      </div>
    </div>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
