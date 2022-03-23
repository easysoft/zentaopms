<style>
.kanban-card .title i.icon-checked {display: none;}
.kanban-card .infos .info-id {display: none;}
.course-groupBy {width: 105px;}
.course-groupBy li > a {float: left;}
.course-groupBy .icon-check {left: 5px !important;}
</style>
<script>
$('#mainMenu').remove();
if(laneCount === 1) $("#kanbanActionMenu li:eq(1)").remove();
</script>
