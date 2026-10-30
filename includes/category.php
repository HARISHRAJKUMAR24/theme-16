<?php
// ==========================================
// SHINISHA — MINIMAL CATEGORY SECTION
// Backend: getCategories() — UI/UX identical to the static mockup
// ==========================================

$stmt = getCategories();
if ($stmt) {
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $categories = array_reverse($categories);
    $categories = array_slice($categories, 0, 5); // 5 cards like the design
} else {
    $categories = [];
}
?>
<?php if (!empty($categories)): ?>
    <!-- ==========================================
     SHINISHA — MINIMAL CATEGORY SECTION
     ========================================== -->
    <section class="category-showcase">
        <div class="category-container">

            <!-- CENTERED HEADING -->
            <div class="category-heading">
                <span class="category-eyebrow">THE STYLE EDIT</span>
                <h2>Find Your <span>Style</span></h2>
                <p>Explore collections made for every mood and moment.</p>
            </div>

            <!-- FIVE CATEGORY CARDS -->
            <div class="category-grid">

                <?php foreach ($categories as $i => $cat):
                    $catName  = $cat['name'] ?? 'Collection';
                    $catSlug  = $cat['slug'] ?? '';
                    $catIcon  = !empty($cat['icon']) ? UPLOADS_URL . $cat['icon'] : '';
                    $catLetter = strtoupper(mb_substr($catName, 0, 1));
                    $num      = str_pad($i + 1, 2, '0', STR_PAD_LEFT);
                    // First card is the tall feature card
                    $isTall   = ($i === 0);
                ?>

                    <!-- <?= $num ?> <?= htmlspecialchars($catName) ?> -->
                    <a href="<?= $storeUrl . 'category/' . htmlspecialchars($catSlug) ?>"
                        class="category-card<?= $isTall ? ' category-tall' : '' ?>">

                        <?php if ($catIcon): ?>
                            <img src="<?= htmlspecialchars($catIcon) ?>"
                                alt="<?= htmlspecialchars($catName) ?> collection"
                                loading="lazy"
                                onerror="this.style.display='none';this.parentNode.style.background='linear-gradient(135deg,#f3edf1,#e8dde4)';">
                        <?php else: ?>
                            <img src=""
                                alt="<?= htmlspecialchars($catName) ?> collection"
                                loading="lazy"
                                style="display:none;"
                                onerror="this.parentNode.style.background='linear-gradient(135deg,#f3edf1,#e8dde4)';">
                        <?php endif; ?>

                        <span class="category-number"><?= $num ?></span>

                        <div class="category-caption">
                            <h3><?= htmlspecialchars($catName) ?></h3>
                            <span class="category-explore">Explore Collection ↗</span>
                        </div>
                    </a>

                <?php endforeach; ?>

            </div>

            <!-- BOTTOM LINK -->
            <div class="category-bottom">
                <a href="<?= $storeUrl ?>collections">
                    DISCOVER ALL COLLECTIONS
                    <span>↗</span>
                </a>
            </div>

        </div>
    </section>

    <style>
        /* ==========================================
       SECTION — tighter padding
       ========================================== */
        .category-showcase {
            width: 100%;
            overflow: hidden;
            padding: 36px 16px 30px;
            background: #ffffff;
            color: #242124;
            font-family: Arial, Helvetica, sans-serif;
        }

        .category-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* ==========================================
       HEADING — compact
       ========================================== */
        .category-heading {
            margin-bottom: 18px;
            text-align: center;
        }

        .category-eyebrow {
            display: inline-block;
            margin-bottom: 6px;
            color: #9a527f;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 2.5px;
        }

        .category-heading h2 {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 500;
            letter-spacing: -0.8px;
            line-height: 1.2;
        }

        .category-heading h2 span {
            color: #a25285;
            font-weight: 400;
        }

        .category-heading p {
            margin: 6px 0 0;
            color: #777;
            font-size: 11px;
            line-height: 1.5;
        }

        /* ==========================================
       GRID — tighter gap
       ========================================== */
        .category-grid {
            display: grid;
            grid-template-columns: repeat(6, minmax(0, 1fr));
            gap: 8px;
        }

        /* ==========================================
       CARDS
       ========================================== */
        .category-card {
            position: relative;
            display: block;
            grid-column: span 2;
            min-width: 0;
            height: 200px;
            overflow: hidden;
            background: #f3edf1;
            border-radius: 8px;
            isolation: isolate;
            text-decoration: none;
        }

        .category-card.category-tall {
            grid-row: span 2;
            grid-column: span 2;
            height: 408px;
        }

        /* ==========================================
       IMAGE
       ========================================== */
        .category-card img {
            position: absolute;
            inset: 0;
            z-index: -2;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.7s cubic-bezier(.2, .7, .2, 1);
        }

        .category-card:hover img {
            transform: scale(1.07);
        }

        /* ==========================================
       GRADIENT
       ========================================== */
        .category-card::after {
            position: absolute;
            inset: 0;
            z-index: -1;
            background: linear-gradient(to top,
                    rgba(15, 12, 15, 0.78) 0%,
                    rgba(15, 12, 15, 0.20) 40%,
                    transparent 70%);
            content: "";
            transition: opacity 0.35s ease;
        }

        .category-card:hover::after {
            opacity: 0.9;
        }

        /* ==========================================
       NUMBER BADGE
       ========================================== */
        .category-number {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            width: 26px;
            height: 26px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.65);
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.16);
            color: white;
            font-size: 9px;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        /* ==========================================
       CAPTION
       ========================================== */
        .category-caption {
            position: absolute;
            right: 8px;
            bottom: 12px;
            left: 8px;
            color: #fff;
            text-align: center;
        }

        .category-caption h3 {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: clamp(14px, 1.6vw, 18px);
            font-weight: 500;
            letter-spacing: 0.2px;
            transition: transform 0.3s ease;
        }

        .category-explore {
            display: inline-block;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
            margin-top: 0;
            font-size: 9px;
            letter-spacing: 0.5px;
            transition:
                max-height 0.3s ease,
                margin-top 0.3s ease,
                opacity 0.3s ease;
        }

        .category-card:hover .category-explore {
            max-height: 20px;
            margin-top: 5px;
            opacity: 1;
        }

        .category-card:hover .category-caption h3 {
            transform: translateY(-1px);
        }

        /* ==========================================
       BOTTOM LINK — tight
       ========================================== */
        .category-bottom {
            margin-top: 18px;
            text-align: center;
        }

        .category-bottom a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #b78ca9;
            color: #4d3749;
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 1.2px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .category-bottom a:hover {
            color: #a25285;
        }

        .category-bottom a span {
            font-size: 12px;
        }

        /* ==========================================
       TABLET
       ========================================== */
        @media (max-width: 900px) {
            .category-showcase {
                padding: 30px 14px 26px;
            }

            .category-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 8px;
            }

            .category-card,
            .category-card:nth-child(n) {
                grid-column: span 1;
                grid-row: auto;
                height: 200px;
            }

            .category-card.category-tall {
                grid-column: span 2;
                grid-row: auto;
                height: 260px;
            }
        }

        /* ==========================================
       MOBILE
       ========================================== */
        @media (max-width: 600px) {
            .category-showcase {
                padding: 24px 10px 20px;
            }

            .category-heading {
                margin-bottom: 14px;
            }

            .category-heading h2 {
                font-size: 22px;
                letter-spacing: -0.5px;
            }

            .category-heading p {
                max-width: 280px;
                margin: 5px auto 0;
                font-size: 10px;
            }

            .category-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 6px;
            }

            .category-card,
            .category-card:nth-child(n) {
                grid-column: span 1;
                height: 150px;
            }

            .category-card.category-tall {
                grid-column: span 2;
                height: 200px;
            }

            .category-caption {
                bottom: 10px;
            }

            .category-caption h3 {
                font-size: 13px;
            }

            .category-explore {
                max-height: none;
                margin-top: 3px;
                opacity: 1;
                font-size: 8px;
            }

            .category-number {
                top: 6px;
                right: 6px;
                width: 22px;
                height: 22px;
                font-size: 8px;
            }

            .category-bottom {
                margin-top: 14px;
            }
        }

        /* ACCESSIBILITY */
        @media (prefers-reduced-motion: reduce) {

            .category-card *,
            .category-card::after,
            .category-bottom a {
                transition: none !important;
            }
        }
    </style>
<?php endif; ?>