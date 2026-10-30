<?php
// ============================================================
// HANGER PRODUCT COMPONENT
// - Hanger card visuals UNCHANGED
// - Popup content = EXACT Theme 9 Quick View
// - Hover = individual shake/swing ONLY
// - Left/Right buttons = custom rAF scroll + scroll-swing
// - "Already in cart" tracking (matches getProductHtml)
// - Popup does NOT auto-close after add-to-cart (manual close only)
// - Clicking a size chip cross-fades the card image EVERY time
// - Popup main image cross-fades EVERY time
// ============================================================
if (!function_exists('calculateSavePercent')) {
    function calculateSavePercent($mrp_price, $price)
    {
        if ($mrp_price && $mrp_price > $price) {
            return round((($mrp_price - $price) / $mrp_price) * 100);
        }
        return 0;
    }
}

if (!function_exists('getHangerProductHtml')) {
    function getHangerProductHtml($id, $index = 0)
    {
        global $storeCurrency, $storeUrl, $customerId, $cookie_id, $db, $sellerId, $storeId;

        $product_id  = getData("product_id", "seller_products", "id='$id'");
        $slug        = getData("slug", "seller_products", "id='$id'");
        $name        = getData("name", "seller_products", "id='$id'");
        if (!$product_id || !$slug || !$name) return '';

        $image       = getData("image", "seller_products", "id='$id'");
        $price       = getData("price", "seller_products", "id='$id'") ?: 0;
        $mrp_price   = getData("mrp_price", "seller_products", "id='$id'") ?: 0;
        $badge       = getData("badge", "seller_products", "id='$id'");
        $totalStocks = getData("total_stocks", "seller_products", "id='$id'") ?: 0;
        $variation   = getData("variation", "seller_products", "id='$id'");
        $description = getData("description", "seller_products", "id='$id'") ?? '';
        $isUnlimited = getData("unlimited_stock", "seller_products", "id='$id'");

        $savePercent = calculateSavePercent($mrp_price, $price);

        $hasAdvanced = getData("id", "seller_product_advanced_variants", "product_id='$product_id'");
        $hasBasic    = getData("id", "seller_product_variants", "product_id='$product_id'");

        $images = [UPLOADS_URL . $image];
        $aiStmt = readData("*", "seller_product_additional_images", "product_id='$product_id'");
        if ($aiStmt) {
            while ($row = $aiStmt->fetch(PDO::FETCH_ASSOC)) {
                $images[] = UPLOADS_URL . $row['image'];
            }
        }

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

        $advVariants = [];
        $sizes       = [];
        if ($hasAdvanced) {
            $avStmt = readData("*", "seller_product_advanced_variants", "product_id='$product_id'");
            while ($av = $avStmt->fetch(PDO::FETCH_ASSOC)) {
                $colorId   = $av['color'] ?? '';
                $colorName = $colorId ? (getData("color_name", "product_colors", "id='$colorId'") ?? '') : '';
                $colorCode = $colorId ? (getData("color_code", "product_colors", "id='$colorId'") ?? '#ccc') : '#ccc';

                if (!empty($av['size']) && !in_array($av['size'], $sizes)) {
                    $sizes[] = $av['size'];
                }

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
                    'inCart'    => in_array((string)$av['id'], $cartVariants) ? 1 : 0,
                ];
            }
        }

        $cardSizes = $sizes;
        if (empty($cardSizes) && $hasAdvanced) {
            $cardSizes = ['S', 'M', 'L', 'XL', 'XXL'];
        }

        $basicVariants = [];
        if ($hasBasic && !$hasAdvanced) {
            $basicVariants[] = [
                'id'        => 'main',
                'label'     => !empty($variation) ? $variation : $name,
                'price'     => (float)$price,
                'mrp'       => (float)$mrp_price,
                'stock'     => (int)$totalStocks,
                'unlimited' => (int)$isUnlimited,
                'image'     => UPLOADS_URL . $image,
                'inCart'    => in_array('main', $cartVariants) ? 1 : 0,
            ];
            $bvStmt = readData("*", "seller_product_variants", "product_id='$product_id'");
            while ($bv = $bvStmt->fetch(PDO::FETCH_ASSOC)) {
                $basicVariants[] = [
                    'id'        => (string)$bv['id'],
                    'label'     => $bv['variation'] ?? $bv['name'] ?? 'Option',
                    'price'     => (float)$bv['price'],
                    'mrp'       => (float)($bv['mrp_price'] ?? 0),
                    'stock'     => (int)($bv['stock'] ?? 0),
                    'unlimited' => (int)($bv['unlimited_stock'] ?? 0),
                    'image'     => !empty($bv['image']) ? UPLOADS_URL . $bv['image'] : UPLOADS_URL . $image,
                    'inCart'    => in_array((string)$bv['id'], $cartVariants) ? 1 : 0,
                ];
            }
        }

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

        $jsData = [
            'id'              => $id,
            'tag'             => $badge ?: 'NEW',
            'title'           => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
            'slug'            => $slug,
            'price'           => (float)$price,
            'mrp'             => (float)$mrp_price,
            'inCart'          => in_array('main', $cartVariants, true) ? 1 : 0,
            'save'            => $savePercent,
            'description_b64' => base64_encode($description),
            'images'          => $images,
            'sizes'           => array_values($sizes),
            'hasAdvanced'     => $hasAdvanced ? true : false,
            'hasBasic'        => ($hasBasic && !$hasAdvanced) ? true : false,
            'variants'        => [
                'advanced' => $advVariants,
                'basic'    => $basicVariants,
            ],
            'alsoBought'      => $alsoBought,
        ];

        $jsDataStr = htmlspecialchars(json_encode($jsData), ENT_QUOTES, 'UTF-8');

        $html  = '<article class="product" data-product-index="' . $index . '" data-product-data="' . $jsDataStr . '">';
        $html .= '<div class="product-visual">';
        $html .= '<div class="hanging-assembly">';
        $html .= '<div class="thread-ring"></div>';
        $html .= '<div class="thread"></div>';
        $html .= '<div class="shirt-stage">';

        $html .= '<img class="shirt-front" src="' . $images[0] . '" alt="' . htmlspecialchars($name) . '" draggable="false">';
        $imgBack = isset($images[1]) ? $images[1] : $images[0];
        $html .= '<img class="shirt-back" src="' . $imgBack . '" alt="Alternate" draggable="false">';

        $html .= '<div class="price-tag" aria-label="MRP ' . currencyToSymbol($storeCurrency) . $mrp_price . ', offer price ' . currencyToSymbol($storeCurrency) . $price . ', save ' . $savePercent . '%">';
        if ($mrp_price > $price) {
            $html .= '<span class="tag-mrp">MRP ' . currencyToSymbol($storeCurrency) . $mrp_price . '</span>';
        }
        $html .= '<span class="tag-price">' . currencyToSymbol($storeCurrency) . $price . '</span>';
        if ($savePercent > 0) {
            $html .= '<span class="tag-save">SAVE ' . $savePercent . '%</span>';
        }
        $html .= '</div>';

        if ($hasAdvanced && !empty($cardSizes)) {
            $html .= '<div class="size-stack" role="group" aria-label="Select size" data-product-size>';
            $count = 0;
            foreach ($cardSizes as $sz) {
                if ($count >= 5) break;
                $html .= '<button type="button" class="size-option" data-size="' . htmlspecialchars($sz) . '" aria-pressed="false">' . htmlspecialchars($sz) . '</button>';
                $count++;
            }
            $html .= '</div>';
        } else if ($hasBasic && !$hasAdvanced && !empty($basicVariants)) {
            $html .= '<div class="size-stack" role="group" aria-label="Select option" data-product-size>';
            $count = 0;
            foreach ($basicVariants as $bv) {
                if ($count >= 5) break;
                $lbl = $bv['label'] ?: 'Option';
                $html .= '<button type="button" class="size-option" data-size="' . htmlspecialchars($lbl) . '" aria-pressed="false">' . htmlspecialchars(mb_substr($lbl, 0, 3)) . '</button>';
                $count++;
            }
            $html .= '</div>';
        }

        $html .= '</div></div></div>';
        $html .= '</article>';

        return $html;
    }
}
?>

