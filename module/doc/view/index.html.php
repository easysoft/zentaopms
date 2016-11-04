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
<div id='featurebar'><strong><?php echo $lang->doclib->all?></strong></div>
<div id='libs'>
  <table class='table'>
  <?php
  $libs = array();
  $libs['product'] = $products;
  $libs['project'] = $projects;
  $libs['custom']  = $customLibs;
  ?>
  <?php foreach ($libs as $libsName => $libs):?>
  <?php $i = 0;?>
  <tr>
    <th class='w-100px lib-heading'><?php echo $lang->doc->libTypeList[$libsName]?></th>
    <td class='row'>
    <?php if($libsName === 'product'): ?>
      <table class='table product table-data'>
      <?php foreach($libs as $product):?>
        <?php if(isset($subLibs['product'][$product->id])):?>
        <?php if($i % 3 == 0) echo '<tr>'?>
        <td>
          <div class='libs-group-heading libs-product-heading'><strong><?php echo html::a(inlink('objectLibs', "type=product&objectID=$product->id&from=doc"), $product->name)?></strong></div>
          <div class='libs-group clearfix'>
            <?php foreach($subLibs['product'][$product->id] as $libID => $libName):?>
            <?php
            if($libID == 'project')   $libLink = inlink('allLibs', "type=project&extra=product=$product->id");
            elseif($libID == 'files') $libLink = inlink('showFiles', "type=product&objectID=$product->id");
            else                      $libLink = inlink('browse', "libID=$libID");
            ?>
            <a class='lib' title='<?php echo $libName?>' href='<?php echo $libLink ?>'>
              <i class='file-icon icon icon-folder-close-alt'></i>
              <div class='lib-name' title='<?php echo $libName?>'><?php echo $libName?></div>
            </a>
            <?php endforeach; ?>
          </div>
        </td>
        <?php $i ++;?>
        <?php if($i % 3 == 0) echo '</tr>'?>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php
      if($i % 3 != 0)
      {
          while($i % 3 != 0)
          {
              echo "<td class='none'></td>";
              $i++;
          }
          echo "</tr>";
      }
      ?>
    </table>
    <?php elseif($libsName === 'project'): ?>
    <table class='table project table-data'>
      <?php foreach($libs as $project):?>
        <?php if(isset($subLibs['project'][$project->id])):?>
        <?php if($i % 3 == 0) echo '<tr>'?>
        <td>
          <div class='libs-group-heading libs-project-heading'><strong><?php echo html::a(inlink('objectLibs', "type=project&objectID=$project->id&from=doc"), $project->name)?></strong></div>
          <div class='libs-group clearfix'>
            <?php foreach($subLibs['project'][$project->id] as $libID => $libName):?>
            <?php
            if($libID == 'files') $libLink = inlink('showFiles', "type=project&objectID=$project->id");
            else                  $libLink = inlink('browse', "libID=$libID");
            ?>
            <a class='lib' title='<?php echo $libName?>' href='<?php echo $libLink ?>'>
              <i class='file-icon icon icon-folder-close-alt'></i>
              <div class='lib-name' title='<?php echo $libName?>'><?php echo $libName?></div>
            </a>
            <?php endforeach; ?>
          </div>
        </td>
        <?php $i++;?>
        <?php if($i % 3 == 0) echo "</td>";?>
        <?php endif; ?>
      <?php endforeach; ?>
      <?php
      if($i % 3 != 0)
      {
          while($i % 3 != 0)
          {
              echo "<td class='none'></td>";
              $i++;
          }
          echo "</tr>";
      }
      ?>
    </table>
    <?php else: ?>
      <div class='libs-group'>
      <?php foreach($libs as $libID => $libName):?>
        <a class='lib' title='<?php echo $libName?>' href='<?php echo inlink('browse', "libID=$libID") ?>'>
          <i class='file-icon icon icon-folder-close-alt'></i>
          <div class='lib-name' title='<?php echo $libName?>'><?php echo $libName?></div>
        </a>
      <?php endforeach; ?>
      </div>
    <?php endif; ?>
    </td>
    <td class='w-20px lib-more'><?php echo html::a(inlink('allLibs', "type=$libsName"), $lang->more, '', "title='$lang->more' class='more'")?></td>
  </tr>
  <?php endforeach;?>
</table>
</div>
<?php include '../../common/view/footer.html.php';?>
