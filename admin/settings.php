<?php 
include 'auth_check.php'; 

// Handle Update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_settings'])) {
    // List of keys to manage
    $keys = [
        'site_name', 'email', 'phone', 'address', 'meta_title', 'meta_description',
        'facebook_url', 'twitter_url', 'linkedin_url', 'instagram_url', 'youtube_url',
        'whatsapp_number', 'whatsapp_status', 'google_map', 'popup_status', 'site_theme',
        'footer_theme', 'topbar_theme', 'menu_layout', 'google_analytics',
        'header_sticky', 'layout_view',
        'hero_heading', 'hero_subheading', 'hero_btn_text', 'hero_btn_link',
        'home_hero_type', 'product_showcase_style'
    ];








    foreach ($keys as $key) {
        if (isset($_POST[$key])) {
            $val = mysqli_real_escape_string($conn, $_POST[$key]);
            mysqli_query($conn, "INSERT INTO site_settings (meta_key, meta_value) VALUES ('$key', '$val') ON DUPLICATE KEY UPDATE meta_value = '$val'");
        }
    }
    
    // Handle Logo Upload
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
        $ext = pathinfo($_FILES['site_logo']['name'], PATHINFO_EXTENSION);
        $site_logo = 'logo_'.time().'.'.$ext;
        if (move_uploaded_file($_FILES['site_logo']['tmp_name'], "../uploads/".$site_logo)) {
            mysqli_query($conn, "INSERT INTO site_settings (meta_key, meta_value) VALUES ('logo', '$site_logo') ON DUPLICATE KEY UPDATE meta_value = '$site_logo'");
        }
    }

    // Handle Popup Image separately
    if (isset($_FILES['popup_image']) && $_FILES['popup_image']['error'] == 0) {
        $ext = pathinfo($_FILES['popup_image']['name'], PATHINFO_EXTENSION);
        $popup_image = 'popup_'.time().'.'.$ext;
        if (move_uploaded_file($_FILES['popup_image']['tmp_name'], "../uploads/".$popup_image)) {
            mysqli_query($conn, "INSERT INTO site_settings (meta_key, meta_value) VALUES ('popup_image', '$popup_image') ON DUPLICATE KEY UPDATE meta_value = '$popup_image'");
        }
    }
    // Handle Hero Banner Upload
    if (isset($_FILES['hero_banner']) && $_FILES['hero_banner']['name']) {
        $ext = pathinfo($_FILES['hero_banner']['name'], PATHINFO_EXTENSION);
        $banner_name = 'hero_banner_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['hero_banner']['tmp_name'], '../uploads/' . $banner_name);
        mysqli_query($conn, "UPDATE site_settings SET meta_value = '$banner_name' WHERE meta_key = 'hero_banner'");
    }


    $success = "Settings updated successfully!";
    
    // Refresh $site array
    $site = [];
    $settings_query = "SELECT * FROM site_settings";
    $settings_result = mysqli_query($conn, $settings_query);
    while ($row = mysqli_fetch_assoc($settings_result)) {
        $site[$row['meta_key']] = $row['meta_value'];
    }
}



include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';
?>

