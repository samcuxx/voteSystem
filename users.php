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
        
            include("connect.php");

            $sql = "SELECT id, indexNumber, role FROM users";
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
                    <!-- Button to open new user modal -->
                    <div class="popBtn">
                        <button id="openModalBtn"><i class="fa fa-user-plus"></i> New User</button>
                    </div>

                    <!-- Table displaying users -->
                    <div class="table-con">
                        <table>
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Index Number</th>
                                    <th>Role</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<tr>
                                                    <td>{$row['id']}</td>
                                                    <td>{$row['indexNumber']}</td>
                                                    <td>{$row['role']}</td>
                                                    <td>
                                                        <button class='edit-btn'><i class='fa fa-user-edit'></i></button>
                                                        <button class='delete-btn'><i class='fa fa-trash'></i></button>
                                                    </td>
                                                    </tr>";
                                        }
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Modal for new user -->
                    <div id="myModal" class="modal">
                        <div class="modal-content">
                            <div>
                                <div>New User</div>
                                <span class="close">&times;</span>
                            </div>
                            <form id="popUpForm" action="add_user.php" method="post">
                                <label for="indexNumber">Index Number</label><br>
                                <input type="text" id="indexNumber" name="indexNumber" pattern="\d{12}" title="Please enter a 12-digit index number" required><br>
                                <label for="password">Password</label><br>
                                <input type="password" id="password" name="password" required><br>
                                <label for="role">Role</label><br>
                                <select id="role" name="role" required>
                                    <option value="admin">Admin</option>
                                    <option value="voter">Voter</option>
                                </select><br><br>
                                <input id="submit" type="submit" value="Save">
                            </form>
                        </div>
                    </div>

                    <!-- Modal for editing users -->
                    <div id="myModalEdit" class="modalEdit">
                        <div class="modalEdit-content">
                            <div>
                                <div>Edit User</div>
                                <span class="close">&times;</span>
                            </div>
                            <form id="popUpFormEdit" action="edit_user.php" method="post">
                                <input type="hidden" id="userIdEdit" name="userIdEdit">
                                <label for="indexNumberEdit">Index Number</label><br>
                                <input type="text" id="indexNumberEdit" name="indexNumberEdit" required><br>
                                <label for="passwordEdit">Password</label><br>
                                <input type="password" id="passwordEdit" name="passwordEdit" required><br>
                                <label for="roleEdit">Role</label><br>
                                <select id="roleEdit" name="roleEdit" required>
                                    <option value="admin">Admin</option>
                                    <option value="voter">Voter</option>
                                </select><br><br>
                                <input id="submitEdit" type="submit" value="Save">
                            </form>
                        </div>
                    </div>

                    <!-- Modal for delete confirmation -->
                    <div id="myModalDelete" class="modalDelete">
                        <div class="modalDelete-content">
                            <div>
                                <div>Delete User</div>
                                <span class="close">&times;</span>
                            </div>
                            <p>Are you sure you want to delete this user?</p>
                            <div class="delete-buttons">
                                <form id="popUpFormDelete" action="delete_user.php" method="post">
                                    <input type="hidden" id="userIdDelete" name="userIdDelete">
                                    <button id="confirmDelete" class="delete-confirm">Delete</button>
                                </form>
                                <button id="cancelDelete" class="delete-cancel">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End of sections for the content -->

                <!-- Footer -->
                <?php include('footer.php')?>
                <!-- End of footer -->

            </main>
            <!-- End of the main content at the right-->
        </div>

        <!-- Add jQuery library -->
        <script>
            // Script for new user modal
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

            // Script for edit user modal
            var modalEdit = document.getElementById("myModalEdit");
            var spanEdit = document.getElementsByClassName("close")[1];
            var editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(function(button) {
                button.onclick = function() {
                    modalEdit.style.display = "block";
                    var row = this.closest('tr');
                    var id = row.children[0].innerText;
                    var indexNumber = row.children[1].innerText;
                    var role = row.children[2].innerText;
                    document.getElementById('userIdEdit').value = id;
                    document.getElementById('indexNumberEdit').value = indexNumber;
                    document.getElementById('roleEdit').value = role;
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
            var confirmDelete = document.getElementById("confirmDelete");
            var cancelDelete = document.getElementById("cancelDelete");
            var deleteRow;
            var deleteUserId;

            deleteButtons.forEach(function(button) {
                button.onclick = function() {
                    modalDelete.style.display = "block";
                    deleteRow = this.closest('tr');
                    deleteUserId = deleteRow.children[0].innerText;
                }
            });

            confirmDelete.onclick = function() {
                fetch('delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userId: deleteUserId })
                }).then(response => {
                    if (response.ok) {
                        deleteRow.remove();
                        modalDelete.style.display = "none";
                    } else {
                        alert('Failed to delete user.');
                    }
                });
            }

            cancelDelete.onclick = function() {
                modalDelete.style.display = "none";
            }

            spanDelete.onclick = function() {
                modalDelete.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modalDelete) {
                    modalDelete.style.display = "none";
                }
            }

            // Handle Add User Form Submission
            document.getElementById('popUpForm').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('add_user.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("User uploaded successfully!");
                        modal.style.display = "none";
                        location.reload(); // Reload the page to see the new user
                    } else {
                        alert("An error occurred: " + data.message);
                    }
                });
            });

            // Handle Edit User Form Submission
            document.getElementById('popUpFormEdit').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('edit_user.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("User updated successfully!");
                        modalEdit.style.display = "none";
                        location.reload(); // Reload the page to see the updated user
                    } else {
                        alert("An error occurred: " + data.message);
                    }
                });
            });

            // Handle Delete User Form Submission
            document.getElementById('popUpFormDelete').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('delete_user.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("User deleted successfully!");
                        modal.style.display = "none";
                        location.reload(); // Reload the page to reflect the deletion
                    } else {
                        alert("An error occurred: " + data.message);
                    }
                });
            });

            // Client-side validation for duplicate user
            document.getElementById('popUpForm').onsubmit = function(event) {
                var newUser = document.getElementById('user').value.trim().toLowerCase();
                var existingCategories = Array.from(document.querySelectorAll('tbody tr td:nth-child(2)')).map(function(td) {
                    return td.innerText.trim().toLowerCase();
                });

                if (existingCategories.includes(newUser)) {
                    alert("This user already exists.");
                    event.preventDefault();
                }
            }
            
        </script>
    </body>
</html>