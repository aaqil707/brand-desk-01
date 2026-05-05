<?php

require 'Pages/db.php'; // Include your database connection

session_start(); // Ensure the session is started

$currentUserEmail = $_SESSION['email'];



if (!isset($_SESSION['email'])) {

    header("Location: Pages/loginpage.php"); // Redirect if not logged in

    exit();

}

?>



<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Entity Selector Homepage</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>

        :root {

            --primary-color: #004d40;

            --secondary-color: #00695c;

            --gradient-start: #e0f7fa;

            --gradient-end: #e3f2fd;

            --card-shadow: rgba(0, 77, 64, 0.15);

        }



        * {

            margin: 0;

            padding: 0;

            box-sizing: border-box;

        }



        body {

            font-family: 'Poppins', sans-serif;

            background: linear-gradient(180deg, #f7f7f7, #e6e6e6);

            min-height: 100vh;

            display: flex;

            flex-direction: column;

            color: #1a1a1a;

        }



        .header {

    text-align: center;

    padding: 100px 20px;

    background: linear-gradient(rgba(36, 34, 153, 0.95), rgba(36, 34, 153, 0.95)), 

                url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&q=80');

    background-size: cover;

    background-position: center;

    color: white;

    position: relative;

    overflow: hidden;



    /* Add fade effect only to the bottom */

    mask-image: linear-gradient(to bottom, black, black 100%, transparent);

    -webkit-mask-image: linear-gradient(to bottom, black, black 30%, transparent);

}





        



        .header h1 {

            font-size: 3.5rem;

            margin-bottom: 1rem;

            font-weight: 700;

            position: relative;

            z-index: 1;

            animation: fadeInUp 1s ease-out;

        }



        .header p {

            font-size: 1.4rem;

            font-weight: 300;

            opacity: 0.9;

            position: relative;

            z-index: 1;

            animation: fadeInUp 1s ease-out 0.2s both;

        }



        #mainContent {

            flex: 1 0 auto;

            display: flex;

            flex-direction: column;

            min-height: 100vh;

        }



        .container {

            display: grid;

            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));

            gap: 3rem;

            padding: 6rem 3rem;

            max-width: 1400px;

            margin: 0 auto;

            z-index: 1;

            flex: 1 0 auto;

        }



        /* Updated Card Styles */

.card {

    background: rgba(255, 255, 255, 0.95);

    backdrop-filter: blur(10px);

    border-radius: 24px;

    padding: 3rem 2rem;

    display: flex;

    flex-direction: column;

    align-items: center;

    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);

    position: relative;

    overflow: hidden;

    max-height: 500px;

    border: 1px solid rgba(255, 255, 255, 0.2);

    box-shadow: 

        0 10px 20px rgba(0, 0, 0, 0.05),

        0 6px 6px rgba(0, 0, 0, 0.1),

        0 0 100px rgba(0, 77, 64, 0.1) inset;

}



/* Gradient Border Effect */

.card::before {

    content: '';

    position: absolute;

    inset: 0;

    border-radius: 24px;

    padding: 2px;

    background: linear-gradient(

        225deg,

        var(--primary-color),

        var(--secondary-color),

        transparent,

        transparent

    );

    -webkit-mask: 

        linear-gradient(#fff 0 0) content-box, 

        linear-gradient(#fff 0 0);

    -webkit-mask-composite: xor;

    mask-composite: exclude;

    opacity: 0;

    transition: opacity 0.5s ease;

}



.card:hover::before {

    opacity: 1;

}



/* Card Hover Effects */

.card:hover {

    transform: translateY(-15px) scale(1.02);

    box-shadow: 

        0 20px 40px rgba(0, 0, 0, 0.12),

        0 12px 12px rgba(0, 0, 0, 0.08),

        0 0 120px rgba(0, 77, 64, 0.15) inset;

}



/* Logo Styles */

.card img {

    width: 180px;

    height: auto;

    object-fit: contain;

    padding: 15px;

    margin-bottom: 2rem;

    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);

    filter: drop-shadow(0 4px 6px rgba(0, 0, 0, 0.1));

}



.card:hover img {

    transform: scale(1.08) translateY(-5px);

    filter: drop-shadow(0 8px 12px rgba(0, 0, 0, 0.2));

}



/* Text Content */

.card p {

    text-align: center;

    line-height: 1.8;

    color: #4a5568;

    margin: 1.5rem 0;

    font-size: 1rem;

    padding: 0 1.5rem;

    transition: transform 0.3s ease;

}



.card strong {

    color: var(--primary-color);

    font-size: 1.4rem;

    font-weight: 600;

    display: block;

    margin-bottom: 1rem;

    text-transform: uppercase;

    letter-spacing: 0.5px;

    background: linear-gradient(120deg, var(--primary-color), var(--secondary-color));

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;

    opacity: 0.9;

    transition: opacity 0.3s ease;

}



.card:hover strong {

    opacity: 1;

}



/* Link Styles */

.entity-link {

    color: var(--primary-color);

    text-decoration: none;

    font-size: 1.2rem;

    font-weight: 500;

    padding: 1rem 2.5rem;

    border-radius: 50px;

    background: linear-gradient(to right, var(--primary-color), var(--secondary-color));

    color: white;

    transition: all 0.3s ease;

    margin-top: 1rem;

    position: relative;

    overflow: hidden;

    cursor: pointer;

    position: relative;

    z-index: 1;

}



.entity-link::before {

    content: '';

    position: absolute;

    top: 0;

    left: 0;

    width: 100%;

    height: 100%;

    background: linear-gradient(120deg,

        transparent 0%,

        rgba(255, 255, 255, 0.3) 50%,

        transparent 100%

    );

    transform: translateX(-100%);

    transition: transform 0.6s ease;

}



.entity-link:hover::before {

    transform: translateX(100%);

}



.entity-link:hover {

    transform: translateY(-3px);

    box-shadow: 0 6px 15px rgba(0, 77, 64, 0.25);

}



/* Container adjustments for cards */

.container {

    display: grid;

    grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));

    gap: 3rem;

    padding: 4rem 3rem;

    max-width: 1400px;

    margin: 0 auto;

    z-index: 1;

}



/* Card Background Accent */

.card::after {

    content: '';

    position: absolute;

    top: 0;

    left: 0;

    right: 0;

    height: 100%;

    background: linear-gradient(

        180deg,

        rgba(255, 255, 255, 0) 0%,

        rgba(var(--primary-color), 0.03) 100%

    );

    opacity: 0;

    transition: opacity 0.5s ease;

}



.card:hover::after {

    opacity: 1;

}



/* Responsive adjustments */

@media (max-width: 768px) {

    .card {

        padding: 2rem 1.5rem;

    }



    .card img {

        width: 150px;

    }



    .card strong {

        font-size: 1.2rem;

    }



    .entity-link {

        padding: 0.8rem 2rem;

        font-size: 1.1rem;

    }

}



/* Animation for cards entrance */

.card {

    animation: cardEntrance 0.8s cubic-bezier(0.21, 1.03, 0.27, 1) backwards;

}



.card:nth-child(1) { animation-delay: 0.1s; }

