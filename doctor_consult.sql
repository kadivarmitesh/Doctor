-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2019 at 10:15 AM
-- Server version: 10.1.29-MariaDB
-- PHP Version: 7.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `doctor_consult`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AddAppointment_parth` (IN `pUserID` INT, IN `pFirstName` VARCHAR(100), IN `pLastName` VARCHAR(100), IN `pEmailId` VARCHAR(100), IN `pDOB` DATE, IN `pMobileNo` VARCHAR(20))  NO SQL
BEGIN
	DECLARE Patient_Id INT DEFAULT 0;
    
	if EXISTS(select * from tbl_patient where firstname=pFirstName and lastname=pLastName and dob=pDOB) THEN
    
    	set Patient_Id = (SELECT id from tbl_patient where firstname=pFirstName and lastname=pLastName and dob=pDOB);
    
    ELSE
    
    	insert into tbl_patient(userid,firstname,lastname,email,dob,mobileno,createdate) values(pUserID,pFirstName,pLastName,pEmailId,pDOB,pMobileNo,CURRENT_TIMESTAMP());
        set Patient_Id  = LAST_INSERT_ID();
    END IF;
    select Patient_Id ;
    
    
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_Admindashboard` (IN `datefrom` DATE, IN `dateto` DATE, IN `patientname` VARCHAR(100), IN `diseaseid` INT)  NO SQL
BEGIN

SELECT tbl_patient.*,tbl_appointment.appointment_date,tbl_appointment.appointment_starttime,tbl_appointment.appointment_endtime,tbl_appointment.description,tbl_appointment.status,tbl_disease.id AS diseaseid,tbl_disease.disease,tbl_appointment.appointment_id FROM `tbl_appointment` INNER JOIN tbl_patient ON tbl_appointment.patient_id=tbl_patient.id INNER JOIN tbl_disease ON tbl_appointment.disease_id=tbl_disease.id WHERE
(datefrom ="" or dateto="" or (tbl_appointment.appointment_date BETWEEN datefrom AND dateto)) AND tbl_appointment.status!='Cancelled' AND tbl_appointment.status!='Followup'
AND (diseaseid ="" or tbl_appointment.disease_id=diseaseid) 
AND (patientname = "" OR (CONCAT(tbl_patient.firstname,' ', tbl_patient.lastname) LIKE CONCAT('%', patientname , '%')));

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_Admindashboard_Backup_12-7-19` ()  NO SQL
BEGIN

SELECT tbl_patient.*,tbl_appointment.appointment_date,tbl_appointment.appointment_starttime,tbl_appointment.appointment_endtime,tbl_appointment.description,tbl_appointment.status,tbl_disease.id AS diseaseid,tbl_disease.disease,tbl_appointment.appointment_id FROM `tbl_appointment` INNER JOIN tbl_patient ON tbl_appointment.patient_id=tbl_patient.id INNER JOIN tbl_disease ON tbl_appointment.disease_id=tbl_disease.id WHERE
(datefrom ="" or dateto="" or (tbl_appointment.appointment_date BETWEEN datefrom AND dateto))
AND (diseaseid ="" or tbl_appointment.disease_id=diseaseid) 
AND (patientname = "" OR (CONCAT(tbl_patient.firstname,' ', tbl_patient.lastname) LIKE CONCAT('%', patientname , '%')));

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_bookappointment` (IN `pUserID` INT, IN `pFirstName` VARCHAR(100), IN `pLastName` VARCHAR(100), IN `pEmailId` VARCHAR(100), IN `pDOB` DATE, IN `pMobileNo` VARCHAR(20), IN `pAppDate` DATE, IN `pAppSatrttime` TIME, IN `pAppEndtime` TIME, IN `pdiseaseid` INT, IN `pdescription` TEXT, IN `pAPPFollowupId` INT)  NO SQL
BEGIN

	DECLARE Patient_Id INT DEFAULT 0;
    
    if EXISTS(select * from tbl_patient where firstname=pFirstName and lastname=pLastName and dob=pDOB) THEN
    
    set Patient_Id = (SELECT id from tbl_patient where firstname=pFirstName and lastname=pLastName and dob=pDOB);
    
    ELSE
    
    insert into tbl_patient(userid,firstname,lastname,email,dob,mobileno,createdate) values(pUserID,pFirstName,pLastName,pEmailId,pDOB,pMobileNo,CURRENT_TIMESTAMP());
        set Patient_Id  = LAST_INSERT_ID();
    END IF;
    select Patient_Id ;
    
    INSERT INTO tbl_appointment(patient_id,appointment_date,appointment_starttime,appointment_endtime,disease_id,description,user_id,createdate,status,Appo_followup_id)
  VALUES(Patient_Id,pAppDate,pAppSatrttime,pAppEndtime,pdiseaseid,pdescription,pUserID,CURRENT_TIMESTAMP(),'Pending',pAPPFollowupId);
  
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_FetchAllappointments` ()  NO SQL
BEGIN

SELECT tbl_patient.*,tbl_appointment.appointment_date,tbl_appointment.appointment_starttime,tbl_appointment.appointment_endtime,tbl_appointment.description,tbl_appointment.status,tbl_disease.disease,tbl_appointment.appointment_id FROM `tbl_appointment` INNER JOIN tbl_patient ON tbl_appointment.patient_id=tbl_patient.id INNER JOIN tbl_disease ON tbl_appointment.disease_id=tbl_disease.id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_FetchAppoTime` (IN `Apoodate` DATE)  NO SQL
BEGIN

