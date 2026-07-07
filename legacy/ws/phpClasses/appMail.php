<?php

  require_once(__DIR__ .'/PHPMailerAutoload.php');
  require_once(__DIR__ .'/../vendor/autoload.php');
  require_once(__DIR__ .'/class.FusionJsonHtml.php');
  

  function envoyerMail($pDest, $pSujet, $pContenu, $pFiles = [], $pCci = false){
    global $pConfig;
    global $globalData;
    
    $spanUnsuscribe = "<span style=\"font-size:8pt;color:black; font-family:arial,sans-serif;\">Vous recevez ce mail car vous êtes inscrit à la liste de diffusion du site internet. Vous pouvez sortir de la liste de diffusion en cliquant sur le lien suivant : <a href=\"".$pConfig["site_url"]."/index.html?action=desabo&id=%id%\">Vous désabonner</a></span>";
      

    try
    {
      $mail = new PHPMailer;

      if($pConfig['mail_Debug'] == true )
      {
        $mail->SMTPDebug = 3; 
      }

      $mail->CharSet = 'UTF-8';

      if($pConfig['mail_SmtpAuth']){
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $pConfig['mail_SmtpHost'];
        $mail->Username = $pConfig['mail_SmtpUser'];
        $mail->Password = $pConfig['mail_SmtpPassword'];
        $mail->SMTPSecure = strtoupper($pConfig['mail_SmtpSecure']);
        $mail->Port = $pConfig['mail_SmtpPort'];
        $mail->SMTPOptions = array(
          'ssl' => array(
            'verify_peer' => $pConfig['mail_SmtpSslOptionVerifyPeer'],
            'verify_peer_name' => $pConfig['mail_SmtpSslOptionVerifyPeerName'],
            'allow_self_signed' => $pConfig['mail_SmtpSslOptionAllowSelfSigned']
          )
        );
      }

      $mail->setFrom($pConfig['mail_ReplyTo'], $pConfig['site_nom']);
      $mail->addReplyTo($pConfig['mail_ReplyTo']);
      $mail->isHTML(true);
      $mail->Subject = $pSujet;

      // ajouter pj
      foreach( $pFiles as $idx => $file ){
        $mail->addStringAttachment($file, $idx);
      }

      if($pConfig['mail_Test'] == true && isset($pConfig['utilisateur'])){
        if($pCci){
          $mail->addBCC($pConfig['utilisateur']['mail_uti']);
        }else{
          $mail->addAddress($pConfig['utilisateur']['mail_uti']);
        }
        $mail->Subject .= " [";
        
        if(is_array($pDest)){
          $mail->Subject .= $pDest[0];
          if( strpos($pContenu, "<unsuscribe/>") === false){
            $mail->Body = $pContenu . '<br>'.str_replace("%id%", md5(crypt(strtoupper($pDest[0]), '$5$rounds=6500$1Px98aW8TyS6mJ4u')), $spanUnsuscribe);
          }else{
            $mail->Body = str_replace("<unsuscribe/>", str_replace("%id%", md5(crypt(strtoupper($pDest[0]), '$5$rounds=6500$1Px98aW8TyS6mJ4u')), $spanUnsuscribe), $pContenu);
          }

        }else{
          $mail->Subject .= $pDest;
          $mail->Body    = str_replace("<unsuscribe/>", "", $pContenu);
        }

        $mail->Subject .= "]";

        // Envoyer mail de test
        $mail->AltBody = '';

        if(!$mail->send())
        {
          $globalData['errCode'] = "ERR_MAIL_01";
          $globalData['errLib'] = "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo;
          throw new Exception('ERREUR');
        }

      }else{
        if(is_array($pDest)){
          foreach($pDest as $email)
          {
            $mail->clearAllRecipients();
            if($pCci){
              $mail->addBCC($email);
            }else{
              $mail->addAddress($email);
            }
            if( strpos($pContenu, "<unsuscribe/>") === false){
              $mail->Body = $pContenu . '<br>'.str_replace("%id%", md5(crypt(strtoupper($email), '$5$rounds=6500$1Px98aW8TyS6mJ4u')), $spanUnsuscribe);
            }else{
              $mail->Body = str_replace("<unsuscribe/>", str_replace("%id%", md5(crypt(strtoupper($email), '$5$rounds=6500$1Px98aW8TyS6mJ4u')), $spanUnsuscribe), $pContenu);
            }

            $mail->AltBody = '';
            if(!$mail->send())
            {
              $globalData['errCode'] = "ERR_MAIL_01";
              $globalData['errLib'] = "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo;
              throw new Exception('ERREUR');
            }
          }
        }else{
          if($pCci){
            $mail->addBCC($pDest);
          }else{
            $mail->addAddress($pDest);
          }
          $mail->Body    = str_replace("<unsuscribe/>", "", $pContenu);
          $mail->AltBody = '';

          if(!$mail->send())
          {
            $globalData['errCode'] = "ERR_MAIL_01";
            $globalData['errLib'] = "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo;
            throw new Exception('ERREUR');
          }
        }
      }

    }
    catch(Exception $e)
    {
      $globalData['errCode'] = "ERR_MAIL_01";
      $globalData['errLib'] = "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo;
      throw new Exception('ERREUR');
    }

  }


