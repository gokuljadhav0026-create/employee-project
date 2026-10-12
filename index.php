
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
$page = max(1, (int)($_GET['page'] ?? 1));
$designation = $_GET['designation'] ?? '';


$limit = 2;

// PART 1: Search condition
$where = "";




if ($search !== '') {
   $where = " WHERE (
    name LIKE ?
    OR email LIKE ?
    OR designation LIKE ? )";
}

if($designation !== ''){

if($where !== ''){
        $where .= " AND designation = ?";
}else{
         $where = " WHERE designation = ?";
}

}



$searchTerm = "%" . $search . "%";

// PART 2: Count total matching employees
$countSql = "SELECT COUNT(*) AS total FROM employees" . $where;

$countStmt = $conn->prepare($countSql);

if ($search !== '' && $designation !== '' ) {
    $countStmt->bind_param(
        "ssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $designation
      
    );
}elseif($search !== ''){

        $countStmt->bind_param(
        "sss",
        $searchTerm,
        $searchTerm,
        $searchTerm
      
      
    );
}elseif($designation !== ''){
 
   $countStmt->bind_param(
        "s",
         $designation
      
      
    );

}

$countStmt->execute();

$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();

$totalEmployees = (int)$countRow['total'];

// PART 3: Calculate total pages
$totalPages = (int)ceil($totalEmployees / $limit);

// Keep page within valid range
$page = max(1, min($page, max(1, $totalPages)));

$offset = ($page - 1) * $limit;

// PART 4: Main query
$sql = "SELECT * FROM employees" . $where;

// PART 5: Sorting
if ($sort === 'low_high') {
    $sql .= " ORDER BY salary ASC";
} elseif ($sort === 'high_low') {
    $sql .= " ORDER BY salary DESC";
}

// PART 6: Pagination
$sql .= " LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);

if ($search !== '' && $designation !== '') {
    $stmt->bind_param(
        "ssssii",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $designation,
        $limit,
        $offset
    );

} elseif ($search !== '') {
    $stmt->bind_param(
        "sssii",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $limit,
        $offset
    );

} elseif ($designation !== '') {
    $stmt->bind_param(
        "sii",
        $designation,
        $limit,
        $offset
    );

} else {
    $stmt->bind_param("ii", $limit, $offset);
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

    <input
        type="text"
        name="search"
        placeholder="Search employee"
        value="<?php echo htmlspecialchars($search); ?>"
    >

    <!-- Salary Sorting -->
    <select name="sort">
        <option value=""
            <?php if ($sort === '') echo 'selected'; ?>>
            Default Order
        </option>

        <option value="low_high"
            <?php if ($sort === 'low_high') echo 'selected'; ?>>
            Salary: Low to High
        </option>

        <option value="high_low"
            <?php if ($sort === 'high_low') echo 'selected'; ?>>
            Salary: High to Low
        </option>
    </select>

    <!-- Designation Filter -->
    <select name="designation">
        <option value="">All Designations</option>
        
        <option value="PHP Developer"
            <?php if (($_GET['designation'] ?? '') === 'PHP Developer') echo 'selected'; ?>>
            PHP Developer
        </option>

        <option value="Designer"
            <?php if (($_GET['designation'] ?? '') === 'Designer') echo 'selected'; ?>>
            Designer
        </option>

        <option value="Manager"
            <?php if (($_GET['designation'] ?? '') === 'Manager') echo 'selected'; ?>>
            Manager
        </option>
    </select>

    <button type="submit">Apply</button>

</form>

        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Salary</th>
                    <th>Designation</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>

            <tbody>

                <?php if ($result->num_rows > 0) { ?>

                    <?php while ($row = $result->fetch_assoc()) { ?>

                        <tr>
                            <td>
                                <?php echo htmlspecialchars($row['name']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['email']); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($row['salary']); ?>
                            </td>

                            <td>
                                <span class="designation">
                                    <?php echo htmlspecialchars($row['designation']); ?>
                                </span>
                            </td>

                            <td>
                                <a href="edit.php?id=<?php echo (int)$row['id']; ?>">
                                    Edit Employee
                                </a>
                            </td>

                            <td>
                                <a href="delet.php?id=<?php echo (int)$row['id']; ?>">
                                    Delete Employee
                                </a>
                            </td>
                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="6">No employees found.</td>
                    </tr>

                <?php } ?>

            </tbody>
        </table>

        <!-- PART 7: Pagination links -->

      
<div class="pagination">

    <!-- Previous Button -->
    <?php if ($page > 1) { ?>

        <a href="index.php?search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&page=<?php echo $page - 1; ?>">
            Previous
        </a>

    <?php } ?>


    <!-- Page Numbers -->
    <?php for ($i = 1; $i <= $totalPages; $i++) { ?>

        <a href="index.php?search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&page=<?php echo $i; ?>">
            <?php echo $i; ?>
        </a>

    <?php } ?>


    <!-- Next Button -->
    <?php if ($page < $totalPages) { ?>

        <a href="index.php?search=<?php echo $search; ?>&sort=<?php echo $sort; ?>&page=<?php echo $page + 1; ?>">
            Next
        </a>

    <?php } ?>

</div>


        <p>
            Total employees: <?php echo $totalEmployees; ?>
            | Page <?php echo $page; ?> of <?php echo $totalPages; ?>
        </p>

    </div>
</div>

</body>
</html>
