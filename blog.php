<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>

    <style>
        /* THEME 17 — Modern Editorial Blog */
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#64748b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#334155') ?>;
        }

        .t17-blog {
            background: #f8fafc;
            color: #0f172a;
        }

        .t17-blog-head {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            text-align: left;
            margin: 0 auto 30px;
            max-width: 1500px;
        }

        .t17-blog-kicker {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 7px 12px;
            border: 1px solid color-mix(in srgb, var(--primary-color) 25%, #fff);
            border-radius: 999px;
            background: color-mix(in srgb, var(--primary-color) 7%, #fff);
            color: var(--primary-color);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .18em;
            text-transform: uppercase;
            margin-bottom: 14px;
        }

        .t17-blog-kicker:before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--primary-color);
        }

        .t17-blog-title {
            font-size: clamp(2rem, 5vw, 3.6rem);
            line-height: 1.02;
            font-weight: 850;
            letter-spacing: -.055em;
            color: #0f172a;
            margin: 0;
        }

        .t17-blog-sub {
            display: block;
            margin-top: 12px;
            font-size: clamp(.9rem, 1.5vw, 1.05rem);
            line-height: 1.7;
            color: #64748b;
            max-width: 600px;
        }

        .t17-blog-rule {
            display: block;
            width: 76px;
            height: 5px;
            border-radius: 99px;
            margin-top: 18px;
            background: linear-gradient(90deg, var(--primary-color), var(--hover-color));
        }

        .t17-blog-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
        }

        @media(min-width:560px) {
            .t17-blog-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 20px;
            }
        }

        @media(min-width:900px) {
            .t17-blog-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 24px;
            }
        }

        @media(min-width:1200px) {
            .t17-blog-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }

        .t17-blog-card {
            position: relative;
            min-width: 0;
            display: flex;
            flex-direction: column;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 0;
            overflow: hidden;
            text-decoration: none;
            box-shadow: none;
            transition: border-color .3s ease;
        }

        .t17-blog-card:hover {
            transform: none;
            border-color: var(--primary-color);
            box-shadow: none;
        }

        .t17-blog-imgwrap {
            position: relative;
            display: block;
            width: 100%;
            aspect-ratio: 4/3;
            overflow: hidden;
            background: #e2e8f0;
        }

        .t17-blog-imgwrap:after {
            content: '';
            position: absolute;
            inset: 45% 0 0;
            background: linear-gradient(transparent, rgba(15, 23, 42, .25));
            pointer-events: none;
        }

        .t17-blog-imgwrap img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .65s cubic-bezier(.2, .7, .2, 1);
        }

        .t17-blog-card:hover .t17-blog-imgwrap img {
            transform: scale(1.07);
        }

        .t17-blog-datebadge {
            position: absolute;
            z-index: 2;
            top: 12px;
            left: 12px;
            padding: 7px 10px;
            border-radius: 0;
            background: #fff;
            color: var(--hover-color);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .04em;
            box-shadow: none;
        }

        .t17-blog-corner {
            display: none;
        }

        .t17-blog-content {
            display: flex;
            flex-direction: column;
            flex: 1;
            padding: 22px 22px 24px;
        }

        .t17-blog-eyebrow {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 11px;
            color: #94a3b8;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        .t17-blog-num {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 28px;
            height: 25px;
            padding: 0 6px;
            border-radius: 0;
            background: color-mix(in srgb, var(--primary-color) 10%, #fff);
            color: var(--primary-color);
        }

        .t17-blog-dash {
            width: 18px;
            height: 2px;
            border-radius: 2px;
            background: #cbd5e1;
        }

        .t17-blog-card-title {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 2.8rem;
            margin: 0 0 11px;
            color: #0f172a;
            font-size: 1.05rem;
            line-height: 1.4;
            font-weight: 800;
            letter-spacing: -.025em;
            transition: color .25s ease;
        }

        .t17-blog-card:hover .t17-blog-card-title {
            color: var(--primary-color);
        }

        .t17-blog-meta {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 12px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
        }

        .t17-blog-meta-sep {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: #cbd5e1;
        }

        .t17-blog-meta i {
            color: var(--primary-color);
            font-size: 14px;
        }

        .t17-blog-excerpt {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            margin: 0 0 18px;
            color: #64748b;
            font-size: .84rem;
            line-height: 1.7;
        }

        .t17-blog-readmore {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            align-self: flex-start;
            margin-top: auto;
            padding: 10px 14px;
            border-radius: 0;
            background: color-mix(in srgb, var(--primary-color) 8%, #fff);
            color: var(--primary-color);
            font-size: 10px;
            font-weight: 850;
            letter-spacing: .1em;
            text-transform: uppercase;
            transition: background .25s ease, color .25s ease, gap .25s ease;
        }

        .t17-blog-card:hover .t17-blog-readmore {
            gap: 12px;
            background: var(--primary-color);
            color: #fff;
        }

        .t17-blog-readmore svg {
            transition: transform .25s ease;
        }

        .t17-blog-card:hover .t17-blog-readmore svg {
            transform: translateX(3px);
        }

        .t17-blog-empty {
            text-align: center;
            padding: clamp(38px, 7vw, 72px) 20px;
            border: 1px dashed #cbd5e1;
            border-radius: 0;
            background: #fff;
        }

        .t17-blog-empty-ico {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 66px;
            height: 66px;
            margin-bottom: 17px;
            border-radius: 0;
            background: color-mix(in srgb, var(--primary-color) 9%, #fff);
            color: var(--primary-color);
            font-size: 28px;
        }

        .t17-blog-empty h3 {
            margin: 0 0 8px;
            color: #0f172a;
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .t17-blog-empty p {
            margin: 0;
            color: #64748b;
            font-size: .9rem;
        }

        .t17-blog-pager {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 34px;
        }

        .t17-blog-page {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0 12px;
            border: 1px solid #e2e8f0;
            border-radius: 0;
            background: #fff;
            color: #475569;
            font-size: 13px;
            font-weight: 750;
            text-decoration: none;
            transition: all .25s ease;
        }

        .t17-blog-page:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
        }

        .t17-blog-page.is-active {
            border-color: var(--primary-color);
            background: var(--primary-color);
            color: #fff;
            box-shadow: none;
        }

        @media(max-width:640px) {
            .t17-blog-imgwrap {
                aspect-ratio: 4/3;
            }

            .t17-blog-content {
                padding: 15px;
            }

            .t17-blog-card-title {
                font-size: 1rem;
            }

            .t17-blog-datebadge {
                top: 9px;
                left: 9px;
                padding: 6px 8px;
                font-size: 9px;
            }
        }

        @media(prefers-reduced-motion:reduce) {

            .t17-blog-card,
            .t17-blog-imgwrap img,
            .t17-blog-readmore {
                transition: none !important;
            }
        }
    </style>
</head>

<body class="font-sans bg-white min-h-screen">


    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ==================== BLOG LIST ==================== -->
    <section class="t17-blog py-12 sm:py-16 px-4 sm:px-6">
        <div class="container mx-auto max-w-7xl">

            <!-- Header -->
            <div class="t17-blog-head">
                <span class="t17-blog-kicker">— Journal —</span>
                <h1 class="t17-blog-title">Our Blogs</h1>
                <span class="t17-blog-sub">Explore our latest articles, tips, and insights.</span>
                <span class="t17-blog-rule"></span>
            </div>

            <?php
            $posts = getBlogPosts();
            if ($posts && $posts->rowCount() > 0):
                $i = 0;
            ?>
                <div class="t17-blog-grid">
                    <?php foreach ($posts as $post): $i++;
                        $num = str_pad($i, 2, '0', STR_PAD_LEFT);
                        $dateShort = date("d M", strtotime($post['created_at']));
                        $dateFull  = date("d M Y", strtotime($post['created_at']));
                    ?>
                        <a href="<?= $storeUrl . 'post/' . $post['slug'] ?>" class="t17-blog-card">

                            <span class="t17-blog-imgwrap">
                                <img src="<?= UPLOADS_URL . $post['image'] ?>"
                                    alt="<?= htmlspecialchars($post['title']) ?>"
                                    loading="lazy">

                                <span class="t17-blog-datebadge"><?= $dateFull ?></span>

                                <span class="t17-blog-corner tl"></span>
                                <span class="t17-blog-corner br"></span>
                            </span>

                            <span class="t17-blog-content">
                                <span class="t17-blog-eyebrow">
                                    <span class="t17-blog-num"><?= $num ?></span>
                                    <span class="t17-blog-dash"></span>
                                    <span>Blog</span>
                                </span>

                                <h3 class="t17-blog-card-title">
                                    <?= htmlspecialchars($post['title']) ?>
                                </h3>

                                <span class="t17-blog-meta">
                                    <i class='bx bx-time-five'></i>
                                    <span><?= $dateShort ?></span>
                                    <?php if (!empty($post['author'])): ?>
                                        <span class="t17-blog-meta-sep"></span>
                                        <i class='bx bx-user'></i>
                                        <span><?= htmlspecialchars($post['author']) ?></span>
                                    <?php endif; ?>
                                </span>

                                <span class="t17-blog-excerpt">
                                    <?= limit_characters(strip_tags($post['description']), 120) ?>
                                </span>

                                <span class="t17-blog-readmore">
                                    Read More
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </span>
                            </span>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="t17-blog-empty">
                    <span class="t17-blog-empty-ico"><i class='bx bx-book-open'></i></span>
                    <h3>No Blog Posts Yet</h3>
                    <p>Check back soon for our latest articles and updates.</p>
                </div>
            <?php endif; ?>

            <!-- Pagination -->
            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <div class="t17-blog-pager">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <a href="?page=<?= $p ?>"
                            class="t17-blog-page <?= ($currentPage == $p) ? 'is-active' : '' ?>">
                            <?= $p ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

        </div>
    </section>
    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>

</html>