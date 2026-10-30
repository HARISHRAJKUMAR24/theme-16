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

        /* Horizontal scroll for categories */
        .category-scroll {
            scroll-behavior: smooth;
            scrollbar-width: none;
            -ms-overflow-style: none;
        }
        .category-scroll::-webkit-scrollbar { display: none; }

        /* Category card */
        .category-card {
            transition: border-color .2s ease, color .2s ease;
        }
        .category-card:hover {
            border-color: var(--primary-color) !important;
            color: var(--primary-color) !important;
        }

        /* Theme 13 buttons */
        .theme13-primary-btn {
            background: var(--primary-color);
            border: 1px solid var(--primary-color);
            color: #fff;
        }
        .theme13-primary-btn:hover {
            background: var(--hover-color);
            border-color: var(--hover-color);
            color: #fff;
        }

        /* Filter/CTA buttons */
        .theme13-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .55rem 1rem;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
            font-size: .7rem;
            letter-spacing: .15em;
            text-transform: uppercase;
            transition: all .3s ease;
        }
        .theme13-filter-btn:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .theme13-cta {
            background: var(--primary-color);
            color: #fff;
            font-size: .7rem;
            letter-spacing: .15em;
            text-transform: uppercase;
            font-weight: 500;
            padding: .65rem 1rem;
            transition: all .3s ease;
            border: 1px solid var(--primary-color);
        }
        .theme13-cta:hover {
            background: var(--hover-color);
            border-color: var(--hover-color);
            color: #fff;
        }

        .theme13-dropdown {
            background: #fff;
            border: 1px solid #e5e7eb;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
        }

        /* Slider */
        .slider-container { position: relative; height: 28px; }
        .slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            position: absolute;
            top: 50%; left: 0;
            transform: translateY(-50%);
            width: 100%; height: 4px;
            background: transparent;
            outline: none; margin: 0; padding: 0;
            pointer-events: auto; cursor: pointer;
            z-index: 2;
        }
        .slider-thumb-min { z-index: 3; }
        .slider-thumb-max { z-index: 2; }
        .slider-thumb::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px; height: 16px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .25);
        }
        .slider-thumb::-moz-range-thumb {
            width: 16px; height: 16px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
            border: 2px solid #fff;
        }
        .slider-thumb::-webkit-slider-runnable-track,
        .slider-thumb::-moz-range-track {
            height: 2px;
            background: transparent;
            border: none;
        }
        .relative.h-1 { height: 4px !important; }

        .price-preset.active {
            background: var(--primary-color) !important;
            color: #fff !important;
            border-color: var(--primary-color) !important;
        }

        #sliderRangeFill { transition: left .1s ease, right .1s ease; }

        .hidden-product { display: none !important; }
