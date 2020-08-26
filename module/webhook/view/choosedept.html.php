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
<?php include '../../common/view/ztree.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-800px'>
    <div class='main-header'>
      <h2><?php echo $lang->webhook->chooseDept?></h2>
    </div>
    <ul id='deptList' class="ztree"></ul>
    <div class='actions'>
      <?php echo html::commonButton($lang->save, '', 'btn btn-primary save');?>
      <?php echo html::a($this->createLink('webhook', 'browse'), $lang->goback, '', "class='btn'");?>
    </div>
  </div>
</div>
<?php js::set('deptTree', $deptTree);?>
<script>
$(function()
{
    var ztreeSettings = 
    {
        check: 
        {
            enable: true,
            chkStyle: "checkbox",
            chkboxType: {"Y":"s", "N":"s"}
        },
        data:
        {
            simpleData: {enable: true}
        }
    };
    ztreeObj = $.fn.zTree.init($("#deptList"), ztreeSettings, deptTree);

    $('.actions .save').click(function()
    {
        var nodes = ztreeObj.getCheckedNodes(true);
        var selectedDepts = '';
        for(i in nodes)
        {
            node = nodes[i];
            selectedDepts += ',' + node.id;
        }
        if(selectedDepts) selectedDepts = selectedDepts.substr(1);

        var sign = config.requestType == 'PATH_INFO' ? '?' : '&';
        var link = createLink('webhook', 'bind', "id=<?php echo $webhookID;?>") + sign + "selectedDepts=" + selectedDepts;
        location.href = link;

        return false;
    })
})
</script>
<?php include '../../common/view/footer.html.php';?>
