<?php /* Smarty version 2.6.25, created on 2009-12-26 01:07:47
         compiled from default/html/page_js.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'site_url', 'default/html/page_js.tpl', 1, false),array('modifier', 'selfUrl', 'default/html/page_js.tpl', 12, false),array('modifier', 'print_unique_id', 'default/html/page_js.tpl', 37, false),)), $this); ?>
	<script type="text/javascript" src="<?php echo ((is_array($_tmp='media/loadJs')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
"></script>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['base_url']; ?>
templates/<?php echo @DEFAULT_TEMPLATE; ?>
/js/jquery/bigframe.js"></script>
<?php if (isset ( $this->_tpl_vars['page'] )): ?>
<?php if ($this->_tpl_vars['page'] == 'index'): ?>
<?php elseif ($this->_tpl_vars['page'] == 'admin_categories'): ?>
	<script type="text/javascript"><?php echo '
		$(document).ready(function(){
			$("#sortableList").sortable({ containment: \'parent\', axis: \'y\', opacity: 0.6, stop: function(event, ui) {
				showUpdate();
				$.post
				(
					\''; ?>
<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('selfUrl', true, $_tmp) : selfUrl($_tmp)); ?>
<?php echo '\',
					jQuery(\'#add_categories\').serialize(),
					function(html){
						document.location = document.location;
					}
				);
			} });
		});'; ?>

	</script>
<?php elseif ($this->_tpl_vars['page'] == 'edit_group' || $this->_tpl_vars['page'] == 'manage_permissions' || $this->_tpl_vars['page'] == 'edit_user' || $this->_tpl_vars['page'] == 'edit_permission' || $this->_tpl_vars['page'] == 'admin_settings'): ?>
<?php elseif ($this->_tpl_vars['page'] == 'admin' || $this->_tpl_vars['page'] == 'manage_tags' || $this->_tpl_vars['page'] == 'my_account' || $this->_tpl_vars['page'] == 'manage_groups' || $this->_tpl_vars['page'] == 'manage_wallpapers' || $this->_tpl_vars['page'] == 'manage_g_permissions' || $this->_tpl_vars['page'] == 'user_search' || $this->_tpl_vars['page'] == 'manage_u_permissions' || $this->_tpl_vars['page'] == 'manage_users' || $this->_tpl_vars['page'] == 'visitor_searches'): ?>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['base_url']; ?>
templates/<?php echo @DEFAULT_TEMPLATE; ?>
/js/tablesorter.js"></script>
<?php elseif ($this->_tpl_vars['page'] == 'wallpaper'): ?>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['base_url']; ?>
templates/<?php echo @DEFAULT_TEMPLATE; ?>
/js/jquery/rater.js"></script>
<?php endif; ?>

<?php if ($this->_tpl_vars['page'] == 'manage_wallpapers' || $this->_tpl_vars['page'] == 'my_account' || $this->_tpl_vars['page'] == 'visitor_searches' || $this->_tpl_vars['page'] == 'manage_users'): ?>
        <script type="text/javascript" src="<?php echo $this->_tpl_vars['base_url']; ?>
templates/<?php echo @DEFAULT_TEMPLATE; ?>
/js/jquery/autosuggest.js"></script>
<?php endif; ?>

	<script type="text/javascript">
<?php if ($this->_tpl_vars['page'] == 'oldest_wallpapers'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="welcome/get_wallpapers/date_added/ASC")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'popular_wallpapers'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="welcome/get_wallpapers/hits/DESC")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'less_popular_wallpapers'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="welcome/get_wallpapers/hits/ASC")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'worst_rated_wallpapers'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="welcome/get_wallpapers/rating/ASC")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'top_wallpapers'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="welcome/get_wallpapers/rating/DESC")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'latest_wallpapers'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="welcome/get_wallpapers/date_added/DESC")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'random_wallpapers'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="welcome/get_wallpapers/RAND()")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'wallpaper'): ?>
<?php echo '
		$(function(){
			$("#more_from_category").load("'; ?>
<?php echo ((is_array($_tmp="wallpapers/more_from_category/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",function(){
				$("#more_from_author").load("'; ?>
<?php echo ((is_array($_tmp="wallpapers/get_more_from_author/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",function(){
					$("#wallpaper_comments").load("'; ?>
<?php echo ((is_array($_tmp="comments/get_wallpaper_comments/".($this->_tpl_vars['segment_3'])."/0")) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",function(){
						$("#wallpaper_colors").load("'; ?>
<?php echo ((is_array($_tmp="wallpapers/get_wallpaper_colors/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",function(response){
							if(response!=\'\'){
								$(\'#tr_wallpaper_colors\').show();
							}
							$("#wallpaper_tags").load("'; ?>
<?php echo ((is_array($_tmp="wallpapers/get_wallpaper_tags/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",function(response){
								if(response!=\'\'){
									$(\'#tr_wallpaper_tags\').show();
								}
								$("#next_prev_wallpapers").load("'; ?>
<?php echo ((is_array($_tmp="wallpapers/get_next_prev_wallpapers/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",function(){
									$(\'#wallpaper_breadcrumb\').load("'; ?>
<?php echo ((is_array($_tmp="wallpapers/get_wallpaper_breadcrumb/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",function(){
										$(\'#tr_wallpaper_breadcrumb\').show();
										hideUpdate();
									});
								});
							});
						});
					});
				});
			});
			
			window.setTimeout(slide_advert,10000);
			function slide_advert(){
				$(\'#wall_advert\').show();
			}
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'categories'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#categories_wrp").load("'; ?>
<?php echo ((is_array($_tmp="categories/fetch/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '");
			$("#wallpapers_wrp").load("'; ?>
<?php echo ((is_array($_tmp="wallpapers/fetch_by_category/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'tags'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="tags/fetch/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'browse_by_color'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="colors/fetch/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'browse_by_size' || $this->_tpl_vars['page'] == 'browse_by_type'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="wallpapers/fetch_by_size/".($this->_tpl_vars['segment_3'])."/".($this->_tpl_vars['segment_4']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'member_wallpapers'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#wallpapers_wrapper").load("'; ?>
<?php echo ((is_array($_tmp="members/fetch_wallpapers/".($this->_tpl_vars['segment_3']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php elseif ($this->_tpl_vars['page'] == 'search_results'): ?>
<?php echo '
		$(function(){
			showUpdate();
			$("#content").load("'; ?>
<?php echo ((is_array($_tmp="search/fetch/0/".($this->_tpl_vars['sess']['search_id']))) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
/0/0/<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('print_unique_id', true, $_tmp) : print_unique_id($_tmp)); ?>
<?php echo '",hideUpdate);
		});
'; ?>

<?php endif; ?>
	</script>
<?php endif; ?>

<?php if (@SITE_HAS_ADULT_MATERIALS && ! $this->_tpl_vars['adult_confirmed']): ?><?php echo '
	<script type="text/javascript">
		$(function(){
			$("#adult_confirmation_wrapper").dialog({
				title: \'Adult content notification\',
				resizable: false,
				height:160,
				width:600,
				closeOnEscape:false,
				modal: true,
				buttons: {
					\'I\\\'m allowed to view adult content\': function() {
						$.get('; ?>
"<?php echo ((is_array($_tmp='generic_messages/confirm_adult')) ? $this->_run_mod_handler('site_url', true, $_tmp) : site_url($_tmp)); ?>
"<?php echo ',function(){
							parent.document.location = parent.document.location;
						});
					}
				},
				beforeclose: function(event, ui) {return false;}
			});
		});'; ?>

	</script>
<?php endif; ?>
	<script type="text/javascript" src="<?php echo $this->_tpl_vars['base_url']; ?>
templates/<?php echo @DEFAULT_TEMPLATE; ?>
/js/custom.js"></script>