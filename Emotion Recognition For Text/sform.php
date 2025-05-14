<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Survey</title>
    <!-- Add Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-3yINj8aVR0BHpAMu/oQn5uWujkIKh5qQf7+ip1nSh44u2gFLpugrxLO3AnNcZkFL" crossorigin="anonymous">
    
    <!-- Add Google Fonts for a smoother look -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            color: #343a40;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.6;
        }

        .container {
            max-width: 900px;
            background-color: #fff;
            padding: 50px;
            border-radius: 25px;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            margin: 30px;
        }

        .container:hover {
            transform: translateY(-7px);
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.18);
        }

        h1, h2, h3, h4 {
            color: #007bff;
            font-weight: 700;
            margin-bottom: 25px;
        }

        h1 {
            font-size: 3rem;
            letter-spacing: 0.5px;
        }

        h2 {
            font-size: 2.4rem;
            letter-spacing: 0.3px;
        }

        .btn-submit {
            width: 50%;
            margin: 0 auto;
            display: block;
            background: linear-gradient(to right, #007bff, #0056b3);
            color: white;
            border-radius: 35px;
            padding: 14px 35px;
            border: none;
            font-size: 17px;
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
            margin-top: 20px;
            letter-spacing: 0.2px;
        }

        .btn-submit:hover {
            background-color: #0056b3;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
        }

        .form-section {
            margin-top: 40px;
        }

        .form-section input[type="number"],
        .form-section textarea,
        .form-section input[type="text"],
        .form-section input[type="date"],
        .form-section select {
            border-radius: 15px;
            border: 1px solid #ced4da;
            padding: 16px;
            font-size: 17px;
            width: 100%;
            margin-bottom: 28px;
            transition: border-color 0.3s ease, transform 0.3s ease;
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .form-section input[type="number"]:focus,
        .form-section textarea:focus,
        .form-section input[type="text"]:focus,
        .form-section input[type="date"]:focus,
        .form-section select:focus {
            border-color: #007bff;
            transform: translateY(-3px);
            box-shadow: inset 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .form-section label {
            font-weight: 600;
            color: #495057;
            display: block;
            margin-bottom: 10px;
            letter-spacing: 0.1px;
        }

        .feedback-text {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            margin-top: 35px;
            font-size: 17px;
        }

        .prediction-section {
            margin-top: 50px;
            padding: 35px;
            background-color: #f8f9fa;
            border-radius: 20px;
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.18);
        }

        .prediction-section h4 {
            color: #28a745;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .prediction-section p {
            font-size: 18px;
            color: #495057;
            margin-bottom: 10px;
        }

        .prediction-section .prediction-result {
            font-size: 22px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }

        .prediction-section .emoji-icon {
            font-size: 32px;
        }

        .chart-container {
            margin-top: 25px;
            padding: 25px;
            background-color: #ffffff;
            border-radius: 18px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.15);
        }

        /* Custom hover effects for form elements */
        .form-section .radio-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px; /* Add spacing between radio buttons */
            margin-bottom: 20px;
        }

        .form-section input[type="radio"] {
            display: none;
        }

        .form-section input[type="radio"] + label {
            display: inline-block;
            padding: 8px 15px;
            border-radius: 20px;
            background-color: #f8f9fa;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .form-section input[type="radio"]:hover + label,
        .form-section input[type="radio"]:focus + label {
            background-color: #e9ecef;
        }

        .form-section input[type="radio"]:checked + label {
            background-color: #007bff;
            color: white;
        }

        /* Add smooth scrolling effect */
        html {
            scroll-behavior: smooth;
        }

        /* Smooth fade-in animation */
        .fade-in {
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .container {
                padding: 30px;
                margin: 15px;
            }

            h1 {
                font-size: 2.2rem;
            }

            h2 {
                font-size: 1.8rem;
            }
            .form-section input[type="number"],
            .form-section textarea,
            .form-section input[type="text"],
            .form-section input[type="date"],
            .form-section select {
                padding: 12px;
                font-size: 15px;
            }
            .btn-submit {
                padding: 10px 25px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container fade-in">
        <h1 class="text-center">Patient Survey</h1>
        <h2 class="text-center">How are you feeling today?</h2>
    
  <?php

    //learn from w3schools.com

    session_start();

    if(isset($_SESSION["user"])){
        if(($_SESSION["user"])=="" or $_SESSION['usertype']!='p'){
            header("location: ../login.php");
        }else{
            $useremail=$_SESSION["user"];
        }

    }else{
        header("location: ../login.php");
    }
    

    //import database
    include("../connection.php");
    $userrow = $database->query("select * from patient where pemail='$useremail'");
    $userfetch=$userrow->fetch_assoc();
    $userid= $userfetch["pid"];
    $username=$userfetch["pname"];

$doctor=$_GET['doctor']; 
    //echo $userid;
    //echo $username;
    
    ?>
        <!-- Form Start -->
        <form method="POST" class="form-section">
            <h3>Patient Experience Survey</h3>
            <input type="hidden" name="patient_id" value="<?php echo $userid; ?>">
            <div class="mb-3">
                <input type="hidden" name="doctor" class="form-control" value = "<?php echo $doctor; ?>" required>
            </div>

             <!-- Patient's Info -->
             <div class="mb-3">
                <label for="patient_name">Patient Name</label>
                <input type="text" name="patient_name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="Patient_ID"> Patient ID</label>
                <input type="text" name="Patient_ID" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="visit_date">Date of Visit</label>
                <input type="date" name="visit_date" class="form-control" value="{{ current_date or '2025-01-05' }}" required>
            </div>


            <!-- Service Rating -->
            <div class="mb-3">
                <label for="service_rating">How would you rate the overall quality of care you received?</label>
                <div class="radio-group">
                    <input type="radio" name="service_rating" id="service_rating_poor" value="Poor"> <label for="service_rating_poor">Poor</label>
                    <input type="radio" name="service_rating" id="service_rating_average" value="Average"> <label for="service_rating_average">Average</label>
                    <input type="radio" name="service_rating" id="service_rating_good" value="Good"> <label for="service_rating_good">Good</label>
                    <input type="radio" name="service_rating" id="service_rating_excellent" value="Excellent" required> <label for="service_rating_excellent">Excellent</label>
                    
                </div>
            </div>


            <div class="mb-3">
                <label for="overall_experience">Overall experience rating?</label>
                <div class="radio-group">
                    <input type="radio" name="overall_experience" id="overall_experience_1" value="" required> <label for="overall_experience_1">1</label>
                    <input type="radio" name="overall_experience" id="overall_experience_2" value=""> <label for="overall_experience_2">2</label>
                    <input type="radio" name="overall_experience" id="overall_experience_3" value=""> <label for="overall_experience_3">3</label>
                    <input type="radio" name="overall_experience" id="overall_experience_4" value=""> <label for="overall_experience_4">4</label>
                    <input type="radio" name="overall_experience" id="overall_experience_5" value=""> <label for="overall_experience_5">5</label>
                    <input type="radio" name="overall_experience" id="overall_experience_6" value=""> <label for="overall_experience_6">6</label>
                    <input type="radio" name="overall_experience" id="overall_experience_7" value=""> <label for="overall_experience_7">7</label>
                    <input type="radio" name="overall_experience" id="overall_experience_8" value=""> <label for="overall_experience_8">8</label>
                    <input type="radio" name="overall_experience" id="overall_experience_9" value=""> <label for="overall_experience_9">9</label>
                    <input type="radio" name="overall_experience" id="overall_experience_10" value=""> <label for="overall_experience_10">10</label>
                </div>
            </div>


            <div class="mb-3">
                <label for="wait_time">How courteous were the staff?</label>
                <div class="radio-group">
                    <input type="radio" name="wait_time" id="wait_time_1" value="" required> <label for="wait_time_1">1</label>
                    <input type="radio" name="wait_time" id="wait_time_2" value=""> <label for="wait_time_2">2</label>
                    <input type="radio" name="wait_time" id="wait_time_3" value=""> <label for="wait_time_3">3</label>
                    <input type="radio" name="wait_time" id="wait_time_4" value=""> <label for="wait_time_4">4</label>
                    <input type="radio" name="wait_time" id="wait_time_5" value=""> <label for="wait_time_5">5</label>
                    <input type="radio" name="wait_time" id="wait_time_6" value=""> <label for="wait_time_6">6</label>
                    <input type="radio" name="wait_time" id="wait_time_7" value=""> <label for="wait_time_7">7</label>
                    <input type="radio" name="wait_time" id="wait_time_8" value=""> <label for="wait_time_8">8</label>
                    <input type="radio" name="wait_time" id="wait_time_9" value=""> <label for="wait_time_9">9</label>
                    <input type="radio" name="wait_time" id="wait_time_10" value=""> <label for="wait_time_10">10</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="staff_courtesy">How courteous were the staff?</label>
                <div class="radio-group">
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_1" value="" required> <label for="staff_courtesy_1">1</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_2" value=""> <label for="staff_courtesy_2">2</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_3" value=""> <label for="staff_courtesy_3">3</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_4" value=""> <label for="staff_courtesy_4">4</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_5" value=""> <label for="staff_courtesy_5">5</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_6" value=""> <label for="staff_courtesy_6">6</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_7" value=""> <label for="staff_courtesy_7">7</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_8" value=""> <label for="staff_courtesy_8">8</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_9" value=""> <label for="staff_courtesy_9">9</label>
                    <input type="radio" name="staff_courtesy" id="staff_courtesy_10" value=""> <label for="staff_courtesy_10">10</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="facilities_rating">How would you rate the hospital facilities?</label>
                <div class="radio-group">
                    <input type="radio" name="facilities_rating" id="would_recommend_1" value="" required> <label for="would_recommend_1">1</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_2" value=""> <label for="would_recommend_2">2</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_3" value=""> <label for="would_recommend_3">3</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_4" value=""> <label for="would_recommend_4">4</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_5" value=""> <label for="would_recommend_5">5</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_6" value=""> <label for="would_recommend_6">6</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_7" value=""> <label for="would_recommend_7">7</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_8" value=""> <label for="would_recommend_8">8</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_9" value=""> <label for="would_recommend_9">9</label>
                    <input type="radio" name="facilities_rating" id="would_recommend_10" value=""> <label for="would_recommend_10">10</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="would_recommend">Would you recommend this hospital to others?</label>
                <div class="radio-group">
                    <input type="radio" name="would_recommend" id="would_recommend_no" value="No"> <label for="would_recommend_no">No</label>
                    <input type="radio" name="would_recommend" id="would_recommend_yes" value="Yes" required> <label for="would_recommend_yes">Yes</label>
                </div>
            </div>

             <!-- Need Support -->
             <div class="mb-3">
                <label for="need_support">Do you feel you need additional support?</label>
                <div class="radio-group">
                    <input type="radio" name="need_support" id="need_support_no" value="No"> <label for="need_support_no">No</label>
                    <input type="radio" name="need_support" id="need_support_yes" value="Yes" required> <label for="need_support_yes">Yes</label>
                </div>
            </div>

              <div class="mb-3">
                <label for="patient_feedback">please add any additional comments or suggestions here</label>
                <textarea name="patient_feedback" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-submit w-50">Submit</button>
             

        </form>
        <!-- Form End -->


        {% if prediction %}
        <!-- Prediction Section -->
        <div class="prediction-section fade-in">
            <h4>Written Feedback</h4>
            <div class="feedback-text">
                <p>{{ feedback }}</p>
            </div>

            <h4>Prediction</h4>
            <div class="prediction-result">
                <p>{{ prediction }} <span class="emoji-icon">{{ emoji_icon }}</span></p>
                <p>Confidence: {{ confidence }}</p>
            </div>

            <h4>Prediction Probability</h4>
            <div class="chart-container">
                {{ chart | safe }}
            </div>

            <h5 class="mt-4 text-center">Thank You.your feedback is crucial in helping us provide the best care possible.</h5>
        </div>
        {% endif %}
    </div>

    <!-- Add Bootstrap JS (Optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

