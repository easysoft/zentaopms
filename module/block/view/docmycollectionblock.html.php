<?php
/**
 * The docmycollectionblock view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<style>
.block-docmycollection .panel-body {padding: 0px 20px;}
.block-docmycollection .doc-list {display: flex; flex-wrap: wrap; padding: 2px 4px 4px 4px;}
.block-docmycollection .doc-list > .doc-item {flex: 1 1 26%; padding: 8px 10px; border: 1px solid #EDEEF2; border-radius: 4px; margin-right: 10px; margin-bottom: 16px; width: 0px; cursor: pointer;}
.block-docmycollection .doc-list > .doc-item .date-interval {float: right; padding: 8px 0px;}
.block-docmycollection .doc-list > .doc-item .file-icon {float: left; padding: 8px 4px;}
.block-docmycollection .doc-list > .doc-item > .plug-title {height: 16px; overflow: hidden;}
.block-docmycollection .doc-list > .doc-item .edit-date {overflow: hidden; height: 16px;}
.block-docmycollection .no-doc {text-align: center; vertical-align: middle; height: 50px;}

.block-docmycollection.block-sm .doc-list > .doc-item {flex: 1 1 100%;}
</style>
<?php $canView = common::hasPriv('doc', 'view');?>
<div class="panel-body">
  <div class="plug">
    <?php if($docList):?>
    <div class="doc-list">
      <?php foreach($docList as $doc):?>
      <?php if($canView):?>
      <a class="doc-item shadow-primary-hover" href='<?php echo $this->createlink("doc", "view", "docid=$doc->id");?>'>
      <?php else:?>
      <div class="doc-item shadow-primary-hover">
      <?php endif;?>
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
          {
            $editTip = sprintf($lang->doc->daysUpdated, $interval->day);
          }
          echo $editTip;
          ?>
        </span>
        <?php
        $docType = $doc->type == 'text' ? 'wiki-file' : $doc->type;
        echo html::image("static/svg/{$docType}.svg", "class='file-icon'");
        ?>
        <h4 class="plug-title" title="<?php echo $doc->title;?>"><?php echo $doc->title;?></h4>
        <p class='edit-date text-muted'><?php echo $lang->doc->editedDate . (common::checkNotCN() ? ': ' : '：') . $doc->editedDate;?></p>
      <?php if($canView):?>
      </a>
      <?php else:?>
      </div>
      <?php endif;?>
      <?php endforeach;?>
    </div>
    <?php else:?>
    <div class='no-doc'><?php echo $lang->doc->noDoc;?></div>
    <?php endif;?>
  </div>
</div>
