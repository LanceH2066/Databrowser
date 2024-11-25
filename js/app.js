let currentIndex = 1;
let size = 2;
let currentPlayer = null;
let sortMode = 0;

function displayItem(item) 
{  
  document.getElementById("name").value = item.player_name || "";
  document.getElementById("team").value = item.player_team || "";
  document.getElementById("number").value = item.player_number || "";
  document.getElementById("healthy").checked = item.player_status === "healthy";
  document.getElementById("injured").checked = item.player_status === "injured";
  document.getElementById("position").value = item.player_position || "";

  const playerImage = document.getElementById("playerImagePreview");
  if (item.image_path)
  { 
    playerImage.src = item.image_path;
  } 
  else 
  {
    playerImage.src = "";
  }
}

function toggleEdit() 
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

function fetchItem()
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

function fetchPreviousItem() 
{
  if (currentIndex > 1) 
  {
    currentIndex--;
    fetchItem();
  }
}

function fetchFirstItem()
{
  if (currentIndex > 0) 
  {
    currentIndex = 1;
    fetchItem();
  }
}

function fetchNextItem() 
{
  if (currentIndex < size) 
  {
    currentIndex++;
    fetchItem();
  }
}

function fetchLastItem()
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

function insertItem() 
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

function saveRecord() 
{
  const request = new XMLHttpRequest();
  request.open("POST", "saveItem.php", true);
  request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  const id = currentPlayer['id'];
  const name = document.getElementById("name").value;
  const team = document.getElementById("team").value;
  const number = document.getElementById("number").value;
  const status = document.querySelector('input[name="status"]:checked').value;
  const position = document.getElementById("position").value;

  request.send(`id=${id}&player_name=${name}&player_team=${team}&player_number=${number}&player_status=${status}&player_position=${position}`);
}

function deleteItem() 
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

          setTimeout(() => 
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

function toggleSort() 
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

function uploadImage() 
{
  const request = new XMLHttpRequest();
  const formData = new FormData();
  const fileInput = document.getElementById("playerImage");

  // Attach the file
  formData.append("playerImage", fileInput.files[0]);
  // Attach the player ID and any other fields
  formData.append("id", currentPlayer['id']);

  request.open('POST', 'uploadImage.php', true);

  request.onload = function () 
  {
    if (request.status === 200) 
    {
      const response = JSON.parse(request.responseText);
      if (!response.error) 
      {
        document.getElementById('playerImagePreview').src = response.imagePath;
      } 
      else 
      {
        alert(`Error: ${response.error}`);
      }
    } 
    else 
    {
      console.error('Request failed with status:', request.status);
    }
  };

  request.send(formData);
}
