let currentIndex = 0;
let items = [];

function fetchItem(index) 
{
  console.log("Fetch Called On Item: " + index);
  const request = new XMLHttpRequest();
  request.open("GET", `loadItem.php?index=${index}`, true);

  request.onload = function () 
  {
    if (request.status === 200) 
    {
      const response = JSON.parse(request.responseText);
      if (!response.error) 
      {
        console.log("Response received:", response);
        displayItem(response); // Display the fetched item
        document.getElementById("item-position").textContent = `${index + 1} / ${response.total}`;
        currentIndex = index; // Update the current index
      } 
      else 
      {
        console.error("Error:", response.error);
        alert(response.error);
      }
    } 
    else 
    {
      console.error("Request failed with status:", request.status);
    }
  };

  request.onerror = function () {
      console.error("Network error occurred while fetching item.");
  };

  request.send();
}

function displayItem(item) 
{
  console.log("Displaying Item Values...");
  document.getElementById("name").value = item.name || "";
  document.getElementById("team").value = item.team || "";
  document.getElementById("number").value = item.number || "";
  document.getElementById("healthy").checked = item.status === "healthy";
  document.getElementById("injured").checked = item.status === "injured";
  document.getElementById("position").value = item.position || "";
}

function toggleEdit() 
{
  console.log("Toggling Edit Mode...");
  document.getElementById("name").toggleAttribute("readonly");
  document.getElementById("team").toggleAttribute("readonly");
  document.getElementById("number").toggleAttribute("readonly");
  document.getElementById("healthy").toggleAttribute("disabled");
  document.getElementById("injured").toggleAttribute("disabled");
  document.getElementById("position").toggleAttribute("disabled");
}

function previousItem() 
{
  console.log("Fetching Previous Item...");
  if (currentIndex > 0) 
  {
    fetchItem(currentIndex - 1);
  }
}

function nextItem() 
{
  console.log("Fetching Next Item...");
  fetchItem(currentIndex + 1);
}

function saveRecord() 
{
  console.log("Save Called On Item: " + currentIndex);
  const request = new XMLHttpRequest();
  request.open("POST", "saveItem.php", true);
  console.log("Request Opened:");
  console.log(request);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  const name = document.getElementById("name").value;
  const team = document.getElementById("team").value;
  const number = document.getElementById("number").value;
  const status = document.querySelector('input[name="status"]:checked').value;
  const position = document.getElementById("position").value;

  request.send(`index=${currentIndex}&name=${name}&team=${team}&number=${number}&status=${status}&position=${position}`);
  console.log("Data Saved!");
}

function deleteItem() 
{
  console.log("Attempting To Delete Item: " + currentIndex);
  const request = new XMLHttpRequest();
  request.open("POST", "deleteItem.php", true);
  console.log("Opened Request:");
  console.log(request);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  request.send(`index=${currentIndex}`);
  if(currentIndex >=1)
  {
    console.log("Fetching Previous Item");
    currentIndex--;
    fetchItem(currentIndex);
  }
  else
  {
    console.log("All Items Deleted, Inserting Empty Item...");
    currentIndex=0;
    insertItem();
    fetchItem(currentIndex);
  }
  console.log("Item Deleted!");
}

function insertItem() 
{
  console.log("Insert Called");
  const request = new XMLHttpRequest();
  request.open("POST", "insertItem.php", true);
  console.log("Request Opened:");
  console.log(request);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  request.onload = function() 
  {
    if (request.status === 200) 
    {
      const response = JSON.parse(request.responseText);
      if (!response.error) 
      {
        console.log("Logging Response New index: " + response.newIndex);
        fetchItem(response.newIndex); // Fetch and display the new item
      } else 
      {
        console.error("Error inserting item:", response.message);
      }
    }
  };

  // Send a blank item (adjust default values as needed)
  request.send(`name=&team=&number=&status=healthy&position=QB`);
  console.log("Insert Complete!");
}

// Load the initial item on page load
document.addEventListener("DOMContentLoaded", () => 
{
    // Make an AJAX request to reset the database
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'resetDb.php', true);
    xhr.onload = function() {
      if (xhr.status === 200) {
        const response = JSON.parse(xhr.responseText);
        if (response.status === 'success') {
          console.log("Database reset successfully");
        } else {
          console.error("Error resetting the database");
        }
      }
    };
    xhr.send();
  
  fetchItem(currentIndex);
});