<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Form</title>
</head>
<body>
  <div class="search-container">
    <input type="text" id="searchName" class="search-input" placeholder="Enter Name">
    <input type="text" id="searchHusbandName" class="search-input" placeholder="Enter Husband/Father Name">
    <p style="margin: 10px;">OR</p>
    <input type="text" id="searchAadharCard" class="search-input" placeholder="Enter Aadhar Card No">
    <button onclick="search()">Search</button>
  </div>
  <div class="result-container" id="resultContainer">
    <h2 class="result-title">Search Results</h2>
    <table class="result-table" id="resultTable">
    
    </table>
  </div>
  <script>
function search() {
  var name = document.getElementById('searchName').value.toLowerCase();
  var husbandName = document.getElementById('searchHusbandName').value.toLowerCase();
  var aadharCard = document.getElementById('searchAadharCard').value.toLowerCase();
  var apiUrl = 'search.php'; 
  console.log('Name:', name);
  console.log('Husband Name:', husbandName);
  console.log('Aadhar Card:', aadharCard);
  console.log('API URL:', apiUrl); 
  fetch(apiUrl)
    .then(response => {
      console.log('Response status:', response.status);
      return response.json(); 
    })
    .then(data => {
      console.log('Data from Google Sheets:', data);
      filterAndDisplayResults(data.values, name, husbandName, aadharCard);
    })
    .catch(error => {
      console.error('Error fetching data from server:', error);
      alert('Error fetching data from server. Check the console for details.');
    });
}
</script>
<script>
function filterAndDisplayResults(data, name, husbandName, aadharCard) {
            var resultContainer = document.getElementById('resultContainer');
            var resultTable = document.getElementById('resultTable');
    
            resultTable.innerHTML = '';
    
            if (data && data.length > 0) {
                var headers = data[0];
                var headerRow = resultTable.insertRow();
    
                
                var desiredColumns = [1, 2, 7];
    
                for (var i = 0; i < desiredColumns.length; i++) {
                    var columnIndex = desiredColumns[i];
                    var th = document.createElement('th');
                    th.innerHTML = headers[columnIndex];
                    headerRow.appendChild(th);
                }
    
                for (var j = 1; j < data.length; j++) {
                    var rowData = data[j];
                    
                    if (
                        (name === '' || (rowData[1] && rowData[1].toLowerCase().includes(name))) &&
                        (husbandName === '' || (rowData[2] && rowData[2].toLowerCase().includes(husbandName))) &&
                        (aadharCard === '' || (rowData[5] && rowData[5].toLowerCase().includes(aadharCard)))
                    ) {
                        var row = resultTable.insertRow();
                        for (var k = 0; k < desiredColumns.length; k++) {
                            var columnIndex = desiredColumns[k];
                            var cell = row.insertCell(k);
                            var cellValue = rowData[columnIndex] ? rowData[columnIndex].toString() : ''; 
                            cell.innerHTML = cellValue.toLowerCase(); 
                        }
                    }
                }
    
                resultContainer.style.display = 'block';
            } else {
                resultContainer.style.display = 'none';
            }
        }
    
  </script>
</body>
</html>