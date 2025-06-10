<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
  public string $email;
  public string $nombre;
  public string $token;

  // Constructor de la clase Email
  public function __construct($email, $nombre, $token)
  {
    $this->email = $email;
    $this->nombre = $nombre;
    $this->token = $token;
  }

  // Método para enviar el email de confirmación
  public function enviarConfirmacion(): void
  {
    // Crear una instancia de PHPMailer
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->Host = $_ENV['EMAIL_HOST'];
    $mail->SMTPAuth = true;
    $mail->Port = $_ENV['EMAIL_PORT'];
    $mail->Username = $_ENV['EMAIL_USER'];
    $mail->Password = $_ENV['EMAIL_PASS'];

    // Configurar el remitente y destinatario
    $mail->setFrom('cuentas@todolist.com');
    $mail->addAddress($this->email, $this->nombre);
    $mail->Subject = 'Confirma tu cuenta';

    // Configurar el contenido del email
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';

    // Contenido del email
    $contenido = "<html>";
    $contenido .= '<p><strong>Hola ' . $this->nombre . '</strong>, Has creado tu cuenta en Todo List, solo debes confirmarla presionando el siguiente enlace.</p>';
    $contenido .= '<p>Presiona aquí: <a href="' . $_ENV['HOST'] . '/confirmar-cuenta?token=' . $this->token . '">Confirmar Cuenta</a></p>';
    $contenido .= '<p>Si no solicitaste esta cuenta, puedes ignorar este mensaje.</p>';
    $contenido .= '</html>';
    $mail->Body = $contenido;

    // Enviar el email
    $mail->send();
  }
}
