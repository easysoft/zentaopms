<?php
$lang->bi->binNotExists        = 'DuckDB 二进制文件不存在。';
$lang->bi->tmpPermissionDenied = 'DuckDB tmp 目录没有权限, 需要修改目录 "%s" 的权限。<br />命令为：<br />chmod 755 -R %s。';

$lang->bi->acl = '访问控制';

$lang->bi->driver = '数据库类型';
$lang->bi->driverList = array();
$lang->bi->driverList['mysql'] = 'MySQL';

$lang->bi->sqlQuery   = 'SQL语句查询';
$lang->bi->sqlBuilder = 'SQL构建器';
$lang->bi->dictionary = '数据字典';

$lang->bi->toggleSqlText    = '手写SQL语句';
$lang->bi->toggleSqlBuilder = 'SQL构建器';

$lang->bi->builderStepList = array();
$lang->bi->builderStepList['table'] = '查询数据表';
$lang->bi->builderStepList['field'] = '选择查询字段';
$lang->bi->builderStepList['func']  = '新增日期函数字段';
$lang->bi->builderStepList['where'] = '确定性查询条件';
$lang->bi->builderStepList['query'] = '动态查询筛选器';
$lang->bi->builderStepList['group'] = '设置分组并聚合';

$lang->bi->stepTableTitle = '选择要查询的数据表';
$lang->bi->stepTableTip   = '请选择要查询的数据表，用于指定您想要从哪张表或哪些表中检索数据。';
$lang->bi->changeModeTip  = '此次切换将清空当前构建器的配置、并将构建的SQL语句回显到手写SQL语句中；且不可再切换回SQL构建器模式，是否继续？';
$lang->bi->modeDisableTip = '手写SQL语句灵活性较高，暂不支持切回SQL构建器模式';

$lang->bi->fromTable     = '主表';
$lang->bi->leftTable     = '左连接';
$lang->bi->joinCondition = '连接条件为';
$lang->bi->joinTable     = '%s的';
$lang->bi->of            = '的';
$lang->bi->do            = '对';
$lang->bi->set           = '进行';
$lang->bi->funcAs        = '运算，对结果重命名为';
$lang->bi->enable        = '启用';
$lang->bi->previewSql    = '预览构建的sql语句';
$lang->bi->addFunc       = '新增日期函数字段';
$lang->bi->emptyFuncs    = '暂未新增函数字段。';
$lang->bi->addWhere      = '添加组';
$lang->bi->emptyWheres   = '暂未添加确定性查询条件。';
$lang->bi->checkAll      = '全选';
$lang->bi->cancelAll     = '取消全选';
$lang->bi->groupField    = '分组字段';
$lang->bi->aggField      = '聚合字段';
$lang->bi->allFields     = '已选/新增字段';
$lang->bi->addQuery      = '添加动态查询筛选器';
$lang->bi->emptyQuerys   = '暂未添加动态查询筛选器。';
$lang->bi->emptySelect   = '请至少选择一个字段。';
$lang->bi->length        = '长度';

$lang->bi->allFieldsTip  = '已选的查询字段和新增的函数字段。';
$lang->bi->groupFieldTip = '使用分组字段对查询结果进行分组。';
$lang->bi->aggFieldTip   = '对聚合字段配置聚合函数运算，从而得到不同分组下的汇总数据。';

$lang->bi->aggTipA = '对 %s 进行';
$lang->bi->aggTipB = '运算，对结果重命名为 %s';

$lang->bi->aggList = array();
$lang->bi->aggList['count']         = '计数';
$lang->bi->aggList['countdistinct'] = '去重后计数';
$lang->bi->aggList['avg']           = '平均值';
$lang->bi->aggList['sum']           = '求和';
$lang->bi->aggList['max']           = '最大值';
$lang->bi->aggList['min']           = '最小值';

$lang->bi->whereGroupTitle  = '第%s组确定性查询条件';
$lang->bi->addWhereGroup    = '添加组';
$lang->bi->removeWhereGroup = '删除组';

