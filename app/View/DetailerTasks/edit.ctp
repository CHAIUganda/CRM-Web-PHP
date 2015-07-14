<div id="content-header">
	<h1>Companies</h1>
	
</div>
<div id="breadcrumb">
	<a href="/" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a>
	<a href="/companies"> Companies</a>
	<a href="/companies/edit/<?=$this->data['Company']['id'];?>" class="current">Edit</a>
</div>

<div class="container-fluid">
	<div class="row-fluid">
		<div class="span12">
			<div class="widget-box">
				<div class="widget-content nopadding">
					<form action="/companies/edit/<?=$this->data['Company']['id'];?>" method="post" class="form-horizontal" />
						<div class="control-group">
							<input name="data[Company][id]" value="<?=$this->data['Company']['id'];?>" id="CompanyId" type="hidden">
							<label class="control-label">Name</label>
							<div class="controls">
								<input name="data[Company][name]" type="text" value="<?=$this->data['Company']['name'];?>"/>
							</div>
						</div>
						<div class="form-actions">
							<button type="submit" class="btn btn-primary">Save</button>
						</div>
					</form>
				</div>
			</div>						
		</div>
	</div>
</div>