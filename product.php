<?php 
require_once 'admin/config.php';
include 'include/header.php'; 

// Initial Load
$apiUrl = "https://kisanogram.com/ampl-products.php?page=1";
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_USERAGENT => "Mozilla/5.0",
));
$response = curl_exec($curl);
curl_close($curl);
$data = json_decode($response, true);
$products = $data['data'] ?? [];
$totalPages = $data['total_pages'] ?? 1;
?>

    <!--==============================
    Breadcumb
    ============================== -->
    <div class="breadcumb-wrapper" style="background-color: #2ecc71; padding: 60px 0;">
        <div class="container z-index-common">
            <div class="breadcumb-content text-center">
                <h1 class="breadcumb-title text-white">Our Products</h1>
            </div>
        </div>
    </div>

    <!--==============================
    Products area
    ============================== -->
    <section class="products space">
        <div class="container">
            <div class="row">
                <!-- Sidebar Filters -->
                <div class="col-lg-3">
                    <aside class="sidebar-area" style="background: #fdfdfd; padding: 25px; border-radius: 15px; border: 1px solid #eee;">
                        <div class="widget">
                            <h3 class="widget_title" style="font-size: 22px; margin-bottom: 20px;">Live Filters</h3>
                            <form id="filterForm">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Product Name</label>
                                    <input type="text" id="search_name" name="search_name" class="form-control" placeholder="Type to search...">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Technical Name</label>
                                    <input type="text" id="tech_name" name="tech_name" class="form-control" placeholder="Technical specs...">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Price Range (₹)</label>
                                    <div class="d-flex gap-2">
                                        <input type="number" id="min_price" name="min_price" class="form-control" placeholder="Min">
                                        <input type="number" id="max_price" name="max_price" class="form-control" placeholder="Max">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </aside>
                </div>

                <!-- Product Grid -->
                <div class="col-lg-9">
                    <div id="loadingIndicator" style="display:none;" class="text-center mb-4">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    
                    <div id="productGrid" class="row">
                        <?php if (!empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                            <div class="col-xl-4 col-md-6 mb-30">
                                <div class="product-style1">
                                    <div class="product-img" style="background: #f9f9f9; padding: 20px; border-radius: 10px;">
                                        <a href="<?php echo $product['full_link']; ?>" target="_blank">
                                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width:100%; height: 180px; object-fit: contain;">
                                        </a>
                                    </div>
                                    <div class="product-meta">AMPL Agro Market</div>
                                    <div class="product-about">
                                        <h2 class="product-title" style="font-size: 16px; min-height: 40px;"><a href="<?php echo $product['full_link']; ?>" target="_blank"><?php echo htmlspecialchars($product['name']); ?></a></h2>
                                        <span class="price" style="font-size: 18px; color: #2ecc71;">₹<?php echo $product['unit_price']; ?></span>
                                    </div>
                                    <div class="social-style">
                                        <ul>
                                            <li>
                                                <a class="main-icon" href="<?php echo $product['full_link']; ?>" target="_blank"><i class="far fa-shopping-basket"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12 text-center py-5">
                                <div class="alert alert-warning">No products found.</div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination -->
                    <div id="paginationContainer" class="vs-pagination text-center mb-0 mt-4">
                        <ul id="paginationList">
                            <!-- JS will inject pagination here -->
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const grid = document.getElementById('productGrid');
        const loader = document.getElementById('loadingIndicator');
        const paginationList = document.getElementById('paginationList');
        const inputs = ['search_name', 'tech_name', 'min_price', 'max_price'];
        let timeout = null;
        let currentPage = 1;

        function renderPagination(total, current) {
            let html = '';
            if (total <= 1) {
                paginationList.innerHTML = '';
                return;
            }

            for (let i = 1; i <= total; i++) {
                html += `<li class="${i === current ? 'active' : ''}"><a href="javascript:void(0)" onclick="window.changePage(${i})">${i}</a></li>`;
            }
            paginationList.innerHTML = html;
        }

        window.changePage = function(page) {
            currentPage = page;
            updateProducts();
            window.scrollTo({ top: 300, behavior: 'smooth' });
        }

        function updateProducts() {
            loader.style.display = 'block';
            grid.style.opacity = '0.5';

            const params = new URLSearchParams({
                page: currentPage,
                search_name: document.getElementById('search_name').value,
                tech_name: document.getElementById('tech_name').value,
                min_price: document.getElementById('min_price').value,
                max_price: document.getElementById('max_price').value
            });

            fetch('fetch-products.php?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    loader.style.display = 'none';
                    grid.style.opacity = '1';
                    
                    if (data.result && data.data.length > 0) {
                        let html = '';
                        data.data.forEach(product => {
                            html += `
                            <div class="col-xl-4 col-md-6 mb-30">
                                <div class="product-style1">
                                    <div class="product-img" style="background: #f9f9f9; padding: 20px; border-radius: 10px;">
                                        <a href="${product.full_link}" target="_blank">
                                            <img src="${product.image_url}" alt="${product.name}" style="width:100%; height: 180px; object-fit: contain;">
                                        </a>
                                    </div>
                                    <div class="product-meta">AMPL Agro Market</div>
                                    <div class="product-about">
                                        <h2 class="product-title" style="font-size: 16px; min-height: 40px;"><a href="${product.full_link}" target="_blank">${product.name}</a></h2>
                                        <span class="price" style="font-size: 18px; color: #2ecc71;">₹${product.unit_price}</span>
                                    </div>
                                    <div class="social-style">
                                        <ul>
                                            <li><a class="main-icon" href="${product.full_link}" target="_blank"><i class="far fa-shopping-basket"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>`;
                        });
                        grid.innerHTML = html;
                        renderPagination(data.total_pages, data.current_page);
                    } else {
                        grid.innerHTML = '<div class="col-12 text-center py-5"><div class="alert alert-warning">No products found matching your search.</div></div>';
                        paginationList.innerHTML = '';
                    }
                })
                .catch(err => {
                    console.error(err);
                    loader.style.display = 'none';
                    grid.style.opacity = '1';
                });
        }

        inputs.forEach(id => {
            document.getElementById(id).addEventListener('input', function() {
                currentPage = 1; // Reset to page 1 on new search
                clearTimeout(timeout);
                timeout = setTimeout(updateProducts, 500);
            });
        });

        // Initial pagination render
        renderPagination(<?php echo $totalPages; ?>, 1);
    });
    </script>

<?php include 'include/footer.php'; ?>