<!-- jQuery UI dialogs -->
<div id="ajaxPopup" class="dom_pop" style="display:none;">
    <div class="widget" style="margin-top:0;">
        <ul class="tabs">
            <li><a href="#addNewTag">Add Tags</a></li>
            <li><a href="#editExistingTags">Edit Tags</a></li>
        </ul>
        <div class="tab_container">
            <div id="addNewTag" style="display:block;">
                <div class="head"><h5 class="iList">Add New Tags</h5></div>
                <?php echo  form_open('/ajax/add_new_tag',array('name'=>'addTags','id'=>'valid')); ?>
                    <fieldset>
                        <div class="rowElem noborder">
                            <label>Tag Name</label>
                            <div class="formRight">
                                <input type="text" name="name" />
                            </div>
                            <div class="fix"></div>
                        </div>
                        <div class="rowElem">
                            <label>Tag Color</label>
                            <div class="formRight">
                                <input type="text" class="colorpick" id="colorpickerField" />
                                <label class="pick" for="colorpickerField"></label>
                            </div>
                            <div class="fix"></div>
                        </div>
                        <input class="greyishBtn submitForm" type="submit" value="Add Tag" />
                    </fieldset>
                <?php echo  form_close(); ?>
            </div>
            <div id="editExistingTags" style="display:none;">
                <div class="head"><h5 class="iList">Edit Tags</h5></div>
            </div>
        </div>
    </div>
    <div class="fix"></div>
</div>