<div id="search">
        <form method="post" action="{"search/results"|site_url}" id="search_form">
                <input type="text" name="search_for" value="{'search'|Lang}..." onclick="javascript:this.focus();this.select();" id="search_input" />
        </form>
</div>
<form action="" method="post" onsubmit="return false;" id="sections_frm" name="sections_frm">
{$sidebarContents}
</form>