<!-- FLYING HANGER LAYER -->
<div class="fly-layer" id="flyLayer" aria-hidden="true">
    <div class="fly-hanger" id="flyHanger">
        <div class="fly-ring"></div>
        <div class="fly-thread"></div>
        <div class="fly-shirt">
            <img id="flyImg" src="" alt="">
        </div>
    </div>
</div>

<!-- PRODUCT POPUP -->
<div id="productZoomModal" class="t9-qv-modal pz-modal" aria-hidden="true">
    <div class="t9-qv-backdrop" id="pzBackdrop"></div>
    <div class="t9-qv-dialog" id="pzPanel" role="dialog" aria-modal="true">
        <button class="t9-qv-close" id="pzClose" aria-label="Close">&times;</button>

        <div class="t9-qv-layout">
            <div class="t9-qv-gallery">
                <div class="t9-qv-thumbs" id="pzThumbs"></div>
                <div class="t9-qv-main-img" id="pzImageFrame">
                    <img id="pzMainImage" src="" alt="">
                    <span class="t9-qv-sale-badge" id="pzBadge">SALE</span>
                </div>
            </div>

            <div class="t9-qv-info">
                <p class="pz-eyebrow" id="pzTag" style="margin:0 0 6px;font-size:9px;font-weight:700;letter-spacing:2px;color:#a13d91;">NEW</p>
                <h2 class="t9-qv-title" id="pzTitle"></h2>

                <div class="t9-qv-price-row">
                    <span class="t9-qv-mrp" id="pzMrp"></span>
                    <span class="t9-qv-price" id="pzPrice"></span>
                    <span class="t9-qv-save" id="pzSave"></span>
                </div>

                <p class="pz-tax" style="margin:4px 0 0;font-size:9px;color:#737373;">Inclusive of all applicable taxes.</p>

                <div class="t9-qv-stock" id="pzStock"></div>

                <div class="t9-qv-field" id="pzColorField" style="display:none;">
                    <p class="t9-qv-label">Available Styles</p>
                    <div class="t9-qv-colors" id="pzColors"></div>
                </div>

                <div class="t9-qv-field" id="pzSizeField" style="display:none;">
                    <p class="t9-qv-label">Select Size</p>
                    <div class="t9-qv-sizes" id="pzSizes"></div>
                </div>

                <div class="t9-qv-field" id="pzBasicField" style="display:none;">
                    <p class="t9-qv-label">Available Styles</p>
                    <div class="t9-qv-basics" id="pzBasics"></div>
                </div>

                <button type="button" class="t9-qv-addbtn" id="pzAddBtn">ADD TO CART</button>

                <div class="t9-qv-acc">
                    <div class="t9-acc-item">
                        <button type="button" class="t9-acc-trigger" aria-expanded="false">
                            <span>DESCRIPTION</span>
                            <svg class="t9-acc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div class="t9-acc-panel">
                            <div class="t9-acc-inner">
                                <div id="pzDesc" class="t9-rich-desc is-truncated"></div><button type="button" id="pzDescToggle" class="t9-desc-toggle" hidden>Read more</button>
                            </div>
                        </div>
                    </div>

                    <div class="t9-acc-item" id="pzAlsoAccItem">
                        <button type="button" class="t9-acc-trigger" aria-expanded="false">
                            <span>CUSTOMERS ALSO BOUGHT</span>
                            <svg class="t9-acc-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="m6 9 6 6 6-6" />
                            </svg>
                        </button>
                        <div class="t9-acc-panel">
                            <div class="t9-acc-inner">
                                <div id="pzAlsoGrid" class="t9-also-grid"></div>
                            </div>
                        </div>
                    </div>

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
    * {
        box-sizing: border-box;
    }

    /* ======================= HANGER CARD ======================= */
    .collection {
        padding: 22px 0 16px;
        overflow: hidden;
        background: #f5f4f0;
        font-family: Arial, sans-serif;
        color: #222;
    }

    .heading {
        text-align: center;
        padding: 0 15px;
        margin-bottom: 14px;
    }

    .heading small {
        font-size: 9px;
        letter-spacing: 2.5px;
        color: #888;
    }

    .heading h1 {
        font-size: clamp(22px, 3.2vw, 34px);
        font-weight: 500;
        margin: 6px 0 4px;
        letter-spacing: -0.3px;
    }

    .heading p {
        color: #888;
        font-size: 11px;
        margin: 0;
    }

    .rail-scene {
        position: relative;
        height: 14px;
        width: 100%;
        z-index: 5;
        pointer-events: none;
    }

    .steel-rod {
        position: absolute;
        top: 50%;
        left: 26px;
        right: 26px;
        height: 6px;
        transform: translateY(-50%);
        background: linear-gradient(to bottom, #fff, #c6c6c6 18%, #777 40%, #eee 58%, #999 80%, #fafafa);
        border-top: 1px solid #aaa;
        border-bottom: 1px solid #999;
        box-shadow: 0 1px 2px #0003;
    }

    .rail-bracket {
        position: absolute;
        top: 50%;
        width: 24px;
        height: 16px;
        transform: translateY(-50%);
        background: linear-gradient(90deg, #bdbdbd, #f8f8f8 35%, #bdbdbd 75%, #eee);
        border: 1px solid #aaa;
        border-radius: 2px;
    }

    .rail-bracket.left {
        left: 0;
    }

    .rail-bracket.right {
        right: 0;
    }

    .products-scroll {
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 0 16px;
        /* IMPORTANT: no scroll-behavior:smooth — we animate manually */
        scrollbar-width: thin;
        scrollbar-color: #aaa transparent;
        -webkit-overflow-scrolling: touch;
        cursor: grab;
    }

    .products-scroll:active {
        cursor: grabbing;
    }

    .products {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        width: max-content;
        min-width: 100%;
    }

    .product {
        flex: 0 0 230px;
        width: 230px;
        text-align: center;
        cursor: pointer;
    }

    .product-visual {
        position: relative;
        height: 330px;
    }

    .hanging-assembly {
        position: absolute;
        top: -8px;
        left: 50%;
        width: 100%;
        height: 320px;
        transform: translateX(-50%);
        transform-origin: 50% 8px;
    }

    .thread {
        position: absolute;
        top: 0;
        left: 50%;
        width: 1.5px;
        height: 32px;
        transform: translateX(-50%);
        background: linear-gradient(to right, #888, #292929 45%, #b8b8b8);
        box-shadow: 0 0 1px #0004;
        z-index: 2;
    }

    .thread-ring {
        position: absolute;
        top: -1px;
        left: 50%;
        width: 7px;
        height: 7px;
        transform: translateX(-50%);
        border: 1px solid #777;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff, #aaa, #eee);
        z-index: 3;
    }

    .shirt-stage {
        position: absolute;
        top: 28px;
        left: 0;
        width: 100%;
        height: 292px;
    }

    .shirt-stage img {
        position: absolute;
        top: 0;
        left: 50%;
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center top;
        transform: translateX(-50%);
        /* Slightly longer crossfade so the variant swap is smooth */
        transition: opacity .45s ease;
        backface-visibility: hidden;
        user-select: none;
        -webkit-user-drag: none;
    }

    .shirt-front {
        opacity: 1;
    }

    .shirt-back {
        opacity: 0;
    }

    .product:hover .shirt-front {
        opacity: 0;
    }

    .product:hover .shirt-back {
        opacity: 1;
    }

    /* ===== HOVER SHAKE / SWING (per card only) ===== */
    .product:hover .hanging-assembly {
        animation: individualSwing .9s ease-in-out;
    }

    @keyframes individualSwing {
        0% {
            transform: translateX(-50%) rotate(0);
        }

        20% {
            transform: translateX(-50%) rotate(3deg);
        }

        40% {
            transform: translateX(-50%) rotate(-2.2deg);
        }

        60% {
            transform: translateX(-50%) rotate(1.4deg);
        }

        80% {
            transform: translateX(-50%) rotate(-.6deg);
        }

        100% {
            transform: translateX(-50%) rotate(0);
        }
    }

    /* ===== SCROLL SWING (all cards, only while scrolling) ===== */
    .products-scroll.is-scrolling .hanging-assembly {
        animation: scrollSwing .28s ease-in-out infinite alternate !important;
    }

    @keyframes scrollSwing {
        0% {
            transform: translateX(-50%) rotate(-5deg);
        }

        100% {
            transform: translateX(-50%) rotate(2deg);
        }
    }

    .price-tag {
        position: absolute;
        z-index: 6;
        top: 6px;
        right: 6px;
        width: 54px;
        min-height: 60px;
        padding: 9px 3px 4px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 3px;
        color: #29251f;
        text-align: center;
        background: #f8f3e8;
        border: 1px solid #d8cdb8;
        border-radius: 2px 2px 5px 2px;
        box-shadow: 1px 3px 6px #0002;
        transform: rotate(3deg);
        transform-origin: 50% 0;
        pointer-events: none;
    }

    .price-tag::before {
        content: "";
        position: absolute;
        top: -8px;
        left: 50%;
        width: 1.5px;
        height: 8px;
        background: linear-gradient(to right, #777, #b6aa94);
    }

    .price-tag::after {
        content: "";
        position: absolute;
        top: 4px;
        left: 50%;
        width: 4px;
        height: 4px;
        border: 1px solid #b9ad98;
        border-radius: 50%;
        background: #f8f3e8;
        transform: translateX(-50%);
    }

    .tag-mrp {
        margin-top: 3px;
        color: #817b70;
        font-size: 8px;
        line-height: 1;
        text-decoration: line-through;
        text-decoration-color: #d9363649;
        text-decoration-thickness: 1.3px;
        white-space: nowrap;
    }

    .tag-price {
        font-size: 10px;
        line-height: 1;
        font-weight: 800;
        letter-spacing: -.3px;
        white-space: nowrap;
    }

    .tag-save {
        padding: 2px 3px;
        color: #fff;
        background: #b72d2d;
        border-radius: 2px;
        font-size: 5.5px;
        line-height: 1;
        font-weight: 800;
        white-space: nowrap;
    }

    .size-stack {
        position: absolute;
        z-index: 8;
        left: 5px;
        bottom: 5px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: flex-start;
        gap: 4px;
        width: calc(100% - 10px);
        max-width: calc(100% - 10px);
        pointer-events: auto;
    }

    .size-option {
        box-sizing: border-box;
        flex: 0 1 auto;
        min-width: 0;
        max-width: 54px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        border: 1px solid #d4cfc5;
        border-radius: 3px;
        background: rgba(255, 255, 255, .94);
        color: #49443d;
        font: 9.5px Arial, sans-serif;
        cursor: pointer;
        box-shadow: 0 1px 2px #00000012;
        transition: background .18s ease, color .18s ease, border-color .18s ease;
    }

    .size-option:hover,
    .size-option.is-selected {
        border-color: #f15d78;
        background: #f15d78;
        color: #fff;
    }

    .scroll-controls {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        padding: 10px 20px 0;
    }

    .scroll-controls button {
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #ccc;
        border-radius: 50%;
        background: transparent;
        color: #222;
        font-size: 18px;
        cursor: pointer;
        transition: background .2s, color .2s;
    }

    .scroll-controls button:hover {
        background: #222;
        color: #fff;
    }

    /* Fly layer */
    .fly-layer {
        position: fixed;
        inset: 0;
        z-index: 900;
        pointer-events: none;
        display: none;
    }

    .fly-layer.is-active {
        display: block;
    }

    .fly-hanger {
        position: fixed;
        top: 0;
        left: 0;
        width: 0;
        height: 0;
        transform-origin: 50% 8px;
        will-change: transform, top, left, width, height;
        transition: top 1s cubic-bezier(.45, .05, .25, 1), left 1s cubic-bezier(.45, .05, .25, 1),
            width 1s cubic-bezier(.45, .05, .25, 1), height 1s cubic-bezier(.45, .05, .25, 1);
    }

    .fly-ring {
        position: absolute;
        top: -1px;
        left: 50%;
        width: 7px;
        height: 7px;
        transform: translateX(-50%);
        border: 1px solid #777;
        border-radius: 50%;
        background: linear-gradient(135deg, #fff, #aaa, #eee);
        z-index: 3;
        transition: width .5s ease, height .5s ease, opacity .5s ease;
    }

    .fly-thread {
        position: absolute;
        top: 0;
        left: 50%;
        width: 1.5px;
        height: 32px;
        transform: translateX(-50%);
        background: linear-gradient(to right, #888, #292929 45%, #b8b8b8);
        box-shadow: 0 0 1px #0004;
        z-index: 2;
        transition: height .5s ease, opacity .5s ease;
    }

    .fly-shirt {
        position: absolute;
        top: 28px;
        left: 0;
        width: 100%;
        height: 292px;
        transition: top .5s ease, height .5s ease;
    }

    .fly-shirt img {
        position: absolute;
        top: 0;
        left: 50%;
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center top;
        transform: translateX(-50%);
    }

    .fly-hanger.is-flying .fly-thread {
        height: 0;
        opacity: 0;
    }

    .fly-hanger.is-flying .fly-ring {
        width: 0;
        height: 0;
        opacity: 0;
    }

    .fly-hanger.is-flying .fly-shirt {
        top: 0;
        height: 100%;
    }

    /* ======================= POPUP (exact T9 styles) ======================= */
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
        background: #84366f;
        color: #fff;
        border-color: #84366f;
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
        border-color: #84366f;
    }

    .t9-qv-thumbs img {
        width: 100%;
        height: 100%;
        object-fit: contain;
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
        object-fit: contain;
        display: block;
        transition: opacity .35s ease;
    }

    .t9-qv-sale-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: #84366f;
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
        color: #84366f;
        letter-spacing: .5px;
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
        box-shadow: 0 0 0 2px #84366f, 0 0 0 4px #fff;
        border-color: #84366f;
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
        border-color: #84366f;
        color: #0a0a0a;
    }

    .t9-qv-size-btn.is-active,
    .t9-qv-basic-btn.is-active {
        background: #84366f;
        color: #fff;
        border-color: #84366f;
    }

    .t9-qv-size-btn:disabled,
    .t9-qv-basic-btn:disabled {
        opacity: .4;
        text-decoration: line-through;
        cursor: not-allowed;
    }

    .t9-qv-addbtn {
        margin-top: 22px;
        width: 100%;
        padding: 16px;
        background: #84366f;
        color: #fff !important;
        border: 1px solid #84366f;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        cursor: pointer;
        transition: background .2s ease, border-color .2s ease;
        border-radius: 4px;
    }

    .t9-qv-addbtn:hover:not(:disabled) {
        background: #222;
        border-color: #222;
        color: #fff !important;
    }

    .t9-qv-addbtn:disabled {
        opacity: 1;
        background: #f3f3f3;
        color: #9ca3af !important;
        border-color: #e5e5e5;
        cursor: not-allowed;
    }

    .t9-qv-addbtn.is-in-cart {
        background: #fbeaf5;
        color: #84366f !important;
        border-color: #e9c8de;
        opacity: 1;
    }

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

    .t9-rich-desc {
        font-size: 12px;
        line-height: 1.75;
        color: #525252;
    }

    .t9-rich-desc.is-truncated {
        display: -webkit-box;
        -webkit-line-clamp: 5;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .t9-desc-toggle {
        margin-top: 8px;
        padding: 0;
        border: 0;
        background: transparent;
        color: #84366f;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
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
        color: #84366f;
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
        border-color: #84366f;
    }

    .t9-also-card img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: contain;
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

    @media (max-width: 900px) {
        .product {
            flex: 0 0 220px;
            width: 220px;
        }
    }

    @media (max-width: 600px) {
        .product {
            flex-basis: 190px;
            width: 190px;
        }

        .product-visual {
            height: 270px;
        }

        .hanging-assembly {
            height: 260px;
            top: -6px;
        }

        .thread {
            height: 26px;
        }

        .shirt-stage {
            top: 22px;
            height: 240px;
        }

        .price-tag {
            top: 5px;
            right: 4px;
            width: 48px;
            min-height: 54px;
            padding: 8px 2px 3px;
        }

        .tag-price {
            font-size: 9px;
        }

        .tag-mrp {
            font-size: 6px;
        }

        .tag-save {
            font-size: 5px;
        }

        .size-stack {
            left: 4px;
            bottom: 4px;
            gap: 3px;
            width: calc(100% - 8px);
            max-width: calc(100% - 8px);
        }

        .size-option {
            max-width: 46px;
            height: 16px;
            padding: 0 4px;
            font-size: 8.5px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        *,
        *::before,
        *::after {
            animation: none !important;
            transition: none !important;
            scroll-behavior: auto !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const CURRENCY = <?= json_encode(currencyToSymbol($storeCurrency)) ?>;
        const STORE_URL = <?= json_encode($storeUrl) ?>;
        const APP_URL = <?= json_encode(rtrim(APP_URL, '/') . '/') ?>;
        const ADD_URL = APP_URL + 'shop/ajax/add-to-cart.php';

        /* ============================================================
           SLIDER — FIXED ARROWS + SWING WHILE SCROLLING
           ============================================================ */
        const slider = document.getElementById("productsScroll");
        const leftButton = document.getElementById("scrollLeft");
        const rightButton = document.getElementById("scrollRight");
        let isClickScrolling = false;
        let scrollTimer = null;

        function startSwingingAllClothes() {
            if (!slider) return;
            if (isClickScrolling) return;
            slider.classList.add("is-scrolling");
            clearTimeout(scrollTimer);
            scrollTimer = setTimeout(() => slider.classList.remove("is-scrolling"), 350);
        }

        /* ------------------------------------------------------------
           CROSS-FADE A CARD IMAGE — runs EVERY click, rapid clicks safe
           - cancels previous timer on the same card
           - forces reflow so CSS opacity transition always fires
           - swaps src while opacity 0, then fades back to 1
           ------------------------------------------------------------ */
        function crossFadeCardImage(card, newSrc) {
            if (!card || !newSrc) return;

            const frontImg = card.querySelector(".shirt-front");
            const backImg = card.querySelector(".shirt-back");
            if (!frontImg) return;

            // Cancel any in-flight timer from a previous click on this card
            if (card._xfadeTimer) {
                clearTimeout(card._xfadeTimer);
                card._xfadeTimer = null;
            }

            // Reset transition to make sure we can restart it cleanly
            frontImg.style.transition = "none";
            void frontImg.offsetWidth; // force reflow
            frontImg.style.transition = "";

            // Fade out (explicit 0)
            frontImg.style.opacity = "0";

            // Swap src after the fade-out, then fade back to 1
            card._xfadeTimer = setTimeout(() => {
                frontImg.src = newSrc;
                void frontImg.offsetWidth; // force reflow so new src is committed
                requestAnimationFrame(() => {
                    frontImg.style.opacity = "1";
                });
                card._xfadeTimer = null;
            }, 460); // matches .45s transition on .shirt-stage img

            // Back image swaps silently
            if (backImg) backImg.src = newSrc;
        }

        if (slider) {
            slider.addEventListener("scroll", startSwingingAllClothes, {
                passive: true
            });

            function scrollSlider(direction) {
                if (!slider) return;

                isClickScrolling = true;
                slider.classList.add("is-scrolling");
                clearTimeout(scrollTimer);

                const start = slider.scrollLeft;
                const target = start + (direction * 450);
                const distance = target - start;
                const duration = 400;
                let startTime = null;

                function animation(currentTime) {
                    if (startTime === null) startTime = currentTime;
                    const timeElapsed = currentTime - startTime;
                    const progress = Math.min(timeElapsed / duration, 1);
                    const ease = progress * (2 - progress);

                    slider.scrollLeft = start + (distance * ease);

                    if (progress < 1) {
                        requestAnimationFrame(animation);
                    } else {
                        isClickScrolling = false;
                        slider.classList.remove("is-scrolling");
                    }
                }
                requestAnimationFrame(animation);
            }

            leftButton?.addEventListener("click", (e) => {
                e.preventDefault();
                scrollSlider(-1);
            });
            rightButton?.addEventListener("click", (e) => {
                e.preventDefault();
                scrollSlider(1);
            });

            /* ------------------------------------------------------------
               SIZE CHIP CLICK ON THE CARD
               - highlights the chip
               - smooth cross-fade the card image to the matched variant
                 (advanced first, then basic). Plays on EVERY click.
               ------------------------------------------------------------ */
            document.querySelectorAll(".size-stack").forEach(sizeStack => {
                sizeStack.addEventListener("click", event => {
                    const button = event.target.closest(".size-option");
                    if (!button) return;
                    event.stopPropagation();

                    sizeStack.querySelectorAll(".size-option").forEach(o => {
                        o.classList.remove("is-selected");
                        o.setAttribute("aria-pressed", "false");
                    });
                    button.classList.add("is-selected");
                    button.setAttribute("aria-pressed", "true");
                    sizeStack.dataset.selectedSize = button.dataset.size;

                    const card = button.closest(".product");
                    if (!card) return;

                    let data;
                    try {
                        data = JSON.parse(card.dataset.productData || "{}");
                    } catch (e) {
                        return;
                    }

                    const clickedSize = (button.dataset.size || "").trim();
                    let matchedImage = null;

                    if (data.hasAdvanced && data.variants && Array.isArray(data.variants.advanced)) {
                        const sizeMatches = data.variants.advanced.filter(
                            v => String(v.size || "").trim() === clickedSize
                        );
                        const withImage = sizeMatches.find(v => !!v.image);
                        if (withImage) matchedImage = withImage.image;
                        else if (sizeMatches.length) matchedImage = sizeMatches[0].image;
                    }

                    if (!matchedImage && data.hasBasic && data.variants && Array.isArray(data.variants.basic)) {
                        const labelMatches = data.variants.basic.filter(
                            v => String(v.label || "").trim() === clickedSize
                        );
                        const withImage = labelMatches.find(v => !!v.image);
                        if (withImage) matchedImage = withImage.image;
                        else if (labelMatches.length) matchedImage = labelMatches[0].image;
                    }

                    if (!matchedImage) return;

                    crossFadeCardImage(card, matchedImage);

                    if (Array.isArray(data.images) && data.images.length) {
                        data.images[0] = matchedImage;
                        card.dataset.productData = JSON.stringify(data);
                    }
                });
            });

            // Drag to scroll
            let dragging = false,
                startXMouse = 0,
                initialScroll = 0;
            slider.addEventListener("mousedown", event => {
                if (event.target.closest("button")) return;
                dragging = true;
                startXMouse = event.pageX;
                initialScroll = slider.scrollLeft;
            });
            window.addEventListener("mouseup", () => dragging = false);
            window.addEventListener("mousemove", event => {
                if (!dragging) return;
                const distance = event.pageX - startXMouse;
                if (Math.abs(distance) > 2) {
                    slider.scrollLeft = initialScroll - distance;
                }
            });
        }

        /* ============ POPUP ELEMENTS ============ */
        const flyLayer = document.getElementById('flyLayer');
        const flyHanger = document.getElementById('flyHanger');
        const flyImg = document.getElementById('flyImg');

        const modal = document.getElementById('productZoomModal');
        const elImg = document.getElementById('pzMainImage');
        const elImageFrame = document.getElementById('pzImageFrame');
        const elThumbs = document.getElementById('pzThumbs');
        const elTitle = document.getElementById('pzTitle');
        const elTag = document.getElementById('pzTag');
        const elPrice = document.getElementById('pzPrice');
        const elMrp = document.getElementById('pzMrp');
        const elSave = document.getElementById('pzSave');
        const elStock = document.getElementById('pzStock');
        const elDesc = document.getElementById('pzDesc');
        const elDescToggle = document.getElementById('pzDescToggle');
        const elColors = document.getElementById('pzColors');
        const elSizes = document.getElementById('pzSizes');
        const elBasics = document.getElementById('pzBasics');
        const elColorFld = document.getElementById('pzColorField');
        const elSizeFld = document.getElementById('pzSizeField');
        const elBasicFld = document.getElementById('pzBasicField');
        const elAddBtn = document.getElementById('pzAddBtn');
        const elAlsoGrid = document.getElementById('pzAlsoGrid');
        const elAlsoAccItem = document.getElementById('pzAlsoAccItem');

        let state = {
            product: null,
            variants: {
                advanced: [],
                basic: []
            },
            selectedColorId: null,
            selectedVariant: null
        };
        let currentImageIndex = 0;
        let isAnimating = false;

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
            t.style.background = isErr ? '#ef4444' : '#84366f';
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

        /* ------------------------------------------------------------
           POPUP MAIN IMAGE CROSS-FADE — plays on EVERY change
           ------------------------------------------------------------ */
        function crossFadePopupImage(newSrc) {
            if (!elImg || !newSrc) return;

            if (elImg._xfadeTimer) {
                clearTimeout(elImg._xfadeTimer);
                elImg._xfadeTimer = null;
            }

            elImg.style.transition = "none";
            void elImg.offsetWidth;
            elImg.style.transition = "";

            elImg.style.opacity = "0";

            elImg._xfadeTimer = setTimeout(() => {
                elImg.src = newSrc;
                void elImg.offsetWidth;
                requestAnimationFrame(() => {
                    elImg.style.opacity = "1";
                });
                elImg._xfadeTimer = null;
            }, 260);
        }

        document.querySelectorAll('.t9-acc-trigger').forEach(trigger => {
            trigger.addEventListener('click', () => {
                const isOpen = trigger.getAttribute('aria-expanded') === 'true';
                document.querySelectorAll('.t9-acc-trigger').forEach(t => t.setAttribute('aria-expanded', 'false'));
                if (!isOpen) trigger.setAttribute('aria-expanded', 'true');
            });
        });

        function launchProduct(card) {
            if (isAnimating) return;
            let product;
            try {
                product = JSON.parse(card.dataset.productData);
            } catch (e) {
                return;
            }
            if (!product) return;

            isAnimating = true;
            const visual = card.querySelector('.hanging-assembly');
            if (!visual) {
                isAnimating = false;
                return;
            }

            const startRect = visual.getBoundingClientRect();
            flyImg.src = product.images[0] || '';
            flyLayer.classList.add('is-active');

            flyHanger.classList.remove('is-flying');
            flyHanger.style.transition = 'none';
            flyHanger.style.top = startRect.top + 'px';
            flyHanger.style.left = startRect.left + 'px';
            flyHanger.style.width = startRect.width + 'px';
            flyHanger.style.height = startRect.height + 'px';
            void flyHanger.offsetWidth;

            modal.style.visibility = 'hidden';
            modal.style.opacity = '1';
            modal.classList.add('is-open');
            elImg.src = product.images[0] || '';
            const frameRect = elImageFrame.getBoundingClientRect();

            modal.classList.remove('is-open');
            modal.style.visibility = '';
            modal.style.opacity = '';

            flyHanger.style.transition = '';

            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    flyHanger.classList.add('is-flying');
                    flyHanger.style.top = frameRect.top + 'px';
                    flyHanger.style.left = frameRect.left + 'px';
                    flyHanger.style.width = frameRect.width + 'px';
                    flyHanger.style.height = frameRect.height + 'px';
                });
            });

            setTimeout(() => {
                flyLayer.classList.remove('is-active');
                flyHanger.classList.remove('is-flying');
                flyHanger.style.transition = 'none';
                flyHanger.style.top = '0px';
                flyHanger.style.left = '0px';
                flyHanger.style.width = '0px';
                flyHanger.style.height = '0px';
                void flyHanger.offsetWidth;
                flyHanger.style.transition = '';

                openPopup(product);
                setTimeout(() => {
                    isAnimating = false;
                }, 300);
            }, 1050);
        }

        function openPopup(product) {
            state.product = {
                id: product.id,
                name: product.title,
                slug: product.slug,
                image: product.images[0] || '',
                price: product.price,
                mrp: product.mrp,
                inCart: !!product.inCart,
                descB64: product.description_b64 || '',
                hasAdvanced: !!product.hasAdvanced,
                hasBasic: !!product.hasBasic,
            };
            state.variants = {
                advanced: (product.variants && product.variants.advanced) ? product.variants.advanced.map(v => Object.assign({}, v)) : [],
                basic: (product.variants && product.variants.basic) ? product.variants.basic.map(v => Object.assign({}, v)) : []
            };
            state.selectedColorId = null;
            state.selectedVariant = null;
            currentImageIndex = 0;

            const p = state.product;

            elTag.textContent = product.tag || 'NEW';
            elTitle.textContent = p.name;
            elPrice.textContent = fmt(p.price);
            if (p.mrp > p.price) {
                elMrp.textContent = fmt(p.mrp);
                elMrp.style.display = '';
                elSave.textContent = 'SAVE ' + Math.round(((p.mrp - p.price) / p.mrp) * 100) + '%';
                elSave.style.display = '';
            } else {
                elMrp.style.display = 'none';
                elSave.style.display = 'none';
            }
            elStock.innerHTML = '';

            const descHTML = decodeB64(p.descB64).trim();
            elDesc.innerHTML = descHTML || '<p style="color:#999;">No description available.</p>';
            elDesc.classList.add('is-truncated');
            if (elDescToggle) {
                elDescToggle.hidden = true;
                elDescToggle.textContent = 'Read more';
                requestAnimationFrame(() => {
                    if (elDesc.scrollHeight > elDesc.clientHeight + 2) elDescToggle.hidden = false;
                });
            }

            elImg.src = p.image;
            elImg.alt = p.name;
            renderThumbs(product.images || []);

            elAlsoGrid.innerHTML = '';
            const alsoBought = product.alsoBought || [];
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
                        '</div></div>';
                    elAlsoGrid.appendChild(link);
                });
                elAlsoAccItem.style.display = '';
            } else {
                elAlsoAccItem.style.display = 'none';
            }

            document.querySelectorAll('.t9-acc-trigger').forEach(t => t.setAttribute('aria-expanded', 'false'));

            elColorFld.style.display = 'none';
            elSizeFld.style.display = 'none';
            elBasicFld.style.display = 'none';
            elColors.innerHTML = '';
            elSizes.innerHTML = '';
            elBasics.innerHTML = '';
            elAddBtn.disabled = true;
            elAddBtn.textContent = 'SELECT OPTIONS';
            elAddBtn.classList.remove('is-in-cart');

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
                    inCart: !!p.inCart,
                };
                updatePriceAndStock();
            }

            modal.classList.add('is-open');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function buildAdvanced() {
            const grouped = {};
            state.variants.advanced.forEach(v => {
                const cid = v.colorId || 'default';
                if (!grouped[cid]) grouped[cid] = {
                    colorId: cid,
                    name: v.color || 'Style',
                    code: v.colorCode || '#ccc',
                    sizes: []
                };
                grouped[cid].sizes.push(v);
            });

            elColorFld.style.display = '';
            elSizeFld.style.display = '';

            const keys = Object.keys(grouped);
            keys.forEach((cid, idx) => {
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

            if (keys.length) {
                state.selectedColorId = keys[0];
                renderSizes(grouped[keys[0]]);
            }
        }

        function renderSizes(colorData) {
            elSizes.innerHTML = '';
            state.selectedVariant = null;
            elAddBtn.disabled = true;
            elAddBtn.textContent = 'SELECT SIZE';
            elAddBtn.classList.remove('is-in-cart');

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
            });

            const firstAny = elSizes.querySelector('button:not([disabled])');
            if (firstAny) firstAny.click();
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
                elSave.textContent = 'SAVE ' + Math.round(((v.mrp - v.price) / v.mrp) * 100) + '%';
                elSave.style.display = '';
            } else {
                elMrp.style.display = 'none';
                elSave.style.display = 'none';
            }
            if (v.image) crossFadePopupImage(v.image);

            if (v.unlimited) elStock.innerHTML = '<span style="color:#16a34a">In Stock</span>';
            else if (v.stock > 0) elStock.innerHTML = '<span style="color:#16a34a">' + v.stock + ' in stock</span>';
            else elStock.innerHTML = '<span style="color:#ef4444">Out of Stock</span>';

            if (v.inCart) {
                elAddBtn.disabled = true;
                elAddBtn.textContent = 'ALREADY IN BAG';
                elAddBtn.classList.add('is-in-cart');
            } else {
                elAddBtn.classList.remove('is-in-cart');
                elAddBtn.disabled = (v.stock <= 0 && !v.unlimited);
                elAddBtn.textContent = elAddBtn.disabled ? 'SOLD OUT' : 'ADD TO CART';
            }
        }

        function renderThumbs(images) {
            elThumbs.innerHTML = '';
            if (!images || !images.length) return;
            images.forEach((src, i) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                if (i === currentImageIndex) btn.classList.add('is-active');
                const img = document.createElement('img');
                img.src = src;
                btn.appendChild(img);
                btn.addEventListener('click', () => {
                    currentImageIndex = i;
                    crossFadePopupImage(src);
                    elThumbs.querySelectorAll('button').forEach((b, bi) => b.classList.toggle('is-active', bi === i));
                });
                elThumbs.appendChild(btn);
            });
        }

        function closePopup() {
            modal.classList.remove('is-open');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.product').forEach(card => {
            card.addEventListener('click', event => {
                if (event.target.closest('.size-option')) return;
                launchProduct(card);
            });
        });

        document.getElementById('pzClose')?.addEventListener('click', closePopup);
        document.getElementById('pzBackdrop')?.addEventListener('click', closePopup);
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && modal?.classList.contains('is-open')) closePopup();
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
                    xhrFields: { withCredentials: true },
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
                    error: (xhr) => {
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
                    body: new URLSearchParams(payload)
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
                .catch(() => onErr('Network error.'));
        }

        function markVariantInCart(variant) {
            if (!variant) return;

            if (state.selectedVariant) state.selectedVariant.inCart = 1;

            if (variant.variantType === 'advanced' && variant.variantId) {
                state.variants.advanced.forEach(v => {
                    if (String(v.id) === String(variant.variantId)) v.inCart = 1;
                });
            } else if (variant.variantType === 'basic' && variant.variantId) {
                state.variants.basic.forEach(v => {
                    if (String(v.id) === String(variant.variantId)) v.inCart = 1;
                });
            } else if (variant.variantType === 'main') {
                state.variants.basic.forEach(v => {
                    if (v.id === 'main') v.inCart = 1;
                });
            }

            try {
                const cards = document.querySelectorAll('.product');
                cards.forEach(card => {
                    if (!card.dataset.productData) return;
                    let data = JSON.parse(card.dataset.productData);
                    if (!data || String(data.id) !== String(state.product.id)) return;

                    if (variant.variantType === 'advanced' && variant.variantId) {
                        (data.variants.advanced || []).forEach(v => {
                            if (String(v.id) === String(variant.variantId)) v.inCart = 1;
                        });
                    } else if (variant.variantType === 'basic' && variant.variantId) {
                        (data.variants.basic || []).forEach(v => {
                            if (String(v.id) === String(variant.variantId)) v.inCart = 1;
                        });
                    } else if (variant.variantType === 'main') {
                        (data.variants.basic || []).forEach(v => {
                            if (v.id === 'main') v.inCart = 1;
                        });
                    }

                    card.dataset.productData = JSON.stringify(data);
                });
            } catch (e) {
                /* ignore */
            }
        }

        elDescToggle?.addEventListener('click', () => {
            const collapsed = elDesc.classList.toggle('is-truncated');
            elDescToggle.textContent = collapsed ? 'Read more' : 'Read less';
        });

        elAddBtn?.addEventListener('click', () => {
            if (!state.selectedVariant || elAddBtn.disabled) return;
            const orig = elAddBtn.textContent;
            elAddBtn.disabled = true;
            elAddBtn.textContent = 'ADDING...';

            const payload = {
                product_id: state.product.id,
                quantity: 1,
                variant: '',
                advanced_variant: '',
                other: ''
            };
            const v = state.selectedVariant;
            if (v.variantType === 'advanced') payload.advanced_variant = v.variantId;
            else if (v.variantType === 'basic') payload.variant = v.variantId;

            sendAddToCart(payload, res => {
                if (res.success || (res.message && /already|duplicate|exist/i.test(res.message))) {
                    toast(res.message || 'Added to bag!', false);
                    markVariantInCart(v);
                    elAddBtn.textContent = 'ALREADY IN BAG';
                    elAddBtn.classList.add('is-in-cart');
                    elAddBtn.disabled = true;
                    if (typeof window.updateHeaderCounts === 'function') window.updateHeaderCounts();
                    if (typeof window.updateCartCounts === 'function') window.updateCartCounts();
                    // Popup stays open (no auto-close)
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
    });
</script>