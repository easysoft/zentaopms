ALTER TABLE `zt_doclib`
    ADD COLUMN `desc` text NULL AFTER `collector`;
ALTER TABLE `zt_doclib`
    ADD COLUMN `baseUrl` varchar(255) NOT NULL DEFAULT '' AFTER `name`;

-- DROP TABLE IF EXISTS `zt_api_lib_release`;
CREATE TABLE `zt_api_lib_release`
(
    `id`        int UNSIGNED NOT NULL AUTO_INCREMENT,
    `lib`       int UNSIGNED NOT NULL DEFAULT 0,
    `desc`      varchar(255) NOT NULL DEFAULT '',
    `version`   varchar(255) NOT NULL DEFAULT '',
    `snap`      mediumtext   NOT NULL,
    `addedBy`   varchar(30)  NOT NULL DEFAULT 0,
    `addedDate` datetime     NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- DROP TABLE IF EXISTS `zt_api`;
CREATE TABLE `zt_api`
(
    `id`              int UNSIGNED NOT NULL AUTO_INCREMENT,
    `product`         varchar(255) NOT NULL DEFAULT '',
    `lib`             int UNSIGNED NOT NULL DEFAULT 0,
    `module`          int UNSIGNED NOT NULL DEFAULT 0,
    `title`           varchar(100) NOT NULL DEFAULT '',
    `path`            varchar(255) NOT NULL DEFAULT '',
    `protocol`        varchar(10)  NOT NULL DEFAULT '',
    `method`          varchar(10)  NOT NULL DEFAULT '',
    `requestType`     varchar(100) NOT NULL DEFAULT '',
    `responseType`    varchar(100) NOT NULL DEFAULT '',
    `status`          varchar(20)  NOT NULL DEFAULT '',
    `owner`           varchar(30)  NOT NULl DEFAULT 0,
    `desc`            text NULL,
    `version`         smallint UNSIGNED NOT NULL DEFAULT 0,
    `params`          text NULL,
    `paramsExample`   text NUll,
    `responseExample` text NUll,
    `response`        text NULL,
    `commonParams`    text NULL,
    `addedBy`         varchar(30)  NOT NULL DEFAULT 0,
    `addedDate`       datetime     NOT NULL,
    `editedBy`        varchar(30)  NOT NULL DEFAULT 0,
    `editedDate`      datetime     NOT NULL,
    `deleted`         enum ('0', '1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- DROP TABLE IF EXISTS `zt_apispec`;
CREATE TABLE `zt_apispec`
(
    `id`              int UNSIGNED NOT NULL AUTO_INCREMENT,
    `doc`             int UNSIGNED NOT NULL DEFAULT 0,
    `module`          int UNSIGNED NOT NULL DEFAULT 0,
    `title`           varchar(100) NOT NULL DEFAULT '',
    `path`            varchar(255) NOT NULL DEFAULT '',
    `protocol`        varchar(10)  NOT NULL DEFAULT '',
    `method`          varchar(10)  NOT NULL DEFAULT '',
    `requestType`     varchar(100) NOT NULL DEFAULT '',
    `responseType`    varchar(100) NOT NULL DEFAULT '',
    `status`          varchar(20)  NOT NULL DEFAULT '',
    `owner`           varchar(255) NOT NULl DEFAULT 0,
    `desc`            text NULL,
    `version`         smallint UNSIGNED NOT NULL DEFAULT 0,
    `params`          text NULL,
    `paramsExample`   text NUll,
    `responseExample` text NUll,
    `response`        text NULL,
    `addedBy`         varchar(30)  NOT NULL DEFAULT 0,
    `addedDate`       datetime NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM;

-- DROP TABLE IF EXISTS `zt_apistruct`;
CREATE TABLE `zt_apistruct`
(
    `id`         int unsigned NOT NULL AUTO_INCREMENT,
    `lib`        int UNSIGNED NOT NULL DEFAULT 0,
    `name`       varchar(30)  NOT NULL DEFAULT '',
    `type`       varchar(50)  NOT NULL DEFAULT '',
    `desc`       varchar(255) NOT NULL DEFAULT '',
    `version`    smallint unsigned NOT NULL DEFAULT 0,
    `attribute`  text NULL,
    `addedBy`    varchar(30)  NOT NULL DEFAULT 0,
    `addedDate`  datetime     NOT NULL,
    `editEdBy`   varchar(30)  NOT NULL DEFAULT 0,
    `editedDate` datetime     NOT NULL,
    `deleted`    enum ('0', '1') NOT NULL DEFAULT '0',
    primary key (`id`)
) ENGINE=MyISAM;

-- DROP TABLE IF EXISTS `zt_apistruct_spec`;
CREATE TABLE `zt_apistruct_spec`
(
    `id`        int UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`      varchar(255) NOT NULL DEFAULT '',
    `type`      varchar(50)  NOT NULL DEFAULT '',
    `desc`      varchar(255) NOT NULL DEFAULT '',
    `attribute` text NULL,
    `version`   smallint unsigned NOT NULL DEFAULT 0,
    `addedBy`   varchar(30)  NOT NULL DEFAULT 0,
    `addedDate` datetime     NOT NULL,
    primary key (`id`)
) ENGINE=MyISAM;
