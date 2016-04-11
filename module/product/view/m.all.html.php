<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
    <table class='table table-fixed'>
      <tr>
        <td><h3><?php echo $product->name?></h3></td>
        <td width='120'>
          <?php echo html::a($this->createLink('product', 'browse', "productID=$product->id"), $lang->story->common, '', "data-role='button' data-mini='true' data-inline='true'")?>
          <?php echo html::a($this->createLink('bug', 'browse', "productID=$product->id"), $lang->bug->common, '', "data-role='button' data-mini='true' data-inline='true'")?>
        </td>
      </tr>
    </table>
  </li>
  <?php endforeach;?>
</ul>
<?php include '../../common/view/m.footer.html.php';?>
