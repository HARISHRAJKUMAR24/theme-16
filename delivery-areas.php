<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wine: '#65132f',
                        cream: '#f7f2ee',
                        gold: '#b68a3b'
                    },
                    fontFamily: {
                        serifDisplay: ['Georgia', 'serif'],
                        clean: ['Arial', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899') ?>;
        }

        .theme13-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            transition: border-color .25s ease;
        }

        .theme13-card:hover {
            border-color: color-mix(in srgb, var(--primary-color) 40%, #e5e7eb);
        }

        .theme13-btn:hover {
            background: var(--hover-color) !important;
            color: #fff !important;
        }

        .info-row {
            transition: background .2s ease;
        }

        .info-row:hover {
            background: color-mix(in srgb, var(--primary-color) 3%, white);
        }


        /* =========================================================
           THEME 17 - DELIVERY AREAS & STORE LOCATIONS
           Distinct editorial layout: flat, square, aligned, responsive
        ========================================================= */
        body {
            color: #18181b;
            background: #f4f4f5 !important;
        }

        .t17-delivery-hero {
            background: #18181b;
            color: #fff;
            padding: clamp(42px, 6vw, 76px) 20px !important;
            border: 0 !important;
            position: relative;
            overflow: hidden;
        }

        .t17-delivery-hero::after {
            content: "17";
            position: absolute;
            right: max(3vw, calc((100vw - 1180px) / 2));
            bottom: -72px;
            font-size: clamp(150px, 23vw, 290px);
            line-height: 1;
            font-weight: 900;
            letter-spacing: -18px;
            color: rgba(255,255,255,.035);
            pointer-events: none;
        }

        .t17-delivery-hero > div {
            position: relative;
            z-index: 1;
            max-width: 1180px !important;
            text-align: left !important;
        }

        .t17-delivery-hero .t17-eyebrow {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #f9a8d4;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .t17-delivery-hero .t17-eyebrow::before {
            content: "";
            display: inline-block;
            width: 28px;
            height: 2px;
            background: var(--primary-color, #db2777);
        }

        .t17-delivery-hero h1 {
            max-width: 720px;
            color: #fff !important;
            font-size: clamp(34px, 5vw, 58px) !important;
            line-height: 1.06;
            font-weight: 800;
            letter-spacing: -1.8px;
            margin: 0 0 18px !important;
        }

        .t17-delivery-hero .t17-hero-description {
            max-width: 620px;
            color: #d4d4d8 !important;
            font-size: 15px;
            line-height: 1.8;
            margin: 0 !important;
        }

        .t17-section {
            padding: clamp(42px, 5vw, 68px) 20px !important;
        }

        .t17-section-inner {
            max-width: 1180px !important;
            margin: 0 auto;
        }

        .t17-section-heading {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 28px;
            padding-bottom: 18px;
            border-bottom: 1px solid #d4d4d8;
            text-align: left;
        }

        .t17-section-heading .t17-kicker {
            color: var(--primary-color, #db2777);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0 0 8px;
        }

        .t17-section-heading h2 {
            color: #18181b !important;
            font-family: Arial, sans-serif !important;
            font-size: clamp(26px, 3vw, 36px) !important;
            font-weight: 800 !important;
            line-height: 1.15;
            letter-spacing: -1px;
            margin: 0 !important;
        }

        .t17-section-heading .t17-section-number {
            color: #d4d4d8;
            font-size: clamp(38px, 5vw, 60px);
            font-weight: 900;
            letter-spacing: -3px;
            line-height: .9;
            flex-shrink: 0;
        }

        .t17-area-grid,
        .t17-location-grid {
            display: grid !important;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1px !important;
            background: #d4d4d8;
            border: 1px solid #d4d4d8;
        }

        .t17-area-card,
        .t17-location-card {
            min-width: 0;
            background: #fff !important;
            border: 0 !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            padding: clamp(18px, 2.2vw, 28px) !important;
            transition: background .2s ease;
        }

        .t17-area-card:hover,
        .t17-location-card:hover {
            background: #fafafa !important;
        }

        .t17-card-topline {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            padding-bottom: 19px;
            margin-bottom: 15px;
            border-bottom: 1px solid #e4e4e7;
        }

        .t17-index {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            background: var(--primary-color, #db2777) !important;
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            border-radius: 0 !important;
        }

        .t17-card-heading {
            min-width: 0;
            flex: 1;
        }

        .t17-card-heading h3 {
            color: #18181b !important;
            font-family: Arial, sans-serif !important;
            font-size: 17px !important;
            line-height: 1.35;
            font-weight: 800 !important;
            margin: 1px 0 5px !important;
            overflow-wrap: anywhere;
        }

        .t17-card-heading .t17-card-subtitle {
            color: #71717a;
            font-size: 11px;
            line-height: 1.5;
            overflow-wrap: anywhere;
        }

        .t17-info-list {
            display: flex;
            flex-direction: column;
            gap: 0 !important;
        }

        .t17-info-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            padding: 13px 0 !important;
            border-bottom: 1px solid #f0f0f2;
            background: transparent !important;
            min-width: 0;
        }

        .t17-info-row:last-child {
            border-bottom: 0;
        }

        .t17-info-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #71717a;
            font-size: 10px;
            line-height: 1.5;
            font-weight: 800;
            letter-spacing: .7px;
            text-transform: uppercase;
            flex: 0 0 auto;
        }

        .t17-info-label i {
            color: var(--primary-color, #db2777) !important;
            font-size: 15px;
        }

        .t17-info-value {
            color: #27272a;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.6;
            text-align: right;
            overflow-wrap: anywhere;
            min-width: 0;
        }

        .t17-free-label {
            color: #15803d !important;
        }

        .t17-notes {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-top: 16px;
            padding: 13px;
            border-left: 3px solid var(--primary-color, #db2777);
            background: #f4f4f5;
            color: #52525b;
            font-size: 12px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .t17-notes i {
            flex-shrink: 0;
            color: var(--primary-color, #db2777);
            font-size: 16px;
            margin-top: 2px;
        }

        .t17-address-row {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 4px 0 17px;
            border-bottom: 1px solid #e4e4e7;
        }

        .t17-address-row > i {
            flex: 0 0 auto;
            color: var(--primary-color, #db2777);
            font-size: 18px;
            margin-top: 2px;
        }

        .t17-small-label {
            color: #71717a;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .t17-address-text {
            color: #27272a;
            font-size: 13px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .t17-location-meta {
            display: grid !important;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 !important;
            margin: 0 0 10px;
        }

        .t17-location-meta > div {
            min-width: 0;
            padding: 13px 8px 13px 0 !important;
            border-bottom: 1px solid #f0f0f2;
            background: transparent !important;
        }

        .t17-location-meta > div:nth-child(even) {
            padding-left: 12px !important;
            border-left: 1px solid #f0f0f2;
        }

        .t17-location-meta p:last-child {
            color: #27272a;
            font-size: 13px;
            line-height: 1.5;
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .t17-location-contact {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f2;
            min-width: 0;
        }

        .t17-location-contact i {
            flex: 0 0 auto;
            color: var(--primary-color, #db2777);
            font-size: 16px;
        }

        .t17-location-contact a {
            min-width: 0;
            color: var(--primary-color, #db2777);
            font-size: 12px;
            line-height: 1.6;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .t17-business-hours {
            padding-top: 17px;
            margin-top: 6px;
            border-top: 1px solid #e4e4e7;
        }

        .t17-business-hours .t17-hour-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding: 7px 0;
            color: #52525b;
            font-size: 11px;
            line-height: 1.5;
        }

        .t17-business-hours .t17-hour-row span:last-child {
            color: #18181b;
            font-weight: 700;
            text-align: right;
            white-space: nowrap;
        }

        .t17-empty-state {
            padding: 34px 20px;
            background: #fff;
            border: 1px solid #d4d4d8;
            color: #71717a;
            text-align: center;
            font-size: 14px;
        }

        .t17-section-divider {
            width: 100%;
            height: 6px;
            background: var(--primary-color, #db2777);
        }

        .t17-shop-cta {
            padding: clamp(40px, 5vw, 64px) 20px !important;
            background: var(--primary-color, #db2777) !important;
        }

        .t17-shop-cta > div {
            max-width: 1180px !important;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 24px 40px;
            text-align: left !important;
        }

        .t17-shop-cta .t17-cta-kicker {
            color: rgba(255,255,255,.75);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .t17-shop-cta h2 {
            color: #fff !important;
            font-family: Arial, sans-serif !important;
            font-size: clamp(26px, 4vw, 42px) !important;
            line-height: 1.1;
            font-weight: 800 !important;
            letter-spacing: -1px;
            margin: 0 0 10px !important;
        }

        .t17-shop-cta .t17-cta-description {
            color: rgba(255,255,255,.84) !important;
            font-size: 14px;
            line-height: 1.7;
            margin: 0 !important;
            max-width: 600px;
        }

        .t17-shop-cta .t17-shop-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 18px;
            min-height: 50px;
            padding: 0 24px !important;
            background: #18181b !important;
            color: #fff !important;
            border: 1px solid #18181b !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1px;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .t17-shop-cta .t17-shop-button:hover {
            background: #fff !important;
            color: #18181b !important;
        }

        .theme13-card,
        .theme13-card:hover,
        .theme13-btn,
        .info-row,
        .info-row:hover {
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        @media (max-width: 980px) {
            .t17-area-grid,
            .t17-location-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .t17-delivery-hero {
                padding: 42px 18px !important;
            }

            .t17-delivery-hero h1 {
                letter-spacing: -1px;
            }

            .t17-delivery-hero .t17-hero-description {
                font-size: 14px;
            }

            .t17-section {
                padding: 38px 14px !important;
            }

            .t17-section-heading {
                align-items: center;
                margin-bottom: 20px;
            }

            .t17-section-heading .t17-section-number {
                font-size: 38px;
            }

            .t17-area-grid,
            .t17-location-grid {
                grid-template-columns: minmax(0, 1fr);
            }

            .t17-area-card,
            .t17-location-card {
                padding: 21px 18px !important;
            }

            .t17-shop-cta > div {
                grid-template-columns: minmax(0, 1fr);
                gap: 22px;
            }

            .t17-shop-cta .t17-shop-button {
                width: 100%;
            }
        }

    </style>
</head>

<body class="bg-white font-sans min-h-screen">

    <?php
    $primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
    $hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';
    ?>


    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Hero -->
    <section class="t17-delivery-hero">
        <div class="max-w-4xl mx-auto text-center">
            <p class="t17-eyebrow">Delivery &amp; Locations</p>
            <h1 class="font-serifDisplay text-4xl sm:text-5xl text-gray-900 mb-4">
                Where We Deliver
            </h1>
            <p class="t17-hero-description">
                Check our delivery areas and visit our stores. We bring your order straight to your doorstep.
            </p>
        </div>
    </section>

    <!-- Delivery Areas -->
    <section class="t17-section">
        <div class="t17-section-inner">

            <div class="t17-section-heading">
                <div><p class="t17-kicker">Now Serving</p><h2>Delivery Areas</h2></div><span class="t17-section-number">01</span>
            </div>

            <?php
            // FIX: getDeliveryAreas() may return PDOStatement — fetch into array
            $areasStmt = getDeliveryAreas();
            if ($areasStmt instanceof PDOStatement) {
                $areas = $areasStmt->fetchAll(PDO::FETCH_ASSOC);
            } elseif (is_array($areasStmt)) {
                $areas = $areasStmt;
            } else {
                $areas = [];
            }

            if (!empty($areas)):
            ?>
                <div class="t17-area-grid">
                    <?php foreach ($areas as $index => $area): ?>
                        <div class="theme13-card t17-area-card">

                            <div class="t17-card-topline">
                                <div class="t17-index"
                                    <?= $index + 1 ?>
                                </div>
                                <h3>
                                    <?= htmlspecialchars($area['value']) ?>
                                </h3>
                            </div>

                            <div class="t17-info-list">
                                <div class="t17-info-row">
                                    <span class="t17-info-label">
                                        <i class='bx bx-map' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                                        <?= getData("delivery_area_type", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") == "zip_code" ? 'Postal Code' : 'State' ?>
                                    </span>
                                    <span class="t17-info-value">
                                        <?= htmlspecialchars($area['value']) ?>
                                    </span>
                                </div>

                                <div class="t17-info-row">
                                    <span class="t17-info-label">
                                        <i class='bx bx-package' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                                        Delivery Fee
                                    </span>
                                    <?php if (getSettings("delivery_charges_type")): ?>
                                        <span class="t17-info-value">
                                            <?= ucwords(str_replace("_", " ", getSettings("delivery_charges_type"))) ?>
                                        </span>
                                    <?php elseif ($area['charges'] > 0): ?>
                                        <span class="font-medium text-gray-900">
                                            <?= currencyToSymbol($storeCurrency) . $area['charges'] ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="t17-info-value t17-free-label">
                                            Free
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <?php if (!empty($area['delivery_notes'])): ?>
                                <div class="t17-notes">
                                    <div class="flex items-start gap-2">
                                        <i class='bx bx-info-circle flex-shrink-0 mt-0.5'
                                           style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                                        <p><?= htmlspecialchars($area['delivery_notes']) ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="t17-empty-state">
                    No delivery areas configured yet.
                </p>
            <?php endif; ?>

        </div>
    </section>

    <!-- Divider -->
    <div class="t17-section-divider"></div>

    <!-- Our Locations -->
    <section class="t17-section">
        <div class="t17-section-inner">

            <div class="t17-section-heading">
                <div><p class="t17-kicker">Visit Us</p><h2>Our Stores</h2></div><span class="t17-section-number">02</span>
            </div>

            <?php
            // FIX: readData() returns PDOStatement — fetch into array
            $locationsStmt = readData("*", "seller_locations", "(seller_id = '$sellerId' AND store_id = '$storeId')");
            $locations = $locationsStmt ? $locationsStmt->fetchAll(PDO::FETCH_ASSOC) : [];

            if (!empty($locations)):
            ?>
                <div class="t17-location-grid">
                    <?php foreach ($locations as $index => $location): ?>
                        <div class="theme13-card t17-location-card">

                            <div class="t17-card-topline">
                                <div class="t17-index"
                                    <?= $index + 1 ?>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3>
                                        <?= htmlspecialchars($location['name']) ?>
                                    </h3>
                                    <?php if ($location['phone']): ?>
                                        <a href="tel:<?= $location['phone'] ?>"
                                           class="t17-card-subtitle hover:underline"
                                           style="color: <?= htmlspecialchars($primary_color) ?>;">
                                            <i class='bx bx-phone'></i>
                                            <?= formatPhoneNumber($location['phone']) ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="t17-info-list">

                                <div class="t17-address-row">
                                    <i class='bx bx-map-pin text-base flex-shrink-0 mt-0.5'
                                       style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs uppercase tracking-widest text-gray-500 mb-1">Address</p>
                                        <p class="text-gray-900 break-words">
                                            <?= htmlspecialchars($location['address_1']) ?>
                                            <?php if ($location['address_2']): ?>
                                                <br><?= htmlspecialchars($location['address_2']) ?>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="t17-location-meta">
                                    <div>
                                        <p class="t17-small-label">City</p>
                                        <p><?= htmlspecialchars($location['city']) ?></p>
                                    </div>
                                    <div>
                                        <p class="t17-small-label">Postal Code</p>
                                        <p><?= htmlspecialchars($location['zip_code']) ?></p>
                                    </div>
                                    <div>
                                        <p class="t17-small-label">State</p>
                                        <p><?= htmlspecialchars($location['state']) ?></p>
                                    </div>
                                    <div>
                                        <p class="t17-small-label">Country</p>
                                        <p class="text-sm font-medium text-gray-900 break-words"><?= codeToCountry($location['country']) ?></p>
                                    </div>
                                </div>

                                <?php if ($location['email']): ?>
                                    <div class="t17-location-contact">
                                        <i class='bx bx-envelope text-base flex-shrink-0'
                                           style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                                        <a href="mailto:<?= $location['email'] ?>"
                                           class="hover:underline"
                                           style="color: <?= htmlspecialchars($primary_color) ?>;">
                                            <?= htmlspecialchars($location['email']) ?>
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>

                            <?php
                            $business_hours = getLocationBusinessHour($location['location_id']);
                            if (!empty($business_hours)):
                            ?>
                                <div class="t17-business-hours">
                                    <p class="t17-small-label">
                                        Business Hours
                                    </p>
                                    <div class="space-y-1 text-xs">
                                        <?php foreach ($business_hours as $hour): ?>
                                            <div class="t17-hour-row">
                                                <span class="text-gray-600"><?= htmlspecialchars($hour['day']) ?></span>
                                                <span class="font-medium text-gray-900">
                                                    <?= strtoupper(date('h:i a', strtotime($hour['time_from']))) ?> – <?= strtoupper(date('h:i a', strtotime($hour['time_to']))) ?>
                                                </span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="t17-empty-state">
                    No store locations added yet.
                </p>
            <?php endif; ?>

        </div>
    </section>

    <!-- CTA -->
    <section class="t17-shop-cta">
        <div class="max-w-3xl mx-auto text-center">
            <p class="t17-cta-kicker">Ready to shop?</p>
            <h2 class="font-serifDisplay text-3xl sm:text-4xl text-white mb-6">
                Start Shopping Today
            </h2>
            <p class="t17-cta-description">
                Order now and let us bring your favourites right to your door.
            </p>
            <a href="<?= $storeUrl ?>shop-all"
               class="t17-shop-button theme13-btn"
               style="color: <?= htmlspecialchars($primary_color) ?>;">
                Shop Now
                <span class="text-base">→</span>
            </a>
        </div>
    </section>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>

</html>