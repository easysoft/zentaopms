<?php
/**
 * The html template file of select source method of convert module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
      <strong><?php echo $lang->convert->selectSource;?></strong>
    </div>
  </div>
  <form method='post' action='<?php echo inlink('setConfig');?>'>
    <table class='table bd-none table-form mgb-20'>
      <?php foreach($lang->convert->sourceList as $name => $versions):?>
      <tr>
        <th class='w-100px'><?php echo $name;?></th>
        <td><?php echo html::radio('source', $versions);?></td>
      </tr>
      <?php endforeach;?>
    </table>
    <div class='text-center'>
      <?php echo html::submitButton();?>
    </div>
  </form>
</div>

<?php include '../../common/view/footer.html.php';?>
