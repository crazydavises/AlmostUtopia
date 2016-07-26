-- phpMyAdmin SQL Dump
-- version 4.6.2
-- https://www.phpmyadmin.net/
--
-- Host: mysql.almost-utopia.com
-- Generation Time: Jul 09, 2016 at 04:25 PM
-- Server version: 5.6.25-log
-- PHP Version: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `almost_utopia_game`
--

-- --------------------------------------------------------

--
-- Table structure for table `arms_trader`
--

CREATE TABLE `au_arms_trader` (
  `id` mediumint(14) NOT NULL,
  `day` smallint(6) NOT NULL DEFAULT '0',
  `num_bombs` smallint(8) NOT NULL DEFAULT '40',
  `num_tanks` smallint(8) NOT NULL DEFAULT '20',
  `num_airplanes` smallint(8) NOT NULL DEFAULT '20',
  `num_boats` smallint(8) NOT NULL DEFAULT '20',
  `balance` bigint(35) NOT NULL DEFAULT '100000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `arms_trader`
--

INSERT INTO `au_arms_trader` (`id`, `day`, `num_bombs`, `num_tanks`, `num_airplanes`, `num_boats`, `balance`) VALUES
(1, 0, 40, 18, 20, 20, 13800000),
(2, 1, 39, 16, 18, 19, 111125000),
(3, 2, 39, 16, 18, 19, 23120000),
(4, 3, 39, 16, 18, 19, 100000),
(5, 4, 39, 16, 18, 19, 150000),
(6, 5, 40, 18, 20, 20, 100000),
(7, 6, 40, 18, 20, 20, 100000),
(8, 7, 40, 18, 20, 20, 100000),
(9, 8, 40, 18, 20, 20, 100000),
(10, 9, 40, 18, 20, 20, 100000),
(11, 10, 40, 18, 20, 20, 100000),
(12, 11, 40, 18, 20, 20, 100000),
(13, 12, 40, 18, 20, 20, 100000),
(14, 13, 40, 18, 20, 20, 100000),
(15, 14, 40, 18, 20, 20, 100000),
(16, 15, 40, 18, 20, 20, 100000),
(17, 16, 40, 18, 20, 20, 100000),
(18, 17, 40, 18, 20, 20, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `arms_trader_properties`
--

CREATE TABLE `au_arms_trader_properties` (
  `id` mediumint(12) NOT NULL,
  `description` text NOT NULL,
  `name` text NOT NULL,
  `plural` text NOT NULL,
  `field_name` text NOT NULL,
  `unit` mediumint(10) NOT NULL,
  `value_per_unit` decimal(20,4) NOT NULL,
  `value_pretty_print` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `arms_trader_properties`
--

INSERT INTO `au_arms_trader_properties` (`id`, `description`, `name`, `plural`, `field_name`, `unit`, `value_per_unit`, `value_pretty_print`) VALUES
(1, 'Tanks:', 'Tank', 'Tanks', 'num_tanks', 1, '1000000.0000', '$1,000,000.00 each.'),
(2, 'Boats:', 'Boat', 'Boats', 'num_boats', 1, '1000000.0000', '$1,000,000.00 each.'),
(3, 'Airplanes:', 'Airplane', 'Airplanes', 'num_airplanes', 1, '10000000.0000', '$10,000,000 each.'),
(4, 'Bombs:', 'Bomb', 'Bombs:', 'num_bombs', 1, '10000.0000', '$10,000 each.');

-- --------------------------------------------------------

--
-- Table structure for table `army_properties`
--

CREATE TABLE `au_army_properties` (
  `id` tinyint(3) NOT NULL,
  `description` text NOT NULL,
  `name` text NOT NULL,
  `plural` text NOT NULL,
  `field_name` text NOT NULL,
  `unit` mediumint(10) DEFAULT NULL,
  `loss_unit` mediumint(10) NOT NULL,
  `loss_print` text NOT NULL,
  `cost_per_unit` decimal(20,4) DEFAULT NULL,
  `value_per_unit` decimal(20,4) NOT NULL,
  `resell_per_unit` decimal(20,4) NOT NULL,
  `value_pretty_print` text,
  `trader_table` text NOT NULL,
  `initial_energy_cost` bigint(35) DEFAULT NULL,
  `ongoing_energy_cost` bigint(25) NOT NULL,
  `initial_construction_cost` bigint(35) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `army_properties`
--

INSERT INTO `au_army_properties` (`id`, `description`, `name`, `plural`, `field_name`, `unit`, `loss_unit`, `loss_print`, `cost_per_unit`, `value_per_unit`, `resell_per_unit`, `value_pretty_print`, `trader_table`, `initial_energy_cost`, `ongoing_energy_cost`, `initial_construction_cost`) VALUES
(1, 'Tanks:', 'Tank', 'Tanks', 'num_tanks', 1, 1, 'Lost', '1000000.0000', '1000000.0000', '900000.0000', '$1,000,000 each.  ', 'arms_trader', 5000, 0, 20000),
(2, 'Boats:', 'Boat', 'Boats', 'num_boats', 1, 1, 'Lost', '1000000.0000', '1000000.0000', '900000.0000', '$1,000,000 each.  ', 'arms_trader', 5000, 0, 20000),
(3, 'Airplanes:', 'Airplane', 'Airplanes', 'num_airplanes', 1, 1, 'Lost', '10000000.0000', '10000000.0000', '9000000.0000', '$10,000,000 each.  ', 'arms_trader', 10000, 0, 50000),
(4, 'Soldiers:', 'Soldiers', 'Soldiers', 'num_soldiers', 1000, 2, 'Killed', '1000.0000', '1000.0000', '1000.0000', '$1,000 each per month.  ', '0', 0, 0, 0),
(5, 'Bombs:', 'Bomb', 'Bombs', 'num_bombs', 1, 1, 'Used', '10000.0000', '10000.0000', '9000.0000', '$10,000 each.  ', 'arms_trader', 0, 0, 0),
(6, 'Bases and Prisons:', 'Base or Prison', 'Bases or Prisons', 'num_bases', 1, 1, 'Destroyed', '11000000.0000', '10000000.0000', '10000000.0000', '$10,000,000 each.  ', 'real_estate_developer', 100000, 5000, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `available_jobs`
--

CREATE TABLE `au_available_jobs` (
  `id` mediumint(12) NOT NULL,
  `Name` text NOT NULL,
  `table_name` text NOT NULL,
  `link` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `available_jobs`
--

INSERT INTO `au_available_jobs` (`id`, `Name`, `table_name`, `link`) VALUES
(1, 'Arms Trader', 'arms_trader', '../arms-trader/'),
(2, 'Real Estate Developer', 'real_estate_developer', '../real-estate-developer/');

-- --------------------------------------------------------

--
-- Table structure for table `available_weather`
--

CREATE TABLE `au_available_weather` (
  `id` smallint(4) NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `available_weather`
--

INSERT INTO `au_available_weather` (`id`, `description`) VALUES
(1, 'cloudy\r\n'),
(2, 'windy'),
(3, 'sunny'),
(4, 'rainy');

-- --------------------------------------------------------

--
-- Table structure for table `bank_properties`
--

CREATE TABLE `au_bank_properties` (
  `Name` text NOT NULL,
  `pay_field_name` text NOT NULL,
  `received_field_name` text NOT NULL,
  `readonly` tinyint(1) NOT NULL,
  `id` mediumint(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `bank_properties`
--

INSERT INTO `au_bank_properties` (`Name`, `pay_field_name`, `received_field_name`, `readonly`, `id`) VALUES
('Industry', 'industry_paid', 'industry_received', 1, 1),
('Army', 'army_paid', 'army_received', 1, 2),
('Taxes/Government Programs', 'govt_programs_paid', 'taxes_received', 1, 3),
('Filios', 'filios_paid', 'filios_received', 0, 7),
('Xtensica', 'xtensica_paid', 'xtensica_received', 0, 8),
('Intelibus', 'intelibus_paid', 'intelibus_received', 0, 9),
('Arms Trader', 'arms_paid', 'arms_received', 0, 10),
('Real Estate Developer', 'developer_paid', 'developer_received', 0, 11),
('Central Bank', 'central_bank_paid', 'central_bank_received', 0, 12);

-- --------------------------------------------------------

--
-- Table structure for table `central_bank`
--

CREATE TABLE `au_central_bank` (
  `id` mediumint(14) NOT NULL,
  `day` smallint(6) NOT NULL DEFAULT '0',
  `filios_balance` bigint(20) NOT NULL DEFAULT '80000000',
  `xtensica_balance` bigint(20) NOT NULL DEFAULT '40000000',
  `intelibus_balance` bigint(20) NOT NULL DEFAULT '20000000',
  `filios_loan` mediumint(14) NOT NULL DEFAULT '0',
  `xtensica_loan` bigint(20) NOT NULL DEFAULT '500000000',
  `intelibus_loan` mediumint(14) NOT NULL DEFAULT '1000000',
  `arms_balance` bigint(20) NOT NULL DEFAULT '100000',
  `arms_loan` bigint(20) NOT NULL DEFAULT '0',
  `developer_balance` bigint(20) NOT NULL DEFAULT '100000',
  `developer_loan` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `central_bank`
--

INSERT INTO `au_central_bank` (`id`, `day`, `filios_balance`, `xtensica_balance`, `intelibus_balance`, `filios_loan`, `xtensica_loan`, `intelibus_loan`, `arms_balance`, `arms_loan`, `developer_balance`, `developer_loan`) VALUES
(1, 0, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(2, 1, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(3, 2, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(4, 3, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(5, 4, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(6, 5, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(7, 6, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(8, 7, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(9, 8, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(10, 9, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(11, 10, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(12, 11, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(13, 12, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(14, 13, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(15, 14, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(16, 15, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(17, 16, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0),
(18, 17, 80000000, 40000000, 20000000, 0, 4000000, 200000, 100000, 0, 100000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `consumption_index`
--

CREATE TABLE `au_consumption_index` (
  `construction_materials_production_facility` mediumint(10) NOT NULL DEFAULT '10000',
  `construction_materials_energy_facility` mediumint(10) NOT NULL DEFAULT '10000',
  `food_per_person` mediumint(10) NOT NULL DEFAULT '90',
  `water_per_person` mediumint(10) NOT NULL DEFAULT '90',
  `energy_per_100` mediumint(10) NOT NULL DEFAULT '30',
  `water_per_person_filios` mediumint(10) NOT NULL DEFAULT '300',
  `id` mediumint(14) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `consumption_index`
--

INSERT INTO `au_consumption_index` (`construction_materials_production_facility`, `construction_materials_energy_facility`, `food_per_person`, `water_per_person`, `energy_per_100`, `water_per_person_filios`, `id`) VALUES
(10000, 10000, 90, 90, 30, 300, 1);

-- --------------------------------------------------------

--
-- Table structure for table `consumption_properties`
--

CREATE TABLE `au_consumption_properties` (
  `id` smallint(4) NOT NULL,
  `Name` text NOT NULL,
  `field_name` text NOT NULL,
  `production_field` text NOT NULL,
  `units_pretty_print` text NOT NULL,
  `formula` varchar(60) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `consumption_properties`
--

INSERT INTO `au_consumption_properties` (`id`, `Name`, `field_name`, `production_field`, `units_pretty_print`, `formula`) VALUES
(1, 'Food', 'food_consumed', 'food_produced', 'in pounds', NULL),
(2, 'Oil', 'oil_consumed', 'oil_produced', 'in barrels of oil', NULL),
(6, 'Construction materials', 'const_mat_consumed', 'construction_produced', 'in pounds', NULL),
(5, 'Water', 'water_consumed', 'water_produced', 'in gallons', NULL),
(4, 'Alternative Energy', 'alt_energy_consumed', 'alt_energy_produced', 'in barrels of oil', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `diplomat_properties`
--

CREATE TABLE `au_diplomat_properties` (
  `id` int(12) NOT NULL,
  `name` text NOT NULL,
  `field_name` text NOT NULL,
  `units_pretty_print` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `diplomat_properties`
--

INSERT INTO `au_diplomat_properties` (`id`, `name`, `field_name`, `units_pretty_print`) VALUES
(1, 'Food', 'food_consumed', 'pounds'),
(2, 'Water', 'water_consumed', 'gallons'),
(3, 'Energy', 'energy_consumed', 'barrels of oil'),
(4, 'Construction Materials', 'const_mat_consumed', 'pounds');

-- --------------------------------------------------------

--
-- Table structure for table `entities`
--

CREATE TABLE `au_entities` (
  `id` mediumint(4) NOT NULL,
  `Name` text NOT NULL,
  `bank_table` text NOT NULL,
  `external_bank_field` text NOT NULL,
  `internal_paid` text NOT NULL,
  `internal_received` text NOT NULL,
  `aggregate` tinyint(1) NOT NULL,
  `link` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `entities`
--

INSERT INTO `au_entities` (`id`, `Name`, `bank_table`, `external_bank_field`, `internal_paid`, `internal_received`, `aggregate`, `link`) VALUES
(1, 'Filios', 'filios_bank', 'filios', 'NULL', 'NULL', 0, './filios/'),
(2, 'Xtensica', 'xtensica_bank', 'xtensica', 'NULL', 'NULL', 0, './xtensica/'),
(3, 'Intelibus', 'intelibus_bank', 'intelibus', 'NULL', 'NULL', 0, './intelibus/'),
(4, 'Arms Trader', 'arms_trader', 'arms', 'money_paid', 'money_received', 1, './arms-trader/'),
(5, 'Real Estate Developer', 'real_estate_developer', 'developer', 'money_paid', 'money_received', 1, './real-estate-developer/'),
(6, 'Central Bank', 'central_bank', 'central_bank', 'NULL', 'NULL', 0, './central-bank/');

-- --------------------------------------------------------

--
-- Table structure for table `filios_army_log`
--

CREATE TABLE `au_army_log` (
  `id` mediumint(14) NOT NULL,
  `entityID` mediumint(14) NOT NULL,
  `day` smallint(6) NOT NULL DEFAULT '0',
  `num_tanks` mediumint(7) DEFAULT '4',
  `num_boats` mediumint(7) DEFAULT '5',
  `num_airplanes` mediumint(7) DEFAULT '5',
  `num_soldiers` mediumint(12) DEFAULT '10000',
  `num_bombs` mediumint(12) DEFAULT '4',
  `num_bases` mediumint(7) DEFAULT '4',
  `balance` bigint(35) NOT NULL DEFAULT '100000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_army_log`
--

INSERT INTO `au_army_log` (`id`, `entityID`, `day`, `num_tanks`, `num_boats`, `num_airplanes`, `num_soldiers`, `num_bombs`, `num_bases`, `balance`) VALUES
(1, 1, 0, 5, 8, 10, 10000, 2, 3, 0),
(2, 1, 1, 7, 9, 12, 11000, 1, 4, 33990000),
(4, 1, 2, 7, 9, 12, 11000, 1, 4, 33990000),
(5, 1, 3, 7, 9, 12, 11000, 1, 4, 33990000),
(6, 1, 4, 7, 9, 12, 11000, 1, 4, 33990000),
(7, 1, 5, 5, 8, 10, 10000, 2, 3, 0),
(8, 1, 6, 5, 8, 10, 10000, 2, 3, 0),
(9, 1, 7, 5, 8, 10, 10000, 2, 3, 0),
(10, 1, 8, 5, 8, 10, 10000, 2, 3, 0),
(11, 1, 9, 5, 8, 10, 10000, 2, 3, 0),
(12, 1, 10, 5, 8, 10, 10000, 2, 3, 0),
(13, 1, 11, 5, 8, 10, 10000, 2, 3, 0),
(14, 1, 12, 5, 8, 10, 10000, 2, 3, 0),
(15, 1, 13, 5, 8, 10, 10000, 2, 3, 0),
(16, 1, 14, 5, 8, 10, 10000, 2, 3, 0),
(17, 1, 15, 5, 8, 10, 10000, 2, 3, 0),
(18, 1, 16, 5, 8, 10, 10000, 2, 3, 0),
(19, 1, 17, 5, 8, 10, 10000, 2, 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `filios_bank`
--

CREATE TABLE `au_bank` (
  `entityID` mediumint(14) NOT NULL,
  `industry_received` bigint(20) NOT NULL DEFAULT '0',
  `industry_paid` bigint(20) NOT NULL DEFAULT '0',
  `army_received` bigint(20) NOT NULL DEFAULT '0',
  `army_paid` bigint(20) NOT NULL DEFAULT '10000000',
  `taxes_received` bigint(20) NOT NULL DEFAULT '125000000',
  `govt_programs_paid` bigint(20) NOT NULL DEFAULT '77000000',
  `infrastructure_received` bigint(20) NOT NULL DEFAULT '0',
  `infrastructure_paid` bigint(20) NOT NULL DEFAULT '0',
  `received_from_entityID` bigint(20) NOT NULL DEFAULT '0',
  `amt_received` bigint(20) NOT NULL DEFAULT '0',
  `central_bank_received` bigint(20) NOT NULL DEFAULT '0',
  `central_bank_paid` bigint(20) NOT NULL DEFAULT '0',
  `arms_paid` bigint(20) NOT NULL DEFAULT '0',
  `arms_received` bigint(20) NOT NULL DEFAULT '0',
  `developer_paid` bigint(20) NOT NULL DEFAULT '0',
  `developer_received` bigint(20) NOT NULL DEFAULT '0',
  `initial_balance` bigint(24) NOT NULL,
  `account_balance` bigint(24) NOT NULL DEFAULT '0',
  `day` smallint(6) NOT NULL DEFAULT '0',
  `id` mediumint(14) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_bank`
--

INSERT INTO `au_bank` (`entityID`, `industry_received`, `industry_paid`, `army_received`, `army_paid`, `taxes_received`, `govt_programs_paid`, `infrastructure_received`, `infrastructure_paid`, `intelibus_received`, `intelibus_paid`, `xtensica_received`, `xtensica_paid`, `central_bank_received`, `central_bank_paid`, `arms_paid`, `arms_received`, `developer_paid`, `developer_received`, `initial_balance`, `account_balance`, `day`, `id`) VALUES
(1, 0, 0, 0, 144200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 80000000, 0, 0, 1),
(1, 0, 0, 9000, 44001000, 84965000, 28038450, 0, 0, 0, 50, 0, 20, 0, 0, 20, 0, 50, 0, 0, 12934410, 1, 58),
(1, 0, 0, 0, 11000000, 135910000, 27182000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 12934410, 0, 2, 59),
(1, 0, 0, 0, 11000000, 135910000, 27182000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 3, 60),
(1, 0, 0, 0, 11000000, 135910000, 27182000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 4, 63),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 64),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 6, 65),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 45426800, 7, 66),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 45426800, 90853600, 8, 67),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 90853600, 0, 9, 68),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 45426800, 10, 69),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 45426800, 102508600, 11, 70),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 102508600, 171245400, 12, 71),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 171245400, 239982200, 13, 72),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 239982200, 307000200, 14, 73),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 307000200, 307000200, 15, 74),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 307000200, 0, 16, 75),
(1, 0, 0, 0, 10000000, 86855000, 20845200, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 17, 76);

-- --------------------------------------------------------

--
-- Table structure for table `filios_consumption`
--

CREATE TABLE `au_consumption` (
  `id` mediumint(14) NOT NULL,
  `entityID` mediumint( 14) NOT NULL,
  `day` smallint(6) NOT NULL DEFAULT '0',
  `food_consumed` bigint(15) NOT NULL DEFAULT '9000000',
  `oil_consumed` mediumint(10) NOT NULL DEFAULT '30000',
  `alt_energy_consumed` mediumint(10) NOT NULL,
  `total_energy_consumed` bigint(20) NOT NULL,
  `const_mat_consumed` mediumint(14) NOT NULL DEFAULT '0',
  `water_consumed` bigint(25) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_consumption`
--

INSERT INTO `au_consumption` (`id`, `entityID`,  `day`, `food_consumed`, `oil_consumed`, `alt_energy_consumed`, `total_energy_consumed`, `const_mat_consumed`, `water_consumed`) VALUES
(1, 1, 0, 9000000, 8388607, 0, 8388607, 0, 0),
(4, 1, 2, 1601328, 8388607, 0, 13628607, 0, 20016600),
(3, 1, 1, 27000000, 8388607, 0, 51888607, 0, 15000000),
(5, 1, 3, 1602656, 8388607, 0, 37073607, 0, 20033200),
(6, 1, 4, 3207968, 8388607, 0, 13073607, 0, 40099600),
(7, 1, 5, 1203984, 8388607, 300000, 14976000, 0, 15049800),
(8, 1, 6, 803320, 7861000, 300000, 9661000, 0, 10041500),
(9, 1, 7, 803984, 7861000, 300000, 9661000, 0, 10049800),
(10, 1, 8, 804648, 8388607, 300000, 14661000, 0, 10058100),
(11, 1, 9, 805312, 7861000, 300000, 9661000, 0, 10066400),
(12, 1, 10, 805976, 8388607, 300000, 12703607, 0, 10074700),
(13, 1, 11, 806640, 8388607, 750000, 12161000, 0, 10083000),
(14, 1, 12, 2018280, 8388607, 750000, 15953607, 0, 25228500),
(15, 1, 13, 807984, 4881000, 750000, 7131000, 0, 10099800),
(16, 1, 14, 808656, 3386000, 750000, 5636000, 0, 10108200),
(17, 1, 15, 809328, 637000, 0, 2137000, 0, 10116600),
(18, 1, 16, 405000, 315000, 0, 1815000, 0, 5062500),
(19, 1, 17, 405336, 315000, 0, 315000, 0, 5066700);

-- --------------------------------------------------------

--
-- Table structure for table `filios_diplomat`
--

CREATE TABLE `au_diplomat` (
  `id` mediumint(12) NOT NULL,
  `entityID` mediumint(14) NOT NULL,
  `day` smallint(6) NOT NULL,
  `action` enum('Buy','Sell') NOT NULL,
  `item_name` text NOT NULL,
  `quantity` bigint(25) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `other_party` text NOT NULL,
  `balance` decimal(25,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_diplomat`
--

INSERT INTO `au_diplomat` (`id`, `day`, `action`, `item_name`, `quantity`, `price`, `other_party`, `balance`) VALUES
(8, 1, 1, 'Buy', 'Energy', 5000, '5000000.00', 'Xtensica', '-4997000.0000'),
(9, 1, 10, 'Sell', 'Food', 10000000, '0.00', 'Intelibus', '-4997000.0000'),
(7, 1, 1, 'Sell', 'Water', 4500, '3000.00', 'Intelibus', '3000.0000'),
(10, 1, 1, 'Buy', 'Construction Materials', 300, '0.00', 'Intelibus', '-4997000.0000');

-- --------------------------------------------------------

--
-- Table structure for table `govprograms_log`
--

CREATE TABLE `au_govprograms_log` (
  `entityID` mediumint(14) NOT NULL,
  `health_pct` smallint(7) NOT NULL DEFAULT '20',
  `health_total` bigint(15) NOT NULL DEFAULT '25000000',
  `educ_pct` smallint(7) NOT NULL DEFAULT '12',
  `educ_total` bigint(15) NOT NULL DEFAULT '15000000',
  `infrastructure_pct` smallint(7) NOT NULL DEFAULT '0',
  `infrastructure_total` mediumint(10) NOT NULL DEFAULT '0',
  `army_pct` smallint(7) NOT NULL DEFAULT '8',
  `army_total` bigint(20) NOT NULL DEFAULT '10000000',
  `id` mediumint(14) NOT NULL,
  `government_total` bigint(14) NOT NULL DEFAULT '97000000',
  `day` smallint(6) NOT NULL DEFAULT '0',
  `resident_pct` smallint(7) NOT NULL DEFAULT '30',
  `resident_total` bigint(20) NOT NULL DEFAULT '37000000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_govprograms_log`
--

INSERT INTO `au_govprograms_log` (`entityID`, `health_pct`, `health_total`, `educ_pct`, `educ_total`, `infrastructure_pct`, `infrastructure_total`, `army_pct`, `army_total`, `id`, `government_total`, `day`, `resident_pct`, `resident_total`) VALUES
(1, 20, 16993000, 13, 11045450, 0, 0, 8, 6797200, 1, 34835650, 0, 30, 37000000),
(1, 10, 16993000, 10, 11045450, 10, 0, 15, 6797200, 2, 34835650, 1, 30, 37000000),
(1, 10, 13591000, 10, 13591000, 10, 8388607, 15, 20386500, 3, 61159500, 2, 30, 37000000),
(1, 10, 13591000, 10, 13591000, 10, 8388607, 15, 20386500, 4, 61159500, 3, 30, 37000000),
(1, 10, 13591000, 10, 13591000, 10, 8388607, 15, 20386500, 5, 61159500, 4, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 6, 43427500, 5, 30, 37000000),
(1, 0, 25000000, 12, 15000000, 0, 0, 8, 10000000, 7, 97000000, 0, 30, 37000000),
(1, 20, 25000000, 0, 15000000, 0, 0, 8, 10000000, 8, 97000000, 0, 30, 37000000),
(1, 20, 25000000, 12, 15000000, 0, 0, 8, 10000000, 9, 97000000, 0, 30, 37000000),
(1, 20, 25000000, 12, 15000000, 0, 0, 0, 10000000, 10, 97000000, 0, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 11, 43427500, 6, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 12, 43427500, 7, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 13, 43427500, 8, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 14, 43427500, 9, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 15, 43427500, 10, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 16, 43427500, 11, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 17, 43427500, 12, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 18, 43427500, 13, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 19, 43427500, 14, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 20, 43427500, 15, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 21, 43427500, 16, 30, 37000000),
(1, 14, 12159700, 10, 8685500, 8, 6948400, 18, 15633900, 22, 43427500, 17, 30, 37000000);

-- --------------------------------------------------------

--
-- Table structure for table `filios_home`
--

CREATE TABLE `au_home` (
  `id` mediumint(14) NOT NULL,
  `entityID` mediumint(14) NOT NULL,
  `weather_id` smallint(4) NOT NULL COMMENT 'index into available_weather',
  `liveable_wage` decimal(15,2) NOT NULL,
  `day` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_home`
--

INSERT INTO `filios_home` (`id`, `entityID`, `weather_id`, `liveable_wage`, `day`) VALUES
(1, 1,  4, '2014.00', 0),
(5, 1, 1, '0.00', 2),
(4, 1, 3, '0.00', 1),
(6, 1, 4, '0.00', 3),
(7, 1, 4, '0.00', 4),
(8, 1, 3, '0.00', 5),
(9, 1, 3, '0.00', 7),
(10, 1, 2, '0.00', 8),
(11, 1, 2, '0.00', 9),
(12, 1, 4, '0.00', 10),
(13, 1, 3, '0.00', 11),
(14, 1, 1, '0.00', 12),
(15, 1, 3, '0.00', 13),
(16, 1, 4, '0.00', 15);

-- --------------------------------------------------------

--
-- Table structure for table `au_industry_log`
--

CREATE TABLE `au_industry_log` (
  `id` mediumint(14) NOT NULL,
  `entityID` mediumint(14),
  `day` smallint(6) NOT NULL DEFAULT '0',
  `num_foodproducers` mediumint(10) NOT NULL DEFAULT '500',
  `num_waterproducers` mediumint(7) NOT NULL DEFAULT '0',
  `num_oilwells` mediumint(4) NOT NULL DEFAULT '5',
  `num_alternativeenergy` mediumint(4) NOT NULL DEFAULT '0',
  `balance` bigint(35) NOT NULL DEFAULT '0',
  `num_constructionmaterialproducers` mediumint(7) NOT NULL DEFAULT '500',
  `oil_produced` bigint(20) NOT NULL DEFAULT '20000000',
  `alt_energy_produced` bigint(20) NOT NULL,
  `water_produced` bigint(20) NOT NULL DEFAULT '0',
  `food_produced` bigint(20) NOT NULL DEFAULT '300000',
  `construction_produced` decimal(25,4) NOT NULL,
  `total_production` decimal(35,4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_industry_log`
--

INSERT INTO `au_industry_log` (`id`, `entityID`, `day`, `num_foodproducers`, `num_waterproducers`, `num_oilwells`, `num_alternativeenergy`, `balance`, `num_constructionmaterialproducers`, `oil_produced`, `alt_energy_produced`, `water_produced`, `food_produced`, `construction_produced`, `total_production`) VALUES
(1, 1, 1, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(2, 1, 0, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(3, 1, 2, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(4, 1, 3, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(5, 1, 4, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(6, 1, 5, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(7, 1, 6, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(8, 1, 7, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(9, 1, 8, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(10, 1, 9, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(11, 1, 10, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(12, 1, 11, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(13, 1, 12, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(14, 1, 13, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(15, 1, 14, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(16, 1, 15, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(17, 1, 16, 200, 0, 3, 0, 0, 300, 450000, 0, 0, 15000000, '900000.0000', '94500000.0000'),
(18, 1, 17, 200, 0, 3, 0, 0, 300, 20000000, 0, 0, 300000, '0.0000', '0.0000');

-- --------------------------------------------------------

--
-- Table structure for table `filios_population_log`
--

CREATE TABLE `au_population_log` (
  `entityID` mediumint(14) NOT NULL,
  `day` mediumint(15) NOT NULL,
  `population` mediumint(14) NOT NULL DEFAULT '4000000',
  `id` mediumint(14) NOT NULL,
  `unemployment_rate` decimal(7,4) NOT NULL DEFAULT '6.2000',
  `pop_growth_rate` mediumint(7) NOT NULL DEFAULT '1',
  `salaries` mediumint(10) NOT NULL DEFAULT '650',
  `cities` mediumint(7) NOT NULL DEFAULT '5',
  `num_immigrated` smallint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_population_log`
--

INSERT INTO `au_population_log` (`entityID`, `day`, `population`, `id`, `unemployment_rate`, `pop_growth_rate`, `salaries`, `cities`, `num_immigrated`) VALUES
(1, 0, 100000, 1, '6.2000', 1, 2014, 5, 0),
(1, 1, 100083, 2, '6.0000', 1, 2014, 10, 800),
(1, 2, 100166, 3, '6.0000', 1, 2008, 10, 800),
(1, 3, 100249, 4, '6.0000', 1, 2007, 10, 800),
(1, 4, 100332, 5, '6.0000', 1, 2005, 10, 800),
(1, 5, 100415, 6, '6.0000', 1, 2003, 5, 800),
(1, 6, 100498, 7, '6.0000', 1, 2002, 5, 800),
(1, 7, 100581, 8, '6.0000', 1, 2000, 5, 800),
(1, 8, 100664, 9, '6.0000', 1, 1999, 5, 800),
(1, 9, 100747, 10, '6.0000', 1, 1997, 5, 800),
(1, 10, 100830, 11, '6.0000', 1, 1995, 5, 800),
(1, 11, 100914, 12, '6.0000', 1, 1994, 5, 800),
(1, 12, 100998, 13, '6.0000', 1, 1992, 5, 800),
(1, 13, 101082, 14, '6.0000', 1, 1990, 5, 800),
(1, 14, 101166, 15, '6.0000', 1, 1989, 5, 800),
(1, 15, 101250, 16, '6.0000', 1, 1987, 5, 800),
(1, 16, 101334, 17, '6.0000', 1, 1985, 5, 800),
(1, 17, 101418, 18, '6.0000', 1, 1984, 5, 800);

-- --------------------------------------------------------

--
-- Table structure for table `filios_taxes`
--

CREATE TABLE `au_taxes` (
  `id` mediumint(14) NOT NULL,
  `entityID` mediumint(14) NOT NULL,
  `income_tax_rate` mediumint(10) NOT NULL DEFAULT '30',
  `sales_tax_rate` mediumint(10) NOT NULL DEFAULT '7',
  `property_tax_rate` mediumint(10) NOT NULL DEFAULT '2',
  `income_taxes` bigint(35) NOT NULL,
  `sales_taxes` bigint(35) NOT NULL,
  `property_taxes` bigint(35) NOT NULL,
  `total_taxes` bigint(35) NOT NULL DEFAULT '125000000',
  `day` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `filios_taxes`
--

INSERT INTO `au_taxes` (`id`, `entityID`, `income_tax_rate`, `sales_tax_rate`, `property_tax_rate`, `income_taxes`, `sales_taxes`, `property_taxes`, `total_taxes`, `day`) VALUES
(1, 1, 30, 7, 2, 0, 0, 0, 125000000, 0),
(2, 1, 31, 7, 2, 28350000, 6615000, 50000000, 84965000, 1),
(3, 1, 31, 7, 2, 29295000, 6615000, 100000000, 135910000, 2),
(4, 1, 31, 7, 2, 29295000, 6615000, 100000000, 135910000, 3),
(5, 1, 31, 7, 2, 29295000, 6615000, 100000000, 135910000, 4),
(6, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 5),
(7, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 6),
(8, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 7),
(9, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 8),
(10, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 9),
(11, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 10),
(12, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 11),
(13, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 12),
(14, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 13),
(15, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 14),
(16, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 15),
(17, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 16),
(18, 1, 30, 9, 2, 28350000, 8505000, 50000000, 86855000, 17);

-- --------------------------------------------------------

--
-- Table structure for table `game_state`
--

CREATE TABLE `au_game_state` (
  `id` mediumint(10) NOT NULL,
  `current_day` mediumint(10) NOT NULL,
  `viewing_day` smallint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game_state`
--

INSERT INTO `au_game_state` (`id`, `current_day`, `viewing_day`) VALUES
(1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `industry_properties`
--

CREATE TABLE `au_industry_properties` (
  `id` mediumint(15) NOT NULL,
  `description` text NOT NULL,
  `name` text NOT NULL,
  `plural` text NOT NULL,
  `field_name` text NOT NULL,
  `unit` mediumint(10) NOT NULL,
  `cost_per_unit` decimal(15,4) NOT NULL,
  `resell_per_unit` decimal(20,4) NOT NULL,
  `value_per_unit` decimal(20,4) NOT NULL,
  `value_pretty_print` text NOT NULL,
  `trader_table` text NOT NULL,
  `initial_energy_cost` bigint(35) NOT NULL,
  `ongoing_energy_cost` bigint(25) NOT NULL,
  `initial_construction_cost` bigint(35) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `industry_properties`
--

INSERT INTO `au_industry_properties` (`id`, `description`, `name`, `plural`, `field_name`, `unit`, `cost_per_unit`, `resell_per_unit`, `value_per_unit`, `value_pretty_print`, `trader_table`, `initial_energy_cost`, `ongoing_energy_cost`, `initial_construction_cost`) VALUES
(1, 'Food production farms and facilities:<br />', 'food facility', 'food facilities', 'num_foodproducers', 1, '11000000.0000', '10000000.0000', '10000000.0000', '$10,000,000 in government infrastructure each. <br />', 'real_estate_developer', 100000, 500, 100000),
(2, 'Water Production Facilities:<br />', 'water facility', 'water facilities', 'num_waterproducers', 1, '110000000.0000', '100000000.0000', '100000000.0000', '$10,000,000 each.  ', 'real_estate_developer', 100000, 500, 100000),
(3, 'Oil Wells:<br />', 'well', 'wells', 'num_oilwells', 1, '1100000.0000', '1000000.0000', '1000000.0000', '$1,000,000 each.  ', 'real_estate_developer', 100000, 0, 100000),
(4, 'Alternative Energy Producers:<br/>', 'Wind or Sun field', 'Wind or Sun fields', 'num_alternativeenergy', 1, '1100000.0000', '1000000.0000', '1000000.0000', '$1,000,000 each.  ', 'real_estate_developer', 100000, 0, 100000),
(5, 'Construction Material Producers:<br/>', 'factory', 'factories', 'num_constructionmaterialproducers', 1, '1100000.0000', '1000000.0000', '1000000.0000', '$1,000,000 each.  ', 'real_estate_developer', 100000, 500, 100000);

-- --------------------------------------------------------


--
-- Dumping data for table `intelibus_army_log`
--

INSERT INTO `au_army_log` (`id`, `entityID`, `day`, `num_tanks`, `num_boats`, `num_airplanes`, `num_soldiers`, `num_bombs`, `num_bases`, `balance`) VALUES
(1, 3, 0, 3, 4, 3, 10000, 2, 2, -58200000),
(4, 3, 1, 3, 4, 3, 10000, 2, 2, -58200000),
(5, 3, 2, 3, 4, 3, 10000, 2, 2, -58200000),
(6, 3, 3, 3, 4, 3, 10000, 2, 2, -58200000),
(7, 3, 4, 3, 4, 3, 10000, 2, 2, -58200000),
(8, 3, 5, 3, 4, 3, 10000, 2, 2, -58200000),
(9, 3, 6, 3, 4, 3, 10000, 2, 2, -58200000),
(10, 3, 7, 3, 4, 3, 10000, 2, 2, -58200000),
(11, 3, 8, 3, 4, 3, 10000, 2, 2, -58200000),
(12, 3, 9, 3, 4, 3, 10000, 2, 2, -58200000),
(13, 3, 10, 3, 4, 3, 10000, 2, 2, -58200000),
(14, 3, 11, 3, 4, 3, 10000, 2, 2, -58200000),
(15, 3, 12, 3, 4, 3, 10000, 2, 2, -58200000),
(16, 3, 13, 3, 4, 3, 10000, 2, 2, -58200000),
(17, 3, 14, 3, 4, 3, 10000, 2, 2, -58200000),
(18, 3, 15, 3, 4, 3, 10000, 2, 2, -58200000),
(19, 3, 16, 3, 4, 3, 10000, 2, 2, -58200000),
(20, 3, 17, 3, 4, 3, 10000, 2, 2, -58200000);

-- --------------------------------------------------------



--
-- Dumping data for table `intelibus_bank`
--

INSERT INTO `au_bank` (`entity_id`, `industry_received`, `industry_paid`, `army_received`, `army_paid`, `taxes_received`, `govt_programs_paid`, `infrastructure_received`, `infrastructure_paid`, `filios_received`, `filios_paid`, `xtensica_received`, `xtensica_paid`, `central_bank_received`, `central_bank_paid`, `arms_paid`, `arms_received`, `developer_paid`, `developer_received`, `initial_balance`, `account_balance`, `day`, `id`) VALUES
(3, 0, 0, 0, 58200000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 0, 1),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 62785425, 1, 2),
(3, 0, 0, 0, 10000000, 90135000, 25237800, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 62785425, 20000000, 2, 3),
(3, 0, 0, 0, 10000000, 90135000, 25237800, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 3, 4),
(3, 0, 0, 0, 10000000, 90135000, 25237800, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 4, 7),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 5, 8),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 6, 9),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 7, 10),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 8, 11),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 9, 12),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 10, 13),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 11, 14),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 12, 15),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 13, 16),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 14, 17),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 15, 18),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 16, 19),
(3, 0, 0, 0, 10000000, 68552500, 15767075, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20000000, 20000000, 17, 20);

-- --------------------------------------------------------

--
-- Dumping data for table `intelibus_consumption`
--

INSERT INTO `au_consumption` (`id`, `entityID`, `day`, `food_consumed`, `oil_consumed`, `alt_energy_consumed`, `total_energy_consumed`, `const_mat_consumed`, `water_consumed`) VALUES
(1, 3, 0, 36000000, 94700000, 0, 0, 0, 0),
(2, 3, 0, 36000000, 94700000, 0, 0, 0, 0),
(3, 3, 1, 86400000, 60215000, 0, 54000000, 0, 0),
(4, 3, 2, 4791996, 2660000, 0, 4910000, 0, 0),
(5, 3, 3, 4784004, 2660000, 0, 25160000, 0, 0),
(6, 3, 4, 9552048, 2660000, 0, 2660000, 0, 0),
(7, 3, 5, 3576042, 205000, 0, 2455000, 0, 0),
(8, 3, 6, 2380050, 205000, 0, 2455000, 0, 0),
(9, 3, 7, 2376078, 205000, 0, 2455000, 0, 0),
(10, 3, 8, 2372112, 205000, 0, 2455000, 0, 0),
(11, 3, 9, 2368158, 205000, 0, 2455000, 0, 0),
(12, 3, 10, 2364210, 205000, 0, 2455000, 0, 0),
(13, 3, 11, 2360268, 2455000, 0, 4705000, 0, 0),
(14, 3, 12, 3534498, 7160000, 0, 9410000, 0, 0),
(15, 3, 13, 2352402, 2660000, 0, 4910000, 0, 0),
(16, 3, 14, 2348478, 2660000, 0, 4910000, 0, 0),
(17, 3, 15, 2344560, 410000, 0, 2660000, 0, 0),
(18, 3, 16, 1170324, 205000, 0, 2455000, 0, 0),
(19, 3, 17, 1168371, 205000, 0, 205000, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `intelibus_govprograms_log`
--

CREATE TABLE `au_govprograms_log` (
  `health_pct` smallint(7) NOT NULL DEFAULT '15',
  `health_total` bigint(15) NOT NULL DEFAULT '6500000',
  `educ_pct` smallint(7) NOT NULL DEFAULT '8',
  `educ_total` bigint(15) NOT NULL DEFAULT '3500000',
  `infrastructure_pct` smallint(7) NOT NULL DEFAULT '0',
  `infrastructure_total` mediumint(10) NOT NULL DEFAULT '0',
  `army_pct` smallint(7) NOT NULL DEFAULT '11',
  `army_total` bigint(20) NOT NULL DEFAULT '10000000',
  `id` mediumint(14) NOT NULL,
  `government_total` bigint(14) NOT NULL DEFAULT '33000000',
  `day` smallint(6) NOT NULL DEFAULT '0',
  `resident_pct` smallint(7) NOT NULL DEFAULT '30',
  `resident_total` bigint(20) NOT NULL DEFAULT '13000000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `intelibus_govprograms_log`
--

INSERT INTO `au_govprograms_log` (`entityID`, `health_pct`, `health_total`, `educ_pct`, `educ_total`, `infrastructure_pct`, `infrastructure_total`, `army_pct`, `army_total`, `id`, `government_total`, `day`, `resident_pct`, `resident_total`) VALUES
(3, 15, 6500000, 8, 3500000, 0, 0, 11, 10000000, 1, 33000000, 0, 30, 13000000),
(3, 20, 10282875, 8, 5484200, 10, 0, 12, 7540775, 2, 23307850, 1, 30, 13000000),
(3, 20, 18027000, 8, 7210800, 10, 8388607, 12, 10816200, 3, 45067500, 2, 30, 13000000),
(3, 20, 18027000, 8, 7210800, 10, 8388607, 12, 10816200, 4, 45067500, 3, 30, 13000000),
(3, 20, 18027000, 8, 7210800, 10, 8388607, 12, 10816200, 5, 45067500, 4, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 6, 23307850, 5, 30, 13000000),
(3, 0, 6500000, 8, 3500000, 0, 0, 11, 10000000, 7, 33000000, 0, 30, 13000000),
(3, 15, 6500000, 0, 3500000, 0, 0, 11, 10000000, 8, 33000000, 0, 30, 13000000),
(3, 15, 6500000, 8, 3500000, 0, 0, 11, 10000000, 9, 33000000, 0, 30, 13000000),
(3, 15, 6500000, 8, 3500000, 0, 0, 0, 10000000, 10, 33000000, 0, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 11, 23307850, 6, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 12, 23307850, 7, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 13, 23307850, 8, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 14, 23307850, 9, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 15, 23307850, 10, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 16, 23307850, 11, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 17, 23307850, 12, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 18, 23307850, 13, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 19, 23307850, 14, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 20, 23307850, 15, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 21, 23307850, 16, 30, 13000000),
(3, 15, 10282875, 8, 5484200, 0, 0, 11, 7540775, 22, 23307850, 17, 30, 13000000);

-- --------------------------------------------------------
--
-- Dumping data for table `intelibus_home`
--

INSERT INTO `au_home` (`id`, `entityID`, `weather_id`, `liveable_wage`, `day`) VALUES
(1, 3, 1, '879.00', 0),
(5, 3, 3, '0.00', 2),
(4, 3, 3, '0.00', 1),
(6, 3, 1, '0.00', 3),
(7, 3, 4, '0.00', 6);

-- --------------------------------------------------------

--
-- Dumping data for table `intelibus_industry_log`
--

INSERT INTO `au_industry_log` (`id`, `entityID`, `day`, `num_foodproducers`, `num_waterproducers`, `num_oilwells`, `num_alternativeenergy`, `balance`, `num_constructionmaterialproducers`, `oil_produced`, `alt_energy_produced`, `water_produced`, `food_produced`, `construction_produced`, `total_production`) VALUES
(1, 3, 1, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(2, 3, 0, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(3, 3, 2, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(4, 3, 3, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(5, 3, 4, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(6, 3, 5, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(7, 3, 6, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(8, 3, 7, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(9, 3, 8, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(10, 3, 9, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(11, 3, 10, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(12, 3, 11, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(13, 3, 12, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(14, 3, 13, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(15, 3, 14, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(16, 3, 15, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(17, 3, 16, 300, 0, 6, 0, 0, 50, 900000, 0, 0, 22500000, '150000.0000', '158250000.0000'),
(18, 3, 17, 300, 0, 6, 0, 0, 50, 20000000, 0, 100000, 800000, '0.0000', '0.0000');

-- --------------------------------------------------------

--
-- Dumping data for table `intelibus_population_log`
--

INSERT INTO `au_population_log` (`day`, `population`, `id`, `unemployment_rate`, `pop_growth_rate`, `salaries`, `cities`, `num_immigrated`) VALUES
(0, 3, 400000, 1, '10.0000', -2, 879, 2, 0),
(1, 3, 399333, 2, '10.0000', -2, 879, 2, -800),
(2, 3, 398667, 3, '10.0000', -2, 880, 2, -800),
(3, 3, 398002, 4, '10.0000', -2, 882, 2, -800),
(4, 3, 397338, 5, '10.0000', -2, 883, 2, -800),
(5, 3, 396675, 6, '10.0000', -2, 885, 2, -800),
(6, 3, 396013, 7, '10.0000', -2, 886, 2, -800),
(7, 3, 395352, 8, '10.0000', -2, 888, 2, -800),
(8, 3, 394693, 9, '10.0000', -2, 889, 2, -800),
(9, 3, 394035, 10, '10.0000', -2, 890, 2, -800),
(10, 3, 393378, 11, '10.0000', -2, 892, 2, -800),
(11, 3, 392722, 12, '10.0000', -2, 893, 2, -800),
(12, 3, 392067, 13, '10.0000', -2, 895, 2, -800),
(13, 3, 391413, 14, '10.0000', -2, 896, 2, -800),
(14, 3, 390760, 15, '10.0000', -2, 898, 2, -800),
(15, 3, 390108, 16, '10.0000', -2, 899, 2, -800),
(16, 3, 389457, 17, '10.0000', -2, 901, 2, -800),
(17, 3, 388807, 18, '10.0000', -2, 902, 2, -800);

-- --------------------------------------------------------



INSERT INTO `au_taxes` (`id`, `entityID`, `income_tax_rate`, `sales_tax_rate`, `property_tax_rate`, `income_taxes`, `sales_taxes`, `property_taxes`, `total_taxes`, `day`) VALUES
(1, 3, 30, 7, 1, 0, 0, 0, 44000000, 0),
(2, 3, 32, 6, 3, 47475000, 11077500, 10000000, 68552500, 1),
(3, 3, 32, 6, 3, 50640000, 9495000, 30000000, 90135000, 2),
(4, 3, 32, 6, 3, 50640000, 9495000, 30000000, 90135000, 3),
(5, 3, 32, 6, 3, 50640000, 9495000, 30000000, 90135000, 4),
(6, 3,  30, 7, 1, 47475000, 11077500, 10000000, 68552500, 5),
(7, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 6),
(8, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 7),
(9, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 8),
(10, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 9),
(11, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 10),
(12, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 11),
(13, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 12),
(14, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 13),
(15, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 14),
(16, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 15),
(17, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 16),
(18, 3, 30, 7, 1, 47475000, 11077500, 10000000, 68552500, 17);

-- --------------------------------------------------------

--
-- Table structure for table `job_properties`
--

CREATE TABLE `au_job_properties` (
  `id` mediumint(15) NOT NULL,
  `jobId` smallint(12) NOT NULL,
  `table_name` text NOT NULL,
  `field_name` text NOT NULL,
  `job_table_field_name` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `job_properties`
--

INSERT INTO `au_job_properties` (`id`, `jobId`, `table_name`, `field_name`, `job_table_field_name`) VALUES
(1, 1, 'army_properties', 'num_tanks', 'tanks'),
(2, 1, 'army_properties', 'num_boats', 'boats'),
(3, 1, 'army_properties', 'num_airplanes', 'airplanes'),
(4, 1, 'army_properties', 'num_bombs', 'bombs');

-- --------------------------------------------------------

--
-- Table structure for table `population_properties`
--

CREATE TABLE `au_population_properties` (
  `id` smallint(12) NOT NULL,
  `Name` text NOT NULL,
  `field_name` text NOT NULL,
  `readonly` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `population_properties`
--

INSERT INTO `au_population_properties` (`id`, `Name`, `field_name`, `readonly`) VALUES
(1, 'Population', 'population', 1),
(2, 'Unemployment Rate (percent)', 'unemployment_rate', 1),
(3, 'Population Growth Rate (percent)', 'pop_growth_rate', 1),
(4, 'Salaries', 'salaries', 1),
(5, 'Cities', 'cities', 0),
(6, 'Number Gained/Lost due to Immigration Last Month', 'num_immigrated', 1);

-- --------------------------------------------------------

--
-- Table structure for table `real_estate_developer`
--

CREATE TABLE `au_real_estate_developer` (
  `id` mediumint(14) NOT NULL,
  `day` smallint(6) NOT NULL DEFAULT '0',
  `num_bases` smallint(8) NOT NULL DEFAULT '10',
  `num_cities` smallint(8) NOT NULL DEFAULT '20',
  `num_waterproducers` smallint(8) NOT NULL DEFAULT '10',
  `num_foodproducers` smallint(8) NOT NULL DEFAULT '10',
  `num_constructionmaterialproducers` smallint(8) NOT NULL DEFAULT '10',
  `num_oilwells` smallint(8) NOT NULL DEFAULT '40',
  `num_alternativeenergy` smallint(8) NOT NULL DEFAULT '40',
  `balance` bigint(35) NOT NULL DEFAULT '100000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `real_estate_developer`
--

INSERT INTO `au_real_estate_developer` (`id`, `day`, `num_bases`, `num_cities`, `num_waterproducers`, `num_foodproducers`, `num_constructionmaterialproducers`, `num_oilwells`, `num_alternativeenergy`, `balance`) VALUES
(1, 0, 8, 20, 20, 10, 10, 40, 40, 21000000),
(2, 1, 2, 19, 20, 50, 50, 20, 20, 1334600000),
(3, 2, 1, 20, 20, 50, 50, 20, 20, 3000000),
(4, 3, 10, 20, 20, 50, 50, 20, 20, 3000000),
(5, 4, 10, 20, 20, 50, 50, 20, 20, 3000000),
(6, 5, 10, 20, 20, 10, 10, 40, 40, 3000000),
(7, 6, 10, 20, 20, 10, 10, 40, 40, 3000000),
(8, 7, 10, 20, 20, 10, 10, 40, 40, 3000000),
(9, 8, 10, 20, 20, 10, 10, 40, 40, 3000000),
(10, 9, 10, 20, 20, 10, 10, 40, 40, 3000000),
(11, 10, 10, 20, 20, 10, 10, 40, 40, 3400000),
(12, 11, 10, 20, 20, 10, 10, 40, 40, 3400000),
(13, 12, 10, 20, 20, 10, 10, 40, 40, 100000),
(14, 13, 9, 20, 20, 10, 10, 40, 40, 15500000),
(15, 14, 10, 20, 20, 10, 10, 40, 40, 220100000),
(16, 15, 10, 20, 20, 10, 10, 40, 40, 100000),
(17, 16, 10, 20, 20, 10, 10, 40, 40, 100000),
(18, 17, 10, 20, 20, 10, 10, 40, 40, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `real_estate_properties`
--

CREATE TABLE `au_real_estate_properties` (
  `id` mediumint(12) NOT NULL,
  `description` text NOT NULL,
  `name` text NOT NULL,
  `plural` text NOT NULL,
  `field_name` text NOT NULL,
  `unit` mediumint(10) NOT NULL,
  `value_per_unit` decimal(15,4) NOT NULL,
  `value_pretty_print` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `real_estate_properties`
--

INSERT INTO `au_real_estate_properties` (`id`, `description`, `name`, `plural`, `field_name`, `unit`, `value_per_unit`, `value_pretty_print`) VALUES
(1, 'Food production farms and facilities:<br />', 'food facility', 'food facilities', 'num_foodproducers', 1, '1000000.0000', '$50,000,000 in government infrastructure for 50 farms. <br />'),
(2, 'Water Production Facilities:<br />', 'water facility', 'water facilities', 'num_waterproducers', 1, '10000000.0000', '$10,000,000 each.'),
(3, 'Oil Wells:<br />', 'well', 'wells', 'num_oilwells', 1, '1000000.0000', '$1,000,000 each.'),
(4, 'Alternative Energy (1/2 Sun, 1/2 Wind):<br/>', 'Wind or Sun field', 'Wind or Sun fields', 'num_alternativeenergy', 1, '1000000.0000', '$1,000,000 each.'),
(5, 'Construction Material Production:<br/>', 'factory', 'factories', 'num_constructionmaterialproducers', 1, '1000000.0000', '$50,000,000 each.'),
(6, 'Bases and Prisons', 'Base or Prison', 'Bases or Prisons', 'num_bases', 1, '10000000.0000', '$10,000,000 each.'),
(7, 'Cities:', 'City', 'Cities', 'num_cities', 1, '50000000.0000', '$50,000,000 each.');

-- --------------------------------------------------------

--
-- Table structure for table `scenarios`
--

CREATE TABLE `au_scenarios` (
  `id` mediumint(20) NOT NULL,
  `day` mediumint(20) NOT NULL,
  `title` text NOT NULL,
  `scene` text NOT NULL,
  `Notes` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `scenarios`
--

INSERT INTO `au_scenarios` (`id`, `day`, `title`, `scene`, `Notes`) VALUES
(1, 1, 'Drinking Water Crisis 1', 'Filios is a small country without natural lakes and has to import its water. The earthquake has destroyed parts of Xtensica, specifically two of the water purification plants. The plants together produce 200 million gallons each day. Filios has bought the rights to use 150 million gallons each month, but Xtensica currently only produces 100 million gallons of clean water a month. Filios pays $ 40 million a year for clean water from Xtensica (part of the programs for residents). Without clean water and with an influx of people from intelibus, cholera will start to spread killing in 2 months.   This will kill 100 people each month.', ''),
(2, 1, 'Earthquake Crisis 2', ' Yesterday there was an earthquake in Xtensica that measured 7.1 on Richter scale. The earthquake\'s epicenter was 50 miles south of the capital La Xtensifica, yet the effects were severe. Earthquakes and reconstruction experts say the cleanup will cost about $ 700 million.\r\n\r\nA big problem is drinking water. Most of the drinking water in the country will go to Filios. With clean water production at only 50% capacity, you can not provide water for Filios and people of the country. It will cost $ 40 million to repair.', ''),
(3, 2, 'Global Warming Crisis 3', 'There is too much carbon dioxide in the atmosphere, which is causing the ozone layer to thin out and the temperature to rise.   The carbon dioxide comes from burning oil. Each barrel of oil creates about 3 tons of carbon dioxide.   The world needs to lower its release of carbon dioxide by 1 billion tons to avoid the hazards of global warming.', ''),
(4, 2, 'Drought Crisis 4', 'Because carbon dioxide and other pollutants are ruining the air, and there is over-cultivation of land and cutting trees to create more areas for agriculture and for use in construction, there is a lot of drought in the three countries. Although the three countries are all near the sea, there is almost no rain, and cities are using more and more energy. After 3 days with no rain, each country will lose 20% of its production every day that it doesn\'t rain there. When a solution to global warming is fixed,', ''),
(5, 3, 'Debt Crisis 5', 'Xtensica owes $ 500 million to the central bank. It must pay within 10 days of the game (10 months time). If not paid within 10 months, the leaders will have to resign their posts and the central bank will lead the country.', ''),
(6, 3, 'Fishing  Crisis 6', 'Due to overfishing in the waters off of Ramafa, Intelibus’s fishing industry is slowly dying.   The profits have dropped 10%.   They will continue to drop if major reforms are not taken.       This will affect food production by 1% a month for any month that it is not resolved.', ''),
(7, 4, 'Demonstrations/Protests Crisis 7', 'In Xtensica the middle class has risen to be the majority of the population. The middle class has been protesting against some strict rulings of their rulers and level of unemployment in the country. The group of protesters is supported by the SUO group that is in the central mountains.   They are considered rebels by the government. So far the protests have been calm, but if unemployment does not fall by 2% or more, there may be violent protests and the government may have to resign their posts. Without an increase in production to help the unemployed in 5 days of play (5 months) Xtensica rulers will have to resign their posts.', ''),
(8, 4, 'Land Mines Crisis 8', 'The last time that southern Intelibus fought for its independence was 20 years ago.   At one point the rebels planted large fields of land mines to blow up incoming tanks.   There are still several hundred of these mines in the ground.   At times they blow off people’s limbs, or kill innocent people.', ''),
(9, 5, 'Forest Fire Crisis 9', 'If there is no rain in Xtensica for the first three days there is a ⅙ chance of there being a fire.   If a fire starts it will destroy 1/10 of all production facilities. (This is to be put in as a loss in buildings and can not be recovered unless they are bought again.)', 'some testing123'),
(10, 5, 'Vaccinations Crisis 10', 'Polio Malaria   Intelibus and Xtensica both suffer from a large Malaria problem.   All three countries still have Polio outbreaks every summer.   Vaccinations need to be given to each child in all three countries at a cost of $1 a vaccination.   The percentage of children under the age of 15 in each country is as follows.   Intelibus 35%   Xtensica 25%   Filios 15%', 'more testing 4565'),
(11, 6, 'Migration Crisis 11', 'Intelibus is a poor country, and formerly was a colony of   Filios. It is losing 800 people every day in migration to the Filios. Filios, meanwhile does not want Intelibitians, although they need their labor in their factories. It sends 200 back to Intelibus every day (so a total of 1,000 leave, 200 come back).     Many of the Intelibitians suffer grave medical problems due to less health spending in Intelibus.   Filios does not want to pay for their medical care. \r\n', ''),
(13, 7, 'Human Trafficking Crisis 13', ' There a lot of immigration from Intelibus to Filios, but there are those who tell the poor in Intelibus that they can get a better life in Filios by promising them a permanent job for a price. When they reach the promised jobs Filios make slaves of people in Filios.   They claim that their earnings go towards paying their debt.\r\n\r\nThe people of Filios feel bad for the slaves but need their cheap labor. Intelibitians do not want their people to be enslaved. ', ''),
(12, 6, 'Mining   Crisis 12', 'Yesterday one of the mines in Intelibus collapsed, leaving 22 miners trapped.   They will have approximately 4 days to live, and it will cost 5 million dollars in energy and personnel to extract them.   Intelibus does not have the equipment to extract people.   This equipment is held by the Arms Dealer, who, for a price, might let them use it.', ''),
(14, 7, 'Rebels / Bombings Crisis 14', ' In the mountains of Xtensica there is a group of rebels in the mountains, "the Suos". The SUO’s spread pamphlets and posters against government of Xtensica and against Filios because of the inequality between the living standards of the countries. SUO says that Filios is stealing from other countries.\r\n\r\nLast week one of the SUO\'s bases was bombed. We do not know who it was. SUO blames the Xtensican government. The government says it was Filios and wants Filios to pay $ 10 million to the rebels for rehabilitation of their camps.   If they do not pay there may be violence in the city.', ''),
(15, 8, 'Oil Spill Crisis 15', 'Filios does not produce enough oil for consumption and Xtensica sends Filios oil   on tanker ships. Unfortunately, the ships are not well monitored, and yesterday oil thieves caused an oil spill off the coast of Filios that is killing sea animals, and causing the production quantity in Xtensica to go down 1% each game day (every month). (To clean everything costs $400 million, the company says is not responsible as they were thieves who caused it.)', ''),
(16, 8, 'Independence Crisis 16', 'In the southern tip of Intelibus there is a group of SUO’s and their sympathizers who are stirring up the population to fight for independence from Intelibus again.   This is not the first   that they have fought.', ''),
(17, 9, 'Disease Crisis 17', 'Intelibus, which has no potable water and does not spend much for their health system is experiencing a major problem with Cholera. Cholera is killing 100 every month. You can increase potable water plants to 1/100,000 people to solve the problem, but you have to cut funds from another program.', ''),
(18, 9, 'Plastics Pollution Crisis 18 ', 'Filios is a country which is rich enough to use a lot of plastic. When it has no use for plastic, it sends the old plastic to Intelibus to recycle it, but residents complain that the plastic is causing cancer. Intelibus is suing Filios for 100 million dollars to cover medical costs for those affected. Filios says that the sicknesses have to do with its scarcity of water and not with plastic.', ''),
(19, 10, 'Food Shortage/Famine Crisis 19', 'Intelibus, has vast desert lands in the south which have been expanding in the arid plains that produce the most corn and wheat for the country. Now the country is experiencing a shortage of basic foods. The southern half of the country is affected. 100,000 tons of maize is needed to finish the year well. The government is asking for help because they do not have enough money to maintain their health and education services, provide transportation infrastructure, public order, and subsidize the food needed for their people.', ''),
(20, 10, 'Disputed island Crisis 20', 'Next to Intelibus there is an island called "Ramafa" it is claimed by Intelibus and Filios. Ramafa does not produce much as it is in war between the two countries, but it is believed to have large oil deposits. Oil is struck ½ times a well is dug.', ''),
(28, 14, 'Sea Piracy Crisis 28', 'Between the countries of Xtensica and Intelibus there is a small band of pirates who terrorize ships, specifically oil tankers, which send goods from one country to another. Something needs to be done about this, or all of the oil supply of Xtensica will end up in dangerous hands (like the Mercenaries of Filios).', ''),
(21, 11, 'Poaching Crisis 21', 'The hides of specific animals (the beautiful Ramafan Lynx, and the Xtensican Tiger) are very prized among the wealthy and elite of all the countries. Unfortunately, both animals are on the endangered species list. Meanwhile poachers kill and skin these animals on a regular basis, terrorizing the residents of the rural areas where they are found, and depleting the already reduced numbers of animals.', ''),
(22, 11, 'Identity fraud Crisis 22 ', 'More than 1 billion dollars is stolen each year from residents of Topia through the internet. While no one knows exactly where the money goes, or who is stealing it, residents complain constantly, and it seems as if the army is not handling the job.', ''),
(23, 12, 'Elections Crisis 23', 'Today you will all hold elections for the post of Prime Minister of each country.   Each member of the game will cast secret ballots as to who should hold the post for each country.', ''),
(24, 12, 'Racism Crisis 24', 'Filios is a country that has a lot of diversity.   There are the native Filitians, but then there are also immigrants, legal and illegal, brought there for work and those who have come to make a better life.   There have been complaints by immigrants and those who are not Filitians of racism from the Filitian community.   The government would like to create a program to combat racism.  ', ''),
(25, 13, 'Refugees   Crisis 25 ', 'As the fighting starts in southern Intelibus 5,000 people flee what they think will be incredible violence.   Intelibus must deal with this population of people whos might contain sympathizers of the SUO’s.   There are known land mines in the territories that they are crossing.', ''),
(26, 13, 'Drug Use Crisis 26', 'Filios has a drug problem. Over 10% of the population is using drugs. This causes major issues with money, responsibility, theft, and other social problems. ', ''),
(27, 14, 'Mercenary Mutiny Crisis 27', '½ of the soldiers employed by Filios are mercenaries.   These mercenaries have decided that their pay is not enough and are demanding a %50 increase in wages.', ''),
(29, 15, 'Endangered Species Crisis 29', 'In the mountains of Xtensica there are three kinds of tree frogs that are very sensitive to their environments.   They are particularly sensitive to the pesticides used by the SUO’s who live in their natural habitat.   Many people in Xtensica are concerned about their wellbeing and have started a petition to save them.   A breeding program has been started at the local zoo, but there is concern that this is not enough to provide enough genetic diversity.   Getting new live frogs seems to be difficult', ''),
(30, 15, 'Drug Trafficking Crisis 30', 'There is a large amount of drug production in Intelibus, especially in areas with fewer police and the desert. These drugs are sent to Filios, a rich country that has money to buy drugs and social problems. Filios says this is Intelibus\'s problem and it needs to solve the problem by employing 10,000 more troops. Intelibus says that if Filios was not consuming so many drugs, it would not be a problem.', '');

-- --------------------------------------------------------

--
-- Table structure for table `tax_types`
--

CREATE TABLE `au_tax_types` (
  `id` smallint(6) NOT NULL,
  `Name` text NOT NULL,
  `field_name` text NOT NULL,
  `money_recd_field_name` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tax_types`
--

INSERT INTO `au_tax_types` (`id`, `Name`, `field_name`, `money_recd_field_name`) VALUES
(1, 'Income Tax', 'income_tax_rate', 'income_taxes'),
(2, 'Sales Tax (on consumption)', 'sales_tax_rate', 'sales_taxes'),
(3, 'Property Tax', 'property_tax_rate', 'property_taxes'),
(5, 'Health', 'health_pct', 'health_total'),
(6, 'Education', 'educ_pct', 'educ_total'),
(7, 'Infrastructure', 'infrastructure_pct', 'infrastructure_total'),
(8, 'Army', 'army_pct', 'army_total');

-- --------------------------------------------------------

--
-- Table structure for table `translation_table`
--

CREATE TABLE `au_translation_table` (
  `id` mediumint(12) NOT NULL,
  `English` mediumtext,
  `Spanish` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `translation_table`
--

INSERT INTO `au_translation_table` (`id`, `English`, `Spanish`) VALUES
(1, NULL, NULL);

-- --------------------------------------------------------


--
-- Dumping data for table `xtensica_army_log`
--

INSERT INTO `au_army_log` (`id`, `entityID`, `day`, `num_tanks`, `num_boats`, `num_airplanes`, `num_soldiers`, `num_bombs`, `num_bases`, `balance`) VALUES
(1, 2, 0, 6, 5, 5, 30000, 1, 4, -104100000),
(2, 2, 1, 6, 5, 5, 30000, 1, 4, -104100000),
(3, 2, 2, 6, 5, 5, 30000, 1, 4, -104100000),
(4, 2, 3, 6, 5, 5, 30000, 1, 4, -104100000),
(5, 2, 4, 6, 5, 5, 30000, 1, 4, -104100000),
(6, 2, 5, 6, 5, 5, 30000, 1, 4, -104100000),
(7, 2, 6, 6, 5, 5, 30000, 1, 4, -104100000),
(8, 2, 7, 6, 5, 5, 30000, 1, 4, -104100000),
(9, 2, 8, 6, 5, 5, 30000, 1, 4, -104100000),
(10, 2, 9, 6, 5, 5, 30000, 1, 4, -104100000),
(11, 2, 10, 6, 5, 5, 30000, 1, 4, -104100000),
(12, 2, 11, 6, 5, 5, 30000, 1, 4, -104100000),
(13, 2, 12, 6, 5, 5, 30000, 1, 4, -104100000),
(14, 2, 13, 6, 5, 5, 30000, 1, 4, -104100000),
(15, 2, 14, 6, 5, 5, 30000, 1, 4, -104100000),
(16, 2, 15, 6, 5, 5, 30000, 1, 4, -104100000),
(17, 2, 16, 6, 5, 5, 30000, 1, 4, -104100000),
(18, 2, 17, 6, 5, 5, 30000, 1, 4, -104100000);

-- --------------------------------------------------------

--
-- Dumping data for table `xtensica_bank`
--

INSERT INTO `au_bank` (`entityID`, `industry_received`, `industry_paid`, `army_received`, `army_paid`, `taxes_received`, `govt_programs_paid`, `infrastructure_received`, `infrastructure_paid`, `intelibus_received`, `intelibus_paid`, `filios_received`, `filios_paid`, `central_bank_received`, `central_bank_paid`, `arms_paid`, `arms_received`, `developer_paid`, `developer_received`, `initial_balance`, `account_balance`, `day`, `id`) VALUES
(2, 0, 0, 0, 104100000, 100628500, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 0, 1),
(2, 0, 0, 0, 30000000, 98540000, 24635000, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 83905000, 1, 2),
(2, 0, 0, 0, 30000000, 81832000, 19639680, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 83905000, 40000000, 2, 3),
(2, 0, 0, 0, 30000000, 81832000, 19639680, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 40000000, 3, 4),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 40000000, 5, 8),
(2, 0, 0, 0, 30000000, 81832000, 19639680, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 40000000, 4, 7),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 40000000, 6, 9),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 79210038, 7, 10),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 79210038, 108420076, 8, 11),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 108420076, 144630114, 9, 12),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 144630114, 40000000, 10, 13),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 40000000, 11, 14),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 69210038, 12, 15),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 69210038, 94020076, 13, 16),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 94020076, 40000000, 14, 17),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 94860038, 15, 18),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 94860038, 40000000, 16, 19),
(2, 0, 0, 0, 30000000, 92274500, 23068625, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 40000000, 40000000, 17, 20);

-- --------------------------------------------------------


INSERT INTO `au_consumption` (`id`, `entityID`, `day`, `food_consumed`, `oil_consumed`, `alt_energy_consumed`, `total_energy_consumed`, `const_mat_consumed`, `water_consumed`) VALUES
(1, 2, 0, 0, 8388607, 0, 0, 0, 0),
(2, 2, 0, 0, 8388607, 0, 0, 0, 0),
(3, 2, 1, 2700000, 8388607, 0, 96875000, 0, 900000),
(4, 2, 2, 3609000, 8184000, 0, 12059000, 0, 1203000),
(5, 2, 3, 3618012, 4309000, 0, 39184000, 0, 1206004),
(6, 2, 4, 7254096, 4309000, 0, 8184000, 0, 2418032),
(7, 2, 5, 2727081, 7967000, 0, 11842000, 0, 909027),
(8, 2, 6, 1822596, 217000, 0, 4092000, 0, 607532),
(9, 2, 7, 1827150, 217000, 0, 4092000, 0, 609050),
(10, 2, 8, 1831716, 217000, 0, 4092000, 0, 610572),
(11, 2, 9, 1836294, 217000, 0, 4092000, 0, 612098),
(12, 2, 10, 1840884, 217000, 0, 4092000, 0, 613628),
(13, 2, 11, 1845486, 4092000, 0, 7967000, 0, 615162),
(14, 2, 12, 2775141, 8388607, 0, 15934500, 0, 925047),
(15, 2, 13, 1854714, 8388607, 600000, 23684500, 0, 618238),
(16, 2, 14, 1859346, 7584500, 600000, 12059500, 0, 619782),
(17, 2, 15, 1863990, 7584500, 600000, 12059500, 0, 621330),
(18, 2, 16, 934323, 217000, 0, 4092000, 0, 311441),
(19, 2, 17, 936657, 217000, 0, 217000, 0, 312219);

-- --------------------------------------------------------


--
-- Dumping data for table `xtensica_govprograms_log`
--

INSERT INTO `au_govprograms_log` (`entityID`, `health_pct`, `health_total`, `educ_pct`, `educ_total`, `infrastructure_pct`, `infrastructure_total`, `army_pct`, `army_total`, `id`, `government_total`, `day`, `resident_pct`, `resident_total`) VALUES
(2, 15, 17245237, 10, 11496825, 10, 8388607, 15, 17245237, 1, 57484125, 0, 20, 19000000),
(2, 12, 14781000, 12, 9854000, 13, 8388607, 11, 14781000, 2, 49270000, 1, 20, 19000000),
(2, 12, 9819840, 12, 9819840, 13, 8388607, 11, 9001520, 3, 39279360, 2, 20, 19000000),
(2, 12, 9819840, 12, 9819840, 13, 8388607, 11, 9001520, 4, 39279360, 3, 20, 19000000),
(2, 12, 9819840, 12, 9819840, 13, 8388607, 11, 9001520, 5, 39279360, 4, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 6, 42446270, 5, 20, 19000000),
(2, 0, 16000000, 8, 7000000, 0, 0, 21, 20000000, 7, 63000000, 0, 20, 19000000),
(2, 17, 16000000, 0, 7000000, 0, 0, 21, 20000000, 8, 63000000, 0, 20, 19000000),
(2, 17, 16000000, 8, 7000000, 0, 0, 21, 20000000, 9, 63000000, 0, 20, 19000000),
(2, 17, 16000000, 8, 7000000, 0, 0, 0, 20000000, 10, 63000000, 0, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 11, 42446270, 6, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 12, 42446270, 7, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 13, 42446270, 8, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 14, 42446270, 9, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 15, 42446270, 10, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 16, 42446270, 11, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 17, 42446270, 12, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 18, 42446270, 13, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 19, 42446270, 14, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 20, 42446270, 15, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 21, 42446270, 16, 20, 19000000),
(2, 17, 15686665, 8, 7381960, 0, 0, 21, 19377645, 22, 42446270, 17, 20, 19000000);

-- --------------------------------------------------------

--
-- Dumping data for table `xtensica_home`
--

INSERT INTO `au_home` (`id`, `entityID`, `weather_id`, `liveable_wage`, `day`) VALUES
(1, 2, 4, '1671.00', 0),
(5, 2, 1, '0.00', 2),
(4, 2, 4, '0.00', 1),
(6, 2, 2, '0.00', 3),
(7, 2, 2, '0.00', 4),
(8, 2, 4, '0.00', 5),
(9, 2, 1, '0.00', 6),
(10, 2, 1, '0.00', 7),
(11, 2, 3, '0.00', 8),
(12, 2, 3, '0.00', 9),
(13, 2, 4, '0.00', 10),
(14, 2, 4, '0.00', 11),
(15, 2, 3, '0.00', 13);

-- --------------------------------------------------------


--
-- Dumping data for table `au_industry_log`
--

INSERT INTO `au_industry_log` (`id`, `entityID`, `day`, `num_foodproducers`, `num_waterproducers`, `num_oilwells`, `num_alternativeenergy`, `balance`, `num_constructionmaterialproducers`, `oil_produced`, `alt_energy_produced`, `water_produced`, `food_produced`, `construction_produced`, `total_production`) VALUES
(1, 2, 1, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(2, 2, 0, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(3, 2, 2, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(4, 2, 3, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(5, 2, 4, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(6, 2, 5, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(7, 2, 6, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(8, 2, 7, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(9, 2, 8, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(10, 2, 9, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(11, 2, 10, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(12, 2, 11, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(13, 2, 12, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(14, 2, 13, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(15, 2, 14, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(16, 2, 15, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(17, 2, 16, 250, 4, 10, 0, 0, 80, 1500000, 0, 200000000, 18750000, '240000.0000', '208850000.0000'),
(18, 2, 17, 250, 4, 10, 0, 0, 80, 20000000, 0, 150000000, 8000000, '0.0000', '0.0000');

-- --------------------------------------------------------

--
-- Dumping data for table `xtensica_population_log`
--

INSERT INTO `au_population_log` (`entityID`, `day`, `population`, `id`, `unemployment_rate`, `pop_growth_rate`, `salaries`, `cities`, `num_immigrated`) VALUES
(2, 0, 300000, 1, '8.0000', 3, 1671, 3, 0),
(2, 1, 300750, 2, '8.0000', 3, 1513, 3, 0),
(2, 2, 301501, 3, '8.0000', 3, 1509, 3, 0),
(2, 3, 302254, 4, '8.0000', 3, 1505, 3, 0),
(2, 4, 303009, 5, '8.0000', 3, 1502, 4, 0),
(2, 5, 303766, 6, '8.0000', 3, 1498, 3, 0),
(2, 6, 304525, 7, '8.0000', 3, 1494, 3, 0),
(2, 7, 305286, 8, '8.0000', 3, 1490, 3, 0),
(2, 8, 306049, 9, '8.0000', 3, 1487, 3, 0),
(2, 9, 306814, 10, '8.0000', 3, 1483, 3, 0),
(2, 10, 307581, 11, '8.0000', 3, 1479, 3, 0),
(2, 11, 308349, 12, '8.0000', 3, 1476, 3, 0),
(2, 12, 309119, 13, '8.0000', 3, 1472, 3, 0),
(2, 13, 309891, 14, '8.0000', 3, 1468, 3, 0),
(2, 14, 310665, 15, '8.0000', 3, 1465, 3, 0),
(2, 15, 311441, 16, '8.0000', 3, 1461, 3, 0),
(2, 16, 312219, 17, '8.0000', 3, 1457, 3, 0),
(2, 17, 312999, 18, '8.0000', 3, 1454, 3, 0);

-- --------------------------------------------------------

--
-- Dumping data for table `xtensica_taxes`
--

INSERT INTO `au_taxes` (`id`, `entityID`, `income_tax_rate`, `sales_tax_rate`, `property_tax_rate`, `income_taxes`, `sales_taxes`, `property_taxes`, `total_taxes`, `day`) VALUES
(1, 2, 31, 10, 1, 64743500, 20885000, 15000000, 100628500, 0),
(2, 2, 25, 7, 1, 62655000, 20885000, 15000000, 98540000, 1),
(3, 2, 25, 7, 1, 52212500, 14619500, 15000000, 81832000, 2),
(4, 2, 25, 7, 1, 52212500, 14619500, 15000000, 81832000, 3),
(5, 2, 25, 7, 1, 52212500, 14619500, 15000000, 81832000, 4),
(6, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 5),
(7, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 6),
(8, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 7),
(9, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 8),
(10, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 9),
(11, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 10),
(12, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 11),
(13, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 12),
(14, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 13),
(15, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 14),
(16, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 15),
(17, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 16),
(18, 2, 30, 7, 1, 62655000, 14619500, 15000000, 92274500, 17);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arms_trader`
--
ALTER TABLE `au_arms_trader`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arms_trader_properties`
--
ALTER TABLE `au_arms_trader_properties`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `army_properties`
--
ALTER TABLE `au_army_properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `available_jobs`
--
ALTER TABLE `au_available_jobs`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `available_weather`
--
ALTER TABLE `au_available_weather`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_properties`
--
ALTER TABLE `au_bank_properties`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `central_bank`
--
ALTER TABLE `au_central_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumption_index`
--
ALTER TABLE `au_consumption_index`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consumption_properties`
--
ALTER TABLE `au_consumption_properties`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `diplomat_properties`
--
ALTER TABLE `au_diplomat_properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `entities`
--
ALTER TABLE `au_entities`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `filios_army_log`
--
ALTER TABLE `au_army_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filios_bank`
--
ALTER TABLE `au_bank`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filios_consumption`
--
ALTER TABLE `au_consumption`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filios_diplomat`
--
ALTER TABLE `au_diplomat`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filios_govprograms_log`
--
ALTER TABLE `au_govprograms_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filios_home`
--
ALTER TABLE `au_home`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filios_industry_log`
--
ALTER TABLE `au_industry_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filios_population_log`
--
ALTER TABLE `au_population_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `filios_taxes`
--
ALTER TABLE `au_taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game_state`
--
ALTER TABLE `au_game_state`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industry_properties`
--
ALTER TABLE `au_industry_properties`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `job_properties`
--
ALTER TABLE `au_job_properties`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `population_properties`
--
ALTER TABLE `au_population_properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `real_estate_developer`
--
ALTER TABLE `au_real_estate_developer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `real_estate_properties`
--
ALTER TABLE `au_real_estate_properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `scenarios`
--
ALTER TABLE `au_scenarios`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `tax_types`
--
ALTER TABLE `au_tax_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `translation_table`
--
ALTER TABLE `au_translation_table`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arms_trader`
--
ALTER TABLE `au_arms_trader`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `arms_trader_properties`
--
ALTER TABLE `au_arms_trader_properties`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `army_properties`
--
ALTER TABLE `au_army_properties`
  MODIFY `id` tinyint(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `available_jobs`
--
ALTER TABLE `au_available_jobs`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `available_weather`
--
ALTER TABLE `au_available_weather`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `bank_properties`
--
ALTER TABLE `au_bank_properties`
  MODIFY `id` mediumint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `central_bank`
--
ALTER TABLE `au_central_bank`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `consumption_index`
--
ALTER TABLE `au_consumption_index`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `consumption_properties`
--
ALTER TABLE `au_consumption_properties`
  MODIFY `id` smallint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `diplomat_properties`
--
ALTER TABLE `au_diplomat_properties`
  MODIFY `id` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `entities`
--
ALTER TABLE `au_entities`
  MODIFY `id` mediumint(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `filios_army_log`
--
ALTER TABLE `au_army_log`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `filios_bank`
--
ALTER TABLE `au_bank`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;
--
-- AUTO_INCREMENT for table `filios_consumption`
--
ALTER TABLE `au_consumption`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `filios_diplomat`
--
ALTER TABLE `au_diplomat`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `filios_govprograms_log`
--
ALTER TABLE `au_govprograms_log`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `filios_home`
--
ALTER TABLE `au_home`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `filios_industry_log`
--
ALTER TABLE `au_industry_log`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `filios_population_log`
--
ALTER TABLE `au_population_log`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `filios_taxes`
--
ALTER TABLE `au_taxes`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `game_state`
--
ALTER TABLE `au_game_state`
  MODIFY `id` mediumint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `industry_properties`
--
ALTER TABLE `au_industry_properties`
  MODIFY `id` mediumint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `job_properties`
--
ALTER TABLE `au_job_properties`
  MODIFY `id` mediumint(15) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `population_properties`
--
ALTER TABLE `au_population_properties`
  MODIFY `id` smallint(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `real_estate_developer`
--
ALTER TABLE `au_real_estate_developer`
  MODIFY `id` mediumint(14) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `real_estate_properties`
--
ALTER TABLE `au_real_estate_properties`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `scenarios`
--
ALTER TABLE `au_scenarios`
  MODIFY `id` mediumint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
--
-- AUTO_INCREMENT for table `tax_types`
--
ALTER TABLE `au_tax_types`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `translation_table`
--
ALTER TABLE `au_translation_table`
  MODIFY `id` mediumint(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
