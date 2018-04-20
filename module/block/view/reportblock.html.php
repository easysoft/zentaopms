<?php
/**
 * The report block view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2018 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$this->loadModel('story');
?>
<div class="panel-body has-table">
  <ul class='nav nav-secondary nav-product-report'>
    <?php foreach($products as $product):?>
    <li><?php echo html::a('###', $product->name, "data-id='$product->id'");?></li>
    <?php endforeach;?>
  </ul>
  <?php foreach($products as $product):?>
  <div class='div-product-report' id='productReport<?php echo $product->id;?>'>
    <div class='col-md-6'>
      <label class='storyCount text-lg'><?php echo count($product->stories);?></li>
      <?php foreach($config->product->report->stageLabels as $stage):?>
        <dl>
          <dt><?php echo zget($product->stories, $stage, '0');?></dt>
          <dd><?php echo zget($lang->story->stageList, $stage, '0');?></dd>
        </dl>
      <?php endforeach;?>
      </ol>
    </div>
    <div class='col-md-6'>

    </div>
  </div>
  <?php endforeach;?>

</div>
