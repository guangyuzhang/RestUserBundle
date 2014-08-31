RestUserBundle
==============
RESTful user bundle was developed based on FOSRestBundle, NelmioApiDocBundle, Doctrine and JMSSerializer on Symfony2 framework.

data base schema:

CREATE TABLE IF NOT EXISTS `tbl_group` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `guid` VARCHAR(36) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `roles` TEXT NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index2` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci

CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `group_id` INT NOT NULL,
  `guid` VARCHAR(36) NULL,
  `username` VARCHAR(255) NOT NULL,
  `username_canonical` VARCHAR(255) NOT NULL,
  `fullname` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `email_canonical` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(45) NOT NULL,
  `department` VARCHAR(255) NOT NULL,
  `salt` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `index2` (`username_canonical` ASC),
  UNIQUE INDEX `index3` USING BTREE (`email_canonical` ASC),
  INDEX `fk_tbluser_tblgroup_idx` (`group_id` ASC),
  CONSTRAINT `fk_tbluser_tblgroup`
    FOREIGN KEY (`group_id`)
    REFERENCES `tbl_group` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci

