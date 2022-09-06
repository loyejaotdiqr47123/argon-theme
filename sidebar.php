<?php if (get_option('argon_page_layout', 'double') == 'single') {
	return;
} ?>
<div id="sidebar_mask"></div>
<aside id="leftbar" class="leftbar widget-area" role="complementary">
		<?php if (get_option('argon_sidebar_announcement') != '') { ?>
			<div id="leftbar_announcement" class="card bg-white shadow-sm border-0">
				<div class="leftbar-announcement-body">
					<div class="leftbar-announcement-title text-white"><?php _e('公告', 'argon');?></div>
					<div class="leftbar-announcement-content text-white"><?php echo get_option('argon_sidebar_announcement'); ?></div>
				</div>
			</div>
		<?php } ?>
		<div id="leftbar_part1" class="widget widget_search card bg-white shadow-sm border-0">
			<div class="leftbar-banner card-body">
				<span class="leftbar-banner-title text-white"><?php echo get_option('argon_sidebar_banner_title') == '' ? bloginfo('name') : get_option('argon_sidebar_banner_title'); ?></span>

				<?php 
					$sidebar_subtitle = get_option('argon_sidebar_banner_subtitle'); 
					if ($sidebar_subtitle == "--hitokoto--"){
						$sidebar_subtitle = "<span class='hitokoto'></span>";
					}
				?>
				<?php if ($sidebar_subtitle != '') { /*左侧栏子标题/格言(如果选项中开启)*/?>
					<span class="leftbar-banner-subtitle text-white"><?php echo $sidebar_subtitle; ?></span>
				<?php } /*顶栏标题*/?>

			</div>

			<?php
				/*侧栏上部菜单*/
				class leftbarMenuWalker extends Walker_Nav_Menu{
					public function start_lvl( &$output, $depth = 0, $args = array() ) {
						$indent = str_repeat("\t", $depth);
						$output .= "\n$indent<ul class=\"leftbar-menu-item leftbar-menu-subitem shadow-sm\">\n";
					}
					public function end_lvl( &$output, $depth = 0, $args = array() ) {
						$indent = str_repeat("\t", $depth);
						$output .= "\n$indent</ul>\n";
					}
					public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
						$output .= "\n
						<li class='leftbar-menu-item" . ( $args -> walker -> has_children == 1 ? " leftbar-menu-item-haschildren" : "" ) . ( $object -> current == 1 ? " current" : "" ) . "'>
							<a href='" . $object -> url . "'" . ( $args -> walker -> has_children == 1 ? " no-pjax onclick='return false;'" : "" ) . " target='" . $object -> target . "'>". $object -> title . "</a>";
					}
					public function end_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
						//if ($depth == 0){
							$output .= "</li>";
						//}
					}
				}
				echo "<ul id='leftbar_part1_menu' class='leftbar-menu'>";
				if ( has_nav_menu('leftbar_menu') ){
					wp_nav_menu( array(
						'container'  => '',
						'theme_location'  => 'leftbar_menu',
						'items_wrap'  => '%3$s',
						'depth' => 0,
						'walker' => new leftbarMenuWalker()
					) );
				}
				echo "</ul>";
			?>
			<div class="card-body text-center leftbar-search-button">
				<button id="leftbar_search_container" class="btn btn-secondary btn-lg active btn-sm btn-block border-0" role="button">
					<i class="menu-item-icon fa fa-search mr-0"></i> <?php _e('搜索', 'argon');?>
					<input id="leftbar_search_input" type="text" placeholder="<?php _e('搜索什么...', 'argon');?>" class="form-control form-control-alternative" autocomplete="off">
				</button>
			</div>
		</div>
		<div id="leftbar_part2" class="widget widget_search card bg-white shadow-sm border-0">
			<div id="leftbar_part2_inner" class="card-body">
				<?php
					$nowActiveTab = 1;/*默认激活的标签*/
					if (have_catalog()){
						$nowActiveTab = 0;
					}
				?>
				<div class="nav-wrapper" style="padding-top: 5px;<?php if (!have_catalog() && !is_active_sidebar('leftbar-tools')) { echo ' display:none;'; }?>">
	                <ul class="nav nav-pills nav-fill" role="tablist">
						<?php if (have_catalog()) { ?>
							<li class="nav-item sidebar-tab-switcher">
								<a class="<?php if ($nowActiveTab == 0) { echo 'active show'; }?>" id="leftbar_tab_catalog_btn" data-toggle="tab" href="#leftbar_tab_catalog" role="tab" aria-controls="leftbar_tab_catalog" no-pjax><?php _e('文章目录', 'argon');?></a>
							</li>
						<?php } ?>
						<li class="nav-item sidebar-tab-switcher">
							<a class="<?php if ($nowActiveTab == 1) { echo 'active show'; }?>" id="leftbar_tab_overview_btn" data-toggle="tab" href="#leftbar_tab_overview" role="tab" aria-controls="leftbar_tab_overview" no-pjax><?php _e('站点概览', 'argon');?></a>
						</li>
						<?php if ( is_active_sidebar( 'leftbar-tools' ) ){?>
							<li class="nav-item sidebar-tab-switcher">
								<a class="<?php if ($nowActiveTab == 2) { echo 'active show'; }?>" id="leftbar_tab_tools_btn" data-toggle="tab" href="#leftbar_tab_tools" role="tab" aria-controls="leftbar_tab_tools" no-pjax><?php _e('功能', 'argon');?></a>
							</li>
						<?php }?>
	                </ul>
				</div>
				<div>
					<div class="tab-content" style="padding: 10px 10px 0 10px;">
						<?php if (have_catalog()) { ?>
							<div class="tab-pane fade<?php if ($nowActiveTab == 0) { echo ' active show'; }?>" id="leftbar_tab_catalog" role="tabpanel" aria-labelledby="leftbar_tab_catalog_btn">
								<div id="leftbar_catalog"></div>
								<script type="text/javascript">
									$(function () {
										$(document).headIndex({
											articleWrapSelector: '#post_content',
											indexBoxSelector: '#leftbar_catalog',
											subItemBoxClass: "index-subItem-box",
											itemClass: "index-item",
											linkClass: "index-link",
											offset: 80,
										});
									})
								</script>
								<?php if (get_option('argon_show_headindex_number') == 'true') {?>
									<style>
										#leftbar_catalog ul {
											counter-reset: blog_catalog_number;
										}
										#leftbar_catalog li.index-item > a:before {
											content: counters(blog_catalog_number, '.') " ";
											counter-increment: blog_catalog_number;
										}
									</style>
								<?php }?>
							</div>
						<?php } ?>
						<div class="tab-pane fade text-center<?php if ($nowActiveTab == 1) { echo ' active show'; }?>" id="leftbar_tab_overview" role="tabpanel" aria-labelledby="leftbar_tab_overview_btn">
							<div id="leftbar_overview_author_image" style="background-image: url(<?php echo get_option('argon_sidebar_auther_image') == '' ? 'https://cravatar.cn/avatar/e626700bc4aa3c1a0ce761c5a01f72a6?s=64&d=mm&r=g' : get_option('argon_sidebar_auther_image'); ?>)" class="rounded-circle shadow-sm" alt="avatar"></div>
							<h6 id="leftbar_overview_author_name"><?php echo get_option('argon_sidebar_auther_name') == '' ? bloginfo('name') : get_option('argon_sidebar_auther_name'); ?></h6>
							<?php $author_desctiption = get_option('argon_sidebar_author_description'); if (!empty($author_desctiption)) {echo '<h6 id="leftbar_overview_author_description">'. $author_desctiption .'</h6>';}?>
							<nav class="site-state">
								<div class="site-state-item site-state-posts">
									<a <?php $archives_page_url = get_option('argon_archives_timeline_url'); echo (empty($archives_page_url) ? ' style="cursor: default;"' : 'href="' . $archives_page_url . '"');?>>
										<span class="site-state-item-count"><?php echo wp_count_posts() -> publish; ?></span>
										<span class="site-state-item-name"><?php _e('文章', 'argon');?></span>
									</a>
								</div>
								<div class="site-state-item site-state-categories">
									<a data-toggle="modal" data-target="#blog_categories">
										<span class="site-state-item-count"><?php echo wp_count_terms('category'); ?></span>
										<span class="site-state-item-name"><?php _e('分类', 'argon');?></span>
									</a>
								</div>      
								<div class="site-state-item site-state-tags">
									<a data-toggle="modal" data-target="#blog_tags">
										<span class="site-state-item-count"><?php echo wp_count_terms('post_tag'); ?></span>
										<span class="site-state-item-name"><?php _e('标签', 'argon');?></span>
									</a>
								</div>
							</nav>
							<?php
								/*侧栏作者链接*/
								class leftbarAuthorLinksWalker extends Walker_Nav_Menu{
									public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
										if ($depth == 0){
											$output .= "\n
											<div class='site-author-links-item'>
												<a href='" . $object -> url . "' rel='noopener' target='_blank'>". $object -> title . "</a>";
										}
									}
									public function end_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
										if ($depth == 0){
											$output .= "</div>";
										}
									}
								}

								if ( has_nav_menu('leftbar_author_links') ){
									echo "<div class='site-author-links'>";
									wp_nav_menu( array(
										'container'  => '',
										'theme_location'  => 'leftbar_author_links',
										'items_wrap'  => '%3$s',
										'depth' => 0,
										'walker' => new leftbarAuthorLinksWalker()
									) );
									echo "</div>";
								}
							?>
							<?php
								/*侧栏友情链接*/
								class leftbarFriendLinksWalker extends Walker_Nav_Menu{
									public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
										if ($depth == 0){
											$output .= "\n
											<li class='site-friend-links-item'>
												<a href='" . $object -> url . "' rel='noopener' target='_blank'>". $object -> title . "</a>";
										}
									}
									public function end_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
										if ($depth == 0){
											$output .= "</li>";
										}
									}
								}

								if ( has_nav_menu('leftbar_friend_links') ){
									echo "<div class='site-friend-links'>
											<div class='site-friend-links-title'><i class='fa fa-fw fa-link'></i> Links</div>
											<ul class='site-friend-links-ul'>";
									wp_nav_menu( array(
										'container'  => '',
									    'theme_location'  => 'leftbar_friend_links',
										'items_wrap'  => '%3$s',
									    'depth' => 0,
										'walker' => new leftbarFriendLinksWalker()
									) );
									echo "</ul></div>";
								}else{
									echo "<div style='height: 20px;'></div>";
								}
							?>
							<?php if ( is_active_sidebar( 'leftbar-siteinfo-extra-tools' ) ){?>
								<div id="leftbar_siteinfo_extra_tools">
									<?php dynamic_sidebar( 'leftbar-siteinfo-extra-tools' ); ?>
								</div>
							<?php }?>
						</div>
						<?php if ( is_active_sidebar( 'leftbar-tools' ) ){?>
							<div class="tab-pane fade<?php if ($nowActiveTab == 2) { echo ' active show'; }?>" id="leftbar_tab_tools" role="tabpanel" aria-labelledby="leftbar_tab_tools_btn">
								<?php dynamic_sidebar( 'leftbar-tools' ); ?>
							</div>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
</aside>
<div class="modal fade" id="blog_categories" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e('分类', 'argon');?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
					$categories = get_categories(array(
						'child_of' => 0,
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => 0,
						'hierarchical' => 0,
						'taxonomy' => 'category',
						'pad_counts' => false
					));
					foreach($categories as $category) {
						echo "<a href=" . get_category_link( $category -> term_id ) . " class='badge badge-secondary tag'>" . $category->name . " <span class='tag-num'>" . $category -> count . "</span></a>";
					}
				?>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="blog_tags" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title"><?php _e('标签', 'argon');?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<?php
					$categories = get_categories(array(
						'child_of' => 0,
						'orderby' => 'name',
						'order' => 'ASC',
						'hide_empty' => 0,
						'hierarchical' => 0,
						'taxonomy' => 'post_tag',
						'pad_counts' => false
					));
					foreach($categories as $category) {
						echo "<a href=" . get_category_link( $category -> term_id ) . " class='badge badge-secondary tag'>" . $category->name . " <span class='tag-num'>" . $category -> count . "</span></a>";
					}
				?>
			</div>
		</div>
	</div>
</div>
<?php
	if (get_option('argon_page_layout') == 'triple'){
		echo '<aside id="rightbar" class="rightbar widget-area" role="complementary">';
		dynamic_sidebar( 'rightbar-tools' );
		echo '</aside>';
	}
?>
