<?php
/**
 * The set view file of block module of RanZhi.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.ranzhico.com
 */
?>
<?php
if(isset($lang->block->moduleList[$source]))
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
<form method='post' id='blockForm' target='hiddenwin' class='form form-horizontal' action='<?php echo $this->createLink('block', 'set', "id=$id&type=$type&source=$source")?>'>
  <table class='table table-form'>
    <tbody>
      <?php include 'publicform.html.php';?>
      <?php if($type == 'html'):?>
      <tr>
        <th class='w-100px'><?php echo $lang->block->lblHtml;?></th>
        <td><?php echo html::textarea('html', $block ? $block->params->html : '', "class='form-control' rows='10'")?></td>
      </tr>
      <?php endif;?>
    </tbody>
    <tfoot><tr><td colspan='2' class='text-center'><?php echo html::submitButton()?></td></tr></tfoot>
  </table>
</form>
<script>
$(function()
{
    options = $('#modules').find("option").text();
    if($('#title').val() == '') $('#title').val($('#modules').find("option:selected").text());
})
</script>
