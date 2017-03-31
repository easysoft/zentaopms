<?php
/**
 * The index view file of cron module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     cron
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-700px'>
  <div id='titlebar'>
    <div class='heading'>
      <strong><?php echo $lang->cron->common?></strong>
      <small class'text-muted'> <?php echo $lang->cron->create?></small>
    </div>
  </div>
  <form class='form-condensed' method='post' id='dataform' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th class='rowhead w-80px'><?php echo $lang->cron->m;?></th>
        <td class='w-p20'><?php echo html::input('m', '', "class='form-control' autocomplete='off'")?></td>
        <td><?php echo $lang->cron->notice->m;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->cron->h;?></th>
        <td><?php echo html::input('h', '', "class='form-control' autocomplete='off'")?></td>
        <td><?php echo $lang->cron->notice->h;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->cron->dom;?></th>
        <td><?php echo html::input('dom', '', "class='form-control' autocomplete='off'")?></td>
        <td><?php echo $lang->cron->notice->dom;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->cron->mon;?></th>
        <td><?php echo html::input('mon', '', "class='form-control' autocomplete='off'")?></td>
        <td><?php echo $lang->cron->notice->mon;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->cron->dow;?></th>
        <td><?php echo html::input('dow', '', "class='form-control' autocomplete='off'")?></td>
        <td><?php echo $lang->cron->notice->dow;?></td>
      </tr>
      <tr>
        <th><?php echo $lang->cron->command;?></th>
        <td colspan='2'><?php echo html::input('command', '', "class='form-control' autocomplete='off'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->cron->remark;?></th>
        <td colspan='2'><?php echo html::input('remark', '', "class='form-control' autocomplete='off'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->cron->type;?></th>
        <td><?php echo html::select('type', $lang->cron->typeList, 'system', "class='form-control'")?></td>
      </tr>
      <tr>
        <th></th>
        <td><?php echo html::submitButton()?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
