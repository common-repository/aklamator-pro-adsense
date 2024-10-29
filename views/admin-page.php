
<div id="aklamatorPro-options" style="width:1160px;margin-top:10px;">

    <div class="left" style="float: left">

    <div style="float: left; width: 300px;">

        <a target="_blank" href="<?php echo $this->aklamator_url; ?>?utm_source=wordpress_pro">
            <img style="border-radius:5px;border:0px;" src=" <?php echo  AKLA_PRO_PLUGIN_URL .'images/logo.jpg'; ?>" /></a>
        <?php
        if ($this->application_id != '') : ?>
            <a target="_blank" href="<?php echo $this->aklamator_url; ?>dashboard?utm_source=wordpress_pro">
                <img style="border:0px;margin-top:5px;border-radius:5px;" src="<?php echo AKLA_PRO_PLUGIN_URL .'images/dashboard.jpg'; ?>" /></a>

        <?php endif; ?>

        <a target="_blank" href="<?php echo $this->aklamator_url;?>contact?utm_source=wp-plugin-contact-pro">
            <img style="border:0px;margin-top:5px; margin-bottom:5px;border-radius:5px;" src="<?php echo AKLA_PRO_PLUGIN_URL .'images/support.jpg'; ?>" /></a>

        <a target="_blank" href="http://qr.rs/q/4649f"><img style="border:0px;margin-top:5px; margin-bottom:5px;border-radius:5px;" src="<?php echo AKLA_PRO_PLUGIN_URL .'images/promo-300x200.png'; ?>" /></a>

    </div>
    <div class="box">

        <h1>Aklamator Digital PR Pro version</h1>

        <?php

        if (isset($this->api_data->error) || $this->application_id == '') : ?>
            <h3 style="float: left">Step 1: Get your Aklamator Aplication ID</h3>
            <a class='aklamator_button aklamator-login-button' id="aklamator_login_button" >Click here for FREE registration/login</a>
            <div style="clear: both"></div>
            <p>Or you can manually <a href="<?php echo $this->aklamator_url . 'registration/publisher'; ?>" target="_blank">register</a> or <a href="<?php echo $this->aklamator_url . 'login'; ?>" target="_blank">login</a> and copy paste your Application ID</p>
            <script>var signup_url = '<?php echo $this->getSignupUrl(); ?>';</script>
        <?php endif; ?>

        <div style="clear: both"></div>
        <?php if ($this->application_id == '') { ?>
            <h3>Step 2: &nbsp;&nbsp;&nbsp;&nbsp; Paste your Aklamator Application ID</h3>
        <?php }else{ ?>
            <h3>Your Aklamator Application ID</h3>
        <?php } ?>

        <form method="post" action="options.php">
            <?php
            settings_fields('aklamatorPro-options');
            ?>

            <p>
                <input type="text" style="width: 400px" name="aklamatorProApplicationID" id="aklamatorProApplicationID" value="<?php echo $this->application_id; ?>" maxlength="999" onchange="appIDChange(this.value)"/>
            </p>
            <p>
                <input type="checkbox" id="aklamatorProPoweredBy" name="aklamatorProPoweredBy" <?php echo (get_option("aklamatorProPoweredBy") == true ? 'checked="checked"' : ''); ?> Required="Required">
                <strong>Required</strong> I acknowledge there is a 'powered by aklamator' link on the QR code. <br />
            </p>

            <p>
                <input type="checkbox" id="aklamatorProFeatured2Feed" name="aklamatorProFeatured2Feed" <?php echo (get_option("aklamatorProFeatured2Feed") == true ? 'checked="checked"' : ''); ?> >
                <strong>Add featured</strong> images from posts to your site's RSS feed output
            </p>

            <p>
            <div class="alert alert-msg">
                <strong>Note </strong><span style="color: red">*</span>: By default, posts without images will not be shown in widgets. If you want to show them click on <strong>EDIT</strong> in table below!
            </div>
            </p>

            <?php if(isset($this->api_data->flag) && $this->api_data->flag === false): ?>
                <p id="aklamator_error" class="alert_red alert-msg_red"><span style="color:red"><?php echo $this->api_data->error; ?></span></p>
            <?php endif; ?>

            <h1>Options</h1>

            <h4>Show all categories in widget (default), or choose specific category:</h4>
            <?php

            wp_dropdown_categories(array(
                'id' => 'aklamatorPRAdsenseCategory',
                'name' => 'aklamatorPRAdsenseCategory',
                'hide_empty' => 1,
                'orderby' => 'name',
                'selected' => get_option('aklamatorPRAdsenseCategory'),
                'hierarchical' => true,
                'show_option_none' => __('ALL'),
                'taxonomy' => 'category',
                'value_field' => 'term_id',
                'show_count' => true
            ));
            ?>

            <h3 style="font-size:120%;margin-bottom:5px"><?php _e('Add your Adsense Code or any other script codes'); ?></h3>
            <p style="margin-top:0px"><span class="description"><?php _e('Paste your <strong>Ad</strong> code and you will be able to assign that <strong>Ad</strong> to single post or static page as shown below, and in Widget section you can drag and drop Aklamator widget and chose from dropdown what you want to show in your sidebar.') ?></span></p>

            <h4><?php _e('Paste your Ad codes :'); ?></h4>
            <table border="0" cellspacing="0" cellpadding="0">

                <tr valign="top">
                    <td align="left" style="width:140px; padding-right: 5px"><strong>Ad1:</strong> <br/>Custom Ad name
                        <input id="aklamatorProAds1Name" name="aklamatorProAds1Name" value="<?php echo stripslashes(htmlspecialchars(get_option('aklamatorProAds1Name'))); ?>" placeholder="Optional Ad1 name"/>
                    </td>
                    <td align="left"><textarea style="margin:0 5px 3px 0; resize: none; overflow-y: scroll;text-align: left" id="aklamatorProAds" name="aklamatorProAds" rows="3" cols="35"><?php echo stripslashes(htmlspecialchars(get_option('aklamatorProAds'))); ?></textarea></td>

                </tr>
                <tr valign="top">
                    <td align="left" style="width:140px; padding-right: 5px"><strong>Ad2:</strong> <br/>Custom Ad name
                        <input id="aklamatorProAds2Name" name="aklamatorProAds2Name" value="<?php echo stripslashes(htmlspecialchars(get_option('aklamatorProAds2Name'))); ?>" placeholder="Optional Ad2 name"/>
                    </td>
                    <td align="left"><textarea style="margin:0 5px 3px 0; resize: none; overflow-y: scroll;text-align: left" id="aklamatorProAds2" name="aklamatorProAds2" rows="3" cols="35"><?php echo stripslashes(htmlspecialchars(get_option('aklamatorProAds2'))); ?></textarea></td>

                </tr>
                <tr valign="top">
                    <td align="left" style="width:140px; padding-right: 5px"><strong>Ad3:</strong> <br/>Custom Ad name
                        <input id="aklamatorProAds3Name" name="aklamatorProAds3Name" value="<?php echo stripslashes(htmlspecialchars(get_option('aklamatorProAds3Name'))); ?>" placeholder="Optional Ad3 name"/>
                    </td>
                    <td align="left"><textarea style="margin:0 5px 3px 0; resize: none; overflow-y: scroll;text-align: left" id="aklamatorProAds3" name="aklamatorProAds3" rows="3" cols="35"><?php echo stripslashes(htmlspecialchars(get_option('aklamatorProAds3'))); ?></textarea></td>

                </tr>

            </table>

            <?php if ($this->api_data->data[0]->uniq_name != 'none') : ?>

                <label for="aklamatorProSingleWidgetTitle">Title Above widget (Optional): </label>
                <input type="text" style="width: 300px; margin-top:10px" name="aklamatorProSingleWidgetTitle" id="aklamatorProSingleWidgetTitle" value="<?php echo (get_option("aklamatorProSingleWidgetTitle")); ?>" maxlength="999" />

                <h4>Select widget to be shown on bottom of the each:</h4>

                <label for="aklamatorProSingleWidgetID">Single post: </label>
                <select id="aklamatorProSingleWidgetID" name="aklamatorProSingleWidgetID">
                    <?php
                    foreach ( $this->api_data->data as $item ): ?>
                        <option <?php echo (stripslashes(htmlspecialchars_decode(get_option('aklamatorProSingleWidgetID'))) == $item->uniq_name)? 'selected="selected"' : '' ;?> value="<?php echo addslashes(htmlspecialchars($item->uniq_name)); ?>"><?php echo $item->title; ?></option>
                    <?php endforeach; ?>
                </select>
                <input style="margin-left: 5px;" type="button" id="preview_single" class="button primary big submit" onclick="myFunction(jQuery('#aklamatorProSingleWidgetID option[selected]').val())" value="Preview" <?php echo get_option('aklamatorProSingleWidgetID')=="none"? "disabled" :"" ;?>>
                <p>
                    <label for="aklamatorProPageWidgetID">Single page: </label>
                    <select id="aklamatorProPageWidgetID" name="aklamatorProPageWidgetID">
                        <?php
                        foreach ( $this->api_data->data as $item ): ?>
                            <option <?php echo (stripslashes(htmlspecialchars_decode(get_option('aklamatorProPageWidgetID'))) == $item->uniq_name)? 'selected="selected"' : '' ;?> value="<?php echo addslashes(htmlspecialchars($item->uniq_name)); ?>"><?php echo $item->title; ?></option>
                        <?php endforeach; ?>

                    </select>
                    <input style="margin-left: 5px;" type="button" id="preview_page" class="button primary big submit" onclick="myFunction(jQuery('#aklamatorProPageWidgetID option[selected]').val())" value="Preview" <?php echo get_option('aklamatorProPageWidgetID')=="none"? "disabled" :"" ;?>>
                </p>
            <?php endif; ?>
            <input id="aklamator_adsense_save" class="aklamator_INlogin" style ="margin: 0; border: 0; float: left;" type="submit" value="<?php echo (_e("Save Changes")); ?>" />
            <?php if(!isset($this->api_data->flag) || !$this->api_data->flag): ?>
                <div style="float: left; padding: 7px 0 0 10px; color: red; font-weight: bold; font-size: 16px"> <-- In order to proceed save changes</div>
            <?php endif ?>
        </form>
    </div>


