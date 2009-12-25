	<script type="text/javascript" src="{'media/loadJs'|site_url}"></script>
	<script type="text/javascript" src="{$base_url}templates/{$smarty.const.DEFAULT_TEMPLATE}/js/jquery/bigframe.js"></script>
{if isset ( $page )}
{if $page == 'index'}
{elseif $page == 'admin_categories'}
	<script type="text/javascript">{literal}
		$(document).ready(function(){
			$("#sortableList").sortable({ containment: 'parent', axis: 'y', opacity: 0.6, stop: function(event, ui) {
				showUpdate();
				$.post
				(
					'{/literal}{''|selfUrl}{literal}',
					jQuery('#add_categories').serialize(),
					function(html){
						document.location = document.location;
					}
				);
			} });
		});{/literal}
	</script>
{elseif $page == 'edit_group' || $page == 'manage_permissions' || $page == 'edit_user' || $page == 'edit_permission' || $page == 'admin_settings'}
{elseif $page == 'admin' || $page == 'manage_tags' || $page == 'my_account' || $page == 'manage_groups' || $page == 'manage_wallpapers' || $page == 'manage_g_permissions' || $page == 'user_search' || $page == 'manage_u_permissions' || $page == 'manage_users' || $page == 'visitor_searches'}
	<script type="text/javascript" src="{$base_url}templates/{$smarty.const.DEFAULT_TEMPLATE}/js/tablesorter.js"></script>
{elseif $page == 'wallpaper'}
	<script type="text/javascript" src="{$base_url}templates/{$smarty.const.DEFAULT_TEMPLATE}/js/jquery/rater.js"></script>
{/if}

{if $page == 'manage_wallpapers' || $page == 'my_account' || $page == 'visitor_searches' || $page == 'manage_users'}
        <script type="text/javascript" src="{$base_url}templates/{$smarty.const.DEFAULT_TEMPLATE}/js/jquery/autosuggest.js"></script>
{/if}

	<script type="text/javascript">
{if $page=='oldest_wallpapers'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"welcome/get_wallpapers/date_added/ASC"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif $page=='popular_wallpapers'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"welcome/get_wallpapers/hits/DESC"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif $page=='less_popular_wallpapers'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"welcome/get_wallpapers/hits/ASC"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif $page=='worst_rated_wallpapers'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"welcome/get_wallpapers/rating/ASC"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif $page=='top_wallpapers'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"welcome/get_wallpapers/rating/DESC"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif $page=='latest_wallpapers'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"welcome/get_wallpapers/date_added/DESC"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif $page=='random_wallpapers'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"welcome/get_wallpapers/RAND()"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif $page=='wallpaper'}
{literal}
		$(function(){
			$("#more_from_category").load("{/literal}{"wallpapers/more_from_category/`$segment_3`"|site_url}/0/0/{''|print_unique_id}{literal}",function(){
				$("#more_from_author").load("{/literal}{"wallpapers/get_more_from_author/`$segment_3`"|site_url}/{''|print_unique_id}{literal}",function(){
					$("#wallpaper_comments").load("{/literal}{"comments/get_wallpaper_comments/`$segment_3`/0"|site_url}/{''|print_unique_id}{literal}",function(){
						$("#wallpaper_colors").load("{/literal}{"wallpapers/get_wallpaper_colors/`$segment_3`"|site_url}/{''|print_unique_id}{literal}",function(response){
							if(response!=''){
								$('#tr_wallpaper_colors').show();
							}
							$("#wallpaper_tags").load("{/literal}{"wallpapers/get_wallpaper_tags/`$segment_3`"|site_url}/{''|print_unique_id}{literal}",function(response){
								if(response!=''){
									$('#tr_wallpaper_tags').show();
								}
								$("#next_prev_wallpapers").load("{/literal}{"wallpapers/get_next_prev_wallpapers/`$segment_3`"|site_url}/{''|print_unique_id}{literal}",function(){
									$('#wallpaper_breadcrumb').load("{/literal}{"wallpapers/get_wallpaper_breadcrumb/`$segment_3`"|site_url}/{''|print_unique_id}{literal}",function(){
										$('#tr_wallpaper_breadcrumb').show();
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
				$('#wall_advert').show();
			}
		});
{/literal}
{elseif $page=='categories'}
{literal}
		$(function(){
			showUpdate();
			$("#categories_wrp").load("{/literal}{"categories/fetch/`$segment_3`"|site_url}/0/0/{''|print_unique_id}{literal}");
			$("#wallpapers_wrp").load("{/literal}{"wallpapers/fetch_by_category/`$segment_3`"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif $page=='tags'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"tags/fetch/`$segment_3`"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif  $page=='browse_by_color'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"colors/fetch/`$segment_3`"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif  $page=='browse_by_size' || $page=='browse_by_type'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"wallpapers/fetch_by_size/`$segment_3`/`$segment_4`"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif  $page=='member_wallpapers'}
{literal}
		$(function(){
			showUpdate();
			$("#wallpapers_wrapper").load("{/literal}{"members/fetch_wallpapers/`$segment_3`"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{elseif  $page=='search_results'}
{literal}
		$(function(){
			showUpdate();
			$("#content").load("{/literal}{"search/fetch/0/`$sess.search_id`"|site_url}/0/0/{''|print_unique_id}{literal}",hideUpdate);
		});
{/literal}
{/if}
	</script>
{/if}

{if $smarty.const.SITE_HAS_ADULT_MATERIALS && ! $adult_confirmed}{literal}
	<script type="text/javascript">
		$(function(){
			$("#adult_confirmation_wrapper").dialog({
				title: 'Adult content notification',
				resizable: false,
				height:160,
				width:600,
				closeOnEscape:false,
				modal: true,
				buttons: {
					'I\'m allowed to view adult content': function() {
						$.get({/literal}"{'generic_messages/confirm_adult'|site_url}"{literal},function(){
							parent.document.location = parent.document.location;
						});
					}
				},
				beforeclose: function(event, ui) {return false;}
			});
		});{/literal}
	</script>
{/if}
	<script type="text/javascript" src="{$base_url}templates/{$smarty.const.DEFAULT_TEMPLATE}/js/custom.js"></script>