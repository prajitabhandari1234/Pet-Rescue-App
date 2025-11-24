# Pet-Rescue-App

## 🐾 About  
Pet Rescue App is a simple web application built with PHP and MySQL, designed to help manage information about rescued pets. Users can log in, add, edit, and delete pet entries, and view existing records. The app includes a clean interface, session handling, and a responsive layout.

## Features  
- **User Authentication**: Log in and log out securely.  
- **CRUD Operations**: Create, Read, Update, and Delete pet records.  
- **Database Management**: Uses MySQL to store pet data (name, type, description, etc.).  
- **Responsive Design**: Basic CSS to ensure the app adapts to different screen sizes.  
- **Session Control**: Maintains user sessions to restrict access to logged-in users.

## 🧰 Technologies Used  
- **Backend**: PHP  
- **Database**: MySQL  
- **Frontend**: HTML & CSS  
- **Session Management**: PHP `$_SESSION` for login persistence  

## File Structure  
├── add.php — Form & logic for adding a new pet

├── edit.php — Form & logic for updating pet information

├── delete.php — Logic for deleting a pet entry

├── home.php — Main page listing all rescued pets

├── login.php — Login form and authentication logic

├── logout.php — Logout logic

├── db.php — Database connection script

├── test_db.php — Optional script to test the database connection

├── header.php — Common header file

├── footer.php — Common footer file

└── styles.css — CSS stylesheet for styling the app
