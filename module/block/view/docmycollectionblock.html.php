<?php
/**
 * The docmycollectionblock view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     block
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<style>
.block-docmycollection .panel-body {padding: 0px 10px 20px;}
.block-docmycollection .doc-list {display: flex; flex-wrap: wrap; padding: 0 4px 0 0;}
.block-docmycollection .doc-list > .doc-box {border: unset; flex: 0 1 50%; padding: 8px; width: 0;}
.block-docmycollection .doc-list > .doc-box > button.btn {padding: 5px 10px; height: 100%; width: 100%; cursor: pointer; white-space: nowrap; text-align: unset; border: 1px solid rgba(227, 228, 233, 0.6)}
.block-docmycollection .doc-list > .doc-box > .btn:hover {background: unset;}
.block-docmycollection .doc-list > .doc-box > .btn.no-priv {cursor: not-allowed; pointer-events: unset;}
.block-docmycollection .doc-list > .doc-box > .btn.no-priv p {pointer-events: none;}
.block-docmycollection .doc-list > .doc-box .date-interval {float: right; padding: 8px 0 8px 12px;}
.block-docmycollection .doc-list > .doc-box > .btn > h4 {padding-right: 5px;}
.block-docmycollection .doc-list > .doc-box .file-icon {margin-right: 2px; margin-bottom: 2px;}
.block-docmycollection .doc-list > .doc-box .plug-title {height: 16px; overflow: hidden;}

.block-docmycollection.block-sm .doc-list > .doc-box {flex: 0 1 100%;}
</style>
<?php $canView = common::hasPriv('doc', 'view');?>
<div class="panel-body">
  <?php if(empty($docList)):?>
    <div class='table-empty-tip'><p><span class='text-muted'><?php echo $lang->doc->noDoc;?></p></span></div>
  <?php else:?>
  <div class="doc-list">
    <?php foreach($docList as $doc):?>
    <div class="doc-box">
    <button class="btn shadow-primary-hover <?php if(!$canView) echo 'no-priv';?>" data-link='<?php echo $this->createLink("doc", "view", "docID=$doc->id");?>'>
        <span class='date-interval text-muted'>
          <?php
          $interval = $doc->editInterval;
          $editTip  = $lang->doc->todayUpdated;
          if($interval->year)
          {
            $editTip = sprintf($lang->doc->yearsUpdated, $interval->year);
          }
          elseif($interval->month)
          {
            $editTip = sprintf($lang->doc->monthsUpdated, $interval->month);
          }
          elseif($interval->day)
          {               $editTip = sprintf($lang->doc->daysUpdated, $interval->day);
          }
          echo $editTip;
          ?>
        </span>
        <h4 class="plug-title" title="<?php echo $doc->title;?>">
          <?php
          $docType = $doc->type == 'text' ? 'wiki-file' : $doc->type;
          echo html::image("static/svg/{$docType}.svg", "class='file-icon'");
          ?>
          <?php echo $doc->title;?>
        </h4>
        <p class='edit-date text-muted'><?php echo $lang->doc->editedDate . (common::checkNotCN() ? ': ' : '：') . $doc->editedDate;?></p>
      </button>
    </div>
    <?php endforeach;?>
  </div>
  <?php endif;?>
</div>
<script>
$(function()
{
    $('.doc-box .btn').on('click', function()
    {
        if($(this).hasClass('no-priv')) return;

        location.href = $(this).data('link');
    });
});
</script>
