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
    <?php foreach($libs as $lib):?>
    <div>
      <?php if($type == 'project' or $type == 'product'):?>
      <div class='lib-heading <?php echo $type?>'><strong><i class='icon <?php echo $type == 'product' ? 'icon-cube-alt' : 'icon-folder-close-alt'?>'></i> <?php echo $lib->name?></strong></div>
        <div class='libs row'>
        <?php if(isset($subLibs[$lib->id])):?>
          <?php foreach($subLibs[$lib->id] as $subLibID => $subLibName):?>
          <div class='col-md-2'>
            <div class='lib' title='<?php echo $subLibName?>'>
            <?php
            if($subLibID == 'project')
            {
                echo html::a(inlink('allLibs', "type=project&extra=product=$lib->id"), $subLibName);
            }
            elseif($subLibID == 'files')
            {
                echo html::a(inlink('showFiles', "type=$type&objectID=$lib->id"), $subLibName);
            }
            else
            {
                echo html::a(inlink('browse', "libID=$subLibID"), $subLibName);
            }
            ?>
            </div>
          </div>
          <?php endforeach?>
        <?php endif?>
        </div>
      <?php else:?>
          <div class='col-md-2'>
        <div class='lib' title='<?php echo $lib->name?>'><?php echo html::a(inlink('browse', "libID=$lib->id"), $lib->name);?></div>
        </div>
      <?php endif;?>
    </div>
    <?php endforeach;?>
  <div class='panel-footer'><?php $pager->show();?><div style='clear:both'></div></div>
<?php js::set('type', $type);?>
<?php include '../../common/view/footer.html.php';?>
