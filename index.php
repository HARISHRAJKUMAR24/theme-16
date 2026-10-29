<?php include_once __DIR__ . "/includes/files_includes.php"; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
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



    <!-- New Collections Section theme 17 -->
    <section class="py-16 bg-gray-50 md:px-4">
        <div class="container mx-auto md:max-w-none max-w-6xl px-4 md:px-20">
            <div class="text-center mb-8">
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-800 mb-1">New Collections</h2>
                <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                    Explore what's trending with fresh styles and endless inspiration.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 gap-y-10 items-stretch">
                <?php
                $productsStmt = getProducts();
                $products = $productsStmt ? $productsStmt->fetchAll(PDO::FETCH_ASSOC) : [];
                $counter = 0;
                foreach ($products as $product) {
                    if ($counter >= 10) break;
                    echo getProductHtml($product["id"]);
                    $counter++;
                }
                ?>
            </div>
        </div>
    </section>

    <?php include_once __DIR__ . '/includes/offer-banner-section.php' ?>

    <?php include_once __DIR__ . '/includes/slide-section.php' ?>

    <?php include_once __DIR__ . '/includes/footer.php' ?>


</body>

</html>