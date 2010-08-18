<?php
/**
 * @package WebsiteChat.net Live Support
 * @author WebsiteChat.net
 * @version 1.0.2
 */
/*
Plugin Name: WebsiteChat.net Live Support
Plugin URI: http://websitechat.net/en/3rd-party/wordpress
Description: This plugin allows to quickly install the live support chat button on any WordPress website.
Author: WebsiteChat.net
Version: 1.0.2
Author URI: http://websitechat.net
License: GPL2
*/

add_action('init', 'websitechat_init');

function websitechat_init()
{
  register_sidebar_widget('WebsiteChat.net Live Support', 'websitechat_widget');

  register_widget_control('WebsiteChat.net Live Support', 'websitechat_widgetcontrol');
}

function websitechat_widget()
{
  $options = get_option('websitechat',array(
    "websitechat_button" => "Paste Live Chat button HTML code here...",
    "websitechat_align"  => 'center'
  ));
  
  $button = $options['websitechat_button'];
  $align  = $options['websitechat_align'];
  
  echo '<div style="width: 100%; text-align: '.$align.';">'.stripslashes($button).'</div>';
}

function websitechat_widgetcontrol()
{
  $options = get_option('websitechat',array(
    "websitechat_button" => "Paste Live Chat button HTML code here...",
    "websitechat_align"  => 'center'
  ));
  
  if ($_POST["websitechat_submit"])
  {
    $options['websitechat_button'] = $_POST["websitechat_button"];
    $options['websitechat_align']  = $_POST["websitechat_align"];
    
    update_option('websitechat', $options);
  }
  
  $button = stripslashes($options['websitechat_button']);
  $align  = $options['websitechat_align'];
  
  $preview = '';
  
  $m = array();
  if (preg_match('/src="(.*)"/iU',$button,$m))
  {
    $preview = '<img src="'.$m[1].'?t='.time().'" alt="Live Chat Button" />';
  }
  
  if ($align == 'left')
  {
    $left = 'checked="checked"';
    $center = '';
    $right = '';
  }
  else if ($align == 'right')
  {
    $left = '';
    $center = '';
    $right = 'checked="checked"';
  }
  else
  {
    $left = '';
    $center = 'checked="checked"';
    $right = '';
  }

$form=<<<EOD
<script type="text/javascript">

var prev_value;

function websitechat_clear(field){
  prev_value = field.value;
  
  field.value = '';
}

function websitechat_restore(field){
  if (!field.value)
  {
    field.value = prev_value;
  }
}

</script>

<p>Add Live Support Chat to your WordPress site in two easy steps:</p>

<ol>
<li>Login into your <a href="http://websitechat.net/login" target="_blank">Customer Portal</a>,  click "Chat Buttons" link in "My Account" panel and copy HTML button code.</li>
<li>Paste Live Chat button HTML code below and click "Save" button.</li>
</ol>

<p>
<input type="hidden" name="websitechat_submit" value="1" />
<textarea onblur="websitechat_restore(this);" onfocus="websitechat_clear(this);" style="font-size: 10px;" name="websitechat_button" cols="30" rows="4" >$button</textarea>
</p>
<p>
Horizontal align:<br/>
<input type="radio" name="websitechat_align" value="left" $left /> Left &nbsp; &nbsp;
<input type="radio" name="websitechat_align" value="center" $center /> Center &nbsp; &nbsp;
<input type="radio" name="websitechat_align" value="right" $right /> Right
</p>

<div id="websitechat_preview" style="width: 100%; text-align: $align;">
$preview
</div>

<div style="font-size: 10px; margin-top: 10px; border-top: 1px solid #BFBFBF;">
Don't have an account yet? <a href="http://websitechat.net/en/register" target="_blank">Register for FREE now.</a>
</div>
EOD;

  echo $form;
}

?>