<div style="clear:both"></div>
<div style="margin-top: 20px; margin-left: 0px; width: 810px;" class="box">

    <?php if (isset($this->curlfailovao) && $this->curlfailovao && $this->application_id != ''): ?>
        <h2 style="color:red">Error communicating with Aklamator server, please refresh plugin page or try again later. </h2>
    <?php endif;?>
    <?php if(!isset($this->api_data_table->flag) || $this->api_data_table->flag == false): ?>
        <a href="<?php echo $this->getSignupUrl(); ?>" target="_blank"><img style="border-radius:5px;border:0px;" src=" <?php echo AKLA_PRO_PLUGIN_URL .'images/teaser-810x262.png'; ?>" /></a>
    <?php else : ?>


    <!-- Start of dataTables -->
    <div id="aklamatorPro-options">
        <h1>Your Widgets</h1>
        <div>In order to add new widgets or change dimensions please <a href="<?php echo $this->aklamator_url; ?>login" target="_blank">login to aklamator</a></div>
    </div>
    <br>
    <table cellpadding="0" cellspacing="0" border="0"
           class="responsive dynamicTable display table table-bordered" width="100%">
        <thead>
        <tr>

            <th>Name</th>
            <th>Domain</th>
            <th>Settings</th>
            <th>Image size</th>
            <th>Column/row</th>
            <th>Created At</th>

        </tr>
        </thead>
        <tbody>

        <?php foreach ($this->api_data_table->data as $item): ?>

            <tr class="odd">
                <td style="vertical-align: middle;" ><?php echo $item->title; ?></td>
                <td style="vertical-align: middle;" >
                    <?php foreach($item->domain_ids as $domain): ?>
                        <a href="<?php echo $domain->url; ?>" target="_blank"><?php echo $domain->title; ?></a><br/>
                    <?php endforeach; ?>
                </td>
                <td style="vertical-align: middle"><div style="float: left; margin-right: 10px" class="button-group">
                        <input type="button" class="button primary big submit" onclick="myFunction('<?php echo $item->uniq_name; ?>')" value="Preview Widget">
                </td>

                <td style="vertical-align: middle;" ><?php echo "<a href = \"$this->aklamator_url"."widget/edit/$item->id\" target='_blank' title='Click & Login to change'>$item->img_size px</a>";  ?></td>
                <td style="vertical-align: middle;" >
                    <?php echo "<a href = \"$this->aklamator_url"."widget/edit/$item->id\" target='_blank' title='Click & Login to change'>".$item->column_number ." x ". $item->row_number."</a>"; ?>
                    <div style="float: right;">
                        <?php echo "<a class=\"btn btn-primary\" href = \"$this->aklamator_url"."widget/edit/$item->id\" target='_blank' title='Edit widget settings'>Edit</a>"; ?>
                    </div>
                </td>
                <td style="vertical-align: middle;" ><?php echo $item->date_created; ?></td>
            </tr>
        <?php endforeach; ?>

        </tbody>
        <tfoot>
        <tr>
            <th>Name</th>
            <th>Domain</th>
            <th>Settings</th>
            <th>Image size</th>
            <th>Column/row</th>
            <th>Created At</th>
        </tr>
        </tfoot>
    </table>
</div>
    </div>
    <div class="right" style="float: right">
        <div class="right_sidebar">

            <iframe width="330" height="1024" src="<?php echo $this->aklamator_url; ?>wp-sidebar/right?plugin=pro-adsense" frameborder="0"></iframe>

        </div>
    </div>
</div>

<?php endif; ?>
