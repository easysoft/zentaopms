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

<?php $spliter = (empty($this->app->user->feedback) && !$this->cookie->feedbackView && $this->from == 'doc') ? true : false;?>
<div class="main-row fade <?php if($spliter) echo 'split-row';?>" id="mainRow">
  <?php if($this->from == 'doc') include './side.html.php';?>
  <?php if($this->cookie->browseType == 'bygrid'):?>
  <?php include dirname(__FILE__) . '/browsebygrid.html.php';?>
  <?php else:?>
  <div class="main-col" data-min-width="400">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='doc'></div>
    <div class="panel block-files block-sm no-margin">
      <div class="panel-heading">
        <div class="panel-title font-normal">
          <?php if($browseType != 'bysearch'):?>
          <i class="icon icon-folder-open-o text-muted"></i>
          <?php else:?>
          <i class="icon icon-search text-muted"></i>
          <?php endif;?>
          <?php echo $breadTitle;?>
        </div>
        <nav class="panel-actions btn-toolbar">
          <div class="btn-group">
            <?php echo html::a('javascript:setBrowseType("bygrid")', "<i class='icon icon-cards-view'></i>", '', "title='{$lang->doc->browseTypeList['grid']}' class='btn btn-icon'");?>
            <?php echo html::a('javascript:setBrowseType("bylist")', "<i class='icon icon-bars'></i>", '', "title='{$lang->doc->browseTypeList['list']}' class='btn btn-icon text-primary'");?>
          </div>
          <?php if($libID):?>
          <div class="dropdown">
            <button class="btn" type="button" data-toggle="dropdown"><i class='icon-cog'></i> <span class="caret"></span></button>
            <ul class='dropdown-menu'>
              <li><?php common::printLink('tree', 'browse', "libID=$libID&viewType=doc", "<i class='icon icon-cog'></i>" . $lang->doc->manageType);?></li>
              <li><?php common::printLink('doc', 'editLib', "libID=$libID", "<i class='icon icon-edit'></i>" . $lang->doc->editLib, '', "class='iframe'");?></li>
              <li><?php common::printLink('doc', 'deleteLib', "libID=$libID", "<i class='icon icon-trash'></i>" . $lang->doc->deleteLib, 'hiddenwin');?></li>
            </ul>
          </div>
          <?php if(common::hasPriv('doc', 'create')):?>
          <div class="dropdown" id='createDropdown'>
            <button class='btn btn-primary' type='button' data-toggle='dropdown'><i class='icon icon-plus'></i> <?php echo $lang->doc->create;?> <span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php foreach($lang->doc->typeList as $typeKey => $typeName):?>
              <?php $class = strpos($config->doc->officeTypes, $typeKey) !== false ? 'iframe' : '';?>
              <li><?php echo html::a($this->createLink('doc', 'create', "libID=$libID&moduleID=$moduleID&type=$typeKey"), $typeName, '', "class='$class'");?></li>
              <?php endforeach;?>
            </ul>
          </div>
          <?php endif;?>
          <?php endif;?>
        </nav>
      </div>
      <?php if(empty($docs) and $browseType == 'bysearch'):?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->doc->noSearchedDoc;?></span></p>
      </div>
      <?php elseif(empty($docs) and empty($modules) and empty($libs) and empty($attachLibs)):?>
      <div class="table-empty-tip">
        <p>
          <?php if($libID):?>
          <span class="text-muted"><?php echo $lang->doc->noDoc;?></span>
          <?php if(common::hasPriv('doc', 'create')):?>
          <?php echo html::a($this->createLink('doc', 'create', "libID={$libID}&moduleID=$moduleID"), "<i class='icon icon-plus'></i> " . $lang->doc->create, '', "class='btn btn-info'");?>
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
                <?php common::printLink('tree', 'browse', "rootID=$lib->id&type=doc", "<i class='icon icon-cog'></i>", '', "title='{$lang->tree->manage}' class='btn btn-link'")?>
              </td>
            </tr>
            <?php endforeach;?>
            <?php endif;?>
            <?php if(!empty($attachLibs) and $browseType != 'bysearch'):?>
            <?php foreach($attachLibs as $libType => $attachLib):?>
            <tr>
              <?php if($libType == 'project'):?>
              <td class="c-name"><?php echo html::a(inlink('allLibs', "type=project&product={$currentLib->product}"), "<i class='icon icon-folder text-yellow'></i> &nbsp;" . $attachLib->name);?></td>
              <?php elseif($libType == 'files'):?>
              <td class="c-name"><?php echo html::a(inlink('showFiles', "type=$type&objectID={$currentLib->$type}"), "<i class='icon icon-folder text-yellow'></i> &nbsp;" . $attachLib->name);?></td>
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
              <td class="c-name"><?php echo html::a(inlink('view', "docID=$doc->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title);?></td>
              <td class="c-num"><?php echo $doc->fileSize ? $doc->fileSize : '-';?></td>
              <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
              <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'y-m-d');?></td>
              <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'y-m-d');?></td>
              <td class="c-actions">
                <?php if(common::hasPriv('doc', 'collect')):?>
                <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
                <?php endif;?>
                <?php common::printLink('doc', 'edit', "docID=$doc->id", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link'")?>
                <?php common::printLink('doc', 'delete', "docID=$doc->id", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
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
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
