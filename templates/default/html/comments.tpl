<div style="margin-top:10px">
<h3 class="headers gray">{'comments'|@Lang}</h3>
<fieldset id="comments_wrp">
{if $rows->num_rows()>0}
<div class="comments">
<ul>
{foreach from=$rows->result() item=comment}
	<li style="background-image:url({$comment->c_email|get_gravatar})">
		<h4>{$comment->c_name}</h4>
{if mdate("%d-%M-%Y",$comment->date_added) == mdate("%d-%M-%Y")}
		<span>{"%h:%i %a"|mdate:$comment->date_added}</span>
{else}
		<span>{"%d-%M-%Y %h:%i %a"|mdate:$comment->date_added}</span>
{/if}
		<p>{$comment->c_comment}</p>
	</li>
{/foreach}
</ul>
</div>
{else}
	<div class="info_messages info"><div class="inner">{'no_comments'|Lang}</div></div>
{/if}
{$pagination}
</fieldset>
<div class="job_indicators" style="padding-left:10px">{$button}</div>
</div>
