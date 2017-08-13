<br />
<br />
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				Top <?=$repo_list_count?> Public PHP Github Repositories - Most Starred
			</div>
			<!-- /.panel-heading -->
			<div class="panel-body">
				<table width="100%" class="table table-striped table-bordered table-hover" id="starred-table">
					<thead>
						<tr>
							<th>Name</th>
							<th>Stargazers</th>							
							<th>Description</th>
							<th>Created</th>
							<th>Updated</th>
							<th>Repository URL</th>
						</tr>
					</thead>
					<tbody>
					
					<?php foreach($repo_list as $repo){ ?>
						<tr>
							<td><a href='<?= $repo['html_url'] ?>' target='_blank'><?= $repo['name'] ?></a></td>
							<td class="text-right"><?= $repo['stargazers_count'] ?></td>							
							<td><?= $repo['description'] ?></td>
							<td nowrap><?= $repo['created_at'] ?></td>
							<td nowrap><?= $repo['updated_at'] ?></td>
							<td><a href='<?= $repo['html_url'] ?>' target='_blank'><?= $repo['html_url'] ?></a></td>
						</tr>						
					<?php } ?>
					
					</tbody>
				</table>
				<!-- /.table-responsive -->
				<div class="well">
					<h4>Table Usage Information</h4>
					<p>Click on the column heading to sort by that column.  Use the directional arrows to determine sorting direction.</p>
					<p>To view the repository on Github, you can click on the respository name. </p>
				</div>
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
</div>
<!-- /.row -->            
            
<script>
$(document).ready(function() {
	$('#starred-table').DataTable({
		responsive: true,
		 "order": [[ 1, "desc" ]],
		 "aoColumnDefs": [{ "bSortable": false, "aTargets": [ 2 ] }],
		 "iDisplayLength": 100,
		 "aLengthMenu": [
			[25, 50, 100, -1],
			[25, 50, 100, "All"] // change per page values here
		]
	});
	
});
</script>            
            