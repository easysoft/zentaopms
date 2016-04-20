<?php
/**
 * The ajaxSaveShortcut view file of search module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     search
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php unset($queries['']);?>
<?php if(empty($queries)):?>
<p><?php echo $lang->search->noQuery?></p>
<?php else:?>
<form class='form-condensed' method='post' target='hiddenwin' style='padding: 40px 5% 150px;'>
<div class='input-group'>
  <?php echo html::select('shortcuts[]', $queries, array_keys($shortcuts), "class='form-control chosen' multiple");?>
  <span class='input-group-btn'><?php echo html::submitButton()?></span>
</div>
<div class='alert alert-info' style='margin-top:5px'><?php echo $lang->search->noticeShortcut?></div>
</form>
<?php endif;?>
<?php include '../../common/view/footer.lite.html.php';?>

