
<?php
include_once __DIR__ . "/includes/files_includes.php";

$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#64748b';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#475569';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>

    <style>
        :root {
            --primary: <?= htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8') ?>;
            --hover-color: <?= htmlspecialchars($hover_color, ENT_QUOTES, 'UTF-8') ?>;
        }

        /* ========================================
           THEME 17 - LOGIN PAGE
        ======================================== */

        .iti {
            width: 100% !important;
        }

        .t17-login-page {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 16px;
            background: #f8f8fa;
        }

        .t17-login-card {
            width: 100%;
            max-width: 1040px;
            min-height: 560px;
            display: grid;
            grid-template-columns: .9fr 1.1fr;
            overflow: hidden;
            background: #fff;
            border: 1px solid #eeeeF2;
            border-radius: 24px;
            box-shadow: 0 15px 50px rgba(24, 24, 27, .07);
        }

        /* Left promotional panel */

        .t17-login-banner {
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 42px 36px;
            overflow: hidden;
            background: linear-gradient(
                145deg,
                color-mix(in srgb, var(--primary) 12%, white) 0%,
                color-mix(in srgb, var(--primary) 6%, white) 50%,
                color-mix(in srgb, var(--hover-color) 10%, white) 100%
            );
        }

        .t17-login-banner::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            right: -100px;
            bottom: -100px;
            border-radius: 50%;
            background: color-mix(in srgb, var(--primary) 10%, transparent);
        }

        .t17-login-banner::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            right: -40px;
            bottom: -30px;
            border: 1px solid color-mix(in srgb, var(--primary) 18%, transparent);
            border-radius: 50%;
        }

        .t17-login-banner-content,
        .t17-login-banner-footer {
            position: relative;
            z-index: 1;
        }

        .t17-login-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 13px;
            border: 1px solid color-mix(in srgb, var(--primary) 15%, transparent);
            border-radius: 999px;
            background: rgba(255, 255, 255, .7);
            color: var(--hover-color);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .t17-login-banner-title {
            margin-top: 26px;
            color: #27272a;
            font-size: clamp(30px, 3vw, 43px);
            line-height: 1.15;
            font-weight: 800;
            letter-spacing: -1.5px;
        }

        .t17-login-banner-title span {
            color: var(--primary);
        }

        .t17-login-banner-description {
            max-width: 310px;
            margin-top: 17px;
            color: #71717a;
            font-size: 14px;
            line-height: 1.8;
        }

        .t17-login-benefits {
            display: grid;
            gap: 15px;
            margin-top: 35px;
        }

        .t17-login-benefit {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #3f3f46;
            font-size: 13px;
            font-weight: 600;
        }

        .t17-login-benefit-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #fff;
            color: var(--primary);
            box-shadow: 0 3px 12px color-mix(in srgb, var(--primary) 8%, transparent);
        }

        .t17-login-banner-footer {
            margin-top: 35px;
            color: #71717a;
            font-size: 12px;
        }

        /* Login form */

        .t17-login-form-panel {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 44px clamp(24px, 5vw, 58px);
        }

        .t17-login-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            margin-bottom: 22px;
        }

        .t17-login-logo img {
            display: block;
            max-width: 155px;
            max-height: 52px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .t17-login-heading {
            margin: 0;
            color: #18181b;
            text-align: center;
            font-size: 29px;
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .t17-login-subheading {
            margin-top: 9px;
            margin-bottom: 30px;
            color: #71717a;
            text-align: center;
            font-size: 13px;
            line-height: 1.7;
        }

        .t17-login-label {
            display: block;
            margin-bottom: 8px;
            color: #3f3f46;
            font-size: 12px;
            font-weight: 700;
        }

        .t17-login-input {
            display: block;
            width: 100%;
            height: 48px;
            padding: 0 14px;
            border: 1px solid #e4e4e7;
            border-radius: 11px;
            outline: none;
            background: #fff;
            color: #18181b;
            font-size: 13px;
            transition: border-color .2s, box-shadow .2s;
        }

        .t17-login-input::placeholder {
            color: #a1a1aa;
        }

        .t17-login-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 10%, transparent);
        }

        .t17-login-group {
            margin-bottom: 21px;
        }

        .t17-login-password-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 8px;
        }

        .t17-login-password-heading label {
            margin-bottom: 0;
        }

        .t17-login-forgot {
            color: var(--primary);
            font-size: 11px;
            font-weight: 700;
            text-decoration: none;
        }

        .t17-login-forgot:hover {
            color: var(--hover-color);
            text-decoration: underline;
        }

        /* Phone input */

        .t17-login-form-panel .iti {
            display: block;
        }

        .t17-login-form-panel .iti input[type="tel"] {
            width: 100%;
            height: 48px;
            padding-top: 0;
            padding-bottom: 0;
            border: 1px solid #e4e4e7;
            border-radius: 11px;
            outline: none;
            font-size: 13px;
        }

        .t17-login-form-panel .iti input[type="tel"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 10%, transparent);
        }

        .t17-login-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            width: 100%;
            min-height: 49px;
            margin-top: 5px;
            padding: 12px 18px;
            border: none;
            border-radius: 11px;
            background: var(--primary);
            color: #fff;
            font-size: 13px;
            font-weight: 800;
            letter-spacing: .3px;
            cursor: pointer;
            transition: background .2s, transform .2s, box-shadow .2s;
        }

        .t17-login-button:hover {
            background: var(--hover-color);
            box-shadow: 0 6px 18px color-mix(in srgb, var(--primary) 18%, transparent);
        }

        .t17-login-button:active {
            transform: scale(.99);
        }

        .t17-login-register {
            margin-top: 28px;
            color: #71717a;
            text-align: center;
            font-size: 12px;
        }

        .t17-login-register a {
            color: var(--primary);
            font-weight: 800;
            text-decoration: none;
        }

        .t17-login-register a:hover {
            text-decoration: underline;
        }

        .t17-login-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 24px;
            color: #a1a1aa;
            font-size: 10px;
        }

        .t17-login-divider::before,
        .t17-login-divider::after {
            content: "";
            height: 1px;
            flex: 1;
            background: #eeeeF2;
        }

        .t17-login-security {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-top: 15px;
            color: #a1a1aa;
            font-size: 10px;
        }

        /* Responsive */

        @media (max-width: 767px) {
            .t17-login-page {
                padding: 24px 12px 32px;
            }

            .t17-login-card {
                max-width: 480px;
                min-height: auto;
                grid-template-columns: 1fr;
                border-radius: 20px;
            }

            .t17-login-banner {
                padding: 23px 24px;
            }

            .t17-login-banner-title {
                margin-top: 18px;
                font-size: 27px;
                letter-spacing: -.8px;
            }

            .t17-login-banner-description {
                margin-top: 10px;
                font-size: 12px;
            }

            .t17-login-benefits {
                gap: 10px;
                margin-top: 20px;
            }

            .t17-login-benefit-icon {
                width: 29px;
                height: 29px;
            }

            .t17-login-banner-footer {
                margin-top: 20px;
            }

            .t17-login-form-panel {
                padding: 30px 22px;
            }

            .t17-login-logo {
                margin-bottom: 15px;
            }

            .t17-login-heading {
                font-size: 26px;
            }

            .t17-login-subheading {
                margin-bottom: 24px;
            }
        }

        @media (max-width: 360px) {
            .t17-login-form-panel {
                padding: 26px 16px;
            }

            .t17-login-banner {
                padding: 21px 18px;
            }

            .t17-login-banner-title {
                font-size: 25px;
            }

            .t17-login-password-heading {
                align-items: flex-start;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .t17-login-input,
            .t17-login-button {
                transition: none;
            }
        }
    </style>
</head>

<body class="font-sans flex flex-col min-h-screen bg-white">

    <!-- Navbar -->
    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Theme 17 Login Section -->
    <main class="t17-login-page">

        <div class="t17-login-card">

            <!-- Left Promotional Panel -->
            <aside class="t17-login-banner">

                <div class="t17-login-banner-content">

                    <div class="t17-login-badge">
                        <span>✦</span>
                        Welcome Back
                    </div>

                    <h1 class="t17-login-banner-title">
                        Good to see<br>
                        you <span>again.</span>
                    </h1>

                    <p class="t17-login-banner-description">
                        Sign in to continue your shopping journey.
                        Your favourite products and a convenient shopping
                        experience are just a step away.
                    </p>

                    <div class="t17-login-benefits">

                        <div class="t17-login-benefit">
                            <span class="t17-login-benefit-icon">✓</span>
                            <span>Quick and easy account access</span>
                        </div>

                        <div class="t17-login-benefit">
                            <span class="t17-login-benefit-icon">♡</span>
                            <span>Continue your shopping journey</span>
                        </div>

                        <div class="t17-login-benefit">
                            <span class="t17-login-benefit-icon">↗</span>
                            <span>Your shopping, all in one place</span>
                        </div>

                    </div>

                </div>

                <div class="t17-login-banner-footer">
                    Welcome back to <?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?>.
                </div>

            </aside>

            <!-- Login Form Panel -->
            <section class="t17-login-form-panel">

                <!-- Store Logo -->
                <div class="t17-login-logo">

                    <?php if (getSettings("logo")) : ?>

                        <img
                            src="<?= htmlspecialchars(UPLOADS_URL . getSettings("logo"), ENT_QUOTES, 'UTF-8') ?>"
                            alt="<?= htmlspecialchars($storeName, ENT_QUOTES, 'UTF-8') ?> logo">

                    <?php else : ?>

                        <div class="flex items-center justify-center w-12 h-12 rounded-2xl text-xl font-extrabold" style="background: color-mix(in srgb, var(--primary) 12%, white); color: var(--primary);">
                            <?= htmlspecialchars(substr($storeName, 0, 1), ENT_QUOTES, 'UTF-8') ?>
                        </div>

                    <?php endif; ?>

                </div>

                <h2 class="t17-login-heading">
                    Login to your account
                </h2>

                <p class="t17-login-subheading">
                    Enter your details to continue.
                </p>

                <!-- Existing Login Form -->
                <form id="form" class="space-y-0">

                    <div id="step1">

                        <!-- Email Field -->
                        <div class="t17-login-group" id="emailWrapper" style="display:none;">

                            <label for="email" class="t17-login-label">
                                Email or phone
                            </label>

                            <input
                                type="email"
                                name="email"
                                id="email"
                                placeholder="Enter your email or phone"
                                autocomplete="username"
                                class="t17-login-input email-and-phone">

                        </div>

                        <!-- Phone Field -->
                        <div class="t17-login-group" id="phoneWrapper">

                            <label for="phone" class="t17-login-label">
                                Phone or email
                            </label>

                            <input
                                type="tel"
                                name="phone[main]"
                                id="phone"
                                placeholder="Enter your phone or email"
                                autocomplete="username"
                                class="t17-login-input email-and-phone">

                        </div>

                        <!-- Password -->
                        <div class="t17-login-group">

                            <div class="t17-login-password-heading">

                                <label for="password" class="t17-login-label">
                                    Password
                                </label>

                                <a
                                    href="<?= htmlspecialchars($storeUrl, ENT_QUOTES, 'UTF-8') ?>forgot-password"
                                    class="t17-login-forgot">
                                    Forgot password?
                                </a>

                            </div>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                                class="t17-login-input">

                        </div>

                        <!-- Login Button -->
                        <button
                            type="button"
                            id="sendOtp"
                            class="t17-login-button login">

                            <span>LOGIN TO YOUR ACCOUNT</span>
                            <span aria-hidden="true">→</span>

                        </button>

                    </div>

                </form>

                <div class="t17-login-divider">
                    SECURE ACCOUNT ACCESS
                </div>

                <div class="t17-login-security">
                    <span aria-hidden="true">🔒</span>
                    Your account details are protected.
                </div>

                <!-- Register Link -->
                <p class="t17-login-register">
                    Don't have an account?

                    <a href="<?= htmlspecialchars($storeUrl, ENT_QUOTES, 'UTF-8') ?>register<?= isset($_GET['step']) ? '?step' : '' ?>">
                        Create an account
                    </a>
                </p>

            </section>

        </div>

    </main>

    <!-- Footer -->
    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- Existing Phone Number Configuration -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
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

    <!-- Existing Login JavaScript -->
    <script src="<?= APP_URL ?>shop/javascripts/login.js"></script>

</body>
</html>
