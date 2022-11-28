<?php
/**
 * The file of vnc to vm  of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     vm
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<div class='clearfix'>
  <?php echo "<iframe width='100%' id='urlIframe' src='{$config->webRoot}js/vnc/vnc_lite.html?host=$host&port=6080&path=websockify/?token=$token&password=pass'></iframe>";?>
</div>
<script>
$(function()
{
    var defaultHeight = window.screen.height - 160;
    $('#urlIframe').height(defaultHeight);
})
</script>
