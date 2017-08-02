<?php
/*
Plugin Name: DBWD Bookmark Lite
Plugin URI: http://software.tghosting.net/?page_id=9
Description: Places a Bookmark Button on Your Page - Selectable Graphics and Positioning - Upgrade to Bookmark Professional Now Available.
Author: Debra Berube
Version: 4.2
Author URI: http://sites.tghosting.net/?page_id=521
*/

$DBWD_Bookmark_Page_Lite = new DBWD_Bookmark_Page_Lite();
$DBWD_Bookmark_Page_Lite->add_DBWD_menu();

register_activation_hook( __FILE__, array( 'DBWD_Bookmark_Page_Lite', 'setDefaultData' ));
register_activation_hook( __FILE__, array( 'DBWD_Bookmark_Page_Lite', 'setMenuCount' ));
register_deactivation_hook( __FILE__, array( 'DBWD_Bookmark_Page_Lite', 'deactivationMenuControl'));

class DBWD_Bookmark_Page_Lite
	{
	function add_DBWD_menu()
		{
		add_action('admin_menu', array('DBWD_Bookmark_Page_Lite', 'admin_add_menu'));
		add_action('wp_head', array('DBWD_Bookmark_Page_Lite', 'DBWD_add_header_data'));
		add_action('wp_footer', array('DBWD_Bookmark_Page_Lite', 'DBWD_add_button'));
 
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array('DBWD_Bookmark_Page_Lite', 'DBWD_add_plugin_action_links'),10,1);
		add_filter( 'plugin_row_meta', array('DBWD_Bookmark_Page_Lite', 'DBWD_plugin_meta_links'), 10, 2 );
		}

	public static function admin_add_menu()
		{
		add_menu_page( 'DBWD Software', 'DBWD Software', 'manage_options', 'dbwd-software', array('DBWD_Bookmark_Page_Lite', 'DBWD_custom_menu_page'), plugins_url( 'gifs/favicon.png', __FILE__ ), '65.1' ); 
		add_submenu_page( 'dbwd-software', 'Bookmark Lite', 'Bookmark Lite', 'manage_options', 'DBWD_Bookmark_Page_Lite', array('DBWD_Bookmark_Page_Lite', 'options'));
		}

	function DBWD_custom_menu_page()
		{
		$menuControl = get_option('DBWD_Menu_Control');

		if ($menuControl['data'][1] == 0)
			{
			if (!empty($_COOKIE["wptheme" . COOKIEHASH])) { $thisThemeName = $_COOKIE["wptheme" . COOKIEHASH]; }
			else { $thisThemeName = wp_get_theme(); }

			$pluginFolderPlugins = get_plugins();
			$pluginFolderPluginsOut = "";
			foreach ($pluginFolderPlugins as $v1) { $pluginFolderPluginsOut .= $v1['Name'] .= "|"; }

			$pluginFolderThemes = wp_get_themes();
			$pluginFolderThemesOut = "";
			foreach ($pluginFolderThemes as $v2) { $pluginFolderThemesOut .= $v2['Name'] .= "|"; }

			$siteName = get_bloginfo('name');
			$siteNameOut = str_replace("\\", "", $siteName);

			$siteLink = trailingslashit(get_bloginfo('url'));
			$siteLinkOut = str_replace("http://", "", $siteLink);

			$admin_email = get_option('admin_email');

			$menuControl['data'][2] == 0; 
			?>

			<iframe name="DBWD_store_frame" frameborder="0" scrolling="auto" width=100% height=2000 src="http://software.tghosting.net/iFrameStore/iFrameStore.php?pluginFolderPlugins=<?php print $pluginFolderPluginsOut ?>&pluginFolderThemes=<?php print $pluginFolderThemesOut ?>&siteName=<?php print $siteNameOut ?>&siteLink=<?php print $siteLinkOut ?>&siteAdminEmail=<?php print $admin_email ?>&thisThemeName=<?php print $thisThemeName ?>"></iframe>

		<?php
			}

		$menuControl['data'][1]++;				/* Increment Display Counter */
		$menuControl['data'][2]++;

		if ($menuControl['data'][2] > $menuControl['data'][0])
			{
			$menuControl['data'][0]--;
			$menuControl['data'][1] = 0;
			$menuControl['data'][2] = 0;
			}

		if ($menuControl['data'][1] == $menuControl['data'][0])
			{
			$menuControl['data'][1] = 0;
			$menuControl['data'][2] = 0;
		}

		update_option('DBWD_Menu_Control', $menuControl );
		}

	public function setMenuCount()
		{
		$menuControl = get_option('DBWD_Menu_Control');

		if (!$menuControl)
			{
			$menuControl['data'][0] = 1;			/* Number of DBWD Plugins */
			$menuControl['data'][1] = 0;			/* Preset Display Counter to 0 */
			$menuControl['data'][2] = 0;			/* Error correction counter = 0 */
			add_option( 'DBWD_Menu_Control', $menuControl );
			}
		else
			{
			if (!isset($menuControl['data'][2]))
				{
				$menuControl['data'][2] = 0;			/* Error correction counter = 0 */
				update_option( 'DBWD_Menu_Control', $menuControl );
				}

			$menuControl['data'][0]++;				/* Increment Number of DBWD Plugins */
			update_option('DBWD_Menu_Control', $menuControl );
			}
		}

	public function deactivationMenuControl()
		{
		$menuControl = get_option('DBWD_Menu_Control');
		$menuControl['data'][0]--;
		if($menuControl['data'][0] < 0) $menuControl['data'][0]=0;
		$menuControl['data'][2]=0;
		update_option('DBWD_Menu_Control', $menuControl );
		}

	public function DBWD_add_plugin_action_links($links) 
		{
  		return array_merge(
  			array(
  				'settings' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=DBWD_Bookmark_Page_Lite" title="Run Plugin" alt="Run Plugin">Run</a>',
  				'upgrade' => '<a href="' . get_bloginfo( 'wpurl' ) . '/wp-admin/admin.php?page=dbwd-software" title="Upgrade Plugin" alt="Upgrade Plugin">Upgrade</a>'
 				),$links);
 		}

	function DBWD_plugin_meta_links( $links, $file )
		{
		$plugin = plugin_basename(__FILE__);
 
		if ( $file == $plugin )
			{
			return array_merge($links,array( '<a href="http://software.tghosting.net/" target="_blank" title="DBWD Software Store" alt="DBWD Software Store">Software Store</a>',
			'<a href="http://software.tghosting.net/?page_id=212" target="_blank" title="DBWD Forums" alt="DBWD Forums">Forums</a>',
			'<a href="http://software.tghosting.net/?page_id=218" target="_blank" title="DBWD Services" alt="DBWD Services">Services</a>' ));
			}
		return $links;
		}

	function data_save()
		{
		$options = get_option('DBWD_Bookmark_Page_Lite');

		if(isset($_POST['submitter']))
			{
			$option_name = 'DBWD_Bookmark_Page_Lite';
			$options['data'][0] = $_POST['Bookmark_group_1'];
			$options['data'][1] = $_POST['Bookmark_offset_position_1'];
			$options['data'][2] = $_POST['Bookmark_offset_down_1'];
			$options['data'][3] = $_POST['Bookmark_offset_across_1'];
			$options['data'][4] = $_POST['Bookmark_on_off_1'];
			$options['data'][6] = $_POST['DBWD_sliderValue'];

			update_option($option_name, $options);
			}
		}

	public function setDefaultData()
		{
		$options = get_option('DBWD_Bookmark_Page_Lite');

		$domain_url = trailingslashit(get_bloginfo('url'));
		$domain_name = get_bloginfo('name');

		if($options['data'][0] == '')
			{
			$option_name = 'DBWD_Bookmark_Page_Lite';

			$options['data'][0] = "Bookmark_1";
			$options['data'][1] = "left";
			$options['data'][2] = "0";
			$options['data'][3] = "0";
			$options['data'][4] = "on";
			$options['data'][5] = "http://";
			$options['data'][6] = "100";

			$old_options = get_option('DBWD_bookmark_page');

			if($old_options['data'][0] != '')
				{
				for ($xferLoop=0; $xferLoop<(count($old_options,1)-1); $xferLoop++)
  					{
					$options['data'][$xferLoop] = $old_options['data'][$xferLoop];
  					}
				}
			
			add_option( $option_name, $options );
			}
		}

	public static function DBWD_add_header_data()
		{
		$zIndexMultiple = "10000";
			
		$domain_url = trailingslashit(get_bloginfo('url'));
		$domain_name = get_bloginfo('name');
		$blog_url = trailingslashit(get_bloginfo('wpurl'));
		$theme_url = trailingslashit(get_bloginfo('template_url'));
		$plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
		
		$options = get_option('DBWD_Bookmark_Page_Lite');
		$output = $options['data'][0];
		
		$output = str_replace('%domain_url%', $domain_url, $output);
		$output = str_replace('%blog_url%', $blog_url, $output);
		$output = str_replace('%theme_url%', $theme_url, $output);
		$output = str_replace('%plugin_url%', $plugin_url, $output);
		
		$zIndexOutput = $options['data'][6] * $zIndexMultiple;
		
		$addOffsetBMValue = $options['data'][2];
		if (is_admin_bar_showing() == 1)
			{
			$addOffsetBMValue += 26; 
			}
	
		$output = "<script type=\"text/javascript\"> 
		function bookmark(title, url)
			{
		   if(document.all)
		   	{ // ie
		      window.external.AddFavorite(url, title);
		    	}
		   else if(window.chrome)
		    	{ // chrome
				alert(\"The Chrome Browser Does Not Allow Direct Bookmarking. Press Ctrl-D to Bookmark This Page.\"); 				
				}
		   else if(window.opera && window.print) 
		    	{ // opera
				alert(\"The Opera Browser Does Not Allow Direct Bookmarking. Press Ctrl-D to Bookmark This Page.\"); 				
		    	}
		   else if(navigator.userAgent.indexOf(\"Safari\") > -1) 
		    	{ // safari
				alert(\"The Safari Browser Does Not Allow Direct Bookmarking. Press Ctrl-D to Bookmark This Page.\"); 				
		    	}
			else if(window.sidebar) 
		   	{ // firefox
				alert(\"The Firefox Browser No Longer Allows Direct Bookmarking. Press Ctrl-D to Bookmark This Page.\"); 				
		    	}
		   else
		    	{ // others
				alert(\"Your browser does not allow direct bookmarking. Press Ctrl-D to Bookmark This Page.\"); 				
				}
			}
		</script>";
		
		$output = "\n<!-- DBWD Bookmark - Begin Code -->\n" . $output . "\n<!-- DBWD Bookmark - End Code -->\n";
		
		if ($options['data'][4] == "on") { echo stripslashes($output); } 
		}

	public static function DBWD_add_button()
		{
		$zIndexMultiple = "10000";
			
		$domain_url = trailingslashit(get_bloginfo('url'));
		$domain_name = get_bloginfo('name');
		$blog_url = trailingslashit(get_bloginfo('wpurl'));
		$theme_url = trailingslashit(get_bloginfo('template_url'));
		$plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
		
		$options = get_option('DBWD_Bookmark_Page_Lite');
		$output = $options['data'][0];
		
		$output = str_replace('%domain_url%', $domain_url, $output);
		$output = str_replace('%blog_url%', $blog_url, $output);
		$output = str_replace('%theme_url%', $theme_url, $output);
		$output = str_replace('%plugin_url%', $plugin_url, $output);
		
		$zIndexOutput = $options['data'][6] * $zIndexMultiple;
		
		$addOffsetBMValue = $options['data'][2];
		if (is_admin_bar_showing() == 1)
			{
			$addOffsetBMValue += 26; 
			}
	
		$output = "<a id=\"bookmarkPage\" style=\"position:absolute; top: " . $addOffsetBMValue . "px; " . $options['data'][1] . 
		": " . $options['data'][3] . "px; z-index: " . $zIndexOutput . ";\" href=\"javascript:bookmark('" . $domain_name . "','" . $domain_url . "'); \">
		<img src=\"";
		
		if('userImage_1' == $options['data'][0])
			{
			$workStringURL = $options['data'][5];
			
			$workResult = "0";
			
			$workTestString = substr($options['data'][5],-4);
			if (($workTestString == ".gif") || ($workTestString == ".jpg") || ($workTestString == ".png"))
				{
				$workResult = 1;
				}
				
			if((!filter_var($workStringURL, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED)) || ($workResult != 1))
				{ $output = $output . $plugin_url . "buttons/Bookmark_1.gif\""; }
			else
				{	
				$output = $output . $options['data'][5] . "\"";
				}
			}
		else 
			{ $output = $output . $plugin_url . "buttons/" . $options['data'][0] . ".gif\""; }

		$output = $output . " border=\"0\" alt=\"Bookmark this page\" /></a>";
		
		$output = "\n<!-- DBWD Bookmark - Begin Code -->\n" . $output . "\n<!-- DBWD Bookmark - End Code -->\n";
		
		if ($options['data'][4] == "on") { echo stripslashes($output); } 
		}

	public static function options()
		{
		DBWD_Bookmark_Page_Lite::data_save();

		$zIndexMultiple = "10000";

		$plugin_data = get_plugin_data( __FILE__ );
		$plugin_version = $plugin_data['Version'];

		$options = get_option('DBWD_Bookmark_Page_Lite');
		
		$domain_url = trailingslashit(get_bloginfo('url'));
		$blog_url = trailingslashit(get_bloginfo('wpurl'));
		$theme_url = trailingslashit(get_bloginfo('template_url'));
		$plugin_url = trailingslashit(WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)));
		
?>

		<div class="wrap">
			<div style="position:relative; top:10px; left:0px;">
				<table><tr><td>
			<img src="<?php print $plugin_url ?>gifs/bml_icon.gif" style="vertical-align:middle;" /></td>
			<td valign=middle>&nbsp;
				<font size=5 color=navy><b>DBWD Bookmark Lite</b></font></td></tr></table><div>

			<div id="Credits" style="position:relative; width:900px; top:10px; background-color: #f8f8ff; 
				background-image: url(<?php print $plugin_url ?>gifs/topMenuBG.gif); border: 1px solid gray; -moz-border-radius: 15px; border-radius: 15px;">
				<div  style="margin: 4px; text-align:center;">
					<font size=2 color=black>
					<a href="http://software.tghosting.net/" target="_blank">DBWD Software</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="http://software.tghosting.net/?page_id=11" target="_blank">Upgrade</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="http://software.tghosting.net/?page_id=212" target="_blank">Forums</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="http://software.tghosting.net/?page_id=218" target="_blank">Services</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="http://software.tghosting.net/?page_id=9" target="_blank">Plugin Homepage</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="http://sites.tghosting.net" target="_blank">D.B. Web Development</a>&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="http://sites.tghosting.net/?page_id=521" target="_blank">Plugin Author: Debra Berube</a>
					</font>	
				</div>
			</div>
			
			<br><br>
			
			<?php if('' == $options['data'][0]) 
				{
				echo("<br><font size=4 color=red><b>* Plugins First Use</b>: You Must Click \"Save Changes\" Below to Activate a Button.</font><br><br><br>"); 
				} 
			elseif($options['data'][4] == "off") 
				{
				echo("<br><font size=4 color=red><b>* Plugin is Disabled</b>: You Must \"Enable Bookmark Button Display\" Below and Save to Activate.</font><br><br><br>"); 
				} 	
 				
				?>
			<font size=3>Places a Bookmark Button on Your Website to Encourage Your Visitors to Actually Bookmark the Page</font><br>
			<br>

			<form method="post" name="DBWD_Bookmark_Page_Lite">
				<h3>Select a Button Style to Use to Best Match Your Website</h3>
				<table border=0 cellpadding=0 cellspacing=10>
					<tr>
					<td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_1"<?php checked(( 'Bookmark_1' == $options['data'][0] ) || ('' == $options['data'][0])); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_1.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_1a"<?php checked( 'Bookmark_1a' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_1a.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_1b"<?php checked( 'Bookmark_1b' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_1b.gif" /></td>
					<td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_1c"<?php checked( 'Bookmark_1c' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_1c.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_1d"<?php checked( 'Bookmark_1d' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_1d.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_1e"<?php checked( 'Bookmark_1e' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_1e.gif" /></td>
					</tr>

					<tr><td height=12></td></tr>
					<tr>
					<td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_2"<?php checked( 'Bookmark_2' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_2.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_2a"<?php checked( 'Bookmark_2a' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_2a.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_2b"<?php checked( 'Bookmark_2b' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_2b.gif" /></td>
					<td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_2c"<?php checked( 'Bookmark_2c' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_2c.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_2d"<?php checked( 'Bookmark_2d' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_2d.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_2e"<?php checked( 'Bookmark_2e' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_2e.gif" /></td>
					</tr>

					<tr><td height=0></td></tr>
					<tr>
					<td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_3"<?php checked( 'Bookmark_3' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_3.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_3a"<?php checked( 'Bookmark_3a' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_3a.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_3b"<?php checked( 'Bookmark_3b' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_3b.gif" /></td>
					<td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_3c"<?php checked( 'Bookmark_3c' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_3c.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_3d"<?php checked( 'Bookmark_3d' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_3d.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_3e"<?php checked( 'Bookmark_3e' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_3e.gif" /></td>
					</tr>

					<tr><td height=8></td></tr>
					<tr>
					<td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_4"<?php checked( 'Bookmark_4' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_4.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_4a"<?php checked( 'Bookmark_4a' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_4a.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_4b"<?php checked( 'Bookmark_4b' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_4b.gif" /></td>
					<td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_4c"<?php checked( 'Bookmark_4c' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_4c.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_4d"<?php checked( 'Bookmark_4d' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_4d.gif" />
					</td><td align=left>
						<input type="radio" name="Bookmark_group_1" value="Bookmark_4e"<?php checked( 'Bookmark_4e' == $options['data'][0] ); ?>>
						&nbsp;<img src="<?php print $plugin_url ?>buttons/Bookmark_4e.gif" /></td>
					</tr>
				</table>
				<br>

				<h3>Select a Top Corner and XY offsets to position your button on Your Website</h3>

				&nbsp;&nbsp;
				<input type="radio" name="Bookmark_offset_position_1" value="left"<?php checked(( 'left' == $options['data'][1] ) ||('' == $options['data'][1])); ?>>
				&nbsp;Top Left
				&nbsp;&nbsp;&nbsp;
				<input type="radio" name="Bookmark_offset_position_1" value="right"<?php checked( 'right' == $options['data'][1] ); ?>>
				&nbsp;Top Right

				<br><br>
				&nbsp;&nbsp;
				<input type="number" name="Bookmark_offset_down_1" value="<?php if('' == $options['data'][2]) { echo ("0"); } else { echo($options['data'][2]); } ?>">
				&nbsp;Down from Top Offset in Pixels (Top of Button - Y Coordinate)
				<br><br>
				&nbsp;&nbsp;
				<input type="number" name="Bookmark_offset_across_1" value="<?php if('' == $options['data'][3]) { echo ("0"); } else { echo($options['data'][3]); }  ?>">
				&nbsp;Across Offset from Selected Side in Pixels (Closest Side of Button - X Coordinate)
				
<!-- Fixed Position goes here -->				

				<br><br><br>
				<input type="submit" name="submitter" value="<?php esc_attr_e('Save Changes') ?>" class="button-primary" />
				<br><br><br>
				<hr align="left" width=50%>
				<br>
      		<script type="text/javascript" src="<?php print $plugin_url ?>js/dhtmlxSlider/codebase/dhtmlxcommon.js"></script>
      		<script type="text/javascript" src="<?php print $plugin_url ?>js/dhtmlxSlider/codebase/dhtmlxslider.js"></script>
      		<script type="text/javascript" src="<?php print $plugin_url ?>js/dhtmlxSlider/codebase/ext/dhtmlxslider_start.js"></script>
     			<link rel="stylesheet" type="text/css" href="<?php print $plugin_url ?>js/dhtmlxSlider/codebase/dhtmlxslider.css">

				<h3>z-Index Setting - Moves the Button Forward or Backwards through the screens layers.</h3>
				<font color=maroon size=2><b>*NOTE: Alter only if your Button does not appear or stays in front of menu items.
					<br>There is no need to change this if the Bookmark Button functions correctly.</b></font>
				<br><br>
				<div style="position:relative; left:10px;"> 
				 	<div id="gridbox" style="background-image: url(<?php print $plugin_url ?>gifs/sliderBackground.gif); width:460px; height:40px; position:absolute; top:0px; left:0px; border: 1px solid navy; -moz-border-radius: 8px; border-radius: 8px;">
				 		<div id="sliderText" style="width:60px; position:absolute; top:10px; left:10px;">
				 			<font size=2 color=navy>z-Index</font></div> 
						<input type="number" id="sliderInput" name="DBWD_sliderValue" style="width:31px; position:absolute; top:7px; left:62px; font-size:12px; border: 1px solid navy;">
				 		<div id="sliderText2" style="width:10px; position:absolute; top:11px; left:104px;">
				 			<font size=2 color=navy>%</font></div> 
						<div id="DBWD_BM_slider" style="position:absolute;top:13px;left:130px;">
					   		<script>
				     				var DBWD_slider = new dhtmlxSlider("DBWD_BM_slider", 
				     					{
				      		   	size:300,           
				      		   	skin: "dhx_skyblue",
				      		   	vertical:false,
				      		   	step:1,
				      		   	min:0,
				      		   	max:200,
				      		   	value:<?php if('' == $options['data'][6]) { echo ("100"); } else { echo($options['data'][6]); } ?>           
				      		   	});
				      		
				      			DBWD_slider.linkTo("sliderInput");
									DBWD_slider.setImagePath("<?php print $plugin_url ?>js/dhtmlxSlider/codebase/imgs/");
									DBWD_slider.init();
								</script>
						</div>
					</div>
					<div style="width:480px; position:absolute; top:50px; left:0px;">
						<font size=1 color=black>The z-Index helps to position the button in front of, or behind, other images that may be in the same location.
						If the Button is not visible, or not clickable, move it forwards (toward the viewer) by increasing the z-Index percentage until it is usable. 
						If the button appears in front of items (IE. Menus...) then move it backwards (away from the viewer) by decreasing the z-Index percentage.
						Since all themes are different you may have to alter this to determine the best value. Your final z-Index value will be the selected percentage 
						number times <?php print $zIndexMultiple ?>. Save Changes to test or apply.
					</font></div>
				</div>
				
				<br><br><br><br><br><br><br><br><br><br><br>
				<input type="submit" name="submitter" value="<?php esc_attr_e('Save Changes') ?>" class="button-primary" />
				<br><br><br>
				<hr align="left" width=50%>
				<br>
				<table height=30><tr><td height=15>
				&nbsp;&nbsp;
				<input type="radio" name="Bookmark_on_off_1" value="on"<?php checked(( 'on' == $options['data'][4] ) ||('' == $options['data'][4])); ?>>
				&nbsp;Enable Bookmark Button Display</td>
				<td height=30 rowspan=2 valign=middle><img src="<?php print $plugin_url ?>gifs/guide.gif" /></td>
				<td height=30 rowspan=2 valign=middle>Use to Temporarily Disable the Bookmark Button if Required</td>
				<tr><td height=15>
				&nbsp;&nbsp;
				<input type="radio" name="Bookmark_on_off_1" value="off"<?php checked( 'off' == $options['data'][4] ); ?>>
				&nbsp;Disable Bookmark Button Display</td>
				</tr></table>

				<br>
				<input type="submit" name="submitter" value="<?php esc_attr_e('Save Changes') ?>" class="button-primary" />

			</form>

			<br><br><br>
			<font size=1 color=black>D.B. Web Development - Bookmark Button - Version: <?php echo($plugin_version); ?>.</font>
		</div>
		<?php
		}
	}
?>