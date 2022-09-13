$(".query-btn").click(function (e) {
    $(".my-indicator").fadeIn();
    let projects = $("#selectedProjects").val(),
        beginDate = $("#beginDate").val(),
        endDate = $("#endDate").val()
    if (projects === undefined || projects === null || projects.length === 0) {
        projects = []
    }
    const postData = {
        projects,
        beginDate,
        endDate
    }
    $.ajax({
        url: './index.php?m=report&f=consumed',
        method: "post",
        data: {
            data: JSON.stringify(postData)
        },
        success: function (res) {
            res = JSON.parse(res)

            const noDataCell = $('.cell.noDataCell'),dataTableCell = $('.cell.dataTableCell')
            console.log(res)
            if(res===undefined||res===null||res.length===0||res['error']!==undefined){
                noDataCell.show()
                dataTableCell.hide()
                if(res['error'] !== undefined){
                    alert(res['msg'])
                }
                return
            }
            const html = createTable(res,beginDate,endDate)
            $('.dataTable').html(html)
            noDataCell.hide()
            dataTableCell.show()
        },

        // contentType :'application/json'
    }).done(function (r){
        $(".my-indicator").fadeOut()
    })
})
Date.prototype.addDays = function (days) {
    const date = new Date(this.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

function toNormalDateFormatString(MyDate) {
    return MyDate.getFullYear() + '-' + ('0' + (MyDate.getMonth() + 1)).slice(-2) + '-'
        + ('0' + MyDate.getDate()).slice(-2)
}

function calcDiffDays(date1, date2) {
    return Math.abs((date2 - date1) / 1000 / 60 / 60 / 24);
}




