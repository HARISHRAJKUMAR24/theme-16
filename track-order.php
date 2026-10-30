
<?php
include_once __DIR__ . "/includes/files_includes.php";

$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>

    <style>
        :root {
            --t17-primary: <?= htmlspecialchars($primary_color, ENT_QUOTES, 'UTF-8') ?>;
            --t17-hover: <?= htmlspecialchars($hover_color, ENT_QUOTES, 'UTF-8') ?>;
        }

        .t17-track-page {
            background: #f7f8fa;
        }

        /* No shadows */
        .t17-track-card {
            box-shadow: none !important;
        }

        .t17-track-accent {
            background: var(--t17-primary);
        }

        .t17-track-button {
            background: var(--t17-primary);
            box-shadow: none !important;
            transition: background .2s ease, transform .2s ease;
        }

        .t17-track-button:hover {
            background: var(--t17-hover);
            box-shadow: none !important;
            transform: translateY(-1px);
        }

        .t17-track-button:active {
            transform: translateY(0);
        }

        .t17-track-input:focus {
            border-color: var(--t17-primary);
            outline: none;
            box-shadow: none !important;
        }

        .t17-track-link {
            color: var(--t17-primary);
        }

        @media (max-width: 640px) {
            .t17-track-card {
                box-shadow: none !important;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .t17-track-button {
                transition: none;
            }
        }
        /* Left panel uses the database primary color */
.t17-track-left {
    background: var(--t17-primary);
}
    </style>
</head>

<body class="min-h-screen bg-white font-sans text-slate-900">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <main class="t17-track-page px-4 py-8 sm:px-6 sm:py-12 lg:py-16">
        <div class="mx-auto max-w-5xl">

            <div class="t17-track-card grid overflow-hidden rounded-2xl border border-slate-200 bg-white md:grid-cols-[.9fr_1.1fr]">

                <!-- LEFT INFORMATION PANEL -->
               <section class="t17-track-left relative flex flex-col justify-between overflow-hidden p-6 text-white sm:p-9 lg:p-11">

                  <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full bg-white opacity-10 blur-2xl"></div>

                    <div class="relative">
                        <div class="mb-8 inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[2px] text-slate-200">
                            <span class="h-1.5 w-1.5 rounded-full t17-track-accent"></span>
                            Order updates
                        </div>

                        <p class="text-[11px] font-semibold uppercase tracking-[3px] text-slate-400">
                            Theme 17 · Order care
                        </p>

                        <h1 class="mt-3 max-w-sm text-3xl font-semibold leading-tight tracking-tight sm:text-4xl">
                            Your order,<br>
                            <span class="text-slate-300">within reach.</span>
                        </h1>

                        <p class="mt-4 max-w-sm text-sm leading-6 text-slate-400">
                            Check your latest order status using your order ID or invoice number.
                        </p>
                    </div>

                    <div class="relative mt-10 rounded-xl border border-white/10 bg-white/[.06] p-4 sm:mt-14">
                        <div class="flex items-center gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg t17-track-accent text-white">
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                                    <path d="M3 7.5 12 3l9 4.5v9L12 21l-9-4.5v-9Z" />
                                    <path d="m3.5 7.7 8.5 4.5 8.5-4.5M12 12.2V21" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-sm font-semibold">Order tracking</p>
                                <p class="mt-0.5 text-xs text-slate-400">
                                    Quick access to your order details
                                </p>
                            </div>

                        </div>
                    </div>

                </section>

                <!-- RIGHT TRACKING FORM -->
                <section class="p-5 sm:p-8 lg:p-11">

                    <div class="mb-7">
                        <p class="text-[10px] font-bold uppercase tracking-[2.5px] t17-track-link">
                            Track an order
                        </p>

                        <h2 class="mt-2 text-2xl font-semibold tracking-tight text-slate-900 sm:text-[28px]">
                            Find your order
                        </h2>

                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Enter the details from your order confirmation to continue.
                        </p>
                    </div>

                    <form action="<?= htmlspecialchars($storeUrl . 'order', ENT_QUOTES, 'UTF-8') ?>" method="get">

                        <label for="order_id" class="mb-2 block text-xs font-semibold text-slate-700">
                            Order ID / Invoice number
                        </label>

                        <div class="relative">

                            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <svg viewBox="0 0 24 24" class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                    <rect x="5" y="3.5" width="14" height="17" rx="2" />
                                    <path d="M8.5 8h7M8.5 12h7M8.5 16h4" />
                                </svg>
                            </span>

                            <input
                                type="text"
                                id="order_id"
                                name="id"
                                required
                                placeholder="e.g. 12345 or invoice number"
                                autocomplete="off"
                                class="t17-track-input h-12 w-full rounded-lg border border-slate-200 bg-slate-50 pl-11 pr-4 text-sm text-slate-800 transition placeholder:text-slate-400"
                            >

                        </div>

                        <button
                            type="submit"
                            class="t17-track-button mt-4 flex h-12 w-full items-center justify-center gap-2 rounded-lg px-5 text-sm font-semibold text-white"
                        >
                            Track order

                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>

                    </form>

                    <div class="mt-6 flex items-start gap-3 rounded-lg border border-slate-100 bg-slate-50 p-3.5">

                        <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 shrink-0 text-slate-500" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 11v5M12 7.5h.01" />
                        </svg>

                        <p class="text-xs leading-5 text-slate-500">
                            You can find your order ID or invoice number in your order confirmation.
                        </p>

                    </div>

                </section>

            </div>
        </div>
    </main>

    <script>
        const orderIdInput = document.getElementById("order_id");

        if (orderIdInput) {
            orderIdInput.addEventListener("input", function () {
                this.value = this.value.replace(/#/g, "");
            });
        }
    </script>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
