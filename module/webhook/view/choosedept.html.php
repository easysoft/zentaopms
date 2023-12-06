<?php
/**
 * The choose dept view file of webhook module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     webhook
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/ztree.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='center-block mw-800px'>
    <div class='main-header'>
      <h2><?php echo $lang->webhook->chooseDept?></h2>
    </div>
    <?php if($webhookType == 'feishuuser'):?>
    <div id="notice" class="alert alert-info">
      <div class="content"><i class="icon-exclamation-sign"></i> <?php echo $lang->webhook->friendlyTips;?></div>
    </div>
    <div id='loadPrompt' class="table-empty-tip"><p><span class="text-muted"><?php echo $lang->webhook->loadPrompt;?></span></p></div>
    <?php endif;?>
    <ul id='deptList' class="ztree"></ul>
    <div class='actions'>
      <?php echo html::commonButton($lang->save, '', 'btn btn-primary save');?>
      <?php echo html::a($this->createLink('webhook', 'browse'), $lang->goback, '', "class='btn'");?>
    </div>
  </div>
</div>
<?php js::set('deptTree', $deptTree);?>
<?php js::set('webhookType', $webhookType);?>
<?php js::set('webhookID', $webhookID);?>
<?php js::set('requestError', $lang->webhook->error->requestError);?>
<?php js::set('feishuUrl', $this->createLink('webhook', 'ajaxGetFeishuDeptList', array('webhookID' => $webhookID)));?>
<script>
$(function()
{
    if(webhookType == 'feishuuser')
    {
        var setting =
        {
            view:
            {
                showIcon: false,
                dblClickExpand: false,
            },
            check:
            {
                enable: true,
                chkStyle: "checkbox",
                chkboxType: {"Y" : "ps", "N" : "ps"}
            },
            data:
            {
                simpleData: {enable: true}
            },
            async:
            {
                enable: true,
                url: feishuUrl,
                autoParam:["id", "name=name"],
                type: 'get',
                dataFilter: filter
            },
            callback:
            {
                beforeClick: ztreeBeforeClick,
                beforeAsync: ztreeBeforeAsync,
                onClick: ztreeOnClick,
                onAsyncSuccess: zTreeOnAsyncSuccess,
                onAsyncError: zTreeOnAsyncError
            }
        };

        function filter(treeId, parentNode, childNodes)
        {
            if(!childNodes) return null;
            for(var i = 0, l = childNodes.length; i < l; i++)
            {
                childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
            }
            return childNodes;
        }

        function ztreeBeforeClick(treeId, treeNode)
        {
            if(treeNode.isParent)
            {
                return false;
            }
            else
            {
                return true;
            }
        }

        function ztreeBeforeAsync(treeId, treeNode)
        {
            return true;
        }

        function ztreeOnClick(event, treeId, treeNode)
        {
            var treeID = treeNode.tId;
            if($('#' + treeID).hasClass('loading')) return false;
            $('#' + treeID).addClass('loading');

            if(treeNode == undefined)
            {
                departmentID = "0";
            }
            else
            {
                departmentID = treeNode.id;
            }
            var url = createLink('webhook', 'ajaxGetFeishuDeptList', 'webhookID=' + webhookID);

            $.ajax(
            {
                type: "post",
                url: url,
                data: {departmentID: departmentID},
                dataType: "json",
                async: true,
                success: function(jsonData)
                {
                    if(jsonData != null)
                    {
                        var data = jsonData;
                        if(data != null && data.length != 0)
                        {
                            if(treeNode == undefined)
                            {
                                ztreeObj.addNodes(null, data, true);
                            }
                            else
                            {
                                ztreeObj.addNodes(treeNode, data, true);
                            }
                        }
                        ztreeObj.expandNode(treeNode, true, false, false);

                        if(treeNode.checked && !treeNode.nocheck)
                        {
                            var childs = getChildNodes(treeNode);
                            for(i = 0; i < childs.length; i++)
                            {
                                var node = ztreeObj.getNodeByParam("id", childs[i]);
                                ztreeObj.checkNode(node, true, true);
                            }
                        }
                    }
                },
                error: function()
                {
                    alert(requestError);
                }
            });
        }

        function getChildNodes(treeNode)
        {
            var childNodes = ztreeObj.transformToArray(treeNode);
            var nodes      = new Array();
            for(i = 0; i < childNodes.length; i++)
            {
                nodes[i] = childNodes[i].id;
            }
            return nodes;
        }

        function zTreeOnAsyncSuccess()
        {
            $("#loadPrompt").remove();
        }

        function zTreeOnAsyncError(event, treeId, treeNode, XMLHttpRequest, textStatus, errorThrown)
        {
            var errorMsg = XMLHttpRequest.responseText;
            alert(errorMsg);
        }

        var ztreeObj = $.fn.zTree.init($("#deptList"), setting);
    }
    else
    {
        var ztreeSettings =
        {
            view:
            {
                showIcon: false
            },
            check:
            {
                enable: true,
                chkStyle: "checkbox",
                chkboxType: {"Y" : "ps", "N" : "ps"}
            },
            data:
            {
                simpleData: {enable: true}
            }
        };
        ztreeObj = $.fn.zTree.init($("#deptList"), ztreeSettings, deptTree);
    }

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

        var link = createLink('webhook', 'bind', "id=<?php echo $webhookID;?>");
        link    += link.indexOf('?') >= 0 ? '&' : '?';
        link    += "selectedDepts=" + selectedDepts;
        location.href = link;

        return false;
    })
})
</script>
<?php include '../../common/view/footer.html.php';?>
