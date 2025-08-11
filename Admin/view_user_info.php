<!-- view_user information -->
        <?php
        require_once("require/database_connection.php");
        require_once("admin_file.php");


        // Check if user_id is set and valid
        if (isset($_GET['user_id']) && is_numeric($_GET['user_id'])) {
            $user_id = (int)$_GET['user_id'];

            // Query to get user details
            $query = "SELECT first_name, last_name, email, address, gender, date_of_birth, is_active, is_approved 
                      FROM user WHERE user_id = $user_id";
            $result = mysqli_query($connection, $query);

            if (mysqli_num_rows($result) == 1) {
                $row = mysqli_fetch_assoc($result);

                echo "<table border='1' cellpadding='10' cellspacing='0' style='border-collapse: collapse; margin: 20px auto; width: 80%; font-family: Arial;'>
                        <thead style='background-color: #f2f2f2;'>
                            <tr>
                                <th>Field</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>";

                foreach ($row as $field => $value) {
                    echo "<tr>
                            <td><strong>" . ucfirst(str_replace('_', ' ', $field)) . "</strong></td>
                            <td>" . htmlspecialchars($value) . "</td>
                          </tr>";
                }

                echo "</tbody></table>";
            } else {
                echo "<p style='color: orange; text-align:center;'>User not found.</p>";
            }

        } else {
            echo "<p style='color: red; text-align:center;'>Invalid or missing user ID.</p>";
        }

        ?>  
            <a href="all_user.php" class="btn btn-secondary">Back</a>
<!-- view_user information -->

