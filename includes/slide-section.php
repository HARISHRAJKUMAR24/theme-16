<!-- ==========================================
     WEAR IT • REVIEW IT • LOVE IT
     CUSTOMER PHOTO CAROUSEL
    ========================================== -->
<section class="customer-love">

    <!-- HEADING -->
    <div class="customer-love-heading">
        <h2>Wear it <span>✦</span> Review it <span>✦</span> Love it</h2>
        <p>Style isn't just about what you see in the mirror—it's about how you feel the moment you put it on.</p>
    </div>

    <!-- PHOTO CAROUSEL -->
    <div class="customer-gallery" id="customerGallery">
        <div class="customer-track" id="customerTrack">
            <div class="customer-photo photo-1">
                <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=600&q=85" alt="Customer fashion look">
            </div>
            <div class="customer-photo photo-2">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=600&q=85" alt="Customer outfit inspiration">
            </div>
            <div class="customer-photo photo-3">
                <img src="https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=600&q=85" alt="Customer style portrait">
            </div>
            <div class="customer-photo photo-4">
                <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?auto=format&fit=crop&w=600&q=85" alt="Customer fashion portrait">
            </div>
            <div class="customer-photo photo-5">
                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=85" alt="Customer wearing a fashion outfit">
            </div>
            <div class="customer-photo photo-6">
                <img src="https://images.unsplash.com/photo-1529139574466-a303027c1d8b?auto=format&fit=crop&w=600&q=85" alt="Customer fashion inspiration">
            </div>
            <div class="customer-photo photo-7">
                <img src="https://images.unsplash.com/photo-1509631179647-0177331693ae?auto=format&fit=crop&w=600&q=85" alt="Customer outfit">
            </div>
            <div class="customer-photo photo-8">
                <img src="https://images.unsplash.com/photo-1496747611176-843222e1e57c?auto=format&fit=crop&w=600&q=85" alt="Customer style">
            </div>
        </div>
    </div>

    <div class="customer-gallery-line"></div>
</section>

<style>
    /* ==========================================
       MAIN SECTION — tighter padding
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
            repeating-linear-gradient(35deg,
                transparent 0,
                transparent 44px,
                rgba(120, 120, 120, 0.20) 45px,
                transparent 46px,
                transparent 80px),
            repeating-linear-gradient(125deg,
                transparent 0,
                transparent 52px,
                rgba(120, 120, 120, 0.16) 53px,
                transparent 54px,
                transparent 95px);
    }

    /* ==========================================
       HEADING — compact
       ========================================== */
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

    /* ==========================================
       GALLERY VIEWPORT — tighter padding
       ========================================== */
    .customer-gallery {
        position: relative;
        width: 100%;
        overflow: hidden;
        padding: 6px 0 16px;
        cursor: grab;
        touch-action: pan-y;
    }

    .customer-gallery:active {
        cursor: grabbing;
    }

    /* ==========================================
       HORIZONTAL TRACK — tighter gap
       ========================================== */
    .customer-track {
        display: flex;
        align-items: center;
        gap: 16px;
        width: max-content;
        padding: 0 16px;
        will-change: transform;
        animation: customerMarquee 32s linear infinite;
    }

    .customer-gallery:hover .customer-track {
        animation-play-state: paused;
    }

    /* ==========================================
       PHOTO CARDS — smaller
       ========================================== */
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
        box-shadow:
            0 6px 16px rgba(0, 0, 0, 0.10),
            0 2px 4px rgba(0, 0, 0, 0.04);
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }

    /* SLIGHTLY TILTED PHOTO CARDS */
    .photo-1 {
        transform: rotate(-4deg);
    }

    .photo-2 {
        transform: rotate(3deg);
    }

    .photo-3 {
        transform: rotate(-3deg);
    }

    .photo-4 {
        transform: rotate(4deg);
    }

    .photo-5 {
        transform: rotate(-2deg);
    }

    .photo-6 {
        transform: rotate(3deg);
    }

    .photo-7 {
        transform: rotate(-4deg);
    }

    .photo-8 {
        transform: rotate(2deg);
    }

    .customer-photo:hover {
        transform: rotate(0deg) translateY(-6px) scale(1.025);
        box-shadow:
            0 14px 28px rgba(0, 0, 0, 0.15),
            0 3px 8px rgba(0, 0, 0, 0.05);
        z-index: 2;
    }

    .customer-photo img {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 6px;
        user-select: none;
        -webkit-user-drag: none;
    }

    /* ==========================================
       CONTINUOUS MARQUEE
       ========================================== */
    @keyframes customerMarquee {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-50%);
        }
    }

    .customer-gallery-line {
        height: 1.5px;
        width: 100%;
        background: rgba(100, 100, 100, 0.30);
    }

    /* ==========================================
       TABLET
       ========================================== */
    @media (max-width: 900px) {
        .customer-love {
            --gallery-card-width: 150px;
            padding-top: 20px;
        }

        .customer-love-heading {
            margin-bottom: 15px;
        }

        .customer-photo {
            height: 200px;
        }

        .customer-track {
            gap: 13px;
            animation-duration: 28s;
        }
    }

    /* ==========================================
       MOBILE
       ========================================== */
    @media (max-width: 600px) {
        .customer-love {
            --gallery-card-width: 125px;
            padding: 18px 0 8px;
        }

        .customer-love-heading {
            padding: 0 16px;
            margin-bottom: 12px;
        }

        .customer-love-heading h2 {
            font-size: 17px;
            letter-spacing: 0;
        }

        .customer-love-heading h2 span {
            margin: 0 1px;
        }

        .customer-love-heading p {
            max-width: 320px;
            margin: 0 auto;
            font-size: 10px;
        }

        .customer-gallery {
            padding: 5px 0 14px;
        }

        .customer-track {
            gap: 11px;
            padding: 0 12px;
            animation-duration: 24s;
        }

        .customer-photo {
            height: 170px;
            padding: 6px;
            border-radius: 9px;
        }

        .customer-photo img {
            border-radius: 5px;
        }
    }

    /* REDUCED MOTION */
    @media (prefers-reduced-motion: reduce) {
        .customer-track {
            animation: none;
        }

        .customer-photo {
            transition: none;
        }
    }
