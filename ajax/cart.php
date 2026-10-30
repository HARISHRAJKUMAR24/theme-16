<?php
// ============================================================
// THEME 17 STYLE CART — matches the "Latest Collection" aesthetic
// ============================================================

$cartItems = getCartData()->fetchAll(PDO::FETCH_ASSOC);

$outOfStockCount   = 0;
$subTotal          = 0.0;
$subTotalWithTax   = 0.0;
$gstAmount         = 0.0;

foreach ($cartItems as $key => $cart) {
    $productId   = $cart['product_id'];
    $productData = readData("*", "seller_products", "id='$productId'")->fetch(PDO::FETCH_ASSOC);

    $image       = $productData['image'] ?? '';
    $productName = $productData['name'] ?? '';

    // ---------- Stock logic ----------
    $isOutOfStock   = false;
    $unlimitedStock = $productData['unlimited_stock'] ?? 0;
    $totalStocks    = $productData['total_stocks'] ?? 0;

    $variantId   = $cart['other'] ?? null;
    $advVarId    = $cart['advanced_variant'] ?? null;

    if (!empty($advVarId)) {
        $advVarData = readData("*", "seller_product_advanced_variants", "id='$advVarId'")->fetch(PDO::FETCH_ASSOC);
        if ($advVarData) {
            if (!empty($advVarData['image'])) $image = $advVarData['image'];
            if (($advVarData['unlimited_stock'] ?? 0) != 1 && ($advVarData['stock'] ?? 0) <= 0) $isOutOfStock = true;
        }
    } elseif (!empty($variantId)) {
        $varData = readData("*", "seller_product_variants", "id='$variantId'")->fetch(PDO::FETCH_ASSOC);
        if ($varData) {
            if (!empty($varData['image'])) $image = $varData['image'];
            if (($varData['unlimited_stock'] ?? 0) != 1 && ($varData['stock'] ?? 0) <= 0) $isOutOfStock = true;
        }
    } else {
        if ($unlimitedStock != 1 && $totalStocks <= 0) $isOutOfStock = true;
    }

    if ($isOutOfStock) $outOfStockCount++;

    // ---------- Variant text ----------
    $variationParts = [];
    if (!empty($variantId)) {
        $varText = getData("variation", "seller_product_variants", "id='$variantId'");
        if ($varText) $variationParts[] = $varText;
    }
    if (!empty($advVarId)) {
        $size    = getData("size", "seller_product_advanced_variants", "id='$advVarId'");
        $colorId = getData("color", "seller_product_advanced_variants", "id='$advVarId'");
        $color   = $colorId ? getData("color_name", "product_colors", "id='$colorId'") : '';
        if ($size)  $variationParts[] = "Size: $size";
        if ($color) $variationParts[] = "Color: $color";
    }
    $variationText = implode(" · ", $variationParts);

    $cartItems[$key]['is_out_of_stock'] = $isOutOfStock;
    $cartItems[$key]['product_name']    = $productName;
    $cartItems[$key]['image']           = $image;
    $cartItems[$key]['variation_text']  = $variationText;
    $cartItems[$key]['slug']            = getData("slug", "seller_products", "id='$productId'");

    // ---------- Totals ----------
    if (!$isOutOfStock) {
        $qty   = (int)$cart['quantity'];
        $price = (float)$cart['price'];
        $subTotal += $price * $qty;

        $gstPercentage = getData("gst_percentage", "seller_products", "id='$productId'");
        $gstCalc = ($gstPercentage !== null && $gstPercentage !== '')
            ? calculateGst($price * $qty, $gstPercentage)
            : calculateGst($price * $qty);

        $subTotalWithTax += isset($gstCalc['getPrice'])  ? (float)$gstCalc['getPrice'] : ($price * $qty);
        $gstAmount       += isset($gstCalc['gstAmount']) ? (float)$gstCalc['gstAmount'] : 0;
    }
}
?>

<script>
    var currentUserId = <?= isset($customerId) && !empty($customerId) ? json_encode($customerId) : 'null' ?>;
</script>

<?php if (empty($cartItems)): ?>
    <!-- ================= EMPTY STATE ================= -->
    <div class="t17-empty">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
            <path d="M2.5 3.5h2l2.4 12h11.8l2.2-8.5H5.3" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="9" cy="19.5" r="1.2"/>
            <circle cx="17" cy="19.5" r="1.2"/>
        </svg>
        <p>Your cart is currently empty.</p>
        <a href="<?= $storeUrl ?>shop-all">
            Continue Shopping <span aria-hidden="true">→</span>
        </a>
    </div>
<?php else: ?>

