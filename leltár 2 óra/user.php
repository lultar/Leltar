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
</style>

<body>
    <div class="container">
        <h1 class="mt-5">Leltár</h1>
        <br>

        <h3>Tárgy Keresése</h3>
        <input type="text" id="itemSearch" onkeyup="myFunction()" placeholder="Tárgy neve">

        <ul id="myUL">
            <li><a href="#">Adele</a></li>
            <li><a href="#">Agnes</a></li>

            <li><a href="#">Billy</a></li>
            <li><a href="#">Bob</a></li>

            <li><a href="#">Calvin</a></li>
            <li><a href="#">Christina</a></li>
            <li><a href="#">Cindy</a></li>
        </ul>
        <br>

        <h3>Darabszám Megadása</h3>
        <input type="number" id="itemNum" min="0">
        <br><br>

        <h3>Tárgy Információ</h3>
        <input type="text" id="warehouse" placeholder="Raktár">
        <input type="text" id="aisles" placeholder="Sor">
        <input type="text" id="shelf" placeholder="Polc">
        <input type="text" id="measurment" placeholder="Mennyiség">
        <input type="text" id="measurmentType" placeholder="Mértékegység">
        
    </div>
</body>

<?php

    require "conn.php";

?>

<script>
    function myFunction() {
        var input, filter, ul, li, a, i, txtValue;
        input = document.getElementById("itemSearch");
        filter = input.value.toUpperCase();
        ul = document.getElementById("myUL");
        li = ul.getElementsByTagName("li");
        for (i = 0; i < li.length; i++) {
            a = li[i].getElementsByTagName("a")[0];
            txtValue = a.textContent || a.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
            } else {
                li[i].style.display = "none";
            }
        }
    }
</script>

</html>