</style>

<script>
    (function() {
        const gallery = document.getElementById("customerGallery");
        const track = document.getElementById("customerTrack");
        if (!gallery || !track) return;

        if (!track.dataset.duplicated) {
            track.innerHTML += track.innerHTML;
            track.dataset.duplicated = "true";
        }

        let startX = 0;
        let isDragging = false;

        gallery.addEventListener("pointerdown", function(event) {
            startX = event.clientX;
            isDragging = true;
            track.style.animationPlayState = "paused";
        });

        gallery.addEventListener("pointerup", function() {
            isDragging = false;
            if (!gallery.matches(":hover")) {
                track.style.animationPlayState = "running";
            }
        });

        gallery.addEventListener("pointercancel", function() {
            isDragging = false;
            track.style.animationPlayState = "running";
        });

        gallery.addEventListener("pointermove", function(event) {
            if (!isDragging) return;
            const difference = Math.abs(event.clientX - startX);
            if (difference > 8) {
                track.style.animationPlayState = "paused";
            }
        });

        gallery.addEventListener("pointerleave", function() {
            isDragging = false;
            track.style.animationPlayState = "running";
        });
    })();
</script>


<!-- ==========================================
     SHINISHA — COMPACT ABOUT US SECTION
    ========================================== -->
<section class="about-shinisha relative overflow-hidden bg-[#faf8f5] py-8 sm:py-10 lg:py-12">

    <!-- DECORATIVE BACKGROUND -->
    <div class="pointer-events-none absolute -left-32 top-20 h-72 w-72 rounded-full bg-[#a13d91]/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -right-32 bottom-0 h-80 w-80 rounded-full bg-[#d9b6ce]/30 blur-3xl"></div>

    <div class="relative mx-auto grid max-w-6xl items-center gap-8 px-5 sm:px-8 lg:grid-cols-2 lg:gap-12 lg:px-10">

        <!-- LEFT: EDITORIAL IMAGE COLLAGE -->
        <div class="relative mx-auto w-full max-w-md">

            <!-- MAIN IMAGE -->
            <div class="relative z-10 w-[75%] overflow-hidden rounded-t-[48%] rounded-b-2xl border-[6px] border-white shadow-2xl sm:border-[8px]">
                <img src="https://images.unsplash.com/photo-1539109136881-3be0616acf4b?auto=format&fit=crop&w=900&q=85"
                    alt="Elegant fashion collection" class="about-main-image aspect-[3/4] w-full object-cover" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent"></div>
                <div class="absolute bottom-4 left-4 text-white sm:bottom-6 sm:left-6">
                    <p class="text-[9px] font-semibold uppercase tracking-[0.3em] sm:text-[10px]">The Art of Dressing</p>
                    <p class="mt-0.5 font-serif text-xl italic sm:text-2xl">Made for you.</p>
                </div>
            </div>

            <!-- FLOATING SECOND IMAGE -->
            <div class="absolute -right-1 top-[12%] z-20 w-[42%] rotate-6 overflow-hidden rounded-xl border-[4px] border-white shadow-xl transition duration-500 hover:rotate-0 sm:-right-2 sm:border-[6px]">
                <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=600&q=85"
                    alt="Contemporary fashion inspiration" class="aspect-[4/5] w-full object-cover" loading="lazy">
            </div>

            <!-- GLASS MESSAGE -->
            <div class="absolute -bottom-4 right-0 z-30 w-[62%] rounded-xl border border-white/80 bg-white/75 p-3 shadow-xl backdrop-blur-xl sm:-bottom-5 sm:p-4">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-[#a13d91] text-base text-white">✦</div>
                    <div>
                        <p class="font-serif text-base text-[#84366f] sm:text-lg">Your Style.</p>
                        <p class="text-[10px] text-neutral-600 sm:text-[11px]">Your Story. Your Confidence.</p>
                    </div>
                </div>
            </div>

            <!-- DECORATIVE OUTLINE -->
            <div class="absolute -bottom-7 left-[10%] h-24 w-24 rounded-full border border-[#a13d91]/30 sm:h-32 sm:w-32"></div>
        </div>

        <!-- RIGHT: ABOUT CONTENT -->
        <div class="pt-3 lg:pt-0">

            <!-- EYEBROW -->
            <div class="mb-3 flex items-center gap-2">
                <span class="h-px w-8 bg-[#a13d91]"></span>
                <span class="text-[9px] font-bold uppercase tracking-[0.25em] text-[#84366f] sm:text-[10px]">Welcome to SHINISHA</span>
            </div>

            <!-- HEADING -->
            <h2 class="max-w-lg font-serif text-3xl font-medium leading-[1.15] tracking-tight text-neutral-900 sm:text-4xl lg:text-5xl">
                Fashion that <span class="italic text-[#a13d91]">feels like you.</span>
            </h2>

            <!-- DESCRIPTION -->
            <p class="mt-4 max-w-lg text-sm leading-6 text-neutral-600 sm:text-[15px] sm:leading-7">
                At SHINISHA, we believe fashion is more than what you wear. It is how you express yourself, celebrate your individuality, and feel confident in every moment.
            </p>
            <p class="mt-2.5 max-w-lg text-sm leading-6 text-neutral-600 sm:text-[15px] sm:leading-7">
                From everyday essentials to outfits for special occasions, explore styles that bring together elegance, comfort, and a touch of something uniquely yours.
            </p>

            <!-- VALUES -->
            <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2">
                <div class="group rounded-lg border border-neutral-200/80 bg-white/70 p-3 transition duration-300 hover:-translate-y-1 hover:border-[#a13d91]/40 hover:shadow-lg">
                    <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#a13d91]/10 text-base text-[#a13d91] transition group-hover:bg-[#a13d91] group-hover:text-white">✧</div>
                    <h3 class="text-[13px] font-bold text-neutral-900">Style with Purpose</h3>
                    <p class="mt-1 text-[11px] leading-5 text-neutral-600">Discover thoughtfully presented styles for your everyday wardrobe.</p>
                </div>
                <div class="group rounded-lg border border-neutral-200/80 bg-white/70 p-3 transition duration-300 hover:-translate-y-1 hover:border-[#a13d91]/40 hover:shadow-lg">
                    <div class="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-[#a13d91]/10 text-base text-[#a13d91] transition group-hover:bg-[#a13d91] group-hover:text-white">♡</div>
                    <h3 class="text-[13px] font-bold text-neutral-900">Made to Feel Good</h3>
                    <p class="mt-1 text-[11px] leading-5 text-neutral-600">Find looks that help you feel comfortable, expressive, and confident.</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="mt-5 flex flex-wrap items-center gap-3">
                <a href="#new-arrivals"
                    class="group inline-flex items-center gap-3 rounded-full bg-[#84366f] px-5 py-3 text-[10px] font-bold uppercase tracking-[0.15em] text-white transition hover:bg-neutral-900 sm:px-6 sm:text-[11px]">
                    Discover Our Collection
                    <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
                </a>
                <span class="text-[11px] italic text-neutral-500">Wear what makes you feel beautiful.</span>
            </div>

            <!-- BOTTOM DECORATIVE TEXT -->
            <div class="mt-6 border-t border-neutral-200 pt-3">
                <p class="font-serif text-base italic text-neutral-400 sm:text-lg">“Your style is your signature.”</p>
            </div>
        </div>
    </div>
</section>

<style>
    /* ==========================================
       ABOUT SECTION ANIMATIONS
       ========================================== */
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

    @media (prefers-reduced-motion: reduce) {

        .about-shinisha *,
        .about-shinisha *::before,
        .about-shinisha *::after {
            animation: none !important;
            transition: none !important;
        }
    }
</style>