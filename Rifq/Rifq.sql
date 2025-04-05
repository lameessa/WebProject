-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 05, 2025 at 05:51 PM
-- Server version: 5.7.24
-- PHP Version: 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rifq`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `id` int(11) NOT NULL,
  `PatientID` int(11) NOT NULL,
  `DoctorID` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `time` time NOT NULL,
  `reason` text NOT NULL,
  `status` enum('Pending','Confirmed','Done') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`id`, `PatientID`, `DoctorID`, `date`, `time`, `reason`, `status`) VALUES
(1, 1234, 1, '2025-02-14 00:00:00', '10:00:00', 'Routine check-up', 'Confirmed'),
(2, 1234, 2, '2025-05-20 00:00:00', '15:00:00', 'Follow-up', 'Pending'),
(3, 1235, 3, '2025-07-16 00:00:00', '17:00:00', 'Flu symptoms', 'Done');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `uniqueFileName` varchar(255) NOT NULL,
  `SpecialityID` int(11) NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`id`, `firstName`, `lastName`, `uniqueFileName`, `SpecialityID`, `emailAddress`, `password`) VALUES
(1, 'Saleh', 'Abdullah', 'doctor1.png', 1, 'saleh@example.com', '$2y$10$tPrZk4bpvSp8v4Gea8ML0OnkkI9BeXzIH7DKGhnF/BaPK7tz/XNpa'),
(2, 'Sara', 'Ahmad', 'doctor2.png', 2, 'sara@example.com', '$2y$10$6eWvRp63ikytGEdnhq5W7uPsk7nTf3L6XN4Gpe6KKaXvoU07UYxje'),
(3, 'Lulu', 'Mohamed', 'doctor3.png', 3, 'lulu@example.com', '$2y$10$2qFb07zK.LtHcNFpDCpefuEvOKPOp2FOmXJAPuZ.PvYzk9CnME1uK');

-- --------------------------------------------------------

--
-- Table structure for table `medication`
--

CREATE TABLE `medication` (
  `id` int(11) NOT NULL,
  `MedicationName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `medication`
--

INSERT INTO `medication` (`id`, `MedicationName`) VALUES
(1, 'Amoxicillin Clavulanic Acid'),
(2, 'Carprofen'),
(3, 'Furosemide'),
(4, 'Prednisone Prednisolone');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `Gender` enum('Male','Female') NOT NULL,
  `DoB` date NOT NULL,
  `emailAddress` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`id`, `firstName`, `lastName`, `Gender`, `DoB`, `emailAddress`, `password`) VALUES
(1234, 'Blue', 'Barron', 'Male', '2014-01-01', 'email@example.com', '$2y$10$y6hsAiMbFtLUUtPzKPv6QO.Zhn6rPlVq34kg5Xf6WdMkz0fxZ1t7C'),
(1235, 'Meme', 'Smith', 'Female', '2024-02-23', 'meme@example.com', '$2y$10$3e7UZR/5dNzgo7.W0HCT6Od4FRdu1f8XNsk8fokT5Oc.AAx1N38OO'),
(1236, 'Koko', 'Ali', 'Male', '2020-07-16', 'koko@example.com', '$2y$10$q0k0s9Et5Y3TYuOE5ezZeuND8BkrCG2Eoqt14AhUhqLaZguQeIbPe');

-- --------------------------------------------------------

--
-- Table structure for table `prescription`
--

CREATE TABLE `prescription` (
  `id` int(11) NOT NULL,
  `AppointmentID` int(11) NOT NULL,
  `MedicationID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `prescription`
--

INSERT INTO `prescription` (`id`, `AppointmentID`, `MedicationID`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3);

-- --------------------------------------------------------

--
-- Table structure for table `speciality`
--

CREATE TABLE `speciality` (
  `id` int(11) NOT NULL,
  `speciality` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `speciality`
--

INSERT INTO `speciality` (`id`, `speciality`) VALUES
(1, 'General Medicine'),
(2, 'Pediatrics'),
(3, 'Cardiology'),
(4, 'Neurology');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `PatientID` (`PatientID`),
  ADD KEY `DoctorID` (`DoctorID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`),
  ADD KEY `SpecialityID` (`SpecialityID`);

--
-- Indexes for table `medication`
--
ALTER TABLE `medication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emailAddress` (`emailAddress`);

--
-- Indexes for table `prescription`
--
ALTER TABLE `prescription`
  ADD PRIMARY KEY (`id`),
  ADD KEY `AppointmentID` (`AppointmentID`),
  ADD KEY `MedicationID` (`MedicationID`);

--
-- Indexes for table `speciality`
--
ALTER TABLE `speciality`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `speciality`
--
ALTER TABLE `speciality`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`PatientID`) REFERENCES `patient` (`id`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`DoctorID`) REFERENCES `doctor` (`id`);

--
-- Constraints for table `doctor`
--
ALTER TABLE `doctor`
  ADD CONSTRAINT `doctor_ibfk_1` FOREIGN KEY (`SpecialityID`) REFERENCES `speciality` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prescription`
--
ALTER TABLE `prescription`
  ADD CONSTRAINT `prescription_ibfk_1` FOREIGN KEY (`AppointmentID`) REFERENCES `appointment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prescription_ibfk_2` FOREIGN KEY (`MedicationID`) REFERENCES `medication` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
