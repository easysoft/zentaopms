<?php
/**
 * The html template file of select source method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: selectsource.html.php 4129 2013-01-18 01:58:14Z wwccss $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <span class='prefix'><?php echo html::icon('cloud-upload');?></span>
      <strong><?php echo $lang->convert->common;?></strong>
      <small class='text-muted'><?php echo $lang->convert->selectSource . ' ' . html::icon('bullseye');?></small>
    </div>
  </div>
  <form method='post' action='<?php echo inlink('setConfig');?>'>
    <table class='table table-form'>
      <thead>
        <tr>
          <th class='w-100px text-right'><?php echo $lang->convert->source;?></th>
          <th class='text-center'><?php echo $lang->convert->version;?></th>
        </tr>
      </thead>
      <?php foreach($lang->convert->sourceList as $name => $versions):?>
      <tr>
        <th><?php echo $name;?></th>
        <td><?php echo html::radio('source', $versions);?></td>
      </tr>
      <?php endforeach;?>
      <tr>
        <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>

<?php include '../../common/view/footer.html.php';?>
