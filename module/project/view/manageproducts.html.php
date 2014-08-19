<?php
/**
 * The manage product view of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: manageproducts.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div>
  <div id='titlebar'>
    <div class='heading'>
      <?php echo html::icon($lang->icons['product']);?> <?php echo $lang->project->manageProducts;?>
    </div>
  </div>
  <form class='form-condensed' method='post'>
    <div id='productsBox'><?php echo html::checkbox("products", $allProducts, $linkedProducts);?><?php echo html::hidden("post", 'post');?></div>
    <div class="text-center">
      <?php echo html::submitButton();?>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
