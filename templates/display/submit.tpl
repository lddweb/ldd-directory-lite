<section class="business-directory  cf">

    <nav class="lite-nav above-header center cf">
        <ul><li><a href="{{url}}">Directory Home</a></li></ul>
    </nav>

    <header class="global-header submit-listing">
        <form class="directory-search cf">
            <input type="text" placeholder="Search the directory..." required />
            <button type="submit">Search</button>
        </form>
    </header>


    <section class="directory-content">





        <style>

        #ldd-submit-wrapper {

            width: 600px;
            overflow: hidden;
            background: -moz-linear-gradient(top,  rgba(250,250,250,1) 39%, rgba(250,250,250,1) 54%, rgba(250,250,250,0) 100%); /* FF3.6+ */
            background: -webkit-gradient(linear, left top, left bottom, color-stop(39%,rgba(250,250,250,1)), color-stop(54%,rgba(250,250,250,1)), color-stop(100%,rgba(250,250,250,0))); /* Chrome,Safari4+ */
            background: -webkit-linear-gradient(top,  rgba(250,250,250,1) 39%,rgba(250,250,250,1) 54%,rgba(250,250,250,0) 100%); /* Chrome10+,Safari5.1+ */
            background: -o-linear-gradient(top,  rgba(250,250,250,1) 39%,rgba(250,250,250,1) 54%,rgba(250,250,250,0) 100%); /* Opera 11.10+ */
            background: -ms-linear-gradient(top,  rgba(250,250,250,1) 39%,rgba(250,250,250,1) 54%,rgba(250,250,250,0) 100%); /* IE10+ */
            background: linear-gradient(to bottom,  rgba(250,250,250,1) 39%,rgba(250,250,250,1) 54%,rgba(250,250,250,0) 100%); /* W3C */
            filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#fafafa', endColorstr='#00fafafa',GradientType=0 ); /* IE6-9 */
        }

        #panels{
            width:600px;
            /*height:320px;*/
            overflow:hidden;
        }
        .panel{
            float:left;
            width:600px;
            /*height:320px;*/
        }

        #navigation{
            height:45px;


        }

        #navigation ul{
            margin: 0 auto;
            width: 600px;
            list-style:none;
            background-color:#f4f4f4;
            display: table;
            table-layout: fixed;
            -moz-border-radius: 10px 10px 0 0;
            -webkit-border-top-left-radius: 10px;
            -webkit-border-top-right-radius: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        #navigation ul > li {
            display: table-cell;
            text-align: center;
            margin-left: 10px;
            position: relative;
            border-left: 1px solid #fff;
        }
        #navigation ul > li:first-child {
            border-left: 0;
        }
        #navigation ul > li:first-child a {
            -moz-border-radius: 10px 0 0 0;
            -webkit-border-top-left-radius: 10px;
            border-top-left-radius: 10px;
        }
        #navigation ul > li:last-child a {
            -moz-border-radius: 0 10px 0 0;
            -webkit-border-top-right-radius: 10px;
            border-top-right-radius: 10px;
        }
        #navigation ul li a{
            display:block;
            height:45px;
            background-color: #f1f3f5;
            color:#777;
            outline:none;
            font-weight:bold;
            text-decoration:none;
            line-height:45px;
            padding:0px 20px;
        }
        #navigation ul li a:hover,
        #navigation ul li.selected a{
            background: #dbe1e8;
            color:#666;
            text-shadow:1px 1px 1px #fff;
        }
        #navigation ul li a.error {
            color: #D00;
            text-shadow: 0 0 4px #fff;
        }
        #navigation ul li a.checked {
            color: #777;
            text-shadow: 0 0 4px #fff;
        }

        #panels form fieldset{
            border:none;
            padding-bottom:20px;
        }


        #panels form legend{
            text-align: left;
            background-color: #dbe1e8; /* #e9eef5 */
            color: #555;
            font-size: 2em;
            font-weight: 700;
            float: left;
            width: 590px;
            padding: 5px 0 5px 10px;
            margin: 2px 0 1em;
            text-shadow: -1px -1px 4px #fff;
        }


        #panels form p{
            float:left;
            clear:both;
            margin:5px 0px;
            background-color: #f1f3f5;
            width:480px;
            padding:10px;
            margin-left:50px;
            -moz-border-radius: 4px;
            -webkit-border-radius: 4px;
            border-radius: 4px;
        }

        #panels form p label{
            width: 160px;
            float: left;
            text-align: right;
            margin-right: 15px;
            line-height: 26px;
            color:#555;
            text-shadow: 1px 1px 2px #fff;
            font-weight: 700;
            font-size: 105%;
        }
        #panels form label.info {
            clear: both;
            display: block;
            margin: 0 0 0 180px;
            text-align: left;
            padding-top: .2em;
            color: #a5a5a5;
            font-weight: 500;
            width: 100%;
            float: none;
            font-size: 100%;
        }
        #panels form label.error {
            color: #D00;
            font-weight: 700;
        }
        #panels form input:not([type=radio]),
        #panels form textarea,
        #panels form select{
            background: #ffffff;
            border: 2px solid #ddd;
            -moz-border-radius: 6px;
            -webkit-border-radius: 6px;
            border-radius: 6px;
            outline: none;
            padding: .5em;
            font-size: 1.2em;
            width: 260px;
            float:left;
        }
        #panels form input:not([type=radio]).error,
        #panels form textarea.error,
        #panels form select.error {

            border-color: #D00;
        }
        #panels form textarea {
            min-height: 120px;
        }
        #panels form input:focus,
        #panels form textarea:focus {
            -moz-box-shadow:0px 0px 1px #aaa;
            -webkit-box-shadow:0px 0px 1px #aaa;
            box-shadow:0px 0px 1px #aaa;
            background-color:#FFFEEF;
        }
        #panels form p.submit{
            background:none;
            border:none;
            -moz-box-shadow:none;
            -webkit-box-shadow:none;
            box-shadow:none;
        }
        #panels form button {
            border:none;
            outline:none;
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
            border-radius: 10px;
            color: #ffffff;
            display: block;
            cursor:pointer;
            margin: 0px auto;
            clear:both;
            padding: 7px 25px;
            text-shadow: 0 1px 1px #777;
            font-weight:bold;
            font-family:"Century Gothic", Helvetica, sans-serif;
            font-size:22px;
            -moz-box-shadow:0px 0px 3px #aaa;
            -webkit-box-shadow:0px 0px 3px #aaa;
            box-shadow:0px 0px 3px #aaa;
            background:#4797ED;
        }
        #panels form button:hover {
            background:#d8d8d8;
            color:#666;
            text-shadow:1px 1px 1px #fff;
        }
        </style>




        <div id="ldd-submit-wrapper">
            <div id="navigation" style="display:none;">
                <ul>
                    <li class="selected"><a href="#">General</a></li>
                    <li><a href="#">Location</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Logo</a></li>
                    <li><a href="#">Confirm</a></li>
                </ul>
            </div>

            <div id="panels">
                <form id="formElem" name="formElem" action="" method="post" data-parsley-validate>

                    <fieldset class="panel">
                        <legend>General Information</legend>
                        <p><label for="name">Business Name</label>
                            <input id="name" type="text" name="name" value="" required />
                        </p>

                        <p><label for="description">Description</label>
                            <textarea id="description" name="description" required></textarea>
                            <label for="description" class="info">A brief summary of your business.</label>
                        </p>
                        <p><label for="username">Account Username</label>
                            <input id="username" type="text" name="username" required />
                        </p>
                        <p>
                            <label for="email">Account Email</label>
                            <input id="email" name="email" placeholder="email@address.com" type="email" AUTOCOMPLETE=OFF />
                        </p>
                        <p>
                            <label for="password">Password</label>
                            <input id="password" name="password" type="password" AUTOCOMPLETE=OFF />
                        </p>
                    </fieldset>
                    <fieldset class="panel">
                        <legend>Business Location</legend>
                        <p>
                            <label for="address_country">Country</label>
                            {{country_dropdown}}
                        </p>
                        <p>
                            <label for="street">Street</label>
                            <input id="street" type="text" name="street" value="{{street}}" />
                        </p>

                        <p>
                            <label for="city">City / Town:</label>
                            <input id="city" type="text" name="city" value="{{city}}" />
                        </p>

                        <p>
                            <label for="subdivision">State:</label>
                            <select id="subdivision" name="subdivision">
                                <option value="AL">Alabama</option>
                                <option value="AK">Alaska</option>
                                <option value="AZ">Arizona</option>
                                <option value="AR">Arkansas</option>
                                <option value="CA">California</option>
                                <option value="CO">Colorado</option>
                                <option value="CT">Connecticut</option>
                                <option value="DE">Delaware</option>
                                <option value="DC">District Of Columbia</option>
                                <option value="FL">Florida</option>
                                <option value="GA">Georgia</option>
                                <option value="HI">Hawaii</option>
                                <option value="ID">Idaho</option>
                                <option value="IL">Illinois</option>
                                <option value="IN">Indiana</option>
                                <option value="IA">Iowa</option>
                                <option value="KS">Kansas</option>
                                <option value="KY">Kentucky</option>
                                <option value="LA">Louisiana</option>
                                <option value="ME">Maine</option>
                                <option value="MD">Maryland</option>
                                <option value="MA">Massachusetts</option>
                                <option value="MI">Michigan</option>
                                <option value="MN">Minnesota</option>
                                <option value="MS">Mississippi</option>
                                <option value="MO">Missouri</option>
                                <option value="MT">Montana</option>
                                <option value="NE">Nebraska</option>
                                <option value="NV">Nevada</option>
                                <option value="NH">New Hampshire</option>
                                <option value="NJ">New Jersey</option>
                                <option value="NM">New Mexico</option>
                                <option value="NY">New York</option>
                                <option value="NC">North Carolina</option>
                                <option value="ND">North Dakota</option>
                                <option value="OH">Ohio</option>
                                <option value="OK">Oklahoma</option>
                                <option value="OR">Oregon</option>
                                <option value="PA">Pennsylvania</option>
                                <option value="RI">Rhode Island</option>
                                <option value="SC">South Carolina</option>
                                <option value="SD">South Dakota</option>
                                <option value="TN">Tennessee</option>
                                <option value="TX">Texas</option>
                                <option value="UT">Utah</option>
                                <option value="VT">Vermont</option>
                                <option value="VA">Virginia</option>
                                <option value="WA">Washington</option>
                                <option value="WV">West Virginia</option>
                                <option value="WI">Wisconsin</option>
                                <option value="WY">Wyoming</option>
                            </select>
                        </p>

                        <p>
                            <label for="zip">Zip/Postal:</label>
                            <input id="zip" type="text" name="zip" value="{{zip}}" />
                        </p>
                    </fieldset>

                    <fieldset class="panel">
                        <legend>Contact Information</legend>
                        <p>
                            <label for="email">Contact Email</label>
                            <input id="email" name="email" placeholder="email@address.com" type="email" AUTOCOMPLETE=OFF />
                        </p>
                        <p>
                            <label for="phone">Contact Phone</label>
                            <input id="phone" type="text" name="phone" />
                        </p>

                        <p>
                            <label for="fax">Contact Fax</label>
                            <input id="fax" type="text" name="fax" />
                        </p>

                        <p>
                            <label for="url">Website</label>
                            <input id="url" type="text" name="url" />
                        </p>

                        <p>
                            <label for="facebook">Facebook Page</label>
                            <input id="facebook" type="text" name="facebook" />
                        </p>

                        <p>
                            <label for="twitter">Twitter Handle</label>
                            <input id="twitter" type="text" name="twitter" />
                        </p>

                        <p>
                            <label for="linkedin">Linked In Profile</label>
                            <input id="linkedin" type="text" name="linkedin" />
                        </p>
                    </fieldset>

                    <fieldset class="panel">
                        <legend>Business Logo</legend>
                        <p>
                            <label for="logo">Logo Image</label>
                            <input id="logo" type="file" name="logo" />
                        </p>
                    </fieldset>
                    <fieldset class="panel">
                        <legend>Confirm</legend>
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer nec odio. Praesent libero. Sed cursus ante dapibus diam. Sed nisi. Nulla quis sem at nibh elementum imperdiet. Duis sagittis ipsum. Praesent mauris. Fusce nec tellus sed augue semper porta.
                        </p>
                        <p class="submit">
                            <button id="submitButton" type="submit">Submit Listing</button>
                        </p>
                    </fieldset>
                </form>
            </div>

        </div>
        </div>








    </section>