.card:nth-child(2) { animation-delay: 0.2s; }

.card:nth-child(3) { animation-delay: 0.3s; }



@keyframes cardEntrance {

    from {

        opacity: 0;

        transform: translateY(60px) scale(0.9);

    }

    to {

        opacity: 1;

        transform: translateY(0) scale(1);

    }

}



        /* Modal Styles */

        .modal-overlay {

    position: fixed;

    top: 0;

    left: 0;

    right: 0;

    bottom: 0;

    background: rgba(0, 0, 0, 0.75);

    backdrop-filter: blur(8px);

    z-index: 9999;

    opacity: 0;

    visibility: hidden;

    transition: opacity 0.3s ease, visibility 0.3s ease;

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 10px;

}



.modal-overlay.active {

    display: flex !important; /* Force display when active */

    align-items: center;

    justify-content: center;

    opacity: 1;

    visibility: visible;

}



.modal-overlay.active .modal {

    transform: translateY(0);

    opacity: 1;

}



.modal {

    background: linear-gradient(145deg, #ffffff, #f5f5f5);

    border-radius: 20px;

    padding: 1.5rem;

    max-width: 900px;

    width: 95%;

    position: relative;

    transform: translateY(20px);

    opacity: 0;

    transition: transform 0.3s ease, opacity 0.3s ease;

    max-height: 80vh;

    display: flex;

    flex-direction: column;

    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);

}



