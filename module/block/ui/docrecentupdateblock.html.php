<?php
declare(strict_types=1);
/**
* The docrecentupdate block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;
?>
<?php $canView = common::hasPriv('doc', 'view');?>
<div>
  <?php if(empty($docList)):?>
    <div class='table-empty-tip'><p><span class='text-muted'><?php echo $lang->doc->noDoc;?></p></span></div>
  <?php else:?>
  <div class="doc-list">
    <?php foreach($docList as $doc):?>
    <div class="doc-box">
      <a href='<?php echo $canView ? $this->createLink("doc", "view", "docID=$doc->id") : 'javascript:;';?>'>
        <div class="p-2 border">
            <span class='date-interval text-muted text-gray'>
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
            <h4 class="plug-title my-2 text-black font-bold" title="<?php echo $doc->title;?>">
              <?php
              $docType = $doc->type == 'text' ? 'wiki-file' : $doc->type;
              echo html::image("static/svg/{$docType}.svg", "class='file-icon inline'");
              ?>
              <?php echo $doc->title;?>
            </h4>
            <p class='edit-date text-muted text-gray mb-2'><?php echo $lang->doc->editedDate . (common::checkNotCN() ? ': ' : '：') . $doc->editedDate;?></p>
        </div>
      </a>
    </div>
    <?php endforeach;?>
  </div>
  <?php endif;?>
</div>
<?php
panel
(
    set('class', 'docrecentupdate-block ' . ($longBlock ? 'block-long' : 'block-sm')),
    set::title($block->title),
    rawContent()
);

render();
