<?php
$lang->bi->binNotExists        = 'DuckDB 二进制文件不存在。';
$lang->bi->tmpPermissionDenied = 'DuckDB tmp 目录没有权限, 需要修改目录 "%s" 的权限。<br />命令为：<br />chmod 755 -R %s。';

$lang->bi->acl = '访问控制';
$lang->bi->aclList['open']    = '公开（所有人均可访问，有维度的视图权限可访问并管理）';
$lang->bi->aclList['private'] = '私有（仅创建者和白名单用户可访问）';

$lang->bi->driver = '数据库类型';
$lang->bi->driverList = array();
$lang->bi->driverList['mysql'] = 'MySQL';

$lang->bi->sqlQuery   = 'SQL语句查询';
$lang->bi->sqlBuilder = 'SQL构建器';

$lang->bi->toggleSqlText    = '手写SQL语句';
$lang->bi->toggleSqlBuilder = 'SQL构建器';

$lang->bi->builderStepList = array();
$lang->bi->builderStepList['table'] = '查询数据表';
$lang->bi->builderStepList['field'] = '选择查询字段';
$lang->bi->builderStepList['func']  = '新增函数字段';
$lang->bi->builderStepList['where'] = '添加查询条件';
$lang->bi->builderStepList['query'] = '添加查询筛选器';
$lang->bi->builderStepList['group'] = '设置分组并聚合';

$lang->bi->stepTableTitle = '选择要查询的数据表';
$lang->bi->stepTableTip   = '请选择要查询的数据表，用于指定您想要从哪张表或哪些表中检索数据。';

$lang->bi->fromTable     = '主表';
$lang->bi->leftTable     = '左连接';
$lang->bi->joinCondition = '连接条件为';
$lang->bi->joinTable     = ' = %s的';
$lang->bi->of            = '的';
$lang->bi->do            = '对';
$lang->bi->set           = '进行';
$lang->bi->funcAs        = '运算，对结果重命名为';
$lang->bi->enable        = '启用';
$lang->bi->allFields     = '全部字段(*)';
$lang->bi->previewSql    = '预览构建的sql语句';
$lang->bi->addFunc       = '新增函数字段';
$lang->bi->emptyFuncs    = '暂未新增函数字段。';
$lang->bi->addWhere      = '添加组';
$lang->bi->emptyWheres   = '暂未添加确定性查询条件。';

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

$lang->bi->emptyError = '不能为空';

$lang->bi->stepFieldTitle = '选择查询表中的字段';
$lang->bi->stepFieldTip   = '选择查询表中的字段用于从已选择的查询表中获取所需的数据。';
$lang->bi->leftTableTip   = '在SQL中，左连接（Left join）是一种表与表之间的关联操作，它返回左表中所有记录以及与右表中匹配的记录。左连接根据指定的条件从两个表中组合数据，其中左表是查询的主表，而右表是要连接的表。具体请看联表查询常用方式：左连接。';

$lang->bi->stepFuncTitle = '新增函数字段';
$lang->bi->stepFuncTip   = '以在查询结果中显示您期望数据。您可以对查询表中的字段设置函数，以在查询结果中新增一列您期望的数据。';

$lang->bi->stepWhereTitle = '添加确定性查询条件';
$lang->bi->stepWhereTip   = '(1)查询条件用于过滤不满足要求的数据，您可以按需添加查询条件，以获取相应的查询结果。<br/>(2)使用=、!=、>、>=、<、<=、和模糊匹配(like)条件符号时，请在符号右侧输入框内输入相应条件值。<br/>(3)使用包含(in)条件符号时，请在符号右侧输入框内输入一个或多个条件值，并用英文逗号隔开，例如：任务类型 包含(in) 开发,测试。';

$lang->bi->stepQueryTitle = '添加动态查询筛选器';
$lang->bi->stepQueryTip   = '动态查询筛选器是通过在 SQL 中插入变量实现动态查询的筛选方式，第三步配置的结果筛选器是对SQL查询结果进行进一步筛选。';

$lang->bi->stepGroupTitle = '设置分组并聚合';
$lang->bi->stepGroupTip   = '首先您需要选择用于分组的字段，系统会根据您选择的字段作为分组的依据，当您选择多个分组字段时系统将根据选择顺序依次进行分组。完成分组后，点击配置聚合为其他非分组字段进行聚合运算，得到不同分组下的汇总值。您可以使用聚合函数（计数、求和、平均值、最大值、最小值）对非分组字段的数据进行聚合。';
