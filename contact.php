<?php
// Formulaire de contact Média Prestations — fonctionne sur l'hébergement OVH (fonction mail()).
// >>> Adresse qui reçoit les demandes de devis :
const DESTINATAIRE = 'f.bergeron@mediaprestations.com';
// >>> Expéditeur technique : doit être une adresse du domaine hébergé chez OVH.
const EXPEDITEUR = 'site@mediaprestations.fr';

function retour($ok) {
  header('Location: /?envoye=' . ($ok ? '1' : '0') . '#contact', true, 303);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') retour(false);

// Piège à robots : ce champ caché doit rester vide.
if (!empty($_POST['site_web'])) retour(true);

$nom     = trim(strip_tags($_POST['nom'] ?? ''));
$email   = trim($_POST['email'] ?? '');
$type    = trim(strip_tags($_POST['type'] ?? ''));
$message = trim(strip_tags($_POST['message'] ?? ''));

if ($nom === '' || $message === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) retour(false);
if (mb_strlen($message) > 5000 || mb_strlen($nom) > 200) retour(false);

// Empêche l'injection d'en-têtes.
$nom   = str_replace(["\r", "\n"], ' ', $nom);
$email = str_replace(["\r", "\n"], '', $email);

$sujet = '=?UTF-8?B?' . base64_encode('Demande de devis — ' . $type . ' — ' . $nom) . '?=';
$corps = "Nouvelle demande depuis mediaprestations.fr\n\n"
       . "Nom : $nom\nE-mail : $email\nType de projet : $type\n\n"
       . "Message :\n$message\n";

$entetes = "From: Site Média Prestations <" . EXPEDITEUR . ">\r\n"
         . "Reply-To: $email\r\n"
         . "MIME-Version: 1.0\r\n"
         . "Content-Type: text/plain; charset=UTF-8\r\n"
         . "Content-Transfer-Encoding: 8bit\r\n";

retour(mail(DESTINATAIRE, $sujet, $corps, $entetes));