.modal-header {

    padding: 0.5rem 1rem;

    position: sticky;

    top: 0;

    background: linear-gradient(145deg, #ffffff, #f5f5f5);

    border-radius: 20px 20px 0 0;

    z-index: 2;

}



.modal-title {

    color: var(--primary-color);

    font-size: 1.8rem;

    font-weight: 700;

    margin: 0 0 0.5rem;

    background: linear-gradient(135deg, #242297, #3A7BD5);

    -webkit-background-clip: text;

    -webkit-text-fill-color: transparent;

}



.modal-title::after {

    content: '';

    display: block;

    width: 370px;

    height: 3px;

    background: linear-gradient(135deg, #242297, #3A7BD5);

    margin: 0.5rem 0;

    border-radius: 2px;

}



.modal-content {

    flex: 1;

    overflow-y: auto;

    padding: 0.5rem 1.5rem;

    margin: 0.5rem 0;

    

   

    &::-webkit-scrollbar {

        width: 6px;

        height: 6px;

    }

    

    &::-webkit-scrollbar-track {

        background: rgba(0, 77, 64, 0.05);

        border-radius: 3px;

    }

    

    &::-webkit-scrollbar-thumb {

        background: linear-gradient(135deg, #242297, #3A7BD5);

        border-radius: 3px;

    }

    

    &::-webkit-scrollbar-thumb:hover {

        background: var(--secondary-color);

    }

    

    /* Firefox Scrollbar */

    scrollbar-width: thin;

    scrollbar-color: linear-gradient(135deg, #242297, #3A7BD5);

}



.modal-content h3 {

    color: black;

    font-size: 1.2rem;

    margin: 1.2rem 0 0.6rem;

    display: flex;

    align-items: center;

    gap: 0.5rem;

}



.modal-content p {

    line-height: 1.6;

    color: #4a5568;

    margin-bottom: 1rem;

    font-size: 1rem;

    padding: 0.5rem 0.8rem 0.5rem 1.2rem;

    border-left: 3px solid rgba(0, 77, 64, 0.1);

    transition: all 0.3s ease;

}



.modal-content p:hover {

    border-left-color: var(--primary-color);

    background: linear-gradient(135deg, #242297, #3A7BD5);

    border-radius: 0 8px 8px 0;

}



.modal-footer {

    padding: 1rem;

    border-top: 2px solid rgba(0, 77, 64, 0.1);

    background: linear-gradient(145deg, #ffffff, #f5f5f5);

    border-radius: 0 0 20px 20px;

    position: sticky;

    bottom: 0;

    z-index: 2;

}



.accept-button {

    background: linear-gradient(135deg, #242297, #3A7BD5);

    color: white;

    border: none;

    padding: 0.8rem 2rem;

    border-radius: 50px;

    font-size: 1.1rem;

    font-weight: 500;

    cursor: pointer;

    min-width: 200px;

    display: inline-flex;

    align-items: center;

    justify-content: center;

    gap: 0.5rem;

    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);

}



.accept-button i {

    font-size: 1.1rem;

    transition: transform 0.3s ease;

}



.accept-button:hover {

    transform: translateY(-2px);

    box-shadow: 0 8px 25px rgba(0, 77, 64, 0.25);

}



/* Ensure entity links stay clickable */

.entity-link {

    position: relative;

    z-index: 1;

}



/* Only blur content when modal is active */

.blur-content {

    filter: blur(5px);

    pointer-events: none;

    transition: filter 0.3s ease;

}



/* Hide modals by default */

#termsModal, #typeModal {

    display: none;

}



/* Mobile Responsiveness */

@media (max-width: 768px) {

    .modal {

        width: 98%;

        max-height: 85vh;

        margin: 10px;

        padding: 1rem;

    }

    

    .modal-title {

        font-size: 1.5rem;

    }

    

    .accept-button {

        width: 100%;

        padding: 0.8rem 1.5rem;

        font-size: 1rem;

    }

}



        /* Enhanced Type Modal Styles */

.type-modal-overlay {

    position: fixed;

    top: 0;

    left: 0;

    right: 0;

    bottom: 0;

    background: rgba(0, 0, 0, 0.85);

    backdrop-filter: blur(12px);

    z-index: 1000;

    padding: 20px;

    opacity: 0;

    visibility: hidden;

    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);

    display: flex !important;

    align-items: center;

    justify-content: center;

}



.type-modal-overlay.active {

    opacity: 1;

    display: flex;

    align-items: center;

    justify-content: center;

    visibility: visible;

}



.type-modal {

    background: rgba(255, 255, 255, 0.98);

    border-radius: 24px;

    padding: 2.8rem;

    width: 95%;

    max-width: 460px;

    position: relative;

    transform: translateY(30px) scale(0.95);

    opacity: 0;

    transition: all 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);

    box-shadow: 

        0 20px 50px rgba(0, 0, 0, 0.2),

        0 10px 20px rgba(0, 0, 0, 0.1),

        inset 0 0 0 1px rgba(255, 255, 255, 0.1);

}



.type-modal-overlay.active .type-modal {

    transform: translateY(0) scale(1);

    opacity: 1;

}





.type-modal-header {

    margin-bottom: 2.5rem;

    text-align: center;

    position: relative;

}



.type-modal-header::after {

    content: '';

    position: absolute;

    bottom: -1rem;

    left: 50%;

    transform: translateX(-50%);

    width: 60px;

    height: 3px;

    background: linear-gradient(90deg, #242297, #3A7BD5);

    border-radius: 3px;

}



.type-modal-header h2 {

    background: linear-gradient(135deg, #242297, #3A7BD5);

    -webkit-background-clip: text;

    background-clip: text;

    color: transparent;

    font-size: 2rem;

    font-weight: 700;

    margin-bottom: 0.8rem;

    letter-spacing: -0.02em;

}



.type-selection-buttons {

    display: flex;

    flex-direction: column;

    gap: 1.2rem;

}



.type-modal::before {

    content: '';

    position: absolute;

    inset: -1px;

    border-radius: 24px;

    padding: 1px;

    background: linear-gradient(

        135deg,

        rgba(255, 255, 255, 0.4),

        rgba(255, 255, 255, 0.1) 25%,

        rgba(36, 34, 151, 0.4) 85%

    );

    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);

    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);

    -webkit-mask-composite: xor;

    mask-composite: exclude;

}



.type-button {

    background: linear-gradient(135deg, #242297, #00C9FF);

    color: white;

    border: none;

    padding: 1.4rem 2rem;

    border-radius: 16px;

    font-size: 1.15rem;

    font-weight: 600;

    cursor: pointer;

    width: 100%;

    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);

    display: flex;

    align-items: center;

    justify-content: center;

    gap: 1.2rem;

    position: relative;

    overflow: hidden;

    box-shadow: 

        0 4px 12px rgba(36, 34, 151, 0.2),

        inset 0 1px 1px rgba(255, 255, 255, 0.3);

}



.type-button i {

    font-size: 1.4rem;

    transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);

    background: rgba(255, 255, 255, 0.9);

    -webkit-background-clip: text;

    background-clip: text;

    -webkit-text-fill-color: transparent;

}



.type-button span {

    position: relative;

    z-index: 1;

    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);

}



.type-button::before {

    content: '';

    position: absolute;

    inset: 1px;

    background: linear-gradient(135deg, rgba(255, 255, 255, 0.1), transparent);

    border-radius: 15px;

    opacity: 0;

    transition: opacity 0.3s ease;

}



.type-button:hover::before {

    left: 100%;

}



.type-button:hover {

    transform: translateY(-2px) scale(1.02);

    box-shadow: 

        0 8px 25px rgba(36, 34, 151, 0.25),

        inset 0 1px 1px rgba(255, 255, 255, 0.4);

    background: linear-gradient(135deg, #2C2A99, #4A8BE5);

}



.type-button:hover::before {

    opacity: 1;

}



.type-button:hover i {

    transform: scale(1.15) rotate(-5deg);

}



.type-button:active {

    transform: translateY(1px) scale(0.98);

    box-shadow: 

        0 4px 15px rgba(36, 34, 151, 0.2),

        inset 0 1px 1px rgba(255, 255, 255, 0.2);

}



/* Close button */

.type-modal-close {

    position: absolute;

    top: 1.2rem;

    right: 1.2rem;

    width: 36px;

    height: 36px;

    border-radius: 50%;

    border: none;

    background: rgba(0, 0, 0, 0.06);

    color: #666;

    cursor: pointer;

    display: flex;

    align-items: center;

    justify-content: center;

    transition: all 0.3s ease;

}



.type-modal-close:hover {

    background: rgba(0, 0, 0, 0.1);

    color: #333;

    transform: rotate(90deg);

}



/* Responsive Adjustments */

@media (max-width: 480px) {

    .type-modal {

        padding: 2.2rem;

    }



    .type-modal-header h2 {

        font-size: 1.75rem;

    }



    .type-button {

        padding: 1.2rem 1.8rem;

        font-size: 1.1rem;

    }

}



/* Animations for modal entrance */

@keyframes modalFadeIn {

    from {

        opacity: 0;

        transform: translateY(40px) scale(0.9);

    }

    to {

        opacity: 1;

        transform: translateY(0) scale(1);

    }

}



/* Animation for button hover effect */

@keyframes buttonGlow {

    0% {

        box-shadow: 0 4px 12px rgba(36, 34, 151, 0.2);

    }

    50% {

        box-shadow: 0 8px 25px rgba(36, 34, 151, 0.3);

    }

    100% {

        box-shadow: 0 4px 12px rgba(36, 34, 151, 0.2);

    }

}



/* Optional: Add a subtle border effect */

.type-modal::after {

    content: '';

    position: absolute;

    inset: 0;

    border-radius: 20px;

    padding: 1px;

    background: linear-gradient(135deg, rgba(36, 34, 151, 0.5), rgba(58, 123, 213, 0.5));

    -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);

    mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);

    -webkit-mask-composite: xor;

    mask-composite: exclude;

    pointer-events: none;

}



@keyframes typeModalSlideIn {

    0% {

        opacity: 0;

        transform: translateY(-20px) scale(0.95);

    }

    100% {

        opacity: 1;

        transform: translateY(0) scale(1);

    }

}



@keyframes modalSlideIn {

    0% {

        opacity: 0;

        transform: translateY(-20px) scale(0.95);

    }

    100% {

        opacity: 1;

        transform: translateY(0) scale(1);

    }

}



/* Responsive Adjustments */

@media (max-width: 480px) {

    .modal, .type-modal {

        padding: 2rem;

        margin: 15px;

    }

    

    .modal-title {

        font-size: 1.6rem;

    }

    

    .type-button {

        padding: 1rem 1.5rem;

        font-size: 1rem;

    }

    

    .accept-button {

        padding: 1rem 2rem;

        font-size: 1rem;

    }

}



        .footer {

    background: linear-gradient(rgba(36, 34, 153, 0.95), rgba(36, 34, 153, 0.95));

    color: white;

    text-align: center;

    padding: 2rem;

    margin-top: auto;



    /* Add fade effect only to the top */

    /* mask-image: linear-gradient(to bottom, transparent, black 80%); */

    /* -webkit-mask-image: linear-gradient(to bottom, transparent, black 60%); */

}





        .blur-content {

            filter: blur(5px);

            transition: filter 0.3s ease;

            pointer-events: none;

        }



        /* Animations */

        @keyframes fadeInUp {

            from {

                opacity: 0;

                transform: translateY(20px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }

        }



        @keyframes headerPattern {

            from { background-position: 0 0; }

            to { background-position: 1000px 0; }

        }



        @keyframes slideIn {

            from {

                opacity: 0;

                transform: scale(0.95) translateY(-20px);

            }

            to {

                opacity: 1;

                transform: scale(1) translateY(0);

            }

        }



        @keyframes modalSlide {

            from {

                opacity: 0;

                transform: scale(0.95);

            }

            to {

                opacity: 1;

                transform: scale(1);

            }

        }



        /* Responsive Styles */

        @media (max-width: 768px) {

            .header {

                padding: 60px 20px;

            }



            .header h1 {

                font-size: 2.5rem;

            }



            .header p {

                font-size: 1.2rem;

            }



            .container {

                padding: 3rem 1.5rem;

                gap: 2rem;

            }



            .card {

                padding: 2rem;

            }



            .card img {

                width: 140px;

            }

        }


        /* Help Icon and Tooltip Styles */
        .help-container {
    position: fixed;
    bottom: 2rem;
    left: 2rem;
    z-index: 1000;
}

.help-icon {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #242297, #3A7BD5);
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    box-shadow: 0 4px 15px rgba(36, 34, 151, 0.3);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.help-icon:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(36, 34, 151, 0.4);
}

.help-icon i {
    font-size: 18px;
}

.help-text {
    font-weight: 500;
    font-family: 'Poppins', sans-serif;
}

.help-tooltip {
    position: absolute;
    bottom: 120%;
    left: 0;
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    width: 320px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}

.help-container:hover .help-tooltip {
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

.help-category {
    margin-bottom: 1.2rem;
    padding-bottom: 1rem;
    border-bottom: 1px dashed rgba(36, 34, 151, 0.1);
}

.help-category:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.help-category h4 {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #242297;
    margin: 0 0 8px 0;
    font-size: 1rem;
    font-weight: 600;
}

.help-category p {
    color: #666;
    margin: 0 0 12px 0;
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

.contact-item:last-child {
    margin-bottom: 0;
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

.help-icon {
    animation: pulse 2s infinite;
}
    </style>

</head>

<body>


<!-- Help Icon with Tooltip -->
<div class="help-container">
    <button class="help-icon" aria-label="Help">
        <i class="fas fa-question-circle"></i>
        <span class="help-text">Need Help</span>
    </button>
    <div class="help-tooltip">
        <div class="tooltip-header">
            <i class="fas fa-headset"></i>
            <h3>Need Help?</h3>
        </div>
        <div class="tooltip-content">
            <div class="help-category">
                <h4><i class="fas fa-tools"></i> Technical Support</h4>
                <p>For application issues, bugs, or technical difficulties:</p>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:saranraj.s@vdartinc.com">saranraj.s@vdartinc.com</a>
                </div>
            </div>
            
            <div class="help-category">
                <h4><i class="fas fa-info-circle"></i> General Inquiries</h4>
                <p>For profile information, account questions, or general assistance:</p>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:lyn.g@vdartinc.com">lyn.g@vdartinc.com</a>
                </div>
                <div class="contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:rukzana.r@vdartinc.com">rukzana.r@vdartinc.com</a>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- Type Selection Modal -->

<div id="typeModal" class="type-modal-overlay">

    <div class="type-modal">

        <div class="type-modal-header">

            <h2 class="modal-title">Choose Your Option</h2><br>

        </div>

        <div class="type-selection-buttons">

            <button class="type-button" onclick="handleTypeSelection('linkedin')">

                <i class="fab fa-linkedin"></i>

                <span>LinkedIn</span>

            </button>

            <button class="type-button" onclick="handleTypeSelection('email')">

                <i class="far fa-envelope"></i>

                <span>Signature Generator</span>

            </button>

        </div>

    </div>

</div>



    <!-- Terms Modal -->

<div id="termsModal" class="modal-overlay">

    <div class="modal">

        <div class="modal-header">

            <h2 class="modal-title">Email Signature Guidelines</h2>

        </div>

        <div class="modal-content">

            <p>Welcome to our service. By accessing or using this website, you agree to be bound by these Terms and Conditions.</p>

            

            <h3><i class="fas fa-check-circle"></i> Signature Selection</h3>

            <p>Ensure to select your own entities for the signature. Do not use VDart's signature if working for Dimiour, TrustPeople.</p>



            <h3><i class="fas fa-shield-alt"></i> Signature Integrity</h3>

            <p>Do not modify the signature in any way. This includes not adding any fields or altering the format and fonts of the signature. Maintain the original format at all times.</p>



            <h3><i class="fas fa-exclamation-triangle"></i> Compliance and Escalation</h3>

            <p>Any modifications found in the signature will lead to escalation according to the company's policy.</p>



            <h3><i class="fas fa-headset"></i> Assistance</h3>

            <p>If you need assistance or have questions about setting up your signature, please reach out to csm@vdartinc.com.</p>

        </div>

        <div class="modal-footer">

            <button class="accept-button" onclick="acceptTerms()">

                <i class="fas fa-check-circle"></i> I Understand and Accept

            </button>

        </div>

    </div>

</div>



<div id="pictureModal" class="modal-overlay">

    <div class="modal">

        <div class="modal-header">

            <h2 class="modal-title">LinkedIn Picture Guidelines</h2>

        </div>

        <div class="modal-content">

            <p>Welcome to our LinkedIn Picture Generator service. Please review these important guidelines before proceeding.</p>

            

            <h3><i class="fas fa-image"></i> Photo Requirements</h3>

            <p>Ensure your photo meets professional LinkedIn standards. The photo should be a clear headshot with a neutral background and professional attire.</p>



            <h3><i class="fas fa-check-circle"></i> Image Quality</h3>

            <p>Upload a high-quality image (minimum 400x400 pixels). The photo should be well-lit and in focus. Avoid using filters or heavy editing.</p>



            <h3><i class="fas fa-shield-alt"></i> Professional Standards</h3>

            <p>Your photo should reflect VDart Group's professional image. Maintain appropriate business attire and a professional appearance.</p>



            <h3><i class="fas fa-exclamation-triangle"></i> Usage Guidelines</h3>

            <p>The generated image is for professional use on LinkedIn and other business platforms associated with VDart Group companies.</p>



            <h3><i class="fas fa-headset"></i> Support</h3>

            <p>For assistance with your LinkedIn profile picture, please contact csm@vdartinc.com.</p>

        </div>

        <div class="modal-footer">

            <button class="accept-button" onclick="acceptPictureTerms()">

                <i class="fas fa-check-circle"></i> I Understand and Proceed

            </button>

        </div>

    </div>

</div>

<!-- Improved Upload Modal -->
<div id="uploadModal" class="upload-modal">
    <div class="modal-content1">
        <div class="modal-header1">
            <h2>Upload Images</h2>
            <button class="close-modal" onclick="closeUploadModal()">&times;</button>
        </div>
        
        <form id="uploadForm" action="Pages/upload.php" method="POST" enctype="multipart/form-data">
        <div class="upload-area" id="dropZone">
            <i class="fas fa-cloud-upload-alt upload-icon"></i>
            <div class="upload-text">Drag and drop your images here</div>
            <div class="upload-subtext">or click to browse</div>
            <div class="file-specs">Supports: JPG, PNG, GIF (Max 5MB each)</div>
            <input type="file" id="fileInput" name="images[]" class="file-input" accept="image/*" multiple style="display: none;">
        </div>

            <div class="preview-container" id="previewContainer"></div>
            
            <div class="upload-footer">
                <div class="upload-stats">
                    <span id="fileCount">0 files selected</span>
                    <span id="totalSize">0 MB total</span>
                </div>
                <button type="submit" class="upload-btn" disabled>
                    <i class="fas fa-upload"></i>
                    Upload Files
                </button>
            </div>
        </form>
        
        <div id="uploadProgress" class="upload-progress">
            <div class="progress-bar">
                <div class="progress-fill"></div>
            </div>
            <div class="progress-text">0%</div>
        </div>
    </div>
</div>


<!-- User Management Modal -->
<div id="userManagementModal" class="modal-overlay">
    <div class="modal user-modal">
        <div class="modal-header">
            <h2 class="modal-title">User Management</h2>
            <button class="close-modal" onclick="closeUserManagement()">&times;</button>
        </div>
        
        <div class="modal-content">
            <!-- Search and Add User Controls -->
            <div class="controls-container">
                <div class="search-container">
                    <i class="fas fa-search"></i>
                    <input type="text" id="userSearch" placeholder="Search users..." onkeyup="filterUsers()">
                </div>
                <button class="add-user-btn" onclick="openAddUserModal()">
                    <i class="fas fa-user-plus"></i>
                    Add User
                </button>
            </div>

            <!-- Users Table -->
            <div class="table-container">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <!-- Table content will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div id="addUserModal" class="modal-overlay">
    <div class="modal add-user-modal">
        <div class="modal-header">
            <h2 class="modal-title">Add New User</h2>
            <button class="close-modal" onclick="closeAddUserModal()">&times;</button>
        </div>
        
        <div class="modal-content">
            <form id="addUserForm" onsubmit="handleAddUser(event)">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Name</label>
                    <input type="text" name="name" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-lock"></i> Password</label>
                    <input type="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user-tag"></i> Role</label>
                    <select name="role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-user-plus"></i>
                    Add User
                </button>
            </form>
        </div>
    </div>
</div>


<!-- Edit User Modal -->
<div id="editUserModal" class="modal-overlay">
    <div class="modal add-user-modal">
        <div class="modal-header">
            <h2 class="modal-title">Edit User</h2>
            <button class="close-modal" onclick="closeEditUserModal()">&times;</button>
        </div>
        
        <div class="modal-content">
            <form id="editUserForm" onsubmit="handleEditUser(event)">
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="form-group">
                    <label><i class="fas fa-user"></i> Name</label>
                    <input type="text" name="name" id="edit_name" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-user-tag"></i> Role</label>
                    <select name="role" id="edit_role" required>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-save"></i>
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</div>

<style>
.upload-modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.85);
    backdrop-filter: blur(8px);
    z-index: 1000;
}

.modal-content1 {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 800px;
    background: white;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
}

.modal-header1 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    border-bottom: 1px solid #eee;
}

.modal-header h2 {
    font-size: 24px;
    color: #242297;
    margin: 0;
}

.close-modal {
    background: none;
    border: none;
    font-size: 28px;
    color: #666;
    cursor: pointer;
    transition: transform 0.3s ease;
}

.close-modal:hover {
    transform: rotate(90deg);
    color: #242297;
}

.upload-area {
    margin: 20px;
    padding: 40px;
    border: 2px dashed #242297;
    border-radius: 15px;
    text-align: center;
    transition: all 0.3s ease;
    background: rgba(36, 34, 151, 0.02);
}

.upload-area.dragover {
    background: rgba(36, 34, 151, 0.05);
    border-color: #3A7BD5;
    transform: scale(0.99);
}

.upload-icon {
    font-size: 48px;
    color: #242297;
    margin-bottom: 15px;
}

.upload-text {
    font-size: 20px;
    color: #242297;
    margin-bottom: 8px;
}

.upload-subtext {
    font-size: 16px;
    color: #666;
    margin-bottom: 10px;
}

.file-specs {
    font-size: 14px;
    color: #888;
}

.preview-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 15px;
    padding: 20px;
    max-height: 300px;
    overflow-y: auto;
}

.preview-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.preview-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-file {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    background: rgba(255, 255, 255, 0.9);
    border: none;
    border-radius: 50%;
    color: #ff4444;
    font-size: 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    opacity: 0;
    transform: scale(0.8);
}

.preview-item:hover .remove-file {
    opacity: 1;
    transform: scale(1);
}

.remove-file:hover {
    background: #ff4444;
    color: white;
    transform: scale(1.1) rotate(90deg);
}

.upload-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-top: 1px solid #eee;
}

