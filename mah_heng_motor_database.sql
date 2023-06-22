-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2023 at 03:35 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mah heng motor database`
--

-- --------------------------------------------------------

--
-- Table structure for table `component`
--

CREATE TABLE `component` (
  `Component_ID` int(6) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Quantity` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer_details`
--

CREATE TABLE `customer_details` (
  `Customer_ID` int(6) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Phone_Number` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_details`
--

CREATE TABLE `invoice_details` (
  `Invoice_ID` int(6) NOT NULL,
  `Date` date DEFAULT NULL,
  `Total_Price` decimal(10,2) DEFAULT NULL,
  `Supplier_ID` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ordered_component`
--

CREATE TABLE `ordered_component` (
  `Invoice_ID` int(6) NOT NULL,
  `Component_ID` int(6) NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Quantity` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE `service` (
  `Service_ID` int(6) NOT NULL,
  `Description` varchar(255) NOT NULL,
  `Total_Price` decimal(10,2) NOT NULL,
  `Service_Date` date NOT NULL,
  `Motor_ID` varchar(255) NOT NULL,
  `Customer_ID` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_component`
--

CREATE TABLE `service_component` (
  `Service_ID` int(6) NOT NULL,
  `Component_ID` int(6) NOT NULL,
  `Price_Per_Unit` decimal(10,2) NOT NULL,
  `Quantity` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_details`
--

CREATE TABLE `supplier_details` (
  `Supplier_ID` int(6) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Phone_Number` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `component`
--
ALTER TABLE `component`
  ADD PRIMARY KEY (`Component_ID`);

--
-- Indexes for table `customer_details`
--
ALTER TABLE `customer_details`
  ADD PRIMARY KEY (`Customer_ID`);

--
-- Indexes for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD PRIMARY KEY (`Invoice_ID`),
  ADD KEY `Supplier_ID` (`Supplier_ID`);

--
-- Indexes for table `ordered_component`
--
ALTER TABLE `ordered_component`
  ADD PRIMARY KEY (`Invoice_ID`,`Component_ID`),
  ADD KEY `Component_ID` (`Component_ID`);

--
-- Indexes for table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`Service_ID`),
  ADD KEY `Customer_ID` (`Customer_ID`);

--
-- Indexes for table `service_component`
--
ALTER TABLE `service_component`
  ADD PRIMARY KEY (`Service_ID`,`Component_ID`),
  ADD KEY `Component_ID` (`Component_ID`);

--
-- Indexes for table `supplier_details`
--
ALTER TABLE `supplier_details`
  ADD PRIMARY KEY (`Supplier_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `component`
--
ALTER TABLE `component`
  MODIFY `Component_ID` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_details`
--
ALTER TABLE `customer_details`
  MODIFY `Customer_ID` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_details`
--
ALTER TABLE `invoice_details`
  MODIFY `Invoice_ID` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service`
--
ALTER TABLE `service`
  MODIFY `Service_ID` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_details`
--
ALTER TABLE `supplier_details`
  MODIFY `Supplier_ID` int(6) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `invoice_details`
--
ALTER TABLE `invoice_details`
  ADD CONSTRAINT `invoice_details_ibfk_1` FOREIGN KEY (`Supplier_ID`) REFERENCES `supplier_details` (`Supplier_ID`);

--
-- Constraints for table `ordered_component`
--
ALTER TABLE `ordered_component`
  ADD CONSTRAINT `ordered_component_ibfk_1` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice_details` (`Invoice_ID`),
  ADD CONSTRAINT `ordered_component_ibfk_2` FOREIGN KEY (`Component_ID`) REFERENCES `component` (`Component_ID`);

--
-- Constraints for table `service`
--
ALTER TABLE `service`
  ADD CONSTRAINT `service_ibfk_1` FOREIGN KEY (`Customer_ID`) REFERENCES `customer_details` (`Customer_ID`);

--
-- Constraints for table `service_component`
--
ALTER TABLE `service_component`
  ADD CONSTRAINT `service_component_ibfk_1` FOREIGN KEY (`Service_ID`) REFERENCES `service` (`Service_ID`),
  ADD CONSTRAINT `service_component_ibfk_2` FOREIGN KEY (`Component_ID`) REFERENCES `component` (`Component_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