/* ================= UNIQUE NEW ARRIVALS LOOK ================= */
        .new-arrivals-hero {
            position: relative;
            overflow: hidden;
            background: #f7f2ee;
            border-bottom: 1px solid #e7ded7;
        }
        .new-arrivals-hero::before {
            content: "NEW SEASON";
            position: absolute;
            right: -22px;
            top: 50%;
            transform: translateY(-50%) rotate(90deg);
            transform-origin: center;
            font: 700 10px/1 Arial, sans-serif;
            letter-spacing: .38em;
            color: rgba(101, 19, 47, .28);
        }
        .na-kicker { display:inline-flex; align-items:center; gap:10px; color:#65132f; font-size:10px; letter-spacing:.28em; text-transform:uppercase; }
        .na-kicker::before, .na-kicker::after { content:""; width:26px; height:1px; background:#b68a3b; }
        .na-heading { color:#211719; letter-spacing:-.045em; line-height:1.05; }
        .na-heading em { color:#65132f; font-style:italic; }
        .na-hero-note { color:#766b68; letter-spacing:.04em; }
        .na-category-link { border:1px solid #e9e0da; background:#fff; transition:transform .25s ease,border-color .25s ease,box-shadow .25s ease; }
        .na-category-link:hover { transform:translateY(-3px); border-color:#b68a3b; box-shadow:0 8px 22px rgba(48,22,26,.07); }
        .na-category-link img { transition:transform .35s ease; }
        .na-category-link:hover img { transform:scale(1.07); }
        /* Spacious, index-page inspired product grid: every product card stays large */
        .new-arrivals-products-shell { width:100%; max-width:1720px; margin-inline:auto; padding-inline:clamp(14px,3.5vw,58px); }
        .new-arrivals-products-heading { display:flex; align-items:end; justify-content:space-between; gap:20px; margin-bottom:28px; }
        .new-arrivals-products-heading .na-section-kicker { color:#8a6a4b; font-size:10px; letter-spacing:.24em; text-transform:uppercase; }
        .new-arrivals-products-heading h2 { color:#251719; font-family:Georgia,serif; font-size:clamp(26px,3vw,40px); line-height:1.1; letter-spacing:-.035em; }
        .new-arrivals-products-heading .na-heading-rule { width:58px; height:2px; margin-top:13px; background:#b68a3b; }
        #content { align-items:stretch; grid-template-columns:repeat(2,minmax(0,1fr)); column-gap:clamp(12px,2vw,28px); row-gap:clamp(28px,3.5vw,48px); }
        #content > .t9-product-card { min-width:0; width:100%; border:1px solid #eee7e2; background:#fff; transition:transform .28s ease,box-shadow .28s ease,border-color .28s ease; }
        #content > .t9-product-card:hover { transform:translateY(-6px); border-color:#c8a878; box-shadow:0 18px 36px rgba(52,27,31,.11); }
        #content .t9-card-media { background:#f7f2ee; }
        #content .t9-card-img { width:100%; aspect-ratio:3 / 4; object-fit:cover; transition:transform .6s cubic-bezier(.2,.7,.2,1); }
        #content > .t9-product-card:hover .t9-card-img { transform:scale(1.045); }
        #content .t9-card-title { padding-left:clamp(10px,1.4vw,20px); padding-right:clamp(10px,1.4vw,20px); }
        #content .t9-card-title a { color:#2a2020; }
        #content .t9-card-title a:hover { color:#65132f; }
        #content .t9-card-price, #content .productPrice { padding-left:clamp(10px,1.4vw,20px); padding-right:clamp(10px,1.4vw,20px); }
        @media (min-width:768px) { #content { grid-template-columns:repeat(3,minmax(0,1fr)); } }
        @media (min-width:1200px) { #content { grid-template-columns:repeat(4,minmax(0,1fr)); } }
        @media (max-width:640px) {
            .new-arrivals-hero::before { right:-30px; font-size:8px; }
            .new-arrivals-products-shell { padding-inline:12px; }
            .new-arrivals-products-heading { align-items:start; flex-direction:column; gap:10px; margin-bottom:22px; }
            #content { column-gap:10px; row-gap:24px; }
            #content .t9-card-title { padding-left:8px; padding-right:8px; }
            #content .t9-card-price, #content .productPrice { padding-left:8px; padding-right:8px; }
        }
    </style>
</head>

<body class="font-sans bg-white min-h-screen">

    <?php
    $primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
    $hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';
    ?>



    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <?php
    // ==================== FETCH PRODUCTS ====================
    $products_stmt = getProducts(["limit" => 40]);
    $products = $products_stmt ? $products_stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    $products_count = count($products);

    // ==================== GET SIZES — only from these products ====================
    global $db, $sellerId, $storeId;
    $sizes = [];

    if (!empty($products) && !empty($sellerId) && !empty($storeId)) {
        $productPkIds = [];
        foreach ($products as $p) {
            if (!empty($p['id'])) $productPkIds[] = (int)$p['id'];
        }
        $productPkIds = array_values(array_unique($productPkIds));

        if (!empty($productPkIds)) {
            $placeholders = implode(',', array_fill(0, count($productPkIds), '?'));
            $sql = "
                SELECT DISTINCT v.size
                FROM seller_product_advanced_variants v
                INNER JOIN seller_products p ON p.product_id = v.product_id
                WHERE p.seller_id = ?
                  AND p.store_id = ?
                  AND p.status = 1
                  AND (p.visibility = 'publish' OR (p.visibility = 'schedule' AND p.schedule_date <= NOW()))
                  AND p.id IN ($placeholders)
                  AND v.size IS NOT NULL
                  AND v.size != ''
                ORDER BY v.size
            ";
            $stmt = $db->prepare($sql);
            $params = array_merge([$sellerId, $storeId], $productPkIds);
            $stmt->execute($params);
            $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
    }

    $hasSizeFilter = !empty($sizes);

    $categories = getCategories([
        "add_to_menu"     => 1,
        "parent_category" => " ",
    ]);
    ?>

    <!-- ============================================ -->
    <!-- NEW ARRIVALS — editorial collection header     -->
    <!-- ============================================ -->
    <section class="new-arrivals-hero px-4 sm:px-6 py-12 sm:py-16">
        <div class="relative z-10 mx-auto max-w-7xl grid grid-cols-1 md:grid-cols-[1fr_auto] items-end gap-6">
            <div>
                <p class="na-kicker mb-4">The latest edit <span class="text-[#b68a3b]">/</span> 2026</p>
                <h1 class="na-heading font-serifDisplay text-4xl sm:text-5xl lg:text-6xl">New <em>Arrivals</em></h1>
                <p class="na-hero-note mt-4 text-xs sm:text-sm max-w-xl leading-6">Fresh finds, just added. Explore the latest pieces selected for your next favourite look.</p>
            </div>
            <div class="flex items-center gap-4 md:pb-2">
                <div class="h-12 w-px bg-[#d9c9be]"></div>
                <div>
                    <p class="text-2xl font-serifDisplay text-[#65132f]"><span id="productCount"><?= $products_count ?></span></p>
                    <p class="text-[9px] tracking-[.22em] uppercase text-[#766b68] mt-1">Items to discover</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================ -->
    <!-- Category Navigation — horizontal scroll       -->
    <!-- ============================================ -->
    <?php if (!empty($categories) && $categories->rowCount()): ?>
        <section class="bg-[#fcfaf8] border-b border-[#eee7e2] py-5">
            <div class="container mx-auto px-4 max-w-6xl">
                <div id="categoriesScroll" class="category-scroll flex gap-3 overflow-x-auto py-2">
                    <?php foreach ($categories as $cat): ?>
                        <a href="<?= $storeUrl . "category/" . $cat['slug'] ?>"
                           class="category-card na-category-link flex-shrink-0 flex flex-col items-center text-center w-[84px] p-2.5 text-gray-700">
                            <div class="w-12 h-12 mb-2 flex items-center justify-center overflow-hidden rounded-full bg-[#f7f2ee]">
                                <?php if (!empty($cat['icon'])): ?>
                                    <img src="<?= UPLOADS_URL . $cat['icon'] ?>"
                                         alt="<?= htmlspecialchars($cat['name']) ?>"
                                         class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="font-serifDisplay font-bold text-xl"><?= strtoupper(substr($cat['name'], 0, 1)) ?></span>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs truncate w-full"><?= htmlspecialchars($cat['name']) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- ============================================ -->
    <!-- FILTER + SORT BAR — Theme 13 style            -->
    <!-- ============================================ -->
    <div class="w-full bg-white border-b border-gray-200 py-4 px-4 sm:px-6">
        <div class="container mx-auto max-w-6xl flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-700">

            <div class="flex flex-wrap items-center gap-3">

                <!-- Price Range Dropdown -->
                <div class="relative">
                    <button id="priceDropdownBtn" type="button" class="theme13-filter-btn">
                        <i class="bx bx-filter text-base"></i>
                        <span>Price</span>
                        <i class="bx bx-chevron-down text-base"></i>
                        <span id="priceActiveBadge" class="hidden ml-1 text-white text-[9px] px-2 py-0.5 tracking-widest"
                              style="background: <?= htmlspecialchars($primary_color) ?>;">Active</span>
                    </button>

                    <div id="priceDropdown" class="hidden absolute mt-2 theme13-dropdown p-5 w-80 z-50">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs uppercase tracking-widest text-gray-600">Price Range</span>
                            <button id="resetPriceFilter" type="button"
                                    class="text-gray-400 hover:text-[<?= htmlspecialchars($primary_color) ?>] transition text-xs flex items-center gap-1">
                                <i class="bx bx-reset"></i> Reset
                            </button>
                        </div>

                        <div class="flex items-center justify-between mb-4 bg-gray-50 border border-gray-200 p-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] uppercase tracking-widest text-gray-500">Min</span>
                                <span id="minPriceDisplay" class="text-base font-serifDisplay font-bold"
                                      style="color: <?= htmlspecialchars($primary_color) ?>;">
                                    <?= currencyToSymbol($storeCurrency) ?>0
                                </span>
                            </div>
                            <span class="text-gray-300">—</span>
                            <div class="flex items-center gap-2">
                                <span id="maxPriceDisplay" class="text-base font-serifDisplay font-bold"
                                      style="color: <?= htmlspecialchars($primary_color) ?>;">
                                    <?= currencyToSymbol($storeCurrency) ?>10000
                                </span>
                                <span class="text-[10px] uppercase tracking-widest text-gray-500">Max</span>
                            </div>
                        </div>

                        <div class="mb-4 relative pt-2 slider-container">
                            <div class="flex items-center justify-between mb-1 px-1">
                                <span class="text-[10px] text-gray-500"><?= currencyToSymbol($storeCurrency) ?>0</span>
                                <span class="text-[10px] text-gray-500"><?= currencyToSymbol($storeCurrency) ?>10000</span>
                            </div>
                            <div class="relative h-1 bg-gray-200">
                                <div id="sliderRangeFill" class="absolute h-full"
                                     style="left:0%; right:0%; background: <?= htmlspecialchars($primary_color) ?>;"></div>
                            </div>
                            <input type="range" id="minPriceSlider" class="slider-thumb slider-thumb-min" min="0" max="10000" value="0" step="100" />
                            <input type="range" id="maxPriceSlider" class="slider-thumb slider-thumb-max" min="0" max="10000" value="10000" step="100" />
                        </div>

                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php
                            $presets = [[0, 500], [500, 1000], [1000, 2500], [2500, 5000], [5000, 10000]];
                            foreach ($presets as $preset):
                                $pMin = $preset[0];
                                $pMax = $preset[1];
                                $sym = currencyToSymbol($storeCurrency);
                            ?>
                                <button type="button"
                                        class="price-preset text-[10px] px-3 py-1 border border-gray-200 uppercase tracking-widest hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:text-[<?= htmlspecialchars($primary_color) ?>] transition"
                                        data-min="<?= $pMin ?>" data-max="<?= $pMax ?>">
                                    <?= $sym . number_format($pMin) ?> - <?= $sym . number_format($pMax) ?>
                                </button>
                            <?php endforeach; ?>
                        </div>

                        <button id="applyPriceFilter" type="button" class="w-full theme13-cta">Apply Price Range</button>
                    </div>
                </div>

                <!-- Size Filter — only if advanced variants with sizes exist -->
                <?php if ($hasSizeFilter): ?>
                    <div class="relative">
                        <button id="sizeDropdownBtn" type="button" class="theme13-filter-btn">
                            <i class="bx bx-ruler text-base"></i>
                            <span>Size</span>
                            <i class="bx bx-chevron-down text-base"></i>
                            <span id="sizeActiveBadge" class="hidden ml-1 text-white text-[9px] px-2 py-0.5 tracking-widest"
                                  style="background: <?= htmlspecialchars($primary_color) ?>;">Active</span>
                        </button>
                        <div id="sizeDropdown" class="hidden absolute mt-2 theme13-dropdown p-4 w-64 z-50">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-xs uppercase tracking-widest text-gray-600">Select Size</span>
                                <button id="resetSizeFilter" type="button"
                                        class="text-gray-400 hover:text-[<?= htmlspecialchars($primary_color) ?>] transition text-xs flex items-center gap-1">
                                    <i class="bx bx-reset"></i> Reset
                                </button>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <?php foreach ($sizes as $size): ?>
                                    <label class="flex items-center gap-2 text-xs cursor-pointer hover:text-[<?= htmlspecialchars($primary_color) ?>] transition">
                                        <input type="checkbox" class="size-filter" value="<?= htmlspecialchars($size) ?>">
                                        <span><?= htmlspecialchars($size) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div class="mt-4">
                                <button id="applySizeFilter" type="button" class="w-full theme13-cta">Apply Size</button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right: Sort + Clear -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] uppercase tracking-widest text-gray-600 whitespace-nowrap">Sort</span>
                    <select id="sortBy"
                            class="border border-gray-300 px-3 py-2 text-xs uppercase tracking-widest text-gray-700 focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>] min-w-[160px]">
                        <option value="default">Featured</option>
                        <option value="latest">Newest</option>
                        <option value="low-high">Price: Low to High</option>
                        <option value="high-low">Price: High to Low</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
                <button id="clearAllFilters" type="button" class="flex items-center gap-2 theme13-cta">
                    <i class="bx bx-reset text-base"></i>
                    <span>Clear</span>
                </button>
            </div>
        </div>
    </div>


    <!-- ============================================ -->
    <!-- PRODUCTS GRID                                 -->
    <!-- ============================================ -->
    <div class="py-10 sm:py-14 bg-[#fcfaf8]" style="margin-bottom: 80px;">
        <div class="new-arrivals-products-shell">
            <div class="new-arrivals-products-heading">
                <div>
                    <p class="na-section-kicker mb-2">Curated for the new season</p>
                    <h2>Fresh in. <span style="color:#65132f;font-style:italic;">Worth a closer look.</span></h2>
                    <div class="na-heading-rule"></div>
                </div>
                <p class="max-w-sm text-xs sm:text-sm leading-6 text-gray-500">Explore the latest additions, presented in a spacious collection layout.</p>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 items-stretch" id="content">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <?= getProductHtml($product['id']) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Empty State -->
                    <div class="col-span-full text-center py-16">
                        <div class="max-w-lg w-full p-10 mx-auto border border-gray-200">
                            <div class="flex justify-center mb-6">
                                <div class="w-20 h-20 flex items-center justify-center border border-gray-200"
                                     style="background: color-mix(in srgb, <?= htmlspecialchars($primary_color) ?> 8%, white);">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" class="w-12 h-12"
                                         style="stroke: <?= htmlspecialchars($primary_color) ?>;">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5m6 4.125l2.25 2.25m0 0l2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                </div>
                            </div>
                            <h2 class="font-serifDisplay text-2xl text-gray-900 mb-2">No New Products</h2>
                            <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                                Check back soon for new arrivals!
                            </p>
                            <a href="<?= $storeUrl ?>"
                               class="theme13-primary-btn inline-block px-8 py-3 text-xs tracking-[2px] uppercase font-semibold transition-all duration-300">
                                Back to Store
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- THEME 13 PRODUCT COMPONENTS (modal + toast + add-to-cart JS) -->
    <!-- ============================================================ -->


    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <script>
        $(document).ready(function() {

            // Keep the original product order so Clear restores the initial listing.
            const productContainer = document.getElementById('content');
            const originalProductOrder = productContainer
                ? Array.from(productContainer.querySelectorAll('.t9-product-card'))
                : [];

            const MAX_LIMIT = 10000;
            const currencySym = "<?= currencyToSymbol($storeCurrency) ?>";

            let minPrice = 0;
            let maxPrice = MAX_LIMIT;
            let currentSizes = [];

            function formatPrice(p) {
                return currencySym + Number(p).toLocaleString('en-IN');
            }

            function updatePriceDisplays() {
                $('#minPriceDisplay').text(formatPrice(minPrice));
                $('#maxPriceDisplay').text(formatPrice(maxPrice));
                $('#minPriceSlider').val(minPrice);
                $('#maxPriceSlider').val(maxPrice);
                const minPct = (minPrice / MAX_LIMIT) * 100;
                const maxPct = (maxPrice / MAX_LIMIT) * 100;
                $('#sliderRangeFill').css({
                    'left': minPct + '%',
                    'right': (100 - maxPct) + '%'
                });
                if (minPrice > 0 || maxPrice < MAX_LIMIT) $('#priceActiveBadge').removeClass('hidden');
                else $('#priceActiveBadge').addClass('hidden');
            }

            // ============ SLIDER ============
            $('#minPriceSlider').on('input', function() {
                let v = parseInt($(this).val());
                if (v > maxPrice - 100) { v = maxPrice - 100; $(this).val(v); }
                minPrice = Math.max(0, v);
                updatePriceDisplays();
            });
            $('#maxPriceSlider').on('input', function() {
                let v = parseInt($(this).val());
                if (v < minPrice + 100) { v = minPrice + 100; $(this).val(v); }
                maxPrice = Math.min(MAX_LIMIT, v);
                updatePriceDisplays();
            });

            $('.price-preset').on('click', function() {
                $('.price-preset').removeClass('active');
                $(this).addClass('active');
                minPrice = parseInt($(this).data('min')) || 0;
                maxPrice = parseInt($(this).data('max')) || MAX_LIMIT;
                updatePriceDisplays();
            });

            // ============ DROPDOWN TOGGLES ============
            $('#priceDropdownBtn').on('click', function(e) {
                e.stopPropagation();
                $('#priceDropdown').toggleClass('hidden');
                $('#sizeDropdown').addClass('hidden');
            });
            $('#sizeDropdownBtn').on('click', function(e) {
                e.stopPropagation();
                $('#sizeDropdown').toggleClass('hidden');
                $('#priceDropdown').addClass('hidden');
            });
            $('#priceDropdown, #sizeDropdown').on('click', function(e) {
                e.stopPropagation();
            });
            $(document).on('click', function() {
                $('#priceDropdown, #sizeDropdown').addClass('hidden');
            });

            // ============ APPLY FILTERS ============
            $('#applyPriceFilter').on('click', function() {
                applyAllFilters();
                $('#priceDropdown').addClass('hidden');
            });

            $('#resetPriceFilter').on('click', function() {
                minPrice = 0;
                maxPrice = MAX_LIMIT;
                $('.price-preset').removeClass('active');
                updatePriceDisplays();
                applyAllFilters();
                $('#priceDropdown').addClass('hidden');
            });

            $('#applySizeFilter').on('click', function() {
                applyAllFilters();
                $('#sizeDropdown').addClass('hidden');
            });

            $('#resetSizeFilter').on('click', function() {
                $('.size-filter').prop('checked', false);
                applyAllFilters();
                $('#sizeDropdown').addClass('hidden');
            });

            // ============ MAIN FILTER LOGIC ============
            function applyAllFilters() {
                currentSizes = $('.size-filter:checked').map((i, el) => $(el).val().trim()).filter(Boolean).get();

                let visibleCount = 0;
                const totalCount = $('#content .t9-product-card').length;

                $('#content .t9-product-card').each(function() {
                    const $p = $(this);

                    // Price check
                    let price = 0;
                    const priceEl = $p.find('.productPrice').first();
                    if (priceEl.length) {
                        const m = priceEl.text().match(/(\d+\.?\d*)/);
                        if (m) price = parseFloat(m[1]);
                    }
                    const priceMatch = price >= minPrice && price <= maxPrice;

                    // Size check
                    let sizeMatch = true;
                    if (currentSizes.length > 0) {
                        const productSizes = ($p.data('size') + '').split(',').map(s => s.trim()).filter(Boolean);
                        sizeMatch = productSizes.length === 0 || productSizes.some(s => currentSizes.includes(s));
                    }

                    if (priceMatch && sizeMatch) {
                        $p.removeClass('hidden-product');
                        visibleCount++;
                    } else {
                        $p.addClass('hidden-product');
                    }
                });

                // Update badges
                if (currentSizes.length > 0) $('#sizeActiveBadge').removeClass('hidden');
                else $('#sizeActiveBadge').addClass('hidden');

                // Update count
                $('#productCount').text(visibleCount);
// Apply sorting
                applySorting();
            }

            // ============ SORTING ============
            $('#sortBy').on('change', function() {
                applySorting($(this).val());
            });

            function applySorting(sortType) {
                sortType = sortType || $('#sortBy').val() || 'default';

                const container = document.getElementById('content');
                if (!container) return;

                if (sortType === 'default') {
                    originalProductOrder.forEach(function(card) {
                        if (card.isConnected || card.parentElement === container) {
                            container.appendChild(card);
                        }
                    });
                    return;
                }
                const visible = Array.from(container.querySelectorAll('.t9-product-card:not(.hidden-product)'));
                const hidden = Array.from(container.querySelectorAll('.t9-product-card.hidden-product'));

                visible.sort((a, b) => {
                    const pa = getPrice(a);
                    const pb = getPrice(b);
                    const ia = parseInt(a.getAttribute('data-id')) || 0;
                    const ib = parseInt(b.getAttribute('data-id')) || 0;

                    switch (sortType) {
                        case 'low-high': return pa - pb;
                        case 'high-low': return pb - pa;
                        case 'latest':   return ib - ia;
                        case 'oldest':   return ia - ib;
                        default:         return 0;
                    }
                });

                container.innerHTML = '';
                visible.forEach(p => container.appendChild(p));
                hidden.forEach(p => container.appendChild(p));
            }

            function getPrice(el) {
                const priceEl = el.querySelector('.productPrice');
                if (priceEl) {
                    const m = priceEl.textContent.match(/(\d+\.?\d*)/);
                    if (m) return parseFloat(m[1]);
                }
                return 0;
            }

            // ============ CLEAR ALL ============
            function clearAll() {
                minPrice = 0;
                maxPrice = MAX_LIMIT;
                currentSizes = [];

                $('.price-preset').removeClass('active');
                $('.size-filter').prop('checked', false);
                $('#sortBy').val('default');

                updatePriceDisplays();

                // Remove active filter states and show every product again.
                $('#priceActiveBadge, #sizeActiveBadge').addClass('hidden');
                $('#content .t9-product-card').removeClass('hidden-product');

                // Restore the original product order after any previous sorting.
                originalProductOrder.forEach(function(card) {
                    productContainer.appendChild(card);
                });

                $('#productCount').text(originalProductOrder.length);
                $('#priceDropdown, #sizeDropdown').addClass('hidden');
            }

            $('#clearAllFilters').on('click', clearAll);

            // ============ INIT ============
            updatePriceDisplays();
        });
    </script>

</body>

</html>