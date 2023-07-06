

# About Laravel

I have reworded the paragraph to make it more concise and organized. Here's the revised version:

## Attendance Management Service

This repository contains the source code and related files for the Attendance Management Service, an online attendance marking system.

### Description

The Attendance Management Service allows users to log in to the website and records their IP address in the database for attendance tracking purposes. The attendance session begins upon login and ends upon logout. 

When a user logs in, the system checks if their IP address matches any of the predefined static IP addresses stored in a model. If the first three octets of the user's IP address match with a static IP, the system marks the user as being in the office. Otherwise, the user is considered to be working remotely. 

The system consists of five models: 
1. User model: Stores user information in the database.
2. Static model: Stores predefined static IP addresses.
3. Remote table: Stores data for users working remotely, including login time, logout time, and total session duration.
4. Office table: Stores data for users working in the office, including login time, logout time, and total session duration.
5. Total duration table: Stores the total session durations for remote and office work.

Based on the total session duration, attendance is marked as follows:
- Less than 3 hours: Absent
- 3 to 5 hours: Half attendance
- More than 5 hours: Full attendance

Users can have multiple sessions by logging in and out. The remote and office tables only store login and logout records. A cron job is implemented to truncate the tables every week to manage table load. At the end of each day, the total session data for remote and office work is stored in the total duration table for attendance marking.

The project includes a Docker repository where the project image is pushed. Additionally, an idle timeout of 15 minutes is implemented, automatically logging out users who haven't performed any task during that time. They will need to log in again to continue using the system.

Please refer to the installation instructions in the repository for details on setting up the Attendance Management Service.

---

Feel free to customize and adapt this text according to your specific project's structure and requirements.
## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[OP.GG](https://op.gg)**
- **[WebReinvent](https://webreinvent.com/?utm_source=laravel&utm_medium=github&utm_campaign=patreon-sponsors)**
- **[Lendio](https://lendio.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
