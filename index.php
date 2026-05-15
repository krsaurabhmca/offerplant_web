<?php include 'includes/header.php'; ?>

<!-- Popup Modal -->
<?php if($site['popup_status'] == 1 && $site['popup_image']): ?>
<div id="sitePopup" style="position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; display:flex; align-items:center; justify-content:center;">
    <div style="position: relative; max-width: 90%; max-height: 90%;">
        <button onclick="document.getElementById('sitePopup').style.display='none'" style="position: absolute; top: -15px; right: -15px; background: var(--primary); color:#fff; border:none; width:35px; height:35px; border-radius:50%; cursor:pointer; font-size:20px; box-shadow: 0 0 10px rgba(0,0,0,0.5);">&times;</button>
        <img src="uploads/<?php echo $site['popup_image']; ?>" style="max-width:100%; max-height:80vh; border-radius:10px; box-shadow: 0 0 30px rgba(0,0,0,0.5);">
    </div>
</div>
<script>
    // Close popup on click outside
    window.onclick = function(event) {
        if (event.target == document.getElementById('sitePopup')) {
            document.getElementById('sitePopup').style.display = 'none';
        }
    }
</script>
<?php endif; ?>

<!-- Hero / Slider Section -->

<?php
$sliders_query = mysqli_query($conn, "SELECT * FROM sliders WHERE status = 1 ORDER BY id DESC");
if (mysqli_num_rows($sliders_query) > 0) {
    echo '<section class="hero-slider" style="position: relative; overflow: hidden;">';
    while($slide = mysqli_fetch_assoc($sliders_query)) {
        echo '
        <div class="slide" style="background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url(\'uploads/'.$slide['image'].'\'); background-size: cover; background-position: center; min-height: 80vh; display: flex; align-items: center; justify-content: center; text-align: center; color: #fff; padding: 100px 20px;">
            <div class="container">
                <h1 data-aos="fade-up" style="-webkit-text-fill-color: #fff; font-size: 3.5rem;">'.$slide['title'].'</h1>
                <p data-aos="fade-up" style="color: #eee; font-size: 1.2rem; margin: 20px auto 40px; max-width: 800px;">'.$slide['subtitle'].'</p>
                <div data-aos="fade-up">
                    <a href="'.$slide['btn_link'].'" class="btn-contact">'.$slide['btn_text'].'</a>
                </div>
            </div>
        </div>';
    }
    echo '</section>';
} else {
?>
<section class="hero">
    <div class="container">
        <h1 data-aos="fade-up">Innovative IT Solutions for <br>Modern Businesses</h1>
        <p data-aos="fade-up" style="transition-delay: 0.1s;">
            We deliver high-quality website design, web applications, and mobile apps that drive growth and efficiency.
        </p>
        <div data-aos="fade-up" style="transition-delay: 0.2s;">
            <a href="#contact" class="btn-contact">Start a Project</a>
            <a href="#services" style="margin-left: 20px; font-weight: 600; color: var(--secondary);">Explore Services <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
</section>
<?php } ?>


<!-- Services Section -->
<section id="services" class="services">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Specialized Services</h2>
            <div class="underline"></div>
            <p style="margin-top: 15px; color: var(--gray);">Crafting digital excellence with precision and passion.</p>
        </div>
        
        <div class="services-grid">
            <?php
            $services_query = mysqli_query($conn, "SELECT * FROM services WHERE status = 1");
            while($service = mysqli_fetch_assoc($services_query)) {
                echo '
                <div class="service-card" data-aos="fade-up">
                    <i class="fas '.$service['icon'].'"></i>
                    <h3>'.$service['title'].'</h3>
                    <p>'.$service['description'].'</p>
                </div>';
            }
            ?>
        </div>
    </div>
</section>

<!-- Products Section -->
<section id="products" style="background-color: var(--light-gray);">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Powerful Products</h2>
            <div class="underline"></div>
            <p style="margin-top: 15px; color: var(--gray);">Ready-to-use software solutions for various industries.</p>
        </div>
        
        <div class="products-grid">
            <?php
            $products_query = mysqli_query($conn, "SELECT * FROM products WHERE status = 1");
            $delay = 0;
            while($product = mysqli_fetch_assoc($products_query)) {
                echo '
                <div class="product-item" data-aos="fade-up" style="transition-delay: '.$delay.'s">
                    <div class="product-icon"><i class="fas fa-cube"></i></div>
                    <div>
                        <h4 style="margin-bottom: 5px;">'.$product['name'].'</h4>
                        <a href="'.$product['url'].'" target="_blank" style="color: var(--secondary); font-size: 14px; font-weight: 600;">Visit Product <i class="fas fa-external-link-alt" style="font-size: 10px;"></i></a>
                    </div>
                </div>';
                $delay += 0.05;
            }
            ?>
        </div>
    </div>
