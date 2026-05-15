<?php include 'includes/header.php'; ?>

<section class="page-header" style="background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('assets/images/hero.png'); background-size: cover; background-position: center; padding: 100px 0; color: #fff; text-align: center;">
    <div class="container">
        <h1 style="font-size: 3rem; margin-bottom: 10px;">Our Media Gallery</h1>
        <p style="opacity: 0.8; font-size: 1.1rem;">Capturing moments and showcasing our creative journey</p>
    </div>
</section>

<section style="padding: 80px 0; background: #f8fafc;">
    <div class="container">
        <?php if (!isset($_GET['album_id']) && !isset($_GET['slug'])): ?>
            <!-- Album List View -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 30px;">
                <?php
                $albums = mysqli_query($conn, "SELECT a.*, (SELECT COUNT(*) FROM gallery WHERE album_id = a.id) as item_count FROM albums a WHERE status = 1 ORDER BY id DESC");
                if (mysqli_num_rows($albums) > 0):
                    while($alb = mysqli_fetch_assoc($albums)):
                        $album_url = !empty($alb['slug']) ? "gallery/".$alb['slug'] : "gallery.php?album_id=".$alb['id'];
                ?>
                    <a href="<?php echo $album_url; ?>" class="album-card" data-aos="fade-up" style="display: block; text-decoration: none; group;">
                        <div style="position: relative; aspect-ratio: 4/3; overflow: hidden; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); margin-bottom: 20px;">
                            <?php $cover = !empty($alb['cover_photo']) ? 'uploads/'.$alb['cover_photo'] : 'assets/images/hero.png'; ?>
                            <img src="<?php echo $cover; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;" class="album-img">
                            <div style="position: absolute; bottom: 20px; left: 20px; background: rgba(255,255,255,0.9); padding: 5px 15px; border-radius: 50px; font-size: 12px; font-weight: 700; color: var(--primary);">
                                <?php echo $alb['item_count']; ?> Items
                            </div>
                        </div>
                        <h3 style="font-size: 1.4rem; color: var(--dark); margin-bottom: 5px;"><?php echo $alb['name']; ?></h3>
                        <p style="color: #64748b; font-size: 14px; line-height: 1.6;"><?php echo substr($alb['description'], 0, 80); ?>...</p>
                    </a>
                <?php 
                    endwhile;
                else:
                    echo '<div style="text-align:center; grid-column: 1/-1; padding: 100px 0;"><h3>No albums found.</h3></div>';
                endif;
                ?>
            </div>

        <?php else: ?>
            <!-- Album Detail View -->
            <?php
            if (isset($_GET['slug'])) {
                $slug = mysqli_real_escape_string($conn, $_GET['slug']);
                $album_query = mysqli_query($conn, "SELECT * FROM albums WHERE slug = '$slug'");
            } else {
                $album_id = (int)$_GET['album_id'];
                $album_query = mysqli_query($conn, "SELECT * FROM albums WHERE id = $album_id");
            }
            $album = mysqli_fetch_assoc($album_query);
            
            if (!$album) {
                echo "<script>window.location.href='gallery';</script>";
                exit;
            }
            
            $album_id = $album['id'];
            ?>

            <div style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end;">
                <div>
                    <a href="gallery" style="color: var(--primary); font-weight: 700; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px; text-decoration: none;">
                        <i class="fas fa-arrow-left"></i> Back to Albums
                    </a>
                    <h2 style="font-size: 2.5rem; color: var(--dark);"><?php echo $album['name']; ?></h2>
                </div>
            </div>

            <div class="gallery-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <?php
                $media = mysqli_query($conn, "SELECT * FROM gallery WHERE album_id = $album_id AND status = 1 ORDER BY id DESC");
                while($item = mysqli_fetch_assoc($media)):
                ?>
                    <div class="gallery-item" data-aos="zoom-in" style="position: relative; border-radius: 20px; overflow: hidden; cursor: pointer; aspect-ratio: 1/1;" onclick="openLightbox('<?php echo $item['media_type']; ?>', '<?php echo $item['media_type'] == 'photo' ? 'uploads/'.$item['media_url'] : $item['video_id']; ?>')">
                        <?php if ($item['media_type'] == 'photo'): ?>
                            <img src="uploads/<?php echo $item['media_url']; ?>" style="width: 100%; height: 100%; object-fit: cover; transition: 0.5s;">
                        <?php else: ?>
                            <img src="https://img.youtube.com/vi/<?php echo $item['video_id']; ?>/hqdefault.jpg" style="width: 100%; height: 100%; object-fit: cover;">
                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; color: #fff; font-size: 50px;">
                                <i class="fab fa-youtube"></i>
                            </div>
                        <?php endif; ?>
                        <div class="gallery-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.7)); opacity: 0; transition: 0.3s; display: flex; align-items: flex-end; padding: 20px;">
                            <span style="color: #fff; font-weight: 700; text-transform: uppercase; font-size: 12px; letter-spacing: 1px;">View <?php echo $item['media_type']; ?></span>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Lightbox Modal -->
<div id="galleryLightbox" style="display:none; position: fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.95); z-index:99999; backdrop-filter: blur(10px); align-items: center; justify-content: center;">
    <div onclick="closeLightbox()" style="position: absolute; top: 30px; right: 30px; color: #fff; font-size: 30px; cursor: pointer;"><i class="fas fa-times"></i></div>
    <div id="lightboxContent" style="width: 90%; max-width: 1000px; max-height: 80vh; display: flex; align-items: center; justify-content: center;">
        <!-- Content injected by JS -->
    </div>
</div>

<style>
.album-card:hover .album-img { transform: scale(1.1); }
.gallery-item:hover .gallery-overlay { opacity: 1; }
.gallery-item:hover img { transform: scale(1.1); }
</style>

<script>
const lightbox = document.getElementById('galleryLightbox');
const content = document.getElementById('lightboxContent');

function openLightbox(type, source) {
    lightbox.style.display = 'flex';
    if (type === 'photo') {
        content.innerHTML = `<img src="${source}" style="max-width: 100%; max-height: 80vh; border-radius: 10px; box-shadow: 0 20px 50px rgba(0,0,0,0.3);">`;
    } else {
        content.innerHTML = `<iframe width="100%" height="500px" src="https://www.youtube.com/embed/${source}?autoplay=1" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="border-radius: 20px; box-shadow: 0 20px 50px rgba(0,0,0,0.5);"></iframe>`;
    }
    document.body.style.overflow = 'hidden';
}

function closeLightbox() {
    lightbox.style.display = 'none';
    content.innerHTML = '';
    document.body.style.overflow = 'auto';
}

// Close on escape
window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeLightbox();
});
</script>

<?php include 'includes/footer.php'; ?>
