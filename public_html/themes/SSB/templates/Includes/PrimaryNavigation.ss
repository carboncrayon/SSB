
<ul class="nav navbar-nav">
	<% loop Menu(1) %>	  
		<li class="$LinkingMode $FirstLast">
			<a href="$Link" title="$Title.XML">$MenuTitle.XML<i class="icon-chevron-right visible-xs"></i><i class="ssb-icon $ClassName hidden-xs"></i></a>
		</li>
	<% end_loop %>
</ul>