<div class="grid grid-cols-1 gap-10 lg:grid-cols-[1fr_360px] lg:gap-12">

    <!-- ================= LEFT: CART ITEMS ================= -->
    <section>

        <!-- Desktop header -->
        <div class="hidden sm:grid mb-2 grid-cols-[110px_1fr_110px_120px_120px_32px] gap-4 border-b border-gray-200 pb-3">
            <span></span>
            <span class="t17-eyebrow">Product</span>
            <span class="t17-eyebrow text-center">Price</span>
            <span class="t17-eyebrow text-center">Quantity</span>
            <span class="t17-eyebrow text-center">Total</span>
            <span></span>
        </div>

        <!-- Cart rows -->
        <div id="cartItemsContainer">
            <?php foreach ($cartItems as $cart): ?>
                <article class="t17-row cart-row"
                    data-id="<?= $cart['product_id'] ?>"
                    data-variant="<?= $cart['other'] ?? '' ?>"
                    data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>">

                    <!-- Image -->
                    <a href="<?= $storeUrl ?>product/<?= htmlspecialchars($cart['slug']) ?>">
                        <div class="t17-row-img <?= $cart['is_out_of_stock'] ? 'is-oos' : '' ?>">
                            <img src="<?= UPLOADS_URL . htmlspecialchars($cart['image']) ?>"
                                 alt="<?= htmlspecialchars($cart['product_name']) ?>">
                        </div>
                    </a>

                    <!-- Info (mobile + desktop) -->
                    <div class="min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <a href="<?= $storeUrl ?>product/<?= htmlspecialchars($cart['slug']) ?>"
                               class="t17-row-name min-w-0 flex-1">
                                <?= htmlspecialchars($cart['product_name']) ?>
                            </a>

                            <!-- Mobile remove button: top-right -->
                            <button type="button" class="removeQtyBtn t17-remove shrink-0 sm:hidden"
                                data-id="<?= $cart['product_id'] ?>"
                                data-variant="<?= $cart['other'] ?? '' ?>"
                                data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>"
                                aria-label="Remove item">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                    <path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M10 11v6M14 11v6" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>

                        <?php if (!empty($cart['variation_text'])): ?>
                            <p class="t17-row-variant"><?= htmlspecialchars($cart['variation_text']) ?></p>
                        <?php endif; ?>

                        <?php if ($cart['is_out_of_stock']): ?>
                            <p class="t17-stock-out">Out of stock</p>
                        <?php else: ?>
                            <p class="t17-stock-in">In stock</p>
                        <?php endif; ?>

                        <!-- Mobile-only price / qty / total / remove -->
                        <div class="mt-4 space-y-3 sm:hidden">
                            <div class="flex items-center justify-between">
                                <span class="t17-mobile-label">Price</span>
                                <span class="t17-row-price"><?= currencyToSymbol($storeCurrency) . number_format($cart['price'], 2) ?></span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="t17-mobile-label">Quantity</span>
                                <?php if ($cart['is_out_of_stock']): ?>
                                    <span class="text-xs text-gray-400 italic">Unavailable</span>
                                <?php else: ?>
                                    <div class="t17-qty">
                                        <button type="button" class="decreaseQtyBtn"
                                            data-id="<?= $cart['product_id'] ?>"
                                            data-variant="<?= $cart['other'] ?? '' ?>"
                                            data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>">−</button>
                                        <input type="number" class="currentQty" value="<?= $cart['quantity'] ?>" min="1" readonly>
                                        <button type="button" class="increaseQtyBtn"
                                            data-id="<?= $cart['product_id'] ?>"
                                            data-variant="<?= $cart['other'] ?? '' ?>"
                                            data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>">+</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="t17-mobile-label">Total</span>
                                <span class="t17-row-total"><?= currencyToSymbol($storeCurrency) . number_format($cart['price'] * $cart['quantity'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop price -->
                    <div class="hidden sm:block t17-row-price text-center">
                        <?= currencyToSymbol($storeCurrency) . number_format($cart['price'], 2) ?>
                    </div>

                    <!-- Desktop qty -->
                    <div class="hidden sm:flex justify-center">
                        <?php if ($cart['is_out_of_stock']): ?>
                            <span class="text-xs text-gray-400 italic">Unavailable</span>
                        <?php else: ?>
                            <div class="t17-qty">
                                <button type="button" class="decreaseQtyBtn"
                                    data-id="<?= $cart['product_id'] ?>"
                                    data-variant="<?= $cart['other'] ?? '' ?>"
                                    data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>">−</button>
                                <input type="number" class="currentQty" value="<?= $cart['quantity'] ?>" min="1" readonly>
                                <button type="button" class="increaseQtyBtn"
                                    data-id="<?= $cart['product_id'] ?>"
                                    data-variant="<?= $cart['other'] ?? '' ?>"
                                    data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>">+</button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Desktop total -->
                    <div class="hidden sm:block t17-row-total text-center">
                        <?= currencyToSymbol($storeCurrency) . number_format($cart['price'] * $cart['quantity'], 2) ?>
                    </div>

                    <!-- Desktop remove -->
                    <div class="hidden sm:flex justify-center">
                        <button type="button" class="removeQtyBtn t17-remove"
                            data-id="<?= $cart['product_id'] ?>"
                            data-variant="<?= $cart['other'] ?? '' ?>"
                            data-advancedvariant="<?= $cart['advanced_variant'] ?? '' ?>"
                            aria-label="Remove item">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                                <path d="M3 6h18M8 6V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M10 11v6M14 11v6" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>

        <!-- Continue shopping -->
        <a href="<?= $storeUrl ?>shop-all" class="t17-continue">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M11 6l-6 6 6 6" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            Continue Shopping
        </a>

    </section>

    <!-- ================= RIGHT: SUMMARY ================= -->
    <aside class="lg:sticky lg:top-6 lg:self-start">
        <div class="t17-summary">
            <h2>Order Summary</h2>

            <div class="t17-summary-row">
                <span>Subtotal</span>
                <b><?= currencyToSymbol($storeCurrency) . number_format($subTotal, 2) ?></b>
            </div>
            <div class="t17-summary-row">
                <span>Shipping</span>
                <span style="color:#737373;font-size:12px;">Calculated at checkout</span>
            </div>
            <div class="t17-summary-row">
                <span>Estimated GST</span>
                <b>
                    <?= getSettings("gst_tax_type") == "inclusive"
                        ? currencyToSymbol($storeCurrency) . "0.00"
                        : currencyToSymbol($storeCurrency) . number_format($gstAmount, 2) ?>
                </b>
            </div>

            <div class="t17-summary-total">
                <span>Total</span>
                <span><?= currencyToSymbol($storeCurrency) . number_format($subTotalWithTax, 2) ?></span>
            </div>

            <button onclick="proceedToCheckout(event)"
                class="t17-checkout-btn"
                <?= ($outOfStockCount > 0) ? 'disabled' : '' ?>>
                <?= ($outOfStockCount > 0) ? 'Remove Out of Stock' : 'Proceed to Checkout' ?>
            </button>

            <div class="t17-trust">
                <div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 3 4 6v6c0 5 3.4 8.5 8 9 4.6-.5 8-4 8-9V6l-8-3Z" stroke-linejoin="round"/>
                        <path d="m9 12 2 2 4-4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Secure SSL encrypted checkout</span>
                </div>
                <div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="6" width="18" height="13" rx="2"/>
                        <path d="M3 10h18" stroke-linecap="round"/>
                    </svg>
                    <span>GST invoice available</span>
                </div>
            </div>
        </div>
    </aside>

</div>
<?php endif; ?>

<!-- ================= JAVASCRIPT ================= -->
<script>
    function showCustomToast(message, type) {
        document.querySelectorAll('.simple-toast').forEach(t => t.remove());
        const toast = document.createElement('div');
        toast.className = 'simple-toast fixed top-24 right-4 z-[999] px-5 py-3 rounded-md shadow-xl text-sm font-semibold text-white flex items-center gap-2 toast-slide-in';
        toast.style.backgroundColor = type === 'error' ? '#ef4444' : '#10b981';
        toast.innerHTML = '<span>' + message + '</span>';
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.classList.remove('toast-slide-in');
            toast.classList.add('toast-slide-out');
            setTimeout(() => toast.remove(), 400);
        }, 2800);
    }

    document.addEventListener('click', function (e) {

        const inc = e.target.closest('.increaseQtyBtn');
        if (inc) {
            const input = inc.parentElement.querySelector('.currentQty');
            updateCartQty(inc.dataset.id, inc.dataset.variant, inc.dataset.advancedvariant, parseInt(input.value) + 1);
            return;
        }

        const dec = e.target.closest('.decreaseQtyBtn');
        if (dec) {
            const input = dec.parentElement.querySelector('.currentQty');
            if (parseInt(input.value) > 1) {
                updateCartQty(dec.dataset.id, dec.dataset.variant, dec.dataset.advancedvariant, parseInt(input.value) - 1);
            }
            return;
        }

        const rem = e.target.closest('.removeQtyBtn');
        if (rem) {
            if (confirm('Remove item from cart?')) {
                removeCartItem(rem.dataset.id, rem.dataset.variant, rem.dataset.advancedvariant);
            }
        }
    });

    function updateCartQty(id, variant, advancedVariant, qty) {
        $.ajax({
            url: 'shop/ajax/update-cart.php',
            type: 'POST',
            data: { product_id: id, variant: variant, advanced_variant: advancedVariant, quantity: qty },
            success: function (res) {
                try {
                    const r = JSON.parse(res);
                    if (r.success) location.reload();
                    else showCustomToast(r.message || 'Failed to update', 'error');
                } catch (err) { location.reload(); }
            }
        });
    }

    function removeCartItem(id, variant, advancedVariant) {
        $.ajax({
            url: 'shop/ajax/remove-from-cart.php',
            type: 'POST',
            data: { product_id: id, variant: variant, advanced_variant: advancedVariant },
            success: function (res) {
                try {
                    const r = JSON.parse(res);
                    if (r.success) location.reload();
                    else showCustomToast(r.message || 'Failed to remove', 'error');
                } catch (err) { location.reload(); }
            }
        });
    }

    function proceedToCheckout(ev) {
        const btn = ev ? ev.currentTarget : event.target;
        const original = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Processing...';
        const userId = typeof currentUserId !== 'undefined' ? currentUserId : null;
        if (!userId) {
            alert('Please login to continue checkout');
            window.location.href = '<?= $storeUrl ?>login';
            return;
        }
        window.location.href = '<?= $storeUrl ?>checkout';
    }
</script>