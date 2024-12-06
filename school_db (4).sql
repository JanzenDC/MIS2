-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2024 at 09:16 PM
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
  `status` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `lrn`, `subject_id`, `first_grading`, `second_grading`, `third_grading`, `fourth_grading`, `final_grade`, `status`) VALUES
(409, '1231312213', 16, 90, 90, 90, 90, 90, 'Passed'),
(410, '1231312213', 17, 90, 90, 90, 90, 90, 'Passed'),
(411, '1231312213', 18, 90, 90, 90, 90, 90, 'Passed'),
(412, '1231312213', 19, 90, 90, 90, 90, 90, 'Passed'),
(413, '1231312213', 20, 90, 90, 90, 90, 90, 'Passed'),
(414, '1231312213', 21, 90, 90, 90, 90, 90, 'Passed'),
(415, '1231312213', 22, 90, 90, 90, 90, 90, 'Passed'),
(416, '1231312213', 23, 90, 90, 90, 90, 90, 'Passed'),
(441, '10129321312', 16, 90, 90, 90, 90, 90, 'Passed'),
(442, '10129321312', 17, 90, 90, 90, 90, 90, 'Passed'),
(443, '10129321312', 18, 90, 90, 90, 90, 90, 'Passed'),
(444, '10129321312', 19, 90, 90, 90, 90, 90, 'Passed'),
(445, '10129321312', 20, 90, 90, 90, 90, 90, 'Passed'),
(446, '10129321312', 21, 90, 90, 90, 90, 90, 'Passed'),
(447, '10129321312', 22, 90, 90, 90, 90, 90, 'Passed'),
(448, '10129321312', 23, 90, 90, 90, 90, 90, 'Passed');

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
  `status` enum('Approved','Pending') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `learners`
--

