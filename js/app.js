let currentIndex = 1;         // keep track of current row     
let size = 2;                 // keep track of size, updated every fetch
let currentPlayer = null;     // keep track of the current player
let sortMode = 0;             // keep track of the sorting mode

function displayItem(item)  // Function to display the current player/item
{  
  document.getElementById("name").value = item.player_name || "";
  document.getElementById("team").value = item.player_team || "";
  document.getElementById("number").value = item.player_number || "";
  document.getElementById("healthy").checked = item.player_status === "healthy";
  document.getElementById("injured").checked = item.player_status === "injured";
  document.getElementById("position").value = item.player_position || "";

  const playerImage = document.getElementById("playerImagePreview");
  document.getElementById("playerImage").value = null;
  if (item.image_path)
  { 
    playerImage.src = item.image_path;
  } 
  else 
  {
    playerImage.src = "images/placeholder.png";
  }
}

function toggleEdit()   // Function to enable edit mode
{
  document.getElementById("name").toggleAttribute("readonly");
  document.getElementById("team").toggleAttribute("readonly");
  document.getElementById("number").toggleAttribute("readonly");
  document.getElementById("healthy").toggleAttribute("disabled");
  document.getElementById("injured").toggleAttribute("disabled");
  document.getElementById("position").toggleAttribute("disabled");
  document.getElementById("playerImage").toggleAttribute("disabled");
  document.getElementById("uploadBtn").toggleAttribute("disabled");
}

function fetchItem()  // function to retrive item at current index / row
{
  const request = new XMLHttpRequest();
  request.open("GET", `fetchItem.php?index=${currentIndex}`, true);

  request.onload = function () 
  {
    if (request.status === 200) 
    {
      const response = JSON.parse(request.responseText);
      if (!response.error) 
      {
        displayItem(response.player);
        currentPlayer = response.player;
        document.getElementById("item-position").textContent = `${currentIndex} / ${response.total_entries}`;
        size = response.total_entries;
      } 
      else 
      {
        console.log("Player not found at index " + currentIndex);
      }
    } 
    else 
    {
      console.error("Request failed with status:", request.status);
    }
  };
  request.send();
}

function fetchPreviousItem()  // fetch previous item
{
  if (currentIndex > 1) 
  {
    currentIndex--;
    fetchItem();
  }
}

function fetchFirstItem()  // fetch first item
{
  if (currentIndex > 0) 
  {
    currentIndex = 1;
    fetchItem();
  }
}

function fetchNextItem()   // fetch next item
{
  if (currentIndex < size) 
  {
    currentIndex++;
    fetchItem();
  }
}

function fetchLastItem()   // fetch last item
{
  if (currentIndex > 0) 
  {
    currentIndex = size;
    fetchItem();
  }
}

// Load the initial item on page load
document.addEventListener("DOMContentLoaded", () => 
{ 
  fetchItem(currentIndex);
});

function insertItem()   // Function to insert blank item
{
  const request = new XMLHttpRequest();
  request.open("POST", "insertItem.php", true);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  request.onload = function() 
  {
    if (request.status === 200) 
    {
      const response = JSON.parse(request.responseText);
      if (!response.error) 
      {
        fetchItem(size);
      } 
      else 
      {
        console.error("Error inserting item:", response.message);
      }
    }
  };
  request.send();

  size++;
  currentIndex = size;
}

function saveRecord()       // Function to save all fields to database including image path, also displays image and stores it on server side /uploads folder
{
  const request = new XMLHttpRequest();
  const formData = new FormData();

  // Gather player details
  const id = currentPlayer['id'];
  const name = document.getElementById("name").value;
  const team = document.getElementById("team").value;
  const number = document.getElementById("number").value;
  const status = document.querySelector('input[name="status"]:checked').value;
  const position = document.getElementById("position").value;

  // Attach player details to FormData
  formData.append("id", id);
  formData.append("player_name", name);
  formData.append("player_team", team);
  formData.append("player_number", number);
  formData.append("player_status", status);
  formData.append("player_position", position);

  // Attach image file (if any)
  const fileInput = document.getElementById("playerImage");
  if (fileInput.files.length > 0) 
  {
    formData.append("playerImage", fileInput.files[0]);
  }

  // Send the data via POST
  request.open("POST", "saveItem.php", true);

  request.onload = function () 
  {
      if (request.status === 200) 
        {
          const response = JSON.parse(request.responseText);
          if (response.success) 
          {
            displayItem();
          } 
          else 
          {
            console.error("Error saving record:", response.error || "Unknown error");
          }
      } 
      else 
      {
        console.error("Request failed with status:", request.status);
      }
  };

  request.send(formData);
}

function deleteItem()       // function to delete item from database
{
  const request = new XMLHttpRequest();
  request.open("POST", "deleteItem.php", true);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  const id = parseInt(currentPlayer['id']);

  request.onreadystatechange = function () 
  {
    if (request.readyState === 4 && request.status === 200) 
    {
      const response = JSON.parse(request.responseText);
      if (response.success) 
      {
        if (size > 1) 
        { 
          // More than one item
          if (currentIndex >= 2) 
          {
            currentIndex--;
            size--;
            fetchItem(currentIndex);
          } 
          else // Delete item 1 but size > 1
          {
            size--;
            fetchItem(currentIndex);
          }
        } 
        else 
        { 
          // Only one item
          size = 0;
          currentIndex = 0;
          insertItem();

          setTimeout(() => // prevent unfavorable interleaving where fetch executes before insert causing errors
          {
            fetchItem(currentIndex);
          }, 3000);
        }
      } 
      else 
      {
        console.error("Failed to delete item:", response.error || "Unknown error");
      }
    }
  };

  request.send(`id=${id}`);

}

function toggleSort()     // function to sort, really just sets a session variable called sortMode that the fetch item uses to determine the order
{
  const request = new XMLHttpRequest();
  request.open("POST", "sortItems.php", true);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  if(sortMode == 0)
  {
    sortMode = 1;
  }
  else 
  {
    sortMode = 0;
  }

  request.onreadystatechange = function () 
  {
    if (request.readyState === 4 && request.status === 200) 
    {
      const response = JSON.parse(request.responseText);
      if (response.success) 
      {
        fetchItem(currentIndex);
      } 
      else 
      {
        console.error("Failed to sort items:", response.error || "Unknown error");
      }
    }
  };

  request.send(`sortMode=${sortMode}`);
}