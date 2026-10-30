<?php
include_once __DIR__ . "/includes/files_includes.php";
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
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#64748b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#475569') ?>;
            --theme17-pink: var(--primary-color);
            --theme17-pink-light: color-mix(in srgb, var(--primary-color) 10%, white);
            --theme17-lavender: color-mix(in srgb, var(--primary-color) 5%, white);
        }

        .theme17-primary-btn {
            background: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: #fff;
            transition: background .2s ease, transform .2s ease, box-shadow .2s ease;
        }
        .theme17-primary-btn:hover {
            background: var(--hover-color);
            border-color: var(--hover-color);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 18px color-mix(in srgb, var(--primary-color) 18%, transparent);
        }

        .theme17-account-card {
            background: #fff;
            border: 1px solid color-mix(in srgb, var(--primary-color) 12%, white);
            border-radius: 22px;
            box-shadow: 0 12px 35px rgba(30, 41, 59, .045);
            overflow: hidden;
        }
        .theme17-profile-top {
            position: relative;
            overflow: hidden;
            background: linear-gradient(145deg, color-mix(in srgb, var(--primary-color) 10%, white) 0%, color-mix(in srgb, var(--primary-color) 4%, white) 50%, color-mix(in srgb, var(--primary-color) 6%, white) 100%);
        }
        .theme17-profile-top:before {
            content: '';
            position: absolute;
            width: 220px;
            height: 220px;
            right: -85px;
            bottom: -125px;
            border-radius: 50%;
            background: color-mix(in srgb, var(--primary-color) 8%, transparent);
            pointer-events: none;
        }
        .theme17-profile-top:after {
            content: '';
            position: absolute;
            width: 135px;
            height: 135px;
            right: -42px;
            bottom: -72px;
            border: 1px solid color-mix(in srgb, var(--primary-color) 18%, transparent);
            border-radius: 50%;
            pointer-events: none;
        }
        .theme17-avatar {
            border: 4px solid #fff;
            box-shadow: 0 8px 22px color-mix(in srgb, var(--primary-color) 14%, transparent);
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid transparent;
            border-radius: 12px;
            padding: 13px 14px;
            color: #575569;
            transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease;
        }
        .sidebar-link:hover {
            background: color-mix(in srgb, var(--primary-color) 5%, white);
            border-color: color-mix(in srgb, var(--primary-color) 25%, white);
            color: var(--primary-color) !important;
            transform: translateX(2px);
        }
        .sidebar-link.active {
            background: linear-gradient(100deg, color-mix(in srgb, var(--primary-color) 10%, white), color-mix(in srgb, var(--primary-color) 3%, white));
            border-color: color-mix(in srgb, var(--primary-color) 40%, white) !important;
            color: var(--primary-color) !important;
            font-weight: 600;
        }
        .sidebar-link.active i { color: var(--primary-color); }
        .sidebar-link.logout:hover {
            background: #fff7f7;
            border-color: #f3dada !important;
            color: #dc2626 !important;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate { padding: 8px 4px; }
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter { color: #777487; font-size: 13px; }
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid color-mix(in srgb, var(--primary-color) 18%, white);
            border-radius: 10px;
            padding: 9px 12px;
            background: #fff;
            font-size: 13px;
            color: #374151;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--primary-color) 13%, transparent);
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid color-mix(in srgb, var(--primary-color) 12%, white) !important;
            border-radius: 9px !important;
            padding: 6px 11px !important;
            margin: 0 2px !important;
            background: #fff !important;
            color: #575569 !important;
            transition: all .2s ease;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #fff1f7 !important;
            color: var(--primary-color) !important;
            border-color: color-mix(in srgb, var(--primary-color) 40%, white) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: var(--primary-color) !important;
            color: #fff !important;
            border-color: var(--primary-color) !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: #f8fafc !important;
            color: #cbd5e1 !important;
            border-color: color-mix(in srgb, var(--primary-color) 12%, white) !important;
        }
        #ordersTable { border-collapse: separate; border-spacing: 0; width: 100%; }
        #ordersTable thead th {
            background: linear-gradient(100deg, var(--primary-color), var(--hover-color));
            color: #fff;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .68rem;
            font-weight: 700;
            padding: 15px 16px;
            text-align: left;
            border: none;
            white-space: nowrap;
        }
        #ordersTable thead th:first-child { border-radius: 10px 0 0 10px; }
        #ordersTable thead th:last-child { border-radius: 0 10px 10px 0; }
        #ordersTable tbody td {
            padding: 15px 16px;
            font-size: 13px;
            color: #4b4658;
            vertical-align: middle;
            border-bottom: 1px solid color-mix(in srgb, var(--primary-color) 8%, white);
        }
        #ordersTable tbody tr { transition: background-color .15s ease; }
        #ordersTable tbody tr:hover { background: color-mix(in srgb, var(--primary-color) 3%, white); }
        .dataTables_empty {
            text-align: center !important;
            padding: 42px 20px !important;
            font-size: 14px !important;
            color: #94a3b8 !important;
        }
        #ordersTable td.dataTables_empty { text-align: center !important; padding: 42px 20px !important; }

        @media (max-width: 640px) {
            .theme17-page-wrap { padding-top: 28px !important; padding-bottom: 36px !important; }
            .theme17-page-title { font-size: 2rem !important; }
            .theme17-account-card { border-radius: 18px; }
            #ordersTable thead th, #ordersTable tbody td { padding: 12px 13px; }
            .dataTables_wrapper .dataTables_filter input { width: 100% !important; }
        }
    </style>