SELECT * FROM tbl_appointment WHERE tbl_appointment.appointment_date=Apoodate;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_FetchFollowupuser` (IN `appid` INT)  NO SQL
BEGIN

SELECT tbl_patient.*,tbl_disease.id AS diseaseID,tbl_disease.disease,tbl_appointment.appointment_date,tbl_appointment.appointment_id FROM `tbl_appointment` INNER JOIN tbl_patient ON tbl_appointment.patient_id=tbl_patient.id INNER JOIN tbl_disease ON tbl_appointment.disease_id=tbl_disease.id WHERE tbl_appointment.appointment_id = appid;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_FetchPatient` ()  NO SQL
SELECT * FROM `tbl_patient`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_FetchUser` ()  NO SQL
SELECT * FROM `tbl_user` WHERE type='user'$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_FetchUserAppointment` (IN `puserid` INT)  NO SQL
BEGIN

SELECT tbl_patient.*,tbl_appointment.appointment_id,tbl_appointment.appointment_date,tbl_appointment.appointment_starttime, tbl_appointment.appointment_endtime, tbl_appointment.status, tbl_disease.disease FROM tbl_appointment,tbl_patient,tbl_disease
where tbl_appointment.patient_id = tbl_patient.id
and tbl_appointment.disease_id = tbl_disease.id 
and tbl_appointment.user_id = puserid 
AND tbl_appointment.isPatient_cancelled = 0 ORDER BY tbl_appointment.appointment_date DESC;

