<?php
// ================================================
// SIMPLE BANNER SLIDER — image only, backend-driven
// - Reads up to 2 images from seller_banners
//   (offer_image_1, offer_image_2) — same source Theme 13 uses
// - No left/right arrows
// - No dots
// - Autoplay + swipe + responsive unchanged
// ================================================

$sbsSlides = [];
for ($i = 1; $i <= 2; $i++) {
    $col = "offer_image_$i";
    $img = getData($col, "seller_banners", "(seller_id = '$sellerId' AND store_id = '$storeId')");
    if (!empty($img)) {
        $sbsSlides[] = $img;
    }
}
?>

<?php if (!empty($sbsSlides)): ?>
<!-- ================================================ -->
<!-- SIMPLE BANNER SLIDER — IMAGE ONLY, NO BG         -->
<!-- ================================================ -->
<section id="simpleBannerSection" class="sbs-section">
    <div class="sbs-frame">
        <div class="sbs-track" id="sbsTrack">
            <?php foreach ($sbsSlides as $i => $img): ?>
                <div class="sbs-slide">
                    <img src="<?= UPLOADS_URL . htmlspecialchars($img) ?>"
                         alt="Banner <?= $i + 1 ?>" draggable="false">
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Arrows removed (prev/next buttons) -->
        <!-- Dots removed -->
    </div>
</section>

<style>
    /* ============ SECTION ============ */
    .sbs-section {
        padding: 12px 16px 6px;
        background: #ffffff;
    }

    /* ============ FRAME ============ */
    .sbs-frame {
        position: relative;
        max-width: 1280px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 10px;
    }

    /* ============ TRACK ============ */
    .sbs-track {
        display: flex;
        width: 100%;
        transition: transform .7s cubic-bezier(.22, .61, .36, 1);
        will-change: transform;
    }

    /* ============ SLIDE ============ */
    .sbs-slide {
        flex: 0 0 100%;
        width: 100%;
        aspect-ratio: 16 / 4.5;
        overflow: hidden;
    }

    .sbs-slide img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: center;
        user-select: none;
        -webkit-user-drag: none;
    }

    /* ============ TABLET ============ */
    @media (max-width: 900px) {
        .sbs-section {
            padding: 8px 10px 4px;
        }

        .sbs-slide {
            aspect-ratio: 16 / 5;
        }
    }

    /* ============ MOBILE ============ */
    @media (max-width: 520px) {
        .sbs-section {
            padding: 5px 7px 2px;
        }

        .sbs-slide {
            aspect-ratio: 16 / 5;
        }
    }

    /* ============ REDUCED MOTION ============ */
    @media (prefers-reduced-motion: reduce) {
        .sbs-track {
            transition: none;
        }
    }
</style>

<script>
    (function() {
        const track = document.getElementById('sbsTrack');
        if (!track) return;

        const slides = Array.from(track.children);
        if (slides.length === 0) return;

        const AUTOPLAY_MS = 5500;
        let index = 0;
        let timer = null;

        /* CORE */
        function goTo(i) {
            index = (i + slides.length) % slides.length;
            track.style.transform = 'translateX(-' + (index * 100) + '%)';
        }

        function next() {
            goTo(index + 1);
        }

        function prev() {
            goTo(index - 1);
        }

        /* AUTOPLAY */
        function startAutoplay() {
            stopAutoplay();
            if (slides.length < 2) return; // no need to autoplay a single slide
            timer = setInterval(() => {
                if (!document.hidden) next();
            }, AUTOPLAY_MS);
        }

        function stopAutoplay() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function restartAutoplay() {
            stopAutoplay();
            startAutoplay();
        }

        /* SWIPE */
        let startX = 0,
            startY = 0,
            swiping = false;

        track.addEventListener('touchstart', (e) => {
            startX = e.changedTouches[0].screenX;
            startY = e.changedTouches[0].screenY;
            swiping = true;
        }, {
            passive: true
        });

        track.addEventListener('touchend', (e) => {
            if (!swiping) return;
            swiping = false;
            const dx = e.changedTouches[0].screenX - startX;
            const dy = e.changedTouches[0].screenY - startY;
            if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) {
                dx < 0 ? next() : prev();
                restartAutoplay();
            }
        }, {
            passive: true
        });

        /* PAUSE WHEN TAB HIDDEN */
        document.addEventListener('visibilitychange', () => {
            document.hidden ? stopAutoplay() : startAutoplay();
        });

        /* INIT */
        goTo(0);
        startAutoplay();
    })();
</script>
<?php endif; ?>