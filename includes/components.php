<?php
// ============================================================
// THEME 9 COMPONENTS — styled as Theme 13/17 (reference layout)
// - Card: BIG style with hover overlay & full-width CTA
// - Quick view: exact reference layout (no Ask a Question)
// - No quantity control (always 1)
// - Add to cart: sends `other: ''` to fix PDO NOT NULL error
// - Description: full HTML render via base64
// ============================================================

// --- GLOBAL COLOR SETUP ---
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

if (!function_exists('calculateSavePercent')) {
    function calculateSavePercent($mrp_price, $price)
    {
        if ($mrp_price && $mrp_price > $price) {
            return round((($mrp_price - $price) / $mrp_price) * 100);
        }
        return 0;
    }
}

if (!function_exists('getProductHtml')) {
    function getProductHtml($id)
    {
        global $storeCurrency, $storeUrl, $customerId, $cookie_id, $db, $sellerId, $storeId, $primary_color, $hover_color;

        $product_id  = getData("product_id", "seller_products", "id='$id'");
        $slug        = getData("slug", "seller_products", "id='$id'");
        $name        = getData("name", "seller_products", "id='$id'");
        if (!$product_id || !$slug || !$name) return '';

        $image       = getData("image", "seller_products", "id='$id'");
        $price       = getData("price", "seller_products", "id='$id'") ?: 0;
        $mrp_price   = getData("mrp_price", "seller_products", "id='$id'") ?: 0;
        $totalStocks = getData("total_stocks", "seller_products", "id='$id'") ?: 0;
        $variation   = getData("variation", "seller_products", "id='$id'");
        $description = getData("description", "seller_products", "id='$id'") ?? '';
        $isUnlimited = getData("unlimited_stock", "seller_products", "id='$id'");

        $savePercent = calculateSavePercent($mrp_price, $price);

        $hasAdvanced = getData("id", "seller_product_advanced_variants", "product_id='$product_id'");
        $hasBasic    = getData("id", "seller_product_variants", "product_id='$product_id'");

        // ---------- Card size chips (advanced only) ----------
        $cardSizes = [];
        if ($hasAdvanced) {
            $avStmt = readData("*", "seller_product_advanced_variants", "product_id='$product_id'");
            $seen = [];
            while ($av = $avStmt->fetch(PDO::FETCH_ASSOC)) {
                $sz = $av['size'] ?? '';
                if ($sz && !in_array($sz, $seen)) {
                    $seen[] = $sz;
                    $cardSizes[] = $sz;
                }
            }
            $cardSizes = array_slice($cardSizes, 0, 5);
        }

        // ---------- Cart state ----------
        $cartVariants = [];
        if (!empty($cookie_id)) {
            $cq = $db->prepare("SELECT other, advanced_variant FROM customer_cart WHERE customer_id=? AND product_id=?");
            $cq->execute([$cookie_id, $id]);
            foreach ($cq->fetchAll(PDO::FETCH_ASSOC) as $row) {
                if (!empty($row['advanced_variant'])) $cartVariants[] = $row['advanced_variant'];
                else if (!empty($row['other'])) $cartVariants[] = $row['other'];
                else $cartVariants[] = 'main';
            }
        }

        // ---------- Advanced variants JSON ----------
        $advVariants = [];
        if ($hasAdvanced) {
            $avStmt = readData("*", "seller_product_advanced_variants", "product_id='$product_id'");
            while ($av = $avStmt->fetch(PDO::FETCH_ASSOC)) {
                $colorId   = $av['color'] ?? '';
                $colorName = $colorId ? (getData("color_name", "product_colors", "id='$colorId'") ?? '') : '';
                $colorCode = $colorId ? (getData("color_code", "product_colors", "id='$colorId'") ?? '#ccc') : '#ccc';
                $advVariants[] = [
                    'id'        => (string)$av['id'],
                    'size'      => $av['size'] ?? '',
                    'colorId'   => $colorId,
                    'color'     => $colorName,
                    'colorCode' => $colorCode,
                    'price'     => (float)$av['price'],
                    'mrp'       => (float)($av['mrp_price'] ?? 0),
                    'stock'     => (int)($av['stock'] ?? 0),
                    'unlimited' => (int)($av['unlimited_stock'] ?? 0),
                    'image'     => !empty($av['image']) ? UPLOADS_URL . $av['image'] : UPLOADS_URL . $image,
                    'inCart'    => in_array($av['id'], $cartVariants) ? 1 : 0,
                ];
            }
        }

        // ---------- Basic variants JSON ----------
        $basicVariants = [];
        if ($hasBasic && !$hasAdvanced) {
            $basicVariants[] = [
                'id' => 'main',
                'label' => !empty($variation) ? $variation : $name,
                'price' => (float)$price,
                'mrp' => (float)$mrp_price,
                'stock' => (int)$totalStocks,
                'unlimited' => (int)$isUnlimited,
                'image' => UPLOADS_URL . $image,
                'inCart' => in_array('main', $cartVariants) ? 1 : 0,
            ];
            $bvStmt = readData("*", "seller_product_variants", "product_id='$product_id'");
            while ($bv = $bvStmt->fetch(PDO::FETCH_ASSOC)) {
                $basicVariants[] = [
                    'id' => (string)$bv['id'],
                    'label' => $bv['variation'] ?? $bv['name'] ?? 'Option',
                    'price' => (float)$bv['price'],
                    'mrp' => (float)($bv['mrp_price'] ?? 0),
                    'stock' => (int)($bv['stock'] ?? 0),
                    'unlimited' => (int)($bv['unlimited_stock'] ?? 0),
                    'image' => !empty($bv['image']) ? UPLOADS_URL . $bv['image'] : UPLOADS_URL . $image,
                    'inCart' => in_array($bv['id'], $cartVariants) ? 1 : 0,
                ];
            }
        }

        $variantData = ['advanced' => $advVariants, 'basic' => $basicVariants];
        $variantJson = htmlspecialchars(json_encode($variantData), ENT_QUOTES, 'UTF-8');

        // ---------- Also bought (3 random, exclude current) ----------
        $alsoBought = [];
        try {
            $abStmt = $db->prepare("
                SELECT id, name, slug, image, price, mrp_price
                FROM seller_products
                WHERE seller_id = ? AND store_id = ? AND status = 1
                  AND id != ?
                  AND (visibility = 'publish' OR (visibility = 'schedule' AND schedule_date <= NOW()))
                ORDER BY RAND()
                LIMIT 3
            ");
            $abStmt->execute([$sellerId, $storeId, $id]);
            while ($ab = $abStmt->fetch(PDO::FETCH_ASSOC)) {
                $alsoBought[] = [
                    'name'  => $ab['name'],
                    'slug'  => $ab['slug'],
                    'image' => UPLOADS_URL . $ab['image'],
                    'price' => (float)$ab['price'],
                    'mrp'   => (float)($ab['mrp_price'] ?? 0),
                ];
            }
        } catch (Throwable $e) {
        }

        $descriptionBase64 = base64_encode($description);
        $alsoBoughtJson    = htmlspecialchars(json_encode($alsoBought), ENT_QUOTES, 'UTF-8');

        $escName  = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $escSlug  = htmlspecialchars($slug, ENT_QUOTES, 'UTF-8');
        $escImage = htmlspecialchars(UPLOADS_URL . $image, ENT_QUOTES, 'UTF-8');

        // ============================================================
        // CARD — BIG "LATEST COLLECTION" STYLE
        // ============================================================
        $html  = '<article class="t9-product-card group"';
        $html .= ' data-id="' . $id . '"';
        $html .= ' data-product-id="' . $product_id . '"';
        $html .= ' data-name="' . $escName . '"';
        $html .= ' data-slug="' . $escSlug . '"';
        $html .= ' data-image="' . $escImage . '"';
        $html .= ' data-price="' . (float)$price . '"';
        $html .= ' data-mrp="' . (float)$mrp_price . '"';
        $html .= ' data-desc-b64="' . $descriptionBase64 . '"';
        $html .= ' data-has-advanced="' . ($hasAdvanced ? '1' : '0') . '"';
        $html .= ' data-has-basic="' . ($hasBasic && !$hasAdvanced ? '1' : '0') . '"';
        $html .= ' data-primary="' . htmlspecialchars($primary_color) . '"';
        $html .= ' data-hover="' . htmlspecialchars($hover_color) . '"';
        $html .= ' data-variants="' . $variantJson . '"';
        $html .= ' data-also-bought="' . $alsoBoughtJson . '">';

        // ---------- IMAGE WRAPPER ----------
        $html .= '<div class="t9-card-media">';

        $html .= '<a href="' . $storeUrl . 'product/' . $escSlug . '" class="t9-card-media-link">';
        $html .= '<img src="' . $escImage . '" alt="' . $escName . '" loading="lazy" class="t9-card-img">';
        $html .= '</a>';

        // Discount badge (top-right)
        if ($savePercent > 0) {
            $html .= '<span class="t9-card-badge">FLAT ' . $savePercent . '% OFF</span>';
        }

        // Wishlist (top-right, under badge)
        $inWishlist = false;
        if (function_exists('isLoggedIn') && isLoggedIn() && !empty($customerId)) {
            $inWishlist = getData("id", "customer_wishlists", "customer_id='$customerId' AND product_id='$id'") ? true : false;
        }
        $wishClass = $inWishlist ? 'is-in' : '';
        $html .= '<button type="button" class="t9-wish ' . $wishClass . '" data-id="' . $id . '" aria-label="Wishlist">';
        $html .= '<svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20.8 8.8c0 4.2-8.8 10-8.8 10S3.2 13 3.2 8.8A4.3 4.3 0 0 1 12 6.6a4.3 4.3 0 0 1 8.8 2.2Z"/></svg>';
        $html .= '</button>';

        // Size chips stacked vertically on bottom-left (advanced only)
        if ($hasAdvanced && !empty($cardSizes)) {
            $html .= '<div class="t9-size-strip">';
            foreach ($cardSizes as $sz) {
                $html .= '<button type="button" class="t9-card-size" data-id="' . $id . '" data-size="' . htmlspecialchars($sz) . '">' . htmlspecialchars($sz) . '</button>';
            }
            $html .= '</div>';
        }

        // Quick View overlay button (bottom, hover-reveal)
        $html .= '<button type="button" class="t9-quickview-btn openQuickView" data-id="' . $id . '">Quick view</button>';

        $html .= '</div>'; // end media

        // ---------- TITLE ----------
        $html .= '<h3 class="t9-card-title"><a href="' . $storeUrl . 'product/' . $escSlug . '">' . $escName . '</a></h3>';

        // ---------- PRICE ROW ----------
        $html .= '<div class="t9-card-price-row">';
        if ($mrp_price && $mrp_price > $price) {
            $html .= '<span class="t9-card-mrp">' . currencyToSymbol($storeCurrency) . number_format($mrp_price, 2) . '</span>';
        }
        $html .= '<span class="t9-card-price">' . currencyToSymbol($storeCurrency) . number_format($price, 2) . '</span>';
        $html .= '</div>';

        // ---------- SAVE % ----------
        if ($savePercent > 0) {
            $html .= '<p class="t9-card-save">Save ' . $savePercent . '%</p>';
        }

        // ---------- FULL-WIDTH CTA ----------
        if ($hasAdvanced || $hasBasic) {
            $html .= '<button type="button" class="t9-card-cta openQuickView" data-id="' . $id . '">ADD TO CART</button>';
        } else if ($totalStocks <= 0 && !$isUnlimited) {
            $html .= '<button type="button" class="t9-card-cta is-disabled" disabled>SOLD OUT</button>';
        } else {
            $html .= '<button type="button" class="t9-card-cta t9-direct-add" data-id="' . $id . '">ADD TO CART</button>';
        }

        $html .= '</article>';
        return $html;
    }
}
?>

<!-- ============================================================ -->
<!-- QUICK VIEW MODAL (no Ask a Question, no Quantity control)     -->
<!-- ============================================================ -->
<div id="t9QuickViewModal" class="t9-qv-modal" aria-hidden="true">
    <div class="t9-qv-backdrop"></div>
    <div class="t9-qv-dialog" role="dialog" aria-modal="true">
        <button class="t9-qv-close" id="t9QvClose" aria-label="Close">&times;</button>

        <div class="t9-qv-layout">
            <!-- LEFT: gallery -->
            <div class="t9-qv-gallery">
                <div class="t9-qv-thumbs" id="t9QvThumbs"></div>
                <div class="t9-qv-main-img">
                    <img id="t9QvImg" src="" alt="">
                    <span class="t9-qv-sale-badge">SALE</span>
                </div>
            </div>

            <!-- RIGHT: info -->
            <div class="t9-qv-info">
                <h2 class="t9-qv-title" id="t9QvTitle"></h2>

                
                <div class="t9-qv-price-row">
                    <span class="t9-qv-mrp" id="t9QvMrp"></span>
                    <span class="t9-qv-price" id="t9QvPrice"></span>
                    <span class="t9-qv-save" id="t9QvSave"></span>
                </div>
                

                <div class="t9-qv-stock" id="t9QvStock"></div>

                <div class="t9-qv-field" id="t9QvColorField" style="display:none;">
                    <p class="t9-qv-label">Available Styles</p>
                    <div class="t9-qv-colors" id="t9QvColors"></div>
                </div>

                <div class="t9-qv-field" id="t9QvSizeField" style="display:none;">
                    <p class="t9-qv-label">Select Size</p>
                    <div class="t9-qv-sizes" id="t9QvSizes"></div>
                </div>

                <div class="t9-qv-field" id="t9QvBasicField" style="display:none;">
                    <p class="t9-qv-label">Available Styles</p>
                    <div class="t9-qv-basics" id="t9QvBasics"></div>
                </div>

                <button type="button" class="t9-qv-addbtn" id="t9QvAddBtn">ADD TO CART</button>

                <div class="t9-qv-acc">
                    <!-- DESCRIPTION -->
                    <div class="t9-acc-item">
                        <button type="button" class="t9-acc-trigger" aria-expanded="false">
                            <span>DESCRIPTION</span>
                            <svg class="t9-acc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div class="t9-acc-panel">
                            <div class="t9-acc-inner">
                                <div id="t9QvDescHtml" class="t9-rich-desc"></div>
                            </div>
                        </div>
                    </div>

                    <!-- CUSTOMERS ALSO BOUGHT -->
                    <div class="t9-acc-item" id="t9QvAlsoAccItem">
                        <button type="button" class="t9-acc-trigger" aria-expanded="false">
                            <span>CUSTOMERS ALSO BOUGHT</span>
                            <svg class="t9-acc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div class="t9-acc-panel">
                            <div class="t9-acc-inner">
                                <div id="t9QvAlsoGrid" class="t9-also-grid"></div>
                            </div>
                        </div>
                    </div>

                    <!-- SHIPPING -->
                    <div class="t9-acc-item">
                        <button type="button" class="t9-acc-trigger" aria-expanded="false">
                            <span>SHIPPING INFORMATION</span>
                            <svg class="t9-acc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div class="t9-acc-panel">
                            <div class="t9-acc-inner">
                                Shipping charges and estimated delivery times depend on your location. Check the final details during checkout.
                            </div>
                        </div>
                    </div>

                    <!-- RETURNS -->
                    <div class="t9-acc-item">
                        <button type="button" class="t9-acc-trigger" aria-expanded="false">
                            <span>RETURNS &amp; EXCHANGES</span>
                            <svg class="t9-acc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div class="t9-acc-panel">
                            <div class="t9-acc-inner">
                                Please refer to your store's return and exchange policy for eligibility and applicable conditions.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    :root {
        --t9-primary: <?= htmlspecialchars($primary_color) ?>;
        --t9-hover: <?= htmlspecialchars($hover_color) ?>;
    }

    /* ============================================================ */
    /* BIG PRODUCT CARD STYLE — "LATEST COLLECTION"                 */
    /* ============================================================ */
    .t9-product-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        position: relative;
        font-family: inherit;
        background: #fff;
    }

    .t9-card-media {
        position: relative;
        overflow: hidden;
        background: #f4f4f4;
    }

    .t9-card-media-link {
        display: block;
    }

    .t9-card-img {
        display: block;
        width: 100%;
        aspect-ratio: 3 / 4;
        object-fit: cover;
        transition: transform .5s ease;
    }

    .t9-product-card:hover .t9-card-img {
        transform: scale(1.03);
    }

    /* Discount badge */
    .t9-card-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: #f45d79;
        color: #fff;
        padding: 5px 10px;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: .3px;
        text-transform: uppercase;
        z-index: 3;
    }

    /* Wishlist under badge */
    .t9-wish {
        position: absolute;
        top: 34px;
        right: 8px;
        z-index: 4;
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .95);
        border: 0;
        border-radius: 50%;
        box-shadow: 0 2px 6px rgba(0, 0, 0, .12);
        color: #4b5563;
        cursor: pointer;
        transition: all .2s ease;
    }

    .t9-wish:hover {
        transform: scale(1.1);
        color: var(--t9-hover);
    }

    .t9-wish.is-in {
        color: #f43f5e;
    }

    .t9-wish.is-in svg {
        fill: currentColor;
    }

    /* Size chips vertical */
    .t9-size-strip {
        position: absolute;
        bottom: 8px;
        left: 8px;
        z-index: 3;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .t9-card-size {
        width: 28px;
        height: 20px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, .95);
        border: 1px solid #d4d4d4;
        border-radius: 3px;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: .3px;
        text-transform: uppercase;
        color: #404040;
        cursor: pointer;
        transition: all .15s ease;
        padding: 0;
    }

    .t9-card-size:hover,
    .t9-card-size.is-selected {
        background: #f45d79;
        color: #fff;
        border-color: #f45d79;
    }


    /* Quick view overlay */
    .t9-quickview-btn {
        position: absolute;
        bottom: 8px;
        left: 44px;
        right: 44px;
        z-index: 2;
        padding: 8px 6px;
        background: #f45d79;
        color: #fff;
        border: 0;
        font-size: 10px;
        font-weight: 500;
        letter-spacing: .5px;
        text-align: center;
        cursor: pointer;
        opacity: 0;
        transform: translateY(6px);
        transition: opacity .25s ease, transform .25s ease, background .2s ease;
    }

    .t9-product-card:hover .t9-quickview-btn {
        opacity: 1;
        transform: translateY(0);
    }

    .t9-quickview-btn:hover {
        background: #e84caa;
    }

    @media (max-width: 768px) {
        .t9-quickview-btn {
            opacity: 1;
            transform: none;
        }
    }

    /* Title */
    .t9-card-title {
        margin: 10px 0 0;
        min-height: 36px;
        font-size: 11px;
        line-height: 1.45;
        font-weight: 400;
        color: #171717;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        letter-spacing: .2px;
    }

    @media (min-width: 640px) {
        .t9-card-title {
            font-size: 13px;
            min-height: 42px;
        }
    }

    .t9-card-title a {
        color: inherit;
        text-decoration: none;
    }

    .t9-card-title a:hover {
        color: var(--t9-hover);
    }

    /* Price */
    .t9-card-price-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin: 8px 0 0;
        font-size: 11px;
    }

    @media (min-width: 640px) {
        .t9-card-price-row {
            font-size: 12px;
        }
    }

    .t9-card-mrp {
        color: #a3a3a3;
        text-decoration: line-through;
    }

    .t9-card-price {
        font-weight: 500;
        color: #171717;
    }

    .t9-card-save {
        margin: 4px 0 0;
        font-size: 10px;
        font-weight: 500;
        color: #f45d79;
    }

    /* CTA (outlined) */
    .t9-card-cta {
        margin: 12px 0 0;
        width: 100%;
        min-height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px 10px;
        background: #fff;
        color: #171717;
        border: 1px solid #e84caa;
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease, color .2s ease;
    }

    @media (min-width: 640px) {
        .t9-card-cta {
            font-size: 12px;
            letter-spacing: 1.5px;
        }
    }

    .t9-card-cta:hover:not(.is-disabled) {
        background: #e84caa;
        border-color: #e84caa;
        color: #fff;
    }

    .t9-card-cta.is-disabled {
        background: #f3f3f3;
        border-color: #e5e5e5;
        color: #9ca3af;
        cursor: not-allowed;
    }

    /* ============================================================ */
    /* QUICK VIEW STYLES                                            */
    /* ============================================================ */
    .t9-qv-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
        transition: opacity .3s ease, visibility 0s linear .3s;
    }

    .t9-qv-modal.is-open {
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
        transition: opacity .3s ease, visibility 0s linear 0s;
    }

    .t9-qv-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .55);
        backdrop-filter: blur(3px);
    }

    .t9-qv-dialog {
        position: relative;
        width: 100%;
        max-width: 1050px;
        max-height: 94vh;
        background: #fff;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .28);
        overflow-y: auto;
        transform: translateY(20px) scale(.98);
        opacity: 0;
        transition: transform .35s cubic-bezier(.22, 1, .36, 1), opacity .35s ease;
        border-radius: 8px;
    }

    .t9-qv-modal.is-open .t9-qv-dialog {
        transform: translateY(0) scale(1);
        opacity: 1;
    }

    @media (min-width: 640px) {
        .t9-qv-modal {
            padding: 20px;
        }
    }

    .t9-qv-close {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 10;
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        line-height: 1;
        background: #fff;
        border: 1px solid #e5e5e5;
        color: #171717;
        cursor: pointer;
        transition: all .2s ease;
        border-radius: 50%;
    }

    .t9-qv-close:hover {
        background: var(--t9-primary);
        color: #fff;
        border-color: var(--t9-primary);
    }

    .t9-qv-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 16px;
        padding: 16px;
    }

    @media (min-width: 768px) {
        .t9-qv-layout {
            grid-template-columns: 1fr 1fr;
            gap: 28px;
            padding: 24px;
        }
    }

    .t9-qv-gallery {
        display: flex;
        gap: 8px;
        min-width: 0;
    }

    .t9-qv-thumbs {
        display: flex;
        flex-direction: column;
        gap: 8px;
        width: 52px;
        flex-shrink: 0;
    }

    @media (min-width: 640px) {
        .t9-qv-thumbs {
            width: 64px;
        }
    }

    .t9-qv-thumbs button {
        border: 2px solid transparent;
        padding: 0;
        cursor: pointer;
        background: #f4f4f4;
        overflow: hidden;
        aspect-ratio: 3/4;
        transition: border-color .15s ease;
        border-radius: 4px;
    }

    .t9-qv-thumbs button.is-active {
        border-color: var(--t9-hover);
    }

    .t9-qv-thumbs img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .t9-qv-main-img {
        flex: 1;
        min-width: 0;
        background: #f4f4f4;
        overflow: hidden;
        position: relative;
        border-radius: 6px;
    }

    .t9-qv-main-img img {
        width: 100%;
        aspect-ratio: 3/4;
        object-fit: cover;
        display: block;
    }

    .t9-qv-sale-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: var(--t9-hover);
        color: #fff;
        padding: 4px 10px;
        font-size: 9px;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        border-bottom-left-radius: 6px;
    }

    .t9-qv-info {
        min-width: 0;
    }

    .t9-qv-title {
        margin: 0;
        padding-right: 34px;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: .8px;
        line-height: 1.5;
        text-transform: uppercase;
        color: #171717;
    }

    @media (min-width: 640px) {
        .t9-qv-title {
            font-size: 16px;
        }
    }

    .t9-qv-festive-strip {
        margin-top: 12px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #a75b29;
        padding: 8px 12px;
        font-size: 9px;
        font-weight: 600;
        color: #fff;
        border-radius: 4px;
    }

    .t9-qv-offer {
        background: #fde047;
        color: #171717;
        padding: 3px 8px;
        font-weight: 700;
        border-radius: 3px;
    }

    .t9-qv-price-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
        font-size: 14px;
    }

    .t9-qv-mrp {
        color: #a3a3a3;
        text-decoration: line-through;
    }

    .t9-qv-price {
        font-weight: 700;
        color: #171717;
    }

    .t9-qv-save {
        font-size: 10px;
        font-weight: 700;
        color: var(--t9-hover);
        letter-spacing: .5px;
    }

    .t9-qv-taxnote {
        margin: 4px 0 0;
        font-size: 9px;
        color: #737373;
    }

    .t9-qv-stock {
        margin-top: 10px;
        font-size: 11px;
        font-weight: 600;
    }

    .t9-qv-field {
        margin-top: 18px;
    }

    .t9-qv-label {
        margin: 0 0 8px;
        font-size: 10px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        color: #737373;
        font-weight: 600;
    }

    .t9-qv-colors {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .t9-qv-color-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 2px solid #d4d4d4;
        padding: 0;
        cursor: pointer;
        transition: all .2s ease;
    }

    .t9-qv-color-btn:hover {
        transform: scale(1.06);
    }

    .t9-qv-color-btn.is-active {
        box-shadow: 0 0 0 2px var(--t9-hover), 0 0 0 4px #fff;
        border-color: var(--t9-hover);
    }

    .t9-qv-sizes,
    .t9-qv-basics {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .t9-qv-size-btn,
    .t9-qv-basic-btn {
        min-width: 46px;
        padding: 8px 14px;
        border: 1px solid #d4d4d4;
        background: #fff;
        color: #171717;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .8px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all .15s ease;
        border-radius: 4px;
    }

    .t9-qv-size-btn:hover:not(:disabled),
    .t9-qv-basic-btn:hover:not(:disabled) {
        border-color: var(--t9-hover);
        color: var(--t9-primary);
    }

    .t9-qv-size-btn.is-active,
    .t9-qv-basic-btn.is-active {
        background: var(--t9-hover);
        color: #fff;
        border-color: var(--t9-hover);
    }

    .t9-qv-size-btn:disabled,
    .t9-qv-basic-btn:disabled {
        opacity: .4;
        text-decoration: line-through;
        cursor: not-allowed;
    }

    /* --- THE MAIN CART BUTTON FIXES --- */
    .t9-qv-addbtn {
        margin-top: 22px;
        width: 100%;
        padding: 16px;
        background: var(--t9-hover);
        color: #151414be !important;
        border: 1px solid var(--t9-hover);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
        border-radius: 4px;
    }

    .t9-qv-addbtn:hover:not(:disabled) {
        background: var(--t9-primary);
        border-color: var(--t9-primary);
        color: #fff !important;
    }

    /* This fixes the invisible text by using a gray background instead of white-on-white */
    .t9-qv-addbtn:disabled {
        opacity: 1;
        background: #f3f3f3;
        color: #9ca3af !important;
        border-color: #e5e5e5;
        cursor: not-allowed;
    }

    /* When item is ALREADY in cart, use your pink theme instead of the default blue */
    .t9-qv-addbtn.is-in-cart {
        background: #ffe4e6;
        /* Light pink */
        color: var(--t9-primary) !important;
        /* Dark pink text */
        border-color: #fbcfe8;
        opacity: 1;
    }

    /* ACCORDIONS */
    .t9-qv-acc {
        margin-top: 22px;
        border-top: 1px solid #e5e5e5;
        border-bottom: 1px solid #e5e5e5;
    }

    .t9-acc-item {
        border-bottom: 1px solid #e5e5e5;
    }

    .t9-acc-item:last-child {
        border-bottom: 0;
    }

    .t9-acc-trigger {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 0;
        background: none;
        border: 0;
        cursor: pointer;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 1.3px;
        text-transform: uppercase;
        text-align: left;
        color: #171717;
    }

    .t9-acc-icon {
        width: 14px;
        height: 14px;
        transition: transform .3s ease;
        flex-shrink: 0;
    }

    .t9-acc-trigger[aria-expanded="true"] .t9-acc-icon {
        transform: rotate(180deg);
    }

    .t9-acc-panel {
        display: grid;
        grid-template-rows: 0fr;
        opacity: 0;
        transition: grid-template-rows .3s ease-in-out, opacity .3s ease-in-out;
        overflow: hidden;
    }

    .t9-acc-panel>.t9-acc-inner {
        overflow: hidden;
    }

    .t9-acc-trigger[aria-expanded="true"]+.t9-acc-panel {
        grid-template-rows: 1fr;
        opacity: 1;
    }

    .t9-acc-inner {
        padding: 0 0 16px;
        font-size: 12px;
        line-height: 1.75;
        color: #525252;
        word-break: break-word;
    }

    /* RICH DESCRIPTION */
    .t9-rich-desc {
        font-size: 12px;
        line-height: 1.75;
        color: #525252;
    }

    .t9-rich-desc p {
        margin: 0 0 10px;
    }

    .t9-rich-desc p:last-child {
        margin-bottom: 0;
    }

    .t9-rich-desc strong,
    .t9-rich-desc b {
        font-weight: 700;
        color: #171717;
    }

    .t9-rich-desc em,
    .t9-rich-desc i {
        font-style: italic;
    }

    .t9-rich-desc ul,
    .t9-rich-desc ol {
        padding-left: 18px;
        margin: 0 0 10px;
    }

    .t9-rich-desc li {
        margin-bottom: 4px;
        list-style: disc;
    }

    .t9-rich-desc a {
        color: var(--t9-hover);
        text-decoration: underline;
    }

    .t9-rich-desc img {
        max-width: 100%;
        height: auto;
        border-radius: 4px;
        margin: 8px 0;
    }

    .t9-rich-desc h1,
    .t9-rich-desc h2,
    .t9-rich-desc h3,
    .t9-rich-desc h4 {
        font-size: 14px;
        font-weight: 700;
        color: #171717;
        margin: 12px 0 6px;
        text-transform: none;
        letter-spacing: 0;
    }

    .t9-rich-desc table {
        width: 100%;
        border-collapse: collapse;
        margin: 8px 0;
        font-size: 11px;
    }

    .t9-rich-desc td,
    .t9-rich-desc th {
        border: 1px solid #e5e5e5;
        padding: 6px 8px;
    }

    /* ALSO BOUGHT grid */
    .t9-also-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }

    .t9-also-card {
        display: block;
        text-decoration: none;
        color: inherit;
        border: 1px solid #eee;
        border-radius: 6px;
        overflow: hidden;
        transition: border-color .2s ease;
        background: #fff;
    }

    .t9-also-card:hover {
        border-color: var(--t9-hover);
    }

    .t9-also-card img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        display: block;
    }

    .t9-also-info {
        padding: 10px 8px;
    }

    .t9-also-name {
        font-size: 11px;
        line-height: 1.35;
        color: #333;
        margin-bottom: 4px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 28px;
    }

    .t9-also-price {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .t9-also-price-current {
        font-size: 12px;
        font-weight: 700;
        color: #171717;
    }

    .t9-also-price-mrp {
        font-size: 10px;
        color: #a3a3a3;
        text-decoration: line-through;
    }

    /* TOAST */
    .t9-toast {
        position: fixed;
        left: 50%;
        bottom: 22px;
        transform: translateX(-50%) translateY(20px);
        background: #171717;
        color: #fff;
        padding: 14px 24px;
        font-size: 13px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .28);
        z-index: 10000;
        opacity: 0;
        visibility: hidden;
        transition: all .3s ease;
        max-width: 90%;
        border-radius: 4px;
    }

    .t9-toast.is-open {
        opacity: 1;
        visibility: visible;
        transform: translateX(-50%) translateY(0);
    }
