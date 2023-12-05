<?php
/**
 * The set view file of block module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
if($type != 'html')
{
    include 'setmodule.html.php';
    die();
}
if($type == 'html')
{
    $webRoot   = $config->webRoot;
    $jsRoot    = $webRoot . "js/";
    $themeRoot = $webRoot . "theme/";
    include '../../common/view/kindeditor.html.php';
}
?>
<?php include 'publicform.html.php';?>
<?php echo html::hidden('actionLink', $this->createLink('block', 'set', "id=$id&type=$type&source=$source"));?>
<?php if($type == 'html'):?>
<div class="form-group">
  <label for="html" class="col-sm-3"><?php echo $lang->block->lblHtml;?></label>
  <div class='col-sm-7'><?php echo html::textarea('html', $block ? $block->params->html : '', "class='form-control' rows='10'")?></div>
</div>
<?php endif;?>
<script>
$(function()
{
    options = $('#modules').find("option").text();
    if($('#title').val() == '') $('#title').val($('#modules').find("option:selected").text());
})
</script>
