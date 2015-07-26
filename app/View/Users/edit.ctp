<div class="col-lg-12">
    <section class="panel">
        <header class="panel-heading">
            <?php echo __('Edit User'); ?>
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
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Business Name</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('businessName'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Company Address</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('address'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Post Office</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('postOffice'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Company Phone Number</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('phone'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Company Email</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('businessEmail'); ?>
                        <p class="help-block"></p>
                    </div>
                </div>

                <div class="form-group">
                    <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Company Website</label>
                    <div class="col-lg-10">
                        <?php echo $this->Form->input('website'); ?>
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