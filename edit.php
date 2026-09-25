<?php
session_start();
include 'db_connect.php'; // Ensure this file establishes a PDO connection and assigns it to $pdo

if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Check if the ID is set in the URL
if(!isset($_GET['id'])) {
    header("Location: dashboard.php"); // Redirect if no ID is provided
    exit();
}

// Fetch the member data from the database
$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM members WHERE id = ?");
$stmt->execute([$id]);
$member = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the member exists
if (!$member) {
    header("Location: dashboard.php"); // Redirect if member not found
    exit();
}

// Handle form submission for updating the member
if(isset($_POST['update'])) {
    try {
        // File upload handling
        $photo = $member['photo']; // Keep the existing photo
        if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $target_dir = "uploads/";
            // Create the uploads directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            // Delete the old photo if a new one is uploaded
            if (file_exists($photo)) {
                unlink($photo);
            }
            $photo = $target_dir . time() . '_' . basename($_FILES["photo"]["name"]);
            move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);
        }

        // Update data in the database
        $stmt = $pdo->prepare("UPDATE members SET name = ?, firm_name = ?, association = ?, address = ?, city = ?, state = ?, contact_no = ?, gst_no = ?, work_type = ?, photo = ? WHERE id = ?");
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
            $photo,
            $id
        ]);

        // Success message
        $success_message = "Record updated successfully!";
    } catch (PDOException $e) {
        // Error handling
        $error_message = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Member - Sarafa Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
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
    </style>
</head>
 
<body class="bg-light">
      <!-- Header -->
   <div class="header">
        <h1>आगरा सर्राफा करीगर व व्यापारी और दुकानदार फ्रॉड</h1>
        <h2>Agra Sarafa Karigar & Vyapari or Dukandar Fraud</h2>
        
    </div>
    <br>
    <div class="form-container">
        <h2 class="text-center mb-4">Edit Member</h2>
        
        <?php if(isset($success_message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                // Redirect to dashboard after 1 seconds
                setTimeout(function() {
                    window.location.href = 'dashboard.php';
                }, 1000);
            </script>
        <?php endif; ?>

        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error_message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($member['name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="firm_name" class="form-label">Firm Name</label>
                    <input type="text" class="form-control" id="firm_name" name="firm_name" value="<?php echo htmlspecialchars($member['firm_name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="association" class="form-label">Association</label>
                    <input type="text" class="form-control" id="association" name="association" value="<?php echo htmlspecialchars($member['association']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="2" required><?php echo htmlspecialchars($member['address']); ?></textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="state" class="form-label">State</label>
                    <select class="form-select" id="state" name="state" required>
                        <option value="">Select State</option>
                        <option value="Andhra Pradesh" <?php echo ($member['state'] == 'Andhra Pradesh') ? 'selected' : ''; ?>>Andhra Pradesh</option>
                        <option value="Arunachal Pradesh" <?php echo ($member['state'] == 'Arunachal Pradesh') ? 'selected' : ''; ?>>Arunachal Pradesh</option>
                        <option value="Assam" <?php echo ($member['state'] == 'Assam') ? 'selected' : ''; ?>>Assam</option>
                        <option value="Bihar" <?php echo ($member['state'] == 'Bihar') ? 'selected' : ''; ?>>Bihar</option>
                        <option value="Chhattisgarh" <?php echo ($member['state'] == 'Chhattisgarh') ? 'selected' : ''; ?>>Chhattisgarh</option>
                        <option value="Goa" <?php echo ($member['state'] == 'Goa') ? 'selected' : ''; ?>>Goa</option>
                        <option value="Gujarat" <?php echo ($member['state'] == 'Gujarat') ? 'selected' : ''; ?>>Gujarat</option>
                        <option value="Haryana" <?php echo ($member['state'] == 'Haryana') ? 'selected' : ''; ?>>Haryana</option>
                        <option value="Himachal Pradesh" <?php echo ($member['state'] == 'Himachal Pradesh') ? 'selected' : ''; ?>>Himachal Pradesh</option>
                        <option value="Jharkhand" <?php echo ($member['state'] == 'Jharkhand') ? 'selected' : ''; ?>>Jharkhand</option>
                        <option value="Karnataka" <?php echo ($member['state'] == 'Karnataka') ? 'selected' : ''; ?>>Karnataka</option>
                        <option value="Kerala" <?php echo ($member['state'] == 'Kerala') ? 'selected' : ''; ?>>Kerala</option>
                        <option value="Madhya Pradesh" <?php echo ($member['state'] == 'Madhya Pradesh') ? 'selected' : ''; ?>>Madhya Pradesh</option>
                        <option value="Maharashtra" <?php echo ($member['state'] == 'Maharashtra') ? 'selected' : ''; ?>>Maharashtra</option>
                        <option value="Manipur" <?php echo ($member['state'] == 'Manipur') ? 'selected' : ''; ?>>Manipur</option>
                        <option value="Meghalaya" <?php echo ($member['state'] == 'Meghalaya') ? 'selected' : ''; ?>>Meghalaya</option>
                        <option value="Mizoram" <?php echo ($member['state'] == 'Mizoram') ? 'selected' : ''; ?>>Mizoram</option>
                        <option value="Nagaland" <?php echo ($member['state'] == 'Nagaland') ? 'selected' : ''; ?>>Nagaland</option>
                        <option value="Odisha" <?php echo ($member['state'] == 'Odisha') ? 'selected' : ''; ?>>Odisha</option>
                        <option value="Punjab" <?php echo ($member['state'] == 'Punjab') ? 'selected' : ''; ?>>Punjab</option>
                        <option value="Rajasthan" <?php echo ($member['state'] == 'Rajasthan') ? 'selected' : ''; ?>>Rajasthan</option>
                        <option value="Sikkim" <?php echo ($member['state'] == 'Sikkim') ? 'selected' : ''; ?>>Sikkim</option>
                        <option value="Tamil Nadu" <?php echo ($member['state'] == 'Tamil Nadu') ? 'selected' : ''; ?>>Tamil Nadu</option>
                        <option value="Telangana" <?php echo ($member['state'] == 'Telangana') ? 'selected' : ''; ?>>Telangana</option>
                        <option value="Tripura" <?php echo ($member['state'] == 'Tripura') ? 'selected' : ''; ?>>Tripura</option>
                        <option value="Uttar Pradesh" <?php echo ($member['state'] == 'Uttar Pradesh') ? 'selected' : ''; ?>>Uttar Pradesh</option>
                        <option value="Uttarakhand" <?php echo ($member['state'] == 'Uttarakhand') ? 'selected' : ''; ?>>Uttarakhand</option>
                        <option value="West Bengal" <?php echo ($member['state'] == 'West Bengal') ? 'selected' : ''; ?>>West Bengal</option>
                        <option value="Andaman and Nicobar Islands" <?php echo ($member['state'] == 'Andaman and Nicobar Islands') ? 'selected' : ''; ?>>Andaman and Nicobar Islands</option>
                        <option value="Chandigarh" <?php echo ($member['state'] == 'Chandigarh') ? 'selected' : ''; ?>>Chandigarh</option>
                        <option value="Dadra and Nagar Haveli and Daman and Diu" <?php echo ($member['state'] == 'Dadra and Nagar Haveli and Daman and Diu') ? 'selected' : ''; ?>>Dadra and Nagar Haveli and Daman and Diu</option>
                        <option value="Lakshadweep" <?php echo ($member['state'] == 'Lakshadweep') ? 'selected' : ''; ?>>Lakshadweep</option>
                        <option value="Delhi" <?php echo ($member['state'] == 'Delhi') ? 'selected' : ''; ?>>Delhi</option>
                        <option value="Puducherry" <?php echo ($member['state'] == 'Puducherry') ? 'selected' : ''; ?>>Puducherry</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="city" class="form-label">City</label>
                    <select class="form-select" id="city" name="city" required>
                        <option value="">Select City</option>
                        <!-- Populate cities based on selected state -->
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="contact_no" class="form-label">Contact No</label>
                    <input type="tel" class="form-control" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($member['contact_no']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gst_no" class="form-label">GST No</label>
                    <input type="text" class="form-control" id="gst_no" name="gst_no" value="<?php echo htmlspecialchars($member['gst_no']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="work_type" class="form-label">Work Type</label>
                    <select class="form-select" id="work_type" name="work_type" required>
                        <option value="">Select Work Type</option>
                        <option value="karigar" <?php echo ($member['work_type'] == 'karigar') ? 'selected' : ''; ?>>Karigar</option>
                        <option value="vyapari" <?php echo ($member['work_type'] == 'vyapari') ? 'selected' : ''; ?>>Vyapari</option>
                        <option value="dukandaar" <?php echo ($member['work_type'] == 'dukandaar') ? 'selected' : ''; ?>>Dukandaar</option>
                   </select>
                </div>
              
                <div class="col-md-6 mb-3">
                    <label for="photo" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="photo" name="photo" accept="image/*" onchange="previewImage(this);">
                    <img id="preview" class="photo-preview d-none" src="<?php echo $member['photo']; ?>" alt="Current Photo">
                </div>
                <div class="col-12 text-center">
                    <button type="submit" name="update" class="btn btn-primary px-5">Update</button>
                </div>
            </div>
        </form>
        <img id="preview" class="photo-preview d-none" src="<?php echo $member['photo']; ?>" alt="Current Photo">
    </div>
<br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // function previewImage(input) {
        //     const preview = document.getElementById('preview');
        //     if (input.files && input.files[0]) {
        //         const reader = new FileReader();
        //         reader.onload = function(e) {
        //             preview.src = e.target.result;
        //             preview.classList.remove('d-none');
        //         }
        //         reader.readAsDataURL(input.files[0]);
        //     }
        // }

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
            // Populate the city dropdown based on the selected state
            $('#state').change(function() {
                const state = $(this).val();
                $('#city').empty().append('<option value="">Select City</option>'); // Clear previous options
                if (state) {
                    statesAndCities[state].forEach(function(city) {
                        $('#city').append('<option value="' + city + '">' + city + '</option>');
                    });
                }
            });

            // Set the selected state and city based on the member data
            $('#state').val('<?php echo $member['state']; ?>').change(); // Trigger change to populate cities
            $('#city').val('<?php echo $member['city']; ?>'); // Set the selected city
        });
    </script>

<?php

include 'footer.php';

?>
</body>
</html>