$lang->bi->selectTableTip = '请选择数据表';
$lang->bi->selectFieldTip = '请选择字段';
$lang->bi->selectFuncTip  = '请选择函数';
$lang->bi->selectInputTip = '请输入';

$lang->bi->funcList = array();
$lang->bi->funcList['date']  = '提取日期';
$lang->bi->funcList['month'] = '提取月份';
$lang->bi->funcList['year']  = '提取年份';

$lang->bi->whereOperatorList = array();
$lang->bi->whereOperatorList['and'] = '且';
$lang->bi->whereOperatorList['or']  = '或';

$lang->bi->whereItemOperatorList = array();
$lang->bi->whereItemOperatorList['=']     = '=';
$lang->bi->whereItemOperatorList['!=']    = '!=';
$lang->bi->whereItemOperatorList['>']     = '>';
$lang->bi->whereItemOperatorList['>=']    = '>=';
$lang->bi->whereItemOperatorList['<']     = '<';
$lang->bi->whereItemOperatorList['<=']    = '<=';
$lang->bi->whereItemOperatorList['in']    = '包含';
$lang->bi->whereItemOperatorList['notIn'] = '不包含';
$lang->bi->whereItemOperatorList['like']  = '模糊匹配';

$lang->bi->queryFilterFormHeader = array();
$lang->bi->queryFilterFormHeader['table']   = '选择表';
$lang->bi->queryFilterFormHeader['field']   = '选择字段';
$lang->bi->queryFilterFormHeader['name']    = '筛选器名称';
$lang->bi->queryFilterFormHeader['type']    = '筛选器类型';
$lang->bi->queryFilterFormHeader['default'] = '默认值';

$lang->bi->emptyError     = '不能为空';
$lang->bi->duplicateError = '存在重复';
$lang->bi->noSql          = '通过SQL构建器配置的SQL查询语句，将展示在此处';

$lang->bi->stepFieldTitle = '选择查询表中的字段';
$lang->bi->stepFieldTip   = '选择查询表中的字段用于从已选择的查询表中获取所需的数据。';
$lang->bi->leftTableTip   = '在SQL中，左连接（Left join）是一种表与表之间的关联操作，它返回左表中所有记录以及与右表中匹配的记录。左连接根据指定的条件从两个表中组合数据，其中左表是查询的主表，而右表是要连接的表。具体请看禅道使用手册7.14.13.1联表查询常用方式：左连接。';

$lang->bi->stepFuncTitle = '新增日期函数字段';
$lang->bi->stepFuncTip   = '您可以对查询表中的字段设置函数，以在查询结果中新增一列您期望的数据。';

$lang->bi->stepWhereTitle = '添加确定性查询条件';
$lang->bi->stepWhereTip   = '1.确定性查询条件用于过滤不满足要求的数据，您可以按需添加查询条件，以获取相应的查询结果。2.使用=、!=、>、>=、<、<=、和模糊匹配(like)条件符号时，请在符号右侧输入框内输入相应条件值。3.使用包含(in)条件符号时，请在符号右侧输入框内输入一个或多个条件值，并用英文逗号隔开，例如：任务类型 包含(in) 开发,测试。';

$lang->bi->stepQueryTitle = '添加动态查询筛选器';
$lang->bi->stepQueryTip   = '动态查询筛选器是通过在 SQL 中插入变量实现动态查询的筛选方式，第三步配置的结果筛选器是对SQL查询结果进行进一步筛选。';

$lang->bi->stepGroupTitle = '设置分组并聚合';
$lang->bi->stepGroupTip   = '设置分组用于对查询结果按照指定的列值进行分组，并对分组后的其他数据应用聚合函数来获取汇总信息。';
$lang->bi->emptyGroups    = '启用“设置分组并聚合”后，系统会将您选择的查询字段与新增的函数字段自动展示在此；您可依次设置分组字段，以及其他需要进行聚合运算的字段。';
