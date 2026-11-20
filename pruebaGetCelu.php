<!doctype html>
<?php
?>
<html>
<head>
<meta charset="utf-8">
<title>Documento sin título</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/css/intlTelInput.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>

</head>

<body>
    <div>
      <input id="phone" type="tel" name="phone"/>
<!--      <input id="phone" type="tel" name="phone" onClick="tomarTelefono(event)" value="+5491150600593"/> -->
    </div>
    <br>
    <div>
      <input id="phone1" type="tel" name="phone1"/>
    </div>

    <div>
      <input id="salidaTel" type="text" name="salidaTel"/>
    </div>
<!--      <input id="btnSubmit" type="submit" class="btn" value="Verify" /> -->

    <script>
        const telefonosID = document.querySelectorAll("input[type='tel']");
        telefonosID.forEach((cadaTelefono) => {
            console.log(cadaTelefono.name);
            var telefo = "#"+cadaTelefono.name;
            var telefo1 = '"'+cadaTelefono.name+'"';
            console.log(telefo+" - "+telefo1);

            var input = document.querySelector(telefo);
            var iti = window.intlTelInput(input, {
              separateDialCode: true,
              preferredCountries: ["es", "ar", "uy", "ad"],
              initialCountry: "auto",
              geoIpLookup: function(callback) {
              fetch('https://ipinfo.io/json')
              .then(response => response.json())
              .then(data => callback(data.country))
              .catch(error => console.error(error));
              },
              utilsScript: "https://cdn.jsdelivr.net/npm/intl-tel-input@17.0.3/build/js/utils.js",
            });
            // store the instance variable so we can access it in the console e.g. window.iti.getNumber()
            window.iti = iti;
    //        input.focus();
            document.querySelector(telefo).addEventListener("change", function(e) {
              e.preventDefault();
                const salidaTel = document.getElementById('salidaTel');
                const phoneNumber = iti.getNumber();
                salidaTel.value=phoneNumber;
                console.log(phoneNumber);
                const activeItem = iti.activeItem.dataset;
            });
        });
    </script>

</body>
</html>