<?php
/**
 * The index view file of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: index.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php echo $this->fetch('block', 'dashboard', 'module=qa');?>
<script>
<?php if($this->config->qa->homepage != 'index'):?>
$(function()
{
    $('#modulemenu .nav li:last').after("<li class='right'><a style='font-size:12px' href='javascript:setHomepage(\"qa\", \"index\")'><i class='icon icon-cog'></i> <?php echo $lang->homepage?></a></li>")
});
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
