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

        .sort-dropdown select:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: none;
        }

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

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .theme13-filter-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1rem;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
            font-size: 0.7rem;
            letter-spacing: 0.15em;
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
            font-size: 0.7rem;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            font-weight: 500;
            padding: 0.65rem 1rem;
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

        .slider-container {
            position: relative;
            height: 28px;
        }

        .slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            position: absolute;
            top: 50%;
            left: 0;
            transform: translateY(-50%);
            width: 100%;
            height: 4px;
            background: transparent;
            outline: none;
            margin: 0;
            padding: 0;
            pointer-events: auto;
            cursor: pointer;
            z-index: 2;
        }

        .slider-thumb-min {
            z-index: 3;
        }

        .slider-thumb-max {
            z-index: 2;
        }

        .slider-thumb::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
            border: 2px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .25);
        }

        .slider-thumb::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--primary-color);
            cursor: pointer;
            border: 2px solid #fff;
        }

        .slider-thumb::-webkit-slider-runnable-track {
            height: 2px;
            background: transparent;
            border: none;
        }

        .slider-thumb::-moz-range-track {
            height: 2px;
            background: transparent;
            border: none;
        }

        .relative.h-1 {
            height: 4px !important;
        }

        .price-preset.active {
            background: var(--primary-color) !important;
            color: #fff !important;
            border-color: var(--primary-color) !important;
        }

        #sliderRangeFill {
            transition: left .1s ease, right .1s ease;
        }

        .hidden-product {
            display: none !important;
        }

        /* Match the New Arrivals product-card sizing and spacing */
        /* Spacious, index-page inspired product grid: every product card stays large */
        .new-arrivals-products-shell { width:100%; max-width:1720px; margin-inline:auto; padding-inline:clamp(14px,3.5vw,58px); }
        .new-arrivals-products-heading { display:flex; align-items:end; justify-content:space-between; gap:20px; margin-bottom:28px; }
        .new-arrivals-products-heading .na-section-kicker { color:#8a6a4b; font-size:10px; letter-spacing:.24em; text-transform:uppercase; }
        .new-arrivals-products-heading h2 { color:#251719; font-family:Georgia,serif; font-size:clamp(26px,3vw,40px); line-height:1.1; letter-spacing:-.035em; }
        .new-arrivals-products-heading .na-heading-rule { width:58px; height:2px; margin-top:13px; background:#b68a3b; }
        #content { align-items:stretch; grid-template-columns:repeat(2,minmax(0,1fr)); column-gap:clamp(12px,2vw,28px); row-gap:clamp(28px,3.5vw,48px); }
        #content > .t9-product-card, #content > .group { min-width:0; width:100%; border:1px solid #eee7e2; background:#fff; transition:transform .28s ease,box-shadow .28s ease,border-color .28s ease; }
        #content > .t9-product-card:hover, #content > .group:hover { transform:translateY(-6px); border-color:#c8a878; box-shadow:0 18px 36px rgba(52,27,31,.11); }
        #content .t9-card-media { background:#f7f2ee; }
        #content .t9-card-img { width:100%; aspect-ratio:3 / 4; object-fit:cover; transition:transform .6s cubic-bezier(.2,.7,.2,1); }
        #content > .t9-product-card:hover .t9-card-img, #content > .group:hover .t9-card-img { transform:scale(1.045); }
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
    if (isset($_GET['q'])) {
        $q = $_GET['q'];
    } else {
        redirect($storeUrl);
    }

    $count = getProducts(array("count" => true, "q" => $q));
    foreach ($count as $key => $value) {
        $count = $value[0];
    }

    $category = $_GET['category'] ?? '';

    // Get search results
    $search_products_stmt = getProducts(array("q" => $q, "category" => $category));
    $search_products = $search_products_stmt ? $search_products_stmt->fetchAll(PDO::FETCH_ASSOC) : [];
    $search_products_count = count($search_products);

    // ==================== GET SIZES for current search results ====================
    // Only from ADVANCED variants with a valid size, filtered by category exactly like category page
    global $db, $sellerId, $storeId;
    $sizes = [];

    if (!empty($search_products) && !empty($sellerId) && !empty($storeId)) {

        // Collect the PK ids (seller_products.id) from search results
        $productPkIds = [];
        foreach ($search_products as $sp) {
            if (!empty($sp['id'])) {
                $productPkIds[] = (int)$sp['id'];
            }
        }
        $productPkIds = array_values(array_unique($productPkIds));

        if (!empty($productPkIds)) {
            $placeholders = implode(',', array_fill(0, count($productPkIds), '?'));

            // 👇 Same structure as category page, but filtered by seller_products.id (PK)
            $sql = "
                SELECT DISTINCT v.size
                FROM seller_product_advanced_variants v
                INNER JOIN seller_products p 
                    ON p.product_id = v.product_id
                WHERE p.seller_id = ?
                  AND p.store_id  = ?
                  AND p.status = 1
                  AND (p.visibility = 'publish' 
                       OR (p.visibility = 'schedule' AND p.schedule_date <= NOW()))
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

    // Show size filter only if search results have ADVANCED variants with sizes
    $hasSizeFilter = !empty($sizes);
    ?>

    <!-- Search Header -->
    <div class="w-full bg-white border-b border-gray-200 py-10 px-4 sm:px-6">
        <div class="container mx-auto max-w-6xl text-center">
            <p class="text-xs tracking-[3px] text-gray-500 uppercase">Search Results</p>
            <h1 class="font-serifDisplay text-3xl sm:text-4xl text-gray-900 mt-2">
                <?= htmlspecialchars($q) ?>
            </h1>
            <p class="text-sm text-gray-500 mt-2 tracking-widest uppercase">
                <span id="productCount"><?= $search_products_count ?></span>
                <?= $search_products_count == 1 ? 'product' : 'products' ?> found
            </p>
        </div>
    </div>

    <!-- FILTER + SORT BAR -->
    <div class="w-full bg-white border-b border-gray-200 py-4 px-4 sm:px-6">
        <div class="container mx-auto max-w-6xl flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-700">

            <div class="flex flex-wrap items-center gap-3">

                <!-- Price Range -->
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
                                <span id="minPriceDisplay" class="text-base font-serifDisplay font-bold" style="color: <?= htmlspecialchars($primary_color) ?>;">
                                    <?= currencyToSymbol($storeCurrency) ?>0
                                </span>
                            </div>
                            <span class="text-gray-300">—</span>
                            <div class="flex items-center gap-2">
                                <span id="maxPriceDisplay" class="text-base font-serifDisplay font-bold" style="color: <?= htmlspecialchars($primary_color) ?>;">
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
                                <div id="sliderRangeFill" class="absolute h-full" style="left:0%; right:0%; background: <?= htmlspecialchars($primary_color) ?>;"></div>
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

                        <button id="applyPriceFilter" type="button" class="w-full theme13-cta">
                            Apply Price Range
                        </button>
                    </div>
                </div>

                <!-- Size — shows only if search results have ADVANCED variants with sizes -->
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
                                <button id="applySizeFilter" type="button" class="w-full theme13-cta">
                                    Apply Size
                                </button>
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

    <!-- PRODUCTS AREA -->
    <div class="py-8 bg-white">
        <div class="new-arrivals-products-shell">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4 items-stretch" id="content">
                <?php if (!empty($search_products)): ?>
                    <?php foreach ($search_products as $product): ?>
                        <?= getProductHtml($product['id']) ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-16">
                        <div class="max-w-lg w-full p-10 mx-auto border border-gray-200">
                            <div class="flex justify-center mb-6">
                                <div class="w-20 h-20 flex items-center justify-center border border-gray-200"
                                    style="background: color-mix(in srgb, <?= htmlspecialchars($primary_color) ?> 8%, white);">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" class="w-12 h-12"
                                        style="stroke: <?= htmlspecialchars($primary_color) ?>;">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>

                            <h2 class="font-serifDisplay text-2xl text-gray-900 mb-2">
                                No Products Found
                            </h2>
                            <p class="text-gray-500 text-sm mb-6 leading-relaxed">
                                We couldn't find anything matching "<?= htmlspecialchars($q) ?>"
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

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <script>
        $(document).ready(function() {

            const MAX_LIMIT = 10000;
            const urlParams = new URLSearchParams(window.location.search);
            let minPrice = parseInt(urlParams.get('min_price')) || 0;
            let maxPrice = parseInt(urlParams.get('max_price')) || MAX_LIMIT;
            const currencySym = "<?= currencyToSymbol($storeCurrency) ?>";

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

            $('#minPriceSlider').on('input', function() {
                let v = parseInt($(this).val());
                if (v > maxPrice - 100) {
                    v = maxPrice - 100;
                    $(this).val(v);
                }
                minPrice = Math.max(0, v);
                updatePriceDisplays();
            });
            $('#maxPriceSlider').on('input', function() {
                let v = parseInt($(this).val());
                if (v < minPrice + 100) {
                    v = minPrice + 100;
                    $(this).val(v);
                }
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

            $('#priceDropdownBtn').on('click', function(e) {
                e.stopPropagation();
                $('#priceDropdown').toggleClass('hidden');
                $('#sizeDropdown').addClass('hidden');
            });
            $('#priceDropdown').on('click', function(e) {
                e.stopPropagation();
            });
            $(document).on('click', function() {
                $('#priceDropdown').addClass('hidden');
                $('#sizeDropdown').addClass('hidden');
            });

            $('#applyPriceFilter').on('click', function() {
                const url = new URL(window.location.href);
                if (minPrice > 0) url.searchParams.set('min_price', minPrice);
                else url.searchParams.delete('min_price');
                if (maxPrice < MAX_LIMIT) url.searchParams.set('max_price', maxPrice);
                else url.searchParams.delete('max_price');
                window.location.href = url.toString();
            });

            $('#resetPriceFilter').on('click', function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('min_price');
                url.searchParams.delete('max_price');
                window.location.href = url.toString();
            });

            // ==================== SIZE FILTER ====================
            function applySizeFilterToDOM() {
                const checked = $(".size-filter:checked").map((i, el) => $(el).val().trim()).get();
                const unique = [...new Set(checked)];

                $("#content > .group").each(function() {
                    if (unique.length === 0) {
                        $(this).removeClass("hidden-product");
                    } else {
                        const productSizes = ($(this).data("size") + "").split(",").map(s => s.trim()).filter(Boolean);
                        if (productSizes.length === 0) {
                            $(this).removeClass("hidden-product");
                        } else if (productSizes.some(s => unique.includes(s))) {
                            $(this).removeClass("hidden-product");
                        } else {
                            $(this).addClass("hidden-product");
                        }
                    }
                });

                if (unique.length > 0) $("#sizeActiveBadge").removeClass("hidden");
                else $("#sizeActiveBadge").addClass("hidden");

                updateProductCount();
            }

            function updateProductCount() {
                const total = $("#content > .group").filter(":not(.hidden-product)").length;
                $("#productCount").text(total);
            }

            $('#sizeDropdownBtn').on('click', function(e) {
                e.stopPropagation();
                $('#sizeDropdown').toggleClass('hidden');
                $('#priceDropdown').addClass('hidden');
            });
            $('#sizeDropdown').on('click', function(e) {
                e.stopPropagation();
            });

            $('#applySizeFilter').on('click', function() {
                applySizeFilterToDOM();
                $('#sizeDropdown').addClass('hidden');
            });

            $('#resetSizeFilter').on('click', function() {
                $('.size-filter').prop('checked', false);
                applySizeFilterToDOM();
                $('#sizeDropdown').addClass('hidden');
            });

            $('#clearAllFilters').on('click', function() {
                const url = new URL(window.location.href);
                url.searchParams.delete('min_price');
                url.searchParams.delete('max_price');
                window.location.href = url.toString();
            });

            $('#sortBy').on('change', function() {
                sortProducts($(this).val());
            });

            function sortProducts(sortType) {
                const container = document.getElementById('content');
                if (!container) return;
                const products = Array.from(container.querySelectorAll('.group'));
                if (!products.length) return;

                switch (sortType) {
                    case 'low-high':
                        products.sort((a, b) => getProductPrice(a) - getProductPrice(b));
                        break;
                    case 'high-low':
                        products.sort((a, b) => getProductPrice(b) - getProductPrice(a));
                        break;
                    case 'latest':
                        products.sort((a, b) => getProductId(b) - getProductId(a));
                        break;
                    case 'oldest':
                        products.sort((a, b) => getProductId(a) - getProductId(b));
                        break;
                    default:
                        return;
                }
                container.innerHTML = '';
                products.forEach(p => container.appendChild(p));
            }

            function getProductPrice(el) {
                const selectors = ['.productPrice', '[class*="price"]'];
                for (const s of selectors) {
                    const node = el.querySelector(s);
                    if (node) {
                        const m = (node.textContent || '').match(/(\d+\.?\d*)/);
                        if (m) return parseFloat(m[1]);
                    }
                }
                return 0;
            }

            function getProductId(el) {
                const idAttr = el.getAttribute('data-id') || el.getAttribute('data-product-id');
                if (idAttr) return parseInt(idAttr);
                const btn = el.querySelector('.customAddToCartBtn');
                if (btn && btn.dataset.id) return parseInt(btn.dataset.id);
                return 0;
            }

            updatePriceDisplays();

        });
    </script>

</body>

</html>