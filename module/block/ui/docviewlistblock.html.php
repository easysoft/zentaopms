<?php
declare(strict_types=1);
/**
* The docviewlist block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;
?>
<div>
  <?php if(empty($docList)):?>
    <div class='table-empty-tip'><p><span class='text-muted'><?php echo $lang->doc->noDoc;?></p></span></div>
  <?php else:?>
  <div class="doc-list table-row w-full">
    <?php $rank = 1;?>
    <?php foreach($docList as $doc):?>
    <?php if(!$doc->views) continue;?>
    <div class='doc-title'>
      <span class="pri-<?php echo $rank;?> label-pri label-rank label-rank-<?php echo $rank;?>"><?php echo $rank;?></span>
      <?php
      if(common::hasPriv('doc', 'view'))
      {
          echo html::a($this->createLink('doc', 'view', "docID=$doc->id"), $doc->title, '', "title='{$doc->title}' class='doc-name' data-app='{$this->app->tab}'");
      }
      else
      {
          echo "<span class='doc-name' title='{$doc->title}'>{$doc->title}</span>";
      }
      ?>
      <div class='label-collect-count'>
        <?php $flameClass = $rank < 4 ? '' : 'text-gray';?>
        <?php echo html::image("static/svg/flame.svg", "class='icon-flame $flameClass'");?>
        <span class='view-text'><?php echo sprintf($lang->doc->viewCount, $doc->views);?></span>
      </div>
    </div>
    <?php $rank ++;?>
    <?php endforeach;?>
  </div>
  <?php endif;?>
</div>
<?php
blockPanel(set::className('doccollectlist-block'), rawContent());

render();
