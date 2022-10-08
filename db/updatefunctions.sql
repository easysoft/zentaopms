SET global log_bin_trust_function_creators = 1;
SET global sql_mode = '';
USE `__TABLE__`;

DROP FUNCTION IF EXISTS `get_monday`;
CREATE FUNCTION `get_monday`(day date) RETURNS date
  begin if date_format(day, '%w') = 0 then return subdate(day, date_format(day, '%w') - 6)__DELIMITER__
  else  return subdate(day, date_format(day, '%w') -1)__DELIMITER__
  end if__DELIMITER__
END;

DROP FUNCTION IF EXISTS `get_sunday`;
CREATE FUNCTION `get_sunday`(day date) RETURNS date
begin
  if date_format(day, '%w') = 0 then return day__DELIMITER__
  else return subdate(day, date_format(day, '%w') - 7)__DELIMITER__
  end if__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_cminited`;
CREATE FUNCTION qc_cminited($project int, $category varchar(30)) returns int
begin
    declare products int default 0__DELIMITER__
    declare objects  int default 0__DELIMITER__
    select count(*) from zt_projectproduct where project = $project into products__DELIMITER__
    select count(distinct product) from zt_object where project = $project and category = $category and type = 'taged' and product in (select product from zt_projectproduct where project = $project) into objects__DELIMITER__
    IF products = objects THEN
    return 1__DELIMITER__
    ELSEIF products != objects THEN
    return 0__DELIMITER__
    END IF__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_initscale`;