.upload-stats {
    display: flex;
    flex-direction: column;
    gap: 5px;
    color: #666;
    font-size: 14px;
}

.upload-btn {
    background: linear-gradient(135deg, #242297, #3A7BD5);
    color: white;
    border: none;
    padding: 12px 30px;
    border-radius: 50px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.upload-btn:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.upload-btn:not(:disabled):hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(36, 34, 151, 0.2);
}

.upload-progress {
    display: none;
    padding: 20px;
}

.progress-bar {
    height: 6px;
    background: #eee;
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #242297, #3A7BD5);
    width: 0%;
    transition: width 0.3s ease;
}

.progress-text {
    text-align: center;
    margin-top: 10px;
    color: #666;
    font-size: 14px;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { transform: translate(-50%, -40%); opacity: 0; }
    to { transform: translate(-50%, -50%); opacity: 1; }
}

.form-group select {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-color: white;
}

.form-group select:focus {
    border-color: #242297;
    box-shadow: 0 0 0 2px rgba(36, 34, 151, 0.1);
    outline: none;
}

.edit-btn {
    color: #2196F3;
    margin-right: 8px;
}

.edit-btn:hover {
    background: rgba(33, 150, 243, 0.1);
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('uploadModal');
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    const uploadBtn = document.querySelector('.upload-btn');
    const fileCount = document.getElementById('fileCount');
    const totalSize = document.getElementById('totalSize');
    const form = document.getElementById('uploadForm');
    const progressBar = document.querySelector('.progress-fill');
    const progressText = document.querySelector('.progress-text');
    
    let selectedFiles = new Set();

    // Modal functions remain the same
    window.openUploadModal = function() {
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
        modal.animate([
            { opacity: 0 },
            { opacity: 1 }
        ], {
            duration: 300,
            easing: 'ease'
        });
    }

    window.closeUploadModal = function() {
        const anim = modal.animate([
            { opacity: 1 },
            { opacity: 0 }
        ], {
            duration: 300,
            easing: 'ease'
        });
        
        anim.onfinish = () => {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
            resetUpload();
        };
    }

    function resetUpload() {
        form.reset();
        selectedFiles.clear();
        previewContainer.innerHTML = '';
        updateFileStats();
        progressBar.style.width = '0%';
        progressText.textContent = '';
        document.getElementById('uploadProgress').style.display = 'none';
    }

    // Modified file handling
    function handleFiles(files) {
        [...files].forEach(file => {
            if (file.size > 5 * 1024 * 1024) {
                showError(`${file.name} is too large (max 5MB)`);
                return;
            }
            
            if (!file.type.startsWith('image/')) {
                showError(`${file.name} is not an image`);
                return;
            }
            
            const exists = [...selectedFiles].some(existingFile => 
                existingFile.name === file.name && existingFile.size === file.size
            );
            
            if (!exists) {
                selectedFiles.add(file);
                addPreview(file);
            }
        });
        
        updateFileStats();
    }

    // Updated click handlers
    // Handle click on the drop zone or any elements inside it
    dropZone.addEventListener('click', function(e) {
    // Only trigger file input if clicking on the drop zone itself or specific elements
    const target = e.target;
    const isPreviewItem = target.closest('.preview-item');
    const isUploadText = target.closest('.upload-text') || target.closest('.upload-subtext');
    const isUploadIcon = target.closest('.upload-icon');
    
    // Prevent opening file dialog when clicking on previews or remove buttons
    if (!isPreviewItem && (target === dropZone || isUploadText || isUploadIcon)) {
        e.stopPropagation(); // Stop event from bubbling
        fileInput.click();
    }
});

    // Handle file selection
    // Update file input change handler to prevent multiple triggers
fileInput.addEventListener('change', function(e) {
    e.stopPropagation(); // Stop event from bubbling
    if (this.files && this.files.length > 0) {
        handleFiles(this.files);
        // Reset the input
        this.value = '';
    }
}, false);

// Make sure to remove any existing click handlers from the drop zone
['dragenter', 'dragover', 'dragleave'].forEach(eventName => {
    dropZone.addEventListener(eventName, function(e) {
        e.preventDefault();
        e.stopPropagation();
    });
});

    // Drag and drop handlers
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        e.stopPropagation();
        this.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });

    // Rest of your functions remain the same
    function addPreview(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            const div = document.createElement('div');
            div.className = 'preview-item';
            div.innerHTML = `
                <img src="${e.target.result}" class="preview-image" alt="${file.name}">
                <div class="file-name">${file.name.length > 20 ? file.name.substring(0, 17) + '...' : file.name}</div>
                <button type="button" class="remove-file" title="Remove ${file.name}">&times;</button>
                <div class="file-status"></div>
            `;
            
            div.querySelector('.remove-file').onclick = (event) => {
                event.stopPropagation();
                selectedFiles.delete(file);
                div.remove();
                updateFileStats();
            };
            
            div.style.opacity = '0';
            div.style.transform = 'scale(0.8)';
            previewContainer.appendChild(div);
            
            requestAnimationFrame(() => {
                div.style.opacity = '1';
                div.style.transform = 'scale(1)';
            });
        };
        reader.readAsDataURL(file);
    }

    function updateFileStats() {
        const totalBytes = [...selectedFiles].reduce((acc, file) => acc + file.size, 0);
        const totalMB = (totalBytes / (1024 * 1024)).toFixed(2);
        
        fileCount.textContent = `${selectedFiles.size} file${selectedFiles.size !== 1 ? 's' : ''} selected`;
        totalSize.textContent = `${totalMB} MB total`;
        uploadBtn.disabled = selectedFiles.size === 0;
    }

    // Form submission handler remains the same
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if (selectedFiles.size === 0) {
            showError('Please select at least one file to upload');
            return;
        }
        
        const formData = new FormData();
        selectedFiles.forEach(file => {
            formData.append('images[]', file);
        });
        
        const progressContainer = document.getElementById('uploadProgress');
        progressContainer.style.display = 'block';
        uploadBtn.disabled = true;
        
        try {
            const response = await fetch('Pages/upload.php', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                progressBar.style.width = '100%';
                progressText.textContent = 'Upload Complete!';
                showSuccess(`Successfully uploaded ${result.files.length} file(s)`);
                
                setTimeout(() => {
                    resetUpload();
                    closeUploadModal();
                }, 1500);
            } else {
                showError(result.message || 'Upload failed');
            }
        } catch (error) {
            showError('Upload failed. Please try again.');
            console.error('Upload error:', error);
        } finally {
            uploadBtn.disabled = false;
        }
    });

    // Notification functions remain the same
    function showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'upload-error';
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: #ff4444;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
        errorDiv.textContent = message;
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => errorDiv.remove(), 300);
        }, 3000);
    }

    function showSuccess(message) {
        const successDiv = document.createElement('div');
        successDiv.className = 'upload-success';
        successDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            background-color: #4CAF50;
            color: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        `;
        successDiv.textContent = message;
        document.body.appendChild(successDiv);
        
        setTimeout(() => {
            successDiv.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => successDiv.remove(), 300);
        }, 3000);
    }
});
</script>

    <div id="mainContent">

    <div class="header" style="background-color: #333; position: relative;">

    <!-- Header Container -->

    <div style="display: flex; justify-content: center; align-items: center; width: 100%; padding: 20px;">

        <div style="display: flex; align-items: center; max-width: 1800px;">

            <h1 style="margin: 0; color: white; font-size: 3rem;">Welcome to the VDart Brand Desk</h1>

        </div>

        <!-- <button class="upload-trigger-btn" onclick="openUploadModal()" style="
            position: absolute;
            right: 180px;
            top: 20px;
            padding: 12px 20px;
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg,rgb(115, 68, 255), #ff9900);
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

    Upload
</button> -->
        <!-- Logout Button -->

        <button onclick="logout()" style="
            position: absolute;
            right: 20px;
            top: 20px;
            padding: 12px 20px;
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


    </div>



    <p style="color: white; text-align: center; margin: 0; padding-bottom: 20px;">Select Your Entity</p>

</div>

        

        <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem; padding: 2rem; max-width: 1400px; margin: 0 auto; ">

    <div class="card" style="background: white; border-radius: 20px; padding: 2.5rem; display: flex; flex-direction: column; align-items: center; min-height: 380px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease;">

        <img src="https://github.com/Saranraj102000/VDart-images/blob/main/VDart_Logo.png?raw=true" alt="VDart Logo" style="width: 140px; height: auto; margin-bottom: 2rem;">

        <h2 style="color:rgb(3, 3, 3); text-align: center; font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem;">VDART</h2>

        <p style="text-align: center; color: #4a5568; line-height: 1.6; margin-bottom: 2rem; font-size: 0.95rem;">

            Empowering digital transformation through innovative workforce solutions and technology services. Leading the future of work with global expertise.

        </p>

        <a href="#" class="entity-link" data-href="Pages/signature.php" style="background: linear-gradient(135deg, #242297, #00C9FF); color: white; text-decoration: none; padding: 0.8rem 2rem; border-radius: 50px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease; margin-top: auto;">Select Entity</a>

    </div>

    <div class="card" style="background: white; border-radius: 20px; padding: 2.5rem; display: flex; flex-direction: column; align-items: center; min-height: 380px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease;">

        <img src="http://vdpl.co/dnimg/VDart_Digital_Blue_Logo.png" alt="Dimiour Logo" style="width: 180px; height: auto; margin-bottom: 2rem;">

        <h2 style="color:rgb(3, 3, 3); text-align: center; font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem;">VDart Digital</h2>

        <p style="text-align: center; color: #4a5568; line-height: 1.6; margin-bottom: 2rem; font-size: 0.95rem;">

            Driving digital innovation through cutting-edge technology solutions. Transforming businesses with expert consulting and advanced development services.

        </p>

        <a href="#" class="entity-link" data-href="Pages/dimiour.php" style="background: linear-gradient(135deg, #242297, #00C9FF); color: white; text-decoration: none; padding: 0.8rem 2rem; border-radius: 50px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease; margin-top: auto;">Select Entity</a>

    </div>


    <div class="card" style="background: white; border-radius: 20px; padding: 2.5rem; display: flex; flex-direction: column; align-items: center; min-height: 380px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); transition: transform 0.3s ease;">

        <img src="https://github.com/Saranraj102000/VDart-images/blob/main/Trustpeople.png?raw=true" alt="Trust People Logo" style="width: 200px; height: auto; margin-bottom: 3.3rem;">

        <h2 style="color:rgb(3, 3, 3); text-align: center; font-size: 1.5rem; font-weight: 600; margin-bottom: 1.5rem;">TRUSTPEOPLE</h2>

        <p style="text-align: center; color: #4a5568; line-height: 1.6; margin-bottom: 2rem; font-size: 0.95rem;">

            Connecting exceptional talent with opportunities. Building trusted partnerships through personalized staffing solutions and career development.

        </p>

        <a href="#" class="entity-link" data-href="Pages/trustpeople.php" style="background: linear-gradient(135deg, #242297, #00C9FF); color: white; text-decoration: none; padding: 0.8rem 2rem; border-radius: 50px; font-size: 1rem; font-weight: 500; transition: all 0.3s ease; margin-top: auto;">Select Entity</a>

    </div>



    

</div>

<!-- User Management Button -->
<!-- <button id="userManagementBtn" class="floating-btn" onclick="openUserManagement()">
    <i class="fas fa-users"></i>
    
</button> -->



<style>
/* Floating Button */
.floating-btn {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: linear-gradient(135deg,rgb(232, 16, 16), #3A7BD5);
    color: white;
    padding: 1rem 2rem;
    border: none;
    border-radius: 50px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
    cursor: pointer;
    box-shadow: 0 4px 15px rgba(36, 34, 151, 0.3);
    transition: all 0.3s ease;
    z-index: 100;
}

.floating-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(36, 34, 151, 0.4);
}

/* User Management Modal Specific Styles */
.user-modal {
    max-width: 1000px;
    width: 90%;
    max-height: 80vh;
}

.controls-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding: 0 1rem;
}

.search-container {
    position: relative;
    flex: 1;
    max-width: 400px;
}

.search-container i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.search-container input {
    width: 100%;
    padding: 0.8rem 1rem 0.8rem 2.5rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.search-container input:focus {
    border-color: #242297;
    box-shadow: 0 0 0 2px rgba(36, 34, 151, 0.1);
    outline: none;
}

.add-user-btn {
    background: linear-gradient(135deg, #00a389, #00c4a7);
    color: white;
    border: none;
    padding: 0.8rem 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-user-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 163, 137, 0.2);
}

/* Table Styles */
.table-container {
    overflow-x: auto;
    margin: 0 1rem;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 0 0 1px #eee;
}

.users-table th,
.users-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.users-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.users-table tr:hover {
    background: #f8f9fa;
}

/* Add User Modal Specific Styles */
.add-user-modal {
    max-width: 500px;
    width: 90%;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
    color: #333;
    font-weight: 500;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #242297;
    box-shadow: 0 0 0 2px rgba(36, 34, 151, 0.1);
    outline: none;
}

.submit-btn {
    width: 100%;
    background: linear-gradient(135deg, #00a389, #00c4a7);
    color: white;
    border: none;
    padding: 1rem;
    border-radius: 8px;
    font-size: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 163, 137, 0.2);
}

/* Action Buttons */
.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.delete-btn {
    color: #dc3545;
}

.delete-btn:hover {
    background: rgba(220, 53, 69, 0.1);
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .controls-container {
        flex-direction: column;
        gap: 1rem;
    }
    
    .search-container {
        max-width: 100%;
    }
    
    .users-table {
        font-size: 0.9rem;
    }
    
    .floating-btn {
        padding: 0.8rem 1.5rem;
        font-size: 0.9rem;
    }
}
</style>


        <div class="footer">

            &copy; 2025 VDart. All rights reserved.

        </div>

    </div>



   <script> 

    let currentUrl = '';

const termsModal = document.getElementById('termsModal');

const typeModal = document.getElementById('typeModal');

const mainContent = document.getElementById('mainContent');



// Helper function to handle modal transitions

function transitionModal(modal, isShowing) {

    if (isShowing) {

        modal.style.visibility = 'visible';

        // Force reflow

        modal.offsetHeight;

        modal.classList.add('active');

        document.body.style.overflow = 'hidden';

    } else {

        modal.classList.remove('active');

        setTimeout(() => {

            modal.style.visibility = 'hidden';

            document.body.style.overflow = '';

        }, 300); // Match this with your transition duration

    }

}



function showTypeModal(url) {

    currentUrl = url;

    mainContent.classList.add('blur-content');

    transitionModal(typeModal, true);

    

    const modalContent = typeModal.querySelector('.type-modal');

    modalContent.style.opacity = '0';

    modalContent.style.transform = 'translateY(20px)';

    

    requestAnimationFrame(() => {

        modalContent.style.opacity = '1';

        modalContent.style.transform = 'translateY(0)';

    });

}



function showPictureModal() {

    const modal = document.getElementById('pictureModal');

    const mainContent = document.getElementById('mainContent');

    modal.style.display = 'flex';

    mainContent.classList.add('blur-content');

    setTimeout(() => {

        modal.classList.add('active');

    }, 10);

}



function acceptPictureTerms() {

    const modal = document.getElementById('pictureModal');

    const mainContent = document.getElementById('mainContent');

    modal.classList.remove('active');

    mainContent.classList.remove('blur-content');

    setTimeout(() => {

        window.location.href = 'Pages/picturegenerator.php';

    }, 300);

}



function handleTypeSelection(type) {
    // Get the selected entity from currentUrl
    const entity = currentUrl.split('/').pop().split('.')[0];
    console.log("Selected entity:", entity); // For debugging
    
    if (type === 'linkedin') {
        const modalContent = typeModal.querySelector('.type-modal');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            transitionModal(typeModal, false);
            showPictureModal();
        }, 300);

        // Handle picture modal acceptance and redirection
        document.getElementById('pictureModal').querySelector('.accept-button').onclick = function() {
            if (entity === 'dimiour') {
                window.location.href = 'Pages/picturegenerator1.php';
            } else if (entity === 'trustpeople') { // Changed from 'trust' to 'trustpeople'
                window.location.href = 'Pages/picturegenerator2.php';
            } else {
                window.location.href = 'Pages/picturegenerator.php';
            }
        };
    } else {
        const modalContent = typeModal.querySelector('.type-modal');
        modalContent.style.opacity = '0';
        modalContent.style.transform = 'translateY(-20px)';
        
        setTimeout(() => {
            transitionModal(typeModal, false);
            showTermsModal();
        }, 300);
    }
}



function showTermsModal() {

    mainContent.classList.add('blur-content');

    transitionModal(termsModal, true);

    

    const modalContent = termsModal.querySelector('.modal');

    modalContent.style.opacity = '0';

    modalContent.style.transform = 'translateY(20px)';

    

    requestAnimationFrame(() => {

        modalContent.style.opacity = '1';

        modalContent.style.transform = 'translateY(0)';

    });

}



function closeModal() {

    const activeModal = document.querySelector('.modal-overlay.active') || 

                       document.querySelector('.type-modal-overlay.active');

    

    if (activeModal) {

        const modalContent = activeModal.querySelector('.modal, .type-modal');

        if (modalContent) {

            modalContent.style.opacity = '0';

            modalContent.style.transform = 'translateY(-20px)';

            

            setTimeout(() => {

                transitionModal(activeModal, false);

                mainContent.classList.remove('blur-content');

                

                // Reset styles after transition

                modalContent.style.opacity = '';

                modalContent.style.transform = '';

            }, 300);

        }

    }

}



function acceptTerms() {

    const acceptButton = termsModal.querySelector('.accept-button');

    acceptButton.style.transform = 'scale(0.95)';

    

    setTimeout(() => {

        acceptButton.style.transform = '';

        if (currentUrl) {

            closeModal();

            setTimeout(() => {

                window.location.href = currentUrl;

            }, 300);

        }

    }, 150);

}



// Add event listener for modal close on outside click

document.getElementById('pictureModal').addEventListener('click', (e) => {

    if (e.target.id === 'pictureModal') {

        const modal = document.getElementById('pictureModal');

        const mainContent = document.getElementById('mainContent');

        modal.classList.remove('active');

        mainContent.classList.remove('blur-content');

    }

});



// Event Listeners

document.addEventListener('DOMContentLoaded', function() {

    // Entity link delegation

    document.addEventListener('click', function(e) {

        const link = e.target.closest('.entity-link');

        if (link) {

            e.preventDefault();

            const href = link.getAttribute('data-href');

            if (href) {

                showTypeModal(href);

            }

        }

    });



    // Outside click handlers

    [termsModal, typeModal].forEach(modal => {

        modal.addEventListener('click', (e) => {

            if (e.target === modal) {

                closeModal();

            }

        });

    });



    // Paragraph hover effects

    const paragraphs = document.querySelectorAll('.modal-content p');

    paragraphs.forEach(p => {

        p.addEventListener('mouseenter', () => {

            p.style.borderLeftColor = 'var(--primary-color)';

            p.style.background = 'rgba(0, 77, 64, 0.03)';

        });

        

        p.addEventListener('mouseleave', () => {

            p.style.borderLeftColor = 'rgba(0, 77, 64, 0.1)';

            p.style.background = 'transparent';

        });

    });



    // Escape key handler

    document.addEventListener('keydown', (e) => {

        if (e.key === 'Escape') {

            closeModal();

        }

    });



    // Button interactions

    const buttons = document.querySelectorAll('.type-button, .accept-button');

    buttons.forEach(button => {

        ['mousedown', 'touchstart'].forEach(event => {

            button.addEventListener(event, () => {

                button.style.transform = 'scale(0.98)';

            });

        });

        

        ['mouseup', 'mouseleave', 'touchend', 'touchcancel'].forEach(event => {

            button.addEventListener(event, () => {

                button.style.transform = '';

            });

        });

    });

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

            window.location.href = 'Pages/logout.php';

            

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

function openUserManagement() {
    const modal = document.getElementById('userManagementModal');
    modal.style.display = 'flex';  // First set display to flex
    modal.style.opacity = '1';     // Make it visible
    modal.style.visibility = 'visible';
    modal.classList.add('active');
    document.getElementById('mainContent').classList.add('blur-content');
    loadUsers();
}

function closeUserManagement() {
    const modal = document.getElementById('userManagementModal');
    modal.classList.remove('active');
    modal.style.opacity = '0';
    document.getElementById('mainContent').classList.remove('blur-content');
    
    // After transition, hide the modal
    setTimeout(() => {
        modal.style.visibility = 'hidden';
        modal.style.display = 'none';
    }, 300); // Match this with your CSS transition duration
}

function openAddUserModal() {
    document.getElementById('addUserModal').classList.add('active');
}

function closeAddUserModal() {
    document.getElementById('addUserModal').classList.remove('active');
    document.getElementById('addUserForm').reset();
}

// User Management Functions
function loadUsers() {
    const tbody = document.getElementById('usersTableBody');
    
    // Show loading state
    tbody.innerHTML = `
        <tr>
            <td colspan="3" class="text-center">
                <div style="padding: 20px;">
                    <i class="fas fa-spinner fa-spin"></i> Loading users...
                </div>
            </td>
        </tr>`;

    // Simple AJAX request
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'Pages/api/get_users.php', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            tbody.innerHTML = xhr.responseText;
        } else {
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="text-center">
                        <div style="padding: 20px; color: #dc3545;">
                            <i class="fas fa-exclamation-circle"></i>
                            Error loading users
                        </div>
                    </td>
                </tr>`;
        }
    };
    
    xhr.onerror = function() {
        tbody.innerHTML = `
            <tr>
                <td colspan="3" class="text-center">
                    <div style="padding: 20px; color: #dc3545;">
                        <i class="fas fa-exclamation-circle"></i>
                        Connection error
                    </div>
                </td>
            </tr>`;
    };
    
    xhr.send();
}

