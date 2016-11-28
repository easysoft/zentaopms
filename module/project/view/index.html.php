<?php
/**
 * The html template file of index method of index module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: index.html.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php echo $this->fetch('block', 'dashboard', 'module=project');?>
<script>
<?php if($this->config->project->homepage != 'index'):?>
$(function()
{
    $('#modulemenu .nav li.right:last').after("<li class='right'><a style='font-size:12px' href='javascript:setHomepage(\"project\", \"index\")'><i class='icon icon-cog'></i> <?php echo $lang->homepage?></a></li>")
});
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
