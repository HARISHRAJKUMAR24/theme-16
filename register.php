<?php
include_once __DIR__ . "/includes/files_includes.php";

// Load this store's theme colors from the database
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#64748b';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#475569';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>

    <style>
        /* ========================================
           THEME 17 - REGISTER PAGE
        ======================================== */

        :root {
            --primary-color: <?= htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8') ?>;
            --hover-color: <?= htmlspecialchars($hover_color, ENT_QUOTES, 'UTF-8') ?>;
            --primary-soft: color-mix(in srgb, var(--primary-color) 10%, white);
            --primary-pale: color-mix(in srgb, var(--primary-color) 5%, white);
            --primary-border: color-mix(in srgb, var(--primary-color) 18%, white);
            --primary-shadow: color-mix(in srgb, var(--primary-color) 16%, transparent);
        }

        .iti {
            width: 100% !important;
        }

        .t17-register-page {
            min-height: 78vh;
            background: #f8fafc;
            padding: 48px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .t17-register-card {
            width: 100%;
            max-width: 1040px;
            min-height: 590px;
            display: grid;
            grid-template-columns: 0.9fr 1.1fr;
            overflow: hidden;
            background: #fff;
            border: 1px solid #eeeeF2;
            border-radius: 24px;
            box-shadow: 0 15px 50px rgba(24, 24, 27, 0.07);
        }

        /* Left panel */

        .t17-register-banner {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 42px 36px;
            overflow: hidden;
            background: linear-gradient(145deg, color-mix(in srgb, var(--primary-color) 14%, white) 0%, color-mix(in srgb, var(--primary-color) 7%, white) 50%, color-mix(in srgb, var(--hover-color) 8%, white) 100%);
        }

        .t17-register-banner::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            right: -100px;
            bottom: -100px;
            border-radius: 50%;
            background: color-mix(in srgb, var(--primary-color) 10%, transparent);
        }

        .t17-register-banner::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            right: -40px;
            bottom: -30px;
            border-radius: 50%;
            border: 1px solid color-mix(in srgb, var(--primary-color) 22%, transparent);
        }

        .t17-banner-content,
        .t17-banner-footer {
            position: relative;
            z-index: 1;
        }

        .t17-banner-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border: 1px solid color-mix(in srgb, var(--primary-color) 20%, white);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.7);
            color: var(--hover-color);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .t17-banner-title {
            margin-top: 26px;
            color: #27272a;
            font-size: clamp(30px, 3vw, 43px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -1.5px;
        }

        .t17-banner-title span {
            color: var(--primary-color);
        }

        .t17-banner-description {
            max-width: 300px;
            margin-top: 17px;
            color: #71717a;
            font-size: 14px;
            line-height: 1.8;
        }

        .t17-benefit-list {
            display: grid;
            gap: 15px;
            margin-top: 35px;
        }

        .t17-benefit-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #3f3f46;
            font-size: 13px;
            font-weight: 600;
        }

        .t17-benefit-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #fff;
            color: var(--primary-color);
            box-shadow: 0 3px 12px color-mix(in srgb, var(--primary-color) 10%, transparent);
        }

        .t17-banner-footer {
            margin-top: 35px;
            color: #71717a;
            font-size: 12px;
        }

        /* Right panel */

        .t17-register-form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 44px clamp(24px, 5vw, 58px);
        }

        .t17-register-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            margin-bottom: 22px;
        }

        .t17-register-logo img {
            display: block;
            max-width: 155px;
            max-height: 52px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .t17-register-heading {
            margin: 0;
            color: #18181b;
            text-align: center;
            font-size: 29px;
            font-weight: 800;
            letter-spacing: -0.8px;
        }

        .t17-register-subheading {
            margin-top: 9px;
            margin-bottom: 28px;
            color: #71717a;
            text-align: center;
            font-size: 13px;
            line-height: 1.7;
        }

        .t17-form-label {
            display: block;
            margin-bottom: 8px;
            color: #3f3f46;
            font-size: 12px;
            font-weight: 700;
        }

        .t17-form-input {
            display: block;
            width: 100%;
            height: 47px;
            padding: 0 14px;
            border: 1px solid #e4e4e7;
            border-radius: 11px;
            outline: none;
            background: #fff;
            color: #18181b;
            font-size: 13px;
            transition: border-color .2s, box-shadow .2s;
        }

        .t17-form-input::placeholder {
            color: #a1a1aa;
        }

        .t17-form-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 12%, transparent);
        }

        .t17-form-group {
            margin-bottom: 18px;
        }

        .t17-register-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            min-height: 48px;
            margin-top: 5px;
            padding: 12px 18px;
            border: none;
           
            background: linear-gradient(135deg, var(--primary-color), var(--hover-color));
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .3px;
            cursor: pointer;
           
        }

        .t17-register-button:hover {
            background: linear-gradient(135deg, var(--hover-color), var(--primary-color));
          
        }

        .t17-register-button:active {
            transform: scale(.99);
        }

        .t17-register-button:disabled {
            opacity: .65;
            cursor: not-allowed;
        }

        .t17-register-login {
            margin-top: 25px;
            color: #71717a;
            text-align: center;
            font-size: 12px;
        }

        .t17-register-login a {
            color: var(--primary-color);
            font-weight: 800;
            text-decoration: none;
        }

        .t17-register-login a:hover {
            text-decoration: underline;
        }

        .t17-register-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 22px 0 0;
            color: #a1a1aa;
            font-size: 10px;
        }

        .t17-register-divider::before,
        .t17-register-divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: #eeeeF2;
        }

        .t17-register-security {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-top: 15px;
            color: #a1a1aa;
            font-size: 10px;
        }

        .t17-register-message {
            margin-bottom: 20px;
            padding: 12px 14px;
            border: 1px solid #bae6fd;
            border-radius: 11px;
            background: #f0f9ff;
            color: #0369a1;
            font-size: 12px;
            line-height: 1.6;
        }

        /* Phone input plugin */

        .t17-register-form-panel .iti {
            display: block;
        }

        .t17-register-form-panel .iti input[type="tel"] {
            width: 100%;
            height: 47px;
            border: 1px solid #e4e4e7;
            border-radius: 11px;
            padding-top: 0;
            padding-bottom: 0;
            outline: none;
            font-size: 13px;
        }

        .t17-register-form-panel .iti input[type="tel"]:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 12%, transparent);
        }

        /* Responsive */

        @media (max-width: 767px) {
            .t17-register-page {
                min-height: auto;
                padding: 24px 12px 32px;
            }

            .t17-register-card {
                max-width: 480px;
                min-height: auto;
                grid-template-columns: 1fr;
                border-radius: 20px;
            }

            .t17-register-banner {
                padding: 23px 24px;
            }

            .t17-banner-title {
                margin-top: 18px;
                font-size: 27px;
                letter-spacing: -.8px;
            }

            .t17-banner-description {
                margin-top: 10px;
                font-size: 12px;
            }

            .t17-benefit-list {
                grid-template-columns: 1fr;
                gap: 10px;
                margin-top: 20px;
            }

            .t17-benefit-icon {
                width: 29px;
                height: 29px;
            }

            .t17-banner-footer {
                margin-top: 20px;
            }

            .t17-register-form-panel {
                padding: 30px 22px;
            }

            .t17-register-logo {
                margin-bottom: 15px;
            }

            .t17-register-heading {
                font-size: 26px;
            }

            .t17-register-subheading {
                margin-bottom: 24px;
            }
        }

        @media (max-width: 360px) {
            .t17-register-form-panel {
                padding: 26px 16px;
            }

            .t17-register-banner {
                padding: 21px 18px;
            }

            .t17-banner-title {
                font-size: 25px;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .t17-form-input,
            .t17-register-button {
                transition: none;
            }
        }
    </style>
</head>

<body class="font-sans min-h-screen bg-white">

    <!-- Navbar -->
    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Theme 17 Register Section -->
    <main class="t17-register-page">

        <div class="t17-register-card">

            <!-- Left promotional panel -->
            <aside class="t17-register-banner">

                <div class="t17-banner-content">

                    <div class="t17-banner-badge">
                        <span>✦</span>
                        Welcome to <?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?>
                    </div>

                    <h1 class="t17-banner-title">
                        Your next<br>
                        favourite thing<br>
                        is <span>waiting.</span>
                    </h1>

                    <p class="t17-banner-description">
                        Create your account for a smoother shopping experience.
                        Keep your details ready and get started in just a few steps.
                    </p>

                    <div class="t17-benefit-list">

                        <div class="t17-benefit-item">
                            <span class="t17-benefit-icon">✓</span>
                            <span>Quick and easy registration</span>
                        </div>

                        <div class="t17-benefit-item">
                            <span class="t17-benefit-icon">✉</span>
                            <span>OTP verification for your account</span>
                        </div>

                        <div class="t17-benefit-item">
                            <span class="t17-benefit-icon">♡</span>
                            <span>A convenient shopping experience</span>
                        </div>

                    </div>

                </div>

                <div class="t17-banner-footer">
                    Simple steps. A better way to shop.
                </div>

            </aside>

            <!-- Registration form -->
            <section class="t17-register-form-panel">

                <!-- Store logo -->
                <div class="t17-register-logo">
                    <?php if (getSettings("logo")) : ?>

                        <img
                            src="<?= htmlspecialchars(UPLOADS_URL . getSettings("logo"), ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?> logo">

                    <?php else : ?>

                        <div class="flex items-center justify-center w-12 h-12 rounded-2xl text-xl font-extrabold" style="background: color-mix(in srgb, var(--primary-color) 12%, white); color: var(--primary-color);">
                            <?= htmlspecialchars(substr($storeName, 0, 1), ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    <?php endif; ?>
                </div>

                <h2 class="t17-register-heading">Create your account</h2>

                <p class="t17-register-subheading">
                    Enter your details below to get started.
                </p>

                <form id="form" class="space-y-0">

                    <!-- Step 1 -->
                    <div id="step1">

                        <!-- Name -->
                        <div class="t17-form-group">
                            <label for="name" class="t17-form-label">
                                Full name
                            </label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                placeholder="Enter your full name"
                                autocomplete="name"
                                required
                                class="t17-form-input">
                        </div>

                        <!-- Phone -->
                        <div class="t17-form-group">
                            <label for="phone" class="t17-form-label">
                                Phone number
                            </label>

                            <input
                                type="tel"
                                name="phone[main]"
                                id="phone"
                                placeholder="Enter your phone number"
                                autocomplete="tel"
                                required
                                class="t17-form-input">
                        </div>

                        <!-- Email -->
                        <div class="t17-form-group">
                            <label for="email" class="t17-form-label">
                                Email address
                                <span class="font-normal text-gray-400">(Optional)</span>
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Enter your email address"
                                autocomplete="email"
                                class="t17-form-input">
                        </div>

                        <!-- Password -->
                        <div class="t17-form-group">
                            <label for="password" class="t17-form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Create a password"
                                autocomplete="new-password"
                                required
                                class="t17-form-input">
                        </div>

                        <!-- Send OTP -->
                        <button
                            type="button"
                            id="sendOtp"
                            class="t17-register-button">

                            <span>CONTINUE WITH OTP</span>
                          

                        </button>

                    </div>

                    <!-- Step 2 -->
                    <div id="step2" style="display:none;">

                        <div
                            id="msg"
                            class="t17-register-message"
                            role="status"
                            aria-live="polite">
                        </div>

                        <div class="t17-form-group">
                            <label for="otp" class="t17-form-label">
                                Verification code
                            </label>

                            <input
                                type="number"
                                name="otp"
                                id="otp"
                                placeholder="Enter the OTP you received"
                                inputmode="numeric"
                                autocomplete="one-time-code"
                                required
                                class="t17-form-input">
                        </div>

                        <button
                            type="button"
                            id="create"
                            class="t17-register-button">

                            <span>CREATE ACCOUNT</span>
                            
                        </button>

                    </div>

                </form>

                <div class="t17-register-divider">
                    SECURE REGISTRATION
                </div>

                <div class="t17-register-security">
                    <span aria-hidden="true">🔒</span>
                    Your information is submitted securely.
                </div>

                <!-- Login -->
                <p class="t17-register-login">
                    Already have an account?

                    <a href="<?= htmlspecialchars($storeUrl, ENT_QUOTES, 'UTF-8') ?>login<?= isset($_GET['step']) ? '?step' : '' ?>">
                        Sign in
                    </a>
                </p>

            </section>

        </div>

    </main>

    <!-- Footer -->
    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- Existing registration JavaScript -->
    <script src="<?= APP_URL ?>shop/javascripts/register.js"></script>

    <!-- Phone number configuration -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInputField = document.querySelector("#phone");

            if (
                phoneInputField &&
                typeof window.intlTelInput === "function"
            ) {
                window.intlTelInput(phoneInputField, {
                    separateDialCode: true,
                    onlyCountries: ['in'],
                    preferredCountries: ["<?= strtolower(htmlspecialchars((string) getSettings("country"), ENT_QUOTES, 'UTF-8')) ?>"],
                    hiddenInput: "full",
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
                });
            }
        });
    </script>

</body>

</html>