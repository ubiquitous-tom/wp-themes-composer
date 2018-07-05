<?php
if (count($_POST) > 0) {
    if(!empty($_COOKIE["ATVSessionCookie"])) {
        $sessionID = $_COOKIE["ATVSessionCookie"];
    }
    else {
        //Generate a temp SessionID.
        $externalSubdomain = apply_filters('atv_get_extenal_subdomain', '');
        //Check if it is using production (empty value) or any other environment (dev or qa) to set the sessionID
        $sessionID = (!empty($externalSubdomain)) ? '2a29349a-ea14-4513-b768-f6dd203b629c': '8c0f2b23-7fe0-4d54-8d6b-93bb3e936df7';
    }
    $formData = $_POST;
    $formData['SessionID'] = $sessionID;
    if(!rljeApiWP_contactFormData($formData)) {
        header($_SERVER["SERVER_PROTOCOL"]." 400 Bad Request");
    }
    exit();
}
else {
    get_header();
    $emailAddress = '';
    if(function_exists('rljeApiWP_getUserEmailAddress') && isset($_COOKIE["ATVSessionCookie"])) {
        $emailAddress = rljeApiWP_getUserEmailAddress($_COOKIE["ATVSessionCookie"]);
    }
?>
<section id="contact-hero">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
               <h3>Contact Us</h3> 
            </div>
       </div>
    </div>
</section>
<section id="contact-form">
    <div class="container browse">
        <div id="contentPane" class="span9"> 
            <div id="contactus-view" class="url-view">
                <script defer src="<?= get_template_directory_uri(); ?>/lib/jquery/jquery-browser.js"></script>
                <script defer src="<?= get_template_directory_uri(); ?>/lib/SWFObject/SWFObject.js"></script>
                <script defer src="<?= get_template_directory_uri(); ?>/lib/jquery/jquery.validate.min.js"></script>
                <script defer src="<?= get_template_directory_uri(); ?>/js/diagnostics.js"></script>
                <script defer src="<?= get_template_directory_uri(); ?>/js/contactForm.js"></script>
                <div class="row-fluid">
                    <div class="span10">

                        <div id="msg"></div>

                        <div id="diagnostic">

                            <form method="post" id="acornDiagnostic" name="acornDiagnostic" accept-charset="UTF-8" novalidate="novalidate">

                                <input id="subject" name="subject" type="hidden" value="">
                                <input id="date" name="Date" type="hidden" value="">
                                <input id="time" name="Time" type="hidden" value="">
                                <input id="browser" name="Browser" type="hidden" value="">
                                <input id="userAgentHeader" name="UserAgent" type="hidden" value="">
                                <input id="screenSize" name="Screen_Size" type="hidden" value="">
                                <input id="cookiesEnabled" name="Cookies_Enabled" type="hidden" value="">
                                <input id="acornOnlineCookies" name="Acorn_Online_Cookies" type="hidden" value="">
                                <input id="flashPlayer" name="Flash_Player" type="hidden" value="">
                                <input id="connSpeed" name="Conn_Speed" type="hidden" value="">
                                <input id="referringUrl" name="Referring_Url" type="hidden" value="">
                                <input id="Model" name="model" type="hidden" value="Web">

                                <p style="margin-bottom:20px;">
                                    Please be sure to visit the <a href="<?= home_url('support/home') ?>" target="_blank"><?php bloginfo("name") ?> Help Center</a> for answers to frequently asked questions, up-to-the-minute alerts, and solutions to many common issues. If you need additional assistance, please <?php if(!rljeApiWP_getCountryCode()): ?>select a category and <?php endif; ?>provide a description of the issue you are experiencing. 
                                </p>

                               <p>
                                 <b>Programming Questions and Requests:</b> Want to request a show? Please fill out <a href="https://www.surveymonkey.com/r/P3NM77F" target="_blank">our survey</a>! Have a question about our schedule? Please contact us on <a href="https://www.facebook.com/UrbanMovieChannel/" target="_blank">Facebook</a>. 
                               </p>

                                <?php if(!rljeApiWP_getCountryCode()): ?>
                                <div class="control-group">
                                    <label for="issue-select" class="control-label">Please Select a Category</label>
                                    <div class="controls">
                                        <select class="input-block-level required valid" id="issue-select" name="IssueSelect">
                                            <option value="" selected="selected">- Select Issue Type -</option>
                                                <option value="issues-billing">Billing &amp; Account Management</option>
                                                <option value="issues-playback">Audio/Video Playback</option>
                                                <option value="issues-account">Log In/Password/Sign Up</option>
                                                <option value="issues-gift">Gifting</option>
                                                <option value="issues-ios">Apple TV/iOS</option>
                                                <option value="issues-roku">Roku</option>
                                                <option value="issues-pc">PC/Mac</option>
                                                <option value="issues-android">Android</option>
                                                <option value="issues-samsung">Samsung Smart TVs</option>
                                                <option value="issues-firetv">Amazon Fire TV</option>
                                        </select>
                                        <label for="issue-select" class="error" style="display: none;">This field is required.</label>
                                    </div>
                                </div> 

                                <div id="issues-billing" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/1000220901">Billing &amp; Account Management solutions</a>
                                    </div>
                                </div>

                                <div id="issues-playback" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/11000001671">Audio/Video Playback solutions</a>
                                    </div>
                                </div>

                                <div id="issues-account" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/1000220900">Log in/Sign Up solutions</a>
                                    </div>
                                </div>

                                <div id="issues-gift" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/11000001670">Gifting solutions</a>
                                    </div>
                                </div>

                                <div id="issues-ios" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/11000001672">Apple TV/iOS solutions</a>
                                    </div>
                                </div>

                                <div id="issues-roku" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/11000001673">Roku solutions</a>
                                    </div>
                                </div>

                                <div id="issues-pc" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/11000001675">PC/Mac solutions</a>
                                    </div>
                                </div>

                                <div id="issues-samsung" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/11000001674">Samsung Smart TVs solutions</a>
                                    </div>
                                </div>

                                <div id="issues-firetv" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/11000001770">Amazon Fire TV solutions</a>
                                    </div>
                                </div>

                                <div id="issues-android" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please try these common <a href="http://support.acorn.tv/support/solutions/folders/11000004938">Android Solution</a>
                                    </div>
                                </div>

                                <div id="issues-other" class="issues-list" style="display: none;">
                                    <div class="alert alert-info self-help">
                                        <i class="fa fa-arrow-right fa-lg"></i> Please see our <a href="/help/faq">Frequently Asked Questions</a>
                                    </div>
                                </div>
                                <?php endif; ?>


                                <div class="control-group" style="margin-top:25px;">
                                    <label for="email" class="control-label"> E-Mail Address</label>
                                    <div class="controls">
                                        <input type="text" id="email" name="email" class="input-block-level required email valid" value="<?= $emailAddress; ?>"> 
                                    </div>
                                </div> 

                                <div class="control-group">
                                    <label for="Description" class="control-label">Please describe the issue you are experiencing (including the device you are using to watch)</label>
                                    <div class="controls">
                                        <textarea id="Description" title="" name="Description" rows="6" class="input-block-level required"></textarea>
                                    </div>
                                </div> 

                                <div class="control-group" style="margin:15px 0 25px;">
                                    <div class="controls">
                                        <button id="submitThisForm" class="btn btn-primary btn-large btn-block" style="opacity: 1;">Submit Info</button>
                                    </div>
                                </div> 

                            </form>
                        </div>

                        <noscript>
                                &amp;lt;p&amp;gt;It appears that your browser does not support JavaScript, or you have it disabled. This site is best viewed with JavaScript enabled.&amp;lt;/p&amp;gt;
                                &amp;lt;p&amp;gt;If JavaScript is disabled in your browser, please turn it back on then reload this page.&amp;lt;/p&amp;gt;
                        </noscript>

                        <div id="flashPortContent" class="customerHidden">
                            <p class="flash"><strong>Flash/Port Testing:</strong></p>
                            <div id="flashPortTest"></div>
                        </div>

                    </div> <!-- diagnostic -->

                    <div class="span2"> 
                        <!--
                        <div class="well">


                        </div>
                        -->
                    </div>
                </div> <!-- row -->
            </div>
        </div>
    </div>
</section>

<?php
wp_footer();
}