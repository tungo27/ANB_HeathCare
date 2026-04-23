<footer class="footer-custom mt-5 pt-5 pb-3">
    <div class="container">
        <div class="row gy-4"> {{-- 'gy-4' giúp tạo khoảng cách giữa các hàng khi thu nhỏ màn hình --}}
            
            <div class="col-lg-4 col-md-6">
                <h5 class="text-uppercase fw-bold mb-3">Phòng Khám Đa Khoa ABC</h5>
                <p class="text-secondary pe-lg-4">
                    Chăm sóc sức khỏe tận tâm, uy tín và chuyên nghiệp. 
                    Luôn đồng hành cùng sức khỏe của bạn và gia đình.
                </p>
                <div class="social-links mt-3">
                    <a href="https://facebook.com/link_cua_ban" class="text-light me-3 fs-4" target="_blank">
                        <i class="bi bi-facebook"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <h5 class="text-uppercase fw-bold mb-3">Liên Hệ</h5>
                <ul class="list-unstyled">
                    <li class="mb-3">
                        <i class="bi bi-geo-alt-fill me-2"></i>
                        99 Tô Hiến Thành, Sơn Trà, Đà Nẵng.
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-telephone-fill me-2"></i>
                        <a href="tel:0900123456" class="text-light text-decoration-none">0900 123 456</a>
                    </li>
                    <li class="mb-3">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <a href="mailto:contact@phongkham.com" class="text-light text-decoration-none">contact@phongkham.com</a>
                    </li>
                </ul>
            </div>

            <div class="col-lg-4 col-md-12">
                <h5 class="text-uppercase fw-bold mb-3">Liên Kết</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="/" class="text-light text-decoration-none">Trang chủ</a></li>
                    <li class="mb-2"><a href="/about" class="text-light text-decoration-none">Giới thiệu</a></li>
                    <li class="mb-2"><a href="/services" class="text-light text-decoration-none">Dịch vụ khám</a></li>
                    <li class="mb-2"><a href="/contact" class="text-light text-decoration-none">Đặt lịch hẹn</a></li>
                </ul>
            </div>
        </div>
        
        <hr class="mt-4 border-light opacity-25">
        <div class="text-center text-light opacity-75 small">
            &copy; {{ date('Y') }} Phòng Khám Đa Khoa ABC. Tất cả quyền được bảo lưu.
        </div>
    </div>
</footer>