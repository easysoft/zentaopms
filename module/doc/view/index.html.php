<?php
/**
 * The index view file of doc module of ZenTaoPMS.
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
<div id="featurebar"><strong><?php echo $lang->doclib->all?></strong></div>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->doc->systemLibs['product']?></strong>
    <div class='panel-actions pull-right'><?php if(common::hasPriv('doc', 'allLibs')) echo html::a(inlink('allLibs', "type=product"), $lang->more, '', "class='btn btn-sm'")?></div>
  </div>
  <div class='panel-body'>
    <?php foreach($products as $product):?>
    <div>
      <div class='lib-heading product'><strong><i class='icon icon-cube-alt'></i> <?php echo $product->name?></strong></div>
      <div class='libs row'>
        <?php if(isset($subLibs['product'][$product->id])):?>
        <?php foreach($subLibs['product'][$product->id] as $libID => $libName):?>
        <div class='col-md-2'>
          <div class='lib' title='<?php echo $libName?>'>
          <?php
          if($libID == 'project')
          {
              echo html::a(inlink('allLibs', "type=project&extra=product=$product->id"), $libName);
          }
          elseif($libID == 'files')
          {
              echo html::a(inlink('showFiles', "type=product&objectID=$product->id"), $libName);
          }
          else
          {
              echo html::a(inlink('browse', "libID=$libID"), $libName);
          }
          ?>
          </div>
        </div>
        <?php endforeach?>
        <?php endif?>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->doc->systemLibs['project']?></strong>
    <div class='panel-actions pull-right'><?php if(common::hasPriv('doc', 'allLibs')) echo html::a(inlink('allLibs', "type=project"), $lang->more, '', "class='btn btn-sm'")?></div>
  </div>
  <div class='panel-body'>
    <?php foreach($projects as $project):?>
    <div>
      <div class='lib-heading project'><strong><i class='icon icon-folder-close-alt'></i> <?php echo $project->name?></strong></div>
      <div class='libs row'>
      <?php if(isset($subLibs['project'][$project->id])):?>
        <?php foreach($subLibs['project'][$project->id] as $libID => $libName):?>
        <div class='col-md-2'>
          <div class='lib' title='<?php echo $libName?>'>
          <?php
          if($libID == 'files')
          {
              echo html::a(inlink('showFiles', "type=project&objectID=$project->id"), $libName);
          }
          else
          {
              echo html::a(inlink('browse', "libID=$libID"), $libName);
          }
          ?>
          </div>
        </div>
        <?php endforeach?>
      <?php endif?>
      </div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->doc->custom?></strong>
    <div class='panel-actions pull-right'><?php if(common::hasPriv('doc', 'allLibs')) echo html::a(inlink('allLibs', "type=custom"), $lang->more, '', "class='btn btn-sm'")?></div>
  </div>
  <div class='panel-body libs row'>
    <?php foreach($customLibs as $libID => $libName):?>
    <div class='col-md-2'>
      <div class='lib' title='<?php echo $libName?>'><?php echo html::a(inlink('browse', "libID=$libID"), $libName);?></div>
    </div>
    <?php endforeach;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
