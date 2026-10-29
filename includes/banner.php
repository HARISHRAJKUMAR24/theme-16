<!-- HERO SLIDER (Theme 16/17 Design + Theme 9 Dynamic Banners) -->
<?php
// ===== Fetch banner images and links from DB (same as Theme 9) =====
$banners = [];
for ($i = 1; $i <= 5; $i++) {
    $column      = "homepage_banner" . ($i > 1 ? "_$i" : "");
    $link_column = "homepage_banner_link" . ($i > 1 ? "_$i" : "");

    $image = getData($column, "seller_banners", "(seller_id = '$sellerId' AND store_id = '$storeId')");
    $link  = getData($link_column, "seller_banners", "(seller_id = '$sellerId' AND store_id = '$storeId')");

    if (!empty($image)) {
        $banners[] = ['image' => $image, 'link' => $link];
    }
}

// Theme colors (kept, in case you want them on the progress bar later)
$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#a13d91';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';
$bannerCount   = count($banners);
?>

<?php if ($bannerCount > 0): ?>
<!-- HERO SLIDER -->
<section class="relative w-full overflow-hidden bg-transparent">
    <div id="sliderTrack" class="flex w-full transition-transform duration-700 ease-in-out">
        <?php foreach ($banners as $index => $banner):
            $imgUrl = UPLOADS_URL . $banner['image'];
            $linkUrl = !empty($banner['link']) ? $banner['link'] : '#';
            $isExternal = !empty($banner['link']) && preg_match('/^https?:\/\//', $banner['link']);
        ?>
            <a href="<?= htmlspecialchars($linkUrl) ?>"
               <?= $isExternal ? 'target="_blank" rel="noopener"' : '' ?>
               class="relative block aspect-[2/1] w-full shrink-0 overflow-hidden bg-transparent sm:aspect-[16/5] lg:aspect-[16/5]">
                <img src="<?= htmlspecialchars($imgUrl) ?>"
                     alt="Banner <?= $index + 1 ?>"
                     <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                     class="absolute inset-0 h-full w-full object-contain object-center">
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Progress Bar -->
    <div class="absolute bottom-0 left-0 z-10 h-[2px] w-full bg-neutral-200 sm:h-[3px]">
        <div id="progressFill" class="h-full w-0"
             style="background-color: <?= htmlspecialchars($primary_color) ?>;"></div>
    </div>
</section>

<script>
    /* HERO SLIDER (Theme 16/17 logic, untouched) */
    const track        = document.getElementById('sliderTrack');
    const slides       = document.querySelectorAll('#sliderTrack > a');
    const progressFill = document.getElementById('progressFill');
    const duration     = 5500;
    let currentIndex   = 0;
    let sliderTimer;

    function showSlide(index) {
        if (!slides.length) return;
        currentIndex = (index + slides.length) % slides.length;
        track.style.transform = `translateX(-${currentIndex * 100}%)`;
        progressFill.style.transition = 'none';
        progressFill.style.width = '0%';
        void progressFill.offsetWidth;
        progressFill.style.transition = `width ${duration}ms linear`;
        progressFill.style.width = '100%';
    }

    function startSlider() {
        clearInterval(sliderTimer);
        if (!document.hidden && slides.length > 1) {
            showSlide(currentIndex);
            sliderTimer = setInterval(() => showSlide(currentIndex + 1), duration);
        } else if (slides.length === 1) {
            // Single banner: no auto-advance, just fill the bar
            progressFill.style.transition = 'none';
            progressFill.style.width = '0%';
        }
    }

    document.addEventListener('visibilitychange', () => {
        if (document.hidden) clearInterval(sliderTimer);
        else startSlider();
    });

    let startX = 0;
    const hero = document.querySelector('#sliderTrack').closest('section');
    hero.addEventListener('touchstart', event => {
        startX = event.changedTouches[0].screenX;
    }, { passive: true });

    hero.addEventListener('touchend', event => {
        const endX = event.changedTouches[0].screenX;
        const difference = endX - startX;
        if (Math.abs(difference) > 50) {
            showSlide(difference < 0 ? currentIndex + 1 : currentIndex - 1);
            clearInterval(sliderTimer);
            sliderTimer = setInterval(() => showSlide(currentIndex + 1), duration);
        }
    }, { passive: true });

    startSlider();
</script>
<?php endif; ?>
<!-- HERO SLIDER END -->