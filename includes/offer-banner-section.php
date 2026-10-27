    <!-- ================================================ -->
    <!-- SIMPLE BANNER SLIDER — IMAGE ONLY, NO BG         -->
    <!-- ================================================ -->
    <section id="simpleBannerSection" class="sbs-section">
        <div class="sbs-frame">
            <div class="sbs-track" id="sbsTrack">
                <div class="sbs-slide">
                    <img src="https://divenaworld.com/cdn/shop/files/Karwachauth.jpg?v=1792492627&width=1920"
                        alt="Banner 1" draggable="false">
                </div>
                <div class="sbs-slide">
                    <img src="https://shinisha.in/cdn/shop/files/Shinisha_Web_Slider_-.jpg_1.jpg?v=1792669202&width=1920"
                        alt="Banner 2" draggable="false">
                </div>
                <div class="sbs-slide">
                    <img src="https://shinisha.in/cdn/shop/files/231026-Shinisha-banner.png?v=1792759792&width=1920"
                        alt="Banner 3" draggable="false">
                </div>
            </div>

            <button class="sbs-arrow sbs-prev" id="sbsPrev" aria-label="Previous">&#10094;</button>
            <button class="sbs-arrow sbs-next" id="sbsNext" aria-label="Next">&#10095;</button>

            <div class="sbs-dots" id="sbsDots"></div>
        </div>
    </section>

    <style>
        /* ============ SECTION ============ */
        .sbs-section {
            padding: 20px 16px;
            background: #ffffff;
        }

        /* ============ FRAME ============ */
        .sbs-frame {
            position: relative;
            max-width: 1280px;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 10px;
        }

        /* ============ TRACK ============ */
        .sbs-track {
            display: flex;
            width: 100%;
            transition: transform .7s cubic-bezier(.22, .61, .36, 1);
            will-change: transform;
        }

        /* ============ SLIDE ============ */
        .sbs-slide {
            flex: 0 0 100%;
            width: 100%;
            aspect-ratio: 16 / 5;
            overflow: hidden;
        }

        .sbs-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            user-select: none;
            -webkit-user-drag: none;
        }

        /* ============ ARROWS ============ */
        .sbs-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #dddddd;
            border-radius: 50%;
            background: #ffffff;
            color: #333333;
            font-size: 14px;
            line-height: 1;
            cursor: pointer;
            transition: background .2s, color .2s;
        }

        .sbs-arrow:hover {
            background: #84366f;
            color: #ffffff;
            border-color: #84366f;
        }

        .sbs-prev {
            left: 12px;
        }

        .sbs-next {
            right: 12px;
        }

        /* ============ DOTS ============ */
        .sbs-dots {
            position: absolute;
            left: 50%;
            bottom: 14px;
            transform: translateX(-50%);
            z-index: 10;
            display: flex;
            gap: 8px;
        }

        .sbs-dot {
            width: 9px;
            height: 9px;
            padding: 0;
            border: 1px solid #ffffff;
            border-radius: 50%;
            background: rgba(0, 0, 0, .30);
            cursor: pointer;
            transition: background .25s, transform .25s;
        }

        .sbs-dot.is-active {
            background: #84366f;
            border-color: #84366f;
            transform: scale(1.15);
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 900px) {
            .sbs-section {
                padding: 14px 10px;
            }

            .sbs-slide {
                aspect-ratio: 2 / 1;
            }

            .sbs-arrow {
                width: 34px;
                height: 34px;
                font-size: 12px;
            }

            .sbs-prev {
                left: 8px;
            }

            .sbs-next {
                right: 8px;
            }

            .sbs-dots {
                bottom: 10px;
            }
        }

        @media (max-width: 520px) {
            .sbs-slide {
                aspect-ratio: 1 / 1;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .sbs-track {
                transition: none;
            }
        }
    </style>

    <script>
        (function() {
            const track = document.getElementById('sbsTrack');
            const slides = Array.from(track.children);
            const prevBtn = document.getElementById('sbsPrev');
            const nextBtn = document.getElementById('sbsNext');
            const dotsWrap = document.getElementById('sbsDots');

            const AUTOPLAY_MS = 5500;
            let index = 0;
            let timer = null;

            /* BUILD DOTS */
            slides.forEach((_, i) => {
                const dot = document.createElement('button');
                dot.className = 'sbs-dot';
                dot.type = 'button';
                dot.setAttribute('aria-label', 'Go to banner ' + (i + 1));
                dot.addEventListener('click', () => {
                    goTo(i);
                    restartAutoplay();
                });
                dotsWrap.appendChild(dot);
            });

            const dots = Array.from(dotsWrap.children);

            /* CORE */
            function goTo(i) {
                index = (i + slides.length) % slides.length;
                track.style.transform = 'translateX(-' + (index * 100) + '%)';
                dots.forEach((dot, d) => {
                    dot.classList.toggle('is-active', d === index);
                });
            }

            function next() {
                goTo(index + 1);
            }

            function prev() {
                goTo(index - 1);
            }

            /* AUTOPLAY */
            function startAutoplay() {
                stopAutoplay();
                timer = setInterval(() => {
                    if (!document.hidden) next();
                }, AUTOPLAY_MS);
            }

            function stopAutoplay() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            function restartAutoplay() {
                stopAutoplay();
                startAutoplay();
            }

            /* CONTROLS */
            nextBtn.addEventListener('click', () => {
                next();
                restartAutoplay();
            });
            prevBtn.addEventListener('click', () => {
                prev();
                restartAutoplay();
            });

            /* SWIPE */
            let startX = 0,
                startY = 0,
                swiping = false;

            track.addEventListener('touchstart', (e) => {
                startX = e.changedTouches[0].screenX;
                startY = e.changedTouches[0].screenY;
                swiping = true;
            }, {
                passive: true
            });

            track.addEventListener('touchend', (e) => {
                if (!swiping) return;
                swiping = false;
                const dx = e.changedTouches[0].screenX - startX;
                const dy = e.changedTouches[0].screenY - startY;
                if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy)) {
                    dx < 0 ? next() : prev();
                    restartAutoplay();
                }
            }, {
                passive: true
            });

            /* PAUSE WHEN TAB HIDDEN */
            document.addEventListener('visibilitychange', () => {
                document.hidden ? stopAutoplay() : startAutoplay();
            });

            /* INIT */
            goTo(0);
            startAutoplay();
        })();
    </script>
