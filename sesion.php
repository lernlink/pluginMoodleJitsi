<html itemscope itemtype="http://schema.org/Product" prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/html">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="content-type" content="text/html;charset=utf-8">
    </head>
    <body>
        <?php
          $nombre=$_POST['nom'];
          $sesion=$_POST['ses'];
          $avatar=$_POST['avatar']
         ?>
        <script src="https://meet.jit.si/external_api.js"></script>
        <script>
            var domain = "meet.jit.si";
            var options = {
                roomName: "<?php echo $sesion; ?>",
            }
            var api = new JitsiMeetExternalAPI(domain, options);
            api.executeCommand('displayName', '<?php echo $nombre; ?>');
            api.executeCommand('toggleVideo');
            api.executeCommand('avatarUrl', '<?php echo $avatar; ?>');


        </script>
    </body>
</html>
