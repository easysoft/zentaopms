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
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->doc->systemLibs['product']?></strong>
    <div class='panel-actions pull-right'><?php if(common::hasPriv('doc', 'allLibs')) echo html::a(inlink('allLibs', "type=product"), $lang->more, '', "class='btn btn-sm'")?></div>
  </div>
  <div class='panel-body row'>
    <?php foreach($products as $product):?>
    <div class='col-md-4'>
      <div class='panel'>
        <div class='panel-heading'>
          <?php echo $product->name?>
          <div class='panel-actions pull-right'><?php if(common::hasPriv('doc', 'showLibs')) echo html::a(inlink('showLibs', "type=product&objectID=$product->id"), $lang->more, '', "class='btn btn-sm'")?></div>
        </div>
        <div class='panel-body row'>
        <?php if(isset($subLibs['product'][$product->id])):?>
          <?php foreach($subLibs['product'][$product->id] as $libID => $libName):?>
          <div class='col-md-4'>
            <div class='lib' title='<?php echo $libName?>'>
            <?php
            if($libID == 'project')
            {
                echo html::a(inlink('allLibs', "type=project&extra=product=$product->id"), $libName);
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
    </div>
    <?php endforeach;?>
  </div>
</div>
<div class='panel'>
  <div class='panel-heading'>
    <strong><?php echo $lang->doc->systemLibs['project']?></strong>
    <div class='panel-actions pull-right'><?php if(common::hasPriv('doc', 'allLibs')) echo html::a(inlink('allLibs', "type=project"), $lang->more, '', "class='btn btn-sm'")?></div>
  </div>
  <div class='panel-body row'>
    <?php foreach($projects as $project):?>
    <div class='col-md-4'>
      <div class='panel'>
        <div class='panel-heading'>
          <?php echo $project->name?>
          <div class='panel-actions pull-right'><?php if(common::hasPriv('doc', 'showLibs')) echo html::a(inlink('showLibs', "type=project&objectID=$project->id"), $lang->more, '', "class='btn btn-sm'")?></div>
        </div>
        <div class='panel-body row'>
        <?php if(isset($subLibs['project'][$project->id])):?>
          <?php foreach($subLibs['project'][$project->id] as $libID => $libName):?>
          <div class='col-md-4'>
            <div class='lib' title='<?php echo $libName?>'><?php echo html::a(inlink('browse', "libID=$libID"), $libName);?></div>
          </div>
          <?php endforeach?>
        <?php endif?>
        </div>
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
  <div class='panel-body row'>
    <?php foreach($customLibs as $libID => $libName):?>
      <div class='col-md-4'>
        <div class='lib' title='<?php echo $libName?>'><?php echo html::a(inlink('browse', "libID=$libID"), $libName);?></div>
      </div>
    <?php endforeach;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
