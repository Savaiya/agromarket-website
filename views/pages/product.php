<!-- Breadcrumb Section Start -->
<div class="breadcrumb-wrapper bg-cover" style="background-image: url('assets/img/breadcrumb.png');">
    <div class="top-image">
        <img src="assets/img/breadcrumb-2.jpg" alt="img">
    </div>
    <div class="container">
        <div class="page-heading">
            <div class="breadcrumb-sub-title">
                <h1 class="text-white wow fadeInUp" data-wow-delay=".3s">Our Products</h1>
            </div>
            <ul class="breadcrumb-items wow fadeInUp" data-wow-delay=".5s">
                <li><a href="index.php"><i class="fa-solid fa-house"></i> Home</a></li>
                <li>:</li>
                <li>Our Products</li>
            </ul>
        </div>
    </div>
</div>

<!-- Products Section Start -->
<section class="products space section-padding fix">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <aside class="sidebar-area" style="background:#fdfdfd; padding:25px; border-radius:15px; border:1px solid #eee;">
                    <div class="widget">
                        <h3 class="widget_title" style="font-size:22px; margin-bottom:20px;">Product Filters</h3>
                        <form id="productFilters">
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="productSearch">Product Name</label>
                                <input type="search" id="productSearch" class="form-control" placeholder="Search products...">
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold" for="categoryFilter">Category</label>
                                <select id="categoryFilter" class="form-select">
                                    <option value="all">All Categories</option>
                                    <option value="seed">Seed</option>
                                    <option value="fertilizer">Fertilizer</option>
                                    <option value="pesticide">Pesticide</option>
                                    <option value="irrigation">Irrigation</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Price Range (₹)</label>
                                <div class="d-flex gap-2">
                                    <input type="number" id="minPrice" class="form-control" placeholder="Min">
                                    <input type="number" id="maxPrice" class="form-control" placeholder="Max">
                                </div>
                            </div>
                            <button type="button" class="btn theme-bg-2 w-100" id="clearFilters">Clear Filters</button>
                        </form>
                    </div>
                </aside>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="mb-0">Agriculture Products</h2>
                    <span class="text-muted" id="productCount">8 products</span>
                </div>
                <div id="productGrid" class="row g-4">
                    <div class="col-xl-4 col-md-6" data-category="seed" data-price="1450">
                        <article class="product-style1 h-100">
                            <div class="product-img" style="background:#f9f9f9; padding:20px; border-radius:10px;">
                                <a href="product-details.php"><img src="assets/img/inner-page/service/01.jpg" alt="Hybrid maize seed" style="width:100%; height:180px; object-fit:contain;"></a>
                            </div>
                            <div class="product-meta">Seed · Hybrid</div>
                            <div class="product-about">
                                <h2 class="product-title" style="font-size:16px; min-height:40px;"><a href="product-details.php">Hybrid Maize Seed</a></h2>
                                <span class="price" style="font-size:18px; color:#2ecc71;">₹1,450 / kg</span>
                            </div>
                            <div class="social-style"><ul><li><a class="main-icon" href="product-details.php"><i class="far fa-shopping-basket"></i></a></li></ul></div>
                        </article>
                    </div>
                    <div class="col-xl-4 col-md-6" data-category="fertilizer" data-price="680">
                        <article class="product-style1 h-100">
                            <div class="product-img" style="background:#f9f9f9; padding:20px; border-radius:10px;">
                                <a href="product-details.php"><img src="assets/img/inner-page/service/02.jpg" alt="Organic compost" style="width:100%; height:180px; object-fit:contain;"></a>
                            </div>
                            <div class="product-meta">Fertilizer · Organic</div>
                            <div class="product-about">
                                <h2 class="product-title" style="font-size:16px; min-height:40px;"><a href="product-details.php">Organic Compost</a></h2>
                                <span class="price" style="font-size:18px; color:#2ecc71;">₹680 / kg</span>
                            </div>
                            <div class="social-style"><ul><li><a class="main-icon" href="product-details.php"><i class="far fa-shopping-basket"></i></a></li></ul></div>
                        </article>
                    </div>
                    <div class="col-xl-4 col-md-6" data-category="pesticide" data-price="920">
                        <article class="product-style1 h-100">
                            <div class="product-img" style="background:#f9f9f9; padding:20px; border-radius:10px;">
                                <a href="product-details.php"><img src="assets/img/inner-page/service/03.jpg" alt="Crop protection spray" style="width:100%; height:180px; object-fit:contain;"></a>
                            </div>
                            <div class="product-meta">Pesticide · Crop Care</div>
                            <div class="product-about">
                                <h2 class="product-title" style="font-size:16px; min-height:40px;"><a href="product-details.php">Crop Protection Spray</a></h2>
                                <span class="price" style="font-size:18px; color:#2ecc71;">₹920 / litre</span>
                            </div>
                            <div class="social-style"><ul><li><a class="main-icon" href="product-details.php"><i class="far fa-shopping-basket"></i></a></li></ul></div>
                        </article>
                    </div>
                    <div class="col-xl-4 col-md-6" data-category="irrigation" data-price="2400">
                        <article class="product-style1 h-100">
                            <div class="product-img" style="background:#f9f9f9; padding:20px; border-radius:10px;">
                                <a href="product-details.php"><img src="assets/img/inner-page/service/04.jpg" alt="Drip irrigation system" style="width:100%; height:180px; object-fit:contain;"></a>
                            </div>
                            <div class="product-meta">Irrigation · Drip</div>
                            <div class="product-about">
                                <h2 class="product-title" style="font-size:16px; min-height:40px;"><a href="product-details.php">Drip Irrigation System</a></h2>
                                <span class="price" style="font-size:18px; color:#2ecc71;">₹2,400 / set</span>
                            </div>
                            <div class="social-style"><ul><li><a class="main-icon" href="product-details.php"><i class="far fa-shopping-basket"></i></a></li></ul></div>
                        </article>
                    </div>
                    <div class="col-xl-4 col-md-6" data-category="seed" data-price="1850">
                        <article class="product-style1 h-100">
                            <div class="product-img" style="background:#f9f9f9; padding:20px; border-radius:10px;">
                                <a href="product-details.php"><img src="assets/img/inner-page/service/05.jpg" alt="Vegetable hybrid seed" style="width:100%; height:180px; object-fit:contain;"></a>
                            </div>
                            <div class="product-meta">Seed · Hybrid</div>
                            <div class="product-about">
                                <h2 class="product-title" style="font-size:16px; min-height:40px;"><a href="product-details.php">Vegetable Hybrid Seed</a></h2>
                                <span class="price" style="font-size:18px; color:#2ecc71;">₹1,850 / kg</span>
                            </div>
                            <div class="social-style"><ul><li><a class="main-icon" href="product-details.php"><i class="far fa-shopping-basket"></i></a></li></ul></div>
                        </article>
                    </div>
                    <div class="col-xl-4 col-md-6" data-category="fertilizer" data-price="1250">
                        <article class="product-style1 h-100">
                            <div class="product-img" style="background:#f9f9f9; padding:20px; border-radius:10px;">
                                <a href="product-details.php"><img src="assets/img/inner-page/service/06.jpg" alt="NPK fertilizer" style="width:100%; height:180px; object-fit:contain;"></a>
                            </div>
                            <div class="product-meta">Fertilizer · NPK</div>
                            <div class="product-about">
                                <h2 class="product-title" style="font-size:16px; min-height:40px;"><a href="product-details.php">Balanced NPK Fertilizer</a></h2>
                                <span class="price" style="font-size:18px; color:#2ecc71;">₹1,250 / kg</span>
                            </div>
                            <div class="social-style"><ul><li><a class="main-icon" href="product-details.php"><i class="far fa-shopping-basket"></i></a></li></ul></div>
                        </article>
                    </div>
                    <div class="col-xl-4 col-md-6" data-category="pesticide" data-price="760">
                        <article class="product-style1 h-100">
                            <div class="product-img" style="background:#f9f9f9; padding:20px; border-radius:10px;">
                                <a href="product-details.php"><img src="assets/img/inner-page/service/07.jpg" alt="Plant growth regulator" style="width:100%; height:180px; object-fit:contain;"></a>
                            </div>
                            <div class="product-meta">Pesticide · Growth</div>
                            <div class="product-about">
                                <h2 class="product-title" style="font-size:16px; min-height:40px;"><a href="product-details.php">Plant Growth Regulator</a></h2>
                                <span class="price" style="font-size:18px; color:#2ecc71;">₹760 / litre</span>
                            </div>
                            <div class="social-style"><ul><li><a class="main-icon" href="product-details.php"><i class="far fa-shopping-basket"></i></a></li></ul></div>
                        </article>
                    </div>
                    <div class="col-xl-4 col-md-6" data-category="irrigation" data-price="3190">
                        <article class="product-style1 h-100">
                            <div class="product-img" style="background:#f9f9f9; padding:20px; border-radius:10px;">
                                <a href="product-details.php"><img src="assets/img/inner-page/service/04.jpg" alt="Smart irrigation controller" style="width:100%; height:180px; object-fit:contain;"></a>
                            </div>
                            <div class="product-meta">Irrigation · Smart</div>
                            <div class="product-about">
                                <h2 class="product-title" style="font-size:16px; min-height:40px;"><a href="product-details.php">Smart Irrigation Controller</a></h2>
                                <span class="price" style="font-size:18px; color:#2ecc71;">₹3,190 / unit</span>
                            </div>
                            <div class="social-style"><ul><li><a class="main-icon" href="product-details.php"><i class="far fa-shopping-basket"></i></a></li></ul></div>
                        </article>
                    </div>
                </div>
                <div id="noProducts" class="alert alert-warning text-center d-none mt-4">No products found matching your filters.</div>
                <nav class="vs-pagination text-center mt-4" aria-label="Product pages">
                    <ul class="pagination justify-content-center mb-0">
                        <li class="page-item disabled"><span class="page-link">Previous</span></li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item"><span class="page-link">2</span></li>
                        <li class="page-item"><span class="page-link">3</span></li>
                        <li class="page-item"><span class="page-link">Next</span></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('productSearch');
        const category = document.getElementById('categoryFilter');
        const minPrice = document.getElementById('minPrice');
        const maxPrice = document.getElementById('maxPrice');
        const cards = Array.from(document.querySelectorAll('#productGrid .product-style1'));
        const productCount = document.getElementById('productCount');
        const noProducts = document.getElementById('noProducts');
        const initialSearch = new URLSearchParams(window.location.search).get('search');
        if (initialSearch) {
            search.value = initialSearch;
        }

        function filterProducts() {
            const searchTerm = search.value.trim().toLowerCase();
            const selectedCategory = category.value;
            const min = minPrice.value === '' ? 0 : Number(minPrice.value);
            const max = maxPrice.value === '' ? Infinity : Number(maxPrice.value);
            let visible = 0;

            cards.forEach((card) => {
                const item = card.closest('.col-xl-4');
                const name = item.querySelector('h2').textContent.toLowerCase();
                const itemCategory = item.dataset.category;
                const price = Number(item.dataset.price);
                const matches = (!searchTerm || name.includes(searchTerm)) &&
                    (selectedCategory === 'all' || itemCategory === selectedCategory) &&
                    price >= min && price <= max;

                item.classList.toggle('d-none', !matches);
                if (matches) visible++;
            });

            productCount.textContent = visible + (visible === 1 ? ' product' : ' products');
            noProducts.classList.toggle('d-none', visible !== 0);
        }

        [search, category, minPrice, maxPrice].forEach((input) => {
            input.addEventListener('input', filterProducts);
            input.addEventListener('change', filterProducts);
        });

        document.getElementById('clearFilters').addEventListener('click', function () {
            search.value = '';
            category.value = 'all';
            minPrice.value = '';
            maxPrice.value = '';
            filterProducts();
        });

        if (initialSearch) {
            filterProducts();
            document.getElementById('productGrid').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
</script>
