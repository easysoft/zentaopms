<?php
/**
 * The mergeProgram view file of upgrade module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     upgrade
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php js::set('weekend', $config->execution->weekend);?>
<?php js::set('type', $type);?>
<?php js::set('errorNoProduct', $lang->upgrade->errorNoProduct);?>
<?php js::set('errorNoExecution', $lang->upgrade->errorNoExecution);?>
<div class='container'>
  <form method='post' target='hiddenwin'>
    <div class='modal-dialog'>
      <div class='modal-header'>
        <strong><?php echo $lang->upgrade->mergeProgram;?></strong>
      </div>
      <div class='modal-body'>
        <?php if($type == 'productline'):?>
        <?php include './mergebyline.html.php';?>
        <?php elseif($type == 'product'):?>
        <?php include './mergebyproduct.html.php';?>
        <?php elseif($type == 'sprint' or $type == 'moreLink'):?>
        <?php include './mergebysprint.html.php';?>
        <?php endif;?>
      </div>
    </div>
  </form>
</div>
<?php include '../../common/view/footer.lite.html.php';?>
