<?php
/**
 *
 * @category    Magelog
 * @package     Magelog_Mql
 * @copyright   Johannes Künsebeck <jk@hdnet.de>
 */

$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();
$installer->run("
DROP TABLE IF EXISTS {$installer->getTable('mql_model')};
CREATE TABLE {$installer->getTable('mql_model')} (
  `model_id`        SMALLINT(6)     NOT NULL AUTO_INCREMENT,
  `shortname`       VARCHAR(255)    NOT NULL DEFAULT '',
  `prefix`          VARCHAR(255)    NOT NULL DEFAULT '',
  `resource_prefix` VARCHAR(255)    NOT NULL DEFAULT '',
  `class`           VARCHAR(255)    NOT NULL DEFAULT '',
  `is_resource`     TINYINT(1)      NOT NULL DEFAULT '0',
  `is_collection`   TINYINT(1)      NOT NULL DEFAULT '0',
  `is_exception`    TINYINT(1)      NOT NULL DEFAULT '0',
  `is_regular`      TINYINT(1)      NOT NULL DEFAULT '0',
  `codepool`        VARCHAR(255)    NOT NULL DEFAULT '',
  `filename`        VARCHAR(255)    NOT NULL DEFAULT '',


  PRIMARY KEY (`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
