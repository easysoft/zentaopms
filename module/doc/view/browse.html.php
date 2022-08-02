<?php
/**
 * The browse view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     lib
 * @version     $Id: browse.html.php 958 2010-07-22 08:09:42Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<?php js::set('browseType', $browseType);?>
<?php js::set('docLang', $lang->doc);?>
<?php js::set('confirmDelete', $lang->doc->confirmDelete)?>
<?php js::set('appTab', $app->tab)?>
<?php if($browseType == 'bySearch'):?>
<div class="cell<?php if($browseType == 'bySearch') echo ' show';?>" id="queryBox" data-module=<?php echo $type . 'Doc';?>></div>
<?php endif;?>
<div class="main-row fade" id="mainRow">
  <div id="mainContent">
    <div class="panel block-files block-sm no-margin">
      <?php if(empty($docs)):?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->doc->noDoc;?></span></p>
      </div>
      <?php else:?>
      <div class="panel-body">
        <table class="table table-borderless table-hover table-files table-fixed no-margin">
          <thead>
            <tr>
              <?php if($browseType !== 'bySearch'):?>
              <th class="c-name"><?php echo $lang->doc->title;?></th>
              <th class="w-200px c-name"><?php echo $lang->doc->object;?></th>
              <?php else:?>
              <th class="c-id"><?php echo $lang->doc->id;?></th>
              <th class="c-name"><?php echo $lang->doc->title;?></th>
              <?php endif;?>
              <th class="c-num"><?php echo $lang->doc->size;?></th>
              <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
              <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
              <th class="c-datetime"><?php echo $lang->doc->editedDate;?></th>
              <th class="w-90px text-center"><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($docs as $doc):?>
            <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
            <?php $collectTitle = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
            <tr>
              <?php if($browseType !== 'bySearch'):?>
              <?php if(common::hasPriv('doc', 'view')):?>
              <td class="c-name"><?php echo html::a($this->createLink('doc', 'view', "docID=$doc->id&version=0", '', true), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "title='{$doc->title}' class='iframe' data-width='90%'");?></td>
              <?php else:?>
              <td class='c-name' title='<?php echo $doc->title;?>'><?php echo "<i class='icon icon-file-text text-muted'></i> {$doc->title}";?></td>
              <?php endif;?>
              <td class='c-name'>
                <?php
                if(!empty($doc->objectType))
                {
                    echo $lang->{$doc->objectType}->common . ' : ';
                    echo html::a($this->createLink($doc->objectType, 'view', "objectID={$doc->objectID}"), $doc->objectName, '', "data-app='{$doc->objectType}' title='{$doc->objectName}'");
                }
                ?>
              </td>
              <?php else:?>
              <?php if(common::hasPriv('doc', 'objectLibs')):?>
              <?php $objectID = isset($doc->{$type}) ? $doc->{$type} : 0;?>
              <td class="c-id"><?php echo html::a($this->createLink('doc', 'objectLibs', "type=$type&objectID=$objectID&libID=$doc->lib&docID=$doc->id"), $doc->id, '', "title='{$doc->id}' data-app='{$this->app->tab}'");?></td>
              <td class="c-name"><?php echo html::a($this->createLink('doc', 'objectLibs', "type=$type&objectID=$objectID&libID=$doc->lib&docID=$doc->id"), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "title='{$doc->title}' data-app='{$this->app->tab}'");?></td>
              <?php else:?>
              <td class='c-id' title='<?php echo $doc->id;?>'><?php echo "<i class='icon icon-file-text text-muted'></i> {$doc->id}";?></td>
              <td class='c-name' title='<?php echo $doc->title;?>'><?php echo "<i class='icon icon-file-text text-muted'></i> {$doc->title}";?></td>
              <?php endif;?>
              <?php endif;?>
              <td class="c-num"><?php echo $doc->fileSize ? $doc->fileSize : '-';?></td>
              <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
              <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'y-m-d');?></td>
              <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'y-m-d');?></td>
              <td class="c-actions">
                <?php if(common::canBeChanged('doc', $doc)):?>
                <?php if(common::hasPriv('doc', 'collect')):?>
                <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><i class='icon <?php echo $star;?>'></i></a>
                <?php endif;?>
                <?php common::printLink('doc', 'edit', "docID=$doc->id&comment=false", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn btn-link iframe'", true, true)?>
                <?php common::printLink('doc', 'delete', "docID=$doc->id&confirm=no", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn btn-link'")?>
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
