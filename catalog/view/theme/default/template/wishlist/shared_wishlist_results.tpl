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
 
  <h1>Wish List Search Results</h1>
<?php if($name_lists){?>
  <div class="wishlist-info" style="margin-bottom:50px;">
   
   <?php foreach($name_lists as $name){
    
    echo "Name: ". $name['firstname'].'  '.$name['lastname'].'<br>';
     echo "List Status: ". $name['status'].'<br><br>';
     if($name['status'] == "Active"){
     if($name['lists']){
     echo "<strong>WISH LISTS</strong>".'<br>';
     foreach($name['lists'] as $key => $list){?>

    <a href ="index.php?route=wishlist/shared_wishlist<?php echo '&name=' . $name['firstname'].'&id=' . $name['customerid'] . '&listid=' . $key;?>"><?php echo $list.'<br>';?></a>
    <?php }
     
     }else{
     echo "This person has no wish lists.";
     }
    
    
     }?>
     <br /><br />
     
  <?php }?>
   
  
  </div>

 
  <?php } else { ?>
  <div class="content">Search results came up empty! Try emailing the person intead.</div>
  <div class="buttons">
  <div id="contact">			
            
			<table>
            <tr>
            <td align="right"><?php echo $text_form_your_name; ?></td> <td><input name="yourname" id="yourname" type="text" value="" size="35" maxlength="100" /></td>
            </tr>
			<tr>
             <td align="right"><?php echo $text_form_recipient_name; ?></td> <td><input name="recipientsname" id="recipientsname" type="text" value="" size="35" maxlength="100" /></td>
              </tr>
			  <tr>
             <td align="right"><span class="required">*</span><?php echo $text_form_email; ?></td> <td><input name="email" id="email" type="text" value="" size="35" maxlength="100" /></td>
              </tr>
              <td align="right"><span class="required">*</span><?php echo $text_form_message; ?></td> <td><textarea cols="36"rows="4" name="message" id="message" wrap="hard"></textarea></td>
            </tr>
            <tr><td>&nbsp;</td><td><a id="send" class ="button"><?php echo $text_form_send; ?></a> </td></tr>
            </table>
            </div>
  </div>
  <?php } ?>
   <form action="<?php echo $wishlistsearch; ?>" method="post" enctype="multipart/form-data">
		 <div id="wish-search"><div class="left"><p><span style="font-size:16px; font-weight:bold;">Search Again:</span><br>Enter the following information about your friend or family member:</p>
		 <table><tr><td><strong>Name or E-mail:</strong></td><td align="left"> <input type="text" value="" name="nameoremail"></td></tr><tr><td> <strong>Town or City:</strong> <small>(optional)</small></td> <td align="left"><input type="text" value="" name="townorcity"></td></tr>
		 <tr><td><input type="submit" value="Search" class="button" /></td></tr></table>
		 </div></div></form>
		 <script type="text/javascript"><!--
		
				
	$('#send').bind('click', function() {	


	$.ajax({
		url: 'index.php?route=wishlist/shared_wishlist/contact',	
		type: 'POST',
		data: $('#contact input[type=\'text\'], #contact textarea'),
		dataType: 'json',
		beforeSend: function() {
			
			$('#send').after('<div class="wait"><img src="catalog/view/theme/default/image/loading.gif" alt="" />'+'sending....'+'</div>');
		},
		success: function(json) {	
		$( ".wait" ).remove();
		$( ".error" ).remove();
		$( ".success" ).remove();
		 	if (json['error']['email']) {
					$('#contact input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');	
			}else if (json['error']['message']) {
					$('#contact textarea[name=\'message\']').after('<span class="error">' + json['error']['message'] + '</span>');					
		
			}else{
				
				if(json['error']['success']){
					$( ".wait" ).remove();
					$( ".error" ).remove();
					$( ".success" ).remove();
				 
				 $('#contact').before('<div class="success">' + json['error']['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					 
				}
				
			}
		}
	});
});
$('#wish-search input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#wish-search').submit();
	}
});
//--></script> 
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>