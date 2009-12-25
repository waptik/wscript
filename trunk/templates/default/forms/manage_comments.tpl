<div id="comments_wrp" class="comments">
{if $rows->num_rows()>0}
<ul>
{foreach from=$rows->result() item=comment}
	<li style="background-image:url({$comment->c_email|get_gravatar})">
		<h4>{$comment->c_name} - <a href="{"wallpapers/show/`$comment->w_ID`"|site_url}" class="img_tooltip" rel="{$comment->w_ID|get_wallpaper_url_location_fixed:$comment->w_date_added}thumb_{$comment->w_hash}.jpg">{$comment->w_title}</a></h4>
{if mdate("%d-%M-%Y",$comment->date_added) == mdate("%d-%M-%Y")}
		<span>{"%h:%i %a"|mdate:$comment->date_added}</span>
{else}
		<span>{"%d-%M-%Y %h:%i %a"|mdate:$comment->date_added}</span>
{/if}
		<p>{$comment->c_comment}</p><br />
		<a class="action" href="javascript:delete_comment('{$comment->ID}');">Delete</a>{if !$comment->active}&nbsp;&nbsp;|&nbsp;&nbsp;<a class="action" style="color:green!important" href="javascript:approve_comment('{$comment->ID}');">Approve</a>{/if}
	
	</li>
{/foreach}
{$pagination}
</ul>
{else}
	<div class="info_messages">{'no_comments_manage'|Lang}</div>
{/if}
</div>

{$pagination}