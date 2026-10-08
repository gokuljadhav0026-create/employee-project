<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Form</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #444;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4f46e5;
        }

        .submit-btn {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #4f46e5;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        .submit-btn:hover {
            background: #3730a3;
        }
    </style>
</head>

<body>

    <div class="form-container">
        

        <h2>Form</h2>

        <form action="insert.php" method="POST">

            <div class="form-group">
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter your name"
                    value="">
                <?php if (isset($error["name"])): ?>
                    <span style="color:red;">
                        <?= $error["name"] ?>
                    </span>
                <?php endif; ?>

            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email">
                <?php if (isset($error["email"])): ?>
                    <span style="color:red;">
                        <?= $error["email"] ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="salary">Salary</label>
                <input
                    type="number"
                    id="salary"
                    name="salary"
                    placeholder="Enter salary"
                    min="0">
            </div>

            <div class="form-group">
                <label for="designation">Designation</label>
                <select id="designation" name="designation">
                    <option value="">Select Designation</option>
                    <option value="WordPress Developer">WordPress Developer</option>
                    <option value="PHP Developer">PHP Developer</option>
                    <option value="Frontend Developer">Frontend Developer</option>
                    <option value="Backend Developer">Backend Developer</option>
                    <option value="Full Stack Developer">Full Stack Developer</option>
                </select>
            </div>

            <button type="submit" name="submit" class="submit-btn">
                Add Employee
            </button>

        </form>

    </div>

</body>

</html>