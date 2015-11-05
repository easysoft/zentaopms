<?php
/**
 * The html template file of setconfig method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: setconfig.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('cloud-upload');?></span>
      <strong><?php echo $lang->convert->setting;?> <?php echo strtoupper($source) . ' ' . html::icon('cog');?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' action='<?php echo inlink('checkconfig');?>'>
    <table align='center' class='table table-form'>
      <?php echo $setting;?>
      <tr>
        <td></td><td><?php echo html::submitButton();?></td>
      </tr>
    </table>
    <?php echo html::hidden('source', $source) . html::hidden('version', $version);?>
  </form>
</div>

<?php include '../../common/view/footer.html.php';?>
