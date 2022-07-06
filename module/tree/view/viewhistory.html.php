<?php
/**
 * The view history view of tree module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     tree
 * @version     $Id: edit.html.php 4795 2022-07-05 16:10:58Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if(empty($actions)):?>
<div class="table-empty-tip" id="emptyBox">
  <p><span class="text-muted"><?php echo $lang->tree->emptyHistory;?></span></p>
</div>
<?php else:?>
<?php include '../../common/view/action.html.php';?>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
