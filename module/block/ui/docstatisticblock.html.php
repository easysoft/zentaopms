<?php
declare(strict_types=1);
/**
* The docstatistic block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;
?>
<div class="flex items-center justify-center h-full">
  <div class='flex flex-column statistic'>
    <div class='flex'>
      <div class="tile">
        <div class="tile-amount"><?php echo (int)$statistic->totalDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->allDoc;?></div>
      </div>
      <div class="tile border-right">
        <div class="tile-amount"><?php echo (int)$statistic->todayEditedDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->todayEdited;?></div>
      </div>
    </div>
  </div>
  <div class='flex flex-column created'>
    <div class='flex'>
      <?php if(common::hasPriv('doc', 'mySpace')):?>
      <a class="tile" href="<?php echo $this->createLink('doc', 'mySpace', 'type=createdBy');?>">
      <?php else:?>
      <div class="tile">
      <?php endif;?>
        <div class="tile-amount"><?php echo (int)$statistic->myDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->docCreated;?></div>
      <?php if(common::hasPriv('doc', 'mySpace')):?>
      </a>
      <?php else:?>
      </div>
      <?php endif;?>
      <div class="tile">
        <div class="tile-amount"><?php echo (int)$statistic->myDoc->docViews;?></div>
        <div class="tile-title"><?php echo $lang->doc->docViews;?></div>
      </div>
      <div class="tile border-right">
        <div class="tile-amount"><?php echo (int)$statistic->myDoc->docCollects;?></div>
        <div class="tile-title"><?php echo $lang->doc->docCollects;?></div>
      </div>
    </div>
  </div>
  <div class='flex flex-column edited'>
    <div class='flex'>
      <div class="tile">
        <div class="tile-amount"><?php echo (int)$statistic->myEditedDocs;?></div>
        <div class="tile-title"><?php echo $lang->doc->docEdited;?></div>
      </div>
    </div>
  </div>
</div>
<?php
blockPanel(set::className('docstatistic-block'), rawContent());
render();
