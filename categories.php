<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
        header('Location: login.php');
        exit;
    }

    if (isset($_SESSION['error'])) {
        echo "<div class='error-msg'>" . $_SESSION['error'] . "</div>";
        unset($_SESSION['error']);
    }

    if (isset($_SESSION['success'])) {
        echo "<div class='success-msg'>" . $_SESSION['success'] . "</div>";
        unset($_SESSION['success']);
    }

    $indexNumber = $_SESSION['indexNumber'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Page</title>
        <link rel="stylesheet" href="assets/css/tablesForms.css">
        <link rel="stylesheet" href="assets/css/main.css">
        <script src="assets/font-awesome/js/all.js" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="assets/font-awesome/css/all.css">
        <script src="assets/js/jquery-3.7.1.js"></script>
    </head>
    <body>

        <?php

            include ('connect.php');

            $sql = "SELECT * FROM categories";
            $result = $conn->query($sql);

        ?>

        <div id="container">
            <!-- The sidebar menu at the left-->
                <?php include('navbar.php') ?>
            <!-- End of sidebar menu at the left side-->

            <!-- The main content at the right-->
            <main id="main">
                <!-- The topbar -->
                <?php include('topbar.php') ?>
                <!-- End the of topbar -->

                <br>

                <!-- Sections for the content -->
                <div id="content">
                    <!-- Button to open new category modal -->
                    <div class="popBtn">
                        <button id="openModalBtn"><i class="fa fa-folder-plus"></i> New Category</button>
                    </div>

                    <!-- Table displaying categories -->
                    <div class="table-con">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Category</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>" . $row["id"]. "</td>
                                                    <td>" . $row["category"]. "</td>
                                                    <td><button class='edit-btn'><i class='fa fa-edit'></i></button> <button class='delete-btn'><i class='fa fa-trash'></i></button></td>
                                                    </tr>";
                                        }
                                    } else {
                                        echo "<tr>
                                                <td colspan='3'>No categories found</td>
                                                </tr>";
                                    }

                                    $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal for new category -->
                    <div id="myModal" class="modal">
                        <!-- Modal content -->
                        <div class="modal-content">
                            <div>
                                <div>New Category</div>
                                <span class="close">&times;</span>
                            </div>
                            <form id="popUpForm" action="add_category.php" method="post">
                                <label for="category">Category</label><br>
                                <input type="text" id="category" name="category" required><br><br>
                                <input id="submit" type="submit" value="Save">
                            </form>
                        </div>
                    </div>

                    <!-- Modal for editing categories -->
                    <div id="myModalEdit" class="modalEdit">
                        <div class="modalEdit-content">
                            <div>
                                <div>Edit Category</div>
                                <span class="close">&times;</span>
                            </div>
                            <form id="popUpFormEdit" action="edit_category.php" method="post">
                                <input type="hidden" id="categoryIdEdit" name="categoryIdEdit">
                                <label for="categoryEdit">Category</label><br>
                                <input type="text" id="categoryEdit" name="categoryEdit" required><br><br>
                                <input id="submitEdit" type="submit" value="Save">
                            </form>
                        </div>
                    </div>

                    <!-- Modal for delete confirmation -->
                    <div id="myModalDelete" class="modalDelete">
                        <div class="modalDelete-content">
                            <div>
                                <div>Delete Category</div>
                                <span class="close">&times;</span>
                            </div>
                            <p>Are you sure you want to delete this category?</p>
                            <div class="delete-buttons">
                                <form id="popUpFormDelete" action="delete_category.php" method="post">
                                    <input type="hidden" id="categoryIdDelete" name="categoryIdDelete">
                                    <button id="confirmDelete" class="delete-confirm">Delete</button>
                                </form>
                                <button id="cancelDelete" class="delete-cancel">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of sections for the content -->

                <!-- footer -->
                <?php include('footer.php')?>
                <!-- End of footer -->

            </main>
            <!-- End of the main content at the right-->
        </div>

        <!-- Add jQuery library -->
        <script>
            // Script for new category modal
            var modal = document.getElementById("myModal");
            var btn = document.getElementById("openModalBtn");
            var span = document.getElementsByClassName("close")[0];

            btn.onclick = function() {
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            // Script for edit category modal
            var modalEdit = document.getElementById("myModalEdit");
            var spanEdit = document.getElementsByClassName("close")[1];
            var editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(function(button) {
                button.onclick = function() {
                    modalEdit.style.display = "block";
                    var row = this.closest('tr');
                    var id = row.children[0].innerText;
                    var category = row.children[1].innerText;
                    document.getElementById('categoryIdEdit').value = id;
                    document.getElementById('categoryEdit').value = category;
                }
            });

            spanEdit.onclick = function() {
                modalEdit.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modalEdit) {
                    modalEdit.style.display = "none";
                }
            }

            // Script for delete confirmation modal
            var modalDelete = document.getElementById("myModalDelete");
            var spanDelete = document.getElementsByClassName("close")[2];
            var deleteButtons = document.querySelectorAll('.delete-btn');
            var cancelDelete = document.getElementById("cancelDelete");
            var deleteRow;

            deleteButtons.forEach(function(button) {
                button.onclick = function() {
                    modalDelete.style.display = "block";
                    var row = this.closest('tr');
                    var id = row.children[0].innerText;
                    document.getElementById('categoryIdDelete').value = id;
                }
            });

            spanDelete.onclick = function() {
                modalDelete.style.display = "none";
            }

            cancelDelete.onclick = function() {
                modalDelete.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modalDelete) {
                    modalDelete.style.display = "none";
                }
            }

            // Handle Add Category Form Submission
            document.getElementById('popUpForm').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('add_category.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("Category uploaded successfully!");
                        modal.style.display = "none";
                        location.reload(); // Reload the page to see the new category
                    } else {
                        alert("An error occurred: " + data.message);
                    }
                });
            });

            // Handle Edit Category Form Submission
            document.getElementById('popUpFormEdit').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('edit_category.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("Category updated successfully!");
                        modalEdit.style.display = "none";
                        location.reload(); // Reload the page to see the updated category
                    } else {
                        alert("An error occurred: " + data.message);
                    }
                });
            });

            // Handle Delete Category Form Submission
            document.getElementById('popUpFormDelete').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('delete_category.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("Category deleted successfully!");
                        modal.style.display = "none";
                        location.reload(); // Reload the page to reflect the deletion
                    } else {
                        alert("An error occurred: " + data.message);
                    }
                });
            });

            // Client-side validation for duplicate category
            document.getElementById('popUpForm').onsubmit = function(event) {
                var newCategory = document.getElementById('category').value.trim().toLowerCase();
                var existingCategories = Array.from(document.querySelectorAll('tbody tr td:nth-child(2)')).map(function(td) {
                    return td.innerText.trim().toLowerCase();
                });

                if (existingCategories.includes(newCategory)) {
                    alert("This category already exists.");
                    event.preventDefault();
                }
            }
        </script>
    </body>                                                                                             
</html>