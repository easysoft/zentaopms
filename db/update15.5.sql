ALTER TABLE `zt_doclib`
    ADD COLUMN `desc` text NULL AFTER `collector`;
ALTER TABLE `zt_doclib`
    ADD COLUMN `baseUrl` varchar(255) NOT NULL DEFAULT '' AFTER `name`;

-- DROP TABLE IF EXISTS `zt_api_lib_release`;
CREATE TABLE `zt_api_lib_release`
(
    `id`        int UNSIGNED NOT NULL AUTO_INCREMENT,
    `doclib`    int UNSIGNED NOT NULL DEFAULT 0,
    `version`   varchar(255) NOT NULL DEFAULT '',
    `snap`      mediumtext   NOT NULL,
    `addedBy`   varchar(30)  NOT NULL DEFAULT 0,
    `addedDate` datetime     NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
);

-- DROP TABLE IF EXISTS `zt_api`;
CREATE TABLE `zt_api`
(
    `id`              int UNSIGNED      NOT NULL AUTO_INCREMENT,
    `product`         varchar(255)      NOT NULL DEFAULT '',
    `lib`             int UNSIGNED      NOT NULL DEFAULT 0,
    `module`          int UNSIGNED      NOT NULL DEFAULT 0,
    `title`           varchar(100)      NOT NULL DEFAULT '',
    `path`            varchar(255)      NOT NULL DEFAULT '',
    `protocol`        varchar(10)       NOT NULL DEFAULT '',
    `method`          varchar(10)       NOT NULL DEFAULT '',
    `requestType`     varchar(100)      NOT NULL DEFAULT '',
    `responseType`    varchar(100)      NOT NULL DEFAULT '',
    `status`          varchar(20)       NOT NULL DEFAULT '',
    `owner`           varchar(30)       NOT NULl DEFAULT 0,
    `desc`            text              NULL,
    `version`         smallint UNSIGNED NOT NULL DEFAULT 0,
    `params`          text              NULL,
    `paramsExample`   text              NUll,
    `responseExample` text              NUll,
    `response`        text              NULL,
    `commonParams`    text              NULL,
    `addedBy`         varchar(30)       NOT NULL DEFAULT 0,
    `addedDate`       datetime          NOT NULL,
    `editedBy`        varchar(30)       NOT NULL DEFAULT 0,
    `editedDate`      datetime          NOT NULL,
    `deleted`         enum ('0', '1')   NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
);

-- DROP TABLE IF EXISTS `zt_apispec`;
CREATE TABLE `zt_apispec`
(
    `id`           int UNSIGNED      NOT NULL AUTO_INCREMENT,
    `doc`          int UNSIGNED      NOT NULL DEFAULT 0,
    `module`       int UNSIGNED      NOT NULL DEFAULT 0,
    `title`        varchar(100)      NOT NULL DEFAULT '',
    `path`         varchar(255)      NOT NULL DEFAULT '',
    `protocol`     varchar(10)       NOT NULL DEFAULT '',
    `method`       varchar(10)       NOT NULL DEFAULT '',
    `requestType`  varchar(100)      NOT NULL DEFAULT '',
    `responseType` varchar(100)      NOT NULL DEFAULT '',
    `status`       tinyint UNSIGNED  NOT NULL DEFAULT 0,
    `owner`        varchar(255)      NOT NULl DEFAULT 0,
    `desc`         text              NULL,
    `version`      smallint UNSIGNED NOT NULL DEFAULT 0,
    `params`       text              NULL,
    `response`     text              NULL,
    `addedBy`      varchar(30)       NOT NULL DEFAULT 0,
    `addedDate`    datetime          NULL,
    PRIMARY KEY (`id`)
);

-- DROP TABLE IF EXISTS `zt_apistruct`;
create table `zt_apistruct`
(
    `id`         int unsigned    NOT NULL AUTO_INCREMENT,
    `lib`        int UNSIGNED    NOT NULL DEFAULT 0,
    `name`       varchar(30)     NOT NULL DEFAULT '',
    `type`       varchar(50)     NOT NULL DEFAULT '',
    `desc`       varchar(255)    NOT NULL DEFAULT '',
    `attribute`  text            NULL,
    `addedBy`    varchar(30)     NOT NULL DEFAULT 0,
    `addedDate`  datetime        NOT NULL,
    `editEdBy`   varchar(30)     NOT NULL DEFAULT 0,
    `editedDate` datetime        NOT NULL,
    `deleted`    enum ('0', '1') NOT NULL DEFAULT '0',
    primary key (`id`)
);

-- DROP TABLE IF EXISTS `zt_api_const`;
create table `zt_api_const`
(
    `id`          int UNSIGNED      NOT NULL AUTO_INCREMENT,
    `doclib`      int UNSIGNED      NOT NULL DEFAULT 0,
    `field`       varchar(50)       NOT NULL DEFAULT '',
    `scope`       varchar(50)       NOT NULL DEFAULT '',
    `type`        varchar(20)       NOT NULL DEFAULT '',
    `default`     varchar(1000)     NOT NULL DEFAULT '',
    `required`    tinyint UNSIGNED  NOT NULL DEFAULT 0,
    `desc`        varchar(255)      NOT NULL DEFAULT 0,
    `version`     smallint UNSIGNED NOT NULL DEFAULT 0,
    `addedBy`     varchar(30)       NOT NULL DEFAULT 0,
    `addedDate`   datetime          NOT NULL DEFAULT '0000-00-00 00:00:00',
    `editEdBy`    varchar(30)       NOT NULL DEFAULT 0,
    `editedDate`  datetime          NOT NULL DEFAULT '0000-00-00 00:00:00',
    `deletedDate` datetime          NULL,
    PRIMARY KEY (`id`)
);

-- DROP TABLE IF EXISTS `zt_api_const_spec`;
create table `zt_api_const_spec`
(
    `id`        int UNSIGNED      NOT NULL AUTO_INCREMENT,
    `doclib`    int UNSIGNED      NOT NULL DEFAULT 0,
    `field`     varchar(50)       NOT NULL DEFAULT '',
    `scope`     varchar(50)       NOT NULL DEFAULT '',
    `type`      varchar(20)       NOT NULL DEFAULT '',
    `default`   varchar(1000)     NOT NULL DEFAULT '',
    `required`  tinyint UNSIGNED  NOT NULL DEFAULT 0,
    `desc`      varchar(255)      NOT NULL DEFAULT 0,
    `version`   smallint UNSIGNED NOT NULL DEFAULT 0,
    `addedBy`   varchar(30)       NOT NULL DEFAULT 0,
    `addedDate` datetime          NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
);