</section>



<script>
    jQuery(document).ready(function($) {
        /*
         number of fieldsets
         */
        var fieldsetCount = $('#formElem').children().length;

        /*
         current position of fieldset / navigation link
         */
        var current 	= 1;

        /*
         sum and save the widths of each one of the fieldsets
         set the final sum as the total width of the steps element
         */
        var stepsWidth	= 0;
        var widths 		= new Array();
        $('#panels .panel').each(function(i){
            var $step 		= $(this);
            widths[i]  		= stepsWidth;
            stepsWidth	 	+= $step.width();
        });
        $('#panels').width(stepsWidth);

        /*
         to avoid problems in IE, focus the first input of the form
         */
        $('#formElem').children(':first').find(':input:first').focus();

        /*
         show the navigation bar
         */
        $('#navigation').show();

        /*
         when clicking on a navigation link
         the form slides to the corresponding fieldset
         */
        $('#navigation a').bind('click',function(e){
            var $this	= $(this);
            var prev	= current;
            $this.closest('ul').find('li').removeClass('selected');
            $this.closest('li').addClass('selected');

            current = $this.closest('li').index() + 1;

            $('#panels').stop().animate({
                marginLeft: '-' + widths[current-1] + 'px'
            },500,function(){
                if(current == fieldsetCount)
                    validateSteps();
                else
                    validateStep(prev);
                $('#formElem').children(':nth-child('+ parseInt(current) +')').find(':input:first').focus();
            });
            e.preventDefault();
        });


        $('#formElem > fieldset').each(function(){
            var $fieldset = $(this);
            $fieldset.children(':last').find(':input').keydown(function(e){
                if (e.which == 9){
                    $('#navigation li:nth-child(' + (parseInt(current)+1) + ') a').click();
                    /* force the blur for validation */
                    $(this).blur();
                    e.preventDefault();
                }
            });
        });

        /*
         validates errors on all the fieldsets
         records if the Form has errors in $('#formElem').data()
         */
        function validateSteps(){
            var FormErrors = false;
            for(var i = 1; i < fieldsetCount; ++i){
                var error = validateStep(i);
                if(error == -1)
                    FormErrors = true;
            }
            $('#formElem').data('errors',FormErrors);
        }

        /*
         validates one fieldset
         and returns -1 if errors found, or 1 if not
         */
        function validateStep(step){
            if(step == fieldsetCount) return;


            var error = 1;
            var hasError = false;

            $('#formElem').children(':nth-child('+ parseInt(step) +')').find(':input:not(button)').each(function(){

                var $this 		= $(this);
                var valueLength = jQuery.trim($this.val()).length;

                if(valueLength == ''){
                    hasError = true;
                    $this.addClass('error');
                }

            });

            var $link = $('#navigation li:nth-child(' + parseInt(step) + ') a');
            //$link.parent().find('.error,.checked').remove();

            var valclass = 'checked';
            if(hasError){
                error = -1;
                valclass = 'error';
            }
            $link.addClass( valclass );
            //$('<span class="'+valclass+'"></span>').insertAfter($link);

            return error;
        }

        $(':input:not(button)').focus(function(){
            if ( $(this).hasClass('error') ) {
                $(this).removeClass('error');
            }
        });

        /*
         if there are errors don't allow the user to submit
         */
        $('#submitButton').bind('click',function(e){
            if($('#formElem').data('errors')){
                alert('Please correct the errors in the Form');
                e.preventDefault();
            }
        });
    });
</script>