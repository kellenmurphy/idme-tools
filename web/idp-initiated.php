<?php

$idp_data = array(
  array(
  'hostname' => "https://samltest.id",
  'entity_id' => "https://samltest.id/saml/idp"
  )
)

?>

<html>

  <head>
    <title>IdP-Initiated Test Script for Shibboleth IdP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/idme.css">
    <meta property="og:title" content="IdP-Initiated Test Script for Shibboleth IdP">
    <meta property="og:image" content="https://idmengineering.com/tools/img/idpinit-final.png">
    <meta property="og:image:alt" content="A quick web utility for generating IDP-initiated SSO URLs for Shibboleth.">
    <meta property="og:url" content="https://idmengineering.com/tools/idp-initiated.php">
    <meta property="og:type" content="website">
    <meta property="og:updated_time" content="2022-07-01T19:58:00-05:00" /> 
    <meta property="og:description" content="A quick web utility for generating IDP-initiated SSO URLs for Shibboleth.">
    <meta property="fb:app_id" content="1304281163097866">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  </head>

  <body>

    <div class="container col-md-8">

      <div class="intro">
        <h3 style="margin-top: 20px;">IdP-Initiated Test Script for Shibboleth IdP</h3>
        <div>This tool can be used to build IdP-initiated single sign-on URLs compatible with <a href="https://www.shibboleth.net/products/identity-provider/">Shibboleth Identity Provider</a>.

          <p></p>

          While in most SSO cases, the process will be "kicked off" by the service sending a request to the IdP, the original SAML 1.0 and SAML 1.1 standards lacked the requisite specification for this flow, and as such support for initiation of the single sign on process was carried forward into the SAML 2.0 specification as "IDP-initiated", i.e. starting the SAML flow without an AuthnRequest.

          <p></p>

          This is also a useful tool for testing SAML integrations without requiring the Service Provider (SP) to have configured an integration on their end, as you can an create sample assertions for the SP integrators to see by kicking off an IdP-initiated SAML workflow, and capturing the SAMLResponse with a browser extension like <a href="https://addons.mozilla.org/en-US/firefox/addon/saml-tracer/">SAML-Tracer</a> for Firefox or <a href="https://chrome.google.com/webstore/detail/saml-devtools-extension/jndllhgbinhiiddokbeoeepbppdnhhio?hl=en-US">SAML DevTools</a> for Chrome.

          <p></p>

          For more details on IdP-initiated SSO with Shibboleth, see the <a href="https://wiki.shibboleth.net/confluence/display/IDP30/UnsolicitedSSOConfiguration">Shib Wiki entry on the Unsolicited endpoint.</a>

        </div>
      </div>

      <hr>
      <h6>Identity Provider</h6>

      <form name="idpform" id="idpform">

        <select id="idp_entity_id" name="idp_entity_id" style="width: 100%;">
          <?php foreach ($idp_data as $idp): ?>
            <option value="<?php echo($idp['hostname']);?>"><?php echo($idp['entity_id']);?></option>
          <?php endforeach; ?>
          <option value="manual">... specify manually.</option>
        </select>

        <div id="manual_idp" class="hidden">
          <p></p>
          <label for="manual_idp_hostname"><code>hostname</code></label>
          <input type="text" id="manual_idp_hostname" name="manual_idp_hostname" length="255" style="width: 100%;">
        </div>

        <hr>
        <h6>Service Provider</h6>

        <label for="providerId"><code>providerId</code> <small>Specifies the SP Entity ID to authenticate; required.</small></label>
        <input type="text" id="providerId" name="providerId" length="255" style="width: 100%;" class="required">

        <p></p>

        <label for="shire"><code>shire</code> <small>Specifies the Assertion Consumer Service (ACS) URL to use; optional</small></label>
        <input type="text" id="shire" name="shire" length="255" style="width: 100%;" class="optional">

        <p></p>

        <label for="target"><code>target</code> <small>Specifies the relayState, or where to be redirected after auth; optional.</small></label>
        <input type="text" id="target" name="target" length="255" style="width: 100%;" class="optional">

        <p></p>

        <label for="time"><code>time</code> <small>Specifies the timestamp for stale request detection; optional.</small></label>
        <br />
        <select id="time" name="time" style="width: 275px;">
          <option value="3600">+1 hour</option>
          <option value="300">+5 min</option>
          <option value="0" selected>now</option>
          <option value="-300">-5 min</option>
          <option value="-3600">-1 hour</option>
          <option value="manual">... or specify timestamp manually.</option>
        </select>

        <div id="manual_time" class="hidden" style="display: inline;">
          <input type="text" id="manual_time_input" name="manual_time" length="14">
        </div>

        <p>&nbsp;</p>
        <hr>
        <h4>IdP-Initiation URL:</h4>

        <div id="buildUrl" class="centered">
          <div id="hostname">foo</div><!--
          --><div id="initiator">/idp/profile/SAML2/Unsolicited/SSO</div><!--
          --><div id="provider_id_qp">?providerId=</div><!--
          --><div id="provider_id"></div><!--
          --><div id="shire_qp">&shire=</div><!--
          --><div id="shire_url"></div><!--
          --><div id="target_qp">&target=</div><!--
          --><div id="target_url"></div><!--
          --><div id="time_qp">&time=</div><!--
          --><div id="time_url"></div>
        </div>
        <p></p>
      </form>

      <div class="centered">
        <button id="submit" disabled>Login Now</button>
        <button id="copy">Copy URL</button>
        <button id="cancel">Clear Form</button>
      </div>

    </div>

    <a href="https://idmengineering.com"><img id="idme-logo" src="https://idmengineering.com/wp-content/themes/bcse/img/logo.png"></a>

    <script type="text/javascript">

      // initial setup stuff
      $.each($('#buildUrl').children(), function () {
        $(this).hide();
      });
      $('#hostname').show();
      $('#initiator').show();
      $(function() {
        $('#manual_idp').hide();
        $('#manual_time').hide();
        $('#idp_entity_id').change(function(){
          if($('#idp_entity_id').val() == 'manual') {
            $('#manual_idp').show();
          } else {
            $('#manual_idp').hide();
          }
        });
      });

      // hostname event and form params
      var hostname = $('#idp_entity_id').val();
      $('#hostname').text(hostname);
      $('#idp_entity_id').change( function() {
        if ($('#idp_entity_id').val() !== 'manual') {
          hostname = $('#idp_entity_id').val();
        }
        $('#hostname').text(hostname);
      });
      $('#manual_idp_hostname').keyup( function() {
        hostname = $('#manual_idp_hostname').val();
        $('#hostname').text(hostname);
      });

      // providerId event and form params
      var providerId = $('#providerId').val();
      if (providerId) {
        $('#provider_id_qp').show();
        $('#provider_id').show();
        $('#provider_id').text(providerId);
      } else {
        $('#submit').prop('disabled', 'true');
        $('#copy').prop('disabled', 'true');
        $('#provider_id_qp').hide();
        $('#provider_id').hide();
      }
      $('#providerId').keyup( function() {
        if ( $('#providerId').val() != '' ) {
          providerId = $('#providerId').val();
          $('#submit').removeAttr('disabled');
          $('#copy').removeAttr('disabled');
          $('#provider_id_qp').show();
          $('#provider_id').show();
          $('#provider_id').text(providerId);
        } else {
          $('#submit').prop('disabled', 'true');
          $('#copy').prop('disabled', 'true');
          $('#provider_id_qp').hide();
          $('#provider_id').hide();
        }
      });

      // shire event and form params
      var shire = $('#shire').val();
      if (shire) {
        $('#shire_qp').show();
        $('#shire_url').show();
        $('#shire_url').text(shire);
      }
      $('#shire').keyup( function() {
        if ( $('#shire').val() != '' ) {
          shire = $('#shire').val();
          $('#shire_qp').show();
          $('#shire_url').show();
          $('#shire_url').text(shire);
        } else {
          $('#shire_qp').hide();
          $('#shire_url').hide();
        }
      });

      // target event and form params
      var target = $('#target').val();
      if (target) {
        $('#target_qp').show();
        $('#target_url').show();
        $('#target_url').text(target);
      }
      $('#target').keyup( function() {
        if ( $('#target').val() != '' ) {
          target = $('#target').val();
          $('#target_qp').show();
          $('#target_url').show();
          $('#target_url').text(target);
        } else {
          $('#target_qp').hide();
          $('#target_url').hide();
        }
      });

      // time event and form params
      var time = Number($('#time').val());
      if (Number($('#time').val()) != '0') {
        $('#time_qp').show();
        $('#time_url').show();
        $('#time_url').text(time);
      }
      $('#time').change( function() {
        time = Number($('#time').val());
        var newTime = Number(time) + Number(Date.now());
        if ( Number($('#time').val()) != '0' ) {
          $('#time_qp').show();
          $('#time_url').show();
          $('#time_url').text(newTime);
          if ($('#time').val() == 'manual') {
            $('#manual_time').show();
            $('#time_qp').show();
            $('#time_url').show();
            time_init = Number(Date.now());
            $('#manual_time_input').val(time_init);
            $('#time_url').text(time_init);
            $('#manual_time > input').keyup(function() {
              time = Number($('#manual_time_input').val())
              $('#time_url').text(time);
            })
          }
          else {
            $('#manual_time').hide();
          }
        } else {
          $('#time_qp').hide();
          $('#time_url').hide();
          $('#manual_time').hide();
        }
      });

      // "clear form" button handler
      $('#cancel').on('click', function() {
        $('#idpform')[0].reset();
        $('#provider_id_qp').hide();
        $('#provider_id').hide();
        $('#shire_qp').hide();
        $('#shire_url').hide();
        $('#target_qp').hide();
        $('#target_url').hide();
        $('#time_qp').hide();
        $('#time_url').hide();
        $('#submit').prop('disabled', 'true');
        $('#copy').prop('disabled', 'true');
        $('#provider_id_qp').hide();
        $('#provider_id').hide();
      });

      // "copy url" button handler
      $('#copy').on('click', function (){
        var url = buildUrl();
        var temp = $("<input>");
        $("body").append(temp);
        temp.val(url).select();
        document.execCommand("copy");
        temp.remove();
      });

      // "login" button handler
      $('#submit').on('click', function (){
        var url = buildUrl();
        window.open(url, '_blank');
      });

      // function to build URL
      function buildUrl() {
        var url = '';
        $.each($('#buildUrl').children(), function () {
          if ($(this).is(':visible')) {
            url += $(this).text();
          }
        });
        return url;
      }

    </script>

  </body>

</html>
