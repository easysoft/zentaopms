<script>
$.extend($.fn.pager.Constructor.LANG,
{
    '<?php echo $app->getClientLang();?>':
    {
        'pageOfText': "<?php echo $lang->pager->pageOfText;?>",
        'prev': "<?php echo $lang->pager->previousPage;?>",
        'next': "<?php echo $lang->pager->nextPage;?>",
        'first': "<?php echo $lang->pager->firstPage;?>",
        'last': "<?php echo $lang->pager->lastPage;?>",
        'goto': "<?php echo $lang->pager->goto;?>",
        'pageOf': "<?php echo $lang->pager->pageOf;?>",
        'totalPage': "<?php echo $lang->pager->totalPage;?>",
        'totalCount': "<?php echo $lang->pager->totalCount;?>",
        'pageSize': "<?php echo $lang->pager->pageSize;?>",
        'itemsRange': "<?php echo $lang->pager->itemsRange;?>",
        'pageOfTotal': "<?php echo $lang->pager->pageOfTotal;?>"
    }
});
$.extend($.fn.colorPicker.Constructor.LANG,
{
    '<?php echo $app->getClientLang();?>':
    {
        'errorTip': "<?php echo $lang->colorPicker->errorTip;?>"
    }
});
</script>
