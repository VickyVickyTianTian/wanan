<?php

require_once("connection.php");

session_start();
$email = "";
$name = "";
$errors = [];

//if user signup button
if (isset($_POST['signup'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    }
    $email_check = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $email_check);
    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = "Email that you have entered is already exist!";
    }

    if (count($errors) === 0) {
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $code = rand(111111, 999999);
        $status = "notverified";
        $insert_data = "INSERT INTO users (user_type, username, email, password_hash, code, status)
                        values(1, '$name', '$email', '$encpass', '$code', '$status')";
        $data_check = mysqli_query($con, $insert_data);
        if ($data_check) {
            $subject = "WAN:Email Verification Code";
            $message = "For verify your email address, enter this verification code when prompted: ".$code;
            $sender = "From:noreply@wangroup.co";
            if (mail($email, $subject, $message, $sender)) {
                $info = "We've sent a verification code to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: user-otp.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed to send code. Please contact customer service.";
            }
        } else {
            $errors['db-error'] = "Failed to signup. Please contact customer service.";
        }
    }
}
//if user click verification code submit button
if (isset($_POST['check'])) {
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM users WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $fetch_code = $fetch_data['code'];
        $email = $fetch_data['email'];
        $code = 0;
        $status = 'verified';
        $update_otp = "UPDATE users SET code = $code, status = '$status' WHERE code = $fetch_code";
        $update_res = mysqli_query($con, $update_otp);
        if ($update_res) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            header('location: index.php');
            exit();
        } else {
            $errors['otp-error'] = "Failed while updating code!";
        }
    } else {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click login button
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $check_email = "SELECT * FROM users WHERE email = '$email'";
    $res = mysqli_query($con, $check_email);
    if (mysqli_num_rows($res) > 0) {
        $fetch = mysqli_fetch_assoc($res);
        $fetch_pass = $fetch['password_hash'];
        if (password_verify($password, $fetch_pass)) {
            $_SESSION['email'] = $email;
            $status = $fetch['status'];
            if ($status === 'verified') {
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $password;
                header('location: index.php');
            } else {
                $info = "It's look like you haven't still verify your email - $email";
                $_SESSION['info'] = $info;
                header('location: user-otp.php');
            }
        } else {
            $errors['email'] = "Incorrect email or password!";
        }
    } else {
        $errors['email'] = "It's look like you're not yet a member! Click on the bottom link to signup.";
    }
}

//if user click continue button in forgot password form
if (isset($_POST['check-email'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $check_email = "SELECT * FROM users WHERE email='$email'";
    $run_sql = mysqli_query($con, $check_email);
    if (mysqli_num_rows($run_sql) > 0) {
        $code = rand(111111, 999999);
        $insert_code = "UPDATE users SET code = $code WHERE email = '$email'";
        $run_query = mysqli_query($con, $insert_code);
        if ($run_query) {
            $subject = "WAN: Password Reset Code";
            $message = "Your password reset code is $code";
            $sender = "From: noreply@wanan.app";
            if (mail($email, $subject, $message, $sender)) {
                $info = "We've sent a passwrod reset otp to your email - $email";
                $_SESSION['info'] = $info;
                $_SESSION['email'] = $email;
                header('location: reset-code.php');
                exit();
            } else {
                $errors['otp-error'] = "Failed while sending code!";
            }
        } else {
            $errors['db-error'] = "Something went wrong!";
        }
    } else {
        $errors['email'] = "This email address does not exist!";
    }
}

//if user click check reset otp button
if (isset($_POST['check-reset-otp'])) {
    $_SESSION['info'] = "";
    $otp_code = mysqli_real_escape_string($con, $_POST['otp']);
    $check_code = "SELECT * FROM users WHERE code = $otp_code";
    $code_res = mysqli_query($con, $check_code);
    if (mysqli_num_rows($code_res) > 0) {
        $fetch_data = mysqli_fetch_assoc($code_res);
        $email = $fetch_data['email'];
        $_SESSION['email'] = $email;
        $info = "Please create a new password that you don't use on any other site.";
        $_SESSION['info'] = $info;
        header('location: new-password.php');
        exit();
    } else {
        $errors['otp-error'] = "You've entered incorrect code!";
    }
}

//if user click change password button
if (isset($_POST['change-password'])) {
    $_SESSION['info'] = "";
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $cpassword = mysqli_real_escape_string($con, $_POST['cpassword']);
    if ($password !== $cpassword) {
        $errors['password'] = "Confirm password not matched!";
    } else {
        $code = 0;
        $email = $_SESSION['email']; //getting this email using session
        $encpass = password_hash($password, PASSWORD_BCRYPT);
        $update_pass = "UPDATE users SET code = $code, password_hash = '$encpass' WHERE email = '$email'";
        $run_query = mysqli_query($con, $update_pass);
        if ($run_query) {
            $info = "Your password changed. Now you can login with your new password.";
            $_SESSION['info'] = $info;
            header('Location: password-changed.php');
        } else {
            $errors['db-error'] = "Failed to change your password!";
        }
    }
}

if (isset($_POST['kyc-individual'])) {
    $nameEn = mysqli_real_escape_string($con, $_POST['nameEn']);
    $nameCn = mysqli_real_escape_string($con, $_POST['nameCn']);
    $passportNo = mysqli_real_escape_string($con, $_POST['passportNumber']);
    $ICNo = mysqli_real_escape_string($con, $_POST['ICNumber']);
    $ICPhotoIds = mysqli_real_escape_string($con, $_POST['input-IC-photo-ids']);
    $passportPhotoIds = mysqli_real_escape_string($con, $_POST['input-passport-photo-ids']);

    $email = $_SESSION['email'];
    $sql = "SELECT * FROM users WHERE email = '{$email}'";
    $userRows = mysqli_query($con, $sql);
    if (mysqli_num_rows($userRows) > 0) {
        $row = mysqli_fetch_assoc($userRows);
        $sql = <<<EOF
INSERT INTO user_profiles(user_id, english_name, chinese_name, passport_number, IC_number, IC_photo_storage_ids, passport_photo_storage_ids)
VALUES({$row['id']}, "{$nameEn}", "{$nameCn}", "{$passportNo}", "{$ICNo}", "{$ICPhotoIds}", "{$passportPhotoIds}")
EOF;
        $result = mysqli_query($con, $sql);
        if ($result) {
            header('Location: kyc.php');
            exit();
        }
    }
}

if (isset($_POST['kyc-corporate'])) {
    $nameEn = $_POST['nameEn'];
    $nameCn = $_POST['nameCn'];
    $passportNo = $_POST['passportNumber'];
    $ICNo = $_POST['ICNumber'];
}

//if login now button click
if (isset($_POST['login-now'])) {
    header('Location: login.php');
}
