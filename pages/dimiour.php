<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $outlookSignature = generateSignature($_POST);

    $ceipalSignature = generateCeipalSignature($_POST);

    $preview = true;

}


?>



<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>VDart Professional Email Signature Generator</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">

    <style>

        :root {

            --primary: #D4A373;

            --secondary: #4A4A4A;

            --accent: #F9E4D4;

            --background: #f8fafc;

            --card-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);

        }



        body {

            margin: 0;

            min-height: 100vh;

            font-family: 'Poppins', sans-serif;

            background: linear-gradient(180deg, #f7f7f7, #e6e6e6);

            color: var(--secondary);

        }



        .hero-section {

    text-align: center;

    padding: 100px 20px;

    background: 

        linear-gradient(

            rgba(36, 34, 153, 0.95) 0%,

            rgba(36, 34, 153, 0.95) 25%,

            #f7f7f7 100%

        ),

        url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80');

    background-size: cover;

    background-position: center;

    color: white;

    position: relative;

    overflow: hidden;

}







h2 {

    font-size: 3.5rem;

    font-weight: 700;

    margin-bottom: 1.5rem;

    background: linear-gradient(45deg,rgb(249, 252, 252),rgb(252, 253, 255));

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;

    /* text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.2); Slightly stronger shadow for better readability */

}


h3 {

font-size: 3.5rem;

font-weight: 700;

margin-bottom: 1.5rem;

background: linear-gradient(45deg,rgb(55, 54, 54),rgb(36, 37, 37));

-webkit-background-clip: text;

-webkit-text-fill-color: transparent;

text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.2); /* Slightly stronger shadow for better readability */

}

h4 {

font-size: 1.5rem;

font-weight: 700;

margin-bottom: 1.5rem;

background: linear-gradient(45deg,rgb(55, 54, 54),rgb(36, 37, 37));

-webkit-background-clip: text;

-webkit-text-fill-color: transparent;

text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.2); /* Slightly stronger shadow for better readability */

}

h1 {

    font-size: 3.5rem;

    font-weight: 700;

    margin-bottom: 1.5rem;

    background: linear-gradient(45deg,rgb(0, 6, 6),rgb(61, 63, 65));

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;

    text-shadow: 3px 3px 5px rgba(0, 0, 0, 0.2); /* Slightly stronger shadow for better readability */

}





        .hero-section p {

            font-size: 1.2rem;

            max-width: 600px;

            margin: 0 auto;

            line-height: 1.6;

        }



        .nav-link {

            color: var(--secondary);

            text-decoration: none;

            padding: 0.5rem 1rem;

            border-radius: 0.5rem;

            transition: all 0.3s ease;

            position: relative;

        }



        .nav-link::after {

            content: '';

            position: absolute;

            bottom: 0;

            left: 50%;

            transform: translateX(-50%);

            width: 0;

            height: 2px;

            background: var(--primary);

            transition: width 0.3s ease;

        }



        .nav-link:hover::after {

            width: 80%;

        }



        .nav-link.active {

            color: var(--primary);

            font-weight: 500;

        }



        .nav-link.active::after {

            width: 80%;

        }



        /* Profile Menu Styles */

        .profile-menu {

            position: relative;

        }



        .profile-button {

            display: flex;

            align-items: center;

            gap: 0.5rem;

            padding: 0.5rem 1rem;

            border-radius: 2rem;

            border: 2px solid #e2e8f0;

            transition: all 0.3s ease;

            cursor: pointer;

        }



        .profile-button:hover {

            border-color: var(--primary);

            background: #f8fafc;

        }



        .profile-menu-content {

            position: absolute;

            top: 120%;

            right: 0;

            background: white;

            border-radius: 1rem;

            box-shadow: var(--card-shadow);

            min-width: 200px;

            padding: 0.5rem;

            opacity: 0;

            visibility: hidden;

            transform: translateY(-10px);

            transition: all 0.3s ease;

        }



        .profile-menu.active .profile-menu-content {

            opacity: 1;

            visibility: visible;

            transform: translateY(0);

        }



        .profile-menu-item {

            display: flex;

            align-items: center;

            gap: 0.5rem;

            padding: 0.75rem 1rem;

            color: var(--secondary);

            text-decoration: none;

            border-radius: 0.5rem;

            transition: all 0.3s ease;

        }



        .profile-menu-item:hover {

            background: #f8fafc;

            color: var(--primary);

        }



        /* Main Content Layout */

        .page-container {

            max-width: 1440px;

            margin: 0 auto;

            padding: 2rem;

        }



        .content-header {

            margin-bottom: 2rem;

            text-align: center;

        }



        .feature-grid {

            display: grid;

            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));

            gap: 2rem;

            margin-bottom: 3rem;

        }



        .feature-card {

            background: white;

            border-radius: 1rem;

            padding: 2rem;

            box-shadow: var(--card-shadow);

            transition: transform 0.3s ease;

        }



        .feature-card:hover {

            transform: translateY(-5px);

        }



        .feature-icon {

            background: linear-gradient(135deg, #242297, #00C9FF);

            width: 3rem;

            height: 3rem;

            border-radius: 0.75rem;

            display: flex;

            align-items: center;

            justify-content: center;

            margin-bottom: 1rem;

        }



        /* Form and Preview Sections */

        .split-layout {

            display: grid;

            grid-template-columns: 1.2fr 0.8fr;

            gap: 2rem;

            margin-top: 3rem;

        }



        .form-section {

            background: white;

            border-radius: 1.5rem;

            padding: 2rem;

            box-shadow: var(--card-shadow);

        }



        .preview-section {

            background: #f2f2f2;

            border-radius: 1.5rem;

            padding: 2rem;

            backdrop-filter: blur(10px);

            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

        }



        /* Form Styles */

        .form-group {

            position: relative;
            display: flex;
            flex-direction: column;

        }



        .form-label {

            position: absolute;
            top: 50%;
            left: 10px;
            transform: translateY(-50%);
            transition: all 0.3s ease;
            color: #777;
            background: white;
            padding: 0 5px;

        }



        .form-input {

            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: transparent;
            appearance: none; /* Removes default browser styling */

        }

        select.form-input {
            cursor: pointer;
        }



        .form-input:focus {

            border-color: var(--primary);

            box-shadow: 0 0 0 3px rgba(212, 163, 115, 0.2);

            outline: none;

        }



        input:focus ~ .form-label,
        input:not(:placeholder-shown) ~ .form-label,
        select:focus ~ .form-label,
        select:not([value=""]) ~ .form-label {
            top: 0;
            font-size: 12px;
            color: #333;
        }



        .submit-btn {

            background: linear-gradient(135deg, #242297, #00C9FF);

            color: white;

            width: 100%;

            padding: 1rem;

            border-radius: 0.75rem;

            border: none;

            font-weight: 500;

            cursor: pointer;

            transition: all 0.3s ease;

        }



        .submit-btn:hover {

            transform: translateY(-2px);

            box-shadow: 0 8px 15px rgba(212, 163, 115, 0.2);

        }



        /* Preview Tabs */

        .tab-container {

            display: flex;

            gap: 1rem;

            margin-bottom: 2rem;

        }



        .tab-button {

            flex: 1;

            padding: 1rem;

            border: none;

            border-radius: 0.75rem;

            background: white;

            color: var(--secondary);

            font-weight: 500;

            cursor: pointer;

            transition: all 0.3s ease;

        }



        .tab-button.active {

            background: linear-gradient(135deg, #242297, #00C9FF);

            color: white;

        }



        /* Responsive Design */

        @media (max-width: 1024px) {

            .split-layout {

                grid-template-columns: 1fr;

            }

            

            .feature-grid {

                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));

            }

        }



        @media (max-width: 768px) {

            .header-wrapper {

                flex-direction: column;

                gap: 1rem;

            }

            

            .nav-links {

                flex-wrap: wrap;

                justify-content: center;

            }

        }



        .help-icon:hover {

            background-color: rgba(0, 0, 0, 0.05);

        }



        .help-icon svg {

            width: 24px;

            height: 24px;

            fill: none;

            stroke: #9ca3af;

            stroke-width: 2;

            transition: stroke 0.3s ease;

        }



        .help-icon:hover svg {

            stroke: #4b5563;

        }



        /* Tooltip styles */

        .help-icon .tooltip {

            position: absolute;

            top: calc(100% + 10px);

            right: 0;

            width: 200px;

            padding: 8px 12px;

            background: #1f2937;

            color: white;

            border-radius: 6px;

            font-size: 0.875rem;

            opacity: 0;

            visibility: hidden;

            transition: all 0.3s ease;

            z-index: 10;

            text-align: left;

            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);

        }



        .help-icon .tooltip::before {

            content: '';

            position: absolute;

            top: -6px;

            right: 10px;

            width: 12px;

            height: 12px;

            background: #1f2937;

            transform: rotate(45deg);

        }



        .help-icon:hover .tooltip {

            opacity: 1;

            visibility: visible;

            transform: translateY(0);

        }



        /* Modal and Navigation Styles */

.help-modal-overlay {

    display: none;

    position: fixed;

    top: 0;

    left: 0;

    right: 0;

    bottom: 0;

    background: rgba(0, 0, 0, 0.5);

    backdrop-filter: blur(5px);

    z-index: 1000;

}



.help-modal {

    position: fixed;

    top: 50%;

    left: 50%;

    transform: translate(-50%, -50%);

    background: white;

    width: 90%;

    max-width: 800px;

    border-radius: 20px;

    overflow: hidden;

    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);

}



.help-modal-header {

    background: linear-gradient(135deg, #0047bb, #6a86b5);

    color: white;

    padding: 20px;

    position: relative;

}



.help-modal-title {

    font-size: 24px;

    font-weight: 600;

    margin: 0;

}



.help-modal-subtitle {

    font-size: 14px;

    opacity: 0.9;

    margin-top: 5px;

}



.help-modal-close {

    position: absolute;

    top: 20px;

    right: 20px;

    background: rgba(255, 255, 255, 0.2);

    border: none;

    width: 30px;

    height: 30px;

    border-radius: 50%;

    cursor: pointer;

    display: flex;

    align-items: center;

    justify-content: center;

    color: white;

    transition: all 0.3s ease;

}



.help-modal-close:hover {

    background: rgba(255, 255, 255, 0.3);

    transform: rotate(90deg);

}



.help-modal-content {

    padding: 30px;

    max-height: 60vh;

    overflow-y: auto;

}



.help-step {

    display: none;

    animation: fadeIn 0.3s ease;

}



.help-step.active {

    display: block;

}



.step-content {

    margin-bottom: 20px;

}



.step-image {

    width: 100%;

    border-radius: 10px;

    overflow: hidden;

    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);

    margin-bottom: 20px;

}



.step-image img {

    width: 100%;

    height: auto;

    display: block;

}



.step-description {

    color: #4a5568;

    line-height: 1.6;

}



.step-description ol {

    padding-left: 20px;

    margin: 15px 0;

}



.step-description li {

    margin-bottom: 10px;

}



.help-modal-navigation {

    display: flex;

    justify-content: space-between;

    align-items: center;

    padding: 20px;

    background: #f8f9fa;

    border-top: 1px solid #e9ecef;

}



.nav-button {

    background: #0047bb;

    color: white;

    border: none;

    padding: 10px 20px;

    border-radius: 8px;

    cursor: pointer;

    font-weight: 500;

    display: flex;

    align-items: center;

    gap: 8px;

    transition: all 0.3s ease;

}



.nav-button:hover {

    background:rgb(113, 162, 241);

    transform: translateY(-2px);

}