</section>

<!-- Pricing Section -->
<section id="pricing" style="background: #fdfdfd;">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Our Pricing Plans</h2>
            <div class="underline"></div>
            <p style="margin-top: 15px; color: var(--gray);">Choose the perfect plan for your business needs.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 50px;">
            <?php
            $plans_query = mysqli_query($conn, "SELECT * FROM pricing_plans WHERE status = 1 ORDER BY price ASC");
            while($plan = mysqli_fetch_assoc($plans_query)) {
                $is_popular = !empty($plan['badge']);
                echo '
                <div class="pricing-card" data-aos="fade-up" style="background: #fff; padding: 40px; border-radius: 30px; box-shadow: var(--shadow); position: relative; border: '.($is_popular ? '2px solid var(--primary)' : '1px solid #eee').';">
                    '.($is_popular ? '<div style="position: absolute; top: -15px; left: 50%; transform: translateX(-50%); background: var(--primary); color: #fff; padding: 5px 20px; border-radius: 20px; font-size: 13px; font-weight: 700;">'.$plan['badge'].'</div>' : '').'
                    
                    <h3 style="margin-bottom: 20px; font-size: 1.5rem; text-align: center;">'.$plan['name'].'</h3>
                    
                    <div style="text-align: center; margin-bottom: 30px;">';
                        if ($plan['offer_price'] > 0) {
                            echo '<span style="font-size: 1.2rem; text-decoration: line-through; color: #bbb;">₹'.$plan['price'].'</span>
                                  <div style="font-size: 2.5rem; font-weight: 800; color: var(--dark);">₹'.$plan['offer_price'].'</div>
                                  <p style="color: var(--secondary); font-weight: 600; font-size: 14px;">Limited Time Offer!</p>';
                        } else {
                            echo '<div style="font-size: 2.5rem; font-weight: 800; color: var(--dark);">₹'.$plan['price'].'</div>
                                  <p style="color: var(--gray); font-size: 14px;">Standard Price</p>';
                        }
                    echo '</div>

                    <ul style="margin-bottom: 35px; list-style: none; padding: 0;">';

                        $features = explode("\n", $plan['features']);
                        foreach($features as $feat) {
                            if(trim($feat)) {
                                echo '<li style="margin-bottom: 12px; display: flex; align-items: center; gap: 10px; font-size: 15px; color: #555;"><i class="fas fa-check-circle" style="color: var(--secondary);"></i> '.trim($feat).'</li>';
                            }
                        }
                    echo '</ul>
                    
                    <div style="text-align: center;">
                        <a href="#contact" class="btn-contact" style="width: 100%; display: block; padding: 15px;">Get Started</a>
                    </div>';

                    if(!empty($plan['offer_ends'])) {
                        $expiry = strtotime($plan['offer_ends']);
                        if($expiry > time()) {
                            echo '
                            <div style="margin-top: 25px; text-align: center; padding: 15px; background: #fff5f8; border-radius: 15px;">
                                <small style="color: #666; display: block; margin-bottom: 5px;">Ends in:</small>
                                <div class="countdown" data-time="'.$plan['offer_ends'].'" style="font-weight: 700; color: var(--primary); font-family: monospace; font-size: 16px;"></div>
                            </div>';
                        }
                    }

                echo '</div>';
            }
            ?>
        </div>
    </div>
</section>

<script>
// Pricing Countdown Logic
function updateCountdowns() {
    document.querySelectorAll('.countdown').forEach(el => {
        const target = new Date(el.dataset.time).getTime();
        const now = new Date().getTime();
        const diff = target - now;
        
        if (diff < 0) {
            el.innerHTML = "EXPIRED";
            return;
        }
        
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const mins = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const secs = Math.floor((diff % (1000 * 60)) / 1000);
        
        el.innerHTML = `${days}d ${hours}h ${mins}m ${secs}s`;
    });
}
setInterval(updateCountdowns, 1000);
updateCountdowns();
</script>

<!-- Blog Section -->