CREATE FUNCTION qc_initscale($project int, $category varchar(30), $estimateType varchar(30)) RETURNS float(10,2)
BEGIN
    declare $estimate int default 0__DELIMITER__
    declare $storyEst varchar(30) default 'storyEst'__DELIMITER__
    declare $requestEst varchar(30) default 'requestEst'__DELIMITER__
    if($estimateType = $storyEst) THEN SELECT sum(storyEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = $category and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__
    end if__DELIMITER__
    if($estimateType = $requestEst) THEN SELECT sum(requestEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = $category and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__
    end if__DELIMITER__
    RETURN @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmplanscale`;
CREATE FUNCTION `qc_pgmplanscale`($project int) RETURNS float(10,2)
BEGIN
   declare programScale float (10,2) default 0__DELIMITER__
   select `scale` from zt_workestimation where project = $project into @programScale__DELIMITER__
   return @programScale__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmsrinitscale`;
CREATE FUNCTION `qc_pgmsrinitscale`($project int) RETURNS float(10,2)
begin
    declare scale int default 0__DELIMITER__
    declare inited int default 0__DELIMITER__
    select qc_cminited($project, 'SRS') into inited__DELIMITER__
    IF inited = 1 THEN
    select qc_initscale($project, 'SRS', 'storyEst') into scale __DELIMITER__
    return scale __DELIMITER__
    ELSE
    return 0__DELIMITER__
    END IF__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmsrrealscale`;
CREATE FUNCTION `qc_pgmsrrealscale`($project int) RETURNS float(10,2)
BEGIN
  declare totalEstimate float(10,2) default 0__DELIMITER__
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_story where id in (select story from zt_projectstory where project=$project) and type='story' and deleted='0' and closedReason not in ('subdivided', 'duplicate', 'willnotdo', 'cancel', 'bydesign') into totalEstimate__DELIMITER__
  return totalEstimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmurinitscale`;
CREATE FUNCTION `qc_pgmurinitscale`($project int) RETURNS float(10,2)
begin
    declare scale int default 0__DELIMITER__
    declare inited int default 0__DELIMITER__
    select qc_cminited($project, 'URS') into inited__DELIMITER__
    IF inited = 1 THEN
    select qc_initscale($project, 'URS', 'requestEst') into scale__DELIMITER__
    return scale__DELIMITER__
    ELSE
    return 0__DELIMITER__
    END IF__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmurrealscale`;
CREATE FUNCTION `qc_pgmurrealscale`($project int) RETURNS float(10,2)
BEGIN
  declare totalEstimate float(10,2) default 0__DELIMITER__
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_story where project=$project and type='requirement' and deleted='0' and closedReason not in ('subdivided', 'duplicate', 'willnotdo', 'cancel', 'bydesign') into totalEstimate__DELIMITER__
  return totalEstimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmallrequirementstage`;
CREATE FUNCTION `qc_pgmallrequirementstage`($project int) RETURNS int(1)
BEGIN
    -- 获取项目产品总数
    select count(*) as products from zt_projectproduct where project = $project into @totalproduct__DELIMITER__
    -- 获取已经设置需求阶段的产品总数
    select count(*) as product from (select product from zt_projectproduct where project in (select id from zt_project where project = $project and type = 'stage' and attribute = 'request' and deleted = '0') GROUP BY product) as product into @product__DELIMITER__
    -- 让项目产品总数和已设置需求阶段产品总数比较,都设置返回1,否则返回0
    if @totalproduct = @product then return 1__DELIMITER__
    end if__DELIMITER__
    RETURN 0__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdesigntplandays`;
CREATE FUNCTION `qc_pgmdesigntplandays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeplanneddays($project,'design') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdesigntrealdays`;
CREATE FUNCTION `qc_pgmdesigntrealdays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeactualdays($project,'design') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdevelplandays`;
CREATE FUNCTION `qc_pgmdevelplandays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeplanneddays($project,'dev') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdevelrealdays`;
CREATE FUNCTION `qc_pgmdevelrealdays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeactualdays($project,'dev') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrequestplandays`;
CREATE FUNCTION `qc_pgmrequestplandays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeplanneddays($project,'request') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrequestrealdays`;
CREATE FUNCTION `qc_pgmrequestrealdays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeactualdays($project,'request') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmspecifiedtypeactualdays`;
CREATE FUNCTION `qc_pgmspecifiedtypeactualdays`($project int,$attribute varchar(50)) RETURNS int(10)
BEGIN
    -- 查询某类型的阶段总数
    select count(*) from zt_project where project = $project and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where project = $project and attribute = $attribute and grade = 2 group by parent) into @totalstory__DELIMITER__
    -- 查询某类型已设置实际工期的阶段总数
    select count(*) from zt_project where project = $project and attribute = $attribute and deleted = '0' and realDuration > 0 and id not in (select parent from zt_project where project = $project and attribute = $attribute and grade = 2 group by parent) into @setstory__DELIMITER__
    -- 查询项目下某类型阶段实际工期总数
    select sum(realDuration) as realDuration from zt_project where project = $project and attribute = $attribute and deleted = '0' and realDuration > 0 and id not in (select parent from zt_project where project = $project and attribute = $attribute and grade = 2 group by parent) into @days__DELIMITER__
    -- 判断项目下某类型的阶段是否都已设置实际工期
    if @totalstory != @setstory then
        set @days = 0__DELIMITER__
    end if__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmspecifiedtypeplanneddays`;
CREATE FUNCTION `qc_pgmspecifiedtypeplanneddays`($project int,$attribute varchar(50)) RETURNS int(10)
BEGIN
    select sum(planDuration) as planDuration from zt_project where project = $project and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where project = $project and attribute = $attribute and grade = 2 group by parent) into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmstageactualduration`;
CREATE FUNCTION `qc_pgmstageactualduration`($product int, $attribute varchar(50)) RETURNS int(10)
BEGIN
    -- 查找某类型的阶段总数
    select count(*) as totalduration from zt_project where id in (select project from zt_projectproduct where product = $product) and type = 'stage' and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and grade = 2 group by parent) into @totalduration__DELIMITER__
    -- 查某类型阶段已设置实际工期的总数
    select count(*) as setduration from zt_project where id in (select project from zt_projectproduct where product = $product) and type = 'stage' and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and grade = 2 group by parent) and realDuration > 0 into @setduration__DELIMITER__
    -- 指定产品下某类型的阶段实际工期总和
    select sum(realDuration) as duration from zt_project where id in (select project from zt_projectproduct where product = $product) and type = 'stage' and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and grade = 2 group by parent) and realDuration > 0 into @duration__DELIMITER__
    -- 需要判断该类型阶段都已设置实际工期,否则不统计
    if @totalduration != @setduration then
        set @duration = 0__DELIMITER__
    end if__DELIMITER__
    return @duration__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmstageplannedduration`;
CREATE FUNCTION `qc_pgmstageplannedduration`($product int, $attribute varchar(50)) RETURNS int(10)
BEGIN
    -- 查找某产品对应阶段
    select sum(planDuration) as duration from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and deleted = '0' and id not in (select parent from zt_project where id in (select project from zt_projectproduct where product = $product) and attribute = $attribute and grade = 2 group by parent) and planDuration > 0 into @duration__DELIMITER__
    RETURN @duration__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmtestplandays`;
CREATE FUNCTION `qc_pgmtestplandays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeplanneddays($project,'qa') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmtestrealdays`;
CREATE FUNCTION `qc_pgmtestrealdays`($project int) RETURNS int(10)
BEGIN
    select qc_pgmspecifiedtypeactualdays($project,'qa') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prddesigntplandays`;
CREATE FUNCTION `qc_prddesigntplandays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageplannedduration($project, $product, 'design') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prddesigntrealdays`;
CREATE FUNCTION `qc_prddesigntrealdays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageactualduration($project, $product, 'design') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prddevelplandays`;
CREATE FUNCTION `qc_prddevelplandays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageplannedduration($project, $product, 'dev') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prddevelrealdays`;
CREATE FUNCTION `qc_prddevelrealdays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageactualduration($project, $product, 'dev') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prdrequestplandays`;
CREATE FUNCTION `qc_prdrequestplandays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageplannedduration($project, $product, 'request') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prdrequestrealdays`;
CREATE FUNCTION `qc_prdrequestrealdays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageactualduration($project, $product, 'request') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prdtestplandays`;
CREATE FUNCTION `qc_prdtestplandays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageplannedduration($project, $product, 'qa') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_prdtestrealdays`;
CREATE FUNCTION `qc_prdtestrealdays`($project int, $product int) RETURNS int(10)
BEGIN
    select qc_pgmstageactualduration($project, $product, 'qa') as days into @days__DELIMITER__
    return @days__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdesgignrealesthours`;
CREATE FUNCTION `qc_pgmdesgignrealesthours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmesthoursbytype($project, 'design')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdesignrealhours`;
CREATE FUNCTION `qc_pgmdesignrealhours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmrealhoursbytype($project, 'design')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdevelrealesthours`;
CREATE FUNCTION `qc_pgmdevelrealesthours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmesthoursbytype($project, 'devel')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmdevelrealhours`;
CREATE FUNCTION `qc_pgmdevelrealhours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmrealhoursbytype($project, 'devel')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrealesthours`;
CREATE FUNCTION `qc_pgmrealesthours`($project int) RETURNS float(10,2)
BEGIN
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_task where project=$project and parent >= 0 and status != 'cancel' and deleted = '0' into @estimate__DELIMITER__
  return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmesthoursbytype`;
CREATE FUNCTION `qc_pgmesthoursbytype`($project int, $type char(30)) RETURNS float(10,2)
BEGIN
  select CAST(sum(estimate) as DECIMAL(10,2)) as estimate from zt_task where project=$project and type = $type and parent >= 0 and status != 'cancel' and deleted = '0' into @estimate__DELIMITER__
  return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrealhours`;
CREATE FUNCTION `qc_pgmrealhours`($project int) RETURNS float(10,2)
BEGIN
  select CAST(sum(consumed) as DECIMAL(10,2)) as consumed from zt_task where project=$project and parent >= 0 and status != 'cancel' and deleted = '0' into @consumed__DELIMITER__
  return @consumed__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrealhoursbytype`;
CREATE FUNCTION `qc_pgmrealhoursbytype`($project int, $type char(30)) RETURNS float(10,2)
BEGIN
  select CAST(sum(consumed) as DECIMAL(10,2)) as consumed from zt_task where project=$project and type = $type and parent >= 0 and status != 'cancel' and deleted = '0' into @consumed__DELIMITER__
  return @consumed__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrequestrealesthours`;
CREATE FUNCTION `qc_pgmrequestrealesthours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmesthoursbytype($project, 'request')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmrequestrealhours`;
CREATE FUNCTION `qc_pgmrequestrealhours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmrealhoursbytype($project, 'request')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmtestrealesthours`;
CREATE FUNCTION `qc_pgmtestrealesthours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmesthoursbytype($project, 'test')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmtestrealhours`;
CREATE FUNCTION `qc_pgmtestrealhours`($project int) RETURNS float(10,2)
BEGIN
return qc_pgmrealhoursbytype($project, 'test')__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getdevelfirstesthours`;
CREATE FUNCTION `qc_getdevelfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(devEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getdesignfirstesthours`;
CREATE FUNCTION `qc_getdesignfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(designEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getstoryfirstesthours`;
CREATE FUNCTION `qc_getstoryfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(requestEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_gettestfirstesthours`;
CREATE FUNCTION `qc_gettestfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(testEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getfirstesthours`;
CREATE FUNCTION `qc_getfirstesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(taskEst) as estimate FROM zt_object WHERE id in(SELECT MIN(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getdevlastesthours`;
CREATE FUNCTION `qc_getdevlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(devEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getrequestlastesthours`;
CREATE FUNCTION `qc_getrequestlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(requestEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_gettestlastesthours`;
CREATE FUNCTION `qc_gettestlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(testEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getdesignlastesthours`;
CREATE FUNCTION `qc_getdesignlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(designEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_getlastesthours`;
CREATE FUNCTION `qc_getlastesthours`($project int) RETURNS float(10,2)
BEGIN
    SELECT sum(taskEst) as estimate FROM zt_object WHERE id in(SELECT MAX(id) FROM zt_object WHERE project = $project and category = 'PP' and type = 'taged' and product in (select product from zt_projectproduct where project = $project) group by `product`) into @estimate__DELIMITER__

    return @estimate__DELIMITER__
END;

DROP FUNCTION IF EXISTS `qc_pgmlastesthours`;
CREATE FUNCTION `qc_pgmlastesthours`($project int) RETURNS float(10,2)
BEGIN
    declare estimate float(10,2) default 0__DELIMITER__
    declare inited int default 0__DELIMITER__
    select qc_cminited($project,'PP') into inited__DELIMITER__
    IF inited = 1 THEN
    select qc_getlastesthours($project) into estimate__DELIMITER__
    return estimate__DELIMITER__
    ELSE
    return 0__DELIMITER__
    END IF__DELIMITER__
END;
