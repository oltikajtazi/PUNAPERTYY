-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2025 at 08:04 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dboltii`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `cv_text` text NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `cover_letter` text DEFAULT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `applied_at` varchar(255) DEFAULT NULL,
  `job_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `job_title`, `full_name`, `email`, `cv_text`, `phone`, `cover_letter`, `submitted_at`, `applied_at`, `job_id`, `status`) VALUES
(1, 1, 'Inxhinier i Inteligjencës Artificiale', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'ccdfcfvfv', '2025-05-24 13:50:37', NULL, NULL, 'pending'),
(2, 1, 'Inxhinier i Inteligjencës Artificiale', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'ddvfvfvf', '2025-05-24 13:52:17', NULL, NULL, 'pending'),
(3, 2, 'dizajn grefik', 'jona kuka', 'kajtaziolti0@gmail.com', '', '5769864', 'ghfgbcvb', '2025-05-24 13:53:13', NULL, NULL, 'pending'),
(4, 1, 'Krijues i Përmbajtjes Digjitale', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'nvjhvjh', '2025-05-24 14:01:45', NULL, NULL, 'pending'),
(5, 1, 'Analist i Sigurisë Kibernetike', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'hvjhhhhhhhhhh', '2025-05-24 14:05:54', NULL, NULL, 'pending'),
(6, 2, 'Analist i Sigurisë Kibernetike', 'jona kuka', 'jona@gmail.com', '', '5769864', 'kfnvjfnvjvnfj', '2025-05-24 14:21:22', NULL, NULL, 'pending'),
(7, 2, 'dizajn grefik', 'jona kuka', 'jona@gmail.com', '', '3564745', 'ijijjjjjjjjj', '2025-05-24 14:22:17', NULL, NULL, 'pending'),
(8, 2, 'Inxhinier i Inteligjencës Artificiale', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'uigjju', '2025-05-24 17:01:48', NULL, NULL, 'pending'),
(9, 2, 'dizajn grefik', 'jona kuka', 'jona@gmail.com', '', '5769864', 'uyfvjvnhb ', '2025-05-24 17:02:18', NULL, NULL, 'pending'),
(10, 2, 'dizajn grefik', 'olti kajtazi', 'jona@gmail.com', '', '3564745', 'kgiguogjno', '2025-05-25 08:32:34', NULL, NULL, 'pending'),
(11, 2, 'dizajn grefik', 'jona kuka', 'jona@gmail.com', '', '5769864', 'jhkvb///////////', '2025-05-25 08:44:15', NULL, NULL, 'pending'),
(12, 2, 'dizajn grefik', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'tudfctygjtesdfgh,.;l', '2025-05-25 08:53:37', NULL, NULL, 'pending'),
(13, 2, 'dizajn grefik', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'tudfctygjtesdfgh,.;l', '2025-05-25 08:55:31', NULL, NULL, 'pending'),
(14, 1, 'dizajn grefik', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'bgrgvdc', '2025-05-25 09:06:27', NULL, NULL, 'pending'),
(22, 1, 'kamarier', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '3564745', 'db njdkcxl kxcj', '2025-05-25 16:03:24', NULL, 8, 'pending'),
(23, 2, 'kuzhiner', 'jona kuka', 'jona@gmail.com', '', '849302345748', 'dsfvhidbjz,LFBVHDSJIQKOAGHC JN', '2025-05-25 18:58:42', NULL, 13, 'declined'),
(24, 2, 'fjovnzjkcx v', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '5769864', 'hkvvvvkjbvnm', '2025-05-25 20:01:44', NULL, 23, 'declined'),
(25, 3, 'fjovnzjkcx v', 'suela kajtazi', 'suelakajtazi44@gmail.com', '', '045 292 481', 'i need this job', '2025-05-25 20:14:34', NULL, 23, 'accepted'),
(26, 2, 'fjovnzjkcx v', 'jona kuka', 'jona@gmail.com', '', '748398373383', 'sodjivslkmclkn m,xc ', '2025-05-25 20:48:58', NULL, 23, 'declined'),
(27, 3, 'fjovnzjkcx v', 'suela kajtazi', 'suelakajtazi44@gmail.com', '', '45292481', 'kwncdlscsdklcnscdk', '2025-05-25 21:54:09', NULL, 23, 'accepted'),
(28, 2, 'menaxher', 'olti kajtazi', 'kajtaziolti35@gmail.com', '', '044814971', 'me pelqen kjo pun', '2025-05-26 10:43:56', NULL, 28, 'accepted'),
(29, 1, 'menaxher', 'olti kajtazi', 'kajtaziolti0@gmail.com', '', '0443564745', 'jknkl', '2025-05-26 10:56:55', NULL, 28, 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookmarks`
--

INSERT INTO `bookmarks` (`id`, `user_id`, `job_id`, `created_at`) VALUES
(1, 1, 23, '2025-05-25 22:40:52'),
(2, 1, 22, '2025-05-25 22:48:12'),
(6, 1, 21, '2025-05-25 23:29:06'),
(7, 1, 28, '2025-05-26 12:44:48'),
(8, 3, 30, '2025-05-28 13:19:43'),
(9, 4, 30, '2025-06-05 20:01:35');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `user_id`, `name`, `description`, `logo`, `created_at`) VALUES
(1, 1, 'olti', '', NULL, '2025-05-26 00:31:52'),
(2, 3, 'suela', 'dofivhnldkfvndlf', NULL, '2025-05-26 15:10:09');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `city` varchar(50) NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'open',
  `phone` varchar(30) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `contact` varchar(9) DEFAULT NULL,
  `category` int(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `salary` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `user_id`, `title`, `city`, `status`, `phone`, `description`, `created_at`, `contact`, `category`, `image`, `salary`) VALUES
(1, 1, 'dizajn grefik', '', 'open', NULL, 'duhcdceucfhe', '2025-05-24 12:31:33', NULL, NULL, NULL, ''),
(2, 2, 'menagjer', '', 'open', NULL, 'menagjer idepos', '2025-05-25 11:50:07', NULL, NULL, NULL, ''),
(3, 2, 'ingjeniere', '', 'open', NULL, 'yufkjmhbifch', '2025-05-25 12:08:51', '045886502', NULL, NULL, ''),
(4, 1, 'kuzhinjer', '', 'open', NULL, 'mghcnvhmng', '2025-05-25 12:10:24', '044748946', NULL, NULL, ''),
(6, 1, 'shofer', '', 'open', NULL, 'jvbhkkj,', '2025-05-25 13:20:46', '044876987', NULL, NULL, ''),
(10, 1, 'jdfvnjdk', '', 'open', NULL, 'tgrtbfvb', '2025-05-25 18:39:26', NULL, NULL, NULL, ''),
(11, 1, 'sfvsf', '', 'open', NULL, 'sldjvncjkvxc', '2025-05-25 18:41:00', NULL, NULL, NULL, ''),
(12, 1, 'dncdjcndjc', '', 'open', NULL, 'jchn jx', '2025-05-25 18:42:28', NULL, NULL, NULL, ''),
(13, 1, 'kuzhiner', '', 'open', NULL, 'dijcdcnmxs', '2025-05-25 18:48:20', NULL, NULL, NULL, ''),
(16, 1, 'inxhinier', '', 'open', NULL, 'idjnjidnvkxv', '2025-05-25 19:25:41', NULL, NULL, NULL, ''),
(17, 1, 'sygfbvcsu', '', 'open', NULL, 'ijk usfbvchkzxjn gzxcubh jk', '2025-05-25 19:28:22', NULL, NULL, NULL, ''),
(18, 1, '\\cdddddddddddd', '', 'open', NULL, 'efgdzvvvvvvv', '2025-05-25 19:29:56', NULL, NULL, NULL, ''),
(19, 1, 'kamarjer', '', 'open', NULL, 'fvfjvn', '2025-05-25 19:32:38', NULL, NULL, NULL, ''),
(20, 1, 'kamrjer', '', 'open', NULL, 'gbjhhj', '2025-05-25 19:35:59', NULL, NULL, 'uploads/job_6833711f84baf4.67820262.png', ''),
(21, 1, 'kamrjer', '', 'open', NULL, 'kcvnxcvjnlxcmv x', '2025-05-25 19:50:05', NULL, NULL, 'uploads/job_6833746df0e311.91778762.png', ''),
(22, 1, 'jisfvnjv', 'prishtin', 'open', NULL, 'snkclv mn jkx', '2025-05-25 19:54:02', NULL, NULL, 'uploads/job_6833755a532b27.14189628.png', ''),
(23, 1, 'fjovnzjkcx v', 'prizren', 'open', NULL, 'kdjbvjkcb zxc', '2025-05-25 20:00:16', NULL, NULL, 'uploads/job_683376d05f7194.08061441.png', ''),
(24, 1, 'web developer', 'gjilan', 'open', '+3830443564745', 'cdbuhjdnckc', '2025-05-25 21:31:49', NULL, NULL, NULL, ''),
(25, 1, 'web developer', 'gjilan', 'open', '+3830443564745', 'cdbuhjdnckc', '2025-05-25 21:36:16', NULL, NULL, NULL, ''),
(26, 1, 'dizajner', 'prishtin', 'open', '+383043567890', 'lisfnkjkcvlkc bnnk', '2025-05-25 21:41:05', NULL, NULL, NULL, ''),
(27, 1, 'dizajner', 'prishtin', 'open', '+383043567890', 'lisfnkjkcvlkc bnnk', '2025-05-25 21:42:16', NULL, NULL, NULL, ''),
(28, 1, 'menaxher', 'vushtrri', 'open', '+3833564745', 'menaxher i nje  depoje te mobileve', '2025-05-26 10:41:44', NULL, NULL, NULL, ''),
(29, 1, 'ldfjvndkjv', 'prizren', 'open', '+3830443564745', 'klcnadcsljdc', '2025-05-26 11:10:35', NULL, NULL, NULL, ''),
(30, 3, 'arkatare', 'prishtin', 'open', '+3830443564745', 'sdjilhvosfhvifbvd', '2025-05-26 13:10:31', NULL, NULL, NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `job_alerts`
--

CREATE TABLE `job_alerts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `keyword` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `is_read`, `sent_at`) VALUES
(1, 1, 2, 'hi', 1, '2025-05-26 12:49:46'),
(2, 2, 1, 'sije', 1, '2025-05-26 12:51:23'),
(3, 1, 2, 'sfgbvndoifh jkkxcn djk ndfi vd', 1, '2025-05-26 15:08:44'),
(4, 2, 3, 'kuch skjdl jbdh', 1, '2025-05-26 15:09:19'),
(5, 1, 3, 'jvbkjhjkb', 1, '2025-05-27 21:55:15'),
(6, 3, 1, 'sfkjvndfkvdflmv', 1, '2025-05-28 13:20:02'),
(7, 4, 1, 'jfvjhkb b j', 1, '2025-06-05 20:01:52');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` varchar(255) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `full_name`, `password`, `created_at`) VALUES
(1, 'olti', 'kajtaziolti0@gmail.com', NULL, '$2y$10$h9RwM9J8fa28OIx8tvw0du/nJ566cYLCFQXgUYry4M6dGdPK86tyq', '2025-05-24 12:18:18'),
(2, 'jona', 'jona@gmail.com', NULL, '$2y$10$riXTkiO4JmjS.6QCVGqOseSH7GvwmFokg0Pj1HBgM0wP6yz8whfeC', '2025-05-24 12:45:01'),
(3, 'suela', 'suelakajtazi44@gmail.com', NULL, '$2y$10$o8VMfcJqPnTUDEUhwc3KceFPX4LJyjPS.N0EHZ.qFylbo8pn8sE7y', '2025-05-25 20:13:22'),
(4, 'donjeta', 'donjeta@gmail.com', NULL, '$2y$10$fC825OJP/LOQnenFWF.b..zWc7hX9zv1YodO3BhmxTwLGHvyRFa8S', '2025-06-05 18:00:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`job_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `job_alerts`
--
ALTER TABLE `job_alerts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `job_alerts`
--
ALTER TABLE `job_alerts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
