<?php
// ==================== DB COLORS ====================
$headerPrimary = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#a13d91';
$headerHover   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#84366f';

// ==================== CART / WISHLIST COUNTS ====================
$cart_count = 0;
$wishlist_count = 0;
if (isset($cookie_id) && !empty($cookie_id)) {
    $cartQuery = $db->prepare("SELECT SUM(quantity) FROM customer_cart WHERE customer_id = ?");
    $cartQuery->execute([$cookie_id]);
    $cart_count = (int)$cartQuery->fetchColumn();

    $wishQuery = $db->prepare("SELECT COUNT(*) FROM customer_wishlists WHERE customer_id = ?");
    $wishQuery->execute([$cookie_id]);
    $wishlist_count = (int)$wishQuery->fetchColumn();
}

// ==================== CATEGORIES ====================
$stmt = getCategories();
$allHeaderCategories = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
$allHeaderCategories = array_filter($allHeaderCategories, function ($cat) {
    return isset($cat['add_to_menu']) && (int)$cat['add_to_menu'] === 1;
});
$allHeaderCategories = array_reverse($allHeaderCategories);

$parentCats = [];
$childCatsMap = [];
foreach ($allHeaderCategories as $cat) {
    $parentId = (int)($cat['parent_category'] ?? 0);
    if ($parentId <= 0) $parentCats[] = $cat;
    else {
        if (!isset($childCatsMap[$parentId])) $childCatsMap[$parentId] = [];
        $childCatsMap[$parentId][] = $cat;
    }
}
if (empty($parentCats)) $parentCats = $allHeaderCategories;

$visibleCats = array_slice($parentCats, 0, 6);
$moreCats    = array_slice($parentCats, 6);

$quickCategories = getCategories();
$quickCats = $quickCategories ? array_slice($quickCategories->fetchAll(PDO::FETCH_ASSOC), 0, 5) : [];

// ==================== AJAX BASE (FIX FOR 404) ====================
// All backend endpoints live at APP_URL (project root), NOT under $storeUrl.
$ajaxBase = rtrim(APP_URL, '/') . '/';
?>


<!-- ================================================== -->
<!-- FESTIVE ANNOUNCEMENT                                -->
<!-- ================================================== -->
<div class="flex h-[26px] items-center justify-center px-2 text-center text-[9px] font-bold tracking-[.6px] text-white sm:h-[32px] sm:text-[11px] sm:tracking-[1.5px]"
    style="background: <?= htmlspecialchars($headerPrimary) ?>;">
    <?php if (getSettings("minimum_order_amount")): ?>
        FESTIVE SALE IS LIVE &nbsp;|&nbsp; MINIMUM ORDER
        <?= currencyToSymbol($storeCurrency) . ' ' . getSettings("minimum_order_amount") ?>
    <?php else: ?>
        FESTIVE SALE IS LIVE &nbsp;|&nbsp; FLAT 20% OFF + EXTRA 10% OFF ON PREPAID ORDERS.
    <?php endif; ?>
</div>


