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
<?php $pageCSS .= $this->doc->appendNavCSS();?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('browseType', $browseType);?>
<?php js::set('confirmDelete', $lang->doc->confirmDelete)?>
<?php js::set('libID', $libID);?>
<?php if($this->from != 'doc') js::set('type', 'doc');?>

<?php $spliter = (empty($this->app->user->feedback) && !$this->cookie->feedbackView && $this->from == 'doc') ? true : false;?>
<div class="main-row fade <?php if($spliter) echo 'split-row';?>" id="mainRow">
  <div id="mainContent">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='doc'></div>
    <div class="panel block-files block-sm no-margin">
      <?php if(empty($docs) and $browseType == 'bysearch'):?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->doc->noSearchedDoc;?></span></p>
      </div>
      <?php elseif(empty($docs) and empty($modules) and empty($libs) and empty($attachLibs)):?>
      <div class="table-empty-tip">
        <p>
          <?php if($libID):?>
          <span class="text-muted"><?php echo $lang->doc->noDoc;?></span>
          <?php if(common::hasPriv('doc', 'create') and common::canBeChanged('doc', $currentLib)):?>
          <?php echo html::a($this->createLink('doc', 'create', "libID={$libID}&moduleID=$moduleID&type=&from={$lang->navGroup->doc}"), "<i class='icon icon-plus'></i> " . $lang->doc->create, '', "class='btn btn-info'");?>
          <?php endif;?>
          <?php elseif($browseType == 'byediteddate'):?>
          <span class="text-muted"><?php echo $lang->doc->noEditedDoc;?></span>
          <?php elseif($browseType == 'openedbyme'):?>
          <span class="text-muted"><?php echo $lang->doc->noOpenedDoc;?></span>
          <?php elseif($browseType == 'collectedbyme'):?>
          <span class="text-muted"><?php echo $lang->doc->noCollectedDoc;?></span>
          <?php endif;?>
        </p>
      </div>
      <?php else:?>
      <div class="panel-body">
        <table class="table table-borderless table-hover table-files table-fixed no-margin">
          <thead>
            <tr>
              <th class="c-name"><?php echo $lang->doc->title;?></th>
              <th class="c-num"><?php echo $lang->doc->size;?></th>
              <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
              <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
              <th class="c-datetime"><?php echo $lang->doc->editedDate;?></th>
              <th class="w-90px text-center"><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php if(!empty($libs) and $browseType != 'bysearch'):?>
            <?php foreach($libs as $lib):?>
            <?php $star = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <?php $collectTitle = strpos($lib->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
            <tr>
              <td class="c-name"><?php echo html::a(inlink('browse', "libID={$lib->id}&browseType=all&param=0&orderBy=$orderBy&from=$from"), "<i class='icon icon-folder text-yellow'></i> &nbsp;" . $lib->name);?></td>
              <td class="c-num"></td>
              <td class="c-user"></td>
              <td class="c-datetime"></td>
              <td class="c-datetime"></td>
              <td>
                <?php if(common::hasPriv('doc', 'collect')):?>
                <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$lib->id&objectType=doclib");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
                <?php endif;?>
                <?php common::printLink('doc', 'editLib', "libID=$lib->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link iframe'")?>
                <?php common::printLink('tree', 'browse', "rootID=$lib->id&viewType=doc&currentModuleID=0&branch=0&from=$from", "<i class='icon icon-cog'></i>", '', "title='{$lang->tree->manage}' class='btn btn-link'")?>
              </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php if(!empty($attachLibs) and $browseType != 'bysearch'):?>
            <?php foreach($attachLibs as $libType => $attachLib):?>
            <tr>
              <?php if($libType == 'execution'):?>
              <td class="c-name"><?php echo html::a(inlink('allLibs', "type=execution&product={$currentLib->product}"), "<i class='icon icon-folder text-yellow'></i> &nbsp;" . $attachLib->name);?></td>
              <?php elseif($libType == 'files'):?>
              <td class="c-name"><?php echo html::a(inlink('showFiles', "type=$type&objectID={$currentLib->$type}&from=$from"), "<i class='icon icon-folder text-yellow'></i> &nbsp;" . $attachLib->name);?></td>
              <?php endif;?>
              <td class="c-num"></td>
              <td class="c-user"></td>
              <td class="c-datetime"></td>
              <td class="c-datetime"></td>
              <td></td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php if(isset($modules) and $browseType != 'bysearch'):?>
            <?php foreach($modules as $module):?>
            <?php $star = strpos($module->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <?php $collectTitle = strpos($module->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
            <tr>
              <td class="c-name"><?php echo html::a(inlink('browse', "libID=$libID&browseType=bymodule&param=$module->id&orderBy=$orderBy&from=$from"), "<i class='icon icon-folder text-yellow'></i> &nbsp;" . $module->name);?></td>
              <td class="c-num"></td>
              <td class="c-user"></td>
              <td class="c-datetime"></td>
              <td class="c-datetime"></td>
              <td class="c-actions">
                <?php if(common::hasPriv('doc', 'collect')):?>
                <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$module->id&objectType=module");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
                <?php endif;?>
              </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php foreach($docs as $doc):?>
            <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <?php $collectTitle = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
            <tr>
              <td class="c-name"><?php echo html::a($this->createLink('doc', 'view', "docID=$doc->id&version=0&from={$lang->navGroup->doc}", '', true), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "title={$doc->title} class='iframe' data-width='90%'");?></td>
              <td class="c-num"><?php echo $doc->fileSize ? $doc->fileSize : '-';?></td>
              <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
              <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'y-m-d');?></td>
              <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'y-m-d');?></td>
              <td class="c-actions">
                <?php if(common::canBeChanged('doc', $doc)):?>
                <?php if(common::hasPriv('doc', 'collect')):?>
                <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
                <?php endif;?>
                <?php common::printLink('doc', 'edit', "docID=$doc->id&comment=false&from={$lang->navGroup->doc}", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link iframe'", true, true)?>
                <?php common::printLink('doc', 'delete', "docID=$doc->id&confirm=no&from={$lang->navGroup->doc}", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
                <?php endif;?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
        <?php if(!empty($docs)):?>
        <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
        <?php endif;?>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
