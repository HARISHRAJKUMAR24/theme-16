<?php include_once __DIR__ . "/includes/files_includes.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        /* Theme 17: Large, consistent product cards */
        .theme17-product-grid>.group {
            min-width: 0;
            width: 100%;
            align-self: stretch;
        }

        /* Prevent product content from overflowing its card */
        .theme17-product-grid>.group img {
            max-width: 100%;
        }

        /* Keep product cards aligned in the grid */
        .theme17-product-grid>.group>* {
            min-width: 0;
        }

        @media (max-width: 639px) {
            .theme17-product-grid {
                column-gap: 8px;
                row-gap: 24px;
            }
        }
    </style>
</head>

<body class="m-0 overflow-x-hidden bg-white font-['DM_Sans'] text-neutral-900">


    <?php include_once __DIR__ . '/includes/nav-bar.php' ?>

    <?php include_once __DIR__ . '/includes/banner.php' ?>


    <!-- HANGER COLLECTION SLIDER (Theme 17 Reference UI) -->
    <section class="collection" id="hangerCollection">
        <header class="heading">
            <small>THE NEW COLLECTION</small>
            <h1>Everyday Essentials.</h1>
            <p>Discover your next favourite.</p>
        </header>

        <div class="rail-scene">
            <div class="rail-bracket left"></div>
            <div class="steel-rod"></div>
            <div class="rail-bracket right"></div>
        </div>

        <div class="products-scroll" id="productsScroll">
            <div class="products">
                <?php
                $productsStmt = getProducts();
                $products = $productsStmt ? $productsStmt->fetchAll(PDO::FETCH_ASSOC) : [];
                $counter = 0;
                foreach ($products as $product) {
                    if ($counter >= 10) break;
                    echo getHangerProductHtml($product["id"], $counter);
                    $counter++;
                }
                ?>
            </div>
        </div>

        <div class="scroll-controls">
            <button type="button" id="scrollLeft" aria-label="Scroll left">&#8592;</button>
            <button type="button" id="scrollRight" aria-label="Scroll right">&#8594;</button>
        </div>
    </section>

    <?php include_once __DIR__ . '/includes/category.php' ?>



    <!-- ==================== THEME 17: BIG PRODUCT CARDS ==================== -->
    <section class="theme17-new-collections bg-white py-10 sm:py-14 lg:py-16">
        <div class="mx-auto max-w-[1600px] px-2 sm:px-5 lg:px-8">

            <!-- Section Heading -->
            <div class="mb-7 sm:mb-10 flex items-end justify-between gap-4 px-1">
                <div>
                    <span class="mb-2 block text-[10px] sm:text-xs uppercase tracking-[0.25em] text-gray-500">
                        Curated for You
                    </span>

                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold tracking-tight text-gray-900">
                        New Collections
                    </h2>

                    <p class="mt-2 text-sm sm:text-base text-gray-500">
                        Discover the latest styles and everyday essentials.
                    </p>
                </div>

                <a href="<?= htmlspecialchars($storeUrl) ?>"
                    class="shrink-0 inline-flex items-center gap-2 border-b border-gray-900 pb-1 text-xs sm:text-sm font-medium text-gray-900 transition hover:opacity-60">
                    View All
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <!-- Large Product Card Grid -->
            <div class="theme17-product-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-2 gap-y-7 sm:gap-x-5 sm:gap-y-10 lg:gap-x-6">

                <?php
                $productsStmt = getProducts();
                $products = $productsStmt
                    ? $productsStmt->fetchAll(PDO::FETCH_ASSOC)
                    : [];

                $counter = 0;

                foreach ($products as $product) {
                    if ($counter >= 10) {
                        break;
                    }

                    echo getProductHtml($product["id"]);
                    $counter++;
                }
                ?>

            </div>

            <?php if (empty($products)): ?>
                <div class="py-16 text-center">
                    <p class="text-sm text-gray-500">
                        No products available right now.
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </section>


    <!-- ==================== END THEME 17 COLLECTIONS ==================== -->

    <?php include_once __DIR__ . '/includes/offer-banner-section.php' ?>

    <?php include_once __DIR__ . '/includes/slide-section.php' ?>

    <!-- ==========================================
     ABOUT US — Theme 9 layout, SHINISHA styling
     - Main about image (from DB) on left
     - Logo floating in place of secondary image
     - About text on right
    ========================================== -->
    <?php
    $aboutPrimaryColor = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#a13d91';
    $aboutContent      = $sellerId ? getData("about_content", "homepage_settings", "(seller_id = '$sellerId' AND store_id = '$storeId')") : '';
    $aboutImage        = $sellerId ? getData("about_image", "homepage_settings", "(seller_id = '$sellerId' AND store_id = '$storeId')") : '';
    $storeLogo         = getSettings("logo");
    $storeLogoUrl      = $storeLogo ? UPLOADS_URL . $storeLogo : '';
    ?>

    <?php if (!empty($aboutContent) || !empty($aboutImage)) : ?>
        <section id="about-section"
            class="about-shinisha relative overflow-hidden bg-[#faf8f5] py-8 sm:py-10 lg:py-12">

            <!-- DECORATIVE BACKGROUND -->
            <div class="pointer-events-none absolute -left-32 top-20 h-72 w-72 rounded-full blur-3xl"
                style="background: <?= htmlspecialchars($aboutPrimaryColor) ?>1a;"></div>
            <div class="pointer-events-none absolute -right-32 bottom-0 h-80 w-80 rounded-full blur-3xl"
                style="background: color-mix(in srgb, <?= htmlspecialchars($aboutPrimaryColor) ?> 20%, transparent);"></div>

            <div class="relative mx-auto grid max-w-6xl items-center gap-8 px-5 sm:px-8 lg:grid-cols-2 lg:gap-12 lg:px-10">

                <!-- ========================================== -->
                <!-- LEFT: IMAGE COLLAGE (Main image + Logo)    -->
                <!-- ========================================== -->
                <div class="relative mx-auto w-full max-w-md">

                    <!-- MAIN ABOUT IMAGE (from DB) -->
                    <div class="relative z-10 w-[75%] overflow-hidden rounded-t-[48%] rounded-b-2xl border-[6px] border-white shadow-2xl sm:border-[8px]">
                        <?php if (!empty($aboutImage)): ?>
                            <img src="<?= htmlspecialchars(UPLOADS_URL . $aboutImage) ?>"
                                alt="<?= htmlspecialchars($storeName) ?>"
                                class="about-main-image aspect-[3/4] w-full object-cover"
                                loading="lazy">
                        <?php else: ?>
                            <div class="aspect-[3/4] w-full flex items-center justify-center"
                                style="background: color-mix(in srgb, <?= htmlspecialchars($aboutPrimaryColor) ?> 15%, white);">
                                <span class="font-serif text-6xl" style="color: <?= htmlspecialchars($aboutPrimaryColor) ?>;">
                                    <?= strtoupper(substr($storeName, 0, 1)) ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 text-white sm:bottom-6 sm:left-6">
                            <p class="text-[9px] font-semibold uppercase tracking-[0.3em] sm:text-[10px]">Our Story</p>
                            <p class="mt-0.5 font-serif text-xl italic sm:text-2xl">Made with care.</p>
                        </div>
                    </div>

                    <!-- ✅ LOGO — floating where the second image was -->
                    <div class="absolute -right-1 top-[12%] z-30 flex w-[42%] items-center justify-center overflow-hidden rounded-full border-[4px] border-white bg-white shadow-xl transition duration-500 hover:rotate-0 rotate-6 sm:-right-2 sm:border-[6px] aspect-square">
                        <?php if (!empty($storeLogoUrl)): ?>
                            <img src="<?= htmlspecialchars($storeLogoUrl) ?>"
                                alt="<?= htmlspecialchars($storeName) ?>"
                                class="h-[70%] w-[70%] object-contain">
                        <?php else: ?>
                            <div class="flex h-full w-full items-center justify-center"
                                style="background: color-mix(in srgb, <?= htmlspecialchars($aboutPrimaryColor) ?> 12%, white);">
                                <span class="font-serif text-5xl font-bold sm:text-6xl"
                                    style="color: <?= htmlspecialchars($aboutPrimaryColor) ?>;">
                                    <?= strtoupper(substr($storeName, 0, 1)) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- GLASS MESSAGE -->
                    <div class="absolute -bottom-4 right-0 z-30 w-[62%] rounded-xl border border-white/80 bg-white/75 p-3 shadow-xl backdrop-blur-xl sm:-bottom-5 sm:p-4">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-base text-white"
                                style="background: <?= htmlspecialchars($aboutPrimaryColor) ?>;">✦</div>
                            <div>
                                <p class="font-serif text-base sm:text-lg" style="color: <?= htmlspecialchars($aboutPrimaryColor) ?>;">
                                    <?= htmlspecialchars($storeName) ?>
                                </p>
                                <p class="text-[10px] text-neutral-600 sm:text-[11px]">Your Story. Your Confidence.</p>
                            </div>
                        </div>
                    </div>

                    <!-- DECORATIVE OUTLINE -->
                    <div class="absolute -bottom-7 left-[10%] h-24 w-24 rounded-full border sm:h-32 sm:w-32"
                        style="border-color: color-mix(in srgb, <?= htmlspecialchars($aboutPrimaryColor) ?> 30%, transparent);"></div>
                </div>

                <!-- ========================================== -->
                <!-- RIGHT: ABOUT CONTENT (from DB)             -->
                <!-- ========================================== -->
                <div class="pt-3 lg:pt-0">

                    <!-- EYEBROW -->
                    <div class="mb-3 flex items-center gap-2">
                        <span class="h-px w-8" style="background: <?= htmlspecialchars($aboutPrimaryColor) ?>;"></span>
                        <span class="text-[9px] font-bold uppercase tracking-[0.25em] sm:text-[10px]"
                            style="color: <?= htmlspecialchars($aboutPrimaryColor) ?>;">
                            About Us
                        </span>
                    </div>

                    <!-- HEADING -->
                    <h2 class="max-w-lg font-serif text-3xl font-medium leading-[1.15] tracking-tight text-neutral-900 sm:text-4xl lg:text-5xl">
                        About <span class="italic" style="color: <?= htmlspecialchars($aboutPrimaryColor) ?>;"><?= htmlspecialchars($storeName) ?></span>
                    </h2>

                    <!-- ABOUT CONTENT — rich HTML from DB -->
                    <?php if (!empty($aboutContent)): ?>
                        <div class="about-rich-content mt-4 max-w-lg text-sm leading-6 text-neutral-600 sm:text-[15px] sm:leading-7">
                            <?= html_entity_decode($aboutContent, ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>
                        </div>
                    <?php else: ?>
                        <p class="mt-4 max-w-lg text-sm leading-6 text-neutral-600 sm:text-[15px] sm:leading-7">
                            Welcome to <?= htmlspecialchars($storeName) ?>. Discover thoughtful styles designed for you.
                        </p>
                    <?php endif; ?>

                    <!-- CTA -->
                    <div class="mt-6 flex flex-wrap items-center gap-3">
                        <a href="<?= $storeUrl ?>shop-all"
                            class="group inline-flex items-center gap-3 rounded-full px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-white transition hover:bg-neutral-900 sm:px-6 sm:text-[11px]"
                            style="background: <?= htmlspecialchars($aboutPrimaryColor) ?>;">
                            Discover Our Collection
                            <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
                        </a>
                        <span class="text-[11px] italic text-neutral-500">Wear what makes you feel beautiful.</span>
                    </div>

                    <!-- BOTTOM DECORATIVE TEXT -->
                    <div class="mt-6 border-t border-neutral-200 pt-3">
                        <p class="font-serif text-base italic text-neutral-400 sm:text-lg">"Your style is your signature."</p>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <style>
        /* ========================================== */
        /* ABOUT SECTION ANIMATIONS                    */
        /* ========================================== */
        .about-shinisha .about-main-image {
            animation: shinishaImageReveal 1s ease both;
        }

        @keyframes shinishaImageReveal {
            from {
                opacity: 0;
                transform: scale(1.04);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            .about-shinisha .absolute.z-30 {
                animation: shinishaFloat 4s ease-in-out infinite;
            }

            @keyframes shinishaFloat {

                0%,
                100% {
                    transform: translateY(0);
                }

                50% {
                    transform: translateY(-5px);
                }
            }
        }

        @media (max-width: 640px) {
            .about-shinisha {
                overflow: hidden;
            }
        }

        /* Rich about content */
        .about-rich-content p {
            margin: 0 0 10px;
        }

        .about-rich-content p:last-child {
            margin-bottom: 0;
        }

        .about-rich-content strong,
        .about-rich-content b {
            font-weight: 700;
            color: #171717;
        }

        .about-rich-content ul,
        .about-rich-content ol {
            padding-left: 18px;
            margin: 0 0 10px;
        }

        .about-rich-content li {
            margin-bottom: 4px;
        }

        .about-rich-content a {
            color: <?= htmlspecialchars($aboutPrimaryColor) ?>;
            text-decoration: underline;
        }

        @media (prefers-reduced-motion: reduce) {

            .about-shinisha *,
            .about-shinisha *::before,
            .about-shinisha *::after {
                animation: none !important;
                transition: none !important;
            }
        }
    </style>


    <?php include_once __DIR__ . '/includes/floting-cart.php' ?>

    <?php include_once __DIR__ . '/includes/footer.php' ?>


</body>

</html>