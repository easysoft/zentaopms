<?php include '../../common/view/header.html.php';?>
<style>
#produc{width:90%}
#story {width:90%}
.delbutton{font-size:20px; font-weight:bolder; color:red; width:50px}
.addbutton{font-size:20px; font-weight:bolder; color:darkgreen; width:50px}
</style>
<script language='Javascript'>
var newRowID = 0;
/* 加载产品对应的模块和需求。*/
function loadAll(productID)
{
    loadModuleMenu(productID);
    loadStory(productID);
}

/* 加载模块。*/
function loadModuleMenu(productID)
{
    link = createLink('tree', 'ajaxGetOptionMenu', 'productID=' + productID + '&viewtype=case');
    $('#moduleIdBox').load(link);
}

/* 加载需求列表。*/
function loadStory(productID)
{
    link = createLink('story', 'ajaxGetProductStories', 'productID=' + productID);
    $('#storyIdBox').load(link);
}

/* 删除一行。*/
function deleteRow(rowID)
{
    if($('.stepID').size() == 1) return;
    $('#row' + rowID).remove();
    updateID();
}

/* 之前增加一行。*/
function preInsert(rowID)
{
    $('#row' + rowID).before(createRow());
    updateID();
}

/* 之后增加一行。*/
function postInsert(rowID)
{
    $('#row' + rowID).after(createRow());
    updateID();
}

/* 创建一个row对象。*/
function createRow()
{
    if(newRowID == 0) newRowID = $('.stepID').size();
    newRowID ++;
    var lblDelete = '<?php echo $lang->testcase->deleteStep;?>';
    var lblBefore = '<?php echo $lang->testcase->insertBefore;?>';
    var lblAfter  = '<?php echo $lang->testcase->insertAfter;?>';
    var newRow    = "<tr class='a-center' id='row" + newRowID + "'>";
    newRow += "<th class='stepID'></th>";
    newRow += "<td class='w-p50'><textarea name='steps[]' class='w-p100'></textarea></td>";
    newRow += "<td><textarea name='expects[]' class='w-p100'></textarea></td>";
    newRow += "<td class='a-center w-100px'><nobr>";
    newRow += "<input type='button' tabindex='-1' class='addbutton' value='" + lblBefore + "' onclick='preInsert("  + newRowID + ")' /> ";
    newRow += "<input type='button' tabindex='-1' class='addbutton' value='" + lblAfter  + "' onclick='postInsert(" + newRowID + ")' /> ";
    newRow += "<input type='button' tabindex='-1' class='delbutton' value='" + lblDelete + "' onclick='deleteRow("  + newRowID + ")' /> ";
    newRow += "</nobr></td>";
    return newRow;
}

/* 重新计算并更新stepID。*/
function updateID()
{
    i = 1;
    $('.stepID').each(function(){$(this).html(i ++)});
}
</script>