<section id="blog" style="background-color: var(--white);">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Latest from our Blog</h2>
            <div class="underline"></div>
            <p style="margin-top: 15px; color: var(--gray);">Stay updated with the latest trends in IT and software.</p>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <?php
            $blogs_query = mysqli_query($conn, "SELECT * FROM blogs WHERE status = 1 ORDER BY created_at DESC LIMIT 3");
            while($blog = mysqli_fetch_assoc($blogs_query)) {
                echo '
                <div class="blog-card" data-aos="fade-up" style="background: #fff; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow);">
                    <img src="uploads/'.$blog['image'].'" style="width: 100%; height: 200px; object-fit: cover;">
                    <div style="padding: 25px;">
                        <p style="color: var(--primary); font-size: 13px; font-weight: 600; margin-bottom: 10px;">'.date('M d, Y', strtotime($blog['created_at'])).'</p>
                        <h3 style="margin-bottom: 15px; font-size: 1.2rem;">'.$blog['title'].'</h3>
                        <p style="color: #666; font-size: 14px; margin-bottom: 20px;">'.substr(strip_tags($blog['content']), 0, 100).'...</p>
                        <a href="blog-details.php?slug='.$blog['slug'].'" style="color: var(--secondary); font-weight: 700;">Read More <i class="fas fa-chevron-right" style="font-size: 10px;"></i></a>
                    </div>
                </div>';
            }
            ?>
        </div>
        
        <div style="text-align: center; margin-top: 50px;" data-aos="fade-up">
            <a href="blog.php" class="btn-contact" style="background: transparent; border: 2px solid var(--primary); color: var(--primary);">View All Posts</a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" style="background-color: var(--light-gray); padding: 100px 0;">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>What Our Clients Say</h2>
            <div class="underline"></div>
            <p style="margin-top: 15px; color: var(--gray);">Trusted by businesses across the globe.</p>
        </div>
        
        <div class="swiper testimonial-swiper" style="margin-top: 50px; padding-bottom: 50px;">
            <div class="swiper-wrapper">
                <?php
                $testis_query = mysqli_query($conn, "SELECT * FROM testimonials WHERE status = 1 ORDER BY id DESC");
                while($t = mysqli_fetch_assoc($testis_query)) {
                    echo '
                    <div class="swiper-slide">
                        <div class="testimonial-card" style="background: #fff; padding: 40px; border-radius: 20px; box-shadow: var(--shadow); position: relative; height: 100%; margin: 10px;">
                            <div style="color: #ffc107; margin-bottom: 20px; font-size: 14px;">';
                                for($i=1; $i<=5; $i++) {
                                    echo $i <= $t['rating'] ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>';
                                }
                            echo '</div>
                            <p style="font-style: italic; color: #555; line-height: 1.8; margin-bottom: 30px;">"'.$t['feedback'].'"</p>
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <img src="uploads/'.$t['image'].'" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                <div>
                                    <h4 style="font-size: 16px; margin-bottom: 2px;">'.$t['name'].'</h4>
                                    <p style="font-size: 13px; color: var(--gray);">'.$t['designation'].' at '.$t['company'].'</p>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
                ?>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
            <!-- Navigation -->
            <div class="swiper-button-next" style="color: var(--primary);"></div>
            <div class="swiper-button-prev" style="color: var(--primary);"></div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('.testimonial-swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 4000,
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
        breakpoints: {
            768: {
                slidesPerView: 2,
            }
        }
    });
});
</script>


