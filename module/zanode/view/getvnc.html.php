<?php
/**
 * The file of vnc to vm  of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     vm
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.lite.html.php';?>
<div class='clearfix'>
  <?php echo "<iframe width='100%' id='urlIframe' src='http://$url/novnc/vnc.html?resize=scale&autoconnect=true&port=6080&path=websockify/?token=$token&password=pass&resize=scale&autoconnect=true'></iframe>";?>
</div>
<script>
$(function()
{
    var defaultHeight = window.screen.height - 160;
    $('#urlIframe').height(defaultHeight);
})
</script>
