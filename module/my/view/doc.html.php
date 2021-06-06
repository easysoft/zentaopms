<?php
/**
 * The project view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     my
 * @version     $Id
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('mode', 'doc');?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    echo html::a(inlink($app->rawMethod, "mode=doc&type=openedbyme"), "<span class='text'>{$lang->doc->openedByMe}</span>" . ($type == 'openedbyme' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'openedbyme' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink($app->rawMethod, "mode=doc&type=editedbyme"), "<span class='text'>{$lang->doc->editedByMe}</span>" . ($type == 'editedbyme' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'editedbyme' ? ' btn-active-text' : '') . "'");
    ?>
  </div>
</div>
<div id="mainContent" class='main-table'>
  <?php if(empty($docs)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->doc->noDoc;?></span>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' id='projectForm' method='post' data-ride='table' data-nested='true' data-checkable='false'>
    <table class='table table-fixed' id='docList'>
      <thead>
        <tr>
          <th class="c-name"><?php echo $lang->doc->title;?></th>
          <th class="c-num"><?php echo $lang->doc->size;?></th>
          <?php if($type != 'openedbyme'):?>
          <th class="c-user"><?php echo $lang->doc->addedBy;?></th>
          <?php endif;?>
          <th class="c-datetime"><?php echo $lang->doc->addedDate;?></th>
          <th class="c-datetime"><?php echo $lang->doc->editedDate;?></th>
          <th class="w-90px text-center"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id='docTableList'>
        <?php foreach($docs as $doc):?>
        <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'icon-star text-yellow' : 'icon-star-empty';?>
        <?php $collectTitle = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
        <tr>
          <td class="c-name"><?php echo html::a($this->createLink('doc', 'view', "docID=$doc->id&version=0&from={$lang->navGroup->doc}", '', true), "<i class='icon icon-file-text text-muted'></i> &nbsp;" . $doc->title, '', "title={$doc->title} class='iframe' data-width='90%'");?></td>
          <td class="c-num"><?php echo $doc->fileSize ? $doc->fileSize : '-';?></td>
          <?php if($type != 'openedbyme'):?>
          <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
          <?php endif;?>
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
    <div class='table-footer'>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
