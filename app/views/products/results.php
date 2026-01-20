<?php if (empty($data['products'])): ?>
    <p style="text-align: center; width: 100%; grid-column: 1/-1; padding: 20px; color: #777;">
        No products found matching your search.
    </p>
<?php else: ?>

    <?php foreach ($data['products'] as $product) : ?>
        <div class="product-card">
            <div class="card-image" style="background-color: #f9f9f9; padding: 0;">
                <?php if ($product->image != 'no_image.png'): ?>
                    <img src="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>"
                        alt="<?php echo $product->title; ?>"
                        style="width:100%; height:100%; object-fit:cover;">
                <?php else: ?>
                    <img src="<?php echo URLROOT; ?>/img/no_image.png" style="width:100%; height:100%; object-fit:cover; opacity: 0.5;">
                <?php endif; ?>
            </div>
            <div class="card-details">
                <h4><?php echo $product->title; ?></h4>
                <p class="price"><?php echo $product->price; ?> Tk</p>
                <p class="seller">Sold by: <?php echo isset($product->fullname) ? $product->fullname : 'Unknown'; ?></p>
                <a href="<?php echo URLROOT; ?>/products/show/<?php echo $product->productId; ?>">
                    <button style="width:100%;">View Details</button>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>