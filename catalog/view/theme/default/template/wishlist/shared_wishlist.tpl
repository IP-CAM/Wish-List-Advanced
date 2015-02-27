<?php
//==============================================================================
// Wish List Extended
// 
// Author: Avvici, Involution Media
// E-mail: joseph@involutionmedia.com
// Website: http://www.involutionmedia.com
//==============================================================================
//Edit code with care. Always comment your code to take notes on what was changed so you can go back to it later.
//==============================================================================

?>
<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $page_title; ?></h1>
  <?php if ($products) { ?>
  <div class="wishlist-info">
    <table>
      <thead>
        <tr>
          <td class="image"><?php echo $column_image; ?></td>
          <td class="name"><?php echo $column_name; ?></td>
          <td class="model"><?php echo $column_model; ?></td>
          <td class="stock"><?php echo $column_stock; ?></td>
          <td class="price"><?php echo $column_price; ?></td>
          <td align="center"><?php echo $column_share; ?></td>
         <td align="center"><?php echo $column_add; ?></td>
        </tr>
      </thead>
      <?php $counter = 1;?>
                
      <?php foreach ($products as $product) { ?>
     
<script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script> 
      <tbody>
        <tr>
         <td width="82"><?php if ($product['thumb']) { ?>
            <div id="drag<?php echo $counter;?>" class ="drag_mod"> <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a></div>
            <?php } ?></td>
          <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          
         <br><br>
						<?php if($product['options']){ ?>
						<?php foreach($product['options'] as $option){?>
						<?php echo $option['name'];?><br>
						<small><?php echo "-".$option['option_value'];?></small><br><br>
						<?php }?>
						<?php }?>
         
          </td>
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
            <td align="center"><a class="addthis_button_facebook"
       addthis:url="<?php echo HTTP_SERVER;?>index.php?route=product/product&product_id=<?php echo $product['product_id'];?>"
       addthis:title="<?php echo $product['name']; ?>"
       addthis:description="<?php echo $product['name']; ?>"></a> <br><a class="addthis_button_twitter"
       addthis:url="<?php echo HTTP_SERVER;?>index.php?route=product/product&product_id=<?php echo $product['product_id'];?>"
       addthis:title="<?php echo $product['name']; ?>"
       addthis:description="<?php echo $product['name']; ?>"></a> </td>
<td class="action"><a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button">Add To Cart</a></td>        </tr>
      </tbody>
      <?php $counter++;?>
           
      <?php } ?>
    </table>
  </div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>