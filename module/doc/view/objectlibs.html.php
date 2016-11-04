<?php
/**
 * The objectLibs view file of doc module of ZenTaoPMS.
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
<div id='featurebar'>
  <strong><?php echo $object->name?></strong>
  <div class='actions'><?php echo html::backButton();?></div>
</div>
<div id='libs'>
  <div class='libs-group clearfix'>
    <?php foreach($libs as $libID => $lib):?>
    <?php
    if($libID == 'project' and $from != 'doc') continue;

    $libLink = inlink('browse', "libID=$lib->id&browseType=all&param=0&orderBy=id_desc&from=$from");
    if($libID == 'project') $libLink = inlink('allLibs', "type=project&extra=product=$lib->id");
    if($libID == 'files')   $libLink = inlink('showFiles', "type=$type&objectID=$lib->id");
    ?>
    <a class='lib' title='<?php echo $lib->name?>' href='<?php echo $libLink?>'>
      <i class='file-icon icon icon-folder-close-alt'></i>
      <div class='lib-name' title='<?php echo $lib->name?>'><?php echo $lib->name?></div>
    </a>
    <?php endforeach; ?>
  </div>
</div>
<?php js::set('type', 'doc')?>
<?php include '../../common/view/footer.html.php';?>