<style>
    .settings-tabs { display: flex; gap: 10px; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 0; overflow-x: auto; }
    .tab-btn { padding: 12px 25px; border: none; background: none; font-weight: 600; color: #64748b; cursor: pointer; border-bottom: 3px solid transparent; transition: 0.3s; white-space: nowrap; }
    .tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); background: #fff0f7; border-radius: 10px 10px 0 0; }
    .tab-content { display: none; animation: fadeIn 0.4s ease; }
    .tab-content.active { display: block; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #334155; font-size: 14px; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 10px; font-size: 14px; transition: 0.3s; outline: none; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(255, 20, 147, 0.1); }
    
    .sticky-footer { position: sticky; bottom: -30px; margin: 30px -30px -30px -30px; background: #fff; padding: 20px 30px; box-shadow: 0 -5px 15px rgba(0,0,0,0.05); z-index: 100; display: flex; justify-content: flex-end; }
</style>

<div style="margin-bottom: 30px;">
    <h2 style="font-size: 24px; font-weight: 800; color: #1e293b;">Site Configuration</h2>
    <p style="color: #64748b; font-size: 14px; margin-top: 5px;">Manage your website branding, SEO, and global settings.</p>
</div>

<?php if (isset($success)): ?>
    <div style="background: #ecfdf5; color: #059669; padding: 15px 25px; border-radius: 12px; margin-bottom: 30px; border: 1px solid #d1fae5; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-check-circle"></i> <?php echo $success; ?>
    </div>
<?php endif; ?>

<div class="settings-tabs">
    <button class="tab-btn active" onclick="showTab('general')"><i class="fas fa-cog" style="margin-right: 8px;"></i> General</button>
    <button class="tab-btn" onclick="showTab('hero')"><i class="fas fa-home" style="margin-right: 8px;"></i> Hero Section</button>
    <button class="tab-btn" onclick="showTab('appearance')"><i class="fas fa-palette" style="margin-right: 8px;"></i> Appearance</button>
    <button class="tab-btn" onclick="showTab('seo')"><i class="fas fa-search" style="margin-right: 8px;"></i> SEO & Marketing</button>
    <button class="tab-btn" onclick="showTab('social')"><i class="fas fa-share-alt" style="margin-right: 8px;"></i> Social & Contact</button>
</div>

<form method="POST" enctype="multipart/form-data">
    <div class="card" style="padding: 40px;">
        <!-- General Tab -->
        <div id="general" class="tab-content active">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                <div class="form-group">
                    <label>Site Name</label>
                    <input type="text" name="site_name" value="<?php echo $site['site_name']; ?>" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Contact Email</label>
                    <input type="email" name="email" value="<?php echo $site['email']; ?>" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Contact Phone</label>
                    <input type="text" name="phone" value="<?php echo $site['phone']; ?>" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Site Logo</label>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <?php if (isset($site['logo']) && $site['logo']): ?>
                            <img src="../uploads/<?php echo $site['logo']; ?>" style="height: 50px; background: #f8fafc; border: 1px solid #e2e8f0; padding: 5px; border-radius: 10px;">
                        <?php endif; ?>
                        <input type="file" name="site_logo" class="form-control" style="padding: 8px;">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Company Address</label>
                <textarea name="address" required class="form-control" style="height: 100px;"><?php echo $site['address']; ?></textarea>
            </div>
        </div>

        <div id="hero" class="tab-content">
            <div class="form-group">
                <label>Hero Section Type</label>
                <select name="home_hero_type" class="form-control">
                    <option value="hero" <?php echo ($site['home_hero_type'] ?? 'hero') == 'hero' ? 'selected' : ''; ?>>Static Hero (Single Image)</option>
                    <option value="slider" <?php echo ($site['home_hero_type'] ?? '') == 'slider' ? 'selected' : ''; ?>>Dynamic Slider (Multi-Image)</option>
                </select>
                <small style="color: #64748b;">If 'Dynamic Slider' is selected, you can manage slides in the 'Sliders' module.</small>
            </div>
            <hr style="margin: 20px 0; border-top: 1px dashed #eee;">
            <div class="form-group">

                <label>Hero Heading (Main Title)</label>
                <input type="text" name="hero_heading" value="<?php echo $site['hero_heading'] ?? ''; ?>" class="form-control" placeholder="e.g. Empowering Your Business with IT Solutions">
            </div>
            <div class="form-group">
                <label>Hero Subheading (Short Description)</label>
                <textarea name="hero_subheading" class="form-control" style="height: 80px;"><?php echo $site['hero_subheading'] ?? ''; ?></textarea>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                <div class="form-group">
                    <label>CTA Button Text</label>
                    <input type="text" name="hero_btn_text" value="<?php echo $site['hero_btn_text'] ?? 'Get Started'; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label>CTA Button Link</label>
                    <input type="text" name="hero_btn_link" value="<?php echo $site['hero_btn_link'] ?? '#contact'; ?>" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label>Main Hero Banner Image</label>
                <div style="display: flex; align-items: center; gap: 20px;">
                    <?php if (isset($site['hero_banner']) && $site['hero_banner']): ?>
                        <img src="../uploads/<?php echo $site['hero_banner']; ?>" style="width: 150px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <?php endif; ?>
                    <input type="file" name="hero_banner" class="form-control" style="padding: 8px;">
                </div>
                <small style="color: #64748b; margin-top: 5px; display: block;">Recommended size: 1920x1080px (High Quality PNG/JPG)</small>
            </div>
        </div>


        <!-- Appearance Tab -->
        <div id="appearance" class="tab-content">
            <div class="form-group">
                <label>Color Combination / Preset</label>
                <select name="site_theme" class="form-control">
                    <option value="" <?php echo ($site['site_theme'] == '') ? 'selected' : ''; ?>>Default (Pink & Green)</option>
                    <option value="theme-royal-blue" <?php echo ($site['site_theme'] == 'theme-royal-blue') ? 'selected' : ''; ?>>Royal Blue (Corporate)</option>
                    <option value="theme-dark-mode" <?php echo ($site['site_theme'] == 'theme-dark-mode') ? 'selected' : ''; ?>>Midnight Dark (High Tech)</option>
                    <option value="theme-sunset" <?php echo ($site['site_theme'] == 'theme-sunset') ? 'selected' : ''; ?>>Sunset Glow (Vibrant)</option>
                    <option value="theme-forest" <?php echo ($site['site_theme'] == 'theme-forest') ? 'selected' : ''; ?>>Forest Green (Eco-Friendly)</option>
                </select>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px;">
                <div class="form-group">
                    <label>Footer Theme</label>
                    <select name="footer_theme" class="form-control">
                        <option value="footer-dark" <?php echo ($site['footer_theme'] == 'footer-dark') ? 'selected' : ''; ?>>Dark Theme</option>
                        <option value="footer-light" <?php echo ($site['footer_theme'] == 'footer-light') ? 'selected' : ''; ?>>Light Theme</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Top Bar Theme</label>
                    <select name="topbar_theme" class="form-control">
                        <option value="topbar-dark" <?php echo ($site['topbar_theme'] == 'topbar-dark') ? 'selected' : ''; ?>>Dark Theme</option>
                        <option value="topbar-light" <?php echo ($site['topbar_theme'] == 'topbar-light') ? 'selected' : ''; ?>>Light Theme</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Menu Layout</label>
                    <select name="menu_layout" class="form-control">
                        <option value="menu-inline" <?php echo ($site['menu_layout'] == 'menu-inline') ? 'selected' : ''; ?>>Inline with Logo</option>
                        <option value="menu-separate" <?php echo ($site['menu_layout'] == 'menu-separate') ? 'selected' : ''; ?>>Separate (Full Width)</option>
                    </select>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 25px;">
                <div class="form-group">
                    <label>Header Sticky</label>
                    <select name="header_sticky" class="form-control">
                        <option value="sticky" <?php echo ($site['header_sticky'] == 'sticky') ? 'selected' : ''; ?>>Sticky (Fixed at Top)</option>
                        <option value="static" <?php echo ($site['header_sticky'] == 'static') ? 'selected' : ''; ?>>Static (Scrolls with Page)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Website Layout</label>
                    <select name="layout_view" class="form-control">
                        <option value="layout-full" <?php echo ($site['layout_view'] == 'layout-full') ? 'selected' : ''; ?>>Full Width (Fluid)</option>
                        <option value="layout-boxed" <?php echo ($site['layout_view'] == 'layout-boxed') ? 'selected' : ''; ?>>Boxed (Centered with Borders)</option>
                    </select>
                </div>
            </div>
        </div>


        <!-- SEO Tab -->
        <div id="seo" class="tab-content">
            <div class="form-group">
                <label>SEO Meta Title</label>
                <input type="text" name="meta_title" value="<?php echo $site['meta_title']; ?>" required class="form-control">
            </div>
            <div class="form-group">
                <label>SEO Meta Description</label>
                <textarea name="meta_description" required class="form-control" style="height: 100px;"><?php echo $site['meta_description']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Google Analytics Tracking Code</label>
                <textarea name="google_analytics" class="form-control" style="height: 120px; font-family: monospace; font-size: 13px; background: #f8fafc;"><?php echo $site['google_analytics']; ?></textarea>
                <small style="color: #64748b;">Paste your Global Site Tag (gtag.js) here.</small>
            </div>
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #f1f5f9;">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 25px;">
                <div class="form-group">
                    <label>Site Popup Status</label>
                    <select name="popup_status" class="form-control">
                        <option value="1" <?php echo $site['popup_status'] == 1 ? 'selected' : ''; ?>>Enabled (Show)</option>
                        <option value="0" <?php echo $site['popup_status'] == 0 ? 'selected' : ''; ?>>Disabled (Hide)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Popup Image</label>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <?php if ($site['popup_image']): ?>
                            <img src="../uploads/<?php echo $site['popup_image']; ?>" style="height: 50px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        <?php endif; ?>
                        <input type="file" name="popup_image" class="form-control" style="padding: 8px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Tab -->
        <div id="social" class="tab-content">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;">
                <div class="form-group"><label><i class="fab fa-facebook" style="color: #1877f2;"></i> Facebook URL</label><input type="url" name="facebook_url" value="<?php echo $site['facebook_url']; ?>" class="form-control"></div>
                <div class="form-group"><label><i class="fab fa-twitter" style="color: #1da1f2;"></i> Twitter URL</label><input type="url" name="twitter_url" value="<?php echo $site['twitter_url']; ?>" class="form-control"></div>
                <div class="form-group"><label><i class="fab fa-linkedin" style="color: #0a66c2;"></i> LinkedIn URL</label><input type="url" name="linkedin_url" value="<?php echo $site['linkedin_url']; ?>" class="form-control"></div>
                <div class="form-group"><label><i class="fab fa-instagram" style="color: #e4405f;"></i> Instagram URL</label><input type="url" name="instagram_url" value="<?php echo $site['instagram_url']; ?>" class="form-control"></div>
                <div class="form-group"><label><i class="fab fa-youtube" style="color: #ff0000;"></i> YouTube URL</label><input type="url" name="youtube_url" value="<?php echo $site['youtube_url']; ?>" class="form-control"></div>
            </div>
            <hr style="margin: 30px 0; border: none; border-top: 1px solid #f1f5f9;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px;">
                <div class="form-group">
                    <label><i class="fab fa-whatsapp" style="color: #25d366;"></i> WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" value="<?php echo $site['whatsapp_number']; ?>" class="form-control" placeholder="e.g. 919431426600">
                </div>
                <div class="form-group">
                    <label>WhatsApp Button</label>
                    <select name="whatsapp_status" class="form-control">
                        <option value="1" <?php echo $site['whatsapp_status'] == 1 ? 'selected' : ''; ?>>Visible</option>
                        <option value="0" <?php echo $site['whatsapp_status'] == 0 ? 'selected' : ''; ?>>Hidden</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marked-alt"></i> Google Map Embed Code (Iframe)</label>
                <textarea name="google_map" class="form-control" style="height: 100px; font-family: monospace; font-size: 13px;"><?php echo $site['google_map']; ?></textarea>
            </div>
        </div>

        <div class="sticky-footer">
            <button type="submit" name="update_settings" class="btn btn-primary" style="padding: 12px 40px; border-radius: 12px; font-size: 15px; box-shadow: 0 4px 15px rgba(255, 20, 147, 0.3);">
                <i class="fas fa-save" style="margin-right: 8px;"></i> Save All Changes
            </button>
        </div>
    </div>
</form>

<script>
function showTab(tabId) {
    // Update buttons
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('active');
        if(btn.getAttribute('onclick').includes(tabId)) btn.classList.add('active');
    });
    
    // Update content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    document.getElementById(tabId).classList.add('active');
}
</script>

<?php include 'includes/admin_footer.php'; ?>

