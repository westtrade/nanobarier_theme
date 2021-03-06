<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see bootstrap_preprocess_page()
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see bootstrap_process_page()
 * @see template_process()
 * @see html.tpl.php
 *
 ** @ingroup themeable
* 
*
*
*/
?>


<div id="container">
	<?php include ($directory."/includes/head.php"); ?>
	
	<div class="white" id="product_page">
		<div class="container">

				<div class="row">

					<div class="col-xs-4">
						<div class="left_col">


							<?php if( !empty( $node_wrapper->field_product_cover->value() ) ): ?>
								<div class="main_image">

									<?php 
										// Main image 

										$image = field_get_items('node', $node, 'field_product_cover');
										$output = 

											field_view_value('node', $node, 'field_product_cover', $image[0], 
													array(
														'type' => 'image',
														'settings' => array(
														'image_style' => '220x280',
														),
													)
												);

										if( !empty( $output ) ) echo render( $output );
									?>

								</div>
							<?php endif; ?>


							<div class="fields_wrapper">
								<div class="attached_files">

									<?php
										// Attached files

										$files = $node_wrapper->field_attached_files->value();
										foreach ($files as $fid => $file) :							
											$url_to_file = file_create_url($file['uri']);
											$spl = explode('.', $file['uri']);
											$label = '<span class="file_icon '. $spl[ count($spl) - 1 ] .'"></span>';

											$label .= !empty( $file['description'] ) ? $file['description'] : basename( $file['uri'],  '.'.$spl[ count($spl) - 1 ] );
										?>

										<?php 

											echo l($label, 'http://docs.google.com/viewer?url='.$url_to_file, 
												array( 'html' => true , 'attributes' => array( 'target' => '_blank' ) )
											);	

										?>
									<?php endforeach; ?>
								</div>

								<?php if( !empty( $node_wrapper->field_attached_files->value() ) ):?>
									<?php echo l( t('Question-reply'), 'faq/'.$node->nid, array( 'attributes' => array( 'class' => array( 'btn', 'btn-lg', 'btn-preordered'  ) ) ) ); ?>
								<?php  endif; ?>
							</div>

						</div>			

					</div>



					<div class="col-xs-8">	

						<div class="page_content">				
							<?php print render($title_prefix); ?>

							<?php if (!empty($title) ): ?>
									<h1 class="page-header"><?php print mb_strtoupper($title) ; ?></h1>
							<?php endif; ?>

							<?php print render($title_suffix); ?>

							<?php if(!empty($messages)) : ?>
								<br>
								<?php print $messages; ?>
								<br>
							<?php endif; ?>
						
							<?php if ( !empty($tabs) && ( !empty($tabs["#primary"]) ||  !empty($tabs["#primary"]) ) ): ?>
								<?php if(empty($messages)) : ?> <br><?php endif; ?>
								<?php print render($tabs); ?>
								<br>
							<?php endif; ?>


							<?php print render($page['content']); ?>
						</div>


					</div>	
				

				</div>	<!--/row -->
		</div>
	</div>
</div>


	<div class="container">		
		<div class="row">

				<div class="col-xs-12">

					<div id="order_form">
						<?php 
							$form =  $is_preordered ? drupal_get_form('nbmod_preorder_form') : __nbmod_cart_form($node);

							$messages = theme_status_messages(array( 'display' => 'error' ));
							$messages = !empty( $messages ) ? "<div class=\"alert alert-block alert-danger\">{$messages}</div>" : $messages;

							$success_messages = theme_status_messages(array( 'display' => 'status' ));
							$success_messages = !empty( $success_messages ) ? "<div class=\"alert alert-block alert-success\">{$success_messages}</div>" : $success_messages;

							echo str_replace( 
								'__messages__', 
								$messages.$success_messages ,
								render($form)
							);

						?>
					</div>					
				</div>	

		</div>
	</div>



<?php include ($directory."/includes/footer.php"); ?>