.nav-button:disabled {

    background: #e2e8f0;

    cursor: not-allowed;

    transform: none;

}



.step-indicators {

    display: flex;

    gap: 8px;

}



.step-indicator {

    width: 10px;

    height: 10px;

    border-radius: 50%;

    background: #e2e8f0;

    cursor: pointer;

    transition: all 0.3s ease;

}



.step-indicator.active {

    background: #0047bb;

    transform: scale(1.2);

}



@keyframes fadeIn {

    from {

        opacity: 0;

        transform: translateY(10px);

    }

    to {

        opacity: 1;

        transform: translateY(0);

    }

}



@media (max-width: 768px) {

    .help-modal {

        width: 95%;

        margin: 20px;

    }

    

    .help-modal-content {

        max-height: 70vh;

    }

}



.modal-tabs {

    display: flex;

    gap: 1rem;

    margin: 1rem 0;

    padding: 0.5rem;

    background: #f3f4f6;

    border-radius: 0.5rem;

}



.modal-tab {

    display: flex;

    align-items: center;

    gap: 0.5rem;

    padding: 0.75rem 1.5rem;

    border-radius: 0.375rem;

    border: none;

    background: transparent;

    color: #6b7280;

    cursor: pointer;

    transition: all 0.2s ease;

}



.modal-tab .tab-icon {

    transition: color 0.2s ease;

}



.modal-tab:hover {

    background: rgba(255, 255, 255, 0.5);

}



.modal-tab.active {

    background: white;

    color: #2563eb;

    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);

}



.steps-container {

    display: none;

}



.steps-container.active {

    display: block;

}

.home-icon {

        text-decoration: none;

        color:rgb(253, 250, 250); /* Tailwind blue-600 */

        font-weight: bold;

        position: absolute;

        top: 20px;

        right: 20px;

    }



    .home-icon:hover {

        color:rgb(180, 192, 224); /* Tailwind hover blue-700 */

    }



    .home-icon svg {

        stroke: currentColor;

        width: 32px;

        height: 32px; /* Increased size for better visibility */

    }



    .home-icon span {

        font-size: 18px; /* Larger text for better readability */

    }

    .top-left-logo {
        position: absolute;
        top: 20px;
        left: 20px;
        height: 60px;
        transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
    }

    /* Add a hover effect to make it more lively */
    .top-left-logo:hover {
        transform: scale(1.1);
        opacity: 0.9;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
        .top-left-logo {
            height: 50px; /* Reduce size on mobile */
            right: 10px;
            top: 10px;
        }
    }

    /* Home Button Styles */
    .home-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color:rgb(254, 254, 255); /* Blue */
        font-size: 20px;
        font-weight: 800;
        padding: 18px 22px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.2);
        box-shadow: 0 5px 15px rgba(0, 119, 255, 0.2);
        transition: all 0.3s ease-in-out;
        backdrop-filter: blur(8px); /* Glassmorphism Effect */
    }

    /* Hover Effect */
    .home-icon:hover {
        color: white;
        background:rgb(1, 6, 12);
        box-shadow: 0 5px 25px rgba(0, 119, 255, 0.4);
        transform: scale(1.05);
    }

    /* Click (Press) Effect */
    .home-icon:active {
        transform: scale(0.95);
    }

    /* Home Icon SVG Styles */
    .home-icon svg {
        stroke: currentColor;
        width: 28px;
        height: 28px;
        transition: transform 0.3s ease-in-out;
    }

    /* Glow Effect on Hover */
    .home-icon:hover svg {
        transform: scale(1.2);
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
        .home-icon {
            top: 10px;
            left: 10px;
            font-size: 16px;
            padding: 8px 12px;
        }

        .home-icon svg {
            width: 24px;
            height: 24px;
        }
    }

    .cta-link {
   display: inline-block;
   margin: 20px 0;
   text-decoration: none;
   transition: transform 0.3s ease;
}

.cta-link:hover {
   transform: translateY(-2px);
}

.cta-container {
   display: flex;
   align-items: center;
   gap: 15px;
   padding: 12px 20px;
   background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
   border: 1px solid #e1e4e8;
   border-radius: 12px;
   box-shadow: 0 2px 10px rgba(0,0,0,0.05);
   transition: all 0.3s ease;
}

.cta-container:hover {
   background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
   box-shadow: 0 4px 15px rgba(0,0,0,0.08);
   border-color: #0077B5; /* LinkedIn blue */
}

.cta-icon {
   display: flex;
   align-items: center;
   justify-content: center;
   width: 40px;
   height: 40px;
   background: #0077B5; /* LinkedIn blue */
   border-radius: 50%;
   color: white;
   font-size: 20px;
   transition: transform 0.3s ease;
}

.cta-container:hover .cta-icon {
   transform: scale(1.1);
}

.cta-text {
   display: flex;
   flex-direction: column;
   gap: 4px;
}

.cta-question {
   color: #4a5568;
   font-size: 14px;
}

.cta-action {
   color: #0077B5; /* LinkedIn blue */
   font-weight: 600;
   font-size: 15px;
   position: relative;
}

.cta-action::after {
   content: '';
   position: absolute;
   bottom: -2px;
   left: 0;
   width: 100%;
   height: 2px;
   background: #0077B5; /* LinkedIn blue */
   transform: scaleX(0);
   transform-origin: right;
   transition: transform 0.3s ease;
}

.cta-container:hover .cta-action::after {
   transform: scaleX(1);
   transform-origin: left;
}

.cta-arrow {
   margin-left: auto;
   color: #0077B5; /* LinkedIn blue */
   font-size: 16px;
   transition: transform 0.3s ease;
}

.cta-container:hover .cta-arrow {
   transform: translateX(5px);
}

@media (max-width: 768px) {
   .cta-container {
       padding: 10px 15px;
   }
   
   .cta-icon {
       width: 35px;
       height: 35px;
       font-size: 16px;
   }
   
   .cta-question {
       font-size: 13px;
   }
   
   .cta-action {
       font-size: 14px;
   }
}

