-- Database: school_project

-- Users table
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin','teacher','student','parent') NOT NULL,
  `email` VARCHAR(100),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Classes table
CREATE TABLE `classes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class_name` VARCHAR(50) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Sections table
CREATE TABLE `sections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `class_id` INT,
  `section_name` VARCHAR(10) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`class_id`) REFERENCES classes(`id`) ON DELETE CASCADE
);

-- Students table
CREATE TABLE `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT,
  `name` VARCHAR(100) NOT NULL,
  `gender` ENUM('male','female','other'),
  `dob` DATE,
  `class_id` INT,
  `section_id` INT,
  `roll_no` VARCHAR(20),
  `parent_name` VARCHAR(100),
  `parent_contact` VARCHAR(20),
  `address` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES users(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`class_id`) REFERENCES classes(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`section_id`) REFERENCES sections(`id`) ON DELETE SET NULL
);

-- Teachers table
CREATE TABLE `teachers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100),
  `contact` VARCHAR(20),
  `address` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES users(`id`) ON DELETE SET NULL
);

-- Subjects table
CREATE TABLE `subjects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_name` VARCHAR(100) NOT NULL,
  `class_id` INT,
  `teacher_id` INT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`class_id`) REFERENCES classes(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`teacher_id`) REFERENCES teachers(`id`) ON DELETE SET NULL
);

-- Attendance table
CREATE TABLE IF NOT EXISTS `attendance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `status` enum('Present','Absent','Leave') NOT NULL DEFAULT 'Present',
  `lecture` int(11) NOT NULL DEFAULT 1,
  `class_id` int(11) DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `class_id` (`class_id`),
  KEY `section_id` (`section_id`),
  CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `attendance_ibfk_4` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add sample attendance data
INSERT INTO `attendance` (`student_id`, `date`, `status`, `lecture`, `class_id`, `section_id`) VALUES
(1, CURDATE(), 'Present', 1, 1, 1),
(2, CURDATE(), 'Absent', 1, 1, 1),
(3, CURDATE(), 'Present', 2, 2, 2);

INSERT INTO `attendance` (`teacher_id`, `date`, `status`, `lecture`) VALUES
(1, CURDATE(), 'Present', 1),
(2, CURDATE(), 'Present', 2);

-- Holidays table
CREATE TABLE `holidays` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `holiday_date` DATE NOT NULL,
  `description` VARCHAR(255),
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Leaves table
CREATE TABLE `leaves` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` INT,
  `from_date` DATE NOT NULL,
  `to_date` DATE NOT NULL,
  `reason` TEXT,
  `status` ENUM('pending','approved','rejected') DEFAULT 'pending',
  `applied_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`student_id`) REFERENCES students(`id`) ON DELETE CASCADE
);

-- Language table
CREATE TABLE `language` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `lang_key` VARCHAR(100) NOT NULL,
  `english` VARCHAR(255) NOT NULL,
  `gujarati` VARCHAR(255) NOT NULL
); 