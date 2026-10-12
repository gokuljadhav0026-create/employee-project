<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee List</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6fb;
            padding: 40px 20px;
        }

        .table-container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }

        .table-header {
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: white;
            padding: 25px 30px;
        }

        .table-header h2 {
            font-size: 28px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 650px;
        }

        thead {
            background: #eef2ff;
        }

        th,
        td {
            padding: 20px 25px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            color: #1e293b;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            color: #374151;
            font-size: 15px;
        }

        tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        tbody tr:hover {
            background: #eef2ff;
            transition: 0.2s;
        }

        .designation {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 20px;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 13px;
            font-weight: bold;
            white-space: nowrap;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
            }

            .table-header {
                padding: 20px;
            }

            .table-header h2 {
                font-size: 23px;
            }

            th,
            td {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <?php
    include "connection.php";
    $search = $_GET['search'] ?? '';
    $sort = $_GET['sort'] ?? '';
  

    $sql = "SELECT * FROM employees";

    if ($search !== '') {

        $sql .= " WHERE name LIKE ? OR email LIKE ? OR designation LIKE ?";
        // $stmt = $conn->prepare($sql);
        // $searchTerm = "%" . $search . "%";
        // $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        // $stmt->execute();
        // $result = $stmt->get_result();
    }
    if ($sort == 'low_high') {
        $sql .= " ORDER BY salary ASC";
    } elseif ($sort == 'high_low') {

     $sql .= " ORDER BY salary DESC";
    }
     $stmt = $conn->prepare($sql);

    if ($search !== '') {
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    
    }
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <div class="table-container">

        <div class="table-header">
            <h2>List</h2>
        </div>

        <div class="table-wrapper">
            <form method="GET" action="index.php">

                <input type="text" name="search" placeholder="Search employee">
                <select name="sort">
                    <option value="" <?php if ($sort === '') echo 'selected'; ?>>Default Order</option>

                    <option value="low_high" <?php if ($sort === 'low_high') echo 'selected'; ?>> Salary: Low to High </option>

                    <option value="high_low" <?php if ($sort === 'high_low') echo 'selected'; ?>>Salary: High to Low </option>
                </select>


                <button type="submit">Search</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Salary</th>
                        <th>Designation</th>
                    </tr>
                </thead>
                <tbody>

                    <?php



                    // Process the result set
                    if ($result->num_rows > 0) { ?>



                        <?php while ($row = $result->fetch_assoc()) { ?>

                            <tr>
                                <td><?php echo $row['name'] ?></td>
                                <td><?php echo $row['email'] ?></td>
                                <td><?php echo $row['salary'] ?></td>
                                <td><span class="designation"><?php echo $row['designation'] ?></span></td>
                                <td><span class="designation"><a href="edit.php?id=<?php echo $row['id'] ?>">Edit Employee</a></span></td>
                                <td><span class="designation"><a href="delet.php?id=<?php echo $row['id'] ?>">Delet Employee</a></span></td>
                            </tr>
                        <?php   }
                    } else { ?>
                        <tr>
                            <td colspan="6">No employees found.</td>
                        </tr>
                    <?php  }

                    ?>





                </tbody>
            </table>
            <a href='index.php?page="1"'>"page"</a></li>
        </div>

    </div>

</body>

</html>