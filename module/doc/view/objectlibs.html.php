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
    <?php foreach($libs as $libID => $libName):?>
    <?php
    if($libID == 'project' and $from != 'doc') continue;

    $libLink = inlink('browse', "libID=$libID&browseType=all&param=0&orderBy=id_desc&from=$from");
    if($libID == 'project') $libLink = inlink('allLibs', "type=project&product=$object->id");
    if($libID == 'files')   $libLink = inlink('showFiles', "type=$type&objectID=$object->id");
    ?>
    <a class='lib <?php echo $libID == 'files' ? 'files' : '';?>' title='<?php echo $libName?>' href='<?php echo $libLink?>' data-id='<?php echo $libID;?>'>
      <?php if($libID != 'files' and $libID != 'project'):?><i class='icon icon-move'></i><?php endif;?>
      <img src='<?php echo $config->webRoot . 'theme/default/images/main/doc-lib.png'?>' class='file-icon' />
      <div class='lib-name' title='<?php echo $libName?>'><?php echo $libName?></div>
    </a>
    <?php endforeach; ?>
    <?php if(common::hasPriv('doc', 'createLib')) echo html::a(inlink('createLib', "type=$type&objectID={$object->id}"), "<i class='icon icon-plus'></i>", '', "class='lib addbtn' data-toggle='modal' data-type='iframe' title='{$lang->doc->createLib}'")?>
  </div>
</div>
<?php js::set('type', 'doc');?>
<?php include '../../common/view/footer.html.php';?>
