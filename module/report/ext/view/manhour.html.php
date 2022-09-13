<?php include '../../../../../module/common/view/header.html.php'; ?>
<?php include '../../../../../module/common/view/datepicker.html.php'; ?>
<?php include '../../../../../module/common/view/chart.html.php'; ?>
<?php if ($this->config->edition != 'open'): ?>
    <style>#mainContent > .side-col.col-lg {
            width: 235px
        }</style>
    <style>.hide-sidebar #sidebar {
            width: 0 !important
        }</style>
<?php endif; ?>
<?php $chartData = array('labels' => array(), 'data' => array()); ?>
<style>
    #sidebar > .cell {
        width: 100%
    }

    .cell.noDataCell {
        /*display: none;*/
    }

    .cell.dataTableCell {
        display: none;
    }
    .dataTable{
        width: 100%;
    }

    .myTopTH,.myLeftTH,.myMidTH{
        width: 100px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        margin: auto 0;
    }

    .myTD{
        text-align: center;
    }
    .my-indicator{
        display: none;width: 100px; height: 100px; background:#ccc;
        border-radius: 5px;
        position: fixed;
        top: 150px;
        left: 50%;
        margin-left: -50px;
    }
</style>
<div class="load-indicator loading my-indicator" style=""></div>
<div id='mainContent' class='main-row'>
    <div class='side-col col-lg' id='sidebar'>
        <?php include 'manhourleftbar.html.php'; ?>
    </div>
    <div class='main-col'>
        <div class='cell'>
            <div class="row" id='conditions'>
                <div class='w-220px col-md-3 col-sm-6'>
                    <div class='input-group'>
                        <span class='input-group-addon'><?php echo $lang->manhour->program ?></span>
                        <?php echo html::select('selectedProjects[]', $projectList, "", 'class="form-control chosen" multiple'); ?>
                    </div>
                </div>
                <div class='w-220px col-md-3 col-sm-6'>
                    <div class='input-group'>
                        <span class='input-group-addon'><?php echo $lang->manhour->startDate ?></span>
                        <div class='datepicker-wrapper datepicker-date'><?php echo html::input('beginDate', date('Y-m-d', strtotime('-7 days')), "class='form-control form-date'"); ?></div>
                    </div>
                </div>
                <div class='w-220px col-md-3 col-sm-6'>
                    <div class='input-group'>
                        <span class='input-group-addon'><?php echo $lang->manhour->endDate ?></span>
                        <div class='datepicker-wrapper datepicker-date'><?php echo html::input('endDate', date('Y-m-d'), "class='form-control form-date'"); ?></div>
                    </div>
                </div>
                <div class='w-220px col-md-3 col-sm-6'>
                    <a href="javascript:void(0)" class="query-btn btn btn-primary create-project-btn"
                       data-app="project"><?php echo $lang->manhour->query ?></a>
                </div>
            </div>
        </div>
        <div class="cell noDataCell">
            <div class="table-empty-tip">
                <p><span class="text-muted"><?php echo $lang->error->noData; ?></span></p>
            </div>
        </div>
        <div class='cell dataTableCell'>
            <div class='panel'>
                <div class="panel-heading">
                    <div class="panel-title">
                        <?php echo $lang->manhour->tableName; ?>
                        <i class="icon icon-exclamation-sign icon-rotate-180"></i>
                        <span class="hidden" id="desc"><?php echo $lang->manhour->tableName; ?></span>
                    </div>
                    <nav class="panel-actions btn-toolbar"></nav>
                </div>
                <div  class="tableContainer" style="overflow-x: scroll">
                    <table class='dataTable table table-condensed table-striped table-bordered no-margin'>

                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    function createTable(data, begin, end) {
        if(data===undefined) {
            console.log("fail to create table , data is undefined!")
            return
        }
        const date1 = new Date(begin), date2 = new Date(end), n = calcDiffDays(date1, date2)+1;
        let thead = '<thead>'
        thead += '<th> <div class="myTopTH"><?php echo $lang->manhour->projectName ?></div></th>'
        thead += '<th><div class="myTopTH"><?php echo $lang->manhour->stuff ?></div></th>'
        for (let i = 0; i < n; i++) {
            thead += '<th><div class="myTopTH">' + toNormalDateFormatString(date1.addDays(i)) + '</div></th>'
        }
        thead += '</thead>'

        let tbody = '<tbody>'
        for(let i=0; i<data.length;i++){
            const project = data[i]
            console.log(project['users'])
            tbody += '<tr>'
            tbody += '<th rowspan="'+ project['users'].length +'" scope="rowgroup"> <div class="myLeftTH">' + project['projectName'] + '</div></th>'

            tbody += '<th scope="row" class="'+project['projectName']+'-'+project['users'][0]+'"> <div class="myMidTH">' + project['users'][0] + '</div></th>'
            for (let j = 0; j < n; j++) { // 创建 该用户在该项目中的消耗
                const cellDate = toNormalDateFormatString(date1.addDays(j))
                if(project['consumeInfo'][cellDate] !== undefined){
                    let t = ''
                    for(let item of project['consumeInfo'][cellDate]){
                        if(item['realname']===project['users'][0]){
                            t = '<td> <div class="myTD">' + item['consumed'] + '</div></td>'
                            break;
                        }
                    }
                    if(t===''){
                        tbody += '<td> <div class="myTD"> - </div></td>'
                    }else {
                        tbody += t;
                    }
                }else{
                    tbody += '<td> <div class="myTD"> - </div></td>'
                }
            }
            tbody += '</tr>'

            for(let x=1;x<project['users'].length;x++){
                tbody += '<tr>'
                tbody += '<th scope="row" class="'+project['projectName']+'-'+project['users'][x]+'"> <div class="myMidTH">' + project['users'][x] + '</div></th>'
                for (let j = 0; j < n; j++) { // 创建 该用户在该项目中的消耗
                    const cellDate = toNormalDateFormatString(date1.addDays(j))
                    if(project['consumeInfo'][cellDate] !== undefined){
                        let t = ''
                        for(let item of project['consumeInfo'][cellDate]){
                            if(item['realname']===project['users'][x]){
                                t = '<td> <div class="myTD">' + item['consumed'] + '</div></td>'
                                break;
                            }
                        }
                        if(t===''){
                            tbody += '<td> <div class="myTD"> - </div></td>'
                        }else {
                            tbody += t;
                        }
                    }else{
                        tbody += '<td> <div class="myTD"> - </div></td>'
                    }
                }
                tbody += '</tr>'
            }
        }
        tbody += '</tbody>'
        console.log(tbody)
        return thead + tbody
    }
</script>
<?php include '../../../../../module/common/view/footer.html.php'; ?>
