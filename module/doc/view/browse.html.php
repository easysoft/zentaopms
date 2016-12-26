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
<?php js::set('fixedMenu', $fixedMenu);?>
<?php js::set('libID', $libID);?>
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
      <div class='panel-heading nobr'><?php echo html::icon('folder-close-alt');?> <strong><?php echo $libName;?></strong></div>
      <div class='panel-body'>
        <?php echo $moduleTree;?>
        <div class='text-right'><?php common::printLink('tree', 'browse', "rootID=$libID&view=doc", $lang->doc->manageType);?></div>
      </div>
    </div>
  </div>
</div>
<div class='main'>
  <script>setTreeBox();</script>
  <table class='table table-condensed table-hover table-striped tablesorter table-fixed' id='docList'>
    <thead>
      <tr>
        <?php $vars = "libID=$libID&browseType=$browseType&param=$param&orderBy=%s&from=$from&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
        <th class='w-id'>   <?php common::printOrderLink('id',        $orderBy, $vars, $lang->idAB);?></th>
        <th>                <?php common::printOrderLink('title',     $orderBy, $vars, $lang->doc->title);?></th>
        <th class='w-100px'><?php common::printOrderLink('addedBy',   $orderBy, $vars, $lang->doc->addedBy);?></th>
        <th class='w-120px'><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->doc->addedDate);?></th>
        <th class='w-120px'><?php common::printOrderLink('editedDate', $orderBy, $vars, $lang->doc->editedDate);?></th>
        <th class='w-100px {sorter:false}'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($docs as $key => $doc):?>
      <?php
      $viewLink = $this->createLink('doc', 'view', "docID=$doc->id");
      $canView  = common::hasPriv('doc', 'view');
      ?>
      <tr class='text-center'>
        <td><?php if($canView) echo html::a($viewLink, sprintf('%03d', $doc->id)); else printf('%03d', $doc->id);?></td>
        <td class='text-left' title="<?php echo $doc->title?>"><nobr><?php echo html::a($viewLink, $doc->title);?></nobr></td>
        <td><?php isset($users[$doc->addedBy]) ? print($users[$doc->addedBy]) : print($doc->addedBy);?></td>
        <td><?php echo substr($doc->addedDate, 5, 11);?></td>
        <td><?php echo substr($doc->editedDate, 5, 11);?></td>
        <td>
          <?php 
          common::printIcon('doc', 'edit', "doc={$doc->id}", '', 'list');
          if(common::hasPriv('doc', 'delete'))
          {
              $deleteURL = $this->createLink('doc', 'delete', "docID=$doc->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"docList\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->doc->delete}'");
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
    <tfoot><tr><td colspan='6'><?php $pager->show();?></td></tr></tfoot>
  </table>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