// Helper function to escape HTML
function escapeHtml(unsafe) {
    if (unsafe == null) return '';
    return unsafe
        .toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function handleAddUser(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'Pages/api/add_user.php', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            closeAddUserModal();
            loadUsers();
            form.reset();
            showNotification('User added successfully', 'success');
        } else {
            showNotification('Error adding user', 'error');
        }
    };
    
    xhr.send(formData);
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'Pages/api/delete_user.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                loadUsers(); // Reload the user list
                showNotification('User deleted successfully', 'success');
            } else {
                showNotification('Error deleting user', 'error');
            }
        };
        
        xhr.send('id=' + userId);
    }
}

function filterUsers() {
    const searchTerm = document.getElementById('userSearch').value.toLowerCase();
    const tbody = document.getElementById('usersTableBody');
    const rows = tbody.getElementsByTagName('tr');

    for (const row of rows) {
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (const cell of cells) {
            if (cell.textContent.toLowerCase().includes(searchTerm)) {
                found = true;
                break;
            }
        }

        row.style.display = found ? '' : 'none';
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 25px;
        background-color: ${type === 'success' ? '#4CAF50' : '#ff4444'};
        color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Add click handlers for modal
    document.getElementById('userManagementBtn').onclick = function() {
        openUserManagement();
        loadUsers();
    };
    
    // Close button handlers
    document.querySelectorAll('.close-modal').forEach(button => {
        button.onclick = function() {
            if (this.closest('#userManagementModal')) {
                closeUserManagement();
            } else if (this.closest('#addUserModal')) {
                closeAddUserModal();
            }
        };
    });
});

function editUser(userId) {
    // Fetch user data
    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'Pages/api/get_user.php?id=' + userId, true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            try {
                // Assuming the response is a comma-separated string: id,name,email,role
                const userData = xhr.responseText.split(',');
                
                // Populate form fields
                document.getElementById('edit_user_id').value = userData[0];
                document.getElementById('edit_name').value = userData[1];
                document.getElementById('edit_email').value = userData[2];
                document.getElementById('edit_role').value = userData[3];
                
                // Show modal
                document.getElementById('editUserModal').classList.add('active');
            } catch (error) {
                showNotification('Error parsing user data', 'error');
            }
        } else {
            showNotification('Error loading user data', 'error');
        }
    };
    
    xhr.send();
}

function closeEditUserModal() {
    document.getElementById('editUserModal').classList.remove('active');
    document.getElementById('editUserForm').reset();
}

function handleEditUser(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);
    
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'Pages/api/update_user.php', true);
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            closeEditUserModal();
            loadUsers();
            showNotification('User updated successfully', 'success');
        } else {
            showNotification('Error updating user', 'error');
        }
    };
    
    xhr.send(formData);
}
</script>

</body>

</html>