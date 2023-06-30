window.randTipInfo = function(rowDatas)
{
    let tipHtml = `<p>${rowDatas[0].name} ${workHour} /h</p>`;
    rowDatas.forEach(rowData =>
    {
        if(rowData.data == 'null') return;
        tipHtml += `<div><span style="background: ${rowData.color}; height: 10px; width: 10px; display: inline-block; margin-right: 10px;"></span>${rowData.seriesName} ${rowData.data}</div>`;
    });
    return tipHtml;
}
