<footer class="<?php echo $site['footer_theme'] ?? 'footer-dark'; ?>">
    <!-- Footer Graphic -->
    <div class="footer-wave">
        <svg viewBox="0 0 1440 100" preserveAspectRatio="none">
            <path fill-opacity="1" d="M0,64L80,69.3C160,75,320,85,480,80C640,75,800,53,960,48C1120,43,1280,53,1360,58.7L1440,64L1440,100L1360,100C1280,100,1120,100,960,100C800,100,640,100,480,100C320,100,160,100,80,100L0,100Z"></path>
        </svg>
    </div>

    <div class="container">
        <div class="footer-grid">
            <div class="footer-col" data-aos="fade-up">
                <a href="index.php" class="logo">
                    <?php 
                    $logo_src = !empty($site['logo']) ? "uploads/".$site['logo'] : "assets/images/logo.png";
                    $is_light = ($site['footer_theme'] ?? 'footer-dark') == 'footer-light';
                    $filter = $is_light ? 'filter: grayscale(100%);' : 'filter: brightness(0) invert(1);';
                    ?>
                    <img src="<?php echo $logo_src; ?>" alt="<?php echo $site['site_name'] ?? 'OfferPlant'; ?>" style="height: 55px; margin-bottom: 25px; <?php echo $filter; ?> transition: 0.3s;">
                </a>
                <p style="margin-bottom: 25px; line-height: 1.8; opacity: 0.8;">

                    <?php echo $site['meta_description']; ?>
                </p>
                <div class="social-links">
                    <?php if($site['facebook_url']): ?><a href="<?php echo $site['facebook_url']; ?>" target="_blank"><i class="fab fa-facebook-f"></i></a><?php endif; ?>
                    <?php if($site['twitter_url']): ?><a href="<?php echo $site['twitter_url']; ?>" target="_blank"><i class="fab fa-twitter"></i></a><?php endif; ?>
                    <?php if($site['linkedin_url']): ?><a href="<?php echo $site['linkedin_url']; ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a><?php endif; ?>
                    <?php if($site['instagram_url']): ?><a href="<?php echo $site['instagram_url']; ?>" target="_blank"><i class="fab fa-instagram"></i></a><?php endif; ?>
                </div>
            </div>
            
            <div class="footer-col" data-aos="fade-up" style="transition-delay: 0.1s;">
                <h4>Quick Links</h4>
                <ul class="footer-links">
                    <?php
                    $footer_menus = mysqli_query($conn, "SELECT * FROM menus WHERE status = 1 ORDER BY order_no ASC LIMIT 6");
                    while($fm = mysqli_fetch_assoc($footer_menus)) {
                        echo '<li><a href="'.$fm['url'].'">'.$fm['title'].'</a></li>';
                    }
                    ?>
                </ul>
            </div>
            
            <div class="footer-col" data-aos="fade-up" style="transition-delay: 0.2s;">
                <h4>Our Policies</h4>
                <ul class="footer-links">
                    <?php
                    $footer_pages = mysqli_query($conn, "SELECT title, slug FROM pages WHERE status = 1");
                    while($pg = mysqli_fetch_assoc($footer_pages)) {
                        echo '<li><a href="page.php?slug='.$pg['slug'].'">'.$pg['title'].'</a></li>';
                    }
                    ?>
                </ul>
            </div>
            
            <div class="footer-col" data-aos="fade-up" style="transition-delay: 0.3s;">
                <h4>Contact Us</h4>
                <ul class="footer-links">
                    <li style="display: flex; gap: 15px; margin-bottom: 15px;">
                        <i class="fas fa-map-marker-alt" style="color: var(--primary); margin-top: 5px;"></i> 
                        <span><?php echo $site['address']; ?></span>
                    </li>
                    <li style="display: flex; gap: 15px; margin-bottom: 15px;">
                        <i class="fas fa-phone-alt" style="color: var(--primary);"></i> 
                        <span><?php echo $site['phone']; ?></span>
                    </li>
                    <li style="display: flex; gap: 15px;">
                        <i class="fas fa-envelope" style="color: var(--primary);"></i> 
                        <span><?php echo $site['email']; ?></span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> OfferPlant Technologies Pvt. Ltd. | Designed with <i class="fas fa-heart" style="color: var(--primary);"></i> in India</p>
        </div>
    </div>
</footer>


<!-- WhatsApp Floating Button -->
<?php if($site['whatsapp_status'] == 1 && $site['whatsapp_number']): ?>
<a href="https://wa.me/<?php echo $site['whatsapp_number']; ?>" target="_blank" style="position: fixed; bottom: 30px; right: 30px; background: #25d366; color: #fff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 1000; transition: 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
    <i class="fab fa-whatsapp"></i>
</a>
<?php endif; ?>


<!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
    const homeSlider = new Swiper('.home-slider', {
        loop: true,
        speed: 800,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        effect: 'fade',
        fadeEffect: {
            crossFade: true
        }
    });

    const testimonialSlider = new Swiper('.testimonial-slider', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        breakpoints: {
            768: { slidesPerView: 2 },
            1024: { slidesPerView: 3 }
        }
    });
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

<script src="assets/js/main.js"></script>
<script>
    // Simple AOS Implementation
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('[data-aos]').forEach(el => observer.observe(el));
    });
</script>

</div> <!-- End Main Wrapper -->
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('sw.js')
                .then(reg => console.log('SW Registered', reg))
                .catch(err => console.log('SW Failed', err));
        });
    }
</script>
</body>
</html>