</head>

<body class="font-sans bg-[#fffafd] min-h-screen text-gray-800">

    <?php
    $primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#64748b';
    $hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#475569';
    ?>

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Main Container -->
    <main class="theme17-page-wrap container mx-auto px-4 sm:px-6 lg:px-8 py-12 max-w-7xl">

        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 mb-8">
            <div>
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-[2px] uppercase" style="color: var(--primary-color);"><span class="inline-block w-7 h-[2px] rounded-full" style="background: var(--primary-color);"></span> My Account</p>
                <h1 class="theme17-page-title text-4xl sm:text-5xl font-bold tracking-tight text-gray-900 mt-2">My Orders</h1>
                <p class="text-sm text-gray-500 mt-2">View and track your recent purchases in one place.</p>
            </div>
            <div class="hidden sm:flex items-center gap-2 rounded-full border bg-white px-4 py-2 text-sm text-gray-500 shadow-sm" style="border-color: color-mix(in srgb, var(--primary-color) 15%, white);"><i class='bx bx-package text-xl' style='color: var(--primary-color);'></i><span>Order history</span></div>
        </div>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-7">

            <!-- Left Sidebar -->
            <div class="w-full lg:w-1/3">
                <div class="theme17-account-card">

                    <!-- Profile Info -->
                    <div class="theme17-profile-top px-6 pt-7 pb-6 flex flex-col items-center text-center">
                        <div class="relative mb-4">
                            <img id="previewImage"
                                src="<?= !empty(customer('photo')) ? UPLOADS_URL . customer('photo') : 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=200&q=80' ?>"
                                alt="User Profile"
                                class="theme17-avatar relative z-10 w-24 h-24 rounded-full object-cover">
                            <label for="photo"
                                class="absolute bottom-0 right-0 z-20 w-8 h-8 rounded-full flex items-center justify-center border-2 border-white cursor-pointer transition theme17-primary-btn"
                                onclick="window.location.href='<?= $storeUrl ?>profile'">
                                <i class='bx bx-edit text-white text-sm'></i>
                            </label>
                        </div>
                        <h2 class="relative z-10 font-semibold text-xl text-gray-900"><?= customer("name") ?></h2>
                        <p class="relative z-10 text-gray-600 text-sm mt-1 break-all"><?= customer("email") ?></p>
                    </div>

                    <!-- Sidebar Links -->
                    <div class="p-4 sm:p-5 space-y-2">
                        <a href="<?= $storeUrl ?>profile"
                           class="sidebar-link">
                            <i class='bx bx-user text-xl'></i>
                            <span class="font-medium text-sm tracking-wide">My Profile</span>
                        </a>

                        <a href="<?= $storeUrl ?>orders"
                           class="sidebar-link active"
                           >
                            <i class='bx bx-package text-xl'></i>
                            <span class="font-medium text-sm tracking-wide">My Orders</span>
                        </a>

                        <a href="<?= $storeUrl ?>wishlists"
                           class="sidebar-link">
                            <i class='bx bx-heart text-xl'></i>
                            <span class="font-medium text-sm tracking-wide">Wishlists</span>
                        </a>

                        <a href="<?= $storeUrl ?>logout"
                           class="sidebar-link logout">
                            <i class='bx bx-log-out text-xl'></i>
                            <span class="font-medium text-sm tracking-wide">Logout</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right: Orders Table -->
            <div class="w-full lg:w-2/3">
                <div class="theme17-account-card p-4 sm:p-6 lg:p-7">

                    <div class="flex items-center justify-between gap-3 mb-5">
                        <div>
                            <h2 class="text-lg font-bold text-gray-900">Order history</h2>
                            <p class="text-xs sm:text-sm text-gray-500 mt-1">Your orders and payment details</p>
                        </div>
                        <span class="flex-shrink-0 inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold" style="background: color-mix(in srgb, var(--primary-color) 8%, white); color: var(--primary-color);"><i class='bx bx-time-five text-base'></i> Recent</span>
                    </div>
                    <!-- Orders Table Container -->
                    <div class="overflow-x-auto">
                        <table id="ordersTable" class="min-w-full table-auto border-collapse text-sm">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Created</th>
                                    <th>Total</th>
                                    <th>Payment Method</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <!-- DataTables Script -->
    <script>
        $(document).ready(function() {
            // Initialize DataTable with server-side processing
            var table = $('#ordersTable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "shop/ajax/orders.php",
                    "type": "POST",
                    "data": function(d) {
                        if (!d.order || d.order.length === 0) {
                            d.order = [[1, 'desc']];
                        }
                    }
                },
                "columns": [
                    { "data": "order_id", "className": "whitespace-nowrap" },
                    {
                        "data": "created_at",
                        "className": "whitespace-nowrap",
                        "render": function(data, type, row) {
                            if (!data) return "";
                            const date = new Date(data);
                            const day = String(date.getDate()).padStart(2, '0');
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${day}/${month}/${year}`;
                        }
                    },
                    { "data": "total", "className": "whitespace-nowrap" },
                    { "data": "payment_method", "className": "whitespace-nowrap" },
                    { "data": "status", "className": "whitespace-nowrap" }
                ],
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50], [10, 25, 50]],
                "order": [[1, 'desc']],
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search orders...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ orders",
                    "infoEmpty": "No orders found",
                    "infoFiltered": "(filtered from _MAX_ total orders)",
                    "zeroRecords": "You haven't placed any orders yet 🛒",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "previous": "‹",
                        "next": "›"
                    },
                    "processing": "Loading orders..."
                },
                "responsive": true,
                "dom": '<"flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6"<"w-full sm:w-auto"l><"w-full sm:w-auto flex justify-end"f>>rt<"flex flex-col sm:flex-row justify-between items-center gap-4 mt-6"<"text-sm text-gray-500"i><"flex gap-2"p>>',
                "initComplete": function() {
                    $('.dataTables_filter')
                        .addClass('w-full sm:w-auto')
                        .find('input')
                        .addClass('w-full sm:w-64 px-4 py-2 border border-[color:var(--primary-color)] bg-white text-gray-800 focus:outline-none transition placeholder-gray-400')
                        .attr('placeholder', 'Search orders...')
                        .css('border-radius', '10px');

                    $('.dataTables_length select')
                        .addClass('px-3 py-2 border border-[color:var(--primary-color)] bg-white text-gray-800 focus:outline-none transition')
                        .css('border-radius', '10px');
                },
                "drawCallback": function() {
                    $('.dataTables_filter')
                        .addClass('w-full sm:w-auto')
                        .find('input')
                        .addClass('w-full sm:w-64 px-4 py-2 border border-[color:var(--primary-color)] bg-white text-gray-800 focus:outline-none transition placeholder-gray-400')
                        .attr('placeholder', 'Search orders...')
                        .css('border-radius', '10px');

                    $('.dataTables_length select')
                        .addClass('px-3 py-2 border border-[color:var(--primary-color)] bg-white text-gray-800 focus:outline-none transition')
                        .css('border-radius', '10px');
                }
            });
        });
    </script>

</body>

</html>