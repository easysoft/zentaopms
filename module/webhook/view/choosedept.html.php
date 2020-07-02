<?php
/**
 * The choose dept view file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     webhook
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-800px'>
    <div class='main-header'>
      <h2><?php echo $lang->webhook->chooseDept?></h2>
    </div>
    <table id='deptList' class='table table-fixed table-bordered active-disabled table-hover'>
      <tbody>
      <?php foreach($topDepts as $deptID => $deptName):?>
      <tr>
        <td><?php echo html::checkbox('deptID', array($deptID => $deptName));?></td>
      </tr>
      <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td>
            <?php echo html::selectAll();?>
            <?php echo html::selectReverse();?>
            <?php echo html::commonButton($lang->save, '', 'btn btn-primary save');?>
            <?php echo html::a($this->createLink('webhook', 'browse'), $lang->goback, '', "class='btn'");?>
          </td>
      </tfoot>
    </table>
  </div>
</div>
<script>
$(function()
{
    $('#deptList tfoot .save').click(function()
    {
        var whiteListDept = '';
        $('#deptList tbody tr td :checkbox[id^=deptID]:checked').each(function()
        {
            whiteListDept += ',' + $(this).val();
        });
        if(whiteListDept) whiteListDept = whiteListDept.substr(1);

        var sign = config.requestType == 'PATH_INFO' ? '?' : '&';
        var link = createLink('webhook', 'bind', "id=<?php echo $webhookID;?>") + sign + "whiteListDept=" + whiteListDept;
        location.href = link;

        return false;
    })
})
</script>
<?php include '../../common/view/footer.html.php';?>
