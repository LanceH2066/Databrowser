Fantasy Football Manager - Created By Lance Heinrich

Fantasy Football Manager is a web-based data browser application designed to help users manage their fantasy football players. 
Users can add, edit, view, save, sort, and delete player records, including uploading player images. 
The project is built with PHP, JavaScript, HTML, CSS, and a mysql database for storing and managing player data.

Installation and Setup

Ensure XAMPP is installed.
Extract the project files (Folder Name: Databrowser) into the server's root directory (htdocs).
Ensure APACHE and MYSQL servers are running.

Database Configuration:
NO CONFIGURATION REQUIRED
DATABASE NAME: databrowserdb
USER: root , PWD: ""
dbh.inc.php automatically creates the database and tables on the first load, no need to change user or database names around.

Load Sample Data:
The JSON data in tables/data.json is imported automatically via import_json_data.inc.php.

Start the Server:
Run your local server and access the application in the browser at:
http://localhost/databrowser/

Usage
Use the navigation buttons to browse through player records.
Add new players using the "Add Empty" button. 
Then edit their details, upload an image and then save to see your image and save your details into the database.
Delete players or sort records as needed.
Check the database with phpmyadmin to confirm everything is working as intended!

NOTE: If you want to restart with the default set of players simply delete the database through phpmyadmin 
or delete all of the players in the table through phpmyadmin, THE DELETE BUTTON DOES NOT ALLOW YOU TO DELETE ALL ELEMENTS THERE WILL ALWAYS BE ONE.

Features
Add Players: Insert Empty players into the database to be modified.
Edit Players: Modify existing player details.
View Players: Navigate through player records (first, previous, next, last).
Delete Players: Remove a player record from the database.
Image Uploads: Upload custom images for players.
Sorting: Sort player records dynamically.
Responsive UI: User-friendly interface with data previews and navigation controls.

Project Structure

Frontend Files
css/style.css
Stylesheet for the Fantasy Football Manager user interface.

images/
logo.png: NFL logo displayed on the main page.
placeholder.png: Default placeholder image for players without an uploaded image.

js/app.js
Contains all client-side JavaScript functions for:
Navigating records: fetchFirstItem(), fetchNextItem(), fetchPreviousItem(), fetchLastItem().
Managing records: insertItem(), saveRecord(), deleteItem(), toggleSort().
UI updates: displayItem(), toggleEdit().

Backend Files
includes/
dbh.inc.php: Handles database creation, connection, and table setup.
config_session.inc.php: Manages sessions, including session regeneration for security.
import_json_data.inc.php: Imports test data from a JSON file (tables/data.json) for quick setup.

PHP Scripts
index.php: Main entry point. Sets up the UI and initializes required backend components.
fetchItem.php: Retrieves a specific player record.
insertItem.php: Adds an empty player field to the database.
saveItem.php: Saves changes to an existing player.
deleteItem.php: Deletes a player record from the database.
sortItems.php: Handles sorting of records using session variables.

tables/data.json
Contains 20 sample player records for testing.

uploads/
Directory to store player image uploads.

CONTACT INFO
Name: Lance Heinrich
Email: heinrichlance2066@gmail.com
Phone #: (559)572-2299