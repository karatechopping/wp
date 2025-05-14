<form method="post" name="contect_frm" id="contect_frm" action="" onsubmit="return submitUserForm();">
    <div class="acf-error-message" style="display:none;"><p><?php echo __('Validation failed. 2 fields require attention', 'directorytheme'); ?></p><a href="#" class="acf-icon -cancel small"></a></div>

        <div id="acf-form-data" class="acf-hidden">

        </div>
        <div class="acf-fields acf-form-fields -top">
            <div class="acf-field acf-field-text acf-field--post-title" data-name="_post_title" data-type="text" data-key="_post_title" data-required="1">
                <div class="acf-label"><label for="acf-_post_title"><?php echo __('Name:', 'directorytheme'); ?><span class="acf-required">*</span></label></div>
                <div class="acf-input">
                <div class="acf-input-wrap"><input type="text" id="acf-_post_title" name="acf[_post_title]" required="required"></div></div>
                <!-- H o n e y p o t -->
                <div class="acf-input ohnohoney">
                    <div class="acf-input-wrap ohnohoney">
                        <input class="ohnohoney" autocomplete="off" type="text" id="post_title_ohnohoney" name="post_title_ohnohoney">
                    </div>
                </div>
            </div>
        <div class="acf-field acf-field-email acf-field-5a56fd92872ea acf-error" data-name="c_email" data-type="email" data-key="field_5a56fd92872ea" data-required="1">
                <div class="acf-label"><label for="acf-field_5a56fd92872ea"><?php echo __('Email:', 'directorytheme'); ?><span class="acf-required">*</span></label></div>
                <div class="acf-input"><div class="acf-error-message" style="display:none;"><p><?php echo __('Email field is required.', 'directorytheme'); ?></p></div>
                <div class="acf-input-wrap"><input type="email" id="acf-field_5a56fd92872ea" name="acf[field_5a56fd92872ea]" required="required"></div></div>
                <!-- H o n e y p o t -->
                <div class="acf-input ohnohoney">
                    <div class="acf-input-wrap ohnohoney">
                        <input class="ohnohoney" type="email" id="email_ohnohoney" name="email_ohnohoney">
                    </div>					
                </div>
        </div>

        <?php if($enable_phone == '1' && !empty($enable_phone)): ?>

            <div class="acf-field acf-field-text acf-field-5a56fd9f872eb" data-name="c_phone" data-type="text" data-key="field_5a56fd9f872eb" >
                    <div class="acf-label"><label for="acf-field_5a56fd9f872eb"><?php echo __('Phone:', 'directorytheme'); ?></label></div>
                    <div class="acf-input"><div class="acf-error-message" style="display:none;"><p><?php echo __('Phone number is required.', 'directorytheme'); ?></p></div>
                    <div class="acf-input-wrap"><input type="text" id="acf-field_5a56fd9f872eb" name="acf[field_5a56fd9f872eb]"></div>					</div>
                    <!-- H o n e y p o t -->
                    <div class="acf-input ohnohoney">
                        <div class="acf-input-wrap ohnohoney">
                            <input class="ohnohoney" type="text" id="phone_ohnohoney" name="phone_ohnohoney">
                        </div>					
                    </div>
            </div>
        <?php
            endif;

            if($enable_subject == '1'):  ?>
                <div class="acf-field acf-field-text acf-field-5a56fda9872ec" data-name="c_subject" data-type="text" data-key="field_5a56fda9872ec">
                    <div class="acf-label"><label for="acf-field_5a56fda9872ec"><?php echo __('Subject:', 'directorytheme'); ?></label></div>
                    <div class="acf-input"><div class="acf-error-message" style="display:none;"><p><?php echo __('Subject is required', 'directorytheme'); ?></p></div>
                    <div class="acf-input-wrap"><input type="text" id="acf-field_5a56fda9872ec" name="acf[field_5a56fda9872ec]"></div></div>
                    <!-- H o n e y p o t -->
                    <div class="acf-input ohnohoney">
                        <div class="acf-input-wrap ohnohoney">
                            <input class="ohnohoney" type="text" id="subject_ohnohoney" name="subject_ohnohoney">
                        </div>					
                    </div>
                </div>
            <?php
            endif;

            if($enable_message == '1'): ?>
            <div class="acf-field acf-field-textarea acf-field-5a56fdb0872ed" data-name="c_message" data-type="textarea" data-key="field_5a56fdb0872ed">
                <div class="acf-label"><label for="acf-field_5a56fdb0872ed"><?php echo __('Message:', 'directorytheme'); ?></label></div>
                <div class="acf-input">
                    <div class="acf-error-message" style="display:none;"><p><?php echo __('Message is required', 'directorytheme'); ?></p></div>
                    <textarea id="acf-field_5a56fdb0872ed" name="acf[field_5a56fdb0872ed]" rows="8"></textarea>
                </div>
                <!-- H o n e y p o t -->
                <div class="acf-input ohnohoney">
                    <div class="acf-input-wrap ohnohoney">
                        <textarea class="ohnohoney" id="c_message_ohnohoney" name="c_message_ohnohoney" rows="8"></textarea>
                    </div>					
                </div>
            </div>
        <?php
            endif;
        ?>
        <div class="acf-field acf-field-text acf-field--validate-email" style="display:none !important;" data-name="_validate_email" data-type="text" data-key="_validate_email">
            <div class="acf-label"><label for="acf-_validate_email"><?php echo __('Validate Email', 'directorytheme'); ?></label></div>
            <div class="acf-input">
                <div class="acf-input-wrap"><input type="text" id="acf-_validate_email" name="acf[_validate_email]"></div>
            </div>
            <!-- H o n e y p o t -->
            <div class="acf-input ohnohoney">
                <div class="acf-input-wrap ohnohoney">
                    <input type="text" class="ohnohoney" id="validate_email_ohnohoney" name="validate_email_ohnohoney">
                </div>					
            </div>
        </div>
        </div>
        <?php
        $enable_captcha_key1 = get_field('recaptch_field','option');
        $contact_captcha_key = get_field('captcha_contact','option');

        if($enable_captcha_key1 == '1' && $contact_captcha_key == '1'){
            if( !empty($captcha_site_key) ){ ?>
            <div class="col-md-12">
                <div class="form-group">
                <div class="g-recaptcha" data-sitekey="<?php echo $captcha_site_key; ?>" data-callback="verifyCaptcha"></div>
                <div id="g-recaptcha-error"></div>
            </div>
            </div>

            <?php
            }
        }

        if(get_sendio_uid()){
            $sandio_uid1 = get_sendio_uid();
        ?>
        <input type="hidden" name="sendio_subscribe" value="sandiosubscribe">
        <?php
        }
        ?>

        <div class="col-md-12">
            <div class="form-group">
                <input type="button" value="<?php if($form_button_text == '1' || empty($form_button_text)) { echo "Submit Message"; } else { echo $form_button_text; } ?>" class="btn btn-primary submit-form">
                <input type="hidden" name="Action" value="contact_inq">
                <?php
                if( $maichimp_enable == 1){
                    if( $api_key != '' and $list_id != ''){ ?>
                    <input type="hidden" name="mailchimp_action" value="mailchimpsubscribe">
                    <?php
                    }
                } ?>
            </div>
        </div>
</form>