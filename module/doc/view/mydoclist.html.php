<?php
/**
 * The allLibs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     doc
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<style>
body {margin-bottom: 25px;}
#docListForm th.c-id {width: 72px;}
#docListForm th.c-user {width: 80px;}
#docListForm th.c-actions {width: 84px; padding-left: 15px;}
#docListForm .c-module, #docListForm .c-object {width: 120px; overflow: hidden; white-space: nowrap; text-overflow: clip;}
#docListForm .c-object {width: 180px;}
#docListForm .table .c-name > .doc-title {display: inline-block; overflow: hidden; background: transparent; padding-right:0px; max-width: calc(100% - 40px);}
#docListForm .table .c-name > span.doc-title {line-height: 0; vertical-align: inherit;}
#docListForm .table .c-name[data-status=draft] > .doc-title {max-width: calc(100% - 80px);}
#docListForm .table .c-name > .draft {background-color:rgba(129, 102, 238, 0.12); color:#8166EE;}
#docListForm .table .c-name > .ajaxCollect {float: right; position: relative; right: 10px; top: 0px; padding: 0 6px;}
#docListForm table.table > thead > tr {height: 32px;}
#docListForm .checkbox-primary {line-height: 16px;}
#docListForm table .checkbox-primary {top: -2px;}
#docListForm .checkbox-primary > label {height: 16px; line-height: 16px; padding-left: 16px;}
#docListForm .checkbox-primary > label:before {left: -1px; font-size: 10px;}
#docListForm .checkbox-primary > label:after {width: 12px; height: 12px;}
.table-files .btn {padding: 2px;}
</style>
<?php if(common::checkNotCN()):?>
<style>
#docListForm th.c-date {width: 108px;}
#docListForm th.c-user {width: 102px !important;}
</style>
<?php endif;?>
<?php js::set('isMyCollection', $type == 'collect' ? 1 : 0);?>
<?php if(empty($docs)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->doc->noDoc;?></span>
    <?php
    if($browseType != 'bysearch' and $libID and common::hasPriv('doc', 'create'))
    {
        echo $this->doc->printCreateBtn($lib, $moduleID, 'list');
    }
    ?>
  </p>
</div>
<?php else:?>
<?php $vars = "$libID=$libID&moduleID=$moduleID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
<?php if($app->rawMethod == 'myspace') $vars = "type=$type&" . $vars;?>
<div>
  <form class='main-table' method='post' id='docListForm'>
    <table class="table table-files has-sort-head">
      <thead>
        <tr>
          <th class="c-id">
            <?php if($canExport):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class="c-name"><?php common::printOrderLink('title', $orderBy, $vars, $lang->doc->title);?></th>
          <?php if($type != 'mine'):?>
          <th class='c-object'><?php echo $lang->doc->object;?></th>
          <th class="c-module"><?php common::printOrderLink('module', $orderBy, $vars, $lang->doc->position);?></th>
          <?php endif;?>
          <?php if(!in_array($type, array('mine', 'createdby'))):?>
          <th class="c-user"><?php common::printOrderLink('addedBy', $orderBy, $vars, $lang->doc->addedByAB);?></th>
          <?php endif;?>
          <th class="c-date"><?php common::printOrderLink('addedDate', $orderBy, $vars, $lang->doc->addedDate);?></th>
          <?php if($type != 'mine'):?>
          <th class="c-user"><?php common::printOrderLink('editedBy', $orderBy, $vars, $lang->doc->editedBy);?></th>
          <?php endif;?>
          <th class="c-date"><?php common::printOrderLink('editedDate', $orderBy, $vars, $lang->doc->editedDate);?></th>
          <th class="c-actions"><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($docs as $doc):?>
        <?php $star = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? 'star' : 'star-empty';?>
        <?php $collectTitle = strpos($doc->collector, ',' . $this->app->user->account . ',') !== false ? $lang->doc->cancelCollection : $lang->doc->collect;?>
        <tr>
        <?php $objectID = isset($doc->{$type}) ? $doc->{$type} : 0;?>
          <td class="c-id" title='<?php echo $doc->id?>'>
            <?php if($canExport):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='docIDList[]' value='<?php echo $doc->id;?>'/>
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $doc->id);?>
          </td>
          <td class="c-name" title='<?php echo $doc->title;?>' data-status='<?php echo $doc->status?>'>
            <?php
            $docType = zget($config->doc->iconList, $doc->type);
            $icon    = html::image("static/svg/{$docType}.svg", "class='file-icon'");
            if(common::hasPriv('doc', 'view'))
            {
                echo html::a($this->createLink('doc', 'view', "docID=$doc->id"), $icon . $doc->title, '', "title='{$doc->title}' class='doc-title' data-app='{$this->app->tab}'");
            }
            else
            {
                echo "<span class='doc-title'>$icon {$doc->title}</span>";
            }
            ?>
            <?php if($doc->status == 'draft') echo "<span class='label label-badge draft'>{$lang->doc->draft}</span>";?>
            <?php if(common::canBeChanged('doc', $doc) and common::hasPriv('doc', 'collect') and $libType and $libType != 'api'):?>
            <a data-url="<?php echo $this->createLink('doc', 'collect', "objectID=$doc->id&objectType=doc");?>" title="<?php echo $collectTitle;?>" class='btn btn-link ajaxCollect'><?php echo html::image("static/svg/{$star}.svg", "class='$star'");?></a>
            <?php endif;?>
          </td>
          <?php if($type != 'mine'):?>
          <td class="c-object" title='<?php echo $doc->objectName;?>'>
            <?php $objectIcon = zget($config->doc->objectIconList, $doc->objectType);?>
            <?php echo "<i class='icon $objectIcon'></i> " . $doc->objectName;?>
          </td>
          <td class="c-module">
            <?php
            $moduleDivide = $doc->moduleName ? ' > ' : '';
            $moduleName   = $doc->libName . $moduleDivide . $doc->moduleName;
            $spaceMethod  = zget($config->doc->spaceMethod, $doc->objectType);
            $spaceParams  = "libID={$doc->lib}&moduleID={$doc->module}";
            if(in_array($doc->objectType, array('product', 'project', 'execution', 'custom'))) $spaceParams = "objectID={$doc->objectID}&$spaceParams";
            if($doc->objectType == 'mine') $spaceParams = "type={$doc->objectType}&$spaceParams";

            if(common::hasPriv('doc', $spaceMethod))
            {
                echo html::a($this->createLink('doc', $spaceMethod, $spaceParams), $moduleName, '', "title='$moduleName' data-app='{$this->app->tab}'");
            }
            else
            {
                echo "<span>$moduleName</span>";
            }
            ?>
          </td>
          <?php endif;?>
          <?php if(!in_array($type, array('mine', 'createdby'))):?>
          <td class="c-user"><?php echo zget($users, $doc->addedBy);?></td>
          <?php endif;?>
          <td class="c-datetime"><?php echo formatTime($doc->addedDate, 'Y-m-d');?></td>
          <?php if($type != 'mine'):?>
          <td class="c-user"><?php echo zget($users, $doc->editedBy);?></td>
          <?php endif;?>
          <td class="c-datetime"><?php echo formatTime($doc->editedDate, 'Y-m-d');?></td>
          <td class="c-actions">
            <?php if(common::canBeChanged('doc', $doc)):?>
            <?php
            $iframe   = '';
            $onlybody = false;
            if($doc->type != 'text' or isonlybody())
            {
                $iframe   = 'iframe';
                $onlybody = true;
            }
            ?>
            <?php common::printLink('doc', 'edit', "docID=$doc->id&comment=false", "<i class='icon icon-edit'></i>", '', "title='{$lang->edit}' class='btn $iframe'", true, $onlybody);?>
            <?php common::printLink('doc', 'delete', "docID=$doc->id&confirm=no", "<i class='icon icon-trash'></i>", 'hiddenwin', "title='{$lang->delete}' class='btn'")?>
            <?php endif;?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if(!empty($docs)):?>
    <div class='table-footer'>
      <?php if($canExport):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-statistic"><?php echo sprintf($lang->doc->docSummary, count($docs));?></div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
    <?php endif;?>
  <form>
</div>
<?php endif;?>
<script>
$(function()
{
    var pageSummary    = '<?php echo sprintf($lang->doc->docSummary, count($docs));?>';
    var checkedSummary = '<?php echo $lang->doc->docCheckedSummary?>';
    $('#docListForm').table(
    {
        replaceId: 'docIDList',
        statisticCreator: function(table)
        {
            var $table       = table.getTable();
            var $checkedRows = $table.find('tbody>tr.checked');
            var checkedTotal = $checkedRows.length;
            return checkedTotal ? checkedSummary.replace('%total%', checkedTotal) : pageSummary;
        }
    });

    $('.ajaxCollect').click(function()
    {
        if(isMyCollection) window.location.reload();
    });

});
</script>
