<div id="content-header">
	<h1>Companies</h1>
</div>
<div id="breadcrumb">
	<a href="#" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
	<a href="/companies" class="current">Companies</a>
	<a href="#" class="current">View</a>
</div>


<div class="companies view">
	<h2><?php echo __($company['Company']['name']); ?></h2>
</div>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-content nopadding">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><?php echo __('Title'); ?></th>
								<th><?php echo __('Interval'); ?></th>
								<th><?php echo __('StartTime'); ?></th>
								<th><?php echo __('EndTime'); ?></th>
								<th><?php echo __('StartDate'); ?></th>
								<th><?php echo __('EndDate'); ?></th>
								<th><?php echo __('Color'); ?></th>
								<th><?php echo __('Cpm'); ?></th>
								<th><?php echo __('Active'); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($company['Advert'] as $advert): ?>
							<tr>
								<td><?php echo $advert['title']; ?></td>
								<td><?php echo $advert['interval']; ?></td>
								<td><?php echo $advert['startTime']; ?></td>
								<td><?php echo $advert['endTime']; ?></td>
								<td><?php echo $advert['startDate']; ?></td>
								<td><?php echo $advert['endDate']; ?></td>
								<td><?php echo $advert['color']; ?></td>
								<td><?php echo $advert['cpm']; ?></td>
								<td><?php echo $advert['active']; ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>							
				</div>
			</div>
		</div>
	</div>
</div>