<?php
include_once __DIR__ . "/includes/files_includes.php";

$primary_color = getData("color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ff007f';
$hover_color   = getData("hover_color", "seller_settings", "(seller_id='$sellerId' AND store_id='$storeId')") ?? '#ec4899';

$categoryStmt = getCategories();
$allCategories = $categoryStmt ? $categoryStmt->fetchAll(PDO::FETCH_ASSOC) : [];
$allCategories = array_reverse($allCategories);
$parentCategories = [];
$subCategoriesMap = [];
$allSubs = [];
foreach ($allCategories as $cat) {
    $parentId = (int)($cat['parent_category'] ?? 0);
    if ($parentId <= 0) $parentCategories[] = $cat;
    else {
        $subCategoriesMap[$parentId][] = $cat;
        $allSubs[] = $cat;
    }
}
if (empty($parentCategories)) $parentCategories = $allCategories;

$productStmt = getProducts();
$allProducts = $productStmt ? $productStmt->fetchAll(PDO::FETCH_ASSOC) : [];
$featuredProducts = $allProducts;
shuffle($featuredProducts);
$featuredProducts = array_slice($featuredProducts, 0, 4);
$shopProducts = $allProducts;
shuffle($shopProducts);
$shopProducts = array_slice($shopProducts, 0, 16);
$categoryCount = count($parentCategories);
$productCount = count($allProducts);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once __DIR__ . "/includes/head_links.php"; ?>
    <style>
        :root {
            --sm17-primary: <?= htmlspecialchars($primary_color, ENT_QUOTES) ?>;
            --sm17-hover: <?= htmlspecialchars($hover_color, ENT_QUOTES) ?>;
            --sm17-ink: #191919;
            --sm17-paper: #f7f6f2;
            --sm17-line: #deddd7
        }

        * {
            box-sizing: border-box
        }

        html {
            scroll-behavior: smooth
        }

        body {
            margin: 0;
            background: var(--sm17-paper);
            color: var(--sm17-ink)
        }

        .sm17-main *,
        .sm17-main *:before,
        .sm17-main *:after {
            box-shadow: none !important;
            text-shadow: none !important
        }

        .sm17-shell {
            width: min(1600px, 100%);
            margin: auto;
            padding: clamp(10px, 1.35vw, 20px);
            display: grid;
            grid-template-columns: 155px minmax(0, 1fr);
            gap: clamp(12px, 1.5vw, 22px)
        }

        .sm17-side {
            align-self: stretch;
            min-width: 0;
            border: 1px solid var(--sm17-line);
            background: #fff;
            display: flex;
            flex-direction: column;
            overflow: hidden
        }

        .sm17-side-top {
            padding: 13px 12px;
            border-bottom: 1px solid var(--sm17-line)
        }

        .sm17-kicker {
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .17em;
            text-transform: uppercase;
            color: var(--sm17-primary)
        }

        .sm17-side-title {
            font-size: 23px;
            line-height: .94;
            font-weight: 950;
            letter-spacing: -.07em;
            margin: 11px 0 0
        }

        .sm17-side-title span {
            display: block;
            color: var(--sm17-primary)
        }

        .sm17-side-window {
            height: 440px;
            overflow: hidden;
            position: relative;
            mask-image: linear-gradient(to bottom, transparent, #000 8%, #000 92%, transparent)
        }

        .sm17-side-track {
            display: flex;
            flex-direction: column;
            animation: sm17-vertical 28s linear infinite
        }

        .sm17-side-window:hover .sm17-side-track {
            animation-play-state: paused
        }

        .sm17-side-link {
            min-height: 68px;
            padding: 12px 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--sm17-line);
            text-decoration: none;
            color: var(--sm17-ink);
            transition: background .2s, color .2s
        }

        .sm17-side-link .sm17-side-no {
            font-size: 9px;
            font-weight: 900;
            color: var(--sm17-primary);
            min-width: 19px
        }

        .sm17-side-link span:last-child {
            font-size: 11px;
            font-weight: 850;
            line-height: 1.25;
            overflow-wrap: anywhere
        }

        .sm17-side-link:hover {
            background: var(--sm17-primary);
            color: #fff
        }

        .sm17-side-link:hover .sm17-side-no {
            color: #fff
        }

        .sm17-side-bottom {
            margin-top: auto;
            padding: 14px;
            border-top: 1px solid var(--sm17-line);
            font-size: 9px;
            line-height: 1.6;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #777
        }

        @keyframes sm17-vertical {
            from {
                transform: translateY(0)
            }

            to {
                transform: translateY(-50%)
            }
        }

        .sm17-content {
            min-width: 0
        }

        .sm17-topline {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            padding: 5px 0 16px;
            border-bottom: 1px solid var(--sm17-line);
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .16em;
            text-transform: uppercase
        }

        .sm17-live {
            display: inline-flex;
            align-items: center;
            gap: 8px
        }

        .sm17-live:before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--sm17-primary)
        }

        .sm17-hero {
            min-height: 300px;
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(210px, .9fr);
            gap: 20px;
            align-items: center;
            padding: clamp(22px, 3.2vw, 46px) 0 26px
        }

        .sm17-hero h1 {
            font-size: clamp(58px, 8.8vw, 130px);
            font-weight: 950;
            line-height: .78;
            letter-spacing: -.105em;
            margin: 0
        }

        .sm17-hero h1 span {
            display: block;
            color: var(--sm17-primary);
            padding-left: clamp(18px, 4vw, 64px)
        }

        .sm17-hero-copy {
            max-width: 350px;
            justify-self: end;
            border-left: 2px solid var(--sm17-primary);
            padding-left: 20px
        }

        .sm17-hero-copy p {
            font-size: 13px;
            line-height: 1.8;
            color: #686862;
            margin: 0 0 22px
        }

        .sm17-btn {
            display: inline-flex;
            align-items: center;
            gap: 20px;
            padding: 14px 17px;
            background: var(--sm17-primary);
            color: #fff;
            text-decoration: none;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .12em;
            text-transform: uppercase;
            transition: background .2s
        }

        .sm17-btn:hover {
            background: var(--sm17-hover);
            color: #fff
        }

        .sm17-counts {
            display: flex;
            gap: 23px;
            margin-top: 25px
        }

        .sm17-count strong {
            font-size: 25px;
            letter-spacing: -.06em;
            display: block
        }

        .sm17-count span {
            display: block;
            font-size: 8px;
            letter-spacing: .13em;
            text-transform: uppercase;
            color: #777;
            margin-top: 4px
        }

        .sm17-marquee {
            overflow: hidden;
            background: var(--sm17-primary);
            color: #fff;
            display: flex;
            white-space: nowrap;
            margin: 0 0 28px
        }

        .sm17-marquee-track {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            min-width: 100%;
            animation: sm17-horizontal 25s linear infinite
        }

        .sm17-marquee:hover .sm17-marquee-track {
            animation-play-state: paused
        }

        .sm17-marquee-item {
            display: inline-flex;
            align-items: center;
            gap: 20px;
            padding: 13px 18px;
            font-size: clamp(15px, 2vw, 24px);
            font-weight: 950;
            letter-spacing: -.04em
        }

        .sm17-marquee-item i {
            font-style: normal;
            color: #fff;
            opacity: .75;
            font-size: 18px
        }

        @keyframes sm17-horizontal {
            from {
                transform: translateX(0)
            }

            to {
                transform: translateX(-100%)
            }
        }

        .sm17-section {
            padding: 0 0 clamp(28px, 3.5vw, 46px)
        }

        .sm17-section-head {
            display: flex;
            align-items: end;
            justify-content: space-between;
            gap: 22px;
            margin-bottom: 15px
        }

        .sm17-overline {
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .17em;
            text-transform: uppercase;
            color: var(--sm17-primary)
        }

        .sm17-section-head h2 {
            font-size: clamp(34px, 5vw, 68px);
            font-weight: 950;
            line-height: .86;
            letter-spacing: -.085em;
            margin: 10px 0 0
        }

        .sm17-section-head h2 span {
            color: var(--sm17-primary)
        }

        .sm17-section-head p {
            max-width: 270px;
            color: #777;
            font-size: 11px;
            line-height: 1.8;
            margin: 0
        }

        .sm17-cat-list {
            display: flex;
            flex-direction: column;
            border-top: 1px solid var(--sm17-line)
        }

        .sm17-cat-row {
            display: grid;
            grid-template-columns: 54px minmax(0, 1fr) 100px 38px;
            gap: 15px;
            align-items: center;
            min-height: 76px;
            padding: 10px 4px;
            border-bottom: 1px solid var(--sm17-line);
            text-decoration: none;
            color: var(--sm17-ink);
            transition: padding .2s, background .2s
        }

        .sm17-cat-row:hover {
            padding-inline: 15px;
            background: color-mix(in srgb, var(--sm17-primary) 7%, white)
        }

        .sm17-cat-index {
            font-size: 10px;
            font-weight: 900;
            color: var(--sm17-primary)
        }

        .sm17-cat-name {
            font-size: clamp(22px, 3vw, 42px);
            font-weight: 950;
            letter-spacing: -.07em;
            line-height: 1;
            overflow-wrap: anywhere
        }

        .sm17-cat-sub {
            font-size: 9px;
            color: #777;
            letter-spacing: .1em;
            text-transform: uppercase;
            text-align: right
        }

        .sm17-cat-thumb {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border: 1px solid var(--sm17-line);
            color: var(--sm17-primary);
            overflow: hidden;
            font-weight: 900
        }

        .sm17-cat-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .sm17-cat-row:hover .sm17-cat-thumb {
            background: var(--sm17-primary);
            color: #fff;
            border-color: var(--sm17-primary)
        }

        .sm17-products-section {
            padding: clamp(14px, 2vw, 24px);
            background: #fff;
            border: 1px solid var(--sm17-line)
        }

        .sm17-products {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px 10px
        }

        .sm17-product {
            min-width: 0
        }

        .sm17-product-label {
            font-size: 8px;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: var(--sm17-primary);
            margin: 0 0 8px
        }

        .sm17-subcats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            border-top: 1px solid var(--sm17-line);
            border-left: 1px solid var(--sm17-line)
        }

        .sm17-subcat {
            min-height: 62px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-right: 1px solid var(--sm17-line);
            border-bottom: 1px solid var(--sm17-line);
            text-decoration: none;
            color: var(--sm17-ink);
            font-size: 11px;
            font-weight: 850;
            transition: background .2s, color .2s
        }

        .sm17-subcat b {
            font-size: 19px;
            font-weight: 400;
            color: var(--sm17-primary)
        }

        .sm17-subcat:hover {
            background: var(--sm17-primary);
            color: #fff
        }

        .sm17-subcat:hover b {
            color: #fff
        }

        .sm17-empty {
            padding: 28px;
            border: 1px dashed var(--sm17-line);
            text-align: center;
            color: #777;
            font-size: 12px
        }

        .sm17-bottom {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 20px 0 10px;
            border-top: 1px solid var(--sm17-line);
            font-size: 8px;
            font-weight: 900;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: #777
        }

        /* HANGER COLLECTION SLIDER — compact, responsive Theme 17 styling */
        .sm17-hanger-collection {
            width: 100%;
            max-width: 1600px;
            margin: 0 auto 28px;
            padding: 0 clamp(10px, 1.35vw, 20px);
            min-width: 0;
            overflow: hidden
        }

        .sm17-hanger-collection * {
            box-sizing: border-box
        }

        .sm17-hanger-heading {
            text-align: center;
            margin: 0 auto 14px;
            padding: 0 8px
        }

        .sm17-hanger-heading small {
            display: block;
            font-size: 9px;
            font-weight: 900;
            letter-spacing: .18em;
            color: var(--sm17-primary);
            text-transform: uppercase;
            margin-bottom: 7px
        }

        .sm17-hanger-heading h2 {
            font-size: clamp(27px, 4vw, 48px);
            line-height: 1;
            letter-spacing: -.065em;
            font-weight: 950;
            margin: 0
        }

        .sm17-hanger-heading p {
            font-size: 12px;
            color: #777;
            margin: 7px 0 0;
            line-height: 1.5
        }

        .sm17-hanger-rail {
            height: 25px;
            position: relative;
            margin: 0 5% -1px
        }

        .sm17-hanger-rod {
            position: absolute;
            left: 2%;
            right: 2%;
            top: 7px;
            height: 4px;
            border-radius: 8px;
            background: linear-gradient(#b8b8b8, #555 45%, #d5d5d5 70%, #777);
            box-shadow: 0 2px 3px #0002
        }

        .sm17-hanger-bracket {
            position: absolute;
            top: 5px;
            width: 12px;
            height: 17px;
            border: 3px solid #777;
            border-top: 0;
            border-bottom: 0
        }

        .sm17-hanger-bracket.left {
            left: 0
        }

        .sm17-hanger-bracket.right {
            right: 0
        }

        .sm17-hanger-scroll {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            scrollbar-width: none;
            padding: 4px 1px 12px;
            overscroll-behavior-inline: contain
        }

        .sm17-hanger-scroll::-webkit-scrollbar {
            display: none
        }

        .sm17-hanger-products {
            display: flex;
            align-items: stretch;
            gap: clamp(10px, 1.3vw, 18px);
            width: max-content;
            min-width: 100%
        }

        .sm17-hanger-item {
            flex: 0 0 clamp(155px, 18vw, 235px);
            width: clamp(155px, 18vw, 235px);
            min-width: 0
        }

        .sm17-hanger-item>* {
            max-width: 100%
        }

        .sm17-hanger-controls {
            display: flex;
            justify-content: flex-end;
            gap: 7px;
            margin-top: 3px
        }

        .sm17-hanger-controls button {
            width: 34px;
            height: 34px;
            display: grid;
            place-items: center;
            border: 1px solid var(--sm17-line);
            background: #fff;
            color: var(--sm17-ink);
            font-size: 18px;
            cursor: pointer;
            transition: background .2s, color .2s, border-color .2s
        }

        .sm17-hanger-controls button:hover {
            background: var(--sm17-primary);
            border-color: var(--sm17-primary);
            color: #fff
        }

        @media(max-width:700px) {
            .sm17-hanger-collection {
                padding: 0 10px;
                margin-bottom: 22px
            }

            .sm17-hanger-heading {
                margin-bottom: 10px
            }

            .sm17-hanger-heading h2 {
                font-size: clamp(25px, 7vw, 36px)
            }

            .sm17-hanger-heading p {
                font-size: 11px
            }

            .sm17-hanger-rail {
                height: 20px;
                margin-inline: 4%
            }

            .sm17-hanger-rod {
                top: 6px;
                height: 3px
            }

            .sm17-hanger-item {
                flex-basis: clamp(145px, 43vw, 190px);
                width: clamp(145px, 43vw, 190px)
            }

            .sm17-hanger-products {
                gap: 10px
            }

            .sm17-hanger-controls button {
                width: 32px;
                height: 32px
            }
        }

        @media(max-width:360px) {
            .sm17-hanger-item {
                flex-basis: 72vw;
                width: 72vw
            }
        }

        \n @media(max-width:1050px) {
            .sm17-shell {
                grid-template-columns: 135px minmax(0, 1fr)
            }

            .sm17-side-window {
                height: 390px
            }

            .sm17-products {
                grid-template-columns: repeat(3, minmax(0, 1fr))
            }

            .sm17-hero {
                grid-template-columns: 1fr;
                gap: 26px
            }

            .sm17-hero-copy {
                justify-self: start;
                max-width: 450px
            }
        }

        @media(max-width:700px) {
            .sm17-shell {
                display: flex;
                flex-direction: column;
                padding: 10px;
                gap: 12px
            }

            .sm17-side {
                order: 2
            }

            .sm17-side-top {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px
            }

            .sm17-side-title {
                font-size: 18px;
                margin: 0
            }

            .sm17-side-window {
                height: auto;
                mask-image: none;
                overflow-x: auto;
                overflow-y: hidden
            }

            .sm17-side-track {
                flex-direction: row;
                width: max-content;
                animation: sm17-mobile-marquee 30s linear infinite
            }

            .sm17-side-link {
                min-height: 62px;
                width: 175px;
                border-right: 1px solid var(--sm17-line);
                border-bottom: 0
            }

            .sm17-side-bottom {
                display: none
            }

            @keyframes sm17-mobile-marquee {
                from {
                    transform: translateX(0)
                }

                to {
                    transform: translateX(-50%)
                }
            }

            .sm17-content {
                order: 1
            }

            .sm17-topline {
                font-size: 8px
            }

            .sm17-hero {
                min-height: 0;
                padding: 28px 0 22px
            }

            .sm17-hero h1 {
                font-size: clamp(58px, 15vw, 94px)
            }

            .sm17-hero-copy {
                padding-left: 14px
            }

            .sm17-marquee {
                margin-bottom: 24px
            }

            .sm17-marquee-item {
                padding: 14px 17px
            }

            .sm17-section-head {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px
            }

            .sm17-section-head p {
                max-width: 420px
            }

            .sm17-cat-row {
                grid-template-columns: 30px minmax(0, 1fr) 32px;
                gap: 9px;
                min-height: 78px
            }

            .sm17-cat-sub {
                display: none
            }

            .sm17-cat-name {
                font-size: clamp(21px, 6vw, 32px)
            }

            .sm17-cat-thumb {
                width: 32px;
                height: 32px
            }

            .sm17-products-section {
                padding: 12px
            }

            .sm17-products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 12px 8px
            }

            .sm17-subcats {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .sm17-subcat {
                min-height: 65px;
                padding: 11px;
                font-size: 10px
            }

            .sm17-bottom {
                flex-direction: column;
                line-height: 1.7
            }
        }

        @media(prefers-reduced-motion:reduce) {
            html {
                scroll-behavior: auto
            }

            .sm17-side-track,
            .sm17-marquee-track {
                animation: none !important
            }

            *,
            *:before,
            *:after {
                transition: none !important
            }
        }

        /* FINAL FIX: prevent horizontal page movement and keep typography balanced */
        html,
        body {
            max-width: 100%;
            overflow-x: clip;
        }

        body {
            font-size: 14px;
        }

        .sm17-main {
            width: 100%;
            max-width: 100%;
            overflow-x: clip;
        }

        .sm17-main .sm17-shell {
            width: 100%;
            max-width: 1500px;
            min-width: 0;
            padding: clamp(8px, 1.1vw, 16px);
            gap: clamp(10px, 1.2vw, 18px);
            grid-template-columns: clamp(112px, 12vw, 155px) minmax(0, 1fr);
        }

        .sm17-main .sm17-content,
        .sm17-main .sm17-section,
        .sm17-main .sm17-products,
        .sm17-main .sm17-product {
            min-width: 0;
            max-width: 100%;
        }

        .sm17-main .sm17-hero {
            min-height: 0;
            padding: 24px 0 20px;
            gap: 16px;
        }

        .sm17-main .sm17-hero h1 {
            font-size: clamp(40px, 5.2vw, 78px);
            line-height: .88;
            letter-spacing: -.075em;
            overflow-wrap: anywhere;
        }

        .sm17-main .sm17-hero h1 span {
            padding-left: clamp(8px, 2vw, 28px);
        }

        .sm17-main .sm17-hero-copy {
            max-width: 320px;
            padding-left: 14px;
        }

        .sm17-main .sm17-hero-copy p {
            font-size: 12px;
            line-height: 1.6;
            margin-bottom: 14px;
        }

        .sm17-main .sm17-counts {
            gap: 15px;
            margin-top: 16px;
        }

        .sm17-main .sm17-count strong {
            font-size: 21px;
        }

        .sm17-main .sm17-section-head {
            gap: 14px;
            margin-bottom: 12px;
        }

        .sm17-main .sm17-section-head h2 {
            font-size: clamp(27px, 3.5vw, 48px);
            line-height: .95;
            letter-spacing: -.065em;
            margin-top: 7px;
        }

        .sm17-main .sm17-section-head p {
            max-width: 230px;
            font-size: 10px;
            line-height: 1.6;
        }

        .sm17-main .sm17-cat-row {
            grid-template-columns: 35px minmax(0, 1fr) 82px 30px;
            gap: 9px;
            min-height: 62px;
            padding: 8px 3px;
        }

        .sm17-main .sm17-cat-name {
            font-size: clamp(17px, 2vw, 28px);
            letter-spacing: -.045em;
        }

        .sm17-main .sm17-cat-sub {
            font-size: 8px;
            letter-spacing: .04em;
        }

        .sm17-main .sm17-cat-thumb {
            width: 30px;
            height: 30px;
        }

        .sm17-main .sm17-products {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px 9px;
        }

        .sm17-main .sm17-products-section {
            padding: clamp(10px, 1.5vw, 20px);
        }

        .sm17-main .sm17-marquee {
            margin-bottom: 20px;
        }

        .sm17-main .sm17-marquee-item {
            font-size: clamp(12px, 1.45vw, 19px);
            padding: 11px 15px;
            gap: 12px;
        }

        .sm17-main .sm17-hanger-collection {
            width: 100%;
            max-width: 1500px;
            min-width: 0;
            padding: 0 clamp(8px, 1.1vw, 16px);
            margin: 0 auto 20px;
        }

        .sm17-main .sm17-hanger-heading {
            margin-bottom: 9px;
        }

        .sm17-main .sm17-hanger-heading h2 {
            font-size: clamp(23px, 3.1vw, 38px);
            line-height: 1.08;
            letter-spacing: -.045em;
        }

        .sm17-main .sm17-hanger-heading p {
            font-size: 11px;
            margin-top: 5px;
        }

        .sm17-main .sm17-hanger-products {
            width: max-content;
            min-width: 0;
        }

        .sm17-main .sm17-hanger-item {
            flex: 0 0 clamp(145px, 16vw, 205px);
            width: clamp(145px, 16vw, 205px);
        }

        .sm17-main .sm17-hanger-scroll {
            max-width: 100%;
            overscroll-behavior-x: contain;
        }

        .sm17-main .sm17-hanger-item img {
            max-width: 100%;
        }

        .sm17-main .sm17-bottom {
            padding: 13px 0 8px;
        }

        @media (max-width:1050px) {
            .sm17-main .sm17-shell {
                grid-template-columns: 120px minmax(0, 1fr);
            }

            .sm17-main .sm17-hero {
                grid-template-columns: minmax(0, 1fr);
                min-height: 0;
            }

            .sm17-main .sm17-hero-copy {
                justify-self: start;
                max-width: 430px;
            }

            .sm17-main .sm17-products {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .sm17-main .sm17-hero h1 {
                font-size: clamp(42px, 6.5vw, 68px);
            }
        }

        @media (max-width:700px) {
            .sm17-main .sm17-shell {
                display: flex;
                flex-direction: column;
                padding: 9px;
                gap: 10px;
            }

            .sm17-main .sm17-content {
                width: 100%;
            }

            .sm17-main .sm17-topline {
                flex-wrap: wrap;
                gap: 6px 12px;
                font-size: 7px;
                letter-spacing: .09em;
                padding-bottom: 10px;
            }

            .sm17-main .sm17-hero {
                padding: 23px 0 17px;
                gap: 14px;
            }

            .sm17-main .sm17-hero h1 {
                font-size: clamp(38px, 10vw, 60px);
            }

            .sm17-main .sm17-hero-copy p {
                font-size: 11px;
            }

            .sm17-main .sm17-btn {
                padding: 10px 12px;
                gap: 12px;
                font-size: 8px;
            }

            .sm17-main .sm17-count strong {
                font-size: 19px;
            }

            .sm17-main .sm17-marquee {
                margin-bottom: 16px;
            }

            .sm17-main .sm17-marquee-item {
                font-size: 13px;
                padding: 10px 12px;
            }

            .sm17-main .sm17-section {
                padding-bottom: 25px;
            }

            .sm17-main .sm17-section-head {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .sm17-main .sm17-section-head h2 {
                font-size: clamp(25px, 7vw, 36px);
            }

            .sm17-main .sm17-section-head p {
                max-width: 100%;
                font-size: 10px;
            }

            .sm17-main .sm17-cat-row {
                grid-template-columns: 25px minmax(0, 1fr) 28px;
                gap: 7px;
                min-height: 55px;
            }

            .sm17-main .sm17-cat-name {
                font-size: clamp(16px, 4.8vw, 23px);
            }

            .sm17-main .sm17-cat-index {
                font-size: 8px;
            }

            .sm17-main .sm17-cat-sub {
                display: none;
            }

            .sm17-main .sm17-cat-thumb {
                width: 28px;
                height: 28px;
            }

            .sm17-main .sm17-products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 11px 8px;
            }

            .sm17-main .sm17-products-section {
                padding: 10px;
            }

            .sm17-main .sm17-hanger-collection {
                padding: 0;
                margin-bottom: 17px;
            }

            .sm17-main .sm17-hanger-heading h2 {
                font-size: clamp(22px, 6vw, 31px);
            }

            .sm17-main .sm17-hanger-heading small {
                font-size: 8px;
            }

            .sm17-main .sm17-hanger-item {
                flex-basis: clamp(138px, 43vw, 175px);
                width: clamp(138px, 43vw, 175px);
            }

            .sm17-main .sm17-hanger-products {
                gap: 9px;
            }

            .sm17-main .sm17-subcats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sm17-main .sm17-subcat {
                min-height: 52px;
                padding: 8px;
                font-size: 10px;
            }

            .sm17-main .sm17-bottom {
                font-size: 7px;
                letter-spacing: .08em;
            }
        }

        @media (max-width:360px) {
            .sm17-main .sm17-hero h1 {
                font-size: 37px;
            }

            .sm17-main .sm17-hanger-item {
                flex-basis: 72vw;
                width: 72vw;
            }

            .sm17-main .sm17-products-section {
                padding: 7px;
            }
        }
    </style>
</head>

<body>

    <?php include_once __DIR__ . "/includes/nav-bar.php"; ?>

    <main class="sm17-main">
        <div class="sm17-shell">
            <aside class="sm17-side" aria-label="Scrolling category menu">
                <div class="sm17-side-top">
                    <div>
                        <div class="sm17-kicker">The index</div>
                        <div class="sm17-side-title">Shop by<span>category.</span></div>
                    </div><span class="sm17-kicker">↕</span>
                </div>
                <?php if (!empty($parentCategories)): ?>
                    <div class="sm17-side-window">
                        <div class="sm17-side-track">
                            <?php for ($pass = 0; $pass < 2; $pass++): foreach ($parentCategories as $i => $cat): ?>
                                    <a class="sm17-side-link" href="<?= htmlspecialchars($storeUrl . 'category/' . $cat['slug'], ENT_QUOTES) ?>"><span class="sm17-side-no"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span><span><?= htmlspecialchars($cat['name']) ?></span></a>
                            <?php endforeach;
                            endfor; ?>
                        </div>
                    </div>
                <?php else: ?><div class="sm17-empty">Categories will appear here.</div><?php endif; ?>
                <div class="sm17-side-bottom">Move through the list<br>Find your next favourite ↗</div>
            </aside>

            <div class="sm17-content">
                <div class="sm17-topline"><span class="sm17-live">The store, in motion</span><span><?= str_pad((string)$categoryCount, 2, '0', STR_PAD_LEFT) ?> categories / <?= str_pad((string)$productCount, 2, '0', STR_PAD_LEFT) ?> products</span></div>
                <section class="sm17-hero">
                    <h1>Find your<br><span>own thing.</span></h1>
                    <div class="sm17-hero-copy">
                        <p>Skip the usual browsing. Move through the categories, follow what catches your eye, and discover something new at every turn.</p><a class="sm17-btn" href="#sm17-collections">Start exploring <span>↘</span></a>
                        <div class="sm17-counts">
                            <div class="sm17-count"><strong><?= str_pad((string)$categoryCount, 2, '0', STR_PAD_LEFT) ?></strong><span>Collections</span></div>
                            <div class="sm17-count"><strong><?= str_pad((string)$productCount, 2, '0', STR_PAD_LEFT) ?></strong><span>Products to explore</span></div>
                        </div>
                    </div>
                </section>

                <!-- HANGER COLLECTION SLIDER -->
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
                            $products = $productsStmt
                                ? $productsStmt->fetchAll(PDO::FETCH_ASSOC)
                                : [];

                            $counter = 0;

                            foreach ($products as $product) {
                                if ($counter >= 10) {
                                    break;
                                }

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
                <script>
                    (() => {
                        const rail = document.getElementById('sm17HangerScroll');
                        const left = document.getElementById('sm17HangerLeft');
                        const right = document.getElementById('sm17HangerRight');
                        if (!rail || !left || !right) return;
                        const amount = () => Math.max(180, Math.round(rail.clientWidth * 0.75));
                        left.addEventListener('click', () => rail.scrollBy({
                            left: -amount(),
                            behavior: 'smooth'
                        }));
                        right.addEventListener('click', () => rail.scrollBy({
                            left: amount(),
                            behavior: 'smooth'
                        }));
                    })();
                </script>


            </div>
        </div>

        <div class="sm17-marquee" aria-label="Store highlights">
            <div class="sm17-marquee-track">
                <?php for ($pass = 0; $pass < 2; $pass++): ?><span class="sm17-marquee-item">GOOD FINDS, NO DETOURS <i>✳</i></span><span class="sm17-marquee-item">YOUR STYLE, YOUR DISCOVERY <i>↗</i></span><span class="sm17-marquee-item">TAKE THE LONG WAY AROUND <i>✳</i></span><span class="sm17-marquee-item">SOMETHING NEW IS WAITING <i>↗</i></span><?php endfor; ?>
            </div>
        </div>


        <div class="sm17-shell" style="padding-top:0">
            <div aria-hidden="true"></div>
            <div class="sm17-content">
                <section class="sm17-section" id="sm17-collections">
                    <div class="sm17-section-head">
                        <div>
                            <div class="sm17-overline">01 / The category directory</div>
                            <h2>Pick a lane.<br><span>Then wander.</span></h2>
                        </div>
                        <p>Every collection gets its own line. Browse the list or use the moving category rail to jump directly to a section.</p>
                    </div>
                    <?php if (!empty($parentCategories)): ?><div class="sm17-cat-list">
                            <?php foreach ($parentCategories as $i => $cat): $catImage = !empty($cat['icon']) ? UPLOADS_URL . $cat['icon'] : '';
                                $subs = $subCategoriesMap[$cat['id']] ?? []; ?>
                                <a class="sm17-cat-row" href="<?= htmlspecialchars($storeUrl . 'category/' . $cat['slug'], ENT_QUOTES) ?>"><span class="sm17-cat-index">/<?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></span><span class="sm17-cat-name"><?= htmlspecialchars($cat['name']) ?></span><span class="sm17-cat-sub"><?= count($subs) ? count($subs) . ' subcategories' : 'Explore collection' ?></span><span class="sm17-cat-thumb"><?php if ($catImage): ?><img src="<?= htmlspecialchars($catImage, ENT_QUOTES) ?>" alt="" loading="lazy"><?php else: ?>↗<?php endif; ?></span></a>
                            <?php endforeach; ?>
                        </div><?php else: ?><div class="sm17-empty">Your categories will appear here when they are added to the store.</div><?php endif; ?>
                </section>

                <?php if (!empty($featuredProducts)): ?><section class="sm17-section">
                        <div class="sm17-section-head">
                            <div>
                                <div class="sm17-overline">02 / A little preview</div>
                                <h2>Worth a<br><span>closer look.</span></h2>
                            </div>
                            <p>A small rotating selection from your catalogue. See something you like? Open it and explore further.</p>
                        </div>
                        <div class="sm17-products">
                            <?php foreach ($featuredProducts as $i => $product): ?><div class="sm17-product">
                                    <p class="sm17-product-label">Spotlight / <?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></p><?php echo getProductHtml($product['id']); ?>
                                </div><?php endforeach; ?>
                        </div>
                    </section><?php endif; ?>

                <?php if (!empty($shopProducts)): ?><section class="sm17-section" id="sm17-products">
                        <div class="sm17-products-section">
                            <div class="sm17-section-head">
                                <div>
                                    <div class="sm17-overline">03 / The wider edit</div>
                                    <h2>Keep looking<span>.</span></h2>
                                </div>
                                <p>More products from across the store. Let the next good find be a surprise.</p>
                            </div>
                            <div class="sm17-products">
                                <?php foreach ($shopProducts as $product): ?><div class="sm17-product"><?php echo getProductHtml($product['id']); ?></div><?php endforeach; ?>
                            </div>
                        </div>
                    </section><?php endif; ?>

                <?php if (!empty($allSubs)): ?><section class="sm17-section">
                        <div class="sm17-section-head">
                            <div>
                                <div class="sm17-overline">04 / Get more specific</div>
                                <h2>Little corners.<br><span>Big finds.</span></h2>
                            </div>
                            <p>Jump straight into a subcategory when you already know what you want to find.</p>
                        </div>
                        <div class="sm17-subcats">
                            <?php foreach ($allSubs as $sub): ?><a class="sm17-subcat" href="<?= htmlspecialchars($storeUrl . 'category/' . $sub['slug'], ENT_QUOTES) ?>"><span><?= htmlspecialchars($sub['name']) ?></span><b>↗</b></a><?php endforeach; ?>
                        </div>
                    </section><?php endif; ?>
                <div class="sm17-bottom"><span>Your next find is somewhere in here.</span><span><?= $categoryCount ?> categories · <?= $productCount ?> products</span></div>
            </div>
        </div>
    </main>
    <?php include_once __DIR__ . '/includes/floting-cart.php' ?>
    <?php include_once __DIR__ . "/includes/footer.php"; ?>
</body>

</html>