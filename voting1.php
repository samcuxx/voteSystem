<!-- Candidate vote bars -->
<div id="candidate-bars">
    <?php
    $current_category = '';
    if ($result_votes) {
        while ($row_votes = $result_votes->fetch_assoc()) {
            if ($current_category != $row_votes['category']) {
                if ($current_category != '') echo '</div>';
                echo "<div class='category-title'>" . htmlspecialchars($row_votes['category']) . "</div>";
                echo "<div class='candidate-bar'>";
                $current_category = $row_votes['category'];
            }
            $total_votes = $row_votes['votes'];
            $bar_width = $total_votes / $total_voted * 100; // Adjust based on total votes

            echo "<div class='progress-container'>
                    <div class='progress-bar' style='width: " . number_format($bar_width, 2) . "%;'>
                        " . htmlspecialchars($row_votes['candidate']) . " - " . $total_votes . " votes
                    </div>
                    </div>";
        }
        echo '</div>'; // Close last category div
    } else {
        echo "Error: " . $conn->error;
    }
    ?>
</div>