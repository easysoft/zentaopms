<?php
/**
 * The allLibs view file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     doc
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='featurebar'><strong><?php echo isset($lang->doc->systemLibs[$type]) ? $lang->doc->systemLibs[$type] : $lang->doc->custom?></strong></div>
<div class='libs'>
  <?php if(($type == 'project' or $type == 'product')):?>
    <?php foreach($libs as $lib):?>
      <?php if(isset($subLibs[$lib->id])): ?>
      <div class='libs-group-heading libs-<?php echo $type?>-heading'><i class='icon <?php echo $type == 'product' ? 'icon-cube-alt' : 'icon-folder-close-alt'?>'></i> <strong><?php echo $lib->name?></strong></div>
      <div class='libs-group clearfix'>
        <?php foreach($subLibs[$lib->id] as $subLibID => $subLibName):?>
        <?php
        if($subLibID == 'project')   $libLink = inlink('allLibs', "type=project&extra=product=$lib->id");
        elseif($subLibID == 'files') $libLink = inlink('showFiles', "type=$type&objectID=$lib->id");
        else                         $libLink = inlink('browse', "libID=$subLibID");
        ?>
        <a class='lib' title='<?php echo $subLibName?>' href='<?php echo $libLink ?>'><?php echo $subLibName?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php else: ?>
  <div class='libs-group clearfix'>
    <?php foreach($libs as $lib):?>
    <a class='lib' title='<?php echo $lib->name?>' href='<?php echo inlink('browse', "libID=$lib->id") ?>'><?php echo $lib->name?></a>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
<div class='clearfix pager-wrapper'><?php $pager->show('left');?></div>
<?php js::set('type', $type);?>
<?php include '../../common/view/footer.html.php';?>
