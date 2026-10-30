<?php

// Including header
include_once __DIR__ . "/includes/header.php";

// DB colors
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

if (isset($_GET['id']) && getData("id", "seller_orders", "order_id = '{$_GET['id']}' AND (seller_id = '$sellerId' AND store_id = '$storeId') AND customer_id = '$customerId'")) {
    $order_id = $_GET['id'];

    $ordered_date = date('F d, Y', strtotime(getData("created_at", "seller_orders", "order_id = '$order_id'")));
    $status = ucwords(getData("status", "seller_orders", "order_id = '$order_id'"));
    $payment_status = ucwords(getData("payment_status", "seller_orders", "order_id = '$order_id'"));
    $courier_service = getData("courier_service", "seller_orders", "order_id = '$order_id'");
    $tracking_id = getData("tracking_id", "seller_orders", "order_id = '$order_id'");
    $expected_delivery_date = getData("expected_delivery_date", "seller_orders", "order_id = '$order_id'");
    $tax = getData("tax", "seller_orders", "order_id = '$order_id'");
    $customized_gift = getData("customized_gift", "seller_orders", "order_id = '$order_id'");
    $delivery_charges = getData("delivery_charges", "seller_orders", "order_id = '$order_id'");
    $total = getData("total", "seller_orders", "order_id = '$order_id'");
    $discount = getData("discount", "seller_orders", "order_id = '$order_id'");
    $discount_type = getData("discount_type", "seller_orders", "order_id = '$order_id'");
    $currency = getData("currency", "seller_orders", "order_id = '$order_id'");
    $payment_method = getData("payment_method", "seller_orders", "order_id = '$order_id'");

    $gst_number = getData("gst_number", "seller_orders", "order_id = '$order_id'");
    $gst_tax_type = getData("gst_tax_type", "seller_orders", "order_id = '$order_id'");
    $gst_percentage = getData("gst_percentage", "seller_orders", "order_id = '$order_id'");

    $gstTaxAmount = '';
    $totalGst = '';
    if ($tax != 0 && !empty($gst_percentage)) {
        $totalGst = $gst_tax_type == "inclusive" ? "(Inclusive GST -$gst_percentage%)" : "(Exclusive GST +$gst_percentage%)";

        if ($gst_tax_type != "inclusive") {
            $gstTaxAmount = '<li class="grid grid-cols-11 max-w-[350px] text-sm">
                        <span class="col-span-4 font-semibold">GST (' . $gst_percentage . '%)</span>
                        <span class="col-span-1">:</span>
                        <span class="col-span-6">' . currencyToSymbol($currency) . number_format($tax, 2) . '</span>
                    </li>';
        }
    }

    $subTotal = 0;
    foreach (getOrderedProducts(array("orderId" => $order_id)) as $key => $product) {
        $subTotal += $product['price'] * $product['quantity'];
    }
} else {
    redirect($storeUrl);
}
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
            --primary-color: <?= htmlspecialchars($primary_color) ?>;
            --hover-color: <?= htmlspecialchars($hover_color) ?>;
            --theme17-primary-soft: color-mix(in srgb, var(--primary-color) 7%, white);
            --theme17-primary-pale: color-mix(in srgb, var(--primary-color) 3%, white);
            --theme17-primary-border: color-mix(in srgb, var(--primary-color) 18%, white);
            --theme17-primary-border-strong: color-mix(in srgb, var(--primary-color) 30%, white);
            --theme17-primary-shadow: color-mix(in srgb, var(--primary-color) 16%, transparent);
        }

        * { box-sizing: border-box; }

        body {
            background: var(--theme17-primary-pale);
            color: #1f2937;
        }

        .theme17-primary-btn {
            background: linear-gradient(135deg, var(--primary-color), var(--hover-color));
            border: 1px solid transparent;
            border-radius: 12px;
            color: #fff;
            box-shadow: 0 8px 20px var(--theme17-primary-shadow);
        }
        .theme17-primary-btn:hover {
            filter: brightness(.97);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 10px 24px color-mix(in srgb, var(--primary-color) 22%, transparent);
        }

        .theme17-page-shell {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background: linear-gradient(145deg, var(--theme17-primary-soft) 0%, #ffffff 52%, var(--theme17-primary-pale) 100%);
        }
        .theme17-page-shell::before,
        .theme17-page-shell::after {
            content: '';
            position: absolute;
            z-index: -1;
            pointer-events: none;
            border-radius: 9999px;
        }
        .theme17-page-shell::before {
            width: 300px;
            height: 300px;
            right: -100px;
            top: 30px;
            background: color-mix(in srgb, var(--primary-color) 8%, transparent);
        }
        .theme17-page-shell::after {
            width: 180px;
            height: 180px;
            left: -60px;
            bottom: 80px;
            border: 1px solid color-mix(in srgb, var(--primary-color) 15%, transparent);
        }

        .theme17-page-title {
            background: linear-gradient(145deg, var(--theme17-primary-soft) 0%, color-mix(in srgb, var(--primary-color) 4%, white) 50%, var(--theme17-primary-pale) 100%);
            border: 1px solid color-mix(in srgb, var(--primary-color) 10%, transparent);
            border-radius: 24px;
            padding: 28px 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 12px 35px color-mix(in srgb, var(--primary-color) 6%, transparent);
        }
        .theme17-page-title::after {
            content: '';
            position: absolute;
            width: 140px;
            height: 140px;
            right: -35px;
            bottom: -75px;
            border: 1px solid color-mix(in srgb, var(--primary-color) 20%, transparent);
            border-radius: 50%;
            pointer-events: none;
        }

        .theme17-card {
            min-width: 0;
            background: rgba(255,255,255,.96);
            border: 1px solid var(--theme17-primary-border);
            border-radius: 20px;
            padding: 1.35rem;
            box-shadow: 0 10px 28px color-mix(in srgb, var(--primary-color) 6%, transparent);
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }
        .theme17-card:hover {
            border-color: var(--theme17-primary-border-strong);
            box-shadow: 0 14px 32px color-mix(in srgb, var(--primary-color) 8%, transparent);
        }
        .theme17-card h3 { letter-spacing: -.02em; }
        .theme17-card h3 i { flex-shrink: 0; }

        .theme17-detail-row {
            border: 1px solid color-mix(in srgb, var(--primary-color) 12%, white);
            border-radius: 10px;
            padding: 11px 12px;
            background: #fff;
            overflow-wrap: anywhere;
        }

        .theme17-table {
            border-collapse: separate;
            border-spacing: 0;
            min-width: 640px;
            width: 100%;
        }
        .theme17-table thead th {
            background: linear-gradient(135deg, var(--primary-color), var(--hover-color));
            color: #fff;
            text-align: left;
            font-weight: 600;
            padding: .9rem 1rem;
            font-size: .7rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .theme17-table thead th:first-child { border-radius: 10px 0 0 0; }
        .theme17-table thead th:last-child { border-radius: 0 10px 0 0; }
        .theme17-table td {
            padding: 1rem;
            border-bottom: 1px solid color-mix(in srgb, var(--primary-color) 10%, white);
            vertical-align: middle;
            font-size: .875rem;
            background: #fff;
        }
        .theme17-table tbody tr:hover td { background: var(--theme17-primary-soft); }
        .theme17-table td img { border-radius: 10px; background: var(--theme17-primary-pale); border-color: var(--theme17-primary-border); flex-shrink: 0; }

        .theme17-info-panel {
            border: 1px solid color-mix(in srgb, var(--primary-color) 12%, white);
            background: linear-gradient(135deg, var(--theme17-primary-soft), var(--theme17-primary-pale));
            border-radius: 12px;
        }
        .theme17-total-row {
            background: linear-gradient(135deg, color-mix(in srgb, var(--primary-color) 8%, white), color-mix(in srgb, var(--hover-color) 5%, white));
            border-radius: 12px;
            padding: 12px 10px;
        }

        @media (max-width: 640px) {
            .theme17-page-shell { padding-top: 1.5rem; padding-bottom: 1.5rem; }
            .theme17-page-title { padding: 22px 18px; border-radius: 18px; }
            .theme17-card { padding: 1rem; border-radius: 16px; }
            .theme17-page-shell::before { width: 190px; height: 190px; right: -90px; top: 20px; }
            .theme17-page-shell::after { width: 125px; height: 125px; left: -55px; bottom: 35px; }
        }
    </style>
</head>

<body class="font-sans min-h-screen">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <div class="theme17-page-shell py-10 sm:py-12">
        <div class="container mx-auto px-4 max-w-6xl">

            <!-- Page Header -->
            <div class="theme17-page-title mb-7 sm:mb-9">
                <p class="text-xs tracking-[3px] uppercase font-semibold" style="color: <?= htmlspecialchars($primary_color) ?>;">Order Details</p>
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mt-2">
                    <h1 class="font-serifDisplay text-3xl sm:text-4xl text-gray-900 font-semibold">
                        Order #<?= htmlspecialchars($order_id) ?>
                    </h1>

                    <a href="<?= $storeUrl ?>download-invoice?id=<?= $order_id ?>"
                       class="theme17-primary-btn inline-flex items-center gap-2 px-5 py-2.5 text-xs tracking-[2px] uppercase font-semibold transition-all duration-300">
                        <i class='bx bxs-download text-base'></i>
                        Download Invoice
                    </a>
                </div>
            </div>

            <!-- Order Status + Products -->
            <div class="flex flex-col lg:flex-row gap-5 mb-5">

                <!-- Order Status Sidebar -->
                <div class="theme17-card lg:w-[35%] flex flex-col">
                    <h3 class="font-serifDisplay text-xl text-gray-900 mb-5">Order Status</h3>

                    <ul class="space-y-3">
                        <li class="theme17-detail-row grid grid-cols-11 text-sm w-full">
                            <span class="col-span-4 text-gray-600 font-medium">Ordered At</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= $ordered_date ?></span>
                        </li>

                        <li class="theme17-detail-row grid grid-cols-11 text-sm w-full">
                            <span class="col-span-4 text-gray-600 font-medium">Status</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 font-medium" style="color: <?= htmlspecialchars($hover_color) ?>;"><?= $status ?></span>
                        </li>

                        <li class="theme17-detail-row grid grid-cols-11 text-sm w-full">
                            <span class="col-span-4 text-gray-600 font-medium">Payment Status</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 font-medium" style="color: <?= htmlspecialchars($primary_color) ?>;"><?= $payment_status ?></span>
                        </li>

                        <?php if ($courier_service) : ?>
                            <li class="theme17-detail-row grid grid-cols-11 text-sm w-full">
                                <span class="col-span-4 text-gray-600 font-medium">Courier Service</span>
                                <span class="col-span-1 text-gray-400">:</span>
                                <span class="col-span-6 text-gray-900"><?= $courier_service ?: 'update soon' ?></span>
                            </li>
                        <?php endif; ?>

                        <?php if ($expected_delivery_date) : ?>
                            <li class="theme17-detail-row grid grid-cols-11 text-sm w-full">
                                <span class="col-span-4 text-gray-600 font-medium">Expected Delivery</span>
                                <span class="col-span-1 text-gray-400">:</span>
                                <span class="col-span-6 text-gray-900"><?= $expected_delivery_date ? date('M d, Y', strtotime($expected_delivery_date)) : 'update soon' ?></span>
                            </li>
                        <?php endif; ?>
                    </ul>

                    <?php if ($payment_status == "Unpaid") : ?>
                        <p class="block text-sm mt-5 p-3 border-l-2 border-red-400 bg-red-50 text-red-600">
                            <strong>Note:</strong> Once you paid manually it can take 3-5 hours to verify the payment.
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Ordered Products -->
                <div class="theme17-card lg:w-[65%]">
                    <h3 class="font-serifDisplay text-xl text-gray-900 mb-5 flex items-center gap-2">
                        <i class='bx bx-package text-2xl' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                        Ordered Products
                    </h3>

                    <div class="w-full overflow-x-auto">
                        <table class="theme17-table table-auto w-full">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>MRP Price</th>
                                    <th>Qty</th>
                                    <th>Sub Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <?php
                                $products = getOrderedProducts(array("orderId" => $order_id));
                                $subTotal = 0;

                                foreach ($products as $key => $product) :
                                    $subTotal += $product['price'] * $product['quantity'];

                                    $image = getData("image", "seller_products", "id = '{$product['product_id']}'");
                                    if ($product['other'] && getData("image", "seller_product_variants", "id = '{$product['other']}'")) {
                                        $image = getData("image", "seller_product_variants", "id = '{$product['other']}'");
                                    }

                                    $other = "";
                                    $advanced_variant = "";
                                    $size = '';
                                    $colorId = '';

                                    if ($product['other']) {
                                        $other = " - " . getData("variation", "seller_product_variants", "id = '{$product['other']}'");
                                    }
                                    if ($product && $product['advanced_variant']) {
                                        $size = getData("size", "seller_product_advanced_variants", "id = '{$product['advanced_variant']}'");
                                        $colorId = getData("color", "seller_product_advanced_variants", "id = '{$product['advanced_variant']}'");
                                        $color = getData("color_name", "product_colors", "id = '$colorId'");

                                        $advanced_variant = "Size: $size | Color: $color";

                                        if (getData("image", "seller_product_advanced_variants", "id = '{$product['advanced_variant']}'")) {
                                            $image = getData("image", "seller_product_advanced_variants", "id = '{$product['advanced_variant']}'");
                                        }
                                    }

                                    $productUrl = $storeUrl . "product/" . getData("slug", "seller_products", "id = '{$product['product_id']}'");
                                    if ($product['other']) {
                                        $productUrl .= "?variation=" . $product['other'];
                                    }
                                    if ($size && $colorId) {
                                        $productUrl .= "?size=" . $size . "&color=" . $colorId;
                                    }
                                ?>
                                    <tr>
                                        <td>
                                            <a href="<?= $productUrl ?>" class="flex items-center gap-4" style="color: <?= htmlspecialchars($primary_color) ?>;">
                                                <img class="object-contain w-16 h-16 border border-gray-200"
                                                     src="<?= UPLOADS_URL . $image ?>"
                                                     alt="<?= htmlspecialchars(getData("name", "seller_products", "id = '{$product['product_id']}'") . $other) ?>">

                                                <div>
                                                    <span class="font-medium block text-gray-900">
                                                        <?= htmlspecialchars(getData("name", "seller_products", "id = '{$product['product_id']}'") . $other) ?>
                                                    </span>
                                                    <span class="block text-xs text-gray-400 mt-1">
                                                        <?= htmlspecialchars($advanced_variant) ?>
                                                    </span>
                                                </div>
                                            </a>
                                        </td>
                                        <td><?= currencyToSymbol($currency) . number_format($product['price'], 2) ?></td>
                                        <td><?= currencyToSymbol($currency) . number_format($product['mrp_price'], 2) ?></td>
                                        <td><?= $product['quantity'] ?></td>
                                        <td class="font-medium" style="color: <?= htmlspecialchars($primary_color) ?>;">
                                            <?= currencyToSymbol($currency) . number_format($product['price'] * $product['quantity'], 2) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Summary + Address + Courier cards grid -->
            <div class="grid gap-5 lg:grid-cols-3 md:grid-cols-2 mb-5">

                <!-- Bank Info (unpaid + bank transfer) -->
                <?php if ($payment_status == "Unpaid" && !empty(getSettings("bank_details")) && plan("payment_bank") && $payment_method === "Bank Transfer") : ?>
                    <div class="theme17-card">
                        <h3 class="font-serifDisplay text-xl text-gray-900 mb-5 flex items-center gap-2">
                            <i class='bx bx-credit-card text-2xl' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                            Bank Information
                        </h3>
                        <div class="theme17-info-panel p-4 text-sm leading-6">
                            <?= nl2br(getSettings("bank_details")) ?>
                        </div>
                    </div>
                <?php endif ?>

                <!-- UPI QR (unpaid + UPI) -->
                <?php if ($payment_status == "Unpaid" && !empty(getSettings("upi_qr_code")) && plan("payment_upi") && $payment_method === "UPI") : ?>
                    <div class="theme17-card">
                        <h3 class="font-serifDisplay text-xl text-gray-900 mb-5 flex items-center gap-2">
                            <i class='bx bx-qr text-2xl' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                            UPI Payment
                        </h3>
                        <div class="theme17-info-panel p-4">
                            <img src="<?= UPLOADS_URL . getSettings("upi_qr_code") ?>" alt="UPI QR" class="max-h-[200px]">
                        </div>
                    </div>
                <?php endif ?>

                <!-- Order Summary -->
                <div class="theme17-card">
                    <h3 class="font-serifDisplay text-xl text-gray-900 mb-5 flex items-center gap-2">
                        <i class='bx bx-wallet text-2xl' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                        Order Summary
                    </h3>

                    <ul class="space-y-4">
                        <li class="grid grid-cols-11 max-w-[350px] text-sm">
                            <span class="col-span-4 text-gray-600 font-medium">Sub Total</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= currencyToSymbol($storeCurrency) . number_format($subTotal, 2) ?></span>
                        </li>

                        <li class="grid grid-cols-11 max-w-[350px] text-sm">
                            <span class="col-span-4 text-gray-600 font-medium">Delivery Charge</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= currencyToSymbol($storeCurrency) . $delivery_charges ?></span>
                        </li>

                        <?= $gstTaxAmount ?>

                        <?php if ($discount_type == "fixed") { ?>
                            <li class="grid grid-cols-11 max-w-[350px] text-sm">
                                <span class="col-span-4 text-gray-600 font-medium">Discount</span>
                                <span class="col-span-1 text-gray-400">:</span>
                                <span class="col-span-6 text-gray-900"><?= currencyToSymbol($storeCurrency) . number_format($discount, 2) ?></span>
                            </li>
                        <?php } else { ?>
                            <li class="grid grid-cols-11 max-w-[350px] text-sm">
                                <span class="col-span-4 text-gray-600 font-medium">Discount</span>
                                <span class="col-span-1 text-gray-400">:</span>
                                <span class="col-span-6 text-gray-900"><?= number_format($discount, 2) . "%" ?></span>
                            </li>
                        <?php } ?>

                        <li class="theme17-total-row grid grid-cols-11 max-w-[350px] text-base border-t border-transparent pt-3 mt-3">
                            <span class="col-span-4 font-semibold text-gray-900">Total</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 font-bold" style="color: <?= htmlspecialchars($primary_color) ?>;">
                                <?= currencyToSymbol($currency) . number_format($total, 2) ?>
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Delivery Address -->
                <div class="theme17-card">
                    <h3 class="font-serifDisplay text-xl text-gray-900 mb-5 flex items-center gap-2">
                        <i class='bx bx-map text-2xl' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                        Delivery Address
                    </h3>

                    <ul class="space-y-4 text-sm">
                        <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                            <span class="col-span-4 text-gray-600 font-medium">Name</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= getData("name", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?></span>
                        </li>
                        <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                            <span class="col-span-4 text-gray-600 font-medium">Phone</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= getData("phone", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?></span>
                        </li>
                        <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                            <span class="col-span-4 text-gray-600 font-medium">Email</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900 break-all"><?= getData("email", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?></span>
                        </li>

                        <li class="text-sm border-t border-gray-200 pt-4 mt-4">
                            <span class="block mb-2 font-medium text-gray-600">Address</span>
                            <span class="block text-gray-900 leading-relaxed">
                                <?= getData("address", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?><br>
                                <?= getData("city", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?>,
                                <?= getData("state", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?>
                                <?= getData("pin_code", "seller_order_address", "type = 'shipping' AND order_id = '$order_id'") ?>
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Billing Address -->
                <div class="theme17-card">
                    <h3 class="font-serifDisplay text-xl text-gray-900 mb-5 flex items-center gap-2">
                        <i class='bx bx-receipt text-2xl' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                        Billing Address
                    </h3>

                    <ul class="space-y-4 text-sm">
                        <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                            <span class="col-span-4 text-gray-600 font-medium">Name</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= getData("name", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?></span>
                        </li>
                        <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                            <span class="col-span-4 text-gray-600 font-medium">Phone</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900"><?= getData("phone", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?></span>
                        </li>
                        <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                            <span class="col-span-4 text-gray-600 font-medium">Email</span>
                            <span class="col-span-1 text-gray-400">:</span>
                            <span class="col-span-6 text-gray-900 break-all"><?= getData("email", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?></span>
                        </li>

                        <li class="text-sm border-t border-gray-200 pt-4 mt-4">
                            <span class="block mb-2 font-medium text-gray-600">Address</span>
                            <span class="block text-gray-900 leading-relaxed">
                                <?= getData("address", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?><br>
                                <?= getData("city", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?>,
                                <?= getData("state", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?>
                                <?= getData("pin_code", "seller_order_address", "type = 'billing' AND order_id = '$order_id'") ?>
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Courier Information -->
                <?php if (!empty($courier_service)) : ?>
                    <div class="theme17-card">
                        <h3 class="font-serifDisplay text-xl text-gray-900 mb-5 flex items-center gap-2">
                            <i class='bx bx-package text-2xl' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                            Courier Information
                        </h3>

                        <ul class="space-y-4 text-sm">
                            <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                                <span class="col-span-4 text-gray-600 font-medium">Courier Service</span>
                                <span class="col-span-1 text-gray-400">:</span>
                                <span class="col-span-6 text-gray-900"><?= $courier_service ?></span>
                            </li>
                            <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                                <span class="col-span-4 text-gray-600 font-medium">Tracking ID</span>
                                <span class="col-span-1 text-gray-400">:</span>
                                <span class="col-span-6 text-gray-900"><?= $tracking_id ?></span>
                            </li>
                            <li class="grid grid-cols-11 max-w-[350px] gap-y-1">
                                <span class="col-span-4 text-gray-600 font-medium">Expected Date</span>
                                <span class="col-span-1 text-gray-400">:</span>
                                <span class="col-span-6 text-gray-900"><?= $expected_delivery_date ? date('M d, Y', strtotime($expected_delivery_date)) : 'update soon' ?></span>
                            </li>
                        </ul>
                    </div>
                <?php endif ?>
            </div>

            <!-- Customized Gift -->
            <?php if ($customized_gift) : ?>
                <div class="theme17-card mb-5">
                    <h3 class="font-serifDisplay text-xl text-gray-900 mb-5 flex items-center gap-2">
                        <i class='bx bx-gift text-2xl' style="color: <?= htmlspecialchars($primary_color) ?>;"></i>
                        Customized Gift
                    </h3>
                    <div class="text-sm text-gray-700 leading-relaxed [&_ul]:ml-8 [&_ul]:mt-2 [&_ul]:list-disc">
                        <?= preg_replace('/customers\/\//', UPLOADS_URL . 'customers//', $customized_gift) ?>
                    </div>
                </div>
            <?php endif ?>

            <!-- Contact -->
            <p class="mt-10 text-center text-sm text-gray-500">
                Contact us for any help at
                <a href="tel:<?= getSettings("phone") ?>" class="font-medium" style="color: <?= htmlspecialchars($primary_color) ?>;">
                    <?= getSettings("phone") ?>
                </a>
                — We're always here to assist you.
            </p>

        </div>
    </div>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>

</html>