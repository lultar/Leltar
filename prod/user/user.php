<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Spinner styles */
        .spinner {
            display: none;
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #3498db;
            animation: spin 1s linear infinite;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Overlay styles */
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        <div class="form-group">
            <label for="building">Select Building:</label>
            <select id="building" class="form-control">
                <option value="">Select Building</option>
            </select>
        </div>

        <div class="form-group">
            <label for="aisle">Select Aisle:</label>
            <select id="aisle" class="form-control" disabled>
                <option value="">Select Aisle</option>
            </select>
        </div>

        <div class="form-group">
            <label for="search-bar">Search Items:</label>
            <input type="text" id="search-bar" class="form-control" placeholder="Search by name...">
        </div>

        <div id="search-results"></div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="form-group">
                            <label for="itemName">Item Name</label>
                            <input type="text" class="form-control" id="itemName" readonly>
                        </div>
                        <div class="form-group">
                            <label for="itemQuantity">Quantity</label>
                            <input type="number" class="form-control" id="itemQuantity">
                        </div>
                        <div class="form-group">
                            <label for="itemShelf">Shelf</label>
                            <input type="text" class="form-control" id="itemShelf">
                        </div>
                        <div class="form-group">
                            <label for="itemBuilding">Building</label>
                            <select class="form-control" id="itemBuilding">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="itemAisle">Aisle</label>
                            <select class="form-control" id="itemAisle">
                                <!-- Options will be populated dynamically -->
                            </select>
                        </div>
                        <input type="hidden" id="itemId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveChanges()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Spinner and Overlay -->
    <div class="overlay"></div>
    <div class="spinner"></div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function showSpinner() {
                document.querySelector('.overlay').style.display = 'block';
                document.querySelector('.spinner').style.display = 'block';
            }

            function hideSpinner() {
                document.querySelector('.overlay').style.display = 'none';
                document.querySelector('.spinner').style.display = 'none';
            }

            function populateBuildings() {
                showSpinner();
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById('building').innerHTML = xhr.responseText;
                            document.getElementById('itemBuilding').innerHTML = xhr.responseText;
                        } else {
                            console.error('Error fetching buildings: ' + xhr.status);
                        }
                        hideSpinner();
                    }
                };
                xhr.open('GET', 'get_buildings.php', true);
                xhr.send();
            }
            search("", "", "");

            populateBuildings();

            function populateAisles(building, targetId, currentAisle) {
                showSpinner();
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById(targetId).innerHTML = xhr.responseText;
                            if (targetId === 'aisle') {
                                document.getElementById(targetId).disabled = false;
                                document.getElementById(targetId).addEventListener('change', function () {
                                    var selectedBuilding = document.getElementById('building').value;
                                    var selectedAisle = this.value;
                                    search(selectedBuilding, selectedAisle, '');
                                });
                                if (currentAisle) {
                                    document.getElementById(targetId).value = currentAisle;
                                }
                            }
                        } else {
                            console.error('Error fetching aisles: ' + xhr.status);
                        }
                        hideSpinner();
                    }
                };
                xhr.open('GET', 'get_aisles.php?building=' + encodeURIComponent(building), true);
                xhr.send();
            }

            function search(building, aisle, searchTerm) {
                showSpinner();
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            document.getElementById('search-results').innerHTML = xhr.responseText;
                            addEditButtons();
                        } else {
                            console.error('Error fetching search results: ' + xhr.status);
                        }
                        hideSpinner();
                    }
                };
                xhr.open('GET', 'search.php?building=' + encodeURIComponent(building) + '&aisle=' + encodeURIComponent(aisle) + '&search=' + encodeURIComponent(searchTerm), true);
                xhr.send();
            }

            function addEditButtons() {
                document.querySelectorAll('.edit-button').forEach(function (button) {
                    button.addEventListener('click', function () {
                        var itemId = this.dataset.itemId;
                        var itemName = this.dataset.itemName;
                        var itemQuantity = this.dataset.itemQuantity;
                        var itemShelf = this.dataset.itemShelf;
                        var itemBuilding = document.getElementById('building').value;
                        var itemAisle = document.getElementById('aisle').value;

                        document.getElementById('itemId').value = itemId;
                        document.getElementById('itemName').value = itemName;
                        document.getElementById('itemQuantity').value = itemQuantity;
                        document.getElementById('itemShelf').value = itemShelf;

                        document.getElementById('itemBuilding').value = itemBuilding;
                        populateAisles(itemBuilding, 'itemAisle', itemAisle);

                        $('#editModal').modal('show');
                    });
                });
            }

            window.saveChanges = function () {
                showSpinner();
                var itemId = document.getElementById('itemId').value;
                var itemQuantity = document.getElementById('itemQuantity').value;
                var itemShelf = document.getElementById('itemShelf').value;
                var itemBuilding = document.getElementById('itemBuilding').value;
                var itemAisle = document.getElementById('itemAisle').value;

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert('Changes saved successfully');
                            $('#editModal').modal('hide');
                            var selectedBuilding = document.getElementById('building').value;
                            var selectedAisle = document.getElementById('aisle').value;
                            search(selectedBuilding, selectedAisle, '');
                        } else {
                            alert('Error saving changes: ' + xhr.status);
                        }
                        hideSpinner();
                    }
                };
                xhr.open('POST', 'update_item.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send('itemId=' + encodeURIComponent(itemId) + '&itemQuantity=' + encodeURIComponent(itemQuantity) + '&itemShelf=' + encodeURIComponent(itemShelf) + '&itemBuilding=' + encodeURIComponent(itemBuilding) + '&itemAisle=' + encodeURIComponent(itemAisle));
            }

            document.getElementById('building').addEventListener('change', function () {
                var selectedBuilding = this.value;
                if (selectedBuilding) {
                    populateAisles(selectedBuilding, 'aisle');
                } else {
                    document.getElementById('aisle').innerHTML = '<option value="">Select Aisle</option>';
                    document.getElementById('aisle').disabled = true;
                }
                search(selectedBuilding, '', '');
            });

            document.getElementById('search-bar').addEventListener('input', function () {
                var selectedBuilding = document.getElementById('building').value;
                var selectedAisle = document.getElementById('aisle').value;
                var searchTerm = this.value;
                search(selectedBuilding, selectedAisle, searchTerm);
            });
        });
    </script>
</body>
</html>
