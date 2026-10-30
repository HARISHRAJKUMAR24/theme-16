<?php
include_once __DIR__ . "/includes/files_includes.php";

$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#64748b';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#475569';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wine: '<?= htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8') ?>',
                        cream: '#f7f2ee',
                        gold: '<?= htmlspecialchars($hover_color, ENT_QUOTES, 'UTF-8') ?>'
                    },
                    fontFamily: {
                        serifDisplay: ['Georgia', 'serif'],
                        clean: ['Arial', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />

    <style>
        :root {
            --primary-color: <?= htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8') ?>;
            --hover-color: <?= htmlspecialchars($hover_color, ENT_QUOTES, 'UTF-8') ?>;
            --theme17-ink: #171717;
            --theme17-muted: #737373;
            --theme17-line: #e5e5e5;
        }

        .iti,
        .iti input {
            width: 100% !important;
        }

        .theme17-btn {
            background: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: #fff;
        }

        .theme17-btn:hover {
            background: var(--hover-color);
            border-color: var(--hover-color);
            color: #fff;
        }

        .theme17-field {
            width: 100%;
            min-height: 52px;
            border: 1px solid #d4d4d4;
            background: #fff;
            padding: 14px 15px;
            color: #171717;
            outline: none;
            border-radius: 0;
            transition: border-color .2s, box-shadow .2s;
        }

        .theme17-field:focus {
            border-color: var(--primary-color);
            box-shadow: inset 3px 0 0 var(--primary-color);
        }

        .theme17-label {
            display: block;
            margin-bottom: 9px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #525252;
        }

        .theme17-auth-grid {
            display: grid;
            grid-template-columns: minmax(0, .92fr) minmax(0, 1.08fr);
            min-height: 520px;
        }

        .theme17-auth-aside {
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, var(--primary-color) 0%, var(--hover-color) 100%);
            color: #fff;
            padding: clamp(30px, 5vw, 68px);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .theme17-auth-aside:after {
            content: '17';
            position: absolute;
            right: -12px;
            bottom: -75px;
            font-size: clamp(190px, 26vw, 340px);
            line-height: 1;
            font-weight: 900;
            color: rgba(255, 255, 255, .045);
            pointer-events: none;
        }

        .theme17-auth-content {
            position: relative;
            z-index: 1;
        }

        .theme17-auth-form {
            background: #fff;
            padding: clamp(28px, 5vw, 64px);
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .theme17-kicker {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
        }

        .theme17-kicker:before {
            content: '';
            display: inline-block;
            width: 28px;
            height: 2px;
            background: rgba(255, 255, 255, .85);
        }

        .theme17-number {
            color: var(--primary-color);
            font-size: 12px;
            letter-spacing: .18em;
            font-weight: 800;
        }

        @media(max-width:767px) {
            .theme17-auth-grid {
                grid-template-columns: 1fr;
            }

            .theme17-auth-aside {
                min-height: 220px;
                padding: 28px 24px;
            }

            .theme17-auth-aside:after {
                font-size: 190px;
                bottom: -58px;
            }

            .theme17-auth-form {
                padding: 30px 24px;
            }
        }
    </style>
</head>

<body class="bg-[#f5f5f5] font-sans min-h-screen text-neutral-900">



    <header class="bg-white z-40">
        <div class="relative h-[108px] md:h-[118px] flex items-center justify-center px-6">

            <div class="text-center select-none">
                <div class="w-[70px] h-[70px] mx-auto rounded-full flex items-center justify-center">
                    <div class="text-wine text-5xl leading-none">❧</div>
                </div>
                <div class="font-serifDisplay text-[14px] text-wine -mt-2 tracking-tight">SHOBITAM</div>
            </div>

        </div>


        <!-- Mobile navigation — LEFT DRAWER TRIGGER -->
        <div class="md:hidden">
            <button id="menuBtn"
                class="w-full flex justify-between items-center px-6 py-4 text-sm border-t border-gray-100">
                <span></span><span id="menuIcon">☰</span>
            </button>
        </div>
    </header>

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Theme 17 Forgot Password Section -->
    <main class="px-4 py-8 sm:px-6 sm:py-12 lg:py-16">
        <div class="mx-auto max-w-5xl overflow-hidden border border-neutral-200 bg-white">
            <div class="theme17-auth-grid">
                <aside class="theme17-auth-aside">
                    <div class="theme17-auth-content">
                        <div class="theme17-kicker text-neutral-300">Account assistance</div>
                        <p class="mt-8 text-xs font-bold uppercase tracking-[.22em] text-neutral-400">SECURE ACCESS / 01</p>
                        <h1 class="mt-4 max-w-sm text-4xl font-black leading-[1.08] tracking-tight sm:text-5xl">A fresh start<br>for your account.</h1>
                        <p class="mt-5 max-w-sm text-sm leading-7 text-neutral-300">Reset your password securely and get back to shopping with confidence.</p>
                    </div>
                    <div class="theme17-auth-content mt-10 flex items-center gap-3 border-t border-white/15 pt-5">
                        <span class="flex h-9 w-9 items-center justify-center border border-white/25 text-sm" aria-hidden="true">✓</span>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider">Secure password reset</p>
                            <p class="mt-1 text-xs text-neutral-400">A simple, protected recovery process.</p>
                        </div>
                    </div>
                </aside>

                <section class="theme17-auth-form">
                    <div class="mb-8">
                        <div class="mb-5 flex items-center gap-3">
                            <?php if (getSettings("logo")) : ?>
                                <img src="<?= UPLOADS_URL . getSettings("logo") ?>" alt="Logo" class="max-h-12 w-auto object-contain">
                            <?php else : ?>
                                <div class="flex h-12 w-12 items-center justify-center text-lg font-black uppercase text-white" style="background: <?= htmlspecialchars($primary_color) ?>;">
                                    <?= htmlspecialchars(substr($storeName, 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div class="min-w-0">
                                <p class="text-xs font-bold uppercase tracking-[.16em] text-neutral-500">Welcome to</p>
                                <p class="mt-1 truncate text-sm font-bold text-neutral-900"><?= htmlspecialchars($storeName) ?></p>
                            </div>
                        </div>
                        <p class="theme17-number">PASSWORD RECOVERY</p>
                        <h2 class="mt-3 text-3xl font-black tracking-tight sm:text-4xl">Forgot password?</h2>
                        <p class="mt-3 max-w-md text-sm leading-6 text-neutral-500">
                            <?php if ((int)getSeller("credits") <= 0) { ?>Enter your registered email address to reset your password.<?php } else { ?>Enter your registered phone number to reset your password.<?php } ?>
                        </p>
                    </div>

                    <form id="form" class="space-y-5" autocomplete="off">
                        <div id="step1">
                            <?php if ((int)getSeller("credits") <= 0) { ?>
                                <div class="mb-5">
                                    <label for="email" class="theme17-label">Email Address</label>
                                    <input type="email" name="user" id="email" placeholder="you@example.com" class="theme17-field" autocomplete="email">
                                </div>
                            <?php } else { ?>
                                <div class="mb-5">
                                    <label for="phone" class="theme17-label">Phone Number</label>
                                    <input type="tel" name="user" id="phone" placeholder="Enter your phone number" class="theme17-field" autocomplete="tel">
                                </div>
                            <?php } ?>
                            <button id="sendOtp" type="button" class="theme17-btn flex min-h-[52px] w-full items-center justify-center gap-3 px-5 text-xs font-bold uppercase tracking-[.18em] transition-colors duration-200">
                                Send OTP <span aria-hidden="true">→</span>
                            </button>
                        </div>

                        <div id="step2" style="display:none;">
                            <div id="msg" class="mb-5 border-l-4 p-4 text-sm" style="background: color-mix(in srgb, <?= htmlspecialchars($primary_color) ?> 8%, white); border-color: <?= htmlspecialchars($primary_color) ?>; color: <?= htmlspecialchars($primary_color) ?>;"></div>
                            <div class="mb-5"><label for="otp" class="theme17-label">OTP Code</label><input type="number" name="otp" id="otp" placeholder="Enter OTP code" class="theme17-field" inputmode="numeric" autocomplete="one-time-code"></div>
                            <div class="mb-5"><label for="new_password" class="theme17-label">New Password</label><input type="password" name="new_password" id="new_password" placeholder="Create a new password" class="theme17-field" autocomplete="new-password"></div>
                            <div class="mb-5"><label for="confirm_password" class="theme17-label">Confirm Password</label><input type="password" name="confirm_password" id="confirm_password" placeholder="Re-enter new password" class="theme17-field" autocomplete="new-password"></div>
                            <button id="change" type="button" class="theme17-btn flex min-h-[52px] w-full items-center justify-center gap-3 px-5 text-xs font-bold uppercase tracking-[.16em] transition-colors duration-200">Change Password <span aria-hidden="true">→</span></button>
                        </div>
                    </form>

                    <div class="mt-8 border-t border-neutral-200 pt-5 text-sm text-neutral-500">
                        Remembered your password?
                        <a href="<?= $storeUrl ?>login" class="ml-1 font-bold underline decoration-1 underline-offset-4" style="color: <?= htmlspecialchars($primary_color) ?>;">Back to login</a>
                    </div>
                </section>
            </div>
        </div>
        <p class="mx-auto mt-5 max-w-5xl text-center text-[10px] font-semibold uppercase tracking-[.18em] text-neutral-400">Account security · Password recovery</p>
    </main>
    <!-- Theme 17 Forgot Password Section End -->

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- Intl Tel Input JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>
        // Initialize intl-tel-input only if phone field exists
        const phoneInputField = document.querySelector("#phone");
        if (phoneInputField) {
            const phoneInput = window.intlTelInput(phoneInputField, {
                initialCountry: "in",
                separateDialCode: true,
                onlyCountries: ['IN'],
                preferredCountries: ["<?= getSettings("country") ?>"],
                hiddenInput: "full",
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });
        }
    </script>

    <script src="<?= APP_URL ?>shop/javascripts/forgot-password.js"></script>

</body>

</html>