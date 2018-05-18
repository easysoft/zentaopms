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
<script>
var browseType = '<?php echo $browseType;?>';
</script>
<?php js::set('confirmDelete', $lang->doc->confirmDelete)?>
<?php js::set('libID', $libID);?>
<?php if($this->from != 'doc') js::set('type', 'doc');?>

<div class="main-row <?php if($this->from == 'doc') echo 'split-row';?>" id="mainRow">
  <?php if($this->from == 'doc'):?>
  <?php include './side.html.php';?>
  <div class="col-spliter"></div>
  <?php endif;?>
  <?php if($this->cookie->browseType == 'bygrid'):?>
  <?php include dirname(__FILE__) . '/browsebygrid.html.php';?>
  <?php else:?>
  <div class="main-col" data-min-width="400">
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <?php if($browseType != 'fastsearch'):?>
          <i class="icon icon-folder-open-o text-muted"></i>
          <?php else:?>
          <i class="icon icon-search text-muted"></i>
          <?php endif;?>
          <?php echo $title;?>
        </div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
            <?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-bars'></i>", '', "title='{$lang->doc->browseTypeList['list']}' class='btn btn-icon btn-gray text-primary'");?>
            <?php echo html::a('javascript:setBrowseType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='{$lang->doc->browseTypeList['grid']}' class='btn btn-icon btn-gray'");?>
          </div>
        </nav>
      </div>
      <div class="panel-body has-table">
        <table class="table table-borderless table-hover table-files">
          <thead>
            <tr class="muted">
              <th class="c-name"><?php echo $lang->doc->title;?></th>
              <th class="c-num"><?php echo $lang->doc->size;?></th>
              <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
              <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
              <th class="c-datetime"><?php echo $lang->doc->editedDate;?></th>
              <th class="c-actions"><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php if(isset($modules)):?>
            <?php foreach($modules as $module):?>
            <?php $star = strpos($module->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <tr>
              <td class="c-name"><?php echo html::a(inlink('browse', "libID=$libID&browseType=bymodule&param=$module->id&orderBy=$orderBy&from=$from"), "<i class='icon icon-folder text-yellow'></i> &nbsp;" . $module->name);?></td>
              <td class="c-num"></td>
              <td class="c-user"></td>
              <td class="c-datetime"></td>
              <td class="c-datetime"></td>
              <td>
                <?php common::printLink('doc', 'collect', "objectID=$module->id&objectType=module", "<i class='icon {$star}'></i>", 'hiddenwin', "title='{$lang->doc->collect}' class='btn btn-link'")?>
                <?php common::printLink('tree', 'browse', "rootID=$libID&type=doc", "<i class='icon icon-cog'></i>", '', "title='{$lang->tree->manage}' class='btn btn-link'")?>
              </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php foreach($docs as $doc):?>
            <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <tr>
              <td class="c-name"><?php echo html::a(inlink('view', "docID=$doc->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title);?></td>
              <td class="c-num"></td>
              <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
              <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'm-d h:i');?></td>
              <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'm-d h:i');?></td>
              <td>
                <?php common::printLink('doc', 'collect', "objectID=$doc->id&objectType=doc", "<i class='icon {$star}'></i>", 'hiddenwin', "title='{$lang->doc->collect}' class='btn btn-link'")?>
                <?php common::printLink('doc', 'edit', "docID=$doc->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link'")?>
                <?php common::printLink('doc', 'delete', "docID=$doc->id", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class='table-footer'><?php echo $pager->show('right', 'pagerjs');?></div>
    </div>
  </div>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
