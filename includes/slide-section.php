<?php
// 1. Fetch advanced categories from DB
$advance_categories = [];
for ($i = 1; $i <= 6; $i++) {
    $name  = getData("advance_category_name_$i", "seller_banners", "seller_id = '$sellerId' AND store_id = '$storeId'");
    $link  = getData("advance_category_link_$i", "seller_banners", "seller_id = '$sellerId' AND store_id = '$storeId'");
    $image = getData("advance_category_image_$i", "seller_banners", "seller_id = '$sellerId' AND store_id = '$storeId'");

    if (!empty($image)) {
        $advance_categories[] = [
            'name'  => $name, // Keep as is, don't fallback to "Category"
            'link'  => $link ?: '#',
            'image' => UPLOADS_URL . $image
        ];
    }
}

$main_heading = getData("advance_category_main_heading", "seller_banners", "seller_id = '$sellerId' AND store_id = '$storeId'");

// 2. Only render section if categories exist
if (!empty($advance_categories)) :
?>

<!-- ==========================================
     DYNAMIC ADVANCED CATEGORY CAROUSEL
    ========================================== -->
<section class="customer-love">

    <!-- HEADING -->
    <div class="customer-love-heading">
        <h2><?= !empty($main_heading) ? htmlspecialchars($main_heading) : 'Wear it ✦ Review it ✦ Love it' ?></h2>
        <p>Style isn't just about what you see in the mirror - it's about how you feel the moment you put it on.</p>
    </div>

    <!-- PHOTO CAROUSEL -->
    <div class="customer-gallery" id="customerGallery">
        <div class="customer-track" id="customerTrack">
            <?php 
            // Loop through the advanced categories
            foreach ($advance_categories as $index => $category): 
                $photoClass = 'photo-' . (($index % 8) + 1); // Repeats classes 1 to 8 for rotations
            ?>
            <div class="customer-photo <?= $photoClass ?>">
                <a href="<?= htmlspecialchars($category['link']) ?>" target="_blank" style="display: block; width: 100%; height: 100%;">
                    <img src="<?= htmlspecialchars($category['image']) ?>" alt="<?= !empty($category['name']) ? htmlspecialchars($category['name']) : 'Category Image' ?>">
                    
                    <!-- ONLY SHOW CAPTION IF NAME IS NOT EMPTY -->
                    <?php if (!empty(trim($category['name']))): ?>
                    <div class="customer-photo-caption">
                        <?= htmlspecialchars($category['name']) ?>
                    </div>
                    <?php endif; ?>
                    
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="customer-gallery-line"></div>
</section>

