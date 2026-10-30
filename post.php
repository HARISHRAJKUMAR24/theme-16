<?php
include_once __DIR__ . "/includes/files_includes.php";

if (isset($_GET['slug']) && getData("id", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')")) {

    $slug = $_GET['slug'];
    $id = getData("id", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $title = getData("title", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $image = getData("image", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $description = getData("description", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $created_at = getData("created_at", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");
    $visitors = getData("visitors", "seller_blog", "slug = '{$_GET['slug']}' AND (seller_id = '$sellerId' AND store_id = '$storeId')");

    $db->prepare("UPDATE seller_blog SET visitors = visitors+1 WHERE id = :id")->execute(['id' => $id]);
} else {
    redirect($storeUrl);
}

$relatedPosts = getBlogPosts(3, $id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>

    <style>
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#64748b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#475569') ?>;
        }

        /* Theme 17: compact article layout, dynamic store colors, no decorative frames */
        .t17-post {
            background: #fff;
            color: #1f2937;
        }

        .t17-post .container {
            max-width: 1120px;
        }

        .t17-post-back {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 22px;
            color: var(--primary-color);
            font-size: 11px;
            font-weight: 750;
            text-decoration: none;
            transition: color .2s, gap .2s;
        }

        .t17-post-back:hover {
            color: var(--hover-color);
            gap: 11px;
        }

        .t17-post-back svg {
            flex-shrink: 0;
        }

        .t17-post-head {
            max-width: 900px;
            margin: 0 auto 22px;
            text-align: center;
        }

        .t17-post-kicker {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 10px;
            color: var(--primary-color);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
        }

        .t17-post-kicker:before {
            content: "";
            width: 18px;
            height: 2px;
            background: var(--primary-color);
        }

        .t17-post-kicker-line {
            display: none;
        }

        .t17-post-title {
            margin: 0 0 15px;
            color: #111827;
            font-size: clamp(1.65rem, 3.4vw, 2.8rem);
            line-height: 1.13;
            font-weight: 800;
            letter-spacing: -.04em;
            overflow-wrap: anywhere;
        }

        .t17-post-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px 16px;
            color: #6b7280;
            font-size: 11px;
            font-weight: 600;
        }

        .t17-post-meta-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .t17-post-meta-item i {
            color: var(--primary-color);
            font-size: 14px;
        }

        .t17-post-meta-sep {
            width: 3px;
            height: 3px;
            display: inline-block;
            border-radius: 50%;
            background: #9ca3af;
        }

        .t17-post-hero {
            position: relative;
            width: 100%;
            max-width: 940px;
            margin: 0 auto 30px;
            aspect-ratio: 16/7.5;
            overflow: hidden;
            background: #f3f4f6;
        }

        .t17-post-hero img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .t17-post-corner {
            display: none;
        }

        .t17-post-body {
            max-width: 760px;
            margin: 0 auto;
            padding: 0;
            color: #374151;
            font-size: 1rem;
            line-height: 1.85;
            overflow-wrap: anywhere;
        }

        .t17-post-body p {
            margin: 0 0 1.15rem;
        }

        .t17-post-body>p:first-of-type {
            color: #374151;
        }

        .t17-post-body>p:first-of-type::first-letter {
            float: left;
            padding: 4px 8px 0 0;
            color: var(--primary-color);
            font-size: 2.8rem;
            font-weight: 800;
            line-height: .85;
        }

        .t17-post-body h1,
        .t17-post-body h2,
        .t17-post-body h3,
        .t17-post-body h4 {
            margin: 1.8rem 0 .8rem;
            color: #111827;
            font-weight: 800;
            line-height: 1.25;
            letter-spacing: -.025em;
        }

        .t17-post-body h1 {
            font-size: 1.8rem;
        }

        .t17-post-body h2 {
            font-size: 1.55rem;
        }

        .t17-post-body h3 {
            font-size: 1.25rem;
        }

        .t17-post-body h4 {
            font-size: 1.08rem;
        }

        .t17-post-body a {
            color: var(--primary-color);
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        .t17-post-body a:hover {
            color: var(--hover-color);
        }

        .t17-post-body ul,
        .t17-post-body ol {
            margin: 0 0 1.15rem;
            padding-left: 1.4rem;
        }

        .t17-post-body li {
            margin-bottom: .4rem;
        }

        .t17-post-body img {
            display: block;
            max-width: 100%;
            height: auto;
            margin: 1.5rem auto;
        }

        .t17-post-body blockquote {
            margin: 1.6rem 0;
            padding: 14px 18px;
            border-left: 3px solid var(--primary-color);
            background: #f8fafc;
            color: #4b5563;
            line-height: 1.75;
        }

        .t17-post-body hr {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 1.7rem 0;
        }

        .t17-post-body code {
            padding: 2px 5px;
            background: #f3f4f6;
            font-size: .9em;
            font-family: ui-monospace, Menlo, Consolas, monospace;
        }

        .t17-post-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            max-width: 760px;
            margin: 32px auto 0;
        }

        .t17-post-divider-line {
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        .t17-post-divider-label {
            color: #9ca3af;
            font-size: 9px;
            font-weight: 750;
            letter-spacing: .14em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .t17-post-related {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #e5e7eb;
        }

        .t17-post-related-head {
            margin-bottom: 18px;
            text-align: left;
        }

        .t17-post-related-kicker {
            display: block;
            margin-bottom: 6px;
            color: var(--primary-color);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .13em;
            text-transform: uppercase;
        }

        .t17-post-related-title {
            margin: 0;
            color: #111827;
            font-size: clamp(1.35rem, 2.5vw, 2rem);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -.035em;
        }

        .t17-post-related-rule {
            display: block;
            width: 42px;
            height: 3px;
            margin-top: 10px;
            background: var(--primary-color);
        }

        .t17-post-related-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        @media(min-width:600px) {
            .t17-post-related-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 18px;
            }
        }

        .t17-related-card {
            display: flex;
            min-width: 0;
            flex-direction: column;
            overflow: hidden;
            background: #fff;
            color: inherit;
            text-decoration: none;
            border: 0;
            transition: color .2s;
        }

        .t17-related-card:hover {
            color: var(--primary-color);
        }

        .t17-related-imgwrap {
            display: block;
            width: 100%;
            aspect-ratio: 5/3;
            overflow: hidden;
            background: #f3f4f6;
        }

        .t17-related-imgwrap img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .35s ease;
        }

        .t17-related-card:hover .t17-related-imgwrap img {
            transform: scale(1.03);
        }

        .t17-related-content {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 12px 0 4px;
            border: 0;
        }

        .t17-related-date {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 7px;
            color: #6b7280;
            font-size: 10px;
            font-weight: 650;
            text-transform: uppercase;
        }

        .t17-related-date i {
            color: var(--primary-color);
            font-size: 13px;
        }

        .t17-related-name {
            display: -webkit-box;
            min-height: 2.6rem;
            overflow: hidden;
            color: #111827;
            font-size: 1rem;
            font-weight: 750;
            line-height: 1.35;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            transition: color .2s;
        }

        .t17-related-card:hover .t17-related-name {
            color: var(--primary-color);
        }

        @media(max-width:640px) {
            .t17-post {
                padding-top: 24px;
                padding-bottom: 32px;
            }

            .t17-post-back {
                margin-bottom: 18px;
            }

            .t17-post-head {
                margin-bottom: 18px;
                text-align: left;
            }

            .t17-post-title {
                font-size: clamp(1.55rem, 7vw, 2.2rem);
            }

            .t17-post-meta {
                justify-content: flex-start;
                gap: 8px 11px;
            }

            .t17-post-hero {
                aspect-ratio: 4/3;
                margin-bottom: 22px;
            }

            .t17-post-body {
                font-size: .96rem;
                line-height: 1.8;
            }

            .t17-post-body>p:first-of-type::first-letter {
                font-size: 2.35rem;
            }

            .t17-post-related {
                margin-top: 30px;
                padding-top: 20px;
            }
        }

        @media(prefers-reduced-motion:reduce) {

            .t17-post-back,
            .t17-related-imgwrap img,
            .t17-related-name {
                transition: none !important;
            }
        }
    </style>
</head>

<body class="font-sans bg-white min-h-screen">

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <!-- ==================== SINGLE BLOG POST ==================== -->
    <section class="t17-post py-10 sm:py-14 px-4 sm:px-6">
        <div class="container mx-auto max-w-6xl">

            <!-- Back -->
            <a href="<?= $storeUrl ?>blog" class="t17-post-back">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Journal
            </a>

            <!-- Header -->
            <header class="t17-post-head">
                <span class="t17-post-kicker">Article / Journal</span>
                <h1 class="t17-post-title"><?= htmlspecialchars($title) ?></h1>

                <div class="t17-post-meta">
                    <span class="t17-post-meta-item">
                        <i class='bx bx-time-five'></i>
                        <span><?= date("d M Y", strtotime($created_at)) ?></span>
                    </span>
                    <span class="t17-post-meta-sep"></span>
                    <span class="t17-post-meta-item">
                        <i class='bx bx-user'></i>
                        <span><?= htmlspecialchars($storeName ?? 'Admin') ?></span>
                    </span>
                    <span class="t17-post-meta-sep"></span>
                    <span class="t17-post-meta-item">
                        <i class='bx bx-show'></i>
                        <span><?= (int)$visitors + 1 ?> views</span>
                    </span>
                </div>
            </header>

            <!-- Feature image -->
            <figure class="t17-post-hero">
                <img src="<?= UPLOADS_URL . $image ?>"
                    alt="<?= htmlspecialchars($title) ?>">
            </figure>

            <!-- Body -->
            <article class="t17-post-body description">
                <?= $description ?>
            </article>

            <!-- Divider -->
            <div class="t17-post-divider">
                <span class="t17-post-divider-line"></span>
                <span class="t17-post-divider-label">End of Article</span>
                <span class="t17-post-divider-line"></span>
            </div>

            <!-- Related Posts -->
            <?php if ($relatedPosts && $relatedPosts->rowCount() > 0): ?>
                <section class="t17-post-related">
                    <div class="t17-post-related-head">
                        <span class="t17-post-related-kicker">— Keep Reading —</span>
                        <h2 class="t17-post-related-title">You Might Also Like</h2>
                        <span class="t17-post-related-rule"></span>
                    </div>

                    <div class="t17-post-related-grid">
                        <?php
                        $shown = 0;
                        while ($rp = $relatedPosts->fetch()):
                            if ($rp['id'] == $id) continue;
                            if ($shown >= 3) break;
                            $shown++;
                        ?>
                            <a href="<?= $storeUrl . 'post/' . $rp['slug'] ?>" class="t17-related-card">
                                <span class="t17-related-imgwrap">
                                    <img src="<?= UPLOADS_URL . $rp['image'] ?>"
                                        alt="<?= htmlspecialchars($rp['title']) ?>"
                                        loading="lazy">
                                </span>
                                <span class="t17-related-content">
                                    <span class="t17-related-date">
                                        <i class='bx bx-time-five'></i>
                                        <?= date("d M Y", strtotime($rp['created_at'])) ?>
                                    </span>
                                    <span class="t17-related-name"><?= htmlspecialchars($rp['title']) ?></span>
                                </span>
                            </a>
                        <?php endwhile; ?>
                    </div>
                </section>
            <?php endif; ?>

        </div>
    </section>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>

</html>