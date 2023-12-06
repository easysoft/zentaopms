<?php
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<div class='main-header'>
  <div class='heading'>
    <i class='icon-list-ul'></i>
    <strong><?php echo zget($lang->editor->modules, $module, isset($lang->{$module}->common) ? $lang->{$module}->common : $module);?></strong>
  </div>
</div>
<div class='main-content extend-content'>
  <?php echo $tree?>
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

    $('.has-list a').on('click', function()
    {
        $('.has-list a.text-primary').removeClass('text-primary active');
        $(this).addClass('text-primary active');
    }).on('mouseover', function()
    {
        $('.has-list a:not(.active)').removeClass('text-primary');
        $(this).addClass('text-primary');
    }).on('mouseout', function()
    {
        if(!$(this).hasClass('active')) $(this).removeClass('text-primary');
    });

    var $firstBtn = $('a[target="editWin"]');
    if($firstBtn.length)
    {
        $firstBtn = $firstBtn.eq(0);
        $firstBtn.trigger('click');
        $(parent.document).find('#editWin').attr('src', $firstBtn.attr('href'));
    }
});
</script>
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='hidden'></iframe>
<body>
</html>
