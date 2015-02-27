<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $heading_title; ?></title>
</head>
<body>

<div style="page-break-after: always;">
  <h1><?php echo $heading_title; ?></h1>  
			
		
  <?php if ($products) { ?>
  <div class="wishlist-info">
 
     
      <?php foreach ($products as $product) { ?>
      <tbody id="wishlist-row<?php echo $product['product_id']; ?>">
        <tr>
          <td class="image"><?php if ($product['thumb']) { ?>
          <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
            <?php } ?></td>
          <td class="name"><?php echo $product['name']; ?> <br><br>
						<?php if($product['options']){ ?>
						<?php foreach($product['options'] as $option){?>
						<?php echo $option['name'];?><br>
						<small><?php echo "-".$option['option_value'];?></small><br><br>
						<?php }?>
						<?php }?></td>
          <td class="model"><?php echo $product['model']; ?></td>
          <td class="stock"><?php echo $product['stock']; ?></td>
          <td class="price"><?php if ($product['price']) { ?>
            <div class="price">
              <?php if (!$product['special']) { ?>
              <?php echo $product['price']; ?>
              <?php } else { ?>
              <s><?php echo $product['price']; ?></s> <b><?php echo $product['special']; ?></b>
              <?php } ?>
            </div>
            <?php } ?></td>
         
        </tr>
      </tbody>
      <?php } ?>
    </table>
  </div>
   <?php } ?>
  </div>
</body>
</html>