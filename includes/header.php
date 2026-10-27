    <!-- FESTIVE ANNOUNCEMENT -->
    <div
        class="flex h-5 items-center justify-center bg-[#a13d91] px-2 text-center text-[7px] font-bold tracking-[.6px] text-white sm:h-[30px] sm:text-[10px] sm:tracking-[1.5px]">
        FESTIVE SALE IS LIVE | FLAT 20% OFF + EXTRA 10% OFF ON PREPAID ORDERS.
    </div>

    <!-- HEADER -->
    <header class="relative z-30 grid h-12 grid-cols-3 items-center bg-white px-3 sm:h-[68px] sm:px-[5%]">
        <div class="flex items-center gap-3 sm:gap-6">
            <button onclick="openMenu()" aria-label="Open navigation menu"
                class="flex items-center justify-center lg:hidden">
                <svg class="h-[19px] w-[19px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M3 7h18M3 12h18M3 17h18" />
                </svg>
            </button>
            <button onclick="openSearch()" aria-label="Search" class="flex items-center justify-center">
                <svg class="h-[17px] w-[17px] sm:h-[21px] sm:w-[21px]" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.6">
                    <circle cx="10.8" cy="10.8" r="6.8" />
                    <path d="m16 16 5 5" />
                </svg>
            </button>
        </div>
        <a href="#"
            class="whitespace-nowrap text-center font-['Playfair_Display'] text-[19px] tracking-[.5px] text-[#84366f] sm:text-[30px]">
            SHINISHA
        </a>
        <div class="flex items-center justify-end gap-3 sm:gap-5">
            <button onclick="openMenu()" aria-label="Account" class="flex items-center justify-center">
                <svg class="h-[17px] w-[17px] sm:h-[21px] sm:w-[21px]" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <circle cx="12" cy="8" r="3.5" />
                    <path d="M4.5 20c0-4.1 3-6.5 7.5-6.5s7.5 2.4 7.5 6.5" />
                </svg>
            </button>
            <button onclick="openMenu()" aria-label="Shopping bag" class="flex items-center justify-center">
                <svg class="h-[18px] w-[18px] sm:h-[22px] sm:w-[22px]" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5">
                    <path d="M5 8h14l1.2 13H3.8L5 8Z" />
                    <path d="M9 9V6a3 3 0 0 1 6 0v3" />
                </svg>
            </button>
        </div>
    </header>

    <!-- DESKTOP NAVIGATION -->
    <nav
        class="relative z-20 hidden min-h-[34px] items-center justify-center gap-5 bg-white px-2 pb-3 lg:flex xl:gap-7">
        <a href="#new-arrivals" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">NEW ARRIVALS</a>
        <a href="#co-ord-sets" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">CO-ORD SETS</a>
        <a href="#kurta-sets" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">KURTA SET</a>
        <a href="#dresses" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">DRESSES</a>
        <a href="#kurtis" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">KURTIS</a>
        <a href="#plus-size" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">PLUS SIZE</a>
        <a href="#best-seller" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">BEST SELLER</a>
        <a href="#reviews" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">REVIEWS</a>
        <a href="#track-order" class="text-[10px] tracking-[.7px] hover:text-[#a13d91]">TRACK ORDER</a>
    </nav>

    <!-- HERO SLIDER -->
    <section class="relative w-full overflow-hidden bg-transparent">
        <div id="sliderTrack" class="flex w-full transition-transform duration-700 ease-in-out">
            <a href="#new-arrivals"
                class="relative block aspect-[2/1] w-full shrink-0 overflow-hidden bg-transparent sm:aspect-[16/5] lg:aspect-[16/5]">
                <img src="https://divenaworld.com/cdn/shop/files/Karwachauth.jpg?v=1792492627&width=1920"
                    alt="Festive fashion collection" fetchpriority="high"
                    class="absolute inset-0 h-full w-full object-contain object-center">
            </a>
            <a href="#co-ord-sets"
                class="relative block aspect-[2/1] w-full shrink-0 overflow-hidden bg-transparent sm:aspect-[16/5] lg:aspect-[16/5]">
                <img src="https://shinisha.in/cdn/shop/files/Shinisha_Web_Slider_-.jpg_1.jpg?v=1792669202&width=1920"
                    alt="Women's festive fashion" loading="lazy"
                    class="absolute inset-0 h-full w-full object-contain object-center">
            </a>
            <a href="#best-seller"
                class="relative block aspect-[2/1] w-full shrink-0 overflow-hidden bg-transparent sm:aspect-[16/5] lg:aspect-[16/5]">
                <img src="https://shinisha.in/cdn/shop/files/231026-Shinisha-banner.png?v=1792759792&width=1920"
                    alt="Women's fashion collection" loading="lazy"
                    class="absolute inset-0 h-full w-full object-contain object-center">
            </a>
        </div>
        <div class="absolute bottom-0 left-0 z-10 h-[2px] w-full bg-neutral-200 sm:h-[3px]">
            <div id="progressFill" class="h-full w-0 bg-[#a13d91]"></div>
        </div>
    </section>

    <!-- SEARCH POPUP -->
    <div id="searchOverlay"
        class="invisible fixed inset-0 z-[100] bg-black/40 opacity-0 transition-opacity duration-300">
        <div id="searchPanel"
            class="absolute left-0 top-0 w-full -translate-y-full bg-white px-5 pb-6 pt-5 shadow-xl transition-transform duration-500 ease-out sm:px-[6%]">
            <div class="mb-4 flex items-center justify-between">
                <span class="font-['Playfair_Display'] text-2xl text-[#84366f]">SHINISHA</span>
                <button onclick="closeSearch()" aria-label="Close search"
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 text-2xl">
                    &times;
                </button>
            </div>
            <form id="searchForm" class="flex items-center gap-3 border-b border-neutral-300">
                <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="10.8" cy="10.8" r="6.8" />
                    <path d="m16 16 5 5" />
                </svg>
                <input id="searchInput" type="search" placeholder="Search products..." aria-label="Search products"
                    class="w-full border-0 bg-transparent py-3 text-sm outline-none">
            </form>
        </div>
    </div>

    <!-- NAVIGATION POPUP -->
    <div id="menuOverlay" class="invisible fixed inset-0 z-[110] bg-black/40 opacity-0 transition-opacity duration-300">
        <div id="menuPanel"
            class="absolute bottom-0 left-0 max-h-[80vh] w-full translate-y-full overflow-y-auto rounded-t-2xl bg-white px-5 pb-7 pt-4 shadow-xl transition-transform duration-500 ease-out sm:px-[6%]">
            <div class="mx-auto mb-5 h-1 w-10 rounded-full bg-neutral-300"></div>
            <div class="mb-4 flex items-center justify-between">
                <span class="font-['Playfair_Display'] text-2xl text-[#84366f]">SHINISHA</span>
                <button onclick="closeMenu()" aria-label="Close navigation"
                    class="flex h-9 w-9 items-center justify-center rounded-full border border-neutral-200 text-2xl">
                    &times;
                </button>
            </div>
            <nav class="grid grid-cols-1 gap-x-8 sm:grid-cols-2 lg:grid-cols-3">
                <a href="#new-arrivals"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">NEW
                    ARRIVALS <span>↗</span></a>
                <a href="#co-ord-sets"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">CO-ORD
                    SETS <span>↗</span></a>
                <a href="#kurta-sets"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">KURTA
                    SET <span>↗</span></a>
                <a href="#dresses"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">DRESSES
                    <span>↗</span></a>
                <a href="#kurtis"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">KURTIS
                    <span>↗</span></a>
                <a href="#plus-size"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">PLUS
                    SIZE <span>↗</span></a>
                <a href="#best-seller"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">BEST
                    SELLER <span>↗</span></a>
                <a href="#reviews"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">REVIEWS
                    <span>↗</span></a>
                <a href="#track-order"
                    class="flex justify-between border-b border-neutral-100 py-3 text-xs tracking-wide hover:text-[#a13d91]">TRACK
                    ORDER <span>↗</span></a>
            </nav>
        </div>
    </div>

    <!-- JAVASCRIPT (popups + hero slider) -->
    <script>
        const searchOverlay = document.getElementById('searchOverlay');
        const searchPanel = document.getElementById('searchPanel');
        const menuOverlay = document.getElementById('menuOverlay');
        const menuPanel = document.getElementById('menuPanel');

        function openSearch() {
            closeMenu();
            searchOverlay.classList.remove('invisible');
            requestAnimationFrame(() => {
                searchOverlay.classList.remove('opacity-0');
                searchOverlay.classList.add('opacity-100');
                searchPanel.classList.remove('-translate-y-full');
                searchPanel.classList.add('translate-y-0');
            });
            document.body.classList.add('overflow-hidden');
            setTimeout(() => {
                document.getElementById('searchInput').focus();
            }, 350);
        }

        function closeSearch() {
            searchOverlay.classList.remove('opacity-100');
            searchOverlay.classList.add('opacity-0');
            searchPanel.classList.remove('translate-y-0');
            searchPanel.classList.add('-translate-y-full');
            setTimeout(() => {
                searchOverlay.classList.add('invisible');
                if (!menuOverlay.classList.contains('opacity-100')) {
                    document.body.classList.remove('overflow-hidden');
                }
            }, 500);
        }

        searchOverlay.addEventListener('click', event => {
            if (event.target === searchOverlay) closeSearch();
        });

        function openMenu() {
            closeSearch();
            menuOverlay.classList.remove('invisible');
            requestAnimationFrame(() => {
                menuOverlay.classList.remove('opacity-0');
                menuOverlay.classList.add('opacity-100');
                menuPanel.classList.remove('translate-y-full');
                menuPanel.classList.add('translate-y-0');
            });
            document.body.classList.add('overflow-hidden');
        }

        function closeMenu() {
            menuOverlay.classList.remove('opacity-100');
            menuOverlay.classList.add('opacity-0');
            menuPanel.classList.remove('translate-y-0');
            menuPanel.classList.add('translate-y-full');
            setTimeout(() => {
                menuOverlay.classList.add('invisible');
                if (!searchOverlay.classList.contains('opacity-100')) {
                    document.body.classList.remove('overflow-hidden');
                }
            }, 500);
        }

        menuOverlay.addEventListener('click', event => {
            if (event.target === menuOverlay) closeMenu();
        });

        document.querySelectorAll('#menuPanel a').forEach(link => {
            link.addEventListener('click', closeMenu);
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                closeSearch();
                closeMenu();
            }
        });

        document.getElementById('searchForm').addEventListener('submit', event => {
            event.preventDefault();
            const query = document.getElementById('searchInput').value.trim();
            if (query) window.location.href = '/search?q=' + encodeURIComponent(query);
        });

        /* HERO SLIDER */
        const track = document.getElementById('sliderTrack');
        const slides = document.querySelectorAll('#sliderTrack > a');
        const progressFill = document.getElementById('progressFill');
        const duration = 5500;
        let currentIndex = 0;
        let sliderTimer;

        function showSlide(index) {
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
            if (!document.hidden) {
                showSlide(currentIndex);
                sliderTimer = setInterval(() => showSlide(currentIndex + 1), duration);
            }
        }

        document.addEventListener('visibilitychange', () => {
            if (document.hidden) clearInterval(sliderTimer);
            else startSlider();
        });

        let startX = 0;
        const hero = document.querySelector('section');
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