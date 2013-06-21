<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yangyang Shi <shiyangyang@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id$
 */
?>
<?php include '../../common/view/m.header.html.php';?>
</div>
<ul data-role='listview'>
  <?php foreach($productStats as $product):?>
  <li>
    <h3><?php echo $product->name?></h3>
    <div style='text-align:right'>
      <span><?php echo html::a($this->createLink('product', 'browse', "productID=$product->id"), $lang->story->browse, '', "data-role='button' data-mini='true' data-inline='true'")?></span>
      <span><?php echo html::a($this->createLink('bug', 'browse', "productID=$product->id"), $lang->bug->browse, '', "data-role='button' data-mini='true' data-inline='true'")?></span>
    </div>
  </li>
  <?php endforeach;?>
</ul>
<?php include '../../common/view/m.footer.html.php';?>
