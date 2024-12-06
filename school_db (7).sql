-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 11:43 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `lrn` varchar(50) DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  `first_grading` float DEFAULT NULL,
  `second_grading` float DEFAULT NULL,
  `third_grading` float DEFAULT NULL,
  `fourth_grading` float DEFAULT NULL,
  `final_grade` float DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `general_average` float DEFAULT NULL,
  `adviser` varchar(100) DEFAULT NULL,
  `school_year` varchar(20) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `lrn`, `subject_id`, `first_grading`, `second_grading`, `third_grading`, `fourth_grading`, `final_grade`, `status`, `general_average`, `adviser`, `school_year`, `section`) VALUES
(924, '123', 52, 90, 90, 90, 90, 90, 'Passed', 90, 'Brennan S. Barnacha', '2017-2018', 'Acasia'),
(925, '123', 53, 90, 90, 90, 90, 90, 'Passed', 90, 'Brennan S. Barnacha', '2017-2018', 'Acasia'),
(926, '123', 54, 90, 90, 90, 90, 90, 'Passed', 90, 'Brennan S. Barnacha', '2017-2018', 'Acasia');

-- --------------------------------------------------------

--
-- Table structure for table `learners`
--

CREATE TABLE `learners` (
  `id` int(11) NOT NULL,
  `lrn` varchar(20) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `gender` enum('Male','Female') DEFAULT NULL,
  `student_type` enum('Old','New Transferee') DEFAULT NULL,
  `school_attended` varchar(255) DEFAULT NULL,
  `other_school` varchar(255) DEFAULT NULL,
  `grade_level` int(11) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relationship` varchar(50) DEFAULT NULL,
  `other_guardian` varchar(255) DEFAULT NULL,
  `curriculum` varchar(50) DEFAULT NULL,
  `sf10_file` varchar(255) DEFAULT NULL,
  `image_file` varchar(255) DEFAULT NULL,
  `status` enum('Approved','Pending') DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `name_extension` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learners`
--

INSERT INTO `learners` (`id`, `lrn`, `first_name`, `last_name`, `dob`, `gender`, `student_type`, `school_attended`, `other_school`, `grade_level`, `guardian_name`, `guardian_relationship`, `other_guardian`, `curriculum`, `sf10_file`, `image_file`, `status`, `middle_name`, `name_extension`) VALUES
(63, '123', 'Ronald', 'Junio', '2001-08-30', 'Male', 'Old', 'San Pablo Elementary School', '', 7, 'Romeo Junio', 'Step Parent', 'sadasdas', 'DepEd Matatag', 'uploads/sf10/LESSON-5.pdf', 'uploads/images/lovier.jpg', 'Approved', NULL, NULL),
(91, '13234', 'ROnald', 'Junio', '2024-11-19', 'Female', 'Old', 'Macayo Integrated School', '', 11, '123123', 'Parent', '', 'K-12', 'uploads/sf10/Activity-1-in-Integrated-Program (1).pdf', 'uploads/images/1.png', 'Approved', 'C.', 'Jr.');

-- --------------------------------------------------------

--
-- Table structure for table `shs_grades`
--

CREATE TABLE `shs_grades` (
  `id` int(11) NOT NULL,
  `lrn` varchar(50) DEFAULT NULL,
  `subject_id` int(11) NOT NULL,
  `first_grading` float DEFAULT NULL,
  `second_grading` float DEFAULT NULL,
  `third_grading` float DEFAULT NULL,
  `fourth_grading` float DEFAULT NULL,
  `final_grade` float DEFAULT NULL,
  `status` varchar(10) DEFAULT NULL,
  `general_average` decimal(5,2) DEFAULT NULL,
  `adviser` varchar(100) DEFAULT NULL,
  `school_year` varchar(20) DEFAULT NULL,
  `section` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shs_grades`
--

INSERT INTO `shs_grades` (`id`, `lrn`, `subject_id`, `first_grading`, `second_grading`, `third_grading`, `fourth_grading`, `final_grade`, `status`, `general_average`, `adviser`, `school_year`, `section`) VALUES
(635, '13234', 45, 90, 90, 78, 78, 84, 'Passed', 84.00, 'Brennan S. Barnacha', '2017-2019', 'Acasia'),
(636, '13234', 46, 90, 90, 78, 78, 84, 'Passed', 84.00, 'Brennan S. Barnacha', '2017-2019', 'Acasia');

-- --------------------------------------------------------

--
-- Table structure for table `shs_subjects`
--

CREATE TABLE `shs_subjects` (
  `id` int(11) NOT NULL,
  `curriculum` varchar(255) NOT NULL,
  `grade_level` varchar(10) NOT NULL,
  `semester` varchar(10) NOT NULL,
  `subject_name` varchar(255) NOT NULL,
  `subject_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shs_subjects`
--

INSERT INTO `shs_subjects` (`id`, `curriculum`, `grade_level`, `semester`, `subject_name`, `subject_description`) VALUES
(45, 'DepEd Matatag', '12', '2', 'Araling Panlipunan', 'asdasd'),
(46, 'DepEd Matatag', '11', '2', 'sadsad', 'dasdasdsadas');

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `curriculum` varchar(50) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `subject_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `curriculum`, `subject_name`, `subject_description`, `created_at`) VALUES
(52, 'k12', 'Araling Panlipunan', 'Introduction to research methods', '2024-10-22 01:14:01'),
(53, 'DepEd Matatag', 'Araling Panlipunan', 'Introduction to research methods', '2024-10-22 01:14:12'),
(54, 'DepEd Matatag', 'Science', 'Introduction to research methods.asdasd', '2024-10-22 01:14:21');

-- --------------------------------------------------------

--
-- Table structure for table `total_grades_subjects`
--

CREATE TABLE `total_grades_subjects` (
  `lrn` int(30) NOT NULL,
  `subjects` int(20) NOT NULL,
  `1ST_GRADING` varchar(10) NOT NULL,
  `2ND_GRADING` varchar(10) NOT NULL,
  `3RD_GRADING` varchar(10) NOT NULL,
  `4TH_GRADING` varchar(10) NOT NULL,
  `FINAL_GRADES` varchar(10) NOT NULL,
  `PASSED_FAILED` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','ict_faculty','teacher') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'junioronald13@gmail.com', '$2y$10$7IJfCtXc1JVLnicy.IN2PuQyKvEu/7LQ9AZ2OZZdzps5H3mw4z.kW', 'ict_faculty', '2024-10-12 10:03:15'),
(2, 'kharl@gmail.com', '$2y$10$TZQWuUNUKukEc5VY3lwYHOLI9Lg1eWFoMop4mwjmfUEUNyK67/bnW', 'admin', '2024-10-12 10:03:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_grade` (`lrn`,`subject_id`),
  ADD KEY `fk_subject` (`subject_id`);

--
-- Indexes for table `learners`
--
ALTER TABLE `learners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lrn` (`lrn`);

--
-- Indexes for table `shs_grades`
--
ALTER TABLE `shs_grades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_grade` (`lrn`,`subject_id`),
  ADD KEY `fk_subject` (`subject_id`);

--
-- Indexes for table `shs_subjects`
--
ALTER TABLE `shs_subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `total_grades_subjects`
--
ALTER TABLE `total_grades_subjects`
  ADD PRIMARY KEY (`lrn`,`subjects`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=987;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `shs_grades`
--
ALTER TABLE `shs_grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=645;

--
-- AUTO_INCREMENT for table `shs_subjects`
--
ALTER TABLE `shs_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
