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
    <?php foreach($hasViewPriv as $type => $bool):?>
    <?php if($config->global->flow != 'full' && $config->global->flow != 'onlyTask' && $type == 'task') continue;?>
    <?php if($config->global->flow != 'full' && $config->global->flow != 'onlyTest' && $type == 'bug') continue;?>
    <li<?php if($type === 'todo') echo ' class="active"';?>><a data-tab href='#assigntomeTab-<?php echo $type;?>'><?php echo $lang->block->availableBlocks->$type;?></a></li>
    <?php endforeach;?>
  </ul>
  <div class="tab-content">
    <?php foreach($hasViewPriv as $type => $bool):?>
    <?php if($config->global->flow != 'full' && $config->global->flow != 'onlyTask' && $type == 'task') continue;?>
    <?php if($config->global->flow != 'full' && $config->global->flow != 'onlyTest' && $type == 'bug') continue;?>
    <div class="tab-pane<?php if($type === 'todo') echo ' active';?>" id="assigntomeTab-<?php echo $type?>">
      <?php include "{$type}block.html.php";?>
    </div>
    <?php endforeach;?>
  </div>
</div>
<style>
#assigntomeBlock {position: relative;}
#assigntomeBlock .block-todoes {padding-top: 10px}
#assigntomeBlock > .nav {position: absolute; top: -41px; left: 120px;}
#assigntomeBlock .block-todoes .todoes-form{top: -55px;}
</style>
