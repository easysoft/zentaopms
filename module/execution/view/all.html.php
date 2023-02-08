<?php include '../../common/view/header.html.php';?>
<?php js::import($jsRoot . 'dtable/min.js'); ?>
<?php css::import($jsRoot . 'dtable/min.css'); ?>
<?php
    $setting = $this->datatable->getSetting('execution');
    $widths  = $this->datatable->setFixedFieldWidth($setting);
?>
<div id="myTable"></div>

<script>
// 定义一个方法用于渲染操作列单元格内的操作按钮
//const renderActions = (result, rowID, col, rowData) => {
//    return [{
//        html: rowData[col.name].map(action => {
//            const actionNames = {start: '开始', close: '关闭', edit: '编辑'};
//            return `<a href="#action=${action}">${actionNames[action] || action}</a>`;
//        }).join(' '),
//    }];
//};

// 定义数据表格初始化选项
const options = {
    height: 400,
    width: '100%',
    striped: true,
    cols: [
        {name: 'id', title: 'ID', width: 60, fixed: 'left', sortType: true},
        {name: 'project', title: '项目名称', width: 600, fixed: 'left'},
        {name: 'manager', title: '负责人', width: 60},
        {name: 'storyPoints', title: '需求规模', width: 80, align: 'center'},
        {name: 'executionCounts', title: '执行数', width: 70, align: 'center'},
        {name: 'investedDays', title: '已投入', width: 70, align: 'center'},
        {name: 'startDate', title: '开始日期', width: 90, align: 'center'},
        {name: 'finishDate', title: '完成日期', width: 90, align: 'center'},
        {name: 'progress', title: '进度', width: 65, align: 'center'},
    ],
    data: [
        {id: 1, project: '禅道开源版', manager: '李明', storyPoints: 1024, executionCounts: 42, investedDays: 32, startDate: '2022-05-03', finishDate: '2022-09-20', progress: 55},
        {id: 2, project: '禅道企业版', manager: 'Zhang Giao', storyPoints: 1024, executionCounts: 42, investedDays: 32, startDate: '2022-05-03', finishDate: '2022-09-20', progress: 55},
        {id: 2, project: '禅道旗舰版', manager: 'HAHAHA', storyPoints: 1024, executionCounts: 42, investedDays: 32, startDate: '2022-05-03', finishDate: '2022-09-20', progress: 55},
    ],
};

// 初始化数据表格
$('#myTable').dtable(options);
</script>
<?php include '../../common/view/footer.html.php';?>
