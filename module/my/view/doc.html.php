<?php
/**
 * The project view file of my module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
    foreach($lang->my->featureBar[$app->rawMethod]['doc'] as $typeKey => $name)
    {
        echo html::a(inlink($app->rawMethod, "mode=doc&type=$typeKey"), "<span class='text'>{$name}</span>" . ($type == $typeKey ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == $typeKey ? ' btn-active-text' : '') . "'");
    }
    ?>
  </div>
  <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->my->byQuery;?></a>
</div>
<div id="mainContent">
  <?php if(empty($docs)):?>
  <div class="cell<?php if($type == 'bySearch') echo ' show';?>" id="queryBox" data-module=<?php echo 'contributeDoc';?>></div>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->doc->noDoc;?></span>
    </p>
  </div>
  <?php else:?>
  <div class="cell<?php if($type == 'bySearch') echo ' show';?>" id="queryBox" data-module=<?php echo 'contributeDoc';?>></div>
  <form id='docForm' class="main-table" method='post' data-ride='table'>
    <table class='table table-fixed' id='docList'>
      <thead>
        <tr>
          <th class="c-id"><?php echo $lang->doc->id;?></th>
          <th class="c-name"><?php echo $lang->doc->title;?></th>
          <th class="c-object"><?php echo $lang->doc->object;?></th>
          <?php if($type != 'openedbyme'):?>
          <th class="c-user"><?php echo $lang->doc->addedByAB;?></th>
          <?php endif;?>
          <th class="c-date"><?php echo $lang->doc->addedDate;?></th>
          <?php if($type == 'openedbyme'):?>
          <th class="c-user"><?php echo $lang->doc->editedBy;?></th>
          <?php endif;?>
          <th class="c-date"><?php echo $lang->doc->editedDate;?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody id='docTableList'>
        <?php foreach($docs as $doc):?>
        <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'star' : 'star-empty';?>
        <?php $collectTitle = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
        <tr>
          <td class="c-id"><?php echo $doc->id;?></td>
          <td class="c-name" title='<?php echo $doc->title;?>' data-status='<?php echo $doc->status;?>'>
          <?php
          $docType = $doc->type == 'text' ? 'wiki-file' : $doc->type;
          $icon    = html::image("static/svg/{$docType}.svg", "class='file-icon'");
          if(common::hasPriv('doc', 'view'))
          {
              echo html::a($this->createLink('doc', 'view', "docID=$doc->id"), $icon . $doc->title, '', "title='{$doc->title}' class='doc-title' data-app='doc'");
          }
          else
          {
              echo "<span class='doc-title'>$icon {$doc->title}</span>";
          }
          ?>
          <?php if($doc->status == 'draft') echo "<span class='label label-badge draft'>{$lang->doc->draft}</span>";?>
          <?php if(common::canBeChanged('doc', $doc) and common::hasPriv('doc', 'collect')):?>
            <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><?php echo html::image("static/svg/{$star}.svg", "class='$star'");?></a>
          <?php endif;?>
          </td>
          <td class="c-object" title='<?php echo $doc->objectName;?>'>
            <?php $objectIcon = zget($config->doc->objectIconList, $doc->objectType);?>
            <?php echo "<i class='icon $objectIcon'></i> " . $doc->objectName;?>
          </td>
          <?php if($type != 'openedbyme'):?>
          <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
          <?php endif;?>
          <td class="c-date"><?php echo formatTime($doc->addedDate, 'Y-m-d');?></td>
          <?php if($type == 'openedbyme'):?>
          <td class="c-user"><?php echo zget($users, $doc->editedBy);?></td>
          <?php endif;?>
          <td class="c-date"><?php echo formatTime($doc->editedDate, 'Y-m-d');?></td>
          <td class="c-actions">
            <?php if(common::canBeChanged('doc', $doc)):?>
            <?php common::printLink('doc', 'edit', "docID=$doc->id&comment=false&from={$lang->navGroup->doc}", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn' data-app='doc'", true, false)?>
            <?php common::printLink('doc', 'delete', "docID=$doc->id&confirm=no&from={$lang->navGroup->doc}", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn'")?>
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