</style>


<script>
    (function() {
        'use strict';
        if (window.__t9QvLoaded) return;
        window.__t9QvLoaded = true;

        const CURRENCY = <?= json_encode(currencyToSymbol($storeCurrency)) ?>;
        const STORE_URL = <?= json_encode($storeUrl) ?>;
        const APP_URL = <?= json_encode(rtrim(APP_URL, '/') . '/') ?>;
        const ADD_URL = APP_URL + 'shop/ajax/add-to-cart.php';

        const DB_PRIMARY = <?= json_encode($primary_color) ?>;
        const DB_HOVER = <?= json_encode($hover_color) ?>;

        document.documentElement.style.setProperty('--t9-primary', DB_PRIMARY);
        document.documentElement.style.setProperty('--t9-hover', DB_HOVER);

        const modal = document.getElementById('t9QuickViewModal');
        if (!modal) return;

        const elImg = document.getElementById('t9QvImg');
        const elThumbs = document.getElementById('t9QvThumbs');
        const elTitle = document.getElementById('t9QvTitle');
        const elPrice = document.getElementById('t9QvPrice');
        const elMrp = document.getElementById('t9QvMrp');
        const elSave = document.getElementById('t9QvSave');
        const elStock = document.getElementById('t9QvStock');
        const elDesc = document.getElementById('t9QvDescHtml');
        const elAlsoAccItem = document.getElementById('t9QvAlsoAccItem');
        const elAlsoGrid = document.getElementById('t9QvAlsoGrid');
        const elColors = document.getElementById('t9QvColors');
        const elSizes = document.getElementById('t9QvSizes');
        const elBasics = document.getElementById('t9QvBasics');
        const elColorFld = document.getElementById('t9QvColorField');
        const elSizeFld = document.getElementById('t9QvSizeField');
        const elBasicFld = document.getElementById('t9QvBasicField');
        const elAddBtn = document.getElementById('t9QvAddBtn');

        let state = {
            product: null,
            variants: {
                advanced: [],
                basic: []
            },
            selectedColorId: null,
            selectedVariant: null,
        };

        function fmt(n) {
            return CURRENCY + Number(n || 0).toLocaleString();
        }

        function toast(msg, isErr) {
            let t = document.getElementById('t9Toast');
            if (!t) {
                t = document.createElement('div');
                t.id = 't9Toast';
                t.className = 't9-toast';
                document.body.appendChild(t);
            }
            t.textContent = msg;
            t.style.background = isErr ? '#ef4444' : DB_HOVER;
            t.classList.add('is-open');
            clearTimeout(t._timer);
            t._timer = setTimeout(() => t.classList.remove('is-open'), 2600);
        }

        function decodeB64(encoded) {
            if (!encoded) return '';
            try {
                return decodeURIComponent(escape(atob(encoded)));
            } catch (e) {
                try {
                    return atob(encoded);
                } catch (e2) {
                    return '';
                }
            }
        }

        document.querySelectorAll('.t9-acc-trigger').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const isOpen = trigger.getAttribute('aria-expanded') === 'true';
                document.querySelectorAll('.t9-acc-trigger').forEach(t => {
                    t.setAttribute('aria-expanded', 'false');
                });
                if (!isOpen) trigger.setAttribute('aria-expanded', 'true');
            });
        });

        function openQuickView(card) {
            if (!card) return;

            state.product = {
                id: card.dataset.id,
                name: card.dataset.name,
                slug: card.dataset.slug,
                image: card.dataset.image,
                price: parseFloat(card.dataset.price) || 0,
                mrp: parseFloat(card.dataset.mrp) || 0,
                descB64: card.dataset.descB64 || '',
                hasAdvanced: card.dataset.hasAdvanced === '1',
                hasBasic: card.dataset.hasBasic === '1',
            };
            try {
                state.variants = JSON.parse(card.dataset.variants || '{"advanced":[],"basic":[]}');
            } catch (e) {
                state.variants = {
                    advanced: [],
                    basic: []
                };
            }
            let alsoBought = [];
            try {
                alsoBought = JSON.parse(card.dataset.alsoBought || '[]');
            } catch (e) {
                alsoBought = [];
            }

            state.selectedColorId = null;
            state.selectedVariant = null;

            const p = state.product;

            elImg.src = p.image;
            elImg.alt = p.name;
            elTitle.textContent = p.name;
            elPrice.textContent = fmt(p.price);
            if (p.mrp > p.price) {
                elMrp.textContent = fmt(p.mrp);
                elMrp.style.display = '';
                const off = Math.round(((p.mrp - p.price) / p.mrp) * 100);
                elSave.textContent = 'SAVE ' + off + '%';
                elSave.style.display = '';
            } else {
                elMrp.style.display = 'none';
                elSave.style.display = 'none';
            }
            elStock.innerHTML = '';

            const descHTML = decodeB64(p.descB64).trim();
            elDesc.innerHTML = descHTML || '<p style="color:#999;">No description available.</p>';

            elAlsoGrid.innerHTML = '';
            if (alsoBought.length > 0) {
                alsoBought.forEach(ab => {
                    const link = document.createElement('a');
                    link.href = STORE_URL + 'product/' + ab.slug;
                    link.className = 't9-also-card';
                    link.innerHTML =
                        '<img src="' + ab.image + '" alt="' + ab.name + '">' +
                        '<div class="t9-also-info">' +
                        '<p class="t9-also-name">' + ab.name + '</p>' +
                        '<div class="t9-also-price">' +
                        '<span class="t9-also-price-current">' + fmt(ab.price) + '</span>' +
                        (ab.mrp > ab.price ? '<span class="t9-also-price-mrp">' + fmt(ab.mrp) + '</span>' : '') +
                        '</div>' +
                        '</div>';
                    elAlsoGrid.appendChild(link);
                });
                elAlsoAccItem.style.display = '';
            } else {
                elAlsoAccItem.style.display = 'none';
            }

            elThumbs.innerHTML = '';
            const thumbBtn = document.createElement('button');
            thumbBtn.type = 'button';
            thumbBtn.className = 'is-active';
            thumbBtn.innerHTML = '<img src="' + p.image + '" alt="">';
            elThumbs.appendChild(thumbBtn);

            document.querySelectorAll('.t9-acc-trigger').forEach(t => {
                t.setAttribute('aria-expanded', 'false');
            });

            elColorFld.style.display = 'none';
            elSizeFld.style.display = 'none';
            elBasicFld.style.display = 'none';
            elColors.innerHTML = '';
            elSizes.innerHTML = '';
            elBasics.innerHTML = '';
            elAddBtn.disabled = true;
            elAddBtn.textContent = 'SELECT OPTIONS';

            if (p.hasAdvanced && state.variants.advanced.length) {
                buildAdvanced();
            } else if (p.hasBasic && state.variants.basic.length) {
                buildBasic();
            } else {
                state.selectedVariant = {
                    variantId: '',
                    variantType: 'main',
                    price: p.price,
                    mrp: p.mrp,
                    image: p.image,
                    stock: 999,
                    unlimited: 1,
                    inCart: 0,
                };
                elAddBtn.disabled = false;
                elAddBtn.textContent = 'ADD TO CART';
                elStock.innerHTML = '<span style="color:#16a34a">In Stock</span>';
            }

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function buildAdvanced() {
            const grouped = {};
            state.variants.advanced.forEach(v => {
                const cid = v.colorId || 'default';
                if (!grouped[cid]) {
                    grouped[cid] = {
                        colorId: cid,
                        name: v.color || 'Style',
                        code: v.colorCode || '#ccc',
                        sizes: []
                    };
                }
                grouped[cid].sizes.push(v);
            });

            elColorFld.style.display = '';
            elSizeFld.style.display = '';

            const colorKeys = Object.keys(grouped);
            colorKeys.forEach((cid, idx) => {
                const c = grouped[cid];
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 't9-qv-color-btn' + (idx === 0 ? ' is-active' : '');
                btn.style.background = c.code;
                btn.title = c.name;
                btn.addEventListener('click', () => {
                    elColors.querySelectorAll('.t9-qv-color-btn').forEach(b => b.classList.remove('is-active'));
                    btn.classList.add('is-active');
                    state.selectedColorId = cid;
                    renderSizes(c);
                });
                elColors.appendChild(btn);
            });

            if (colorKeys.length) {
                state.selectedColorId = colorKeys[0];
                renderSizes(grouped[colorKeys[0]]);
            }
        }

        function renderSizes(colorData) {
            elSizes.innerHTML = '';
            state.selectedVariant = null;
            elAddBtn.disabled = true;
            elAddBtn.textContent = 'SELECT SIZE';

            let firstAvail = null;
            colorData.sizes.forEach(v => {
                const out = (v.stock <= 0 && v.unlimited != 1);
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 't9-qv-size-btn';
                btn.textContent = v.size || 'One Size';
                if (out) btn.disabled = true;

                btn.addEventListener('click', () => {
                    elSizes.querySelectorAll('.t9-qv-size-btn').forEach(b => b.classList.remove('is-active'));
                    btn.classList.add('is-active');
                    state.selectedVariant = {
                        variantId: v.id,
                        variantType: 'advanced',
                        price: v.price,
                        mrp: v.mrp,
                        image: v.image,
                        stock: v.stock,
                        unlimited: v.unlimited,
                        inCart: v.inCart,
                    };
                    updatePriceAndStock();
                });

                elSizes.appendChild(btn);
                if (!firstAvail && !out && !v.inCart) firstAvail = btn;
            });

            if (firstAvail) firstAvail.click();
        }

        function buildBasic() {
            elBasicFld.style.display = '';
            state.variants.basic.forEach((v, idx) => {
                const out = (v.stock <= 0 && v.unlimited != 1);
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 't9-qv-basic-btn' + (idx === 0 ? ' is-active' : '');
                btn.textContent = v.label || 'Option';
                if (out) btn.disabled = true;

                btn.addEventListener('click', () => {
                    elBasics.querySelectorAll('.t9-qv-basic-btn').forEach(b => b.classList.remove('is-active'));
                    btn.classList.add('is-active');
                    state.selectedVariant = {
                        variantId: v.id === 'main' ? '' : v.id,
                        variantType: v.id === 'main' ? 'main' : 'basic',
                        price: v.price,
                        mrp: v.mrp,
                        image: v.image,
                        stock: v.stock,
                        unlimited: v.unlimited,
                        inCart: v.inCart,
                    };
                    updatePriceAndStock();
                });

                elBasics.appendChild(btn);
            });

            const first = elBasics.querySelector('.t9-qv-basic-btn:not([disabled])');
            if (first) first.click();
        }

        function updatePriceAndStock() {
            const v = state.selectedVariant;
            if (!v) return;

            elPrice.textContent = fmt(v.price);
            if (v.mrp > v.price) {
                elMrp.textContent = fmt(v.mrp);
                elMrp.style.display = '';
                const off = Math.round(((v.mrp - v.price) / v.mrp) * 100);
                elSave.textContent = 'SAVE ' + off + '%';
                elSave.style.display = '';
            } else {
                elMrp.style.display = 'none';
                elSave.style.display = 'none';
            }
            if (v.image) elImg.src = v.image;

            if (v.unlimited) {
                elStock.innerHTML = '<span style="color:#16a34a">In Stock</span>';
            } else if (v.stock > 0) {
                elStock.innerHTML = '<span style="color:#16a34a">' + v.stock + ' in stock</span>';
            } else {
                elStock.innerHTML = '<span style="color:#ef4444">Out of Stock</span>';
            }

            if (v.inCart) {
                elAddBtn.disabled = true;
                elAddBtn.textContent = 'ALREADY IN BAG';
                elAddBtn.classList.add('is-in-cart'); // Add a new class
            } else {
                elAddBtn.classList.remove('is-in-cart'); // Remove it if not in cart
                elAddBtn.disabled = (v.stock <= 0 && !v.unlimited);
                elAddBtn.textContent = elAddBtn.disabled ? 'SOLD OUT' : 'ADD TO CART';
            }
        }

        function closeModal() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }
        document.getElementById('t9QvClose').addEventListener('click', closeModal);
        modal.querySelector('.t9-qv-backdrop').addEventListener('click', closeModal);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && modal.classList.contains('is-open')) closeModal();
        });

        document.addEventListener('click', e => {
            const btn = e.target.closest('.openQuickView');
            if (!btn) return;
            e.preventDefault();
            const card = btn.closest('.t9-product-card');
            if (card) openQuickView(card);
        });

        function sendAddToCart(payload, onOk, onErr) {
            if (payload.other === undefined) payload.other = '';
            if (payload.variant === undefined) payload.variant = '';
            if (payload.advanced_variant === undefined) payload.advanced_variant = '';
            if (payload.quantity === undefined) payload.quantity = 1;

            if (window.jQuery && jQuery.ajax) {
                jQuery.ajax({
                    url: ADD_URL,
                    type: 'POST',
                    data: payload,
                    dataType: 'text',
                    success: raw => {
                        let res;
                        try {
                            res = JSON.parse(raw);
                        } catch (e) {
                            res = {
                                success: false,
                                message: String(raw || 'Invalid response')
                            };
                        }
                        onOk(res);
                    },
                    error: (xhr, status, err) => {
                        console.warn('[t9 add] fail', status, err, ADD_URL);
                        let msg = 'Network error. Please try again.';
                        try {
                            const j = JSON.parse(xhr.responseText || '{}');
                            if (j.message) msg = j.message;
                        } catch (_) {}
                        onErr(msg);
                    }
                });
                return;
            }

            fetch(ADD_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    credentials: 'same-origin',
                    body: new URLSearchParams(payload),
                })
                .then(r => r.text())
                .then(raw => {
                    let res;
                    try {
                        res = JSON.parse(raw);
                    } catch (e) {
                        res = {
                            success: false,
                            message: String(raw || 'Invalid response')
                        };
                    }
                    onOk(res);
                })
                .catch(err => {
                    console.warn('[t9 add] fail', err, ADD_URL);
                    onErr('Network error.');
                });
        }

        elAddBtn.addEventListener('click', function() {
            if (!state.selectedVariant || elAddBtn.disabled) return;
            const orig = elAddBtn.textContent;
            elAddBtn.disabled = true;
            elAddBtn.textContent = 'ADDING...';

            const payload = {
                product_id: state.product.id,
                quantity: 1,
                variant: '',
                advanced_variant: '',
                other: '',
            };

            const v = state.selectedVariant;
            if (v.variantType === 'advanced') payload.advanced_variant = v.variantId;
            else if (v.variantType === 'basic') payload.variant = v.variantId;

            sendAddToCart(payload, res => {
                if (res.success || (res.message && /already|duplicate|exist/i.test(res.message))) {
                    toast(res.message || 'Added to bag!', false);
                    elAddBtn.textContent = 'ALREADY IN BAG';
                    if (typeof window.updateHeaderCounts === 'function') window.updateHeaderCounts();
                    if (typeof window.updateCartCounts === 'function') window.updateCartCounts();
                    setTimeout(closeModal, 900);
                } else {
                    toast(res.message || 'Could not add.', true);
                    elAddBtn.disabled = false;
                    elAddBtn.textContent = orig;
                }
            }, msg => {
                toast(msg, true);
                elAddBtn.disabled = false;
                elAddBtn.textContent = orig;
            });
        });

        document.addEventListener('click', e => {
            const btn = e.target.closest('.t9-direct-add');
            if (!btn || btn.disabled) return;
            e.preventDefault();
            const orig = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'ADDING...';

            const payload = {
                product_id: btn.dataset.id,
                variant: '',
                advanced_variant: '',
                other: '',
                quantity: 1,
            };

            sendAddToCart(payload, res => {
                if (res.success || (res.message && /already|duplicate|exist/i.test(res.message))) {
                    toast(res.message || 'Added to bag!', false);
                    if (typeof window.updateHeaderCounts === 'function') window.updateHeaderCounts();
                    if (typeof window.updateCartCounts === 'function') window.updateCartCounts();
                    btn.textContent = 'ADDED TO BAG';
                    btn.disabled = true;
                } else {
                    toast(res.message || 'Could not add.', true);
                    btn.disabled = false;
                    btn.textContent = orig;
                }
            }, msg => {
                toast(msg, true);
                btn.disabled = false;
                btn.textContent = orig;
            });
        });
    })();
</script>