<!-- ================================================== -->
<!-- MAIN HEADER                                         -->
<!-- ================================================== -->
<header class="relative z-30 grid h-[56px] grid-cols-3 items-center bg-white px-3 sm:h-[74px] sm:px-[5%] shadow-sm">

    <div class="flex items-center gap-3 sm:gap-6">
        <button onclick="openMenu()" aria-label="Open navigation menu" class="flex items-center justify-center lg:hidden">
            <svg class="h-[21px] w-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="M3 7h18M3 12h18M3 17h18" />
            </svg>
        </button>
        <button onclick="openSearch()" aria-label="Search" class="flex items-center justify-center">
            <svg class="h-[19px] w-[19px] sm:h-[22px] sm:w-[22px]" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.6">
                <circle cx="10.8" cy="10.8" r="6.8" />
                <path d="m16 16 5 5" />
            </svg>
        </button>
    </div>

    <a href="<?= $storeUrl ?>"
        class="whitespace-nowrap text-center font-['Playfair_Display'] text-[22px] tracking-[.5px] sm:text-[32px]"
        style="color: <?= htmlspecialchars($headerPrimary) ?>;">
        <?php if (getSettings("logo")): ?>
            <img src="<?= UPLOADS_URL . getSettings("logo") ?>"
                alt="<?= htmlspecialchars($storeName) ?>"
                class="mx-auto h-[34px] sm:h-[46px] w-auto object-contain">
        <?php else: ?>
            <?= htmlspecialchars(strtoupper($storeName)) ?>
        <?php endif; ?>
    </a>

    <div class="flex items-center justify-end gap-3 sm:gap-5">
        <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
            <a href="<?= $storeUrl ?>profile" aria-label="Account" class="flex items-center justify-center"
                style="color: <?= htmlspecialchars($headerPrimary) ?>;">
                <svg class="h-[19px] w-[19px] sm:h-[22px] sm:w-[22px]" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="3.5" />
                    <path d="M4.5 20c0-4.1 3-6.5 7.5-6.5s7.5 2.4 7.5 6.5" />
                </svg>
            </a>
        <?php else: ?>
            <a href="<?= $storeUrl ?>login" aria-label="Login" class="flex items-center justify-center">
                <svg class="h-[19px] w-[19px] sm:h-[22px] sm:w-[22px]" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="3.5" />
                    <path d="M4.5 20c0-4.1 3-6.5 7.5-6.5s7.5 2.4 7.5 6.5" />
                </svg>
            </a>
        <?php endif; ?>

        <a href="<?= $storeUrl ?>wishlists" aria-label="Wishlist" class="relative flex items-center justify-center">
            <svg class="h-[19px] w-[19px] sm:h-[22px] sm:w-[22px]" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5">
                <path d="M20.8 8.8c0 4.2-8.8 10-8.8 10S3.2 13 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z" />
            </svg>
            <span class="wishlistItemsCount absolute -right-2 -top-2 text-[10px] font-bold text-white min-w-[16px] h-[16px] rounded-full items-center justify-center px-1"
                style="background: <?= htmlspecialchars($headerPrimary) ?>; display: <?= $wishlist_count > 0 ? 'flex' : 'none' ?>;">
                <?= $wishlist_count ?>
            </span>
        </a>

        <a href="<?= $storeUrl ?>cart" aria-label="Shopping bag" class="relative flex items-center justify-center">
            <svg class="h-[20px] w-[20px] sm:h-[23px] sm:w-[23px]" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="1.5">
                <path d="M5 8h14l1.2 13H3.8L5 8Z" />
                <path d="M9 9V6a3 3 0 0 1 6 0v3" />
            </svg>
            <span class="cartItemsCount absolute -right-2 -top-2 text-[10px] font-bold text-white min-w-[16px] h-[16px] rounded-full items-center justify-center px-1"
                style="background: <?= htmlspecialchars($headerPrimary) ?>; display: <?= $cart_count > 0 ? 'flex' : 'none' ?>;">
                <?= $cart_count ?>
            </span>
        </a>
    </div>
</header>


