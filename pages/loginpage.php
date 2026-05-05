<?php 
session_start(); // Move this to the very top?>

<!DOCTYPE html>

<html lang="en">

  <head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>Login & Signup Form</title>

    <style>

      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");



      * {

        margin: 0;

        padding: 0;

        box-sizing: border-box;

        font-family: "Poppins", sans-serif !important;

      }



      input, button, a, label, header {

        font-family: "Poppins", sans-serif !important;

      }



      body {

        min-height: 100vh;

        display: flex;

        flex-direction: column;

        align-items: center;

        justify-content: center;

        background-image: url('../img/VDart_GCC_Building.jpg');

        background-size: cover;

        background-position: center;

        background-repeat: no-repeat;

        font-family: "Poppins", sans-serif;

      }



      body::before {

        content: '';

        position: fixed;

        top: 0;

        left: 0;

        width: 100%;

        height: 100%;

        background: rgba(0, 0, 0, 0.5);

        z-index: 1;

      }



      .main-heading {

        color: white;

        font-size: 2.5rem;

        font-weight: 600;

        margin-bottom: 2rem;

        text-align: center;

        position: relative;

        z-index: 2;

        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);

      }



      .header {

        text-align: center;

        margin-bottom: 2rem;

        position: relative;

        z-index: 2;

      }



      .main-heading {

        color: white;

        font-size: 2.5rem;

        font-weight: 600;

        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);

        margin-bottom: 0.5rem;

      }



      .sub-heading {

        color: rgba(255, 255, 255, 0.9);

        font-size: 1.1rem;

        font-weight: 300;

        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);

      }



      .wrapper {

        position: relative;

        max-width: 470px;

        width: 100%;

        border-radius: 12px;

        padding: 20px 30px 120px;

        background: rgba(64, 112, 244, 0.15);

        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);

        overflow: hidden;

        z-index: 2;

        backdrop-filter: blur(8px);

        border: 1px solid rgba(255, 255, 255, 0.1);

      }



      .form.login {

        position: absolute;

        left: 50%;

        bottom: -86%;

        transform: translateX(-50%);

        width: calc(100% + 220px);

        padding: 20px 140px;

        border-radius: 50%;

        height: 100%;

        background: rgba(255, 255, 255, 0.95);

        transition: all 0.6s ease;

        backdrop-filter: blur(8px);

      }



      .wrapper.active .form.login {

        bottom: -15%;

        border-radius: 35%;

        box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.2);

      }



      .form header {

        font-size: 30px;

        text-align: center;

        color: #fff;

        font-weight: 600;

        cursor: pointer;

        font-family: "Poppins", sans-serif;

      }



      .form.login header {

        color: #333;

        opacity: 0.6;

      }



      .wrapper.active .form.login header {

        opacity: 1;

      }



      .wrapper.active .signup header {

        opacity: 0.6;

      }



      .wrapper form {

        display: flex;

        flex-direction: column;

        gap: 20px;

        margin-top: 40px;

      }



      form input {

        height: 60px;

        outline: none;

        border: none;

        padding: 0 15px;

        font-size: 16px;

        font-weight: 400;

        color: #333;

        border-radius: 8px;

        background: #fff;

        font-family: "Poppins", sans-serif;

      }



      .form.login input {

        border: 1px solid #aaa;

      }



      .form.login input:focus {

        box-shadow: 0 1px 0 #ddd;

      }



      form .checkbox {

        display: flex;

        align-items: center;

        gap: 10px;

      }



      .checkbox input[type="checkbox"] {

        height: 16px;

        width: 16px;

        accent-color: #fff;

        cursor: pointer;

      }



      form .checkbox label {

        cursor: pointer;

        color: #fff;

        font-family: "Poppins", sans-serif;

      }



      form a {

        color: #333;

        text-decoration: none;

        font-family: "Poppins", sans-serif;

      }



      form a:hover {

        text-decoration: underline;

      }



      form input[type="submit"] {

        margin-top: 15px;

        padding: none;

        font-size: 18px;

        font-weight: 500;

        cursor: pointer;

        font-family: "Poppins", sans-serif;

      }



      .form.login input[type="submit"] {

        background: #4070f4;

        color: #fff;

        border: none;

      }



      .form header { font-weight: 600; }

      form input { font-weight: 400; }

      form input[type="submit"] { font-weight: 500; }

      form .checkbox label { font-weight: 400; }


      .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transform: translateX(150%);
            transition: transform 0.5s ease;
            z-index: 1000;
            font-family: "Poppins", sans-serif;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            border-left: 4px solid #28a745;
            color: #28a745;
        }

        .notification.error {
            border-left: 4px solid #dc3545;
            color: #dc3545;
        }

        /* Form validation styles */
        .error-message {
            font-size: 12px;
            color: #dc3545;
            margin-top: 5px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 10px;
            border-radius: 4px;
            display: none;
        }

        input.error {
            border: 1px solid #dc3545 !important;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 25px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(8px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transform: translateX(150%);
    transition: transform 0.5s ease;
    z-index: 9999;
    font-family: "Poppins", sans-serif;
}

