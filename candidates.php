<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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

            $sql = "SELECT candidates.*, categories.category FROM candidates JOIN categories ON candidates.category_id = categories.id";
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
                <!-- Button to open new candidate modal -->
                <div class="popBtn">
                    <button id="openModalBtn"><i class="fa fa-user-plus"></i> New Candidate</button>
                </div>

                <!-- Table displaying candidate -->
                <div class="table-con">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Candidate Name</th>
                                <th>Category</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['id']}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['category']}</td>
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

                <!-- Modal for new candidate-->
                <div id="myModal" class="modal">
                    <div class="modal-content">
                        <div>
                            <div>New Candidate</div>
                            <span class="close">&times;</span>
                        </div>
                        <form id="popUpForm" enctype="multipart/form-data" action="add_candidate.php" method="POST">
                            <label for="category">Category</label><br>
                            <select id="category" name="category" required>
                            <?php
                                $sql = "SELECT * FROM categories";
                                $result = $conn->query($sql);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['category']}</option>";
                                }
                            ?>
                            </select><br><br>
                            <label for="candidate">Candidate Name</label><br>
                            <input type="text" id="candidate" name="candidate" required><br>
                            <label for="picture">Picture</label><br>
                            <input type="file" id="picture" name="picture" required><br>
                            <input id="submit" type="submit" value="Save">
                        </form>
                    </div>
                </div>

                <!-- Modal for editing users -->
                <div id="myModalEdit" class="modalEdit">
                    <div class="modalEdit-content">
                        <div>
                            <div>Edit Candidate</div>
                            <span class="close">&times;</span>
                        </div>
                        <form id="popUpFormEdit" action="edit_candidate.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" id="candidateIdEdit" name="candidateIdEdit">
                            <label for="categoryEdit">Category</label><br>
                            <select id="categoryEdit" name="categoryEdit" required>
                                <?php
                                    $result = mysqli_query($conn, "SELECT * FROM categories");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo "<option value='{$row['id']}'>{$row['category']}</option>";
                                    }
                                ?>
                            </select><br><br>
                            <label for="candidateEdit">Candidate Name</label><br>
                            <input type="text" id="candidateEdit" name="candidateEdit" required><br>
                            <label for="pictureEdit">Picture</label><br>
                            <input type="file" id="pictureEdit" name="pictureEdit" required><br>
                            <input id="submitEdit" type="submit" value="Save">
                        </form>
                    </div>
                </div>

                <!-- Modal for delete confirmation -->
                <div id="myModalDelete" class="modalDelete">
                    <div class="modalDelete-content">
                        <div>
                            <div>Delete Candidate</div>
                            <span class="close">&times;</span>
                        </div>
                        <p>Are you sure you want to delete this candidate?</p>
                        <div class="delete-buttons">
                            <form id="popUpFormDelete" action="delete_candidate.php" method="post">
                                <input type="hidden" id="candidateIdDelete" name="candidateIdDelete">
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
            // Script for Add Candidate Modal
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

            // Script for Edit Candidate Modal
            var modalEdit = document.getElementById("myModalEdit");
            var spanEdit = document.getElementsByClassName("close")[1];
            var editButtons = document.querySelectorAll('.edit-btn');

            editButtons.forEach(function(button) {
                button.onclick = function() {
                    modalEdit.style.display = "block";
                    var row = this.closest('tr');
                    var id = row.children[0].innerText;
                    var candidate = row.children[1].innerText;
                    var category = row.children[2].innerText;
                    document.getElementById('candidateIdEdit').value = id;
                    document.getElementById('candidateEdit').value = candidate;
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

            // Script for Delete Confirmation Modal
            var modalDelete = document.getElementById("myModalDelete");
            var spanDelete = document.getElementsByClassName("close")[2];
            var deleteButtons = document.querySelectorAll('.delete-btn');
            var confirmDelete = document.getElementById("confirmDelete");
            var cancelDelete = document.getElementById("cancelDelete");
            var deleteRow;
            var deleteCandidateId;

            deleteButtons.forEach(function(button) {
                button.onclick = function() {
                    modalDelete.style.display = "block";
                    deleteRow = this.closest('tr');
                    deleteCandidateId = deleteRow.children[0].innerText;
                }
            });

            confirmDelete.onclick = function() {
                fetch('delete_candidate.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ userId: deleteCandidateId })
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        deleteRow.remove();
                        modalDelete.style.display = "none";
                    } else {
                        alert('Failed to delete candidate: ' + data.message);
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

            // Handle Add Candidate Form Submission
            document.getElementById('popUpForm').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('add_candidate.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("Candidate uploaded successfully!");
                        modal.style.display = "none";
                        location.reload(); // Reload the page to see the new candidate
                    } else {
                        alert("An error occurred: " + data.message);
                    }
                });
            });

            // Handle Edit Candidate Form Submission
            document.getElementById('popUpFormEdit').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('edit_candidate.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("Candidate updated successfully!");
                        modalEdit.style.display = "none";
                        location.reload(); // Reload the page to see the updated candidate
                    } else {
                        alert("An error occurred: " + data.message);
                    }
                });
            });

            // Handle Delete Category Form Submission
            document.getElementById('popUpFormDelete').addEventListener('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('delete_candidate.php', {
                    method: 'POST',
                    body: formData
                }).then(response => response.json()).then(data => {
                    if (data.success) {
                        alert("Candidate deleted successfully!");
                        modal.style.display = "none";
                        location.reload(); // Reload the page to reflect the deletion
                    } else {
                        alert(data.message);
                    }
                });
            });

            // Client-side validation for duplicate category
            document.getElementById('popUpForm').onsubmit = function(event) {
                var newCandidateName = document.getElementById('candidate').value.trim().toLowerCase();
                var existingCandidates = Array.from(document.querySelectorAll('tbody tr td:nth-child(2)')).map(function(td) {
                    return td.innerText.trim().toLowerCase();
                });

                if (existingCandidates.includes(newCandidateName)) {
                    alert("This candidate already exists.");
                    event.preventDefault();
                }
            }

            // Client-side validation for duplicate candidate in edit form
            document.getElementById('popUpFormEdit').onsubmit = function(event) {
                var updatedCandidateName = document.getElementById('candidateEdit').value.trim().toLowerCase();
                var existingCandidates = Array.from(document.querySelectorAll('tbody tr td:nth-child(2)')).map(function(td) {
                    return td.innerText.trim().toLowerCase();
                });

                // Exclude the candidate being edited
                var candidateId = document.getElementById('candidateIdEdit').value;
                var candidateRow = Array.from(document.querySelectorAll('tbody tr')).find(row => row.children[0].innerText === candidateId);
                if (candidateRow) {
                    existingCandidates = existingCandidates.filter(name => name !== updatedCandidateName);
                }

                if (existingCandidates.includes(updatedCandidateName)) {
                    alert("This candidate already exists.");
                    event.preventDefault();
                }
            }
        </script>
    </body>                                                                                             
</html>