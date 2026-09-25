<?php
session_start();
include 'db_connect.php'; // Ensure this file establishes a PDO connection and assigns it to $pdo

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch all records from the database
$stmt = $pdo->prepare("SELECT * FROM members");
$stmt->execute();
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total members
$total_members = count($members);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sarafa Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
    <style>
        .dashboard-container {
            padding: 20px;
        }
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .photo-preview {
            max-width: 200px;
            margin-top: 10px;
        }
        .table-container {
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            overflow-x: auto;
        }
table td{
word-wrap: normal !important;
}
        .table img {
            max-width: 100px;
            border-radius: 5px;
        }
        .header, .footer {
            background-color: #007bff;
            color: white;
            padding: 10px;
            text-align: center;
        }
        .header h1, .header h2 {
            margin: 0;
        }
        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: auto;
            max-width: 100%;
        }
        .total-members-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 20px;
        }
        .addnew-btn {
            position: absolute;
            top: 150px;
            right: 150px;
            width: auto;
            max-width: 100%;
        }
        .table-container {
            overflow-x: auto; /* Enable horizontal scrolling */
        }
        .addnew-btn, .logout-btn {
            width: auto; /* Ensure buttons are responsive */
            max-width: 100%; /* Prevent overflow */
        }
    </style>
</head>
<body class="bg-light">
    <!-- Header -->
    <div class="header">
        <h1>आगरा सर्राफा करीगर व व्यापारी और दुकानदार फ्रॉड</h1>
        <h2>Agra Sarafa Karigar & Vyapari or Dukandar Fraud</h2>
        <a href="logout.php" class="btn btn-danger logout-btn">Logout <i class="bi bi-person-circle"></i></a>
    </div>

    <div class="table-container">
        <div class="total-members-box">
        <a href="registration.php" class="btn btn-success addnew-btn">Add New Record <i class="bi bi-person-circle"></i></a>  <h3>Total Registered Members: <span class="badge bg-primary"><?php echo $total_members; ?></span></h3>
        </div>
        <!-- Table to display members -->
        <div class="row m-0">
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
            <div class="row m-0 p-3">
         <div class="table-container">
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($members as $member): ?>
                            <tr>
                                <td><?php echo $member['id']; ?></td>
                                <td><?php echo $member['name']; ?></td>
                                <td><?php echo $member['firm_name']; ?></td>
                                <td><?php echo $member['association']; ?></td>
                                <td><?php echo $member['address']; ?></td>
                                <td><?php echo $member['city']; ?></td>
                                <td><?php echo $member['state']; ?></td>
                                <td><?php echo $member['contact_no']; ?></td>
                                <td><?php echo $member['gst_no']; ?></td>
                                <td><?php echo $member['work_type']; ?></td>
                                <td><img src="<?php echo $member['photo']; ?>" alt="Photo" class="photo-preview" style="max-width: 100px;"></td>
                                <td>
                                    <a href="edit.php?id=<?php echo $member['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="delete.php" method="POST" style="display:inline;">
                                        <input type="hidden" name="id" value="<?php echo $member['id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this record?');">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>

    <?php

include 'footer.php';

