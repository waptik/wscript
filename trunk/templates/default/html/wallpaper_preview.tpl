	<li class="picture_wrapper{$margin_append}">
{if $display == 'box'}
		<span class="title">{$row->file_title|__character_limiter:20}</span>
		<ul class="star-rating">
			<li><span class="{if $row->rating && $row->rating==1}star-rating-readonly-one{/if}">1</span></li>
			<li><span class="{if $row->rating && $row->rating==2}star-rating-readonly-two{/if}">2</span></li>
			<li><span class="{if $row->rating && $row->rating==3}star-rating-readonly-three{/if}">3</span></li>
			<li><span class="{if $row->rating && $row->rating==4}star-rating-readonly-four{/if}">4</span></li>
			<li><span class="{if $row->rating && $row->rating==5}star-rating-readonly-five{/if}">5</span></li>
		</ul>
{/if}
{if $display == 'list'}
		<div class="list_data">
			<span class="list_title">{$row->file_title}</span>
			<ul class="left_c">
				<li class="data">Author</li>
				<li class="data">Date added</li>
				<li class="data">Hits</li>
				<li class="data">Downloads</li>
				<li class="data">Rating</li>
			</ul>
			
			<ul class="right_c">				
				<li class="data"><a href="{"members/show/`$row->user_id`"|site_url}">{$row->Username}</a></li>
				<li class="data">{"%d-%M-%Y"|mdate:$row->date_added}</li>
				<li class="data">{$row->hits}</li>
				<li class="data">{$row->downloads}</li>
				<li class="data">
					<ul class="star-rating">
						<li><span class="{if $row->rating && $row->rating==1}star-rating-readonly-one{/if}">1</span></li>
						<li><span class="{if $row->rating && $row->rating==2}star-rating-readonly-two{/if}">2</span></li>
						<li><span class="{if $row->rating && $row->rating==3}star-rating-readonly-three{/if}">3</span></li>
						<li><span class="{if $row->rating && $row->rating==4}star-rating-readonly-four{/if}">4</span></li>
						<li><span class="{if $row->rating && $row->rating==5}star-rating-readonly-five{/if}">5</span></li>
					</ul>
				</li>
			</ul>
			<div class="preview_download">
				<span style="-moz-user-select: none;" class="ui-icon ui-icon-arrowthick-1-s"><!-- --></span>
				<a title="{"download"|Lang} {$row->file_title} {"wallpaper"|Lang}" href="javascript:dialog(600,260,'{"download"|Lang} {$row->file_title}',true,true,'{"wallpapers/show_download/`$row->ID`"|site_url}')">{'download'|Lang}</a>
			</div>
		</div>
{/if}
		
		<a class="preview {$row->type}" style="background-image:url({$row|get_wallpaper_url_location}thumb_{$row->hash}.jpg);" href="{$row|get_wallpaper_url}">
			<img src="{$images_path}pixel.gif" alt="{$row->file_title}" />
		</a>
	</li>