END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointment`
--

CREATE TABLE `tbl_appointment` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_starttime` time NOT NULL,
  `appointment_endtime` time NOT NULL,
  `disease_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `createdate` datetime NOT NULL,
  `updatedate` datetime NOT NULL,
  `isDoc_folloup` tinyint(1) NOT NULL,
  `isDoc_cancelled` tinyint(1) NOT NULL,
  `reason` text NOT NULL,
  `isPatient_cancelled` tinyint(1) NOT NULL,
  `status` varchar(20) NOT NULL,
  `Appo_followup_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_appointment`
--

INSERT INTO `tbl_appointment` (`appointment_id`, `patient_id`, `appointment_date`, `appointment_starttime`, `appointment_endtime`, `disease_id`, `description`, `user_id`, `createdate`, `updatedate`, `isDoc_folloup`, `isDoc_cancelled`, `reason`, `isPatient_cancelled`, `status`, `Appo_followup_id`) VALUES
(3, 14, '2019-06-29', '08:00:00', '08:10:00', 3, 'test desp', 2, '2019-06-27 10:42:25', '0000-00-00 00:00:00', 0, 0, '', 1, 'Cancelled', 0),
(6, 16, '2019-06-27', '19:20:00', '19:30:00', 8, 'demo', 2, '2019-06-27 13:19:48', '0000-00-00 00:00:00', 0, 0, '', 1, 'Cancelled', 0),
(7, 16, '2019-06-28', '20:20:00', '20:30:00', 5, 'again', 2, '2019-06-27 13:23:09', '0000-00-00 00:00:00', 0, 0, '', 1, 'Cancelled', 0),
(8, 17, '2019-06-28', '19:20:00', '19:30:00', 9, 'demo', 2, '2019-06-27 19:14:13', '0000-00-00 00:00:00', 0, 0, '', 1, 'Cancelled', 0),
(10, 16, '2019-06-29', '20:00:00', '20:10:00', 10, 'testing demo', 2, '2019-06-28 10:51:52', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', 0),
(13, 18, '2019-07-04', '20:00:00', '20:10:00', 9, 'first demo', 2, '2019-06-28 11:28:55', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(14, 19, '2019-07-04', '19:00:00', '19:10:00', 2, 'testing', 2, '2019-06-28 14:02:51', '0000-00-00 00:00:00', 0, 1, 'go to mumbai', 0, 'Cancelled', NULL),
(15, 17, '2019-07-02', '20:00:00', '20:10:00', 9, 'second time test', 2, '2019-06-28 14:04:51', '0000-00-00 00:00:00', 0, 0, '', 1, 'Cancelled', 8),
(16, 20, '2019-06-29', '19:20:00', '19:30:00', 1, 'first time', 3, '2019-06-28 14:37:41', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(17, 20, '2019-07-02', '19:00:00', '19:10:00', 4, 'second appointment', 3, '2019-06-28 14:38:51', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', 16),
(18, 21, '2019-07-01', '19:00:00', '19:10:00', 6, 'first appointment', 3, '2019-06-28 16:02:51', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(19, 21, '2019-07-09', '19:00:00', '19:10:00', 6, 'dsfasdf', 3, '2019-06-28 17:32:07', '0000-00-00 00:00:00', 0, 0, '', 1, 'Cancelled', 18),
(20, 19, '2019-07-05', '19:40:00', '19:50:00', 4, 'AGAIN ADD ', 2, '2019-07-01 18:51:36', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', 14),
(21, 21, '2019-07-10', '19:40:00', '19:50:00', 6, 'second time booked', 3, '2019-07-02 19:09:36', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', 18),
(22, 22, '2019-07-10', '19:40:00', '19:50:00', 11, 'first time book', 3, '2019-07-03 10:26:33', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(23, 22, '2019-07-08', '20:00:00', '20:10:00', 8, 'second book today', 3, '2019-07-03 10:28:20', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', 22),
(24, 23, '2019-07-08', '01:00:00', '01:00:00', 2, 'sceen damage issue ', 28, '2019-07-08 16:26:55', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(25, 24, '2019-07-10', '19:20:00', '19:30:00', 1, 'hair fall ', 12, '2019-07-08 17:08:46', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(27, 21, '2019-07-13', '19:00:00', '19:10:00', 7, 'safasdfafasfd', 2, '2019-07-10 19:04:10', '0000-00-00 00:00:00', 1, 0, '', 0, 'Followup', NULL),
(28, 20, '2019-07-13', '20:20:00', '20:30:00', 1, 'today add ', 3, '2019-07-11 10:55:44', '0000-00-00 00:00:00', 1, 0, '', 0, 'Followup', 16),
(29, 20, '2019-07-13', '20:00:00', '20:10:00', 1, 'tertafasfaf', 3, '2019-07-11 13:44:11', '0000-00-00 00:00:00', 0, 1, 'not avaibale', 0, 'Cancelled', 28),
(31, 19, '2019-07-17', '19:40:00', '19:50:00', 4, 'shankar add sppointment by admin', 1, '2019-07-11 17:02:52', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', 20),
(33, 25, '2019-07-17', '19:20:00', '19:30:00', 1, 'dipak add new appointment', 1, '2019-07-11 17:47:11', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', 26),
(34, 26, '2019-07-16', '19:20:00', '19:30:00', 7, 'nikul first offline book by admin', 1, '2019-07-11 17:51:07', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(36, 28, '2019-07-16', '20:00:00', '20:10:00', 5, 'first book', 1, '2019-07-11 18:56:54', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(44, 36, '2019-07-18', '20:00:00', '20:10:00', 5, 'mukesh first add ', 1, '2019-07-12 17:47:56', '0000-00-00 00:00:00', 0, 1, 'due to rain', 0, 'Cancelled', NULL),
(47, 38, '2019-07-15', '20:20:00', '20:30:00', 2, 'asdf', 29, '2019-07-13 12:10:42', '0000-00-00 00:00:00', 0, 0, '', 1, 'Cancelled', NULL),
(48, 38, '2019-07-13', '19:20:00', '19:30:00', 2, 'sadf', 29, '2019-07-13 12:13:39', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(49, 39, '2019-07-30', '19:40:00', '19:50:00', 2, 'zxfv', 29, '2019-07-13 13:48:59', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(50, 40, '2019-07-30', '20:00:00', '20:10:00', 2, 'xc', 29, '2019-07-13 13:51:24', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(51, 41, '2019-07-27', '19:20:00', '19:30:00', 2, 'dfg', 29, '2019-07-13 13:57:35', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(52, 42, '2019-07-25', '19:20:00', '19:30:00', 16, 'efg', 1, '2019-07-18 11:21:33', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL),
(53, 43, '2019-07-18', '19:00:00', '19:10:00', 2, 'mnb', 1, '2019-07-18 11:34:28', '0000-00-00 00:00:00', 0, 0, '', 0, 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_appointmenttime`
--