<!-- FAQ Section -->
<section id="faq" style="padding: 100px 0; background: #fff;">
    <div class="container">
        <div class="section-title" style="text-align: center; margin-bottom: 60px;">
            <span style="color: var(--primary); font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">Questions?</span>
            <h2 style="font-size: 2.5rem; margin-top: 10px;">Frequently Asked Questions</h2>
        </div>

        <div style="max-width: 800px; margin: 0 auto;">
            <?php
            $faqs = mysqli_query($conn, "SELECT * FROM faqs WHERE status = 1 ORDER BY order_no ASC");
            if (mysqli_num_rows($faqs) > 0) {
                while($f = mysqli_fetch_assoc($faqs)) {
                    echo '
                    <div class="faq-item" style="margin-bottom: 20px; border: 1px solid #eee; border-radius: 15px; overflow: hidden; transition: 0.3s;">
                        <div class="faq-question" onclick="toggleFaq(this)" style="padding: 20px 25px; background: #fdfdfd; cursor: pointer; display: flex; justify-content: space-between; align-items: center; font-weight: 600; color: var(--dark);">
                            '.$f['question'].'
                            <i class="fas fa-chevron-down" style="transition: 0.3s; font-size: 14px; color: var(--primary);"></i>
                        </div>
                        <div class="faq-answer" style="padding: 0 25px; max-height: 0; overflow: hidden; transition: 0.4s cubic-bezier(0, 1, 0, 1); background: #fff; line-height: 1.8; color: #666;">
                            <div style="padding-bottom: 25px; padding-top: 10px;">'.$f['answer'].'</div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p style="text-align:center; color:#888;">No FAQs added yet.</p>';
            }
            ?>
        </div>
    </div>
</section>

<script>
function toggleFaq(element) {
    const item = element.parentElement;
    const answer = item.querySelector('.faq-answer');
    const icon = element.querySelector('i');
    
    // Close others
    document.querySelectorAll('.faq-item').forEach(other => {
        if(other !== item) {
            other.querySelector('.faq-answer').style.maxHeight = '0';
            other.querySelector('i').style.transform = 'rotate(0deg)';
            other.style.borderColor = '#eee';
        }
    });

    if (answer.style.maxHeight === '0px' || answer.style.maxHeight === '') {
        answer.style.maxHeight = '500px';
        icon.style.transform = 'rotate(180deg)';
        item.style.borderColor = 'var(--primary)';
    } else {
        answer.style.maxHeight = '0';
        icon.style.transform = 'rotate(0deg)';
        item.style.borderColor = '#eee';
    }
}
</script>

<!-- Contact Section -->


<section id="contact">
    <div class="container">
        <div class="section-title" data-aos="fade-up">
            <h2>Get In Touch</h2>
            <div class="underline"></div>
        </div>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 50px;">
            <div data-aos="fade-right">
                <h3 style="margin-bottom: 20px;">Let\'s discuss your project</h3>
                <p style="color: var(--gray); margin-bottom: 30px;">
                    Have an idea or a project in mind? We\'d love to hear from you. Our team is ready to help you build something amazing.
                </p>
                
                <div style="margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #fff5f9; color: var(--primary); display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <i class="fas fa-phone-alt"></i>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: var(--gray);">Call Us</p>
                            <p style="font-weight: 600;"><?php echo $site['phone']; ?></p>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f0fff4; color: var(--secondary); display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: var(--gray);">Email Us</p>
                            <p style="font-weight: 600;"><?php echo $site['email']; ?></p>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: #f8f9fa; color: var(--dark); display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <p style="font-size: 14px; color: var(--gray);">Visit Us</p>
                            <p style="font-weight: 600;"><?php echo $site['address']; ?></p>
                        </div>
                    </div>
                </div>

                <!-- Google Map -->
                <?php if($site['google_map']): ?>
                <div style="margin-top: 30px; border-radius: 20px; overflow: hidden; height: 250px; box-shadow: var(--shadow);">
                    <?php echo $site['google_map']; ?>
                    <style>iframe{width:100% !important; height:100% !important; border:0;}</style>
                </div>
                <?php endif; ?>
            </div>

            
            <div data-aos="fade-left">
                <?php if(isset($_GET['success'])): ?>
                    <div style="background: #ecfdf5; color: #059669; padding: 20px; border-radius: 15px; margin-bottom: 25px; border-left: 5px solid #10b981; animation: slideInRight 0.5s ease;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 35px; height: 35px; background: #10b981; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Success!</h4>
                                <p style="margin: 0; font-size: 14px; opacity: 0.8;">Thank you for contacting us. We'll get back to you soon.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error'])): ?>
                    <div style="background: #fef2f2; color: #dc2626; padding: 20px; border-radius: 15px; margin-bottom: 25px; border-left: 5px solid #ef4444; animation: slideInRight 0.5s ease;">
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="width: 35px; height: 35px; background: #ef4444; color: #fff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-times"></i>
                            </div>
                            <div>
                                <h4 style="margin: 0; font-size: 16px;">Oops!</h4>
                                <p style="margin: 0; font-size: 14px; opacity: 0.8;">Something went wrong. Please try again later.</p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="contact-process.php" method="POST" style="background: var(--white); padding: 40px; border-radius: 20px; box-shadow: var(--shadow);">

                    <div style="margin-bottom: 20px;">
                        <input type="text" name="name" placeholder="Your Name" required style="width: 100%; padding: 12px 20px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <input type="email" name="email" placeholder="Your Email" required style="width: 100%; padding: 12px 20px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <input type="text" name="subject" placeholder="Subject" style="width: 100%; padding: 12px 20px; border-radius: 10px; border: 1px solid #ddd; outline: none;">
                    </div>
                    <div style="margin-bottom: 20px;">
                        <textarea name="message" placeholder="Your Message" required style="width: 100%; padding: 12px 20px; border-radius: 10px; border: 1px solid #ddd; outline: none; height: 120px;"></textarea>
                    </div>
                    <button type="submit" class="btn-contact" style="width: 100%; border: none; cursor: pointer;">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
