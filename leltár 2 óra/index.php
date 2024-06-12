<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/x-icon" href="/Leltar/prod/imgs/colored/icons8-quantum-64.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Dashboard</h1>
    <label for="building">Select Building:</label>
    <select id="building">
        <option value="">Select Building</option>
    </select>

    <label for="aisle">Select Aisle:</label>
    <select id="aisle" disabled>
        <option value="">Select Aisle</option>
    </select>

    <div id="search-results"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
             
            function populateBuildings() {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById('building').innerHTML = xhr.responseText;
                        } else {
                            console.error('Error fetching buildings: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'get_buildings.php', true);
                xhr.send();
            }

             
            populateBuildings();

             
            function populateAisles(building) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById('aisle').innerHTML = xhr.responseText;
                            document.getElementById('aisle').disabled = false;
                             
                            document.getElementById('aisle').addEventListener('change', function() {
                                var selectedBuilding = document.getElementById('building').value;
                                var selectedAisle = this.value;
                                search(selectedBuilding, selectedAisle);
                            });
                        } else {
                            console.error('Error fetching aisles: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'get_aisles.php?building=' + encodeURIComponent(building), true);
                xhr.send();
            }

             
            function search(building, aisle) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById('search-results').innerHTML = xhr.responseText;
                        } else {
                            console.error('Error fetching search results: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'search.php?building=' + encodeURIComponent(building) + '&aisle=' + encodeURIComponent(aisle), true);
                xhr.send();
            }

             
            document.getElementById('building').addEventListener('change', function () {
                var selectedBuilding = this.value;
                if (selectedBuilding) {
                    populateAisles(selectedBuilding);
                } else {
                    document.getElementById('aisle').innerHTML = '<option value="">Select Aisle</option>';
                    document.getElementById('aisle').disabled = true;
                }
                 
                search(selectedBuilding, '');
            });
        });
    </script>
</body>
</html>
