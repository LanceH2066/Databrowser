<?php
  require_once 'includes/dbh.inc.php';
  require_once 'includes/config_session.inc.php';
  require_once 'includes/import_json_data.inc.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Data Browser - Edit Items</title>
  <link href="style.css" rel="stylesheet">
  <script src="app.js" defer></script>
</head>

<body>
  <form id="item-form">
     <h1>Fantasy Football Manager</h1>

    <label for="name">Name:</label>
    <input type="text" id="name" name="name" readonly>

    <label for="team">Team:</label>
    <input type="text" id="team" name="team" readonly>

    <label for="number">Number:</label>
    <input type="number" id="number" name="number" readonly>

    <label for="position">Position:</label>
    <select id="position" name="position" disabled>
      <option value="QB">QB</option>
      <option value="RB">RB</option>
      <option value="WR">WR</option>
      <option value="TE">TE</option>
      <option value="K">K</option>
      <option value="DEF">DEF</option>
    </select>

    <label>Status:</label>
    <div id ="status">
      <label for="healthy">Healthy</label>
      <input type="radio" id="healthy" name="status" value="healthy" disabled>
      <label for="injured">Injured</label>
      <input type="radio" id="injured" name="status" value="injured" disabled>
    </div>

    </div>
    <div id= "buttonContainer">
      <button id ="nav" type="button" onclick="fetchFirstItem()">First</button>
      <button id ="nav" type="button" onclick="fetchPreviousItem()">Previous</button>
      <button id ="nav" type="button" onclick="fetchNextItem()">Next</button>
      <button id ="nav" type="button" onclick="fetchLastItem()">Last</button>
    </div>

    <div id= "buttonContainer">
      <button id ="btn" type="button" onclick="">Upload Image</button>
      <button id ="btn" type="button" onclick="insertItem()">Add Empty</button>
      <button id ="btn" type="button" onclick="toggleEdit()">Edit</button>
      <button id ="btn" type="button" onclick="saveRecord()">Save</button>
      <button id ="btn" type="button" onclick="toggleSort()">Sort</button>
      <button id ="btn" type="button" onclick="deleteItem()">Delete</button>
    </div>

    <p id="item-position"></p>
    
  </form>

</body>
</html>
