window.getCellSpan = function(cell)
{
    if(['id', 'name', 'module', 'method', 'createdBy', 'createdDate', 'actions'].includes(cell.col.name) && cell.row.data.rowspan)
    {
        return {rowSpan: cell.row.data.rowspan};
    }
}
