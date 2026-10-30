<?php
// Fetch dynamic colors from database
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#d7a4c8';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ffffff';
$storeName     = $storeName ?? 'SHINISHA';

// Check if at least one social media link exists
$hasSocialLinks = getSettings("facebook") || getSettings("instagram") || getSettings("twitter") || getSettings("linkedin") || getSettings("youtube") || getSettings("pinterest") || getSettings("sharechat");
?>

<!-- ==========================================
     PREMIUM FOOTER
========================================== -->
<footer class="relative overflow-hidden bg-[#171318] text-white">

    <!-- MAIN FOOTER CONTENT -->
    <div class="mx-auto max-w-7xl px-5 py-12 sm:px-8 sm:py-16 lg:px-12">

        <div class="grid grid-cols-2 gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-5 lg:gap-10">

            <!-- BRAND -->
            <div class="col-span-2 lg:col-span-2">

                <a href="<?= $storeUrl ?>" class="inline-block">
                    <?php if (getSettings("logo")): ?>
                        <img src="<?= UPLOADS_URL . getSettings("logo") ?>" alt="<?= htmlspecialchars($storeName) ?>" class="h-10 object-contain">
                    <?php else: ?>
                        <span class="font-serif text-3xl font-bold tracking-[0.12em]">
                            <?= htmlspecialchars(strtoupper($storeName)) ?><span class="text-[<?= htmlspecialchars($primary_color) ?>]">.</span>
                        </span>
                    <?php endif; ?>
                </a>

                <p class="mt-5 max-w-sm text-sm leading-7 text-white/60">
                    Discover styles that express your individuality.
                    From everyday essentials to special occasions,
                    find pieces that make you feel confident and beautiful.
                </p>

                <!-- SOCIAL LINKS (Dynamic from DB) -->
                <?php if ($hasSocialLinks): ?>
                <div class="mt-6 flex items-center gap-3">
                    <?php if (getSettings("instagram")): ?>
                        <a href="<?= getSettings("instagram") ?>" target="_blank" aria-label="Instagram"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-sm transition hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:bg-[<?= htmlspecialchars($primary_color) ?>] hover:text-[#251522]">
                            IG
                        </a>
                    <?php endif; ?>

                    <?php if (getSettings("facebook")): ?>
                        <a href="<?= getSettings("facebook") ?>" target="_blank" aria-label="Facebook"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-sm font-bold transition hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:bg-[<?= htmlspecialchars($primary_color) ?>] hover:text-[#251522]">
                            f
                        </a>
                    <?php endif; ?>

                    <?php if (getSettings("youtube")): ?>
                        <a href="<?= getSettings("youtube") ?>" target="_blank" aria-label="YouTube"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-xs font-bold transition hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:bg-[<?= htmlspecialchars($primary_color) ?>] hover:text-[#251522]">
                            ▶
                        </a>
                    <?php endif; ?>

                    <?php if (getSettings("pinterest")): ?>
                        <a href="<?= getSettings("pinterest") ?>" target="_blank" aria-label="Pinterest"
                            class="flex h-10 w-10 items-center justify-center rounded-full border border-white/15 text-sm font-bold transition hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:bg-[<?= htmlspecialchars($primary_color) ?>] hover:text-[#251522]">
                            P
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

            </div>

            <!-- SHOP LINKS -->
            <div>
                <h3 class="mb-5 text-xs font-bold uppercase tracking-[0.2em] text-[<?= htmlspecialchars($primary_color) ?>]">
                    Explore
                </h3>

                <ul class="space-y-3 text-sm text-white/65">
                    <li><a href="<?= $storeUrl ?>" class="transition hover:text-white">Home</a></li>
                    <li><a href="<?= $storeUrl ?>shop-all" class="transition hover:text-white">Shop All</a></li>
                    <li><a href="<?= $storeUrl ?>new-arrivals" class="transition hover:text-white">New Arrivals</a></li>
                    <li><a href="<?= $storeUrl ?>best-seller" class="transition hover:text-white">Best Sellers</a></li>
                </ul>
            </div>

            <!-- CUSTOMER CARE -->
            <div>
                <h3 class="mb-5 text-xs font-bold uppercase tracking-[0.2em] text-[<?= htmlspecialchars($primary_color) ?>]">
                    Customer Care
                </h3>

                <ul class="space-y-3 text-sm text-white/65">
                    <li><a href="<?= $storeUrl ?>track-order" class="transition hover:text-white">Track Your Order</a></li>
                    <li><a href="<?= $storeUrl ?>profile" class="transition hover:text-white">My Account</a></li>
                    <li><a href="<?= $storeUrl ?>cart" class="transition hover:text-white">Cart</a></li>
                    <li><a href="<?= $storeUrl ?>wishlists" class="transition hover:text-white">Wishlist</a></li>
                </ul>
            </div>

            <!-- POLICIES (Dynamic Pages from DB) -->
            <div>
                <h3 class="mb-5 text-xs font-bold uppercase tracking-[0.2em] text-[<?= htmlspecialchars($primary_color) ?>]">
                    Information
                </h3>

                <ul class="space-y-3 text-sm text-white/65">
                    <?php
                    $pages = getPages();
                    if ($pages->rowCount()):
                        foreach ($pages as $page):
                    ?>
                        <li>
                            <a href="<?= $storeUrl . "pages/" . $page['slug'] ?>" class="transition hover:text-white">
                                <?= htmlspecialchars($page['name']) ?>
                            </a>
                        </li>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </ul>
            </div>

        </div>

        <!-- TRUST STRIP -->
        <div class="mt-12 grid grid-cols-1 gap-4 border-y border-white/10 py-6 sm:grid-cols-3">

            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/5 text-lg text-[<?= htmlspecialchars($primary_color) ?>]">♡</span>
                <div>
                    <p class="text-xs font-semibold">Style You'll Love</p>
                    <p class="mt-1 text-[11px] text-white/45">Find your own signature look</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/5 text-lg text-[<?= htmlspecialchars($primary_color) ?>]">↗</span>
                <div>
                    <p class="text-xs font-semibold">Order Assistance</p>
                    <p class="mt-1 text-[11px] text-white/45">We're here to help</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-white/5 text-lg text-[<?= htmlspecialchars($primary_color) ?>]">✦</span>
                <div>
                    <p class="text-xs font-semibold">Fresh Fashion Finds</p>
                    <p class="mt-1 text-[11px] text-white/45">Discover your next favourite</p>
                </div>
            </div>

        </div>

        <!-- BOTTOM BAR -->
        <div class="flex flex-col gap-5 pt-7 text-center sm:flex-row sm:items-center sm:justify-between sm:text-left">

            <p class="text-xs leading-6 text-white/45">
                © <span id="footerYear"></span> <?= htmlspecialchars(strtoupper($storeName)) ?>. All rights reserved.
                <br class="block sm:hidden">
                <span class="block sm:inline">Powered by <a href="https://ztorespot.com/" target="_blank" class="text-[<?= htmlspecialchars($primary_color) ?>] hover:underline">Ztorespot</a></span>
            </p>

        </div>

        <!-- DISCLAIMER SECTION (Fixed colors to be visible on dark background) -->
        <?php if (getData("disclaimer", "settings") && !$isCustomDomain): ?>
            <div class="mt-8 pt-6 border-t border-white/10 px-6 text-center">
                <h3 class="text-white/80 font-semibold text-sm mb-2 uppercase tracking-wider">Disclaimer</h3>
                <p class="text-white/40 text-xs mx-auto max-w-3xl leading-relaxed">
                    <?= getData("disclaimer", "settings") ?>
                </p>
            </div>
        <?php endif; ?>

    </div>
</footer>

<script>
    $(document).on("click", ".searchBtn", function(e) {
        e.preventDefault();
        const searchInput = $(".searchInput");
        const searchCategory = $(".searchCategory");
        let url = '';
        if (typeof searchCategory.val() === 'undefined') {
            url = '<?= $storeUrl ?>search/' + encodeURIComponent(searchInput.val());
        } else {
            url = '<?= $storeUrl ?>search/' + encodeURIComponent(searchInput.val()) + "?category=" + searchCategory.val();
        }
        if (searchInput.val() !== "") window.location.href = url;
    });
</script>

<!--JS File Include -->

<!--This File For my Customise Js For my Theme 9-->
<script src="<?= APP_URL ?>themes/theme9/js/script.js"></script>

<!--This File For Cart Functions For Theme 9-->
<script src="<?= APP_URL ?>themes/theme9/ajax/common-cart.js"></script>

<!--This File For Track Cart & Checkout Theme 9-->
<script src="<?= APP_URL ?>shop/javascripts/utm-tracker.js"></script>

<!--This File For Wishlist-->
<script src="<?= APP_URL ?>themes/theme9/ajax/common-wishlist.js"></script>

<!--This File For Wishlist-->
<script src="<?= APP_URL ?>assets/js/global.js"></script>
<script src="<?= APP_URL ?>assets/js/theme3/app.js"></script>




<!--This File For Checkout -->
<script src="<?= APP_URL ?>assets/js/theme3/checkout.js"></script>
<script src="<?= APP_URL ?>shop/javascripts/checkout.js"></script>


<!-- Datatable JS -->
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="<?= APP_URL ?>shop/javascripts/orders.js"></script>