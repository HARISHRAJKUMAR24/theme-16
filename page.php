<?php include_once __DIR__ . "/includes/files_includes.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        wine: '#65132f',
                        cream: '#f7f2ee',
                        gold: '#b68a3b'
                    },
                    fontFamily: {
                        serifDisplay: ['Georgia', 'serif'],
                        clean: ['Arial', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        :root {
            --primary-color: <?= htmlspecialchars(getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#64748b') ?>;
            --hover-color: <?= htmlspecialchars(getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#475569') ?>;
        }

        /* Theme 17 — minimal information page */
        .t17-page { color:#1f2937; background:#fff; }
        .t17-page-head { padding:clamp(30px,5vw,56px) 16px 26px; background:#f8fafc; }
        .t17-page-eyebrow {
            display:flex; align-items:center; justify-content:center; gap:9px;
            margin-bottom:10px; color:var(--primary-color); font-size:10px;
            font-weight:800; letter-spacing:.16em; text-transform:uppercase;
        }
        .t17-page-eyebrow:before,.t17-page-eyebrow:after { content:""; width:22px; height:2px; background:var(--primary-color); }
        .t17-page-title {
            margin:0; color:#111827; font-size:clamp(1.65rem,4vw,2.65rem);
            line-height:1.15; font-weight:800; letter-spacing:-.04em; overflow-wrap:anywhere;
        }
        .t17-page-accent { width:42px; height:3px; margin:15px auto 0; background:var(--primary-color); }
        .t17-page-content { padding:clamp(28px,5vw,52px) 16px; }
        .t17-page-inner { max-width:900px; margin:0 auto; }
        .t17-page-article { padding:0; background:#fff; }
        .t17-content {
            color:#374151; font-size:15px; line-height:1.85; overflow-wrap:anywhere; word-wrap:break-word;
        }
        .t17-content h1,.t17-content h2,.t17-content h3,.t17-content h4 {
            margin:1.7rem 0 .75rem; color:#111827; font-weight:800; line-height:1.25; letter-spacing:-.025em;
        }
        .t17-content h1 { font-size:1.8rem; }
        .t17-content h2 { font-size:1.55rem; }
        .t17-content h3 { font-size:1.25rem; }
        .t17-content h4 { font-size:1.08rem; }
        .t17-content p { margin:0 0 1rem; }
        .t17-content p:last-child { margin-bottom:0; }
        .t17-content a { color:var(--primary-color); text-decoration:underline; text-underline-offset:3px; }
        .t17-content a:hover { color:var(--hover-color); }
        .t17-content strong,.t17-content b { color:#111827; font-weight:750; }
        .t17-content em,.t17-content i { font-style:italic; }
        .t17-content ul,.t17-content ol { margin:1rem 0; padding-left:1.45rem; }
        .t17-content li { margin-bottom:.4rem; }
        .t17-content ul li { list-style:disc; }
        .t17-content ol li { list-style:decimal; }
        .t17-content blockquote {
            margin:1.35rem 0; padding:13px 18px; border-left:3px solid var(--primary-color);
            background:#f8fafc; color:#4b5563;
        }
        .t17-content img { max-width:100%; height:auto; margin:1rem 0; }
        .t17-content table { width:100%; border-collapse:collapse; margin:1rem 0; font-size:14px; }
        .t17-content th,.t17-content td { border-bottom:1px solid #e5e7eb; padding:9px 11px; text-align:left; }
        .t17-content th { background:#f8fafc; color:#111827; font-weight:750; }
        .t17-content hr { margin:1.6rem 0; border:0; border-top:1px solid #e5e7eb; }
        .t17-content code { padding:2px 5px; background:#f3f4f6; font-size:13px; color:#111827; }
        .t17-content pre { padding:1rem; overflow-x:auto; background:#111827; color:#f9fafb; font-size:13px; margin:1rem 0; }
        .t17-page-actions { margin-top:30px; padding-top:22px; border-top:1px solid #e5e7eb; }
        .t17-page-btn {
            display:inline-flex; align-items:center; justify-content:center; gap:9px;
            padding:11px 17px; background:var(--primary-color); color:#fff; font-size:11px;
            font-weight:800; letter-spacing:.08em; text-transform:uppercase; text-decoration:none;
            transition:background .2s,gap .2s;
        }
        .t17-page-btn:hover { background:var(--hover-color); color:#fff; gap:13px; }
        @media(max-width:640px) {
            .t17-page-head { padding:28px 16px 22px; }
            .t17-page-content { padding:28px 16px; }
            .t17-content { font-size:14px; line-height:1.8; }
            .t17-content h1 { font-size:1.55rem; }
            .t17-content h2 { font-size:1.35rem; }
            .t17-content h3 { font-size:1.15rem; }
            .t17-page-actions { margin-top:24px; padding-top:18px; }
        }
    </style>
</head>

<body class="bg-white font-sans min-h-screen">

    <?php

    if (isset($_GET['slug']) && getData("id", "seller_pages", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'")) {
        $slug    = $_GET['slug'];
        $id      = getData("id", "seller_pages", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
        $name    = getData("name", "seller_pages", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
        $content = getData("content", "seller_pages", "slug = '{$_GET['slug']}' AND seller_id = '$sellerId'");
    } else {
        redirect($storeUrl);
    }

    $primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#64748b';
    $hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?: '#475569';
    ?>

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <main class="t17-page">
        <header class="t17-page-head">
            <div class="max-w-4xl mx-auto text-center">
                <p class="t17-page-eyebrow">Information</p>
                <h1 class="t17-page-title"><?= htmlspecialchars($name) ?></h1>
                <div class="t17-page-accent"></div>
            </div>
        </header>

        <section class="t17-page-content">
            <div class="t17-page-inner">
                <article class="t17-page-article">
                    <div class="t17-content">
                        <?= $content ?>
                    </div>
                </article>

                <div class="t17-page-actions">
                    <a href="<?= $storeUrl ?>" class="t17-page-btn">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Home
                    </a>
                </div>
            </div>
        </section>
    </main>

    <?php include_once __DIR__ . "/includes/footer.php"; ?>

</body>
</html>
