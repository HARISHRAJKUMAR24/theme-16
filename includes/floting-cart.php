<?php

/**
 * FLOATING CART + WHATSAPP BUTTON
 * Live cart updates + floating animation
 */

// ========================================
// THEME COLORS
// ========================================

$fcbPrimary = getData(
    "color",
    "seller_settings",
    "(seller_id='$sellerId' AND store_id='$storeId')"
) ?? '#ff007f';

$fcbHover = getData(
    "hover_color",
    "seller_settings",
    "(seller_id='$sellerId' AND store_id='$storeId')"
) ?? '#ec4899';

// ========================================
// INITIAL CART COUNT
// ========================================

$fcbCartCount = 0;

if (isset($db) && !empty($cookie_id)) {
    try {
        $stmt = $db->prepare("
            SELECT COALESCE(SUM(quantity), 0)
            FROM customer_cart
            WHERE customer_id = ?
        ");

        $stmt->execute([$cookie_id]);
        $fcbCartCount = (int)$stmt->fetchColumn();
    } catch (Throwable $e) {
        $fcbCartCount = 0;
    }
}

// Cart URL
$fcbCartUrl = rtrim((string)($storeUrl ?? ''), '/') . '/cart';

// WhatsApp number
$fcbWhatsappNumber = preg_replace(
    '/[^0-9]/',
    '',
    (string)getSettings("whatsapp_number")
);
?>

<!-- ========================================
     FLOATING CART BUTTON
========================================= -->

<a
    href="<?= htmlspecialchars($fcbCartUrl, ENT_QUOTES, 'UTF-8') ?>"
    id="fcbCartFloat"
    class="fcb-cart-float"
    aria-label="Your cart, <?= $fcbCartCount ?> items"
    style="
        --fcb-primary: <?= htmlspecialchars($fcbPrimary, ENT_QUOTES, 'UTF-8') ?>;
        --fcb-hover: <?= htmlspecialchars($fcbHover, ENT_QUOTES, 'UTF-8') ?>;
        display: <?= $fcbCartCount > 0 ? 'flex' : 'none' ?>;
    ">
    <span class="fcb-cart-count" id="fcbCartCount">
        <?= $fcbCartCount > 99 ? '99+' : $fcbCartCount ?>
    </span>

    <span class="fcb-cart-icon">
        <svg viewBox="0 0 24 24" fill="none">
            <path
                d="M3 4H5L7.1 14.2C7.3 15.2 8.2 16 9.3 16H17.5C18.5 16 19.4 15.3 19.7 14.3L21 8H6"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                stroke-linejoin="round" />
            <circle cx="9.5" cy="20" r="1.2" fill="currentColor" />
            <circle cx="17.5" cy="20" r="1.2" fill="currentColor" />
        </svg>
    </span>

    <span class="fcb-cart-label">YOUR CART</span>

    <span class="fcb-cart-arrow">↗</span>
</a>


<!-- ========================================
     FLOATING WHATSAPP BUTTON
========================================= -->

<?php if (!empty($fcbWhatsappNumber)): ?>

    <a
        href="https://wa.me/<?= htmlspecialchars($fcbWhatsappNumber, ENT_QUOTES, 'UTF-8') ?>"
        target="_blank"
        rel="noopener noreferrer"
        class="fcb-whatsapp-float"
        aria-label="Chat with us on WhatsApp">
        <svg viewBox="0 0 32 32" fill="none">

            <circle cx="16" cy="16" r="15" fill="#25D366" />

            <path
                d="M10.2 24.1L11.1 20.8C9.9 19.5 9.2 17.8 9.2 16
            C9.2 12.2 12.3 9.1 16.1 9.1
            C19.9 9.1 23 12.2 23 16
            C23 19.8 19.9 22.9 16.1 22.9
            C14.7 22.9 13.3 22.5 12.1 21.7L10.2 24.1Z"
                stroke="#fff"
                stroke-width="1.6"
                stroke-linejoin="round" />

            <path
                d="M13.2 12.7C13 12.3 12.8 12.3 12.5 12.3
            C12.3 12.3 12 12.3 11.8 12.6
            C11.5 12.9 11 13.4 11 14.4
            C11 15.4 11.8 16.4 11.9 16.6
            C12 16.8 13.5 19.1 15.8 20
            C17.7 20.7 18.1 20.5 18.5 20.5
            C18.9 20.4 19.8 19.9 20 19.4
            C20.2 18.9 20.2 18.5 20.1 18.4
            C20 18.3 19.9 18.2 19.6 18.1
            L17.9 17.3
            C17.6 17.2 17.4 17.2 17.2 17.5
            L16.5 18.4
            C16.3 18.6 16.1 18.6 15.8 18.5
            C15.5 18.4 14.6 18 13.9 17.3
            C13.3 16.8 12.9 16 12.8 15.7
            C12.7 15.4 12.8 15.3 13 15.1
            L13.5 14.5
            C13.7 14.3 13.7 14.1 13.6 13.9
            L13.2 12.7Z"
                fill="#fff" />

        </svg>
    </a>

<?php endif; ?>


<!-- ========================================
     FLOATING BUTTON STYLES
========================================= -->

<style>
    /* ========================================
   CART BUTTON
========================================= */

    .fcb-cart-float {
        position: fixed;
        z-index: 99999;

        top: 48%;
        right: 7px;

        width: 46px;
        min-height: 145px;
        padding: 12px 5px;

        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;

        color: #fff;
        background: var(--fcb-primary, #ff007f);

        border: 1px solid rgba(255, 255, 255, .4);
        border-radius: 14px 5px 5px 14px;

        text-decoration: none;

        box-shadow: 0 5px 18px rgba(0, 0, 0, .18);

        transform: translateY(-50%);

        animation: fcbFloat 3s ease-in-out infinite;

        transition:
            background .25s ease,
            width .25s ease,
            box-shadow .25s ease;
    }

    .fcb-cart-float:hover {
        width: 51px;
        color: #fff;
        background: var(--fcb-hover, #ec4899);

        box-shadow: 0 8px 24px rgba(0, 0, 0, .22);
    }

    /* CART COUNT */

    .fcb-cart-count {
        display: flex;
        align-items: center;
        justify-content: center;

        min-width: 22px;
        height: 22px;
        padding: 0 5px;

        color: var(--fcb-primary, #ff007f);
        background: #fff;

        border-radius: 50px;

        font: 700 9px/1 Arial, sans-serif;
    }

    /* CART ICON */

    .fcb-cart-icon,
    .fcb-cart-icon svg {
        display: block;
        width: 21px;
        height: 21px;
    }

    /* VERTICAL TEXT */

    .fcb-cart-label {
        writing-mode: vertical-rl;
        transform: rotate(180deg);

        font: 700 8px/1 Arial, sans-serif;
        letter-spacing: 1.8px;

        white-space: nowrap;
    }

    /* ARROW */

    .fcb-cart-arrow {
        font: 14px/1 Arial, sans-serif;
        opacity: .9;

        transition: transform .25s ease;
    }

    .fcb-cart-float:hover .fcb-cart-arrow {
        transform: translate(3px, -3px);
    }

    /* CART FLOAT ANIMATION */

    @keyframes fcbFloat {

        0%,
        100% {
            transform: translateY(-50%);
        }

        50% {
            transform: translateY(calc(-50% + 7px));
        }
    }


    /* ========================================
   WHATSAPP BUTTON
========================================= */

    .fcb-whatsapp-float {
        position: fixed;
        z-index: 99999;

        top: calc(48% + 88px);
        right: 9px;

        width: 43px;
        height: 43px;

        display: flex;
        align-items: center;
        justify-content: center;

        background: #25D366;

        border: 3px solid #fff;
        border-radius: 50%;

        box-shadow:
            0 4px 14px rgba(0, 0, 0, .18),
            0 0 0 3px rgba(37, 211, 102, .13);

        animation: fcbWhatsappFloat 3s ease-in-out infinite;
        animation-delay: .4s;

        transition:
            transform .25s ease,
            box-shadow .25s ease;
    }

    .fcb-whatsapp-float:hover {
        transform: scale(1.12);

        box-shadow:
            0 6px 20px rgba(0, 0, 0, .2),
            0 0 0 5px rgba(37, 211, 102, .15);
    }

    .fcb-whatsapp-float svg {
        display: block;
        width: 32px;
        height: 32px;
    }

    /* WHATSAPP ANIMATION */

    @keyframes fcbWhatsappFloat {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(6px);
        }
    }


    /* ========================================
   MOBILE RESPONSIVE
========================================= */

    @media (max-width: 640px) {

        /* CART */

        .fcb-cart-float {
            top: auto;
            right: 5px;
            bottom: 24%;

            width: 34px;
            min-height: 108px;

            padding: 8px 3px;
            gap: 6px;

            border-radius: 12px 4px 4px 12px;
            transform: none;
        }

        .fcb-cart-float:hover {
            width: 37px;
        }

        @keyframes fcbFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(6px);
            }
        }

        .fcb-cart-count {
            min-width: 17px;
            height: 17px;
            font-size: 7px;
        }

        .fcb-cart-icon,
        .fcb-cart-icon svg {
            width: 16px;
            height: 16px;
        }

        .fcb-cart-label {
            font-size: 6px;
            letter-spacing: 1.2px;
        }

        .fcb-cart-arrow {
            font-size: 10px;
        }

        /* WHATSAPP */

        .fcb-whatsapp-float {
            top: auto;
            right: 5px;

            bottom: calc(24% - 48px);

            width: 36px;
            height: 36px;

            border-width: 2px;
        }

        .fcb-whatsapp-float svg {
            width: 27px;
            height: 27px;
        }
    }


    /* ========================================
   REDUCED MOTION
========================================= */

    @media (prefers-reduced-motion: reduce) {

        .fcb-cart-float,
        .fcb-whatsapp-float {
            animation: none;
        }

    }
</style>


<!-- ========================================
     LIVE CART UPDATE SCRIPT
========================================= -->

<script>
    (function() {

        'use strict';

        const button = document.getElementById('fcbCartFloat');
        const badge = document.getElementById('fcbCartCount');

        if (!button || !badge) return;

        // Update cart count and visibility
        function updateFloatingCart(count) {

            count = Math.max(
                0,
                parseInt(count, 10) || 0
            );

            badge.textContent = count > 99 ? '99+' : count;

            button.setAttribute(
                'aria-label',
                'Your cart, ' + count + ' items'
            );

            button.style.display = count > 0 ? 'flex' : 'none';
        }

        // Make function available to existing AJAX
        window.updateFloatingCart = updateFloatingCart;

        // Watch navbar cart count
        document.querySelectorAll('.cartItemsCount').forEach(function(element) {

            updateFloatingCart(element.textContent);

            const observer = new MutationObserver(function() {
                updateFloatingCart(element.textContent);
            });

            observer.observe(element, {
                childList: true,
                characterData: true,
                subtree: true
            });

        });

        // Support custom cart update events
        window.addEventListener('fcb:cart-updated', function(event) {
            updateFloatingCart(event.detail?.count);
        });

    })();
</script>