.feedback-card {
        display: block;
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        text-align: center;
        text-decoration: none;
        color: inherit;
    }

    .feedback-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    }

    .card-content {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .icon-container {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(0, 119, 255, 0.1);
        margin-bottom: 10px;
    }

    .feedback-icon {
        width: 60px;
        height: auto;
    }

    .feedback-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .feedback-text {
        font-size: 14px;
        color: #4a5568;
        margin-bottom: 10px;
    }

    .feedback-btn {
        background: linear-gradient(135deg, #0066CC, #00CC99);
        color: white;
        font-size: 14px;
        padding: 10px 15px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .feedback-btn:hover {
        background: linear-gradient(135deg, #0055aa, #009977);
        transform: scale(1.05);
    }
    .custom-titles-section {
    border-top: 1px solid #eee;
    margin-top: 8px;
    padding-top: 8px;
}

.custom-titles-section .option-category {
    color: #242297;
    font-weight: 600;
    background: #f8f9fa;
    padding: 8px 15px;
    border-bottom: 1px solid #eee;
}

.custom-titles-section .custom-option {
    padding: 8px 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.custom-titles-section .custom-option:hover {
    background-color: #f8f9fa;
}

/* Help Icon and Tooltip Styles */
.help-container1 {
    position: fixed;
    bottom: 2rem;
    left: 2rem;
    z-index: 1000;
}

.help-icon1 {
    background: linear-gradient(135deg, #242297, #3A7BD5);
    color: white;
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 4px 15px rgba(36, 34, 151, 0.3);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.help-icon1:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(36, 34, 151, 0.4);
}

.help-tooltip {
    position: absolute;
    bottom: 120%;
    left: 0;
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    width: 300px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.help-container1:hover .help-tooltip {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.tooltip-header {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 1rem;
    padding-bottom: 0.8rem;
    border-bottom: 2px solid rgba(36, 34, 151, 0.1);
}

.tooltip-header i {
    color: #242297;
    font-size: 1.5rem;
}

.tooltip-header h3 {
    color: #242297;
    margin: 0;
    font-size: 1.2rem;
    font-weight: 600;
}

.tooltip-content p {
    color: #666;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.8rem;
    margin-bottom: 0.8rem;
    padding: 0.8rem;
    border-radius: 8px;
    background: rgba(36, 34, 151, 0.05);
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: rgba(36, 34, 151, 0.1);
    transform: translateX(5px);
}

.contact-item i {
    color: #242297;
    font-size: 1rem;
}

.contact-item a {
    color: #242297;
    text-decoration: none;
    font-size: 0.9rem;
    transition: color 0.3s ease;
}

.contact-item a:hover {
    color: #3A7BD5;
}

/* Animation for the help icon */
@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(36, 34, 151, 0.4);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(36, 34, 151, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(36, 34, 151, 0);
    }
}

.help-icon1 {
    animation: pulse 2s infinite;
}
    </style>

</head>

<body>


    <!-- Enhanced Header -->

    <div class="hero-section relative">

    <!-- Logout Button (Top Left Corner) -->
<button onclick="logout()" style="
    position: absolute;
    top: 20px;
    right: 180px; /* Fixed at top left */
    padding: 12px 24px;
    font-family: 'Poppins', sans-serif;
    font-size: 16px;
    font-weight: 600;
    color: white;
    background: linear-gradient(135deg, #cc0000, #ff6666);
    border: none;
    border-radius: 10px;
    cursor: pointer;
    box-shadow: 0 5px 15px rgba(255, 68, 68, 0.3);
    transition: all 0.3s ease-in-out;
    text-transform: uppercase;
    letter-spacing: 1px;
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    gap: 8px;
    z-index: 1000; /* Ensures it stays on top */
"
    onmouseover="this.style.boxShadow='0 5px 25px rgba(255, 68, 68, 0.5)';"
    onmouseout="this.style.boxShadow='0 5px 15px rgba(255, 68, 68, 0.3)';"
    onmousedown="this.style.transform='scale(0.95)';"
    onmouseup="this.style.transform='scale(1)';">
    
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M10 17l5-5-5-5"></path>
        <path d="M15 12H3"></path>
        <path d="M19 21V3"></path>
    </svg>

    Logout
</button>

<!-- Home Button (Next to Logout) -->
<a href="../index.php" class="home-icon" style="
    position: absolute;
    top: 20px;
    right: 20px; /* Pushes it to the right of Logout */
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
    color: white;
    font-size: 16px;
    font-weight: 600;
    padding: 10px 28px;
    border-radius: 10px;
    background: linear-gradient(135deg, #0066CC, #00CC99);
    box-shadow: 0 5px 15px rgba(0, 102, 204, 0.3);
    transition: all 0.3s ease-in-out;
    text-transform: uppercase;
    letter-spacing: 1px;
    backdrop-filter: blur(10px);
    cursor: pointer;
    z-index: 999; /* Ensures proper layering */
"
    onmouseover="this.style.boxShadow='0 5px 25px rgba(0, 102, 204, 0.5)';"
    onmouseout="this.style.boxShadow='0 5px 15px rgba(0, 102, 204, 0.3)';"
    onmousedown="this.style.transform='scale(0.95)';"
    onmouseup="this.style.transform='scale(1)';">

    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M3 12l9-9 9 9"></path>
        <path d="M9 21V9h6v12"></path>
    </svg>

    Home
</a>


    <!-- VDart Logo Positioned in Top Right Corner -->
    <img src="images/vdartwhitelogo (1).png" 
        alt="VDart Logo" 
        class="top-left-logo">

    <!-- Hero Section Content -->

    <h2>VDart Email Signature Generator</h2>

    <p>Generate a professional email signature that reflects our brand identity and includes all the essential contact information your recipients need.</p>

</div>



    <!-- Main Content -->

    <div class="page-container">

        

<!-- Help Modal -->

<div id="helpModal" class="help-modal-overlay">

    <div class="help-modal">

        <div class="help-modal-header">

            <h2 class="help-modal-title">How to Set Up Your Email Signature</h2>

            

            <!-- Tab Switcher -->

            <div class="modal-tabs">

                <button class="modal-tab active" data-tab="outlook">

                    <svg class="tab-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>

                    </svg>

                    Outlook Steps

                </button>

                <button class="modal-tab" data-tab="ceipal">

                    <svg class="tab-icon" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>

                    </svg>

                    CEIPAL Steps

                </button>

            </div>

            <button class="help-modal-close" onclick="closeHelpModal()">&times;</button>

        </div>

        

        <div class="help-modal-content">

            <!-- Outlook Steps Container -->

            <div class="steps-container active" id="outlookSteps">

                <!-- [Previous Outlook steps content remains the same] -->

                <div class="help-step active" data-step="1">

                <div class="step-content">

                    <div class="step-image">

                        <img src="images/Outlook_step1.png" alt="Fill the form">

                    </div>

                    <div class="step-description">

                        <h4>Step 1: Fill and Generate Signature</h4>

                        <ol>

                            <li>Enter your Full Name</li>

                            <li>Enter your Designation</li>

                            <li>Add your Phone Number (format: 4703238433)</li>

                            <li>Add your Email Address</li>

                            <li>Add your LinkedIn Profile URL (optional)</li>

                            <li>Click "Generate Signature" button</li>

                        </ol>

                    </div>

                </div>

            </div>



            <!-- Step 2 -->

            <div class="help-step" data-step="2">

                <div class="step-content">

                    <div class="step-image">

                        <img src="images/Outlook_step2.png" alt="Copy signature">

                    </div>

                    <div class="step-description">

                        <h4>Step 2: Copy Generated Signature</h4>

                        <ol>

                            <li>Switch to "Outlook Format" tab in preview section</li>

                            <li>Review your generated signature</li>

                            <li>Click "Copy Signature for Outlook" button</li>

                            <li>Wait for the "Signature copied successfully" message</li>

                        </ol>

                    </div>

                </div>

            </div>



            <!-- Step 3 -->

            <div class="help-step" data-step="3">

                <div class="step-content">

                    <div class="step-image">

                        <img src="images/Outlook_step3.png" alt="Access Outlook settings">

                    </div>

                    <div class="step-description">

                        <h4>Step 3: Access Outlook Settings</h4>

                        <ol>

                            <li>Open Microsoft Outlook</li>

                            <li>Click on "File" in the top menu</li>

                            <li>Click "Options" in the left sidebar</li>

                            <li>Select "Mail" from the left menu</li>

                            <li>Find and click the "Signatures..." button</li>

                        </ol>

                    </div>

                </div>

            </div>



            <!-- Step 4 -->

            <div class="help-step" data-step="4">

                <div class="step-content">

                    <div class="step-image">

                        <img src="images/Outlook_step4.png" alt="Create new signature">

                    </div>

                    <div class="step-description">

                        <h4>Step 4: Create New Signature</h4>

                        <ol>

                            <li>Click the "New" button</li>

                            <li>Enter a name for your signature (e.g., "VDart Signature")</li>

                            <li>Click "OK" to create</li>

                            <li>In the right pane, paste your copied signature (Ctrl+V)</li>

                            <li>Verify that all elements appear correctly</li>

                        </ol>

                    </div>

                </div>

            </div>



            <!-- Step 5 -->

            <div class="help-step" data-step="5">

                <div class="step-content">

                    <div class="step-image">

                        <img src="images/Outlook_step5.png" alt="Set default signature">

                    </div>

                    <div class="step-description">

                        <h4>Step 5: Set Default Signature</h4>

                        <ol>

                            <li>Under "Choose default signature"</li>

                            <li>Select your email account from the "E-mail account" dropdown</li>

                            <li>Choose your new signature for "New messages"</li>

                            <li>Choose it for "Replies/forwards" if desired</li>

                            <li>Click "OK" to save all changes</li>

                        </ol>

                    </div>

                </div>

            </div>

            </div>



            <!-- CEIPAL Steps Container -->

            <div class="steps-container" id="ceipalSteps">

                <!-- Step 1 -->

                <div class="help-step active" data-step="1">

                    <div class="step-content">

                        <div class="step-image">

                            <img src="images/Ceipal Step1 (1).png" alt="Fill the form">

                        </div>

                        <div class="step-description">

                            <h4>Step 1: Fill and Generate Signature</h4>

                            <ol>

                                <li>Enter your Full Name</li>

                                <li>Enter your Designation</li>

                                <li>Add your Phone Number</li>

                                <li>Add your Email Address</li>

                                <li>Add your LinkedIn Profile URL (optional)</li>

                                <li>Click "Generate Signature" button</li>

                            </ol>

                        </div>

                    </div>

                </div>



                <!-- Step 2 -->

                <div class="help-step" data-step="2">

                    <div class="step-content">

                        <div class="step-image">

                            <img src="images/Ceipal Step2 (1).png" alt="Copy signature">

                        </div>

                        <div class="step-description">

                            <h4>Step 2: Copy Generated Signature</h4>

                            <ol>

                                <li>Switch to "CEIPAL Format" tab in preview section</li>

                                <li>Review your generated signature</li>

                                <li>Click "Copy Signature for CEIPAL" button</li>

                                <li>Wait for the "Signature copied" message</li>

                            </ol>

                        </div>

                    </div>

                </div>



                <!-- Step 3 -->

                <div class="help-step" data-step="3">

                    <div class="step-content">

                        <div class="step-image">

                            <img src="images/Ceipal Step3 (1).png" alt="Access CEIPAL">

                        </div>

                        <div class="step-description">

                            <h4>Step 3: Access CEIPAL Settings</h4>

                            <ol>

                                <li>Log into your CEIPAL account</li>

                                <li>Click on your profile picture/icon</li>

                                <li>Select "Settings" from the dropdown</li>

                                <li>Navigate to "Email Settings"</li>

                            </ol>

                        </div>

                    </div>

                </div>



                <!-- Step 4 -->

                <div class="help-step" data-step="4">

                    <div class="step-content">

                        <div class="step-image">

                            <img src="images/Ceipal Step4 (1).png" alt="Set signature">

                        </div>

                        <div class="step-description">

                            <h4>Step 4: Set Your Signature</h4>

                            <ol>

                                <li>Locate the "Email Signature" section</li>

                                <li>Paste your copied signature (Ctrl+V)</li>

                                <li>Verify all elements appear correctly</li>

                                <li>Click "Save Changes" to apply</li>

                            </ol>

                        </div>

                    </div>

                </div>



                <!-- Step 5 -->

                <div class="help-step" data-step="5">

                    <div class="step-content">

                        <div class="step-image">

                            <img src="images/Ceipal Step5 (1).png" alt="Set signature">

                        </div>

                        <div class="step-description">

                            <h4>Step 5: Set Your Signature</h4>

                            <ol>

                                <li>Locate the "Email Signature" section</li>

                                <li>Paste your copied signature (Ctrl+V)</li>

                                <li>Verify all elements appear correctly</li>

                                <li>Click "Save Changes" to apply</li>

                            </ol>

                        </div>

                    </div>

                </div>



                <!-- Step 6 -->

                <div class="help-step" data-step="6">

                    <div class="step-content">

                        <div class="step-image">

                            <img src="images/Ceipal Step6 (1).png" alt="Set signature">

                        </div>

                        <div class="step-description">

                            <h4>Step 6: Set Your Signature</h4>

                            <ol>

                                <li>Locate the "Email Signature" section</li>

                                <li>Paste your copied signature (Ctrl+V)</li>

                                <li>Verify all elements appear correctly</li>

                                <li>Click "Save Changes" to apply</li>

                            </ol>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        <div class="help-modal-navigation">

            <button class="nav-button prev-button" onclick="navigateStep(-1)" disabled>

                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>

                </svg>

                Previous

            </button>

            

            <div class="step-indicators">

                <!-- Will be dynamically updated based on active tab -->

            </div>



            <button class="nav-button next-button" onclick="navigateStep(1)">

                Next

                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>

                </svg>

            </button>

        </div>

    </div>

</div>





        <!-- Main Form and Preview Layout -->

        <div class="split-layout">

            <!-- Form Section -->

            <section class="form-section">

                

                <div class="bg-gradient-to-r from-primary/10 to-primary/5 p-4 rounded-lg mb-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Quick Tips:</h3>

                    <ul class="text-gray-600 space-y-2">

                        <li>• Use your official VDart email address</li>

                        <li>• Enter phone number without spaces or special characters</li>

                        <li>• Include your complete professional title</li>

                        <li>• Add your LinkedIn profile for better networking</li>

                    </ul>

                </div>



                <form id="signatureForm" class="space-y-6">

                    <div class="form-group">

                        <input type="text" name="name" id="name" class="form-input" placeholder=" " required>

                        <label class="form-label">Full Name</label>

                    </div>



                    <!-- Replace your existing dropdown with this complete code -->
<div class="form-group">
    <div class="custom-select-wrapper">
        <div class="custom-select">
            <div class="custom-select__trigger">
                <span>Select Your Title</span>
                <div class="arrow"></div>
            </div>
            <div class="custom-options">
                <div class="search-container">
                    <input type="text" placeholder="Search titles..." class="search-input">
                </div>
                <div class="add-custom" onclick="showCustomInput()">
                    <span class="add-icon">+</span>
                    Add Custom Title
                </div>
                <div class="custom-input-container" style="display: none;">
                    <input type="text" class="custom-title-input" placeholder="Enter custom title">
                    <div class="error-message"></div>
                    <div class="success-message"></div>
                    <div class="custom-input-buttons">
                        <button type="button" class="save-btn" onclick="saveCustomTitle()">Save</button>
                        <button type="button" class="cancel-btn" onclick="hideCustomInput()">Cancel</button>
                    </div>
                </div>
                
                <!-- Entry-Level & Mid-Level -->
                <div class="option-category">Entry-Level & Mid-Level</div>
                <div class="custom-option" data-value="Trainee Recruiter">Trainee Recruiter</div>
                <div class="custom-option" data-value="Technical Sourcer">Technical Sourcer</div>
                <div class="custom-option" data-value="Executive Technical Sourcer">Executive Technical Sourcer</div>
                <div class="custom-option" data-value="Technical Recruiter">Technical Recruiter</div>
                <div class="custom-option" data-value="Senior Recruiter">Senior Recruiter</div>
                <div class="custom-option" data-value="Senior Recruiter - APAC">Senior Recruiter - APAC</div>
                <div class="custom-option" data-value="Senior Recruiter - Gulf">Senior Recruiter - Gulf</div>
                <div class="custom-option" data-value="Lead Recruiter">Lead Recruiter</div>

                <!-- Team Leads & Managers -->
                <div class="option-category">Team Leads & Managers</div>
                <div class="custom-option" data-value="Associate Team Lead - Sourcing">Associate Team Lead - Sourcing</div>
                <div class="custom-option" data-value="Team Lead">Team Lead</div>
                <div class="custom-option" data-value="Delivery Manager">Delivery Manager</div>
                <div class="custom-option" data-value="Delivery Account Lead">Delivery Account Lead</div>
                <div class="custom-option" data-value="BU Engagement Leader">BU Engagement Leader</div>

                <!-- Client & Account Management -->
                <div class="option-category">Client & Account Management</div>
                <div class="custom-option" data-value="Customer Relationship Manager">Customer Relationship Manager</div>
                <div class="custom-option" data-value="Manager - Client Relations">Manager - Client Relations</div>
                <div class="custom-option" data-value="Account Manager">Account Manager</div>
                <div class="custom-option" data-value="Account Manager - Strategic Accounts">Account Manager - Strategic Accounts</div>
                <div class="custom-option" data-value="Senior Account Executive">Senior Account Executive</div>

                <!-- Leadership & Director Level -->
                <div class="option-category">Leadership & Director Level</div>
                <div class="custom-option" data-value="Operations Head - India Strategic Accounts">Operations Head - India Strategic Accounts</div>
                <div class="custom-option" data-value="Associate Director - Strategic Accounts">Associate Director - Strategic Accounts</div>
                <div class="custom-option" data-value="Associate Director - Delivery">Associate Director - Delivery</div>

                <!-- Corporate -->
                <div class="option-category">Corporate</div>
                <div class="custom-option" data-value="Assistant General Manager">Assistant General Manager</div>
                <div class="custom-option" data-value="Executive">Executive</div>
                <div class="custom-option" data-value="Global Delivery Head">Global Delivery Head</div>
                <div class="custom-option" data-value="Head of Learning and Development">Head of Learning and Development</div>
                <div class="custom-option" data-value="Assistant Manager">Assistant Manager</div>
                <div class="custom-option" data-value="Manager">Manager</div>
                <div class="custom-option" data-value="Manager - HR Operations">Manager - HR Operations</div>
                <div class="custom-option" data-value="Regional Head - Corporate HR">Regional Head - Corporate HR</div>
                <div class="custom-option" data-value="Lead">Lead</div>
                <div class="custom-option" data-value="L3 Support Engineer">L3 Support Engineer</div>
                <div class="custom-option" data-value="Senior Executive">Senior Executive</div>
                <div class="custom-option" data-value="L3 EPS Administrator">L3 EPS Administrator</div>
                <div class="custom-option" data-value="L2 Support Engineer">L2 Support Engineer</div>
                <div class="custom-option" data-value="Assistant Manager - Admin">Assistant Manager - Admin</div>
                <div class="custom-option" data-value="Lead - Digital Marketing">Lead - Digital Marketing</div>
                <div class="custom-option" data-value="Senior Manager">Senior Manager</div>
                <div class="custom-option" data-value="Executive Assistant - CEO">Executive Assistant - CEO</div>
                <div class="custom-option" data-value="Lead - HR TAG">Lead - HR TAG</div>
                <div class="custom-option" data-value="L3 Support System Engineer">L3 Support System Engineer</div>
                <div class="custom-option" data-value="L1 Support Engineer">L1 Support Engineer</div>
                <div class="custom-option" data-value="PMO Lead">PMO Lead</div>
                <div class="custom-option" data-value="Executive Multiskill Technician">Executive Multiskill Technician</div>
                <div class="custom-option" data-value="Office Assistant">Office Assistant</div>
                <div class="custom-option" data-value="Executive - Web Developer">Executive - Web Developer</div>
                <div class="custom-option" data-value="Executive - Graphic Designer">Executive - Graphic Designer</div>
                <div class="custom-option" data-value="Executive Technical Sourcer">Executive Technical Sourcer</div>
                <div class="custom-option" data-value="Executive - MIS">Executive - MIS</div>
                <div class="custom-option" data-value="Senior Executive - MIS">Senior Executive - MIS</div>
                <div class="custom-option" data-value="Executive - Digital Marketing Specialist">Executive - Digital Marketing Specialist</div>
                <div class="custom-option" data-value="Executive - Digital Marketing">Executive - Digital Marketing</div>
                <div class="custom-option" data-value="Executive - Multiskill Technician">Executive - Multiskill Technician</div>
                <div class="custom-option" data-value="PMO Lead - Strategic Transformation">PMO Lead - Strategic Transformation</div>
                <div class="custom-option" data-value="Lead - Personal Branding">Lead - Personal Branding</div>
                <div class="custom-option" data-value="Lead - Content Marketing">Lead - Content Marketing</div>
                <div class="custom-option" data-value="Associate - Digital Marketing">Associate - Digital Marketing</div>
                <div class="custom-option" data-value="Associate - Candidate Relationship">Associate - Candidate Relationship</div>
                <div class="custom-option" data-value="Executive - Response Manager">Executive - Response Manager</div>
                <div class="custom-option" data-value="Lead - Video Editor & Graphic Designer">Lead - Video Editor & Graphic Designer</div>
                <div class="custom-option" data-value="Executive Process Trainer">Executive Process Trainer</div>
                <div class="custom-option" data-value="Associate - Data Analyst">Associate - Data Analyst</div>
                <div class="custom-option" data-value="Associate">Associate</div>
                <div class="custom-option" data-value="Executive - Client Relationship">Executive - Client Relationship</div>
                <div class="custom-option" data-value="Digital Marketing Executive - Graphic Designer">Digital Marketing Executive - Graphic Designer</div>
                <div class="custom-option" data-value="Senior Executive - Video Editor">Senior Executive - Video Editor</div>
                <div class="custom-option" data-value="Lead - US HR">Lead - US HR</div>
                <div class="custom-option" data-value="Director - Sales Enablement">Director - Sales Enablement</div>
                <div class="custom-option" data-value="Associate - Graphic Designer">Associate - Graphic Designer</div>
                <div class="custom-option" data-value="Associate - Developer">Associate - Developer</div>
                <div class="custom-option" data-value="Response Management Coordinator">Response Management Coordinator</div>
                <div class="custom-option" data-value="Associate - Content writer">Associate - Content writer</div>
                <div class="custom-option" data-value="Communication Trainer">Communication Trainer</div>
                <div class="custom-option" data-value="Associate - Talent Acquisition">Associate - Talent Acquisition</div>
                <div class="custom-option" data-value="Executive - Video Editor">Executive - Video Editor</div>
                <div class="custom-option" data-value="Social Media Manager">Social Media Manager</div>
                <div class="custom-option" data-value="Executive - Talent Acquisition">Executive - Talent Acquisition</div>
                <div class="custom-option" data-value="Associate - Video Editor">Associate - Video Editor</div>
                <div class="custom-option" data-value="Associate - Response Management Coordinator">Associate - Response Management Coordinator</div>
                <div class="custom-option" data-value="Associate - GRC Analyst">Associate - GRC Analyst</div>
                <div class="custom-option" data-value="Senior Executive - GRC Analyst">Senior Executive - GRC Analyst</div>
                <div class="custom-option" data-value="Talent Acquisition Specialist">Talent Acquisition Specialist</div>
                <div class="custom-option" data-value="Associate - Corporate Driver">Associate - Corporate Driver</div>
                <div class="custom-option" data-value="Executive - Digital Talent Manager">Executive - Digital Talent Manager</div>
                <div class="custom-option" data-value="Senior Executive - Content Writer">Senior Executive - Content Writer</div>
                <div class="custom-option" data-value="Senior Executive - Business Development">Senior Executive - Business Development</div>
                <div class="custom-option" data-value="Associate - Community and Response Manager">Associate - Community and Response Manager</div>
                <div class="custom-option" data-value="Associate - Graphical Designer">Associate - Graphical Designer</div>
                <div class="custom-option" data-value="Associate - Business Development">Associate - Business Development</div>
                <div class="custom-option" data-value="Technical Specialist - Service Desk">Technical Specialist - Service Desk</div>
                <div class="custom-option" data-value="Subject Matter Expert - FullStack Web Suite">Subject Matter Expert - FullStack Web Suite</div>
                <div class="custom-option" data-value="Executive - Content writer">Executive - Content writer</div>
                <div class="custom-option" data-value="Executive - UI/UX Designer">Executive - UI/UX Designer</div>
                <div class="custom-option" data-value="Deputy Manager - Corporate Finance">Deputy Manager - Corporate Finance</div>
                <div class="custom-option" data-value="Content Creator">Content Creator</div>
                <div class="custom-option" data-value="Content Writer">Content Writer</div>
                <div class="custom-option" data-value="Lead - Process Trainer">Lead - Process Trainer</div>
                <div class="custom-option" data-value="IKAROS - Social Media Manager">IKAROS - Social Media Manager</div>
                <div class="custom-option" data-value="Executive Assistant">Executive Assistant</div>
                <div class="custom-option" data-value="Associate - AP Dimiour Operation">Associate - AP Dimiour Operation</div>
                <div class="custom-option" data-value="Assistant Manager - HR Business Partner">Assistant Manager - HR Business Partner</div>
                <div class="custom-option" data-value="Solarwinds Administrator">Solarwinds Administrator</div>
                <div class="custom-option" data-value="Support Contractor">Support Contractor</div>
                <div class="custom-option" data-value="Lead - Data Analyst">Lead - Data Analyst</div>
                <div class="custom-option" data-value="Senior Executive - Graphic Designer">Senior Executive - Graphic Designer</div>
                <div class="custom-option" data-value="Executive - HR">Executive - HR</div>
                <div class="custom-option" data-value="Lead - Social Media Specialist">Lead - Social Media Specialist</div>
                <div class="custom-option" data-value="Senior Web Developer">Senior Web Developer</div>

            </div>
        </div>
    </div>
    <input type="hidden" name="title" id="title" required>
    <label class="form-label">Designation</label>

    <div class="custom-input-container" style="display: none;">
    <input type="text" class="custom-title-input" placeholder="Enter custom title">
    <div class="error-message"></div>
    <div class="custom-input-buttons">
        <button type="button" class="save-btn" onclick="saveCustomTitle()">Save</button>
        <button type="button" class="cancel-btn" onclick="hideCustomInput()">Cancel</button>
    </div>
</div>
</div>

<style>
.custom-select-wrapper {
    position: relative;
    user-select: none;
    width: 100%;
}

.custom-select {
    position: relative;
    width: 100%;
}

.custom-select__trigger {
    position: relative;
    width: 100%;
    padding: 10px;
    font-size: 16px;
    background: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.custom-select__trigger:hover {
    border-color: #999;
}

.arrow {
    border: solid #666;
    border-width: 0 2px 2px 0;
    padding: 3px;
    transform: rotate(45deg);
    transition: transform 0.3s ease;
}

.custom-select.open .arrow {
    transform: rotate(-135deg);
}

.custom-options {
    position: absolute;
    display: none;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ccc;
    border-top: none;
    border-radius: 0 0 4px 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    z-index: 1000;
    max-height: 300px;
    overflow-y: auto;
}

.custom-select.open .custom-options {
    display: block;
}

.search-container {
    padding: 10px;
    border-bottom: 1px solid #eee;
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 2;
}

.search-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.add-custom {
    padding: 10px 15px;
    color: #2b6cb0;
    cursor: pointer;
    display: flex;
    align-items: center;
    transition: background-color 0.3s ease;
    border-bottom: 1px solid #eee;
}

.add-custom:hover {
    background-color: #f8f9fa;
}

.add-icon {
    margin-right: 8px;
    font-size: 16px;
    font-weight: bold;
}

.custom-input-container {
    padding: 10px;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.custom-title-input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    margin-bottom: 8px;
    font-size: 14px;
}

.custom-input-buttons {
    display: flex;
    gap: 8px;
}

.custom-input-buttons button {
    flex: 1;
    padding: 6px 12px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: all 0.3s ease;
}

.save-btn {
    background: #2b6cb0;
    color: white;
}

.save-btn:hover {
    background: #2c5282;
}

.cancel-btn {
    background: #e2e8f0;
    color: #4a5568;
}

.cancel-btn:hover {
    background: #cbd5e0;
}

.option-category {
    padding: 8px 15px;
    font-weight: 600;
    color: #4a5568;
    background: #f8f9fa;
    border-bottom: 1px solid #eee;
}

.custom-option {
    padding: 8px 15px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.custom-option:hover {
    background-color: #f8f9fa;
}

.custom-option.selected {
    background-color: #ebf8ff;
    color: #2b6cb0;
}

/* Scrollbar Styling */
.custom-options::-webkit-scrollbar {
    width: 6px;
}

.custom-options::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.custom-options::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

.custom-options::-webkit-scrollbar-thumb:hover {
    background: #999;
}

.error-message {
    color: #dc2626;
    font-size: 12px;
    margin-bottom: 8px;
    padding: 4px 8px;
    background-color: #fee2e2;
    border-radius: 4px;
    border: 1px solid #fecaca;
    display: none;
}

.shake {
    animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
}

@keyframes shake {
    10%, 90% { transform: translate3d(-1px, 0, 0); }
    20%, 80% { transform: translate3d(2px, 0, 0); }
    30%, 50%, 70% { transform: translate3d(-2px, 0, 0); }
    40%, 60% { transform: translate3d(2px, 0, 0); }
}

.error-message, .success-message {
    font-size: 12px;
    margin: 4px 0;
    padding: 8px;
    border-radius: 4px;
    display: none;
}

.error-message {
    color: #dc2626;
    background-color: #fee2e2;
}

.success-message {
    color: #059669;
    background-color: #d1fae5;
}

.custom-input-container {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 4px;
    margin-top: 10px;
}
</style>

<!-- Keep your existing HTML structure -->
<script>
// Constants
const STORAGE_KEY = 'customTitles';

// Helper Functions
async function getStoredTitles() {
    try {
        const formData = new FormData();
        formData.append('action', 'get');
        
        const response = await fetch('manage_titles.php', {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Network error');
        }

        const data = await response.json();
        return data.success ? data.titles : [];
    } catch (error) {
        console.error('Error loading titles:', error);
        showError('Error loading titles');
        return [];
    }
}

async function storeTitle(title) {
    try {
        const formData = new FormData();
        formData.append('action', 'save');
        formData.append('title', title);
        
        const response = await fetch('manage_titles.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        return data.success;
    } catch (error) {
        console.error('Error storing title:', error);
        return false;
    }
}

function createCustomOption(title) {
    const option = document.createElement('div');
    option.className = 'custom-option custom-title';  // Add custom-title class
    option.dataset.value = title;
    option.textContent = title;
    return option;
}

function ensureCustomTitlesSection() {
    const optionsContainer = document.querySelector('.custom-options');
    
    // Remove any existing custom titles section first
    const existingSection = optionsContainer.querySelector('.custom-titles-section');
    if (existingSection) {
        existingSection.remove();
    }
    
    // Create new custom titles section
    const customSection = document.createElement('div');
    customSection.className = 'custom-titles-section';
    
    // Add the category header
    const categoryHeader = document.createElement('div');
    categoryHeader.className = 'option-category';
    categoryHeader.textContent = 'Custom Titles';
    customSection.appendChild(categoryHeader);
    
    // Add it at the end of the options container
    optionsContainer.appendChild(customSection);
    
    return customSection;
}

async function loadCustomTitles() {
    try {
        const titles = await getStoredTitles();
        if (titles && titles.length > 0) {
            const customSection = ensureCustomTitlesSection();
            
            titles.forEach(title => {
                const option = createCustomOption(title);
                customSection.appendChild(option);
            });
            
            bindOptionEvents();
        }
    } catch (error) {
        console.error('Error loading titles:', error);
        showError('Error loading custom titles');
    }
}

function showAllOptions() {
    const select = document.querySelector('.custom-select');
    Array.from(select.querySelector('.custom-options').children).forEach(element => {
        if (element.classList.contains('option-category') || 
            element.classList.contains('custom-option')) {
            element.style.display = 'block';
        }
    });
}

function handleSearch(searchTerm) {
    const select = document.querySelector('.custom-select');
    let lastCategory = null;
    let hasVisibleOptions = false;
    
    Array.from(select.querySelector('.custom-options').children).forEach(element => {
        // Skip the search container and add custom button
        if (element.classList.contains('search-container') || 
            element.classList.contains('add-custom') ||
            element.classList.contains('custom-input-container')) {
            return;
        }

        if (element.classList.contains('option-category')) {
            lastCategory = element;
            element.style.display = 'none';
        } else if (element.classList.contains('custom-option')) {
            const matches = element.textContent.toLowerCase().includes(searchTerm.toLowerCase());
            element.style.display = matches ? 'block' : 'none';
            if (matches) {
                hasVisibleOptions = true;
                if (lastCategory) {
                    lastCategory.style.display = 'block';
                }
            }
        }
    });
}

function bindOptionEvents() {
    const allOptions = document.querySelectorAll('.custom-option');
    allOptions.forEach(option => {
        // Remove existing event listeners
        option.replaceWith(option.cloneNode(true));
        const newOption = document.querySelector(`[data-value="${option.dataset.value}"]`);
        newOption.addEventListener('click', () => selectOption(newOption));
    });
}

function selectOption(option) {
    const trigger = document.querySelector('.custom-select__trigger span');
    const hiddenInput = document.getElementById('title');
    const select = document.querySelector('.custom-select');
    
    // Remove previous selection
    const selected = document.querySelector('.custom-option.selected');
    if (selected) {
        selected.classList.remove('selected');
    }
    
    // Select new option
    option.classList.add('selected');
    trigger.textContent = option.textContent;
    hiddenInput.value = option.dataset.value;
    select.classList.remove('open');
    
    // Show all options again
    showAllOptions();
}

function showCustomInput() {
    const addCustomBtn = document.querySelector('.add-custom');
    const customInputContainer = document.querySelector('.custom-input-container');
    if (addCustomBtn && customInputContainer) {
        addCustomBtn.style.display = 'none';
        customInputContainer.style.display = 'block';
        customInputContainer.querySelector('input').focus();
    }
}

function hideCustomInput() {
    const addCustomBtn = document.querySelector('.add-custom');
    const customInputContainer = document.querySelector('.custom-input-container');
    if (addCustomBtn && customInputContainer) {
        addCustomBtn.style.display = 'flex';
        customInputContainer.style.display = 'none';
        customInputContainer.querySelector('input').value = '';
        const errorElement = document.querySelector('.error-message');
        if (errorElement) {
            errorElement.style.display = 'none';
        }
    }
}

function getAllExistingTitles() {
    // Get default titles
    const defaultTitles = Array.from(document.querySelectorAll('.custom-option:not(.custom-titles-section .custom-option)'))
        .map(option => option.textContent.replace('×', '').trim().toLowerCase());
    
    // Get custom titles
    const customTitles = getStoredTitles().map(title => title.toLowerCase());
    
    // Combine and remove duplicates
    return [...new Set([...defaultTitles, ...customTitles])];
}

function showError(message) {
    const errorElement = document.querySelector('.error-message');
    const successElement = document.querySelector('.success-message');
    
    if (errorElement) {
        errorElement.textContent = message;
        errorElement.style.display = 'block';
        successElement.style.display = 'none';
        
        setTimeout(() => {
            errorElement.style.display = 'none';
        }, 3000);
    }
}

function showSuccess(message) {
    const successElement = document.querySelector('.success-message');
    const errorElement = document.querySelector('.error-message');
    
    if (successElement) {
        successElement.textContent = message;
        successElement.style.display = 'block';
        errorElement.style.display = 'none';
        
        setTimeout(() => {
            successElement.style.display = 'none';
        }, 3000);
    }
}

async function saveCustomTitle() {
    const input = document.querySelector('.custom-title-input');
    const title = input.value.trim();
    
    if (!title) {
        showError('Please enter a title');
        return;
    }

    // Check if title already exists in the dropdown
    const existingOptions = Array.from(document.querySelectorAll('.custom-option'))
        .map(option => option.textContent.trim());
    
    if (existingOptions.includes(title)) {
        showError('This title already exists');
        return;
    }

    try {
        const formData = new FormData();
        formData.append('action', 'save');
        formData.append('title', title);
        
        const response = await fetch('manage_titles.php', {
            method: 'POST',
            body: formData
        });
        
        if (!response.ok) {
            throw new Error('Network error');
        }

        const data = await response.json();
        if (data.success) {
            // Reload all custom titles to ensure consistency
            await loadCustomTitles();
            
            // Clear input and hide container
            input.value = '';
            hideCustomInput();
            
            // Show success message
            showSuccess('Title added successfully');
            
            // Find and select the new option
            const newOption = Array.from(document.querySelectorAll('.custom-option'))
                .find(option => option.textContent.trim() === title);
            if (newOption) {
                selectOption(newOption);
            }
        } else {
            showError(data.message || 'Error saving title');
        }
    } catch (error) {
        console.error('Error:', error);
        showError('Error saving title');
    }
}

// Initialize dropdown
document.addEventListener('DOMContentLoaded', async function() {
    const selectWrapper = document.querySelector('.custom-select-wrapper');
    const select = selectWrapper.querySelector('.custom-select');
    const trigger = selectWrapper.querySelector('.custom-select__trigger');
    const searchInput = selectWrapper.querySelector('.search-input');

    await loadCustomTitles();
    bindOptionEvents();

    // Toggle dropdown
    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        select.classList.toggle('open');
        if (select.classList.contains('open')) {
            searchInput.value = ''; // Clear search input
            showAllOptions(); // Show all options when opening
            searchInput.focus();
        }
    });

    // Search functionality
    searchInput.addEventListener('input', (e) => {
        e.stopPropagation();
        const searchTerm = e.target.value;
        handleSearch(searchTerm);
    });

    // When selecting an option, show all options again
    select.addEventListener('click', (e) => {
        if (e.target.classList.contains('custom-option')) {
            selectOption(e.target);
            searchInput.value = ''; // Clear search input
            showAllOptions(); // Reset visibility of all options
        }
    });

    // Close when clicking outside
    document.addEventListener('click', (e) => {
        if (!selectWrapper.contains(e.target)) {
            select.classList.remove('open');
            hideCustomInput();
        }
    });

    // Prevent search input from closing dropdown
    searchInput.addEventListener('click', (e) => e.stopPropagation());

    // Handle custom title input
    const customTitleInput = document.querySelector('.custom-title-input');
    if (customTitleInput) {
        customTitleInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveCustomTitle();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                hideCustomInput();
                select.classList.remove('open');
            }
        });
    }

    // Stop propagation for custom input container
    const customInputContainer = document.querySelector('.custom-input-container');
    if (customInputContainer) {
        customInputContainer.addEventListener('click', (e) => e.stopPropagation());
    }
});

// Add this CSS
const adminStyles = document.createElement('style');
adminStyles.textContent = `
.admin-toggle {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
    display: flex;
    gap: 10px;
    align-items: center;
    padding: 8px 16px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.admin-switch {
    position: relative;
    display: inline-block;
    width: 44px;
    height: 24px;
}

.admin-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.admin-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e0;
    transition: .4s;
    border-radius: 24px;
}

.admin-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .4s;
    border-radius: 50%;
}

input:checked + .admin-slider {
    background-color: #2b6cb0;
}

input:checked + .admin-slider:before {
    transform: translateX(20px);
}

.custom-option .delete-btn {
    display: none;
    position: absolute;
    right: 10px;
    color: #dc2626;
    cursor: pointer;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
}

.custom-option {
    position: relative;
}

.custom-option:hover .delete-btn {
    display: inline;
}

.delete-btn:hover {
    background-color: #fee2e2;
}

.admin-password-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1001;
    justify-content: center;
    align-items: center;
}

.admin-password-content {
    background: white;
    padding: 24px;
    border-radius: 8px;
    width: 90%;
    max-width: 400px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.admin-password-content input {
    width: 100%;
    padding: 8px;
    margin: 16px 0;
    border: 1px solid #e2e8f0;
    border-radius: 4px;
}

.admin-password-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}

.admin-password-buttons button {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.admin-password-buttons .confirm {
    background: #2b6cb0;
    color: white;
}

.admin-password-buttons .cancel {
    background: #e2e8f0;
    color: #4a5568;
}
`;
document.head.appendChild(adminStyles);

// Add these elements to your page
const adminToggle = document.createElement('div');
adminToggle.className = 'admin-toggle';
adminToggle.innerHTML = `
    <span>Admin Mode</span>
    <label class="admin-switch">
        <input type="checkbox" id="adminToggle">
        <span class="admin-slider"></span>
    </label>
`;

const passwordModal = document.createElement('div');
passwordModal.className = 'admin-password-modal';
passwordModal.innerHTML = `
    <div class="admin-password-content">
        <h4>Enter Admin Password</h4>
        <input type="password" id="adminPassword" placeholder="Enter password">
        <div class="admin-password-buttons">
            <button class="cancel">Cancel</button>
            <button class="confirm">Confirm</button>
        </div>
    </div>
`;

document.body.appendChild(adminToggle);
document.body.appendChild(passwordModal);

// Add this JavaScript
const ADMIN_PASSWORD = 'trustpeople2024'; // Change this to your desired password
let isAdminMode = false;

function toggleAdminMode(password) {
    if (password === ADMIN_PASSWORD) {
        isAdminMode = !isAdminMode;
        document.getElementById('adminToggle').checked = isAdminMode;
        updateDeleteButtons();
        return true;
    }
    return false;
}

function showPasswordModal() {
    const modal = document.querySelector('.admin-password-modal');
    const input = document.getElementById('adminPassword');
    modal.style.display = 'flex';
    input.focus();
}

function hidePasswordModal() {
    const modal = document.querySelector('.admin-password-modal');
    const input = document.getElementById('adminPassword');
    modal.style.display = 'none';
    input.value = '';
}

// Add event listeners
document.getElementById('adminToggle').addEventListener('change', function(e) {
    if (this.checked && !isAdminMode) {
        this.checked = false; // Reset checkbox
        showPasswordModal();
    } else if (!this.checked && isAdminMode) {
        isAdminMode = false;
        updateDeleteButtons();
    }
});

document.querySelector('.admin-password-modal .confirm').addEventListener('click', () => {
    const password = document.getElementById('adminPassword').value;
    if (toggleAdminMode(password)) {
        hidePasswordModal();
    } else {
        alert('Incorrect password');
    }
});

document.querySelector('.admin-password-modal .cancel').addEventListener('click', hidePasswordModal);

document.getElementById('adminPassword').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
        document.querySelector('.admin-password-modal .confirm').click();
    } else if (e.key === 'Escape') {
        hidePasswordModal();
    }
});

// Update the updateDeleteButtons function
function updateDeleteButtons() {
    const customOptions = document.querySelectorAll('.custom-titles-section .custom-option');
    customOptions.forEach(option => {
        let deleteBtn = option.querySelector('.delete-btn');
        if (!deleteBtn && isAdminMode) {
            deleteBtn = document.createElement('span');
            deleteBtn.className = 'delete-btn';
            deleteBtn.textContent = '×';
            deleteBtn.title = 'Delete title';
            deleteBtn.onclick = (e) => {
                e.stopPropagation();
                if (confirm('Are you sure you want to delete this title?')) {
                    deleteCustomTitle(option);
                }
            };
            option.appendChild(deleteBtn);
        } else if (deleteBtn && !isAdminMode) {
            deleteBtn.remove();
        }
    });
}

async function deleteTitle(title) {
    try {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('title', title);
        
        const response = await fetch('manage_titles.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        return data.success;
    } catch (error) {
        console.error('Error deleting title:', error);
        return false;
    }
}

async function deleteCustomTitle(option) {
    if (!isAdminMode) return;
    
    const titleToDelete = option.textContent.replace('×', '').trim();
    const success = await deleteTitle(titleToDelete);
    
    if (success) {
        option.remove();
        
        // Reset selection if this was the selected option
        const trigger = document.querySelector('.custom-select__trigger span');
        const hiddenInput = document.getElementById('title');
        if (trigger.textContent === titleToDelete) {
            trigger.textContent = 'Select Your Title';
            hiddenInput.value = '';
        }
        
        // Remove custom section if no more titles
        const customSection = document.querySelector('.custom-titles-section');
        if (customSection && !customSection.querySelector('.custom-option')) {
            customSection.remove();
        }
    }
}

document.addEventListener('DOMContentLoaded', loadCustomTitles);

const deleteButtonStyles = document.createElement('style');
deleteButtonStyles.textContent = `
.custom-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-right: 30px !important;
}

.delete-btn {
    display: none;
    position: absolute;
    right: 10px;
    color: #dc2626;
    cursor: pointer;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 16px;
    font-weight: bold;
}

.custom-option:hover .delete-btn {
    display: inline;
}

.delete-btn:hover {
    background-color: #fee2e2;
}
`;
document.head.appendChild(deleteButtonStyles);
</script>




                    <div class="form-group">

                        <input type="tel" name="phone" id="phone" class="form-input" placeholder=" " required 

                               pattern="[0-9]{10}" title="Please enter a valid 10-digit phone number">

                        <label class="form-label">Phone Number</label>

                    </div>



                    <div class="form-group">
                        <input type="email" name="email" id="email" class="form-input" placeholder=" " required>
                        <label class="form-label">Email Address</label>
                        <small id="email-error" style="color: red; display: none;">Email must end with @vdartdigital.com</small>
                    </div>

                    <script>
                        document.getElementById("email").addEventListener("input", function() {
                            var email = this.value;
                            var errorMsg = document.getElementById("email-error");

                            if (!email.endsWith("@vdartdigital.com")) {
                                errorMsg.style.display = "block"; // Show error message
                                this.setCustomValidity("Email must end with @vdartdigital.com"); // Prevent form submission
                            } else {
                                errorMsg.style.display = "none"; // Hide error message
                                this.setCustomValidity(""); // Allow form submission
                            }
                        });
                    </script>



                    <div class="form-group">

                        <input type="url" name="linkedin" id="linkedin" class="form-input" placeholder=" ">

                        <label class="form-label">LinkedIn Profile URL</label>

                    </div>



                    <button type="submit" class="submit-btn group">

                        <span class="flex items-center justify-center gap-2">

                            Generate Signature

                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>

                            </svg>

                        </span>

                    </button>

                </form>

            </section>



        <section class="preview-section">

    <div class="relative mb-6">

        <div class="flex items-center justify-between">

            <h1 class="text-2xl font-bold text-gray-800">Preview</h1>

            <div class="flex items-center gap-4">

                <!-- Help Button - Changed to a more identifiable button -->

                <button class="help-icon bg-blue-100 hover:bg-blue-200 text-blue-700 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors duration-200">

                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>

                    </svg>

                    <span class="font-medium">Need Help?</span>

                </button>


            </div>

        </div>

    </div>



    <div class="tab-container">

        <button class="tab-button active" data-tab="outlook">

            <span class="flex items-center justify-center gap-2">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>

                </svg>

                Outlook

            </span>

        </button>

        <button class="tab-button" data-tab="ceipal">

            <span class="flex items-center justify-center gap-2">

                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>

                </svg>

                CEIPAL

            </span>

        </button>

    </div>



    <div class="preview-content bg-white rounded-lg shadow-lg p-6 mb-6">

        <div id="signature-preview" class="min-h-[200px]">

            <div class="text-gray-400 text-center py-10">

                Fill out the form to generate your signature

            </div>

        </div>

    </div>



    <div class="flex flex-col items-center gap-3 w-full text-center">
    <!-- Copy Signature Button -->
    <button id="copyButton" 
        class="flex items-center justify-center gap-3 px-5 py-3 w-full sm:w-auto text-white bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl shadow-lg hover:shadow-xl transition duration-300 ease-in-out transform hover:scale-105 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
        disabled>
        
        <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
        </svg>

        <span class="text-lg font-semibold">Copy Signature</span>

    </button>

    <!-- Navigation Message on the Next Line -->
    <a href="picturegenerator1.php" 
        class="cta-link">
        <div class="cta-container">
            <div class="cta-icon">
                <i class="fab fa-linkedin-in"></i> <!-- Changed to LinkedIn icon -->
            </div>
            <div class="cta-text">
                <span class="cta-question">Still haven't updated your LinkedIn profile picture?</span>
                <span class="cta-action">Continue here</span>
            </div>
            <div class="cta-arrow">
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
    </a>
</div>



</section>

        </div>

    </div>



    <!-- Toast Notification -->

    <div id="toast" class="fixed bottom-4 right-4 bg-gray-800 text-white px-6 py-3 rounded-lg shadow-lg transform translate-y-full opacity-0 transition-all duration-300">

        Signature copied to clipboard!

    </div>



<script>

function switchTab(tabName) {

    document.querySelectorAll('.tab-button').forEach(btn => {

        btn.classList.remove('active');

        if(btn.getAttribute('data-tab') === tabName) {

            btn.classList.add('active');

        }

    });

    updatePreview(); // Add this line to update preview when switching tabs

}

</script>



    </div>



<script>

let currentStep = 1;

const totalSteps = 6; // Updated to match the total number of steps



// Show help modal

function showHelpModal() {

    document.getElementById('helpModal').style.display = 'block';

    document.body.style.overflow = 'hidden';

}



// Close help modal

function closeHelpModal() {

    document.getElementById('helpModal').style.display = 'none';

    document.body.style.overflow = '';

}



// Navigate between steps

function navigateStep(direction) {

    const newStep = currentStep + direction;

    if (newStep >= 1 && newStep <= totalSteps) {

        goToStep(newStep);

    }

}



// Go to specific step

function goToStep(step) {

    // Hide current step

    document.querySelector(`.help-step[data-step="${currentStep}"]`).classList.remove('active');

    document.querySelector(`.step-indicator:nth-child(${currentStep})`).classList.remove('active');

    

    // Show new step

    currentStep = step;

    document.querySelector(`.help-step[data-step="${currentStep}"]`).classList.add('active');

    document.querySelector(`.step-indicator:nth-child(${currentStep})`).classList.add('active');

    

    // Update navigation buttons

    document.querySelector('.prev-button').disabled = currentStep === 1;

    document.querySelector('.next-button').disabled = currentStep === totalSteps;

    

    if (currentStep === totalSteps) {

        document.querySelector('.next-button').innerHTML = `

            Finish

            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>

            </svg>

        `;

    } else {

        document.querySelector('.next-button').innerHTML = `

            Next

            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>

            </svg>

        `;

    }

}



// Close modal when clicking outside

document.getElementById('helpModal').addEventListener('click', (e) => {

    if (e.target.classList.contains('help-modal-overlay')) {

        closeHelpModal();

    }

});



// Handle keyboard navigation

document.addEventListener('keydown', (e) => {

    if (document.getElementById('helpModal').style.display === 'block') {

        if (e.key === 'Escape') {

            closeHelpModal();

        } else if (e.key === 'ArrowRight' && currentStep < totalSteps) {

            navigateStep(1);

        } else if (e.key === 'ArrowLeft' && currentStep > 1) {

            navigateStep(-1);

        }

    }

});



// Initialize help icon click handler

document.querySelector('.help-icon').onclick = showHelpModal;

</script>



<script>

        // Toggle Profile Menu

        function toggleProfileMenu() {

            const menu = document.getElementById('profileMenu');

            menu.classList.toggle('active');

        }



        // Close profile menu when clicking outside

        document.addEventListener('click', (e) => {

            const menu = document.getElementById('profileMenu');

            const button = document.querySelector('.profile-button');

            if (!menu.contains(e.target) && !button.contains(e.target)) {

                menu.classList.remove('active');

            }

        });



        // Form Handling

        const form = document.getElementById('signatureForm');

        const preview = document.getElementById('signature-preview');

        const copyButton = document.getElementById('copyButton');

        const toast = document.getElementById('toast');



        // Add this - Form submit handler

        form.addEventListener('submit', (e) => {

            e.preventDefault();

            updatePreview();

        });



        // Update preview and enable/disable copy button

        function updatePreview() {

            const formData = new FormData(form);

            const name = formData.get('name');

            const title = formData.get('title');

            const phone = formData.get('phone');

            const email = formData.get('email');

            const linkedin = formData.get('linkedin');



            if (name && title && phone && email) {

                preview.innerHTML = generateSignature(name, title, phone, email, linkedin);

                copyButton.classList.remove('opacity-50', 'cursor-not-allowed');

                copyButton.disabled = false;

            } else {

                preview.innerHTML = `

                    <div class="text-gray-400 text-center py-10">

                        Fill out the required fields to generate your signature

                    </div>

                `;

                copyButton.classList.add('opacity-50', 'cursor-not-allowed');

                copyButton.disabled = true;

            }

        }



        // Format phone number

        function formatPhone(phone) {

            if (!phone) return '';

            return phone.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');

        }



        // Copy signature to clipboard

        // Copy signature to clipboard

        copyButton.addEventListener('click', () => {

            const signatureHTML = preview.innerHTML;

            if (signatureHTML) {

                copySignature(document.querySelector('.tab-button.active').getAttribute('data-tab'));

            }

        });



        // Show toast notification

        function showToast() {

            toast.classList.remove('translate-y-full', 'opacity-0');

            setTimeout(() => {

                toast.classList.add('translate-y-full', 'opacity-0');

            }, 3000);

        }



        // Tab switching

        const tabButtons = document.querySelectorAll('.tab-button');

        tabButtons.forEach(button => {

            button.addEventListener('click', () => {

                tabButtons.forEach(btn => btn.classList.remove('active'));

                button.classList.add('active');

                updatePreview();

            });

        });

    </script>



    <script>

        // Generate signature HTML based on form data

        function generateSignature(name, title, phone, email, linkedin) {

const activeTab = document.querySelector('.tab-button.active').getAttribute('data-tab');

const formattedPhone = formatPhone(phone);



if (activeTab === 'outlook') {

    return `

    <div style="font-family: 'Montserrat', Arial, sans-serif; font-size: 12px; color: #000000; line-height: 1.2; max-width: 600px; margin: 0; padding: 0; border: none;">

    <!-- Main Table -->

    <table cellpadding="0" cellspacing="0" style="width: 330px; border-collapse: collapse; margin: 0; padding: 0;">

        <!-- Content Section -->

                

        <tr>

            <td style="padding: 0; border: none;">

                <!-- Logo and Contact Info Table -->

                <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">

                

                    <tr>

                        <!-- Logo Column -->

                        <td width="80" valign="top" style="padding-right: 20px; border: none;">

                            <a  target="_blank" style="border: none; text-decoration: none;">
                                <img src="images/VDart_Digital_Blue_Logo.png" alt="VDart" width="90" style="display: block; max-width: 110px; height: auto; border: none;">
                            </a>

                        </td>

                        

                        <!-- Contact Info Column -->

                        <td valign="top " style="border: none;">

                            <div style="color: #242299; font-size: 13.33px; font-weight: bold; margin-bottom: 3px; font-family: 'Montserrat', Arial, sans-serif;">${name}</div>

                            <div style="color: #000000; font-size: 10.67px; margin-bottom: 6px; font-family: 'Montserrat', Arial, sans-serif;">${title}</div>

                            <div style="font-size: 10.67px; margin-bottom: 3px; font-family: 'Montserrat', Arial, sans-serif;"><span style="font-weight: bold; border: none; font-family: 'Montserrat', Arial, sans-serif;">P:</span> ${formattedPhone}</div>

                            <div style="font-size: 10.67px; margin-bottom: 6px; font-family: 'Montserrat', Arial, sans-serif;"><span style="font-weight: bold; border: none; font-family: 'Montserrat', Arial, sans-serif;">E:</span> <a href="mailto:${email}" style="color: #0066cc; text-decoration: none; border: none;">${email}</a></div>

                            <div style="margin-bottom: 6px; border: none;">

                                ${linkedin ? `<a href="${linkedin}" style="display: inline-block; text-decoration: none; border: none;">

                                        <img src="images/LinkedIn Logo (1).png" alt="LinkedIn" width="15" height="15">

                                </a>` : ''}

                            </div>

                            <div>

                                <a href="https://www.surveymonkey.com/r/Vsupport" style="color:rgb(12, 12, 12); text-decoration: none; font-size: 10.67px; font-weight: bold; font-style: italic; border: none; font-family: 'Montserrat', Arial, sans-serif;">Need help? Click for assistance</a>

                            </div>

                        </td>

                    </tr>

                </table>

            </td>

        </tr>



        <!-- Separator and Banner Section -->

        <tr>

            <td style="padding: 2px 0; border: none;">  

                <table cellpadding="0" cellspacing="0" style="width: 330px; border-collapse: collapse; margin: 0;">

                    <tr>

                        <td style="border: none;">

                            <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">

                                <tr>

                                    <td style="padding: 0; margin: 0; height: 8px; line-height: 1px; border: none;">

                                        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">

                                            <tr>

                                                <td bgcolor="#242299" style="height: 1px; line-height: 1px; padding: 0; margin: 0; font-size: 1px; border: none;">

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr>

                    <tr>

                        <td style="padding: 2px 0 0 0; border: none;"> 

                            <img src="images/VDart_Digital_Email_Banner.png" alt="Largest staffing Firms in the US" width="330" style="display: block; border: none;">

                        </td>

                    </tr>

                </table>

            </td>

        </tr>



        <!-- Disclaimer Section -->

        <tr>

            <td style="padding-top: 8px; border: none;">

                <p style="margin: 0; font-size: 8px; color: #A9A9A9; line-height: 1.3; border: none; font-family: 'Montserrat', Arial, sans-serif;">

                    The content of this email is confidential and intended for the recipient specified in the message only. It is strictly forbidden to share any part of this message with any third party, without a written consent of the sender. If you received this message by mistake, please reply to this message and follow with its deletion, so that we can ensure such a mistake does not occur in the future.

                </p>

            </td>

        </tr>

        <tr>
            <td style="padding-bottom: 8px; border: none; text-align: center; border-collapse: collapse;">
                <br>
                <a href="https://www.surveymonkey.com/r/Opout" style="color: #0066cc; text-decoration: none; font-size: 10px; border: none; border-collapse: collapse;">
                    Unsubscribe
                </a> 
                | 
                <a href="https://www.vdart.com/what-we-do/media/vdart-celebrates-five-consecutive-wins-receives-national-supplier-of-the-year-class-iv-award-from-nmsdc" style="color: #0066cc; text-decoration: none; font-size: 10px; border: none; border-collapse: collapse;">
                    Want to know more About Us?
                </a>
            </td>
        </tr>
        
    </table>

</div>`;

} else {

    return `

    <div style="font-family: 'Montserrat', Arial, sans-serif !important; font-size: 12px !important; color: #000000 !important; line-height: 1.2 !important; max-width: 600px !important; margin: 0 !important; padding: 0 !important;">

        <table cellpadding="0" cellspacing="0" border="0" style="width: 330px !important; border-collapse: collapse !important;">

            <tr>

                <td style="padding: 0 !important; border: none !important;">

                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100% !important; border-collapse: collapse !important;">

                        <tr>

                            <td width="80" valign="top" style="padding-right: 20px; border: none !important;">

                                    <a  target="_blank" style="border: none; text-decoration: none;">
                                        <img src="images/VDart_Digital_Blue_Logo.png" alt="VDart" width="90" style="display: block; max-width: 110px; height: auto; border: none;">
                                    </a>

                            </td>

                            <td valign="top" style="border: none !important;">

                                <div class="name" style="color: #242299 !important; font-size: 13.33px !important; font-weight: bold !important; margin-bottom: 3px !important; font-family: 'Montserrat', Arial, sans-serif !important;">${name}</div>

                                <div class="title" style="color: #000000 !important; font-size: 10.67px !important; margin-bottom: 6px !important; font-family: 'Montserrat', Arial, sans-serif !important;">${title}</div>

                                <div class="phone" style="font-size: 10.67px !important; margin-bottom: 3px !important; font-family: 'Montserrat', Arial, sans-serif !important;"><span style="font-weight: bold; font-family: 'Montserrat', Arial, sans-serif !important;">P:</span> ${formattedPhone}</div>

                                <div style="font-size: 10.67px !important; margin-bottom: 6px !important; font-family: 'Montserrat', Arial, sans-serif !important;"><span style="font-weight: bold; font-family: 'Montserrat', Arial, sans-serif !important;">E:</span> <a class="email" href="mailto:${email}" style="color: #0066cc !important; text-decoration: none !important;">${email}</a></div>

                                <div style="margin-bottom: 6px !important;">

                                    ${linkedin ? `<a class="linkedin" href="${linkedin}" style="display: inline-block !important; text-decoration: none !important;"><img src="images/LinkedIn Logo (1).png" alt="LinkedIn" width="18" height="15"></a>` : ''}

                                </div>

                                <div>

                                    <a href="https://www.surveymonkey.com/r/Vsupport" style="color:rgb(11, 11, 11) !important; text-decoration: none !important; font-size: 10px !important; font-weight: bold !important; font-style: italic !important; font-family: 'Montserrat', Arial, sans-serif !important;">Need help? Click for assistance</a>

                                </div>

                            </td>

                        </tr>

                    </table>

                </td>

            </tr>

            <!-- Separator and Banner Section -->

        <tr>

            <td style="padding: 2px 0; border: none;">  

                <table cellpadding="0" cellspacing="0" style="width: 330px; border-collapse: collapse; margin: 0;">

                    <tr>

                        <td style="border: none;">

                            <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">

                                <tr>

                                    <td style="padding: 0; margin: 0; height: 8px; line-height: 1px; border: none;">

                                        <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">

                                            <tr>

                                                <td bgcolor="#0066cc" style="height: 1px; line-height: 1px; padding: 0; margin: 0; font-size: 1px; border: none;">

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

                            </table>

                        </td>

                    </tr>

                    <tr>

                        <td style="padding: 2px 0 0 0; border: none;"> 

                            <img src="images/VDart_Digital_Email_Banner.png" alt="Largest staffing Firms in the US" width="330" style="display: block; border: none;">

                        </td>

                    </tr>

                </table>

            </td>

        </tr>

            <!-- New row for Unsubscribe and About Us links -->
            <tr>
                <td style="padding-top: 8px; border: none !important; text-align: center;">
                    <a href="https://www.surveymonkey.com/r/Opout" style="color: #0066cc !important; text-decoration: none !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;">
                        Unsubscribe
                    </a>
                    <span style="color: #666666 !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;"> | </span>
                    <a href="https://www.vdart.com/what-we-do/media/vdart-celebrates-five-consecutive-wins-receives-national-supplier-of-the-year-class-iv-award-from-nmsdc" style="color: #0066cc !important; text-decoration: none !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;">
                        Want to know more About Us?
                    </a>
                </td>
            </tr>

        </table>

    </div>`;

}

}



function copySignature(target) {
if (target === 'outlook') {
    const preview = document.getElementById('signature-preview');
    const range = document.createRange();
    range.selectNodeContents(preview);
    window.getSelection().removeAllRanges();
    window.getSelection().addRange(range);
    document.execCommand('copy');
    window.getSelection().removeAllRanges();
    showToast();
} else {
    const formData = new FormData(form);
    const name = formData.get('name');
    const title = formData.get('title');
    const phone = formatPhone(formData.get('phone'));
    const email = formData.get('email');
    const linkedin = formData.get('linkedin');

    const ceipalHTML = `
    <div style="font-family: 'Montserrat', Arial, sans-serif !important; font-size: 12px !important; color: #000000 !important; line-height: 1.2 !important; max-width: 350px; width: 100%; margin: 0 !important; padding: 0 !important;">
        <table cellpadding="0" cellspacing="0" border="0" style="max-width: 350px; width: 100%; border-collapse: collapse !important; table-layout: fixed; border: none !important; margin: 0 !important; padding: 0 !important;">
            <tr>
                <td style="padding: 0 !important; border: none !important;">
                    <table cellpadding="0" cellspacing="0" border="0" style="width: 100% !important; border-collapse: collapse !important;">
                        <tr>
                            <td width="80" valign="top" style="padding-right: 20px; border: none !important;">
                                <a  target="_blank" style="border: none !important; text-decoration: none !important;">
                                    <img src="http://vdpl.co/dnimg/VDart_Digital_Blue_Logo.png" alt="Trust People" width="90" style="display: block !important; max-width: 110px !important; height: auto !important; border: none !important;">
                                </a>
                            </td>
                            <td valign="top" style="border: none !important;">
                                <div style="color: #242299 !important; font-size: 13.33px !important; font-weight: bold !important; margin-bottom: 3px !important; font-family: 'Montserrat', Arial, sans-serif !important;">${name}</div>
                                <div style="color: #000000 !important; font-size: 10.67px !important; margin-bottom: 6px !important; font-family: 'Montserrat', Arial, sans-serif !important;">${title}</div>
                                <div style="font-size: 10.67px !important; margin-bottom: 3px !important; font-family: 'Montserrat', Arial, sans-serif !important;"><span style="font-weight: bold !important; color: #000000 !important;">P:</span> ${phone}</div>
                                <div style="font-size: 10.67px !important; margin-bottom: 6px !important; font-family: 'Montserrat', Arial, sans-serif !important;"><span style="font-weight: bold !important; color: #000000 !important;">E:</span> <a href="mailto:${email}" style="color: #0066cc !important; text-decoration: none !important;">${email}</a></div>
                                ${linkedin ? `
                                <div style="margin-bottom: 6px !important;">
                                    <a href="${linkedin}" style="display: inline-block !important; text-decoration: none !important;">
                                        <img src="http://vdpl.co/dnimg/linkedinlogo.png" alt="LinkedIn" width="18" height="15" style="border: none !important;">
                                    </a>
                                </div>` : ''}
                                <div>
                                    <a href="https://www.surveymonkey.com/r/Vsupport" style="color: rgb(11, 11, 11) !important; text-decoration: none !important; font-size: 10.67px !important; font-weight: bold !important; font-style: italic !important; font-family: 'Montserrat', Arial, sans-serif !important;">Need help? Click for assistance</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="padding: 0px 0 !important; border: none !important;">
                    <table cellpadding="0" cellspacing="0" border="0" style="max-width: 100%; border-collapse: collapse !important;">
                        <tr>
                            <td>
                                <div style="background-color: #242299; height: 1px; line-height: 1px; font-size: 0; width: 100%;">&nbsp;</div>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0 0 0 !important; border: none !important;">
                                <img src="http://vdpl.co/dnimg/VDart_Digital_Email_Banner.png" alt="Largest staffing Firms in the US" width="100%" style="display: block !important; border: none !important; max-width: 330px; height: auto;">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

                <!-- New row for Unsubscribe and About Us links -->
                <tr>
                    <td style="padding-top: 8px; border: none !important; text-align: center;">
                        <a href="https://www.surveymonkey.com/r/Opout" style="color: #0066cc !important; text-decoration: none !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;">
                            Unsubscribe
                        </a>
                        <span style="color: #666666 !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;"> | </span>
                        <a href="https://www.vdart.com/what-we-do/media/vdart-celebrates-five-consecutive-wins-receives-national-supplier-of-the-year-class-iv-award-from-nmsdc" style="color: #0066cc !important; text-decoration: none !important; font-size: 10px !important; font-family: 'Montserrat', Arial, sans-serif !important;">
                            Want to know more About Us?
                        </a>
                    </td>
                </tr>

        </table>
    </div>`;

    // Copy the HTML using Clipboard API
    navigator.clipboard.writeText(ceipalHTML).then(() => {
        alert('HTML code copied successfully for CEIPAL!');
    }).catch(err => {
        alert('Failed to copy HTML code: ' + err);
    });
}
}


    </script>



<script>

let currentStep = 1;

const totalSteps = 5;



// Show help modal

function showHelpModal() {

    document.getElementById('helpModal').style.display = 'block';

    document.body.style.overflow = 'hidden';

}



// Close help modal

function closeHelpModal() {

    document.getElementById('helpModal').style.display = 'none';

    document.body.style.overflow = '';

}



// Navigate between steps

function navigateStep(direction) {

    const newStep = currentStep + direction;

    if (newStep >= 1 && newStep <= totalSteps) {

        goToStep(newStep);

    }

}



// Go to specific step

function goToStep(step) {

    // Hide current step

    document.querySelector(`.help-step[data-step="${currentStep}"]`).classList.remove('active');

    document.querySelector(`.step-indicator:nth-child(${currentStep})`).classList.remove('active');

    

    // Show new step

    currentStep = step;

    document.querySelector(`.help-step[data-step="${currentStep}"]`).classList.add('active');

    document.querySelector(`.step-indicator:nth-child(${currentStep})`).classList.add('active');

    

    // Update navigation buttons

    document.querySelector('.prev-button').disabled = currentStep === 1;

    document.querySelector('.next-button').disabled = currentStep === totalSteps;

    

    if (currentStep === totalSteps) {

        document.querySelector('.next-button').innerHTML = `

            Finish

            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>

            </svg>

        `;

    } else {

        document.querySelector('.next-button').innerHTML = `

            Next

            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>

            </svg>

        `;

    }

}



// Close modal when clicking outside

document.getElementById('helpModal').addEventListener('click', (e) => {

    if (e.target.classList.contains('help-modal-overlay')) {

        closeHelpModal();

    }

});



// Handle keyboard navigation

document.addEventListener('keydown', (e) => {

    if (document.getElementById('helpModal').style.display === 'block') {

        if (e.key === 'Escape') {

            closeHelpModal();

        } else if (e.key === 'ArrowRight' && currentStep < totalSteps) {

            navigateStep(1);

        } else if (e.key === 'ArrowLeft' && currentStep > 1) {

            navigateStep(-1);

        }

    }

});



// Initialize help icon click handler

document.querySelector('.help-icon').onclick = showHelpModal;

</script>



<script>

document.addEventListener('DOMContentLoaded', function() {

    let currentTab = 'outlook';

    const tabs = document.querySelectorAll('.modal-tab');

    const stepsContainers = document.querySelectorAll('.steps-container');

    

    // Get total steps for each tab dynamically

    function getMaxSteps(tabName) {

        const container = document.getElementById(`${tabName}Steps`);

        return container.querySelectorAll('.help-step').length;

    }

    

    function updateStepIndicators() {

        const indicators = document.querySelector('.step-indicators');

        const totalSteps = getMaxSteps(currentTab);

        

        indicators.innerHTML = '';

        for(let i = 1; i <= totalSteps; i++) {

            const indicator = document.createElement('div');

            indicator.className = `step-indicator${i === 1 ? ' active' : ''}`;

            indicator.onclick = () => goToStep(i);

            indicators.appendChild(indicator);

        }

    }

    

    function switchTab(tabName) {

        currentTab = tabName;

        

        // Update tab buttons

        tabs.forEach(tab => {

            tab.classList.toggle('active', tab.dataset.tab === tabName);

        });

        

        // Update step containers

        stepsContainers.forEach(container => {

            container.classList.toggle('active', container.id === `${tabName}Steps`);

        });

        

        // Reset to first step and update indicators

        currentStep = 1;

        goToStep(1);

        updateStepIndicators();

    }

    

    // Add click handlers to tabs

    tabs.forEach(tab => {

        tab.addEventListener('click', () => switchTab(tab.dataset.tab));

    });

    

    // Modified step navigation

    window.navigateStep = function(direction) {

        const currentStepEl = document.querySelector(`#${currentTab}Steps .help-step.active`);

        const currentStep = parseInt(currentStepEl.dataset.step);

        const maxSteps = getMaxSteps(currentTab);

        const nextStep = currentStep + direction;

        

        if (nextStep >= 1 && nextStep <= maxSteps) {

            goToStep(nextStep);

        }

    };

    

    window.goToStep = function(step) {

        const container = document.getElementById(`${currentTab}Steps`);

        const steps = container.querySelectorAll('.help-step');

        const indicators = document.querySelectorAll('.step-indicator');

        const maxSteps = getMaxSteps(currentTab);

        

        steps.forEach(s => s.classList.remove('active'));

        indicators.forEach(i => i.classList.remove('active'));

        

        const targetStep = container.querySelector(`[data-step="${step}"]`);

        if (targetStep) {

            targetStep.classList.add('active');

            if (indicators[step - 1]) {

                indicators[step - 1].classList.add('active');

            }

        }

        

        // Update navigation buttons

        const prevButton = document.querySelector('.prev-button');

        const nextButton = document.querySelector('.next-button');

        

        if (prevButton) prevButton.disabled = step === 1;

        if (nextButton) {

            nextButton.disabled = step === maxSteps;

            

            nextButton.innerHTML = step === maxSteps ? `

                Finish

                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>

                </svg>

            ` : `

                Next

                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>

                </svg>

            `;

        }

    };

    

    // Initialize

    updateStepIndicators();

});

</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        function capitalizeWords(input) {
            return input
                .toLowerCase()
                .replace(/\b\w/g, (char) => char.toUpperCase());
        }

        function autoCapitalize(event) {
            event.target.value = capitalizeWords(event.target.value);
        }

        // Apply event listeners to both fields
        document.getElementById("name").addEventListener("input", autoCapitalize);
        document.getElementById("title").addEventListener("input", autoCapitalize);
    });
</script>


<script>

    function logout() {

    // Show confirmation dialog

    if (confirm("Are you sure you want to logout?")) {

        try {

            // Clear local storage

            localStorage.clear();

            

            // Clear session storage

            sessionStorage.clear();

            

            // Clear cookies

            document.cookie.split(";").forEach(function(c) { 

                document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/"); 

            });



            console.log('Logout successful');

            

            // Redirect to logout.php

            window.location.href = 'logout.php';

            

        } catch (error) {

            console.error('Logout failed:', error);

            alert('Logout failed. Please try again.');

        }

    }

}



// Add click event listener to logout button

document.addEventListener('DOMContentLoaded', function() {

    const logoutButton = document.querySelector('.logout-btn');

    if (logoutButton) {

        logoutButton.addEventListener('click', logout);

    }

});

</script>


</body>

</html>

