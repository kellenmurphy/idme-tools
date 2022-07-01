<!doctype html>
<html>

  <head>
    <title>Wrapper for Shibboleth IdP AACLI Utility</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <meta property="og:title" content="Wrapper for Shibboleth IdP AACI Utility">
    <meta property="og:image" content="https://idmengineering.com/tools/aacli-final.png">
    <meta property="og:image:alt" content="A quick web utility for generating IDP-initiated SSO URLs for Shibboleth.">
    <meta property="og:url" content="https://idmengineering.com/tools/aacli-wrapper.php">
    <meta property="og:type" content="website">
    <meta property="og:updated_time" content="2020-03-31T19:49:01-05:00" /> 
    <meta property="og:description" content="A quick web utility for utilizing the Shibboleth IDP's AACLI Utility">
    <meta property="fb:app_id" content="1304281163097866">
    <style>
      small {font-size: 14px; padding-left: 0px; color: indigo;}
      img#idme-logo {position: fixed; bottom: 10px; right: 10px; width: 150px; height: auto;}
      .hidden {display: none;}
      button {width: 150px; padding: 10px; margin: 5px;}
      .centered {text-align: center;}
      #buildUrl div {display: inline;}
      code {color: indigo;}
      #wrapper {width: 100%; height: 300px; margin-top: 20px;}
      xmp {background-color: #eee;}
      #id {font-family: mono, color: indigo}
      .inline {display: inline;}
      select {height: 30px;  width: 100px;}
    </style>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="./js/aacli.js"></script>
  </head>

  <body>

    <div class="container col-md-8">

      <div class="intro">
        <h3 style="margin-top: 20px;">Wrapper for Shibboleth IdP AACLI Utility</h3>
        <div>
        
          This page is intended to facilitate the use of the AACLI script that's bundled with the <a href="https://www.shibboleth.net/products/identity-provider//">Shibboleth Identity Provider</a> (IdP).

          <p></p>
          
          AACLI stands for the <b>A</b>ttribute <b>A</b>uthority <b>C</b>ommand <b>L</b>ine <b>I</b>nterface and it's purpose is to assess, for a given <em>principal</em> (i.e. user name), what attributes would be released to a given <em>relying party</em> (or service provider). This is incredibly useful for testing an integration as you are building the configuration within the IdP's config without actually attempting a login (perhaps because a service provider has not deployed their configuration yet).

        </div>

      <hr />

      <h5>Security Considerations</h5>

      <small><b>Note</b>: this script performs no server-side communication with your server, <b>nothing</b> is stored on our server, and <b>all</b> communication with the targeted IdP occurs on the client-side (between your browser and the IdP).</small>

      <p></p>

      In order to leverage calls to the AACLI script, you must <a href="#">white-list</a> your access to the Administrative functions of Shibboleth. To do this you can temporarily allow access for your local IP address by editing <code>{idp.home}/conf/access-control.xml</code>. The code-snipped below demonstrates how to whitelist the IP address <span id="ip">aaa.bbb.ccc.ddd</span>. You should replace this with your <a href="https://ifconfig.co">public IP address</a>.

      <p></p>

      <xmp>
        <!-- Include the following to access-control.xml -->
        <entry key="AccessByIPAddress">
            <bean id="AccessByIPAddress" parent="shibboleth.IPRangeAccessControl" 
                  p:allowedRanges="#{ {'127.0.0.1/32', '::1/128', 'aaa.bbb.ccc.ddd/32'} }" />
        </entry>
      </xmp>

      <hr />

      <h5>AACLI Parameters</h5>

      <form id="idpform">

        <div id="idp">
          <p></p>
          <label for="idp_hostname">IdP Hostname: </label>
          <select id="scheme" class="inline">
            <option>https://</option>
            <option>http://</option>
          </select>
          <input type="text" id="idp" length="255" style="width: 75%; display: inline-block;" placeholder="idp.example.org" class="required">
        </div>

        <label for="requester">Relying Party / Service Provider EntityID: </label>
        <input type="text" id="requester" length="255" style="width: 75%; display: inline-block;" placeholder="https://sp.example.org/shibboleth" class="required">

        <label for="principal">Principal / Username: </label>
        <input type="text" id="principal" length="255" style="width: 25%; display: inline-block;" placeholder="john.doe" class="required">

        <p></p>

        <h5 class="centered">AACLI URL:</h5>
        <div id="buildUrl" class="centered"></div>

        <p></p>
      </form>

      <div class="centered">
        <button id="submit" disabled>Check Attributes</button>
        <button id="copy">Copy URL</button>
        <button id="cancel">Clear Form</button>
      </div>

    </div>

    <a href="https://idmengineering.com"><img id="idme-logo" src="https://idmengineering.com/wp-content/themes/bcse/img/logo.png"></a>

    <iframe id="wrapper"></iframe>

  </body>

</html> 
