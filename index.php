<?php
include 'db_connect.php'; // Ensure this file establishes a PDO connection and assigns it to $pdo

// Fetch all records from the database
$stmt = $pdo->prepare("SELECT * FROM members");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agra Sarafa Karigar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
    <style>
        body {
            background-color: #f4f4f4;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 2px solid #ddd;
        }
        
        .footer {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
            position: relative;
            bottom: 0;
            width: 100%;
            border-top: 1px solid #ddd;
        }
        
        .table-container {
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
table td{
    word-wrap: normal !important;
}
        .table img {
            max-width: 100px;
            border-radius: 5px;
        }

        .btn {
            margin: 0 5px;
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>आगरा सर्राफा करीगर व व्यापारी और दुकानदार फ्रॉड</h1>
        <h2>Agra Sarafa Karigar & Vyapari or Dukandar Fraud</h2>
        <a href="login.php" class="btn btn-dark logout-btn">Admin Login <i class="bi bi-person-circle"></i></a>
    </div>
<!-- Add this section where you want the slider to appear -->
<div id="responsiveSlider" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="img/shiv1.jpg" class="d-block w-100" alt="Slide 1" style="height: 80vh;">
        </div>
        <div class="carousel-item">
            <img src="img/shiv2.jpg" class="d-block w-100" alt="Slide 2" style="height: 80vh;">
        </div>
        <div class="carousel-item">
            <img src="img/shiv3.jpg" class="d-block w-100" alt="Slide 3" style="height: 80vh;">
        </div>
        <!-- Add more slides as needed -->
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#responsiveSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#responsiveSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Ensure Bootstrap JS is included -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Table Section -->
    <div class="container table-container table-responsive">
        <h2 class="text-center mb-4">Member List</h2>
        <div class="row">
        <div class="col-md-4">
                <label for="filterState" class="form-label">Filter by State:</label>
                <select id="filterState" class="form-select">
                    <option value="">All States</option>
<option value="Andhra Pradesh">Andhra Pradesh</option>
<option value="Arunachal Pradesh">Arunachal Pradesh</option>
<option value="Assam">Assam</option>
<option value="Bihar">Bihar</option>
<option value="Chhattisgarh">Chhattisgarh</option>
<option value="Goa">Goa</option>
<option value="Gujarat">Gujarat</option>
<option value="Haryana">Haryana</option>
<option value="Himachal Pradesh">Himachal Pradesh</option>
<option value="Jharkhand">Jharkhand</option>
<option value="Karnataka">Karnataka</option>
<option value="Kerala">Kerala</option>
<option value="Madhya Pradesh">Madhya Pradesh</option>
<option value="Maharashtra">Maharashtra</option>
<option value="Manipur">Manipur</option>
<option value="Meghalaya">Meghalaya</option>
<option value="Mizoram">Mizoram</option>
<option value="Nagaland">Nagaland</option>
<option value="Odisha">Odisha</option>
<option value="Punjab">Punjab</option>
<option value="Rajasthan">Rajasthan</option>
<option value="Sikkim">Sikkim</option>
<option value="Tamil Nadu">Tamil Nadu</option>
<option value="Telangana">Telangana</option>
<option value="Tripura">Tripura</option>
<option value="Uttar Pradesh">Uttar Pradesh</option>
<option value="Uttarakhand">Uttarakhand</option>
<option value="West Bengal">West Bengal</option>
<option value="Andaman and Nicobar Islands">Andaman and Nicobar Islands</option>
<option value="Chandigarh">Chandigarh</option>
<option value="Dadra and Nagar Haveli and Daman and Diu">Dadra and Nagar Haveli and Daman and Diu</option>
<option value="Lakshadweep">Lakshadweep</option>
<option value="Delhi">Delhi</option>

                    <!-- Add more states as needed -->
                </select>
            </div>
            <div class="col-md-4">
                <label for="filterwork_type" class="form-label">Filter by Work Type:</label>
                <select id="filterwork_type" class="form-select">
                    <option value="">All Work Type</option>
                    <option value="karigar">Karigar</option>
                    <option value="vyapari">Vyapari</option>
                    <option value="dukandaar">Dukandaar</option>
                </select>
            </div>
        </div>
        <br>
        <table id="memberTable" class="table table-striped table-responsive">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Firm Name</th>
                    <th>Association</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Contact No</th>
                    <th>GST No</th>
                    <th>Work Type</th>
                                       <th>Photo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?php echo $member['id']; ?></td>
                        <td><?php echo htmlspecialchars($member['name']); ?></td>
                        <td><?php echo htmlspecialchars($member['firm_name']); ?></td>
                        <td><?php echo htmlspecialchars($member['association']); ?></td>
                        <td><?php echo htmlspecialchars($member['address']); ?></td>
                        <td><?php echo htmlspecialchars($member['city']); ?></td>
                        <td><?php echo htmlspecialchars($member['state']); ?></td>
                        <td><?php echo htmlspecialchars($member['contact_no']); ?></td>
                        <td><?php echo htmlspecialchars($member['gst_no']); ?></td>
                        <td><?php echo htmlspecialchars($member['work_type']); ?></td>                    
                        <td><img src="<?php echo htmlspecialchars($member['photo']); ?>" alt="Photo"></td>
                   
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php

include 'footer.php';

?>

    <!-- Bootstrap JS and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#memberTable').DataTable({
                responsive: true,
                language: {
                    search: "Search:",
                    lengthMenu: "Display _MENU_ records per page",
                    info: "Showing page _PAGE_ of _PAGES_",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });
        });
    </script>
    <script>
        
            // Filter functionality
            $('#filterState').change(function() {
                const selectedState = $(this).val();
                $('#memberTable tbody tr').each(function() {
                    const rowState = $(this).find('td').eq(6).text(); // Assuming state is in the 7th column (index 6)
                    if (selectedState === "" || rowState === selectedState) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
     
        $(document).ready(function() {
    // Filter functionality for State
    $('#filterState').change(function() {
        const selectedState = $(this).val();
        $('#memberTable tbody tr').each(function() {
            const rowState = $(this).find('td').eq(6).text(); // Assuming state is in the 7th column (index 6)
            if (selectedState === "" || rowState === selectedState) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Filter functionality for work_type
    $('#filterwork_type').change(function() {
        const selectedWorkType = $(this).val();
        $('#memberTable tbody tr').each(function() {
            const rowWorkType = $(this).find('td').eq(9).text(); // Assuming work_type is in the 10th column (index 9)
            if (selectedWorkType === "" || rowWorkType === selectedWorkType) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});
    </script>
    
</body>
</html>