<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    
    // Fetch the specific record
    $query = "SELECT * FROM core_values_db WHERE coreID = $id";
    $result = $conn->query($query);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Core Value</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: 'Arial', sans-serif;
                    background-color: #f4f4f4;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    line-height: 1.6;
                }
                .container {
                    background-color: #ffffff;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                    width: 100%;
                    max-width: 500px;
                    padding: 30px;
                }
                .form-title {
                    text-align: center;
                    color: #333;
                    margin-bottom: 20px;
                    font-size: 24px;
                    font-weight: bold;
                }
                .form-group {
                    margin-bottom: 15px;
                }
                label {
                    display: block;
                    margin-bottom: 5px;
                    color: #555;
                    font-weight: bold;
                }
                input, select, textarea {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    font-size: 16px;
                    transition: border-color 0.3s ease;
                }
                input:focus, 
                select:focus, 
                textarea:focus {
                    outline: none;
                    border-color: #4CAF50;
                    box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
                }
                textarea {
                    resize: vertical;
                    min-height: 100px;
                }
                .submit-btn {
                    background-color: #4CAF50;
                    color: white;
                    border: none;
                    padding: 12px 20px;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: background-color 0.3s ease;
                    width: 100%;
                    margin-bottom: 15px;
                }
                .submit-btn:hover {
                    background-color: #45a049;
                }
                .back-btn {
                    display: block;
                    text-align: center;
                    text-decoration: none;
                    background-color: #f44336;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 5px;
                    transition: background-color 0.3s ease;
                }
                .back-btn:hover {
                    background-color: #d32f2f;
                }
                .btn-container {
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                }
                @media (max-width: 600px) {
                    .container {
                        width: 95%;
                        padding: 20px;
                    }
                }
            </style>
        </head>
        <body>
            <div class="container">
                <div class="form-title">Edit Core Value</div>
                <form method="POST" action="update.php">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    
                    <div class="form-group">
                        <label>Core Values:</label>
                        <select name="behavior_type" required>
                            <option value="Maka-Diyos" <?php echo ($row['coreName'] == 'Maka-Diyos') ? 'selected' : ''; ?>>Maka-Diyos</option>
                            <option value="Maka-Tao" <?php echo ($row['coreName'] == 'Maka-Tao') ? 'selected' : ''; ?>>Maka-Tao</option>
                            <option value="Maka-Kalikasan" <?php echo ($row['coreName'] == 'Maka-Kalikasan') ? 'selected' : ''; ?>>Maka-Kalikasan</option>
                            <option value="Maka-Bansa" <?php echo ($row['coreName'] == 'Maka-Bansa') ? 'selected' : ''; ?>>Maka-Bansa</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>First Behavior Statement:</label>
                        <textarea name="first_behavior_statement" required><?php echo htmlspecialchars($row['behaviour_one']); ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Second Behavior Statement:</label>
                        <textarea name="second_behavior_statement" required><?php echo htmlspecialchars($row['behavior_two']); ?></textarea>
                    </div>
                    
                    <div class="btn-container">
                        <button type="submit" class="submit-btn">Update Core Value</button>
                        <a href="card-maintenance.php" class="back-btn">Back to Card Maintenance</a>
                    </div>
                </form>
            </div>
        </body>
        </html>
        <?php
    } else {
        echo "<script>alert('Record not found.'); window.location.href='card-maintenance.php';</script>";
        exit();
    }
}
?>