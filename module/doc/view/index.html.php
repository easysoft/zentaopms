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
<div id='libs'>
  <?php
  $allLibs = array();
  $allLibs['product'] = $products;
  $allLibs['project'] = $projects;
  $allLibs['custom']  = $customLibs;
  ?>
  <?php foreach($allLibs as $libsName => $libs):?>
    <?php if(empty($libs)) continue;?>
    <?php if($libsName === 'product'): ?>
      <div class='row'>
      <?php
      $objectNum   = 1;
      $objectCount = count($libs);
      ?>
      <?php foreach($libs as $product):?>
        <?php if($objectCount > 8 and $objectNum == 8):?>
        <div class='col-md-3'>
          <div class='libs-group clearfix lib-more'>
            <?php echo html::a(inlink('allLibs', "type=$libsName"), "{$lang->more}{$lang->doc->libTypeList['product']}<i class='icon icon-double-angle-right'></i>", '', "title='$lang->more' class='more'")?>
          </div>
        </div>
        <?php break;?>
        <?php endif;?>
        <?php if(isset($subLibs['product'][$product->id])):?>
        <div class='col-md-3'>
          <?php
          $i = 0;
          $subLibCount = count($subLibs['product'][$product->id]);
          ?>
          <div class='libs-group-heading libs-product-heading'>
            <?php
            $label = $objectNum == 1 ? "<span class='label label-primary'>{$lang->doclib->product}</span> " : '';
            echo html::a(inlink('objectLibs', "type=product&objectID=$product->id&from=doc"), $label . $product->name, '', "title='{$product->name}'");
            if($subLibCount > 3) echo html::a(inlink('objectLibs', "type=product&objectID=$product->id&from=doc"), "{$lang->more}<i class='icon icon-double-angle-right'></i>", '', "title='{$lang->more}' class='pull-right'");
            ?>
          </div>
          <div class='libs-group clearfix'>
            <?php
            $widthClass = 'w-lib-p100';
            if($subLibCount == 2) $widthClass = 'w-lib-p50';
            if($subLibCount >= 3) $widthClass = 'w-lib-p33';
            ?>
            <?php foreach($subLibs['product'][$product->id] as $libID => $libName):?>
            <?php
            if($libID == 'project')   $libLink = inlink('allLibs', "type=project&product=$product->id");
            elseif($libID == 'files') $libLink = inlink('showFiles', "type=product&objectID=$product->id");
            else                      $libLink = inlink('browse', "libID=$libID");
            ?>
            <a class='lib <?php echo $widthClass?>' title='<?php echo $libName?>' href='<?php echo $libLink ?>'>
              <img src='<?php echo $config->webRoot . 'theme/default/images/main/doc-lib.png'?>' class='file-icon' />
              <div class='lib-name' title='<?php echo $libName?>'><?php echo $libName?></div>
            </a>
            <?php if($i >= 2) break;?>
            <?php $i++;?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php $objectNum++;?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <hr />
    <?php elseif($libsName === 'project'): ?>
    <div class='row'>
      <?php
      $objectNum   = 1;
      $objectCount = count($libs);
      ?>
      <?php foreach($libs as $project):?>
        <?php if($objectCount > 8 and $objectNum == 8):?>
        <div class='col-md-3'>
          <div class='libs-group clearfix lib-more'>
            <?php echo html::a(inlink('allLibs', "type=$libsName"), "{$lang->more}{$lang->doc->libTypeList['project']}<i class='icon icon-double-angle-right'></i>", '', "title='$lang->more' class='more'")?>
          </div>
        </div>
        <?php break;?>
        <?php endif;?>
        <?php if(isset($subLibs['project'][$project->id])):?>
        <div class='col-md-3'>
          <?php
          $i = 0;
          $subLibCount = count($subLibs['project'][$project->id]);
          ?>
          <div class='libs-group-heading libs-project-heading'>
            <?php
            $label = $objectNum == 1 ? "<span class='label label-success'>{$lang->doclib->project}</span> " : '';
            echo html::a(inlink('objectLibs', "type=project&objectID=$project->id&from=doc"), $label . $project->name, '', "title='{$project->name}'");
            if($subLibCount > 3) echo html::a(inlink('objectLibs', "type=project&objectID=$project->id&from=doc"), "{$lang->more}<i class='icon icon-double-angle-right'></i>", '', "title='{$lang->more}' class='pull-right'");
            ?>
          </div>
          <div class='libs-group clearfix'>
            <?php
            $widthClass = 'w-lib-p100';
            if($subLibCount == 2) $widthClass = 'w-lib-p50';
            if($subLibCount >= 3) $widthClass = 'w-lib-p33';
            ?>
            <?php foreach($subLibs['project'][$project->id] as $libID => $libName):?>
            <?php
            if($libID == 'files') $libLink = inlink('showFiles', "type=project&objectID=$project->id");
            else                  $libLink = inlink('browse', "libID=$libID");
            ?>
            <a class='lib <?php echo $widthClass?>' title='<?php echo $libName?>' href='<?php echo $libLink ?>'>
              <img src='<?php echo $config->webRoot . 'theme/default/images/main/doc-lib.png'?>' class='file-icon' />
              <div class='lib-name' title='<?php echo $libName?>'><?php echo $libName?></div>
            </a>
            <?php if($i >= 2) break;?>
            <?php $i++;?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php $objectNum++;?>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
    <hr />
    <?php else:?>
      <div class='row clearfix'>
      <?php
      $objectNum   = 1;
      $objectCount = count($libs);
      ?>
      <?php foreach($libs as $libID => $libName):?>
        <?php if($objectCount > 8 and $objectNum == 8):?>
        <div class='col-md-3'>
          <div class='libs-group clearfix lib-more'>
            <?php echo html::a(inlink('allLibs', "type=$libsName"), "{$lang->more}{$lang->doc->libTypeList['custom']}<i class='icon icon-double-angle-right'></i>", '', "title='$lang->more' class='more'")?>
          </div>
        </div>
        <?php break;?>
        <?php endif;?>
        <div class='col-md-3'>
          <div class='libs-group-heading libs-custom-heading'>
            <?php
            if($objectNum == 1) echo "<span class='label label-info lable-custom'>{$lang->doc->customAB}</span> ";
            echo html::a(inlink('browse', "libID=$libID"), $libName, '', "title='{$libName}'")
            ?>
          </div>
        </div>
        <?php $objectNum++;?>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endforeach;?>
</div>
<?php include '../../common/view/footer.html.php';?>
