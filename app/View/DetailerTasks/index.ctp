<?php pr($tasks); ?>
<div class="page-header"><h1>Detailer tasks</h1></div>
<div class="row">
	<div class="col-md-6">
    	<div class="panel panel-default">
            <div class="panel-heading">Detailer tasks</div>
            <div class="panel-body">
            
            	<table class="table no-margn">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Task Name</th>
                      <th>Message</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($tasks as $task): ?>
						<tr>
							<td><?php echo $task["id"]; ?></td>
							<td><?php echo $task["name"]; ?></td>
							<td><?php echo $task["message"]; ?></td>
						</tr>
					<?php endforeach; ?>
                  </tbody>
                </table>
            </div>
        </div>
    </div>
</div>