<?php
session_start();
include 'db_connect.php'; // Ensure this file establishes a PDO connection and assigns it to $pdo

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if (isset($_POST['submit'])) {
    try {
        // File upload handling
        $photo = '';
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "uploads/";
            // Create the uploads directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $photo = $target_dir . time() . '_' . basename($_FILES["photo"]["name"]);
            move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
        }

        // Insert data into database
        $stmt = $pdo->prepare("INSERT INTO members (name, firm_name, association, address, city, state, contact_no, gst_no, work_type, photo) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $_POST['name'],
            $_POST['firm_name'],
            $_POST['association'],
            $_POST['address'],
            $_POST['city'],
            $_POST['state'],
            $_POST['contact_no'],
            $_POST['gst_no'],
            $_POST['work_type'],
            $photo
        ]);

        // Success message
        $success_message = "Record added successfully!";
    // Redirect to dashboard.php
    // header("Location: dashboard.php");
    echo "<script>
    alert('Record added successfully!');
    setTimeout(function() {
        window.location.href = 'dashboard.php';
    }); // Redirect after 1 second
</script>";
    exit(); // Make sure to call exit to stop further script execution
    
    } catch (PDOException $e) {
        // Error handling
        $error_message = "Error: " . $e->getMessage();
    }
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
            margin-top: 20px;
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
        }
        .total-members-box {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            margin-bottom: 20px;
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
<br>

        <div class="form-container">
            <h2 class="text-center mb-4">Member Registration Form</h2>
            
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error_message; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="row">
                    <!-- Form fields -->
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="firm_name" class="form-label">Firm Name</label>
                        <input type="text" class="form-control" id="firm_name" name="firm_name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="association" class="form-label">Association</label>
                        <input type="text" class="form-control" id="association" name="association" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="state" class="form-label">State</label>
                        <select class="form-select" id="state" name="state" required>
                        <option value="">Select State</option>
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
                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">City</label>
                        <select class="form-select" id="city" name="city" required>
                            <option value="">Select City</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="contact_no" class="form-label">Contact No</label>
                        <input type="tel" class="form-control" id="contact_no" name="contact_no" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="gst_no" class="form-label">GST No</label>
                        <input type="text" class="form-control" id="gst_no" name="gst_no">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="work_type" class="form-label">Work Type</label>
                        <select class="form-select" id="work_type" name="work_type" required>
                        <option value="">Select Work Type</option>
                            <option value="karigar">Karigar</option>
                            <option value="vyapari">Vyapari</option>
                            <option value="dukandaar">Dukandaar</option>
                        </select>
                    </div>
                 
                    <div class="col-md-6 mb-3">
                        <label for="photo" class="form-label">Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" onchange="previewImage(this);">
                        <img id="preview" class="photo-preview d-none">
                    </div>
                    <div class="col-12 text-center">
                        <button type="submit" name="submit" class="btn btn-primary px-5">Submit</button>
                    </div>
                </div>
            </form>
        </div>
<br>

<?php

include 'footer.php';

?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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
                $('#membersTable tbody tr').each(function() {
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
        $('#membersTable tbody tr').each(function() {
            const rowState = $(this).find('td').eq(6).text(); // Assuming state is in the 7th column (index 6)
            if (selectedState === "" || rowState === selectedState) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Filter functionality for work_type
    $('#filterCategory').change(function() {
        const selectedCategory = $(this).val();
        $('#membersTable tbody tr').each(function() {
            const rowCategory = $(this).find('td').eq(10).text(); // Assuming category is in the 11th column (index 10)
            if (selectedCategory === "" || rowCategory === selectedCategory) {
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