<!DOCTYPE html>
<html lang="en">

<head>
    <title>Felhasználói felület</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</head>
<style>
    * {
        box-sizing: border-box;
    }

    #myInput {
        background-image: url('/css/searchicon.png');
        background-position: 10px 12px;
        background-repeat: no-repeat;
        width: 100%;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
    }

    #myUL {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    #myUL li a {
        border: 1px solid #ddd;
        margin-top: -1px;
        background-color: #f6f6f6;
        padding: 12px;
        text-decoration: none;
        font-size: 18px;
        color: black;
        display: block
    }

    #myUL li a:hover:not(.header) {
        background-color: #eee;
    }

    body {
        background-color: lightgrey;
    }
</style>

<body>
    <div class="container p-5 mt-5" style="background-color: white; border-radius: 25px;">
        <h1 class="mt-5">Leltár</h1>
        <br>

        <h3>Tárgy Keresése</h3>
        <input type="text" id="itemSearch" placeholder="Tárgy neve">
        <br><br>

        <ul id="myUL">
            
        </ul>
        <br>

        <h3>Darabszám Megadása</h3>
        <input type="number" id="itemNum" min="0">
        <br><br>

        <h3>Tárgy Információ</h3>
         
            <label for="building">Select Building:</label>
        <select id="building">
            <option value="">Select Building</option>
        </select>

        <label for="aisle">Select Aisle:</label>
        <select id="aisle" disabled>
            <option value="">Select Aisle</option>
        </select>
         
        <input type="text" id="shelf" placeholder="Polc">
        <input type="number" id="measurment" min="0" placeholder="Mennyiség">
        <input type="text"  placeholder="Mértékegység">

        
    </div>

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

            search('', '', '')

             
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
                                search(selectedBuilding, selectedAisle, '');
                            });
                        } else {
                            console.error('Error fetching aisles: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'get_aisles.php?building=' + encodeURIComponent(building), true);
                xhr.send();
            }

             
            function search(building, aisle, searchTerm) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById('myUL').innerHTML = xhr.responseText;
                        } else {
                            console.error('Error fetching search results: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'search.php?building=' + encodeURIComponent(building) + '&aisle=' + encodeURIComponent(aisle) + '&search=' + encodeURIComponent(searchTerm), true);
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
                 
                search(selectedBuilding, '', '');
            });

             
            document.getElementById('itemSearch').addEventListener('input', function () {
                var selectedBuilding = document.getElementById('building').value;
                var selectedAisle = document.getElementById('aisle').value;
                var searchTerm = this.value;
                search(selectedBuilding, selectedAisle, searchTerm);
            });
        });
</script>
</body>

<?php

    require "conn.php";

?>


</html>