<!-- ================================================== -->
<!-- DESKTOP NAVIGATION                                  -->
<!-- ================================================== -->
<nav class="relative z-20 hidden min-h-[44px] items-center justify-center gap-6 bg-white px-4 pb-3 lg:flex xl:gap-8">

    <?php foreach ($visibleCats as $cat):
        $catSubs = $childCatsMap[$cat['id']] ?? [];
    ?>
        <?php if (!empty($catSubs)): ?>
            <div class="group relative">
                <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                    class="flex items-center gap-1 text-[13px] font-semibold uppercase tracking-[.8px] transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>] max-w-[150px]">
                    <span class="truncate"><?= htmlspecialchars($cat['name']) ?></span>
                    <svg class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path d="m5 7 5 5 5-5" />
                    </svg>
                </a>
                <div class="dropdown-panel absolute left-1/2 top-full z-50 mt-2 w-56 -translate-x-1/2 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                    <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                        class="block px-5 py-2.5 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] truncate">
                        All <?= htmlspecialchars($cat['name']) ?>
                    </a>
                    <?php foreach (array_slice($catSubs, 0, 8) as $sub): ?>
                        <a href="<?= $storeUrl . 'category/' . $sub['slug'] ?>"
                            class="block px-5 py-2.5 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] truncate">
                            <?= htmlspecialchars($sub['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php else: ?>
            <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                class="text-[13px] font-semibold uppercase tracking-[.8px] transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>] max-w-[150px] truncate">
                <?= htmlspecialchars($cat['name']) ?>
            </a>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (!empty($moreCats)): ?>
        <div class="group relative">
            <button type="button"
                class="flex items-center gap-1 text-[13px] font-semibold uppercase tracking-[.8px] hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                More
                <svg class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="m5 7 5 5 5-5" />
                </svg>
            </button>
            <div class="dropdown-panel dropdown-right absolute right-0 top-full z-50 mt-2 w-56 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                <?php foreach ($moreCats as $cat): ?>
                    <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                        class="block px-5 py-2.5 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] truncate">
                        <?= htmlspecialchars($cat['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="group relative">
        <button type="button"
            class="flex items-center gap-1 text-[13px] font-semibold uppercase tracking-[.8px] hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
            Quick Shop
            <svg class="h-3 w-3 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path d="m5 7 5 5 5-5" />
            </svg>
        </button>
        <div class="dropdown-panel dropdown-right absolute right-0 top-full z-50 mt-2 w-56 bg-white py-2 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
            <a href="<?= $storeUrl ?>shop-all" class="block px-5 py-2.5 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">All Products</a>
            <a href="<?= $storeUrl ?>new-arrivals" class="block px-5 py-2.5 text-sm hover:bg-gray-50 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">New Arrivals</a>
        </div>
    </div>

    <a href="<?= $storeUrl ?>best-seller"
        class="text-[13px] font-semibold uppercase tracking-[.8px] transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">BEST SELLER</a>
    <a href="<?= $storeUrl ?>reviews"
        class="text-[13px] font-semibold uppercase tracking-[.8px] transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">REVIEWS</a>
    <a href="<?= $storeUrl ?>track-order"
        class="text-[13px] font-semibold uppercase tracking-[.8px] transition hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">TRACK ORDER</a>
</nav>


<!-- ================================================== -->
<!-- SEARCH OVERLAY (slides DOWN from TOP)               -->
<!-- ================================================== -->
<div id="searchOverlay"
    class="invisible fixed inset-0 z-[100] bg-black/40 opacity-0 transition-opacity duration-300"
    aria-hidden="true">
    <div id="searchPanel"
        class="absolute left-0 top-0 w-full -translate-y-full bg-white px-5 pb-6 pt-5 shadow-xl transition-transform duration-500 ease-out sm:px-[6%]"
        role="dialog" aria-modal="true" aria-label="Search">

        <div class="mb-4 flex items-center justify-between">
            <span class="font-['Playfair_Display'] text-2xl" style="color: <?= htmlspecialchars($headerPrimary) ?>;">
                <?= htmlspecialchars(strtoupper($storeName)) ?>
            </span>
            <button onclick="closeSearch()" aria-label="Close search"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 text-2xl leading-none hover:bg-neutral-50 transition-colors">&times;</button>
        </div>

        <form id="searchForm" method="GET" action="<?= $storeUrl ?>search/"
            autocomplete="off" class="relative flex items-center gap-3 border-b border-neutral-300">
            <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="10.8" cy="10.8" r="6.8" />
                <path d="m16 16 5 5" />
            </svg>
            <input id="searchInput" name="q" type="search" placeholder="Search products..."
                aria-label="Search products"
                class="w-full border-0 bg-transparent py-3 text-sm outline-none"
                autocomplete="off">
        </form>

        <div id="searchSuggestions"
            class="absolute left-5 right-5 mt-2 hidden max-h-[60vh] overflow-y-auto rounded-xl border border-neutral-200 bg-white shadow-xl sm:left-[6%] sm:right-[6%]">
            <div id="suggestionsList" class="py-2"></div>
        </div>

        <?php if (!empty($quickCats)): ?>
            <div class="mt-8">
                <p class="mb-3 text-[10px] uppercase tracking-[3px] text-neutral-400">Popular</p>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($quickCats as $qc): ?>
                        <a href="<?= $storeUrl ?>category/<?= htmlspecialchars($qc['slug']) ?>"
                            class="max-w-[160px] truncate rounded-full border border-neutral-200 px-4 py-2 text-[11px] uppercase tracking-widest text-neutral-600 transition-colors hover:border-[<?= htmlspecialchars($headerPrimary) ?>] hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                            <?= htmlspecialchars($qc['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>


<!-- ================================================== -->
<!-- NAVIGATION POPUP (slides UP from BOTTOM, Theme 17)  -->
<!-- ================================================== -->
<div id="menuOverlay"
    class="invisible fixed inset-0 z-[110] bg-black/40 opacity-0 transition-opacity duration-300"
    aria-hidden="true">
    <div id="menuPanel"
        class="absolute bottom-0 left-0 max-h-[85vh] w-full translate-y-full overflow-y-auto rounded-t-2xl bg-white px-5 pb-7 pt-4 shadow-xl transition-transform duration-500 ease-out sm:px-[6%]"
        role="dialog" aria-modal="true" aria-label="Menu">

        <div class="mx-auto mb-5 h-1 w-10 rounded-full bg-neutral-300"></div>

        <div class="mb-4 flex items-center justify-between">
            <span class="font-['Playfair_Display'] text-2xl" style="color: <?= htmlspecialchars($headerPrimary) ?>;">
                <?= htmlspecialchars(strtoupper($storeName)) ?>
            </span>
            <button onclick="closeMenu()" aria-label="Close navigation"
                class="flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 text-2xl leading-none hover:bg-neutral-50 transition-colors">&times;</button>
        </div>

        <nav class="grid grid-cols-1 gap-x-8 sm:grid-cols-2 lg:grid-cols-3">

            <?php foreach ($parentCats as $cat):
                $catSubs = $childCatsMap[$cat['id']] ?? [];
            ?>
                <?php if (!empty($catSubs)): ?>
                    <div class="mobile-accordion-group border-b border-neutral-100">
                        <button type="button"
                            class="mobile-dropdown-toggle flex w-full items-center justify-between py-3.5 text-sm font-semibold tracking-wide uppercase"
                            data-target="mobileSub-<?= (int)$cat['id'] ?>">
                            <span class="truncate text-left"><?= htmlspecialchars($cat['name']) ?></span>
                            <svg class="mobile-chevron h-4 w-4 shrink-0 text-neutral-400 transition-transform duration-200"
                                viewBox="0 0 20 20" fill="currentColor">
                                <path d="m5 7 5 5 5-5" />
                            </svg>
                        </button>
                        <div id="mobileSub-<?= (int)$cat['id'] ?>" class="hidden flex-col bg-neutral-50/70 pb-2">
                            <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                                class="block py-2 pl-4 pr-2 text-xs uppercase text-neutral-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] truncate">
                                All <?= htmlspecialchars($cat['name']) ?>
                            </a>
                            <?php foreach ($catSubs as $sub): ?>
                                <a href="<?= $storeUrl . 'category/' . $sub['slug'] ?>"
                                    class="block py-2 pl-4 pr-2 text-xs uppercase text-neutral-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>] truncate">
                                    <?= htmlspecialchars($sub['name']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= $storeUrl . 'category/' . $cat['slug'] ?>"
                        class="flex justify-between border-b border-neutral-100 py-3.5 text-sm font-semibold tracking-wide uppercase hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">
                        <?= htmlspecialchars($cat['name']) ?> <span>↗</span>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="mobile-accordion-group border-b border-neutral-100">
                <button type="button"
                    class="mobile-dropdown-toggle flex w-full items-center justify-between py-3.5 text-sm font-semibold tracking-wide uppercase"
                    data-target="mobileQuickShop">
                    <span>Quick Shop</span>
                    <svg class="mobile-chevron h-4 w-4 shrink-0 text-neutral-400 transition-transform duration-200"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path d="m5 7 5 5 5-5" />
                    </svg>
                </button>
                <div id="mobileQuickShop" class="hidden flex-col bg-neutral-50/70 pb-2">
                    <a href="<?= $storeUrl ?>shop-all" class="block py-2 pl-4 pr-2 text-xs uppercase text-neutral-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">All Products</a>
                    <a href="<?= $storeUrl ?>new-arrivals" class="block py-2 pl-4 pr-2 text-xs uppercase text-neutral-600 hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">New Arrivals</a>
                </div>
            </div>

            <a href="<?= $storeUrl ?>best-seller"
                class="flex justify-between border-b border-neutral-100 py-3.5 text-sm font-semibold tracking-wide uppercase hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">BEST SELLER <span>↗</span></a>
            <a href="<?= $storeUrl ?>reviews"
                class="flex justify-between border-b border-neutral-100 py-3.5 text-sm font-semibold tracking-wide uppercase hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">REVIEWS <span>↗</span></a>
            <a href="<?= $storeUrl ?>track-order"
                class="flex justify-between border-b border-neutral-100 py-3.5 text-sm font-semibold tracking-wide uppercase hover:text-[<?= htmlspecialchars($headerPrimary) ?>]">TRACK ORDER <span>↗</span></a>
        </nav>

        <div class="px-1 py-5">
            <?php if (function_exists('isLoggedIn') && isLoggedIn()): ?>
                <a href="<?= $storeUrl ?>profile"
                    class="block w-full rounded-lg border border-neutral-300 px-4 py-3 text-center text-sm font-bold uppercase hover:border-[<?= htmlspecialchars($headerPrimary) ?>] hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
                    My Account
                </a>
            <?php else: ?>
                <a href="<?= $storeUrl ?>login"
                    class="block w-full rounded-lg border border-neutral-300 px-4 py-3 text-center text-sm font-bold uppercase hover:border-[<?= htmlspecialchars($headerPrimary) ?>] hover:text-[<?= htmlspecialchars($headerPrimary) ?>] transition-colors">
                    Login / Register
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>


<style>
    :root {
        --primary: <?= htmlspecialchars($headerPrimary) ?>;
        --hover-color: <?= htmlspecialchars($headerHover) ?>;
    }

    .dropdown-panel {
        transform: translateY(8px) translateX(-50%);
        transition: opacity .2s ease, visibility .2s ease, transform .2s ease;
    }

    .group:hover .dropdown-panel {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) translateX(-50%);
    }

    .dropdown-panel.dropdown-right {
        transform: translateY(8px);
    }

    .group:hover .dropdown-panel.dropdown-right {
        transform: translateY(0);
    }

    #menuPanel::-webkit-scrollbar {
        width: 6px;
    }

    #menuPanel::-webkit-scrollbar-thumb {
        background: #ddd;
        border-radius: 4px;
    }

    #searchSuggestions {
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
        border-radius: 12px;
    }

    .suggestion-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        text-decoration: none;
        color: inherit;
        border-bottom: 1px solid #f3f4f6;
        transition: background-color .15s ease;
    }

    .suggestion-item:last-child {
        border-bottom: none;
    }

    .suggestion-item:hover {
        background-color: #f9fafb;
    }

    .suggestion-item .suggestion-image {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        object-fit: cover;
        flex-shrink: 0;
        background: #f3f4f6;
    }

    .suggestion-item .suggestion-info {
        flex: 1;
        min-width: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .suggestion-item .suggestion-name {
        font-size: 14px;
        font-weight: 500;
        color: #1f2937;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .suggestion-item .suggestion-badge {
        font-size: 10px;
        background: #fef3c7;
        color: #d97706;
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .suggestion-item .highlight {
        color: var(--hover-color);
        font-weight: 600;
    }

    .suggestion-empty,
    .suggestion-loading {
        padding: 20px 16px;
        text-align: center;
        font-size: 14px;
        color: #9ca3af;
    }

    @media (max-width: 768px) {
        .suggestion-item {
            padding: 8px 12px;
        }

        .suggestion-item .suggestion-image {
            width: 34px;
            height: 34px;
        }

        .suggestion-item .suggestion-name {
            font-size: 13px;
        }
    }
</style>


<script>
    (function() {
        'use strict';

        // ================= FIXED URLS =================
        // All AJAX endpoints live at APP_URL root, NOT under $storeUrl.
        const AJAX_BASE = <?= json_encode($ajaxBase) ?>; // e.g. "/seller_website/"
        const STORE_URL = <?= json_encode($storeUrl) ?>; // e.g. "/seller_website/toy-shop/"
        const UPLOADS_URL_JS = <?= json_encode(UPLOADS_URL) ?>;
        const APP_URL_JS = <?= json_encode(APP_URL) ?>;
        const STORE_SLUG = <?= json_encode($storeSlug ?? '') ?>;

        const SEARCH_SUGGESTIONS_URL = AJAX_BASE + 'shop/ajax/get-search-suggestions.php';
        const CART_COUNT_URL = AJAX_BASE + 'shop/ajax/get-product-count-and-price.php';

        const searchOverlay = document.getElementById('searchOverlay');
        const searchPanel = document.getElementById('searchPanel');
        const menuOverlay = document.getElementById('menuOverlay');
        const menuPanel = document.getElementById('menuPanel');

        // ============ SEARCH POPUP ============
        window.openSearch = function() {
            closeMenu();
            searchOverlay.classList.remove('invisible');
            searchOverlay.setAttribute('aria-hidden', 'false');
            requestAnimationFrame(() => {
                searchOverlay.classList.remove('opacity-0');
                searchOverlay.classList.add('opacity-100');
                searchPanel.classList.remove('-translate-y-full');
                searchPanel.classList.add('translate-y-0');
            });
            document.body.style.overflow = 'hidden';
            setTimeout(() => document.getElementById('searchInput')?.focus(), 350);
        };

        window.closeSearch = function() {
            searchOverlay.classList.remove('opacity-100');
            searchOverlay.classList.add('opacity-0');
            searchPanel.classList.remove('translate-y-0');
            searchPanel.classList.add('-translate-y-full');
            searchOverlay.setAttribute('aria-hidden', 'true');
            document.getElementById('searchSuggestions')?.classList.add('hidden');
            setTimeout(() => {
                searchOverlay.classList.add('invisible');
                if (!menuOverlay.classList.contains('opacity-100')) document.body.style.overflow = '';
            }, 500);
        };

        // ============ MENU POPUP (Theme 17 bottom-sheet) ============
        window.openMenu = function() {
            closeSearch();
            menuOverlay.classList.remove('invisible');
            menuOverlay.setAttribute('aria-hidden', 'false');
            requestAnimationFrame(() => {
                menuOverlay.classList.remove('opacity-0');
                menuOverlay.classList.add('opacity-100');
                menuPanel.classList.remove('translate-y-full');
                menuPanel.classList.add('translate-y-0');
            });
            document.body.style.overflow = 'hidden';
        };

        window.closeMenu = function() {
            menuOverlay.classList.remove('opacity-100');
            menuOverlay.classList.add('opacity-0');
            menuPanel.classList.remove('translate-y-0');
            menuPanel.classList.add('translate-y-full');
            menuOverlay.setAttribute('aria-hidden', 'true');
            setTimeout(() => {
                menuOverlay.classList.add('invisible');
                if (!searchOverlay.classList.contains('opacity-100')) document.body.style.overflow = '';
            }, 500);
        };

        searchOverlay.addEventListener('click', e => {
            if (e.target === searchOverlay) closeSearch();
        });
        menuOverlay.addEventListener('click', e => {
            if (e.target === menuOverlay) closeMenu();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeSearch();
                closeMenu();
            }
        });
        document.querySelectorAll('#menuPanel a').forEach(link => link.addEventListener('click', closeMenu));

        // ============ ACCORDION ============
        document.querySelectorAll('.mobile-dropdown-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                if (!target) return;
                const isHidden = target.classList.contains('hidden');
                target.classList.toggle('hidden', !isHidden);
                const chevron = this.querySelector('.mobile-chevron');
                if (chevron) chevron.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
            });
        });

        // ============ SEARCH SUGGESTIONS ============
        const searchInput = document.getElementById('searchInput');
        const suggestionsBox = document.getElementById('searchSuggestions');
        const suggestionsList = document.getElementById('suggestionsList');
        const searchForm = document.getElementById('searchForm');
        let debounceTimer, lastQuery = '';

        const escapeRegExp = s => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
        const highlightText = (text, q) => !q ? text : text.replace(
            new RegExp('(' + escapeRegExp(q) + ')', 'gi'),
            '<span class="highlight">$1</span>'
        );

        function fetchSuggestions(query) {
            if (query.length < 1) {
                suggestionsBox.classList.add('hidden');
                return;
            }
            lastQuery = query;
            suggestionsList.innerHTML = '<div class="suggestion-loading">Searching...</div>';
            suggestionsBox.classList.remove('hidden');

            const form = new FormData();
            form.append('q', query);
            form.append('store', STORE_SLUG);

            fetch(SEARCH_SUGGESTIONS_URL, {
                    method: 'POST',
                    body: form
                })
                .then(r => {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(response => {
                    if (lastQuery !== query) return;
                    if (response.success && response.data && response.data.length > 0) {
                        let html = '';
                        response.data.forEach(p => {
                            const image = (p.image && p.image !== 'no-image.png') ?
                                UPLOADS_URL_JS + p.image :
                                APP_URL_JS + 'assets/image/no-image.png';
                            const badge = p.badge || '';
                            const url = STORE_URL + 'product/' + (p.slug || '');
                            html += `
                        <a href="${url}" class="suggestion-item">
                            <img src="${image}" alt="${p.name || ''}" class="suggestion-image"
                                 onerror="this.src='${APP_URL_JS}assets/image/no-image.png'">
                            <div class="suggestion-info">
                                <div class="suggestion-name">${highlightText(p.name || '', query)}</div>
                                ${badge ? `<span class="suggestion-badge">${badge}</span>` : ''}
                            </div>
                        </a>`;
                        });
                        suggestionsList.innerHTML = html;
                    } else {
                        suggestionsList.innerHTML =
                            '<div class="suggestion-empty">No products found for "<strong>' + query + '</strong>"</div>';
                    }
                })
                .catch(err => {
                    console.error('[search-suggestions]', err, 'URL:', SEARCH_SUGGESTIONS_URL);
                    suggestionsList.innerHTML =
                        '<div class="suggestion-empty">Could not load suggestions.<br><small style="font-size:11px;color:#bbb">' + SEARCH_SUGGESTIONS_URL + '</small></div>';
                });
        }

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const q = this.value.trim();
                if (!q) {
                    suggestionsBox.classList.add('hidden');
                    return;
                }
                debounceTimer = setTimeout(() => fetchSuggestions(q), 300);
            });
            searchInput.addEventListener('focus', function() {
                const q = this.value.trim();
                if (q.length > 0) fetchSuggestions(q);
            });
        }

        document.addEventListener('click', e => {
            if (!searchPanel.contains(e.target)) return;
            if (!e.target.closest('#searchForm') && !e.target.closest('#searchSuggestions')) {
                suggestionsBox?.classList.add('hidden');
            }
        });

        if (searchForm) {
            searchForm.addEventListener('submit', e => {
                if (!(searchInput?.value || '').trim()) e.preventDefault();
            });
        }

        // ============ CART COUNT ONLY ============
        // Initial count is rendered by PHP; this refresh keeps it updated after cart changes.
        window.updateHeaderCounts = function() {
            const url = new URL(CART_COUNT_URL, window.location.origin);
            if (STORE_SLUG) url.searchParams.set('store', STORE_SLUG);

            fetch(url.toString(), { credentials: 'same-origin' })
                .then(response => {
                    if (!response.ok) throw new Error('HTTP ' + response.status);
                    return response.json();
                })
                .then(data => {
                    const rawCount = data.itemsCount ?? data.count ?? data.cart_count ?? data.total_items ?? 0;
                    const count = Math.max(0, parseInt(rawCount, 10) || 0);
                    document.querySelectorAll('.cartItemsCount').forEach(badge => {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = count > 0 ? 'flex' : 'none';
                    });
                })
                .catch(error => console.warn('[theme17-cart-count]', error));
        };

        // Keep the existing cart refresh hook, without adding another count function.
        window.refreshCartUI = window.refreshCartUI || window.updateHeaderCounts;
        document.addEventListener('DOMContentLoaded', () => setTimeout(window.updateHeaderCounts, 300));
    })();
</script>