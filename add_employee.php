<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>
    <style>
        body {
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
        }
        button {
            background:rgb(43, 120, 236);
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
        }
        .loader {
            display: none;
            width: 30px;
            height: 30px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            margin: 10px auto;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .message {
            padding: 10px;
            margin-top: 10px;
            display: none;
        }
        .success { background: #dff0d8; }
        .error { background: #f2dede; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Employee</h2>
        <form id="employeeForm">
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" required>
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Department:</label>
                <select name="department" required>
                    <?php
                    require_once 'db_functions.php';
                    $departments = getDepartments();
                    foreach ($departments as $dept) {
                        echo "<option value='{$dept['id']}'>{$dept['name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Phone:</label>
                <input type="tel" name="phone" required>
            </div>
            <button type="submit">Add Employee</button>
        </form>
        <div class="loader"></div>
        <div id="message" class="message"></div>
    </div>

    <script>
    const form = document.getElementById('employeeForm');
    const loader = document.querySelector('.loader');
    const message = document.getElementById('message');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        // Show loader, hide message
        loader.style.display = 'block';
        message.style.display = 'none';
        
        // Add minimal delay to ensure loader is visible
        await new Promise(resolve => setTimeout(resolve, 500));
        
        try {
            const response = await fetch('submit_employee.php', {
                method: 'POST',
                body: new FormData(form)
            });
            
            const data = await response.json();
            
            // Show message
            message.style.display = 'block';
            message.className = `message ${data.success ? 'success' : 'error'}`;
            message.textContent = data.message;
            
            // Reset form if successful
            if (data.success) {
                form.reset();
            }
        } catch (error) {
            message.style.display = 'block';
            message.className = 'message error';
            message.textContent = 'Server error. Please try again.';
        } finally {
            loader.style.display = 'none';
        }
    });
</script>
</body>
</html>