<!DOCTYPE html>
<html>
<head>
<link href="/api/draft/46/exhibit" type="application/json"
	rel="exhibit-data" />
<meta charset="ISO-8859-1">
<title>Insert title here</title>


<link rel="exhibit-extension"
	href="http://api.simile-widgets.org/exhibit/3.0.0/extensions/time/time-extension.js"
	type="text/javascript">

<script src="http://api.simile-widgets.org/exhibit/3.0.0/exhibit-api.js"></script>

</head>

<body>
<?php
// set the default timezone to use. Available since PHP 5.1
date_default_timezone_set('UTC');

$date = new DateTime();
$date->modify('+1 day');
// Prints something like: Monday
echo $date->format('d M Y');
?>
	<div ex:role="controlPanel" ex:developerMode="true"></div>
	<div ex:role="collection" ex:itemTypes="sentence,paragraph"></div>

	<table id="frame">
		<tr>
			<td id="sidebar">
				<h1>US Presidents</h1>
				<p>
					Here is the <a href="/api/draft/46/exhibit" target="_blank">Exhibit
						JSON data file</a>.
				</p>

				<div id="exhibit-browse-panel">
					<b>Search:</b>
					<div ex:role="facet" ex:facetClass="TextSearch"></div>
					<hr />
					<div ex:role="facet" ex:expression=".type"
						ex:facetLabel="Religions" ex:height="20em"></div>
					
				</div>
			</td>
			<td id="content">
				<div ex:role="coder" ex:coderClass="Color" id="party-colors">
					<span ex:color="red">Republican</span> <span ex:color="blue">Democratic</span>

					<span ex:case="others" ex:color="#aaa">Other parties</span> <span
						ex:case="mixed" ex:color="#eee">Many parties</span> <span
						ex:case="missing" ex:color="#444">No party</span>
				</div>

				
			

				<div ex:role="viewPanel">
					
					<div ex:role="view" >
							
						
					</div>
				</div>
			</td>
		</tr>
	</table>


</body>
</html>