-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 08, 2025 at 10:21 AM
-- Server version: 9.1.0
-- PHP Version: 8.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `incredidose`
--

-- --------------------------------------------------------

--
-- Table structure for table `prescriptionitem`
--

DROP TABLE IF EXISTS `prescriptionitem`;
CREATE TABLE IF NOT EXISTS `prescriptionitem` (
  `prescriptionitemid` int NOT NULL AUTO_INCREMENT,
  `prescriptionid` int NOT NULL,
  `name` varchar(35) NOT NULL,
  `brand` varchar(35) NOT NULL,
  `quantity` int NOT NULL,
  `dosage` varchar(10) NOT NULL,
  `frequency` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `substitutions` tinyint(1) NOT NULL,
  PRIMARY KEY (`prescriptionitemid`,`prescriptionid`),
  KEY `prescprescitem` (`prescriptionid`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `prescriptionitem`
--

INSERT INTO `prescriptionitem` (`prescriptionitemid`, `prescriptionid`, `name`, `brand`, `quantity`, `dosage`, `frequency`, `description`, `substitutions`) VALUES
(1, 22, 'Amoxicillin', 'Amoxil', 30, '500mg', 3, 'Take one capsule three times daily after meals', 0),
(2, 22, 'Paracetamol', 'Generic', 0, '500mg', 4, 'Take as needed for fever or pain, maximum 4 times daily', 1),
(3, 23, 'Cetirizine', 'Zyrtec', 15, '10mg', 1, 'Take one tablet daily at bedtime', 1),
(4, 23, 'Salbutamol', 'Ventolin', 1, '100mcg', 4, 'Inhaler - Use as needed for asthma symptoms', 0),
(5, 24, 'Clobetasol', 'Clobex', 1, '0.05%', 2, 'Topical cream - Apply thin layer to affected area twice daily', 0),
(6, 25, 'Atorvastatin', 'Lipitor', 30, '20mg', 1, 'Take one tablet daily at bedtime', 0),
(7, 25, 'Aspirin', 'Ecotrin', 30, '81mg', 1, 'Take one tablet daily in the morning', 1),
(8, 26, 'Metformin', 'Glucophage', 60, '500mg', 2, 'Take one tablet twice daily with meals', 1),
(9, 27, 'Prenatal Vitamins', 'Pregna', 30, 'Once daily', 1, 'Take one capsule daily with breakfast', 0),
(10, 27, 'Ferrous Sulfate', 'FeroSul', 30, '325mg', 1, 'Take one tablet daily', 1),
(11, 28, 'Gabapentin', 'Neurontin', 90, '300mg', 3, 'Take one capsule three times daily', 0),
(12, 29, 'Ibuprofen', 'Advil', 30, '400mg', 3, 'Take one tablet every 6 hours as needed for pain', 1),
(13, 29, 'Acetaminophen', 'Tylenol', 20, '500mg', 4, 'Take as needed for pain relief', 1),
(14, 30, 'Cephalexin', 'Keflex', 40, '500mg', 4, 'Take one capsule four times daily', 0),
(15, 31, 'Lisinopril', 'Generic', 30, '10mg', 1, 'Take one tablet daily in the morning', 0),
(16, 32, 'Azithromycin', 'Zithromax', 6, '250mg', 1, 'Take two tablets on first day, then one daily for 4 days', 0),
(17, 33, 'Loratadine', 'Claritin', 20, '10mg', 1, 'Take one tablet daily', 1),
(18, 33, 'Dextromethorphan', 'Robitussin', 1, '15mg/5ml', 4, 'Syrup - Take 10ml every 6 hours as needed for cough', 1),
(19, 34, 'Hydrocortisone', 'Cortizone', 1, '1%', 3, 'Cream - Apply to affected area three times daily', 1),
(20, 35, 'Simvastatin', 'Zocor', 30, '20mg', 1, 'Take one tablet daily at bedtime', 0),
(21, 36, 'Metoprolol', 'Lopressor', 60, '50mg', 2, 'Take one tablet twice daily', 0),
(22, 36, 'Furosemide', 'Lasix', 30, '40mg', 1, 'Take one tablet daily in the morning', 0),
(23, 37, 'Prenatal Calcium', 'Caltrate', 60, '600mg', 2, 'Take one tablet twice daily', 1),
(24, 38, 'Pregabalin', 'Lyrica', 60, '75mg', 2, 'Take one capsule twice daily', 0),
(25, 39, 'Naproxen', 'Aleve', 30, '500mg', 2, 'Take one tablet twice daily with food', 1),
(26, 40, 'Ciprofloxacin', 'Cipro', 20, '500mg', 2, 'Take one tablet twice daily', 0),
(27, 41, 'Omeprazole', 'Prilosec', 30, '20mg', 1, 'Take one capsule daily before breakfast', 1),
(28, 41, 'Multivitamin', 'Centrum', 30, 'Once daily', 1, 'Take one tablet daily', 1),
(30, 22, 'Fent', 'Generic', 4, '10mg', 5, 'Bringing Japanese Culture to the Streets of Vancouver', 1);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prescriptionitem`
--
ALTER TABLE `prescriptionitem`
  ADD CONSTRAINT `prescprescitem` FOREIGN KEY (`prescriptionid`) REFERENCES `prescription` (`prescriptionid`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
