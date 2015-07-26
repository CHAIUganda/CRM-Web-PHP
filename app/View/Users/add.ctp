<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            <?php echo __('Add User'); ?>
        </header>
        <div class="panel-body">
            <?php echo $this->Form->create('User', array(
            	'inputDefaults' => array(
				    'class' => 'form-control',
				    'label' => false,
        			'div' => false
				  )
            	)); 

            ?>
                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Email</label>
                    <div class="col-lg-10">
                    	<?php echo $this->Form->input('id');
						echo $this->Form->input('email');?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Password</label>
                    <div class="col-lg-10">
						<? echo $this->Form->input('password');?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">First Name</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('firstName'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Last Name</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('lastName'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Group</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('group_id'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>


                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Company</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('company_id'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
                <?php echo $this->Form->end(); ?>
        </div>
    </section>
</div>
