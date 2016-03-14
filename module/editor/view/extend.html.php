<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<div class='panel panel-sm'>
  <div class='panel-heading'><i class='icon-list-ul'></i> <strong><?php echo isset($lang->editor->modules[$module])? $lang->editor->modules[$module] : $module;?></strong></div>
  <div class='panel-body'>
  <?php echo $tree?>
  </div>
</div>
<script>
$(function()
{
    $('.hitarea').click(function()
    {
        var $this  = $(this);
        var parent = $this.parent();
        if(parent.hasClass('expandable'))
        {
            parent.removeClass('expandable').addClass('collapsable');
            $this.removeClass('expandable-hitarea').addClass('collapsable-hitarea');
        }
        else
        {
            parent.addClass('expandable').removeClass('collapsable');
            $this.addClass('expandable-hitarea').removeClass('collapsable-hitarea');
        }
    });
});
</script>
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='hidden'></iframe>
<body>
</html>
