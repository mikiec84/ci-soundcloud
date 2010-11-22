<h3>Upload a new track</h3>
<p>Your track will be kept private by default.</p>
<?php echo form_open_multipart($form_action, array('id' => 'add_track')); ?>
    <p>
        <?php echo form_label('Title', 'title'); ?>
        <?php echo form_input(array('name' => 'title', 'id' => 'title', 'class' => 'text')); ?>
    </p>
    <p>
        <?php echo form_label('Tags', 'tag'); ?>
        <?php echo form_input(array('name' => 'tag', 'id' => 'tag', 'class' => 'text tag')); ?>
        <?php echo form_button(array('name' => 'add_tag', 'id' => 'add_tag', 'class' => 'button', 'content' => 'Add tag')); ?>
    </p>
    <ul id="tags"></ul>
    <p>
        <?php echo form_label('Track', 'track'); ?>
        <?php echo form_upload(array('name' => 'track', 'id' => 'track', 'class' => 'file')); ?>
    </p>
    <p class="center submit">
        <?php echo form_submit(array('name' => 'submit', 'id' => 'submit', 'class' => 'submit', 'value' => 'Upload track')); ?>
    </p>
<?php echo form_close(); ?>