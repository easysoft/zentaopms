<?php
/**
 * The select task file of testreport module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Guangming Sun <chunsheng@cnezsoft.com>
 * @package     testreport 
 * @version     $Id: resolve.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->testreport->selectTask;?></h2>
    </div>
    <table class='table table-form'>
      <tr>
        <th><?php echo $lang->testtask->common;?></th>
        <td><?php echo html::select('testtask', $taskPairs, '', "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th></th>
        <td colspan='2'><?php echo html::commonButton($lang->testreport->create, "onclick=locateToCreate(this)", 'btn btn-wide btn-primary') . ' ' . html::backButton();?></td>
      </tr>
    </table>
  </div>
</div>
<script>
function locateToCreate(obj)
{
    var taskID = $(obj).closest('table').find('#testtask').val();
    if(taskID)
    {
        location.href = createLink('testreport', 'create', 'objectID=' + taskID + '&objectType=testtask');
        return false;
    }
}
</script>
<?php include '../../common/view/footer.html.php';?>