<style>
    /* ==========================================
       MAIN SECTION
       ========================================== */
    .customer-love {
        --gallery-card-width: 170px;
        position: relative;
        width: 100%;
        overflow: hidden;
        padding: 22px 0 12px;
        background-color: #fafafa;
        color: #111;
        font-family: Arial, Helvetica, sans-serif;
        isolation: isolate;
    }

    /* GEOMETRIC LINE BACKGROUND */
    .customer-love::before {
        content: "";
        position: absolute;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        opacity: 0.55;
        background-image:
            repeating-linear-gradient(35deg, transparent 0, transparent 44px, rgba(120, 120, 120, 0.20) 45px, transparent 46px, transparent 80px),
            repeating-linear-gradient(125deg, transparent 0, transparent 52px, rgba(120, 120, 120, 0.16) 53px, transparent 54px, transparent 95px);
    }

    /* HEADING */
    .customer-love-heading {
        position: relative;
        z-index: 1;
        padding: 0 15px;
        margin: 0 auto 18px;
        text-align: center;
    }
    .customer-love-heading h2 {
        margin: 0 0 5px;
        font-size: clamp(17px, 2.2vw, 24px);
        font-weight: 700;
        line-height: 1.25;
        letter-spacing: 0.4px;
    }
    .customer-love-heading h2 span {
        display: inline-block;
        margin: 0 2px;
        font-size: 0.85em;
    }
    .customer-love-heading p {
        margin: 0;
        color: #666;
        font-size: 11px;
        line-height: 1.5;
        letter-spacing: 0.2px;
    }

    /* GALLERY VIEWPORT */
    .customer-gallery {
        position: relative;
        width: 100%;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 6px 0 16px;
        cursor: grab;
        touch-action: pan-y;
        scrollbar-width: none; /* Hide scrollbar */
        -ms-overflow-style: none;
        scroll-behavior: auto; /* JS handles the smooth reverse */
    }
    .customer-gallery::-webkit-scrollbar {
        display: none;
    }
    .customer-gallery:active {
        cursor: grabbing;
    }

    /* HORIZONTAL TRACK */
    .customer-track {
        display: flex;
        align-items: center;
        gap: 16px;
        width: max-content;
        padding: 0 16px;
    }

    /* PHOTO CARDS */
    .customer-photo {
        position: relative;
        flex: 0 0 var(--gallery-card-width);
        width: var(--gallery-card-width);
        height: 226px;
        padding: 7px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.94);
        border: 1px solid rgba(255, 255, 255, 0.9);
        border-radius: 11px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.10), 0 2px 4px rgba(0, 0, 0, 0.04);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    /* ROTATIONS */
    .photo-1 { transform: rotate(-4deg); }
    .photo-2 { transform: rotate(3deg); }
    .photo-3 { transform: rotate(-3deg); }
    .photo-4 { transform: rotate(4deg); }
    .photo-5 { transform: rotate(-2deg); }
    .photo-6 { transform: rotate(3deg); }
    .photo-7 { transform: rotate(-4deg); }
    .photo-8 { transform: rotate(2deg); }

    .customer-photo:hover {
        transform: rotate(0deg) translateY(-6px) scale(1.025);
        box-shadow: 0 14px 28px rgba(0, 0, 0, 0.15), 0 3px 8px rgba(0, 0, 0, 0.05);
        z-index: 2;
    }

    .customer-photo a {
        display: flex;
        flex-direction: column;
        width: 100%;
        height: 100%;
        text-decoration: none;
    }

    .customer-photo img {
        display: block;
        width: 100%;
        flex: 1; /* Image takes remaining height */
        object-fit: cover;
        object-position: center;
        border-radius: 6px;
        user-select: none;
        -webkit-user-drag: none;
    }

    /* CATEGORY NAME CAPTION */
    .customer-photo-caption {
        text-align: center;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #333;
        padding-top: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .customer-gallery-line {
        height: 1.5px;
        width: 100%;
        background: rgba(100, 100, 100, 0.30);
    }

    /* TABLET */
    @media (max-width: 900px) {
        .customer-love { --gallery-card-width: 150px; padding-top: 20px; }
        .customer-love-heading { margin-bottom: 15px; }
        .customer-photo { height: 210px; }
        .customer-track { gap: 13px; }
        .customer-photo-caption { font-size: 10px; }
    }

    /* MOBILE */
    @media (max-width: 600px) {
        .customer-love { --gallery-card-width: 125px; padding: 18px 0 8px; }
        .customer-love-heading { padding: 0 16px; margin-bottom: 12px; }
        .customer-love-heading h2 { font-size: 17px; letter-spacing: 0; }
        .customer-love-heading h2 span { margin: 0 1px; }
        .customer-love-heading p { max-width: 320px; margin: 0 auto; font-size: 10px; }
        .customer-gallery { padding: 5px 0 14px; }
        .customer-track { gap: 11px; padding: 0 12px; }
        .customer-photo { height: 180px; padding: 6px; border-radius: 9px; }
        .customer-photo img { border-radius: 5px; }
        .customer-photo-caption { font-size: 9px; padding-top: 6px; }
    }

    /* REDUCED MOTION */
    @media (prefers-reduced-motion: reduce) {
        .customer-photo { transition: none; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const gallery = document.getElementById("customerGallery");
        const track = document.getElementById("customerTrack");
        if (!gallery || !track) return;

        // 1. Auto Scroll Logic (Ping-Pong: Left to Right, then Right to Left)
        let autoScrollPaused = false;
        let resumeTimer = null;
        let direction = 1; // 1 = Right, -1 = Left
        const scrollSpeed = 0.8; // pixels per frame

        function autoScroll() {
            if (!autoScrollPaused) {
                const maxScroll = gallery.scrollWidth - gallery.clientWidth;
                
                // Scroll based on direction
                gallery.scrollLeft += scrollSpeed * direction;

                // Check boundaries
                if (direction === 1 && gallery.scrollLeft >= maxScroll - 1) {
                    direction = -1; // Reverse to Left
                } else if (direction === -1 && gallery.scrollLeft <= 1) {
                    direction = 1; // Reverse to Right
                }
            }
            requestAnimationFrame(autoScroll);
        }

        // Start the auto scroll
        requestAnimationFrame(autoScroll);

        // 2. Pause on Hover/Touch/Wheel
        function pauseAuto(duration = 2000) {
            autoScrollPaused = true;
            clearTimeout(resumeTimer);
            resumeTimer = setTimeout(() => { autoScrollPaused = false; }, duration);
        }

        gallery.addEventListener('mouseenter', () => pauseAuto(3000));
        gallery.addEventListener('mouseleave', () => pauseAuto(500));
        gallery.addEventListener('touchstart', () => pauseAuto(3000), { passive: true });
        gallery.addEventListener('wheel', () => pauseAuto(2000), { passive: true });

        // 3. Manual Drag
        let startX = 0;
        let startScroll = 0;
        let isDragging = false;

        gallery.addEventListener("pointerdown", function(event) {
            isDragging = true;
            startX = event.clientX;
            startScroll = gallery.scrollLeft;
            autoScrollPaused = true;
            clearTimeout(resumeTimer);
        });

        gallery.addEventListener("pointermove", function(event) {
            if (!isDragging) return;
            const distance = event.clientX - startX;
            gallery.scrollLeft = startScroll - distance;
        });

        window.addEventListener("pointerup", function() {
            if (isDragging) {
                isDragging = false;
                pauseAuto(1500);
            }
        });

        window.addEventListener("pointercancel", function() {
            if (isDragging) {
                isDragging = false;
                pauseAuto(1500);
            }
        });
    });
</script>

<?php endif; ?>