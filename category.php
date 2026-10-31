<?php include_once __DIR__ . "/includes/files_includes.php";
// ==================== DB COLORS ====================
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

// ==================== CATEGORY DETECTION ====================
if (isset($_GET['slug']) && getData("id", "seller_product_categories", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'")) {
    $slug = $_GET['slug'];
    $id = getData("id", "seller_product_categories", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
    $name = getData("name", "seller_product_categories", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
    $icon = getData("icon", "seller_product_categories", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
    $db->prepare("UPDATE seller_product_categories SET visitors = visitors+1 WHERE id = :id")->execute(['id' => $id]);
} else {
    redirect($storeUrl);
}

// ==================== GET SIZES — only sizes IN THIS CATEGORY ====================
global $db, $sellerId, $storeId;
$sizes = [];

if (!empty($sellerId) && !empty($storeId) && !empty($id)) {
    $sql = "
        SELECT DISTINCT v.size
        FROM seller_product_advanced_variants v
        INNER JOIN seller_products p 
            ON p.product_id = v.product_id
        WHERE p.seller_id = :seller_id
          AND p.store_id  = :store_id
          AND p.status = 1
          AND p.category = :category_id
          AND (p.visibility = 'publish' 
               OR (p.visibility = 'schedule' AND p.schedule_date <= NOW()))
          AND v.size IS NOT NULL
          AND v.size != ''
        ORDER BY v.size
    ";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':seller_id'   => $sellerId,
        ':store_id'    => $storeId,
        ':category_id' => $id
    ]);
    $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Show size filter only if this category has sized products
$hasSizeFilter = !empty($sizes);

// ==================== GET MAX PRICE ====================
$maxPrice = 10000;
$maxPriceSql = "
    SELECT MAX(price) as max_price 
    FROM seller_products 
    WHERE seller_id = :seller_id 
      AND store_id = :store_id 
      AND status = 1
      AND (visibility = 'publish' 
           OR (visibility = 'schedule' AND schedule_date <= NOW()))
";
$maxPriceStmt = $db->prepare($maxPriceSql);
$maxPriceStmt->execute([':seller_id' => $sellerId, ':store_id' => $storeId]);
$maxPriceResult = $maxPriceStmt->fetch(PDO::FETCH_ASSOC);
if ($maxPriceResult && $maxPriceResult['max_price'] > 0) {
    $maxPrice = ceil($maxPriceResult['max_price'] / 100) * 100;
}

// ==================== MOBILE DETECTION ====================
$isMobile = false;
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
if (
    stripos($userAgent, 'Mobile') !== false ||
    stripos($userAgent, 'Android') !== false ||
    stripos($userAgent, 'Tablet') !== false
) {
    $isMobile = true;
}

// ==================== CATEGORIES ====================
$categories = getCategories([
    "add_to_menu" => 1,
    "parent_category" => " ",
    "limit" => $isMobile ? 15 : 7
]);

// ==================== PRODUCT COUNT ====================
$countStmt = getProducts(["count" => true, "category" => $id]);
$totalProductsCount = $countStmt ? $countStmt->fetchColumn() : 0;

// ==================== PRICE PRESETS ====================
$presets = [];
if ($maxPrice >= 100) {
    $presets[] = [0, round($maxPrice * 0.05)];
    $presets[] = [round($maxPrice * 0.05), round($maxPrice * 0.10)];
    $presets[] = [round($maxPrice * 0.10), round($maxPrice * 0.25)];
    $presets[] = [round($maxPrice * 0.25), round($maxPrice * 0.50)];
    $presets[] = [round($maxPrice * 0.50), $maxPrice];
} else {
    $presets[] = [0, $maxPrice];
}

// ==================== SUB-CATEGORIES ====================
$currentParent = getData("parent_category", "seller_product_categories", "id = '$id'");
$parentId = ($currentParent > 0) ? $currentParent : $id;
$hasSubCategories = getData("id", "seller_product_categories", "parent_category = '$parentId' AND seller_id = '$sellerId'");

if ($hasSubCategories) {
    $sub_categories = getCategories(["parent_category" => $parentId]);
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
</head>

<body class="font-sans bg-white min-h-screen">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- Hidden category id for JS -->
    <span id="category" style="display:none;"><?= $id ?></span>


    <!-- ==================== THEME 17 EDITORIAL CATEGORY STRIP ==================== -->
    <?php if ($categories && $categories->rowCount()): ?>
        <section id="categorySection" class="t17-category-section">
            <div class="t17-category-topline">
                <div>
                    <span class="t17-eyebrow">EXPLORE THE COLLECTION</span>
                    <h2>Shop by <em>category</em></h2>
                </div>
                <span class="t17-category-note">FIND YOUR NEXT FAVOURITE</span>
            </div>
            <button id="prevBtn" type="button" aria-label="Previous categories" class="t17-carousel-arrow t17-prev">&#8592;</button>
            <button id="nextBtn" type="button" aria-label="Next categories" class="t17-carousel-arrow t17-next">&#8594;</button>
            <div class="t17-category-viewport">
                <div id="categoryCarousel" class="t17-category-track no-scrollbar">
                    <?php foreach ($categories as $category):
                        $catIcon = !empty($category['icon']) ? UPLOADS_URL . $category['icon'] : null;
                        $firstLetter = strtoupper(substr($category['name'], 0, 1));
                        $isCurrentCategory = (($category['slug'] ?? '') === ($slug ?? ''));
                    ?>
                        <a href="<?= htmlspecialchars($storeUrl . 'category/' . $category['slug']) ?>"
                            class="category-item t17-category-card <?= $isCurrentCategory ? 'is-current' : '' ?>">
                            <span class="t17-card-index"><?= str_pad((string)($categoryIndex ?? 1), 2, '0', STR_PAD_LEFT) ?></span>
                            <div class="t17-category-image">
                                <?php if ($catIcon): ?>
                                    <img src="<?= htmlspecialchars($catIcon) ?>" alt="<?= htmlspecialchars($category['name']) ?>" loading="lazy">
                                <?php else: ?>
                                    <span class="t17-category-letter"><?= htmlspecialchars($firstLetter) ?></span>
                                <?php endif; ?>
                                <span class="t17-image-shade"></span>
                                <span class="t17-category-open" aria-hidden="true">&#8599;</span>
                            </div>
                            <div class="t17-category-label">
                                <span><?= htmlspecialchars($category['name']) ?></span>
                                <small><?= $isCurrentCategory ? 'YOU ARE HERE' : 'EXPLORE' ?></small>
                            </div>
                        </a>
                        <?php $categoryIndex = ($categoryIndex ?? 1) + 1; ?>
                    <?php endforeach;
                    $categoryIndex = 1; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>


    <!-- ==================== THEME 17 SUB-CATEGORY RAIL ==================== -->
    <?php if (!empty($hasSubCategories) && !empty($sub_categories)): ?>
        <section class="t17-subcategory-section">
            <div class="t17-subcategory-heading"><span>IN THIS COLLECTION</span><b>Explore subcategories</b></div>
            <div class="t17-subcategory-rail-wrap">
                <button id="subPrev" type="button" aria-label="Previous subcategories" class="t17-sub-arrow">&#8592;</button>
                <div id="subCategoryCarousel" class="t17-subcategory-rail no-scrollbar">
                    <?php foreach ($sub_categories as $sub):
                        $subIcon = !empty($sub['icon']) ? UPLOADS_URL . $sub['icon'] : null;
                        $firstLetter = strtoupper(substr($sub['name'], 0, 1));
                        $isActive = ($sub['id'] == $id);
                    ?>
                        <a href="<?= htmlspecialchars($storeUrl . 'category/' . $sub['slug']) ?>"
                            class="t17-subcategory-pill <?= $isActive ? 'is-active' : '' ?>">
                            <span class="t17-subcategory-thumb">
                                <?php if ($subIcon): ?>
                                    <img src="<?= htmlspecialchars($subIcon) ?>" alt="" loading="lazy">
                                <?php else: ?>
                                    <b><?= htmlspecialchars($firstLetter) ?></b>
                                <?php endif; ?>
                            </span>
                            <span><?= htmlspecialchars($sub['name']) ?></span>
                            <i aria-hidden="true">&#8594;</i>
                        </a>
                    <?php endforeach; ?>
                </div>
                <button id="subNext" type="button" aria-label="Next subcategories" class="t17-sub-arrow">&#8594;</button>
            </div>
        </section>
    <?php endif; ?>


    <!-- ==================== PAGE HEADER — Theme 17 ==================== -->
    <div class="w-full bg-white border-b border-gray-200 py-10 px-4 sm:px-6">
        <div class="container mx-auto max-w-6xl text-center">
            <p class="text-xs tracking-[3px] text-gray-500 uppercase">Category</p>
            <h1 class="font-serifDisplay text-3xl sm:text-4xl text-gray-900 mt-2">
                <?= htmlspecialchars($name) ?>
            </h1>
            <p class="text-sm text-gray-500 mt-2 tracking-widest uppercase">
                <span id="productCount"><?= $totalProductsCount ?> products</span>
            </p>
        </div>
    </div>


    <!-- ==================== FILTER + SORT BAR — Theme 17 ==================== -->
    <div class="w-full bg-white border-b border-gray-200 py-4 px-4 sm:px-6 desktop-filter-bar">
        <div class="container mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-700">

            <!-- Left: Filter buttons -->
            <div class="flex flex-wrap items-center gap-3">

                <!-- Price Range -->
                <div class="relative">
                    <button id="priceDropdownBtn"
                        class="flex items-center gap-2 px-4 py-2 border border-gray-300 bg-white text-gray-700 text-xs uppercase tracking-widest hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:text-[<?= htmlspecialchars($primary_color) ?>] transition-all duration-300">
                        <i class="bx bx-filter text-base"></i>
                        <span>Price</span>
                        <i class="bx bx-chevron-down text-base"></i>
                        <span id="priceActiveBadge" class="hidden ml-1 text-white text-[9px] px-2 py-0.5 tracking-widest"
                            style="background: <?= htmlspecialchars($primary_color) ?>;">Active</span>
                    </button>

                    <div id="priceDropdown"
                        class="hidden absolute mt-2 bg-white border border-gray-200 p-5 w-80 z-50">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-xs uppercase tracking-widest text-gray-600">Price Range</span>
                            <button id="resetPriceFilter"
                                class="text-gray-400 hover:text-[<?= htmlspecialchars($primary_color) ?>] transition text-xs flex items-center gap-1">
                                <i class="bx bx-reset"></i> Reset
                            </button>
                        </div>
                        <div class="flex items-center justify-between mb-4 bg-gray-50 border border-gray-200 p-3">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] uppercase tracking-widest text-gray-500">Min</span>
                                <span id="minPriceDisplay" class="text-base font-serifDisplay font-bold" style="color: <?= htmlspecialchars($primary_color) ?>;">₹0</span>
                            </div>
                            <span class="text-gray-300">—</span>
                            <div class="flex items-center gap-2">
                                <span id="maxPriceDisplay" class="text-base font-serifDisplay font-bold" style="color: <?= htmlspecialchars($primary_color) ?>;">₹<?= number_format($maxPrice) ?></span>
                                <span class="text-[10px] uppercase tracking-widest text-gray-500">Max</span>
                            </div>
                        </div>
                        <div class="mb-4 relative pt-2 slider-container">
                            <div class="flex items-center justify-between mb-1 px-1">
                                <span class="text-[10px] text-gray-500">₹0</span>
                                <span class="text-[10px] text-gray-500">₹<?= number_format($maxPrice) ?></span>
                            </div>
                            <div class="relative h-1 bg-gray-200">
                                <div id="sliderRangeFill" class="absolute h-full" style="left: 0%; right: 0%; background: <?= htmlspecialchars($primary_color) ?>;"></div>
                            </div>
                            <input type="range" id="minPriceSlider" class="slider-thumb slider-thumb-min" min="0" max="<?= $maxPrice ?>" value="0" step="100" />
                            <input type="range" id="maxPriceSlider" class="slider-thumb slider-thumb-max" min="0" max="<?= $maxPrice ?>" value="<?= $maxPrice ?>" step="100" />
                        </div>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <?php foreach ($presets as $preset):
                                $pMin = $preset[0];
                                $pMax = $preset[1];
                                $label = '₹' . number_format($pMin) . ' - ₹' . number_format($pMax);
                            ?>
                                <button class="price-preset text-[10px] px-3 py-1 border border-gray-200 uppercase tracking-widest hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:text-[<?= htmlspecialchars($primary_color) ?>] transition"
                                    data-min="<?= $pMin ?>" data-max="<?= $pMax ?>"><?= $label ?></button>
                            <?php endforeach; ?>
                        </div>
                        <button id="applyPriceFilter"
                            class="w-full text-white text-xs uppercase tracking-widest font-medium py-2.5 transition theme13-cat-cta"
                            style="background: <?= htmlspecialchars($primary_color) ?>;">
                            Apply Price Range
                        </button>
                    </div>
                </div>

                <!-- Size — only if category has sized products -->
                <?php if ($hasSizeFilter): ?>
                    <div class="relative">
                        <button id="sizeDropdownBtn"
                            class="flex items-center gap-2 px-4 py-2 border border-gray-300 bg-white text-gray-700 text-xs uppercase tracking-widest hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:text-[<?= htmlspecialchars($primary_color) ?>] transition-all duration-300">
                            <i class="bx bx-ruler text-base"></i>
                            <span>Size</span>
                            <i class="bx bx-chevron-down text-base"></i>
                            <span id="sizeActiveBadge" class="hidden ml-1 text-white text-[9px] px-2 py-0.5 tracking-widest"
                                style="background: <?= htmlspecialchars($primary_color) ?>;">Active</span>
                        </button>
                        <div id="sizeDropdown" class="hidden absolute mt-2 bg-white border border-gray-200 p-4 w-64 z-50">
                            <div class="mb-3 flex items-center justify-between">
                                <span class="text-xs uppercase tracking-widest text-gray-600">Select Size</span>
                                <button id="resetSizeFilter" class="text-gray-400 hover:text-[<?= htmlspecialchars($primary_color) ?>] transition text-xs flex items-center gap-1">
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
                                <button id="applySizeFilter"
                                    class="w-full text-white text-xs uppercase tracking-widest font-medium py-2.5 transition theme13-cat-cta"
                                    style="background: <?= htmlspecialchars($primary_color) ?>;">
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
                    <select id="sortBy" class="border border-gray-300 px-3 py-2 text-xs uppercase tracking-widest text-gray-700 focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>] min-w-[140px]">
                        <option value="featured">Featured</option>
                        <option value="newest">Newest</option>
                        <option value="low-high">Price: Low to High</option>
                        <option value="high-low">Price: High to Low</option>
                    </select>
                </div>
                <button id="clearAllFilters"
                    class="flex items-center gap-2 px-4 py-2 text-white text-xs uppercase tracking-widest hover:opacity-90 transition theme13-cat-cta"
                    style="background: <?= htmlspecialchars($primary_color) ?>;">
                    <i class="bx bx-reset text-base"></i>
                    <span>Clear</span>
                </button>
            </div>
        </div>
    </div>


    <!-- ==================== MOBILE FILTER BUTTON ==================== -->
    <div class="sm:hidden bg-white border-b border-gray-200 py-3 px-4 flex justify-between items-center">
        <h3 class="font-serifDisplay text-base text-gray-900 uppercase"><?= htmlspecialchars($name) ?></h3>
        <div class="flex items-center gap-3">
            <span class="text-gray-500 text-xs tracking-widest" id="mobileProductCount"><?= $totalProductsCount ?> Products</span>
            <button id="openMobileFilter"
                class="flex items-center gap-2 text-white text-xs uppercase tracking-widest px-4 py-2"
                style="background: <?= htmlspecialchars($primary_color) ?>;">
                <i class="bx bx-filter-alt text-base"></i> Filter
            </button>
        </div>
    </div>


    <!-- ==================== PRODUCT AREA ==================== -->
    <div class="py-4 bg-white">
        <div class="!px-1 lg:container">
            <div id="contentWrapper" class="w-full bg-white py-3 px-1 sm:px-2" style="min-height: 50vh;">

                <div id="content"
                    class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 transition-all duration-300">

                    <div id="loaderBox" class="col-span-full flex flex-col items-center justify-center py-16">
                        <div class="relative w-14 h-14 mb-3">
                            <div class="absolute inset-0 border-2" style="border-color: <?= htmlspecialchars($primary_color) ?>; opacity: 0.25;"></div>
                            <div class="absolute inset-0 border-2 border-transparent animate-spin"
                                style="border-top-color: <?= htmlspecialchars($primary_color) ?>; border-right-color: <?= htmlspecialchars($primary_color) ?>;"></div>
                        </div>
                        <p class="text-gray-700 text-sm tracking-widest uppercase">Loading...</p>
                    </div>

                </div>

                <!-- Empty State -->
                <div id="emptyMessage" class="hidden flex-col items-center justify-center text-center w-full min-h-[60vh] px-4">
                    <div class="p-8 sm:p-10 max-w-lg w-full mx-auto">
                        <div class="flex flex-col items-center space-y-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 14l2-2 4 4m0 0l2-2m-6 6v-6m0 0H6m6 0h6" />
                            </svg>
                            <h2 class="font-serifDisplay text-2xl text-gray-900">No products found</h2>
                            <p class="text-gray-500 text-sm leading-relaxed max-w-md">
                                We couldn't find anything matching your filters. Try adjusting your price range or size selection.
                            </p>
                            <a href="<?= $storeUrl ?>"
                                class="inline-block mt-3 px-6 py-2.5 text-white text-xs uppercase tracking-widest theme13-cat-cta"
                                style="background: <?= htmlspecialchars($primary_color) ?>;">
                                Back to Shop
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- ==================== MOBILE FILTER SIDEBAR ==================== -->
    <div id="mobileFilterSidebar"
        class="fixed top-0 right-0 w-80 sm:w-96 h-full bg-white border-l border-gray-200 transform translate-x-full transition-transform duration-300 ease-in-out z-[9999] overflow-y-auto">
        <div class="flex items-center justify-between p-4 border-b border-gray-200">
            <h2 class="font-serifDisplay text-lg text-gray-900">Filter & Sort</h2>
            <button id="closeMobileFilter" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="p-4 space-y-6">
            <div>
                <h3 class="text-xs uppercase tracking-widest text-gray-600 mb-3">Price Range</h3>
                <div class="flex items-center justify-between mb-3 bg-gray-50 border border-gray-200 p-3">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-gray-500">Min</span>
                        <span id="minPriceMobileDisplay" class="text-base font-serifDisplay font-bold" style="color: <?= htmlspecialchars($primary_color) ?>;">₹0</span>
                    </div>
                    <span class="text-gray-300">—</span>
                    <div class="flex items-center gap-2">
                        <span id="maxPriceMobileDisplay" class="text-base font-serifDisplay font-bold" style="color: <?= htmlspecialchars($primary_color) ?>;">₹<?= number_format($maxPrice) ?></span>
                        <span class="text-[10px] text-gray-500">Max</span>
                    </div>
                </div>
                <div class="relative pt-2 slider-container">
                    <div class="flex items-center justify-between mb-1 px-1">
                        <span class="text-[10px] text-gray-500">₹0</span>
                        <span class="text-[10px] text-gray-500">₹<?= number_format($maxPrice) ?></span>
                    </div>
                    <div class="relative h-1 bg-gray-200">
                        <div id="mobileSliderRangeFill" class="absolute h-full" style="left: 0%; right: 0%; background: <?= htmlspecialchars($primary_color) ?>;"></div>
                    </div>
                    <input type="range" id="minPriceMobileSlider" class="slider-thumb slider-thumb-min" min="0" max="<?= $maxPrice ?>" value="0" step="100" />
                    <input type="range" id="maxPriceMobileSlider" class="slider-thumb slider-thumb-max" min="0" max="<?= $maxPrice ?>" value="<?= $maxPrice ?>" step="100" />
                </div>
                <div class="flex flex-wrap gap-2 mt-3">
                    <?php foreach ($presets as $preset):
                        $pMin = $preset[0];
                        $pMax = $preset[1];
                        $label = '₹' . number_format($pMin) . ' - ₹' . number_format($pMax);
                    ?>
                        <button class="mobile-preset text-[10px] px-3 py-1 border border-gray-200 uppercase tracking-widest hover:border-[<?= htmlspecialchars($primary_color) ?>] hover:text-[<?= htmlspecialchars($primary_color) ?>] transition"
                            data-min="<?= $pMin ?>" data-max="<?= $pMax ?>"><?= $label ?></button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Size — only if category has sized products -->
            <?php if ($hasSizeFilter): ?>
                <div>
                    <h3 class="text-xs uppercase tracking-widest text-gray-600 mb-2">Size</h3>
                    <div class="grid grid-cols-3 gap-2">
                        <?php foreach ($sizes as $size): ?>
                            <label class="flex items-center gap-2 text-xs cursor-pointer hover:text-[<?= htmlspecialchars($primary_color) ?>] transition">
                                <input type="checkbox" class="size-filter-mobile" value="<?= htmlspecialchars($size) ?>">
                                <span><?= htmlspecialchars($size) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div>
                <h3 class="text-xs uppercase tracking-widest text-gray-600 mb-2">Sort by</h3>
                <select id="sortMobile" class="w-full border border-gray-300 px-3 py-2 text-xs uppercase tracking-widest text-gray-700 focus:outline-none focus:border-[<?= htmlspecialchars($primary_color) ?>]">
                    <option value="featured">Featured</option>
                    <option value="newest">Newest</option>
                    <option value="low-high">Price: Low to High</option>
                    <option value="high-low">Price: High to Low</option>
                </select>
            </div>
            <div class="flex gap-3 pt-4">
                <button id="clearMobileFilters" class="w-1/2 border border-gray-300 text-gray-700 font-medium py-2.5 text-xs uppercase tracking-widest hover:bg-gray-50 transition">Clear All</button>
                <button id="applyMobileFilters" class="w-1/2 text-white font-medium py-2.5 text-xs uppercase tracking-widest theme13-cat-cta"
                    style="background: <?= htmlspecialchars($primary_color) ?>;">Apply Filters</button>
            </div>
        </div>
    </div>

    <div id="mobileBackdrop" class="fixed inset-0 bg-black/40 hidden z-[9998] transition-opacity duration-300"></div>
    <?php include_once __DIR__ . '/includes/floting-cart.php' ?>
    <?php include_once __DIR__ . "/includes/footer.php"; ?>

    <?php if (isset($_GET['variation'])): ?>
        <script>
            $(document).ready(function() {
                getVariantData(<?= $_GET['variation'] ?>);
            });
        </script>
    <?php endif; ?>

    <script>
        $(".additionalImg").on("click", function() {
            $("#image").attr("src", $(this).attr("src"));
        });
    </script>


    <!-- ==================== THEME 17 STYLES ==================== -->
    <style>
        :root {
            --primary-color: <?= htmlspecialchars($primary_color) ?>;
            --hover-color: <?= htmlspecialchars($hover_color) ?>;
        }

        .theme13-cat-btn:hover,
        .theme13-cat-cta:hover {
            background: var(--hover-color) !important;
            border-color: var(--hover-color) !important;
            color: #fff !important;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Theme 17: editorial, rectangular category cards (not circular icons) */
        .t17-category-section {
            position: relative;
            overflow: hidden;
            background: #f7f5f1;
            padding: 27px clamp(16px, 4vw, 58px) 30px;
            border-bottom: 1px solid #e7e2db;
            color: #211f1c
        }

        .t17-category-topline {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            max-width: 1500px;
            margin: 0 auto 20px;
            padding-right: 54px
        }

        .t17-eyebrow,
        .t17-category-note,
        .t17-subcategory-heading>span {
            display: block;
            font-size: 9px;
            letter-spacing: .22em;
            font-weight: 700;
            color: #77716a
        }

        .t17-category-topline h2 {
            font-family: Georgia, serif;
            font-size: clamp(23px, 3vw, 34px);
            line-height: 1.1;
            margin: 7px 0 0;
            font-weight: 400;
            letter-spacing: -.04em
        }

        .t17-category-topline h2 em {
            font-weight: 400;
            color: var(--primary-color)
        }

        .t17-category-viewport {
            max-width: 1500px;
            margin: auto;
            overflow: hidden
        }

        .t17-category-track {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            padding: 3px 2px 9px;
            align-items: stretch
        }

        .t17-category-card {
            position: relative;
            display: block;
            flex: 0 0 clamp(145px, 16vw, 218px);
            min-width: 0;
            color: #211f1c;
            text-decoration: none;
            transition: transform .25s ease
        }

        .t17-category-card:hover {
            transform: translateY(-4px)
        }

        .t17-category-image {
            height: clamp(125px, 14vw, 190px);
            position: relative;
            overflow: hidden;
            background: #e5ded5;
            border: 1px solid #e1d9cf
        }

        .t17-category-image img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            transition: transform .55s cubic-bezier(.2, .7, .2, 1);
            filter: saturate(.88)
        }

        .t17-category-card:hover .t17-category-image img {
            transform: scale(1.07)
        }

        .t17-category-card.is-current .t17-category-image {
            outline: 2px solid var(--primary-color);
            outline-offset: -2px
        }

        .t17-card-index {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            background: #f8f5ef;
            color: #292521;
            padding: 5px 7px;
            font-size: 9px;
            letter-spacing: .08em;
            font-weight: 700
        }

        .t17-image-shade {
            position: absolute;
            inset: 35% 0 0;
            background: linear-gradient(180deg, transparent, rgba(20, 17, 15, .28));
            pointer-events: none
        }

        .t17-category-open {
            position: absolute;
            right: 10px;
            bottom: 10px;
            width: 29px;
            height: 29px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f5ef;
            color: #25211e;
            font-size: 16px;
            transition: background .2s, color .2s
        }

        .t17-category-card:hover .t17-category-open,
        .t17-category-card.is-current .t17-category-open {
            background: var(--primary-color);
            color: #fff
        }

        .t17-category-letter {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font: italic 64px Georgia, serif;
            color: var(--primary-color);
            background: linear-gradient(135deg, #f0e9df, #d8cbbd)
        }

        .t17-category-label {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
            padding: 11px 1px 0;
            border-bottom: 1px solid #d9d1c7;
            padding-bottom: 10px
        }

        .t17-category-label>span {
            font: 500 14px/1.25 Georgia, serif;
            overflow-wrap: anywhere
        }

        .t17-category-label small {
            font: 700 8px/1.3 Arial, sans-serif;
            letter-spacing: .12em;
            color: #827a72;
            text-align: right;
            white-space: nowrap;
            padding-top: 3px
        }

        .t17-category-card.is-current .t17-category-label {
            border-bottom: 2px solid var(--primary-color)
        }

        .t17-carousel-arrow {
            position: absolute;
            top: 36px;
            right: clamp(16px, 4vw, 58px);
            width: 36px;
            height: 36px;
            border: 1px solid #d5cdc3;
            background: #fdfbf8;
            color: #27231f;
            z-index: 4;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s
        }

        .t17-carousel-arrow:hover,
        .t17-sub-arrow:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff
        }

        .t17-carousel-arrow.t17-prev {
            right: calc(clamp(16px, 4vw, 58px) + 43px)
        }

        .t17-subcategory-section {
            padding: 17px clamp(16px, 4vw, 58px) 19px;
            background: #fff;
            border-bottom: 1px solid #eae6e0
        }

        .t17-subcategory-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            max-width: 1500px;
            margin: 0 auto 11px
        }

        .t17-subcategory-heading b {
            font: 400 15px Georgia, serif;
            color: #2b2723
        }

        .t17-subcategory-rail-wrap {
            position: relative;
            max-width: 1500px;
            margin: auto;
            display: flex;
            align-items: center;
            gap: 10px
        }

        .t17-subcategory-rail {
            display: flex;
            gap: 9px;
            overflow-x: auto;
            scroll-behavior: smooth;
            flex: 1;
            padding: 2px 1px 5px
        }

        .t17-subcategory-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            padding: 7px 12px 7px 7px;
            border: 1px solid #e4ded6;
            background: #fff;
            color: #39332e;
            text-decoration: none;
            font-size: 12px;
            transition: all .2s
        }

        .t17-subcategory-pill:hover {
            border-color: var(--primary-color);
            color: var(--primary-color)
        }

        .t17-subcategory-pill.is-active {
            background: #f7f2ed;
            border-color: var(--primary-color);
            box-shadow: inset 0 -2px 0 var(--primary-color)
        }

        .t17-subcategory-thumb {
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #eee7df;
            color: var(--primary-color);
            font: italic 20px Georgia, serif
        }

        .t17-subcategory-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .t17-subcategory-pill>i {
            font-style: normal;
            font-size: 15px;
            color: #92877c;
            margin-left: 4px
        }

        .t17-sub-arrow {
            width: 31px;
            height: 31px;
            flex: 0 0 31px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ded7ce;
            background: #fff;
            color: #342f2a;
            transition: all .2s
        }

        @media(max-width:640px) {
            .t17-category-section {
                padding: 22px 14px 20px
            }

            .t17-category-topline {
                padding-right: 0;
                margin-bottom: 15px
            }

            .t17-category-note {
                display: none
            }

            .t17-carousel-arrow {
                top: 20px;
                right: 14px;
                width: 31px;
                height: 31px
            }

            .t17-carousel-arrow.t17-prev {
                right: 52px
            }

            .t17-category-track {
                gap: 11px
            }

            .t17-category-card {
                flex-basis: 143px
            }

            .t17-category-image {
                height: 135px
            }

            .t17-category-label {
                display: block
            }

            .t17-category-label>span {
                display: block;
                font-size: 13px
            }

            .t17-category-label small {
                display: block;
                text-align: left;
                margin-top: 5px
            }

            .t17-subcategory-section {
                padding: 15px 14px
            }

            .t17-subcategory-heading {
                align-items: flex-start;
                flex-direction: column;
                gap: 4px
            }

            .t17-sub-arrow {
                display: none
            }

            .t17-category-topline h2 {
                font-size: 25px
            }
        }

        /* Slider thumbs — DB colors */
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
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.25);
            transition: all 0.15s ease;
        }

        .slider-thumb::-webkit-slider-thumb:hover {
            transform: scale(1.1);
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

        .hidden-product {
            display: none !important;
        }

        .price-preset.active,
        .mobile-preset.active {
            background-color: var(--primary-color) !important;
            color: white !important;
            border-color: var(--primary-color) !important;
        }

        #sliderRangeFill,
        #mobileSliderRangeFill {
            transition: left 0.1s ease, right 0.1s ease;
        }
    </style>


    <!-- ==================== CAROUSEL SCRIPT ==================== -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const carousel = document.getElementById("categoryCarousel");
            const prevBtn = document.getElementById("prevBtn");
            const nextBtn = document.getElementById("nextBtn");
            if (!carousel || !prevBtn || !nextBtn) return;

            function getScrollStep() {
                const firstItem = carousel.querySelector(".category-item");
                if (!firstItem) return 200;
                const style = getComputedStyle(carousel);
                return Math.round(firstItem.getBoundingClientRect().width + (parseFloat(style.gap) || 15));
            }

            nextBtn.addEventListener("click", () => carousel.scrollBy({
                left: getScrollStep(),
                behavior: "smooth"
            }));
            prevBtn.addEventListener("click", () => carousel.scrollBy({
                left: -getScrollStep(),
                behavior: "smooth"
            }));

            let startX = 0;
            carousel.addEventListener("touchstart", e => (startX = e.touches[0].clientX));
            carousel.addEventListener("touchend", e => {
                const diff = e.changedTouches[0].clientX - startX;
                if (Math.abs(diff) > 40) rotateCarousel(diff < 0 ? "right" : "left");
            });

            const subCarousel = document.getElementById("subCategoryCarousel");
            const subPrev = document.getElementById("subPrev");
            const subNext = document.getElementById("subNext");
            if (subCarousel && subPrev && subNext) {
                const scrollAmount = 250;
                subNext.addEventListener("click", () => subCarousel.scrollBy({
                    left: scrollAmount,
                    behavior: "smooth"
                }));
                subPrev.addEventListener("click", () => subCarousel.scrollBy({
                    left: -scrollAmount,
                    behavior: "smooth"
                }));
            }
        });
    </script>


    <!-- ==================== MAIN FILTER + AJAX SCRIPT ==================== -->
    <script>
        $(document).ready(function() {

            const MAX_LIMIT = <?= $maxPrice ?>;
            let minPrice = 0;
            let maxPrice = MAX_LIMIT;
            let page = 1;
            let isLoading = false;
            let hasMoreData = true;

            function formatPrice(price) {
                return '₹' + Number(price).toLocaleString('en-IN');
            }

            function updatePriceDisplays() {
                $('#minPriceDisplay').text(formatPrice(minPrice));
                $('#maxPriceDisplay').text(formatPrice(maxPrice));
                $('#minPriceSlider').val(minPrice);
                $('#maxPriceSlider').val(maxPrice);

                const minPercent = (minPrice / MAX_LIMIT) * 100;
                const maxPercent = (maxPrice / MAX_LIMIT) * 100;
                $('#sliderRangeFill').css({
                    'left': minPercent + '%',
                    'right': (100 - maxPercent) + '%'
                });

                if ($('#minPriceMobileDisplay').length) {
                    $('#minPriceMobileDisplay').text(formatPrice(minPrice));
                    $('#maxPriceMobileDisplay').text(formatPrice(maxPrice));
                    $('#minPriceMobileSlider').val(minPrice);
                    $('#maxPriceMobileSlider').val(maxPrice);
                    $('#mobileSliderRangeFill').css({
                        'left': minPercent + '%',
                        'right': (100 - maxPercent) + '%'
                    });
                }

                if (minPrice > 0 || maxPrice < MAX_LIMIT) $('#priceActiveBadge').removeClass('hidden');
                else $('#priceActiveBadge').addClass('hidden');
            }

            $('#minPriceSlider').on('input', function() {
                let val = parseInt($(this).val());
                if (val > maxPrice - 100) {
                    val = maxPrice - 100;
                    $(this).val(val);
                }
                minPrice = Math.max(0, val);
                updatePriceDisplays();
            });
            $('#maxPriceSlider').on('input', function() {
                let val = parseInt($(this).val());
                if (val < minPrice + 100) {
                    val = minPrice + 100;
                    $(this).val(val);
                }
                maxPrice = Math.min(MAX_LIMIT, val);
                updatePriceDisplays();
            });

            $('#minPriceMobileSlider').on('input', function() {
                let val = parseInt($(this).val());
                if (val > maxPrice - 100) {
                    val = maxPrice - 100;
                    $(this).val(val);
                }
                minPrice = Math.max(0, val);
                updatePriceDisplays();
            });
            $('#maxPriceMobileSlider').on('input', function() {
                let val = parseInt($(this).val());
                if (val < minPrice + 100) {
                    val = minPrice + 100;
                    $(this).val(val);
                }
                maxPrice = Math.min(MAX_LIMIT, val);
                updatePriceDisplays();
            });

            $('.price-preset').on('click', function() {
                $('.price-preset').removeClass('active');
                $(this).addClass('active');
                minPrice = parseInt($(this).data('min')) || 0;
                maxPrice = parseInt($(this).data('max')) || MAX_LIMIT;
                updatePriceDisplays();
            });
            $('.mobile-preset').on('click', function() {
                $('.mobile-preset').removeClass('active');
                $(this).addClass('active');
                minPrice = parseInt($(this).data('min')) || 0;
                maxPrice = parseInt($(this).data('max')) || MAX_LIMIT;
                updatePriceDisplays();
            });

            function applySizeFilterToDOM() {
                const desktopSizes = $(".size-filter:checked").map((i, el) => $(el).val().trim()).get();
                const mobileSizes = $(".size-filter-mobile:checked").map((i, el) => $(el).val().trim()).get();
                const unique = [...new Set([...desktopSizes, ...mobileSizes])];

                $("#content > .group").each(function() {
                    if (unique.length === 0) {
                        $(this).removeClass("hidden-product");
                    } else {
                        const productSizes = ($(this).data("size") + "").split(",").map(s => s.trim()).filter(Boolean);
                        // Products with no size are always shown
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
            }

            function sortProducts(sortType) {
                const container = $("#content");
                const products = container.children(".group").get();
                products.sort(function(a, b) {
                    const priceA = parseFloat($(a).find(".selected-price, .productPrice").first().text().replace(/[^0-9.]/g, "")) || 0;
                    const priceB = parseFloat($(b).find(".selected-price, .productPrice").first().text().replace(/[^0-9.]/g, "")) || 0;
                    const idA = parseInt($(a).attr("data-id")) || 0;
                    const idB = parseInt($(b).attr("data-id")) || 0;
                    switch (sortType) {
                        case "low-high":
                            return priceA - priceB;
                        case "high-low":
                            return priceB - priceA;
                        case "newest":
                            return idB - idA;
                        default:
                            return idA - idB;
                    }
                });
                container.empty().append(products);
            }

            function applyAllClientFilters() {
                applySizeFilterToDOM();
                sortProducts($("#sortBy").val() || "featured");
                updateProductCount();
            }

            function updateProductCount() {
                const total = $("#content > .group").filter(":not(.hidden-product)").length;
                const text = total + (total === 1 ? " product" : " products");
                $("#productCount").text(text);
                $("#mobileProductCount").text(text);
            }

            function showLoader() {
                if (!$("#content #loaderBox").length) {
                    $("#content").prepend(`
                        <div id="loaderBox" class="col-span-full flex flex-col items-center justify-center py-16">
                            <div class="relative w-14 h-14 mb-3">
                                <div class="absolute inset-0 border-2" style="border-color: var(--primary-color); opacity: 0.25;"></div>
                                <div class="absolute inset-0 border-2 border-transparent animate-spin"
                                     style="border-top-color: var(--primary-color); border-right-color: var(--primary-color);"></div>
                            </div>
                            <p class="text-gray-700 text-sm tracking-widest uppercase">Loading...</p>
                        </div>
                    `);
                }
                $("#loaderBox").show();
            }

            function hideLoader() {
                $("#loaderBox").remove();
            }

            function showEmptyMessage() {
                hideLoader();
                $("#content").empty();
                $("#emptyMessage").removeClass("hidden").css("display", "flex").hide().fadeIn(250);
                $("footer").fadeIn(200);
            }

            function hideEmptyMessage() {
                $("#emptyMessage").hide().addClass("hidden");
            }

            function loadAllProducts() {
                if (isLoading) return;
                isLoading = true;
                page = 1;
                hasMoreData = true;

                $("#content").empty();
                hideEmptyMessage();

                showLoader();

                function loadNextPage() {
                    $.ajax({
                        url: "shop/ajax/get-category-wise-product.php",
                        type: "POST",
                        data: {
                            page: page,
                            category: $("#category").text(),
                            min_price: minPrice > 0 ? minPrice : 0,
                            max_price: maxPrice < MAX_LIMIT ? maxPrice : 0,
                        },
                        success: function(response) {
                            const stripped = $.trim(response || "").replace(/<script[\s\S]*?<\/script>/gi, "").trim();

                            if (stripped === "" || stripped.length < 10) {
                                hasMoreData = false;
                            } else {
                                if (page === 1) hideLoader();
                                const $products = $(stripped);
                                const $validProducts = $products.filter(".group");

                                $validProducts.each(function() {
                                    const productId = $(this).attr("data-id");
                                    if (productId && $("#content > .group[data-id='" + productId + "']").length) {
                                        $(this).remove();
                                    }
                                });
                                $("#content").append($validProducts);
                                page++;
                            }

                            if (hasMoreData) loadNextPage();
                            else {
                                isLoading = false;
                                hideLoader();
                                $("footer").fadeIn(200);
                                if ($("#content .group").length === 0) showEmptyMessage();
                                else applyAllClientFilters();
                            }
                        },
                        error: function() {
                            isLoading = false;
                            hideLoader();
                            if (page === 1) showEmptyMessage();
                            $("footer").fadeIn(200);
                        }
                    });
                }
                loadNextPage();
            }

            // Dropdown toggles
            $("#priceDropdownBtn").on("click", function(e) {
                e.stopPropagation();
                $("#priceDropdown").toggleClass("hidden");
                $("#sizeDropdown").addClass("hidden");
            });
            $("#sizeDropdownBtn").on("click", function(e) {
                e.stopPropagation();
                $("#sizeDropdown").toggleClass("hidden");
                $("#priceDropdown").addClass("hidden");
            });
            $("#priceDropdown, #sizeDropdown").on("click", function(e) {
                e.stopPropagation();
            });
            $(document).on("click", function() {
                $("#priceDropdown, #sizeDropdown").addClass("hidden");
            });

            $("#applyPriceFilter").on("click", function() {
                $("#priceDropdown").addClass("hidden");
                loadAllProducts();
            });
            $("#resetPriceFilter").on("click", function() {
                minPrice = 0;
                maxPrice = MAX_LIMIT;
                $('.price-preset, .mobile-preset').removeClass('active');
                updatePriceDisplays();
                $("#priceDropdown").addClass("hidden");
                loadAllProducts();
            });
            $("#applySizeFilter").on("click", function() {
                applySizeFilterToDOM();
                updateProductCount();
                $("#sizeDropdown").addClass("hidden");
            });
            $("#resetSizeFilter").on("click", function() {
                $(".size-filter, .size-filter-mobile").prop("checked", false);
                applySizeFilterToDOM();
                updateProductCount();
                $("#sizeDropdown").addClass("hidden");
            });

            $("#sortBy").on("change", function() {
                sortProducts($(this).val());
                $("#sortMobile").val($(this).val());
            });
            $("#sortMobile").on("change", function() {
                sortProducts($(this).val());
                $("#sortBy").val($(this).val());
            });

            function clearAllFilters() {
                minPrice = 0;
                maxPrice = MAX_LIMIT;
                $('.price-preset, .mobile-preset').removeClass('active');
                updatePriceDisplays();
                $(".size-filter, .size-filter-mobile").prop("checked", false);
                $("#sizeActiveBadge").addClass("hidden");
                $("#sortBy, #sortMobile").val("featured");
                $("#priceDropdown, #sizeDropdown").addClass("hidden");
                loadAllProducts();
            }
            $("#clearAllFilters").on("click", clearAllFilters);

            // Mobile sidebar
            const sidebar = $("#mobileFilterSidebar");
            const backdrop = $("#mobileBackdrop");

            $("#openMobileFilter").on("click", function() {
                sidebar.removeClass("translate-x-full");
                backdrop.removeClass("hidden").css("opacity", "1");
            });
            $("#closeMobileFilter, #mobileBackdrop").on("click", function() {
                sidebar.addClass("translate-x-full");
                backdrop.addClass("hidden").css("opacity", "0");
            });
            $("#clearMobileFilters").on("click", function() {
                clearAllFilters();
                sidebar.addClass("translate-x-full");
                backdrop.addClass("hidden").css("opacity", "0");
            });
            $("#applyMobileFilters").on("click", function() {
                $("#sortBy").val($("#sortMobile").val());
                sidebar.addClass("translate-x-full");
                backdrop.addClass("hidden").css("opacity", "0");
                loadAllProducts();
            });

            // Init
            updatePriceDisplays();

            $("#emptyMessage").hide();
            setTimeout(loadAllProducts, 100);
        });
    </script>

</body>

</html>