    <!-- ================================================ -->
    <!-- LATEST COLLECTION + QUICK VIEW                   -->
    <!-- ================================================ -->
    <section id="latest-collection" class="bg-white px-3 py-8 sm:px-6 sm:py-10 lg:px-10">
        <div class="mx-auto mb-7 max-w-[1400px] text-center sm:mb-9">
            <h2 class="text-xl font-normal tracking-[3px] text-[#b43b9b] sm:text-2xl">LATEST COLLECTION</h2>
            <div class="mx-auto mt-3 h-[2px] w-12 bg-[#ed4e83]"></div>
        </div>
        <div id="productGrid"
            class="mx-auto grid max-w-[1400px] grid-cols-2 gap-x-3 gap-y-7 sm:gap-x-5 sm:gap-y-9 lg:grid-cols-4 lg:gap-x-6">
        </div>
    </section>

    <!-- QUICK VIEW POPUP -->
    <div id="quickViewModal"
        class="fixed inset-0 z-[200] hidden items-center justify-center overflow-y-auto bg-black/50 p-2 sm:p-5">
        <div class="relative my-auto w-full max-w-5xl bg-white shadow-2xl">
            <button id="closeQuickView" aria-label="Close quick view"
                class="absolute right-2 top-2 z-30 flex h-9 w-9 items-center justify-center bg-white text-2xl text-neutral-800">
                &times;
            </button>
            <div class="grid grid-cols-1 gap-4 p-3 sm:p-5 md:grid-cols-2 md:gap-6">
                <div class="flex min-w-0 gap-2 sm:gap-3">
                    <div id="quickViewThumbnails" class="flex w-12 shrink-0 flex-col gap-2 sm:w-16"></div>
                    <div class="relative min-w-0 flex-1 self-start overflow-hidden bg-neutral-100">
                        <img id="quickViewImage" src="" alt="Product" class="aspect-[3/4] w-full object-cover">
                        <span class="absolute right-0 top-0 bg-[#f45d79] px-2 py-1 text-[9px] font-medium text-white">
                            SALE
                        </span>
                    </div>
                </div>
                <div class="min-w-0 pt-1">
                    <h2 id="quickViewTitle"
                        class="pr-7 text-sm font-medium uppercase leading-relaxed tracking-[1px] sm:text-base"></h2>
                    <div
                        class="mt-3 flex flex-wrap items-center justify-center gap-2 bg-[#a75b29] px-2 py-2 text-[9px] font-semibold text-white">
                        <span>FESTIVE SALE</span>
                        <span class="bg-yellow-300 px-2 py-1 text-neutral-900">SPECIAL OFFER</span>
                    </div>
                    <div class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                        <span id="quickViewOriginalPrice" class="text-neutral-400 line-through"></span>
                        <span id="quickViewPrice" class="font-semibold text-neutral-900"></span>
                        <span id="quickViewSavings" class="text-[10px] font-medium text-[#ed6682]"></span>
                    </div>
                    <p class="mt-1 text-[9px] text-neutral-500">Inclusive of all applicable taxes.</p>
                    <div class="mt-4">
                        <p class="mb-2 text-[9px] uppercase tracking-[1.5px] text-neutral-500">Available Styles</p>
                        <div id="quickViewVariants" class="flex flex-wrap gap-2"></div>
                    </div>
                    <div class="mt-4">
                        <div class="mb-2 flex items-center justify-between">
                            <p class="text-[9px] uppercase tracking-[1.5px] text-neutral-500">Select Size</p>
                            <button id="openSizeGuide" class="text-[9px] underline">Size Guide</button>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button data-size="S"
                                class="qv-size border border-neutral-300 px-3 py-2 text-[10px]">S</button>
                            <button data-size="M"
                                class="qv-size border border-neutral-300 px-3 py-2 text-[10px]">M</button>
                            <button data-size="L"
                                class="qv-size border border-neutral-300 px-3 py-2 text-[10px]">L</button>
                            <button data-size="XL"
                                class="qv-size border border-neutral-300 px-3 py-2 text-[10px]">XL</button>
                            <button data-size="XXL"
                                class="qv-size border border-neutral-300 px-3 py-2 text-[10px]">XXL</button>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="mb-2 text-[9px] uppercase tracking-[1.5px] text-neutral-500">Quantity</p>
                        <div class="flex h-9 w-24 items-center justify-between border border-neutral-300 px-3">
                            <button id="decreaseQuantity" aria-label="Decrease quantity">−</button>
                            <span id="quickViewQuantity" class="text-xs">1</span>
                            <button id="increaseQuantity" aria-label="Increase quantity">+</button>
                        </div>
                    </div>
                    <button id="quickViewAddButton"
                        class="mt-4 w-full border border-neutral-900 px-4 py-3 text-[10px] font-semibold tracking-[2px] transition hover:bg-neutral-900 hover:text-white">
                        ADD TO CART
                    </button>
                    <div id="productAccordions" class="mt-5 divide-y divide-neutral-200 border-y border-neutral-200">
                        <!-- DESCRIPTION -->
                        <div class="accordion-item">
                            <button type="button"
                                class="accordion-trigger flex w-full items-center justify-between py-3 text-left text-[10px] font-medium uppercase tracking-[1.3px]"
                                aria-expanded="false">
                                DESCRIPTION
                                <svg class="accordion-icon h-3 w-3 transition-transform duration-300"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>
                            <div class="accordion-panel grid transition-[grid-template-rows,opacity] duration-300 ease-in-out"
                                style="grid-template-rows:0fr;opacity:0">
                                <div class="overflow-hidden">
                                    <div class="pb-4 text-[10px] leading-6 text-neutral-600">
                                        <p id="quickViewDescription"></p>
                                        <p class="mt-2">Please check the selected product details before ordering.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- SHIPPING -->
                        <div class="accordion-item">
                            <button type="button"
                                class="accordion-trigger flex w-full items-center justify-between py-3 text-left text-[10px] font-medium uppercase tracking-[1.3px]"
                                aria-expanded="false">
                                SHIPPING INFORMATION
                                <svg class="accordion-icon h-3 w-3 transition-transform duration-300"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>
                            <div class="accordion-panel grid transition-[grid-template-rows,opacity] duration-300 ease-in-out"
                                style="grid-template-rows:0fr;opacity:0">
                                <div class="overflow-hidden">
                                    <div class="pb-4 text-[10px] leading-6 text-neutral-600">
                                        Shipping charges and estimated delivery times depend on your location. Check the
                                        final details during checkout.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ASK A QUESTION -->
                        <div class="accordion-item">
                            <button type="button"
                                class="accordion-trigger flex w-full items-center justify-between py-3 text-left text-[10px] font-medium uppercase tracking-[1.3px]"
                                aria-expanded="false">
                                ASK A QUESTION
                                <svg class="accordion-icon h-3 w-3 transition-transform duration-300"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>
                            <div class="accordion-panel grid transition-[grid-template-rows,opacity] duration-300 ease-in-out"
                                style="grid-template-rows:0fr;opacity:0">
                                <div class="overflow-hidden">
                                    <form id="questionForm" class="space-y-3 pb-4">
                                        <input id="qvQuestionName" type="text" placeholder="Your name" required
                                            class="w-full border border-neutral-200 px-3 py-2 text-xs outline-none">
                                        <input id="qvQuestionEmail" type="email" placeholder="Your email" required
                                            class="w-full border border-neutral-200 px-3 py-2 text-xs outline-none">
                                        <textarea id="qvQuestionText" rows="3" placeholder="Your question" required
                                            class="w-full border border-neutral-200 px-3 py-2 text-xs outline-none"></textarea>
                                        <button type="submit"
                                            class="border border-neutral-900 px-4 py-2 text-[9px] uppercase hover:bg-neutral-900 hover:text-white">
                                            Submit Question
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- RETURNS -->
                        <div class="accordion-item">
                            <button type="button"
                                class="accordion-trigger flex w-full items-center justify-between py-3 text-left text-[10px] font-medium uppercase tracking-[1.3px]"
                                aria-expanded="false">
                                RETURNS & EXCHANGES
                                <svg class="accordion-icon h-3 w-3 transition-transform duration-300"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </button>
                            <div class="accordion-panel grid transition-[grid-template-rows,opacity] duration-300 ease-in-out"
                                style="grid-template-rows:0fr;opacity:0">
                                <div class="overflow-hidden">
                                    <div class="pb-4 text-[10px] leading-6 text-neutral-600">
                                        Please refer to your store's return and exchange policy for eligibility and
                                        applicable conditions.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SIZE GUIDE POPUP -->
    <div id="quickViewSizeGuide" class="fixed inset-0 z-[220] hidden items-center justify-center bg-black/50 p-4">
        <div class="relative w-full max-w-sm bg-white p-5">
            <button id="closeSizeGuide" class="absolute right-3 top-2 text-2xl">&times;</button>
            <h3 class="mb-4 text-lg font-medium">Size Guide</h3>
            <table class="w-full border-collapse text-center text-xs">
                <thead>
                    <tr class="bg-neutral-100">
                        <th class="border p-2">Size</th>
                        <th class="border p-2">Bust</th>
                        <th class="border p-2">Waist</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border p-2">S</td>
                        <td class="border p-2">34"</td>
                        <td class="border p-2">28"</td>
                    </tr>
                    <tr>
                        <td class="border p-2">M</td>
                        <td class="border p-2">36"</td>
                        <td class="border p-2">30"</td>
                    </tr>
                    <tr>
                        <td class="border p-2">L</td>
                        <td class="border p-2">38"</td>
                        <td class="border p-2">32"</td>
                    </tr>
                    <tr>
                        <td class="border p-2">XL</td>
                        <td class="border p-2">40"</td>
                        <td class="border p-2">34"</td>
                    </tr>
                    <tr>
                        <td class="border p-2">XXL</td>
                        <td class="border p-2">42"</td>
                        <td class="border p-2">36"</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- NOTIFICATION -->
    <div id="cartToast" role="status" aria-live="polite"
        class="fixed bottom-5 left-1/2 z-[300] hidden -translate-x-1/2 bg-neutral-900 px-5 py-3 text-xs text-white shadow-lg">
    </div>

    <!-- JAVASCRIPT (latest collection) -->
    <script>
        const products = [{
                name: 'Purple Cotton Blend Embroidered Festive Kurta Set',
                price: 1599.20,
                original: 1999,
                discount: 20,
                description: 'A beautiful festive kurta set with an elegant design. Perfect for celebrations and special occasions.',
                images: [
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=800&q=85',
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e4?auto=format&fit=crop&w=800&q=85',
                    'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=800&q=85'
                ]
            },
            {
                name: 'Rust Cotton Blend Embroidered Festive Kurta Set',
                price: 1599.20,
                original: 1999,
                discount: 20,
                description: 'A stylish festive outfit suitable for family gatherings and special occasions.',
                images: [
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e4?auto=format&fit=crop&w=800&q=85',
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=800&q=85',
                    'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=800&q=85'
                ]
            },
            {
                name: 'Blue Cotton Printed Office Wear Kurti',
                price: 639.20,
                original: 799,
                discount: 20,
                description: 'A versatile printed kurti for office wear and everyday styling.',
                images: [
                    'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=800&q=85',
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=800&q=85',
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e4?auto=format&fit=crop&w=800&q=85'
                ]
            },
            {
                name: 'Red Cotton Printed Everyday Kurti',
                price: 639.20,
                original: 799,
                discount: 20,
                description: 'A comfortable printed kurti designed for everyday wear.',
                images: [
                    'https://images.unsplash.com/photo-1610030469983-98e550d6193c?auto=format&fit=crop&w=800&q=85',
                    'https://images.unsplash.com/photo-1583391733956-6c78276477e4?auto=format&fit=crop&w=800&q=85',
                    'https://images.unsplash.com/photo-1617627143750-d86bc21e42bb?auto=format&fit=crop&w=800&q=85'
                ]
            }
        ];

        let selectedProduct = null;
        let selectedSize = '';
        let selectedQuantity = 1;
        let selectedImageIndex = 0;
        let cart = [];
        let toastTimer;

        function money(value) {
            return '₹' + Number(value).toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function showToast(message) {
            const toast = document.getElementById('cartToast');
            toast.textContent = message;
            toast.classList.remove('hidden');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast.classList.add('hidden'), 2500);
        }

        function renderProducts() {
            document.getElementById('productGrid').innerHTML = products.map((product, index) => `
                <article class="group min-w-0">
                    <div class="relative overflow-hidden bg-neutral-100">
                        <button type="button" data-quick-view="${index}" class="block w-full text-left">
                            <img src="${product.images[0]}" alt="${product.name}" loading="lazy"
                                class="aspect-[3/4] w-full object-cover transition-transform duration-500 group-hover:scale-[1.03]">
                        </button>
                        <span class="absolute right-0 top-0 bg-[#f45d79] px-2 py-1 text-[9px] font-medium text-white">
                            FLAT ${product.discount}% OFF
                        </span>
                        <div class="absolute bottom-2 left-1 z-10 flex flex-col gap-0.5">
                            ${['S', 'M', 'L', 'XL', 'XXL'].map(size => `
                                <button type="button" data-card-size="${size}" aria-label="Select size ${size}"
                                    class="card-size flex h-[18px] w-[27px] items-center justify-center rounded-[3px] border border-neutral-300 bg-white/95 text-[9px] leading-none text-neutral-700 transition hover:border-[#f45d79] hover:bg-[#f45d79] hover:text-white">
                                    ${size}
                                </button>
                            `).join('')}
                        </div>
                        <button type="button" data-quick-view="${index}"
                            class="absolute bottom-2 left-9 right-2 bg-[#f45d79] py-2 text-[10px] font-medium text-white transition hover:bg-[#e84caa] sm:text-xs">
                            Quick view
                        </button>
                    </div>
                    <h3 class="mt-2 min-h-[34px] text-[11px] leading-4 sm:text-[13px]">${product.name}</h3>
                    <div class="mt-1 flex flex-wrap items-center gap-2 text-[10px] sm:text-xs">
                        <span class="text-neutral-400 line-through">${money(product.original)}</span>
                        <span class="font-medium">${money(product.price)}</span>
                    </div>
                    <p class="mt-1 text-[10px] font-medium text-[#f45d79]">Save ${product.discount}%</p>
                    <button type="button" data-quick-view="${index}"
                        class="mt-3 flex min-h-9 w-full items-center justify-center border border-[#e84caa] bg-white px-2 py-2 text-[10px] font-semibold transition hover:bg-[#e84caa] hover:text-white sm:text-xs">
                        ADD TO CART
                    </button>
                </article>
            `).join('');
        }

        renderProducts();

        document.getElementById('productGrid').addEventListener('click', event => {
            const sizeButton = event.target.closest('[data-card-size]');
            if (!sizeButton) return;
            event.preventDefault();
            event.stopPropagation();
            const card = sizeButton.closest('article');
            card.querySelectorAll('[data-card-size]').forEach(button => {
                button.classList.remove('!border-[#f45d79]', '!bg-[#f45d79]', '!text-white');
            });
            sizeButton.classList.add('!border-[#f45d79]', '!bg-[#f45d79]', '!text-white');
            card.dataset.selectedSize = sizeButton.dataset.cardSize;
        });

        function openQuickView(index) {
            selectedProduct = products[index];
            selectedSize = '';
            selectedQuantity = 1;
            selectedImageIndex = 0;
            document.getElementById('quickViewTitle').textContent = selectedProduct.name;
            document.getElementById('quickViewOriginalPrice').textContent = money(selectedProduct.original);
            document.getElementById('quickViewPrice').textContent = money(selectedProduct.price);
            document.getElementById('quickViewSavings').textContent = 'SAVE ' + selectedProduct.discount + '%';
            document.getElementById('quickViewDescription').textContent = selectedProduct.description;
            document.getElementById('quickViewQuantity').textContent = '1';
            document.querySelectorAll('.qv-size').forEach(button => {
                button.classList.remove('border-pink-500', 'bg-pink-500', 'text-white');
                button.classList.add('border-neutral-300');
            });
            document.querySelectorAll('.accordion-item').forEach(item => setAccordion(item, false));
            renderGallery();
            renderVariants();
            const modal = document.getElementById('quickViewModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeQuickView() {
            const modal = document.getElementById('quickViewModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        document.getElementById('closeQuickView').addEventListener('click', closeQuickView);
        document.getElementById('productGrid').addEventListener('click', event => {
            const button = event.target.closest('[data-quick-view]');
            if (!button) return;
            openQuickView(Number(button.dataset.quickView));
        });

        function renderGallery() {
            const container = document.getElementById('quickViewThumbnails');
            container.replaceChildren();
            selectedProduct.images.forEach((src, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'overflow-hidden border-2 ' + (index === selectedImageIndex ? 'border-pink-500' : 'border-transparent');
                const image = document.createElement('img');
                image.src = src;
                image.alt = 'Product image ' + (index + 1);
                image.loading = 'lazy';
                image.className = 'aspect-[3/4] w-full object-cover';
                button.appendChild(image);
                button.addEventListener('click', () => {
                    selectedImageIndex = index;
                    renderGallery();
                    renderVariants();
                });
                container.appendChild(button);
            });
            document.getElementById('quickViewImage').src = selectedProduct.images[selectedImageIndex];
        }

        function renderVariants() {
            const container = document.getElementById('quickViewVariants');
            container.replaceChildren();
            selectedProduct.images.forEach((src, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'w-10 overflow-hidden border-2 ' + (index === selectedImageIndex ? 'border-pink-500' : 'border-neutral-300');
                const image = document.createElement('img');
                image.src = src;
                image.alt = 'Style ' + (index + 1);
                image.className = 'aspect-[3/4] w-full object-cover';
                button.appendChild(image);
                button.addEventListener('click', () => {
                    selectedImageIndex = index;
                    renderGallery();
                    renderVariants();
                });
                container.appendChild(button);
            });
        }

        function setAccordion(item, open) {
            const trigger = item.querySelector('.accordion-trigger');
            const panel = item.querySelector('.accordion-panel');
            const icon = item.querySelector('.accordion-icon');
            trigger.setAttribute('aria-expanded', String(open));
            panel.style.gridTemplateRows = open ? '1fr' : '0fr';
            panel.style.opacity = open ? '1' : '0';
            icon.classList.toggle('rotate-180', open);
        }

        document.querySelectorAll('.accordion-item').forEach(item => {
            item.querySelector('.accordion-trigger').addEventListener('click', () => {
                const isOpen = item.querySelector('.accordion-trigger').getAttribute('aria-expanded') === 'true';
                document.querySelectorAll('.accordion-item').forEach(other => setAccordion(other, false));
                if (!isOpen) setAccordion(item, true);
            });
        });

        document.querySelectorAll('.qv-size').forEach(button => {
            button.addEventListener('click', () => {
                selectedSize = button.dataset.size;
                document.querySelectorAll('.qv-size').forEach(other => {
                    other.classList.remove('border-pink-500', 'bg-pink-500', 'text-white');
                    other.classList.add('border-neutral-300');
                });
                button.classList.remove('border-neutral-300');
                button.classList.add('border-pink-500', 'bg-pink-500', 'text-white');
            });
        });

        document.getElementById('decreaseQuantity').addEventListener('click', () => {
            selectedQuantity = Math.max(1, selectedQuantity - 1);
            document.getElementById('quickViewQuantity').textContent = selectedQuantity;
        });

        document.getElementById('increaseQuantity').addEventListener('click', () => {
            selectedQuantity++;
            document.getElementById('quickViewQuantity').textContent = selectedQuantity;
        });

        document.getElementById('quickViewAddButton').addEventListener('click', () => {
            if (!selectedProduct) return;
            if (!selectedSize) {
                showToast('Please select a size first');
                return;
            }
            const existing = cart.find(item => item.name === selectedProduct.name && item.size === selectedSize);
            if (existing) {
                existing.quantity += selectedQuantity;
            } else {
                cart.push({
                    name: selectedProduct.name,
                    price: selectedProduct.price,
                    size: selectedSize,
                    quantity: selectedQuantity
                });
            }
            console.log('Demo cart:', cart);
            showToast('Product added to cart');
            closeQuickView();
        });

        const sizeGuide = document.getElementById('quickViewSizeGuide');
        document.getElementById('openSizeGuide').addEventListener('click', () => {
            sizeGuide.classList.remove('hidden');
            sizeGuide.classList.add('flex');
        });

        function closeSizeGuide() {
            sizeGuide.classList.add('hidden');
            sizeGuide.classList.remove('flex');
        }
        document.getElementById('closeSizeGuide').addEventListener('click', closeSizeGuide);
        sizeGuide.addEventListener('click', event => {
            if (event.target === sizeGuide) closeSizeGuide();
        });

        document.getElementById('questionForm').addEventListener('submit', event => {
            event.preventDefault();
            showToast('Connect this form to your backend to submit');
        });

        document.addEventListener('keydown', event => {
            if (event.key === 'Escape') {
                closeQuickView();
                closeSizeGuide();
            }
        });
        document.getElementById('quickViewModal').addEventListener('click', event => {
            if (event.target.id === 'quickViewModal') closeQuickView();
        });
    </script>