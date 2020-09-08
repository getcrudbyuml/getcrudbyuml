-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema getcru24_getcrudbyuml
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `usuario`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(500) NULL,
  `email` VARCHAR(500) NULL,
  `login` VARCHAR(500) NULL,
  `senha` VARCHAR(500) NULL,
  `nivel` INT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `software`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `software` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(500) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `objeto`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `objeto` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(500) NULL,
  `id_software_objetos` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `email_confirmar`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `email_confirmar` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(500) NULL,
  `codigo` VARCHAR(500) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `auditoria`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `auditoria` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `pagina` VARCHAR(500) NULL,
  `ip_visitante` VARCHAR(500) NULL,
  `info_sessao` VARCHAR(500) NULL,
  `data` DATETIME NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `atributo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `atributo` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(500) NULL,
  `tipo` VARCHAR(500) NULL,
  `indice` VARCHAR(500) NULL,
  `id_objeto_atributos` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `usuario_software`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `usuario_software` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_usuario` INT NOT NULL,
  `id_software` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