.notification.show {
    transform: translateX(0) !important;
}

.notification.success {
    background: linear-gradient(135deg, #28a745, #20c997);
    color: white;
}

.notification.error {
    background: linear-gradient(135deg, #dc3545, #ff4444);
    color: white;
}

@keyframes slideIn {
    from {
        transform: translateX(150%);
    }
    to {
        transform: translateX(0);
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(150%);
    }
}
    </style>

  </head>

  <body>

  <body>

  <?php
    
    
    // Single notification check for both success and error
    if(isset($_SESSION['success']) || isset($_SESSION['error'])) {
        $type = isset($_SESSION['success']) ? 'success' : 'error';
        $message = isset($_SESSION['success']) ? $_SESSION['success'] : $_SESSION['error'];
    ?>
        <div class="notification <?php echo $type; ?> show">
            <?php echo $message; ?>
        </div>
        <script>
            // Notification auto-hide
            setTimeout(() => {
                const notification = document.querySelector('.notification');
                if (notification) {
                    notification.style.transform = 'translateX(150%)';
                    setTimeout(() => notification.remove(), 500);
                }
            }, 5000);
        </script>
    <?php
        // Clear the messages
        unset($_SESSION['success']);
        unset($_SESSION['error']);
    }
    ?>

  <div class="header">
        <h1 class="main-heading">VDart Brand Desk</h1>
        <p class="sub-heading">Create Professional Signatures & LinkedIn Branding</p>
    </div>

    

    <section class="wrapper">
        <div class="form signup">
            <header>Sign Up</header>
            <form action="register.php" method="POST" id="signupForm">
                <input type="text" name="name" placeholder="Full name" required />
                <div class="error-message" id="nameError"></div>
                
                <input type="email" name="email" placeholder="Email address" required />
                <div class="error-message" id="emailError"></div>
                
                <input type="password" name="password" placeholder="Password" required />
                <div class="error-message" id="passwordError"></div>
                
                <input type="submit" value="Signup" style="background: linear-gradient(to right, #242299, #3DCFD8); color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 5px 10px rgba(64, 112, 244, 0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(64, 112, 244, 0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 10px rgba(64, 112, 244, 0.3)'" />
            </form>
        </div>

        <div class="form login">
            <header>Login</header>
            <form action="loginval.php" method="POST" id="loginForm">
                <input type="email" name="email" placeholder="Email address" required />
                <div class="error-message" id="loginEmailError"></div>
                
                <input type="password" name="password" placeholder="Password" required />
                <div class="error-message" id="loginPasswordError"></div>
                
                <a href="#">Forgot password?</a>
                <input type="submit" value="Login" style="background: linear-gradient(to right, #242299, #3DCFD8); color: white; padding: 12px 24px; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 5px 10px rgba(64, 112, 244, 0.3);"
                    onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 15px rgba(64, 112, 244, 0.4)'"
                    onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 5px 10px rgba(64, 112, 244, 0.3)'" />
            </form>
        </div>
    </section>

    <script>
        const wrapper = document.querySelector(".wrapper");
        const signupHeader = document.querySelector(".signup header");
        const loginHeader = document.querySelector(".login header");
        const allowedDomains = ['vdartinc.com', 'dimiour.io', 'trustpeople.com'];

        // Toggle between login and signup forms
        loginHeader.addEventListener("click", () => {
            wrapper.classList.add("active");
        });
        signupHeader.addEventListener("click", () => {
            wrapper.classList.remove("active");
        });

        // Show notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => notification.classList.add('show'), 100);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 500);
            }, 5000);
        }

        // Email validation
        function validateEmail(email) {
            const domain = email.split('@')[1];
            return allowedDomains.includes(domain);
        }

        // Form validation
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            const emailInput = this.querySelector('input[name="email"]');
            const email = emailInput.value.trim();
            
            if (!validateEmail(email)) {
                e.preventDefault();
                emailInput.classList.add('error');
                document.getElementById('emailError').style.display = 'block';
                document.getElementById('emailError').textContent = 
                    'Please use a valid company email (@vdartinc.com, @dimiour.io, @trustpeople.com)';
                showNotification('Please use a valid company email', 'error');
            }
        });

        // Real-time email validation
        document.querySelector('input[name="email"]').addEventListener('blur', function() {
            const email = this.value.trim();
            if (email && !validateEmail(email)) {
                this.classList.add('error');
                document.getElementById('emailError').style.display = 'block';
                document.getElementById('emailError').textContent = 
                    'Please use a valid company email (@vdartinc.com, @dimiour.io, @trustpeople.com)';
            } else {
                this.classList.remove('error');
                document.getElementById('emailError').style.display = 'none';
            }
        });

        // Check for session messages
        <?php if(isset($_SESSION['error'])): ?>
            showNotification('<?php echo addslashes($_SESSION['error']); ?>', 'error');
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            showNotification('<?php echo addslashes($_SESSION['success']); ?>', 'success');
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
    </script>

  </body>

</html>