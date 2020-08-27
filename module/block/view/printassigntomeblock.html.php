<?php
/**
 * The assigntome block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<div id='assigntomeBlock'>
  <ul class="nav nav-secondary">
    <?php $isFirstTab = true; ?>
    <?php foreach($hasViewPriv as $type => $bool):?>
    <li<?php if($isFirstTab) {echo ' class="active"';}?>>
        <a data-tab href='#assigntomeTab-<?php echo $type;?>' onClick="changeLabel('<?php echo $type;?>')">
        <?php echo $lang->block->availableBlocks->$type;?>
        <span class='label label-light label-badge label-assignto <?php echo $type . "-count "; echo $isFirstTab ? '' : 'hidden'; $isFirstTab = false ?>'><?php echo $count[$type];?></span>
      </a>
    </li>
    <?php endforeach;?>
  </ul>
  <div class="tab-content">
    <?php $isFirstTab = true; ?>
    <?php foreach($hasViewPriv as $type => $bool):?>
    <div class="tab-pane<?php if($isFirstTab) {echo ' active'; $isFirstTab = false;}?>" id="assigntomeTab-<?php echo $type?>">
      <?php include "{$type}block.html.php";?>
    </div>
    <?php endforeach;?>
  </div>
</div>
<style>
#assigntomeBlock {position: relative;}
#assigntomeBlock .block-todoes {padding-top: 10px}
#assigntomeBlock > .nav {position: absolute; top: -41px; left: 120px;}
#assigntomeBlock .block-todoes .todoes-form{top: -50px;}
</style>
<script>
function changeLabel(type)
{
  $('.label-assignto').addClass('hidden');
  $('.' + type + '-count').removeClass('hidden');
}
</script>
