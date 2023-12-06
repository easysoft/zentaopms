<?php
/**
 * The create view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: create.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        https://www.zentao.net
 */
?>
<?php if($docType != '' and strpos($config->doc->officeTypes, $docType) !== false):?>
<?php include '../../common/view/header.lite.html.php';?>
<div id="mainContent" class="main-content">
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->doc->create;?></h2>
    </div>
    <?php if($this->config->edition != 'open'):?>
    <div class='alert alert-warning strong'>
      <?php printf($lang->doc->notSetOffice, zget($lang->doc->typeList, $docType), common::hasPriv('custom', 'libreoffice') ? $this->createLink('custom', 'libreoffice', '', '', true) : '###');?>
    </div>
    <?php else:?>
    <div class='alert alert-warning strong'><?php printf($lang->doc->cannotCreateOffice, zget($lang->doc->typeList, $docType));?></div>
    <?php endif;?>
  </div>
</div>
<script>
$("a[href^='###']").click(function()
{
    alert('<?php echo $lang->doc->noLibreOffice;?>');
});
</script>
</body>
</html>
<?php else:?>
<?php include './createtexttype.html.php';?>
<?php endif;?>