CREATE TABLE `tbl_appointmenttime` (
  `id` int(11) NOT NULL,
  `start-time` varchar(50) NOT NULL,
  `end-time` varchar(50) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_appointmenttime`
--

INSERT INTO `tbl_appointmenttime` (`id`, `start-time`, `end-time`, `status`, `createddate`, `updatedate`) VALUES
(1, '7:00 PM', '7:10 PM', 1, '2019-06-26 07:03:02', '0000-00-00 00:00:00'),
(2, '7:20 PM', '7:30 PM', 1, '2019-07-16 03:15:47', '0000-00-00 00:00:00'),
(3, '7:40 PM', '7:50 PM', 1, '2019-06-26 07:04:18', '0000-00-00 00:00:00'),
(4, '8:00 PM', '8:10 PM', 1, '2019-06-26 07:04:51', '0000-00-00 00:00:00'),
(5, '8:20 PM', '8:30 PM', 1, '2019-06-26 07:05:04', '0000-00-00 00:00:00'),
(15, '12:15 PM', '12:15 PM', 1, '2019-07-18 07:32:04', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_disease`
--

CREATE TABLE `tbl_disease` (
  `id` int(11) NOT NULL,
  `disease` varchar(100) NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedate` datetime NOT NULL,
  `orderby` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_disease`
--

INSERT INTO `tbl_disease` (`id`, `disease`, `createddate`, `updatedate`, `orderby`) VALUES
(2, 'Skin Treatment', '2019-06-26 05:23:41', '2019-07-18 07:56:08', 4),
(3, 'Psoriasis Allergy Eczema', '2019-06-26 05:26:11', '2019-07-18 07:56:08', 5),
(4, 'Ayurvedic Pain Management', '2019-06-26 05:27:13', '2019-07-18 07:56:08', 6),
(5, 'Infertility and PCOD Treatment', '2019-06-26 05:27:29', '2019-07-18 07:56:08', 3),
(6, 'Depression and Insomnia', '2019-06-26 05:27:59', '2019-07-18 07:56:08', 2),
(7, 'Weight Loss Treatment', '2019-06-26 05:28:37', '2019-07-18 07:56:08', 7),
(8, 'Menstruation Problems', '2019-06-26 05:28:50', '2019-07-18 07:56:08', 8),
(9, 'Pimples Treatment', '2019-06-26 05:29:01', '2019-07-18 07:56:08', 9),
(10, 'Piles and Fissure Treatment', '2019-06-26 05:29:11', '2019-07-18 07:56:08', 10),
(11, 'Tattoo and Birthmark remove', '2019-06-26 05:29:23', '2019-07-18 07:56:08', 11),
(12, 'Other', '2019-06-26 05:30:06', '2019-07-18 07:56:08', 12),
(16, 'Hair Treatment', '2019-07-17 08:41:30', '2019-07-18 07:56:08', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_patient`
--

CREATE TABLE `tbl_patient` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `mobileno` varchar(20) NOT NULL,
  `createdate` datetime NOT NULL,
  `updatedate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_patient`
--

INSERT INTO `tbl_patient` (`id`, `userid`, `firstname`, `lastname`, `email`, `dob`, `mobileno`, `createdate`, `updatedate`) VALUES
(7, 1, 'test', 'test', 'test01@test.com', '2019-06-29', '12345679801', '2019-06-26 11:53:02', '0000-00-00 00:00:00'),
(8, 1, 'test', 'test ', 'test.test.net', '2019-06-15', '9876543210', '2019-06-26 12:10:02', '0000-00-00 00:00:00'),
(12, 1, 'test', 'test', 'test.test.info', '2019-06-18', '9876543211', '2019-06-26 12:11:27', '0000-00-00 00:00:00'),
(13, 1, 'test', 'test', 'test2', '2016-06-26', '1234567890', '2019-06-26 18:10:09', '0000-00-00 00:00:00'),
(14, 2, 'paresh', 'panchal', 'paresh@gmail.com', '1995-06-15', '9874561230', '2019-06-27 10:32:51', '0000-00-00 00:00:00'),
(16, 2, 'Mitesh', 'Kadivar', 'mitesh123@yopmail.com', '1997-12-20', '9723514584', '2019-06-27 13:19:48', '0000-00-00 00:00:00'),
(17, 2, 'bhargav', 'prajapati', 'bhargav123@yopmail.com', '1995-05-11', '9630214587', '2019-06-27 19:14:13', '0000-00-00 00:00:00'),
(18, 2, 'Paresh', 'panchal', 'paresh123@yopmail.com', '1999-06-17', '7894562100', '2019-06-28 11:28:55', '0000-00-00 00:00:00'),
(19, 2, 'Shankar', 'Patel', 'shankar123@yopmail.com', '1998-05-15', '7891425360', '2019-06-28 14:02:51', '0000-00-00 00:00:00'),
(20, 3, 'Jigar', 'Patel', 'jigar123@yopmail.com', '2000-02-11', '7845120963', '2019-06-28 14:37:41', '0000-00-00 00:00:00'),
(21, 3, 'Dipak', 'patel', 'dipak123@yopmail.com', '1992-09-11', '9685147023', '2019-06-28 16:02:51', '0000-00-00 00:00:00'),
(22, 3, 'Ashvin', 'patel', 'ashvin123@yopmail.com', '1986-08-27', '9685740123', '2019-07-03 10:26:33', '0000-00-00 00:00:00'),
(23, 28, 'paresh', 'PATEL', 'komal.gupta@overseasits.com', '2018-10-08', '8995835393', '2019-07-08 16:26:55', '0000-00-00 00:00:00'),
(24, 12, 'Kim', 'Rana', 'komal.gupta@overseasits.com', '2018-12-06', '3248239473', '2019-07-08 17:08:46', '0000-00-00 00:00:00'),
(25, 2, 'Dipak', 'test', 'test@gmail.com', '2019-07-10', '1321313131', '2019-07-10 18:46:13', '0000-00-00 00:00:00'),
(26, 1, 'Nikul', 'Patel', 'nikul123@yopmail.com', '1995-11-23', '9974386861', '2019-07-11 17:51:07', '0000-00-00 00:00:00'),
(28, 1, 'Dharmesh', 'patel', 'dharmesh123@yopmail.com', '1998-11-15', '7986541320', '2019-07-11 18:56:54', '0000-00-00 00:00:00'),
(34, 1, 'Nirav', 'Patel', 'nirav123@yopmail.com', '1992-02-12', '8974541231', '2019-07-12 11:57:47', '0000-00-00 00:00:00'),
(35, 1, 'mukesh', 'bhavsar', 'mukesh123@yopmail.com', '1994-04-15', '7894564564', '2019-07-12 16:28:18', '0000-00-00 00:00:00'),
(37, 1, 'maya', 'patel', 'maya123@yopmail.com', '2000-10-25', '8798754564', '2019-07-13 11:01:05', '0000-00-00 00:00:00'),
(38, 29, 'abc', 'de', 'admin@gmail.com', '2019-06-26', '1234567891', '2019-07-13 12:10:42', '0000-00-00 00:00:00'),
(39, 29, 'meet', 'modh', 'meet.modh@overseasits.com', '2019-07-08', '123456789', '2019-07-13 13:48:59', '0000-00-00 00:00:00'),
(40, 29, 'meet', 'bhm', 'meet.modh@overseasits.com', '2019-07-08', '12345678', '2019-07-13 13:51:24', '0000-00-00 00:00:00'),
(41, 29, 'meet', 'modh', 'meet.modh@overseasits.com', '2019-07-13', '12345678', '2019-07-13 13:57:35', '0000-00-00 00:00:00'),
(42, 1, 'meet', 'm', 'meet@gmail.com', '2019-07-11', '1234567897', '2019-07-18 11:21:33', '0000-00-00 00:00:00'),
(43, 1, 'meet', 'm', 'meet@gmail.com', '2010-01-02', '1234567897', '2019-07-18 11:34:28', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_prescription`
--

CREATE TABLE `tbl_prescription` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `prescription` text NOT NULL,
  `createdate` datetime NOT NULL,
  `updatedate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_prescription`
--

INSERT INTO `tbl_prescription` (`id`, `appointment_id`, `prescription`, `createdate`, `updatedate`) VALUES
(1, 16, 'first prescription add ', '2019-06-29 07:47:56', '0000-00-00 00:00:00'),
(2, 16, 'add second pre', '2019-06-29 07:53:59', '0000-00-00 00:00:00'),
(3, 10, 'mitesh pre', '2019-06-29 07:56:09', '0000-00-00 00:00:00'),
(4, 18, 'dipak prescription', '2019-07-01 07:45:28', '0000-00-00 00:00:00'),
(93, 15, 'bhargav prescription', '2019-07-02 01:05:29', '0000-00-00 00:00:00'),
(113, 17, 'again add prescription', '2019-07-02 02:06:20', '0000-00-00 00:00:00'),
(114, 15, 'agian add ', '2019-07-02 02:09:38', '0000-00-00 00:00:00'),
(115, 19, 'add', '2019-07-03 07:07:58', '2019-07-03 03:35:27'),
(116, 22, 'ashvin presiton ', '2019-07-03 07:09:09', '2019-07-03 03:28:43'),
(117, 22, 'again add prescription', '2019-07-03 07:09:25', '2019-07-10 12:56:32'),
(132, 2, 'first pres ', '2019-07-03 01:47:20', '2019-07-04 01:01:16'),
(133, 2, 'testing change', '2019-07-03 01:48:49', '2019-07-04 01:01:06'),
(149, 13, 'chekig  update data', '2019-07-04 06:50:05', '2019-07-04 10:55:26'),
(155, 13, 'fdszfd update ', '2019-07-04 08:15:45', '2019-07-04 03:04:30'),
(166, 13, 'update  prescription 2 oclock', '2019-07-04 09:38:25', '2019-07-04 11:30:08'),
(189, 2, 'insert prescripton', '2019-07-04 11:38:52', '2019-07-05 06:59:33'),
(190, 13, 'test ', '2019-07-04 03:04:18', '0000-00-00 00:00:00'),
(192, 13, 'fsdfsdfsdafds', '2019-07-04 03:05:26', '0000-00-00 00:00:00'),
(193, 2, 'today', '2019-07-05 06:59:35', '0000-00-00 00:00:00'),
(220, 20, 'dassa', '2019-07-05 11:10:00', '2019-07-05 11:32:36'),
(279, 20, 'uparedf ds', '2019-07-05 01:11:54', '2019-07-05 01:16:31'),
(283, 20, ' dfasdf', '2019-07-05 01:16:35', '0000-00-00 00:00:00'),
(284, 19, 'prescription  8 add', '2019-07-08 06:55:20', '0000-00-00 00:00:00'),
(287, 19, 'today ', '2019-07-08 06:58:58', '2019-07-08 09:35:39'),
(334, 19, 'new  prescription  ', '2019-07-08 09:37:52', '2019-07-09 06:55:27'),
(338, 25, 'kil first prescription', '2019-07-09 02:56:43', '2019-07-10 03:54:44'),
(341, 25, 'asdfasd update', '2019-07-10 07:36:08', '2019-07-10 03:54:43'),
(342, 21, 'test ', '2019-07-10 08:15:30', '0000-00-00 00:00:00'),
(343, 21, 'sfds update', '2019-07-10 08:15:32', '2019-07-10 10:32:44'),
(344, 21, 'start presciption', '2019-07-10 08:15:35', '2019-07-10 12:56:05'),
(367, 25, 'new insert', '2019-07-10 11:05:43', '2019-07-10 11:10:24'),
(369, 25, 'now to addded prescription ', '2019-07-10 12:58:53', '2019-07-10 01:29:11'),
(370, 27, 'Prescription add 11-7', '2019-07-11 07:11:27', '2019-07-11 07:11:42'),
(373, 26, 'check', '2019-07-12 07:02:10', '0000-00-00 00:00:00'),
(374, 26, 'new update ', '2019-07-12 07:04:27', '2019-07-12 07:04:36'),
(384, 27, 'add', '2019-07-12 07:31:31', '0000-00-00 00:00:00'),
(388, 27, 'test', '2019-07-12 07:31:48', '0000-00-00 00:00:00'),
(398, 28, 'asdfs', '2019-07-12 07:34:00', '0000-00-00 00:00:00'),
(404, 29, 'adfd tyer te', '2019-07-12 07:35:16', '2019-07-12 12:45:03'),
(407, 42, 'add resction', '2019-07-12 11:49:10', '0000-00-00 00:00:00'),
(408, 42, 'test data ', '2019-07-12 11:49:17', '2019-07-12 11:50:37'),
(415, 42, 'test data df', '2019-07-12 11:52:15', '2019-07-12 11:52:20'),
(416, 42, 'dfadf', '2019-07-12 11:52:24', '2019-07-12 11:52:30'),
(417, 26, '456', '2019-07-13 10:10:46', '0000-00-00 00:00:00'),
(420, 34, 'testadf', '2019-07-16 07:40:24', '2019-07-16 08:05:46'),
(421, 34, 'addd df', '2019-07-16 07:40:32', '2019-07-16 08:05:44'),
(428, 34, 'test update', '2019-07-16 08:05:52', '2019-07-16 08:07:04'),
(448, 24, 'fgdf', '2019-07-18 07:43:12', '0000-00-00 00:00:00'),
(451, 24, 'hhhhh', '2019-07-18 07:43:18', '0000-00-00 00:00:00'),
(452, 24, 'zf', '2019-07-18 07:46:01', '0000-00-00 00:00:00'),
(453, 24, 'sdf', '2019-07-18 07:46:04', '0000-00-00 00:00:00'),
(454, 24, 'gggg', '2019-07-18 07:46:08', '0000-00-00 00:00:00'),
(461, 48, 'jnk', '2019-07-18 07:57:10', '2019-07-18 07:57:18'),
(465, 53, '123\n', '2019-07-18 08:38:06', '0000-00-00 00:00:00'),
(466, 53, '123\n', '2019-07-18 08:38:06', '0000-00-00 00:00:00'),
(467, 53, ';lkjl;jk', '2019-07-18 09:48:49', '2019-07-18 09:48:54');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_symtoms`
--

CREATE TABLE `tbl_symtoms` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) NOT NULL,
  `symtoms` text NOT NULL,
  `createdate` datetime NOT NULL,
  `updatedate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_symtoms`
--

INSERT INTO `tbl_symtoms` (`id`, `appointment_id`, `symtoms`, `createdate`, `updatedate`) VALUES
(1, 16, 'first symtoms', '2019-06-29 07:03:29', '0000-00-00 00:00:00'),
(2, 10, 'mitesh symtoms first', '2019-06-29 07:20:15', '0000-00-00 00:00:00'),
(3, 18, 'dipak symtoms', '2019-07-01 06:52:58', '0000-00-00 00:00:00'),
(4, 15, 'bhargav symtoms ', '2019-07-02 08:37:45', '0000-00-00 00:00:00'),
(5, 19, 'add symtoms', '2019-07-03 07:07:49', '0000-00-00 00:00:00'),
(6, 22, 'ashvin symtoms', '2019-07-03 07:08:59', '0000-00-00 00:00:00'),
(7, 2, 'paresh symtoms 28-06-2019', '2019-07-03 01:32:03', '0000-00-00 00:00:00'),
(8, 2, 'symtoms 28-06', '2019-07-03 01:34:28', '0000-00-00 00:00:00'),
(9, 3, 'symtoms 29-06 paresh', '2019-07-03 01:35:02', '0000-00-00 00:00:00'),
(21, 25, 'kin rana symtoms', '2019-07-09 02:44:12', '0000-00-00 00:00:00'),
(22, 25, 'again add symtoms', '2019-07-09 02:44:55', '0000-00-00 00:00:00'),
(23, 25, 'add symtoms 10-7-19', '2019-07-10 11:04:36', '0000-00-00 00:00:00'),
(24, 27, 'add symtoms 11-07', '2019-07-11 07:11:15', '0000-00-00 00:00:00'),
(25, 26, 'add dipak test symtoms', '2019-07-12 07:01:43', '0000-00-00 00:00:00'),
(26, 42, 'nirav symtoms', '2019-07-12 11:48:29', '0000-00-00 00:00:00'),
(27, 26, '213', '2019-07-13 10:10:41', '0000-00-00 00:00:00'),
(28, 31, 'sdf', '2019-07-15 10:35:01', '0000-00-00 00:00:00'),
(29, 31, 'T6U76', '2019-07-15 11:25:04', '0000-00-00 00:00:00'),
(30, 13, 'gh', '2019-07-18 07:36:08', '0000-00-00 00:00:00'),
(31, 13, 'cgh', '2019-07-18 07:37:16', '0000-00-00 00:00:00'),
(32, 13, 'cgh', '2019-07-18 07:37:16', '0000-00-00 00:00:00'),
(33, 48, 'jhbv', '2019-07-18 07:57:25', '0000-00-00 00:00:00'),
(34, 53, 'vb', '2019-07-18 08:24:34', '0000-00-00 00:00:00'),
(35, 53, 'vb', '2019-07-18 08:24:34', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `mobileno` varchar(20) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createddate` datetime NOT NULL,
  `updatedate` datetime NOT NULL,
  `type` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `username`, `email`, `password`, `mobileno`, `status`, `createddate`, `updatedate`, `type`) VALUES
(1, 'Admin', 'admin@gmail.com', 'e6e061838856bf47e1de730719fb2609', '', 1, '2019-06-26 08:08:00', '0000-00-00 00:00:00', 'admin'),
(2, 'Mitesh patel', 'mitesh123@yopmail.com', 'a5adfa38dd8636196ad78087f91234ab', '9876543210', 1, '2019-06-26 04:47:49', '2019-07-18 07:34:41', 'user'),
(3, 'Jigar methaniya', 'jigar@gmail.com', '9208879e56e1e65513b4ab5284a73ea8', '9785410236', 1, '2019-06-26 04:53:30', '2019-07-18 07:34:40', 'user'),
(4, 'komal ', 'komalshah.162@gmail.com', '77fc31448ae6955a7d107308ad821b3d', '1234567890', 1, '2019-06-28 01:18:49', '2019-07-12 10:22:43', 'user'),
(12, 'komal', 'komal.gupta@overseasits.com', '68673662afe234e2598220915639c3e2', '1283328423', 1, '2019-07-01 07:54:23', '0000-00-00 00:00:00', 'user'),
(26, 'komal', 'komal@gmail.com', '77fc31448ae6955a7d107308ad821b3d', '8890908098', 1, '2019-07-03 10:35:50', '0000-00-00 00:00:00', 'user'),
(28, 'komal', 'komal.gupta@overaseasits.com', '2826e04ee9517d47548dc35542cf2fe6', '8787289289', 1, '2019-07-08 12:41:42', '0000-00-00 00:00:00', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  ADD PRIMARY KEY (`appointment_id`);

--
-- Indexes for table `tbl_appointmenttime`
--
ALTER TABLE `tbl_appointmenttime`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_disease`
--
ALTER TABLE `tbl_disease`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `disease` (`disease`);

--
-- Indexes for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_prescription`
--
ALTER TABLE `tbl_prescription`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_symtoms`
--
ALTER TABLE `tbl_symtoms`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `mobileno` (`mobileno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_appointment`
--
ALTER TABLE `tbl_appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `tbl_appointmenttime`
--
ALTER TABLE `tbl_appointmenttime`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `tbl_disease`
--
ALTER TABLE `tbl_disease`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `tbl_patient`
--
ALTER TABLE `tbl_patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tbl_prescription`
--
ALTER TABLE `tbl_prescription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=468;

--
-- AUTO_INCREMENT for table `tbl_symtoms`
--
ALTER TABLE `tbl_symtoms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
