<?php
/**
 * The index view file of cron module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     cron
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <?php echo $lang->cron->common?>
        <small class'text-muted'> <?php echo $lang->arrow . $lang->cron->create?></small>
      </h2>
    </div>
    <form method='post' id='dataform' target='hiddenwin'>
      <table class='table table-form'>
        <tr>
          <th><?php echo $lang->cron->m;?></th>
          <td><?php echo html::input('m', '', "class='form-control'")?></td>
          <td colspan='2'><?php echo $lang->cron->notice->m;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->cron->h;?></th>
          <td><?php echo html::input('h', '', "class='form-control'")?></td>
          <td><?php echo $lang->cron->notice->h;?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->cron->dom;?></th>
          <td><?php echo html::input('dom', '', "class='form-control'")?></td>
          <td><?php echo $lang->cron->notice->dom;?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->cron->mon;?></th>
          <td><?php echo html::input('mon', '', "class='form-control'")?></td>
          <td><?php echo $lang->cron->notice->mon;?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->cron->dow;?></th>
          <td><?php echo html::input('dow', '', "class='form-control'")?></td>
          <td><?php echo $lang->cron->notice->dow;?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->cron->command;?></th>
          <td colspan='2'><?php echo html::input('command', '', "class='form-control'")?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->cron->remark;?></th>
          <td colspan='2'><?php echo html::input('remark', '', "class='form-control'")?></td><td></td>
        </tr>
        <tr>
          <th><?php echo $lang->cron->type;?></th>
          <td><?php echo html::select('type', $lang->cron->typeList, 'zentao', "class='form-control'")?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center'><?php echo html::submitButton()?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
