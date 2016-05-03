<?php
/**
 * The setIndexPage view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<script>
var result = confirm('<?php echo $this->lang->custom->notice->indexPage[$module];?>');
$.get(createLink('custom', 'ajaxSetHomepage', 'module=<?php echo $module?>&page=' + (result ? 'index' : 'browse')), function(){location.reload(true)});
</script>
<?php include '../../common/view/footer.lite.html.php';?>

