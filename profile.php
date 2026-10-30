<?php
include_once __DIR__ . "/includes/files_includes.php";

$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#64748b';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#475569';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
    
    <style>
        :root {
            --primary: <?= htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8') ?>;
            --hover-color: <?= htmlspecialchars($hover_color, ENT_QUOTES, 'UTF-8') ?>;
        }


        /* Store-configured accent colors */
        .t17-profile-sidebar label[for="photo"] {
            background: var(--primary) !important;
        }
        .t17-profile-sidebar label[for="photo"]:hover {
            background: var(--hover-color) !important;
        }
        .t17-profile-sidebar .space-y-4 > a:hover,
        .t17-profile-sidebar .space-y-4 > a.bg-indigo-100,
        .t17-profile-sidebar .space-y-4 > a.bg-orange-100,
        .t17-profile-sidebar .space-y-4 > a.bg-purple-100 {
            border-color: color-mix(in srgb, var(--primary) 28%, white) !important;
        }

        /* Custom Toast Styles */
        .custom-product-toast {
            z-index: 9999;
        }


        /* ==========================================
           THEME 17 - CUSTOMER PROFILE
           Visual styling only; existing PHP/AJAX retained
        ========================================== */
        body.bg-pink-50 {
            background: #f8f8fa !important;
            color: #27272a;
        }

        .t17-profile-container {
            width: 100%;
            max-width: 1320px;
            margin: 0 auto;
            padding: 34px 24px 48px;
        }

        .t17-profile-title {
            margin: 0 0 26px !important;
            color: #18181b !important;
            font-size: clamp(26px, 3vw, 34px) !important;
            font-weight: 800 !important;
            letter-spacing: -1px;
            text-align: left !important;
        }

        .t17-profile-layout {
            display: grid !important;
            grid-template-columns: minmax(250px, .72fr) minmax(0, 1.65fr);
            align-items: start;
            gap: 24px !important;
        }

        .t17-profile-sidebar,
        .t17-profile-editor {
            width: 100% !important;
            min-width: 0;
        }

        .t17-profile-sidebar > div,
        .t17-profile-editor > div {
            background: #fff !important;
            border: 1px solid #eeeef2 !important;
            border-radius: 20px !important;
            box-shadow: 0 10px 35px rgba(24, 24, 27, .045) !important;
            backdrop-filter: none !important;
        }

        .t17-profile-sidebar > div {
            padding: 26px !important;
        }

        .t17-profile-editor > div {
            padding: clamp(22px, 3vw, 36px) !important;
        }

        .t17-profile-sidebar #previewImage {
            width: 96px !important;
            height: 96px !important;
            border: 4px solid color-mix(in srgb, var(--primary) 18%, white) !important;
            box-shadow: 0 5px 18px color-mix(in srgb, var(--primary) 12%, transparent);
        }

        .t17-profile-sidebar h2 {
            color: #18181b !important;
            font-size: 19px !important;
            font-weight: 800 !important;
        }

        .t17-profile-sidebar p {
            color: #71717a !important;
            overflow-wrap: anywhere;
        }

        .t17-profile-sidebar .space-y-4 {
            display: grid;
            gap: 9px;
        }

        .t17-profile-sidebar .space-y-4 > a {
            min-height: 58px;
            padding: 9px 12px !important;
            border: 1px solid #f0f0f3;
            border-radius: 12px !important;
            background: #fafafa !important;
            color: #52525b !important;
            font-size: 13px;
            font-weight: 600;
            transition: background .2s, border-color .2s, color .2s;
        }

        .t17-profile-sidebar .space-y-4 > a:hover,
        .t17-profile-sidebar .space-y-4 > a.bg-indigo-100,
        .t17-profile-sidebar .space-y-4 > a.bg-orange-100,
        .t17-profile-sidebar .space-y-4 > a.bg-purple-100 {
            background: color-mix(in srgb, var(--primary) 8%, white) !important;
            border-color: color-mix(in srgb, var(--primary) 28%, white) !important;
            color: var(--primary) !important;
        }

        .t17-profile-sidebar .space-y-4 > a > span:first-child {
            width: 38px !important;
            height: 38px !important;
            flex: 0 0 38px;
            border: 1px solid #f4f4f5 !important;
            border-radius: 10px !important;
            background: #fff !important;
            color: var(--primary);
            font-size: 19px !important;
        }

        .t17-profile-editor h3 {
            margin-bottom: 24px !important;
            color: #18181b !important;
            font-size: 23px !important;
            font-weight: 800 !important;
            letter-spacing: -.5px;
            text-align: left !important;
        }

        .t17-profile-editor form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px 18px !important;
        }

        .t17-profile-editor form > div {
            min-width: 0;
            margin: 0;
        }

        .t17-profile-editor form label.block {
            display: block;
            margin-bottom: 8px !important;
            color: #3f3f46 !important;
            font-size: 12px;
            font-weight: 700 !important;
        }

        .t17-profile-editor form input[type="text"],
        .t17-profile-editor form input[type="email"],
        .t17-profile-editor form input[type="tel"] {
            width: 100%;
            min-width: 0;
            height: 46px;
            padding: 0 13px;
            border: 1px solid #e4e4e7 !important;
            border-radius: 11px !important;
            background: #fff !important;
            color: #18181b;
            font-size: 13px;
            outline: none;
            box-shadow: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .t17-profile-editor form input:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary) 12%, transparent) !important;
        }

        .t17-profile-editor form input::placeholder {
            color: #a1a1aa;
        }

        .t17-profile-editor .iti {
            display: block;
            width: 100% !important;
        }

        .t17-profile-editor .iti input[type="tel"] {
            padding-top: 0;
            padding-bottom: 0;
        }

        .t17-profile-editor form label.flex {
            min-height: 46px;
            border: 1px solid #e4e4e7 !important;
            border-radius: 11px !important;
            background: #fafafa !important;
            transition: background .2s, border-color .2s;
        }

        .t17-profile-editor form label.flex:hover {
            border-color: color-mix(in srgb, var(--primary) 45%, white) !important;
            background: color-mix(in srgb, var(--primary) 8%, white) !important;
        }

        .t17-profile-editor #oldImage {
            display: block;
            margin-top: 12px !important;
            border: 1px solid #eeeef2 !important;
            border-radius: 12px !important;
        }

        .t17-profile-editor form > div:last-child {
            grid-column: 1 / -1;
            display: flex;
            justify-content: flex-end;
            padding-top: 4px;
        }

        .t17-profile-editor button[type="submit"] {
            min-height: 45px;
            padding: 11px 22px !important;
            border: 0;
            border-radius: 11px !important;
            background: var(--primary, #db2777) !important;
            color: #fff !important;
            font-size: 13px;
            font-weight: 800 !important;
            box-shadow: 0 5px 14px rgba(219, 39, 119, .13) !important;
            transition: filter .2s, transform .2s;
        }

        .t17-profile-editor button[type="submit"]:hover {
            filter: brightness(.93);
            transform: translateY(-1px);
        }

        .t17-profile-editor button[type="submit"]:disabled {
            opacity: .65;
            cursor: not-allowed;
            transform: none;
        }

        @media (max-width: 900px) {
            .t17-profile-layout {
                grid-template-columns: minmax(220px, .8fr) minmax(0, 1.4fr);
                gap: 16px !important;
            }

            .t17-profile-sidebar > div {
                padding: 20px !important;
            }
        }

        @media (max-width: 767px) {
            .t17-profile-container {
                padding: 24px 14px 34px;
            }

            .t17-profile-title {
                margin-bottom: 20px !important;
                text-align: center !important;
            }

            .t17-profile-layout {
                grid-template-columns: minmax(0, 1fr);
                gap: 18px !important;
            }

            .t17-profile-sidebar > div,
            .t17-profile-editor > div {
                border-radius: 17px !important;
                padding: 21px !important;
            }

            .t17-profile-sidebar .space-y-4 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .t17-profile-sidebar .space-y-4 > a {
                min-width: 0;
                gap: 8px;
                padding: 9px !important;
                font-size: 12px;
            }

            .t17-profile-sidebar .space-y-4 > a > span:first-child {
                width: 32px !important;
                height: 32px !important;
                flex-basis: 32px;
            }

            .t17-profile-editor h3 {
                font-size: 21px !important;
            }

            .t17-profile-editor form {
                grid-template-columns: minmax(0, 1fr);
                gap: 17px !important;
            }

            .t17-profile-editor form > div:last-child {
                grid-column: auto;
            }

            .t17-profile-editor button[type="submit"] {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 380px) {
            .t17-profile-sidebar .space-y-4 {
                grid-template-columns: minmax(0, 1fr);
            }
        }



        /* Theme 17 flat style: remove shadows and rounded corners */
        .t17-profile-sidebar > div,
        .t17-profile-editor > div,
        .t17-profile-sidebar #previewImage,
        .t17-profile-sidebar .space-y-4 > a,
        .t17-profile-sidebar .space-y-4 > a > span:first-child,
        .t17-profile-editor form input[type="text"],
        .t17-profile-editor form input[type="email"],
        .t17-profile-editor form input[type="tel"],
        .t17-profile-editor form label.flex,
        .t17-profile-editor #oldImage,
        .t17-profile-editor button[type="submit"] {
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .t17-profile-sidebar > div,
        .t17-profile-editor > div {
            box-shadow: none !important;
        }

        .t17-profile-sidebar #previewImage {
            border-radius: 50% !important;
            box-shadow: none !important;
        }



        /* Theme 17 - distinct editorial layout, flat surfaces, precise alignment */
        body.bg-pink-50 {
            background: #f4f4f5 !important;
            color: #18181b !important;
        }

        .t17-profile-container {
            max-width: 1240px !important;
            padding: 36px 24px 56px !important;
        }

        .t17-profile-title {
            font-size: clamp(28px, 3vw, 36px) !important;
            font-weight: 800 !important;
            letter-spacing: -1.1px !important;
            text-align: left !important;
            margin-bottom: 28px !important;
            padding-bottom: 18px;
            border-bottom: 1px solid #d4d4d8;
        }

        .t17-profile-layout {
            display: grid !important;
            grid-template-columns: minmax(245px, 290px) minmax(0, 1fr) !important;
            align-items: stretch !important;
            gap: 28px !important;
        }

        .t17-profile-sidebar > div {
            background: #18181b !important;
            border: 0 !important;
            border-top: 5px solid var(--primary, #db2777) !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            padding: 28px 22px !important;
            height: 100%;
        }

        .t17-profile-sidebar #previewImage {
            width: 88px !important;
            height: 88px !important;
            border: 3px solid #52525b !important;
            border-radius: 50% !important;
            box-shadow: none !important;
        }

        .t17-profile-sidebar h2 {
            color: #fafafa !important;
            font-size: 18px !important;
            font-weight: 750 !important;
            text-align: center;
            overflow-wrap: anywhere;
        }

        .t17-profile-sidebar p {
            color: #a1a1aa !important;
            text-align: center;
            font-size: 12px !important;
        }

        .t17-profile-sidebar .space-y-4 {
            display: flex !important;
            flex-direction: column !important;
            gap: 0 !important;
            margin-top: 28px;
        }

        .t17-profile-sidebar .space-y-4 > a {
            display: flex !important;
            width: 100%;
            min-height: 54px !important;
            padding: 8px 10px !important;
            gap: 12px !important;
            background: transparent !important;
            color: #d4d4d8 !important;
            border: 0 !important;
            border-bottom: 1px solid #3f3f46 !important;
            border-radius: 0 !important;
            font-size: 13px !important;
            font-weight: 600 !important;
        }

        .t17-profile-sidebar .space-y-4 > a:hover,
        .t17-profile-sidebar .space-y-4 > a.bg-indigo-100,
        .t17-profile-sidebar .space-y-4 > a.bg-orange-100,
        .t17-profile-sidebar .space-y-4 > a.bg-purple-100 {
            background: #27272a !important;
            color: color-mix(in srgb, var(--primary) 65%, white) !important;
            border-left: 3px solid var(--primary, #db2777) !important;
            padding-left: 7px !important;
        }

        .t17-profile-sidebar .space-y-4 > a > span:first-child {
            width: 34px !important;
            height: 34px !important;
            flex: 0 0 34px !important;
            border: 0 !important;
            border-radius: 0 !important;
            background: #27272a !important;
            color: color-mix(in srgb, var(--primary) 65%, white) !important;
            font-size: 18px !important;
        }

        .t17-profile-editor > div {
            background: #fff !important;
            border: 0 !important;
            border-top: 5px solid #18181b !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            padding: clamp(24px, 4vw, 42px) !important;
            height: 100%;
        }

        .t17-profile-editor h3 {
            margin: 0 0 30px !important;
            padding-bottom: 18px;
            border-bottom: 1px solid #e4e4e7;
            color: #18181b !important;
            font-size: 23px !important;
            font-weight: 800 !important;
            letter-spacing: -.5px !important;
            text-align: left !important;
        }

        .t17-profile-editor form {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            column-gap: 26px !important;
            row-gap: 24px !important;
            align-content: start;
        }

        .t17-profile-editor form > div {
            min-width: 0;
            margin: 0 !important;
        }

        .t17-profile-editor form label.block {
            display: block;
            margin-bottom: 9px !important;
            color: #52525b !important;
            font-size: 12px !important;
            font-weight: 750 !important;
            letter-spacing: .25px;
            text-transform: uppercase;
        }

        .t17-profile-editor form input[type="text"],
        .t17-profile-editor form input[type="email"],
        .t17-profile-editor form input[type="tel"] {
            width: 100% !important;
            min-width: 0 !important;
            height: 48px !important;
            padding: 0 12px !important;
            background: #fafafa !important;
            color: #18181b !important;
            border: 1px solid #d4d4d8 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            font-size: 14px !important;
        }

        .t17-profile-editor form input:focus {
            outline: 2px solid color-mix(in srgb, var(--primary, #db2777) 25%, transparent) !important;
            outline-offset: 1px;
            border-color: var(--primary, #db2777) !important;
            box-shadow: none !important;
        }

        .t17-profile-editor .iti {
            display: block !important;
            width: 100% !important;
            min-width: 0 !important;
        }

        .t17-profile-editor .iti input[type="tel"] {
            padding-top: 0 !important;
            padding-bottom: 0 !important;
        }

        .t17-profile-editor form label.flex {
            display: flex !important;
            align-items: center !important;
            min-height: 48px !important;
            padding: 0 12px !important;
            background: #fafafa !important;
            border: 1px dashed #a1a1aa !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .t17-profile-editor form label.flex:hover {
            background: color-mix(in srgb, var(--primary) 8%, white) !important;
            border-color: var(--primary, #db2777) !important;
        }

        .t17-profile-editor #oldImage {
            display: block;
            width: 76px;
            height: 76px;
            object-fit: cover;
            margin-top: 12px !important;
            border: 1px solid #d4d4d8 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .t17-profile-editor form > div:last-child {
            grid-column: 1 / -1 !important;
            display: flex !important;
            justify-content: flex-start !important;
            padding-top: 10px !important;
            margin-top: 4px !important;
            border-top: 1px solid #e4e4e7;
        }

        .t17-profile-editor button[type="submit"] {
            min-width: 160px;
            min-height: 47px !important;
            padding: 12px 24px !important;
            background: var(--primary, #db2777) !important;
            color: #fff !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            font-size: 13px !important;
            font-weight: 800 !important;
            letter-spacing: .2px;
            transition: filter .2s;
        }

        .t17-profile-editor button[type="submit"]:hover {
            filter: brightness(.92);
            transform: none !important;
        }

        @media (max-width: 850px) {
            .t17-profile-layout {
                grid-template-columns: minmax(0, 1fr) !important;
                gap: 20px !important;
            }

            .t17-profile-sidebar > div {
                height: auto;
            }

            .t17-profile-sidebar .space-y-4 {
                display: grid !important;
                grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
                gap: 0 14px !important;
            }
        }

        @media (max-width: 600px) {
            .t17-profile-container {
                padding: 24px 14px 36px !important;
            }

            .t17-profile-title {
                font-size: 28px !important;
                margin-bottom: 20px !important;
            }

            .t17-profile-sidebar > div,
            .t17-profile-editor > div {
                padding: 22px 16px !important;
            }

            .t17-profile-editor form {
                grid-template-columns: minmax(0, 1fr) !important;
                row-gap: 19px !important;
            }

            .t17-profile-editor form > div:last-child {
                grid-column: auto !important;
            }

            .t17-profile-editor button[type="submit"] {
                width: 100%;
            }

            .t17-profile-sidebar .space-y-4 {
                grid-template-columns: minmax(0, 1fr) !important;
            }
        }

    
        /* Database-driven sidebar hover/active colors; keep sidebar surface black */
        .t17-profile-sidebar > div {
            background: #18181b !important;
            border-top-color: var(--primary) !important;
        }

        .t17-profile-sidebar .space-y-4 > a:hover,
        .t17-profile-sidebar .space-y-4 > a.bg-indigo-100,
        .t17-profile-sidebar .space-y-4 > a.bg-orange-100,
        .t17-profile-sidebar .space-y-4 > a.bg-purple-100 {
            background: color-mix(in srgb, var(--primary) 16%, #18181b) !important;
            color: color-mix(in srgb, var(--primary) 68%, white) !important;
            border-left: 3px solid var(--primary) !important;
            border-bottom-color: color-mix(in srgb, var(--primary) 45%, #3f3f46) !important;
        }

        .t17-profile-sidebar .space-y-4 > a > span:first-child {
            color: color-mix(in srgb, var(--primary) 68%, white) !important;
        }

        .t17-profile-sidebar .space-y-4 > a:hover > span:first-child,
        .t17-profile-sidebar .space-y-4 > a.bg-indigo-100 > span:first-child,
        .t17-profile-sidebar .space-y-4 > a.bg-orange-100 > span:first-child,
        .t17-profile-sidebar .space-y-4 > a.bg-purple-100 > span:first-child {
            background: color-mix(in srgb, var(--primary) 18%, #18181b) !important;
            color: var(--primary) !important;
        }

        /* Prevent profile layout from causing horizontal overflow on mobile */
        .t17-profile-container,
        .t17-profile-layout,
        .t17-profile-sidebar,
        .t17-profile-editor {
            min-width: 0;
            max-width: 100%;
        }

        @media (max-width: 850px) {
            .t17-profile-layout {
                grid-template-columns: minmax(0, 1fr) !important;
            }
            .t17-profile-sidebar > div,
            .t17-profile-editor > div {
                width: 100%;
                height: auto !important;
            }
        }

        @media (max-width: 600px) {
            .t17-profile-container {
                padding: 20px 12px 30px !important;
            }
            .t17-profile-sidebar .space-y-4 {
                grid-template-columns: minmax(0, 1fr) !important;
            }
            .t17-profile-editor form {
                grid-template-columns: minmax(0, 1fr) !important;
            }
            .t17-profile-editor form > div:last-child {
                grid-column: auto !important;
            }
        }


        /* Fix intl-tel-input flag/country-code overlap */
        .t17-profile-editor .iti {
            position: relative !important;
            display: block !important;
            width: 100% !important;
            min-width: 0 !important;
        }

        .t17-profile-editor .iti--allow-dropdown input[type="tel"],
        .t17-profile-editor .iti--separate-dial-code input[type="tel"] {
            box-sizing: border-box !important;
            width: 100% !important;
            padding-left: 96px !important;
            padding-right: 12px !important;
        }

        .t17-profile-editor .iti__flag-container {
            left: 0 !important;
        }

        .t17-profile-editor .iti__selected-flag {
            display: flex !important;
            align-items: center !important;
            height: 100% !important;
            padding: 0 8px 0 12px !important;
            background: transparent !important;
        }

        .t17-profile-editor .iti__selected-dial-code {
            margin-left: 6px !important;
            white-space: nowrap !important;
        }

        @media (max-width: 600px) {
            .t17-profile-editor .iti--allow-dropdown input[type="tel"],
            .t17-profile-editor .iti--separate-dial-code input[type="tel"] {
                padding-left: 96px !important;
            }
        }

    </style>
</head>

<body class="bg-pink-50 font-sans min-h-screen">



    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Profile Section -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 t17-profile-container">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center t17-profile-title">
            My Profile
        </h1>

        <div class="flex flex-col lg:flex-row gap-8 t17-profile-layout">

            <?php $page = basename($_SERVER['PHP_SELF']); ?>

            <!-- Left Sidebar -->
            <div class="w-full lg:w-2/4 t17-profile-sidebar">
                <div class="bg-white/70 backdrop-blur-md border border-white/50 rounded-2xl p-6 shadow-lg">

                    <!-- Profile Info -->
                    <div class="flex flex-col items-center mb-6">
                        <div class="relative mb-4">
                            <img id="previewImage"
                                src="<?= !empty(customer('photo')) ? UPLOADS_URL . customer('photo') : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=200&q=80' ?>"
                                alt="User Profile"
                                class="w-24 h-24 rounded-full border-4 border-white/50 object-cover transition-all duration-300 shadow-sm">
                            <label for="photo"
                                class="absolute bottom-0 right-0 w-8 h-8 rounded-full flex items-center justify-center border-2 border-white cursor-pointer transition" style="background:var(--primary)" onmouseover="this.style.background='var(--hover-color)'" onmouseout="this.style.background='var(--primary)'">
                                <i class='bx bx-edit text-white text-sm'></i>
                            </label>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800"><?= customer("name") ?></h2>
                        <p class="text-gray-600 text-sm"><?= customer("email") ?></p>
                    </div>

                    <!-- Sidebar Links -->
                    <div class="space-y-4 w-full">
                        <a href="<?= $storeUrl ?>profile"
                            class="flex items-center gap-3 p-3 rounded-xl transition <?= $page == 'profile.php' ? 'bg-indigo-100 text-indigo-500' : 'bg-gray-100 text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?>">
                            <span class="bg-white border transition w-[44px] h-[44px] flex items-center justify-center rounded-full text-xl">
                                <i class='bx bx-user'></i>
                            </span>
                            <span class="font-medium">My Profile</span>
                        </a>

                        <a href="<?= $storeUrl ?>orders"
                            class="flex items-center gap-3 p-3 rounded-xl transition <?= $page == 'orders.php' ? 'bg-orange-100 text-orange-500' : 'bg-gray-100 text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?>">
                            <span class="bg-white border transition w-[44px] h-[44px] flex items-center justify-center rounded-full text-xl">
                                <i class='bx bx-package'></i>
                            </span>
                            <span class="font-medium">My Orders</span>
                        </a>

                        <a href="<?= $storeUrl ?>wishlists"
                            class="flex items-center gap-3 p-3 rounded-xl transition <?= $page == 'wishlists.php' ? 'bg-purple-100 text-purple-500' : 'bg-gray-100 text-gray-700 hover:bg-gray-100 hover:text-gray-900' ?>">
                            <span class="bg-white border transition w-[44px] h-[44px] flex items-center justify-center rounded-full text-xl">
                                <i class='bx bx-heart'></i>
                            </span>
                            <span class="font-medium">Wishlists</span>
                        </a>

                        <a href="<?= $storeUrl ?>logout"
                            class="flex items-center gap-3 p-3 rounded-xl transition bg-gray-100 text-gray-700 hover:bg-red-100 hover:text-red-500">
                            <span class="bg-white border transition w-[44px] h-[44px] flex items-center justify-center rounded-full text-xl">
                                <i class='bx bx-log-out'></i>
                            </span>
                            <span class="font-medium">Logout</span>
                        </a>
                    </div>
                </div>
            </div>


            <!-- Right Edit Form -->
            <div class="w-full lg:w-3/4 t17-profile-editor">
                <div class="bg-white/70 backdrop-blur-md p-6 rounded-3xl shadow-lg">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4 text-center">
                        Edit Profile
                    </h3>

                    <form id="form" method="post" enctype="multipart/form-data" class="space-y-4">

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-1">Name</label>
                            <input type="text" name="name" id="name"
                                value="<?= customer('name') ?>"
                                class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300 transition"
                                placeholder="Enter your name" required>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                            <input type="email" name="email" id="email"
                                value="<?= customer('email') ?>"
                                class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300 transition"
                                placeholder="Enter your email">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-gray-700 font-medium mb-1">Phone Number</label>
                            <input type="tel" name="phone[main]" id="phone"
                                value="<?= customer('phone') ?>"
                                class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300 transition"
                                placeholder="Enter your phone" required>
                        </div>

                        <!-- Photo Upload -->
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Photo</label>
                            <label
                                class="flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-xl cursor-pointer hover:bg-gray-100 text-gray-700 text-sm">
                                <i class='bx bx-upload text-lg'></i>
                                Choose File
                                <input type="file" name="photo" id="photo"
                                    class="hidden" accept="image/*">
                            </label>

                            <?php if (!empty(customer("photo"))): ?>
                                <img id="oldImage"
                                    src="<?= UPLOADS_URL . customer("photo") ?>"
                                    alt="Profile Photo"
                                    class="max-w-[100px] max-h-[100px] object-cover mt-2 rounded-xl border transition-all duration-300">
                            <?php endif; ?>
                        </div>

                        <!-- Save Button -->
                        <div class="text-right">
                            <button type="submit"
                                class="bg-primary-500 text-white py-2 px-5 rounded-md font-semibold shadow-lg hover:bg-hover transition-all">
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- IntlTelInput -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <script>
        // Custom toast function (same as product page)
        function showCustomToast(message, type) {
            // Remove any existing toasts
            const existingToasts = document.querySelectorAll('.custom-product-toast');
            existingToasts.forEach(toast => toast.remove());

            // Create toast element
            const toast = document.createElement('div');
            toast.className = 'custom-product-toast fixed top-4 right-4 z-50 transform transition-all duration-500 ease-in-out translate-x-full opacity-0';
            toast.innerHTML = `
                <div style="background-color: var(--primary) !important; color: white !important; border-color: color-mix(in srgb, var(--primary) 70%, #10b981) !important;" 
                     class="px-4 py-3 rounded-lg shadow-lg border-l-4 flex items-center gap-3">
                    <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'} text-xl'></i>
                    <span class="font-semibold">${message}</span>
                </div>
            `;

            // Add to page
            document.body.appendChild(toast);

            // Animate in from right to left
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
                toast.classList.add('translate-x-0', 'opacity-100');
            }, 10);

            // Auto remove after 3 seconds with reverse animation
            setTimeout(() => {
                toast.classList.remove('translate-x-0', 'opacity-100');
                toast.classList.add('translate-x-full', 'opacity-0');

                // Remove from DOM after animation completes
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.remove();
                    }
                }, 500);
            }, 3000);
        }

        // Phone Input (India only)
        const phoneInputField = document.querySelector("#phone");
        const phoneInput = window.intlTelInput(phoneInputField, {
            separateDialCode: true,
            onlyCountries: ["in"],
            initialCountry: "in",
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
        });

        // Live Photo Preview - Enhanced to update both images
        document.getElementById("photo").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Update main profile image
                    document.getElementById("previewImage").src = e.target.result;

                    // Also update the old image preview if it exists
                    const oldImage = document.getElementById("oldImage");
                    if (oldImage) {
                        oldImage.src = e.target.result;
                    }
                };
                reader.readAsDataURL(file);
            }
        });

        // AJAX Save Profile - Fixed FormData handling with custom toast
        $(document).on("submit", "#form", function(e) {
            e.preventDefault();

            // Create FormData from the form
            const formData = new FormData(this);

            // Get the full phone number with country code
            const fullPhoneNumber = phoneInput.getNumber();
            console.log("Full Phone Number:", fullPhoneNumber);

            // Append the full phone number to FormData
            formData.append("phone[full]", fullPhoneNumber);

            // Show loading state on button
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html(`
                <i class='bx bx-loader-alt animate-spin mr-2'></i>
                Saving...
            `);

            $.ajax({
                url: "shop/ajax/profile.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    console.log("Server Response:", result);
                    
                    // Reset button state
                    submitBtn.prop('disabled', false).html(originalText);
                    
                    try {
                        const response = result && JSON.parse(result);
                        if (response) {
                            if (response.success) {
                                // Use custom toast for success
                                showCustomToast(response.message || "Profile updated successfully!", 'success');
                                setTimeout(() => window.location.reload(), 1500);
                            } else {
                                // Use custom toast for error
                                showCustomToast(response.message || "Failed to update profile.", 'error');
                            }
                        } else {
                            showCustomToast("Invalid response from server.", 'error');
                        }
                    } catch (error) {
                        console.error("Error parsing response:", error);
                        showCustomToast("Invalid response from server.", 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                    // Reset button state
                    submitBtn.prop('disabled', false).html(originalText);
                    showCustomToast("Something went wrong. Please try again.", 'error');
                }
            });
        });
    </script>

</body>

</html>