

Certainly! Here's the revised version formatted for a README.md file:

## Attendance Management Service

This repository contains the source code and related files for the Attendance Management Service, an online attendance marking system.

### Description

The Attendance Management Service allows users to log in to the website and records their IP address in the database for attendance tracking purposes. The attendance session begins upon login and ends upon logout.

When a user logs in, the system checks if their IP address matches any of the predefined static IP addresses stored in a model. If the first three octets of the user's IP address match with a static IP, the system marks the user as being in the office. Otherwise, the user is considered to be working remotely.

### System Models

The system consists of the following models:

- User model: Stores user information in the database.
- Static model: Stores predefined static IP addresses.
- Remote table: Stores data for users working remotely, including login time, logout time, total session duration, and workplace location.
- Office table: Stores data for users working in the office, including login time, logout time, total session duration, and workplace location.
- Total duration table: Stores the total session durations for remote and office work, and attendance marking.

### Attendance Marking

Based on the total session duration, attendance is marked as follows:

- Less than 3 hours: Absent
- 3 to 5 hours: Half attendance
- More than 5 hours: Full attendance

For more details on how the attendance marking is implemented, please refer to the `UserController` in the source code for reference.

### Cron Job for Table Truncation

To manage the table load and maintain data integrity, a cron job is implemented to automatically truncate the remote and office tables every week. This ensures that the tables are cleaned and ready to store new attendance data for the upcoming week.

### Session Management and Idle Timeout

To enhance security and resource management, the Attendance Management Service implements an idle timeout feature. After 15 minutes of user inactivity, a custom middleware automatically logs out users who haven't performed any task during that time. This ensures that only actively engaged users are utilizing system resources. To continue using the service, users will need to log in again.

### Docker Repository

The project includes a Docker repository where the project image is pushed.

Please refer to the installation instructions in the repository for details on setting up the Attendance Management Service.

---