INSERT INTO `learners` (`id`, `lrn`, `first_name`, `last_name`, `dob`, `gender`, `student_type`, `school_attended`, `other_school`, `grade_level`, `guardian_name`, `guardian_relationship`, `other_guardian`, `curriculum`, `sf10_file`, `image_file`, `status`) VALUES
(47, '10129321312', 'Ronald', 'Junio', '0000-00-00', 'Male', 'Old', 'Aliaga Elementary School', '', 7, 'Romeo Junio', 'Parent', '', 'DepEd Matatag', 'uploads/sf10/Activity-1-in-Integrated-Program (1).pdf', 'uploads/images/lovier.jpg', 'Approved'),
(48, '1234454356', 'Kharl', 'Junio', '2024-10-15', 'Female', 'Old', 'Aliaga Elementary School', '', 7, 'Romeo Junio', 'Parent', '', 'DepEd Matatag', 'uploads/sf10/Activity-1-in-Integrated-Program (1).pdf', 'uploads/images/macayo_bg.jfif', 'Approved'),
(49, '13213153131', 'Kharl', 'Junio', '0000-00-00', 'Male', 'Old', 'Macayo Integrated School', '', 8, 'Romeo Junio', 'Parent', '', 'K-12', 'uploads/sf10/LESSON-5.pdf', 'uploads/images/1.png', 'Approved'),
(50, '1234124', 'Kharlsadas', 'Junio', '0000-00-00', 'Male', 'Old', 'Aliaga Elementary School', '', 9, 'Romeo Junio', 'Parent', '', 'K-12', 'uploads/sf10/LESSON-5.pdf', 'uploads/images/lovier.jpg', 'Approved'),
(51, '123412421321', 'Kharlsadas', 'Junio', '0000-00-00', 'Female', 'Old', 'Macayo Integrated School', '', 10, 'Romeo Junio', 'Grandparent', '', 'K-12', 'uploads/sf10/LESSON-4_merged.pdf', 'uploads/images/mvp.png', 'Approved'),
(52, '10112321313', 'Ronald', 'Junio', '2024-10-01', 'Female', 'Old', 'San Jose Elementary School', '', 11, 'Romeo Junio', 'Step Parent', 'Parent', 'K-12', 'uploads/sf10/Activity-1-in-Integrated-Program.pdf', 'uploads/images/luffy.jpg', 'Approved'),
(53, '10112321313123', 'Ronald', 'Junio', '2024-02-20', 'Female', 'Old', 'Macayo Integrated School', '', 12, 'Romeo Junio', 'Parent', 'Parent', 'K-12', 'uploads/sf10/LESSON-4.pdf', 'uploads/images/school_bg.jfif', 'Approved');

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
(1, 'K-12', 'Grade 11', 'First Sem', 'Oral Communication', 'Study of communication theories and practices.'),
(2, 'K-12', 'Grade 11', 'First Sem', 'Komunikasyon at Pananaliksik sa Wika at Kulturang Pilipino', 'Exploration of language and culture.'),
(3, 'K-12', 'Grade 11', 'First Sem', 'General Mathematics', 'Fundamentals of mathematics.'),
(4, 'K-12', 'Grade 11', 'First Sem', 'Earth and Life Science', 'Introduction to earth and life sciences.'),
(5, 'K-12', 'Grade 11', 'First Sem', 'Understanding Culture, Society, and Politics', 'Study of cultural, societal, and political dynamics.'),
(6, 'K-12', 'Grade 11', 'First Sem', 'Introduction to the Philosophy of the Human Person', 'Exploration of human existence and philosophy.'),
(7, 'K-12', 'Grade 12', 'First Sem', '21st Century Literature from the Philippine and the World', 'Study of contemporary literature.'),
(8, 'K-12', 'Grade 12', 'First Sem', 'Physical Science', 'Fundamentals of physical sciences.'),
(9, 'K-12', 'Grade 12', 'First Sem', 'Physical Education and Health 3', 'Physical education and health practices.'),
(10, 'K-12', 'Grade 12', 'First Sem', 'Practical Research 2', 'Research methods and practices.'),
(11, 'K-12', 'Grade 12', 'First Sem', 'Pagsulat na Filipino sa Larangan ng Akademik', 'Academic writing in Filipino.'),
(12, 'K-12', 'Grade 12', 'First Sem', 'Entrepreneurship', 'Principles of entrepreneurship.'),
(13, 'K-12', 'Grade 12', 'First Sem', 'Applied Economics', 'Economic principles applied to real-world situations.'),
(14, 'K-12', 'Grade 12', 'First Sem', 'Elective 1', 'Elective course.'),
(15, 'K-12', 'Grade 12', 'Second Sem', 'Contemporary Philippine Arts from the Region', 'Study of regional arts.'),
(16, 'K-12', 'Grade 12', 'Second Sem', 'Media and Information Literacy', 'Understanding media and information.'),
(17, 'K-12', 'Grade 12', 'Second Sem', 'Personal Development', 'Personal growth and development.'),
(18, 'K-12', 'Grade 12', 'Second Sem', 'Physical Education and Health 4', 'Further studies in physical education.'),
(19, 'K-12', 'Grade 12', 'Second Sem', 'Inquiries, Investigation and Immersion', 'Research and immersion practices.'),
(20, 'K-12', 'Grade 12', 'Second Sem', 'Disaster Readiness and Risk Reduction', 'Preparation for disasters.'),
(21, 'K-12', 'Grade 12', 'Second Sem', 'Elective 2', 'Elective course.'),
(22, 'K-12', 'Grade 12', 'Second Sem', 'Work Immersion', 'Hands-on work experience.'),
(23, 'K-12', 'Grade 11', 'Second Sem', 'Physical Education and Health 1', 'Introduction to physical education.'),
(24, 'K-12', 'Grade 11', 'Second Sem', 'Humanities 1 (Introduction to World Religion and Belief System)', 'Study of world religions and beliefs.'),
(25, 'K-12', 'Grade 11', 'Second Sem', 'Organization and Management', 'Fundamentals of organization and management.'),
(26, 'K-12', 'Grade 11', 'Second Sem', 'Reading and Writing Skills', 'Development of reading and writing skills.'),
(27, 'K-12', 'Grade 11', 'Second Sem', 'Pagbasa at Pagsusuri ng iba\'t ibang Teksto Tungo sa Pananaliksik', 'Reading and analysis for research.'),
(28, 'K-12', 'Grade 11', 'Second Sem', 'Statics and Probability', 'Fundamentals of statistics and probability.'),
(29, 'K-12', 'Grade 11', 'Second Sem', 'Physical Education and Health 2', 'Continuation of physical education studies.'),
(30, 'K-12', 'Grade 11', 'Second Sem', 'English for Academic and Professional Purposes', 'English skills for academic and professional use.'),
(31, 'K-12', 'Grade 11', 'Second Sem', 'Practical Research 1', 'Introduction to research methods.'),
(32, 'K-12', 'Grade 11', 'Second Sem', 'Empowerment Technologies (E-Tech) ICT or GAS', 'Introduction to technology in education.'),
(33, 'K-12', 'Grade 11', 'Second Sem', 'Humanities 2 (Trends Network and Critical Thinking in the 21st Century Culture)', 'Critical thinking and cultural trends.'),
(34, 'K-12', 'Grade 11', 'Second Sem', 'Social Science 1 (Philippine Politics and Governance)', 'Study of Philippine politics and governance.');

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
(16, 'K-12', 'Filipino', 'Study of the Filipino language and literature', '2024-10-01 11:38:00'),
(17, 'K-12', 'English', 'Study of the English language and literature', '2024-10-01 11:38:00'),
(18, 'K-12', 'Mathematics', 'Study of numbers, shapes, and patterns', '2024-10-01 11:38:00'),
(19, 'K-12', 'Science', 'Study of the natural and physical sciences', '2024-10-01 11:38:00'),
(20, 'K-12', 'Araling Panlipunan (AP)', 'Study of social studies and history', '2024-10-01 11:38:00'),
(21, 'K-12', 'Edukasyon sa Pagpapakatao (EsP)', 'Education in human values and character', '2024-10-01 11:38:00'),
(22, 'K-12', 'Technology and Livelihood Education (TLE)', 'Study of practical skills for living and earning', '2024-10-01 11:38:00'),
(23, 'K-12', 'MAPEH', 'Music, Arts, Physical Education, and Health', '2024-10-01 11:38:00');

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
(2, 'kharl@gmail.com', '$2y$10$TZQWuUNUKukEc5VY3lwYHOLI9Lg1eWFoMop4mwjmfUEUNyK67/bnW', 'admin', '2024-10-12 10:03:51'),
(3, 'admin@example.com', '$2y$10$luuyGtA1u6WI0rBUi7kGJ.pDEIdHu.x6QpPMIBOl/HxYZkGPB3pg2', 'teacher', '2024-10-14 14:32:56');

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
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=473;

--
-- AUTO_INCREMENT for table `learners`
--
ALTER TABLE `learners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `shs_subjects`
--
ALTER TABLE `shs_subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
