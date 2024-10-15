KineticKards – Flashcard Study Tool

Project Overview

KineticKards is a web-based flashcard study tool that allows users to create, store, and review digital flashcards for A-Level Physics. The project includes a login system and personalised flashcard storage, utilising PHP for the backend and CSV for data storage. It was developed as part of my A-Level Computer Science coursework.

Installation and Setup Instructions

Prerequisites

To run this project locally, you need to have the following installed:

	•	XAMPP (or any other local web server that supports PHP and MySQL)
	•	PHP 7.4+
	•	A modern web browser

Database Setup

KineticKards uses a local database on XAMPP for storing user data and flashcards. Here’s how to set it up:

	1.	Download and Install XAMPP
If you haven’t installed XAMPP yet, download it from here and follow the installation instructions for your operating system.
	2.	Start Apache and MySQL
Launch the XAMPP Control Panel and start the Apache and MySQL services.
	3.	Create the Database
	•	Open phpMyAdmin by visiting http://localhost/phpmyadmin/ in your browser.
	•	Create a new database for KineticKards
	•	Import the provided SQL file (kinetickards_db.sql) to create the required tables. You can find the SQL file in the database folder of this repository.
	4.	Configure Database Connection
	•	Navigate to the project’s root folder and open the config.php file.
	•	Update the database configuration with your local MySQL credentials:
 
$host = 'localhost';
$db = 'kinetikards_db';  // Your database name
$user = 'root';          // Your MySQL username (default is 'root')
$pass = '';              // Your MySQL password

Running the Project

	1.	Place the project folder in the htdocs directory of your XAMPP installation. For example:
C:\xampp\htdocs\kinetikards
	2.	In your browser, go to http://localhost/kinetikards to access the website.
	3.	You should now be able to register, log in, and start using the flashcard system.

Important Notes

	•	The project is set up for local use and is not currently configured for deployment on a live server. 
	•	The database uses CSV files for some data storage, and any user flashcards or login data are stored locally.

Future Improvements

	•	Convert the system to use a cloud-based or hosted database service (e.g., MySQL on AWS) for broader access.
	•	Implement user data encryption for enhanced security.

License

This project is open-source and available under the MIT License.
