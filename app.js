let currentIndex = 1;
let size = 2;

function displayItem(item) 
{  
  document.getElementById("name").value = item.player_name || "";
  document.getElementById("team").value = item.player_team || "";
  document.getElementById("number").value = item.player_number || "";
  document.getElementById("healthy").checked = item.player_status === "healthy";
  document.getElementById("injured").checked = item.player_status === "injured";
  document.getElementById("position").value = item.player_position || "";
}

function toggleEdit() 
{
  document.getElementById("name").toggleAttribute("readonly");
  document.getElementById("team").toggleAttribute("readonly");
  document.getElementById("number").toggleAttribute("readonly");
  document.getElementById("healthy").toggleAttribute("disabled");
  document.getElementById("injured").toggleAttribute("disabled");
  document.getElementById("position").toggleAttribute("disabled");
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
        document.getElementById("item-position").textContent = `${currentIndex} / ${response.total_entries}`;
        size = response.total_entries;
      } 
      else 
      {
        alert("Player not found at index " + currentIndex);
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

function fetchNextItem() 
{
  if (currentIndex < size) 
  {
    currentIndex++;
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
        size++;
        currentIndex = size;
        fetchItem(size);
      } 
      else 
      {
        console.error("Error inserting item:", response.message);
      }
    }
  };
  request.send();
}