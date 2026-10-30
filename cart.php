<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        /* ===== Theme 17 cart page ===== */
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#f45d79') ?>;
            --hover-color:   <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#e84caa') ?>;
        }

        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; appearance: textfield; }

        /* Theme 17 fonts / spacing */
        .t17-cart {
            font-family: inherit;
            color: #171717;
        }

        /* Centered title with pink underline (matches "Shopping Cart" in Theme 17) */
        .t17-cart-title {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #171717;
            margin: 0;
        }
        .t17-cart-title-underline {
            width: 34px;
            height: 2px;
            margin: 14px auto 0;
            background: var(--primary-color);
        }

        /* Section labels */
        .t17-eyebrow {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #a3a3a3;
        }

        /* Cart row card – soft editorial look */
        .t17-row {
            display: grid;
            grid-template-columns: 96px 1fr;
            gap: 18px;
            padding: 22px 0;
            border-bottom: 1px solid #eee;
            align-items: flex-start;
        }
        @media (min-width: 640px) {
            .t17-row {
                grid-template-columns: 110px 1fr 110px 120px 120px 32px;
                align-items: center;
            }
        }

        .t17-row-img {
            width: 96px; height: 120px;
            background: #f4f4f4;
            overflow: hidden;
            border-radius: 4px;
        }
        .t17-row-img img {
            width: 100%; height: 100%;
            object-fit: cover; display: block;
        }
        .t17-row-img.is-oos img {
            opacity: .45; filter: grayscale(1);
        }

        .t17-row-name {
            font-size: 13px;
            font-weight: 500;
            line-height: 1.5;
            color: #171717;
            text-decoration: none;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .t17-row-name:hover { color: var(--hover-color); }

        .t17-row-variant {
            margin-top: 4px;
            font-size: 11px;
            color: #737373;
        }

        .t17-stock-in   { color: #16a34a; font-size: 11px; margin-top: 4px; }
        .t17-stock-out  { color: #ef4444; font-size: 11px; margin-top: 4px; font-weight: 600; }

        .t17-row-price,
        .t17-row-total {
            font-size: 13px;
            color: #171717;
        }
        .t17-row-total { font-weight: 600; }

        /* qty stepper – pink outline style */
        .t17-qty {
            display: inline-flex;
            height: 36px;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            overflow: hidden;
            background: #fff;
        }
        .t17-qty button {
            width: 34px;
            border: 0;
            background: #fff;
            color: #171717;
            font-size: 15px;
            cursor: pointer;
            transition: background .15s, color .15s;
        }
        .t17-qty button:hover {
            background: var(--primary-color);
            color: #fff;
        }
        .t17-qty input {
            width: 42px;
            border: 0;
            text-align: center;
            font-size: 13px;
            font-weight: 600;
            color: #171717;
            background: #fff;
            outline: none;
        }

        /* Remove button */
        .t17-remove {
            background: none;
            border: 0;
            color: #a3a3a3;
            cursor: pointer;
            padding: 4px;
            transition: color .15s;
        }
        .t17-remove:hover { color: #ef4444; }

        /* Mobile labels */
        .t17-mobile-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #a3a3a3;
        }

        /* Summary card – matching Theme 17 sidebar */
        .t17-summary {
            border: 1px solid #e5e5e5;
            background: #fafafa;
            padding: 26px 22px;
            border-radius: 4px;
        }
        .t17-summary h2 {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #171717;
            padding-bottom: 14px;
            border-bottom: 1px solid #e5e5e5;
            margin: 0 0 18px;
        }
        .t17-summary-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #525252;
            margin-bottom: 12px;
        }
        .t17-summary-row b { color: #171717; font-weight: 600; }
        .t17-summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e5e5e5;
            padding-top: 16px;
            margin-top: 16px;
            font-size: 15px;
            font-weight: 700;
            text-transform: uppercase;
            color: #171717;
        }
        .t17-summary-total span:last-child {
            color: var(--primary-color);
            font-size: 17px;
        }

        /* Checkout – pink outline CTA (Theme 17 style) */
        .t17-checkout-btn {
            margin-top: 20px;
            width: 100%;
            height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #171717;
            border: 1px solid var(--primary-color);
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            cursor: pointer;
            transition: background .2s, color .2s;
            border-radius: 3px;
        }
        .t17-checkout-btn:hover:not(:disabled) {
            background: var(--primary-color);
            color: #fff;
        }
        .t17-checkout-btn:disabled {
            background: #f3f3f3;
            color: #9ca3af;
            border-color: #e5e5e5;
            cursor: not-allowed;
        }

        .t17-trust {
            margin-top: 20px;
            padding-top: 18px;
            border-top: 1px solid #e5e5e5;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 11px;
            color: #737373;
        }
        .t17-trust div { display: flex; align-items: center; gap: 8px; }
        .t17-trust svg { color: var(--primary-color); flex-shrink: 0; }

        /* Empty state */
        .t17-empty {
            text-align: center;
            padding: 80px 20px;
        }
        .t17-empty svg { color: #d4d4d4; margin: 0 auto 16px; }
        .t17-empty p { font-size: 14px; color: #737373; margin-bottom: 24px; }
        .t17-empty a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: var(--primary-color);
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            transition: opacity .2s;
        }
        .t17-empty a:hover { opacity: .9; }

        /* Continue shopping link */
        .t17-continue {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 26px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #525252;
            text-decoration: none;
            transition: color .2s;
        }
        .t17-continue:hover { color: var(--primary-color); }

        /* Toast */
        @keyframes slideInRight  { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
        .toast-slide-in  { animation: slideInRight .4s ease-out forwards; }
        .toast-slide-out { animation: slideOutRight .4s ease-in forwards; }
    </style>
</head>

<body class="bg-white font-sans text-gray-800 antialiased min-h-screen">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ================= CART PAGE ================= -->
    <main class="mx-auto max-w-[1280px] px-4 py-10 sm:px-6 sm:py-14 lg:px-8">

        <!-- PAGE TITLE (Theme 17 style) -->
        <div class="mb-10 sm:mb-14">
            <h1 class="t17-cart-title">Shopping Cart</h1>
            <div class="t17-cart-title-underline"></div>
        </div>

        <!-- AJAX CONTAINER -->
        <div id="content" class="t17-cart min-h-[400px]">
            <div class="flex justify-center items-center py-20">
                <svg class="animate-spin h-8 w-8" style="color: var(--primary-color);" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" stroke-opacity=".2"/>
                    <path d="M22 12a10 10 0 0 0-10-10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>

    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- Cart JS -->
    <script src="<?= APP_URL ?>shop/javascripts/cart.js"></script>

    <!-- Auto-load AJAX cart -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof loadCart === 'function') {
                loadCart();
            } else if (window.jQuery) {
                jQuery('#content').load('shop/ajax/cart.php');
            }
        });
    </script>
</body>
</html>