?>

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
        function previewImage(input) {
            const preview = document.getElementById('preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function() {
            const statesAndCities = {
    "Andhra Pradesh": ["Amaravati", "Visakhapatnam", "Vijayawada", "Guntur", "Nellore", "Kakinada", "Tirupati", "Rajahmundry", "Eluru", "Ongole", "Machilipatnam", "Vizianagaram", "Srikakulam", "Anantapur", "Kurnool", "Kadapa"],
    "Arunachal Pradesh": ["Itanagar", "Naharlagun", "Pasighat", "Ziro", "Bomdila", "Tawang", "Roing", "Changlang", "Seppa", "Along", "Anini", "Daporijo"],
    "Assam": ["Guwahati", "Dispur", "Dibrugarh", "Silchar", "Nagaon", "Tezpur", "Jorhat", "Tinsukia", "Dhubri", "Goalpara", "North Lakhimpur", "Sivasagar", "Karimganj", "Barpeta", "Golaghat"],
    "Bihar": ["Patna", "Gaya", "Bhagalpur", "Muzaffarpur", "Darbhanga", "Purnia", "Ara", "Katihar", "Munger", "Chhapra", "Hajipur", "Saharsa", "Bihar Sharif", "Begusarai", "Siwan", "Motihari"],
    "Chhattisgarh": ["Raipur", "Bilaspur", "Durg", "Korba", "Jagdalpur", "Rajnandgaon", "Ambikapur", "Raigarh", "Mahasamund", "Bhilai", "Dhamtari", "Kawardha"],
    "Goa": ["Panaji", "Margao", "Vasco da Gama", "Ponda", "Mapusa", "Bicholim", "Curchorem", "Canacona", "Sanquelim", "Valpoi"],
    "Gujarat": ["Ahmedabad", "Surat", "Vadodara", "Rajkot", "Bhavnagar", "Jamnagar", "Junagadh", "Anand", "Gandhinagar", "Nadiad", "Morbi", "Mehsana", "Surendranagar", "Bharuch", "Navsari", "Porbandar"],
    "Haryana": ["Chandigarh", "Gurugram", "Faridabad", "Ambala", "Hisar", "Panipat", "Karnal", "Sonipat", "Yamunanagar", "Rohtak", "Bhiwani", "Sirsa", "Bahadurgarh", "Jind", "Kaithal"],
    "Himachal Pradesh": ["Shimla", "Dharamshala", "Kullu", "Manali", "Solan", "Mandi", "Chamba", "Nahan", "Bilaspur", "Kangra", "Una", "Hamirpur", "Kinnaur"],
    "Jharkhand": ["Ranchi", "Jamshedpur", "Dhanbad", "Bokaro", "Deoghar", "Hazaribagh", "Giridih", "Ramgarh", "Medininagar", "Gumla", "Chatra", "Lohardaga", "Chaibasa", "Sahibganj", "Godda"],
    "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru", "Hubli", "Dharwad", "Belagavi", "Shivamogga", "Ballari", "Gulbarga", "Davangere", "Udupi", "Hassan", "Bidar", "Chitradurga", "Tumakuru"],
    "Kerala": ["Thiruvananthapuram", "Kochi", "Kozhikode", "Kollam", "Malappuram", "Thrissur", "Alappuzha", "Palakkad", "Kottayam", "Kannur", "Idukki", "Kasargod", "Pathanamthitta", "Wayanad"],
    "Madhya Pradesh": ["Bhopal", "Indore", "Gwalior", "Jabalpur", "Ujjain", "Sagar", "Rewa", "Ratlam", "Satna", "Shivpuri", "Chhindwara", "Hoshangabad", "Vidisha", "Chhatarpur", "Sehore"],
    "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Nashik", "Aurangabad", "Thane", "Solapur", "Amravati", "Kolhapur", "Akola", "Nanded", "Jalgaon", "Latur", "Dhule", "Ahmednagar"],
    "Manipur": ["Imphal", "Thoubal", "Kakching", "Churachandpur", "Bishnupur", "Ukhrul", "Senapati", "Tamenglong", "Jiribam", "Moreh"],
    "Meghalaya": ["Shillong", "Tura", "Jowai", "Nongpoh", "Bholaganj", "Baghmara", "Williamnagar", "Resubelpara", "Khliehriat", "Mawlai"],
    "Mizoram": ["Aizawl", "Lunglei", "Saiha", "Champhai", "Kolasib", "Serchhip", "Mamit", "Lawngtlai"],
    "Nagaland": ["Kohima", "Dimapur", "Mokokchung", "Wokha", "Tuensang", "Mon", "Zunheboto", "Phek", "Longleng", "Kiphire"],
    "Odisha": ["Bhubaneswar", "Cuttack", "Berhampur", "Rourkela", "Sambalpur", "Puri", "Balasore", "Baripada", "Jeypore", "Angul", "Dhenkanal", "Bargarh", "Bhadrak", "Rayagada"],
    "Punjab": ["Chandigarh", "Amritsar", "Ludhiana", "Jalandhar", "Patiala", "Bathinda", "Hoshiarpur", "Pathankot", "Mohali", "Moga", "Phagwara"],
    "Rajasthan": ["Jaipur", "Udaipur", "Jodhpur", "Ajmer", "Bikaner", "Kota", "Alwar", "Bharatpur", "Sikar", "Pali", "Nagaur", "Tonk", "Bhilwara", "Banswara"],
    "Sikkim": ["Gangtok", "Namchi", "Mangan", "Gyalshing", "Pakyong", "Rangpo"],
    "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli", "Salem", "Tirunelveli", "Vellore", "Erode", "Thoothukudi", "Nagercoil", "Dindigul", "Thanjavur", "Karur"],
    "Telangana": ["Hyderabad", "Warangal", "Nizamabad", "Khammam", "Karimnagar", "Ramagundam", "Mahbubnagar", "Adilabad", "Nalgonda"],
    "Tripura": ["Agartala", "Udaipur", "Ambassa", "Dharmanagar", "Kailashahar", "Belonia", "Khowai", "Melaghar"],
    "Uttar Pradesh": ["Lucknow", "Kanpur", "Agra", "Varanasi", "Ghaziabad", "Meerut", "Allahabad", "Bareilly", "Moradabad", "Aligarh", "Saharanpur", "Noida", "Faizabad", "Shahjahanpur", "Mathura", "Firozabad", "Jhansi"],
    "Uttarakhand": ["Dehradun", "Haridwar", "Nainital", "Rudrapur", "Roorkee", "Haldwani", "Kashipur", "Ranikhet", "Pithoragarh"],
    "West Bengal": ["Kolkata", "Siliguri", "Durgapur", "Asansol", "Howrah", "Kharagpur", "Bardhaman", "Darjeeling", "Jalpaiguri"],
    "Andaman and Nicobar Islands": ["Port Blair", "Diglipur", "Havelock Island", "Neil Island", "Rangat"],
    "Chandigarh": ["Chandigarh"],
    "Dadra and Nagar Haveli and Daman and Diu": ["Daman", "Diu", "Silvassa", "Amli"],
    "Lakshadweep": ["Kavaratti", "Agatti", "Minicoy", "Amini", "Andrott"],
    "Delhi": ["New Delhi", "Old Delhi", "Dwarka", "Rohini", "Pitampura", "Saket", "Karol Bagh", "Janakpuri"]
};


            $('#state').change(function() {
                const state = $(this).val();
                $('#city').empty().append('<option value="">Select City</option>'); // Clear previous options
                if (state) {
                    statesAndCities[state].forEach(function(city) {
                        $('#city').append('<option value="' + city + '">' + city + '</option>');
                    });
                }
            });

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
        const selectedwork_type = $(this).val();
        $('#memberTable tbody tr').each(function() {
            const rowwork_type = $(this).find('td').eq(9).text(); // Assuming work_type is in the 11th column (index 10)
            if (selectedwork_type === "" || rowwork_type === selectedwork_type) {
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