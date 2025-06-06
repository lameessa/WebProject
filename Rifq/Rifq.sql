-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2025 at 12:07 PM
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
(4, 1122, 2244, '2025-05-20 00:00:00', '15:30:00', 'Routine monthly checkup', 'Pending'),
(5, 1122, 1001, '2025-06-15 00:00:00', '12:00:00', 'Concern about unusual movements or behavior', 'Confirmed'),
(6, 3030, 1001, '2025-05-25 00:00:00', '14:45:00', 'Worried about balance and head tilting', 'Done'),
(7, 3030, 3303, '2025-06-20 00:00:00', '15:00:00', 'Worried about lack of energy and appetite.', 'Pending'),
(8, 1114, 2244, '2025-05-19 00:00:00', '10:30:00', 'Routine check-up and vaccine update', 'Confirmed');

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
(1001, 'Saleh', 'Abdullah', '68062273103737.02198081.png', 4, 'saleh@gmail.com', '$2y$10$KppHtlTkK1fe5MdL4VzcGuzVXoXUPnPI7Bcnuf7uVyws4ByCHiPP2'),
(2244, 'Sara', 'Ahmad', '68062351110dc9.73994000.png', 1, 'sara@gmail.com', '$2y$10$/c9i/ZXpesNAANaXHvHD.OFDeKOPQ90a2MzFeEX9wr0VF9CN7mrg.'),
(3303, 'Lulu', 'Mohammad', '680623a0b27de4.84531770.png', 2, 'lulu@gmail.com', '$2y$10$YlynlBVBYcqa2t.0bKozoeqsr1TNK88zQpsmkILQGI6snJBHdbZIq');

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
(1114, 'Coco', 'Ali', 'Female', '2022-07-16', 'coco@gmail.com', '$2y$10$/VMLK5quPkm7/ORAzJXv4e69SKVSerpf914C4F6iaoDHSLH4C8Drm'),
(1122, 'Blue', 'Barron', 'Male', '2014-01-01', 'blue@gmail.com', '$2y$10$1bkqIlI./Ba7AaPxHJ9Wi.NRqe3SG7Ku7fhLLP0bVI0jdAFrBJXAi'),
(3030, 'Meme', 'Smith', 'Female', '2024-02-23', 'meme@gmail.com', '$2y$10$doixKG5g/aKbbIVtq6sDcuAHj4v8MJPs8ElmxQsqJW0vbrT/k6aFC');

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
(4, 6, 2),
(5, 6, 3);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medication`
--
ALTER TABLE `medication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prescription`
--
ALTER TABLE `prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
